<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Management - blanjapoin.id</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <style>
    body{font-family:'Poppins',sans-serif;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;text-rendering:optimizeLegibility;letter-spacing:-0.01em}
    .chip{display:inline-flex;align-items:center;gap:.4rem;border-radius:9999px;padding:.25rem .6rem;font-size:.75rem;font-weight:600}
  </style>
</head>
<body class="min-h-screen bg-white font-poppins">
  <!-- Navbar -->
  <nav id="navbar" class="sticky top-0 z-20 bg-white transition-shadow duration-300 w-full">
    <div class="mx-auto max-w-7xl px-2 sm:px-4 md:px-6 lg:px-8 py-4 md:py-5 lg:py-6 relative">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-6">
          <img src="/logo.png" alt="BlanjaPoin" class="h-10 md:h-12 lg:h-14 w-auto" />
        </div>
        <div class="hidden md:flex items-center gap-6 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
          @if(Auth::check() && Auth::user()->can_approve == 1)
            <a href="{{ route('admin') }}" class="text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] bg-clip-text text-transparent hover:opacity-80 transition-opacity">Home</a>
            <a href="{{ route('approval') }}" class="text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] bg-clip-text text-transparent hover:opacity-80 transition-opacity">Approval</a>
            <a href="{{ route('user.management') }}" class="text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] bg-clip-text text-transparent hover:opacity-80 transition-opacity">User Management</a>
          @endif
        </div>
        <div class="relative">
          <button onclick="toggleUserDropdown()" id="userDropdownBtn" class="inline-flex items-center gap-1.5 md:gap-2 rounded-xl md:rounded-2xl bg-gradient-to-r from-[#FF3B30] via-[#FF6B2C] to-[#FF9F0A] px-4 md:px-6 py-2 md:py-2.5 text-xs md:text-sm font-semibold text-white shadow-lg shadow-orange-300/50 drop-shadow-lg ring-1 ring-white/30 transition-all hover:shadow-xl hover:shadow-orange-400/50 hover:drop-shadow-xl hover:scale-105 active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5 md:h-4 md:w-4 opacity-95">
              <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5Z"/>
            </svg>
            <span class="tracking-tight">{{ Auth::user()->username }}</span>
            <svg id="userDropdownArrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3 w-3 md:h-3.5 md:w-3.5 opacity-95 transition-transform duration-300">
              <path d="M7 10l5 5 5-5z"/>
            </svg>
          </button>
          <div id="userDropdown" class="absolute right-0 mt-2 w-48 rounded-xl bg-white shadow-xl ring-1 ring-neutral-200 overflow-hidden opacity-0 invisible scale-95 origin-top-right transition-all duration-300 ease-out z-50 backdrop-blur-sm">
            <div class="py-1">
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-3 text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                    <path fill-rule="evenodd" d="M7.5 3.75A1.5 1.5 0 006 5.25v13.5a1.5 1.5 0 001.5 1.5h6a1.5 1.5 0 001.5-1.5V15a.75.75 0 011.5 0v3.75a3 3 0 01-3 3h-6a3 3 0 01-3-3V5.25a3 3 0 013-3h6a3 3 0 013 3V9A.75.75 0 0115 9V5.25a1.5 1.5 0 00-1.5-1.5h-6zm10.72 4.72a.75.75 0 011.06 0l3 3a.75.75 0 010 1.06l-3 3a.75.75 0 11-1.06-1.06l1.72-1.72H9a.75.75 0 010-1.5h10.94l-1.72-1.72a.75.75 0 010-1.06z" clip-rule="evenodd" />
                  </svg>
                  <span>Logout</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <main class="max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between gap-4 flex-wrap">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
        <p class="text-gray-600 mt-1">Kelola role, status, dan keamanan akun admin.</p>
      </div>
      <div class="flex items-center gap-2">
        <button id="btn-open-create" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] ring-2 ring-orange-200/60 shadow-sm hover:shadow-md active:scale-[0.98] transition-all">
          <i class="fa-solid fa-user-plus text-[12px]"></i> Buat Akun Baru
        </button>
      </div>
    </div>

    <!-- KPI Cards -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
      <div class="rounded-2xl border bg-white p-4 shadow-sm">
        <div class="text-xs text-gray-500">Total User</div>
        <div class="mt-1 text-2xl font-semibold">128</div>
      </div>
      <div class="rounded-2xl border bg-white p-4 shadow-sm">
        <div class="text-xs text-gray-500">Admin Aktif</div>
        <div class="mt-1 text-2xl font-semibold">64</div>
      </div>
      <div class="rounded-2xl border bg-white p-4 shadow-sm">
        <div class="text-xs text-gray-500">User Aktif</div>
        <div class="mt-1 text-2xl font-semibold">7</div>
      </div>
    </section>

    <!-- Toolbar -->
    <section class="mt-6 bg-white border rounded-2xl shadow-sm p-4">
      <div class="flex flex-col lg:flex-row gap-3 lg:items-center lg:justify-between">
        <div class="flex flex-1 gap-2">
          <div class="relative flex-1 min-w-[220px]">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" placeholder="Cari nama atau email…" class="w-full pl-9 pr-3 py-2.5 rounded-xl border focus:ring-orange-400" />
        </div>  
      </div>
    </section>

    <!-- Bulk bar (muncul saat ada checkbox yang dicentang) -->
    <div id="bulkbar" class="hidden mt-4 bg-slate-800 text-slate-100 rounded-2xl p-3 flex items-center justify-between">
      <div class="flex items-center gap-2 text-sm"><span id="bulk-count" class="font-semibold">0</span> dipilih</div>
      <div class="flex items-center gap-2">
        <button class="chip bg-emerald-600/15 text-emerald-300 border border-emerald-400/20"><i class="fa-solid fa-user-check text-[12px]"></i> Activate</button>
        <button class="chip bg-amber-600/15 text-amber-300 border border-amber-400/20"><i class="fa-solid fa-lock text-[12px]"></i> Suspend</button>
        <button class="chip bg-rose-600/15 text-rose-300 border border-rose-400/20"><i class="fa-solid fa-trash text-[12px]"></i> Delete</button>
      </div>
    </div>

    <!-- Table -->
    <section class="mt-4 bg-white border rounded-2xl shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-collapse align-middle">
          <colgroup>
            <col style="width:48px" /> <!-- No -->
            <col /> <!-- Nama auto -->
            <col /> <!-- Email auto -->
            <col /> <!-- Can Approve -->
            <col /> <!-- Aksi -->
          </colgroup>
          <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
            <tr class="text-left">
              <th class="px-2 py-2 text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">No</th>
              <th class="px-3 py-2 text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Nama</th>
              <th class="px-3 py-2 text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Email</th>
              <th class="px-3 py-2 text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Can Approve</th>
              <th class="px-3 py-2 text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100 text-sm">
            <!-- Row sample 1 -->
            <tr class="hover:bg-gray-50">
              <td class="px-2 py-2 text-left text-gray-800 font-semibold">1</td>
            
              <td class="px-3 py-2">
                <div class="font-medium text-gray-900">Budi Santoso</div>
                <div class="text-xs text-gray-500">Terakhir login 2025-11-03 14:10</div>
              </td>
              <td class="px-3 py-2 text-gray-700">budi@blanjapoin.id</td>
              <td class="px-3 py-2">
                <!-- Toggle Can Approve -->
                <label class="relative inline-flex items-center cursor-pointer" title="Toggle Can Approve">
                  <input type="checkbox" value="" class="sr-only peer">
                  <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-0 peer-focus:ring-transparent rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600 hover:peer-checked:bg-green-700"></div>
                </label>
              </td>
              <td class="px-3 py-2">
                <div class="flex items-center gap-2">
                  <button onclick="openEditMerchandise(1, {nama:'Gourmet Gift Box', SKB:'Premium gift package', redeem_point:'1000', stock:50, start_date:'01/01/2025', end_date:'31/12/2025', cta:'https://example.com/giftbox'})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border text-blue-600 hover:bg-blue-50"><i class="fas fa-edit text-sm"></i></button>
                  <button onclick="showDeleteConfirmation('Merchandise','Gourmet Gift Box','1','Merchandise Gourmet Gift Box akan dihapus dari sistem')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border text-red-600 hover:bg-red-50"><i class="fas fa-trash text-sm"></i></button>
                </div>
              </td> 
            </tr>
            <!-- Row sample 2 -->
            <tr class="hover:bg-gray-50">
              <td class="px-2 py-2 text-left text-gray-800 font-semibold">2</td>
              <td class="px-3 py-2">
                <div class="font-medium text-gray-900">Siti Aminah</div>
                <div class="text-xs text-gray-500">Terakhir login 2025-11-01 09:24</div>
              </td>
              <td class="px-3 py-2 text-gray-700">siti@blanjapoin.id</td>
              <td class="px-3 py-2">
               <!-- Toggle Can Approve -->
               <label class="relative inline-flex items-center cursor-pointer" title="Toggle Can Approve">
                  <input type="checkbox" value="" class="sr-only peer">
                  <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-0 peer-focus:ring-transparent rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600 hover:peer-checked:bg-green-700"></div>
                </label>
              </td>
              <td class="px-3 py-2">
                <div class="flex items-center gap-2">
                  <button onclick="openEditMerchandise(1, {nama:'Gourmet Gift Box', SKB:'Premium gift package', redeem_point:'1000', stock:50, start_date:'01/01/2025', end_date:'31/12/2025', cta:'https://example.com/giftbox'})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border text-blue-600 hover:bg-blue-50"><i class="fas fa-edit text-sm"></i></button>
                  <button onclick="showDeleteConfirmation('Merchandise','Gourmet Gift Box','1','Merchandise Gourmet Gift Box akan dihapus dari sistem')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border text-red-600 hover:bg-red-50"><i class="fas fa-trash text-sm"></i></button>
                </div>
              </td> 
            </tr>
            <!-- Row sample 3 -->
            <tr class="hover:bg-gray-50">
              <td class="px-2 py-2 text-left text-gray-800 font-semibold">3</td>
              <td class="px-3 py-2">
                <div class="font-medium text-gray-900">Andre Pratama</div>
                <div class="text-xs text-gray-500">Terakhir login 2025-10-28 20:03</div>
              </td>
              <td class="px-3 py-2 text-gray-700">andre@blanjapoin.id</td>
              <td class="px-3 py-2">
                <!-- Toggle Can Approve -->
                <label class="relative inline-flex items-center cursor-pointer" title="Toggle Can Approve">
                  <input type="checkbox" value="" class="sr-only peer">
                  <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-0 peer-focus:ring-transparent rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600 hover:peer-checked:bg-green-700"></div>
                </label>
              </td>
              <td class="px-3 py-2">
                <div class="flex items-center gap-2">
                  <button onclick="openEditMerchandise(1, {nama:'Gourmet Gift Box', SKB:'Premium gift package', redeem_point:'1000', stock:50, start_date:'01/01/2025', end_date:'31/12/2025', cta:'https://example.com/giftbox'})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border text-blue-600 hover:bg-blue-50"><i class="fas fa-edit text-sm"></i></button>
                  <button onclick="showDeleteConfirmation('Merchandise','Gourmet Gift Box','1','Merchandise Gourmet Gift Box akan dihapus dari sistem')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border text-red-600 hover:bg-red-50"><i class="fas fa-trash text-sm"></i></button>
                </div>
              </td> 
            </tr>
          </tbody>
        </table>
      </div>
      <!-- footer -->
      <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-t">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
          <div class="text-sm text-gray-700">Showing <span class="font-semibold">1</span> to <span class="font-semibold">10</span> of <span class="font-semibold">128</span> users</div>
          <nav class="flex items-center gap-1" aria-label="Pagination">
            <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border bg-white text-sm font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all"><i class="fas fa-chevron-left text-xs"></i></a>
            <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border-2 border-orange-500 bg-gradient-to-r from-orange-50 to-red-50 text-sm font-semibold text-orange-600 shadow-sm">1</a>
            <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border bg-white text-sm font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all">2</a>
            <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border bg-white text-sm font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all">3</a>
            <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border bg-white text-sm font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all"><i class="fas fa-chevron-right text-xs"></i></a>
          </nav>
        </div>
      </div>
    </section>
  </main>

  <!-- Modal: Create Admin -->
  <div id="modal-create" class="hidden fixed inset-0 z-50 items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/50"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">
      <div class="flex items-center justify-between px-6 py-4 border-b">
        <h3 class="text-lg font-semibold">Buat Akun Baru</h3>
        <button id="btn-close-create" class="text-gray-500 hover:text-gray-700"><i class="fa-solid fa-xmark text-lg"></i></button>
      </div>
      <div class="p-6 space-y-4 overflow-y-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" class="w-full px-3 py-2 rounded-xl border focus:ring-orange-400" placeholder="Nama" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" class="w-full px-3 py-2 rounded-xl border focus:ring-orange-400" placeholder="email@domain.com" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="text" class="w-full px-3 py-2 rounded-xl border focus:ring-orange-400" placeholder="Password" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <div class="relative inline-block bp-dd" data-default="Admin">
              <input type="hidden" value="Admin" />
              <button type="button" class="bp-btn inline-flex items-center gap-2 w-full justify-between px-3 py-2 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] ring-2 ring-orange-200/60">
                <span class="bp-label">Admin</span>
                <i class="fa-solid fa-chevron-down text-[11px] opacity-90"></i>
              </button>
              <div class="bp-menu hidden absolute right-0 z-40 mt-2 w-full rounded-2xl bg-slate-700 text-slate-100 shadow-xl ring-1 ring-black/10 overflow-hidden">
                <div class="py-1">
                  <button type="button" data-value="User" class="bp-item w-full text-left px-4 py-2 text-sm hover:bg-slate-600">User</button>
                  <button type="button" data-value="Admin" class="bp-item w-full text-left px-4 py-2 text-sm hover:bg-slate-600">Admin</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="flex items-center justify-end gap-2 px-6 py-4 border-t">
        <button class="px-4 py-2 rounded-lg border hover:bg-gray-50" id="btn-cancel-create">Batal</button>
        <button class="px-4 py-2 rounded-lg text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] ring-2 ring-orange-200/60">Simpan</button>
      </div>
    </div>
  </div>

  <!-- Scripts: Dropdown portal + helpers -->
  <script>
    // Bulkbar toggle
    const bulkbar = document.getElementById('bulkbar');
    const bulkCount = document.getElementById('bulk-count');
    const checkAll = document.getElementById('check-all');
    const rowChecks = () => Array.from(document.querySelectorAll('.row-check'));
    const syncBulk = () => {
      const selected = rowChecks().filter(c=>c.checked).length;
      bulkCount.textContent = selected;
      bulkbar.classList.toggle('hidden', selected===0);
    };
    if (checkAll){
      checkAll.addEventListener('change', () => {
        rowChecks().forEach(c=>{c.checked = checkAll.checked});
        syncBulk();
      });
    }
    document.addEventListener('change', (e)=>{
      if(e.target.classList && e.target.classList.contains('row-check')){ syncBulk(); }
    });

    // Modal create
    const modalCreate = document.getElementById('modal-create');
    const openCreate = document.getElementById('btn-open-create');
    const closeCreate = document.getElementById('btn-close-create');
    const cancelCreate = document.getElementById('btn-cancel-create');
    const openModal = (m)=>{ m.classList.remove('hidden'); m.classList.add('flex'); document.body.style.overflow='hidden'; };
    const closeModal = (m)=>{ m.classList.add('hidden'); m.classList.remove('flex'); document.body.style.overflow=''; };
    openCreate?.addEventListener('click',()=>openModal(modalCreate));
    closeCreate?.addEventListener('click',()=>closeModal(modalCreate));
    cancelCreate?.addEventListener('click',()=>closeModal(modalCreate));

    /* ========== Custom Dropdown (portal ke <body>) — FIXED untuk modal ========== */
(function () {
  function setState(root, value) {
    const btn = root.querySelector('.bp-btn');
    const label = root.querySelector('.bp-label');
    const hidden = root.querySelector('input[type="hidden"]');
    if (label) label.textContent = value;
    if (hidden) hidden.value = value;
  }

  function closeAll(except) {
    document.querySelectorAll('.bp-dd').forEach(dd => {
      const menu = dd.__portalMenu;
      if (!menu) return;
      if (except && dd === except) return;
      hideMenu(dd);
    });
  }

  let portal = document.getElementById('bp-dd-portal');
  if (!portal) {
    portal = document.createElement('div');
    portal.id = 'bp-dd-portal';
    portal.style.position = 'relative';
    portal.style.zIndex = 9999; // di atas modal
    document.body.appendChild(portal);
  }

  function showMenu(dd) {
    const btn   = dd.querySelector('.bp-btn');
    const menuT = dd.querySelector('.bp-menu');
    const caret = dd.querySelector('.fa-chevron-down');

    if (!dd.__portalMenu) {
      const clone = menuT.cloneNode(true);
      clone.classList.remove('absolute');
      clone.classList.add('fixed');  // portal -> fixed
      clone.style.top = '0px';
      clone.style.left = '0px';
      clone.style.visibility = 'hidden';
      clone.classList.remove('hidden');
      clone.style.zIndex = '10000';  // pastikan di atas backdrop
      portal.appendChild(clone);

      // Klik item -> set value + tutup
      clone.querySelectorAll('.bp-item').forEach(item => {
        item.addEventListener('click', (e) => {
          e.stopPropagation();
          setState(dd, item.dataset.value);
          hideMenu(dd);
        });
      });

      dd.__portalMenu = clone;
    }

    const menu = dd.__portalMenu;

    // Posisi: untuk elemen FIXED gunakan rect langsung (tanpa scrollY/X)
    const rect = btn.getBoundingClientRect();
    menu.style.visibility = 'hidden';
    menu.classList.remove('hidden');

    // Lebar menu = lebar tombol, mengatasi 'w-full' yang jadi selebar viewport
    menu.style.width = rect.width + 'px';

    const gap = 8;
    const menuW = menu.offsetWidth || rect.width;

    let left = rect.right - menuW;       // rata kanan tombol
    let top  = rect.bottom + gap;        // di bawah tombol

    // Batasi supaya tidak keluar layar
    const vw = document.documentElement.clientWidth;
    left = Math.min(left, vw - menuW - 8);
    left = Math.max(left, 8);

    menu.style.left = left + 'px';
    menu.style.top  = top  + 'px';
    menu.style.visibility = 'visible';

    dd.setAttribute('data-open', 'true');
    btn.setAttribute('aria-expanded', 'true');
    if (caret) caret.classList.add('rotate-180');
  }

  function hideMenu(dd) {
    const btn   = dd.querySelector('.bp-btn');
    const caret = dd.querySelector('.fa-chevron-down');
    const menu  = dd.__portalMenu;
    if (menu) menu.classList.add('hidden');
    dd.removeAttribute('data-open');
    if (btn) btn.setAttribute('aria-expanded', 'false');
    if (caret) caret.classList.remove('rotate-180');
  }

  // Init semua dropdown
  document.querySelectorAll('.bp-dd').forEach(dd => {
    const def  = dd.getAttribute('data-default') || dd.querySelector('input[type="hidden"]')?.value || '';
    setState(dd, def);

    const btn  = dd.querySelector('.bp-btn');
    const tmpl = dd.querySelector('.bp-menu');
    if (tmpl) tmpl.classList.add('hidden');

    btn?.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = dd.getAttribute('data-open') === 'true';
      closeAll(dd);
      if (!isOpen) showMenu(dd); else hideMenu(dd);
    });
  });

  // Global close handlers
  document.addEventListener('click', () => closeAll());
  window.addEventListener('resize', () => closeAll());
  window.addEventListener('scroll', () => closeAll(), true);
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeAll(); });
})();
   
  </script>
  <script>
    // Slide interaction for .bp-toggle (drag to slide) with safe click handling
    (function(){
      const toggles = document.querySelectorAll('.bp-toggle');
      toggles.forEach(label => {
        const input = label.querySelector('input[type="checkbox"]');
        const track = label.querySelector('div.relative');
        const knob  = track?.querySelector('.bp-toggle-knob');
        const fill  = track?.querySelector('.bp-toggle-fill');
        if (!input || !track || !knob || !fill) return;

        let isDragging = false;
        let startedDrag = false;
        let startX = 0;
        let suppressClick = false;

        const computeTravel = () => {
          const rect = track.getBoundingClientRect();
          const knobRect = knob.getBoundingClientRect();
          const knobLeft = parseFloat(getComputedStyle(knob).left) || 0; // left padding
          const travel = Math.max(0, rect.width - knobRect.width - knobLeft * 2);
          return { rect, travel, knobLeft };
        };

        const setVisual = (fraction) => {
          const { travel } = computeTravel();
          const clamped = Math.max(0, Math.min(1, fraction));
          knob.style.transform = `translateX(${Math.round(travel * clamped)}px)`;
          fill.style.transform = `scaleX(${clamped})`;
        };

        const clearVisual = () => {
          knob.style.transform = '';
          fill.style.transform = '';
        };

        const fractionFromClientX = (clientX) => {
          const { rect, travel, knobLeft } = computeTravel();
          const x = Math.max(knobLeft, Math.min(rect.width - knobLeft, clientX - rect.left));
          const relative = x - knobLeft;
          const fraction = travel > 0 ? relative / travel : (input.checked ? 1 : 0);
          return Math.max(0, Math.min(1, fraction));
        };

        const commitState = (fraction) => {
          const next = fraction >= 0.5;
          if (input.checked !== next) {
            input.checked = next;
            input.dispatchEvent(new Event('change', { bubbles: true }));
          }
          clearVisual();
        };

        const onPointerDown = (e) => {
          isDragging = true;
          startedDrag = false;
          startX = e.clientX;
          label.classList.add('select-none');
        };

        const onPointerMove = (e) => {
          if (!isDragging) return;
          const moved = Math.abs(e.clientX - startX);
          if (!startedDrag && moved > 4) {
            startedDrag = true;
            track.setPointerCapture?.(e.pointerId);
          }
          if (startedDrag) {
            const f = fractionFromClientX(e.clientX);
            setVisual(f);
            suppressClick = true; // we are dragging; suppress the click that may follow
          }
        };

        const onPointerUp = (e) => {
          if (!isDragging) return;
          isDragging = false;
          label.classList.remove('select-none');
          if (startedDrag) {
            const f = fractionFromClientX(e.clientX);
            commitState(f);
          } else {
            // no drag: let native label click toggle the checkbox
          }
          startedDrag = false;
        };

        // Suppress the synthetic click after drag only
        track.addEventListener('click', (ev) => {
          if (suppressClick) {
            ev.preventDefault();
            ev.stopPropagation();
            suppressClick = false;
          }
        }, true);

        // Pointer events for drag
        track.addEventListener('pointerdown', onPointerDown);
        window.addEventListener('pointermove', onPointerMove);
        window.addEventListener('pointerup', onPointerUp);
      });
    })();
  </script>
  <script>
    function toggleUserDropdown() {
      const dropdown = document.getElementById('userDropdown');
      const arrow = document.getElementById('userDropdownArrow');
      if (!dropdown) return;
      if (dropdown.classList.contains('opacity-0')) {
        dropdown.classList.remove('opacity-0', 'invisible', 'scale-95');
        dropdown.classList.add('opacity-100', 'visible', 'scale-100');
        if (arrow) arrow.style.transform = 'rotate(180deg)';
      } else {
        dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
        dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
        if (arrow) arrow.style.transform = 'rotate(0deg)';
      }
    }
    document.addEventListener('click', function(event) {
      const userDropdownBtn = document.getElementById('userDropdownBtn');
      const userDropdown = document.getElementById('userDropdown');
      if (userDropdownBtn && userDropdown &&
          !userDropdownBtn.contains(event.target) &&
          !userDropdown.contains(event.target) &&
          !userDropdown.classList.contains('opacity-0')) {
        toggleUserDropdown();
      }
    });
  </script>
</body>
</html>
