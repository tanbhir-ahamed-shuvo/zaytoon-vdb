<?php

namespace App\Exports;

use App\Models\AccountInformation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AccountDataExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return AccountInformation::with('submission')->get();
    }

    public function headings(): array
    {
        return [
            'Submission ID', 
            'Officer Name', 
            'Officer Email', 
            'Submission Date', 
            'Account Holder', 
            'Type', 
            'Account No', 
            'Created Through', 
            'VDB Name', 
            'App Dist.', 
            'QR Dist.', 
            'Card Dist.', 
            'Card No'
        ];
    }

    public function map($acct): array
    {
        return [
            $acct->submission_id,
            $acct->submission->field_officer_name ?? '',
            $acct->submission->submitter_email ?? '',
            $acct->submission ? $acct->submission->submission_date->format('Y-m-d') : '',
            $acct->account_holder_name,
            $acct->account_type,
            $acct->account_no,
            $acct->created_through,
            $acct->vdb_name,
            $acct->app_distribution ? 'Yes' : 'No',
            $acct->qr_distribution ? 'Yes' : 'No',
            $acct->card_distributed ? 'Yes' : 'No',
            $acct->card_no,
        ];
    }
}
