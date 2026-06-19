<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Threshold extends Model
{
    use HasFactory;

    protected $fillable = [
        'substance_id',
        'max_value',
        'description'
    ];

    public function substance(): BelongsTo
    {
        return $this->belongsTo(Substance::class);
    }
}