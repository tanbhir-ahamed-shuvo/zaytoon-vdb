<?php

namespace App\Exports;

use App\Models\VdbEntry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VdbDataExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return VdbEntry::with('submission')->get();
    }

    public function headings(): array
    {
        return [
            'Submission ID', 
            'Officer Name', 
            'Officer Email', 
            'Submission Date', 
            'VDB Name', 
            'Division', 
            'District', 
            'Thana', 
            'Union', 
            'Village'
        ];
    }

    public function map($vdb): array
    {
        return [
            $vdb->submission_id,
            $vdb->submission->field_officer_name ?? '',
            $vdb->submission->submitter_email ?? '',
            $vdb->submission ? $vdb->submission->submission_date->format('Y-m-d') : '',
            $vdb->vdb_name,
            $vdb->division,
            $vdb->district,
            $vdb->thana,
            $vdb->union,
            $vdb->village,
        ];
    }
}
