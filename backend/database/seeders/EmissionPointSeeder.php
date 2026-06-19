<?php

namespace Database\Seeders;

use App\Models\EmissionPoint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmissionPointSeeder extends Seeder
{
    public function run(): void
    {
        $points = [
            [
                'code' => 'EP-001',
                'name' => 'Main Stack North',
                'description' => 'Primary emission stack near the north line',
                'location' => 'SRID=4326;POINT(-7.6110 32.2510)',
                'factory_zone' => 'ZONE_A_SULFURIC',
            ],
            [
                'code' => 'EP-002',
                'name' => 'Crusher Unit',
                'description' => 'Dust monitoring point for crusher unit',
                'location' => 'SRID=4326;POINT(-7.6095 32.2522)',
                'factory_zone' => 'ZONE_B_CRUSHER',
            ],
        ];

        foreach ($points as $point) {
            EmissionPoint::updateOrCreate(
                ['code' => $point['code']],
                [
                    'name' => $point['name'],
                    'description' => $point['description'],
                    'factory_zone' => $point['factory_zone'],
                ]
            );

            DB::statement(
                'update emission_points set location = ST_GeomFromEWKT(?) where code = ?',
                [$point['location'], $point['code']]
            );
        }
    }
}