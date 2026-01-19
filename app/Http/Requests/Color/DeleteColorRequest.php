<?php

namespace App\Http\Requests\Color;

use Illuminate\Foundation\Http\FormRequest;

class DeleteColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "color_id"=>"required|integer|exists:colors,id"
        ];
    }
}
