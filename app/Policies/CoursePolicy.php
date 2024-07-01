<?php

namespace App\Policies;

use App\Models\Admin as User;
use App\Models\Course;

class CoursePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any course');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Course $course): bool
    {

        if ($user->can('view own course')) {
            return $course->institution->state?->admin?->id === $user->id;
        }

        return $user->can('view course');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create course');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Course $course): bool
    {
        if ($user->can('update course')) {
            return true;
        }
        if ($user->can('update own course')) {
            return $course->institution->state?->admin?->id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Course $course): bool
    {

        if ($user->can('delete course')) {
            return true;
        }

        if ($user->can('delete own course')) {
            return $course->institution->state?->admin?->id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Course $course): bool
    {

        if ($user->can('restore own institution')) {
            return $course->institution->state?->admin?->id === $user->id;
        }

        return $user->can('restore course');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Course $course): bool
    {
        if ($user->can('force-delete own institution')) {
            return $course->institution->state?->admin?->id === $user->id;
        }

        return $user->can('force-delete course');
    }
}
