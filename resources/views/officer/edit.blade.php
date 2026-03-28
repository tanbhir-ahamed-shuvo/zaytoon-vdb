<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Submission #{{ $submission->id }} — Zaytoon VDB</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{brand:{50:'#f0fdf4',100:'#dcfce7',200:'#bbf7d0',500:'#22c55e',600:'#16a34a',700:'#15803d'}}}}}</script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Inter',sans-serif;}
        .entry-block{animation:slideIn .25s ease-out;}
        @keyframes slideIn{from{opacity:0;transform:translateY(-8px);}to{opacity:1;transform:translateY(0);}}
        select:focus,input:focus{outline:none;box-shadow:0 0 0 3px rgba(34,197,94,.15);}
        .btn-primary{background:linear-gradient(135deg,#16a34a,#15803d);transition:all .2s;}
        .btn-primary:hover{transform:translateY(-1px);box-shadow:0 8px 25px rgba(22,163,74,.35);}
        .btn-add{background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1.5px dashed #4ade80;transition:all .2s;}
        .btn-add:hover{background:linear-gradient(135deg,#dcfce7,#bbf7d0);border-color:#16a34a;}
        .section-card{@apply bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden;}
    </style>
</head>
<body class="bg-gradient-to-br from-brand-50 via-white to-emerald-50 min-h-screen">

    {{-- Navbar --}}
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('officer.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-800 flex items-center gap-1.5 transition">
                    ← Dashboard
                </a>
                <span class="font-bold text-gray-900">Edit Submission <span class="text-gray-400 font-normal">#{{ $submission->id }}</span></span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="text-xs bg-gray-100 hover:bg-red-50 hover:text-red-600 text-gray-600 px-3 py-1.5 rounded-lg transition">Sign Out</button>
            </form>
        </div>
    </header>

    @if($errors->any())
    <div class="max-w-5xl mx-auto mt-4 px-4 sm:px-6">
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-5 py-4">
            <p class="font-semibold mb-2">Please fix the following errors:</p>
            <ul class="list-disc list-inside text-sm space-y-1">@foreach($errors->all() as $e)<li>{{$e}}</li>@endforeach</ul>
        </div>
    </div>
    @endif

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        <form action="{{ route('officer.update', $submission) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Officer info (read-only) --}}
            <div class="section-card mb-6">
                <div class="bg-gradient-to-r from-brand-600 to-brand-700 px-6 py-4">
                    <h2 class="text-white font-semibold">Submission Info</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Field Officer</label>
                        <p class="font-semibold text-gray-900">{{ session('officer_name') }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                        <p class="text-gray-700">{{ session('officer_email') }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Submission Date</label>
                        <input type="date" name="submission_date" value="{{ old('submission_date', $submission->submission_date->format('Y-m-d')) }}" required readonly
                               class="w-full rounded-xl border border-gray-200 bg-gray-100 px-3 py-2.5 text-gray-600 text-sm cursor-not-allowed">
                    </div>
                </div>
            </div>

            {{-- VDB Section --}}
            <div class="section-card mb-6">
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-white/20 text-white text-sm font-bold flex items-center justify-center">B</span>
                        <h2 class="text-white font-semibold">VDB Entries</h2>
                        <span id="vdb-count-badge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/20 text-white">0 added</span>
                    </div>
                    <button type="button" onclick="addVdbBlock()" class="btn-add flex items-center gap-2 px-4 py-2 rounded-xl text-brand-700 font-semibold text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg> Add VDB
                    </button>
                </div>
                <div id="vdb-container" class="p-6 space-y-4"></div>
            </div>

            {{-- Account Section --}}
            <div class="section-card mb-8">
                <div class="bg-gradient-to-r from-violet-600 to-violet-700 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-white/20 text-white text-sm font-bold flex items-center justify-center">C</span>
                        <h2 class="text-white font-semibold">Account Information</h2>
                        <span id="acct-count-badge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/20 text-white">0 added</span>
                    </div>
                    <button type="button" onclick="addAccountBlock()" class="btn-add flex items-center gap-2 px-4 py-2 rounded-xl text-brand-700 font-semibold text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg> Add Account
                    </button>
                </div>
                <div id="acct-container" class="p-6 space-y-4"></div>
            </div>

            <div class="flex gap-3 justify-end">
                <a href="{{ route('officer.dashboard') }}" class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="btn-primary text-white font-semibold px-10 py-3 rounded-xl flex items-center gap-2 shadow-lg text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Changes
                </button>
            </div>
        </form>
    </main>

    <script>
    const GEO_ROUTES = {
        districts: "{{ route('geo.districts') }}",
        thanas:    "{{ route('geo.thanas') }}",
        unions:    "{{ route('geo.unions') }}",
    };
    const DIVISIONS = @json($divisions);
    // Pre-populated data from existing submission
    const EXISTING_VDBS     = @json($submission->vdbEntries);
    const EXISTING_ACCOUNTS = @json($submission->accountInformations);

    let vdbIndex  = 0;
    let acctIndex = 0;

    const ACCOUNT_TYPES = [
        'Savings Account (SB)','Current Account (CD)','Personal Retail Account (PRA)',
        'Fixed Deposit Receipt (FDR)','Deposit Pension Scheme (DPS)','Others',
    ];

    function labelWrap(label, inner, required = true) {
        const star = required ? '<span class="text-red-500 ml-0.5">*</span>' : '';
        return `<div><label class="block text-xs font-medium text-gray-600 mb-1.5">${label}${star}</label>${inner}</div>`;
    }
    function inputField(name, val, placeholder) {
        return `<input type="text" name="${name}" value="${val}" placeholder="${placeholder}" required class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm">`;
    }
    function fetchGeo(url, params, sel, ph) {
        sel.disabled=true; sel.innerHTML=`<option value="">⏳ Loading…</option>`;
        fetch(`${url}?${new URLSearchParams(params).toString()}`, {headers:{'X-Requested-With':'XMLHttpRequest'}})
            .then(r=>r.json()).then(items=>{
                sel.innerHTML=`<option value="">${ph}</option>`;
                items.forEach(v=>{const o=document.createElement('option');o.value=v;o.textContent=v;sel.appendChild(o);});
            }).catch(()=>{sel.innerHTML=`<option value="">⚠ Error</option>`;}).finally(()=>{sel.disabled=false;});
    }
    function resetSelect(sel, ph){sel.innerHTML=`<option value="">${ph}</option>`;sel.disabled=false;}

    const PAST_VDBS = @json($pastVdbs ?? []);

    function getVdbNames() { 
        const currentInputs = Array.from(document.querySelectorAll('.vdb-name-input'))
                                   .map(el => el.value.trim())
                                   .filter(v => v !== '');
        return [...new Set([...PAST_VDBS, ...currentInputs])];
    }
    function syncVdbNames() {
        const names = getVdbNames();
        document.querySelectorAll('.acct-vdb-name-select').forEach(sel=>{
            const cur=sel.value; sel.innerHTML='<option value="">— Select VDB —</option>';
            names.forEach(n=>{const o=document.createElement('option');o.value=n;o.textContent=n;if(n===cur)o.selected=true;sel.appendChild(o);});
        });
    }

    function addVdbBlock(prefill = null) {
        const idx = vdbIndex++;
        const prefix = `vdb_entries[${idx}]`;
        const vn = prefill?.vdb_name || '', vil = prefill?.village || '';
        const div = prefill?.division || '', dist = prefill?.district || '', thana = prefill?.thana || '', union = prefill?.union || '';

        const block = document.createElement('div');
        block.className = 'vdb-block entry-block bg-gray-50 border border-gray-200 rounded-2xl p-5';
        block.innerHTML = `
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-semibold text-indigo-700 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center vdb-num"></span>VDB Entry</span>
                <button type="button" onclick="removeVdbBlock(this)" class="w-8 h-8 rounded-lg bg-red-50 border border-red-200 text-red-500 hover:bg-red-100 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                ${labelWrap('VDB Name',`<input type="text" name="${prefix}[vdb_name]" value="${vn}" placeholder="Enter VDB name" required class="vdb-name-input w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm">`)}
                ${labelWrap('Village',`<input type="text" name="${prefix}[village]" value="${vil}" placeholder="Enter village name" required class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm">`)}
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                ${labelWrap('Division',`<select name="${prefix}[division]" required class="vdb-division w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm"><option value="">— Division —</option>${DIVISIONS.map(d=>`<option value="${d}" ${d===div?'selected':''}>${d}</option>`).join('')}</select>`)}
                ${labelWrap('District',`<select name="${prefix}[district]" required class="vdb-district w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm"><option value="">${dist||'— District —'}</option></select>`)}
                ${labelWrap('Thana',`<select name="${prefix}[thana]" required class="vdb-thana w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm"><option value="">${thana||'— Thana —'}</option></select>`)}
                ${labelWrap('Union',`<select name="${prefix}[union]" required class="vdb-union w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm"><option value="">${union||'— Union —'}</option></select>`)}
            </div>`;

        const divSel=block.querySelector('.vdb-division'), distSel=block.querySelector('.vdb-district'),
              thanaSel=block.querySelector('.vdb-thana'), unionSel=block.querySelector('.vdb-union');

        // If pre-filling, chain-load the dropdowns
        if(div) {
            fetchGeo(GEO_ROUTES.districts,{division:div},distSel,'— District —');
            if(dist) {
                setTimeout(()=>{
                    distSel.value=dist;
                    fetchGeo(GEO_ROUTES.thanas,{division:div,district:dist},thanaSel,'— Thana —');
                    if(thana){
                        setTimeout(()=>{
                            thanaSel.value=thana;
                            fetchGeo(GEO_ROUTES.unions,{division:div,district:dist,thana:thana},unionSel,'— Union —');
                            if(union) setTimeout(()=>{unionSel.value=union;},800);
                        },800);
                    }
                },800);
            }
        }

        divSel.addEventListener('change',function(){
            resetSelect(distSel,'— District —'); resetSelect(thanaSel,'— Thana —'); resetSelect(unionSel,'— Union —');
            if(this.value) fetchGeo(GEO_ROUTES.districts,{division:this.value},distSel,'— District —');
        });
        distSel.addEventListener('change',function(){
            resetSelect(thanaSel,'— Thana —'); resetSelect(unionSel,'— Union —');
            if(this.value) fetchGeo(GEO_ROUTES.thanas,{division:divSel.value,district:this.value},thanaSel,'— Thana —');
        });
        thanaSel.addEventListener('change',function(){
            resetSelect(unionSel,'— Union —');
            if(this.value) fetchGeo(GEO_ROUTES.unions,{division:divSel.value,district:distSel.value,thana:this.value},unionSel,'— Union —');
        });
        block.querySelector('.vdb-name-input').addEventListener('input', syncVdbNames);

        document.getElementById('vdb-container').appendChild(block);
        reindexVdb(); updateVdbBadge();
    }

    function removeVdbBlock(btn){btn.closest('.vdb-block').remove();reindexVdb();updateVdbBadge();syncVdbNames();}
    function reindexVdb(){document.querySelectorAll('.vdb-block').forEach((b,i)=>{const n=b.querySelector('.vdb-num');if(n)n.textContent=i+1;});}
    function updateVdbBadge(){const c=document.querySelectorAll('.vdb-block').length;document.getElementById('vdb-count-badge').textContent=`${c} added`;}

    function addAccountBlock(prefill = null) {
        const idx = acctIndex++;
        const prefix = `account_informations[${idx}]`;
        const ct = prefill?.created_through || '', ahn = prefill?.account_holder_name || '',
              at = prefill?.account_type || '', ano = prefill?.account_no || '',
              cno = prefill?.card_no || '', vn = prefill?.vdb_name || '',
              appDist = prefill?.app_distribution || false, qrDist = prefill?.qr_distribution || false,
              cd = prefill?.card_distributed || false;

        const block = document.createElement('div');
        block.className = 'acct-block entry-block bg-gray-50 border border-gray-200 rounded-2xl p-5';
        block.innerHTML = `
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-semibold text-violet-700 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-violet-100 text-violet-700 text-xs font-bold flex items-center justify-center acct-num"></span>Account Record</span>
                <button type="button" onclick="removeAcctBlock(this)" class="w-8 h-8 rounded-lg bg-red-50 border border-red-200 text-red-500 hover:bg-red-100 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Created Through <span class="text-red-500">*</span></label>
                    <div class="flex rounded-xl overflow-hidden border border-gray-200">
                        <label class="flex-1 flex items-center justify-center gap-2 py-2.5 cursor-pointer bg-white hover:bg-green-50 transition acct-through-label ${ct==='VDB'?'bg-green-50':''}" data-val="VDB">
                            <input type="radio" name="${prefix}[created_through]" value="VDB" class="acct-through-radio" ${ct==='VDB'?'checked':''} required><span class="text-sm font-medium text-gray-700">VDB</span>
                        </label>
                        <div class="w-px bg-gray-200"></div>
                        <label class="flex-1 flex items-center justify-center gap-2 py-2.5 cursor-pointer bg-white hover:bg-green-50 transition acct-through-label ${ct==='OWN'?'bg-green-50':''}" data-val="OWN">
                            <input type="radio" name="${prefix}[created_through]" value="OWN" class="acct-through-radio" ${ct==='OWN'?'checked':''} required><span class="text-sm font-medium text-gray-700">Own</span>
                        </label>
                    </div>
                </div>
                <div class="acct-vdb-name-wrapper" style="display:${ct==='VDB'?'block':'none'};">
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">VDB Name <span class="text-red-500">*</span></label>
                    <select name="${prefix}[vdb_name]" class="acct-vdb-name-select w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm">
                        <option value="">— Select VDB —</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                ${labelWrap('Account Holder Name',`<input type="text" name="${prefix}[account_holder_name]" value="${ahn}" placeholder="Full name" required class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm">`)}
                <div><label class="block text-xs font-medium text-gray-600 mb-1.5">Account Type <span class="text-red-500">*</span></label>
                    <select name="${prefix}[account_type]" required class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm">
                        <option value="">— Select type —</option>
                        ${ACCOUNT_TYPES.map(t=>`<option value="${t}" ${t===at?'selected':''}>${t}</option>`).join('')}
                    </select></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                ${labelWrap('Account No.',`<input type="text" name="${prefix}[account_no]" value="${ano}" placeholder="Account number" required class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm">`)}
                <div class="acct-card-no-wrapper" style="display:${cd?'block':'none'};">
                    ${labelWrap('Card No.',`<input type="text" name="${prefix}[card_no]" value="${cno}" placeholder="Card number" ${cd?'required':''} class="acct-card-no-input w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-gray-800 text-sm">`)}
                </div>
            </div>
            <div class="flex flex-wrap gap-6">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="hidden" name="${prefix}[card_distributed]" value="0">
                    <input type="checkbox" name="${prefix}[card_distributed]" value="1" ${cd?'checked':''} class="acct-card-distributed-cb w-4 h-4 rounded border-gray-300 text-brand-600">
                    <span class="text-sm font-medium text-gray-700">Card Distributed</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="hidden" name="${prefix}[app_distribution]" value="0">
                    <input type="checkbox" name="${prefix}[app_distribution]" value="1" ${appDist?'checked':''} class="w-4 h-4 rounded border-gray-300 text-brand-600">
                    <span class="text-sm font-medium text-gray-700">App Distribution</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="hidden" name="${prefix}[qr_distribution]" value="0">
                    <input type="checkbox" name="${prefix}[qr_distribution]" value="1" ${qrDist?'checked':''} class="w-4 h-4 rounded border-gray-300 text-brand-600">
                    <span class="text-sm font-medium text-gray-700">QR Distribution</span>
                </label>
            </div>`;

        block.querySelectorAll('.acct-through-radio').forEach(radio=>{
            radio.addEventListener('change',function(){
                const wrap=block.querySelector('.acct-vdb-name-wrapper');
                const sel=block.querySelector('.acct-vdb-name-select');
                if(this.value==='VDB'){wrap.style.display='block';sel.required=true;syncVdbNames();}else{wrap.style.display='none';sel.value='';sel.required=false;}
                block.querySelectorAll('.acct-through-label').forEach(l=>l.classList.toggle('bg-green-50',l.dataset.val===this.value));
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
        reindexAcct(); updateAcctBadge(); syncVdbNames();

        // Restore selected VDB name
        if(vn) setTimeout(()=>{const s=block.querySelector('.acct-vdb-name-select');if(s)s.value=vn;},100);
    }

    function removeAcctBlock(btn){btn.closest('.acct-block').remove();reindexAcct();updateAcctBadge();}
    function reindexAcct(){document.querySelectorAll('.acct-block').forEach((b,i)=>{const n=b.querySelector('.acct-num');if(n)n.textContent=i+1;});}
    function updateAcctBadge(){const c=document.querySelectorAll('.acct-block').length;document.getElementById('acct-count-badge').textContent=`${c} added`;}

    // Pre-populate from existing data
    EXISTING_VDBS.forEach(v => addVdbBlock(v));
    EXISTING_ACCOUNTS.forEach(a => addAccountBlock(a));
    </script>
</body>
</html>
