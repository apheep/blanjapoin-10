<style>
    /* Desktop sidebar */
    #desktopSidebar {
        transition: none;
    }
    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 12px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 500;
        color: #4b5563;
        text-decoration: none;
        transition: background .15s, color .15s;
        width: 100%;
    }
    .sidebar-link:hover {
        background: #fff7ed;
        color: #ea580c;
    }
    .sidebar-link.active {
        background: linear-gradient(135deg, #fff1f0, #fffbeb);
        color: #dc2626;
        font-weight: 600;
        box-shadow: inset 3px 0 0 #F81611;
    }
    .sidebar-link .si {
        width: 16px;
        text-align: center;
        flex-shrink: 0;
        font-size: 13px;
        opacity: 0.5;
        transition: opacity .15s;
    }
    .sidebar-link:hover .si,
    .sidebar-link.active .si { opacity: 1; }
    .sidebar-section {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .08em;
        color: #9ca3af;
        text-transform: uppercase;
        padding: 12px 12px 4px;
    }

    /* Shift body content right on desktop to make room for sidebar */
    @media (min-width: 768px) {
        body { padding-left: 14rem; } /* w-56 = 224px */
    }
</style>

{{-- ── Top Navbar ── --}}
<nav id="navbar" class="fixed top-0 left-0 right-0 z-[30] bg-white transition-shadow duration-300">
    <div class="px-4 md:px-6 py-3 md:py-4 flex items-center justify-between">
        {{-- Logo --}}
        <img src="/logo.png" alt="BlanjaPoin" class="h-10 md:h-11 w-auto" />

        {{-- Right side --}}
        <div class="relative" style="overflow: visible;">
            {{-- Mobile: hamburger --}}
            <button id="openSidebar" class="md:hidden w-10 h-10 flex items-center justify-center text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>

            {{-- Desktop: user dropdown button --}}
            <button onclick="toggleUserDropdown()" id="userDropdownBtn"
                class="hidden md:inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-[#FF3B30] via-[#FF6B2C] to-[#FF9F0A] px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-orange-300/50 ring-1 ring-white/30 transition-all hover:opacity-90 hover:scale-105 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 opacity-90">
                    <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5Z"/>
                </svg>
                <span class="tracking-tight">{{ Auth::user()->username }}</span>
                <svg id="userDropdownArrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5 opacity-90 transition-transform duration-300">
                    <path d="M7 10l5 5 5-5z"/>
                </svg>
            </button>

            {{-- Desktop: user dropdown menu --}}
            <div id="userDropdown"
                class="absolute right-0 mt-2 w-48 rounded-xl bg-white shadow-xl ring-1 ring-neutral-200 overflow-hidden opacity-0 invisible scale-95 origin-top-right transition-all duration-300 ease-out z-[10050]">
                <div class="py-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-3 text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                <path fill-rule="evenodd" d="M7.5 3.75A1.5 1.5 0 006 5.25v13.5a1.5 1.5 0 001.5 1.5h6a1.5 1.5 0 001.5-1.5V15a.75.75 0 011.5 0v3.75a3 3 0 01-3 3h-6a3 3 0 01-3-3V5.25a3 3 0 013-3h6a3 3 0 013 3V9A.75.75 0 0115 9V5.25a1.5 1.5 0 00-1.5-1.5h-6zm10.72 4.72a.75.75 0 011.06 0l3 3a.75.75 0 010 1.06l-3 3a.75.75 0 11-1.06-1.06l1.72-1.72H9a.75.75 0 010-1.5h10.94l-1.72-1.72a.75.75 0 010-1.06z" clip-rule="evenodd"/>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

{{-- ── Desktop Sidebar (md+) ── --}}
@php
    $isTopBanner = request()->routeIs('iklan.index');
    $isHome      = request()->routeIs('admin');
    $isBestOffer = request()->routeIs('spesial-promo.*');
    $isUserMgmt  = request()->routeIs('user.management');
    $isWithdraw  = request()->routeIs('withdraw.approval');
    $isHistory   = request()->routeIs('click.history.*');
    $isCatOrder  = request()->routeIs('category-order.*');
@endphp
<aside id="desktopSidebar" class="hidden md:flex fixed left-0 flex-col w-56 bg-white border-r border-gray-100 shadow-sm z-20">
    <div class="flex-1 overflow-y-auto py-3 px-3">
        <p class="sidebar-section">Menu</p>

        <a href="{{ route('iklan.index') }}" class="sidebar-link {{ $isTopBanner ? 'active' : '' }}">
            <i class="fa-solid fa-image si"></i><span>Top Banner</span>
        </a>

        @if(Auth::check() && Auth::user()->can_approve == 1)
            <a href="{{ route('admin') }}" class="sidebar-link {{ $isHome ? 'active' : '' }}">
                <i class="fa-solid fa-house si"></i><span>Home</span>
            </a>
            <a href="{{ route('spesial-promo.form') }}" class="sidebar-link {{ $isBestOffer ? 'active' : '' }}">
                <i class="fa-solid fa-star si"></i><span>Best Offer</span>
            </a>
            <a href="{{ route('user.management') }}" class="sidebar-link {{ $isUserMgmt ? 'active' : '' }}">
                <i class="fa-solid fa-users si"></i><span>User Management</span>
            </a>
            <a href="{{ route('withdraw.approval') }}" class="sidebar-link {{ $isWithdraw ? 'active' : '' }}">
                <i class="fa-solid fa-money-bill-transfer si"></i><span>Withdraw Approval</span>
            </a>
            <a href="{{ route('click.history.index') }}" class="sidebar-link {{ $isHistory ? 'active' : '' }}">
                <i class="fa-solid fa-clock-rotate-left si"></i><span>Redeem History</span>
            </a>
            <a href="{{ route('category-order.index') }}" class="sidebar-link {{ $isCatOrder ? 'active' : '' }}">
                <i class="fa-solid fa-arrow-up-wide-short si"></i><span>Urutan Kategori</span>
            </a>
        @endif
    </div>

    {{-- Logout --}}
    <div class="px-3 py-3 border-t border-gray-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-link text-red-500 hover:!bg-red-50 hover:!text-red-600">
                <i class="fa-solid fa-right-from-bracket si" style="opacity:1;color:inherit;"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>

{{-- ── Mobile Sidebar (unchanged) ── --}}
<div id="mobileSidebar" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div id="sidebarPanel" class="bg-white w-72 h-full shadow-xl transform translate-x-full transition-transform duration-300 ml-auto flex flex-col">
        <div class="border-b border-gray-200 p-4">
            <div class="flex justify-between items-center">
                <img src="/logo.png" alt="BlanjaPoin" class="h-8 w-auto" />
                <button id="closeSidebar" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-user text-gray-600"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->username ?? 'User' }}</p>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto py-2">
            <a href="{{ route('iklan.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fa-solid fa-image w-5 text-gray-500"></i><span class="font-medium">Top Banner</span>
            </a>
            @if(Auth::check() && Auth::user()->can_approve == 1)
                <a href="{{ route('admin') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fa-solid fa-home w-5 text-gray-500"></i><span class="font-medium">Home</span>
                </a>
                <a href="{{ route('spesial-promo.form') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fa-solid fa-tags w-5 text-gray-500"></i><span class="font-medium">Best Offer</span>
                </a>
                <a href="{{ route('user.management') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fa-solid fa-users w-5 text-gray-500"></i><span class="font-medium">User Management</span>
                </a>
                <a href="{{ route('withdraw.approval') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fa-solid fa-money-bill-transfer w-5 text-gray-500"></i><span class="font-medium">Withdraw Approval</span>
                </a>
                <a href="{{ route('click.history.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fa-solid fa-mouse-pointer w-5 text-gray-500"></i><span class="font-medium">Redeem History</span>
                </a>
                <a href="{{ route('category-order.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fa-solid fa-arrow-up-wide-short w-5 text-gray-500"></i><span class="font-medium">Urutan Kategori</span>
                </a>
            @endif
        </div>

        <div class="border-t border-gray-200 p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fa-solid fa-right-from-bracket text-sm"></i><span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    window.addEventListener('scroll', function () {
        document.getElementById('navbar').classList.toggle('shadow-md', window.scrollY > 10);
    });

    document.addEventListener('DOMContentLoaded', function () {
        const navbar  = document.getElementById('navbar');
        const navbarH = navbar.offsetHeight;

        // Position desktop sidebar below navbar
        const deskSidebar = document.getElementById('desktopSidebar');
        if (deskSidebar) {
            deskSidebar.style.top    = navbarH + 'px';
            deskSidebar.style.height = `calc(100vh - ${navbarH}px)`;
        }

        // Push main content down (top padding) — left offset handled by body CSS
        const main = document.querySelector('main');
        if (main) {
            main.style.paddingTop = navbarH + 'px';
        }

        // ── Mobile sidebar ────────────────────────────────────────────────────
        const openBtn      = document.getElementById('openSidebar');
        const closeBtn     = document.getElementById('closeSidebar');
        const overlay      = document.getElementById('mobileSidebar');
        const panel        = document.getElementById('sidebarPanel');

        function openSidebarFunc() {
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => panel.classList.remove('translate-x-full'), 10);
        }
        function closeSidebarFunc() {
            panel.classList.add('translate-x-full');
            document.body.style.overflow = '';
            setTimeout(() => overlay.classList.add('hidden'), 300);
        }

        if (openBtn)  openBtn.addEventListener('click', openSidebarFunc);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebarFunc);
        if (overlay)  overlay.addEventListener('click', e => { if (e.target === overlay) closeSidebarFunc(); });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && overlay && !overlay.classList.contains('hidden')) closeSidebarFunc();
        });
    });
</script>
