<?php

namespace App\Policies;

use App\Models\User;

class GraduationPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewany(User $user): bool
    {
        return $user->hasPermission('graduation.index');
    }
}
