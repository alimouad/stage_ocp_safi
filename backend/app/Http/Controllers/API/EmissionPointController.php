<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EmissionPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmissionPointController extends Controller
{
    /**
     * Get all emission points with PostGIS Coordinates converted to text
     */
    public function index()
    {
        // ST_AsGeoJSON oula ST_X/ST_Y kat-khrej l-coordonnées spatiaux mn PostGIS binary format
        $points = EmissionPoint::select(
            'id', 'code', 'name', 'description', 'factory_zone',
            DB::raw('ST_Y(location::geometry) as latitude'),
            DB::raw('ST_X(location::geometry) as longitude')
        )->get();

        return response()->json($points, 200);
    }

    /**
     * Store a new emission point (HSE_ADMIN Only)
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:emission_points',
            'name' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'factory_zone' => 'required|string'
        ]);

        // Inserer un point Point(longitude latitude) f PostGIS spatial table
        $point = EmissionPoint::create([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'factory_zone' => $request->factory_zone,
            'location' => DB::raw("ST_GeomFromText('POINT({$request->longitude} {$request->latitude})', 4326)")
        ]);

        return response()->json(['message' => 'Emission point created successfully', 'data' => $point], 211);
    }
}