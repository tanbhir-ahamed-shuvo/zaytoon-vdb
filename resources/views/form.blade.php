<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Zaytoon VDB Data Collection</title>
    <meta name="description" content="Zaytoon VDB Data Collection Form for Field Officers">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50:  '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .section-card { @apply bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden; }
        .entry-block {
            animation: slideIn 0.25s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        select:focus, input:focus, textarea:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(34,197,94,0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            transition: all 0.2s ease;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 25px rgba(22,163,74,0.35); }
        .btn-add {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1.5px dashed #4ade80;
            transition: all 0.2s ease;
        }
        .btn-add:hover { background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border-color: #16a34a; }
        .remove-btn { transition: all 0.15s ease; }
        .remove-btn:hover { transform: scale(1.1); }
        .badge { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium; }
    </style>
</head>
<body class="bg-gradient-to-br from-brand-50 via-white to-emerald-50 min-h-screen">

    {{-- ===== HEADER ===== --}}
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('officer.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-800 flex items-center gap-1 transition">
                    ← Dashboard
                </a>
                <span class="text-gray-300">|</span>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center shadow">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-base font-bold text-gray-900 leading-tight">New Submission</h1>
                        <p class="text-xs text-gray-500">Zaytoon VDB Data Collection</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm hidden sm:block">
                    <span class="font-semibold text-gray-800">{{ session('officer_name') }}</span>
                </span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="text-xs bg-gray-100 hover:bg-red-50 hover:text-red-600 text-gray-600 px-3 py-1.5 rounded-lg transition font-medium">Sign Out</button>
                </form>
            </div>
        </div>
    </header>

    {{-- ===== SUCCESS ALERT ===== --}}
    @if(session('success'))
    <div id="successAlert" class="max-w-5xl mx-auto mt-4 px-4 sm:px-6">
        <div class="flex items-center gap-3 bg-brand-50 border border-brand-200 text-brand-800 rounded-xl px-5 py-4">
            <svg class="w-5 h-5 text-brand-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
            <button onclick="document.getElementById('successAlert').remove()" class="ml-auto text-brand-600 hover:text-brand-800">✕</button>
        </div>
    </div>
    @endif

    {{-- ===== VALIDATION ERRORS ===== --}}
    @if($errors->any())
    @php
        $fieldLabels = [
            'submission_date'        => 'Submission Date',
            'vdb_entries'            => null, // handled separately
            'account_informations'   => null, // handled separately
            'vdb_name'               => 'VDB Name',
            'village'                => 'Village',
            'division'               => 'Division',
            'district'               => 'District',
            'thana'                  => 'Thana',
            'union'                  => 'Union',
            'created_through'        => 'Created Through',
            'account_holder_name'    => 'Account Holder Name',
            'account_type'           => 'Account Type',
            'account_no'             => 'Account No.',
            'card_no'                => 'Card No.',
        ];

        $hasEmptySubmission = $errors->has('vdb_entries') || $errors->has('account_informations');
        $general   = [];
        $vdbErrors = [];
        $acctErrors = [];

        foreach ($errors->messages() as $key => $messages) {
            if (in_array($key, ['vdb_entries', 'account_informations'])) continue;

            // vdb_entries.0.field_name
            if (preg_match('/^vdb_entries\.(\d+)\.(.+)$/', $key, $m)) {
                $num = (int)$m[1] + 1;
                $field = $fieldLabels[$m[2]] ?? ucwords(str_replace('_', ' ', $m[2]));
                $vdbErrors[$num][] = $field;
                continue;
            }

            // account_informations.0.field_name
            if (preg_match('/^account_informations\.(\d+)\.(.+)$/', $key, $m)) {
                $num = (int)$m[1] + 1;
                $field = $fieldLabels[$m[2]] ?? ucwords(str_replace('_', ' ', $m[2]));
                $acctErrors[$num][] = $field;
                continue;
            }

            $label = $fieldLabels[$key] ?? ucwords(str_replace('_', ' ', $key));
            $general[] = $label . ': ' . $messages[0];
        }
    @endphp
    <div class="max-w-5xl mx-auto mt-4 px-4 sm:px-6">
        <div class="flex items-start gap-4 bg-red-50 border border-red-200 rounded-xl px-5 py-4">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <div class="text-sm text-red-700 space-y-1.5 w-full">
                <p class="font-semibold text-red-800">Please fix the following before submitting:</p>

                @if($hasEmptySubmission)
                    <p>• Please add at least one <strong>VDB Entry</strong> or one <strong>Account Record</strong>.</p>
                @endif

                @foreach($general as $msg)
                    <p>• {{ $msg }}</p>
                @endforeach

                @foreach($vdbErrors as $num => $fields)
                    <p>• <strong>VDB Entry #{{ $num }}</strong>: Missing {{ implode(', ', $fields) }}.</p>
                @endforeach

                @foreach($acctErrors as $num => $fields)
                    <p>• <strong>Account Record #{{ $num }}</strong>: Missing {{ implode(', ', $fields) }}.</p>
                @endforeach
            </div>
        </div>
    </div>
    @endif



    {{-- ===== MAIN FORM ===== --}}
    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        <form id="mainForm" action="{{ route('form.store') }}" method="POST" novalidate>
            @csrf

            {{-- ============================================================
                 SECTION A — MAIN INFO
            ============================================================ --}}
            <div class="section-card mb-6">
                <div class="bg-gradient-to-r from-brand-600 to-brand-700 px-6 py-4 flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-white/20 text-white text-sm font-bold flex items-center justify-center">A</span>
                    <h2 class="text-white font-semibold text-lg">Submission Information</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                    {{-- Logged-in officer (read-only) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Field Officer</label>
                        <div class="w-full rounded-xl border border-gray-100 bg-gray-100 px-4 py-2.5 text-gray-700 text-sm font-semibold">
                            {{ session('officer_name') }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                        <div class="w-full rounded-xl border border-gray-100 bg-gray-100 px-4 py-2.5 text-gray-500 text-sm">
                            {{ session('officer_email') }}
                        </div>
                    </div>
                    {{-- Submission Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5" for="submission_date_display">
                            Submission Date
                        </label>
                        <input id="submission_date_display" type="text"
                               class="w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-2.5 text-gray-600 text-sm cursor-not-allowed"
                               value="{{ now()->format('d M Y') }}" readonly>
                        <input type="hidden" id="submission_date" name="submission_date" value="{{ now()->format('Y-m-d') }}">
                    </div>
                </div>
            </div>

            {{-- ============================================================
                 SECTION B — VDB ENTRIES
            ============================================================ --}}
            <div class="section-card mb-6">
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-white/20 text-white text-sm font-bold flex items-center justify-center">B</span>
                        <h2 class="text-white font-semibold text-lg">VDB Entries</h2>
                        <span id="vdb-count-badge" class="badge bg-white/20 text-white text-xs">0 added</span>
                    </div>
                    <button type="button" id="add-vdb-btn" onclick="addVdbBlock()"
                            class="btn-add flex items-center gap-2 px-4 py-2 rounded-xl text-brand-700 font-semibold text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add VDB
                    </button>
                </div>

                <div id="vdb-container" class="p-6 space-y-4">
                    <div id="vdb-empty" class="text-center py-10 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <p class="text-sm">No VDB entries yet. Click <strong>Add VDB</strong> to begin.</p>
                    </div>
                </div>
            </div>

            {{-- ============================================================
                 SECTION C — ACCOUNT INFORMATIONS
            ============================================================ --}}
            <div class="section-card mb-8">
                <div class="bg-gradient-to-r from-violet-600 to-violet-700 px-6 py-4 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-white/20 text-white text-sm font-bold flex items-center justify-center">C</span>
                        <h2 class="text-white font-semibold text-lg">Account Information</h2>
                        <span id="acct-count-badge" class="badge bg-white/20 text-white text-xs">0 added</span>
                    </div>
                    <button type="button" id="add-acct-btn" onclick="addAccountBlock()"
                            class="btn-add flex items-center gap-2 px-4 py-2 rounded-xl text-brand-700 font-semibold text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Account
                    </button>
                </div>

                <div id="acct-container" class="p-6 space-y-4">
                    <div id="acct-empty" class="text-center py-10 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <p class="text-sm">No accounts yet. Click <strong>Add Account</strong> to begin.</p>
                    </div>
                </div>
            </div>

            {{-- ===== SUBMIT ===== --}}
            <div class="flex justify-end">
                <button type="submit" id="submit-btn"
                        class="btn-primary text-white font-semibold px-10 py-3.5 rounded-xl flex items-center gap-3 shadow-lg text-base">
                    <svg id="submit-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <svg id="submit-spinner" class="w-5 h-5 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                    Save All Data
                </button>
            </div>
        </form>
    </main>

    {{-- ===== GEO AJAX ROUTES (passed to JS) ===== --}}
    <script>
        const GEO_ROUTES = {
            districts: "{{ route('geo.districts') }}",
            thanas:    "{{ route('geo.thanas') }}",
            unions:    "{{ route('geo.unions') }}",
        };
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        // Distinct divisions pre-loaded from DB (only division names, very small)
        const DIVISIONS = @json($divisions);
    </script>

    <script>
    // =========================================================
    //  STATE
    // =========================================================
    let vdbIndex   = 0;
    let acctIndex  = 0;

    // =========================================================
    //  HELPERS
    // =========================================================
    function makeSelect(name, placeholder, classes = '') {
        return `<select name="${name}" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-gray-800 text-sm focus:border-brand-500 transition ${classes}">
                    <option value="">${placeholder}</option>
                </select>`;
    }

    function inputField(name, placeholder, type = 'text', required = true) {
        const req = required ? 'required' : '';
        return `<input type="${type}" name="${name}" placeholder="${placeholder}" ${req}
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-gray-800 text-sm focus:border-brand-500 transition">`;
    }

    function labelWrap(label, inner, required = true) {
        const star = required ? '<span class="text-red-500 ml-0.5">*</span>' : '';
        return `<div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">${label}${star}</label>
                    ${inner}
                </div>`;
    }

    function updateVdbCountBadge() {
        const count = document.querySelectorAll('.vdb-block').length;
        document.getElementById('vdb-count-badge').textContent = `${count} added`;
        document.getElementById('vdb-empty').style.display = count === 0 ? 'block' : 'none';
    }

    function updateAcctCountBadge() {
        const count = document.querySelectorAll('.acct-block').length;
        document.getElementById('acct-count-badge').textContent = `${count} added`;
        document.getElementById('acct-empty').style.display = count === 0 ? 'block' : 'none';
    }

    // =========================================================
    //  VDB NAME SYNC — push all VDB names to all Account selects
    // =========================================================
    const PAST_VDBS = @json($pastVdbs ?? []);

    function getVdbNames() {
        const currentInputs = Array.from(document.querySelectorAll('.vdb-name-input'))
                                   .map(el => el.value.trim())
                                   .filter(v => v !== '');
        return [...new Set([...PAST_VDBS, ...currentInputs])];
    }

    function syncVdbNamesToAccounts() {
        const names = getVdbNames();
        document.querySelectorAll('.acct-vdb-name-select').forEach(sel => {
            const currentVal = sel.value;
            sel.innerHTML = '<option value="">— Select VDB —</option>';
            names.forEach(name => {
                const opt = document.createElement('option');
                opt.value = name;
                opt.textContent = name;
                if (name === currentVal) opt.selected = true;
                sel.appendChild(opt);
            });
        });
    }

    // =========================================================
    //  SECTION B — ADD VDB BLOCK
    // =========================================================
    function addVdbBlock(prefill = null) {
        const idx = vdbIndex++;
        const prefix = `vdb_entries[${idx}]`;
        const vn  = prefill?.vdb_name || '';
        const vil = prefill?.village  || '';
        const div = prefill?.division || '';
        const dist = prefill?.district || '';
        const thana = prefill?.thana  || '';
        const union = prefill?.union  || '';

        const block = document.createElement('div');
        block.className = 'vdb-block entry-block bg-gray-50 border border-gray-200 rounded-2xl p-5';
        block.dataset.vdbIdx = idx;

        block.innerHTML = `
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-semibold text-indigo-700 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center vdb-num"></span>
                    VDB Entry
                </span>
                <button type="button" onclick="removeVdbBlock(this)"
                        class="remove-btn w-8 h-8 rounded-lg bg-red-50 border border-red-200 text-red-500 hover:bg-red-100 flex items-center justify-center" title="Remove">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                ${labelWrap('VDB Name', `<input type="text" name="${prefix}[vdb_name]" value="${vn}" placeholder="Enter VDB name" required
                    class="vdb-name-input w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm focus:border-brand-500 transition">`)}
                ${labelWrap('Village', `<input type="text" name="${prefix}[village]" value="${vil}" placeholder="Enter village name" required
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-gray-800 text-sm focus:border-brand-500 transition">`)}
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                ${labelWrap('Division', `<select name="${prefix}[division]" required
                    class="vdb-division w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm focus:border-brand-500 transition">
                    <option value="">— Division —</option>
                    ${DIVISIONS.map(d => `<option value="${d}" ${d===div?'selected':''}>${d}</option>`).join('')}
                </select>`)}
                ${labelWrap('District', `<select name="${prefix}[district]" required
                    class="vdb-district w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm focus:border-brand-500 transition">
                    <option value="">— District —</option>
                </select>`)}
                ${labelWrap('Thana', `<select name="${prefix}[thana]" required
                    class="vdb-thana w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm focus:border-brand-500 transition">
                    <option value="">— Thana —</option>
                </select>`)}
                ${labelWrap('Union', `<select name="${prefix}[union]" required
                    class="vdb-union w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm focus:border-brand-500 transition">
                    <option value="">— Union —</option>
                </select>`)}
            </div>
        `;

        // ── AJAX cascading listeners ──────────────────────────────────
        const divSel   = block.querySelector('.vdb-division');
        const distSel  = block.querySelector('.vdb-district');
        const thanaSel = block.querySelector('.vdb-thana');
        const unionSel = block.querySelector('.vdb-union');

        divSel.addEventListener('change', function () {
            resetSelect(distSel,  '— District —');
            resetSelect(thanaSel, '— Thana —');
            resetSelect(unionSel, '— Union —');
            if (!this.value) return;
            fetchGeo(GEO_ROUTES.districts, { division: this.value }, distSel, '— District —');
        });

        distSel.addEventListener('change', function () {
            resetSelect(thanaSel, '— Thana —');
            resetSelect(unionSel, '— Union —');
            if (!this.value) return;
            fetchGeo(GEO_ROUTES.thanas, { division: divSel.value, district: this.value }, thanaSel, '— Thana —');
        });

        thanaSel.addEventListener('change', function () {
            resetSelect(unionSel, '— Union —');
            if (!this.value) return;
            fetchGeo(GEO_ROUTES.unions, { division: divSel.value, district: distSel.value, thana: this.value }, unionSel, '— Union —');
        });

        // VDB name real-time sync to Account blocks
        block.querySelector('.vdb-name-input').addEventListener('input', syncVdbNamesToAccounts);

        document.getElementById('vdb-container').appendChild(block);
        reindexVdbNumbers();
        updateVdbCountBadge();

        // Restore geo cascade if prefill has division
        if (div) {
            fetchGeo(GEO_ROUTES.districts, { division: div }, distSel, '— District —')
                .then(() => { if (dist) {
                    distSel.value = dist;
                    return fetchGeo(GEO_ROUTES.thanas, { division: div, district: dist }, thanaSel, '— Thana —');
                }})
                .then(() => { if (thana) {
                    thanaSel.value = thana;
                    return fetchGeo(GEO_ROUTES.unions, { division: div, district: dist, thana }, unionSel, '— Union —');
                }})
                .then(() => { if (union) unionSel.value = union; })
                .catch(() => {});
        }
    }

    function removeVdbBlock(btn) {
        btn.closest('.vdb-block').remove();
        reindexVdbNumbers();
        updateVdbCountBadge();
        syncVdbNamesToAccounts();
    }

    function reindexVdbNumbers() {
        document.querySelectorAll('.vdb-block').forEach((block, i) => {
            const numEl = block.querySelector('.vdb-num');
            if (numEl) numEl.textContent = i + 1;
        });
    }

    // =========================================================
    //  SECTION C — ADD ACCOUNT BLOCK
    // =========================================================
    const ACCOUNT_TYPES = [
        'Savings Account (SB)',
        'Current Account (CD)',
        'Personal Retail Account (PRA)',
        'Fixed Deposit Receipt (FDR)',
        'Deposit Pension Scheme (DPS)',
        'Others',
    ];

    function addAccountBlock(prefill = null) {
        const idx    = acctIndex++;
        const prefix = `account_informations[${idx}]`;
        const ct = prefill?.created_through || '';
        const ahn = prefill?.account_holder_name || '';
        const at = prefill?.account_type || '';
        const ano = prefill?.account_no || '';
        const cno = prefill?.card_no || '';
        const vn = prefill?.vdb_name || '';
        const appDist = prefill?.app_distribution || false;
        const qrDist = prefill?.qr_distribution || false;
        const cd = prefill?.card_distributed || false;

        const block = document.createElement('div');
        block.className = 'acct-block entry-block bg-gray-50 border border-gray-200 rounded-2xl p-5';

        block.innerHTML = `
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-semibold text-violet-700 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-violet-100 text-violet-700 text-xs font-bold flex items-center justify-center acct-num"></span>
                    Account Record
                </span>
                <button type="button" onclick="removeAcctBlock(this)"
                        class="remove-btn w-8 h-8 rounded-lg bg-red-50 border border-red-200 text-red-500 hover:bg-red-100 flex items-center justify-center" title="Remove">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Row 1: Created Through + VDB Name -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Created Through <span class="text-red-500">*</span></label>
                    <div class="flex rounded-xl overflow-hidden border border-gray-200">
                        <label class="flex-1 flex items-center justify-center gap-2 py-2.5 cursor-pointer bg-white hover:bg-brand-50 transition acct-through-label ${ct==='VDB'?'bg-brand-50':''}" data-val="VDB">
                            <input type="radio" name="${prefix}[created_through]" value="VDB" class="acct-through-radio text-brand-600" required ${ct==='VDB'?'checked':''}>
                            <span class="text-sm font-medium text-gray-700">VDB</span>
                        </label>
                        <div class="w-px bg-gray-200"></div>
                        <label class="flex-1 flex items-center justify-center gap-2 py-2.5 cursor-pointer bg-white hover:bg-brand-50 transition acct-through-label ${ct==='OWN'?'bg-brand-50':''}" data-val="OWN">
                            <input type="radio" name="${prefix}[created_through]" value="OWN" class="acct-through-radio text-brand-600" required ${ct==='OWN'?'checked':''}>
                            <span class="text-sm font-medium text-gray-700">Own</span>
                        </label>
                    </div>
                </div>
                <div class="acct-vdb-name-wrapper" style="display:${ct==='VDB'?'block':'none'};">
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">VDB Name <span class="text-red-500">*</span></label>
                    <select name="${prefix}[vdb_name]" class="acct-vdb-name-select w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm focus:border-brand-500 transition">
                        <option value="">— Select VDB —</option>
                    </select>
                </div>
            </div>

            <!-- Row 2: Account Holder + Account Type -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                ${labelWrap('Account Holder Name', `<input type="text" name="${prefix}[account_holder_name]" value="${ahn}" placeholder="Full name" required class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-gray-800 text-sm focus:border-brand-500 transition">`)}
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Account Type <span class="text-red-500">*</span></label>
                    <select name="${prefix}[account_type]" required
                            class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm focus:border-brand-500 transition">
                        <option value="">— Select type —</option>
                        ${ACCOUNT_TYPES.map(t => `<option value="${t}" ${t===at?'selected':''}>${t}</option>`).join('')}
                    </select>
                </div>
            </div>

            <!-- Row 3: Account No + Card No -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                ${labelWrap('Account No.', `<input type="text" name="${prefix}[account_no]" value="${ano}" placeholder="Account number" required class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-gray-800 text-sm focus:border-brand-500 transition">`)}
                <div class="acct-card-no-wrapper" style="display:${cd?'block':'none'};">
                    ${labelWrap('Card No.', `<input type="text" name="${prefix}[card_no]" value="${cno}" placeholder="Card number" ${cd?'required':''} class="acct-card-no-input w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm focus:border-brand-500 transition">`)}
                </div>
            </div>

            <!-- Row 4: Checkboxes -->
            <div class="flex flex-wrap gap-6">
                <label class="flex items-center gap-2.5 cursor-pointer group">
                    <input type="hidden" name="${prefix}[card_distributed]" value="0">
                    <input type="checkbox" name="${prefix}[card_distributed]" value="1" ${cd?'checked':''}
                           class="acct-card-distributed-cb w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500 cursor-pointer">
                    <span class="text-sm font-medium text-gray-700 group-hover:text-brand-700 transition">Card Distributed</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer group">
                    <input type="hidden" name="${prefix}[app_distribution]" value="0">
                    <input type="checkbox" name="${prefix}[app_distribution]" value="1" ${appDist?'checked':''}
                           class="w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500 cursor-pointer">
                    <span class="text-sm font-medium text-gray-700 group-hover:text-brand-700 transition">App Distribution</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer group">
                    <input type="hidden" name="${prefix}[qr_distribution]" value="0">
                    <input type="checkbox" name="${prefix}[qr_distribution]" value="1" ${qrDist?'checked':''}
                           class="w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500 cursor-pointer">
                    <span class="text-sm font-medium text-gray-700 transition">QR Distribution</span>
                </label>
            </div>
        `;

        // Bind created_through radio buttons
        block.querySelectorAll('.acct-through-radio').forEach(radio => {
            radio.addEventListener('change', function () {
                const vdbNameWrapper = block.querySelector('.acct-vdb-name-wrapper');
                const select = block.querySelector('.acct-vdb-name-select');
                if (this.value === 'VDB') {
                    vdbNameWrapper.style.display = 'block';
                    select.required = true;
                    syncVdbNamesToAccounts();
                } else {
                    vdbNameWrapper.style.display = 'none';
                    select.value = '';
                    select.required = false;
                }
                // Highlight active label
                block.querySelectorAll('.acct-through-label').forEach(lbl => {
                    lbl.classList.toggle('bg-brand-50', lbl.dataset.val === this.value);
                });
            });
        });

        // Bind Card Distributed checkbox
        block.querySelector('.acct-card-distributed-cb').addEventListener('change', function () {
            const wrap = block.querySelector('.acct-card-no-wrapper');
            const input = block.querySelector('.acct-card-no-input');
            if (this.checked) {
                wrap.style.display = 'block';
                input.required = true;
            } else {
                wrap.style.display = 'none';
                input.required = false;
                input.value = '';
            }
        });

        document.getElementById('acct-container').appendChild(block);
        reindexAcctNumbers();
        updateAcctCountBadge();
        // Populate VDB names immediately
        syncVdbNamesToAccounts();

        // Restore selected VDB name specifically for this block if VDB is selected
        if (vn) {
            setTimeout(() => {
                const s = block.querySelector('.acct-vdb-name-select');
                if (s) s.value = vn;
            }, 100);
        }
    }

    function removeAcctBlock(btn) {
        btn.closest('.acct-block').remove();
        reindexAcctNumbers();
        updateAcctCountBadge();
    }

    function reindexAcctNumbers() {
        document.querySelectorAll('.acct-block').forEach((block, i) => {
            const numEl = block.querySelector('.acct-num');
            if (numEl) numEl.textContent = i + 1;
        });
    }

    // =========================================================
    //  FORM SUBMIT — loading state
    // =========================================================
    document.getElementById('mainForm').addEventListener('submit', function () {
        const btn = document.getElementById('submit-btn');
        document.getElementById('submit-icon').classList.add('hidden');
        document.getElementById('submit-spinner').classList.remove('hidden');
        btn.disabled = true;
        btn.classList.add('opacity-75');
    });

    // =========================================================
    //  AJAX HELPERS
    // =========================================================

    /**
     * Fetch geo options from the server and populate a <select>.
     * Shows a loading state while the request is in flight.
     */
    function fetchGeo(url, params, selectEl, placeholder) {
        selectEl.disabled = true;
        selectEl.innerHTML = `<option value="">⏳ Loading…</option>`;
        const qs = new URLSearchParams(params).toString();
        return fetch(`${url}?${qs}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(items => {
            selectEl.innerHTML = `<option value="">${placeholder}</option>`;
            items.forEach(val => {
                const opt = document.createElement('option');
                opt.value = val;
                opt.textContent = val;
                selectEl.appendChild(opt);
            });
        })
        .catch(() => {
            selectEl.innerHTML = `<option value="">⚠ Error loading</option>`;
        })
        .finally(() => {
            selectEl.disabled = false;
        });
    }

    /** Reset a select to just its placeholder option */
    function resetSelect(selectEl, placeholder) {
        selectEl.innerHTML = `<option value="">${placeholder}</option>`;
        selectEl.disabled = false;
    }

    // =========================================================
    //  INIT — restore old input on validation error
    // =========================================================
    const OLD_VDBS    = @json(old('vdb_entries', []));
    const OLD_ACCOUNTS = @json(old('account_informations', []));

    OLD_VDBS.forEach(v => addVdbBlock(v));
    OLD_ACCOUNTS.forEach(a => addAccountBlock(a));
    updateVdbCountBadge();
    updateAcctCountBadge();
    </script>
</body>
</html>
