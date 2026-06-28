<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>INVOICE - {{ $order->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-logo {
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
            text-transform: uppercase;
        }
        .header-info {
            text-align: right;
        }
        .info-table {
            width: 100%;
            margin-bottom: 25px;
        }
        .info-title {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f8fafc;
            border-bottom: 1px solid #cbd5e1;
            padding: 8px 10px;
            font-weight: bold;
            text-align: left;
            font-size: 10px;
            color: #475569;
        }
        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .total-box {
            float: right;
            width: 250px;
        }
        .total-row {
            width: 100%;
        }
        .total-row td {
            padding: 4px 0;
        }
        .total-final {
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
            border-top: 1px solid #cbd5e1;
            padding-top: 6px;
            margin-top: 6px;
        }
        .footer-note {
            margin-top: 150px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <!-- Header Block -->
    <table class="header-table">
        <tr>
            <td>
                <div class="header-logo">CV. Bintang Jaya Komputer</div>
                <div style="font-size: 9px; color: #64748b; margin-top: 3px;">
                    Jl. Ahmad Yani No.68, Iringmulyo, Kota Metro, Lampung<br>
                    Telp: (0725) 45678 | Email: bintangjayakomputer.metro@gmail.com
                </div>
            </td>
            <td class="header-info">
                <div style="font-size: 16px; font-weight: bold; color: #0f172a;">FAKTUR PENJUALAN</div>
                <div style="font-size: 11px; font-weight: bold; color: #2563eb; margin-top: 3px;">{{ $order->invoice_number }}</div>
                <div style="font-size: 9px; color: #64748b; margin-top: 2px;">Tanggal: {{ $order->created_at->format('d/m/Y H:i') }}</div>
            </td>
        </tr>
    </table>

    <!-- Billing Info Block -->
    <table class="info-table">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                <div class="info-title">Pelanggan / Pembeli:</div>
                @if ($order->customer)
                    <strong>{{ $order->customer->name }}</strong><br>
                    Telp: {{ $order->customer->phone ?: '-' }}<br>
                    Alamat: {{ $order->customer->address ?: '-' }}
                @else
                    <strong>Guest / Walk-in Customer</strong><br>
                    Toko Retail Offline Bintang Jaya Komputer
                @endif
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 20px;">
                <div class="info-title">Pembayaran & Kasir:</div>
                <strong>Kasir:</strong> {{ $order->user->name }}<br>
                <strong>Status Pembayaran:</strong> 
                <span style="font-weight: bold; color: {{ $order->status === 'Lunas' ? '#10b981' : '#ef4444' }};">
                    {{ strtoupper($order->status) }}
                </span><br>
                <strong>Metode Pembayaran:</strong> {{ $order->payments->first() ? $order->payments->first()->payment_method : 'Cash' }}
            </td>
        </tr>
    </table>

    <!-- Items Listing -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">Nama Barang / Produk</th>
                <th style="text-align: right; width: 20%;">Harga Satuan</th>
                <th style="text-align: center; width: 10%;">Qty</th>
                <th style="text-align: right; width: 20%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->item_name }}</strong>
                        @if ($item->product_id)
                            <div style="font-size: 8px; color: #64748b; margin-top: 2px;">SKU: {{ $item->product->sku }}</div>
                        @endif
                    </td>
                    <td style="text-align: right;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right; font-weight: bold;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total Calculations -->
    <div class="total-box">
        <table style="width: 100%;">
            <tr class="total-row">
                <td style="color: #64748b;">Subtotal Belanja:</td>
                <td style="text-align: right;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td style="color: #64748b;">Pajak / PPN (0%):</td>
                <td style="text-align: right;">Rp 0</td>
            </tr>
            <tr class="total-row total-final">
                <td style="font-weight: bold;">Total Bayar:</td>
                <td style="text-align: right; font-weight: bold;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    @if ($order->notes)
        <div style="margin-top: 40px; width: 60%; background-color: #f8fafc; padding: 10px; border-radius: 4px; border: 1px solid #e2e8f0;">
            <strong style="font-size: 9px; color: #475569;">Catatan / Syarat Ketentuan:</strong>
            <div style="font-size: 8.5px; color: #64748b; margin-top: 3px;">{{ $order->notes }}</div>
        </div>
    @endif

    <!-- Footer Note -->
    <div class="footer-note">
        Terima kasih atas kunjungan dan kepercayaan Anda berbelanja di CV. Bintang Jaya Komputer.<br>
        Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan kecuali terdapat perjanjian garansi produk.
    </div>

</body>
</html>
