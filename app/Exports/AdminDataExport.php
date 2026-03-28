<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AdminDataExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new VdbDataExport(),
            new AccountDataExport(),
        ];
    }
}
