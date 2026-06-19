<?php

namespace Database\Seeders;

use App\Models\EmissionPoint;
use App\Models\Measurement;
use App\Models\Substance;
use Illuminate\Database\Seeder;

class MeasurementSeeder extends Seeder
{
    public function run(): void
    {
        $measurements = [
            ['emission_point_code' => 'EP-001', 'substance_code' => 'SO2', 'value' => 420.2500, 'measured_at' => '2026-06-19 08:00:00'],
            ['emission_point_code' => 'EP-001', 'substance_code' => 'NOX', 'value' => 190.1000, 'measured_at' => '2026-06-19 08:15:00'],
            ['emission_point_code' => 'EP-002', 'substance_code' => 'PM10', 'value' => 61.3000, 'measured_at' => '2026-06-19 08:30:00'],
        ];

        foreach ($measurements as $measurementData) {
            $emissionPoint = EmissionPoint::where('code', $measurementData['emission_point_code'])->firstOrFail();
            $substance = Substance::where('code', $measurementData['substance_code'])->firstOrFail();

            Measurement::updateOrCreate(
                [
                    'emission_point_id' => $emissionPoint->id,
                    'substance_id' => $substance->id,
                    'measured_at' => $measurementData['measured_at'],
                ],
                [
                    'value' => $measurementData['value'],
                ]
            );
        }
    }
}