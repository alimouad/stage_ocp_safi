<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Measurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'emission_point_id',
        'substance_id',
        'value',
        'measured_at'
    ];

    protected $casts = [
        'measured_at' => 'datetime'
    ];

    public function emissionPoint(): BelongsTo
    {
        return $this->belongsTo(EmissionPoint::class);
    }

    public function substance(): BelongsTo
    {
        return $this->belongsTo(Substance::class);
    }

    /**
     * Relation: Une mesure peut déclencher au maximum une seule alerte
     */
    public function alert(): HasOne
    {
        return $this->hasOne(Alert::class);
    }

    /**
     * Helper logic: Vérifier si la valeur dépasse le seuil de la substance
     */
    public function checkThreshold(): bool
    {
        $threshold = $this->substance->threshold;
        if ($threshold && $this->value > $threshold->max_value) {
            return true;
        }
        return false;
    }
}