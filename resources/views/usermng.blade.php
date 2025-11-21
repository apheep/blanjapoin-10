
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
   <nav id="navbar" class="sticky top-0 z-20 bg-white transition-shadow duration-300 w-full">
    <div class="mx-auto max-w-7xl px-2 sm:px-4 md:px-6 lg:px-8 py-4 md:py-5 lg:py-6 relative">
     <div class="flex items-center justify-between">
      <div class="flex items-center gap-6">
       <!-- Mobile hamburger -->
       <button id="openSidebar" class="md:hidden text-gray-700 text-2xl mr-3">
         <i class="fa-solid fa-bars"></i>
       </button>
       <img src="/logo.png" alt="BlanjaPoin" class="h-10 md:h-12 lg:h-14 w-auto" />
      </div>

      <!-- Centered primary navigation (desktop only, untouched) -->
      <div class="hidden md:flex items-center gap-6 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
      @if(Auth::check() && Auth::user()->can_approve == 1) 
        <a href="{{ route('admin') }}" class="text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] bg-clip-text text-transparent hover:opacity-80 transition-opacity">Home</a>
        <a href="{{ route('user.management') }}" class="text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] bg-clip-text text-transparent hover:opacity-80 transition-opacity">User Management</a>
       @endif
      </div>

      <div class="relative hidden md:block">
        <button onclick="toggleUserDropdown()" id="userDropdownBtn" class="inline-flex items-center gap-1.5 md:gap-2 rounded-xl md:rounded-2xl bg-gradient-to-r from-[#FF3B30] via-[#FF6B2C] to-[#FF9F0A] px-4 md:px-6 py-2 md:py-2.5 text-xs md:text-sm font-semibold text-white">
          <i class="fa-solid fa-user"></i>
          <span>{{ Auth::user()->username }}</span>
          <i id="userDropdownArrow" class="fa-solid fa-chevron-down text-xs"></i>
        </button>
        <div id="userDropdown" class="absolute right-0 mt-2 w-48 rounded-xl bg-white shadow-xl ring-1 ring-neutral-200 opacity-0 invisible scale-95 transition-all">
          <div class="py-1">
            <form method="POST" action="{{ route('logout') }}">@csrf
              <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold text-red-600 hover:bg-red-50">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
              </button>
            </form>
          </div>
        </div>
      </div>
     </div>
    </div>
   </nav>

<!-- Mobile Sidebar -->
<div id="mobileSidebar" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
  <div id="sidebarPanel" class="bg-white w-72 h-full p-6 shadow-xl transform -translate-x-full transition-all">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-lg font-semibold">Menu</h2>
      <button id="closeSidebar"><i class="fa-solid fa-xmark text-xl"></i></button>
    </div>

    @if(Auth::check() && Auth::user()->can_approve == 1)
      <a href="{{ route('admin') }}" class="block py-2 font-semibold text-gray-700">Home</a>
      <a href="{{ route('user.management') }}" class="block py-2 font-semibold text-gray-700">User Management</a>
    @endif

    <hr class="my-4">

    <form method="POST" action="{{ route('logout') }}">@csrf
      <button type="submit" class="w-full text-left text-red-600 font-semibold py-2">Logout</button>
    </form>
  </div>
</div>

<script>
  const openSidebar = document.getElementById('openSidebar');
  const closeSidebar = document.getElementById('closeSidebar');
  const mobileSidebar = document.getElementById('mobileSidebar');
  const sidebarPanel = document.getElementById('sidebarPanel');

  openSidebar?.addEventListener('click', () => {
    mobileSidebar.classList.remove('hidden');
    setTimeout(() => sidebarPanel.classList.remove('-translate-x-full'), 10);
  });

  closeSidebar?.addEventListener('click', () => {
    sidebarPanel.classList.add('-translate-x-full');
    setTimeout(() => mobileSidebar.classList.add('hidden'), 300);
  });
</script>

<!-- Sidebar Mobile -->
<div id="mobileSidebar" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
  <div class="bg-white w-72 h-full p-6 shadow-xl transform -translate-x-full transition-all" id="sidebarPanel">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-lg font-semibold">Menu</h2>
      <button id="closeSidebar"><i class="fa-solid fa-xmark text-xl"></i></button>
    </div>

    @if(Auth::check() && Auth::user()->can_approve == 1)
      <a href="{{ route('admin') }}" class="block py-2 font-semibold text-gray-700">Home</a>
      <a href="{{ route('user.management') }}" class="block py-2 font-semibold text-gray-700">User Management</a>
    @endif

    <hr class="my-4">

    <form method="POST" action="{{ route('logout') }}"> @csrf
      <button type="submit" class="w-full text-left text-red-600 font-semibold py-2">Logout</button>
    </form>
  </div>
</div>
<script>
  const openSidebar = document.getElementById('openSidebar');
  const closeSidebar = document.getElementById('closeSidebar');
  const mobileSidebar = document.getElementById('mobileSidebar');
  const sidebarPanel = document.getElementById('sidebarPanel');

  openSidebar?.addEventListener('click', () => {
    mobileSidebar.classList.remove('hidden');
    setTimeout(() => sidebarPanel.classList.remove('-translate-x-full'), 10);
  });

  closeSidebar?.addEventListener('click', () => {
    sidebarPanel.classList.add('-translate-x-full');
    setTimeout(() => mobileSidebar.classList.add('hidden'), 300);
  });
</script>

<!-- MOBILE SIDEBAR OVERLAY -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden"></div>

<!-- MOBILE SIDEBAR -->
<div id="mobileSidebar" class="fixed top-0 left-0 w-64 h-full bg-white shadow-xl z-50 transform -translate-x-full transition-transform duration-300">
  <div class="p-4 border-b flex items-center justify-between">
    <img src="/logo.png" class="h-10" />
    <button id="closeSidebar" class="text-gray-700 text-xl"><i class="fa-solid fa-xmark"></i></button>
  </div>

  <div class="p-4 flex flex-col gap-4">
    @if(Auth::check() && Auth::user()->can_approve == 1)
      <a href="{{ route('admin') }}" class="text-base font-semibold text-gray-700">Home</a>
      <a href="{{ route('user.management') }}" class="text-base font-semibold text-gray-700">User Management</a>
    @endif

    <!-- User button -->
    <form method="POST" action="{{ route('logout') }}" class="pt-4 border-t">
      @csrf
      <button type="submit" class="flex items-center gap-3 text-red-600 font-semibold">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </button>
    </form>
  </div>
</div>
<script>
// Mobile Sidebar
const sidebar = document.getElementById('mobileSidebar');
const overlay = document.getElementById('sidebarOverlay');
document.getElementById('mobileMenuBtn').onclick = () => {
  sidebar.classList.remove('-translate-x-full');
  overlay.classList.remove('hidden');
};
document.getElementById('closeSidebar').onclick = closeSidebar;
overlay.onclick = closeSidebar;
function closeSidebar() {
  sidebar.classList.add('-translate-x-full');
  overlay.classList.add('hidden');
}
</script>
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

  <main class="max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-8 transform transition-all duration-500 opacity-0 translate-y-3">
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
    <!-- KPI Cards -->
<section class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
  <div class="rounded-2xl border bg-white p-4 shadow-sm">
    <div class="text-xs text-gray-500">Total User</div>
    <div class="mt-1 text-2xl font-semibold" id="total-users">{{ $totalUsers ?? 0 }}</div>
  </div>
  <div class="rounded-2xl border bg-white p-4 shadow-sm">
    <div class="text-xs text-gray-500">Admin Aktif</div>
    <div class="mt-1 text-2xl font-semibold" id="admin-active">{{ $adminActiveCount ?? 0 }}</div>
  </div>
  <div class="rounded-2xl border bg-white p-4 shadow-sm">
    <div class="text-xs text-gray-500">User Aktif</div>
    <div class="mt-1 text-2xl font-semibold" id="user-active">{{ $userActiveCount ?? 0 }}</div>
  </div>
  <div class="rounded-2xl border bg-white p-4 shadow-sm">
    <div class="text-xs text-gray-500">Total Admin</div>
    <div class="mt-1 text-2xl font-semibold" id="total-admin">{{ $adminCount ?? 0 }}</div>
  </div>
</section>

    <!-- Toolbar (search with button) -->
    <section class="mt-6 bg-white border rounded-2xl shadow-sm p-4">
      <div class="flex flex-col lg:flex-row gap-3 lg:items-center lg:justify-between">
        <div class="flex flex-1 gap-2">
          <div class="relative flex-1 min-w-[220px]">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input
              type="text"
              id="search-input"
              placeholder="Cari username…"
              value="{{ request('search') ?? '' }}"
              class="w-full pl-9 pr-3 py-2.5 rounded-xl border focus:ring-orange-400 focus:outline-none focus:ring-2"
            />
          </div>
          <button
            id="search-btn"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] ring-2 ring-orange-200/60 shadow-sm hover:shadow-md active:scale-[0.97] transition-all"
          >
            <i class="fa-solid fa-search text-[12px]"></i>
            <span>Cari</span>
          </button>
        </div>
      </div>
    </section>

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
    <section class="mt-4 bg-white border rounded-2xl shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-collapse align-middle">
          <colgroup>
            <col style="width:48px" /> <!-- No -->
            <col /> <!-- Username auto -->
            <col /> <!-- Role -->
            <col /> <!-- Can Approve -->
            <col style="width:100px" /> <!-- Aksi -->
          </colgroup>
          <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
            <tr class="text-left">
              <th class="px-2 py-2 text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">No</th>
              <th class="px-3 py-2 text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Username</th>
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
              <tr class="hover:bg-gray-50" data-user-id="{{ $user->id }}">
                <td class="px-2 py-2 text-left text-gray-800 font-semibold">{{ $rowNum }}</td>
                <td class="px-3 py-2">
                  <div class="font-medium text-gray-900">{{ $user->username }}</div>
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
                      <button onclick="deleteUser({{ $user->id }})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border text-red-600 hover:bg-red-50"><i class="fas fa-trash text-sm"></i></button>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada data user</td></tr>
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
            @if($isPaginator)
              {!! $users->links('vendor.pagination.tailwind') !!}
            @endif
          </nav>
        </div>
      </div>
    </section>
  </main>

  <!-- Modal: Create Admin -->
  <div id="modal-create" class="hidden fixed inset-0 z-50 items-center justify-center p-4">
    <div id="modal-create-overlay" class="fixed inset-0 bg-black/50 opacity-0 transition-opacity duration-300"></div>
    <div id="modal-create-panel" class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden opacity-0 scale-95 translate-y-4 transition-all duration-300">
      <div class="flex items-center justify-between px-6 py-4 border-b">
        <h3 class="text-lg font-semibold">Buat Akun Baru</h3>
        <button id="btn-close-create" class="text-gray-500 hover:text-gray-700"><i class="fa-solid fa-xmark text-lg"></i></button>
      </div>
      <div class="p-6 space-y-4 overflow-y-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
            <input type="text" id="create-username" class="w-full px-3 py-2 rounded-xl border focus:ring-orange-400 focus:outline-none focus:ring-2" placeholder="Username" />
            <span class="text-red-500 text-xs mt-1 hidden" id="username-error"></span>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select id="create-role" class="w-full px-3 py-2 rounded-xl border focus:ring-orange-400 focus:outline-none focus:ring-2">
              <option value="user">User</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" id="create-password" class="w-full px-3 py-2 rounded-xl border focus:ring-orange-400 focus:outline-none focus:ring-2" placeholder="Password" />
            <span class="text-red-500 text-xs mt-1 hidden" id="password-error"></span>
          </div>
          <div class="flex items-center gap-3 pt-4">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" id="create-can-approve" class="w-4 h-4 rounded border-gray-300" />
              <span class="text-sm font-medium text-gray-700">Can Approve</span>
            </label>
          </div>
        </div>
      </div>
      <div class="flex items-center justify-end gap-2 px-6 py-4 border-t">
        <button class="px-4 py-2 rounded-lg border hover:bg-gray-50" id="btn-cancel-create">Batal</button>
        <button class="px-4 py-2 rounded-lg text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] ring-2 ring-orange-200/60 hover:shadow-md transition-all" id="btn-save-create">Simpan</button>
      </div>
    </div>
  </div>

  <!-- Toast Notification -->
  <div id="toast" class="fixed bottom-4 right-4 bg-white rounded-lg shadow-lg p-4 max-w-sm opacity-0 invisible transition-all duration-300 z-[9999]">
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
    <div class="fixed inset-0 bg-black/50"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-sm">
      <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-2" id="confirm-title">Konfirmasi</h3>
        <p class="text-gray-600 text-sm" id="confirm-message"></p>
      </div>
      <div class="flex items-center justify-end gap-2 px-6 py-4 border-t">
        <button class="px-4 py-2 rounded-lg border hover:bg-gray-50" id="btn-confirm-cancel">Batal</button>
        <button class="px-4 py-2 rounded-lg text-white bg-red-600 hover:bg-red-700 transition-colors" id="btn-confirm-ok">Hapus</button>
      </div>
    </div>
  </div>

  <script>
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
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
          if (!document.getElementById('modal-confirm').classList.contains('hidden')) {
            closeConfirmModal();
          }
        }
      });
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
      const isCurrentUser = (user.id === {{ Auth::id() ?? 'null' }});

      return `
<tr class="hover:bg-gray-50" data-user-id="${user.id}">
  <td class="px-2 py-2 text-left text-gray-800 font-semibold">-</td>
  <td class="px-3 py-2">
    <div class="font-medium text-gray-900">${username}</div>
    <div class="text-xs text-gray-500">Dibuat ${createdAt}</div>
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

    // createUser: attempt AJAX create and insert row if API returns user
    async function createUser() {
      const username = document.getElementById('create-username').value.trim();
      const password = document.getElementById('create-password').value.trim();
      const role = document.getElementById('create-role').value;
      const canApprove = document.getElementById('create-can-approve').checked;

      // Validation
      let isValid = true;
      if (!username || username.length < 3) {
        showFieldError('username-error', 'Username minimal 3 karakter');
        isValid = false;
      } else {
        hideFieldError('username-error');
      }

      if (!password || password.length < 6) {
        showFieldError('password-error', 'Password minimal 6 karakter');
        isValid = false;
      } else {
        hideFieldError('password-error');
      }

      if (!isValid) return;

      try {
        const response = await fetch('/api/users', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            username: username,
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

        showToast(data.message || 'User berhasil dibuat');
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

    // Bulk functions left as stubs (no row checkboxes present)
    function bulkDelete() {
      showToast('Fitur bulk tidak tersedia karena checkbox baris dihapus', 'error');
    }
    function bulkToggleApproval(status) {
      showToast('Fitur bulk tidak tersedia karena checkbox baris dihapus', 'error');
    }

    // Clear create form
    function clearCreateForm() {
      document.getElementById('create-username').value = '';
      document.getElementById('create-password').value = '';
      document.getElementById('create-role').value = 'user';
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
      document.getElementById('modal-confirm').classList.remove('hidden');
      document.getElementById('modal-confirm').classList.add('flex');
      document.body.style.overflow = 'hidden';
    }
    function closeConfirmModal() {
      document.getElementById('modal-confirm').classList.add('hidden');
      document.getElementById('modal-confirm').classList.remove('flex');
      document.body.style.overflow = '';
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
  </script>
</body>
</html>
