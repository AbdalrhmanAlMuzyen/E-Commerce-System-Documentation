<?php
namespace App\DTO\ProductVariant;

class DeleteProductVariantDTO{
    public int $product_variant_id;

    public function __construct(int $product_variant_id)
    {
        $this->product_variant_id=$product_variant_id;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("product_variant_id"));
    }
}