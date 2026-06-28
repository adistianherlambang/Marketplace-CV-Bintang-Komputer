<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Persediaan Stok Barang</title>
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

    <h3 style="text-align: center; margin-top: 0; text-transform: uppercase;">LAPORAN PERSEDIAAN STOK BARANG GUDANG</h3>
    <div class="subtitle">Dicetak Pada: {{ now()->format('d F Y H:i') }}</div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 35%;">Nama Barang / SKU</th>
                <th style="width: 15%;">Merk</th>
                <th style="width: 20%;">Supplier</th>
                <th style="width: 15%; text-align: right;">Harga Modal</th>
                <th style="width: 15%; text-align: center;">Sisa Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['products'] as $product)
                <tr>
                    <td>
                        <strong>{{ $product->name }}</strong>
                        <div style="font-size: 8px; color: #64748b; margin-top: 2px;">SKU: {{ $product->sku }}</div>
                    </td>
                    <td>{{ $product->brand->name }}</td>
                    <td>{{ $product->supplier->name }}</td>
                    <td style="text-align: right;">Rp {{ number_format($product->price_modal, 0, ',', '.') }}</td>
                    <td style="text-align: center; font-weight: bold; color: {{ $product->stock <= $product->min_stock ? '#ef4444' : '#000000' }};">
                        {{ $product->stock }} pcs
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-box">
        <table style="width: 100%;">
            <tr class="summary-row">
                <td style="color: #64748b;">Total Unit Stok:</td>
                <td style="text-align: right; font-weight: bold;">{{ $data['total_stock'] }} unit</td>
            </tr>
            <tr class="summary-row" style="font-size: 13px; font-weight: bold;">
                <td>Total Nilai Aset:</td>
                <td style="text-align: right; color: #10b981;">Rp {{ number_format($data['total_value'], 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
