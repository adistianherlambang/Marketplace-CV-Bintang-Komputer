<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>NOTA ONLINE - {{ $order->invoice_number }}</title>
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
            width: 270px;
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
                <div style="font-size: 16px; font-weight: bold; color: #0f172a;">NOTA PEMBELIAN ONLINE</div>
                <div style="font-size: 11px; font-weight: bold; color: #2563eb; margin-top: 3px;">{{ $order->invoice_number }}</div>
                <div style="font-size: 9px; color: #64748b; margin-top: 2px;">Tanggal: {{ $order->created_at->format('d/m/Y H:i') }}</div>
            </td>
        </tr>
    </table>

    <!-- Billing Info Block -->
    <table class="info-table">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                <div class="info-title">Penerima / Pemesan:</div>
                <strong>{{ $order->customer_display_name }}</strong><br>
                Telp: {{ $order->customer_phone ?? optional($order->customer)->phone ?? '-' }}<br>
                @if($order->customer && $order->customer->address)
                    Alamat: {{ $order->customer->address }}<br>
                @elseif($order->kecamatan || $order->kelurahan)
                    Alamat: Kec. {{ optional($order->kecamatan)->nama_kecamatan }}, Kel. {{ optional($order->kelurahan)->nama_kelurahan }}<br>
                @endif
                Kurir: {{ $order->shipping_cost > 0 || !empty($order->shareloc_link) ? 'GrabExpress Instant' : 'Ambil di Toko' }}
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 20px;">
                <div class="info-title">Detail Pesanan &amp; Pembayaran:</div>
                <strong>Status:</strong> 
                @if(in_array(strtolower($order->status), ['selesai', 'lunas']))
                    <span style="font-weight: bold; color: #10b981;">{{ strtoupper($order->status) }}</span>
                @elseif(strtolower($order->status) === 'batal' || strtolower($order->status) === 'dibatalkan')
                    <span style="font-weight: bold; color: #ef4444;">{{ strtoupper($order->status) }}</span>
                @else
                    <span style="font-weight: bold; color: #f59e0b;">{{ strtoupper($order->status) }}</span>
                @endif
                <br>
                <strong>Metode Pembayaran:</strong> {{ $order->payment_method ?? optional($order->payments->first())->payment_method ?? 'Transfer' }}
            </td>
        </tr>
    </table>

    <!-- Items Listing -->
    <table class="items-table">
        <thead>
            <tr>
                <th>Produk</th>
                <th style="text-align: right;">Harga Satuan</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->item_name ?? optional($item->product)->name }}</strong>
                        @if($item->product_id && $item->product)
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
            @if($order->shipping_cost > 0)
                <tr class="total-row">
                    <td style="color: #64748b;">Subtotal Barang:</td>
                    <td style="text-align: right;">Rp {{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td style="color: #64748b;">Ongkos Kirim:</td>
                    <td style="text-align: right;">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                </tr>
            @else
                <tr class="total-row">
                    <td style="color: #64748b;">Subtotal Belanja:</td>
                    <td style="text-align: right;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="total-row total-final">
                <td style="font-weight: bold;">Total Bayar:</td>
                <td style="text-align: right; font-weight: bold;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    @if ($order->notes)
        <div style="margin-top: 40px; width: 60%; background-color: #f8fafc; padding: 10px; border-radius: 4px; border: 1px solid #e2e8f0;">
            <strong style="font-size: 9px; color: #475569;">Catatan Transaksi:</strong>
            <div style="font-size: 8.5px; color: #64748b; margin-top: 3px;">{{ $order->notes }}</div>
        </div>
    @endif

    <!-- Footer Note -->
    <div class="footer-note">
        Terima kasih telah berbelanja di CV. Bintang Jaya Komputer.<br>
        Simpan nota ini sebagai bukti transaksi sah untuk klaim garansi produk.
    </div>

</body>
</html>
