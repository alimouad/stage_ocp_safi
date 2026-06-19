<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Statistic;

class StatisticController extends Controller
{
    public function getDashboardStats()
    {
        // Récupérer la toute dernière ligne m-calculya
        $latestStats = Statistic::latest()->first();

        if (!$latestStats) {
            return response()->json([
                'total_measurements' => 0,
                'normal_measurements' => 0,
                'ongoing_alerts' => 0,
                'resolved_alerts' => 0,
                'alerts_by_substance' => [
                    'SO2' => 0,
                    'NOX' => 0,
                    'PM10' => 0
                ]
            ], 200);
        }

        return response()->json($latestStats, 200);
    }
}