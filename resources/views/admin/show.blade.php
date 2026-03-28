<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission #{{ $submission->id }} — Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif;} .badge{@apply inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold;}</style>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="text-xs text-gray-500 hover:text-gray-800 flex items-center gap-1 transition">
                    ← Back to Dashboard
                </a>
                <span class="text-gray-200">|</span>
                <span class="font-semibold text-gray-800 text-sm">Submission #{{ $submission->id }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="text-xs bg-gray-100 hover:bg-red-50 hover:text-red-600 text-gray-600 px-3 py-1.5 rounded-lg transition font-medium">Sign Out</button>
            </form>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 space-y-6">

        {{-- Summary Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 grid grid-cols-2 md:grid-cols-4 gap-5">
            <div>
                <p class="text-xs text-gray-500 mb-1">Field Officer</p>
                <p class="font-semibold text-gray-900 text-sm">{{ $submission->field_officer_name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Email</p>
                <p class="font-medium text-gray-700 text-sm">{{ $submission->submitter_email }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Submission Date</p>
                <p class="font-medium text-gray-700 text-sm">{{ $submission->submission_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Records</p>
                <p class="font-medium text-gray-700 text-sm">{{ $submission->vdbEntries->count() }} VDB · {{ $submission->accountInformations->count() }} Accounts</p>
            </div>
        </div>

        {{-- VDB Entries --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-indigo-50 bg-indigo-50/60">
                <h2 class="font-bold text-indigo-800">VDB Entries <span class="font-normal text-indigo-500 text-sm">({{ $submission->vdbEntries->count() }})</span></h2>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">#</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">VDB Name</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Division</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">District</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Thana</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Union</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Village</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($submission->vdbEntries as $i => $vdb)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $i + 1 }}</td>
                        <td class="px-5 py-3.5 font-medium text-gray-900">{{ $vdb->vdb_name }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $vdb->division }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $vdb->district }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $vdb->thana }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $vdb->union }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $vdb->village }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Account Information --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-violet-50 bg-violet-50/60">
                <h2 class="font-bold text-violet-800">Account Information <span class="font-normal text-violet-500 text-sm">({{ $submission->accountInformations->count() }})</span></h2>
            </div>
            <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[900px]">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">#</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Account Holder</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Type</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Account No.</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Card Dist.</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Card No.</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Created Via</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">App</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">QR</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($submission->accountInformations as $i => $acct)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $i + 1 }}</td>
                        <td class="px-5 py-3.5 font-medium text-gray-900">{{ $acct->account_holder_name }}</td>
                        <td class="px-5 py-3.5 text-gray-600 text-xs">{{ $acct->account_type }}</td>
                        <td class="px-5 py-3.5 text-gray-700 font-mono text-xs">{{ $acct->account_no }}</td>
                        <td class="px-5 py-3.5">
                            @if($acct->card_distributed)
                                <span class="badge bg-green-50 text-green-700">✓ Yes</span>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 font-mono text-xs">{{ $acct->card_no ?? '—' }}</td>
                        <td class="px-5 py-3.5">
                            @if($acct->created_through === 'VDB')
                                <span class="badge bg-indigo-50 text-indigo-700">VDB{{ $acct->vdb_name ? ': '.$acct->vdb_name : '' }}</span>
                            @else
                                <span class="badge bg-gray-100 text-gray-600">Own</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            @if($acct->app_distribution)
                                <span class="badge bg-green-50 text-green-700">✓ Yes</span>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            @if($acct->qr_distribution)
                                <span class="badge bg-green-50 text-green-700">✓ Yes</span>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </main>
</body>
</html>
