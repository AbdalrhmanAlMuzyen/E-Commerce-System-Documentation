<?php

namespace App\Http\Requests\Color;

use Illuminate\Foundation\Http\FormRequest;

class CreateColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name"=>"required|string|unique:colors,name"
        ];
    }
}
