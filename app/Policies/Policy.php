<?php

namespace App\Policies;

use App\Models\User;

class Policy
{
    protected string $permission;

    /**
     * Determine whether the user can view any models.
     */
    public function read(User $user): bool
    {
        return $user->role->hasPermission("read_{$this->permission}");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role->hasPermission("create_{$this->permission}");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->role->hasPermission("update_{$this->permission}");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->role->hasPermission("delete_{$this->permission}");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return $user->role->hasPermission("restore_{$this->permission}");
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): bool
    {
        return $user->role->hasPermission("force_delete_{$this->permission}");
    }
}
