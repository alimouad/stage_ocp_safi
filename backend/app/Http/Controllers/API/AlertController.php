<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * Get recent alerts with measurement context.
     */
    public function index()
    {
        $alerts = Alert::with([
            'measurement:id,emission_point_id,substance_id,value,measured_at',
            'measurement.emissionPoint:id,code,name,factory_zone',
            'measurement.substance:id,code,name,unit',
            'measurement.substance.threshold:id,substance_id,max_value',
        ])
            ->latest()
            ->limit(100)
            ->get();

        return response()->json($alerts, 200);
    }

    /**
     * Update alert workflow status.
     */
    public function updateStatus(Request $request, Alert $alert)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:PENDING,ONGOING,RESOLVED',
        ]);

        $alert->update($validated);

        return response()->json([
            'message' => 'Alert status updated successfully.',
            'data' => $alert->fresh(),
        ], 200);
    }
}
