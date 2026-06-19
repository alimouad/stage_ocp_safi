<?php

namespace Database\Seeders;

use App\Models\Substance;
use Illuminate\Database\Seeder;

class SubstanceSeeder extends Seeder
{
    public function run(): void
    {
        $substances = [
            ['code' => 'SO2', 'name' => 'Sulfur Dioxide', 'unit' => 'mg/m3'],
            ['code' => 'NOX', 'name' => 'Nitrogen Oxides', 'unit' => 'mg/m3'],
            ['code' => 'PM10', 'name' => 'Particulate Matter 10', 'unit' => 'µg/m3'],
        ];

        foreach ($substances as $substance) {
            Substance::updateOrCreate(
                ['code' => $substance['code']],
                $substance
            );
        }
    }
}