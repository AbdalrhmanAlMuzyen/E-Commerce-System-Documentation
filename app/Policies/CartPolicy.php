<?php

namespace App\Policies;

use App\Models\User;

class CartPolicy
{
    public function view(User $user)
    {
        return $user->hasRole("user");
    }

    public function create(User $user)
    {
        return $user->hasRole("user");
    }
}