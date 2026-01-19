<?php

namespace App\Http\Requests\ForgetPassword;

use Illuminate\Foundation\Http\FormRequest;

class ForegetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "email"=>"required|string"
        ];
    }
}
