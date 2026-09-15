<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Formulir Komplain Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h2 class="text-xl font-bold mb-4">Detail Komplain</h2>
                
                <div class="mb-6 p-4 bg-gray-50 rounded border text-sm">
                    <p><strong>No. Invoice:</strong> {{ $order->invoice_number }}</p>
                    <p><strong>Tanggal Pembelian:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                    <p class="mt-2 font-semibold">Daftar Barang yang Dibeli:</p>
                    <ul class="list-disc pl-5 mt-1 text-gray-600">
                        @foreach($order->items as $item)
                            <li>{{ $item->item_name ?? optional($item->product)->name }} ({{ $item->quantity }} pcs)</li>
                        @endforeach
                    </ul>
                </div>

                <form action="{{ route('customer.complaints.store', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Jenis Kendala / Komplain</label>
                        <select name="complaint_type" class="w-full mt-1 border-gray-300 rounded-md shadow-sm text-sm" required>
                            <option value="">-- Pilih Jenis Kendala --</option>
                            <option value="Barang Rusak / Cacat Fisik">Barang Rusak / Cacat Fisik</option>
                            <option value="Tidak Berfungsi / Kendala Teknis">Tidak Berfungsi / Kendala Teknis</option>
                            <option value="Barang Tidak Sesuai Pesanan">Barang Tidak Sesuai Pesanan</option>
                            <option value="Kelengkapan / Aksesoris Kurang">Kelengkapan / Aksesoris Kurang</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Keterangan Detail Kerusakan / Masalah</label>
                        <textarea name="description" rows="4" class="w-full mt-1 border-gray-300 rounded-md shadow-sm text-sm" placeholder="Jelaskan kendala atau kerusakan secara rinci..." required>{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Upload Foto Nota Pembelian (JPG / PNG)</label>
                        <input type="file" name="nota_bukti" accept="image/jpeg,image/png,image/jpg" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium text-sm text-gray-700">Upload Foto Bukti Kerusakan Produk (JPG / PNG)</label>
                        <input type="file" name="product_bukti" accept="image/jpeg,image/png,image/jpg" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('customer.orders.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-400">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700">Kirim Komplain ke Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>