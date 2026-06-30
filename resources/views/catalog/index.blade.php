<x-catalog-layout>
    <!-- Hero Section -->
    <section class="container catalog-section-top">
        <div class="guest-hero">
            <div class="hero-content">
                <span class="catalog-hero-badge">Hot Gadget Deals</span>
                <h1 class="hero-title mt-4">Diskon Gadget Pilihan s/d 15% Off</h1>
                <p class="hero-subtitle">Temukan teknologi terbaik dengan penawaran menarik dari toko kami. Produk berkualitas, garansi terjamin.</p>
                <a href="#katalog-produk" class="btn btn-secondary catalog-hero-cta">
                    Belanja Sekarang <i class="fa-solid fa-arrow-down ml-1"></i>
                </a>
            </div>
            <div class="catalog-hero-graphic">
                <div class="catalog-hero-glow"></div>
                <img src="{{ asset('img/comp.png') }}" alt="Comp" class="w-1/2">
            </div>
        </div>
    </section>

    <!-- Catalog Section -->
    <section class="container" id="katalog-produk">
        <div class="catalog-section-header">
            <div>
                <h2 class="font-bold catalog-heading">Katalog Produk</h2>
                <p class="text-secondary text-sm">Cari produk berdasarkan kategori, merk, atau spesifikasi.</p>
            </div>
        </div>

        <div class="catalog-wrapper" x-data="{ filterOpen: false }">

            {{-- DESKTOP FILTER: sidebar kiri --}}
            <form method="GET" action="{{ route('catalog.index') }}" class="filter-bar filter-bar-desktop">
                <div class="filter-inputs">
                    <p class="font-bold">Cari Produk</p>
                    <div style="position:relative">
                        <i class="fa-solid fa-magnifying-glass filter-search-icon"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk" class="form-control filter-input-indent">
                    </div>
                </div>
                <div class="filter-inputs">
                    <p class="font-bold">Kategori</p>
                    <div class="category-wrapper">
                        @foreach ($categories as $category)
                            <div class="category-normal {{ request('category') == $category->id ? 'category-active' : '' }}"
                                onclick="window.location.href='{{ request()->fullUrlWithQuery(['category' => $category->id]) }}'">
                                {{ $category->name }}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="filter-inputs">
                    <p class="font-bold">Brand</p>
                    <div>
                        @foreach ($brands as $brand)
                            <div class="category-normal {{ request('brand') == $brand->id ? 'category-active' : '' }}"
                                onclick="window.location.href='{{ request()->fullUrlWithQuery(['brand' => $brand->id]) }}'">
                                {{ $brand->name }}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    @if (request()->anyFilled(['search', 'category', 'brand']))
                        <a href="{{ route('catalog.index') }}" class="btn btn-secondary">Clear</a>
                    @endif
                </div>
            </form>

            {{-- MOBILE: search bar + tombol filter --}}
            <div class="catalog-mobile-search">
                <form method="GET" action="{{ route('catalog.index') }}" class="catalog-mobile-search-form">
                    @if (request()->filled('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if (request()->filled('brand'))
                        <input type="hidden" name="brand" value="{{ request('brand') }}">
                    @endif
                    <div class="catalog-mobile-search-inner">
                        <i class="fa-solid fa-magnifying-glass filter-search-icon"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..." class="form-control filter-input-indent">
                    </div>
                </form>
                <button type="button" @click="filterOpen = true" class="btn btn-secondary catalog-filter-btn">
                    <i class="fa-solid fa-sliders"></i> Filter
                    @if (request()->anyFilled(['category', 'brand']))
                        <span class="catalog-filter-dot"></span>
                    @endif
                </button>
                @if (request()->anyFilled(['search', 'category', 'brand']))
                    <a href="{{ route('catalog.index') }}" class="btn btn-secondary catalog-clear-btn">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>

            {{-- MOBILE FILTER MODAL --}}
            <div class="catalog-filter-modal-backdrop" x-show="filterOpen" @click.self="filterOpen = false" x-cloak>
                <div class="catalog-filter-modal" x-show="filterOpen"
                    x-transition:enter="catalog-modal-transition"
                    x-transition:enter-start="catalog-modal-hidden"
                    x-transition:enter-end="catalog-modal-visible"
                    x-transition:leave="catalog-modal-transition"
                    x-transition:leave-start="catalog-modal-visible"
                    x-transition:leave-end="catalog-modal-hidden">
                    <div class="catalog-filter-modal-header">
                        <span class="font-bold" style="font-size:1.05rem">Filter Produk</span>
                        <button type="button" @click="filterOpen = false" class="modal-close">&times;</button>
                    </div>
                    <form method="GET" action="{{ route('catalog.index') }}" class="catalog-filter-modal-body">
                        <div class="form-group">
                            <label class="form-label">Cari Nama Produk</label>
                            <div style="position:relative">
                                <i class="fa-solid fa-magnifying-glass filter-search-icon"></i>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..." class="form-control filter-input-indent">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kategori</label>
                            <select name="category" class="form-control">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Brand</label>
                            <select name="brand" class="form-control">
                                <option value="">Semua Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-2 catalog-filter-modal-actions">
                            <button type="submit" class="btn btn-primary" style="flex:1">
                                <i class="fa-solid fa-filter"></i> Terapkan Filter
                            </button>
                            @if (request()->anyFilled(['search', 'category', 'brand']))
                                <a href="{{ route('catalog.index') }}" class="btn btn-secondary">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="catalog-products-col">
                @if ($products->isEmpty())
                    <div class="catalog-empty-state">
                        <i class="fa-solid fa-box-open catalog-empty-icon"></i>
                        <h3 class="font-bold">Produk Tidak Ditemukan</h3>
                        <p class="text-secondary text-sm">Coba bersihkan pencarian atau ganti filter Anda.</p>
                    </div>
                @else
                    <div class="product-grid">
                        @foreach ($products as $product)
                            <article class="product-card"
                                data-url="{{ route('catalog.show', $product->id) }}"
                                onclick="window.location.href=this.dataset.url">
                                @if ($product->primaryImage)
                                    <img src="{{ asset('storage/' . $product->primaryImage->path) }}" class="product-card-img" alt="{{ $product->name }}">
                                @else
                                    <div class="product-card-img flex items-center justify-center catalog-fallback-img">
                                        <i class="fa-solid fa-laptop-code catalog-fallback-icon"></i>
                                    </div>
                                @endif
                                <div class="product-card-body">
                                    <div class="product-meta">
                                        <span>{{ $product->category->name }}</span>
                                        <span>{{ $product->brand->name }}</span>
                                    </div>
                                    <h3 class="product-title" title="{{ $product->name }}">{{ Str::limit($product->name, 48) }}</h3>
                                    <p class="text-secondary text-xs catalog-description-clamp">{{ $product->description }}</p>
                                    <div class="product-price-container">
                                        <div class="product-price-wrapper">
                                            <p class="text-xs">Harga</p>
                                            <div class="product-price">Rp {{ number_format($product->price_jual, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="product-price-wrapper">
                                            <p class="text-xs">Stok</p>
                                            <div class="product-price">
                                                @if ($product->stock > 0) {{ $product->stock }} unit @else Habis @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <div class="flex justify-center" style="margin-top:2rem">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

        </div>
    </section>
</x-catalog-layout>
