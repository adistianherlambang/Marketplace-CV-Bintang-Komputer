<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat & Pelacakan Pesanan Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Invoice</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk & Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi & Pengiriman</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($pesanan as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $item->invoice_number }}<br>
                                    <small class="text-muted">{{ $item->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @foreach($item->items as $orderItem)
                                        <div class="fw-bold"><strong>{{ $orderItem->item_name }}</strong> ({{ $orderItem->quantity }}x)</div>
                                    @endforeach
                                    <div class="font-semibold text-primary mt-1">Total: Rp {{ number_format($item->total_amount, 0, ',', '.') }}</div>
                                    <div class="text-xs text-muted">Metode: {{ $item->payment_method }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <div>Kec. {{ optional($item->kecamatan)->nama_kecamatan }}, Kel. {{ optional($item->kelurahan)->nama_kelurahan }}</div>
                                    <a href="{{ $item->shareloc_link }}" target="_blank" class="text-blue-600 text-xs underline">Buka Shareloc Maps</a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-y-1">
                                    <a href="{{ route('admin.transactions.nota', $item->id) }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs inline-block mb-1">
                                        Download Nota
                                    </a>

                                    @if(in_array(strtolower($item->status), ['menunggu konfirmasi', 'diproses', 'dikirim']))
                                        <form action="{{ route('customer.orders.selesai', $item->id) }}" method="POST" class="inline-block mt-1">
                                            @csrf
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs" onclick="return confirm('Yakin pesanan sudah diterima?')">
                                                Pesanan Diterima
                                            </button>
                                        </form>
                                    @elseif(strtolower($item->status) == 'selesai')
                                        <span class="text-green-600 font-bold block mt-1">Selesai</span>
                                    @endif

                                    <!-- TOMBOL KOMPLAIN PELANGGAN -->
                                    <a href="{{ route('customer.complaints.create', $item->id) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs block text-center mt-1">
                                        Komplain
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada riwayat pesanan yang tercatat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>