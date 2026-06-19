<?php

namespace Database\Seeders;

use App\Models\Substance;
use App\Models\Threshold;
use Illuminate\Database\Seeder;

class ThresholdSeeder extends Seeder
{
    public function run(): void
    {
        $thresholds = [
            ['substance_code' => 'SO2', 'max_value' => 350.0000, 'description' => 'Hourly limit for sulfur dioxide'],
            ['substance_code' => 'NOX', 'max_value' => 400.0000, 'description' => 'Hourly limit for nitrogen oxides'],
            ['substance_code' => 'PM10', 'max_value' => 50.0000, 'description' => 'Daily limit for particulate matter'],
        ];

        foreach ($thresholds as $thresholdData) {
            $substance = Substance::where('code', $thresholdData['substance_code'])->firstOrFail();

            Threshold::updateOrCreate(
                ['substance_id' => $substance->id],
                [
                    'max_value' => $thresholdData['max_value'],
                    'description' => $thresholdData['description'],
                ]
            );
        }
    }
}