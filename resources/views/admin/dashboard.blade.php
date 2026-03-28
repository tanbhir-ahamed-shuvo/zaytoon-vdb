<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — Zaytoon VDB</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <span class="font-bold text-gray-900 text-sm">Zaytoon VDB</span>
                    <span class="ml-2 text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-semibold">Admin</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="text-xs bg-gray-100 hover:bg-red-50 hover:text-red-600 text-gray-600 px-3 py-1.5 rounded-lg transition font-medium">
                    Sign Out
                </button>
            </form>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Submissions</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['submissions']) }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-indigo-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-indigo-500 uppercase tracking-wide mb-1">VDB Entries</p>
                <p class="text-3xl font-bold text-indigo-700">{{ number_format($stats['vdb_entries']) }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-violet-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-violet-500 uppercase tracking-wide mb-1">Account Records</p>
                <p class="text-3xl font-bold text-violet-700">{{ number_format($stats['accounts']) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <h2 class="font-bold text-gray-900">All Submissions</h2>
                    <span class="text-xs text-gray-400">{{ $submissions->total() }} total</span>
                </div>
                <a href="{{ route('admin.export') }}" class="flex items-center gap-2 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white px-4 py-2 rounded-xl text-sm font-semibold transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export Data
                </a>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide">#</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide">Field Officer</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide">Email</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide">Date</th>
                        <th class="text-center px-4 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide">VDBs</th>
                        <th class="text-center px-4 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide">Accounts</th>
                        <th class="text-right px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($submissions as $sub)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4 text-gray-400 font-mono text-xs">#{{ $sub->id }}</td>
                        <td class="px-5 py-4">
                            <span class="font-medium text-gray-900">{{ $sub->field_officer_name }}</span>
                        </td>
                        <td class="px-5 py-4 text-gray-500 text-xs">{{ $sub->submitter_email }}</td>
                        <td class="px-5 py-4 text-gray-700">{{ $sub->submission_date->format('d M Y') }}</td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold">{{ $sub->vdb_entries_count }}</span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-violet-50 text-violet-700 text-xs font-semibold">{{ $sub->account_informations_count }}</span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.show', $sub) }}"
                               class="text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 px-3 py-1.5 rounded-lg transition">
                                View Details →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($submissions->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $submissions->links() }}
            </div>
            @endif
        </div>

        {{-- All VDB Entries --}}
        <div class="bg-white rounded-2xl border border-indigo-100 shadow-sm overflow-hidden mt-8 mb-8">
            <div class="px-6 py-4 border-b border-indigo-50 bg-indigo-50/60 flex items-center justify-between">
                <h2 class="font-bold text-indigo-900">All VDB Entries</h2>
                <span class="text-xs text-indigo-500 font-semibold">{{ $vdbs->total() }} total</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">ID</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Submission</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">VDB Name</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Village</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Division</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">District</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Thana</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($vdbs as $vdb)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5 text-gray-400 font-mono text-xs">#{{ $vdb->id }}</td>
                            <td class="px-5 py-3.5">
                                <a href="{{ route('admin.show', $vdb->submission_id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-xs underline">
                                    Sub #{{ $vdb->submission_id }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5 font-medium text-gray-900">{{ $vdb->vdb_name }}</td>
                            <td class="px-5 py-3.5 text-gray-600 text-xs">{{ $vdb->village }}</td>
                            <td class="px-5 py-3.5 text-gray-600 text-xs">{{ $vdb->division }}</td>
                            <td class="px-5 py-3.5 text-gray-600 text-xs">{{ $vdb->district }}</td>
                            <td class="px-5 py-3.5 text-gray-600 text-xs">{{ $vdb->thana }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($vdbs->hasPages())
            <div class="px-5 py-4 border-t border-indigo-50 bg-indigo-50/20">
                {{ $vdbs->appends(['submissions_page' => request('submissions_page'), 'account_page' => request('account_page')])->links() }}
            </div>
            @endif
        </div>

        {{-- All Account Records --}}
        <div class="bg-white rounded-2xl border border-violet-100 shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-violet-50 bg-violet-50/60 flex items-center justify-between">
                <h2 class="font-bold text-violet-900">All Account Records</h2>
                <span class="text-xs text-violet-500 font-semibold">{{ $accounts->total() }} total</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">ID</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Submission</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Account Holder</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Account No</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Type</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Created Via</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">VDB Name</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($accounts as $acct)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5 text-gray-400 font-mono text-xs">#{{ $acct->id }}</td>
                            <td class="px-5 py-3.5">
                                <a href="{{ route('admin.show', $acct->submission_id) }}" class="text-violet-600 hover:text-violet-800 font-medium text-xs underline">
                                    Sub #{{ $acct->submission_id }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5 font-medium text-gray-900">{{ $acct->account_holder_name }}</td>
                            <td class="px-5 py-3.5 text-gray-700 font-mono text-xs">{{ $acct->account_no }}</td>
                            <td class="px-5 py-3.5 text-gray-600 text-xs">{{ $acct->account_type }}</td>
                            <td class="px-5 py-3.5">
                                @if($acct->created_through === 'VDB')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase tracking-wider">VDB</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-[10px] font-bold uppercase tracking-wider">Own</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-gray-900 text-xs">{{ $acct->vdb_name ?: '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($accounts->hasPages())
            <div class="px-5 py-4 border-t border-violet-50 bg-violet-50/20">
                {{ $accounts->appends(['submissions_page' => request('submissions_page'), 'vdb_page' => request('vdb_page')])->links() }}
            </div>
            @endif
        </div>
    </main>
</body>
</html>
