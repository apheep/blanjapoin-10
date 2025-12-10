
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
@include('partials.head')
<body class="min-h-screen bg-white font-poppins">
@include('partials.navbar-admin')

<!-- <script>
  const openSidebar = document.getElementById('openSidebar');
  const closeSidebar = document.getElementById('closeSidebar');
  const mobileSidebar = document.getElementById('mobileSidebar');
  const sidebarPanel = document.getElementById('sidebarPanel');

  // Close sidebar when clicking overlay
  mobileSidebar?.addEventListener('click', (e) => {
    if (e.target === mobileSidebar) {
      closeSidebarFunc();
    }
  });

  function openSidebarFunc() {
    if (mobileSidebar && sidebarPanel) {
      mobileSidebar.classList.remove('hidden');
      setTimeout(() => {
        sidebarPanel.classList.remove('translate-x-full');
      }, 10);
    }
  }

  function closeSidebarFunc() {
    if (sidebarPanel && mobileSidebar) {
      sidebarPanel.classList.add('translate-x-full');
      setTimeout(() => {
        mobileSidebar.classList.add('hidden');
      }, 300);
    }
  }

  openSidebar?.addEventListener('click', openSidebarFunc);
  closeSidebar?.addEventListener('click', closeSidebarFunc);

  // Close on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && mobileSidebar && !mobileSidebar.classList.contains('hidden')) {
      closeSidebarFunc();
    }
  });
</script> -->
    <!-- Flash Message Container -->
    @if(session('success'))
        <div data-flash-message="{{ session('success') }}" data-flash-type="success" class="hidden"></div>
    @endif
    @if(session('error'))
        <div data-flash-message="{{ session('error') }}" data-flash-type="error" class="hidden"></div>
    @endif
    @if($errors->any())
        <div data-flash-message="{{ $errors->first() }}" data-flash-type="error" class="hidden"></div>
    @endif

  <main class="max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-8 transform transition-all duration-500 opacity-0 translate-y-3 mt-4">
    <div class="flex items-center justify-between gap-4 flex-wrap">
      <div class="pl-2">
        <h1 class="text-2xl md:text-3xl font-black tracking-tight text-neutral-900">User Management</h1>
      </div>
      <div class="flex items-center gap-2 pr-4">
        <button id="btn-open-import" class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-semibold text-white bg-gradient-to-r from-green-600 to-green-700 shadow-l hover:shadow-md active:scale-[0.98] transition-all">
          <i class="fa-solid fa-file-excel text-[12px]"></i>
          <span class="hidden sm:inline">Import Excel</span>
        </button>
        <button id="btn-open-create" class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] shadow-l hover:shadow-md active:scale-[0.98] transition-all">
          <i class="fa-solid fa-user-plus text-[12px]"></i>
        </button>
      </div>
    </div>

<section class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
  <div class="rounded-2xl border bg-white p-4 shadow-sm">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-xs text-neutral-500">Total User</div>
        <div class="mt-1 text-2xl font-semibold" id="total-users">{{ $totalUsers ?? 0 }}</div>
      </div>
      <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center text-amber-600">
        <i class="fa-solid fa-users"></i>
      </div>
    </div>
  </div>
  <div class="rounded-2xl border bg-white p-4 shadow-sm">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-xs text-neutral-500">Admin Aktif</div>
        <div class="mt-1 text-2xl font-semibold" id="admin-active">{{ $adminActiveCount ?? 0 }}</div>
      </div>
      <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-purple-100 to-violet-100 flex items-center justify-center text-purple-600">
        <i class="fa-solid fa-user-shield"></i>
      </div>
    </div>
  </div>
  <div class="rounded-2xl border bg-white p-4 shadow-sm">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-xs text-neutral-500">User Aktif</div>
        <div class="mt-1 text-2xl font-semibold" id="user-active">{{ $userActiveCount ?? 0 }}</div>
      </div>
      <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-100 to-green-100 flex items-center justify-center text-emerald-600">
        <i class="fa-solid fa-user-check"></i>
      </div>
    </div>
  </div>
  <div class="rounded-2xl border bg-white p-4 shadow-sm">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-xs text-neutral-500">Total Admin</div>
        <div class="mt-1 text-2xl font-semibold" id="total-admin">{{ $adminCount ?? 0 }}</div>
      </div>
      <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-rose-100 to-orange-100 flex items-center justify-center text-rose-600">
        <i class="fa-solid fa-user-tie"></i>
      </div>
    </div>
  </div>
</section>

    <!-- Search -->
    <div class="mt-6">
      <div class="relative max-w-md ">
        <input
        type="text"
        id="search-input"
        placeholder="Cari username…"
        autocomplete="off"
        class="w-full pl-9 pr-3 py-2.5 rounded-full border border-neutral-300 focus:outline-none focus:ring-2 focus:ring-orange-400"
        />
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
      </div>
    </div>

    <!-- Bulk bar (muncul saat ada checkbox yang dicentang) -->
    <div id="bulkbar" class="hidden mt-4 bg-slate-800 text-slate-100 rounded-2xl p-3 flex items-center justify-between">
      <div class="flex items-center gap-2 text-sm"><span id="bulk-count" class="font-semibold">0</span> dipilih</div>
      <div class="flex items-center gap-2 flex-wrap">
        <button onclick="bulkToggleApproval(true)" class="chip bg-emerald-600/15 text-emerald-300 border border-emerald-400/20 hover:bg-emerald-600/25 transition-colors"><i class="fa-solid fa-user-check text-[12px]"></i> Activate</button>
        <button onclick="bulkToggleApproval(false)" class="chip bg-amber-600/15 text-amber-300 border border-amber-400/20 hover:bg-amber-600/25 transition-colors"><i class="fa-solid fa-lock text-[12px]"></i> Suspend</button>
        <button onclick="bulkDelete()" class="chip bg-rose-600/15 text-rose-300 border border-rose-400/20 hover:bg-rose-600/25 transition-colors"><i class="fa-solid fa-trash text-[12px]"></i> Delete</button>
      </div>
    </div>

    <!-- Table -->
    <section class="mt-4 bg-white border rounded-2xl shadow-xl overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-collapse align-middle">
          <colgroup>
            <col style="width:48px" /> <!-- No -->
            <col /> <!-- Username auto -->
            <col /> <!-- Email -->
            <col /> <!-- Role -->
            <col /> <!-- Can Approve -->
            <col style="width:100px" /> <!-- Aksi -->
          </colgroup>
          <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
            <tr class="text-left">
              <th class="px-2 py-2 text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">No</th>
              <th class="px-3 py-2 text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Username</th>
              <th class="px-3 py-2 text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Email</th>
              <th class="px-3 py-2 text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Role</th>
              <th class="px-3 py-2 text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Can Approve</th>
              <th class="px-3 py-2 text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Aksi</th>
            </tr>
          </thead>

          {{-- Server-side rendering of rows (Blade) --}}
          @php
            $isPaginator = $users instanceof \Illuminate\Pagination\LengthAwarePaginator
                        || $users instanceof \Illuminate\Pagination\Paginator;
          @endphp

          <tbody class="bg-white divide-y divide-gray-100 text-sm" id="users-tbody">
            @forelse($users as $user)
              @php
                $rowNum = $isPaginator ? ($users->firstItem() + $loop->index) : $loop->iteration;
                $isCurrentUser = $user->id === Auth::id();
              @endphp
              <tr class="hover:bg-neutral-50 transition-colors" data-user-id="{{ $user->id }}">
                <td class="px-2 py-2 text-left text-gray-800 font-semibold">{{ $rowNum }}</td>
                <td class="px-3 py-2">
                  <div class="font-medium text-gray-900">{{ $user->username }}</div>
                </td>
                <td class="px-3 py-2">
                  <div class="text-sm text-gray-700">{{ $user->email ?? '-' }}</div>
                </td>
                <td class="px-3 py-2">
                  <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                    {{ $user->role }}
                  </span>
                </td>
                <td class="px-3 py-2">
                  <label class="relative inline-flex items-center cursor-pointer" title="Toggle Can Approve" {{ $isCurrentUser ? 'onclick=return false;' : '' }}>
                    <input type="checkbox" data-user-id="{{ $user->id }}" class="sr-only peer toggle-approve" {{ $user->can_approve ? 'checked' : '' }} {{ $isCurrentUser ? 'disabled' : '' }} />
                    <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-0 peer-focus:ring-transparent rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600 hover:peer-checked:bg-green-700"></div>
                  </label>
                </td>
                <td class="px-3 py-2">
                  <div class="flex items-center gap-2">
                    @if($isCurrentUser)
                      <span class="text-xs text-gray-500">Your Account</span>
                    @else
                      <button onclick="deleteUser({{ $user->id }})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-red-200 bg-white text-red-600 ring-1 ring-red-100 hover:bg-red-50 hover:shadow-sm active:scale-95 transition-all"><i class="fas fa-trash text-sm"></i></button>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada data user</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- footer -->
      @php
        $showFrom = $isPaginator ? ($users->firstItem() ?? 0) : ($users->count() ? 1 : 0);
        $showTo   = $isPaginator ? ($users->lastItem() ?? 0) : $users->count();
        $showTotal= $isPaginator ? ($users->total() ?? 0) : $users->count();
      @endphp

      <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-t">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
          <div class="text-sm text-gray-700">Showing <span class="font-semibold" id="showing-from">{{ $showFrom }}</span> to <span class="font-semibold" id="showing-to">{{ $showTo }}</span> of <span class="font-semibold" id="showing-total">{{ $showTotal }}</span> users</div>
          <nav class="flex items-center gap-1" aria-label="Pagination" id="pagination">
            @if($isPaginator && $users->hasPages())
              {{-- Previous Page Link --}}
              @if ($users->onFirstPage())
                <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                  <i class="fas fa-chevron-left"></i>
                </button>
              @else
                <a href="{{ $users->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                  <i class="fas fa-chevron-left"></i>
                </a>
              @endif

              {{-- Pagination Elements --}}
              @php
                $currentPage = $users->currentPage();
                $lastPage = $users->lastPage();
                $startPage = max(1, $currentPage - 2);
                $endPage = min($lastPage, $currentPage + 2);
              @endphp

              @if($startPage > 1)
                <a href="{{ $users->url(1) }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">1</a>
                @if($startPage > 2)
                  <span class="px-2 text-gray-400">...</span>
                @endif
              @endif

              @for ($page = $startPage; $page <= $endPage; $page++)
                @if ($page == $currentPage)
                  <button disabled class="px-3 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg">
                    {{ $page }}
                  </button>
                @else
                  <a href="{{ $users->url($page) }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    {{ $page }}
                  </a>
                @endif
              @endfor

              @if($endPage < $lastPage)
                @if($endPage < $lastPage - 1)
                  <span class="px-2 text-gray-400">...</span>
                @endif
                <a href="{{ $users->url($lastPage) }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">{{ $lastPage }}</a>
              @endif

              {{-- Next Page Link --}}
              @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                  <i class="fas fa-chevron-right"></i>
                </a>
              @else
                <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                  <i class="fas fa-chevron-right"></i>
                </button>
              @endif
            @endif
          </nav>
        </div>
      </div>
    </section>
  </main>

  <!-- Modal: Import Excel -->
  <div id="modal-import" class="hidden fixed inset-0 z-50 items-center justify-center p-4">
    <div id="modal-import-overlay" class="fixed inset-0 bg-black/50 opacity-0 transition-opacity duration-300"></div>
    <div id="modal-import-panel" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden opacity-0 scale-95 translate-y-4 transition-all duration-300">
      <div class="flex items-center justify-between px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-gray-100">
        <h3 class="text-lg font-semibold text-neutral-900">Import User dari Excel</h3>
        <button id="btn-close-import" class="text-gray-500 hover:text-gray-700"><i class="fa-solid fa-xmark text-lg"></i></button>
      </div>
      <div class="p-6 space-y-4 overflow-y-auto">
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
          <div class="flex items-start gap-3">
            <i class="fa-solid fa-info-circle text-blue-600 mt-0.5"></i>
            <div class="flex-1">
              <h4 class="text-sm font-semibold text-blue-900 mb-1">Format Excel yang Diperlukan (Data harus ada di mynami)</h4>
              <p class="text-xs text-blue-700 mb-2">File Excel harus memiliki header pada baris pertama dengan kolom berikut (urutan bebas):</p>
              <ul class="text-xs text-blue-700 list-disc list-inside space-y-1">
                <li><strong>no_hp</strong> - Nomor HP </li>
                <li><strong>username</strong> - Username </li>
                <li><strong>email</strong> - Email</li>
                <li><strong>role</strong> - Isi dengan 'admin' </li>
                <li><strong>can_approve</strong> - Isi dengan '1' atau '0'</li>
                <li><strong>password</strong> - Password </li>
              </ul>
            </div>
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File Excel (.xlsx, .xls)</label>
          <div class="relative">
            <input type="file" id="import-file-input" accept=".xlsx,.xls" class="hidden" />
            <button type="button" id="btn-select-file" class="w-full px-4 py-3 rounded-xl border-2 border-dashed border-gray-300 hover:border-blue-400 hover:bg-blue-50 transition-colors text-left">
              <div class="flex items-center gap-3">
                <i class="fa-solid fa-file-excel text-blue-600 text-xl"></i>
                <div class="flex-1">
                  <span id="file-name" class="text-sm text-gray-600">Klik untuk memilih file Excel</span>
                  <p class="text-xs text-gray-500 mt-1">Format: .xlsx atau .xls</p>
                </div>
                <i class="fa-solid fa-upload text-gray-400"></i>
              </div>
            </button>
          </div>
          <span class="text-red-500 text-xs mt-1 hidden" id="import-file-error"></span>
        </div>

        <!-- Preview Table -->
        <div id="import-preview" class="hidden mt-4">
          <h4 class="text-sm font-semibold text-gray-700 mb-2">Preview Data (Baris pertama akan diabaikan sebagai header)</h4>
          <div class="border rounded-xl overflow-hidden">
            <div class="overflow-x-auto max-h-64">
              <table class="min-w-full text-sm">
                <thead class="bg-gray-100 sticky top-0">
                  <tr id="preview-header"></tr>
                </thead>
                <tbody id="preview-body" class="bg-white divide-y divide-gray-200"></tbody>
              </table>
            </div>
          </div>
          <div class="mt-2 text-xs text-gray-600">
            <span id="preview-count">0</span> baris data akan diimport
          </div>
        </div>
      </div>
      <div class="flex items-center justify-end gap-2 px-6 py-4 border-t bg-white">
        <button class="px-4 py-2 rounded-lg border border-neutral-300 hover:bg-neutral-50" id="btn-cancel-import">Batal</button>
        <button class="px-4 py-2 rounded-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 ring-1 ring-white/30 shadow-lg hover:shadow-xl active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed" id="btn-submit-import" disabled>
          <i class="fa-solid fa-upload mr-2"></i>Import
        </button>
      </div>
    </div>
  </div>

  <!-- Modal: Create Admin -->
  <div id="modal-create" class="hidden fixed inset-0 z-50 items-center justify-center p-4">
    <div id="modal-create-overlay" class="fixed inset-0 bg-black/50 opacity-0 transition-opacity duration-300"></div>
  <div id="modal-create-panel" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden opacity-0 scale-95 translate-y-4 transition-all duration-300">
      <div class="flex items-center justify-between px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-gray-100">
        <h3 class="text-lg font-semibold text-neutral-900">Buat Akun Baru</h3>
        <button id="btn-close-create" class="text-gray-500 hover:text-gray-700"><i class="fa-solid fa-xmark text-lg"></i></button>
      </div>
      <div class="p-6 space-y-4 overflow-y-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
            <input type="text" id="create-no-hp" class="w-full px-3 py-2 rounded-xl border border-neutral-300 focus:outline-none focus:ring-2 focus:ring-orange-400" placeholder="No HP" />
            <span class="text-red-500 text-xs mt-1 hidden" id="no-hp-error"></span>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
            <input type="text" id="create-username" class="w-full px-3 py-2 rounded-xl border border-neutral-300 focus:outline-none focus:ring-2 focus:ring-orange-400" placeholder="Username" />
            <span class="text-red-500 text-xs mt-1 hidden" id="username-error"></span>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" id="create-email" class="w-full px-3 py-2 rounded-xl border border-neutral-300 focus:outline-none focus:ring-2 focus:ring-orange-400" placeholder="Email" />
            <span class="text-red-500 text-xs mt-1 hidden" id="email-error"></span>
          </div>
          <div class="hidden">
            <input type="password" id="create-password" class="w-full px-3 py-2 rounded-xl border border-neutral-300 focus:outline-none focus:ring-2 focus:ring-orange-400" placeholder="Password" />
            <span class="text-red-500 text-xs mt-1 hidden" id="password-error"></span>
          </div>
          <div class="flex items-center gap-3 pt-4">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" id="create-can-approve" class="w-4 h-4 rounded border-gray-300" />
              <span class="text-sm font-medium text-gray-700">Can Approve</span>
            </label>
          </div>
          <div class="md:col-span-2">
          </div>
        </div>
      </div>
      <div class="flex items-center justify-end gap-2 px-6 py-4 border-t bg-white">
        <button class="px-4 py-2 rounded-lg border border-neutral-300 hover:bg-neutral-50" id="btn-cancel-create">Batal</button>
        <button class="px-4 py-2 rounded-lg text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] ring-1 ring-white/30 shadow-lg hover:shadow-xl active:scale-95 transition-all" id="btn-save-create">Simpan</button>
      </div>
    </div>
  </div>

  <!-- Create User Confirmation Modal -->
  <div id="createUserVerificationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="createUserVerificationContent">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-6 border-b border-gray-200">
        <div class="flex items-center">
          <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-orange-100 to-yellow-100 rounded-full flex items-center justify-center">
            <i class="fas fa-user-plus text-orange-600 text-lg"></i>
          </div>
          <div class="ml-4">
            <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Buat Akun</h3>
            <p class="text-sm text-gray-500">Pastikan data sudah benar</p>
          </div>
        </div>
        <button onclick="closeCreateUserVerificationModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      
      <!-- Modal Body -->
      <div class="p-6">
        <div class="text-center">
          <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-orange-100 to-yellow-100 mb-4">
            <i class="fas fa-user-plus text-orange-600 text-2xl"></i>
          </div>
          <h4 class="text-lg font-medium text-gray-900 mb-2" id="createUserItemName">Username</h4>
          <p class="text-sm text-gray-600 mb-6" id="createUserItemDescription">
            Apakah Anda yakin ingin membuat akun baru dengan data ini?
          </p>
        </div>
      </div>
      
      <!-- Modal Footer -->
      <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 rounded-b-2xl">
        <button onclick="closeCreateUserVerificationModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
          Batal
        </button>
        <button onclick="confirmCreateUser()" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg hover:shadow-lg transition-all duration-300">
          <i class="fas fa-check-circle mr-2"></i>
          Ya, Buat Akun
        </button>
      </div>
    </div>
  </div>

  <!-- Create User Success Modal -->
  <div id="createUserSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="createUserSuccessContent">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-6 border-b border-gray-200">
        <div class="flex items-center">
          <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-green-100 to-emerald-100 rounded-full flex items-center justify-center">
            <i class="fas fa-check text-green-600 text-lg"></i>
          </div>
          <div class="ml-4">
            <h3 class="text-lg font-semibold text-gray-900">Berhasil!</h3>
            <p class="text-sm text-gray-500">Akun berhasil dibuat</p>
          </div>
        </div>
        <button onclick="closeCreateUserSuccessModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      
      <!-- Modal Body -->
      <div class="p-6">
        <div class="text-center">
          <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 mb-4">
            <i class="fas fa-check-circle text-green-600 text-3xl"></i>
          </div>
          <h4 class="text-lg font-medium text-gray-900 mb-2">Akun Berhasil Dibuat!</h4>
          <p class="text-sm text-gray-600 mb-6">
            Akun <span id="createUserSuccessItemName" class="font-semibold"></span> telah berhasil dibuat dan sekarang aktif di sistem.
          </p>
        </div>
      </div>
      
      <!-- Modal Footer -->
      <div class="flex items-center justify-center px-6 py-4 bg-gray-50 rounded-b-2xl">
        <button onclick="closeCreateUserSuccessModal()" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg hover:shadow-lg transition-all duration-300">
          <i class="fas fa-check mr-2"></i>
          OK
        </button>
      </div>
    </div>
  </div>

  <!-- Toast Notification -->
  <div id="toast" class="fixed bottom-4 right-4 bg-white rounded-xl border border-neutral-200 shadow-lg ring-1 ring-white/30 p-4 max-w-sm opacity-0 invisible transition-all duration-300 z-[9999]">
    <div class="flex items-center gap-3">
      <i class="fa-solid fa-check-circle text-green-500 text-xl"></i>
      <span id="toast-message"></span>
    </div>
  </div>

  <!-- Success Modal (dari upload-verification-modal) -->
  <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="successContent">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-6 border-b border-gray-200">
        <div class="flex items-center">
          <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-green-100 to-emerald-100 rounded-full flex items-center justify-center">
            <i class="fas fa-check text-green-600 text-lg"></i>
          </div>
          <div class="ml-4">
            <h3 class="text-lg font-semibold text-gray-900">Berhasil!</h3>
            <p class="text-sm text-gray-500" id="successSubtitle">Operasi berhasil</p>
          </div>
        </div>
        <button onclick="closeSuccessModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      
      <!-- Modal Body -->
      <div class="p-6">
        <div class="text-center">
          <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 mb-4">
            <i class="fas fa-check-circle text-green-600 text-3xl"></i>
          </div>
          <h4 class="text-lg font-medium text-gray-900 mb-2">Sukses!</h4>
          <p class="text-sm text-gray-600 mb-6" id="successMessage">
            Operasi berhasil dilakukan.
          </p>
        </div>
      </div>
      
      <!-- Modal Footer -->
      <div class="flex items-center justify-center px-6 py-4 bg-gray-50 rounded-b-2xl">
        <button onclick="closeSuccessModal()" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg hover:shadow-lg transition-all duration-300">
          <i class="fas fa-check mr-2"></i>
          OK
        </button>
      </div>
    </div>
  </div>

  <!-- Confirmation Modal -->
  <div id="modal-confirm" class="hidden fixed inset-0 z-50 items-center justify-center p-4">
    <div id="modal-confirm-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
    <div id="modal-confirm-panel" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden opacity-0 scale-95 translate-y-4 transition-all duration-300">
      <!-- Header with icon -->
      <div class="bg-gradient-to-r from-red-50 to-orange-50 px-6 py-5 border-b border-red-100">
        <div class="flex items-center gap-4">
          <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-r from-red-500 to-orange-500 rounded-full flex items-center justify-center shadow-lg">
            <i class="fa-solid fa-exclamation-triangle text-white text-lg"></i>
          </div>
          <div class="flex-1">
            <h3 class="text-xl font-bold text-gray-900" id="confirm-title">Konfirmasi</h3>
          </div>
          <button id="btn-confirm-close" class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-white/50 rounded-lg">
            <i class="fa-solid fa-xmark text-lg"></i>
          </button>
        </div>
      </div>
      
      <!-- Body -->
      <div class="p-6">
        <p class="text-gray-700 text-base leading-relaxed" id="confirm-message"></p>
      </div>
      
      <!-- Footer -->
      <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 border-t">
        <button class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-semibold hover:bg-gray-100 active:scale-95 transition-all" id="btn-confirm-cancel">Batal</button>
        <button class="px-5 py-2.5 rounded-xl text-white bg-gradient-to-r from-red-600 to-orange-500 ring-1 ring-white/30 shadow-lg hover:shadow-xl active:scale-95 transition-all font-semibold" id="btn-confirm-ok">
          <i class="fa-solid fa-trash mr-2"></i>Hapus
        </button>
      </div>
    </div>
  </div>

  <script>
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
    const CURRENT_USER_ID = @json(Auth::id());
    let pendingAction = null;

    // Simple HTML-escape to prevent XSS when inserting user-controlled strings
    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    // helper: temukan row <tr> berdasarkan userId
    function findRowByUserId(userId) {
      return document.querySelector(`tr[data-user-id="${userId}"]`);
    }

    // helper: update counters (simple increment/decrement)
    function updateKpiCounts({ totalDelta = 0, adminActiveDelta = 0, userActiveDelta = 0, adminTotalDelta = 0 } = {}) {
      const elTotal = document.getElementById('total-users');
      const elAdminActive = document.getElementById('admin-active');
      const elUserActive = document.getElementById('user-active');
      const elAdminTotal = document.getElementById('total-admin');

      if (elTotal && totalDelta) elTotal.textContent = parseInt(elTotal.textContent || '0', 10) + totalDelta;
      if (elAdminActive && adminActiveDelta) elAdminActive.textContent = parseInt(elAdminActive.textContent || '0', 10) + adminActiveDelta;
      if (elUserActive && userActiveDelta) elUserActive.textContent = parseInt(elUserActive.textContent || '0', 10) + userActiveDelta;
      if (elAdminTotal && adminTotalDelta) elAdminTotal.textContent = parseInt(elAdminTotal.textContent || '0', 10) + adminTotalDelta;
    }

    document.addEventListener('DOMContentLoaded', () => {
      // flash
      const flashMessage = document.querySelector('[data-flash-message]');
      if (flashMessage) {
        const message = flashMessage.getAttribute('data-flash-message');
        const type = flashMessage.getAttribute('data-flash-type');
        if (message) showToast(message, type);
      }

      setupEventListeners();

      // Attach toggle-approve listeners for server-rendered checkboxes
      document.querySelectorAll('.toggle-approve').forEach(toggle => {
        toggle.addEventListener('change', (e) => {
          const userId = e.target.dataset.userId;
          if (!userId) return;
          toggleApproval(userId);
        });
      });

      // Smooth page enter animation
      const mainEl = document.querySelector('main');
      if (mainEl) {
        requestAnimationFrame(() => {
          mainEl.classList.remove('opacity-0', 'translate-y-3');
        });
      }
    });

    // Setup event listeners
    function setupEventListeners() {
      document.getElementById('btn-open-create')?.addEventListener('click', openCreateModal);
      document.getElementById('btn-close-create')?.addEventListener('click', closeCreateModal);
      document.getElementById('btn-cancel-create')?.addEventListener('click', closeCreateModal);
      document.getElementById('btn-save-create')?.addEventListener('click', createUser);

      // Import Excel listeners
      document.getElementById('btn-open-import')?.addEventListener('click', openImportModal);
      document.getElementById('btn-close-import')?.addEventListener('click', closeImportModal);
      document.getElementById('btn-cancel-import')?.addEventListener('click', closeImportModal);
      document.getElementById('btn-select-file')?.addEventListener('click', () => {
        document.getElementById('import-file-input')?.click();
      });
      document.getElementById('import-file-input')?.addEventListener('change', handleFileSelect);
      document.getElementById('btn-submit-import')?.addEventListener('click', submitImport);

      // Search: Enter atau klik tombol
      const searchInput = document.getElementById('search-input');
      const searchBtn = document.getElementById('search-btn');

      function performSearch() {
        if (!searchInput) return;
        const q = searchInput.value.trim();
        const params = new URLSearchParams(window.location.search);
        if (q) params.set('search', q);
        else params.delete('search');
        params.set('page', 1);
        window.location.search = params.toString();
      }

      searchInput?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
          e.preventDefault();
          performSearch();
        }
      });

      searchBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        performSearch();
      });

      document.getElementById('btn-confirm-cancel')?.addEventListener('click', closeConfirmModal);
      document.getElementById('btn-confirm-ok')?.addEventListener('click', executeConfirmedAction);
      document.getElementById('btn-confirm-close')?.addEventListener('click', closeConfirmModal);

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

      // Escape key closes modals
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
          if (!document.getElementById('modal-create').classList.contains('hidden')) {
            closeCreateModal();
          }
          if (!document.getElementById('modal-import').classList.contains('hidden')) {
            closeImportModal();
          }
          if (!document.getElementById('modal-confirm').classList.contains('hidden')) {
            closeConfirmModal();
          }
        }
      });
      
      // Close modal when clicking overlay
      const modalConfirm = document.getElementById('modal-confirm');
      const modalConfirmOverlay = document.getElementById('modal-confirm-overlay');
      if (modalConfirm && modalConfirmOverlay) {
        modalConfirm.addEventListener('click', (e) => {
          if (e.target === modalConfirm || e.target === modalConfirmOverlay) {
            closeConfirmModal();
          }
        });
      }
    }

    // toggleApproval: call API but do NOT reload; reflect UI immediately (checkbox already toggled by user)
    async function toggleApproval(userId) {
      try {
        const response = await fetch(`/api/users/${userId}/toggle-approval`, {
          method: 'PATCH',
          headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
          }
        });

        const data = await response.json();

        if (!response.ok) {
          showToast(data.error || 'Gagal mengubah status', 'error');
          // rollback checkbox if API failed
          const inputEl = document.querySelector(`.toggle-approve[data-user-id="${userId}"]`);
          if (inputEl) inputEl.checked = !inputEl.checked;
          return;
        }

        showSuccessModal(data.message || 'Status berhasil diperbarui', 'Status approval telah diubah');
        // optionally update KPI if API returns counts: if (data.counts) updateKpiCounts(data.counts);
      } catch (error) {
        console.error('Error:', error);
        showToast('Terjadi kesalahan', 'error');
        const inputEl = document.querySelector(`.toggle-approve[data-user-id="${userId}"]`);
        if (inputEl) inputEl.checked = !inputEl.checked;
      }
    }

    // Delete user (open confirm modal)
    function deleteUser(userId) {
      pendingAction = {
        type: 'delete-single',
        userId: userId
      };
      document.getElementById('confirm-title').textContent = 'Hapus User';
      document.getElementById('confirm-message').textContent = 'Apakah Anda yakin ingin menghapus user ini?';
      openConfirmModal();
    }

    // Execute confirmed action (delete) without reload
    async function executeConfirmedAction() {
      if (!pendingAction) return;
      closeConfirmModal();

      try {
        if (pendingAction.type === 'delete-single') {
          const response = await fetch(`/api/users/${pendingAction.userId}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': CSRF_TOKEN,
              'X-Requested-With': 'XMLHttpRequest'
            }
          });

          const data = await response.json();

          if (!response.ok) {
            showToast(data.error || 'Gagal menghapus user', 'error');
            return;
          }

          showSuccessModal(data.message || 'User berhasil dihapus', 'User telah dihapus dari sistem');

          // remove row from DOM
          const row = findRowByUserId(pendingAction.userId);
          if (row) row.remove();

          // update simple counters
          const showingTotalEl = document.getElementById('showing-total');
          if (showingTotalEl) {
            const val = parseInt(showingTotalEl.textContent || '0', 10);
            showingTotalEl.textContent = Math.max(0, val - 1);
          }
          updateKpiCounts({ totalDelta: -1 });

          pendingAction = null;
          return;
        }

        // bulk-delete not implemented (no row checkboxes)
      } catch (error) {
        console.error('Error:', error);
        showToast('Terjadi kesalahan', 'error');
      }
    }

    // helper: build row HTML string (must match your Blade row structure)
    function buildUserRowHtml(user) {
      const username = escapeHtml(user.username || '');
      const role = escapeHtml(user.role || 'user');
      const createdAt = user.created_at ? new Date(user.created_at).toLocaleDateString('id-ID') : '';
      const checked = user.can_approve ? 'checked' : '';
      const isCurrentUser = (CURRENT_USER_ID !== null && user.id === CURRENT_USER_ID);

      const email = escapeHtml(user.email || '-');
      return `
<tr class="hover:bg-gray-50" data-user-id="${user.id}">
  <td class="px-2 py-2 text-left text-gray-800 font-semibold">-</td>
  <td class="px-3 py-2">
    <div class="font-medium text-gray-900">${username}</div>
    <div class="text-xs text-gray-500">Dibuat ${createdAt}</div>
  </td>
  <td class="px-3 py-2">
    <div class="text-sm text-gray-700">${email}</div>
  </td>
  <td class="px-3 py-2">
    <span class="px-2 py-1 rounded-full text-xs font-semibold ${role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'}">
      ${role}
    </span>
  </td>
  <td class="px-3 py-2">
    <label class="relative inline-flex items-center cursor-pointer" title="Toggle Can Approve" ${isCurrentUser ? 'onclick=return false;' : ''}>
      <input type="checkbox" data-user-id="${user.id}" class="sr-only peer toggle-approve" ${checked} ${isCurrentUser ? 'disabled' : ''} />
      <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-0 peer-focus:ring-transparent rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600 hover:peer-checked:bg-green-700"></div>
    </label>
  </td>
  <td class="px-3 py-2">
    <div class="flex items-center gap-2">
      ${isCurrentUser ? '<span class="text-xs text-gray-500">Your Account</span>' : `<button onclick="deleteUser(${user.id})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border text-red-600 hover:bg-red-50"><i class="fas fa-trash text-sm"></i></button>`}
    </div>
  </td>
</tr>
  `;
    }

    // Insert row into table (prepend or append)
    function insertUserRow(user, prepend = true) {
      const tbody = document.getElementById('users-tbody');
      if (!tbody) return;
      const html = buildUserRowHtml(user);
      if (prepend) {
        tbody.insertAdjacentHTML('afterbegin', html);
      } else {
        tbody.insertAdjacentHTML('beforeend', html);
      }

      // attach event listener for new row's toggle checkbox
      const toggleEl = document.querySelector(`.toggle-approve[data-user-id="${user.id}"]`);
      if (toggleEl) {
        toggleEl.addEventListener('change', (e) => {
          toggleApproval(e.target.dataset.userId);
        });
      }

      // update KPIs (simple)
      updateKpiCounts({ totalDelta: 1, adminTotalDelta: user.role === 'admin' ? 1 : 0 });
      const showingTotalEl = document.getElementById('showing-total');
      if (showingTotalEl) showingTotalEl.textContent = parseInt(showingTotalEl.textContent || '0', 10) + 1;
    }

    // createUser: show confirmation modal first
    function createUser() {
      const noHp = document.getElementById('create-no-hp').value.trim();
      const username = document.getElementById('create-username').value.trim();
      const email = document.getElementById('create-email').value.trim();
      const password = 'namiku'; // Password otomatis "namiku"
      const role = 'admin'; // Otomatis admin
      const canApprove = document.getElementById('create-can-approve').checked;

      // Validation
      let isValid = true;
      if (!username || username.length < 3) {
        showFieldError('username-error', 'Username minimal 3 karakter');
        isValid = false;
      } else {
        hideFieldError('username-error');
      }

      // Email validation (optional but if filled must be valid)
      if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showFieldError('email-error', 'Format email tidak valid');
        isValid = false;
      } else {
        hideFieldError('email-error');
      }

      if (!isValid) return;

      // Show confirmation modal
      showCreateUserConfirmation(username, role, canApprove, noHp, email);
    }

    // confirmCreateUser: actually create the user after confirmation
    async function confirmCreateUser() {
      const noHp = document.getElementById('create-no-hp').value.trim();
      const username = document.getElementById('create-username').value.trim();
      const email = document.getElementById('create-email').value.trim();
      const password = 'namiku'; // Password otomatis "namiku"
      const role = 'admin'; // Otomatis admin
      const canApprove = document.getElementById('create-can-approve').checked;

      // Close confirmation modal
      closeCreateUserVerificationModal();

      try {
        const response = await fetch('/api/users', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            no_hp: noHp || null,
            username: username,
            email: email || null,
            password: password,
            role: role,
            can_approve: canApprove
          })
        });

        const data = await response.json();

        if (!response.ok) {
          if (data.errors) {
            Object.entries(data.errors).forEach(([key, messages]) => {
              showFieldError(`${key}-error`, messages[0]);
            });
          } else {
            showToast(data.error || 'Gagal membuat user', 'error');
          }
          return;
        }

        // Show success modal
        showCreateUserSuccessModal(username);
        closeCreateModal();

        // If API returned the created user object, insert the row without reload
        if (data.user) {
          insertUserRow(data.user, true);
        } else {
          // fallback: if API did not return user, prompt manual refresh
          showToast('User dibuat. Jika tidak terlihat, refresh halaman.', 'error');
        }

      } catch (error) {
        console.error('Error:', error);
        showToast('Terjadi kesalahan', 'error');
      }
    }

    // Show create user confirmation modal
    function showCreateUserConfirmation(username, role, canApprove, noHp, email) {
      const roleText = 'Admin';
      const approveText = canApprove ? ' (Dapat Approve)' : '';
      let details = [];
      if (noHp) details.push(`No HP: ${noHp}`);
      if (email) details.push(`Email: ${email}`);
      const detailsText = details.length > 0 ? `\n\nDetail:\n${details.join('\n')}` : '';
      
      document.getElementById('createUserItemName').textContent = username;
      document.getElementById('createUserItemDescription').textContent = 
        `Apakah Anda yakin ingin membuat akun baru dengan username "${username}" sebagai ${roleText}${approveText}?${detailsText}`;
      
      const modal = document.getElementById('createUserVerificationModal');
      const modalContent = document.getElementById('createUserVerificationContent');
      
      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      
      setTimeout(() => {
        if (modalContent) {
          modalContent.style.transform = 'scale(1)';
          modalContent.style.opacity = '1';
        }
      }, 10);
    }

    // Close create user verification modal
    function closeCreateUserVerificationModal() {
      const modal = document.getElementById('createUserVerificationModal');
      const modalContent = document.getElementById('createUserVerificationContent');
      
      if (modalContent) {
        modalContent.style.transform = 'scale(0.95)';
        modalContent.style.opacity = '0';
      }
      
      setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        if (modalContent) {
          modalContent.style.transform = 'scale(0.95)';
          modalContent.style.opacity = '0';
        }
      }, 300);
    }

    // Show create user success modal
    function showCreateUserSuccessModal(username) {
      document.getElementById('createUserSuccessItemName').textContent = username;
      
      const modal = document.getElementById('createUserSuccessModal');
      const modalContent = document.getElementById('createUserSuccessContent');
      
      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      
      setTimeout(() => {
        if (modalContent) {
          modalContent.style.transform = 'scale(1)';
          modalContent.style.opacity = '1';
        }
      }, 10);
      
      // Auto close after 3 seconds
      setTimeout(() => {
        closeCreateUserSuccessModal();
      }, 3000);
    }

    // Close create user success modal
    function closeCreateUserSuccessModal() {
      const modal = document.getElementById('createUserSuccessModal');
      const modalContent = document.getElementById('createUserSuccessContent');
      
      if (modalContent) {
        modalContent.style.transform = 'scale(0.95)';
        modalContent.style.opacity = '0';
      }
      
      setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        if (modalContent) {
          modalContent.style.transform = 'scale(0.95)';
          modalContent.style.opacity = '0';
        }
      }, 300);
    }

    // Bulk functions left as stubs (no row checkboxes present)
    function bulkDelete() {
      showToast('Fitur bulk tidak tersedia karena checkbox baris dihapus', 'error');
    }
    function bulkToggleApproval(status) {
      showToast('Fitur bulk tidak tersedia karena checkbox baris dihapus', 'error');
    }

    // Clear create form
    function clearCreateForm() {
      document.getElementById('create-no-hp').value = '';
      document.getElementById('create-username').value = '';
      document.getElementById('create-email').value = '';
      document.getElementById('create-can-approve').checked = false;
      document.querySelectorAll('[id$="-error"]').forEach(el => hideFieldError(el.id));
    }

    // Field error helpers
    function showFieldError(elementId, message) {
      const el = document.getElementById(elementId);
      if (el) {
        el.textContent = message;
        el.classList.remove('hidden');
      }
    }

    function hideFieldError(elementId) {
      const el = document.getElementById(elementId);
      if (el) {
        el.classList.add('hidden');
        el.textContent = '';
      }
    }

    // Toast notification
    function showToast(message, type = 'success') {
      const toast = document.getElementById('toast');
      const messageEl = document.getElementById('toast-message');
      const icon = toast.querySelector('i');

      messageEl.textContent = message;
      if (type === 'error') {
        icon.className = 'fa-solid fa-circle-exclamation text-red-500 text-xl';
      } else {
        icon.className = 'fa-solid fa-check-circle text-green-500 text-xl';
      }

      toast.classList.remove('opacity-0', 'invisible');
      toast.classList.add('opacity-100', 'visible');

      setTimeout(() => {
        toast.classList.add('opacity-0', 'invisible');
        toast.classList.remove('opacity-100', 'visible');
      }, 3000);
    }

    // Success Modal Functions
    function showSuccessModal(message, subtitle = 'Operasi berhasil') {
      const modal = document.getElementById('successModal');
      const modalContent = document.getElementById('successContent');
      const messageEl = document.getElementById('successMessage');
      const subtitleEl = document.getElementById('successSubtitle');

      if (messageEl) messageEl.textContent = message;
      if (subtitleEl) subtitleEl.textContent = subtitle;

      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';

      setTimeout(() => {
        if (modalContent) {
          modalContent.style.transform = 'scale(1)';
          modalContent.style.opacity = '1';
        }
      }, 10);

      setTimeout(() => {
        closeSuccessModal();
      }, 3000);
    }

    function closeSuccessModal() {
      const modal = document.getElementById('successModal');
      const modalContent = document.getElementById('successContent');

      if (modalContent) {
        modalContent.style.transform = 'scale(0.95)';
        modalContent.style.opacity = '0';
      }

      setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        if (modalContent) {
          modalContent.style.transform = 'scale(0.95)';
          modalContent.style.opacity = '0';
        }
      }, 300);
    }

    // Modal helpers
    function openCreateModal() {
      const modal = document.getElementById('modal-create');
      const overlay = document.getElementById('modal-create-overlay');
      const panel = document.getElementById('modal-create-panel');

      if (!modal) return;
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      document.body.style.overflow = 'hidden';
      clearCreateForm();

      requestAnimationFrame(() => {
        overlay?.classList.remove('opacity-0');
        overlay?.classList.add('opacity-100');
        panel?.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
        panel?.classList.add('opacity-100', 'scale-100', 'translate-y-0');
      });
    }
    function closeCreateModal() {
      const modal = document.getElementById('modal-create');
      const overlay = document.getElementById('modal-create-overlay');
      const panel = document.getElementById('modal-create-panel');

      overlay?.classList.add('opacity-0');
      overlay?.classList.remove('opacity-100');
      panel?.classList.add('opacity-0', 'scale-95', 'translate-y-4');
      panel?.classList.remove('opacity-100', 'scale-100', 'translate-y-0');

      setTimeout(() => {
        modal?.classList.add('hidden');
        modal?.classList.remove('flex');
        document.body.style.overflow = '';
        clearCreateForm();
      }, 300);
    }
    function openConfirmModal() {
      const modal = document.getElementById('modal-confirm');
      const overlay = document.getElementById('modal-confirm-overlay');
      const panel = document.getElementById('modal-confirm-panel');
      
      if (!modal) return;
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      document.body.style.overflow = 'hidden';
      
      // Trigger animation after DOM update
      requestAnimationFrame(() => {
        overlay?.classList.remove('opacity-0');
        overlay?.classList.add('opacity-100');
        panel?.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
        panel?.classList.add('opacity-100', 'scale-100', 'translate-y-0');
      });
    }
    
    function closeConfirmModal() {
      const modal = document.getElementById('modal-confirm');
      const overlay = document.getElementById('modal-confirm-overlay');
      const panel = document.getElementById('modal-confirm-panel');
      
      overlay?.classList.remove('opacity-100');
      overlay?.classList.add('opacity-0');
      panel?.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
      panel?.classList.add('opacity-0', 'scale-95', 'translate-y-4');
      
      setTimeout(() => {
        modal?.classList.add('hidden');
        modal?.classList.remove('flex');
        document.body.style.overflow = '';
      }, 300);
    }

    // User dropdown
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

    // Import Excel Functions
    let selectedFile = null;
    let previewData = [];

    function openImportModal() {
      const modal = document.getElementById('modal-import');
      const overlay = document.getElementById('modal-import-overlay');
      const panel = document.getElementById('modal-import-panel');

      if (!modal) return;
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      document.body.style.overflow = 'hidden';
      resetImportForm();

      requestAnimationFrame(() => {
        overlay?.classList.remove('opacity-0');
        overlay?.classList.add('opacity-100');
        panel?.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
        panel?.classList.add('opacity-100', 'scale-100', 'translate-y-0');
      });
    }

    function closeImportModal() {
      const modal = document.getElementById('modal-import');
      const overlay = document.getElementById('modal-import-overlay');
      const panel = document.getElementById('modal-import-panel');

      overlay?.classList.add('opacity-0');
      overlay?.classList.remove('opacity-100');
      panel?.classList.add('opacity-0', 'scale-95', 'translate-y-4');
      panel?.classList.remove('opacity-100', 'scale-100', 'translate-y-0');

      setTimeout(() => {
        modal?.classList.add('hidden');
        modal?.classList.remove('flex');
        document.body.style.overflow = '';
        resetImportForm();
      }, 300);
    }

    function resetImportForm() {
      selectedFile = null;
      previewData = [];
      const fileInput = document.getElementById('import-file-input');
      const fileName = document.getElementById('file-name');
      const preview = document.getElementById('import-preview');
      const submitBtn = document.getElementById('btn-submit-import');
      const fileError = document.getElementById('import-file-error');

      if (fileInput) fileInput.value = '';
      if (fileName) fileName.textContent = 'Klik untuk memilih file Excel';
      if (preview) preview.classList.add('hidden');
      if (submitBtn) submitBtn.disabled = true;
      if (fileError) {
        fileError.classList.add('hidden');
        fileError.textContent = '';
      }
    }

    function handleFileSelect(event) {
      const file = event.target.files[0];
      if (!file) return;

      const allowedTypes = [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
        'application/vnd.ms-excel' // .xls
      ];

      if (!allowedTypes.includes(file.type) && !file.name.match(/\.(xlsx|xls)$/i)) {
        showFieldError('import-file-error', 'File harus berformat Excel (.xlsx atau .xls)');
        return;
      }

      hideFieldError('import-file-error');
      selectedFile = file;
      document.getElementById('file-name').textContent = file.name;
      document.getElementById('btn-submit-import').disabled = false;

      // Preview file (read and display first few rows)
      previewExcelFile(file);
    }

    function previewExcelFile(file) {
      const formData = new FormData();
      formData.append('file', file);
      formData.append('preview_only', '1');

      // Show loading
      const preview = document.getElementById('import-preview');
      preview.classList.remove('hidden');
      document.getElementById('preview-body').innerHTML = '<tr><td colspan="10" class="px-4 py-4 text-center text-gray-500">Memuat preview...</td></tr>';

      fetch('/api/users/import-preview', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          showFieldError('import-file-error', data.error);
          preview.classList.add('hidden');
          return;
        }

        previewData = data.data || [];
        displayPreview(data.headers || [], previewData);
      })
      .catch(error => {
        console.error('Error:', error);
        showFieldError('import-file-error', 'Gagal membaca file Excel');
        preview.classList.add('hidden');
      });
    }

    function displayPreview(headers, rows) {
      const headerRow = document.getElementById('preview-header');
      const body = document.getElementById('preview-body');
      const countEl = document.getElementById('preview-count');

      // Clear previous content
      headerRow.innerHTML = '';
      body.innerHTML = '';

      // Display headers
      headers.forEach(header => {
        const th = document.createElement('th');
        th.className = 'px-3 py-2 text-left text-xs font-semibold text-gray-700 uppercase';
        th.textContent = header;
        headerRow.appendChild(th);
      });

      // Display rows (max 10 rows for preview)
      const displayRows = rows.slice(0, 10);
      displayRows.forEach((row, idx) => {
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50';
        headers.forEach(header => {
          const td = document.createElement('td');
          td.className = 'px-3 py-2 text-xs text-gray-700';
          td.textContent = row[header] || '';
          tr.appendChild(td);
        });
        body.appendChild(tr);
      });

      if (rows.length > 10) {
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = headers.length;
        td.className = 'px-3 py-2 text-xs text-center text-gray-500 italic';
        td.textContent = `... dan ${rows.length - 10} baris lainnya`;
        tr.appendChild(td);
        body.appendChild(tr);
      }

      countEl.textContent = rows.length;
    }

    async function submitImport() {
      if (!selectedFile) {
        showToast('Pilih file Excel terlebih dahulu', 'error');
        return;
      }

      const submitBtn = document.getElementById('btn-submit-import');
      const originalText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Mengimport...';

      const formData = new FormData();
      formData.append('file', selectedFile);

      try {
        const response = await fetch('/api/users/import', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: formData
        });

        const data = await response.json();

        if (!response.ok) {
          showToast(data.error || 'Gagal mengimport data', 'error');
          if (data.errors) {
            let errorMsg = 'Error: ';
            Object.entries(data.errors).forEach(([key, messages]) => {
              errorMsg += `${key}: ${Array.isArray(messages) ? messages.join(', ') : messages}. `;
            });
            showToast(errorMsg, 'error');
          }
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalText;
          return;
        }

        // Success
        showToast(data.message || `Berhasil mengimport ${data.success_count || 0} user`, 'success');
        
        // Insert imported users to table
        if (data.users && Array.isArray(data.users)) {
          data.users.forEach(user => {
            insertUserRow(user, true);
          });
        }

        // Update counters
        if (data.counts) {
          updateKpiCounts(data.counts);
        }

        // Update showing total
        const showingTotalEl = document.getElementById('showing-total');
        if (showingTotalEl && data.success_count) {
          const val = parseInt(showingTotalEl.textContent || '0', 10);
          showingTotalEl.textContent = val + data.success_count;
        }

        closeImportModal();
        
        // Reload page after 2 seconds to show all imported users
        setTimeout(() => {
          window.location.reload();
        }, 2000);

      } catch (error) {
        console.error('Error:', error);
        showToast('Terjadi kesalahan saat mengimport', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
      }
    }
  </script>
</body>
</html>
