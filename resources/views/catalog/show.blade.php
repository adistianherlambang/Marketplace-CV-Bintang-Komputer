<x-catalog-layout>
    <div class="container catalog-show-wrap">
        <!-- Breadcrumb / Back button -->
        <div class="catalog-back-btn">
            <a href="{{ route('catalog.index') }}" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
            </a>
        </div>

        <div class="grid product-detail-card">
            
            <!-- Left Pane: Product Image Gallery -->
            <div class="flex flex-col gap-4">
                <div class="product-main-image">
                    @if ($product->primaryImage)
                        <img src="{{ asset('storage/' . $product->primaryImage->path) }}" id="main-product-image" alt="{{ $product->name }}">
                    @else
                        <i class="fa-solid fa-laptop-code product-no-image-icon"></i>
                    @endif
                </div>

                <!-- Gallery Sub-images -->
                @if ($product->images->count() > 1)
                    <div class="flex gap-2 product-gallery-strip">
                        @foreach ($product->images as $image)
                            <div class="product-thumb-sm" 
                                 onclick="document.getElementById('main-product-image').src = '{{ asset('storage/' . $image->path) }}'">
                                <img src="{{ asset('storage/' . $image->path) }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right Pane: Product Details -->
            <div class="flex flex-col">
                <div class="product-meta product-info-meta">
                    <span class="badge badge-info">{{ $product->category->name }}</span>
                    <span class="badge badge-success">{{ $product->brand->name }}</span>
                </div>

                <h1 class="font-bold product-detail-title">{{ $product->name }}</h1>
                
                <div class="product-sku-row">
                    <span><strong>SKU:</strong> {{ $product->sku }}</span>
                    @if ($product->barcode)
                        <span><strong>Barcode:</strong> {{ $product->barcode }}</span>
                    @endif
                </div>

                <!-- Price and Stock Details -->
                <div class="product-price-box">
                    <div class="text-secondary text-xs font-semibold product-price-label">Harga Jual Terbaik</div>
                    <div class="product-price-val">
                        Rp {{ number_format($product->price_jual, 0, ',', '.') }}
                    </div>
                    
                    <div class="flex items-center gap-2 mt-4">
                        <span class="badge {{ $product->stock > $product->min_stock ? 'badge-success' : ($product->stock > 0 ? 'badge-warning' : 'badge-danger') }}">
                            @if ($product->stock > 0)
                                Ready ({{ $product->stock }} unit)
                            @else
                                Stok Habis
                            @endif
                        </span>
                        <span class="text-xs text-secondary">Merk: {{ $product->brand->name }} | Supplier: {{ $product->supplier->name }}</span>
                    </div>
                </div>

                <!-- Tabs/Accordions for description & specs -->
                <div class="product-tabs">
                    <div>
                        <h4 class="font-bold mb-4 product-section-heading">Deskripsi Produk</h4>
                        <p class="product-description">
                            {{ $product->description ?: 'Tidak ada deskripsi untuk produk ini.' }}
                        </p>
                    </div>

                    @if ($product->specs)
                        <div class="product-specs-block">
                            <h4 class="font-bold mb-4 product-section-heading">Spesifikasi Detail</h4>
                            <div class="product-specs-content">
                                {{ $product->specs }}
                            </div>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</x-catalog-layout>
