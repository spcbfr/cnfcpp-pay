<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;

class Admin extends User implements FilamentUser
{
    use HasFactory;

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return ! $this->is_super;
        }
        if ($panel->getId() === 'superAdmin') {
            return $this->is_super;
        }
    }

    public function states()
    {
        return $this->hasMany(State::class);
    }

    protected $casts = [
        'password' => 'hashed',
    ];
}
