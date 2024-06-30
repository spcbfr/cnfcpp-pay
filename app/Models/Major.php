<?php

namespace App\Models;

use App\InstitutionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Major extends Model
{
    public function institutions(): BelongsToMany
    {
        return $this->belongsToMany(Institution::class);
    }

    protected $casts = [
        'type' => InstitutionType::class,
    ];

    use HasFactory;
}
