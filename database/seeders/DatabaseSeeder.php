<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            GeoDataSeeder::class,
            FieldOfficerSeeder::class,
        ]);

        // Create admin user (idempotent)
        User::firstOrCreate(
            ['email' => 'admin@zaytoon.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('Admin@2024'),
                'is_admin' => true,
            ]
        );
    }
}
