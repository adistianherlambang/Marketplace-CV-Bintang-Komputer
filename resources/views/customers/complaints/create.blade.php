<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Formulir Pengajuan Komplain') }}
            </h2>
            <a href="{{ route('customer.orders.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat Pesanan
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Card Ringkasan Pesanan Terkait -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-gray-100">
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 mb-2">
                            <i class="fa-solid fa-receipt mr-1.5"></i> Order Terverifikasi
                        </span>
                        <h3 class="text-lg font-bold text-gray-900">Invoice: {{ $order->invoice_number }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Tanggal Pembelian: {{ $order->created_at->format('d F Y, H:i') }} WIB</p>
                    </div>
                    <div class="text-left md:text-right">
                        <span class="text-xs text-gray-500 block">Total Pesanan</span>
                        <span class="text-lg font-extrabold text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Daftar Produk yang Dikomplain -->
                <div class="mt-4 pt-2">
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Produk yang Dibeli</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($order->items as $item)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm shrink-0">
                                    <i class="fa-solid fa-box"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h5 class="text-sm font-semibold text-gray-900 truncate">{{ $item->item_name ?? optional($item->product)->name }}</h5>
                                    <p class="text-xs text-gray-500">{{ $item->quantity }} unit &times; Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Formulir Komplain Utama -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100" x-data="{ notaPreview: null, productPreview: null }">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-headset text-blue-600"></i> Detail Kendala &amp; Pengajuan Garansi
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Sampaikan kendala yang Anda alami secara rinci dan lampirkan bukti foto nota serta foto fisik produk agar tim CV Bintang Jaya Komputer dapat memproses klaim Anda dengan cepat.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                        <div class="font-bold flex items-center gap-2 mb-2">
                            <i class="fa-solid fa-circle-exclamation"></i> Mohon periksa kembali input Anda:
                        </div>
                        <ul class="list-disc pl-5 space-y-1 text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('customer.complaints.store', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- 1. Jenis Kendala -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">
                            Jenis Kendala / Komplain <span class="text-red-500">*</span>
                        </label>
                        <select name="complaint_type" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-3" required>
                            <option value="">-- Pilih Jenis Kendala --</option>
                            <option value="Barang Rusak / Cacat Fisik Saat Tiba" {{ old('complaint_type') == 'Barang Rusak / Cacat Fisik Saat Tiba' ? 'selected' : '' }}>
                                🔴 Barang Rusak / Cacat Fisik Saat Tiba
                            </option>
                            <option value="Tidak Berfungsi / Kendala Teknis / Mati Total" {{ old('complaint_type') == 'Tidak Berfungsi / Kendala Teknis / Mati Total' ? 'selected' : '' }}>
                                ⚙️ Tidak Berfungsi / Kendala Teknis / Mati Total
                            </option>
                            <option value="Barang Tidak Sesuai Spesifikasi / Salah Kirim" {{ old('complaint_type') == 'Barang Tidak Sesuai Spesifikasi / Salah Kirim' ? 'selected' : '' }}>
                                📦 Barang Tidak Sesuai Spesifikasi / Salah Kirim
                            </option>
                            <option value="Kelengkapan Aksesoris / Kabel / Kardus Kurang" {{ old('complaint_type') == 'Kelengkapan Aksesoris / Kabel / Kardus Kurang' ? 'selected' : '' }}>
                                🔌 Kelengkapan Aksesoris / Kabel / Kardus Kurang
                            </option>
                            <option value="Kendala Lainnya" {{ old('complaint_type') == 'Kendala Lainnya' ? 'selected' : '' }}>
                                💬 Kendala Lainnya
                            </option>
                        </select>
                    </div>

                    <!-- 2. Penjelasan Detail -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">
                            Penjelasan Detail Kerusakan / Masalah <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" rows="4" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-3.5" placeholder="Contoh: Saat pertama kali unboxing dan dinyalakan, layar monitor bergaris dan kabel power tidak merespon saat dihubungkan ke adaptor..." required>{{ old('description') }}</textarea>
                        <p class="text-xs text-gray-400 mt-1.5">Jelaskan kronologi kejadian dan kondisi barang secara spesifik.</p>
                    </div>

                    <!-- 3. Upload Bukti Foto (Grid 2 Kolom dengan Interactive Live Preview) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                        <!-- Upload Nota -->
                        <div class="p-4 rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 text-center flex flex-col justify-between">
                            <div>
                                <div class="w-12 h-12 mx-auto rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-lg mb-2">
                                    <i class="fa-solid fa-receipt"></i>
                                </div>
                                <label class="block text-sm font-bold text-gray-800">Foto Nota Pembelian <span class="text-red-500">*</span></label>
                                <p class="text-xs text-gray-500 mt-1">Upload foto nota / invoice fisik yang jelas.</p>
                            </div>

                            <div class="mt-4">
                                <template x-if="notaPreview">
                                    <div class="mb-3">
                                        <img :src="notaPreview" class="h-32 mx-auto rounded-lg object-contain shadow-sm border border-gray-200">
                                    </div>
                                </template>

                                <input type="file" name="nota_bukti" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer" required @change="
                                    const file = $event.target.files[0];
                                    if(file) {
                                        const reader = new FileReader();
                                        reader.onload = (e) => { notaPreview = e.target.result; };
                                        reader.readAsDataURL(file);
                                    }
                                ">
                            </div>
                        </div>

                        <!-- Upload Foto Kerusakan Produk -->
                        <div class="p-4 rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 text-center flex flex-col justify-between">
                            <div>
                                <div class="w-12 h-12 mx-auto rounded-full bg-red-100 text-red-600 flex items-center justify-center text-lg mb-2">
                                    <i class="fa-solid fa-camera"></i>
                                </div>
                                <label class="block text-sm font-bold text-gray-800">Foto Bukti Fisik Kerusakan <span class="text-red-500">*</span></label>
                                <p class="text-xs text-gray-500 mt-1">Foto jelas bagian produk yang cacat / berkendala.</p>
                            </div>

                            <div class="mt-4">
                                <template x-if="productPreview">
                                    <div class="mb-3">
                                        <img :src="productPreview" class="h-32 mx-auto rounded-lg object-contain shadow-sm border border-gray-200">
                                    </div>
                                </template>

                                <input type="file" name="product_bukti" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-red-600 file:text-white hover:file:bg-red-700 cursor-pointer" required @change="
                                    const file = $event.target.files[0];
                                    if(file) {
                                        const reader = new FileReader();
                                        reader.onload = (e) => { productPreview = e.target.result; };
                                        reader.readAsDataURL(file);
                                    }
                                ">
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('customer.orders.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-semibold text-sm hover:bg-gray-100 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md transition flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Komplain ke Admin
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>