<?php
namespace App\DTO\ProductVariant;

class CreateProductVariantDTO{
    public int $product_id;
    public array $product_variants;

    public function __construct(int $product_id,array $product_variants)
    {
        $this->product_id=$product_id;
        $this->product_variants=$product_variants;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("product_id"),$request->input("product_variants"));
    }
}