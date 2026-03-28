<?php

namespace App\Imports;

use App\Models\GeoData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class GeoDataImport implements ToModel, WithHeadingRow, WithChunkReading, SkipsEmptyRows
{
    /**
     * Process each row and map it to a GeoData model.
     * Column names are lowercased & spaces replaced with underscores
     * by WithHeadingRow, so "Division" → "division", etc.
     */
    public function model(array $row): ?GeoData
    {
        // Sanitize: skip rows where any required field is blank
        $division = trim($row['division'] ?? '');
        $district = trim($row['district'] ?? '');
        $thana    = trim($row['thana']    ?? '');
        $union    = trim($row['union']    ?? '');

        if ($division === '' || $district === '' || $thana === '' || $union === '') {
            return null;
        }

        return new GeoData([
            'division' => $division,
            'district' => $district,
            'thana'    => $thana,
            'union'    => $union,
        ]);
    }

    /**
     * Read the file in chunks to avoid memory exhaustion on large datasets.
     */
    public function chunkSize(): int
    {
        return 500;
    }
}
