<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4">Kelola Pesanan Masuk</h2>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Invoice & Pembeli</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rincian Produk</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Bukti TF</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pengiriman</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi / Ubah Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm">
                            @forelse ($pesanan as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <strong>{{ $item->invoice_number }}</strong><br>
                                        <span class="text-gray-600">{{ $item->customer_name }}</span><br>
                                        <small class="text-gray-400">{{ $item->customer_phone }}</small>
                                    </td>
                                    <td class="px-6 py-4">
                                        @foreach($item->items as $orderItem)
                                            <div>{{ $orderItem->item_name }} ({{ $orderItem->quantity }}x)</div>
                                        @endforeach
                                        <div class="font-semibold text-blue-600 mt-1">Total: Rp {{ number_format($item->total_amount, 0, ',', '.') }}</div>
                                        <div class="text-xs text-gray-500">Metode: {{ $item->payment_method }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($item->bukti_transfer)
                                            <a href="{{ asset('storage/' . $item->bukti_transfer) }}" target="_blank" class="text-blue-600 underline font-semibold text-xs">
                                                Lihat Bukti
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs">Belum ada</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-1 text-xs rounded font-semibold 
                                            {{ $item->status === 'Selesai' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $item->status === 'Menunggu Konfirmasi' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $item->status === 'Diproses' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $item->status === 'Dikirim' ? 'bg-purple-100 text-purple-800' : '' }}
                                            {{ $item->status === 'Belum Dibayar' ? 'bg-gray-100 text-gray-800' : '' }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-600">
                                        Kec. {{ optional($item->kecamatan)->name }}, Kel. {{ optional($item->kelurahan)->name }}<br>
                                        @if($item->shareloc_link)
                                            <a href="{{ $item->shareloc_link }}" target="_blank" class="text-blue-500 underline">Buka Shareloc</a>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('admin.pesanan.update', $item->id) }}" method="POST" class="inline-flex flex-col gap-2">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="text-xs border-gray-300 rounded shadow-sm" onchange="this.form.submit()">
                                                <option value="Belum Dibayar" {{ $item->status == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                                                <option value="Menunggu Konfirmasi" {{ $item->status == 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                                <option value="Diproses" {{ $item->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                                <option value="Dikirim" {{ $item->status == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                                                <option value="Selesai" {{ $item->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada pesanan masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>