<!-- ======================= DESKTOP / TABLE VIEW ======================= -->
<div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Merchant</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Diskon</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">SKB</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Point</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Stock</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Periode</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">CTA</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Logo Merchant</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Images</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="merchant-table-body">
                <!-- Row 1 -->
                <tr class="hover:bg-gray-50 transition-colors merchant-row" data-category="F&B">
                    <td class="px-4 py-4 text-sm font-medium text-gray-900">1</td>
                    <td class="px-4 py-4 text-sm"><div class="font-medium text-gray-900">Restaurant ABC</div></td>
                    <td class="px-4 py-4"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">F&B</span></td>
                    <td class="px-4 py-4 text-sm text-green-600 font-semibold">20%</td>
                    <td class="px-4 py-4 text-sm text-gray-900">SKU123</td>
                    <td class="px-4 py-4 text-sm text-gray-900">500 pts</td>
                    <td class="px-4 py-4"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">100</span></td>
                    <td class="px-4 py-4 text-xs text-gray-500"><div>01/01/2025</div><div>31/12/2025</div></td>
                    <td class="px-4 py-4 text-sm"><a href="https://example.com" target="_blank" class="text-blue-600 hover:underline">Link</a></td>
                    <td class="px-4 py-4"><img src="{{ asset('storage/logo/merchant1.png') }}" class="h-10 w-16 object-cover rounded"></td>
                    <td class="px-4 py-4 text-gray-500"><div class="flex space-x-1"><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div></div></td>
                    <td class="px-4 py-4">
                        <div class="flex space-x-2">
                            <button onclick="openEditMerchant(1, {nama:'Restaurant ABC', kategori:'F&B', diskon:'20', skb:'SKU123', redeem_point:'500', stock:100, start_date:'01/01/2025', end_date:'31/12/2025', cta:'https://example.com'})" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                            <button onclick="showDeleteConfirmation('Merchant','Restaurant ABC','1')" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- Row 2 -->
                <tr class="hover:bg-gray-50 transition-colors merchant-row" data-category="Entertain">
                    <td class="px-4 py-4 text-sm font-medium text-gray-900">2</td>
                    <td class="px-4 py-4 text-sm"><div class="font-medium text-gray-900">Cinema XYZ</div></td>
                    <td class="px-4 py-4"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Entertain</span></td>
                    <td class="px-4 py-4 text-sm text-green-600 font-semibold">15%</td>
                    <td class="px-4 py-4 text-sm text-gray-900">SKU456</td>
                    <td class="px-4 py-4 text-sm text-gray-900">300 pts</td>
                    <td class="px-4 py-4"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">50</span></td>
                    <td class="px-4 py-4 text-xs text-gray-500"><div>01/01/2025</div><div>31/12/2025</div></td>
                    <td class="px-4 py-4 text-sm"><a href="https://example.com" target="_blank" class="text-blue-600 hover:underline">Link</a></td>
                    <td class="px-4 py-4"><img src="{{ asset('storage/logo/merchant2.png') }}" class="h-10 w-16 object-cover rounded"></td>
                    <td class="px-4 py-4 text-gray-500"><div class="flex space-x-1"><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div></div></td>
                    <td class="px-4 py-4">
                        <div class="flex space-x-2">
                            <button onclick="openEditMerchant(2, {nama:'Cinema XYZ', kategori:'Entertain', diskon:'15', skb:'SKU456', redeem_point:'300', stock:50, start_date:'01/01/2025', end_date:'31/12/2025', cta:'https://example.com'})" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                            <button onclick="showDeleteConfirmation('Merchant','Cinema XYZ','2')" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination (unchanged desktop) -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-4 border-t border-gray-200">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-700">
                Showing <span class="font-semibold text-gray-900">1</span> to <span class="font-semibold text-gray-900">2</span> of
                <span class="font-semibold text-gray-900">10</span> results
            </div>
            <nav class="flex items-center gap-1" aria-label="Pagination">
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all"><i class="fas fa-chevron-left text-xs"></i></a>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border-2 border-orange-500 bg-gradient-to-r from-orange-50 to-red-50 text-sm font-semibold text-orange-600 shadow-sm">1</a>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all">2</a>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all">3</a>
                <span class="inline-flex items-center justify-center w-9 h-9 text-gray-500">...</span>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all">5</a>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all"><i class="fas fa-chevron-right text-xs"></i></a>
            </nav>
        </div>
    </div>
</div>

<!-- ======================= MOBILE CARD VIEW (LOGO FIX + PAGINATION) ======================= -->
<div class="md:hidden space-y-4 mt-4">
    <!-- Card 1 -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow merchant-row" data-category="F&B">
        <div class="flex items-start justify-between">
            <div class="flex items-start space-x-3">
                <!-- LOGO diperlebar -->
                <img src="{{ asset('storage/logo/transmart.png') }}" alt="Logo" class="h-12 w-16 rounded object-contain">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 leading-tight">Restaurant ABC</h3>
                    <p class="text-xs text-gray-500"><span class="text-green-600 font-semibold">20%</span> Diskon</p>
                </div>
            </div>
            <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full bg-orange-100 text-orange-800">F&B</span>
        </div>

        <div class="mt-2 text-xs text-gray-500">SKU: <span class="font-medium text-gray-700">SKU123</span></div>

        <div class="grid grid-cols-2 gap-2 mt-3">
            <div class="bg-gray-50 rounded-lg p-2">
                <p class="text-[11px] text-gray-500">Point</p>
                <p class="text-sm font-semibold text-gray-900">500 pts</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-2">
                <p class="text-[11px] text-gray-500">Stock</p>
                <p class="text-sm font-semibold text-blue-600">100</p>
            </div>
            <div class="col-span-2 bg-gray-50 rounded-lg p-2">
                <p class="text-[11px] text-gray-500">Periode</p>
                <p class="text-xs text-gray-900">01/01/2025 – 31/12/2025</p>
            </div>
        </div>

        <div class="flex items-center justify-between mt-4 border-t pt-3">
            <a href="https://example.com" target="_blank" class="text-sm font-medium text-blue-600 hover:underline">Lihat Promo</a>
            <div class="flex items-center space-x-3">
                <button onclick="openEditMerchant(1, {nama:'Restaurant ABC', kategori:'F&B', diskon:'20', skb:'SKU123', redeem_point:'500', stock:100, start_date:'01/01/2025', end_date:'31/12/2025', cta:'https://example.com'})" class="text-blue-600 hover:text-blue-800"><i class="fas fa-edit text-sm"></i></button>
                <button onclick="showDeleteConfirmation('Merchant','Restaurant ABC','1')" class="text-red-600 hover:text-red-800"><i class="fas fa-trash text-sm"></i></button>
            </div>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow merchant-row" data-category="Entertain">
        <div class="flex items-start justify-between">
            <div class="flex items-start space-x-3">
                <img src="{{ asset('storage/logo/merchant2.png') }}" alt="Logo" class="h-12 w-16 rounded object-contain">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 leading-tight">Cinema XYZ</h3>
                    <p class="text-xs text-gray-500"><span class="text-green-600 font-semibold">15%</span> Diskon</p>
                </div>
            </div>
            <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full bg-purple-100 text-purple-800">Entertain</span>
        </div>

        <div class="mt-2 text-xs text-gray-500">SKU: <span class="font-medium text-gray-700">SKU456</span></div>

        <div class="grid grid-cols-2 gap-2 mt-3">
            <div class="bg-gray-50 rounded-lg p-2">
                <p class="text-[11px] text-gray-500">Point</p>
                <p class="text-sm font-semibold text-gray-900">300 pts</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-2">
                <p class="text-[11px] text-gray-500">Stock</p>
                <p class="text-sm font-semibold text-blue-600">50</p>
            </div>
            <div class="col-span-2 bg-gray-50 rounded-lg p-2">
                <p class="text-[11px] text-gray-500">Periode</p>
                <p class="text-xs text-gray-900">01/01/2025 – 31/12/2025</p>
            </div>
        </div>

        <div class="flex items-center justify-between mt-4 border-t pt-3">
            <a href="https://example.com" target="_blank" class="text-sm font-medium text-blue-600 hover:underline">Lihat Promo</a>
            <div class="flex items-center space-x-3">
                <button onclick="openEditMerchant(2, {nama:'Cinema XYZ', kategori:'Entertain', diskon:'15', skb:'SKU456', redeem_point:'300', stock:50, start_date:'01/01/2025', end_date:'31/12/2025', cta:'https://example.com'})" class="text-blue-600 hover:text-blue-800"><i class="fas fa-edit text-sm"></i></button>
                <button onclick="showDeleteConfirmation('Merchant','Cinema XYZ','2')" class="text-red-600 hover:text-red-800"><i class="fas fa-trash text-sm"></i></button>
            </div>
        </div>
    </div>

    <!-- MOBILE PAGINATION (copy of desktop style) -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-4 border-t border-gray-200 rounded-xl">
        <div class="flex flex-col items-center justify-center gap-3">
            <div class="text-sm text-gray-700">
                Showing <span class="font-semibold text-gray-900">1</span> to <span class="font-semibold text-gray-900">2</span> of
                <span class="font-semibold text-gray-900">10</span> results
            </div>
            <nav class="flex items-center gap-1" aria-label="Pagination">
                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 bg-white text-xs font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all"><i class="fas fa-chevron-left text-xs"></i></a>
                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border-2 border-orange-500 bg-gradient-to-r from-orange-50 to-red-50 text-xs font-semibold text-orange-600 shadow-sm">1</a>
                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 bg-white text-xs font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all">2</a>
                <span class="inline-flex items-center justify-center w-8 h-8 text-gray-500 text-xs">...</span>
                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 bg-white text-xs font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all">5</a>
                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 bg-white text-xs font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all"><i class="fas fa-chevron-right text-xs"></i></a>
            </nav>
        </div>
    </div>
</div>
