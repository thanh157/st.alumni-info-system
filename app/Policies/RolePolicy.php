<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;

class RolePolicy
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
        return $user->hasPermission('role.index');
    }

    /**
     * determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('role.create');
    }

    /**
     * determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        return $user->hasPermission('role.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermission('role.delete');
    }
}
