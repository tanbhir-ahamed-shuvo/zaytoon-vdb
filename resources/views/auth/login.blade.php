<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Zaytoon VDB</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .card { animation: fadeUp 0.4s ease-out; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .tab-active { background: linear-gradient(135deg,#16a34a,#15803d); color:#fff; box-shadow:0 4px 15px rgba(22,163,74,.3); }
        .tab-inactive { background:#f1f5f9; color:#64748b; }
        .input-field { @apply w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-800 text-sm transition; }
        .input-field:focus { outline:none; box-shadow:0 0 0 3px rgba(34,197,94,.15); border-color:#22c55e; }
        .btn-submit { background:linear-gradient(135deg,#16a34a,#15803d); transition:all .2s; }
        .btn-submit:hover { transform:translateY(-1px); box-shadow:0 8px 25px rgba(22,163,74,.35); }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-green-50 via-white to-emerald-50 flex items-center justify-center p-4">

    <div class="w-full max-w-md card">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center mx-auto shadow-lg mb-4">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Zaytoon VDB</h1>
            <p class="text-gray-500 text-sm mt-1">Data Collection Portal</p>
        </div>

        {{-- Global alert --}}
        @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- Tabs --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="flex p-1.5 gap-1.5 bg-gray-50 border-b border-gray-100">
                <button id="tab-officer" onclick="switchTab('officer')"
                        class="flex-1 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 tab-active">
                    👮 Field Officer
                </button>
                <button id="tab-admin" onclick="switchTab('admin')"
                        class="flex-1 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 tab-inactive">
                    🛡 Admin
                </button>
            </div>

            {{-- ── Officer Login Panel ── --}}
            <div id="panel-officer" class="p-6">
                <h2 class="text-base font-semibold text-gray-800 mb-5">Sign in as Field Officer</h2>

                @if($errors->has('officer_id') || $errors->has('email'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
                    @foreach(['officer_id','email'] as $field)
                        @error($field)<p>{{ $message }}</p>@enderror
                    @endforeach
                </div>
                @endif

                <form action="{{ route('login.officer') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Your Name <span class="text-red-500">*</span></label>
                        <select name="officer_id" required class="input-field">
                            <option value="">— Select your name —</option>
                            @foreach($officers as $officer)
                                <option value="{{ $officer->id }}" {{ old('officer_id') == $officer->id ? 'selected' : '' }}>
                                    {{ $officer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Your Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               placeholder="Enter your personal email"
                               class="input-field">
                    </div>
                    <button type="submit" class="btn-submit w-full text-white font-semibold py-3 rounded-xl mt-2 text-sm">
                        Sign In as Field Officer →
                    </button>
                </form>
            </div>

            {{-- ── Admin Login Panel ── --}}
            <div id="panel-admin" class="p-6 hidden">
                <h2 class="text-base font-semibold text-gray-800 mb-5">Admin Sign In</h2>

                @if($errors->has('admin_password'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
                    {{ $errors->first('admin_password') }}
                </div>
                @endif

                <form action="{{ route('login.admin') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Admin Email <span class="text-red-500">*</span></label>
                        <input type="email" name="admin_email" value="{{ old('admin_email') }}" required
                               placeholder="Enter admin email"
                               class="input-field">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="admin_password" required
                               placeholder="Enter admin password"
                               class="input-field">
                    </div>
                    <button type="submit" class="btn-submit w-full text-white font-semibold py-3 rounded-xl mt-2 text-sm">
                        Sign In as Admin →
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">© {{ date('Y') }} Zaytoon VDB Data Collection</p>
    </div>

    <script>
        // Auto-switch to admin tab if admin validation error
        const activeTab = '{{ old("active_tab", "officer") }}' === 'admin' || {{ $errors->has('admin_password') ? 'true' : 'false' }} ? 'admin' : 'officer';
        if (activeTab === 'admin') switchTab('admin');

        function switchTab(tab) {
            const isOfficer = tab === 'officer';
            document.getElementById('panel-officer').classList.toggle('hidden', !isOfficer);
            document.getElementById('panel-admin').classList.toggle('hidden', isOfficer);
            document.getElementById('tab-officer').className = 'flex-1 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 ' + (isOfficer ? 'tab-active' : 'tab-inactive');
            document.getElementById('tab-admin').className   = 'flex-1 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 ' + (!isOfficer ? 'tab-active' : 'tab-inactive');
        }
    </script>
</body>
</html>
