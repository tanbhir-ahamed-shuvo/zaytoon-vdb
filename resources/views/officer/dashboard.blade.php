<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Submissions — Zaytoon VDB</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <span class="font-bold text-gray-900 text-sm">Zaytoon VDB</span>
                    <p class="text-xs text-gray-400">Field Officer Portal</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-gray-800">{{ session('officer_name') }}</p>
                    <p class="text-xs text-gray-400">{{ session('officer_email') }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="text-xs bg-gray-100 hover:bg-red-50 hover:text-red-600 text-gray-600 px-3 py-1.5 rounded-lg transition font-medium">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8">

        {{-- Alert --}}
        @if(session('success'))
        <div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-3.5 text-sm">
            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- Header Row --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold text-gray-900">My Submissions</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $submissions->count() }} total submission{{ $submissions->count() !== 1 ? 's' : '' }}</p>
            </div>
            <a href="{{ route('form.index') }}"
               class="inline-flex items-center gap-2 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow hover:shadow-lg hover:-translate-y-0.5 transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                New Submission
            </a>
        </div>

        @if($submissions->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-14 text-center">
            <svg class="w-14 h-14 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-gray-500 font-medium mb-3">No submissions yet</h3>
            <a href="{{ route('form.index') }}" class="inline-flex items-center gap-2 bg-green-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-green-700 transition">
                Create Your First Submission
            </a>
        </div>
        @else
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wide">#</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wide">Date</th>
                        <th class="text-center px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wide">VDB Entries</th>
                        <th class="text-center px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wide">Accounts</th>
                        <th class="text-right px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wide">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($submissions as $i => $sub)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4 text-gray-400 font-mono text-xs">#{{ $sub->id }}</td>
                        <td class="px-5 py-4">
                            <span class="font-medium text-gray-800">{{ $sub->submission_date->format('d M Y') }}</span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold">
                                {{ $sub->vdbEntries->count() }} VDB{{ $sub->vdbEntries->count() !== 1 ? 's' : '' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-violet-50 text-violet-700 text-xs font-semibold">
                                {{ $sub->accountInformations->count() }} Acct{{ $sub->accountInformations->count() !== 1 ? 's' : '' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('officer.edit', $sub) }}"
                               class="inline-flex items-center gap-1.5 text-xs font-semibold text-green-700 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($submissions->hasPages())
        <div class="mt-4 px-5 py-4 bg-white rounded-2xl border border-gray-100 shadow-sm">
            {{ $submissions->links() }}
        </div>
        @endif

        {{-- My VDB Entries --}}
        <div class="bg-white rounded-2xl border border-indigo-100 shadow-sm overflow-hidden mt-8 mb-8">
            <div class="px-6 py-4 border-b border-indigo-50 bg-indigo-50/60 flex items-center justify-between">
                <h2 class="font-bold text-indigo-900">My VDB Entries</h2>
                <span class="text-xs text-indigo-500 font-semibold">{{ $vdbs->total() }} total</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">ID</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">VDB Name</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Village</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Division</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">District</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Thana</th>
                            <th class="text-right px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($vdbs as $vdb)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5 text-gray-400 font-mono text-xs">#{{ $vdb->id }}</td>
                            <td class="px-5 py-3.5 font-medium text-gray-900">{{ $vdb->vdb_name }}</td>
                            <td class="px-5 py-3.5 text-gray-600 text-xs">{{ $vdb->village }}</td>
                            <td class="px-5 py-3.5 text-gray-600 text-xs">{{ $vdb->division }}</td>
                            <td class="px-5 py-3.5 text-gray-600 text-xs">{{ $vdb->district }}</td>
                            <td class="px-5 py-3.5 text-gray-600 text-xs">{{ $vdb->thana }}</td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('officer.edit', $vdb->submission_id) }}"
                                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-green-700 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                            </td>
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

        {{-- My Account Records --}}
        <div class="bg-white rounded-2xl border border-violet-100 shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-violet-50 bg-violet-50/60 flex items-center justify-between">
                <h2 class="font-bold text-violet-900">My Account Records</h2>
                <span class="text-xs text-violet-500 font-semibold">{{ $accounts->total() }} total</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">ID</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Account Holder</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Account No</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Type</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Created Via</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">VDB Name</th>
                            <th class="text-right px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($accounts as $acct)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5 text-gray-400 font-mono text-xs">#{{ $acct->id }}</td>
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
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('officer.edit', $acct->submission_id) }}"
                                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-green-700 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                            </td>
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
