<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Preview Laporan - CV Bintang Jaya Komputer</title>
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/modules/reports-preview.module.css') }}">
</head>
<body class="preview-body">

    <!-- Top Action Toolbar -->
    <div class="preview-toolbar">
        <div class="preview-toolbar-left">
            <a href="{{ route('admin.reports.index') }}" class="preview-btn preview-btn-back">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Rekap Laporan
            </a>
            <span class="preview-nav-indicator">
                <i class="fa-solid fa-file-invoice text-primary"></i> Preview Dokumen
            </span>
        </div>
        
        <div class="preview-toolbar-right">
            <button type="button" onclick="window.print()" class="preview-btn preview-btn-print">
                <i class="fa-solid fa-print"></i> Cetak Dokumen
            </button>
            <a href="{{ route('admin.reports.download', ['type' => $data['type'], 'param' => $data['param'] ?? '']) }}" class="preview-btn preview-btn-download">
                <i class="fa-solid fa-file-pdf"></i> Download PDF
            </a>
        </div>
    </div>

    <!-- Printable Document Sheet Box -->
    <div class="preview-box">
        <!-- Header Kop Surat Resmi -->
        <div class="preview-header-section">
            <div class="preview-logo-wrapper">
                @if(file_exists(public_path('img/logo/logoKesamping.jpg')))
                    <img src="{{ asset('img/logo/logoKesamping.jpg') }}" alt="CV Bintang Jaya Komputer">
                @else
                    <strong style="font-size: 1.25rem; font-weight: 800; color: #2563eb;">BINTANG JAYA</strong>
                @endif
            </div>
            <div class="preview-company-info">
                <h2 class="preview-company-title">CV. BINTANG JAYA KOMPUTER</h2>
                <p class="preview-company-sub">Pusat Penjualan Komputer, Laptop, Sparepart &amp; Layanan Servis Resmi</p>
                <p class="preview-company-address">Jl. Ahmad Yani No.68, Iringmulyo, Metro Timur, Kota Metro, Lampung | Telp: (0725) 45678</p>
            </div>
        </div>

        <!-- 1. Daily Report -->
        @if ($data['type'] === 'daily')
            <div class="preview-report-section">
                <h3 class="preview-report-title">LAPORAN PENJUALAN HARIAN</h3>
                <p class="preview-report-meta">
                    Tanggal Laporan: <strong>{{ $data['date'] }}</strong> &nbsp;•&nbsp; Dicetak pada: {{ now()->format('d/m/Y H:i') }} WIB
                </p>
            </div>

            <div style="overflow-x: auto; margin-bottom: 20px;">
                <table class="preview-table-compact">
                    <thead>
                        <tr>
                            <th style="width: 5%; text-align: center;">No</th>
                            <th style="width: 20%;">No. Invoice</th>
                            <th style="width: 35%;">Pelanggan &amp; Rincian Barang</th>
                            <th style="width: 15%;">Kasir</th>
                            <th style="width: 10%; text-align: center;">Status</th>
                            <th style="width: 15%; text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data['orders'] as $order)
                            <tr>
                                <td style="text-align: center; color: #64748b;">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="preview-invoice-no">{{ $order->invoice_number }}</span>
                                </td>
                                <td>
                                    <div class="preview-customer-name">{{ $order->customer_display_name }}</div>
                                    @if($order->items && $order->items->count() > 0)
                                        <div style="margin-top: 3px;">
                                            @foreach ($order->items as $item)
                                                <div class="preview-item-row">
                                                    • {{ $item->item_name ?? optional($item->product)->name }}
                                                    <span class="preview-item-qty">({{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }})</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $order->cashier_display_name }}</td>
                                <td class="td-status-text">{{ ucfirst($order->status) }}</td>
                                <td class="td-amount-text">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 24px; color: #64748b;">
                                    Tidak ada data transaksi pada tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="preview-footer-wrap">
                <div class="preview-notes-box">
                    <strong>Catatan Laporan:</strong><br>
                    • Laporan ini memuat data transaksi harian resmi CV Bintang Jaya Komputer.<br>
                    • Dicetak secara otomatis oleh sistem pada {{ now()->format('d/m/Y H:i:s') }} WIB.
                </div>
                <div class="preview-summary-container">
                    <table class="preview-summary-card">
                        <tr style="background-color: #f8fafc;">
                            <td class="preview-summary-label">Total Transaksi:</td>
                            <td class="preview-summary-val">{{ $data['total_transactions'] }} Transaksi</td>
                        </tr>
                        <tr style="background-color: #eff6ff;">
                            <td class="preview-summary-total-label">Total Penjualan:</td>
                            <td class="preview-summary-total-val">Rp {{ number_format($data['total_sales'], 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        @endif

        <!-- 2. Monthly Report -->
        @if ($data['type'] === 'monthly')
            <div class="preview-report-section">
                <h3 class="preview-report-title">LAPORAN PENJUALAN BULANAN</h3>
                <p class="preview-report-meta">
                    Bulan Periode: <strong>{{ $data['month'] }}</strong> &nbsp;•&nbsp; Tanggal Cetak: {{ now()->format('d/m/Y H:i') }} WIB
                </p>
            </div>

            <div style="overflow-x: auto; margin-bottom: 20px;">
                <table class="preview-table-compact">
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
                                    <span class="preview-invoice-no">{{ $order->invoice_number }}</span>
                                </td>
                                <td style="white-space: nowrap; color: #334155;">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    <div class="preview-customer-name">{{ $order->customer_display_name }}</div>
                                    @if ($order->customer_phone)
                                        <div class="preview-customer-phone">
                                            <i class="fa-solid fa-phone" style="font-size: 0.65rem;"></i> {{ $order->customer_phone }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($order->items && $order->items->count() > 0)
                                        @foreach ($order->items as $item)
                                            <div class="preview-item-row">
                                                • {{ $item->item_name ?? optional($item->product)->name }}
                                                <span class="preview-item-qty">({{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }})</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <span style="color: #94a3b8; font-style: italic;">Tidak ada rincian item</span>
                                    @endif
                                </td>
                                <td class="td-status-text">
                                    @if (in_array($st, ['selesai', 'lunas']))
                                        Selesai
                                    @elseif ($st === 'diproses')
                                        Diproses
                                    @elseif ($st === 'dikirim')
                                        Dikirim
                                    @elseif (in_array($st, ['menunggu konfirmasi', 'belum dibayar']))
                                        Menunggu
                                    @elseif (in_array($st, ['batal', 'dibatalkan']))
                                        Batal
                                    @else
                                        {{ ucfirst($order->status) }}
                                    @endif
                                </td>
                                <td class="td-amount-text">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 28px; color: #64748b;">
                                    Tidak ada data transaksi pada bulan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Summary & Lembar Pengesahan -->
            <div class="preview-footer-wrap">
                <div class="preview-notes-box">
                    <strong>Catatan Laporan:</strong><br>
                    • Laporan ini memuat rekapitulasi data penjualan resmi CV Bintang Jaya Komputer.<br>
                    • Seluruh data terintegrasi langsung dengan database transaksi kasir dan pesanan online.<br>
                    • Dicetak secara otomatis oleh sistem pada {{ now()->format('d/m/Y H:i:s') }} WIB.
                </div>
                <div class="preview-summary-container">
                    <table class="preview-summary-card">
                        <tr style="background-color: #f8fafc;">
                            <td class="preview-summary-label">Total Transaksi:</td>
                            <td class="preview-summary-val">{{ $data['total_transactions'] }} Transaksi</td>
                        </tr>
                        <tr style="background-color: #eff6ff;">
                            <td class="preview-summary-total-label">Total Penjualan:</td>
                            <td class="preview-summary-total-val">Rp {{ number_format($data['total_sales'], 0, ',', '.') }}</td>
                        </tr>
                    </table>

                    <div class="preview-signature-box">
                        Kota Metro, {{ now()->translatedFormat('d F Y') }}<br>
                        <strong>Pimpinan CV. Bintang Jaya Komputer</strong>
                        <br><br><br><br>
                        <u>( ___________________________ )</u>
                    </div>
                </div>
            </div>
        @endif

        <!-- 3. Stock Report -->
        @if ($data['type'] === 'stock')
            <div class="preview-report-section">
                <h3 class="preview-report-title">LAPORAN PERSEDIAAN STOK BARANG GUDANG</h3>
                <p class="preview-report-meta">Dicetak Pada: {{ now()->format('d F Y H:i') }} WIB</p>
            </div>

            <div style="overflow-x: auto; margin-bottom: 20px;">
                <table class="preview-table-compact">
                    <thead>
                        <tr>
                            <th style="width: 5%; text-align: center;">No</th>
                            <th style="width: 35%;">Nama Barang / SKU</th>
                            <th style="width: 15%;">Merk</th>
                            <th style="width: 15%;">Supplier</th>
                            <th style="width: 15%; text-align: right;">Harga Modal</th>
                            <th style="width: 7%; text-align: center;">Min.</th>
                            <th style="width: 8%; text-align: center;">Sisa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['products'] as $product)
                            <tr>
                                <td style="text-align: center; color: #64748b;">{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    <div style="font-size: 0.7rem; color: #64748b;">SKU: {{ $product->sku }}</div>
                                </td>
                                <td>{{ optional($product->brand)->name ?? '-' }}</td>
                                <td>{{ optional($product->supplier)->name ?? '-' }}</td>
                                <td style="text-align: right; font-weight: 600;">Rp {{ number_format($product->price_modal, 0, ',', '.') }}</td>
                                <td style="text-align: center;">{{ $product->min_stock }}</td>
                                <td style="text-align: center; font-weight: 700; color: {{ $product->stock <= $product->min_stock ? '#dc2626' : '#1e293b' }};">
                                    {{ $product->stock }} pcs
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="preview-footer-wrap">
                <div class="preview-notes-box">
                    <strong>Catatan Stok Gudang:</strong><br>
                    • Data persediaan stok diperbarui secara berkala berdasarkan mutasi stok gudang dan penjualan kasir.
                </div>
                <div class="preview-summary-container">
                    <table class="preview-summary-card">
                        <tr style="background-color: #f8fafc;">
                            <td class="preview-summary-label">Total Unit Stok:</td>
                            <td class="preview-summary-val">{{ $data['total_stock'] }} unit</td>
                        </tr>
                        <tr style="background-color: #eff6ff;">
                            <td class="preview-summary-total-label">Total Nilai Aset:</td>
                            <td class="preview-summary-total-val">Rp {{ number_format($data['total_value'], 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        @endif

        <!-- 4. Return Report -->
        @if ($data['type'] === 'return')
            <div class="preview-report-section">
                <h3 class="preview-report-title">LAPORAN RETUR BARANG RUSAK / RETUR PENJUALAN</h3>
                <p class="preview-report-meta">Dicetak Pada: {{ now()->format('d F Y H:i') }} WIB</p>
            </div>

            <div style="overflow-x: auto; margin-bottom: 20px;">
                <table class="preview-table-compact">
                    <thead>
                        <tr>
                            <th style="width: 5%; text-align: center;">No</th>
                            <th style="width: 20%;">Nomor Invoice</th>
                            <th style="width: 25%;">Nama Barang</th>
                            <th style="width: 8%; text-align: center;">Qty</th>
                            <th style="width: 24%;">Alasan Kerusakan</th>
                            <th style="width: 10%; text-align: center;">Status</th>
                            <th style="width: 8%; text-align: center;">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data['returns'] as $ret)
                            <tr>
                                <td style="text-align: center; color: #64748b;">{{ $loop->iteration }}</td>
                                <td><strong>{{ optional($ret->order)->invoice_number ?? '-' }}</strong></td>
                                <td>{{ optional($ret->product)->name ?? '-' }}</td>
                                <td style="text-align: center; font-weight: 700;">{{ $ret->quantity }} pcs</td>
                                <td style="font-size: 0.74rem;">{{ $ret->reason }}</td>
                                <td class="td-status-text">{{ $ret->status }}</td>
                                <td style="text-align: center; font-size: 0.72rem;">{{ optional($ret->date)->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 24px; color: #64748b;">
                                    Tidak ada data retur barang.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        <!-- 5. Top Products Report -->
        @if ($data['type'] === 'top_products')
            <div class="preview-report-section">
                <h3 class="preview-report-title">LAPORAN PRODUK TERLARIS</h3>
                <p class="preview-report-meta">Dicetak Pada: {{ now()->format('d F Y H:i') }} WIB</p>
            </div>

            <div style="overflow-x: auto; margin-bottom: 20px;">
                <table class="preview-table-compact">
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
                                <td style="text-align: center; font-weight: 800; color: #2563eb;">#{{ $index + 1 }}</td>
                                <td><strong>{{ $item->item_name }}</strong></td>
                                <td style="text-align: center; font-weight: 700;">{{ $item->total_qty }} pcs</td>
                                <td style="text-align: right; font-weight: 700; color: #15803d;">
                                    Rp {{ number_format($item->total_sales, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 24px; color: #64748b;">
                                    Tidak ada data penjualan produk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

    </div>

</body>
</html>