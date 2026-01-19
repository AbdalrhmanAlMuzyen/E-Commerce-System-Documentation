<?php

namespace App\DTO\Cart;

class UpdateProductFromCartDTO{
    public int $cart_item_id;
    public int $quantity;

    public function __construct(int $cart_item_id,int $quantity)
    {
        $this->cart_item_id=$cart_item_id;
        $this->quantity=$quantity;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("cart_item_id"),$request->input("quantity"));
    }
}