<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Preview Laporan - CV Bintang Jaya Komputer</title>
    <!-- FontAwesome & Modular CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/global-utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules/reports-preview.module.css') }}">
</head>
<body class="preview-body">
    <div class="container">
        
        <div class="preview-toolbar">
            <button onclick="window.close()" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-xmark"></i> Tutup Preview
            </button>
            
            <a href="{{ route('admin.reports.download', ['type' => $data['type'], 'param' => $data['param'] ?? '']) }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-file-arrow-down mr-1"></i> Download PDF
            </a>
        </div>

        <div class="preview-box">
            <!-- Header Kop Surat -->
            <div class="preview-header-section">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <img src="{{ asset('img/logo/logoKesamping.jpg') }}" alt="CV Bintang Jaya Komputer" style="height: 40px; max-width: 170px; object-fit: contain;">
                </div>
                <div style="text-align: right;">
                    <h2 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;">CV. BINTANG JAYA KOMPUTER</h2>
                    <p style="font-size: 0.78rem; color: #475569; margin: 2px 0 0 0;">Pusat Penjualan Komputer, Laptop, Sparepart &amp; Layanan Servis Resmi</p>
                    <p style="font-size: 0.75rem; color: #64748b; margin: 2px 0 0 0;">Jl. Ahmad Yani No.68, Iringmulyo, Metro Timur, Kota Metro, Lampung | Telp: (0725) 45678</p>
                </div>
            </div>

            <!-- Content depends on Report Type -->
            
            <!-- 1. Daily Report -->
            @if ($data['type'] === 'daily')
                <div class="preview-report-section">
                    <h3 class="font-bold text-center preview-report-title">LAPORAN PENJUALAN HARIAN</h3>
                    <p class="text-center text-secondary text-sm">Tanggal Laporan: {{ $data['date'] }}</p>
                </div>

                <div class="table-container preview-table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No. Invoice</th>
                                <th>Pelanggan & Rincian Produk</th>
                                <th>Kasir</th>
                                <th class="th-center">Status</th>
                                <th class="th-right">Total Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data['orders'] as $order)
                                <tr>
                                    <td><strong>{{ $order->invoice_number }}</strong></td>
                                    <td>
                                        <div class="font-semibold mb-1">{{ $order->customer_display_name }}</div>
                                        <ul style="margin: 0; padding-left: 15px; font-size: 11px; color: #475569;">
                                            @foreach ($order->items as $item)
                                                <li>{{ $item->item_name ?? optional($item->product)->name }} ({{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }})</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $order->cashier_display_name }}</td>
                                    <td class="td-center">
                                        <span class="badge {{ in_array($order->status, ['Lunas', 'Selesai']) ? 'badge-success' : (in_array($order->status, ['Diproses', 'Dikirim']) ? 'badge-info' : 'badge-warning') }}">{{ $order->status }}</span>
                                    </td>
                                    <td class="preview-td-amount">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="td-empty">Tidak ada transaksi pada tanggal ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="preview-summary-wrap">
                    <div class="preview-summary-inner">
                        <div class="flex justify-between mb-2">
                            <span>Jml Transaksi:</span>
                            <span class="font-semibold">{{ $data['total_transactions'] }}</span>
                        </div>
                        <div class="flex justify-between mb-2 preview-summary-total-row">
                            <span>Total Penjualan:</span>
                            <span class="preview-summary-total-val">Rp {{ number_format($data['total_sales'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 2. Monthly Report -->
            @if ($data['type'] === 'monthly')
                <div class="preview-report-section" style="margin-bottom: 16px;">
                    <h3 class="font-bold text-center preview-report-title" style="letter-spacing: 0.5px;">LAPORAN PENJUALAN BULANAN</h3>
                    <p class="text-center text-secondary text-sm" style="margin-top: 4px;">
                        Bulan Periode: <strong>{{ $data['month'] }}</strong> &nbsp;•&nbsp; Tanggal Cetak: {{ now()->format('d/m/Y H:i') }} WIB
                    </p>
                </div>

                <div class="table-responsive" style="margin-bottom: 20px;">
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
                                    <td style="text-align: center; color: var(--secondary);">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="preview-invoice-no">{{ $order->invoice_number }}</span>
                                    </td>
                                    <td style="white-space: nowrap; color: var(--dark);">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: var(--dark);">{{ $order->customer_display_name }}</div>
                                        @if ($order->customer_phone)
                                            <div style="font-size: 0.7rem; color: var(--secondary); margin-top: 1px;">
                                                <i class="fa-solid fa-phone me-1" style="font-size: 0.65rem;"></i>{{ $order->customer_phone }}
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
                                            <span style="color: var(--secondary); font-style: italic;">Tidak ada rincian item</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        @if (in_array($st, ['selesai', 'lunas']))
                                            <span class="preview-status-badge preview-status-selesai">Selesai</span>
                                        @elseif ($st === 'diproses')
                                            <span class="preview-status-badge preview-status-diproses">Diproses</span>
                                        @elseif ($st === 'dikirim')
                                            <span class="preview-status-badge preview-status-dikirim">Dikirim</span>
                                        @elseif (in_array($st, ['menunggu konfirmasi', 'belum dibayar']))
                                            <span class="preview-status-badge preview-status-menunggu">Menunggu</span>
                                        @elseif (in_array($st, ['batal', 'dibatalkan']))
                                            <span class="preview-status-badge preview-status-batal">Batal</span>
                                        @else
                                            <span class="preview-status-badge">{{ $order->status }}</span>
                                        @endif
                                    </td>
                                    <td style="text-align: right; font-weight: 700; color: var(--dark); white-space: nowrap;">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 24px; color: var(--secondary);">
                                        Tidak ada transaksi pada bulan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Summary & Catatan Laporan --}}
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; margin-top: 24px;">
                    <div style="flex: 1; font-size: 0.75rem; color: var(--secondary); line-height: 1.5;">
                        <strong style="color: var(--dark);">Catatan Laporan:</strong><br>
                        • Laporan ini memuat rekapitulasi data penjualan resmi CV Bintang Jaya Komputer.<br>
                        • Seluruh data terintegrasi langsung dengan database transaksi kasir dan pesanan online.<br>
                        • Dicetak secara otomatis oleh sistem pada {{ now()->format('d/m/Y H:i:s') }} WIB.
                    </div>
                    <div style="width: 320px;">
                        <table style="width: 100%; border-collapse: collapse; border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden;">
                            <tr style="background-color: #f8fafc;">
                                <td style="padding: 8px 12px; border: 1px solid #cbd5e1; font-size: 0.8rem; color: #475569;">Total Transaksi:</td>
                                <td style="padding: 8px 12px; border: 1px solid #cbd5e1; font-size: 0.8rem; font-weight: 700; text-align: right;">{{ $data['total_transactions'] }} Transaksi</td>
                            </tr>
                            <tr style="background-color: #eff6ff;">
                                <td style="padding: 10px 12px; border: 1px solid #cbd5e1; font-size: 0.85rem; font-weight: 700; color: #1e3a8a;">Total Penjualan:</td>
                                <td style="padding: 10px 12px; border: 1px solid #cbd5e1; font-size: 0.95rem; font-weight: 800; color: #1d4ed8; text-align: right;">Rp {{ number_format($data['total_sales'], 0, ',', '.') }}</td>
                            </tr>
                        </table>

                        <div style="text-align: center; margin-top: 24px; font-size: 0.75rem; color: #334155;">
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
                    <h3 class="font-bold text-center preview-report-title">LAPORAN PERSEDIAAN STOK BARANG GUDANG</h3>
                    <p class="text-center text-secondary text-sm">Dicetak Pada: {{ now()->format('d F Y H:i') }}</p>
                </div>

                <div class="table-container preview-table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Barang / SKU</th>
                                <th>Merk</th>
                                <th>Supplier</th>
                                <th class="th-right">Harga Modal</th>
                                <th class="th-center">Min. Stok</th>
                                <th class="th-center">Sisa Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['products'] as $product)
                                <tr>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        <div class="text-xs text-secondary">SKU: {{ $product->sku }}</div>
                                    </td>
                                    <td>{{ optional($product->brand)->name }}</td>
                                    <td>{{ optional($product->supplier)->name }}</td>
                                    <td class="td-right">Rp {{ number_format($product->price_modal, 0, ',', '.') }}</td>
                                    <td class="td-center">{{ $product->min_stock }}</td>
                                    <td class="{{ $product->stock <= $product->min_stock ? 'preview-td-stock-danger' : 'preview-td-stock-normal' }}">
                                        {{ $product->stock }} pcs
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="preview-summary-wrap">
                    <div class="preview-summary-inner">
                        <div class="flex justify-between mb-2">
                            <span>Total Unit Stok:</span>
                            <span class="font-semibold">{{ $data['total_stock'] }} unit</span>
                        </div>
                        <div class="flex justify-between mb-2 preview-summary-total-row">
                            <span>Total Nilai Aset:</span>
                            <span class="preview-summary-total-val">Rp {{ number_format($data['total_value'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 4. Return Report -->
            @if ($data['type'] === 'return')
                <div class="preview-report-section">
                    <h3 class="font-bold text-center preview-report-title">LAPORAN RETUR BARANG RUSAK / RETUR PENJUALAN</h3>
                    <p class="text-center text-secondary text-sm">Dicetak Pada: {{ now()->format('d F Y H:i') }}</p>
                </div>

                <div class="table-container preview-table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nomor Invoice</th>
                                <th>Nama Barang</th>
                                <th class="th-center">Qty</th>
                                <th>Alasan Kerusakan</th>
                                <th class="th-center">Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data['returns'] as $ret)
                                <tr>
                                    <td><strong>{{ optional($ret->order)->invoice_number }}</strong></td>
                                    <td>{{ optional($ret->product)->name }}</td>
                                    <td class="preview-td-qty">{{ $ret->quantity }} pcs</td>
                                    <td class="preview-td-reason">{{ $ret->reason }}</td>
                                    <td class="td-center">
                                        <span class="badge {{ $ret->status === 'Disetujui' ? 'badge-success' : 'badge-danger' }}">{{ $ret->status }}</span>
                                    </td>
                                    <td>{{ optional($ret->date)->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="td-empty">Tidak ada data retur.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- 5. Top Products Report -->
            @if ($data['type'] === 'top_products')
                <div class="preview-report-section">
                    <h3 class="font-bold text-center preview-report-title">LAPORAN PRODUK TERLARIS</h3>
                    <p class="text-center text-secondary text-sm">Dicetak Pada: {{ now()->format('d F Y H:i') }}</p>
                </div>

                <div class="table-container preview-table-container">
                    <table>
                        <thead>
                            <tr>
                                <th class="preview-th-rank">Peringkat</th>
                                <th>Nama Barang</th>
                                <th class="th-center">Total Unit Terjual</th>
                                <th class="th-right">Total Nilai Penjualan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data['products'] as $index => $item)
                                <tr>
                                    <td class="preview-td-rank">#{{ $index + 1 }}</td>
                                    <td><strong>{{ $item->item_name }}</strong></td>
                                    <td class="preview-td-qty">{{ $item->total_qty }} pcs</td>
                                    <td class="preview-td-sales">
                                        Rp {{ number_format($item->total_sales, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="td-empty">Tidak ada data penjualan produk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

        </div>

    </div>
</body>
</html>