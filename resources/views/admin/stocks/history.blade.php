<x-admin-layout>
    @section('header_title', 'Riwayat Penyesuaian Stok')

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules/stocks.module.css') }}">
    @endpush

    <div>
        <!-- Header & Action Buttons -->
        <div class="stocks-header-wrap">
            <div class="stocks-header-title">
                <h2>Riwayat Penyesuaian Stok</h2>
                <p>Audit log pencatatan mutasi stok barang (masuk, keluar, dan penyesuaian opname).</p>
            </div>
            <div class="stocks-header-actions">
                <a href="{{ route('admin.stocks.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Kelola Stok
                </a>
            </div>
        </div>

        <!-- Filter & Search Card -->
        <div class="stocks-filter-card">
            <form method="GET" action="{{ route('admin.stocks.history') }}" class="stocks-filter-form">
                <div class="stocks-search-wrap">
                    <i class="fa-solid fa-magnifying-glass stocks-search-icon"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk, SKU, atau keterangan..." class="stocks-search-input">
                </div>

                <div class="stocks-select-wrap">
                    <select name="type" class="stocks-filter-select" onchange="this.form.submit()">
                        <option value="">Semua Tipe Mutasi</option>
                        <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Stok Masuk (+)</option>
                        <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Stok Keluar (-)</option>
                        <option value="return" {{ request('type') === 'return' ? 'selected' : '' }}>Retur Pembelian</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 8px 16px;">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari
                </button>

                @if(request('search') || request('type'))
                    <a href="{{ route('admin.stocks.history') }}" class="btn btn-secondary" style="padding: 8px 14px;">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- History Table Card -->
        <div class="stocks-table-card">
            <div class="table-responsive">
                <table class="stocks-table">
                    <thead>
                        <tr>
                            <th style="width: 5%; text-align: center;">No</th>
                            <th style="width: 15%;">Tanggal</th>
                            <th style="width: 32%;">Produk &amp; SKU</th>
                            <th style="width: 10%; text-align: center;">Tipe</th>
                            <th style="width: 12%; text-align: center;">Perubahan Qty</th>
                            <th style="width: 12%;">Admin / Petugas</th>
                            <th style="width: 14%;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($histories as $history)
                            <tr>
                                <td style="text-align: center; color: #64748b; font-size: 0.8rem;">
                                    {{ $loop->iteration + ($histories->currentPage() - 1) * $histories->perPage() }}
                                </td>
                                <td style="white-space: nowrap; color: #475569; font-size: 0.8rem;">
                                    {{ $history->date ? $history->date->format('d/m/Y H:i') : '-' }} WIB
                                </td>
                                <td>
                                    <div class="stocks-product-name">{{ optional($history->product)->name ?? 'Produk Dihapus / Tidak Tersedia' }}</div>
                                    <div class="stocks-product-sku">
                                        <span>SKU: {{ optional($history->product)->sku ?? '-' }}</span>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    @if ($history->type === 'in')
                                        <span class="badge-stock history-type-in">
                                            <i class="fa-solid fa-arrow-down"></i> Masuk
                                        </span>
                                    @elseif ($history->type === 'out')
                                        <span class="badge-stock history-type-out">
                                            <i class="fa-solid fa-arrow-up"></i> Keluar
                                        </span>
                                    @elseif ($history->type === 'return')
                                        <span class="badge-stock history-type-return">
                                            <i class="fa-solid fa-rotate-left"></i> Retur
                                        </span>
                                    @else
                                        <span class="badge-stock" style="background: #f1f5f9; color: #475569;">
                                            {{ ucfirst($history->type) }}
                                        </span>
                                    @endif
                                </td>
                                <td style="text-align: center; font-weight: 800; font-size: 0.9rem; color: {{ in_array($history->type, ['in', 'return']) ? '#15803d' : '#b91c1c' }};">
                                    {{ in_array($history->type, ['in', 'return']) ? '+' : '-' }}{{ $history->quantity }} pcs
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: #0f172a; font-size: 0.82rem;">
                                        {{ optional($history->user)->name ?? 'Sistem' }}
                                    </span>
                                </td>
                                <td style="font-size: 0.8rem; color: #64748b;" title="{{ $history->description }}">
                                    {{ Str::limit($history->description, 50) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 48px 20px; color: #64748b;">
                                    <i class="fa-solid fa-clock-rotate-left" style="font-size: 2.5rem; opacity: 0.35; margin-bottom: 12px; display: block;"></i>
                                    <div style="font-weight: 700; color: #0f172a; margin-bottom: 4px;">Belum Ada Riwayat Penyesuaian</div>
                                    <div style="font-size: 0.8rem;">Belum ada catatan mutasi stok yang tersimpan atau cocok dengan filter.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($histories, 'links'))
                <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0;">
                    {{ $histories->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
