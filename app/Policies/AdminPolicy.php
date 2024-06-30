<?php

namespace App\Policies;

use App\Models\Admin;

class AdminPolicy
{
    public function viewAny(Admin $user): bool
    {
        return $user->isSuper();
    }
}
