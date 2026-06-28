<x-admin-layout>
    @section('header_title', 'Detail Invoice Transaksi')

    <div style="margin-bottom: 24px;">
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat Transaksi
        </a>
    </div>

    <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
        
        <!-- Left: Invoice Details & Items -->
        <div class="chart-card">
            <div style="display: flex; justify-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 16px; margin-bottom: 20px;">
                <div>
                    <span class="text-secondary text-xs font-semibold" style="text-transform: uppercase;">Nomor Invoice</span>
                    <h2 class="font-bold" style="font-size: 1.5rem; color: var(--dark);">{{ $order->invoice_number }}</h2>
                </div>
                <div style="text-align: right; margin-left: auto;">
                    <span class="text-secondary text-xs font-semibold" style="text-transform: uppercase;">Tanggal Transaksi</span>
                    <div class="font-semibold">{{ $order->created_at->format('d F Y H:i') }}</div>
                </div>
            </div>

            <!-- Items Table -->
            <h3 class="font-bold mb-4" style="font-size: 1rem;">Daftar Barang Belanja</h3>
            <div class="table-container" style="box-shadow: none; border-radius: var(--radius-sm); margin-bottom: 24px;">
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th style="text-align: right;">Harga Satuan</th>
                            <th style="text-align: center;">Jumlah (Qty)</th>
                            <th style="text-align: right;">Subtotal</th>
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
                                        <span class="badge badge-warning text-xs" style="font-size: 0.6rem;">Manual</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td style="text-align: center; font-weight: 600;">
                                    {{ $item->quantity }} pcs
                                </td>
                                <td style="text-align: right; font-weight: 700; color: var(--primary);">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Total Summary Info -->
            <div style="display: flex; justify-content: flex-end; padding-top: 16px; border-top: 1px solid var(--border);">
                <div style="width: 300px;">
                    <div class="flex justify-between mb-2">
                        <span class="text-secondary">Subtotal:</span>
                        <span class="font-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between mb-4" style="border-top: 1px solid var(--border); padding-top: 12px;">
                        <span class="font-bold text-dark" style="font-size: 1.1rem;">Total Akhir:</span>
                        <span class="font-bold text-primary" style="font-size: 1.25rem;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            
            @if ($order->notes)
                <div style="background-color: var(--light); padding: 16px; border-radius: var(--radius); border: 1px solid var(--border); margin-top: 20px;">
                    <strong style="font-size: 0.875rem;"><i class="fa-solid fa-note-sticky mr-1"></i> Catatan Transaksi:</strong>
                    <p style="font-size: 0.9rem; color: #475569; margin-top: 4px;">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Right: Actions and Status Info -->
        <div class="flex flex-col gap-6">
            <!-- Status Card -->
            <div class="chart-card">
                <h3 class="font-bold mb-4" style="font-size: 1rem;">Status Transaksi</h3>
                
                <div class="flex flex-col gap-4">
                    <div>
                        <div class="text-xs text-secondary mb-1">Status Pembayaran:</div>
                        @if ($order->status === 'Lunas')
                            <span class="badge badge-success" style="font-size: 0.9rem; padding: 6px 12px; width: 100%; justify-content: center;">
                                <i class="fa-solid fa-circle-check mr-1"></i> LUNAS (Paid)
                            </span>
                        @elseif ($order->status === 'Belum Dibayar')
                            <span class="badge badge-warning" style="font-size: 0.9rem; padding: 6px 12px; width: 100%; justify-content: center;">
                                <i class="fa-solid fa-clock mr-1"></i> BELUM DIBAYAR (Unpaid)
                            </span>
                        @else
                            <span class="badge badge-danger" style="font-size: 0.9rem; padding: 6px 12px; width: 100%; justify-content: center;">
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
                <h3 class="font-bold mb-4" style="font-size: 1rem;">Cetak & Tindakan</h3>
                
                <div class="flex flex-col gap-2">
                    @if ($order->status !== 'Dibatalkan')
                        <a href="{{ route('admin.transactions.invoice', $order->id) }}" class="btn btn-primary" style="width: 100%;">
                            <i class="fa-solid fa-file-pdf"></i> Download Invoice PDF (A4)
                        </a>
                        <a href="{{ route('admin.transactions.nota', $order->id) }}" class="btn btn-secondary" style="width: 100%; border-color: #cbd5e1;">
                            <i class="fa-solid fa-receipt text-success"></i> Download Nota Receipt (80mm)
                        </a>
                        
                        @if ($order->status === 'Belum Dibayar')
                            <div style="border-top: 1px solid var(--border); margin-top: 12px; padding-top: 12px;">
                                <form method="POST" action="{{ route('admin.transactions.pay', $order->id) }}">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label" style="font-size: 0.75rem;">Metode Bayar Konfirmasi:</label>
                                        <select name="payment_method" class="form-control">
                                            <option value="Cash">Cash (Tunai)</option>
                                            <option value="Transfer">Transfer Bank</option>
                                            <option value="QRIS">QRIS</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success" style="width: 100%;">
                                        <i class="fa-solid fa-check"></i> Konfirmasi Telah Bayar (Lunas)
                                    </button>
                                </form>
                            </div>
                        @endif

                        <div style="border-top: 1px solid var(--border); margin-top: 12px; padding-top: 12px;">
                            <form method="POST" action="{{ route('admin.transactions.cancel', $order->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini? Stok barang akan dikembalikan secara otomatis.')">
                                @csrf
                                <button type="submit" class="btn btn-danger" style="width: 100%;">
                                    <i class="fa-solid fa-ban"></i> Batalkan Transaksi (Refund)
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-secondary text-sm text-center" style="padding: 16px 0;">
                            <i class="fa-solid fa-ban" style="font-size: 1.5rem; margin-bottom: 8px;"></i>
                            <p>Invoice dibatalkan. Tidak ada tindakan lanjutan yang tersedia.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>
