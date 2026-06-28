<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Preview Laporan - CV Bintang Jaya Komputer</title>
    <!-- FontAwesome & Style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body { background-color: #f1f5f9; padding: 40px 0; }
        .preview-box {
            background-color: var(--white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-lg);
            padding: 40px;
            max-width: 900px;
            margin: 0 auto;
        }
        .header-section {
            border-bottom: 2px solid var(--dark);
            padding-bottom: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <div style="max-width: 900px; margin: 0 auto 20px auto; display: flex; justify-content: space-between;">
            <button onclick="window.close()" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-xmark"></i> Tutup Preview
            </button>
            
            <a href="{{ route('admin.reports.download', ['type' => $data['type'], 'param' => $data['param'] ?? '']) }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-file-arrow-down mr-1"></i> Download PDF
            </a>
        </div>

        <div class="preview-box">
            <!-- Header -->
            <div class="header-section">
                <div>
                    <h1 class="font-bold" style="font-size: 1.5rem; text-transform: uppercase;">CV BINTANG JAYA KOMPUTER</h1>
                    <p style="font-size: 0.85rem; color: var(--secondary);">Jl. Ahmad Yani No.68, Iringmulyo, Kota Metro, Lampung | Telp: (0725) 45678</p>
                </div>
                <div style="text-align: right;">
                    <span class="badge badge-info" style="font-size: 0.875rem;">Laporan Preview</span>
                </div>
            </div>

            <!-- Content depends on Report Type -->
            
            <!-- 1. Daily Report -->
            @if ($data['type'] === 'daily')
                <div style="margin-bottom: 24px;">
                    <h3 class="font-bold text-center" style="font-size: 1.25rem;">LAPORAN PENJUALAN HARIAN</h3>
                    <p class="text-center text-secondary text-sm">Tanggal Laporan: {{ $data['date'] }}</p>
                </div>

                <div class="table-container" style="box-shadow: none; border-radius: var(--radius-sm);">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th>No. Invoice</th>
                                <th>Pelanggan</th>
                                <th>Kasir</th>
                                <th style="text-align: center;">Status</th>
                                <th style="text-align: right;">Total Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data['orders'] as $order)
                                <tr>
                                    <td><strong>{{ $order->invoice_number }}</strong></td>
                                    <td>{{ $order->customer ? $order->customer->name : 'Guest' }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td style="text-align: center;">
                                        <span class="badge {{ $order->status === 'Lunas' ? 'badge-success' : 'badge-warning' }}">{{ $order->status }}</span>
                                    </td>
                                    <td style="text-align: right; font-weight: 600; color: var(--primary);">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 24px; color: var(--secondary);">Tidak ada transaksi pada tanggal ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
                    <div style="width: 320px; border-top: 1px solid var(--border); padding-top: 16px;">
                        <div class="flex justify-between mb-2">
                            <span>Jml Transaksi:</span>
                            <span class="font-semibold">{{ $data['total_transactions'] }}</span>
                        </div>
                        <div class="flex justify-between mb-2" style="font-size: 1.15rem; font-weight: 700;">
                            <span>Total Penjualan:</span>
                            <span style="color: var(--primary);">Rp {{ number_format($data['total_sales'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 2. Monthly Report -->
            @if ($data['type'] === 'monthly')
                <div style="margin-bottom: 24px;">
                    <h3 class="font-bold text-center" style="font-size: 1.25rem;">LAPORAN PENJUALAN BULANAN</h3>
                    <p class="text-center text-secondary text-sm">Bulan Laporan: {{ $data['month'] }}</p>
                </div>

                <div class="table-container" style="box-shadow: none; border-radius: var(--radius-sm);">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th>No. Invoice</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th style="text-align: center;">Status</th>
                                <th style="text-align: right;">Total Belanja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data['orders'] as $order)
                                <tr>
                                    <td><strong>{{ $order->invoice_number }}</strong></td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $order->customer ? $order->customer->name : 'Guest' }}</td>
                                    <td style="text-align: center;">
                                        <span class="badge {{ $order->status === 'Lunas' ? 'badge-success' : 'badge-warning' }}">{{ $order->status }}</span>
                                    </td>
                                    <td style="text-align: right; font-weight: 600; color: var(--primary);">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 24px; color: var(--secondary);">Tidak ada transaksi pada bulan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
                    <div style="width: 320px; border-top: 1px solid var(--border); padding-top: 16px;">
                        <div class="flex justify-between mb-2">
                            <span>Jml Transaksi:</span>
                            <span class="font-semibold">{{ $data['total_transactions'] }}</span>
                        </div>
                        <div class="flex justify-between mb-2" style="font-size: 1.15rem; font-weight: 700;">
                            <span>Total Penjualan:</span>
                            <span style="color: var(--primary);">Rp {{ number_format($data['total_sales'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 3. Stock Report -->
            @if ($data['type'] === 'stock')
                <div style="margin-bottom: 24px;">
                    <h3 class="font-bold text-center" style="font-size: 1.25rem;">LAPORAN PERSERDIAAN STOK BARANG GUDANG</h3>
                    <p class="text-center text-secondary text-sm">Dicetak Pada: {{ now()->format('d F Y H:i') }}</p>
                </div>

                <div class="table-container" style="box-shadow: none; border-radius: var(--radius-sm);">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Nama Barang / SKU</th>
                                <th>Merk</th>
                                <th>Supplier</th>
                                <th style="text-align: right;">Harga Modal</th>
                                <th style="text-align: center;">Min. Stok</th>
                                <th style="text-align: center;">Sisa Stok</th>
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
                                    <td style="text-align: right;">Rp {{ number_format($product->price_modal, 0, ',', '.') }}</td>
                                    <td style="text-align: center;">{{ $product->min_stock }}</td>
                                    <td style="text-align: center; font-weight: 700; color: {{ $product->stock <= $product->min_stock ? 'var(--danger)' : 'var(--dark)' }};">
                                        {{ $product->stock }} pcs
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
                    <div style="width: 320px; border-top: 1px solid var(--border); padding-top: 16px;">
                        <div class="flex justify-between mb-2">
                            <span>Total Unit Stok:</span>
                            <span class="font-semibold">{{ $data['total_stock'] }} unit</span>
                        </div>
                        <div class="flex justify-between mb-2" style="font-size: 1.15rem; font-weight: 700;">
                            <span>Total Nilai Aset:</span>
                            <span style="color: var(--primary);">Rp {{ number_format($data['total_value'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 4. Return Report -->
            @if ($data['type'] === 'return')
                <div style="margin-bottom: 24px;">
                    <h3 class="font-bold text-center" style="font-size: 1.25rem;">LAPORAN RETUR BARANG RUSAK / RETUR PENJUALAN</h3>
                    <p class="text-center text-secondary text-sm">Dicetak Pada: {{ now()->format('d F Y H:i') }}</p>
                </div>

                <div class="table-container" style="box-shadow: none; border-radius: var(--radius-sm);">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Nomor Invoice</th>
                                <th>Nama Barang</th>
                                <th style="text-align: center;">Qty</th>
                                <th>Alasan Kerusakan</th>
                                <th style="text-align: center;">Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data['returns'] as $ret)
                                <tr>
                                    <td><strong>{{ $ret->order->invoice_number }}</strong></td>
                                    <td>{{ $ret->product->name }}</td>
                                    <td style="text-align: center; font-weight: 600;">{{ $ret->quantity }} pcs</td>
                                    <td style="font-size: 0.85rem;">{{ $ret->reason }}</td>
                                    <td style="text-align: center;">
                                        <span class="badge {{ $ret->status === 'Disetujui' ? 'badge-success' : 'badge-danger' }}">{{ $ret->status }}</span>
                                    </td>
                                    <td>{{ $ret->date->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 24px; color: var(--secondary);">Tidak ada data retur.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- 5. Top Products Report -->
            @if ($data['type'] === 'top_products')
                <div style="margin-bottom: 24px;">
                    <h3 class="font-bold text-center" style="font-size: 1.25rem;">LAPORAN PRODUK TERLARIS</h3>
                    <p class="text-center text-secondary text-sm">Dicetak Pada: {{ now()->format('d F Y H:i') }}</p>
                </div>

                <div class="table-container" style="box-shadow: none; border-radius: var(--radius-sm);">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Peringkat</th>
                                <th>Nama Barang</th>
                                <th style="text-align: center;">Total Unit Terjual</th>
                                <th style="text-align: right;">Total Nilai Penjualan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data['products'] as $index => $item)
                                <tr>
                                    <td style="text-align: center; font-weight: 800; color: var(--primary);">#{{ $index + 1 }}</td>
                                    <td><strong>{{ $item->item_name }}</strong></td>
                                    <td style="text-align: center; font-weight: 700;">{{ $item->total_qty }} pcs</td>
                                    <td style="text-align: right; font-weight: 700; color: var(--success);">
                                        Rp {{ number_format($item->total_sales, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 24px; color: var(--secondary);">Tidak ada data penjualan produk.</td>
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
