<x-admin-layout>
    @section('header_title', 'Kelola & Riwayat Stok Barang')

    <div class="grid" style="grid-template-columns: 1fr 2fr; gap: 24px; align-items: start;">
        
        <!-- Left: Quick Stock Adjustment Form -->
        <div class="chart-card">
            <h3 class="font-bold mb-4" style="font-size: 1.10rem;"><i class="fa-solid fa-plus-minus text-primary mr-2"></i>Penyesuaian Stok Manual</h3>
            
            <form method="POST" action="{{ route('admin.stocks.adjust') }}">
                @csrf
                
                <!-- Product select -->
                <div class="form-group">
                    <label class="form-label">Pilih Produk</label>
                    <select name="product_id" class="form-control" required style="width: 100%;">
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
                        <label class="flex items-center gap-2" style="cursor: pointer;">
                            <input type="radio" name="type" value="in" checked style="width: 18px; height: 18px; accent-color: var(--success);">
                            <span class="font-semibold text-success"><i class="fa-solid fa-square-plus"></i> Tambah Stok (+)</span>
                        </label>
                        <label class="flex items-center gap-2" style="cursor: pointer;">
                            <input type="radio" name="type" value="out" style="width: 18px; height: 18px; accent-color: var(--danger);">
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

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Penyesuaian
                </button>
            </form>
        </div>

        <!-- Right: Stock History Logs Table -->
        <div class="chart-card" style="padding: 24px 0;">
            <h3 class="font-bold mb-4" style="font-size: 1.10rem; padding: 0 24px;"><i class="fa-solid fa-clock-rotate-left text-secondary mr-2"></i>Audit Log Riwayat Stok</h3>
            
            <div class="table-responsive">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="background: none;">Barang</th>
                            <th style="background: none; text-align: center;">Tipe</th>
                            <th style="background: none; text-align: center;">Jumlah</th>
                            <th style="background: none;">Admin</th>
                            <th style="background: none;">Keterangan</th>
                            <th style="background: none;">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($histories as $history)
                            <tr>
                                <td>
                                    <strong>{{ $history->product->name }}</strong>
                                    <div class="text-xs text-secondary">SKU: {{ $history->product->sku }}</div>
                                </td>
                                <td style="text-align: center;">
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
                                <td style="text-align: center; font-weight: 700; color: {{ in_array($history->type, ['in', 'return']) ? 'var(--success)' : 'var(--danger)' }};">
                                    {{ in_array($history->type, ['in', 'return']) ? '+' : '-' }}{{ $history->quantity }} pcs
                                </td>
                                <td>{{ $history->user->name }}</td>
                                <td style="max-width: 200px; font-size: 0.825rem;" title="{{ $history->description }}">
                                    {{ Str::limit($history->description, 60) }}
                                </td>
                                <td style="font-size: 0.8rem; color: var(--secondary);">
                                    {{ $history->date->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: var(--secondary);">
                                    <i class="fa-solid fa-history" style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.5;"></i>
                                    <p>Belum ada catatan riwayat stok barang.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-4" style="padding: 0 24px;">
                {{ $histories->links() }}
            </div>
        </div>

    </div>
</x-admin-layout>
