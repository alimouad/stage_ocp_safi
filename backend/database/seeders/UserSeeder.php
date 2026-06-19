<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Mouad OCP',
                'email' => 'mouad@ocp.ma',
                'password' => Hash::make('mouad1411'),
                'role' => 'HSE_ADMIN',
            ],
            [
                'name' => 'Operator User',
                'email' => 'operator@ocp.ma',
                'password' => Hash::make('password'),
                'role' => 'OPERATOR',
            ],
            [
                'name' => 'Auditor User',
                'email' => 'auditor@ocp.ma',
                'password' => Hash::make('password'),
                'role' => 'AUDITOR',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => $user['password'],
                    'role' => $user['role'],
                ]
            );
        }
    }
}