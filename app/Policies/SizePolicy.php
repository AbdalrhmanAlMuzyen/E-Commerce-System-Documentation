<?php

namespace App\Policies;

use App\Models\User;

class SizePolicy
{
    public function view(User $user): bool
    {
        return $user->hasRole("admin");
    }

    public function create(User $user): bool
    {
        return $user->hasRole("admin");
    }

    public function delete(User $user): bool
    {
        return $user->hasRole("admin");
    }
}
