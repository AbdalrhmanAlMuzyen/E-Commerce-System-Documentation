<?php

namespace App\Http\Requests\Size;

use Illuminate\Foundation\Http\FormRequest;

class DeleteSizeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "size_id"=>"required|integer|exists:sizes,id"
        ];
    }
}
