<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan Bulanan</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 11px; color: #333333; line-height: 1.4; }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 12px; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 5px; }
        .subtitle { font-size: 11px; text-align: center; color: #64748b; margin-bottom: 15px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th { background-color: #f8fafc; border-bottom: 1px solid #cbd5e1; padding: 8px 10px; font-weight: bold; text-align: left; }
        .table td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; }
        .summary-box { float: right; width: 250px; margin-top: 20px; border-top: 1px solid #cbd5e1; padding-top: 10px; }
        .summary-row { width: 100%; }
        .summary-row td { padding: 3px 0; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">CV. BINTANG JAYA KOMPUTER</div>
        <div style="text-align: center; font-size: 9px; color: #64748b;">Jl. Ahmad Yani No.68, Iringmulyo, Metro, Lampung</div>
    </div>

    <h3 style="text-align: center; margin-top: 0; text-transform: uppercase;">LAPORAN PENJUALAN BULANAN</h3>
    <div class="subtitle">Bulan Laporan: {{ $data['month'] }}</div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 25%;">No. Invoice</th>
                <th style="width: 20%;">Tanggal</th>
                <th style="width: 30%;">Pelanggan</th>
                <th style="width: 10%; text-align: center;">Status</th>
                <th style="width: 15%; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data['orders'] as $order)
                <tr>
                    <td><strong>{{ $order->invoice_number }}</strong></td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>{{ $order->customer ? $order->customer->name : 'Guest' }}</td>
                    <td style="text-align: center;">
                        <span style="font-weight: bold; color: {{ $order->status === 'Lunas' ? '#10b981' : '#f59e0b' }}">{{ $order->status }}</span>
                    </td>
                    <td style="text-align: right; font-weight: bold;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: #64748b;">Tidak ada data transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-box">
        <table style="width: 100%;">
            <tr class="summary-row">
                <td style="color: #64748b;">Jumlah Transaksi:</td>
                <td style="text-align: right; font-weight: bold;">{{ $data['total_transactions'] }} trx</td>
            </tr>
            <tr class="summary-row" style="font-size: 13px; font-weight: bold;">
                <td>Total Penjualan:</td>
                <td style="text-align: right; color: #2563eb;">Rp {{ number_format($data['total_sales'], 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
