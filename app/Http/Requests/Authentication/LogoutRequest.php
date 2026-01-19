<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;
use Jenssegers\Agent\Agent;

class LogoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes("refrehToken","required|string",function(){
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