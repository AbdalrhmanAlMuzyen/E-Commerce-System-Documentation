<?php

namespace App\Http\Requests\ProductVariant;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "product_id"=>"required|integer|exists:products,id",
            "product_variants"=>"required|array|min:1",
            "product_variants.*.size_id"=>"required|integer|exists:sizes,id",
            "product_variants.*.color_id"=>"required|integer|exists:colors,id",
            "product_variants.*.image"=>"required|image|mimes:jpg,png",
            "product_variants.*.total_stock"=>"required|integer|"
        ];
    }
}
