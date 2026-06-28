<x-admin-layout>
    @section('header_title', 'Kelola Retur Barang & Pengembalian')

    <div class="grid" style="grid-template-columns: 1fr 2fr; gap: 24px; align-items: start;" x-data="returnApp()">
        
        <!-- Left: Log Return Form -->
        <div class="chart-card">
            <h3 class="font-bold mb-4" style="font-size: 1.1rem;"><i class="fa-solid fa-rotate-left text-primary mr-2"></i>Catat Retur Baru</h3>
            
            <form method="POST" action="{{ route('admin.returns.store') }}">
                @csrf
                
                <!-- Order selection -->
                <div class="form-group">
                    <label class="form-label">Pilih Order / Invoice</label>
                    <select name="order_id" class="form-control" required x-model="selectedOrderId" @change="onOrderChange()">
                        <option value="">-- Pilih Transaksi --</option>
                        @foreach ($orders as $order)
                            <option value="{{ $order->id }}">
                                {{ $order->invoice_number }} (Pelanggan: {{ $order->customer ? $order->customer->name : 'Guest' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Product selection (filtered dynamically) -->
                <div class="form-group">
                    <label class="form-label">Pilih Produk</label>
                    <select name="product_id" class="form-control" required x-model="selectedProductId" :disabled="!availableProducts.length">
                        <option value="">-- Pilih Barang --</option>
                        <template x-for="item in availableProducts" :key="item.product_id">
                            <option :value="item.product_id" x-text="item.item_name + ' (Beli: ' + item.quantity + ' pcs)'"></option>
                        </template>
                    </select>
                </div>

                <!-- Quantity to return -->
                <div class="form-group">
                    <label class="form-label">Jumlah Diretur (pcs)</label>
                    <input type="number" name="quantity" class="form-control" required min="1" placeholder="Masukkan jumlah barang">
                </div>

                <!-- Return Reason -->
                <div class="form-group">
                    <label class="form-label">Alasan Retur / Kerusakan</label>
                    <textarea name="reason" class="form-control" rows="3" required placeholder="Contoh: Layar monitor bergaris, Mati total, dll"></textarea>
                </div>

                <!-- Immediate approval toggle -->
                <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="approve_immediately" id="approve_immediately" style="width: 16px; height: 16px; accent-color: var(--primary);">
                    <label for="approve_immediately" class="text-xs text-secondary font-semibold" style="cursor: pointer;">Setujui langsung & kembalikan stok</label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;" :disabled="!selectedOrderId || !selectedProductId">
                    <i class="fa-solid fa-floppy-disk"></i> Catat Retur
                </button>
            </form>
        </div>

        <!-- Right: Returns Log List Table -->
        <div class="chart-card" style="padding: 24px 0;">
            <h3 class="font-bold mb-4" style="font-size: 1.1rem; padding: 0 24px;"><i class="fa-solid fa-clock-rotate-left text-secondary mr-2"></i>Audit Log Retur Barang</h3>
            
            <div class="table-responsive">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="background: none;">Invoice / Barang</th>
                            <th style="background: none; text-align: center;">Jumlah</th>
                            <th style="background: none;">Alasan</th>
                            <th style="background: none; text-align: center;">Status</th>
                            <th style="background: none; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returns as $ret)
                            <tr>
                                <td>
                                    <strong>{{ $ret->order->invoice_number }}</strong>
                                    <div class="text-xs text-secondary">Barang: {{ $ret->product->name }}</div>
                                </td>
                                <td style="text-align: center; font-weight: 600;">{{ $ret->quantity }} pcs</td>
                                <td style="font-size: 0.85rem; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $ret->reason }}">
                                    {{ $ret->reason }}
                                </td>
                                <td style="text-align: center;">
                                    @if ($ret->status === 'Disetujui')
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif ($ret->status === 'Ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-warning">Menunggu</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    @if ($ret->status === 'Menunggu')
                                        <div class="flex justify-center gap-1">
                                            <form method="POST" action="{{ route('admin.returns.approve', $ret->id) }}" onsubmit="return confirm('Apakah Anda yakin menyetujui retur ini? Stok barang akan otomatis bertambah.')">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" style="padding: 4px 8px; font-size: 0.75rem;">
                                                    Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.returns.reject', $ret->id) }}" onsubmit="return confirm('Apakah Anda yakin menolak retur ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" style="padding: 4px 8px; font-size: 0.75rem;">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-secondary">{{ $ret->date->format('d/m/y H:i') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px; color: var(--secondary);">
                                    <i class="fa-solid fa-rotate-left" style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.5;"></i>
                                    <p>Belum ada log retur barang.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-4" style="padding: 0 24px;">
                {{ $returns->links() }}
            </div>
        </div>

    </div>

    <!-- Alpine Order filtering helper script -->
    <script id="orders-data" type="application/json">
        {!! json_encode($orders->mapWithKeys(function($order) {
            return [$order->id => $order->items->filter(function($i) { return !is_null($i->product_id); })->map(function($i) {
                return [
                    'product_id' => $i->product_id,
                    'item_name' => $i->item_name,
                    'quantity' => $i->quantity
                ];
            })];
        })->toArray()) !!}
    </script>

    <script>
        function returnApp() {
            return {
                selectedOrderId: '',
                selectedProductId: '',
                availableProducts: [],
                ordersData: JSON.parse(document.getElementById('orders-data').textContent),

                onOrderChange() {
                    this.selectedProductId = '';
                    if (this.selectedOrderId && this.ordersData[this.selectedOrderId]) {
                        this.availableProducts = this.ordersData[this.selectedOrderId];
                    } else {
                        this.availableProducts = [];
                    }
                }
            };
        }
    </script>
</x-admin-layout>
