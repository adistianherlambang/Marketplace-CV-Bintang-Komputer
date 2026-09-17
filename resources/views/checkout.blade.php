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
                <div class="d-flex align-items-center gap-3 mt-3">
                    <img src="{{ asset('img/logo/logoKesamping.jpg') }}" alt="CV Bintang Jaya Komputer" style="height: 48px; width: auto; object-fit: contain; border-radius: 8px;">
                    <div>
                        <h2 class="fw-bold mb-0">Checkout Pengiriman</h2>
                        <span class="text-muted small">CV Bintang Jaya Komputer</span>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="row">
                <!-- Kolom Kiri: Data Diri & Alamat Pengiriman -->
                <div class="col-md-7">
                    <div class="card shadow-sm p-4 mb-4 border-0 rounded-4">
                        <h4 class="mb-3 text-primary fw-bold"><i class="fa-solid fa-user-pen"></i> Informasi Penerima</h4>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', Auth::check() ? Auth::user()->name : '') }}" placeholder="Masukkan nama lengkap Anda" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor WhatsApp / HP</label>
                            <input type="text" name="customer_phone" class="form-control" placeholder="Contoh: 081234567890" required>
                        </div>

                        <hr class="my-4">

                        <h4 class="mb-3 text-primary fw-bold"><i class="fa-solid fa-location-dot"></i> Lokasi Pengiriman (GrabExpress)</h4>
                        
                        <!-- Pilihan Kecamatan -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kecamatan (Wilayah Metro)</label>
                            <select name="kecamatan_id" id="kecamatan_id" class="form-control" required>
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach($kecamatans as $kec)
                                    <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
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
                                        <option value="{{ $kel->id }}" data-kecamatan="{{ $kec->id }}" data-tarif="{{ $kel->tarif_grab }}">
                                            {{ $kel->nama_kelurahan }} (Ongkir: Rp {{ number_format($kel->tarif_grab, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                        <!-- Link Shareloc Google Maps -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Link Shareloc Google Maps</label>
                            <input type="url" name="shareloc_link" class="form-control" placeholder="https://maps.app.goo.gl/..." required>
                            <small class="text-muted">Buka Google Maps, cari titik rumah Anda, klik <b>Bagikan (Share)</b>, lalu salin tautannya ke sini.</small>
                        </div>

                        <hr class="my-4">

                        <!-- Pilihan Metode Pembayaran (3 Opsi Sesuai Permintaan) -->
                        <h4 class="mb-3 text-primary fw-bold"><i class="fa-solid fa-wallet"></i> Metode Pembayaran</h4>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Cara Bayar</label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="">-- Pilih Metode Pembayaran --</option>
                                <option value="Transfer BNI">Transfer Bank BNI (Manual)</option>
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

                <!-- Kolom Kanan: Ringkasan Produk & Pembayaran -->
                <div class="col-md-5">
                    <div class="card shadow-sm p-4 border-0 rounded-4 bg-white sticky-top" style="top: 20px;">
                        <h4 class="mb-3 fw-bold">Ringkasan Pesanan</h4>
                        
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $product->name }}</h6>
                                <span class="text-muted small">Harga: Rp {{ number_format($product->price_jual, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal Produk</span>
                            <span class="fw-bold">Rp {{ number_format($product->price_jual, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span>Estimasi Ongkir GrabExpress</span>
                            <span class="text-success fw-bold" id="text-ongkir">Pilih Kelurahan dulu</span>
                        </div>

                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5 fw-bold">Total Pembayaran</span>
                            <span class="h5 fw-bold text-primary" id="text-total">Rp {{ number_format($product->price_jual, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-3 fw-bold shadow-sm">
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