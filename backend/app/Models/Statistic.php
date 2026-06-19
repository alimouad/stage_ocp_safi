<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;

    // Nom de la table f la base de données (si différent du pluriel standard)
    protected $table = 'statistics';

    protected $fillable = [
        'total_measurements',
        'normal_measurements',
        'ongoing_alerts',
        'resolved_alerts',
        'alerts_by_substance' // Le champ JSON dynamic
    ];

    /**
     * The attributes that should be cast.
     * Had l-partie hiya l-sa7: kat-goul l Laravel automatic y-converti l-JSON mn la base de données 
     * l un tableau PHP associatif, w l-3ks melli t-bghi t-stori data.
     */
    protected $casts = [
        'alerts_by_substance' => 'array',
        'total_measurements' => 'integer',
        'normal_measurements' => 'integer',
        'ongoing_alerts' => 'integer',
        'resolved_alerts' => 'integer',
    ];
}