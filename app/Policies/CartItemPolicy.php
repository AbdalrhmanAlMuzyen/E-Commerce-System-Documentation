<?php

namespace App\Policies;

use App\Models\CartItem;
use App\Models\User;

class CartItemPolicy
{
    public function delete(User $user,CartItem $cartItem)
    {
        return $user->hasRole("user") && $user->cart->cartItems()->where("id",$cartItem->id)->exists();
    }

    public function update(User $user,CartItem $cartItem)
    {
        return $user->hasRole("user") && $user->cart->cartItems()->where("id",$cartItem->id)->exists();
    }
}
