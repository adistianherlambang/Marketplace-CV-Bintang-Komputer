<x-admin-layout>
    @section('header_title', 'POS Kasir - Penjualan Baru')

    <div class="pos-wrapper" x-data="posApp()">
        
        <!-- Left: Product List & Manual Items -->
        <div class="pos-products">
            <div class="pos-search-wrapper">
                <!-- Product Search -->
                <div class="pos-search-inner">
                    <i class="fa-solid fa-magnifying-glass pos-search-icon"></i>
                    <input type="text" x-model="searchQuery" placeholder="Cari nama barang atau SKU..." class="form-control pos-search-input">
                </div>
                
                <!-- Manual Add button -->
                <button type="button" @click="openManualModal = true" class="btn btn-secondary btn-sm pos-add-manual-btn">
                    <i class="fa-solid fa-square-plus text-primary"></i> Barang Manual (Metode 2)
                </button>
            </div>

            <!-- Product Grid -->
            <div class="pos-product-list">
                <template x-for="prod in filteredProducts" :key="prod.id">
                    <div class="pos-item-card" @click="addToCart(prod)">
                        <div>
                            <div class="product-meta">
                                <span class="badge badge-info" x-text="prod.brand_name"></span>
                            </div>
                            <div class="font-bold pos-item-name" x-text="prod.name"></div>
                            <div class="text-xs text-secondary mt-1">SKU: <span x-text="prod.sku"></span></div>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="font-bold text-primary text-sm" x-text="formatRupiah(prod.price_jual)"></span>
                            <span class="badge badge-success text-xs" x-text="'Stok: ' + prod.stock"></span>
                        </div>
                    </div>
                </template>
                <div x-show="filteredProducts.length === 0" class="pos-empty-product">
                    <i class="fa-solid fa-ban pos-empty-icon"></i>
                    <p>Produk tidak ditemukan atau habis.</p>
                </div>
            </div>
        </div>

        <!-- Right: Active Cart panel -->
        <div class="pos-cart">
            <div class="pos-cart-header">
                <span>Keranjang Belanja</span>
                <span class="badge badge-info" x-text="cart.length + ' item'"></span>
            </div>

            <!-- Main POST form for Checkout -->
            <form method="POST" action="{{ route('admin.transactions.store') }}" class="pos-form-wrapper">
                @csrf
                
                <!-- Cart items list -->
                <div class="pos-cart-list">
                    <template x-for="(item, index) in cart" :key="index">
                        <div class="pos-cart-item">
                            <!-- Hidden inputs for item serialization -->
                            <input type="hidden" :name="'items['+index+'][product_id]'" :value="item.product_id">
                            <input type="hidden" :name="'items['+index+'][item_name]'" :value="item.item_name">
                            <input type="hidden" :name="'items['+index+'][price]'" :value="item.price">
                            <input type="hidden" :name="'items['+index+'][quantity]'" :value="item.quantity">

                            <div class="pos-item-info">
                                <div class="font-semibold text-sm" x-text="item.item_name"></div>
                                <div class="text-xs text-secondary">
                                    <span x-text="formatRupiah(item.price)"></span>
                                    <span x-show="item.product_id" class="badge badge-info text-xs ml-1 pos-item-badge-sm">DB Product</span>
                                    <span x-show="!item.product_id" class="badge badge-warning text-xs ml-1 pos-item-badge-sm">Manual</span>
                                </div>
                            </div>
                            
                            <!-- Quantity adjust buttons -->
                            <div class="flex items-center gap-2 pos-qty-wrapper">
                                <button type="button" @click="decreaseQty(index)" class="btn btn-secondary btn-sm pos-qty-btn">-</button>
                                <span class="font-bold text-sm pos-qty-val" x-text="item.quantity"></span>
                                <button type="button" @click="increaseQty(index)" class="btn btn-secondary btn-sm pos-qty-btn">+</button>
                            </div>

                            <div class="pos-subtotal-wrap">
                                <div class="font-bold text-sm text-primary" x-text="formatRupiah(item.price * item.quantity)"></div>
                                <button type="button" @click="removeFromCart(index)" class="text-xs font-semibold text-danger pos-remove-btn">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                    <div x-show="cart.length === 0" class="pos-empty-cart">
                        <i class="fa-solid fa-cart-shopping pos-empty-cart-icon"></i>
                        <p>Keranjang kosong. Pilih produk di sebelah kiri atau tambah barang manual.</p>
                    </div>
                </div>

                <!-- POS checkout form controls -->
                <div class="pos-cart-footer">
                    <!-- Customer selection -->
                    <div class="form-group">
                        <label class="form-label">Pelanggan</label>
                        <select name="customer_id" class="form-control">
                            <option value="">-- Guest / Walk-in Customer --</option>
                            @foreach ($customers as $cust)
                                <option value="{{ $cust->id }}">{{ $cust->name }} ({{ $cust->phone ?: 'No phone' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Payment Status and Method -->
                    <div class="grid pos-payment-row">
                        <div>
                            <label class="form-label">Status Pembayaran</label>
                            <select name="status" class="form-control" required>
                                <option value="Lunas">Lunas</option>
                                <option value="Belum Dibayar">Belum Dibayar</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Metode Bayar</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="Cash">Cash (Tunai)</option>
                                <option value="Transfer">Transfer Bank</option>
                                <option value="QRIS">QRIS Manual</option>
                            </select>
                        </div>
                    </div>

                    <!-- Transaction notes -->
                    <div class="form-group pos-notes-group">
                        <label class="form-label">Catatan Tambahan (Opsional)</label>
                        <input type="text" name="notes" class="form-control" placeholder="Contoh: Kirim via Gojek, Garansi 1 tahun">
                    </div>

                    <!-- Total Amount Display -->
                    <div class="pos-total-row">
                        <span class="font-semibold text-secondary">Total Bayar:</span>
                        <span class="font-bold pos-total-val" x-text="formatRupiah(totalCartAmount)"></span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary invoice-btn-full-pad" :disabled="cart.length === 0">
                        <i class="fa-solid fa-circle-check mr-2"></i> Proses Checkout Transaksi
                    </button>
                </div>
            </form>
        </div>

        <!-- Manual Add Item Modal (Metode 2) -->
        <div class="modal-backdrop" :class="{ 'show': openManualModal }">
            <div class="modal">
                <div class="modal-header">
                    <h3 class="modal-title">Tambah Barang Manual (Metode 2)</h3>
                    <button @click="openManualModal = false" class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" x-model="manualItem.item_name" class="form-control" placeholder="Contoh: Kabel HDMI 5 Meter Custom">
                    </div>
                    <div class="form-group pos-manual-grid">
                        <div>
                            <label class="form-label">Harga Barang (Rp)</label>
                            <input type="number" x-model="manualItem.price" class="form-control" placeholder="75000">
                        </div>
                        <div>
                            <label class="form-label">Jumlah / Qty</label>
                            <input type="number" x-model="manualItem.quantity" class="form-control" min="1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" @click="openManualModal = false" class="btn btn-secondary">Batal</button>
                    <button type="button" @click="addManualToCart()" class="btn btn-primary">Masukkan Keranjang</button>
                </div>
            </div>
        </div>

    </div>

    <!-- Alpine POS Script -->
    <script id="products-data" type="application/json">
        {!! json_encode($products->map(function($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'price_jual' => (float)$p->price_jual,
                'stock' => $p->stock,
                'brand_name' => $p->brand?->name ?? 'No Brand',
            ];
        })->toArray()) !!}
    </script>

    <script>
        function posApp() {
            return {
                searchQuery: '',
                openManualModal: false,
                products: JSON.parse(document.getElementById('products-data').textContent),
                cart: [],
                manualItem: {
                    item_name: '',
                    price: '',
                    quantity: 1
                },

                get filteredProducts() {
                    if (!this.searchQuery) {
                        return this.products;
                    }
                    const query = this.searchQuery.toLowerCase();
                    return this.products.filter(p => 
                        p.name.toLowerCase().includes(query) || 
                        p.sku.toLowerCase().includes(query)
                    );
                },

                get totalCartAmount() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                },

                addToCart(product) {
                    if (product.stock <= 0) {
                        alert("Stok produk habis!");
                        return;
                    }

                    const index = this.cart.findIndex(item => item.product_id === product.id);
                    if (index > -1) {
                        if (this.cart[index].quantity >= product.stock) {
                            alert("Jumlah melebihi stok yang tersedia!");
                            return;
                        }
                        this.cart[index].quantity++;
                    } else {
                        this.cart.push({
                            product_id: product.id,
                            item_name: product.name,
                            price: product.price_jual,
                            quantity: 1
                        });
                    }
                },

                addManualToCart() {
                    if (!this.manualItem.item_name || !this.manualItem.price || this.manualItem.quantity < 1) {
                        alert("Semua field barang manual wajib diisi!");
                        return;
                    }

                    this.cart.push({
                        product_id: null,
                        item_name: this.manualItem.item_name,
                        price: parseFloat(this.manualItem.price),
                        quantity: parseInt(this.manualItem.quantity)
                    });

                    this.manualItem = { item_name: '', price: '', quantity: 1 };
                    this.openManualModal = false;
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                increaseQty(index) {
                    const item = this.cart[index];
                    if (item.product_id) {
                        const originalProduct = this.products.find(p => p.id === item.product_id);
                        if (originalProduct && item.quantity >= originalProduct.stock) {
                            alert("Jumlah melebihi stok yang tersedia!");
                            return;
                        }
                    }
                    item.quantity++;
                },

                decreaseQty(index) {
                    if (this.cart[index].quantity > 1) {
                        this.cart[index].quantity--;
                    } else {
                        this.removeFromCart(index);
                    }
                },

                formatRupiah(amount) {
                    return 'Rp ' + Number(amount).toLocaleString('id-ID');
                }
            };
        }
    </script>
</x-admin-layout>
