<?php

namespace App\Http\Requests\Size;

use Illuminate\Foundation\Http\FormRequest;

class CreateSizeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "value"=>"required|integer|unique:sizes,value"
        ];
    }
}
