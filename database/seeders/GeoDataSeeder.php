<?php

namespace Database\Seeders;

use App\Imports\GeoDataImport;
use App\Models\GeoData;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class GeoDataSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/geo/Union_list.xlsx');

        if (! file_exists($path)) {
            $this->command->warn(
                "⚠  File not found: {$path}\n" .
                "   Please place Union_list.xlsx inside storage/app/geo/ and re-run the seeder."
            );
            return;
        }

        // Wipe existing geo data before re-seeding to avoid duplicates
        GeoData::truncate();

        $this->command->info('Importing geo data from Union_list.xlsx…');
        Excel::import(new GeoDataImport(), $path);
        $this->command->info('✓ Geo data imported: ' . GeoData::count() . ' rows.');
    }
}
