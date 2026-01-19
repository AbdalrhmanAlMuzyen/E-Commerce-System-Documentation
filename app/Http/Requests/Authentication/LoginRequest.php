<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;
use Jenssegers\Agent\Agent;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "email"=>"required|string",
            "password"=>"required|string",
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes("device_id","required|string",function(){
            $agent=new Agent();
            if($agent->isMobile())
            {
                return true;
            }
            else{
                return false;
            }
        });
    }  
}