<?php

namespace App\Policies;

use App\Models\Admin as User;
use App\Models\State;

class StatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any state');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, State $state): bool
    {
        return $user->can('view state');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create state');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, State $state): bool
    {
        return $user->can('update state');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, State $state): bool
    {
        return $user->can('delete state');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, State $state): bool
    {
        return $user->can('restore state');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, State $state): bool
    {
        return $user->can('force-delete state');
    }
}
