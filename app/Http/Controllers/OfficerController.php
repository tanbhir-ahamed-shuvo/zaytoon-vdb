<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\VdbEntry;
use App\Models\AccountInformation;
use App\Models\GeoData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfficerController extends Controller
{
    /** Officer's own submission list */
    public function dashboard()
    {
        $officerId = session('officer_id');

        $submissions = Submission::with(['vdbEntries', 'accountInformations'])
            ->where('field_officer_id', $officerId)
            ->latest()
            ->paginate(10, ['*'], 'submissions_page');

        $vdbs = VdbEntry::whereHas('submission', function($q) use ($officerId) {
            $q->where('field_officer_id', $officerId);
        })->latest()->paginate(15, ['*'], 'vdb_page');

        $accounts = AccountInformation::whereHas('submission', function($q) use ($officerId) {
            $q->where('field_officer_id', $officerId);
        })->latest()->paginate(15, ['*'], 'account_page');

        return view('officer.dashboard', compact('submissions', 'vdbs', 'accounts'));
    }

    /** Load a submission for editing */
    public function editForm(Submission $submission)
    {
        // Ensure officer owns this submission
        if ($submission->field_officer_id !== session('officer_id')) {
            abort(403);
        }

        $divisions = GeoData::distinct()->orderBy('division')->pluck('division');
        $submission->load(['vdbEntries', 'accountInformations']);

        $pastVdbs = VdbEntry::whereHas('submission', function($q) {
            $q->where('field_officer_id', session('officer_id'));
        })->distinct()->pluck('vdb_name')->toArray();

        return view('officer.edit', compact('submission', 'divisions', 'pastVdbs'));
    }

    /** Save the edited submission */
    public function update(Request $request, Submission $submission)
    {
        if ($submission->field_officer_id !== session('officer_id')) {
            abort(403);
        }

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

        DB::transaction(function () use ($request, $submission) {
            // Update parent
            $submission->update([
                'submission_date' => $request->submission_date,
            ]);

            // Replace children
            $submission->vdbEntries()->delete();
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

            $submission->accountInformations()->delete();
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

        return redirect()->route('officer.dashboard')
                         ->with('success', 'Submission updated successfully!');
    }
}
