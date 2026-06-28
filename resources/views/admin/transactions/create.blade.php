<x-admin-layout>
    @section('header_title', 'POS Kasir - Penjualan Baru')

    <div class="pos-wrapper" x-data="posApp()">
        
        <!-- Left: Product List & Manual Items -->
        <div class="pos-products">
            <div style="padding: 20px; border-bottom: 1px solid var(--border); display: flex; gap: 12px; align-items: center; justify-content: space-between;">
                <!-- Product Search -->
                <div style="position: relative; flex-grow: 1; max-width: 320px;">
                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 14px; top: 12px; color: var(--secondary);"></i>
                    <input type="text" x-model="searchQuery" placeholder="Cari nama barang atau SKU..." class="form-control" style="padding-left: 40px; padding-top: 8px; padding-bottom: 8px;">
                </div>
                
                <!-- Manual Add button -->
                <button type="button" @click="openManualModal = true" class="btn btn-secondary btn-sm" style="height: 38px;">
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
                            <div class="font-bold" style="font-size: 0.95rem; line-height: 1.3; height: 36px; overflow: hidden;" x-text="prod.name"></div>
                            <div class="text-xs text-secondary mt-1">SKU: <span x-text="prod.sku"></span></div>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="font-bold text-primary text-sm" x-text="formatRupiah(prod.price_jual)"></span>
                            <span class="badge badge-success text-xs" x-text="'Stok: ' + prod.stock"></span>
                        </div>
                    </div>
                </template>
                <div x-show="filteredProducts.length === 0" style="grid-column: 1 / -1; text-align: center; padding: 40px 0; color: var(--secondary);">
                    <i class="fa-solid fa-ban" style="font-size: 2rem; margin-bottom: 8px;"></i>
                    <p>Produk tidak ditemukan atau habis.</p>
                </div>
            </div>
        </div>

        <!-- Right: Active Cart panel -->
        <div class="pos-cart">
            <div style="padding: 20px; border-bottom: 1px solid var(--border); font-weight: 700; font-size: 1.1rem; display: flex; align-items: center; justify-content: space-between;">
                <span>Keranjang Belanja</span>
                <span class="badge badge-info" x-text="cart.length + ' item'"></span>
            </div>

            <!-- Main POST form for Checkout -->
            <form method="POST" action="{{ route('admin.transactions.store') }}" style="display: flex; flex-direction: column; flex-grow: 1; overflow: hidden;">
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

                            <div style="flex-grow: 1; padding-right: 12px;">
                                <div class="font-semibold text-sm" x-text="item.item_name"></div>
                                <div class="text-xs text-secondary">
                                    <span x-text="formatRupiah(item.price)"></span>
                                    <span x-show="item.product_id" class="badge badge-info text-xs ml-1" style="font-size: 0.65rem;">DB Product</span>
                                    <span x-show="!item.product_id" class="badge badge-warning text-xs ml-1" style="font-size: 0.65rem;">Manual</span>
                                </div>
                            </div>
                            
                            <!-- Quantity adjust buttons -->
                            <div class="flex items-center gap-2" style="margin-right: 16px;">
                                <button type="button" @click="decreaseQty(index)" class="btn btn-secondary btn-sm" style="padding: 2px 6px; font-size: 0.75rem;">-</button>
                                <span class="font-bold text-sm" style="width: 24px; text-align: center;" x-text="item.quantity"></span>
                                <button type="button" @click="increaseQty(index)" class="btn btn-secondary btn-sm" style="padding: 2px 6px; font-size: 0.75rem;">+</button>
                            </div>

                            <div style="text-align: right;">
                                <div class="font-bold text-sm text-primary" x-text="formatRupiah(item.price * item.quantity)"></div>
                                <button type="button" @click="removeFromCart(index)" class="text-xs font-semibold text-danger" style="border: none; background: none; cursor: pointer; margin-top: 4px;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                    <div x-show="cart.length === 0" style="text-align: center; padding: 60px 0; color: var(--secondary);">
                        <i class="fa-solid fa-cart-shopping" style="font-size: 3rem; margin-bottom: 12px; opacity: 0.4;"></i>
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
                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
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
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="form-label">Catatan Tambahan (Opsional)</label>
                        <input type="text" name="notes" class="form-control" placeholder="Contoh: Kirim via Gojek, Garansi 1 tahun">
                    </div>

                    <!-- Total Amount Display -->
                    <div style="border-top: 1px solid var(--border); padding-top: 16px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
                        <span class="font-semibold text-secondary">Total Bayar:</span>
                        <span class="font-bold" style="font-size: 1.8rem; color: var(--primary);" x-text="formatRupiah(totalCartAmount)"></span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px 20px; font-size: 1.05rem; font-weight: 700;" :disabled="cart.length === 0">
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
                    <div class="form-group" style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 12px;">
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
    <script>
        function posApp() {
            return {
                searchQuery: '',
                openManualModal: false,
                products: @json($products->map(function($p) {
                    return [
                        'id' => $p->id,
                        'name' => $p->name,
                        'sku' => $p->sku,
                        'price_jual' => (float) $p->price_jual,
                        'stock' => $p->stock,
                        'brand_name' => $p->brand->name,
                    ];
                })->toArray()),
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
                    // Check if stock is 0
                    if (product.stock <= 0) {
                        alert("Stok produk habis!");
                        return;
                    }

                    // Check if already in cart
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
                        product_id: null, // marks as manual entry
                        item_name: this.manualItem.item_name,
                        price: parseFloat(this.manualItem.price),
                        quantity: parseInt(this.manualItem.quantity)
                    });

                    // Reset manual item inputs
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
