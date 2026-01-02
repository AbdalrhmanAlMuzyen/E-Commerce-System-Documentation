<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name"=>"required|string",
            "description"=>"required|string",
            "price"=>"required|numeric|min:0.1"
        ];
    }
}
