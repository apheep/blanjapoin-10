<!-- ======================= DESKTOP / TABLE VIEW ======================= -->
<div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Merchandise</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">SKB</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Point</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Stock</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Periode</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">CTA</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Gambar Merchandise</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Images</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="merchandise-table-body">

                <!-- Row 1 -->
                <tr class="hover:bg-gray-50 transition-colors merchandise-row" data-category="F&B">
                    <td class="px-4 py-4 text-sm font-medium text-gray-900">1</td>
                    <td class="px-4 py-4 text-sm">
                        <div class="font-medium text-gray-900">Gourmet Gift Box</div>
                    </td>
                    <td class="px-4 py-4 text-xs text-gray-500">Premium gift package</td>
                    <td class="px-4 py-4 text-sm text-gray-900">1000 pts</td>
                    <td class="px-4 py-4"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">50</span></td>
                    <td class="px-4 py-4 text-xs text-gray-500"><div>01/01/2025</div><div>31/12/2025</div></td>
                    <td class="px-4 py-4 text-sm"><a href="https://example.com/giftbox" target="_blank" class="text-blue-600 hover:underline">Link</a></td>
                    <td class="px-4 py-4"><img src="{{ asset('storage/merchandise/giftbox.jpg') }}" class="h-10 w-16 object-cover rounded"></td>
                    <td class="px-4 py-4 text-gray-500"><div class="flex space-x-1"><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div></div></td>
                    <td class="px-4 py-4">
                        <div class="flex space-x-2">
                            <button onclick="openEditMerchandise(1, {nama:'Gourmet Gift Box', SKB:'Premium gift package', redeem_point:'1000', stock:50, start_date:'01/01/2025', end_date:'31/12/2025', cta:'https://example.com/giftbox'})" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                            <button onclick="showDeleteConfirmation('Merchandise','Gourmet Gift Box','1','Merchandise Gourmet Gift Box akan dihapus dari sistem')" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- Row 2 -->
                <tr class="hover:bg-gray-50 transition-colors merchandise-row" data-category="Entertain">
                    <td class="px-4 py-4 text-sm font-medium text-gray-900">2</td>
                    <td class="px-4 py-4 text-sm">
                        <div class="font-medium text-gray-900">Movie Tickets</div>
                    </td>
                    <td class="px-4 py-4 text-xs text-gray-500">Cinema vouchers</td>
                    <td class="px-4 py-4 text-sm text-gray-900">750 pts</td>
                    <td class="px-4 py-4"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">100</span></td>
                    <td class="px-4 py-4 text-xs text-gray-500"><div>01/02/2025</div><div>30/11/2025</div></td>
                    <td class="px-4 py-4 text-sm"><a href="https://example.com/movietickets" target="_blank" class="text-blue-600 hover:underline">Link</a></td>
                    <td class="px-4 py-4"><img src="{{ asset('storage/merchandise/movie.jpg') }}" class="h-10 w-16 object-cover rounded"></td>
                    <td class="px-4 py-4 text-gray-500"><div class="flex space-x-1"><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div></div></td>
                    <td class="px-4 py-4">
                        <div class="flex space-x-2">
                            <button onclick="openEditMerchandise(2, {nama:'Movie Tickets', SKB:'Cinema vouchers', redeem_point:'750', stock:100, start_date:'01/02/2025', end_date:'30/11/2025', cta:'https://example.com/movietickets'})" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                            <button onclick="showDeleteConfirmation('Merchandise','Movie Tickets','2','Merchandise Movie Tickets akan dihapus dari sistem')" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- Row 3 -->
                <tr class="hover:bg-gray-50 transition-colors merchandise-row" data-category="Vacation">
                    <td class="px-4 py-4 text-sm font-medium text-gray-900">3</td>
                    <td class="px-4 py-4 text-sm">
                        <div class="font-medium text-gray-900">Travel Voucher</div>
                    </td>
                    <td class="px-4 py-4 text-xs text-gray-500">Holiday packages</td>
                    <td class="px-4 py-4 text-sm text-gray-900">2000 pts</td>
                    <td class="px-4 py-4"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">25</span></td>
                    <td class="px-4 py-4 text-xs text-gray-500"><div>01/03/2025</div><div>31/10/2025</div></td>
                    <td class="px-4 py-4 text-sm"><a href="https://example.com/travelvoucher" target="_blank" class="text-blue-600 hover:underline">Link</a></td>
                    <td class="px-4 py-4"><img src="{{ asset('storage/merchandise/travel.jpg') }}" class="h-10 w-16 object-cover rounded"></td>
                    <td class="px-4 py-4 text-gray-500"><div class="flex space-x-1"><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div></div></td>
                    <td class="px-4 py-4">
                        <div class="flex space-x-2">
                            <button onclick="openEditMerchandise(3, {nama:'Travel Voucher', SKB:'Holiday packages', redeem_point:'2000', stock:25, start_date:'01/03/2025', end_date:'31/10/2025', cta:'https://example.com/travelvoucher'})" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                            <button onclick="showDeleteConfirmation('Merchandise','Travel Voucher','3','Merchandise Travel Voucher akan dihapus dari sistem')" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- Row 4 -->
                <tr class="hover:bg-gray-50 transition-colors merchandise-row" data-category="Shopping">
                    <td class="px-4 py-4 text-sm font-medium text-gray-900">4</td>
                    <td class="px-4 py-4 text-sm">
                        <div class="font-medium text-gray-900">Designer Handbag</div>
                    </td>
                    <td class="px-4 py-4 text-xs text-gray-500">Luxury fashion item</td>
                    <td class="px-4 py-4 text-sm text-gray-900">1500 pts</td>
                    <td class="px-4 py-4"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">10</span></td>
                    <td class="px-4 py-4 text-xs text-gray-500"><div>01/04/2025</div><div>30/09/2025</div></td>
                    <td class="px-4 py-4 text-sm"><a href="https://example.com/handbag" target="_blank" class="text-blue-600 hover:underline">Link</a></td>
                    <td class="px-4 py-4"><img src="{{ asset('storage/merchandise/handbag.jpg') }}" class="h-10 w-16 object-cover rounded"></td>
                    <td class="px-4 py-4 text-gray-500"><div class="flex space-x-1"><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div></div></td>
                    <td class="px-4 py-4">
                        <div class="flex space-x-2">
                            <button onclick="openEditMerchandise(4, {nama:'Designer Handbag', SKB:'Luxury fashion item', redeem_point:'1500', stock:10, start_date:'01/04/2025', end_date:'30/09/2025', cta:'https://example.com/handbag'})" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                            <button onclick="showDeleteConfirmation('Merchandise','Designer Handbag','4','Merchandise Designer Handbag akan dihapus dari sistem')" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- Row 5 -->
                <tr class="hover:bg-gray-50 transition-colors merchandise-row" data-category="Beauty & Care">
                    <td class="px-4 py-4 text-sm font-medium text-gray-900">5</td>
                    <td class="px-4 py-4 text-sm">
                        <div class="font-medium text-gray-900">Skincare Set</div>
                    </td>
                    <td class="px-4 py-4 text-xs text-gray-500">Beauty products</td>
                    <td class="px-4 py-4 text-sm text-gray-900">500 pts</td>
                    <td class="px-4 py-4"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">75</span></td>
                    <td class="px-4 py-4 text-xs text-gray-500"><div>01/05/2025</div><div>31/08/2025</div></td>
                    <td class="px-4 py-4 text-sm"><a href="https://example.com/skincare" target="_blank" class="text-blue-600 hover:underline">Link</a></td>
                    <td class="px-4 py-4"><img src="{{ asset('storage/merchandise/skincare.jpg') }}" class="h-10 w-16 object-cover rounded"></td>
                    <td class="px-4 py-4 text-gray-500"><div class="flex space-x-1"><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div></div></td>
                    <td class="px-4 py-4">
                        <div class="flex space-x-2">
                            <button onclick="openEditMerchandise(5, {nama:'Skincare Set', deskripsi:'Beauty products', redeem_point:'500', stock:75, start_date:'01/05/2025', end_date:'31/08/2025', cta:'https://example.com/skincare'})" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                            <button onclick="showDeleteConfirmation('Merchandise','Skincare Set','5','Merchandise Skincare Set akan dihapus dari sistem')" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <!-- Pagination Desktop -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-4 border-t border-gray-200">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-700">
                Showing <span class="font-semibold text-gray-900">1</span> to <span class="font-semibold text-gray-900">5</span> of
                <span class="font-semibold text-gray-900">20</span> results
            </div>
            <nav class="flex items-center gap-1" aria-label="Pagination">
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:text-orange-600 transition-all"><i class="fas fa-chevron-left text-xs"></i></a>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border-2 border-orange-500 bg-gradient-to-r from-orange-50 to-red-50 text-sm text-orange-600 font-semibold shadow-sm">1</a>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:text-orange-600 transition-all">2</a>
                <span class="inline-flex items-center justify-center w-9 h-9 text-gray-500">...</span>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:text-orange-600 transition-all">10</a>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:text-orange-600 transition-all"><i class="fas fa-chevron-right text-xs"></i></a>
            </nav>
        </div>
    </div>
</div>

<!-- ======================= MOBILE CARD VIEW ======================= -->
<div class="md:hidden space-y-4 mt-4">
    @foreach([
        ['id'=>1,'nama'=>'Gourmet Gift Box','desc'=>'Premium gift package','pts'=>'1000 pts','stock'=>50,'periode'=>'01/01/2025 – 31/12/2025','img'=>'giftbox.jpg','cta'=>'https://example.com/giftbox'],
        ['id'=>2,'nama'=>'Movie Tickets','desc'=>'Cinema vouchers','pts'=>'750 pts','stock'=>100,'periode'=>'01/02/2025 – 30/11/2025','img'=>'movie.jpg','cta'=>'https://example.com/movietickets'],
        ['id'=>3,'nama'=>'Travel Voucher','desc'=>'Holiday packages','pts'=>'2000 pts','stock'=>25,'periode'=>'01/03/2025 – 31/10/2025','img'=>'travel.jpg','cta'=>'https://example.com/travelvoucher'],
        ['id'=>4,'nama'=>'Designer Handbag','desc'=>'Luxury fashion item','pts'=>'1500 pts','stock'=>10,'periode'=>'01/04/2025 – 30/09/2025','img'=>'handbag.jpg','cta'=>'https://example.com/handbag'],
        ['id'=>5,'nama'=>'Skincare Set','desc'=>'Beauty products','pts'=>'500 pts','stock'=>75,'periode'=>'01/05/2025 – 31/08/2025','img'=>'skincare.jpg','cta'=>'https://example.com/skincare'],
    ] as $m)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow merchandise-row">
        <div class="flex items-start space-x-3">
            <img src="{{ asset('storage/merchandise/'.$m['img']) }}" alt="Merchandise" class="h-12 w-16 rounded object-contain">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 leading-tight">{{ $m['nama'] }}</h3>
                <p class="text-xs text-gray-500">{{ $m['desc'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 mt-3">
            <div class="bg-gray-50 rounded-lg p-2">
                <p class="text-[11px] text-gray-500">Point</p>
                <p class="text-sm font-semibold text-gray-900">{{ $m['pts'] }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-2">
                <p class="text-[11px] text-gray-500">Stock</p>
                <p class="text-sm font-semibold text-blue-600">{{ $m['stock'] }}</p>
            </div>
            <div class="col-span-2 bg-gray-50 rounded-lg p-2">
                <p class="text-[11px] text-gray-500">Periode</p>
                <p class="text-xs text-gray-900">{{ $m['periode'] }}</p>
            </div>
        </div>

        <div class="flex items-center justify-between mt-4 border-t pt-3">
            <a href="{{ $m['cta'] }}" target="_blank" class="text-sm font-medium text-blue-600 hover:underline">Lihat Produk</a>
            <div class="flex items-center space-x-3">
                <button onclick="openEditMerchandise({{ $m['id'] }}, {nama:'{{ $m['nama'] }}'})" class="text-blue-600 hover:text-blue-800"><i class="fas fa-edit text-sm"></i></button>
                <button onclick="showDeleteConfirmation('Merchandise','{{ $m['nama'] }}','{{ $m['id'] }}','Hapus {{ $m['nama'] }}?')" class="text-red-600 hover:text-red-800"><i class="fas fa-trash text-sm"></i></button>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Pagination Mobile -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-4 border-t border-gray-200 rounded-xl">
        <div class="flex flex-col items-center justify-center gap-3">
            <div class="text-sm text-gray-700">
                Showing <span class="font-semibold text-gray-900">1</span> to <span class="font-semibold text-gray-900">5</span> of
                <span class="font-semibold text-gray-900">20</span> results
            </div>
            <nav class="flex items-center gap-1" aria-label="Pagination">
                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 bg-white text-xs text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:text-orange-600 transition-all"><i class="fas fa-chevron-left text-xs"></i></a>
                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border-2 border-orange-500 bg-gradient-to-r from-orange-50 to-red-50 text-xs font-semibold text-orange-600 shadow-sm">1</a>
                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 bg-white text-xs text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:text-orange-600 transition-all">2</a>
                <span class="inline-flex items-center justify-center w-8 h-8 text-gray-500 text-xs">...</span>
                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 bg-white text-xs text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:text-orange-600 transition-all">10</a>
                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 bg-white text-xs text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:text-orange-600 transition-all"><i class="fas fa-chevron-right text-xs"></i></a>
            </nav>
        </div>
    </div>
</div>
