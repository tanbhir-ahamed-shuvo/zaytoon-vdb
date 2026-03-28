<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\VdbEntry;
use App\Models\AccountInformation;
use App\Exports\AdminDataExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'submissions' => Submission::count(),
            'vdb_entries' => VdbEntry::count(),
            'accounts'    => AccountInformation::count(),
        ];

        $submissions = Submission::with('fieldOfficer')
            ->withCount(['vdbEntries', 'accountInformations'])
            ->latest()
            ->paginate(10, ['*'], 'submissions_page');

        $vdbs = VdbEntry::with('submission')->latest()->paginate(15, ['*'], 'vdb_page');
        $accounts = AccountInformation::with('submission')->latest()->paginate(15, ['*'], 'account_page');

        return view('admin.dashboard', compact('stats', 'submissions', 'vdbs', 'accounts'));
    }

    public function show(Submission $submission)
    {
        $submission->load(['fieldOfficer', 'vdbEntries', 'accountInformations']);
        return view('admin.show', compact('submission'));
    }

    public function export()
    {
        return Excel::download(new AdminDataExport, 'zaytoon_vdb_data_' . date('Y-m-d') . '.xlsx');
    }
}
