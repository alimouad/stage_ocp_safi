<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmissionPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'location', // PostGIS Geometry Point format
        'factory_zone' // Men l-Enum (ZONE_A_SULFURIC, etc.)
    ];

    /**
     * Relation: Un point d'émission contient plusieurs mesures
     */
    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class);
    }
}   