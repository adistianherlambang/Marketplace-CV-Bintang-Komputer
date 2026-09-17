<x-guest-layout>
    <x-slot name="title">
        Formulir Pengajuan Komplain - CV Bintang Jaya Komputer
    </x-slot>

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/custom-select.css') }}">
    <style>
        :root {
            --font: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --radius: 12px;
            --radius-sm: 8px;
            --border: #e2e8f0;
            --white: #ffffff;
            --dark: #0f172a;
            --secondary: #64748b;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --transition: all 0.2s ease;
            --bj-primary: #dc2626;
            --bj-primary-hover: #b91c1c;
            --bj-primary-light: #fef2f2;
            --bj-border: #e2e8f0;
            --bj-card-bg: #ffffff;
            --bj-dark: #0f172a;
            --bj-muted: #64748b;
        }

        .complaint-container {
            max-width: 860px;
            margin: 0 auto;
            padding: 10px 16px 40px 16px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--bj-muted);
            text-decoration: none;
            margin-bottom: 16px;
            transition: color 0.15s ease;
        }

        .back-link:hover {
            color: var(--bj-primary);
        }

        .complaint-card {
            background: var(--bj-card-bg);
            border: 1px solid var(--bj-border);
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            padding: 24px;
            margin-bottom: 24px;
        }

        @media (min-width: 768px) {
            .complaint-card {
                padding: 28px 32px;
            }
        }

        .complaint-header-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--bj-dark);
            margin-bottom: 4px;
        }

        .complaint-header-subtitle {
            font-size: 0.85rem;
            color: var(--bj-muted);
            margin: 0;
        }

        /* Order Summary Card Elements */
        .order-summary-header {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--bj-border);
        }

        @media (min-width: 768px) {
            .order-summary-header {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }

        .invoice-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            background-color: var(--bj-primary-light);
            color: var(--bj-primary);
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }

        .product-mini-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 12px;
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
        }

        .product-mini-icon {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background-color: #fee2e2;
            color: var(--bj-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        /* Form Controls */
        .form-label-custom {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--bj-dark);
            margin-bottom: 6px;
            display: block;
        }

        .form-control-custom,
        .form-select-custom {
            width: 100%;
            border: 1px solid var(--bj-border);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.875rem;
            color: var(--bj-dark);
            background-color: #ffffff;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-control-custom:focus,
        .form-select-custom:focus {
            border-color: var(--bj-primary);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
        }

        /* Upload Dropzone Boxes */
        .upload-dropzone {
            border: 2px dashed #cbd5e1;
            border-radius: 14px;
            background-color: #f8fafc;
            padding: 20px 16px;
            text-align: center;
            transition: all 0.2s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .upload-dropzone:hover {
            border-color: var(--bj-primary);
            background-color: #fffafa;
        }

        .upload-dropzone-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px auto;
            font-size: 1.25rem;
        }

        .preview-img-container {
            margin-bottom: 12px;
            background: #ffffff;
            border: 1px solid var(--bj-border);
            border-radius: 10px;
            padding: 6px;
            display: inline-block;
        }

        .preview-img-container img {
            max-height: 120px;
            max-width: 100%;
            border-radius: 6px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        /* Buttons */
        .btn-cancel {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--bj-muted);
            background: #f1f5f9;
            border: 1px solid var(--bj-border);
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-cancel:hover {
            background: #e2e8f0;
            color: var(--bj-dark);
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 24px;
            font-size: 0.875rem;
            font-weight: 700;
            color: #ffffff;
            background-color: var(--bj-primary);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: 0 2px 4px rgba(220, 38, 38, 0.25);
        }

        .btn-submit:hover {
            background-color: var(--bj-primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
        }
    </style>
    @endpush

    <div class="complaint-container">
        {{-- Tombol Navigasi Kembali --}}
        <a href="{{ route('customer.orders.index') }}" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat Pesanan
        </a>

        {{-- Card 1: Ringkasan Pesanan Terkait --}}
        <div class="complaint-card">
            <div class="order-summary-header">
                <div>
                    <span class="invoice-pill">
                        <i class="fa-solid fa-receipt"></i> {{ $order->invoice_number }}
                    </span>
                    <h2 class="complaint-header-title mt-2">Ringkasan Pesanan Terkait</h2>
                    <p class="complaint-header-subtitle">
                        <i class="fa-regular fa-calendar me-1"></i> Tanggal Pembelian: {{ $order->created_at->format('d M Y, H:i') }} WIB
                    </p>
                </div>
                <div class="text-md-end">
                    <span class="text-muted small d-block">Total Pembayaran</span>
                    <span class="fs-5 fw-bold text-danger">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-3 pt-2">
                <div class="text-muted small fw-bold text-uppercase mb-2" style="letter-spacing: 0.03em;">Produk dalam Pesanan ini:</div>
                <div class="row g-2">
                    @foreach($order->items as $item)
                        <div class="col-12 col-md-6">
                            <div class="product-mini-item">
                                <div class="product-mini-icon">
                                    <i class="fa-solid fa-box"></i>
                                </div>
                                <div class="min-w-0 flex-grow-1">
                                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.85rem;">
                                        {{ $item->item_name ?? optional($item->product)->name }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ $item->quantity }} unit &times; Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Card 2: Formulir Komplain --}}
        <div class="complaint-card">
            <div class="d-flex align-items-center gap-3 pb-3 mb-4 border-bottom">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div>
                    <h2 class="complaint-header-title mb-0">Formulir Pengajuan Komplain</h2>
                    <p class="complaint-header-subtitle">Sampaikan kendala kerusakan atau ketidaksesuaian barang untuk klaim garansi toko.</p>
                </div>
            </div>

            {{-- Petunjuk / Info Alert --}}
            <div class="alert alert-light border d-flex gap-2 align-items-start mb-4 p-3 rounded-3" style="background-color: #f8fafc;">
                <i class="fa-solid fa-circle-info text-primary mt-1"></i>
                <div class="small text-muted">
                    Pastikan foto nota pembelian dan foto fisik kerusakan produk terlihat jelas dan tidak buram agar tim verifikasi CV Bintang Jaya Komputer dapat memproses klaim garansi Anda secara cepat.
                </div>
            </div>

            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger rounded-3 mb-4 p-3">
                    <div class="fw-bold d-flex align-items-center gap-2 mb-1">
                        <i class="fa-solid fa-triangle-exclamation"></i> Mohon lengkapi formulir dengan benar:
                    </div>
                    <ul class="mb-0 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('customer.complaints.store', $order->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- 1. Jenis Kendala --}}
                <div class="mb-4">
                    <label class="form-label-custom">
                        Jenis Kendala / Masalah <span class="text-danger">*</span>
                    </label>
                    <select name="complaint_type" class="form-control tom-select" required>
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

                {{-- 2. Penjelasan Detail --}}
                <div class="mb-4">
                    <label class="form-label-custom">
                        Penjelasan Detail Kerusakan / Masalah <span class="text-danger">*</span>
                    </label>
                    <textarea name="description" rows="4" class="form-control-custom" placeholder="Jelaskan secara spesifik kerusakan atau kendala yang dialami. Contoh: Saat unboxing dan dihidupkan, layar laptop tidak menyala dan kipas berputar kencang..." required>{{ old('description') }}</textarea>
                    <div class="text-muted small mt-1">Sebutkan kronologi kejadian dan kondisi fisik barang saat diterima.</div>
                </div>

                {{-- 3. Upload Bukti Foto (Grid 2 Kolom) --}}
                <div class="row g-3 mb-4">
                    {{-- Upload Foto Nota --}}
                    <div class="col-12 col-md-6">
                        <div class="upload-dropzone">
                            <div>
                                <div class="upload-dropzone-icon" style="background: #eff6ff; color: #2563eb;">
                                    <i class="fa-solid fa-receipt"></i>
                                </div>
                                <label class="form-label-custom mb-1" for="notaInput" style="cursor: pointer;">Foto Nota Pembelian <span class="text-danger">*</span></label>
                                <p class="text-muted small mb-3">Foto nota fisik atau screenshot invoice resmi.</p>
                            </div>

                            <div>
                                <div id="notaPreviewWrap" class="preview-img-container d-none">
                                    <img id="notaPreviewImg" src="" alt="Preview Nota">
                                </div>

                                <input type="file" name="nota_bukti" id="notaInput" accept="image/jpeg,image/png,image/jpg,image/webp" class="form-control form-control-sm" required onchange="handleImagePreview(this, 'notaPreviewWrap', 'notaPreviewImg')">
                                <div class="text-muted small mt-1" style="font-size: 0.72rem;">Format: JPG, PNG, WEBP (Maks: 3MB)</div>
                            </div>
                        </div>
                    </div>

                    {{-- Upload Foto Kerusakan Produk --}}
                    <div class="col-12 col-md-6">
                        <div class="upload-dropzone">
                            <div>
                                <div class="upload-dropzone-icon" style="background: #fee2e2; color: #dc2626;">
                                    <i class="fa-solid fa-camera"></i>
                                </div>
                                <label class="form-label-custom mb-1" for="productInput" style="cursor: pointer;">Foto Fisik Kerusakan Produk <span class="text-danger">*</span></label>
                                <p class="text-muted small mb-3">Foto bagian yang rusak atau berkendala.</p>
                            </div>

                            <div>
                                <div id="productPreviewWrap" class="preview-img-container d-none">
                                    <img id="productPreviewImg" src="" alt="Preview Kerusakan">
                                </div>

                                <input type="file" name="product_bukti" id="productInput" accept="image/jpeg,image/png,image/jpg,image/webp" class="form-control form-control-sm" required onchange="handleImagePreview(this, 'productPreviewWrap', 'productPreviewImg')">
                                <div class="text-muted small mt-1" style="font-size: 0.72rem;">Format: JPG, PNG, WEBP (Maks: 3MB)</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end align-items-center gap-2 pt-3 border-top">
                    <a href="{{ route('customer.orders.index') }}" class="btn-cancel">
                        Batal
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan Komplain
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/custom-select.js') }}"></script>
    <script>
        function handleImagePreview(input, wrapId, imgId) {
            const file = input.files && input.files[0];
            const wrap = document.getElementById(wrapId);
            const img = document.getElementById(imgId);

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    wrap.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                wrap.classList.add('d-none');
                img.src = '';
            }
        }
    </script>
    @endpush
</x-guest-layout>