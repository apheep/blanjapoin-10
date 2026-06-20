<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Urutan Kategori - BlanjaPoin</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @include('partials.head')

    <style>
        body { font-family: 'Poppins', sans-serif; }

        .route-tab {
            transition: all .2s ease;
            white-space: nowrap;
        }
        .route-tab.active {
            background: linear-gradient(to right, #F81611, #F0B100);
            color: #fff;
            box-shadow: 0 4px 14px rgba(240,100,0,.3);
        }

        /* Drag-and-drop card */
        .cat-item {
            cursor: grab;
            user-select: none;
            transition: box-shadow .15s ease, transform .15s ease, opacity .15s ease, background .15s ease;
        }
        .cat-item:active { cursor: grabbing; }
        .cat-item.dragging {
            opacity: .4;
            transform: scale(.97);
            box-shadow: 0 8px 28px rgba(0,0,0,.18);
        }
        .cat-item.drag-over {
            box-shadow: 0 0 0 2px #F0B100;
            background: #fffbeb;
        }
        /* Hidden category */
        .cat-item.cat-hidden {
            opacity: .45;
            background: #f9fafb;
        }
        .cat-item.cat-hidden .cat-name {
            text-decoration: line-through;
            color: #9ca3af;
        }
        .cat-item.cat-hidden .cat-badge {
            background: #e5e7eb;
            color: #9ca3af;
        }
        /* Position badge */
        .cat-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px; height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #F81611, #F0B100);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
            transition: background .15s;
        }

        /* Skeleton loader */
        .skeleton {
            background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
            background-size: 200% 100%;
            animation: shimmer 1.2s infinite;
            border-radius: .5rem;
        }
        @keyframes shimmer { 0%{ background-position: 200% 0 } 100%{ background-position: -200% 0 } }

        /* Saved value pills */
        .saved-pill {
            cursor: pointer;
            transition: all .15s;
        }
        .saved-pill:hover {
            background: #fef3c7;
            border-color: #F0B100;
            color: #92400e;
        }
        .saved-pill.active-pill {
            background: linear-gradient(to right, #F81611, #F0B100);
            color: #fff;
            border-color: transparent;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">

@include('partials.navbar-admin')

<main class="max-w-3xl mx-auto px-4 py-8" style="padding-top: 100px;">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Urutan Kategori</h1>
        <p class="text-sm text-gray-500 mt-1">
            Tentukan urutan tampilan kategori di setiap halaman BlanjaPoin.
            Kamu bisa buat pengaturan berbeda untuk tiap jenis halaman.
        </p>
    </div>

    {{-- Flash message --}}
    <div id="alertBox" class="hidden mb-4 px-4 py-3 rounded-xl text-sm font-medium"></div>

    {{-- ── STEP 1: Pilih jenis halaman ── --}}
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 p-5 mb-4">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Langkah 1 — Pilih Jenis Halaman</p>
        <p class="text-xs text-gray-400 mb-3">Urutan kategori bisa berbeda-beda tergantung halaman yang dibuka pengguna.</p>
        <div class="flex flex-wrap gap-2" id="routeTabs">
            @php
                $routeIcons = [
                    'default'   => 'fa-house',
                    'u'         => 'fa-link',
                    'city'      => 'fa-city',
                    'reg'       => 'fa-map',
                    'poin-tsel' => 'fa-store',
                    'cluster'   => 'fa-layer-group',
                ];
                $routeUrls = [
                    'default'   => '/',
                    'u'         => '/u/...',
                    'city'      => '/city/...',
                    'reg'       => '/reg/...',
                    'poin-tsel' => '/poin-tsel/...',
                    'cluster'   => '/cluster/...',
                ];
            @endphp
            @foreach($routeTypes as $key => $meta)
                <button
                    class="route-tab flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium border border-gray-200 text-gray-600 hover:border-orange-300"
                    data-route="{{ $key }}"
                    data-placeholder="{{ $meta['placeholder'] }}"
                    onclick="selectRouteType('{{ $key }}', '{{ $meta['placeholder'] }}')"
                >
                    <i class="fa-solid {{ $routeIcons[$key] ?? 'fa-file' }} text-xs opacity-70"></i>
                    {{ $meta['label'] }}
                    <span class="text-xs opacity-50 font-mono">{{ $routeUrls[$key] ?? '' }}</span>
                </button>
            @endforeach
        </div>
    </div>

    {{-- ── STEP 2: Link spesifik (opsional, hidden untuk 'default') ── --}}
    <div id="specificPathPanel" class="hidden bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 p-5 mb-4">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Langkah 2 — Untuk Link Tertentu? <span class="normal-case font-normal">(opsional)</span></p>
        <p class="text-xs text-gray-400 mb-3">
            Kalau <strong class="text-gray-600">dikosongkan</strong>, urutan ini berlaku untuk <strong class="text-gray-600">semua</strong> link jenis ini.
            Kalau diisi, hanya berlaku untuk link yang spesifik itu saja.
        </p>

        <div class="flex gap-2 items-center">
            <span id="routePrefix" class="text-sm text-gray-400 font-mono shrink-0 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200"></span>
            <input
                id="routeValueInput"
                type="text"
                placeholder="Kosongkan = berlaku untuk semua"
                class="flex-1 px-3 py-2 text-sm rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-300"
                oninput="onValueInput()"
                onkeydown="if(event.key==='Enter') loadEditor()"
            />
            <button
                onclick="loadEditor()"
                class="px-4 py-2 text-sm font-semibold rounded-lg bg-gray-800 text-white hover:bg-gray-700 transition shrink-0"
            >Tampilkan</button>
        </div>

        {{-- Saved specific paths --}}
        <div id="savedValuesRow" class="hidden mt-3 pt-3 border-t border-gray-100">
            <p class="text-xs text-gray-400 mb-2">Pengaturan yang sudah kamu buat untuk jenis halaman ini:</p>
            <div id="savedValuesPills" class="flex flex-wrap gap-2"></div>
        </div>
    </div>

    {{-- ── STEP 3: Editor drag-and-drop ── --}}
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 p-5" id="editorPanel">

        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Langkah 3 — Susun Urutan Kategori</p>
                <p class="text-xs text-gray-500 mt-0.5" id="editorSubtitle">Pilih jenis halaman di atas untuk mulai mengatur.</p>
            </div>
            <div id="editorActions" class="hidden flex gap-2">
                <button
                    onclick="resetScope()"
                    class="px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-200 text-gray-600 hover:bg-orange-50 hover:border-orange-300 hover:text-orange-600 transition"
                    title="Hapus pengaturan ini dan kembali ke urutan default / pengaturan yang lebih umum."
                >↺ Reset ke Default</button>
                <button
                    onclick="saveOrder()"
                    id="saveBtn"
                    class="px-4 py-1.5 text-xs font-semibold rounded-lg bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white shadow hover:opacity-90 transition"
                >Simpan</button>
            </div>
        </div>

        {{-- Inheritance notice --}}
        <div id="inheritanceNotice" class="hidden mb-3 px-4 py-3 rounded-xl bg-amber-50 ring-1 ring-amber-200 text-xs text-amber-800 leading-relaxed">
            <p class="font-semibold mb-0.5"><i class="fa-solid fa-circle-info mr-1.5"></i>Urutan ini diambil dari pengaturan umum</p>
            <p id="inheritanceText" class="opacity-80"></p>
        </div>

        {{-- Skeleton --}}
        <div id="skeletonLoader" class="hidden space-y-3">
            @for($i = 0; $i < 6; $i++)
                <div class="h-14 skeleton"></div>
            @endfor
        </div>

        {{-- Empty state --}}
        <div id="emptyState" class="py-12 text-center text-gray-400">
            <i class="fa-solid fa-arrow-up-wide-short text-3xl mb-3 opacity-30"></i>
            <p class="text-sm">Pilih jenis halaman di atas untuk mulai mengatur urutannya.</p>
        </div>

        {{-- Usage hint (shown when editor is loaded) --}}
        <div id="dragHint" class="hidden mb-3 px-4 py-3 rounded-xl bg-blue-50 ring-1 ring-blue-100 text-xs text-blue-700 flex flex-wrap gap-x-5 gap-y-1">
            <span><i class="fa-solid fa-up-down mr-1.5 opacity-70"></i><strong>Seret</strong> kartu naik/turun untuk mengubah urutan</span>
            <span><i class="fa-solid fa-eye-slash mr-1.5 opacity-70"></i>Klik <strong>Sembunyikan</strong> agar kategori tidak muncul di halaman</span>
            <span><i class="fa-solid fa-eye mr-1.5 opacity-70"></i>Klik <strong>Tampilkan</strong> untuk memunculkan kembali kategori yang disembunyikan</span>
        </div>

        {{-- Drag list --}}
        <ul id="categoryList" class="space-y-3"></ul>
    </div>

</main>

<script>
    const ROUTE_TYPES = @json($routeTypes);
    const CSRF        = document.querySelector('meta[name="csrf-token"]').content;

    let currentRoute = null;       // e.g. 'u'
    let currentValue = '';         // e.g. 'sector1' or ''
    let currentCategories = [];
    let draggedEl = null;

    // ── Alert ─────────────────────────────────────────────────────────────────
    function showAlert(msg, type = 'success') {
        const box = document.getElementById('alertBox');
        box.textContent = msg;
        box.className = `mb-4 px-4 py-3 rounded-xl text-sm font-medium ${
            type === 'success'
                ? 'bg-green-50 text-green-700 ring-1 ring-green-200'
                : 'bg-red-50 text-red-700 ring-1 ring-red-200'
        }`;
        box.classList.remove('hidden');
        setTimeout(() => box.classList.add('hidden'), 4000);
    }

    // ── Step 1: Select route type ─────────────────────────────────────────────
    function selectRouteType(routeKey, placeholder) {
        currentRoute = routeKey;
        currentValue = '';

        // Tab styling
        document.querySelectorAll('.route-tab').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.route === routeKey);
        });

        const specificPanel = document.getElementById('specificPathPanel');
        const valueInput    = document.getElementById('routeValueInput');

        if (routeKey === 'default') {
            specificPanel.classList.add('hidden');
            loadEditor(); // default has no specific value, load immediately
        } else {
            specificPanel.classList.remove('hidden');
            document.getElementById('routePrefix').textContent = `/${routeKey}/`;
            valueInput.placeholder = placeholder ? `Contoh: ${placeholder}   (kosongkan = semua /${routeKey}/...)` : `Kosongkan = berlaku untuk semua /${routeKey}/...`;
            valueInput.value = '';

            loadSavedValues(routeKey);
            loadEditor(); // load generic scope immediately
        }
    }

    function onValueInput() {
        // Clear active pill when user types manually
        document.querySelectorAll('.saved-pill').forEach(p => p.classList.remove('active-pill'));
    }

    // ── Step 2: Load saved values pills ──────────────────────────────────────
    function loadSavedValues(routeKey) {
        fetch(`/api/category-order/${routeKey}/saved-values`, { headers: { 'X-CSRF-TOKEN': CSRF } })
            .then(r => r.json())
            .then(values => {
                const row   = document.getElementById('savedValuesRow');
                const pills = document.getElementById('savedValuesPills');
                pills.innerHTML = '';

                if (!values || values.length === 0) {
                    row.classList.add('hidden');
                    return;
                }

                row.classList.remove('hidden');
                values.forEach(val => {
                    const label = val === '' ? `Semua /${routeKey}/... (umum)` : `/${routeKey}/${val}`;
                    const pill  = document.createElement('button');
                    pill.className = 'saved-pill px-3 py-1 text-xs rounded-full border border-gray-200 text-gray-600 font-mono';
                    pill.textContent = label;
                    pill.dataset.value = val;
                    pill.onclick = () => pickSavedValue(val);
                    pills.appendChild(pill);
                });
            })
            .catch(() => {});
    }

    function pickSavedValue(val) {
        document.getElementById('routeValueInput').value = val;
        document.querySelectorAll('.saved-pill').forEach(p => {
            p.classList.toggle('active-pill', p.dataset.value === val);
        });
        loadEditor();
    }

    // ── Load editor ───────────────────────────────────────────────────────────
    function loadEditor() {
        if (!currentRoute) return;

        currentValue = currentRoute === 'default'
            ? ''
            : (document.getElementById('routeValueInput')?.value.trim() ?? '');

        // Update subtitle
        const scopeLabel = buildScopeLabel();
        document.getElementById('editorSubtitle').textContent = `Sedang menampilkan urutan untuk: ${scopeLabel}`;

        // Show skeleton
        document.getElementById('emptyState').classList.add('hidden');
        document.getElementById('categoryList').innerHTML = '';
        document.getElementById('skeletonLoader').classList.remove('hidden');
        document.getElementById('dragHint').classList.add('hidden');
        document.getElementById('inheritanceNotice').classList.add('hidden');
        document.getElementById('editorActions').classList.add('hidden');

        const url = `/api/category-order/${currentRoute}?value=${encodeURIComponent(currentValue)}`;

        fetch(url, { headers: { 'X-CSRF-TOKEN': CSRF } })
            .then(r => r.json())
            .then(data => {
                const categories = Array.isArray(data) ? data : (data.categories ?? []);
                const inherited  = data.inherited ?? false;
                currentCategories = categories;
                document.getElementById('skeletonLoader').classList.add('hidden');
                document.getElementById('editorActions').classList.remove('hidden');
                renderList(categories);
                document.getElementById('dragHint').classList.remove('hidden');
                showInheritanceNotice(inherited);
            })
            .catch(() => {
                document.getElementById('skeletonLoader').classList.add('hidden');
                showAlert('Gagal memuat data. Coba refresh halaman ini.', 'error');
            });
    }

    function buildScopeLabel() {
        if (currentRoute === 'default') return 'Halaman Utama (berlaku umum)';
        if (currentValue === '') return `Semua halaman /${currentRoute}/... (berlaku umum)`;
        return `/${currentRoute}/${currentValue} (spesifik)`;
    }

    function showInheritanceNotice(inherited) {
        const notice = document.getElementById('inheritanceNotice');
        const text   = document.getElementById('inheritanceText');

        if (currentRoute === 'default' || !inherited) {
            notice.classList.add('hidden');
            return;
        }

        if (currentValue === '') {
            text.textContent =
                `Belum ada pengaturan khusus yang tersimpan untuk halaman /${currentRoute}/... Urutan di bawah ini adalah urutan default. ` +
                `Susun sesuai keinginan, lalu klik Simpan — pengaturan ini akan berlaku untuk semua halaman /${currentRoute}/... yang belum punya pengaturan sendiri.`;
        } else {
            text.textContent =
                `Halaman "/${currentRoute}/${currentValue}" belum punya pengaturan sendiri. Urutan di bawah ini adalah urutan default. ` +
                `Kalau sudah kamu susun sesuai keinginan, klik Simpan — pengaturan ini akan disimpan khusus untuk halaman tersebut.`;
        }
        notice.classList.remove('hidden');
    }

    // ── Render list ───────────────────────────────────────────────────────────
    function renderList(categories) {
        const list = document.getElementById('categoryList');
        list.innerHTML = '';
        categories.forEach((cat, idx) => list.appendChild(createItem(cat, idx)));
        initDragDrop();
    }

    function createItem(cat, idx) {
        const li = document.createElement('li');
        const isHidden = !cat.is_visible;
        li.className = 'cat-item flex items-center gap-3 bg-white rounded-xl px-4 py-3 ring-1 ring-gray-100' + (isHidden ? ' cat-hidden' : '');
        li.dataset.key = cat.key;
        li.draggable = true;

        const badgeNum  = isHidden ? '—' : (idx + 1);
        const hideLabel = isHidden ? 'Tampilkan' : 'Sembunyikan';
        const hideIcon  = isHidden ? 'fa-eye' : 'fa-eye-slash';
        const hideColor = isHidden ? 'text-green-600 hover:text-green-700' : 'text-gray-400 hover:text-red-500';

        li.innerHTML = `
            <span class="cat-badge">${badgeNum}</span>
            <span class="text-gray-300 cursor-grab shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <circle cx="9" cy="5" r="1.5"/><circle cx="15" cy="5" r="1.5"/>
                    <circle cx="9" cy="12" r="1.5"/><circle cx="15" cy="12" r="1.5"/>
                    <circle cx="9" cy="19" r="1.5"/><circle cx="15" cy="19" r="1.5"/>
                </svg>
            </span>
            <span class="text-xl leading-none shrink-0">${cat.emoji}</span>
            <span class="cat-name flex-1 text-sm font-semibold text-gray-800">${cat.label}</span>
            <button
                onclick="toggleVisibility('${cat.key}', this)"
                class="${hideColor} text-xs transition shrink-0 flex items-center gap-1"
                title="${hideLabel}"
            >
                <i class="fa-solid ${hideIcon}"></i>
                <span class="hidden sm:inline">${hideLabel}</span>
            </button>
        `;
        return li;
    }

    function toggleVisibility(key, btn) {
        const cat = currentCategories.find(c => c.key === key);
        if (!cat) return;
        cat.is_visible = !cat.is_visible;
        // Re-render to update numbering and styles
        syncCurrentOrder();
        renderList(currentCategories);
    }

    // Re-number badges after drag
    function refreshBadges() {
        const items = document.querySelectorAll('#categoryList .cat-item');
        let visibleIdx = 1;
        items.forEach(li => {
            const badge = li.querySelector('.cat-badge');
            if (!badge) return;
            const isHidden = li.classList.contains('cat-hidden');
            badge.textContent = isHidden ? '—' : visibleIdx++;
        });
    }

    // ── Drag & Drop ───────────────────────────────────────────────────────────
    function initDragDrop() {
        document.querySelectorAll('.cat-item').forEach(item => {
            item.addEventListener('dragstart', onDragStart);
            item.addEventListener('dragend',   onDragEnd);
            item.addEventListener('dragover',  onDragOver);
            item.addEventListener('dragleave', onDragLeave);
            item.addEventListener('drop',      onDrop);
        });
    }

    function onDragStart(e) {
        draggedEl = this;
        this.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
    }
    function onDragEnd() {
        this.classList.remove('dragging');
        document.querySelectorAll('.cat-item').forEach(el => el.classList.remove('drag-over'));
        syncCurrentOrder();
        refreshBadges();
    }
    function onDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        if (draggedEl && this !== draggedEl) this.classList.add('drag-over');
    }
    function onDragLeave() { this.classList.remove('drag-over'); }
    function onDrop(e) {
        e.preventDefault();
        this.classList.remove('drag-over');
        if (!draggedEl || draggedEl === this) return;
        const list     = document.getElementById('categoryList');
        const allItems = Array.from(list.querySelectorAll('.cat-item'));
        const from     = allItems.indexOf(draggedEl);
        const to       = allItems.indexOf(this);
        list.insertBefore(draggedEl, from < to ? this.nextSibling : this);
    }

    function syncCurrentOrder() {
        const items    = document.querySelectorAll('#categoryList .cat-item');
        const newOrder = [];
        items.forEach(item => {
            const cat = currentCategories.find(c => c.key === item.dataset.key);
            if (cat) newOrder.push(cat);
        });
        currentCategories = newOrder;
    }

    // ── Save ──────────────────────────────────────────────────────────────────
    function saveOrder() {
        if (!currentRoute) return;
        syncCurrentOrder();

        const btn = document.getElementById('saveBtn');
        btn.disabled = true;
        btn.textContent = 'Menyimpan...';

        fetch('/category-order/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({
                route_type:  currentRoute,
                route_value: currentValue,
                categories:  currentCategories.map(c => ({ key: c.key, is_visible: c.is_visible })),
            }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                document.getElementById('inheritanceNotice').classList.add('hidden');
                // Refresh saved value pills
                if (currentRoute !== 'default') loadSavedValues(currentRoute);
            } else {
                showAlert(data.error || 'Gagal menyimpan.', 'error');
            }
        })
        .catch(() => showAlert('Gagal menyimpan. Coba lagi atau refresh halaman.', 'error'))
        .finally(() => { btn.disabled = false; btn.textContent = 'Simpan'; });
    }

    // ── Reset ─────────────────────────────────────────────────────────────────
    function resetScope() {
        if (!currentRoute) return;
        const scope = buildScopeLabel();
        if (!confirm(`Hapus pengaturan khusus untuk "${scope}"?\n\nUrutan kategorinya akan otomatis mengikuti pengaturan yang lebih umum (atau default jika tidak ada).`)) return;

        fetch('/category-order/reset', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ route_type: currentRoute, route_value: currentValue }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                loadEditor();
                if (currentRoute !== 'default') loadSavedValues(currentRoute);
            } else {
                showAlert(data.error || 'Gagal reset.', 'error');
            }
        })
        .catch(() => showAlert('Gagal menghapus pengaturan. Coba lagi.', 'error'));
    }

    // Auto-select first tab on page load
    document.addEventListener('DOMContentLoaded', () => {
        const first = document.querySelector('.route-tab');
        if (first) first.click();
    });
</script>

</body>
</html>
