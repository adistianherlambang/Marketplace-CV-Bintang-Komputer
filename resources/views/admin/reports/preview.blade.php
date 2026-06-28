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
            <!-- Header -->
            <div class="preview-header-section">
                <div>
                    <h1 class="font-bold preview-company-name">CV BINTANG JAYA KOMPUTER</h1>
                    <p class="preview-company-address">Jl. Ahmad Yani No.68, Iringmulyo, Kota Metro, Lampung | Telp: (0725) 45678</p>
                </div>
                <div class="td-right">
                    <span class="badge badge-info preview-badge-lg">Laporan Preview</span>
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
                                <th>Pelanggan</th>
                                <th>Kasir</th>
                                <th class="th-center">Status</th>
                                <th class="th-right">Total Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data['orders'] as $order)
                                <tr>
                                    <td><strong>{{ $order->invoice_number }}</strong></td>
                                    <td>{{ $order->customer ? $order->customer->name : 'Guest' }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td class="td-center">
                                        <span class="badge {{ $order->status === 'Lunas' ? 'badge-success' : 'badge-warning' }}">{{ $order->status }}</span>
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
                <div class="preview-report-section">
                    <h3 class="font-bold text-center preview-report-title">LAPORAN PENJUALAN BULANAN</h3>
                    <p class="text-center text-secondary text-sm">Bulan Laporan: {{ $data['month'] }}</p>
                </div>

                <div class="table-container preview-table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No. Invoice</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th class="th-center">Status</th>
                                <th class="th-right">Total Belanja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data['orders'] as $order)
                                <tr>
                                    <td><strong>{{ $order->invoice_number }}</strong></td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $order->customer ? $order->customer->name : 'Guest' }}</td>
                                    <td class="td-center">
                                        <span class="badge {{ $order->status === 'Lunas' ? 'badge-success' : 'badge-warning' }}">{{ $order->status }}</span>
                                    </td>
                                    <td class="preview-td-amount">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="td-empty">Tidak ada transaksi pada bulan ini.</td>
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

            <!-- 3. Stock Report -->
            @if ($data['type'] === 'stock')
                <div class="preview-report-section">
                    <h3 class="font-bold text-center preview-report-title">LAPORAN PERSERDIAAN STOK BARANG GUDANG</h3>
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
                                    <td>{{ $product->brand->name }}</td>
                                    <td>{{ $product->supplier->name }}</td>
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
                                    <td><strong>{{ $ret->order->invoice_number }}</strong></td>
                                    <td>{{ $ret->product->name }}</td>
                                    <td class="preview-td-qty">{{ $ret->quantity }} pcs</td>
                                    <td class="preview-td-reason">{{ $ret->reason }}</td>
                                    <td class="td-center">
                                        <span class="badge {{ $ret->status === 'Disetujui' ? 'badge-success' : 'badge-danger' }}">{{ $ret->status }}</span>
                                    </td>
                                    <td>{{ $ret->date->format('d/m/Y') }}</td>
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
