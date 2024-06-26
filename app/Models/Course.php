<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->as('enrollment')
            ->withPivot('is_paid', 'payment_id')->withTimestamps();
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);

    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);

    }
}
