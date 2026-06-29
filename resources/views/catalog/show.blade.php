<x-catalog-layout>
    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules/catalog-show.module.css') }}">
    @endpush

    <div class="container catalog-show-wrap">
        <!-- Breadcrumb / Back button -->
        <div class="catalog-back-btn">
            <a href="{{ route('catalog.index') }}">
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
                                 data-src="{{ asset('storage/' . $image->path) }}"
                                 onclick="document.getElementById('main-product-image').src = this.dataset.src">
                                <img src="{{ asset('storage/' . $image->path) }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right Pane: Product Details -->
            <div class="flex flex-col">
                <div class="product-meta product-info-meta">
                    <span>{{ $product->category->name }}</span>
                    <span>{{ $product->brand->name }}</span>
                </div>

                <h1 class="font-bold product-detail-title">{{ $product->name }}</h1>
                
                <div class="product-sku-row">
                    <span><strong>SKU:</strong> {{ $product->sku }}</span>
                    @if ($product->barcode)
                        <span><strong>Barcode:</strong> {{ $product->barcode }}</span>
                    @endif
                    @if ($product->stock > 0)
                        <span><strong>Stok: </strong>{{ $product->stock }} unit</span>
                    @else
                        <span><strong>Stok: </strong> Habis</span>
                    @endif
                    
                </div>

                <div class="product-price-val">
                    Rp {{ number_format($product->price_jual, 0, ',', '.') }}
                </div>

                <!-- Tabs/Accordions for description & specs -->
                <div class="product-tabs">
                    <div>
                        <h4 class="font-bold product-section-heading">Deskripsi Produk</h4>
                        <p class="product-description">
                            {{ $product->description ?: 'Tidak ada deskripsi untuk produk ini.' }}
                        </p>
                    </div>

                    @if ($product->specs)
                        <div class="product-specs-block">
                            <h4 class="font-bold mb-4 product-section-heading">Spesifikasi Detail</h4>
                            <article class="product-specs-content">
                                {!! nl2br(e(str_replace('\n', "\n", $product->specs))) !!}
                            </article>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</x-catalog-layout>
