<?php
namespace App\DTO\ProductVariant;

class UpdateProductVariantDTO{
    public int $product_variant_id;
    public int $total_stock;

    public function __construct(int $product_variant_id,int $total_stock)
    {
        $this->product_variant_id=$product_variant_id;
        $this->total_stock=$total_stock;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("product_variant_id"),$request->input("total_stock"));
    }
}