<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Substance extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', // e.g., "SO2", "NOX"
        'name', // e.g., "Sulfur Dioxide"
        'unit'  // e.g., "mg/m3"
    ];

    /**
     * Relation: Une substance possède un seuil réglementaire unique
     */
    public function threshold(): HasOne
    {
        return $this->hasOne(Threshold::class);
    }

    /**
     * Relation: Une substance caractérise plusieurs mesures
     */
    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class);
    }
}