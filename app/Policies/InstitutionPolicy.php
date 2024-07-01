<?php

namespace App\Policies;

use App\Models\Admin as User;
use App\Models\Institution;

class InstitutionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any institution');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Institution $institution): bool
    {
        if ($user->can('view own institution')) {
            return $institution->state?->admin?->id === $user->id;
        }

        return $user->can('view institution');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create institution');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Institution $institution): bool
    {
        if ($user->can('update institution')) {
            return true;
        }
        if ($user->can('update own institution')) {
            return $institution->state?->admin?->id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Institution $institution): bool
    {
        if ($user->can('delete own institution')) {
            return $institution->state?->admin?->id === $user->id;
        }

        return $user->can('delete institution');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Institution $institution): bool
    {
        if ($user->can('restore own institution')) {
            return $institution->state?->admin?->id === $user->id;
        }

        return $user->can('restore institution');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Institution $institution): bool
    {

        if ($user->can('force-delete own institution')) {
            return $institution->state?->admin?->id === $user->id;
        }

        return $user->can('force-delete institution');
    }
}
