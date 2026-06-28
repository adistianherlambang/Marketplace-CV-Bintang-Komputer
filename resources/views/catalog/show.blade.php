<x-catalog-layout>
    <div class="container" style="margin-top: 40px; margin-bottom: 60px;">
        <!-- Breadcrumb / Back button -->
        <div style="margin-bottom: 24px;">
            <a href="{{ route('catalog.index') }}" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
            </a>
        </div>

        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 48px; background-color: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 40px; box-shadow: var(--shadow);">
            
            <!-- Left Pane: Product Image Gallery -->
            <div class="flex flex-col gap-4">
                <div style="border-radius: var(--radius); border: 1px solid var(--border); overflow: hidden; background-color: var(--light); height: 400px; display: flex; align-items: center; justify-content: center;">
                    @if ($product->primaryImage)
                        <img src="{{ asset('storage/' . $product->primaryImage->path) }}" style="width: 100%; height: 100%; object-fit: contain;" id="main-product-image" alt="{{ $product->name }}">
                    @else
                        <i class="fa-solid fa-laptop-code" style="font-size: 8rem; color: #94a3b8;"></i>
                    @endif
                </div>

                <!-- Gallery Sub-images -->
                @if ($product->images->count() > 1)
                    <div class="flex gap-2" style="overflow-x: auto; padding-bottom: 8px;">
                        @foreach ($product->images as $image)
                            <div style="width: 80px; height: 80px; border-radius: var(--radius-sm); border: 1px solid var(--border); cursor: pointer; overflow: hidden; background-color: var(--light); flex-shrink: 0;" 
                                 onclick="document.getElementById('main-product-image').src = '{{ asset('storage/' . $image->path) }}'">
                                <img src="{{ asset('storage/' . $image->path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right Pane: Product Details -->
            <div class="flex flex-col">
                <div class="product-meta" style="margin-bottom: 12px; font-size: 0.825rem;">
                    <span class="badge badge-info">{{ $product->category->name }}</span>
                    <span class="badge badge-success">{{ $product->brand->name }}</span>
                </div>

                <h1 class="font-bold" style="font-size: 2.25rem; line-height: 1.2; margin-bottom: 8px;">{{ $product->name }}</h1>
                
                <div style="display: flex; gap: 24px; margin-bottom: 24px; font-size: 0.875rem; color: var(--secondary);">
                    <span><strong>SKU:</strong> {{ $product->sku }}</span>
                    @if ($product->barcode)
                        <span><strong>Barcode:</strong> {{ $product->barcode }}</span>
                    @endif
                </div>

                <!-- Price and Stock Details -->
                <div style="background-color: var(--light); padding: 24px; border-radius: var(--radius); margin-bottom: 24px; border: 1px solid var(--border);">
                    <div class="text-secondary text-xs font-semibold" style="text-transform: uppercase;">Harga Jual Terbaik</div>
                    <div style="font-size: 2.25rem; font-weight: 800; color: var(--primary); margin: 4px 0;">
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
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div>
                        <h4 class="font-bold mb-4" style="border-bottom: 2px solid var(--border); padding-bottom: 8px;">Deskripsi Produk</h4>
                        <p style="color: #475569; font-size: 0.95rem; white-space: pre-line;">
                            {{ $product->description ?: 'Tidak ada deskripsi untuk produk ini.' }}
                        </p>
                    </div>

                    @if ($product->specs)
                        <div style="margin-top: 16px;">
                            <h4 class="font-bold mb-4" style="border-bottom: 2px solid var(--border); padding-bottom: 8px;">Spesifikasi Detail</h4>
                            <div style="background-color: var(--light); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border); font-size: 0.9rem; color: #334155; font-family: monospace; white-space: pre-wrap;">
                                {{ $product->specs }}
                            </div>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</x-catalog-layout>
