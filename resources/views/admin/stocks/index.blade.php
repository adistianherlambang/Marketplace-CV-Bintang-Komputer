<x-admin-layout>
    @section('header_title', 'Kelola Stok Barang')

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules/stocks.module.css') }}">
    @endpush

    <div x-data="{
        modalOpen: false,
        selectedProductId: '',
        selectedProductName: '',
        currentStock: 0,
        adjustType: 'in',
        quantity: '',
        description: '',
        openModal(productId = '', productName = '', stock = 0) {
            this.selectedProductId = productId;
            this.selectedProductName = productName;
            this.currentStock = stock;
            this.adjustType = 'in';
            this.quantity = '';
            this.description = '';
            this.modalOpen = true;
        },
        closeModal() {
            this.modalOpen = false;
        }
    }">

        <!-- Header Title & Action Buttons -->
        <div class="stocks-header-wrap">
            <div class="stocks-header-title">
                <h2>Kelola Stok Barang</h2>
                <p>Pantau ketersediaan stok fisik produk gudang dan peringatan stok menipis.</p>
            </div>
            <div class="stocks-header-actions">
                <a href="{{ route('admin.stocks.history') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Stok
                </a>
                <button type="button" @click="openModal()" class="btn btn-primary">
                    <i class="fa-solid fa-plus-minus"></i> Sesuaikan Stok
                </button>
            </div>
        </div>

        <!-- Low Stock Warning Banner -->
        @if(($counts['low'] ?? 0) > 0 || ($counts['empty'] ?? 0) > 0)
            <div class="stocks-warning-banner">
                <div class="stocks-warning-content">
                    <i class="fa-solid fa-triangle-exclamation stocks-warning-icon"></i>
                    <div>
                        <h4 class="stocks-warning-title">Peringatan Ketersediaan Stok!</h4>
                        <p class="stocks-warning-desc">
                            Terdapat <strong>{{ $counts['low'] }} produk</strong> dengan stok menipis (di bawah batas minimum) 
                            @if(($counts['empty'] ?? 0) > 0)
                                dan <strong>{{ $counts['empty'] }} produk habis</strong>
                            @endif
                            yang perlu segera dilakukan pengadaan / restock.
                        </p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('admin.stocks.index', ['status' => 'low']) }}" class="stocks-warning-btn">
                        <i class="fa-solid fa-filter"></i> Lihat Stok Menipis
                    </a>
                </div>
            </div>
        @endif

        <!-- Summary Metric Cards -->
        <div class="stocks-metrics-grid">
            <div class="stocks-metric-card">
                <div>
                    <div class="stocks-metric-label">Total Produk</div>
                    <div class="stocks-metric-value">{{ $counts['total'] ?? 0 }}</div>
                </div>
                <div class="stocks-metric-icon metric-icon-total">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>

            <div class="stocks-metric-card">
                <div>
                    <div class="stocks-metric-label">Stok Aman</div>
                    <div class="stocks-metric-value" style="color: #16a34a;">{{ $counts['safe'] ?? 0 }}</div>
                </div>
                <div class="stocks-metric-icon metric-icon-safe">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>

            <div class="stocks-metric-card">
                <div>
                    <div class="stocks-metric-label">Stok Menipis</div>
                    <div class="stocks-metric-value" style="color: #d97706;">{{ $counts['low'] ?? 0 }}</div>
                </div>
                <div class="stocks-metric-icon metric-icon-low">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>

            <div class="stocks-metric-card">
                <div>
                    <div class="stocks-metric-label">Stok Habis</div>
                    <div class="stocks-metric-value" style="color: #dc2626;">{{ $counts['empty'] ?? 0 }}</div>
                </div>
                <div class="stocks-metric-icon metric-icon-empty">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="stocks-filter-card">
            <form method="GET" action="{{ route('admin.stocks.index') }}" class="stocks-filter-form">
                <div class="stocks-search-wrap">
                    <i class="fa-solid fa-magnifying-glass stocks-search-icon"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk atau SKU..." class="stocks-search-input">
                </div>

                <div class="stocks-select-wrap">
                    <select name="status" class="stocks-filter-select" onchange="this.form.submit()">
                        <option value="">Semua Status Stok</option>
                        <option value="low" {{ request('status') === 'low' ? 'selected' : '' }}>⚠️ Stok Menipis</option>
                        <option value="empty" {{ request('status') === 'empty' ? 'selected' : '' }}>⛔ Stok Habis</option>
                        <option value="safe" {{ request('status') === 'safe' ? 'selected' : '' }}>✅ Stok Aman</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 8px 16px;">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari
                </button>

                @if(request('search') || request('status'))
                    <a href="{{ route('admin.stocks.index') }}" class="btn btn-secondary" style="padding: 8px 14px;">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Stock Table -->
        <div class="stocks-table-card">
            <div class="table-responsive">
                <table class="stocks-table">
                    <thead>
                        <tr>
                            <th style="width: 5%; text-align: center;">No</th>
                            <th style="width: 40%;">Produk &amp; SKU</th>
                            <th style="width: 15%;">Harga Modal</th>
                            <th style="width: 12%; text-align: center;">Min. Stok</th>
                            <th style="width: 13%; text-align: center;">Sisa Stok</th>
                            <th style="width: 15%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            @php
                                $isLow = $product->stock > 0 && $product->stock <= $product->min_stock;
                                $isEmpty = $product->stock <= 0;
                            @endphp
                            <tr>
                                <td style="text-align: center; color: #64748b; font-size: 0.8rem;">
                                    {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                                </td>

                                <td>
                                    <div class="stocks-product-cell">
                                        @if($product->primaryImage && file_exists(public_path('storage/' . $product->primaryImage->image_path)))
                                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->name }}" class="stocks-product-img">
                                        @else
                                            <div class="stocks-product-img-fallback">
                                                <i class="fa-solid fa-box"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="stocks-product-name">{{ $product->name }}</div>
                                            <div class="stocks-product-sku">
                                                <span>SKU: {{ $product->sku }}</span>
                                                @if($product->brand)
                                                    <span style="color: #cbd5e1; margin: 0 4px;">•</span>
                                                    <span>{{ $product->brand->name }}</span>
                                                @endif
                                                @if($product->category)
                                                    <span style="color: #cbd5e1; margin: 0 4px;">•</span>
                                                    <span>{{ $product->category->name }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div style="font-weight: 600; color: #0f172a; font-size: 0.85rem;">
                                        Rp {{ number_format($product->price_modal, 0, ',', '.') }}
                                    </div>
                                    <div style="font-size: 0.72rem; color: #64748b;">
                                        Jual: Rp {{ number_format($product->price_jual, 0, ',', '.') }}
                                    </div>
                                </td>

                                <td style="text-align: center; color: #64748b; font-weight: 600;">
                                    {{ $product->min_stock }} pcs
                                </td>

                                <td style="text-align: center;">
                                    <div class="stock-qty-text {{ $isEmpty ? 'stock-qty-empty' : ($isLow ? 'stock-qty-low' : 'stock-qty-safe') }}">
                                        {{ $product->stock }} pcs
                                    </div>
                                    <div style="margin-top: 3px;">
                                        @if ($isEmpty)
                                            <span class="badge-stock badge-stock-empty">
                                                <i class="fa-solid fa-circle-xmark"></i> Habis
                                            </span>
                                        @elseif ($isLow)
                                            <span class="badge-stock badge-stock-low">
                                                <i class="fa-solid fa-triangle-exclamation"></i> Menipis
                                            </span>
                                        @else
                                            <span class="badge-stock badge-stock-safe">
                                                <i class="fa-solid fa-circle-check"></i> Aman
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td style="text-align: center;">
                                    <button type="button" 
                                            @click="openModal({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->stock }})"
                                            class="btn btn-secondary btn-sm"
                                            style="padding: 5px 12px; font-size: 0.78rem;">
                                        <i class="fa-solid fa-plus-minus text-primary"></i> Sesuaikan
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 48px 20px; color: #64748b;">
                                    <i class="fa-solid fa-boxes-stacked" style="font-size: 2.5rem; opacity: 0.35; margin-bottom: 12px; display: block;"></i>
                                    <div style="font-weight: 700; color: #0f172a; margin-bottom: 4px;">Tidak Ada Data Produk</div>
                                    <div style="font-size: 0.8rem;">Tidak ada produk yang cocok dengan pencarian atau filter yang dipilih.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($products, 'links'))
                <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0;">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

        <!-- Stock Adjustment Modal (Alpine.js) -->
        <div x-show="modalOpen" class="stocks-modal-backdrop" @click="closeModal()" x-cloak style="display: none;">
            <div class="stocks-modal-box" @click.stop>
                <div class="stocks-modal-header">
                    <h3 class="stocks-modal-title">
                        <i class="fa-solid fa-plus-minus text-primary"></i> Penyesuaian Stok Barang
                    </h3>
                    <button type="button" @click="closeModal()" class="stocks-modal-close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.stocks.adjust') }}">
                    @csrf
                    <div class="stocks-modal-body">
                        <!-- Product Selection -->
                        <div class="form-group mb-3">
                            <label class="form-label font-semibold">Pilih Barang / Produk <span class="text-danger">*</span></label>
                            <select name="product_id" x-model="selectedProductId" class="form-control" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach ($allProducts as $p)
                                    <option value="{{ $p->id }}">
                                        {{ $p->name }} (Sisa: {{ $p->stock }} pcs | SKU: {{ $p->sku }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Adjustment Type -->
                        <div class="form-group mb-3">
                            <label class="form-label font-semibold">Tipe Penyesuaian <span class="text-danger">*</span></label>
                            <div style="display: flex; gap: 16px; margin-top: 4px;">
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="radio" name="type" value="in" x-model="adjustType" required>
                                    <span style="font-weight: 600; color: #15803d;">
                                        <i class="fa-solid fa-square-plus"></i> Tambah Stok (+)
                                    </span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="radio" name="type" value="out" x-model="adjustType" required>
                                    <span style="font-weight: 600; color: #b91c1c;">
                                        <i class="fa-solid fa-square-minus"></i> Kurangi Stok (-)
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="form-group mb-3">
                            <label class="form-label font-semibold">Jumlah / Quantity (pcs) <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" x-model="quantity" class="form-control" required min="1" placeholder="Contoh: 10">
                        </div>

                        <!-- Description / Reason -->
                        <div class="form-group mb-2">
                            <label class="form-label font-semibold">Keterangan / Alasan Penyesuaian <span class="text-danger">*</span></label>
                            <textarea name="description" x-model="description" class="form-control" rows="3" required placeholder="Contoh: Barang masuk dari supplier, Stock opname, Barang rusak/cacat, dll"></textarea>
                        </div>
                    </div>

                    <div class="stocks-modal-footer">
                        <button type="button" @click="closeModal()" class="btn btn-secondary">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Penyesuaian
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-admin-layout>

