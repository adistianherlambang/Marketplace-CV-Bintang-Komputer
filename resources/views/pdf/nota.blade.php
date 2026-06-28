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
        <div class="header-logo">BINTANG JAYA KOMPUTER</div>
        <div style="font-size: 7.5px; margin-top: 2px;">
            Jl. Ahmad Yani No.68, Iringmulyo, Metro<br>
            Telp: (0725) 45678
        </div>
    </div>

    <div class="divider"></div>

    <!-- Metadata Block -->
    <table class="meta-table">
        <tr>
            <td>No: {{ $order->invoice_number }}</td>
            <td class="text-right">Kasir: {{ Str::limit($order->user->name, 8) }}</td>
        </tr>
        <tr>
            <td>Tgl: {{ $order->created_at->format('d/m/y H:i') }}</td>
            <td class="text-right">Status: {{ strtoupper($order->status) }}</td>
        </tr>
        <tr>
            <td colspan="2">Pelanggan: {{ $order->customer ? Str::limit($order->customer->name, 20) : 'Guest' }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Items Listing -->
    <table class="items-table">
        @foreach ($order->items as $item)
            <tr>
                <td colspan="3"><strong>{{ $item->item_name }}</strong></td>
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
        <tr>
            <td>TOTAL:</td>
            <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td style="font-size: 8px; font-weight: normal; color: #555;">METODE BAYAR:</td>
            <td class="text-right" style="font-size: 8px; font-weight: normal; color: #555;">{{ $order->payments->first() ? $order->payments->first()->payment_method : 'Cash' }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Footer Note -->
    <div class="text-center" style="font-size: 8px; margin-top: 10px;">
        Terima Kasih Atas Kunjungan Anda<br>
        Barang Yang Sudah Dibeli<br>
        Tidak Dapat Ditukar/Dikembalikan
    </div>

</body>
</html>
