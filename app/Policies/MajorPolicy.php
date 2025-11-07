<?php

namespace App\Policies;

use App\Models\User;

class MajorPolicy
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
        return $user->hasPermission('training-program.index');
    }
}
