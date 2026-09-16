<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Riwayat & Pelacakan Pesanan Saya') }}
            </h2>
            <a href="{{ route('catalog.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                <i class="fa-solid fa-cart-shopping"></i> Belanja Lagi di Katalog
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6">
                
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2" role="alert">
                        <i class="fa-solid fa-circle-check text-green-600"></i>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No. Invoice &amp; Waktu</th>
                                <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Produk &amp; Total</th>
                                <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status Pesanan</th>
                                <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lokasi &amp; Pengiriman</th>
                                <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi &amp; Dokumen</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($pesanan as $item)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-bold text-blue-600 text-sm block">{{ $item->invoice_number }}</span>
                                    <small class="text-gray-400 text-xs">{{ $item->created_at->format('d/m/Y H:i') }} WIB</small>
                                </td>
                                <td class="px-6 py-4">
                                    @foreach($item->items as $orderItem)
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $orderItem->item_name }} <span class="text-xs font-normal text-gray-500">({{ $orderItem->quantity }}x @ Rp {{ number_format($orderItem->price, 0, ',', '.') }})</span>
                                        </div>
                                    @endforeach
                                    <div class="font-extrabold text-blue-700 text-sm mt-1">
                                        Total: Rp {{ number_format($item->total_amount, 0, ',', '.') }}
                                        @if($item->shipping_cost > 0)
                                            <span class="text-xs text-gray-400 font-normal">(Termasuk Ongkir Rp {{ number_format($item->shipping_cost, 0, ',', '.') }})</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        <i class="fa-regular fa-credit-card mr-1"></i> {{ $item->payment_method }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $st = strtolower(trim($item->status));
                                    @endphp
                                    @if($st === 'selesai' || $st === 'lunas')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800">
                                            <i class="fa-solid fa-circle-check mr-1 mt-0.5"></i> Selesai
                                        </span>
                                    @elseif($st === 'dikirim')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-purple-100 text-purple-800">
                                            <i class="fa-solid fa-motorcycle mr-1 mt-0.5"></i> Dalam Pengiriman
                                        </span>
                                    @elseif($st === 'diproses')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-blue-100 text-blue-800">
                                            <i class="fa-solid fa-box mr-1 mt-0.5"></i> Sedang Diproses
                                        </span>
                                    @elseif($st === 'menunggu konfirmasi')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-amber-100 text-amber-800">
                                            <i class="fa-solid fa-clock mr-1 mt-0.5"></i> Menunggu Konfirmasi
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gray-100 text-gray-800">
                                            {{ $item->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-600">
                                    <div class="font-medium text-gray-800">Kec. {{ optional($item->kecamatan)->nama_kecamatan }}, Kel. {{ optional($item->kelurahan)->nama_kelurahan }}</div>
                                    @if($item->shareloc_link)
                                        <a href="{{ $item->shareloc_link }}" target="_blank" class="text-blue-600 hover:underline font-semibold inline-flex items-center gap-1 mt-1">
                                            <i class="fa-solid fa-location-dot"></i> Buka Titik Maps
                                        </a>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-xs space-y-1.5">
                                    <a href="{{ route('admin.transactions.nota', $item->id) }}" target="_blank" class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-1.5 px-3 rounded-lg text-xs transition shadow-sm">
                                        <i class="fa-solid fa-file-arrow-down"></i> Download Nota
                                    </a>

                                    @if(strtolower($item->status) === 'dikirim')
                                        <div>
                                            <form action="{{ route('customer.orders.selesai', $item->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg text-xs shadow-sm transition" onclick="return confirm('Konfirmasi bahwa barang pesanan Anda telah tiba dan diterima dengan baik?')">
                                                    <i class="fa-solid fa-check"></i> Pesanan Diterima
                                                </button>
                                            </form>
                                        </div>
                                    @endif

                                    <!-- TOMBOL KOMPLAIN PELANGGAN -->
                                    <div>
                                        <a href="{{ route('customer.complaints.create', $item->id) }}" class="inline-flex items-center gap-1 bg-amber-500 hover:bg-amber-600 text-white font-bold py-1.5 px-3 rounded-lg text-xs shadow-sm transition">
                                            <i class="fa-solid fa-triangle-exclamation"></i> Ajukan Komplain
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400">
                                    <i class="fa-solid fa-box-open text-3xl mb-2 text-gray-300 block"></i>
                                    Belum ada riwayat pesanan yang tercatat di akun Anda.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>