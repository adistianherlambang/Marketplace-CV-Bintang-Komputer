<x-catalog-layout>
    <!-- Hero Section -->
    <section class="container catalog-section-top">
        <div class="guest-hero">
            <div class="hero-content">
                <span class="catalog-hero-badge">Komputer, Laptop &amp; Gadget Pilihan</span>
                <h1 class="hero-title mt-4">CV Bintang Jaya Komputer</h1>
                <p class="hero-subtitle">Pusat penjualan laptop, komputer PC, aksesoris, dan solusi IT terlengkap dan bergaransi di Kota Metro. Pesan online cepat dengan kurir GrabExpress.</p>
                <a href="#katalog-produk" class="btn btn-secondary catalog-hero-cta">
                    Lihat Produk <i class="fa-solid fa-arrow-down ml-1"></i>
                </a>
            </div>
            <div class="catalog-hero-graphic">
                <div class="catalog-hero-glow"></div>
                <img src="{{ asset('img/comp.png') }}" alt="CV Bintang Jaya Komputer" class="w-1/2">
            </div>
        </div>
    </section>

    <!-- Catalog Section -->
    <section class="container" id="katalog-produk">
        <div class="catalog-section-header">
            <div>
                <h2 class="font-bold catalog-heading">Katalog Produk</h2>
                <p class="text-secondary text-sm">Temukan perangkat komputer dan aksesoris berkualitas untuk kebutuhan Anda.</p>
            </div>
        </div>

        <div class="catalog-wrapper">

            {{-- DESKTOP SEARCH BAR --}}
            <form method="GET" action="{{ route('catalog.index') }}" class="filter-bar filter-bar-desktop" style="min-width: 250px;">
                <div class="filter-inputs">
                    <p class="font-bold text-dark"><i class="fa-solid fa-magnifying-glass text-primary mr-1"></i> Cari Produk</p>
                    <div style="position:relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..." class="form-control filter-input-indent">
                    </div>
                </div>
                <div class="flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fa-solid fa-search"></i> Cari
                    </button>
                    @if (request()->filled('search'))
                        <a href="{{ route('catalog.index') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </div>
            </form>

            {{-- MOBILE SEARCH BAR --}}
            <div class="catalog-mobile-search">
                <form method="GET" action="{{ route('catalog.index') }}" class="catalog-mobile-search-form" style="display: flex; gap: 8px; width: 100%;">
                    <div class="catalog-mobile-search-inner" style="flex: 1;">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..." class="form-control filter-input-indent">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-search"></i>
                    </button>
                    @if (request()->filled('search'))
                        <a href="{{ route('catalog.index') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </form>
            </div>

            <!-- Product Grid -->
            <div class="catalog-products-col">
                @if ($products->isEmpty())
                    <div class="catalog-empty-state">
                        <i class="fa-solid fa-box-open catalog-empty-icon"></i>
                        <h3 class="font-bold">Produk Tidak Ditemukan</h3>
                        <p class="text-secondary text-sm">Coba bersihkan kata kunci pencarian Anda.</p>
                        @if (request()->filled('search'))
                            <a href="{{ route('catalog.index') }}" class="btn btn-secondary mt-3">Lihat Semua Produk</a>
                        @endif
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
                                    <h3 class="product-title" title="{{ $product->name }}">{{ Str::limit($product->name, 50) }}</h3>
                                    <p class="text-secondary text-xs catalog-description-clamp">{{ $product->description }}</p>
                                    <div class="product-price-container">
                                        <div class="product-price-wrapper">
                                            <p class="text-xs">Harga</p>
                                            <div class="product-price">Rp {{ number_format($product->price_jual, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="product-price-wrapper">
                                            <p class="text-xs">Stok</p>
                                            <div class="product-price">
                                                @if ($product->stock > 0)
                                                    <span class="text-success font-semibold">{{ $product->stock }} unit</span>
                                                @else
                                                    <span class="text-danger font-semibold">Habis</span>
                                                @endif
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
