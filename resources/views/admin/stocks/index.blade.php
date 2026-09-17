<x-admin-layout>
    @section('header_title', 'Kelola Stok Barang')

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules/stocks.module.css') }}">
    @endpush

    <div x-data="{
        modalOpen: false,
        selectedProductId: '',
        selectedProductName: '',
        selectedProductSku: '',
        selectedMinStock: 0,
        currentStock: 0,
        dropdownProductId: '',
        adjustType: 'in',
        quantity: '',
        description: '',
        openModal(productId = '', productName = '', productSku = '', stock = 0, minStock = 0) {
            this.selectedProductId = productId;
            this.selectedProductName = productName;
            this.selectedProductSku = productSku;
            this.currentStock = stock;
            this.selectedMinStock = minStock;
            this.dropdownProductId = productId;
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
        <div class="stocks-header">
            <div>
                <h3 class="stocks-title">Kelola Stok Barang</h3>
                <p class="stocks-subtitle">Pantau ketersediaan stok fisik produk gudang dan deteksi stok yang menipis secara real-time.</p>
            </div>
            <div class="stocks-header-actions">
                <a href="{{ route('admin.stocks.history') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Riwayat Stok</span>
                </a>
                <button type="button" @click="openModal()" class="btn btn-primary">
                    <i class="fa-solid fa-plus-minus"></i>
                    <span>Penyesuaian Stok</span>
                </button>
            </div>
        </div>

        <!-- Low Stock Warning Alert Banner -->
        @if(($counts['low'] ?? 0) > 0 || ($counts['empty'] ?? 0) > 0)
            <div class="stocks-alert-banner">
                <div class="stocks-alert-left">
                    <i class="fa-solid fa-triangle-exclamation stocks-alert-icon"></i>
                    <div class="stocks-alert-text">
                        <strong>Perhatian Ketersediaan Stok:</strong>
                        Terdapat <b>{{ $counts['low'] }} produk stok menipis</b>
                        @if(($counts['empty'] ?? 0) > 0)
                            dan <b>{{ $counts['empty'] }} produk habis</b>
                        @endif
                        yang membutuhkan pengadaan ulang segera.
                    </div>
                </div>
                @if(request('status') !== 'low')
                    <a href="{{ route('admin.stocks.index', ['status' => 'low']) }}" class="stocks-alert-btn">
                        <i class="fa-solid fa-filter"></i>
                        <span>Lihat Stok Menipis</span>
                    </a>
                @endif
            </div>
        @endif

        <!-- Status Metric Cards (Interactive) -->
        <div class="stocks-stats-grid">
            <a href="{{ route('admin.stocks.index') }}" class="stat-card {{ !request('status') ? 'active' : '' }}">
                <div class="stat-card-label">
                    <i class="fa-solid fa-boxes-stacked"></i> Semua Produk
                </div>
                <div class="stat-card-value">{{ $counts['total'] ?? 0 }}</div>
            </a>

            <a href="{{ route('admin.stocks.index', ['status' => 'safe']) }}" class="stat-card stat-card-success {{ request('status') === 'safe' ? 'active-success' : '' }}">
                <div class="stat-card-label">
                    <i class="fa-solid fa-circle-check"></i> Stok Aman
                </div>
                <div class="stat-card-value">{{ $counts['safe'] ?? 0 }}</div>
            </a>

            <a href="{{ route('admin.stocks.index', ['status' => 'low']) }}" class="stat-card stat-card-warning {{ request('status') === 'low' ? 'active-warning' : '' }} {{ ($counts['low'] ?? 0) > 0 ? 'has-pending' : '' }}">
                <div class="stat-card-label">
                    <i class="fa-solid fa-triangle-exclamation"></i> Stok Menipis
                </div>
                <div class="stat-card-value">{{ $counts['low'] ?? 0 }}</div>
            </a>

            <a href="{{ route('admin.stocks.index', ['status' => 'empty']) }}" class="stat-card stat-card-danger {{ request('status') === 'empty' ? 'active-danger' : '' }}">
                <div class="stat-card-label">
                    <i class="fa-solid fa-circle-xmark"></i> Stok Habis
                </div>
                <div class="stat-card-value">{{ $counts['empty'] ?? 0 }}</div>
            </a>
        </div>

        <!-- Filter & Search Card -->
        <form method="GET" action="{{ route('admin.stocks.index') }}" class="stocks-filter-card">
            <div class="stocks-filter-row">
                <div class="filter-search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk atau kode SKU...">
                </div>

                <div class="filter-select-wrapper">
                    <select name="status" class="form-control" onchange="this.form.submit()">
                        <option value="">Semua Status Stok</option>
                        <option value="safe" {{ request('status') === 'safe' ? 'selected' : '' }}>Stok Aman</option>
                        <option value="low" {{ request('status') === 'low' ? 'selected' : '' }}>Stok Menipis</option>
                        <option value="empty" {{ request('status') === 'empty' ? 'selected' : '' }}>Stok Habis</option>
                    </select>
                </div>

                <button type="submit" class="filter-submit-btn">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filter</span>
                </button>

                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.stocks.index') }}" class="filter-reset-btn">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>

        <!-- Stock Table Container -->
        <div class="stocks-table-container">
            <table class="stocks-table">
                <thead>
                    <tr>
                        <th style="width: 4%; text-align: center;">No</th>
                        <th style="width: 36%;">Produk &amp; SKU</th>
                        <th style="width: 17%;">Harga Modal &amp; Jual</th>
                        <th style="width: 10%; text-align: center;">Batas Min.</th>
                        <th style="width: 11%; text-align: center;">Sisa Stok</th>
                        <th style="width: 11%; text-align: center;">Status</th>
                        <th style="width: 11%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        @php
                            $isEmpty = $product->stock <= 0;
                            $isLow   = !$isEmpty && $product->stock <= $product->min_stock;
                        @endphp
                        <tr>
                            <td style="text-align: center; color: var(--secondary); font-size: 0.8125rem;">
                                {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                            </td>

                            <td>
                                <div class="stocks-product-cell">
                                    @if($product->primaryImage && file_exists(public_path('storage/' . $product->primaryImage->path)))
                                        <img src="{{ asset('storage/' . $product->primaryImage->path) }}" alt="{{ $product->name }}" class="stocks-product-img">
                                    @else
                                        <div class="stocks-product-img-fallback">
                                            <i class="fa-solid fa-box"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="stocks-product-name">{{ $product->name }}</div>
                                        <div class="stocks-meta-text">
                                            <span class="stocks-sku-pill">SKU: {{ $product->sku }}</span>
                                            @if($product->brand)
                                                <span>• {{ $product->brand->name }}</span>
                                            @endif
                                            @if($product->category)
                                                <span>• {{ $product->category->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="stocks-price-modal">
                                    Rp {{ number_format($product->price_modal, 0, ',', '.') }}
                                </div>
                                <div class="stocks-price-jual">
                                    Jual: Rp {{ number_format($product->price_jual, 0, ',', '.') }}
                                </div>
                            </td>

                            <td style="text-align: center;">
                                <span class="stocks-min-stock">{{ $product->min_stock }} pcs</span>
                            </td>

                            <td style="text-align: center;">
                                <span class="stock-num {{ $isEmpty ? 'stock-empty' : ($isLow ? 'stock-low' : 'stock-safe') }}">
                                    {{ $product->stock }} pcs
                                </span>
                            </td>

                            <td style="text-align: center;">
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
                            </td>

                            <td style="text-align: center;">
                                <button type="button" 
                                        @click="openModal({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $product->sku }}', {{ $product->stock }}, {{ $product->min_stock }})"
                                        class="btn-table-action">
                                    <i class="fa-solid fa-sliders"></i>
                                    <span>Sesuaikan</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 48px 20px; color: var(--secondary);">
                                <i class="fa-solid fa-boxes-stacked" style="font-size: 2.5rem; opacity: 0.35; margin-bottom: 12px; display: block;"></i>
                                <div style="font-weight: 700; color: var(--dark); margin-bottom: 4px; font-size: 1rem;">Tidak Ada Data Produk</div>
                                <div style="font-size: 0.85rem;">Tidak ada produk yang cocok dengan pencarian atau status filter yang dipilih.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if(method_exists($products, 'links') && $products->hasPages())
                <div class="stocks-pagination-wrapper">
                    <div class="stocks-pagination-info">
                        Menampilkan <strong>{{ $products->firstItem() ?? 0 }}</strong> - <strong>{{ $products->lastItem() ?? 0 }}</strong> dari <strong>{{ $products->total() }}</strong> produk
                    </div>
                    <div class="stocks-pagination-links">
                        {{ $products->links() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Stock Adjustment Modal (Alpine.js) -->
        <div x-show="modalOpen" class="stocks-modal-backdrop" @click="closeModal()" x-cloak style="display: none;">
            <div class="stocks-modal-box" @click.stop>
                <div class="stocks-modal-header">
                    <h4 class="stocks-modal-title">
                        <i class="fa-solid fa-sliders text-primary"></i>
                        <span>Penyesuaian Stok Barang</span>
                    </h4>
                    <button type="button" @click="closeModal()" class="stocks-modal-close" aria-label="Tutup">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.stocks.adjust') }}">
                    @csrf
                    <div class="stocks-modal-body">
                        <!-- Product Info (if selected from table row) -->
                        <div class="selected-product-box" x-show="selectedProductId">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div>
                                    <div class="font-bold text-dark text-sm" x-text="selectedProductName"></div>
                                    <div class="stocks-meta-text" style="margin-top: 2px;">
                                        <span>SKU: <strong class="text-primary font-mono" x-text="selectedProductSku"></strong></span>
                                        <span style="margin: 0 4px;">•</span>
                                        <span>Min. Stok: <strong x-text="selectedMinStock + ' pcs'"></strong></span>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-size: 0.72rem; color: var(--secondary);">Sisa Stok Fisik</div>
                                    <div style="font-size: 1.05rem; font-weight: 800;" 
                                         :class="currentStock <= 0 ? 'text-danger' : (currentStock <= selectedMinStock ? 'text-warning' : 'text-success')" 
                                         x-text="currentStock + ' pcs'">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="product_id" :value="selectedProductId" :disabled="!selectedProductId">
                        </div>

                        <!-- Product Selection Dropdown (if opened generally from header) -->
                        <div class="form-group mb-3" x-show="!selectedProductId">
                            <label class="form-label font-semibold text-sm">Pilih Produk <span class="text-danger">*</span></label>
                            <select name="product_id" x-model="dropdownProductId" class="form-control no-custom-select" :required="!selectedProductId" :disabled="!!selectedProductId">
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($allProducts as $p)
                                    <option value="{{ $p->id }}">
                                        {{ $p->name }} (Sisa: {{ $p->stock }} pcs | SKU: {{ $p->sku }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Adjustment Type -->
                        <div class="form-group mb-3">
                            <label class="form-label font-semibold text-sm">Jenis Perubahan Stok <span class="text-danger">*</span></label>
                            <div class="adjust-type-grid">
                                <label class="adjust-type-option" :class="{ 'active-in': adjustType === 'in' }">
                                    <input type="radio" name="type" value="in" x-model="adjustType" class="sr-only">
                                    <i class="fa-solid fa-circle-plus"></i>
                                    <span>Tambah Stok (+)</span>
                                </label>
                                <label class="adjust-type-option" :class="{ 'active-out': adjustType === 'out' }">
                                    <input type="radio" name="type" value="out" x-model="adjustType" class="sr-only">
                                    <i class="fa-solid fa-circle-minus"></i>
                                    <span>Kurangi Stok (-)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="form-group mb-3">
                            <label class="form-label font-semibold text-sm">Jumlah Barang <span class="text-danger">*</span></label>
                            <div class="input-addon-wrap">
                                <input type="number" name="quantity" x-model="quantity" class="form-control" required min="1" placeholder="Contoh: 10">
                                <span class="input-addon-text">pcs</span>
                            </div>
                        </div>

                        <!-- Description / Reason -->
                        <div class="form-group mb-2">
                            <label class="form-label font-semibold text-sm">Keterangan / Alasan Perubahan <span class="text-danger">*</span></label>
                            <textarea name="description" x-model="description" class="form-control" rows="2" required placeholder="Contoh: Stok masuk dari supplier, Stock opname rutin, Barang rusak/cacat..."></textarea>
                        </div>
                    </div>

                    <div class="stocks-modal-footer">
                        <button type="button" @click="closeModal()" class="btn btn-secondary">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Penyesuaian
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-admin-layout>
