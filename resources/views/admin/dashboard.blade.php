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
        <!-- Invoices today -->
        <div class="metric-card metric-card-sm">
            <div class="metric-info">
                <h4 class="metric-label-sm">Total Transaksi</h4>
                <div class="metric-val metric-val-sm">{{ $stats['invoice_count'] }}</div>
            </div>
        </div>

        <!-- Categories -->
        <div class="metric-card metric-card-sm">
            <div class="metric-info">
                <h4 class="metric-label-sm">Total Kategori</h4>
                <div class="metric-val metric-val-sm">{{ $stats['total_categories'] }}</div>
            </div>
        </div>

        <!-- Returns count -->
        <div class="metric-card metric-card-sm">
            <div class="metric-info">
                <h4 class="metric-label-sm">Total Retur</h4>
                <div class="metric-val metric-val-sm">{{ $stats['return_count'] }}</div>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="charts-grid">
        <!-- Monthly Sales Chart -->
        <div class="chart-card">
            <p class="font-bold mb-4 chart-title">Grafik Penjualan Bulanan (Lunas)</p>
            <div class="chart-canvas-wrapper chart-satu">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Top Selling Products Chart -->
        <div class="chart-card">
            <h3 class="font-bold mb-4 chart-title">Produk Terlaris</h3>
            <div class="chart-canvas-wrapper">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert and Quick Logs -->
    <div class="charts-grid low-stock-section">
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
                            <th>Kategori</th>
                            <th>Merk</th>
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
                                <td>{{ $product->category->name }}</td>
                                <td>{{ $product->brand->name }}</td>
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
                                <td colspan="7" class="td-empty-wide">
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

    <!-- Charts JavaScript Integration -->
    <script id="sales-chart-data" type="application/json">
        @json($stats['monthly_chart_data'])
    </script>
    <script id="top-products-chart-data" type="application/json">
        @json($stats['top_products'])
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const salesData = JSON.parse(document.getElementById('sales-chart-data').textContent);
            const topProductsData = JSON.parse(document.getElementById('top-products-chart-data').textContent);

            // Monthly Sales Line Chart
            const isMobile = window.matchMedia("(max-width: 767px)").matches;

            new Chart(document.getElementById('salesChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: salesData.map(d => d.month),
                    datasets: [{
                        label: 'Penjualan (Lunas)',
                        data: salesData.map(d => d.total),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.08)',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#2563eb',
                        pointRadius: 4,
                        tension: 0.4,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                display: !isMobile
                            },
                            grid: {
                                display: !isMobile
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                display: !isMobile,
                                callback: v => Number(v).toLocaleString('id-ID')
                            },
                            grid: {
                                display: !isMobile
                            }
                        }
                    }
                }
            });

            // Top Products Doughnut Chart
            new Chart(document.getElementById('topProductsChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: topProductsData.map(p => p.name),
                    datasets: [{
                        data: topProductsData.map(p => p.total_sold),
                        backgroundColor: ['#2563eb','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4'],
                        borderWidth: 2,
                        borderColor: '#fff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', align: 'start', labels: { padding: 16, font: { size: 12 } } }
                    }
                }
            });
        });
    </script>
</x-admin-layout>
