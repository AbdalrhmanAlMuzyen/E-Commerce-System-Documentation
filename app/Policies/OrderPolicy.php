<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{ 
    public function view(User $user): bool
    {
        return $user->hasRole("user");
    }

    public function cancel(User $user,Order $order)
    {
        return $user->hasRole("user") && $user->orders()->find($order->id);
    }

    public function get(User $user)
    {
        return $user->hasRole("admin");
    }

    public function update(User $user)
    {
        return $user->hasRole("admin");
    }
}
