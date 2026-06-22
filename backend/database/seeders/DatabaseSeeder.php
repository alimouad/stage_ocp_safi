<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Substance;
use App\Models\Threshold;
use App\Models\EmissionPoint;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // 1. Seeders dyal les Substances (Parameters)
        $so2 = Substance::updateOrCreate(['code' => 'SO2'], ['name' => 'Sulfur Dioxide', 'unit' => 'mg/m3']);
        $nox = Substance::updateOrCreate(['code' => 'NOX'], ['name' => 'Nitrogen Oxides', 'unit' => 'mg/m3']);
        $pm10 = Substance::updateOrCreate(['code' => 'PM10'], ['name' => 'Particulate Matter', 'unit' => 'mg/m3']);

        // 2. Seeders dyal les Seuils (Thresholds)
        Threshold::updateOrCreate(['substance_id' => $so2->id], ['max_value' => 75.0, 'description' => 'OMS Max Alert Limit']);
        Threshold::updateOrCreate(['substance_id' => $nox->id], ['max_value' => 40.0, 'description' => 'Moroccan Standard Limit']);

        // 3. Seeders dyal les Checkpoints (Emission Points f Safi) b PostGIS Coordinates
        DB::statement("CREATE EXTENSION IF NOT EXISTS postgis;"); // Safety double check
        
        EmissionPoint::updateOrCreate(
            ['code' => 'CHEM_01'],
            [
                'name' => 'Cheminée Principale - Zone A',
                'factory_zone' => 'ZONE_A_SULFURIC',
                'location' => DB::raw("ST_GeomFromText('POINT(-9.2412 32.2214)', 4326)")
            ]
        );

        EmissionPoint::updateOrCreate(
            ['code' => 'CHEM_02'],
            [
                'name' => 'Unité Phosphorique - Nord',
                'factory_zone' => 'ZONE_B_PHOSPHORIC',
                'location' => DB::raw("ST_GeomFromText('POINT(-9.2445 32.2235)', 4326)")
            ]
        );
        $this->call([
            UserSeeder::class,
        ]);
    }
}
