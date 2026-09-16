<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>NOTA - {{ $order->invoice_number }}</title>
    <style>
        @page {
            margin: 5px;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9px;
            color: #000000;
            line-height: 1.2;
            padding: 5px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .divider {
            border-top: 1px dashed #000000;
            margin: 6px 0;
        }
        .header-logo {
            font-size: 11px;
            font-weight: bold;
        }
        .meta-table, .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-table td {
            font-size: 8px;
            padding: 1px 0;
        }
        .items-table td {
            font-size: 8.5px;
            padding: 3px 0;
            vertical-align: top;
        }
        .total-table {
            width: 100%;
            margin-top: 6px;
        }
        .total-table td {
            font-size: 9px;
            font-weight: bold;
            padding: 2px 0;
        }
    </style>
</head>
<body>

    <!-- Header Store Info -->
    <div class="text-center">
        <div class="header-logo">CV. BINTANG JAYA KOMPUTER</div>
        <div style="font-size: 7.5px; margin-top: 2px;">
            Jl. Ahmad Yani No.68, Iringmulyo, Metro, Lampung<br>
            Telp: (0725) 45678
        </div>
    </div>

    <div class="divider"></div>

    <!-- Metadata Block -->
    <table class="meta-table">
        <tr>
            <td>No: {{ $order->invoice_number }}</td>
            <td class="text-right">Tgl: {{ $order->created_at->format('d/m/y H:i') }}</td>
        </tr>
        <tr>
            <td>Status: {{ strtoupper($order->status) }}</td>
            <td class="text-right">Kasir: {{ $order->cashier_display_name }}</td>
        </tr>
        <tr>
            <td colspan="2">
                Pelanggan: {{ $order->customer_display_name }}
                @if(!empty($order->customer_phone)) ({{ $order->customer_phone }}) @endif
            </td>
        </tr>
        <tr>
            <td colspan="2">
                Kurir: {{ $order->shipping_cost > 0 || !empty($order->shareloc_link) ? 'GrabExpress Instant' : 'POS / Ambil di Toko' }}
            </td>
        </tr>
        @if(!empty($order->kecamatan_id) || !empty($order->kelurahan_id))
        <tr>
            <td colspan="2">Tujuan: Kec. {{ optional($order->kecamatan)->nama_kecamatan }} / Kel. {{ optional($order->kelurahan)->nama_kelurahan }}</td>
        </tr>
        @endif
    </table>

    <div class="divider"></div>

    <!-- Items Listing -->
    <table class="items-table">
        @foreach ($order->items as $item)
            <tr>
                <td colspan="3"><strong>{{ $item->item_name ?? optional($item->product)->name }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%;">{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</td>
                <td style="width: 5%;"></td>
                <td style="width: 60%; text-align: right;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <!-- Totals -->
    <table class="total-table">
        @if($order->shipping_cost > 0)
        <tr>
            <td style="font-size: 8px; font-weight: normal;">Ongkir Grab:</td>
            <td class="text-right" style="font-size: 8px; font-weight: normal;">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr>
            <td>TOTAL:</td>
            <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td style="font-size: 8px; font-weight: normal; color: #555;">METODE BAYAR:</td>
            <td class="text-right" style="font-size: 8px; font-weight: normal; color: #555;">{{ strtoupper($order->payment_method ?? 'CASH') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Footer Note -->
    <div class="text-center" style="font-size: 8px; margin-top: 10px;">
        Terima Kasih Atas Kunjungan Anda<br>
        Barang Yang Sudah Dibeli<br>
        Tidak Dapat Ditukar/Dikembalikan Tanpa Nota Resmi
    </div>

</body>
</html>