<!-- ======================= DESKTOP / TABLE VIEW ======================= -->
<div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Paket</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">SKB</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Redeem Point</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Stock</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Periode</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">CTA</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Gambar Paket</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Images</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200" id="telkom-table-body">
                <!-- Row 1 -->
                <tr class="hover:bg-gray-50 transition-colors telkom-row" data-category="Telkomsel Packet">
                    <td class="px-4 py-4 text-sm font-medium text-gray-900">1</td>
                    <td class="px-4 py-4 text-sm">
                        <div class="font-medium text-gray-900">Unlimited Data 10GB</div>
                    </td>
                    <td class="px-4 py-4 text-xs text-gray-500">Paket data internet</td>
                    <td class="px-4 py-4 text-sm text-gray-900">500 pts</td>
                    <td class="px-4 py-4"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">200</span></td>
                    <td class="px-4 py-4 text-xs text-gray-500"><div>01/01/2025</div><div>31/12/2025</div></td>
                    <td class="px-4 py-4 text-sm"><a href="https://example.com/data10gb" target="_blank" class="text-blue-600 hover:underline">Link</a></td>
                    <td class="px-4 py-4"><img src="{{ asset('storage/telkom/data10gb.jpg') }}" class="h-10 w-16 object-cover rounded"></td>
                    <td class="px-4 py-4 text-gray-500"><div class="flex space-x-1"><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div></div></td>
                    <td class="px-4 py-4">
                        <div class="flex space-x-2">
                            <button onclick="openEditTelkom(1)" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                            <button onclick="showDeleteConfirmation('Telkom Package','Unlimited Data 10GB','1','Package Unlimited Data 10GB akan dihapus dari sistem')" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- Row 2 -->
                <tr class="hover:bg-gray-50 transition-colors telkom-row" data-category="Telkomsel Packet">
                    <td class="px-4 py-4 text-sm font-medium text-gray-900">2</td>
                    <td class="px-4 py-4 text-sm">
                        <div class="font-medium text-gray-900">Unlimited Calls 500min</div>
                    </td>
                    <td class="px-4 py-4 text-xs text-gray-500">Paket telepon unlimited</td>
                    <td class="px-4 py-4 text-sm text-gray-900">300 pts</td>
                    <td class="px-4 py-4"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">150</span></td>
                    <td class="px-4 py-4 text-xs text-gray-500"><div>02/01/2025</div><div>30/11/2025</div></td>
                    <td class="px-4 py-4 text-sm"><a href="https://example.com/calls500min" target="_blank" class="text-blue-600 hover:underline">Link</a></td>
                    <td class="px-4 py-4"><img src="{{ asset('storage/telkom/calls500min.jpg') }}" class="h-10 w-16 object-cover rounded"></td>
                    <td class="px-4 py-4 text-gray-500"><div class="flex space-x-1"><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div></div></td>
                    <td class="px-4 py-4">
                        <div class="flex space-x-2">
                            <button onclick="openEditTelkom(2)" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                            <button onclick="showDeleteConfirmation('Telkom Package','Unlimited Calls 500min','2','Package Unlimited Calls 500min akan dihapus dari sistem')" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- Row 3 -->
                <tr class="hover:bg-gray-50 transition-colors telkom-row" data-category="Telkomsel Packet">
                    <td class="px-4 py-4 text-sm font-medium text-gray-900">3</td>
                    <td class="px-4 py-4 text-sm">
                        <div class="font-medium text-gray-900">Combo Package 5GB+300min</div>
                    </td>
                    <td class="px-4 py-4 text-xs text-gray-500">Paket combo data dan telepon</td>
                    <td class="px-4 py-4 text-sm text-gray-900">700 pts</td>
                    <td class="px-4 py-4"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">100</span></td>
                    <td class="px-4 py-4 text-xs text-gray-500"><div>01/03/2025</div><div>31/10/2025</div></td>
                    <td class="px-4 py-4 text-sm"><a href="https://example.com/combo5gb" target="_blank" class="text-blue-600 hover:underline">Link</a></td>
                    <td class="px-4 py-4"><img src="{{ asset('storage/telkom/combo5gb.jpg') }}" class="h-10 w-16 object-cover rounded"></td>
                    <td class="px-4 py-4 text-gray-500"><div class="flex space-x-1"><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div></div></td>
                    <td class="px-4 py-4">
                        <div class="flex space-x-2">
                            <button onclick="openEditTelkom(3)" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                            <button onclick="showDeleteConfirmation('Telkom Package','Combo Package 5GB+300min','3','Package Combo Package 5GB+300min akan dihapus dari sistem')" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- Row 4 -->
                <tr class="hover:bg-gray-50 transition-colors telkom-row" data-category="Telkomsel Packet">
                    <td class="px-4 py-4 text-sm font-medium text-gray-900">4</td>
                    <td class="px-4 py-4 text-sm">
                        <div class="font-medium text-gray-900">Unlimited Data 20GB</div>
                    </td>
                    <td class="px-4 py-4 text-xs text-gray-500">Paket data internet premium</td>
                    <td class="px-4 py-4 text-sm text-gray-900">900 pts</td>
                    <td class="px-4 py-4"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">75</span></td>
                    <td class="px-4 py-4 text-xs text-gray-500"><div>01/04/2025</div><div>30/09/2025</div></td>
                    <td class="px-4 py-4 text-sm"><a href="https://example.com/data20gb" target="_blank" class="text-blue-600 hover:underline">Link</a></td>
                    <td class="px-4 py-4"><img src="{{ asset('storage/telkom/data20gb.jpg') }}" class="h-10 w-16 object-cover rounded"></td>
                    <td class="px-4 py-4 text-gray-500"><div class="flex space-x-1"><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div></div></td>
                    <td class="px-4 py-4">
                        <div class="flex space-x-2">
                            <button onclick="openEditTelkom(4)" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                            <button onclick="showDeleteConfirmation('Telkom Package','Unlimited Data 20GB','4','Package Unlimited Data 20GB akan dihapus dari sistem')" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- Row 5 -->
                <tr class="hover:bg-gray-50 transition-colors telkom-row" data-category="Telkomsel Packet">
                    <td class="px-4 py-4 text-sm font-medium text-gray-900">5</td>
                    <td class="px-4 py-4 text-sm">
                        <div class="font-medium text-gray-900">Premium Package 50GB</div>
                    </td>
                    <td class="px-4 py-4 text-xs text-gray-500">Paket data super premium</td>
                    <td class="px-4 py-4 text-sm text-gray-900">1500 pts</td>
                    <td class="px-4 py-4"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">50</span></td>
                    <td class="px-4 py-4 text-xs text-gray-500"><div>01/05/2025</div><div>31/08/2025</div></td>
                    <td class="px-4 py-4 text-sm"><a href="https://example.com/data50gb" target="_blank" class="text-blue-600 hover:underline">Link</a></td>
                    <td class="px-4 py-4"><img src="{{ asset('storage/telkom/data50gb.jpg') }}" class="h-10 w-16 object-cover rounded"></td>
                    <td class="px-4 py-4 text-gray-500"><div class="flex space-x-1"><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div><div class="bg-gray-200 border-2 border-dashed rounded w-8 h-8"></div></div></td>
                    <td class="px-4 py-4">
                        <div class="flex space-x-2">
                            <button onclick="openEditTelkom(5)" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                            <button onclick="showDeleteConfirmation('Telkom Package','Premium Package 50GB','5','Package Premium Package 50GB akan dihapus dari sistem')" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
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
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-all"><i class="fas fa-chevron-left text-xs"></i></a>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border-2 border-orange-500 bg-gradient-to-r from-orange-50 to-red-50 text-sm text-orange-600 font-semibold shadow-sm">1</a>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-all">2</a>
                <span class="inline-flex items-center justify-center w-9 h-9 text-gray-500">...</span>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-all">10</a>
                <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 bg-white text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-all"><i class="fas fa-chevron-right text-xs"></i></a>
            </nav>
        </div>
    </div>
</div>

<!-- ======================= MOBILE CARD VIEW ======================= -->
<div class="md:hidden space-y-4 mt-4">
    @foreach([
        ['id'=>1,'nama'=>'Unlimited Data 10GB','desc'=>'Paket data internet','pts'=>'500 pts','stock'=>200,'periode'=>'01/01/2025 – 31/12/2025','img'=>'telkomsel.png','cta'=>'https://example.com/data10gb'],
        ['id'=>2,'nama'=>'Unlimited Calls 500min','desc'=>'Paket telepon unlimited','pts'=>'300 pts','stock'=>150,'periode'=>'02/01/2025 – 30/11/2025','img'=>'telkomsel.png','cta'=>'https://example.com/calls500min'],
        ['id'=>3,'nama'=>'Combo Package 5GB+300min','desc'=>'Paket combo data dan telepon','pts'=>'700 pts','stock'=>100,'periode'=>'01/03/2025 – 31/10/2025','img'=>'telkomsel.png','cta'=>'https://example.com/combo5gb'],
        ['id'=>4,'nama'=>'Unlimited Data 20GB','desc'=>'Paket data internet premium','pts'=>'900 pts','stock'=>75,'periode'=>'01/04/2025 – 30/09/2025','img'=>'telkomsel.png','cta'=>'https://example.com/data20gb'],
        ['id'=>5,'nama'=>'Premium Package 50GB','desc'=>'Paket data super premium','pts'=>'1500 pts','stock'=>50,'periode'=>'01/05/2025 – 31/08/2025','img'=>'telkomsel.png','cta'=>'https://example.com/data50gb'],
    ] as $p)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow telkom-row">
        <div class="flex items-start space-x-3">
            <img src="{{ asset('storage/logo/'.$p['img']) }}" alt="Telkomsel Package" class="h-12 w-16 rounded object-contain">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 leading-tight">{{ $p['nama'] }}</h3>
                <p class="text-xs text-gray-500">{{ $p['desc'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 mt-3">
            <div class="bg-gray-50 rounded-lg p-2">
                <p class="text-[11px] text-gray-500">Redeem Point</p>
                <p class="text-sm font-semibold text-gray-900">{{ $p['pts'] }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-2">
                <p class="text-[11px] text-gray-500">Stock</p>
                <p class="text-sm font-semibold text-blue-600">{{ $p['stock'] }}</p>
            </div>
            <div class="col-span-2 bg-gray-50 rounded-lg p-2">
                <p class="text-[11px] text-gray-500">Periode</p>
                <p class="text-xs text-gray-900">{{ $p['periode'] }}</p>
            </div>
        </div>

        <div class="flex items-center justify-between mt-4 border-t pt-3">
            <a href="{{ $p['cta'] }}" target="_blank" class="text-sm font-medium text-blue-600 hover:underline">Lihat Paket</a>
            <div class="flex items-center space-x-3">
                <button onclick="openEditTelkom({{ $p['id'] }}, {nama:'{{ $p['nama'] }}'})" class="text-blue-600 hover:text-blue-800"><i class="fas fa-edit text-sm"></i></button>
                <button onclick="showDeleteConfirmation('Telkom Package','{{ $p['nama'] }}','{{ $p['id'] }}','Hapus {{ $p['nama'] }}?')" class="text-red-600 hover:text-red-800"><i class="fas fa-trash text-sm"></i></button>
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
                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 bg-white text-xs text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-all"><i class="fas fa-chevron-left text-xs"></i></a>
                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border-2 border-orange-500 bg-gradient-to-r from-orange-50 to-red-50 text-xs font-semibold text-orange-600 shadow-sm">1</a>
                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 bg-white text-xs text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-all">2</a>
                <span class="inline-flex items-center justify-center w-8 h-8 text-gray-500 text-xs">...</span>
                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 bg-white text-xs text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-all">10</a>
                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 bg-white text-xs text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-all"><i class="fas fa-chevron-right text-xs"></i></a>
            </nav>
        </div>
    </div>
</div>
