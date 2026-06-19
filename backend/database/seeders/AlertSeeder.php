<?php

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\Measurement;
use Illuminate\Database\Seeder;

class AlertSeeder extends Seeder
{
    public function run(): void
    {
        $measurements = Measurement::with('substance.threshold')->get();

        foreach ($measurements as $measurement) {
            $threshold = $measurement->substance?->threshold;

            if (! $threshold || $measurement->value <= $threshold->max_value) {
                continue;
            }

            Alert::updateOrCreate(
                ['measurement_id' => $measurement->id],
                [
                    'message' => sprintf(
                        '%s value %.4f exceeds threshold %.4f',
                        $measurement->substance->code,
                        $measurement->value,
                        $threshold->max_value
                    ),
                    'status' => 'PENDING',
                ]
            );
        }
    }
}