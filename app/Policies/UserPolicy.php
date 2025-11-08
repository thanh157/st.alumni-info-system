<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('user.index');
    }

    public function view(User $user): bool
    {
        return $user->hasPermission('user.show');
    }

    public function assignRole(User $user): bool
    {
        return $user->hasPermission('user.assign-role');
    }
}
