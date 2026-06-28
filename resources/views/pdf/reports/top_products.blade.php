<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Produk Terlaris</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 11px; color: #333333; line-height: 1.4; }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 12px; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 5px; }
        .subtitle { font-size: 11px; text-align: center; color: #64748b; margin-bottom: 15px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th { background-color: #f8fafc; border-bottom: 1px solid #cbd5e1; padding: 8px 10px; font-weight: bold; text-align: left; }
        .table td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">CV. BINTANG JAYA KOMPUTER</div>
        <div style="text-align: center; font-size: 9px; color: #64748b;">Jl. Ahmad Yani No.68, Iringmulyo, Metro, Lampung</div>
    </div>

    <h3 style="text-align: center; margin-top: 0; text-transform: uppercase;">LAPORAN PENJUALAN PRODUK TERLARIS</h3>
    <div class="subtitle">Dicetak Pada: {{ now()->format('d F Y H:i') }}</div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 10%; text-align: center;">Peringkat</th>
                <th style="width: 50%;">Nama Barang</th>
                <th style="width: 20%; text-align: center;">Total Unit Terjual</th>
                <th style="width: 20%; text-align: right;">Total Nilai Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data['products'] as $index => $item)
                <tr>
                    <td style="text-align: center; font-weight: bold; color: #2563eb;">#{{ $index + 1 }}</td>
                    <td><strong>{{ $item->item_name }}</strong></td>
                    <td style="text-align: center; font-weight: bold;">{{ $item->total_qty }} pcs</td>
                    <td style="text-align: right; font-weight: bold; color: #10b981;">
                        Rp {{ number_format($item->total_sales, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px; color: #64748b;">Tidak ada data penjualan produk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
