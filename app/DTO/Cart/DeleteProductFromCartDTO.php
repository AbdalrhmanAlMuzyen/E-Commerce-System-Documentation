<?php

namespace App\DTO\Cart;

class DeleteProductFromCartDTO{
    public int $cart_item_id;

    public function __construct(int $cart_item_id)
    {
        $this->cart_item_id=$cart_item_id;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("cart_item_id"));
    }
}