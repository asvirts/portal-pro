<?php

namespace App\Policies;

use App\Models\Portal;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PortalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Portal $portal): bool
    {
        return $user->id === $portal->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Portal $portal): bool
    {
        return $user->id === $portal->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Portal $portal): bool
    {
        return $user->id === $portal->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Portal $portal): bool
    {
        return $user->id === $portal->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Portal $portal): bool
    {
        return false;
    }
}
