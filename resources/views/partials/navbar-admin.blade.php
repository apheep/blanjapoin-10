<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-white transition-shadow duration-300 w-full" style="position: fixed; top: 0; left: 0; right: 0; z-index: 50; width: 100%;">
    <div class="mx-auto max-w-7xl px-2 sm:px-4 md:px-6 lg:px-8 py-4 md:py-5 lg:py-6 relative">
     <div class="flex items-center justify-between">
  <div class="flex items-center gap-4 md:gap-6">
   <img src="/logo.png" alt="BlanjaPoin" class="h-10 md:h-12 lg:h-14 w-auto" />
  </div>

      <div class="hidden md:flex items-center gap-6 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
      @if(Auth::check() && Auth::user()->can_approve == 1) 
        <a href="{{ route('admin') }}" class="text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] bg-clip-text text-transparent hover:opacity-80 transition-opacity">Home</a>
        <a href="{{ route('user.management') }}" class="text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] bg-clip-text text-transparent hover:opacity-80 transition-opacity">User Management</a>      
        <a href="{{ route('iklan.index') }}" class="text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] bg-clip-text text-transparent hover:opacity-80 transition-opacity">Iklan</a>      
        <a href="{{ route('withdraw.approval') }}" class="text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] bg-clip-text text-transparent hover:opacity-80 transition-opacity">Withdraw Approval</a>      
         @endif
      </div>

       <div class="relative">
   <!-- Mobile Menu Button -->
   <button id="openSidebar" class="md:hidden w-10 h-10 flex items-center justify-center text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
     <i class="fa-solid fa-bars text-xl"></i>
   </button>
   <button onclick="toggleUserDropdown()" id="userDropdownBtn" class="hidden md:inline-flex items-center gap-1.5 md:gap-2 rounded-xl md:rounded-2xl bg-gradient-to-r from-[#FF3B30] via-[#FF6B2C] to-[#FF9F0A] px-4 md:px-6 py-2 md:py-2.5 text-xs md:text-sm font-semibold text-white shadow-lg shadow-orange-300/50 drop-shadow-lg ring-1 ring-white/30 transition-all hover:shadow-xl hover:shadow-orange-400/50 hover:drop-shadow-xl hover:scale-105 active:scale-95">
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
    </div>
</nav>
<!-- Mobile Sidebar -->
<div id="mobileSidebar" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
  <div id="sidebarPanel" class="bg-white w-72 h-full shadow-xl transform translate-x-full transition-transform duration-300 ml-auto flex flex-col">
    <!-- Header -->
    <div class="border-b border-gray-200 p-4">
      <div class="flex justify-between items-center">
        <div class="flex items-center gap-3">
          <img src="/logo.png" alt="BlanjaPoin" class="h-8 w-auto" />
        </div>
        <button id="closeSidebar" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
      <div class="mt-4 pt-4 border-t border-gray-100">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-user text-gray-600"></i>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->username ?? 'User' }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Menu Items -->
    <div class="flex-1 overflow-y-auto py-2">
      @if(Auth::check() && Auth::user()->can_approve == 1)
        <a href="{{ route('admin') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
          <i class="fa-solid fa-home w-5 text-gray-500"></i>
          <span class="font-medium">Home</span>
        </a>
        <a href="{{ route('user.management') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
          <i class="fa-solid fa-users w-5 text-gray-500"></i>
          <span class="font-medium">User Management</span>
        </a>
        <a href="{{ route('iklan.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
          <i class="fa-solid fa-image w-5 text-gray-500"></i>
          <span class="font-medium">Iklan</span>
        </a>
        <a href="{{ route('withdraw.approval') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
          <i class="fa-solid fa-money-bill-transfer w-5 text-gray-500"></i>
          <span class="font-medium">Withdraw Approval</span>
        </a>
      @endif
    </div>

    <!-- Footer / Logout -->
    <div class="border-t border-gray-200 p-4">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
          <i class="fa-solid fa-right-from-bracket text-sm"></i>
          <span>Logout</span>
        </button>
      </form>
    </div>
  </div>
</div>

<script>
    // Add shadow on scroll for navbar
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 10) {
            navbar.classList.add('shadow-md');
        } else {
            navbar.classList.remove('shadow-md');
        }
    });
    
    // Add padding to body to prevent content from going under fixed navbar
    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.getElementById('navbar');
        const navbarHeight = navbar.offsetHeight;
        const mainContent = document.querySelector('main');
        if (mainContent) {
            mainContent.style.paddingTop = navbarHeight + 'px';
        }

        // Mobile Sidebar functionality
        const openSidebar = document.getElementById('openSidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const sidebarPanel = document.getElementById('sidebarPanel');

        // Close sidebar when clicking overlay
        if (mobileSidebar) {
            mobileSidebar.addEventListener('click', (e) => {
                if (e.target === mobileSidebar) {
                    closeSidebarFunc();
                }
            });
        }

        function openSidebarFunc() {
            if (mobileSidebar && sidebarPanel) {
                mobileSidebar.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent body scroll when sidebar is open
                setTimeout(() => {
                    sidebarPanel.classList.remove('translate-x-full');
                }, 10);
            }
        }

        function closeSidebarFunc() {
            if (sidebarPanel && mobileSidebar) {
                sidebarPanel.classList.add('translate-x-full');
                document.body.style.overflow = ''; // Restore body scroll
                setTimeout(() => {
                    mobileSidebar.classList.add('hidden');
                }, 300);
            }
        }

        if (openSidebar) {
            openSidebar.addEventListener('click', openSidebarFunc);
        }
        if (closeSidebar) {
            closeSidebar.addEventListener('click', closeSidebarFunc);
        }

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileSidebar && !mobileSidebar.classList.contains('hidden')) {
                closeSidebarFunc();
            }
        });
    });
</script>
