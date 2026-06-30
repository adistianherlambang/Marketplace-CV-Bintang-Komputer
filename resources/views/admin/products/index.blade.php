<x-admin-layout>
    @section('header_title', 'Kelola Produk & Barang')

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules/products.module.css') }}">
    @endpush

    <div x-data="{ openCreateModal: false, openEditModal: false, currentProduct: {
        id: '', name: '', sku: '', barcode: '', category_id: '', brand_id: '', supplier_id: '',
        price_modal: '', price_jual: '', stock: '', min_stock: '', description: '', specs: '', is_active: '1'
    } }">
        
        <!-- Action Header & Filters -->
        <div class="flex justify-between items-center mb-6">
            <div class="title">
                <h3 class="font-bold page-title">Daftar Produk / Barang</h3>
                <p class="text-secondary text-sm">Kelola spesifikasi, harga jual, harga modal, dan detail item.</p>
            </div>
            
            <button @click="openCreateModal = true" class="btn btn-primary button">
                <i class="fa-solid fa-plus"></i> Tambah Produk Baru
            </button>
        </div>

        <!-- Filter Bar -->
        <form method="GET" action="{{ route('admin.products.index') }}" class="filter-bar">
            <div class="filter-inputs">
                <div class="filter-search-wrapper-full">
                    <i class="fa-solid fa-magnifying-glass filter-search-icon"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau SKU" class="form-control filter-input-indent">
                </div>
                
                <select name="category" class="form-control filter-select-sm tom-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Cari</button>
                @if (request()->anyFilled(['search', 'category']))
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Clear</a>
                @endif
            </div>
        </form>

        <!-- Table Grid -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>SKU</th>
                            <th>Kategori / Merk</th>
                            <th class="th-right">Harga Modal</th>
                            <th class="th-right">Harga Jual</th>
                            <th class="th-center">Stok</th>
                            <th class="th-center">Status</th>
                            <th class="th-actions">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3 product">
                                        <div class="product-thumb">
                                            @if ($product->primaryImage)
                                                <img src="{{ asset('storage/' . $product->primaryImage->path) }}" alt="{{ $product->name }}">
                                            @else
                                                <i class="fa-solid fa-laptop-code text-secondary product-thumb-icon"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold">{{ $product->name }}</div>
                                            <div class="text-xs text-secondary">Supplier: {{ $product->supplier->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><code>{{ $product->sku }}</code></td>
                                <td>
                                    <div>{{ $product->category->name }}</div>
                                    <div class="text-xs text-secondary">{{ $product->brand->name }}</div>
                                </td>
                                <td class="td-right text-secondary">
                                    Rp {{ number_format($product->price_modal, 0, ',', '.') }}
                                </td>
                                <td class="td-right font-semibold text-primary">
                                    Rp {{ number_format($product->price_jual, 0, ',', '.') }}
                                </td>
                                <td class="td-center">
                                    <span class="font-semibold {{ $product->stock <= $product->min_stock ? 'text-danger' : 'text-success' }}">
                                        {{ $product->stock }} pcs
                                    </span>
                                    <div class="text-xs text-secondary">Min: {{ $product->min_stock }}</div>
                                </td>
                                <td class="td-center">
                                    @if ($product->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="td-center">
                                    <div class="flex justify-center gap-2">
                                        <button @click="currentProduct = {
                                            id: '{{ $product->id }}',
                                            name: '{{ addslashes($product->name) }}',
                                            sku: '{{ $product->sku }}',
                                            barcode: '{{ $product->barcode }}',
                                            category_id: '{{ $product->category_id }}',
                                            brand_id: '{{ $product->brand_id }}',
                                            supplier_id: '{{ $product->supplier_id }}',
                                            price_modal: '{{ (float)$product->price_modal }}',
                                            price_jual: '{{ (float)$product->price_jual }}',
                                            stock: '{{ $product->stock }}',
                                            min_stock: '{{ $product->min_stock }}',
                                            description: '{{ addslashes($product->description) }}',
                                            specs: '{{ addslashes($product->specs) }}',
                                            is_active: '{{ $product->is_active ? 1 : 0 }}'
                                        }; openEditModal = true" class="btn btn-secondary btn-sm">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </button>
                                        
                                        <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini (Soft Delete)?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="td-empty">
                                    <i class="fa-solid fa-box-open icon-empty-lg"></i>
                                    <p>Belum ada data produk. Klik 'Tambah Produk Baru' untuk membuat baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $products->links() }}
        </div>

        <!-- Create Modal -->
        <div class="modal-backdrop" :class="{ 'show': openCreateModal }">
            <div class="modal product-modal-lg">
                <div class="modal-header">
                    <h3 class="modal-title">Tambah Produk Baru</h3>
                    <button @click="openCreateModal = false" class="modal-close">&times;</button>
                </div>
                <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body product-modal-grid">
                        
                        <div>
                            <div class="form-group">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" name="name" class="form-control" required placeholder="Contoh: Asus ROG Strix G15">
                            </div>
                            <div class="form-group">
                                <label class="form-label">SKU</label>
                                <input type="text" name="sku" class="form-control" required placeholder="Contoh: LAP-ASUS-ROG15">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Barcode (Opsional)</label>
                                <input type="text" name="barcode" class="form-control" placeholder="Barcode produk">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kategori</label>
                                <select name="category_id" class="form-control tom-select" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Merk / Brand</label>
                                <select name="brand_id" class="form-control tom-select" required>
                                    <option value="">Pilih Merk</option>
                                    @foreach ($brands as $br)
                                        <option value="{{ $br->id }}">{{ $br->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Supplier Pemasok</label>
                                <select name="supplier_id" class="form-control tom-select" required>
                                    <option value="">Pilih Supplier</option>
                                    @foreach ($suppliers as $sup)
                                        <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <div class="form-group product-price-grid">
                                <div>
                                    <label class="form-label">Harga Modal (Rp)</label>
                                    <input type="number" name="price_modal" class="form-control" required min="0" placeholder="1000000">
                                </div>
                                <div>
                                    <label class="form-label">Harga Jual (Rp)</label>
                                    <input type="number" name="price_jual" class="form-control" required min="0" placeholder="1250000">
                                </div>
                            </div>
                            <div class="form-group product-price-grid">
                                <div>
                                    <label class="form-label">Jumlah Stok Awal</label>
                                    <input type="number" name="stock" class="form-control" required min="0" placeholder="10">
                                </div>
                                <div>
                                    <label class="form-label">Minimum Stok</label>
                                    <input type="number" name="min_stock" class="form-control" required min="0" placeholder="3">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Foto Produk (Multi upload)</label>
                                <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Deskripsi Ringkas</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Penjelasan umum produk..."></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Spesifikasi Detail (Satu baris per spek)</label>
                                <textarea name="specs" class="form-control" rows="3" placeholder="RAM: 16GB&#10;Storage: 512GB SSD&#10;GPU: RTX 3050"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="openCreateModal = false" class="btn btn-secondary">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Produk</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal-backdrop" :class="{ 'show': openEditModal }">
            <div class="modal product-modal-lg">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Produk</h3>
                    <button @click="openEditModal = false" class="modal-close">&times;</button>
                </div>
                <form method="POST" :action="'/admin/products/' + currentProduct.id" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body product-modal-grid">
                        
                        <div>
                            <div class="form-group">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" name="name" x-model="currentProduct.name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">SKU</label>
                                <input type="text" name="sku" x-model="currentProduct.sku" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Barcode (Opsional)</label>
                                <input type="text" name="barcode" x-model="currentProduct.barcode" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kategori</label>
                                <select name="category_id" x-model="currentProduct.category_id" class="form-control tom-select" required>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Merk / Brand</label>
                                <select name="brand_id" x-model="currentProduct.brand_id" class="form-control tom-select" required>
                                    @foreach ($brands as $br)
                                        <option value="{{ $br->id }}">{{ $br->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Supplier Pemasok</label>
                                <select name="supplier_id" x-model="currentProduct.supplier_id" class="form-control tom-select" required>
                                    @foreach ($suppliers as $sup)
                                        <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <div class="form-group product-price-grid">
                                <div>
                                    <label class="form-label">Harga Modal (Rp)</label>
                                    <input type="number" name="price_modal" x-model="currentProduct.price_modal" class="form-control" required min="0">
                                </div>
                                <div>
                                    <label class="form-label">Harga Jual (Rp)</label>
                                    <input type="number" name="price_jual" x-model="currentProduct.price_jual" class="form-control" required min="0">
                                </div>
                            </div>
                             <div class="form-group product-price-grid">
                                 <div>
                                     <label class="form-label">Minimum Stok</label>
                                     <input type="number" name="min_stock" x-model="currentProduct.min_stock" class="form-control" required min="0">
                                 </div>
                                 <div>
                                     <label class="form-label">Jumlah Stok Saat Ini</label>
                                     <input type="number" name="stock" x-model="currentProduct.stock" class="form-control" required min="0">
                                 </div>
                             </div>
                             <div class="form-group product-price-grid">
                                 <div>
                                     <label class="form-label">Keterangan Perubahan Stok (Opsional)</label>
                                     <input type="text" name="stock_reason" class="form-control" placeholder="Contoh: Koreksi opname, dll">
                                 </div>
                                 <div>
                                     <label class="form-label">Status Produk</label>
                                     <select name="is_active" x-model="currentProduct.is_active" class="form-control tom-select" required>
                                         <option value="1">Aktif</option>
                                         <option value="0">Nonaktif</option>
                                     </select>
                                 </div>
                             </div>
                            <div class="form-group">
                                <label class="form-label">Tambah Foto Produk</label>
                                <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Deskripsi Ringkas</label>
                                <textarea name="description" x-model="currentProduct.description" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Spesifikasi Detail</label>
                                <textarea name="specs" x-model="currentProduct.specs" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="openEditModal = false" class="btn btn-secondary">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-admin-layout>
