<?php

namespace App\Http\Controllers;

use App\Models\GeoData;
use App\Models\Submission;
use App\Models\VdbEntry;
use App\Models\AccountInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    public function index()
    {
        $divisions = GeoData::distinct()->orderBy('division')->pluck('division');
        $pastVdbs = [];
        if (session()->has('officer_id')) {
            $pastVdbs = VdbEntry::whereHas('submission', function($q) {
                $q->where('field_officer_id', session('officer_id'));
            })->distinct()->pluck('vdb_name')->toArray();
        }
        return view('form', compact('divisions', 'pastVdbs'));
    }

    /** Store a new submission — officer info comes from session */
    public function store(Request $request)
    {
        $request->validate([
            'submission_date'                               => 'required|date',
            'vdb_entries'                                   => 'required_without:account_informations|nullable|array|min:1',
            'vdb_entries.*.vdb_name'                        => 'required|string|max:255',
            'vdb_entries.*.division'                        => 'required|string|max:255',
            'vdb_entries.*.district'                        => 'required|string|max:255',
            'vdb_entries.*.thana'                           => 'required|string|max:255',
            'vdb_entries.*.union'                           => 'required|string|max:255',
            'vdb_entries.*.village'                         => 'required|string|max:255',
            'account_informations'                          => 'required_without:vdb_entries|nullable|array|min:1',
            'account_informations.*.created_through'        => 'required|in:VDB,OWN',
            'account_informations.*.vdb_name'               => 'nullable|string|max:255',
            'account_informations.*.account_holder_name'    => 'required|string|max:255',
            'account_informations.*.account_type'           => 'required|string',
            'account_informations.*.account_no'             => 'required|string|max:255',
            'account_informations.*.card_distributed'       => 'boolean',
            'account_informations.*.card_no'                => 'required_if:account_informations.*.card_distributed,1|nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $submission = Submission::create([
                'field_officer_id'   => session('officer_id'),
                'field_officer_name' => session('officer_name'),
                'submitter_email'    => session('officer_email'),
                'submission_date'    => $request->submission_date,
            ]);

            if (!empty($request->vdb_entries)) {
                foreach ($request->vdb_entries as $entry) {
                    VdbEntry::create([
                        'submission_id' => $submission->id,
                        'vdb_name'      => $entry['vdb_name'],
                        'division'      => $entry['division'],
                        'district'      => $entry['district'],
                        'thana'         => $entry['thana'],
                        'union'         => $entry['union'],
                        'village'       => $entry['village'],
                    ]);
                }
            }

            if (!empty($request->account_informations)) {
                foreach ($request->account_informations as $acct) {
                    AccountInformation::create([
                        'submission_id'       => $submission->id,
                        'created_through'     => $acct['created_through'],
                        'vdb_name'            => $acct['created_through'] === 'VDB' ? ($acct['vdb_name'] ?? null) : null,
                        'account_holder_name' => $acct['account_holder_name'],
                        'account_type'        => $acct['account_type'],
                        'account_no'          => $acct['account_no'],
                        'card_distributed'    => isset($acct['card_distributed']) ? (bool) $acct['card_distributed'] : false,
                        'card_no'             => !empty($acct['card_distributed']) ? $acct['card_no'] : null,
                        'app_distribution'    => isset($acct['app_distribution']) ? (bool) $acct['app_distribution'] : false,
                        'qr_distribution'     => isset($acct['qr_distribution'])  ? (bool) $acct['qr_distribution']  : false,
                    ]);
                }
            }
        });

        return redirect()->route('officer.dashboard')->with('success', 'Submission saved successfully!');
    }
}
