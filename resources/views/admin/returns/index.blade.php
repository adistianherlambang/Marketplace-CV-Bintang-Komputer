<x-admin-layout>
    @section('header_title', 'Kelola Retur Barang & Pengembalian')

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules/returns.module.css') }}">
    @endpush

    <div class="grid grid-1-2" x-data="returnApp()">
        
        <!-- Left: Log Return Form -->
        <div class="chart-card">
            <h3 class="font-bold mb-4 chart-title"><i class="fa-solid fa-rotate-left text-primary mr-2"></i>Catat Retur Baru</h3>
            
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
                <div class="form-group returns-checkbox-row">
                    <input type="checkbox" name="approve_immediately" id="approve_immediately" class="returns-checkbox">
                    <label for="approve_immediately" class="text-xs text-secondary font-semibold returns-check-label">Setujui langsung &amp; kembalikan stok</label>
                </div>

                <button type="submit" class="btn btn-primary returns-form-btn" :disabled="!selectedOrderId || !selectedProductId">
                    <i class="fa-solid fa-floppy-disk"></i> Catat Retur
                </button>
            </form>
        </div>

        <!-- Right: Returns Log List Table -->
        <div class="chart-card returns-history-card">
            <h3 class="font-bold mb-4 returns-history-title"><i class="fa-solid fa-clock-rotate-left text-secondary mr-2"></i>Audit Log Retur Barang</h3>
            
            <div class="table-responsive">
                <table class="table-no-bg-full table-no-bg">
                    <thead>
                        <tr>
                            <th>Invoice / Barang</th>
                            <th class="th-none-center">Jumlah</th>
                            <th>Alasan</th>
                            <th class="th-none-center">Status</th>
                            <th class="th-none-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returns as $ret)
                            <tr>
                                <td>
                                    <strong>{{ $ret->order->invoice_number }}</strong>
                                    <div class="text-xs text-secondary">Barang: {{ $ret->product->name }}</div>
                                </td>
                                <td class="td-center font-semibold">{{ $ret->quantity }} pcs</td>
                                <td class="td-note-col" title="{{ $ret->reason }}">
                                    {{ $ret->reason }}
                                </td>
                                <td class="td-center">
                                    @if ($ret->status === 'Disetujui')
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif ($ret->status === 'Ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-warning">Menunggu</span>
                                    @endif
                                </td>
                                <td class="td-center">
                                    @if ($ret->status === 'Menunggu')
                                        <div class="flex justify-center gap-1">
                                            <form method="POST" action="{{ route('admin.returns.approve', $ret->id) }}" onsubmit="return confirm('Apakah Anda yakin menyetujui retur ini? Stok barang akan otomatis bertambah.')">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm returns-btn-sm-action">
                                                    Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.returns.reject', $ret->id) }}" onsubmit="return confirm('Apakah Anda yakin menolak retur ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm returns-btn-sm-action">
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
                                <td colspan="5" class="td-empty">
                                    <i class="fa-solid fa-rotate-left icon-empty-lg"></i>
                                    <p>Belum ada log retur barang.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-4 returns-pagination">
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
