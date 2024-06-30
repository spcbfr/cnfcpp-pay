<?php

namespace App\Policies;

use App\Models\Institution;
use App\Models\Admin as User;

class InstitutionPolicy
{
    public function before(User $user): bool|null
    {
        return $user->isSuper() ? true : null;
    }

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
    public function view(User $user, Institution $institution): bool
    {
        return $institution->state?->admin?->id === $user->id;
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
    public function update(User $user, Institution $institution): bool
    {
        return $institution->state?->admin?->id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Institution $institution): bool
    {
        return $institution->state?->admin?->id === $user->id;
    }

}
