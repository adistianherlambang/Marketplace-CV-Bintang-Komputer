<x-admin-layout>
    @section('header_title', 'Riwayat Penyesuaian Stok')

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules/stocks.module.css') }}">
    @endpush

    <div>
        <!-- Header & Action Buttons -->
        <div class="stocks-header">
            <div>
                <h3 class="stocks-title">Riwayat Penyesuaian Stok</h3>
                <p class="stocks-subtitle">Audit log pencatatan mutasi stok barang masuk, keluar, dan retur pembelian.</p>
            </div>
            <div class="stocks-header-actions">
                <a href="{{ route('admin.stocks.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Kembali ke Kelola Stok</span>
                </a>
            </div>
        </div>

        <!-- Status Metric Cards (Interactive) -->
        <div class="stocks-stats-grid">
            <a href="{{ route('admin.stocks.history') }}" class="stat-card {{ !request('type') ? 'active' : '' }}">
                <div class="stat-card-label">
                    <i class="fa-solid fa-clock-rotate-left"></i> Semua Riwayat
                </div>
                <div class="stat-card-value">{{ $counts['all'] ?? 0 }}</div>
            </a>

            <a href="{{ route('admin.stocks.history', ['type' => 'in']) }}" class="stat-card stat-card-success {{ request('type') === 'in' ? 'active-success' : '' }}">
                <div class="stat-card-label">
                    <i class="fa-solid fa-circle-arrow-down"></i> Stok Masuk
                </div>
                <div class="stat-card-value">{{ $counts['in'] ?? 0 }}</div>
            </a>

            <a href="{{ route('admin.stocks.history', ['type' => 'out']) }}" class="stat-card stat-card-danger {{ request('type') === 'out' ? 'active-danger' : '' }}">
                <div class="stat-card-label">
                    <i class="fa-solid fa-circle-arrow-up"></i> Stok Keluar
                </div>
                <div class="stat-card-value">{{ $counts['out'] ?? 0 }}</div>
            </a>

            <a href="{{ route('admin.stocks.history', ['type' => 'return']) }}" class="stat-card stat-card-primary {{ request('type') === 'return' ? 'active' : '' }}">
                <div class="stat-card-label">
                    <i class="fa-solid fa-rotate-left"></i> Retur Pembelian
                </div>
                <div class="stat-card-value">{{ $counts['return'] ?? 0 }}</div>
            </a>
        </div>

        <!-- Filter & Search Card -->
        <form method="GET" action="{{ route('admin.stocks.history') }}" class="stocks-filter-card">
            <div class="stocks-filter-row">
                <div class="filter-search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk, SKU, atau keterangan mutasi...">
                </div>

                <div class="filter-select-wrapper">
                    <select name="type" class="form-control" onchange="this.form.submit()">
                        <option value="">Semua Tipe Mutasi</option>
                        <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Stok Masuk (+)</option>
                        <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Stok Keluar (-)</option>
                        <option value="return" {{ request('type') === 'return' ? 'selected' : '' }}>Retur Pembelian</option>
                    </select>
                </div>

                <button type="submit" class="filter-submit-btn">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filter</span>
                </button>

                @if(request()->anyFilled(['search', 'type']))
                    <a href="{{ route('admin.stocks.history') }}" class="filter-reset-btn">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>

        <!-- History Table Container -->
        <div class="stocks-table-container">
            <table class="stocks-table">
                <thead>
                    <tr>
                        <th style="width: 4%; text-align: center;">No</th>
                        <th style="width: 14%;">Tanggal</th>
                        <th style="width: 32%;">Produk &amp; SKU</th>
                        <th style="width: 12%; text-align: center;">Tipe Mutasi</th>
                        <th style="width: 12%; text-align: center;">Perubahan Qty</th>
                        <th style="width: 12%;">Petugas</th>
                        <th style="width: 14%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($histories as $history)
                        <tr>
                            <td style="text-align: center; color: var(--secondary); font-size: 0.8125rem;">
                                {{ $loop->iteration + ($histories->currentPage() - 1) * $histories->perPage() }}
                            </td>
                            <td style="white-space: nowrap; color: var(--secondary); font-size: 0.8125rem;">
                                {{ $history->date ? $history->date->format('d/m/Y H:i') : '-' }} WIB
                            </td>
                            <td>
                                <div class="stocks-product-name">{{ optional($history->product)->name ?? 'Produk Dihapus / Tidak Tersedia' }}</div>
                                <div class="stocks-meta-text">
                                    <span class="stocks-sku-pill">SKU: {{ optional($history->product)->sku ?? '-' }}</span>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                @if ($history->type === 'in')
                                    <span class="badge-stock badge-type-in">
                                        <i class="fa-solid fa-arrow-down"></i> Masuk
                                    </span>
                                @elseif ($history->type === 'out')
                                    <span class="badge-stock badge-type-out">
                                        <i class="fa-solid fa-arrow-up"></i> Keluar
                                    </span>
                                @elseif ($history->type === 'return')
                                    <span class="badge-stock badge-type-return">
                                        <i class="fa-solid fa-rotate-left"></i> Retur
                                    </span>
                                @else
                                    <span class="badge-stock badge-type-other">
                                        {{ ucfirst($history->type) }}
                                    </span>
                                @endif
                            </td>
                            <td class="td-qty-change {{ in_array($history->type, ['in', 'return']) ? 'td-qty-positive' : 'td-qty-negative' }}">
                                {{ in_array($history->type, ['in', 'return']) ? '+' : '-' }}{{ $history->quantity }} pcs
                            </td>
                            <td>
                                <span class="stocks-petugas-name">
                                    {{ optional($history->user)->name ?? 'Sistem' }}
                                </span>
                            </td>
                            <td class="stocks-desc-cell" title="{{ $history->description }}">
                                {{ Str::limit($history->description, 50) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 48px 20px; color: var(--secondary);">
                                <i class="fa-solid fa-clock-rotate-left" style="font-size: 2.5rem; opacity: 0.35; margin-bottom: 12px; display: block;"></i>
                                <div style="font-weight: 700; color: var(--dark); margin-bottom: 4px; font-size: 1rem;">Belum Ada Riwayat Penyesuaian</div>
                                <div style="font-size: 0.85rem;">Belum ada catatan mutasi stok yang tersimpan atau cocok dengan filter yang dipilih.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if(method_exists($histories, 'links') && $histories->hasPages())
                <div class="stocks-pagination-wrapper">
                    <div class="stocks-pagination-info">
                        Menampilkan <strong>{{ $histories->firstItem() ?? 0 }}</strong> - <strong>{{ $histories->lastItem() ?? 0 }}</strong> dari <strong>{{ $histories->total() }}</strong> riwayat
                    </div>
                    <div class="stocks-pagination-links">
                        {{ $histories->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
