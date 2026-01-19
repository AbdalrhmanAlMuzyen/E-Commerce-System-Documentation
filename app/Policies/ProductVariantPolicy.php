<?php

namespace App\Policies;

use App\Models\ProductVariant;
use App\Models\User;

class ProductVariantPolicy
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
