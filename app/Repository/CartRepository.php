<?php

namespace App\Repository;

use App\DTO\Cart\AddProductToCartDTO;
use App\DTO\Cart\UpdateProductFromCartDTO;

class CartRepository{

    public function getCart($user)
    {
        return $user->cart()->first();
    }

    public function createCart($user)
    {
        return $user->cart()->create();
    }
    public function x($cart,$product_variant_id)
    {
        return $cart->cartItems()->where("product_variant_id",$product_variant_id)->first();
    }
    public function addProductToCart($cart,$user_id,AddProductToCartDTO $dto)
    {  
        return $cart->cartItems()->create([
            "product_variant_id"=>$dto->product_variant_id,
            "quantity"=>$dto->quantity
        ]);
    }
    
    public function deleteCartItems($cart)
    {
        return $cart->cartItems()->delete();
    }

    public function getCartItems($cart)
    {
        return $cart->cartItems;
    }

    public function findCartItemByID($cart,$cart_item_id)
    {
        return $cart->cartItems()->find($cart_item_id);
    }

    public function deleteProductFromCart($cartItem)
    {
        return $cartItem->delete();
    }   

    public function updateProductFromCart($cartItem,$quantity)
    {
        return $cartItem->update([
            "quantity"=>$quantity
        ]);
    }

    public function getCartItemByVariant($cart,$product_variant_id)
    {
        return $cart->cartItems()->where("product_variant_id",$product_variant_id)->first();
    }

    public function getMyCartItems($cart)
    {
        return $cart->cartItems()->with("productVariant.product")->get();
    }
    
}