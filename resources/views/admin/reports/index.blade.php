<x-admin-layout>
    @section('header_title', 'Laporan Keuangan & Stok Toko')

    <div class="grid" style="grid-template-columns: 1fr 1.5fr; gap: 24px; align-items: start;">
        
        <!-- Left Pane: Report Selector Form -->
        <div class="chart-card">
            <h3 class="font-bold mb-4" style="font-size: 1.1rem;"><i class="fa-solid fa-file-pdf text-primary mr-2"></i>Buat / Download Laporan PDF</h3>
            
            <form method="GET" action="{{ route('admin.reports.preview') }}" target="_blank">
                <!-- Report Type -->
                <div class="form-group">
                    <label class="form-label">Jenis Laporan</label>
                    <select name="type" id="report_type_select" class="form-control" required onchange="onReportTypeChange(this.value)">
                        <option value="daily">Laporan Penjualan Harian</option>
                        <option value="monthly" selected>Laporan Penjualan Bulanan</option>
                        <option value="stock">Laporan Status Stok Gudang</option>
                        <option value="return">Laporan Retur Barang</option>
                        <option value="top_products">Laporan Produk Terlaris</option>
                    </select>
                </div>

                <!-- Parameters: Date Selector (Daily) -->
                <div class="form-group" id="group_date" style="display: none;">
                    <label class="form-label">Pilih Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ now()->toDateString() }}">
                </div>

                <!-- Parameters: Month Selector (Monthly) -->
                <div class="form-group" id="group_month">
                    <label class="form-label">Pilih Bulan</label>
                    <input type="month" name="month" class="form-control" value="{{ now()->format('Y-m') }}">
                </div>

                <div class="flex flex-col gap-2 mt-6">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fa-solid fa-eye"></i> Preview Laporan (HTML)
                    </button>
                    
                    <button type="button" onclick="downloadDirectPdf()" class="btn btn-secondary" style="width: 100%; border-color: #cbd5e1;">
                        <i class="fa-solid fa-file-arrow-down text-success"></i> Download PDF Langsung
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Pane: Monthly Reports History Archive -->
        <div class="chart-card" style="padding: 24px 0;">
            <h3 class="font-bold mb-4" style="font-size: 1.1rem; padding: 0 24px;"><i class="fa-solid fa-archive text-secondary mr-2"></i>Arsip Rekap Laporan Bulanan</h3>
            
            <div class="table-responsive">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="background: none;">Bulan Laporan</th>
                            <th style="background: none; text-align: right;">Total Penjualan</th>
                            <th style="background: none; text-align: right;">Est. Laba Bersih</th>
                            <th style="background: none; text-align: center;">Jml Transaksi</th>
                            <th style="background: none; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($monthlyReports as $rep)
                            <tr>
                                <td><strong>{{ Carbon\Carbon::parse($rep->report_month . '-01')->format('F Y') }}</strong></td>
                                <td style="text-align: right; font-weight: 600; color: var(--primary);">
                                    Rp {{ number_format($rep->total_sales, 0, ',', '.') }}
                                </td>
                                <td style="text-align: right; font-weight: 600; color: var(--success);">
                                    Rp {{ number_format($rep->total_earnings, 0, ',', '.') }}
                                </td>
                                <td style="text-align: center; font-weight: 600;">{{ $rep->total_transactions }} trx</td>
                                <td style="text-align: center;">
                                    <a href="{{ route('admin.reports.download', ['type' => 'monthly', 'param' => $rep->report_month]) }}" class="btn btn-secondary btn-sm" style="padding: 4px 8px; font-size: 0.75rem;">
                                        <i class="fa-solid fa-file-arrow-down"></i> PDF
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px; color: var(--secondary);">
                                    <i class="fa-solid fa-folder-open" style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.5;"></i>
                                    <p>Belum ada arsap bulanan yang tercatat. Download rekap bulanan di sebelah kiri untuk menambah arsip.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Script to toggle form input visibility -->
    <script>
        function onReportTypeChange(val) {
            document.getElementById('group_date').style.display = val === 'daily' ? 'block' : 'none';
            document.getElementById('group_month').style.display = val === 'monthly' ? 'block' : 'none';
        }

        function downloadDirectPdf() {
            const typeSelect = document.getElementById('report_type_select').value;
            let paramVal = '';
            
            if (typeSelect === 'daily') {
                paramVal = document.getElementsByName('date')[0].value;
            } else if (typeSelect === 'monthly') {
                paramVal = document.getElementsByName('month')[0].value;
            }

            const downloadUrl = `{{ route('admin.reports.download') }}?type=${typeSelect}&param=${paramVal}`;
            window.location.href = downloadUrl;
        }
    </script>
</x-admin-layout>
