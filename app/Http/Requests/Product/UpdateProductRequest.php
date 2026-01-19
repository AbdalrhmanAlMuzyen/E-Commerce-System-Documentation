<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "product_id"=>"required|integer|exists:products,id",
            "name"=>"nullable|string",
            "description"=>"nullable|string",
            "price"=>"nullable|numeric|min:0.1"
        ];
    }
}
