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

                <div x-data="{ open: false }" style="margin-bottom: 24px;">
                    <button type="button" @click="open = true" class="btn btn-primary btn-booking" style="width: 100%; max-width: 320px; font-weight: 700; padding: 14px 28px; font-size: 1.05rem; border-radius: var(--radius); display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: var(--shadow-md); border: none; cursor: pointer;">
                        <i class="fa-solid fa-cart-shopping"></i> Pesan Sekarang
                    </button>

                    <!-- Popup Modal Backdrop -->
                    <div class="modal-backdrop" :class="{ 'show': open }" @click="open = false" style="z-index: 99999; backdrop-filter: blur(6px); background: rgba(15, 23, 42, 0.6);">
                        
                        <!-- Modal Container -->
                        <div class="modal" @click.stop style="max-width: 480px; padding: 32px; position: relative; max-height: 90vh; overflow-y: auto;">
                            
                            <!-- Close Button -->
                            <button type="button" @click="open = false" style="position: absolute; top: 20px; right: 20px; background: none; border: none; font-size: 1.25rem; cursor: pointer; color: var(--secondary); display: flex; align-items: center; justify-content: center; transition: var(--transition);">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                            
                            <!-- Modal Header -->
                            <div style="margin-bottom: 24px;">
                                <h3 class="font-bold" style="font-size: 1.35rem; color: var(--dark); margin-bottom: 4px;">Pesan Produk</h3>
                                <p style="font-size: 0.875rem; color: var(--secondary); line-height: 1.4;">Silakan isi formulir di bawah ini untuk memesan <strong>{{ $product->name }}</strong>.</p>
                            </div>
                            
                            <!-- Form -->
                            <form method="POST" action="{{ route('catalog.book', $product->id) }}">
                                @csrf
                                
                                <div style="display: flex; flex-direction: column; gap: 16px;">
                                    <!-- Full Name -->
                                    <div class="form-group" style="text-align: left; display: flex; flex-direction: column; gap: 6px;">
                                        <label class="form-label font-semibold" style="font-size: 0.85rem; color: var(--dark);">Nama Lengkap</label>
                                        <input type="text" name="customer_name" class="form-control" style="width: 100%; padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.925rem; outline: none; transition: var(--transition);" required placeholder="Masukkan nama lengkap Anda">
                                    </div>
                                    
                                    <!-- Phone Number -->
                                    <div class="form-group" style="text-align: left; display: flex; flex-direction: column; gap: 6px;">
                                        <label class="form-label font-semibold" style="font-size: 0.85rem; color: var(--dark);">No. Telepon / WhatsApp</label>
                                        <input type="text" name="customer_phone" class="form-control" style="width: 100%; padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.925rem; outline: none; transition: var(--transition);" required placeholder="Contoh: 0812XXXXXXXX">
                                    </div>
                                    
                                    <!-- Date & Time of Pickup -->
                                    <div class="form-group" style="text-align: left; display: flex; flex-direction: column; gap: 6px;">
                                        <label class="form-label font-semibold" style="font-size: 0.85rem; color: var(--dark);">Tanggal & Jam Pengambilan</label>
                                        <input type="datetime-local" name="pickup_time" class="form-control" style="width: 100%; padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.925rem; outline: none; transition: var(--transition);" required min="{{ date('Y-m-d\TH:i') }}">
                                    </div>
                                    
                                    <!-- Additional Notes -->
                                    <div class="form-group" style="text-align: left; display: flex; flex-direction: column; gap: 6px;">
                                        <label class="form-label font-semibold" style="font-size: 0.85rem; color: var(--dark);">Catatan Tambahan (Opsional)</label>
                                        <textarea name="notes" class="form-control" rows="3" style="width: 100%; padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.925rem; outline: none; transition: var(--transition); resize: vertical;" placeholder="Contoh: warna produk, kelengkapan tambahan..."></textarea>
                                    </div>
                                </div>
                                
                                <!-- Form Actions -->
                                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 28px; border-top: 1px solid var(--border); padding-top: 20px;">
                                    <button type="button" @click="open = false" class="btn btn-secondary" style="padding: 10px 20px; font-weight: 600; font-size: 0.9rem; border-radius: var(--radius-sm); cursor: pointer;">Batal</button>
                                    <button type="submit" class="btn btn-primary" style="padding: 10px 20px; font-weight: 600; font-size: 0.9rem; border-radius: var(--radius-sm); cursor: pointer; border: none;">Kirim Pesanan</button>
                                </div>
                            </form>
                        </div>
                    </div>
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
