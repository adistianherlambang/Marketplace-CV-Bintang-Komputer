<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan Bulanan - {{ $data['month'] }}</title>
    <style>
        @page {
            margin: 20px 25px 25px 25px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 8pt;
            color: #1e293b;
            line-height: 1.35;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 8px;
            margin-bottom: 10px;
            border-collapse: collapse;
        }
        .header-table td {
            border: none;
            padding: 0;
        }
        .report-title {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            color: #0f172a;
            margin: 6px 0 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .report-subtitle {
            text-align: center;
            font-size: 8pt;
            color: #64748b;
            margin-bottom: 12px;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .table-data th {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 5px 6px;
            font-size: 7.5pt;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }
        .table-data td {
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
            font-size: 7.5pt;
            vertical-align: top;
        }
        .table-data tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .invoice-no {
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
            color: #1d4ed8;
            font-size: 7.5pt;
            white-space: nowrap;
        }
        .item-row {
            margin-bottom: 2px;
            line-height: 1.25;
        }
        .item-qty {
            color: #475569;
            font-size: 7pt;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 5px;
            font-size: 6.8pt;
            font-weight: bold;
            border-radius: 3px;
            text-align: center;
            white-space: nowrap;
        }
        .status-selesai {
            background-color: #dcfce7;
            color: #15803d;
            border: 0.5px solid #86efac;
        }
        .status-diproses {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 0.5px solid #93c5fd;
        }
        .status-dikirim {
            background-color: #f5f3ff;
            color: #6d28d9;
            border: 0.5px solid #c4b5fd;
        }
        .status-menunggu {
            background-color: #fef3c7;
            color: #b45309;
            border: 0.5px solid #fde68a;
        }
        .status-batal {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 0.5px solid #fca5a5;
        }
        .amount-col {
            text-align: right;
            font-weight: bold;
            white-space: nowrap;
            color: #0f172a;
        }
        .footer-table {
            width: 100%;
            margin-top: 14px;
            border-collapse: collapse;
        }
        .footer-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }
        .summary-card {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-collapse: collapse;
        }
        .summary-card td {
            padding: 4px 8px;
            border: 1px solid #cbd5e1;
            font-size: 8pt;
        }
    </style>
</head>
<body>
    {{-- Header Kop Surat --}}
    <table class="header-table">
        <tr>
            <td style="width: 25%; vertical-align: middle;">
                @if(file_exists(public_path('img/logo/logoKesamping.jpg')))
                    <img src="{{ public_path('img/logo/logoKesamping.jpg') }}" alt="CV Bintang Jaya Komputer" style="height: 34px; max-width: 160px; object-fit: contain;">
                @else
                    <strong style="font-size: 11pt; color: #2563eb;">BINTANG JAYA</strong>
                @endif
            </td>
            <td style="width: 75%; vertical-align: middle; text-align: right;">
                <div style="font-size: 12pt; font-weight: bold; color: #0f172a;">CV. BINTANG JAYA KOMPUTER</div>
                <div style="font-size: 7.2pt; color: #475569; margin-top: 1px;">
                    Pusat Penjualan Komputer, Laptop, Sparepart & Layanan Servis Resmi
                </div>
                <div style="font-size: 7.2pt; color: #64748b;">
                    Jl. Ahmad Yani No.68, Iringmulyo, Metro Timur, Kota Metro, Lampung | Telp: (0725) 45678
                </div>
            </td>
        </tr>
    </table>

    {{-- Judul Laporan --}}
    <div class="report-title">LAPORAN PENJUALAN BULANAN</div>
    <div class="report-subtitle">
        Bulan Periode: <strong>{{ $data['month'] }}</strong> &nbsp;•&nbsp; Tanggal Cetak: {{ now()->format('d/m/Y H:i') }} WIB
    </div>

    {{-- Tabel Laporan Lengkap --}}
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 4%; text-align: center;">No</th>
                <th style="width: 16%;">No. Invoice</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 16%;">Nama Pelanggan</th>
                <th style="width: 32%;">Rincian Barang &amp; Qty</th>
                <th style="width: 10%; text-align: center;">Status</th>
                <th style="width: 10%; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data['orders'] as $order)
                @php
                    $st = strtolower(trim($order->status));
                @endphp
                <tr>
                    <td style="text-align: center; color: #64748b;">{{ $loop->iteration }}</td>
                    <td>
                        <span class="invoice-no">{{ $order->invoice_number }}</span>
                    </td>
                    <td style="white-space: nowrap; color: #334155;">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        <strong>{{ $order->customer_display_name }}</strong>
                        @if ($order->customer_phone)
                            <div style="font-size: 6.8pt; color: #64748b;">{{ $order->customer_phone }}</div>
                        @endif
                    </td>
                    <td>
                        @if($order->items && $order->items->count() > 0)
                            @foreach ($order->items as $item)
                                <div class="item-row">
                                    • {{ $item->item_name ?? optional($item->product)->name }}
                                    <span class="item-qty">({{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }})</span>
                                </div>
                            @endforeach
                        @else
                            <span style="color: #94a3b8; font-style: italic;">Tidak ada rincian item</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if (in_array($st, ['selesai', 'lunas']))
                            <span class="status-badge status-selesai">Selesai</span>
                        @elseif ($st === 'diproses')
                            <span class="status-badge status-diproses">Diproses</span>
                        @elseif ($st === 'dikirim')
                            <span class="status-badge status-dikirim">Dikirim</span>
                        @elseif (in_array($st, ['menunggu konfirmasi', 'belum dibayar']))
                            <span class="status-badge status-menunggu">Menunggu</span>
                        @elseif (in_array($st, ['batal', 'dibatalkan']))
                            <span class="status-badge status-batal">Batal</span>
                        @else
                            <span class="status-badge">{{ $order->status }}</span>
                        @endif
                    </td>
                    <td class="amount-col">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #64748b;">
                        Tidak ada data transaksi pada bulan ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Ringkasan & Lembar Pengesahan --}}
    <table class="footer-table">
        <tr>
            <td style="width: 55%; padding-right: 20px;">
                <div style="font-size: 7.2pt; color: #64748b; line-height: 1.4;">
                    <strong>Catatan Laporan:</strong><br>
                    • Laporan ini memuat rekapitulasi data penjualan resmi CV Bintang Jaya Komputer.<br>
                    • Seluruh data terintegrasi langsung dengan database transaksi kasir dan pesanan online.<br>
                    • Dicetak secara otomatis oleh sistem pada {{ now()->format('d/m/Y H:i:s') }} WIB.
                </div>
            </td>
            <td style="width: 45%;">
                <table class="summary-card">
                    <tr style="background-color: #f8fafc;">
                        <td style="color: #475569; width: 50%;">Total Transaksi:</td>
                        <td style="text-align: right; font-weight: bold;">{{ $data['total_transactions'] }} Transaksi</td>
                    </tr>
                    <tr style="background-color: #eff6ff;">
                        <td style="color: #1e3a8a; font-weight: bold; width: 50%;">Total Penjualan:</td>
                        <td style="text-align: right; font-weight: bold; color: #1d4ed8; font-size: 8.5pt;">
                            Rp {{ number_format($data['total_sales'], 0, ',', '.') }}
                        </td>
                    </tr>
                </table>

                <div style="text-align: center; margin-top: 18px; font-size: 7.5pt; color: #334155;">
                    Kota Metro, {{ now()->translatedFormat('d F Y') }}<br>
                    <strong>Pimpinan CV. Bintang Jaya Komputer</strong>
                    <br><br><br><br>
                    <u>( ___________________________ )</u>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
