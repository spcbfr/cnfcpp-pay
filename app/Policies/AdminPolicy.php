<?php

namespace App\Policies;

use App\Models\Admin;
use Illuminate\Auth\Access\Response;

class AdminPolicy
{
    public function viewAny(Admin $user):bool
    {
        return $user->isSuper();
    }
}
