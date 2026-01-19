<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function dashboard(User $user)
    {
        return $user->hasRole("admin");
    }

    public function checkOut(User $user)
    {
        return $user->hasRole("user");
    }
}
