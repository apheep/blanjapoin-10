@php
    // gabungkan query yang sudah ada (misal search/filter) + paksa tab=keyword
    $keywordPaginator = $keywords->appends(array_merge(request()->query(), ['tab' => 'keyword']));
    if (!isset($allMerchants)) {
        $allMerchants = \App\Models\Merchant::orderBy('nama_merchant')->get();
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keyword • {{ $merchant->nama_merchant }} | blanjapoin.id</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            font-feature-settings: 'kern' 1;
            letter-spacing: -0.01em;
        }
        /* Prevent horizontal scroll on mobile */
        html, body {
            overflow-x: hidden;
            max-width: 100%;
        }
        * {
            box-sizing: border-box;
        }
    </style>
</head>
<body class="min-h-screen bg-[#f8fafc]">
<style>
.page-enter{opacity:0;transform:translateY(8px)}
.page-enter-active{opacity:1;transform:translateY(0);transition:opacity .3s ease,transform .3s ease}
.fade-in-up{opacity:0;transform:translateY(10px)}
.fade-in-up.show{opacity:1;transform:translateY(0);transition:opacity .3s ease,transform .3s ease}
</style>

    @if(session('success'))
        <div data-flash-message="{{ session('success') }}" data-flash-type="success" class="hidden"></div>
    @endif
    @if(session('error'))
        <div data-flash-message="{{ session('error') }}" data-flash-type="error" class="hidden"></div>
    @endif
    @if($errors->any())
        <div data-flash-message="{{ $errors->first() }}" data-flash-type="error" class="hidden"></div>
    @endif

<nav id="navbar" class="sticky top-0 z-20 bg-white transition-shadow duration-300 w-full page-enter">
    <div class="mx-auto max-w-7xl px-2 sm:px-4 md:px-6 lg:px-8 py-4 md:py-5 lg:py-6 relative">
     <div class="flex items-center justify-between">
      <div class="flex items-center gap-6">
       <img src="/logo.png" alt="BlanjaPoin" class="h-10 md:h-12 lg:h-14 w-auto" />
      </div>

      <div class="hidden md:flex items-center gap-6 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
      @if(Auth::check() && Auth::user()->can_approve == 1) 
        <a href="{{ route('admin') }}" class="text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] bg-clip-text text-transparent hover:opacity-80 transition-opacity">Home</a>
        <a href="{{ route('user.management') }}" class="text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] bg-clip-text text-transparent hover:opacity-80 transition-opacity">User Management</a>       @endif
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
    </div>
   </nav>


    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6 page-enter">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Overview</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">{{ $merchant->nama_merchant }}</h1>
                <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-gray-600">
                    <span class="inline-flex items-center gap-1">
                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                        {{ $merchant->daerah ?? '-' }}
                    </span>
                    <span class="inline-flex items-center gap-1">
                        <i class="fas fa-tags text-gray-400"></i>
                        {{ $merchant->kategori ?? '-' }}
                    </span>
                    <span class="inline-flex items-center gap-1">
                        <i class="fas fa-key text-gray-400"></i>
                        {{ $keywordPaginator->total() }} Keyword
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin', ['tab' => 'all']) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                    <i class="fas fa-arrow-left text-xs"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex space-x-3">
                <div class="relative">
                    <button id="statusBtnDetail" onclick="toggleStatusDropdownDetail()" class="flex items-center px-4 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-filter mr-2"></i>
                        Status
                        <i class="fas fa-chevron-down ml-2 text-xs"></i>
                    </button>
                    <div id="statusDropdownDetail" class="hidden absolute md:left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl p-3 border border-gray-200 w-56 z-40">
                        <div class="py-1">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-gray-100 hover:to-gray-200 hover:text-gray-800 rounded-lg transition-all duration-300" onclick="filterKeywordByStatusDetail('all'); return false;">All</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-200 hover:text-yellow-900 rounded-lg transition-all duration-300" onclick="filterKeywordByStatusDetail('pending'); return false;">Pending</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-red-100 hover:to-rose-100 hover:text-red-800 rounded-lg transition-all duration-300" onclick="filterKeywordByStatusDetail('reject'); return false;">Rejected</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-green-100 hover:to-emerald-100 hover:text-green-800 rounded-lg transition-all duration-300" onclick="filterKeywordByStatusDetail('approve'); return false;">Approved</a>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="openUploadKeyword()" class="flex items-center px-4 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Keyword
                </button>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-auto">
                    <input type="text" id="keywordSearchDetail" placeholder="Search..." class="w-full sm:w-48 pl-9 pr-4 py-2 text-sm rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                </div>

                @include('partials.date-filter', ['filterId' => 'dateFilterMerchantDetail'])
            </div>
        </div>

        <div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 sticky top-0 z-20 shadow-sm">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                            @if(Auth::check() && Auth::user()->can_approve == 1)
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Approval</th>
                            @endif
                            @if(Auth::check() && Auth::user()->can_approve == 0)
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            @endif
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Merchant</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Produk</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">CTA LINK</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Redeem</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Diskon</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">SKB</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Stock</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Periode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Image</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200" id="keyword-table-body">
                        @forelse($keywordPaginator as $keyword)
                            <tr id="keyword-row-{{ $keyword->id }}" class="hover:bg-gray-50 transition-colors keyword-row" data-category="{{ $keyword->merchant->kategori ?? 'All' }}" data-status="{{ $keyword->status }}" data-start="{{ ($keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('Y-m-d') : '') }}" data-end="{{ ($keyword->end_date ? \Carbon\Carbon::parse($keyword->end_date)->format('Y-m-d') : '') }}">
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                    {{ ($keywordPaginator->currentPage() - 1) * $keywordPaginator->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex space-x-2">
                                        <button type="button"
                                                onclick="openEditKeyword({{ $keyword->id }}, {{ json_encode($keyword) }})"
                                                class="text-blue-600 hover:text-blue-900 transition-colors"
                                                title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button type="button"
                                                onclick="showDeleteConfirmation('Keyword', '{{ $keyword->nama_produk }}', {{ $keyword->id }})"
                                                class="text-red-600 hover:text-red-900 transition-colors"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>

                                @if(Auth::check() && Auth::user()->can_approve == 1)
                                    <td id="keyword-action-{{ $keyword->id }}" class="px-4 py-4">
                                        @if($keyword->status === 'approve')
                                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 font-medium text-sm shadow-sm">
                                                <i class="fas fa-check-circle text-green-600"></i>
                                                <span>Approved</span>
                                            </div>
                                        @elseif($keyword->status === 'reject')
                                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-red-100 to-rose-100 text-red-700 font-medium text-sm shadow-sm">
                                                <i class="fas fa-times text-red-600"></i>
                                                <span>Rejected</span>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2">
                                                <button onclick="showApproveConfirmation('Keyword','{{ $keyword->nama_produk }}',{{ $keyword->id }})" class="p-2.5 rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 text-white hover:from-green-600 hover:to-emerald-700 shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200" title="Approve"><i class="fas fa-check-circle text-sm"></i></button>
                                                <button onclick="showRejectConfirmation('Keyword','{{ $keyword->nama_produk }}',{{ $keyword->id }})" class="p-2.5 rounded-lg bg-gradient-to-r from-red-500 to-rose-600 text-white hover:from-red-600 hover:to-rose-700 shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200" title="Reject"><i class="fas fa-times text-sm"></i></button>
                                            </div>
                                        @endif
                                    </td>
                                @endif
                                @if(Auth::check() && Auth::user()->can_approve == 0)
                                    <td id="keyword-status-{{ $keyword->id }}" class="px-4 py-4">
                                    <span class="status-badge px-2 py-1 text-xs font-semibold rounded-full
                                        @if($keyword->status === 'approve')
                                            bg-green-100 text-green-800
                                        @elseif($keyword->status === 'pending')
                                            bg-yellow-100 text-yellow-800
                                        @elseif($keyword->status === 'reject')
                                            bg-red-100 text-red-800
                                        @endif
                                    ">
                                        {{ ucfirst($keyword->status) }}
                                    </span>
                                </td>
                                @endif


                                <td class="px-4 py-4 text-sm text-gray-900">
                                    <div class="font-medium">{{ $keyword->merchant->nama_merchant ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    <div class="font-medium">{{ $keyword->nama_produk }}</div>
                                </td>
                                <!-- <td class="px-4 py-4 text-sm text-gray-900">
                                    {{ $keyword->cta_link }}
                                </td> -->
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    <a href="{{ $keyword->cta_link }}" target="_blank" class="text-blue-600 hover:underline">{{ $keyword->cta_link }}</a>
                                </td>   
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $keyword->redeem ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $keyword->diskon ?? '-' }}</td>
                                <td class="px-4 py-4 text-xs text-gray-500">{{ $keyword->skb ?? '-' }}</td>
                                <td class="px-4 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ $keyword->stock }}</span>
                                </td>
                                <td class="px-4 py-4 text-xs text-gray-500">
                                    @if($keyword->start_date || $keyword->end_date)
                                        <div>{{ $keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('d/m/Y') : '-' }}</div>
                                        <div>{{ $keyword->end_date ? \Carbon\Carbon::parse($keyword->end_date)->format('d/m/Y') : '-' }}</div>
                                    @else
                                        <div>-</div>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if($keyword->image)
                                        <img src="{{ asset('storage/' . $keyword->image) }}" 
                                             alt="{{ $keyword->nama_produk }}" 
                                             class="h-10 w-16 object-cover rounded">
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-4 py-4 text-center text-sm text-gray-500">
                                    Belum ada data keyword.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($keywordPaginator->hasPages())
                <div class="bg-white px-4 py-4 border-t border-gray-200 flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Menampilkan <span class="font-semibold">{{ $keywordPaginator->firstItem() }}</span> hingga <span class="font-semibold">{{ $keywordPaginator->lastItem() }}</span> dari <span class="font-semibold">{{ $keywordPaginator->total() }}</span> data
                    </div>
                    <div class="flex items-center space-x-2">
                        @if ($keywordPaginator->onFirstPage())
                            <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                        @else
                            <a href="{{ $keywordPaginator->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        @foreach ($keywordPaginator->getUrlRange(1, $keywordPaginator->lastPage()) as $page => $url)
                            @if ($page == $keywordPaginator->currentPage())
                                <button disabled class="px-3 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg">
                                    {{ $page }}
                                </button>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if ($keywordPaginator->hasMorePages())
                            <a href="{{ $keywordPaginator->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="md:hidden space-y-3" id="keyword-cards-container">
            @forelse($keywordPaginator as $keyword)
                <div id="keyword-card-{{ $keyword->id }}" class="bg-white rounded-xl shadow-sm hover:shadow-md border-l-3 transition-all duration-200 keyword-row
                    @if($keyword->status === 'approve')
                        border-l-green-500
                    @elseif($keyword->status === 'pending')
                        border-l-yellow-500
                    @elseif($keyword->status === 'reject')
                        border-l-red-500
                    @else
                        border-l-gray-400
                    @endif
                " data-category="{{ $keyword->merchant->kategori ?? 'All' }}" data-status="{{ $keyword->status }}" data-start="{{ ($keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('Y-m-d') : '') }}" data-end="{{ ($keyword->end_date ? \Carbon\Carbon::parse($keyword->end_date)->format('Y-m-d') : '') }}">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-2.5">
                            <span class="text-sm font-bold text-gray-900">#{{ ($keywordPaginator->currentPage() - 1) * $keywordPaginator->perPage() + $loop->iteration }}</span>
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                                @if($keyword->status === 'approve')
                                    bg-green-100 text-green-800
                                @elseif($keyword->status === 'pending')
                                    bg-yellow-100 text-yellow-800
                                @elseif($keyword->status === 'reject')
                                    bg-red-100 text-red-800
                                @endif
                            ">
                                {{ ucfirst($keyword->status) }}
                            </span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <button type="button"
                                    onclick="openEditKeyword({{ $keyword->id }}, {{ json_encode($keyword) }})"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                    title="Edit">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            <button type="button"
                                    onclick="showDeleteConfirmation('Keyword', '{{ $keyword->nama_produk }}', {{ $keyword->id }})"
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                    title="Hapus">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="bg-gray-50 rounded-lg p-2.5 border border-gray-100">
                            <p class="text-[10px] text-gray-500 font-medium mb-1 uppercase tracking-wide">Merchant</p>
                            <p class="text-xs font-bold text-gray-900 truncate" title="{{ $keyword->merchant->nama_merchant ?? '-' }}">{{ $keyword->merchant->nama_merchant ?? '-' }}</p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-2.5 border border-blue-100">
                            <p class="text-[10px] text-blue-600 font-medium mb-1 uppercase tracking-wide">Stock</p>
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-bold rounded-full bg-blue-600 text-white">{{ $keyword->stock }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-[10px] text-gray-500 font-medium mb-1.5 uppercase tracking-wide">Produk</p>
                        <p class="text-sm font-semibold text-gray-900 leading-relaxed">{{ $keyword->nama_produk }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="bg-gray-50 rounded-lg p-2 border border-gray-100">
                            <p class="text-[10px] text-gray-500 font-medium mb-1">Redeem</p>
                            <p class="text-xs font-bold text-gray-900">{{ $keyword->redeem ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2 border border-gray-100">
                            <p class="text-[10px] text-gray-500 font-medium mb-1">Diskon</p>
                            <p class="text-xs font-bold text-gray-900">{{ $keyword->diskon ?? '-' }}</p>
                        </div>
                    </div>

                    @if($keyword->start_date || $keyword->end_date)
                    <div class="mb-4">
                        <p class="text-[10px] text-gray-500 font-medium mb-1 uppercase tracking-wide">Periode</p>
                        <p class="text-xs font-medium text-gray-700">
                            {{ $keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('d/m/Y') : '-' }} - 
                            {{ $keyword->end_date ? \Carbon\Carbon::parse($keyword->end_date)->format('d/m/Y') : '-' }}
                        </p>
                    </div>
                    @endif

                    @if($keyword->cta_link)
                    <div class="mb-4">
                        <p class="text-[10px] text-gray-500 font-medium mb-1 uppercase tracking-wide">CTA Link</p>
                        <a href="{{ $keyword->cta_link }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-700 hover:underline truncate block font-medium" title="{{ $keyword->cta_link }}">{{ $keyword->cta_link }}</a>
                    </div>
                    @endif

                    @if(Auth::check() && Auth::user()->can_approve == 1)
                        <div id="keyword-action-mobile-{{ $keyword->id }}" class="mt-4 pt-4 border-t border-gray-100">
                            @if($keyword->status === 'approve')
                                <div class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-green-50 text-green-700 font-semibold text-xs border border-green-200">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span>Approved</span>
                                </div>
                            @elseif($keyword->status === 'reject')
                                <div class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-red-50 text-red-700 font-semibold text-xs border border-red-200">
                                    <i class="fas fa-times text-red-600"></i>
                                    <span>Rejected</span>
                                </div>
                            @else
                                <div class="flex gap-2.5">
                                    <button onclick="showApproveConfirmation('Keyword','{{ $keyword->nama_produk }}',{{ $keyword->id }})" class="flex-1 flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-lg bg-green-500 text-white hover:bg-green-600 text-xs font-semibold transition-colors shadow-sm" title="Approve">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Approve</span>
                                    </button>
                                    <button onclick="showRejectConfirmation('Keyword','{{ $keyword->nama_produk }}',{{ $keyword->id }})" class="flex-1 flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-lg bg-red-500 text-white hover:bg-red-600 text-xs font-semibold transition-colors shadow-sm" title="Reject">
                                        <i class="fas fa-times"></i>
                                        <span>Reject</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($keyword->image)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <button type="button" 
                                onclick="previewKeywordImage('{{ asset('storage/' . $keyword->image) }}', '{{ basename($keyword->image) }}')"
                                class="w-full h-24 rounded-lg overflow-hidden border border-gray-200 hover:border-gray-300 transition-colors shadow-sm">
                            <img src="{{ asset('storage/' . $keyword->image) }}" 
                                 alt="{{ $keyword->nama_produk }}" 
                                 class="h-full w-full object-cover">
                        </button>
                    </div>
                    @endif
                </div>
            @empty
                <p class="text-sm text-center text-gray-500">Belum ada data keyword.</p>
            @endforelse

            @if($keywordPaginator->hasPages())
                <div class="bg-white px-4 py-4 border-t border-gray-200 flex flex-col items-center justify-center space-y-3 rounded-xl">
                    <div class="text-sm text-gray-600 text-center">
                        Menampilkan <span class="font-semibold">{{ $keywordPaginator->firstItem() }}</span> hingga <span class="font-semibold">{{ $keywordPaginator->lastItem() }}</span> dari <span class="font-semibold">{{ $keywordPaginator->total() }}</span> data
                    </div>

                    <div class="flex items-center space-x-2">
                        @if ($keywordPaginator->onFirstPage())
                            <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                        @else
                            <a href="{{ $keywordPaginator->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        @foreach ($keywordPaginator->getUrlRange(1, $keywordPaginator->lastPage()) as $page => $url)
                            @if ($page == $keywordPaginator->currentPage())
                                <button disabled class="px-3 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg">
                                    {{ $page }}
                                </button>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if ($keywordPaginator->hasMorePages())
                            <a href="{{ $keywordPaginator->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </main>

    @include('partials.upload-modal-keyword')
    @include('partials.edit-modal-keyword')
    @include('partials.delete-confirmation-modal')
    @include('partials.approve-confirmation-modal')

    <script>
        window.fixedMerchantId = {{ $merchant->id }};
        window.fixedMerchantName = {!! json_encode($merchant->nama_merchant) !!};
    </script>

    <script>
        let detailSelectedStatus = 'all';

        function toggleStatusDropdownDetail() {
            const dropdown = document.getElementById('statusDropdownDetail');
            if (!dropdown) return;
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                dropdown.classList.add('fade-in-up');
                setTimeout(() => { dropdown.classList.add('show'); }, 10);
            } else {
                dropdown.classList.add('hidden');
                dropdown.classList.remove('show');
                dropdown.classList.remove('fade-in-up');
            }
        }

        function filterKeywordByStatusDetail(status) {
            const button = document.getElementById('statusBtnDetail');
            if (!button) return;

            if (detailSelectedStatus === status) {
                status = 'all';
            }
            detailSelectedStatus = status;

            let label = 'Status';
            let buttonClasses = 'flex items-center px-4 py-2 text-sm rounded-full border transition-all duration-300 ';

            if (status === 'all') {
                buttonClasses += 'border-gray-300 text-gray-700 hover:bg-gray-50';
            } else if (status === 'pending') {
                label = 'Pending';
                buttonClasses += 'border-yellow-300 text-yellow-800 bg-gradient-to-r from-yellow-100 to-amber-100';
            } else if (status === 'reject') {
                label = 'Rejected';
                buttonClasses += 'border-red-300 text-red-800 bg-gradient-to-r from-red-100 to-rose-100';
            } else if (status === 'approve') {
                label = 'Approved';
                buttonClasses += 'border-green-300 text-green-800 bg-gradient-to-r from-green-100 to-emerald-100';
            }

            button.className = buttonClasses;
            button.innerHTML = `<i class="fas fa-filter mr-2"></i>${label}<i class="fas fa-chevron-down ml-2 text-xs"></i>`;

            const rows = document.querySelectorAll('#keyword-table-body tr.keyword-row');
            rows.forEach(row => {
                const s = (row.dataset.status || '').toLowerCase();
                const normalized = s === 'approved' ? 'approve' : s === 'rejected' ? 'reject' : s;
                row.style.display = (status === 'all' || normalized === status) ? '' : 'none';
            });

            const cards = document.querySelectorAll('#keyword-cards-container .keyword-row');
            cards.forEach(card => {
                const s = (card.dataset.status || '').toLowerCase();
                const normalized = s === 'approved' ? 'approve' : s === 'rejected' ? 'reject' : s;
                card.style.display = (status === 'all' || normalized === status) ? '' : 'none';
            });

            toggleStatusDropdownDetail();
        }

        document.getElementById('keywordSearchDetail')?.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#keyword-table-body tr.keyword-row');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
            const cards = document.querySelectorAll('#keyword-cards-container .keyword-row');
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(query) ? '' : 'none';
            });
        });

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('statusDropdownDetail');
            const button = document.getElementById('statusBtnDetail');
            if (!dropdown || !button) return;
            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Pastikan redirect pada form add/edit tetap ke halaman detail ini
        function applyDetailRedirect() {
            const addRedirect = document.getElementById('keywordRedirectUpload');
            const editRedirect = document.getElementById('keywordRedirectEdit');
            const stayAdd = document.getElementById('keywordStayOnDetailUpload');
            const stayEdit = document.getElementById('keywordStayOnDetailEdit');
            if (addRedirect && window.detailRedirectUrl) addRedirect.value = window.detailRedirectUrl;
            if (editRedirect && window.detailRedirectUrl) editRedirect.value = window.detailRedirectUrl;
            if (stayAdd && window.detailRedirectUrl) stayAdd.value = '1';
            if (stayEdit && window.detailRedirectUrl) stayEdit.value = '1';
        }
        document.addEventListener('DOMContentLoaded', applyDetailRedirect);

        function toggleDateFilter(id) {
            const dropdown = document.getElementById(id);
            if (!dropdown) return;
            dropdown.classList.toggle('hidden');
        }
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
                dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
                dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        }

        document.addEventListener('click', function(event) {
            const btn = document.getElementById('userDropdownBtn');
            const dropdown = document.getElementById('userDropdown');
            if (!btn || !dropdown) return;

            if (!btn.contains(event.target) && !dropdown.contains(event.target) && dropdown.classList.contains('opacity-100')) {
                toggleUserDropdown();
            }
        });

        function previewKeywordImage(imageUrl, fileName) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">${fileName}</h3>
                        <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <img src="${imageUrl}" alt="${fileName}" class="w-full h-auto rounded-lg">
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var nav = document.getElementById('navbar');
            var mainEl = document.querySelector('main');
            if (nav) { nav.classList.add('page-enter-active'); setTimeout(function(){ nav.classList.remove('page-enter'); }, 300); }
            if (mainEl) { mainEl.classList.add('page-enter-active'); setTimeout(function(){ mainEl.classList.remove('page-enter'); }, 300); }
            var cards = document.querySelectorAll('#keyword-cards-container .keyword-row');
            cards.forEach(function(card, i) {
                card.classList.add('fade-in-up');
                setTimeout(function(){ card.classList.add('show'); }, 60 + i * 50);
            });
        });
    </script>
</body>
</html>
