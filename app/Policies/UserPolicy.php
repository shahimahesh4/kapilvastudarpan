<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     * Admin CANNOT edit super admin; only super admin can edit super admin.
     */
    public function update(User $user, User $model): bool
    {
        if (! $user->isAdmin()) {
            return false;
        }

        // Admin cannot edit super admin
        if ($model->isSuperAdmin() && ! $user->isSuperAdmin()) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     * Admin CANNOT delete super admin; cannot delete oneself.
     */
    public function delete(User $user, User $model): bool
    {
        if (! $user->isAdmin()) {
            return false;
        }

        // Cannot delete oneself
        if ($user->id === $model->id) {
            return false;
        }

        // Admin cannot delete super admin
        if ($model->isSuperAdmin() && ! $user->isSuperAdmin()) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can delete any models in bulk.
     */
    public function deleteAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->isSuperAdmin();
    }
}
