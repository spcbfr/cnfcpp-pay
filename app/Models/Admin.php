<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;

class Admin extends User implements FilamentUser
{
    use HasFactory;
    use Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }
    public function isSuper(): bool
    {
        return $this->is_super;
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];
}
