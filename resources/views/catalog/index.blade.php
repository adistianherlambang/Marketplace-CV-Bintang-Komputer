<x-catalog-layout>
    <!-- Hero Section -->
    <section class="container" style="margin-top: 24px;">
        <div class="guest-hero">
            <div class="hero-content">
                <span style="background-color: rgba(255,255,255,0.2); padding: 6px 12px; border-radius: 20px; font-size: 0.875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Hot Gadget Deals</span>
                <h1 class="hero-title" style="margin-top: 12px;">Diskon Gadget Pilihan s/d 15% Off</h1>
                <p class="hero-subtitle">Temukan teknologi terbaik dengan penawaran menarik dari toko kami. Produk berkualitas, garansi terjamin.</p>
                <a href="#katalog-produk" class="btn btn-secondary" style="color: var(--primary); font-weight: 700; border: none; border-radius: 30px; padding: 12px 28px;">
                    Belanja Sekarang <i class="fa-solid fa-arrow-down ml-1"></i>
                </a>
            </div>
            
            <!-- Graphic element or simple image display -->
            <div style="z-index: 2; width: 45%; max-width: 450px; display: flex; align-items: center; justify-content: center; position: relative;">
                <div style="background-color: rgba(255,255,255,0.1); border-radius: 50%; width: 350px; height: 350px; position: absolute; filter: blur(40px); z-index: 1;"></div>
                <i class="fa-solid fa-laptop" style="font-size: 15rem; color: rgba(255,255,255,0.85); z-index: 2; text-shadow: 0 10px 30px rgba(0,0,0,0.25);"></i>
            </div>
        </div>
    </section>

    <!-- Quick Stats Features -->
    <section class="container" style="margin-bottom: 48px;">
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px;">
            <div class="flex items-center gap-4" style="background-color: var(--white); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border);">
                <div style="width: 48px; height: 48px; border-radius: 50%; background-color: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm">Best Sellers</h4>
                    <p class="text-xs text-secondary">Produk Terlaris Minggu Ini</p>
                </div>
            </div>
            <div class="flex items-center gap-4" style="background-color: var(--white); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border);">
                <div style="width: 48px; height: 48px; border-radius: 50%; background-color: #dbeafe; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fa-solid fa-tags"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm">New Arrivals</h4>
                    <p class="text-xs text-secondary">Stok & Barang Teranyar</p>
                </div>
            </div>
            <div class="flex items-center gap-4" style="background-color: var(--white); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border);">
                <div style="width: 48px; height: 48px; border-radius: 50%; background-color: #dcfce7; color: #15803d; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fa-solid fa-certificate"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm">Original Guarantee</h4>
                    <p class="text-xs text-secondary">100% Produk Original</p>
                </div>
            </div>
            <div class="flex items-center gap-4" style="background-color: var(--white); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border);">
                <div style="width: 48px; height: 48px; border-radius: 50%; background-color: #fce7f3; color: #db2777; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm">Support 24/7</h4>
                    <p class="text-xs text-secondary">Hubungi kami kapan saja</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Catalog Section -->
    <section class="container" id="katalog-produk">
        <div style="margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h2 class="font-bold" style="font-size: 1.75rem;">Katalog Produk</h2>
                <p class="text-secondary text-sm">Cari produk berdasarkan kategori, merk, atau spesifikasi.</p>
            </div>
        </div>

        <!-- Filter and Search Bar -->
        <form method="GET" action="{{ route('catalog.index') }}" class="filter-bar">
            <div class="filter-inputs">
                <!-- Search input -->
                <div style="position: relative; flex-grow: 1;">
                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 14px; top: 14px; color: var(--secondary);"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, SKU, merk, atau spek..." class="form-control" style="padding-left: 40px;">
                </div>
                
                <!-- Category select -->
                <select name="category" class="form-control" style="max-width: 200px;" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <!-- Brand select -->
                <select name="brand" class="form-control" style="max-width: 200px;" onchange="this.form.submit()">
                    <option value="">Semua Merk / Brand</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
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
            <div style="text-align: center; padding: 60px 0; background-color: var(--white); border-radius: var(--radius); border: 1px solid var(--border);">
                <i class="fa-solid fa-box-open" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 16px;"></i>
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
                            <div class="product-card-img flex items-center justify-center" style="background: linear-gradient(135deg, #f1f5f9 0%, #cbd5e1 100%);">
                                <i class="fa-solid fa-laptop-code" style="font-size: 4rem; color: #94a3b8;"></i>
                            </div>
                        @endif
                        
                        <div class="product-card-body">
                            <div class="product-meta">
                                <span class="badge badge-info">{{ $product->category->name }}</span>
                                <span class="badge badge-success">{{ $product->brand->name }}</span>
                            </div>
                            
                            <h3 class="product-title" title="{{ $product->name }}">{{ Str::limit($product->name, 48) }}</h3>
                            <p class="text-secondary text-xs" style="margin-bottom: 12px; height: 36px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
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
                                
                                <a href="{{ route('catalog.show', $product->id) }}" class="btn btn-secondary btn-sm" style="border-radius: 20px;">
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
    </section>
</x-catalog-layout>
