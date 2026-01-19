<?php
namespace App\DTO\Product;

class DeleteProductDTO{
    public int $product_id;

    public function __construct(int $product_id)
    {
        $this->product_id=$product_id;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("product_id"));
    }
}