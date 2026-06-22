<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use App\Models\Alert;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    /**
     * Get recent measurements for the telemetry stream.
     */
    public function index()
    {
        $measurements = Measurement::with([
            'emissionPoint:id,code,name',
            'substance:id,code,name,unit',
            'substance.threshold:id,substance_id,max_value',
            'alert:id,measurement_id,status',
        ])
            ->latest('measured_at')
            ->limit(50)
            ->get();

        return response()->json($measurements, 200);
    }

    /**
     * Store a new measurement (Real-time IoT stream simulation)
     */
    public function store(Request $request)
    {
        $request->validate([
            'emission_point_id' => 'required|exists:emission_points,id',
            'substance_id' => 'required|exists:substances,id',
            'value' => 'required|numeric',
            'measured_at' => 'required|date'
        ]);

        // 1. Inserer la mesure f la base de données
        $measurement = Measurement::create($request->all());

        // 2. Check automatic dyal threshold (Helper f Model)
        if ($measurement->checkThreshold()) {
            $substanceName = $measurement->substance->name;
            $maxAllowed = $measurement->substance->threshold->max_value;
            
            // 3. Création automatique d'une Alerte critique
            Alert::create([
                'measurement_id' => $measurement->id,
                'message' => "Critical Dépassement: {$substanceName} reached {$measurement->value} mg/m³ (Max allowed: {$maxAllowed}).",
                'status' => 'PENDING' // AlertStatus Enum
            ]);
        }

        return response()->json(['message' => 'Measurement processed successfully', 'data' => $measurement], 201);
    }

    /**
     * Module: Importation CSV (Centralisation manuelle)
     */
    public function importCSV(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt']);
        $file = $request->file('file');
        
        $handle = fopen($file->getRealPath(), 'r');
        fgetcsv($handle); // Passer la ligne de header (entête)

        $count = 0;
        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Supposons le format CSV: emission_point_id, substance_id, value, measured_at
            Measurement::create([
                'emission_point_id' => $row[0],
                'substance_id' => $row[1],
                'value' => $row[2],
                'measured_at' => $row[3],
            ]);
            $count++;
        }
        fclose($handle);

        return response()->json(['message' => "Successfully imported {$count} rows from CSV."], 200);
    }
}
