<?php
namespace App\DTO\Cart;

class AddProductToCartDTO{
    public int $product_variant_id;
    public int $quantity;

    public function __construct(int $product_variant_id,int $quantity)
    {
        $this->product_variant_id=$product_variant_id;
        $this->quantity=$quantity;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("product_variant_id"),$request->input("quantity"));
    }
}