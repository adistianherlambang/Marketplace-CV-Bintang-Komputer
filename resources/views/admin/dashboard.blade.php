<x-admin-layout>
    @section('header_title', 'Dashboard Admin')

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules/dashboard.module.css') }}">
    @endpush

    <!-- Metrics Cards -->
    <div class="metrics-grid">
        <!-- Sales Today -->
        <div class="metric-card">
            <div class="metric-icon metric-icon-blue">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div class="metric-info">
                <h4>Penjualan Hari Ini</h4>
                <div class="metric-val">Rp {{ number_format($stats['sales_today'], 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Sales Month -->
        <div class="metric-card">
            <div class="metric-icon metric-icon-green">
                <i class="fa-solid fa-money-bill-trend-up"></i>
            </div>
            <div class="metric-info">
                <h4>Penjualan Bulan Ini</h4>
                <div class="metric-val">Rp {{ number_format($stats['sales_this_month'], 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="metric-card">
            <div class="metric-icon metric-icon-amber">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <div class="metric-info">
                <h4>Total Produk Aktif</h4>
                <div class="metric-val">{{ $stats['total_products'] }}</div>
            </div>
        </div>

        <!-- Total Stocks -->
        <div class="metric-card">
            <div class="metric-icon metric-icon-pink">
                <i class="fa-solid fa-warehouse"></i>
            </div>
            <div class="metric-info">
                <h4>Total Unit Stok</h4>
                <div class="metric-val">{{ $stats['total_stock'] }} pcs</div>
            </div>
        </div>
    </div>

    <div class="metrics-grid metrics-grid-sm">
        <!-- Total Invoices -->
        <div class="metric-card metric-card-sm">
            <div class="metric-info">
                <h4 class="metric-label-sm">Total Transaksi / Invoice</h4>
                <div class="metric-val metric-val-sm">{{ $stats['invoice_count'] }}</div>
            </div>
        </div>

        <!-- Pesanan Masuk Online -->
        @php
            $pesananMasuk = \App\Models\Order::where('status', 'Menunggu Konfirmasi')->count();
        @endphp
        <div class="metric-card metric-card-sm" style="border-left: 4px solid #f59e0b;">
            <div class="metric-info">
                <h4 class="metric-label-sm">Pesanan Menunggu Verifikasi</h4>
                <div class="metric-val metric-val-sm" style="color: #b45309;">{{ $pesananMasuk }}</div>
            </div>
        </div>

        <!-- Returns count -->
        <div class="metric-card metric-card-sm">
            <div class="metric-info">
                <h4 class="metric-label-sm">Total Retur Barang</h4>
                <div class="metric-val metric-val-sm">{{ $stats['return_count'] }}</div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert and Quick Logs -->
    <div class="charts-grid low-stock-section" style="grid-template-columns: 1fr; margin-top: 24px;">
        <div class="chart-card low-stock-card">
            <div class="low-stock-header">
                <h3 class="font-bold low-stock-title">Produk Hampir Habis</h3>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">Kelola Stok <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            
            <div class="table-responsive">
                <table class="table-full table-no-bg table-auto">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>SKU</th>
                            <th class="th-center">Minimum Stok</th>
                            <th class="th-center">Sisa Stok</th>
                            <th class="th-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stats['low_stock_products'] as $product)
                            <tr>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td><code>{{ $product->sku }}</code></td>
                                <td class="td-center">{{ $product->min_stock }}</td>
                                <td class="td-center-bold">{{ $product->stock }}</td>
                                <td class="td-center">
                                    @if ($product->stock === 0)
                                        <span class="badge badge-danger">Habis</span>
                                    @else
                                        <span class="badge badge-warning">Kritis</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="td-empty-wide">
                                    <i class="fa-solid fa-circle-check text-success icon-check-xl"></i>
                                    <p>Semua stok produk aman di atas batas minimum.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
