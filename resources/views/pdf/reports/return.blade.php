<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Retur Barang</title>
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

    <h3 style="text-align: center; margin-top: 0; text-transform: uppercase;">LAPORAN RETUR BARANG PENJUALAN</h3>
    <div class="subtitle">Dicetak Pada: {{ now()->format('d F Y H:i') }}</div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 25%;">No. Invoice</th>
                <th style="width: 30%;">Nama Barang</th>
                <th style="width: 10%; text-align: center;">Qty</th>
                <th style="width: 20%;">Alasan Kerusakan</th>
                <th style="width: 15%; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data['returns'] as $ret)
                <tr>
                    <td><strong>{{ $ret->order->invoice_number }}</strong></td>
                    <td>{{ $ret->product->name }}</td>
                    <td style="text-align: center;">{{ $ret->quantity }} pcs</td>
                    <td>{{ $ret->reason }}</td>
                    <td style="text-align: center; font-weight: bold; color: {{ $ret->status === 'Disetujui' ? '#10b981' : ($ret->status === 'Ditolak' ? '#ef4444' : '#f59e0b') }};">
                        {{ strtoupper($ret->status) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: #64748b;">Tidak ada data retur barang.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
