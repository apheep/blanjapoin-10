<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Approval - blanjapoin.id</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      text-rendering: optimizeLegibility;
      font-feature-settings: 'kern' 1;
      letter-spacing: -0.01em;
    }
  </style>
</head>

<body class="min-h-screen bg-white font-poppins">
  <!-- Navbar -->
  <nav id="navbar" class="sticky top-0 z-20 bg-white transition-shadow duration-300 w-full border-b border-gray-200">
    <div class="mx-auto max-w-7xl px-2 sm:px-4 md:px-6 lg:px-8 py-4 md:py-5 lg:py-6 relative">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4 md:gap-6">
          <button onclick="toggleMobileMenu()" id="mobileMenuBtn" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6 text-gray-700">
              <path fill-rule="evenodd" d="M3 6.75A.75.75 0 013.75 6h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 6.75zM3 12a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 12zm0 5.25a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
            </svg>
          </button>
          <img src="/logo.png" alt="BlanjaPoin" class="h-10 md:h-12 lg:h-14 w-auto" />
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center gap-6 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
          @if(Auth::check() && Auth::user()->can_approve == 1)
            <a href="{{ route('admin') }}" class="text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] bg-clip-text text-transparent hover:opacity-80 transition-opacity">Home</a>
            <a href="{{ route('approval') }}" class="text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] bg-clip-text text-transparent hover:opacity-80 transition-opacity">Approval</a>
            <a href="{{ route('user.management') }}" class="text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] bg-clip-text text-transparent hover:opacity-80 transition-opacity">User Management</a>
          @endif
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden fixed inset-0 z-30 bg-black bg-opacity-50 md:hidden">
          <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-xl transform transition-transform duration-300 ease-out -translate-x-full" id="mobileMenuPanel">
            <div class="flex flex-col h-full">
              <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <img src="/logo.png" alt="BlanjaPoin" class="h-10 w-auto" />
                <button onclick="toggleMobileMenu()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6 text-gray-700">
                    <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
              <div class="flex-1 overflow-y-auto py-4">
                @if(Auth::check() && Auth::user()->can_approve == 1)
                  <a href="{{ route('admin') }}" class="block px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Home</a>
                  <a href="{{ route('approval') }}" class="block px-4 py-3 text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] bg-clip-text text-transparent hover:bg-gray-50 transition-colors">Approval</a>
                  <a href="{{ route('user.management') }}" class="block px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">User Management</a>
                @endif
              </div>
            </div>
          </div>
        </div>

        <div class="relative">
          <button onclick="toggleUserDropdown()" id="userDropdownBtn" class="inline-flex items-center gap-1.5 md:gap-2 rounded-xl md:rounded-2xl bg-gradient-to-r from-[#FF3B30] via-[#FF6B2C] to-[#FF9F0A] px-3 md:px-6 py-2 md:py-2.5 text-xs md:text-sm font-semibold text-white shadow-lg shadow-orange-300/50 drop-shadow-lg ring-1 ring-white/30 transition-all hover:shadow-xl hover:shadow-orange-400/50 hover:drop-shadow-xl hover:scale-105 active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5 md:h-4 md:w-4 opacity-95">
              <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5Z"/>
            </svg>
            <span class="tracking-tight hidden sm:inline">{{ Auth::user()->username }}</span>
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

  <!-- Main -->
  <main class="max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-6 md:py-8">
    <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6">Approval</h1>

    <section class="space-y-6 sm:space-y-8 md:space-y-10">
      <!-- Merchant Table -->
      <div>
        <h2 class="text-base sm:text-lg md:text-xl font-bold text-gray-800 mb-3 sm:mb-4">Merchant</h2>

        <!-- Mobile Card Layout -->
        <div class="md:hidden space-y-3">
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="space-y-3">
              <div>
                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Uploader</p>
                <p class="text-sm font-medium text-gray-800">Budi Santoso</p>
              </div>
              <div class="flex flex-col gap-2">
                <button
                  class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold rounded-full text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] shadow-sm hover:shadow-md hover:brightness-105 active:scale-[0.98] transition-all duration-300 ease-out"
                  onclick="openPreviewModal('Merchant', 'Budi Santoso', 'Restaurant ABC', 'SKU123', '20%', '500 pts')">
                  <i class="fas fa-eye text-xs"></i><span>View Table</span>
                </button>
                <div class="relative w-full bp-dd" data-default="Approve">
                  <input type="hidden" name="approval_merchant_1" value="Approve"/>
                  <button type="button"
                    class="bp-btn w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-gray-800 bg-white border border-gray-300 ring-2 ring-gray-200/60 shadow-sm hover:shadow-md active:scale-[0.98] transition-all duration-200">
                    <span class="bp-label">Approve</span>
                    <i class="fa-solid fa-chevron-down text-[11px] opacity-90 transition-transform"></i>
                  </button>
                  <div class="bp-menu hidden absolute left-0 right-0 z-40 mt-2 w-full rounded-2xl bg-white text-gray-800 shadow-xl ring-1 ring-black/10 border border-gray-200 overflow-hidden">
                    <div class="py-1">
                      <button type="button" data-value="Approve" class="bp-item w-full text-left px-4 py-2 text-sm flex items-center gap-3 hover:bg-gray-100">
                        <i class="fa-solid fa-check text-[12px] text-emerald-300"></i><span>Approve</span>
                      </button>
                      <button type="button" data-value="Reject" class="bp-item w-full text-left px-4 py-2 text-sm flex items-center gap-3 hover:bg-gray-100">
                        <i class="fa-solid fa-xmark text-[12px] text-rose-300"></i><span>Reject</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="space-y-3">
              <div>
                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Uploader</p>
                <p class="text-sm font-medium text-gray-800">Siti Aminah</p>
              </div>
              <div class="flex flex-col gap-2">
                <button
                  class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold rounded-full text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] shadow-sm hover:shadow-md hover:brightness-105 active:scale-[0.98] transition-all duration-300 ease-out"
                  onclick="openPreviewModal('Merchant', 'Siti Aminah', 'Cinema XYZ', 'SKU456', '15%', '300 pts')">
                  <i class="fas fa-eye text-xs"></i><span>View Table</span>
                </button>
                <div class="relative w-full bp-dd" data-default="Reject">
                  <input type="hidden" name="approval_merchant_2" value="Reject"/>
                  <button type="button"
                    class="bp-btn w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-gray-800 bg-white border border-gray-300 ring-2 ring-gray-200/60 shadow-sm hover:shadow-md active:scale-[0.98] transition-all duration-200">
                    <span class="bp-label">Reject</span>
                    <i class="fa-solid fa-chevron-down text-[11px] opacity-90 transition-transform"></i>
                  </button>
                  <div class="bp-menu hidden absolute left-0 right-0 z-40 mt-2 w-full rounded-2xl bg-white text-gray-800 shadow-xl ring-1 ring-black/10 border border-gray-200 overflow-hidden">
                    <div class="py-1">
                      <button type="button" data-value="Approve" class="bp-item w-full text-left px-4 py-2 text-sm flex items-center gap-3 hover:bg-gray-100">
                        <i class="fa-solid fa-check text-[12px] text-emerald-300"></i><span>Approve</span>
                      </button>
                      <button type="button" data-value="Reject" class="bp-item w-full text-left px-4 py-2 text-sm flex items-center gap-3 hover:bg-gray-100">
                        <i class="fa-solid fa-xmark text-[12px] text-rose-300"></i><span>Reject</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full table-fixed border border-gray-200 border-collapse">
              <colgroup>
                <col style="width:33.3333%" />
                <col style="width:33.3333%" />
                <col style="width:33.3333%" />
              </colgroup>

              <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                  <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border border-gray-200">Uploader</th>
                  <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border border-gray-200">Table</th>
                  <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border border-gray-200">Approve</th>
                </tr>
              </thead>

              <tbody class="bg-white">
                <tr class="hover:bg-gray-50 transition-colors">
                  <td class="px-3 py-2 text-sm text-gray-800 text-center border border-gray-200">Budi Santoso</td>
                  <td class="px-3 py-2 text-center border border-gray-200">
                    <button
                      class="inline-flex items-center gap-2 px-4 py-1.5 text-sm font-semibold rounded-full text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] shadow-sm hover:shadow-md hover:brightness-105 active:scale-[0.98] transition-all duration-300 ease-out pv-anim"
                      onclick="openPreviewModal('Merchant', 'Budi Santoso', 'Restaurant ABC', 'SKU123', '20%', '500 pts')" title="View">
                      <i class="fas fa-eye text-xs"></i><span>View</span>
                    </button>
                  </td>
                  <td class="px-3 py-2 text-center border border-gray-200">
                    <!-- Dropdown approve/reject (default Approved) -->
                    <div class="relative inline-block bp-dd" data-default="Approve">
                      <input type="hidden" name="approval_merchant_1" value="Approve"/>
                      <button type="button"
                        class="bp-btn inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-gray-800 bg-white border border-gray-300 ring-2 ring-gray-200/60 shadow-sm hover:shadow-md active:scale-[0.98] transition-all duration-200"
                        aria-haspopup="listbox" aria-expanded="false">
                        <span class="bp-label">Approve</span>
                        <i class="fa-solid fa-chevron-down text-[11px] opacity-90 transition-transform"></i>
                      </button>

                      <!-- template menu (akan dipindah ke portal oleh JS) -->
                      <div class="bp-menu hidden absolute right-0 z-40 mt-2 w-48 sm:w-56 rounded-2xl bg-white text-gray-800 shadow-xl ring-1 ring-black/10 border border-gray-200 overflow-hidden">
                        <div class="py-1">
                          <button type="button" data-value="Approve" class="bp-item w-full text-left px-4 py-2 text-sm flex items-center gap-3 hover:bg-gray-100">
                            <i class="fa-solid fa-check text-[12px] text-emerald-300"></i><span>Approve</span>
                          </button>
                          <button type="button" data-value="Reject" class="bp-item w-full text-left px-4 py-2 text-sm flex items-center gap-3 hover:bg-gray-100">
                            <i class="fa-solid fa-xmark text-[12px] text-rose-300"></i><span>Reject</span>
                          </button>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>

                <tr class="hover:bg-gray-50 transition-colors">
                  <td class="px-3 py-2 text-sm text-gray-800 text-center border border-gray-200">Siti Aminah</td>
                  <td class="px-3 py-2 text-center border border-gray-200">
                    <button
                      class="inline-flex items-center gap-2 px-4 py-1.5 text-sm font-semibold rounded-full text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] shadow-sm hover:shadow-md hover:brightness-105 active:scale-[0.98] transition-all duration-300 ease-out pv-anim"
                      onclick="openPreviewModal('Merchant', 'Siti Aminah', 'Cinema XYZ', 'SKU456', '15%', '300 pts')" title="View">
                      <i class="fas fa-eye text-xs"></i><span>View</span>
                    </button>
                  </td>
                  <td class="px-3 py-2 text-center border border-gray-200">
                    <!-- Dropdown approve/reject (default Reject) -->
                    <div class="relative inline-block bp-dd" data-default="Reject">
                      <input type="hidden" name="approval_merchant_2" value="Reject"/>
                      <button type="button"
                        class="bp-btn inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-gray-800 bg-white border border-gray-300 ring-2 ring-gray-200/60 shadow-sm hover:shadow-md active:scale-[0.98] transition-all duration-200"
                        aria-haspopup="listbox" aria-expanded="false">
                        <span class="bp-label">Reject</span>
                        <i class="fa-solid fa-chevron-down text-[11px] opacity-90 transition-transform"></i>
                      </button>

                      <!-- template menu (akan dipindah ke portal oleh JS) -->
                      <div class="bp-menu hidden absolute right-0 z-40 mt-2 w-48 sm:w-56 rounded-2xl bg-white text-gray-800 shadow-xl ring-1 ring-black/10 border border-gray-200 overflow-hidden">
                        <div class="py-1">
                          <button type="button" data-value="Approve" class="bp-item w-full text-left px-4 py-2 text-sm flex items-center gap-3 hover:bg-gray-100">
                            <i class="fa-solid fa-check text-[12px] text-emerald-300"></i><span>Approve</span>
                          </button>
                          <button type="button" data-value="Reject" class="bp-item w-full text-left px-4 py-2 text-sm flex items-center gap-3 hover:bg-gray-100">
                            <i class="fa-solid fa-xmark text-[12px] text-rose-300"></i><span>Reject</span>
                          </button>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
              <div class="text-sm text-gray-700">
                Showing <span class="font-semibold text-gray-900">1</span> to
                <span class="font-semibold text-gray-900">2</span> of
                <span class="font-semibold text-gray-900">2</span> results
              </div>

              <nav class="flex items-center gap-1" aria-label="Pagination">
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all"><i class="fas fa-chevron-left text-xs"></i></a>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border-2 border-orange-500 bg-gradient-to-r from-orange-50 to-red-50 text-sm font-semibold text-orange-600 shadow-sm">1</a>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all">2</a>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all"><i class="fas fa-chevron-right text-xs"></i></a>
              </nav>
            </div>
          </div>
        </div>
      </div>

      <!-- Merchandise Table -->
      <div>
        <h2 class="text-base sm:text-lg md:text-xl font-bold text-gray-800 mb-3 sm:mb-4 mt-6 sm:mt-8 md:mt-12">Merchandise</h2>

        <!-- Mobile Card Layout -->
        <div class="md:hidden space-y-3">
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="space-y-3">
              <div>
                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Uploader</p>
                <p class="text-sm font-medium text-gray-800">Andre Pratama</p>
              </div>
              <div class="flex flex-col gap-2">
                <button
                  class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold rounded-full text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] shadow-sm hover:shadow-md hover:brightness-105 active:scale-[0.98] transition-all duration-300 ease-out"
                  onclick="openPreviewModal('Merchandise', 'Andre Pratama', 'T-Shirt Limited', 'SKU-M01', '—', '200 pts')">
                  <i class="fas fa-eye text-xs"></i><span>View Table</span>
                </button>
                <div class="relative w-full bp-dd" data-default="Approve">
                  <input type="hidden" name="approval_merchandise_1" value="Approve"/>
                  <button type="button"
                    class="bp-btn w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-gray-800 bg-white border border-gray-300 ring-2 ring-gray-200/60 shadow-sm hover:shadow-md active:scale-[0.98] transition-all duration-200">
                    <span class="bp-label">Approve</span>
                    <i class="fa-solid fa-chevron-down text-[11px] opacity-90 transition-transform"></i>
                  </button>
                  <div class="bp-menu hidden absolute left-0 right-0 z-40 mt-2 w-full rounded-2xl bg-white text-gray-800 shadow-xl ring-1 ring-black/10 border border-gray-200 overflow-hidden">
                    <div class="py-1">
                      <button type="button" data-value="Approve" class="bp-item w-full text-left px-4 py-2 text-sm flex items-center gap-3 hover:bg-gray-100">
                        <i class="fa-solid fa-check text-[12px] text-emerald-300"></i><span>Approve</span>
                      </button>
                      <button type="button" data-value="Reject" class="bp-item w-full text-left px-4 py-2 text-sm flex items-center gap-3 hover:bg-gray-100">
                        <i class="fa-solid fa-xmark text-[12px] text-rose-300"></i><span>Reject</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full table-fixed border border-gray-200 border-collapse">
              <colgroup>
                <col style="width:33.3333%"/>
                <col style="width:33.3333%"/>
                <col style="width:33.3333%"/>
              </colgroup>

              <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                  <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border border-gray-200">Uploader</th>
                  <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border border-gray-200">Table</th>
                  <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border border-gray-200">Approve</th>
                </tr>
              </thead>

              <tbody class="bg-white">
                <tr class="hover:bg-gray-50 transition-colors">
                  <td class="px-3 py-2 text-sm text-gray-800 text-center border border-gray-200">Andre Pratama</td>
                  <td class="px-3 py-2 text-center border border-gray-200">
                    <button
                      class="inline-flex items-center gap-2 px-4 py-1.5 text-sm font-semibold rounded-full text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] shadow-sm hover:shadow-md hover:brightness-105 active:scale-[0.98] transition-all duration-300 ease-out pv-anim"
                      onclick="openPreviewModal('Merchandise', 'Andre Pratama', 'T-Shirt Limited', 'SKU-M01', '—', '200 pts')">
                      <i class="fas fa-eye text-xs"></i><span>View</span>
                    </button>
                  </td>
                  <td class="px-3 py-2 text-center border border-gray-200">
                    <label class="sr-only">Approval</label>

                    <!-- Custom dropdown – default Approve -->
                    <div class="relative inline-block bp-dd" data-default="Approve">
                      <input type="hidden" name="approval_merchandise_1" value="Approve"/>
                      <button type="button"
                        class="bp-btn inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-gray-800 bg-white border border-gray-300 ring-2 ring-gray-200/60 shadow-sm hover:shadow-md active:scale-[0.98] transition-all duration-200"
                        aria-haspopup="listbox" aria-expanded="false">
                        <span class="bp-label">Approve</span>
                        <i class="fa-solid fa-chevron-down text-[11px] opacity-90 transition-transform"></i>
                      </button>

                      <!-- template menu (akan dipindah ke portal oleh JS) -->
                      <div class="bp-menu hidden absolute right-0 z-40 mt-2 w-48 sm:w-56 rounded-2xl bg-white text-gray-800 shadow-xl ring-1 ring-black/10 border border-gray-200 overflow-hidden">
                        <div class="py-1">
                          <button type="button" data-value="Approve" class="bp-item w-full text-left px-4 py-2 text-sm flex items-center gap-3 hover:bg-gray-100">
                            <i class="fa-solid fa-check text-[12px] text-emerald-300"></i><span>Approve</span>
                          </button>
                          <button type="button" data-value="Reject" class="bp-item w-full text-left px-4 py-2 text-sm flex items-center gap-3 hover:bg-gray-100">
                            <i class="fa-solid fa-xmark text-[12px] text-rose-300"></i><span>Reject</span>
                          </button>
                        </div>
                      </div>
                    </div>
                    <!-- /Custom dropdown -->

                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
              <div class="text-sm text-gray-700">
                Showing <span class="font-semibold text-gray-900">1</span> to
                <span class="font-semibold text-gray-900">1</span> of
                <span class="font-semibold text-gray-900">1</span> results
              </div>

              <nav class="flex items-center gap-1" aria-label="Pagination">
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all"><i class="fas fa-chevron-left text-xs"></i></a>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border-2 border-orange-500 bg-gradient-to-r from-orange-50 to-red-50 text-sm font-semibold text-orange-600 shadow-sm">1</a>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all"><i class="fas fa-chevron-right text-xs"></i></a>
              </nav>
            </div>
          </div>
        </div>
      </div>

      <!-- Telkom Packages Table -->
      <div>
        <h2 class="text-base sm:text-lg md:text-xl font-bold text-gray-800 mb-3 sm:mb-4 mt-6 sm:mt-8 md:mt-12">Telkom Packages</h2>

        <!-- Mobile Card Layout -->
        <div class="md:hidden space-y-3">
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="space-y-3">
              <div>
                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Uploader</p>
                <p class="text-sm font-medium text-gray-800">Rina Kurnia</p>
              </div>
              <div class="flex flex-col gap-2">
                <button
                  class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold rounded-full text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] shadow-sm hover:shadow-md hover:brightness-105 active:scale-[0.98] transition-all duration-300 ease-out"
                  onclick="openPreviewModal('Telkom Packages', 'Rina Kurnia', 'Combo OMG 10GB', 'SKU-T01', '—', '150 pts')">
                  <i class="fas fa-eye text-xs"></i><span>View Table</span>
                </button>
                <div class="relative w-full bp-dd" data-default="Reject">
                  <input type="hidden" name="approval_telkom_1" value="Reject"/>
                  <button type="button"
                    class="bp-btn w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-gray-800 bg-white border border-gray-300 ring-2 ring-gray-200/60 shadow-sm hover:shadow-md active:scale-[0.98] transition-all duration-200">
                    <span class="bp-label">Reject</span>
                    <i class="fa-solid fa-chevron-down text-[11px] opacity-90 transition-transform"></i>
                  </button>
                  <div class="bp-menu hidden absolute left-0 right-0 z-40 mt-2 w-full rounded-2xl bg-white text-gray-800 shadow-xl ring-1 ring-black/10 border border-gray-200 overflow-hidden">
                    <div class="py-1">
                      <button type="button" data-value="Approve" class="bp-item w-full text-left px-4 py-2 text-sm flex items-center gap-3 hover:bg-gray-100">
                        <i class="fa-solid fa-check text-[12px] text-emerald-300"></i><span>Approve</span>
                      </button>
                      <button type="button" data-value="Reject" class="bp-item w-full text-left px-4 py-2 text-sm flex items-center gap-3 hover:bg-gray-100">
                        <i class="fa-solid fa-xmark text-[12px] text-rose-300"></i><span>Reject</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full table-fixed border border-gray-200 border-collapse">
              <colgroup>
                <col style="width:33.3333%"/>
                <col style="width:33.3333%"/>
                <col style="width:33.3333%"/>
              </colgroup>

              <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                  <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border border-gray-200">Uploader</th>
                  <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border border-gray-200">Table</th>
                  <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border border-gray-200">Approve</th>
                </tr>
              </thead>

              <tbody class="bg-white">
                <tr class="hover:bg-gray-50 transition-colors">
                  <td class="px-3 py-2 text-sm text-gray-800 text-center border border-gray-200">Rina Kurnia</td>
                  <td class="px-3 py-2 text-center border border-gray-200">
                    <button
                      class="inline-flex items-center gap-2 px-4 py-1.5 text-sm font-semibold rounded-full text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] shadow-sm hover:shadow-md hover:brightness-105 active:scale-[0.98] transition-all duration-300 ease-out pv-anim"
                      onclick="openPreviewModal('Telkom Packages', 'Rina Kurnia', 'Combo OMG 10GB', 'SKU-T01', '—', '150 pts')">
                      <i class="fas fa-eye text-xs"></i><span>View</span>
                    </button>
                  </td>
                  <td class="px-4 py-2 text-center border border-gray-200">
                    <label class="sr-only">Approval</label>

                    <!-- Custom dropdown – default Reject -->
                    <div class="relative inline-block bp-dd" data-default="Reject">
                      <input type="hidden" name="approval_telkom_1" value="Reject"/>
                      <button type="button"
                        class="bp-btn inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-gray-800 bg-white border border-gray-300 ring-2 ring-gray-200/60 shadow-sm hover:shadow-md active:scale-[0.98] transition-all duration-200"
                        aria-haspopup="listbox" aria-expanded="false">
                        <span class="bp-label">Reject</span>
                        <i class="fa-solid fa-chevron-down text-[11px] opacity-90 transition-transform"></i>
                      </button>

                      <!-- template menu (akan dipindah ke portal oleh JS) -->
                      <div class="bp-menu hidden absolute right-0 z-40 mt-2 w-48 sm:w-56 rounded-2xl bg-white text-gray-800 shadow-xl ring-1 ring-black/10 border border-gray-200 overflow-hidden">
                        <div class="py-1">
                          <button type="button" data-value="Approve" class="bp-item w-full text-left px-4 py-2 text-sm flex items-center gap-3 hover:bg-gray-100">
                            <i class="fa-solid fa-check text-[12px] text-emerald-300"></i><span>Approve</span>
                          </button>
                          <button type="button" data-value="Reject" class="bp-item w-full text-left px-4 py-2 text-sm flex items-center gap-3 hover:bg-gray-100">
                            <i class="fa-solid fa-xmark text-[12px] text-rose-300"></i><span>Reject</span>
                          </button>
                        </div>
                      </div>
                    </div>
                    <!-- /Custom dropdown -->

                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
              <div class="text-sm text-gray-700">
                Showing <span class="font-semibold text-gray-900">1</span> to
                <span class="font-semibold text-gray-900">1</span> of
                <span class="font-semibold text-gray-900">1</span> results
              </div>

              <nav class="flex items-center gap-1" aria-label="Pagination">
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all"><i class="fas fa-chevron-left text-xs"></i></a>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border-2 border-orange-500 bg-gradient-to-r from-orange-50 to-red-50 text-sm font-semibold text-orange-600 shadow-sm">1</a>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all"><i class="fas fa-chevron-right text-xs"></i></a>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Preview Modal -->
    <div id="previewModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4 bg-black bg-opacity-50">
      <div class="fixed inset-0 bg-black opacity-0 transition-opacity duration-300 ease-out"></div>

      <div id="previewModalPanel" class="relative bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[95vh] sm:max-h-[90vh] flex flex-col transform transition-all duration-300 ease-out scale-95 opacity-0">
        <!-- Header -->
        <div class="sticky top-0 z-10 flex justify-between items-center px-4 sm:px-6 py-3 sm:py-4 border-b bg-white rounded-t-xl">
          <h3 class="text-lg sm:text-xl font-semibold text-gray-800 transition-all duration-300 ease-out transform translate-y-2 opacity-0 pv-anim">Preview Data</h3>
          <button type="button" onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0 pv-anim p-1"><i class="fas fa-times text-lg sm:text-xl"></i></button>
        </div>

        <!-- Body -->
        <div class="flex-1 overflow-y-auto">
          <div class="p-3 sm:p-4 md:p-6 space-y-3 sm:space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 md:gap-x-6 md:gap-y-3">
              <div>
                <label class="block text-[15px] font-medium text-gray-700 mb-1">Nama Merchandise</label>
                <input id="pvName" type="text" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:ring-orange-400" readonly />
              </div>
              <div>
                <label class="block text-[15px] font-medium text-gray-700 mb-1">SKU</label>
                <textarea id="pvSku" rows="3" class="w-full px-4 pt-3 border border-gray-300 rounded-lg focus:ring-orange-400 resize-none" readonly></textarea>
              </div>
              <div>
                <label class="block text-[15px] font-medium text-gray-700 mb-1">Redeem Point</label>
                <input id="pvPoint" type="text" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:ring-orange-400" readonly />
              </div>
              <!-- CTA Link -->
              <div>
                <label class="block text-[15px] font-medium text-gray-700 mb-1">CTA</label>
                <a id="pvCta" href="#" target="_blank" class="block w-full px-4 h-12 border border-gray-300 rounded-lg focus:ring-orange-400 text-blue-600 hover:underline flex items-center">
                  <i class="fas fa-link mr-2 text-orange-500"></i>
                  <span id="pvCtaText">https://example.com</span>
                </a>
              </div>
              <div>
                <label class="block text-[15px] font-medium text-gray-700 mb-1">Stock</label>
                <input id="pvStock" type="number" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:ring-orange-400" readonly />
              </div>
              <div>
                <label class="block text-[15px] font-medium text-gray-700 mb-1">Uploader</label>
                <input id="pvUploader" type="text" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:ring-orange-400" readonly />
              </div>
              <div>
                <label class="block text-[15px] font-medium text-gray-700 mb-1">Start Date</label>
                <input id="pvStartDate" type="text" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:ring-orange-400" readonly />
              </div>
              <div>
                <label class="block text-[15px] font-medium text-gray-700 mb-1">End Date</label>
                <input id="pvEndDate" type="text" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:ring-orange-400" readonly />
              </div>
              <div>
                <label class="block text-[15px] font-medium text-gray-700 mb-1">Images</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center text-gray-600 py-6">
                  <i class="fas fa-upload text-2xl mb-2"></i><span id="pvImagesText" class="text-[15px]">Click to upload images</span>
                </div>
              </div>
              <div>
                <label class="block text-[15px] font-medium text-gray-700 mb-1">Gambar Merchandise</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center text-gray-600 py-6">
                  <i class="fas fa-upload text-2xl mb-2"></i><span id="pvImageText" class="text-[15px]">Click to change image</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="sticky bottom-0 z-10 flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 px-4 sm:px-6 py-3 sm:py-4 border-t bg-white rounded-b-xl">
          <button class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors" onclick="closePreviewModal()">Cancel</button>
          <button class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all">OK</button>
        </div>
      </div>
    </div>
  </main>

  <!-- Scripts: 1) Modal  2) Dropdown -->
  <script>
    function openPreviewModal(category, uploader, name, sku, discount, point, stock, cta, startDate, endDate) {
      document.getElementById('pvUploader').value = uploader || '';
      document.getElementById('pvName').value = name || '';
      document.getElementById('pvSku').value = sku || '';
      document.getElementById('pvPoint').value = point || '';
      document.getElementById('pvStock').value = stock || '';
      document.getElementById('pvStartDate').value = startDate || '';
      document.getElementById('pvEndDate').value = endDate || '';

      // CTA link
      const ctaLink = document.getElementById('pvCta');
      const ctaText = document.getElementById('pvCtaText');
      if (cta && cta.trim() !== '') {
        ctaLink.href = cta;
        ctaText.textContent = cta;
        ctaLink.classList.remove('text-gray-400');
        ctaLink.classList.add('text-blue-600');
      } else {
        ctaLink.href = '#';
        ctaText.textContent = 'No CTA link available';
        ctaLink.classList.remove('text-blue-600');
        ctaLink.classList.add('text-gray-400');
      }

      const modal = document.getElementById('previewModal');
      const panel = document.getElementById('previewModalPanel');
      const backdrop = modal.querySelector('div.fixed');

      modal.classList.remove('hidden');
      modal.classList.add('flex');
      document.body.style.overflow = 'hidden';

      setTimeout(() => { backdrop.style.opacity = '0.5'; }, 10);
      setTimeout(() => { panel.style.transform = 'scale(1)'; panel.style.opacity = '1'; }, 50);

      const animEls = panel.querySelectorAll('.pv-anim');
      animEls.forEach((el, index) => {
        setTimeout(() => { el.style.transform = 'translateY(0)'; el.style.opacity = '1'; }, 100 + (index * 30));
      });
    }

    function closePreviewModal() {
      const modal = document.getElementById('previewModal');
      const panel = document.getElementById('previewModalPanel');
      const backdrop = modal.querySelector('div.fixed');
      const animEls = panel.querySelectorAll('.pv-anim');

      animEls.forEach((el, index) => {
        setTimeout(() => { el.style.transform = 'translateY(10px)'; el.style.opacity = '0'; }, index * 20);
      });

      setTimeout(() => { panel.style.transform = 'scale(0.95)'; panel.style.opacity = '0'; }, 100);
      setTimeout(() => { backdrop.style.opacity = '0'; }, 150);

      setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        animEls.forEach(el => { el.style.transform = 'translateY(10px)'; el.style.opacity = '0'; });
      }, 400);
    }
  </script>

  <script>
  /* ========== Custom Dropdown (portal ke <body>) ========== */
  (function () {
    function applyBtnStyle(btn, value) {
      // Reset variant classes
      btn.classList.remove('bg-gradient-to-r','from-[#F81611]','to-[#F0B100]','ring-orange-200/60');
      btn.classList.remove('bg-emerald-600','ring-emerald-200/60');
      btn.classList.remove('bg-rose-600','ring-rose-200/60');

      // Ensure base neutral style is applied when not in approve/reject state
      const neutralClasses = ['bg-white','text-gray-800','border','border-gray-300','ring-gray-200/60'];
      const strongText = 'text-white';

      // Clear text color to avoid conflicts
      btn.classList.remove(strongText);

      if (value === 'Approve') {
        neutralClasses.forEach(c => btn.classList.remove(c));
        btn.classList.add('bg-emerald-600','ring-emerald-200/60',strongText);
      } else if (value === 'Reject') {
        neutralClasses.forEach(c => btn.classList.remove(c));
        btn.classList.add('bg-rose-600','ring-rose-200/60',strongText);
      } else {
        // Default/neutral (white) like admin category dropdown
        neutralClasses.forEach(c => btn.classList.add(c));
      }
    }

    function setState(root, value) {
      const btn = root.querySelector('.bp-btn');
      const label = root.querySelector('.bp-label');
      const hidden = root.querySelector('input[type="hidden"]');
      label.textContent = value;
      hidden.value = value;
      applyBtnStyle(btn, value);
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
      document.body.appendChild(portal);
    }

    function showMenu(dd) {
      const btn   = dd.querySelector('.bp-btn');
      const menuT = dd.querySelector('.bp-menu');
      const caret = dd.querySelector('.fa-chevron-down');

      if (!dd.__portalMenu) {
        const clone = menuT.cloneNode(true);
        clone.classList.remove('absolute');
        clone.classList.add('fixed', 'z-[70]');
        clone.style.top = '0px';
        clone.style.left = '0px';
        clone.style.visibility = 'hidden';
        clone.classList.remove('hidden');
        portal.appendChild(clone);

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

      const rect = btn.getBoundingClientRect();
      menu.style.visibility = 'hidden';
      menu.classList.remove('hidden');
      menu.style.top = '0px';
      menu.style.left = '0px';

      const gap = 8;
      const menuW = menu.offsetWidth || 224;
      const isMobile = window.innerWidth < 768;
      
      let left, top;
      
      if (isMobile) {
        // On mobile, center the menu or align to button
        left = rect.left + window.scrollX;
        top = rect.bottom + gap + window.scrollY;
        // Ensure menu doesn't go off screen
        const maxLeft = window.scrollX + document.documentElement.clientWidth - menuW - 8;
        left = Math.min(left, maxLeft);
        left = Math.max(left, window.scrollX + 8);
      } else {
        // Desktop: align to right edge of button
        left = rect.right - menuW + window.scrollX;
        top = rect.bottom + gap + window.scrollY;
        const maxLeft = window.scrollX + document.documentElement.clientWidth - menuW - 8;
        left = Math.min(left, maxLeft);
        left = Math.max(left, window.scrollX + 8);
      }

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

    document.querySelectorAll('.bp-dd').forEach(dd => {
      const def = dd.getAttribute('data-default') || dd.querySelector('input[type="hidden"]')?.value || 'Approve';
      setState(dd, def);

      const btn   = dd.querySelector('.bp-btn');
      const tmpl  = dd.querySelector('.bp-menu');
      if (tmpl) tmpl.classList.add('hidden');

      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = dd.getAttribute('data-open') === 'true';
        closeAll(dd);
        if (!isOpen) showMenu(dd); else hideMenu(dd);
      });
    });

    document.addEventListener('click', () => closeAll());
    window.addEventListener('resize', () => closeAll());
    window.addEventListener('scroll', () => closeAll(), true);
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeAll(); });
  })();
  </script>
  <script>
    function toggleMobileMenu() {
      const menu = document.getElementById('mobileMenu');
      const panel = document.getElementById('mobileMenuPanel');
      if (!menu || !panel) return;
      
      if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
          panel.classList.remove('-translate-x-full');
        }, 10);
      } else {
        panel.classList.add('-translate-x-full');
        setTimeout(() => {
          menu.classList.add('hidden');
          document.body.style.overflow = '';
        }, 300);
      }
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
      const menu = document.getElementById('mobileMenu');
      const panel = document.getElementById('mobileMenuPanel');
      const btn = document.getElementById('mobileMenuBtn');
      
      if (menu && panel && btn && 
          !panel.contains(event.target) && 
          !btn.contains(event.target) &&
          !menu.classList.contains('hidden')) {
        toggleMobileMenu();
      }
    });

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
