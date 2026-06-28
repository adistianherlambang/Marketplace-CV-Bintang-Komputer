<x-admin-layout>
    @section('header_title', 'Dashboard Ringkasan Toko')

    <!-- Metrics Cards -->
    <div class="metrics-grid">
        <!-- Sales Today -->
        <div class="metric-card">
            <div class="metric-info">
                <h4>Penjualan Hari Ini</h4>
                <div class="metric-val">Rp {{ number_format($stats['sales_today'], 0, ',', '.') }}</div>
            </div>
            <div class="metric-icon" style="background-color: #dbeafe; color: #2563eb;">
                <i class="fa-solid fa-coins"></i>
            </div>
        </div>

        <!-- Sales Month -->
        <div class="metric-card">
            <div class="metric-info">
                <h4>Penjualan Bulan Ini</h4>
                <div class="metric-val">Rp {{ number_format($stats['sales_this_month'], 0, ',', '.') }}</div>
            </div>
            <div class="metric-icon" style="background-color: #d1fae5; color: #10b981;">
                <i class="fa-solid fa-money-bill-trend-up"></i>
            </div>
        </div>

        <!-- Total Products -->
        <div class="metric-card">
            <div class="metric-info">
                <h4>Total Produk Aktif</h4>
                <div class="metric-val">{{ $stats['total_products'] }}</div>
            </div>
            <div class="metric-icon" style="background-color: #fef3c7; color: #d97706;">
                <i class="fa-solid fa-box-open"></i>
            </div>
        </div>

        <!-- Total Stocks -->
        <div class="metric-card">
            <div class="metric-info">
                <h4>Total Unit Stok</h4>
                <div class="metric-val">{{ $stats['total_stock'] }} pcs</div>
            </div>
            <div class="metric-icon" style="background-color: #fce7f3; color: #db2777;">
                <i class="fa-solid fa-warehouse"></i>
            </div>
        </div>
    </div>

    <div class="metrics-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 32px;">
        <!-- Invoices today -->
        <div class="metric-card" style="padding: 16px 20px;">
            <div class="metric-info">
                <h4 style="font-size: 0.75rem;">Total Transaksi</h4>
                <div class="metric-val" style="font-size: 1.35rem;">{{ $stats['invoice_count'] }}</div>
            </div>
            <span class="badge badge-info">Invoices</span>
        </div>

        <!-- Low stock warning -->
        <div class="metric-card" style="padding: 16px 20px;">
            <div class="metric-info">
                <h4 style="font-size: 0.75rem;">Stok Hampir Habis</h4>
                <div class="metric-val" style="font-size: 1.35rem; color: var(--danger);">{{ $stats['low_stock_count'] }}</div>
            </div>
            <span class="badge badge-danger">Tinjau</span>
        </div>

        <!-- Categories -->
        <div class="metric-card" style="padding: 16px 20px;">
            <div class="metric-info">
                <h4 style="font-size: 0.75rem;">Total Kategori</h4>
                <div class="metric-val" style="font-size: 1.35rem;">{{ $stats['total_categories'] }}</div>
            </div>
            <span class="badge badge-warning">Kategori</span>
        </div>

        <!-- Returns count -->
        <div class="metric-card" style="padding: 16px 20px;">
            <div class="metric-info">
                <h4 style="font-size: 0.75rem;">Total Retur</h4>
                <div class="metric-val" style="font-size: 1.35rem;">{{ $stats['return_count'] }}</div>
            </div>
            <span class="badge badge-danger">Retur</span>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="charts-grid">
        <!-- Monthly Sales Chart -->
        <div class="chart-card">
            <h3 class="font-bold mb-4" style="font-size: 1.1rem;"><i class="fa-solid fa-chart-line text-primary mr-2"></i>Grafik Penjualan Bulanan (Lunas)</h3>
            <div style="position: relative; height: 320px; width: 100%;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Top Selling Products Chart -->
        <div class="chart-card">
            <h3 class="font-bold mb-4" style="font-size: 1.1rem;"><i class="fa-solid fa-chart-pie text-success mr-2"></i>Produk Terlaris</h3>
            <div style="position: relative; height: 320px; width: 100%;">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert and Quick Logs -->
    <div class="charts-grid" style="grid-template-columns: 1fr; margin-top: 32px;">
        <div class="chart-card" style="padding: 24px 0;">
            <div style="padding: 0 24px 16px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border);">
                <h3 class="font-bold" style="font-size: 1.1rem; color: var(--danger);"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Peringatan Produk Hampir Habis</h3>
                <a href="{{ route('admin.stocks.index') }}" class="btn btn-secondary btn-sm">Kelola Stok <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            
            <div class="table-responsive">
                <table style="width: 100%; border-collapse: collapse; margin-top: 8px;">
                    <thead>
                        <tr>
                            <th style="background: none;">Nama Barang</th>
                            <th style="background: none;">SKU</th>
                            <th style="background: none;">Kategori</th>
                            <th style="background: none;">Merk</th>
                            <th style="background: none; text-align: center;">Minimum Stok</th>
                            <th style="background: none; text-align: center;">Sisa Stok</th>
                            <th style="background: none; text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stats['low_stock_products'] as $product)
                            <tr>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td><code>{{ $product->sku }}</code></td>
                                <td>{{ $product->category->name }}</td>
                                <td>{{ $product->brand->name }}</td>
                                <td style="text-align: center;">{{ $product->min_stock }}</td>
                                <td style="text-align: center; font-weight: 700; color: var(--danger);">{{ $product->stock }}</td>
                                <td style="text-align: center;">
                                    @if ($product->stock === 0)
                                        <span class="badge badge-danger">Habis</span>
                                    @else
                                        <span class="badge badge-warning">Kritis</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 32px; color: var(--secondary);">
                                    <i class="fa-solid fa-circle-check text-success" style="font-size: 2rem; margin-bottom: 8px;"></i>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Sales Chart Configuration
            const salesData = @json($stats['monthly_chart_data']);
            const salesLabels = salesData.map(item => item.label);
            const salesAmounts = salesData.map(item => item.amount);

            const ctxSales = document.getElementById('salesChart').getContext('2d');
            new Chart(ctxSales, {
                type: 'line',
                data: {
                    labels: salesLabels,
                    datasets: [{
                        label: 'Total Penjualan (Rp)',
                        data: salesAmounts,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.05)',
                        borderWidth: 3,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#2563eb',
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });

            // 2. Top Products Chart Configuration
            const topProducts = @json($stats['top_products']);
            const topLabels = topProducts.length ? topProducts.map(item => item.item_name) : ['Belum Ada Transaksi'];
            const topQtys = topProducts.length ? topProducts.map(item => item.total_qty) : [0];

            const ctxTop = document.getElementById('topProductsChart').getContext('2d');
            new Chart(ctxTop, {
                type: 'doughnut',
                data: {
                    labels: topLabels,
                    datasets: [{
                        data: topQtys,
                        backgroundColor: [
                            '#2563eb',
                            '#10b981',
                            '#f59e0b',
                            '#ec4899',
                            '#8b5cf6'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, padding: 12 }
                        }
                    }
                }
            });
        });
    </script>
</x-admin-layout>
