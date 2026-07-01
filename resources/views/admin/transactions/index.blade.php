<x-admin-layout>
    @section('header_title', 'Riwayat Transaksi')

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules/transactions-index.module.css') }}">
    @endpush

    <!-- Action Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="title">
            <h3 class="font-bold page-title">Daftar Invoice Penjualan</h3>
            <p class="text-secondary text-sm">Cari, cetak, batalkan, atau ubah status pembayaran transaksi toko.</p>
        </div>
    
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="{{ route('admin.transactions.index') }}" class="filter-bar">
        <div class="filter-inputs">
            <div class="filter-search-wrapper">
                <i class="fa-solid fa-magnifying-glass filter-search-icon"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor invoice atau nama pelanggan..." class="form-control filter-input-indent">
            </div>

            <select name="status" class="form-control filter-select-sm tom-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="Lunas" {{ request('status') === 'Lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="Belum Dibayar" {{ request('status') === 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                <option value="Dibatalkan" {{ request('status') === 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Cari</button>
                @if (request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">Clear</a>
                @endif
            </div>
        </div>
        <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary button">
            POS Penjualan Baru
        </a>
    </form>

    <!-- Table Grid -->
    <div class="table-container">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Total Belanja</th>
                        <th class="th-status">Status</th>
                        <th>Kasir (Admin)</th>
                        <th class="th-actions-wide">Cetak / Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>
                                <strong>{{ $order->invoice_number }}</strong>
                                @if($order->notes)
                                    <div class="text-xs text-secondary"><i class="fa-solid fa-note-sticky mr-1"></i>{{ $order->notes }}</div>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td>
                                @if ($order->customer)
                                    <strong>{{ $order->customer->name }}</strong>
                                    <div class="text-xs text-secondary">{{ $order->customer->phone ?: 'No phone' }}</div>
                                @else
                                    <span class="text-secondary font-semibold">Guest (Walk-in)</span>
                                @endif
                            </td>
                            <td class="font-bold text-primary">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="td-center">
                                @if ($order->status === 'Lunas')
                                    <span class="badge badge-success">Lunas</span>
                                @elseif ($order->status === 'Belum Dibayar')
                                    <span class="badge badge-warning">Belum Bayar</span>
                                @else
                                    <span class="badge badge-danger">Batal</span>
                                @endif
                            </td>
                            <td>{{ $order->user->name }}</td>
                            <td class="td-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.transactions.show', $order->id) }}" class="btn btn-secondary btn-sm" title="Detail">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </a>
                                    
                                    @if ($order->status !== 'Dibatalkan')
                                        <a href="{{ route('admin.transactions.invoice', $order->id) }}" class="btn btn-primary btn-sm" title="Download Invoice PDF">
                                            <i class="fa-solid fa-file-pdf"></i> Inv
                                        </a>
                                        <a href="{{ route('admin.transactions.nota', $order->id) }}" class="btn btn-secondary btn-sm" title="Download Nota Receipt PDF">
                                            <i class="fa-solid fa-receipt text-success"></i> Nota
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="td-empty">
                                <i class="fa-solid fa-file-invoice-dollar icon-empty-lg"></i>
                                <p>Belum ada transaksi. Klik 'POS Penjualan Baru' untuk membuat baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center pagination-wrapper">
        <div class="flex justify-center mt-4 pagination">
            {{ $orders->links('pagination::bootstrap-4') }}
        </div>
    </div>
</x-admin-layout>
