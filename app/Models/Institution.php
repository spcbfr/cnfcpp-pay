<?php

namespace App\Models;

use App\InstitutionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    protected $casts = [
        'type' => InstitutionType::class,
    ];
}
