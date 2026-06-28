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
            
            <!-- Graphic element -->
            <div class="catalog-hero-graphic">
                <div class="catalog-hero-glow"></div>
                <img src="{{ asset('img/comp.png') }}" alt="Comp" class="w-1/2">
            </div>
        </div>
    </section>

    <!-- Quick Stats Features -->
    <!-- <section class="container catalog-features-section">
        <div class="grid catalog-feature-grid">
            <div class="flex items-center gap-4 catalog-feature-card">
                <div class="catalog-feature-icon catalog-fi-amber">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm">Best Sellers</h4>
                    <p class="text-xs text-secondary">Produk Terlaris Minggu Ini</p>
                </div>
            </div>
            <div class="flex items-center gap-4 catalog-feature-card">
                <div class="catalog-feature-icon catalog-fi-blue">
                    <i class="fa-solid fa-tags"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm">New Arrivals</h4>
                    <p class="text-xs text-secondary">Stok &amp; Barang Teranyar</p>
                </div>
            </div>
            <div class="flex items-center gap-4 catalog-feature-card">
                <div class="catalog-feature-icon catalog-fi-green">
                    <i class="fa-solid fa-certificate"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm">Original Guarantee</h4>
                    <p class="text-xs text-secondary">100% Produk Original</p>
                </div>
            </div>
            <div class="flex items-center gap-4 catalog-feature-card">
                <div class="catalog-feature-icon catalog-fi-pink">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm">Support 24/7</h4>
                    <p class="text-xs text-secondary">Hubungi kami kapan saja</p>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Catalog Section -->
    <section class="container" id="katalog-produk">
        <div class="catalog-section-header">
            <div>
                <h2 class="font-bold catalog-heading">Katalog Produk</h2>
                <p class="text-secondary text-sm">Cari produk berdasarkan kategori, merk, atau spesifikasi.</p>
            </div>
        </div>

        <div class="catalog-wrapper">
            
            <!-- Filter and Search Bar -->
            <form method="GET" action="{{ route('catalog.index') }}" class="filter-bar">
                
                <div class="filter-inputs">
                    <p class="font-bold">Filter kategori produk</p>
                    <!-- Search input -->
                    <div class="filter-search-wrapper-full">
                        <i class="fa-solid fa-magnifying-glass filter-search-icon"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk" class="form-control filter-input-indent">
                    </div>
                </div>

                <div class="filter-inputs">
                    <p class="font-bold">Kategori</p>

                    <!-- category list -->
                    <div class="category-wrapper">
                        @foreach ($categories as $category)
                            <div
                                class="category-normal {{ request('category') == $category->id ? 'category-active' : '' }}"
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
                            <div
                                class="category-normal {{ request('brand') == $brand->id ? 'category-active' : '' }}"
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
                        <a href="{{ route('catalog.index') }}" class="btn btn-secondary">
                            Clear
                        </a>
                    @endif
                </div>
            </form>

            <!-- Product Grid -->
            @if ($products->isEmpty())
                <div class="catalog-empty-state">
                    <i class="fa-solid fa-box-open catalog-empty-icon"></i>
                    <h3 class="font-bold">Produk Tidak Ditemukan</h3>
                    <p class="text-secondary text-sm">Coba bersihkan pencarian atau ganti filter Anda.</p>
                </div>
            @else
                <div class="product-grid">
                    @foreach ($products as $product)
                        <article class="product-card">
                            @if ($product->primaryImage)
                                <img src="{{ asset('storage/' . $product->primaryImage->path) }}" class="product-card-img" alt="{{ $product->name }}">
                            @else
                                <!-- Beautiful native gradient fallback image -->
                                <div class="product-card-img flex items-center justify-center catalog-fallback-img">
                                    <i class="fa-solid fa-laptop-code catalog-fallback-icon"></i>
                                </div>
                            @endif
                            
                            <div class="product-card-body">
                                <div class="product-meta">
                                    <span class="badge badge-info">{{ $product->category->name }}</span>
                                    <span class="badge badge-success">{{ $product->brand->name }}</span>
                                </div>
                                
                                <h3 class="product-title" title="{{ $product->name }}">{{ Str::limit($product->name, 48) }}</h3>
                                <p class="text-secondary text-xs catalog-description-clamp">
                                    {{ $product->description }}
                                </p>
                                
                                <div class="product-price">
                                    Rp {{ number_format($product->price_jual, 0, ',', '.') }}
                                </div>
                                
                                <div class="product-card-footer">
                                    <span class="text-xs font-semibold {{ $product->stock > $product->min_stock ? 'text-success' : 'text-danger' }}">
                                        @if ($product->stock > 0)
                                            <i class="fa-solid fa-check mr-1"></i> Ready ({{ $product->stock }} unit)
                                        @else
                                            <i class="fa-solid fa-xmark mr-1"></i> Habis
                                        @endif
                                    </span>
                                    
                                    <a href="{{ route('catalog.show', $product->id) }}" class="btn btn-secondary btn-sm catalog-detail-btn">
                                        Detail <i class="fa-solid fa-chevron-right text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                
                <!-- Pagination Links -->
                <div class="flex justify-center">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </section>
</x-catalog-layout>