<?php

namespace App\Http\Requests\ForgetPassword;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "email"=>"required|string",
            "code"=>"required|string",
            "newPassword"=>"required|string|min:6",
        ];
    }
}
