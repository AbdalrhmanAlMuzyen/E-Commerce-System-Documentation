<?php

namespace App\Policies;

use App\Models\User;

class ProductPolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole("admin");
    }

    public function update(User $user): bool
    {
        return $user->hasRole("admin");
    }

    public function delete(User $user): bool
    {
        return $user->hasRole("admin");
    }
}
