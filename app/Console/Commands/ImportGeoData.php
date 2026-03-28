<?php

namespace App\Console\Commands;

use App\Imports\GeoDataImport;
use App\Models\GeoData;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportGeoData extends Command
{
    protected $signature   = 'geo:import {--file=storage/app/geo/Union_list.xlsx : Path to the Excel file (relative to project root)}';
    protected $description = 'Import geo data (Division, District, Thana, Union) from an Excel file into the geo_data table';

    public function handle(): int
    {
        $relativePath = $this->option('file');
        $fullPath     = base_path($relativePath);

        if (! file_exists($fullPath)) {
            $this->error("File not found: {$fullPath}");
            $this->line('');
            $this->line('Place your Union_list.xlsx inside <fg=yellow>storage/app/geo/</> and run:');
            $this->line('  php artisan geo:import');
            return self::FAILURE;
        }

        $this->info("Reading: {$fullPath}");

        if ($this->confirm('This will <fg=red>truncate</> the existing geo_data table before importing. Continue?', true)) {
            GeoData::truncate();
            $this->info('Importing…');
            Excel::import(new GeoDataImport(), $fullPath);
            $count = GeoData::count();
            $this->info("✓ Done! {$count} rows imported into geo_data.");
        } else {
            $this->warn('Import cancelled.');
        }

        return self::SUCCESS;
    }
}
