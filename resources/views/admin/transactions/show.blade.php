<x-admin-layout>
    @section('header_title', 'Detail Invoice Transaksi')

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules/transactions-show.module.css') }}">
    @endpush

    <div class="page-back-btn">
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat Transaksi
        </a>
    </div>

    <div class="grid grid-2-1">
        
        <!-- Left: Invoice Details & Items -->
        <div class="chart-card">
            <div class="invoice-header">
                <div>
                    <span class="text-secondary text-xs font-semibold invoice-number-label">Nomor Invoice</span>
                    <h2 class="font-bold invoice-number-val">{{ $order->invoice_number }}</h2>
                </div>
                <div class="invoice-date-block">
                    <span class="text-secondary text-xs font-semibold invoice-number-label">Tanggal Transaksi</span>
                    <div class="font-semibold">{{ $order->created_at->format('d F Y H:i') }}</div>
                </div>
            </div>

            <!-- Items Table -->
            <h3 class="font-bold mb-4 invoice-items-title">Daftar Barang Belanja</h3>
            <div class="table-container invoice-items-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th class="th-right">Harga Satuan</th>
                            <th class="th-center">Jumlah (Qty)</th>
                            <th class="th-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->item_name }}</strong>
                                    @if ($item->product_id)
                                        <div class="text-xs text-secondary">SKU: {{ $item->product->sku }}</div>
                                    @else
                                        <span class="badge badge-warning text-xs pos-item-badge-sm">Manual</span>
                                    @endif
                                </td>
                                <td class="td-right">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td class="td-center font-semibold">
                                    {{ $item->quantity }} pcs
                                </td>
                                <td class="td-right-bold">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Total Summary Info -->
            <div class="invoice-summary-wrap">
                <div class="invoice-summary-inner">
                    <div class="flex justify-between mb-2">
                        <span class="text-secondary">Subtotal:</span>
                        <span class="font-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between mb-4 invoice-total-row">
                        <span class="font-bold text-dark invoice-total-label">Total Akhir:</span>
                        <span class="font-bold text-primary invoice-total-val">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            
            @if ($order->notes)
                <div class="invoice-notes-box">
                    <strong class="invoice-notes-label"><i class="fa-solid fa-note-sticky mr-1"></i> Catatan Transaksi:</strong>
                    <p class="td-note-text">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Right: Actions and Status Info -->
        <div class="flex flex-col gap-6">
            <!-- Status Card -->
            <div class="chart-card">
                <h3 class="font-bold mb-4 invoice-items-title">Status Transaksi</h3>
                
                <div class="flex flex-col gap-4">
                    <div>
                        <div class="text-xs text-secondary mb-1">Status Pembayaran:</div>
                        @if ($order->status === 'Lunas')
                            <span class="badge badge-success invoice-status-badge">
                                <i class="fa-solid fa-circle-check mr-1"></i> LUNAS (Paid)
                            </span>
                        @elseif ($order->status === 'Belum Dibayar')
                            <span class="badge badge-warning invoice-status-badge">
                                <i class="fa-solid fa-clock mr-1"></i> BELUM DIBAYAR (Unpaid)
                            </span>
                        @else
                            <span class="badge badge-danger invoice-status-badge">
                                <i class="fa-solid fa-circle-xmark mr-1"></i> DIBATALKAN (Cancelled)
                            </span>
                        @endif
                    </div>

                    <div>
                        <div class="text-xs text-secondary mb-1">Pelanggan:</div>
                        <div class="font-bold">{{ $order->customer ? $order->customer->name : 'Guest (Walk-in)' }}</div>
                        @if ($order->customer)
                            <div class="text-xs text-secondary">{{ $order->customer->phone }}</div>
                            <div class="text-xs text-secondary">{{ $order->customer->address }}</div>
                        @endif
                    </div>

                    <div>
                        <div class="text-xs text-secondary mb-1">Kasir Pembuat:</div>
                        <div class="font-bold">{{ $order->user->name }}</div>
                        <div class="text-xs text-secondary">{{ $order->user->email }}</div>
                    </div>
                </div>
            </div>

            <!-- PDF Printing & Confirm Payment -->
            <div class="chart-card">
                <h3 class="font-bold mb-4 invoice-items-title">Cetak &amp; Tindakan</h3>
                
                <div class="flex flex-col gap-2">
                    @if ($order->status !== 'Dibatalkan')
                        <a href="{{ route('admin.transactions.invoice', $order->id) }}" class="btn btn-primary invoice-btn-full">
                            <i class="fa-solid fa-file-pdf"></i> Download Invoice PDF (A4)
                        </a>
                        <a href="{{ route('admin.transactions.nota', $order->id) }}" class="btn btn-secondary invoice-btn-full">
                            <i class="fa-solid fa-receipt text-success"></i> Download Nota Receipt (80mm)
                        </a>
                        
                        @if ($order->status === 'Belum Dibayar')
                            <div class="invoice-action-divider">
                                <form method="POST" action="{{ route('admin.transactions.pay', $order->id) }}">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label invoice-form-label-sm">Metode Bayar Konfirmasi:</label>
                                        <select name="payment_method" class="form-control">
                                            <option value="Cash">Cash (Tunai)</option>
                                            <option value="Transfer">Transfer Bank</option>
                                            <option value="QRIS">QRIS</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success invoice-btn-full">
                                        <i class="fa-solid fa-check"></i> Konfirmasi Telah Bayar (Lunas)
                                    </button>
                                </form>
                            </div>
                        @endif

                        <div class="invoice-action-divider">
                            <form method="POST" action="{{ route('admin.transactions.cancel', $order->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini? Stok barang akan dikembalikan secara otomatis.')">
                                @csrf
                                <button type="submit" class="btn btn-danger invoice-btn-full">
                                    <i class="fa-solid fa-ban"></i> Batalkan Transaksi (Refund)
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-secondary text-sm text-center invoice-cancelled-msg">
                            <i class="fa-solid fa-ban icon-cancelled"></i>
                            <p>Invoice dibatalkan. Tidak ada tindakan lanjutan yang tersedia.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>
