<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Pesanan - CV Bintang Jaya Komputer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('catalog.index') }}" class="text-decoration-none text-dark fw-bold d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
                </a>
                <div class="mt-3">
                    <h2 class="fw-bold mb-0">Checkout Pengiriman</h2>
                    <span class="text-muted small">CV Bintang Jaya Komputer</span>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-4 d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="fa-solid fa-circle-exclamation fs-5 text-danger"></i>
                <span class="fw-semibold small">{{ session('error') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4" role="alert">
                <div class="fw-bold mb-1"><i class="fa-solid fa-circle-exclamation me-1"></i> Mohon lengkapi data berikut:</div>
                <ul class="mb-0 ps-3 small">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $productImage = null;
            if ($product->primaryImage && !empty($product->primaryImage->path)) {
                $productImage = asset('storage/' . $product->primaryImage->path);
            } elseif ($product->images && $product->images->isNotEmpty()) {
                $productImage = asset('storage/' . $product->images->first()->path);
            } elseif (file_exists(public_path('img/comp.png'))) {
                $productImage = asset('img/comp.png');
            }
        @endphp

        <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="row">
                <!-- Kolom Kiri: Data Diri & Alamat Pengiriman -->
                <div class="col-md-7">
                    <!-- Mobile / Tablet Banner: Gambar & Info Produk Tetap Terlihat Sebelum & Saat Mengisi Form -->
                    <div class="card shadow-sm p-3 mb-4 border-0 rounded-4 bg-white d-md-none">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 76px; height: 76px; flex-shrink: 0; background-color: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 4px;">
                                @if($productImage)
                                    <img src="{{ $productImage }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: contain;">
                                @else
                                    <i class="fa-solid fa-laptop-code text-secondary fs-3"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                @if($product->brand)
                                    <span class="badge bg-secondary-subtle text-secondary small py-0.5 px-2 rounded mb-1">{{ $product->brand->name }}</span>
                                @endif
                                <h6 class="fw-bold mb-1 text-dark text-truncate">{{ $product->name }}</h6>
                                <div class="small fw-bold text-danger">Rp {{ number_format($product->price_jual, 0, ',', '.') }} <span class="text-muted fw-normal">(1 Unit)</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm p-4 mb-4 border-0 rounded-4">
                        <h4 class="mb-3 text-primary fw-bold"><i class="fa-solid fa-user-pen"></i> Informasi Penerima</h4>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', Auth::check() ? Auth::user()->name : '') }}" placeholder="Masukkan nama lengkap Anda" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor WhatsApp / HP</label>
                            <input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone') }}" placeholder="Contoh: 081234567890" required>
                        </div>

                        <hr class="my-4">

                        <h4 class="mb-3 text-primary fw-bold"><i class="fa-solid fa-location-dot"></i> Lokasi Pengiriman (GrabExpress)</h4>
                        
                        <!-- Pilihan Kecamatan -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kecamatan (Wilayah Metro)</label>
                            <select name="kecamatan_id" id="kecamatan_id" class="form-control" required>
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach($kecamatans as $kec)
                                    <option value="{{ $kec->id }}" {{ old('kecamatan_id') == $kec->id ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pilihan Kelurahan -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kelurahan / Desa</label>
                            <select name="kelurahan_id" id="kelurahan_id" class="form-control" required disabled>
                                <option value="">-- Pilih Kecamatan terlebih dahulu --</option>
                                @foreach($kecamatans as $kec)
                                    @foreach($kec->kelurahans as $kel)
                                        <option value="{{ $kel->id }}" data-kecamatan="{{ $kec->id }}" data-tarif="{{ $kel->tarif_grab }}" {{ old('kelurahan_id') == $kel->id ? 'selected' : '' }}>
                                            {{ $kel->nama_kelurahan }} (Ongkir: Rp {{ number_format($kel->tarif_grab, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                        <!-- Link Shareloc Google Maps -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Link Shareloc Google Maps</label>
                            <input type="url" name="shareloc_link" class="form-control" value="{{ old('shareloc_link') }}" placeholder="https://maps.app.goo.gl/..." required>
                            <small class="text-muted">Buka Google Maps, cari titik rumah Anda, klik <b>Bagikan (Share)</b>, lalu salin tautannya ke sini.</small>
                        </div>

                        <hr class="my-4">

                        <!-- Pilihan Metode Pembayaran (3 Opsi Sesuai Permintaan) -->
                        <h4 class="mb-3 text-primary fw-bold"><i class="fa-solid fa-wallet"></i> Metode Pembayaran</h4>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Cara Bayar</label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="">-- Pilih Metode Pembayaran --</option>
                                <option value="Transfer BNI" {{ old('payment_method', 'Transfer BNI') === 'Transfer BNI' ? 'selected' : '' }}>Transfer Bank BNI (Manual)</option>
                                <option value="QRIS" disabled>QRIS (Maintenance)</option>
                                <option value="Payment Gateway" disabled>Payment Gateway (Maintenance)</option>
                            </select>
                        </div>

                        <!-- INFO NOMOR REKENING BNI (Muncul / Info Panduan) -->
                        <div class="alert alert-info py-2 px-3 mb-3 small" id="info-bni">
                            <i class="fa-solid fa-circle-info"></i> Silakan transfer ke rekening <b>BNI: 1234567890</b> a.n. <b>CV. Bintang Jaya Komputer</b>. Setelah itu, upload bukti transfer di bawah ini.
                        </div>

                        <!-- INPUT UPLOAD BUKTI TRANSFER -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-success"><i class="fa-solid fa-receipt"></i> Upload Bukti Transfer / Pembayaran</label>
                            <input type="file" name="bukti_transfer" class="form-control" accept="image/png, image/jpeg, image/jpg" required>
                            <small class="text-muted">Format gambar: JPG, JPEG, PNG (Maksimal 2MB).</small>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Ringkasan Produk & Pembayaran (Sticky Desktop) -->
                <div class="col-md-5">
                    <div class="card shadow-sm p-4 border-0 rounded-4 bg-white sticky-top" style="top: 24px;">
                        <h4 class="mb-3 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="fa-solid fa-bag-shopping text-danger"></i> Ringkasan Pesanan
                        </h4>
                        
                        {{-- Informasi Produk dengan Gambar Lengkap --}}
                        <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                            <div style="width: 88px; height: 88px; flex-shrink: 0; background-color: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 4px;">
                                @if($productImage)
                                    <img src="{{ $productImage }}" alt="{{ $product->name }}" id="checkoutProductImage" style="width: 100%; height: 100%; object-fit: contain;">
                                @else
                                    <i class="fa-solid fa-laptop-code text-secondary fs-2"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                @if($product->brand)
                                    <span class="badge bg-secondary-subtle text-secondary small py-0.5 px-2 rounded mb-1">{{ $product->brand->name }}</span>
                                @endif
                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem; line-height: 1.3;" title="{{ $product->name }}">{{ $product->name }}</h6>
                                <div class="small text-muted mb-1">
                                    SKU: <span class="fw-medium text-dark">{{ $product->sku }}</span>
                                </div>
                                <div class="small text-danger fw-bold">
                                    Rp {{ number_format($product->price_jual, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        {{-- Rincian Harga, Jumlah, Subtotal & Ongkir Sesuai Ketentuan --}}
                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span>Harga Produk</span>
                            <span class="fw-semibold text-dark">Rp {{ number_format($product->price_jual, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span>Jumlah Barang</span>
                            <span class="fw-semibold text-dark">1 Unit</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-dark fw-medium">Subtotal Produk</span>
                            <span class="fw-bold text-dark">Rp {{ number_format($product->price_jual, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom small">
                            <span class="text-dark fw-medium">Estimasi Ongkir GrabExpress</span>
                            <span class="text-success fw-bold" id="text-ongkir">Pilih Kelurahan dulu</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="h6 fw-bold mb-0 text-dark">Total Pembayaran</span>
                            <span class="h5 fw-bold text-danger mb-0" id="text-total">Rp {{ number_format($product->price_jual, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" class="btn btn-danger w-100 py-3 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" style="background-color: #dc2626; border-color: #dc2626;">
                            <i class="fa-solid fa-check-to-slot"></i> Buat Pesanan Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Script Logika Interaktif Dinamis -->
    <script>
        const productPrice = Number("{{ $product->price_jual }}");
        const selectKecamatan = document.getElementById('kecamatan_id');
        const selectKelurahan = document.getElementById('kelurahan_id');
        const textOngkir = document.getElementById('text-ongkir');
        const textTotal = document.getElementById('text-total');

        const allKelurahanOptions = Array.from(selectKelurahan.options);

        // 1. Filter Kelurahan saat Kecamatan dipilih
        selectKecamatan.addEventListener('change', function() {
            const kecamatanId = this.value;
            
            selectKelurahan.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
            textOngkir.innerText = 'Pilih Kelurahan dulu';
            textTotal.innerText = 'Rp ' + productPrice.toLocaleString('id-ID');
            
            if (kecamatanId) {
                selectKelurahan.removeAttribute('disabled');
                allKelurahanOptions.forEach(option => {
                    if (option.getAttribute('data-kecamatan') === kecamatanId) {
                        selectKelurahan.appendChild(option);
                    }
                });
            } else {
                selectKelurahan.setAttribute('disabled', 'true');
            }
        });

        // 2. Hitung Ongkir saat Kelurahan dipilih
        selectKelurahan.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const tarif = parseInt(selectedOption.getAttribute('data-tarif')) || 0;
            
            if(tarif > 0) {
                textOngkir.innerText = 'Rp ' + tarif.toLocaleString('id-ID');
                const total = productPrice + tarif;
                textTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
            } else {
                textOngkir.innerText = 'Pilih Kelurahan dulu';
                textTotal.innerText = 'Rp ' + productPrice.toLocaleString('id-ID');
            }
        });
    </script>
</body>
</html>