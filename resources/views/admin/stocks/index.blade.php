<x-admin-layout>
    @section('header_title', 'Kelola & Riwayat Stok Barang')

    <div class="grid stocks-layout">
        
        <!-- Left: Quick Stock Adjustment Form -->
        <div class="chart-card">
            <h3 class="font-bold mb-4 stocks-label"><i class="fa-solid fa-plus-minus text-primary mr-2"></i>Penyesuaian Stok Manual</h3>
            
            <form method="POST" action="{{ route('admin.stocks.adjust') }}">
                @csrf
                
                <!-- Product select -->
                <div class="form-group">
                    <label class="form-label">Pilih Produk</label>
                    <select name="product_id" class="form-control" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }} (Sisa Stok: {{ $product->stock }} | SKU: {{ $product->sku }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Adjustment Type -->
                <div class="form-group">
                    <label class="form-label">Tipe Penyesuaian</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 label-clickable">
                            <input type="radio" name="type" value="in" checked class="radio-input">
                            <span class="font-semibold text-success"><i class="fa-solid fa-square-plus"></i> Tambah Stok (+)</span>
                        </label>
                        <label class="flex items-center gap-2 label-clickable">
                            <input type="radio" name="type" value="out" class="radio-input-danger">
                            <span class="font-semibold text-danger"><i class="fa-solid fa-square-minus"></i> Kurangi Stok (-)</span>
                        </label>
                    </div>
                </div>

                <!-- Quantity -->
                <div class="form-group">
                    <label class="form-label">Jumlah / Quantity (pcs)</label>
                    <input type="number" name="quantity" class="form-control" required min="1" placeholder="Masukkan jumlah barang">
                </div>

                <!-- Description / Reason -->
                <div class="form-group">
                    <label class="form-label">Keterangan / Alasan</label>
                    <textarea name="description" class="form-control" rows="3" required placeholder="Contoh: Stok masuk dari supplier, Retur pembelian, Penyesuaian stock opname, dll"></textarea>
                </div>

                <button type="submit" class="btn btn-primary stocks-form-btn">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Penyesuaian
                </button>
            </form>
        </div>

        <!-- Right: Stock History Logs Table -->
        <div class="chart-card stocks-history-card">
            <h3 class="font-bold mb-4 stocks-history-title"><i class="fa-solid fa-clock-rotate-left text-secondary mr-2"></i>Audit Log Riwayat Stok</h3>
            
            <div class="table-responsive">
                <table class="table-no-bg-full table-no-bg">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th class="th-none-center">Tipe</th>
                            <th class="th-none-center">Jumlah</th>
                            <th>Admin</th>
                            <th>Keterangan</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($histories as $history)
                            <tr>
                                <td>
                                    <strong>{{ $history->product->name }}</strong>
                                    <div class="text-xs text-secondary">SKU: {{ $history->product->sku }}</div>
                                </td>
                                <td class="td-center">
                                    @if ($history->type === 'in')
                                        <span class="badge badge-success">Masuk</span>
                                    @elseif ($history->type === 'out')
                                        <span class="badge badge-danger">Keluar</span>
                                    @elseif ($history->type === 'return')
                                        <span class="badge badge-info">Retur</span>
                                    @else
                                        <span class="badge badge-warning">{{ $history->type }}</span>
                                    @endif
                                </td>
                                <td class="td-center font-bold {{ in_array($history->type, ['in', 'return']) ? 'text-success' : 'text-danger' }}">
                                    {{ in_array($history->type, ['in', 'return']) ? '+' : '-' }}{{ $history->quantity }} pcs
                                </td>
                                <td>{{ $history->user->name }}</td>
                                <td class="td-meta-col" title="{{ $history->description }}">
                                    {{ Str::limit($history->description, 60) }}
                                </td>
                                <td class="td-date">
                                    {{ $history->date->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="td-empty">
                                    <i class="fa-solid fa-history icon-empty-lg"></i>
                                    <p>Belum ada catatan riwayat stok barang.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-4 stocks-pagination">
                {{ $histories->links() }}
            </div>
        </div>

    </div>
</x-admin-layout>
