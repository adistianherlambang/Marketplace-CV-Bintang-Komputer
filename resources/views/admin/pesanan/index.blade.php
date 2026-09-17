<x-admin-layout>
    @section('header_title', 'Kelola Pesanan Masuk')

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules/pesanan-index.module.css') }}">
    @endpush

    <!-- Page Header Title & Status Info -->
    <div class="pesanan-header-wrapper">
        <div>
            <h2 class="pesanan-page-title">
                <span>Kelola Pesanan Masuk Online</span>
                <span class="pesanan-live-badge">
                    <span class="pesanan-live-dot"></span> Real-time
                </span>
            </h2>
            <p class="text-secondary text-sm" style="margin-top: 4px;">Verifikasi bukti transfer pelanggan, atur pengiriman kurir GrabExpress, dan perbarui status pesanan.</p>
        </div>

        @if($counts['menunggu'] > 0)
            <div class="metric-badge-urgent" style="padding: 6px 14px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Ada <strong>{{ $counts['menunggu'] }}</strong> pesanan butuh verifikasi bukti transfer</span>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4" style="background-color: var(--success-light); border: 1px solid var(--success); color: #065f46; padding: 14px 18px; border-radius: var(--radius); display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-circle-check text-success" style="font-size: 1.1rem;"></i>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Status KPI Metric Cards -->
    <div class="pesanan-metrics-grid">
        <!-- Semua -->
        <a href="{{ route('admin.pesanan.index') }}" class="pesanan-metric-card metric-all {{ !request('status') ? 'active' : '' }}">
            <div class="metric-top">
                <span class="metric-label">Semua Pesanan</span>
                <div class="metric-icon-box">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
            <div class="metric-counter">{{ $counts['all'] }}</div>
        </a>

        <!-- Menunggu Konfirmasi -->
        <a href="{{ route('admin.pesanan.index', ['status' => 'Menunggu Konfirmasi']) }}" class="pesanan-metric-card metric-menunggu {{ request('status') === 'Menunggu Konfirmasi' ? 'active' : '' }} {{ $counts['menunggu'] > 0 ? 'has-pending' : '' }}">
            <div class="metric-top">
                <span class="metric-label">Menunggu Konfirmasi</span>
                <div class="metric-icon-box">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
            <div style="display: flex; align-items: baseline; justify-content: space-between;">
                <div class="metric-counter" style="color: #d97706;">{{ $counts['menunggu'] }}</div>
                @if($counts['menunggu'] > 0)
                    <span class="metric-badge-urgent">Perlu Cek</span>
                @endif
            </div>
        </a>

        <!-- Sedang Diproses -->
        <a href="{{ route('admin.pesanan.index', ['status' => 'Diproses']) }}" class="pesanan-metric-card metric-diproses {{ request('status') === 'Diproses' ? 'active' : '' }}">
            <div class="metric-top">
                <span class="metric-label">Sedang Diproses</span>
                <div class="metric-icon-box">
                    <i class="fa-solid fa-box-open"></i>
                </div>
            </div>
            <div class="metric-counter" style="color: #2563eb;">{{ $counts['diproses'] }}</div>
        </a>

        <!-- Dikirim via Grab -->
        <a href="{{ route('admin.pesanan.index', ['status' => 'Dikirim']) }}" class="pesanan-metric-card metric-dikirim {{ request('status') === 'Dikirim' ? 'active' : '' }}">
            <div class="metric-top">
                <span class="metric-label">Dikirim (Grab)</span>
                <div class="metric-icon-box">
                    <i class="fa-solid fa-motorcycle"></i>
                </div>
            </div>
            <div class="metric-counter" style="color: #7c3aed;">{{ $counts['dikirim'] }}</div>
        </a>

        <!-- Selesai -->
        <a href="{{ route('admin.pesanan.index', ['status' => 'Selesai']) }}" class="pesanan-metric-card metric-selesai {{ request('status') === 'Selesai' ? 'active' : '' }}">
            <div class="metric-top">
                <span class="metric-label">Pesanan Selesai</span>
                <div class="metric-icon-box">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="metric-counter" style="color: #059669;">{{ $counts['selesai'] }}</div>
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <form method="GET" action="{{ route('admin.pesanan.index') }}" class="pesanan-filter-bar">
        <div class="pesanan-filter-row">
            <div class="pesanan-search-container">
                <i class="fa-solid fa-magnifying-glass pesanan-search-icon"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari invoice, nama pelanggan, no telepon, atau alamat..." class="pesanan-search-input">
            </div>

            <select name="status" class="pesanan-select-status" onchange="this.form.submit()">
                <option value="">Semua Status Pesanan</option>
                <option value="Menunggu Konfirmasi" {{ request('status') === 'Menunggu Konfirmasi' ? 'selected' : '' }}>⏳ Menunggu Konfirmasi</option>
                <option value="Diproses" {{ request('status') === 'Diproses' ? 'selected' : '' }}>📦 Sedang Diproses</option>
                <option value="Dikirim" {{ request('status') === 'Dikirim' ? 'selected' : '' }}>🛵 Dikirim (GrabExpress)</option>
                <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>✅ Selesai / Lunas</option>
                <option value="Belum Dibayar" {{ request('status') === 'Belum Dibayar' ? 'selected' : '' }}>💳 Belum Dibayar</option>
            </select>

            <button type="submit" class="btn btn-primary pesanan-btn-filter">
                <i class="fa-solid fa-filter"></i> Filter
            </button>

            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('admin.pesanan.index') }}" class="pesanan-btn-reset">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </a>
            @endif
        </div>
    </form>

    <!-- Table Container with Bukti Transfer Modal (Alpine.js State) -->
    <div x-data="{ buktiModal: null, buktiInvoice: '' }" @keydown.escape.window="buktiModal = null">
        <div class="pesanan-table-card">
            <div class="pesanan-table-responsive">
                <table class="pesanan-table">
                    <thead>
                        <tr>
                            <th style="width: 26%;">No. Invoice &amp; Pelanggan</th>
                            <th style="width: 27%;">Rincian Pembelian</th>
                            <th style="width: 14%; text-align: center;">Bukti Transfer</th>
                            <th style="width: 15%; text-align: center;">Status Pesanan</th>
                            <th style="width: 18%; text-align: center;">Tindakan Cepat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesanan as $item)
                            @php
                                $st = strtolower(trim($item->status));
                                $cleanPhone = preg_replace('/[^0-9]/', '', $item->customer_phone ?? '');
                                if (str_starts_with($cleanPhone, '0')) {
                                    $waNumber = '62' . substr($cleanPhone, 1);
                                } else {
                                    $waNumber = $cleanPhone;
                                }
                                $initials = strtoupper(substr($item->customer_display_name, 0, 1));
                            @endphp
                            <tr>
                                <!-- Invoice & Pelanggan -->
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="invoice-badge-pill">
                                            <i class="fa-solid fa-receipt"></i> {{ $item->invoice_number }}
                                        </span>
                                    </div>

                                    <div class="customer-info-box">
                                        <div class="customer-name-row">
                                            <span class="customer-avatar-mini">{{ $initials }}</span>
                                            <span>{{ $item->customer_display_name }}</span>
                                        </div>

                                        <div class="customer-subtext">
                                            @if(!empty($item->customer_phone))
                                                <a href="https://wa.me/{{ $waNumber }}?text=Halo%20{{ urlencode($item->customer_display_name) }},%20kami%20dari%20CV%20Bintang%20Jaya%20Komputer%20mengenai%20pesanan%20{{ urlencode($item->invoice_number) }}" target="_blank" class="customer-wa-btn" title="Chat WhatsApp Pelanggan">
                                                    <i class="fa-brands fa-whatsapp"></i> {{ $item->customer_phone }}
                                                </a>
                                            @else
                                                <span class="text-secondary">-</span>
                                            @endif
                                            <span>
                                                <i class="fa-regular fa-clock" style="margin-right: 3px;"></i>{{ $item->created_at->format('d/m/y H:i') }}
                                            </span>
                                        </div>

                                        @if($item->kecamatan || $item->kelurahan)
                                            <div class="delivery-location-card">
                                                <div style="display: flex; align-items: flex-start; gap: 6px;">
                                                    <i class="fa-solid fa-location-dot" style="color: #ef4444; margin-top: 2px;"></i>
                                                    <div>
                                                        <span>Kec. {{ optional($item->kecamatan)->nama_kecamatan }}, Kel. {{ optional($item->kelurahan)->nama_kelurahan }}</span>
                                                        @if($item->shareloc_link)
                                                            <div>
                                                                <a href="{{ $item->shareloc_link }}" target="_blank" class="maps-link-btn">
                                                                    <i class="fa-solid fa-map-pin"></i> Buka Titik Maps <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.65rem;"></i>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Rincian Produk & Total Biaya -->
                                <td>
                                    <div>
                                        @foreach($item->items as $orderItem)
                                            <div class="order-product-item">
                                                <span class="order-product-name">{{ $orderItem->item_name }}</span>
                                                <span class="order-product-qty">{{ $orderItem->quantity }}x</span>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="order-total-highlight">
                                        <span class="text-secondary" style="font-size: 0.8rem; font-weight: 500;">Total Pembayaran:</span>
                                        <span class="order-total-price">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="order-meta-info">
                                        <div>Metode: <strong style="color: #334155;">{{ $item->payment_method ?? 'Transfer BNI' }}</strong></div>
                                        @if($item->shipping_cost > 0)
                                            <div style="color: #64748b;">
                                                <i class="fa-solid fa-truck-fast text-secondary" style="margin-right: 3px;"></i>
                                                Ongkir Grab: Rp {{ number_format($item->shipping_cost, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Bukti Transfer -->
                                <td style="text-align: center; vertical-align: middle;">
                                    @if($item->bukti_transfer)
                                        <button type="button" @click="buktiModal = '{{ asset('storage/' . $item->bukti_transfer) }}'; buktiInvoice = '{{ $item->invoice_number }}'" class="bukti-preview-trigger" title="Klik untuk memperbesar bukti transfer">
                                            <div class="bukti-thumb-wrap">
                                                <img src="{{ asset('storage/' . $item->bukti_transfer) }}" alt="Bukti Transfer {{ $item->invoice_number }}">
                                                <div class="bukti-thumb-overlay">
                                                    <i class="fa-solid fa-magnifying-glass-plus"></i>
                                                </div>
                                            </div>
                                            <span class="bukti-label-btn"><i class="fa-solid fa-image"></i> Lihat Bukti</span>
                                        </button>
                                    @else
                                        <span class="bukti-empty-badge">
                                            <i class="fa-regular fa-circle-xmark"></i> Belum Ada
                                        </span>
                                    @endif
                                </td>

                                <!-- Status Badge -->
                                <td style="text-align: center; vertical-align: middle;">
                                    @if(in_array($st, ['selesai', 'lunas']))
                                        <span class="status-pill-modern status-pill-selesai">
                                            <i class="fa-solid fa-circle-check"></i> Selesai
                                        </span>
                                    @elseif($st === 'menunggu konfirmasi')
                                        <span class="status-pill-modern status-pill-menunggu">
                                            <i class="fa-solid fa-clock"></i> Menunggu Konfirmasi
                                        </span>
                                    @elseif($st === 'diproses')
                                        <span class="status-pill-modern status-pill-diproses">
                                            <i class="fa-solid fa-box-open"></i> Sedang Diproses
                                        </span>
                                    @elseif($st === 'dikirim')
                                        <span class="status-pill-modern status-pill-dikirim">
                                            <i class="fa-solid fa-motorcycle"></i> Dikirim (Grab)
                                        </span>
                                    @elseif(in_array($st, ['batal', 'dibatalkan']))
                                        <span class="status-pill-modern status-pill-batal">
                                            <i class="fa-solid fa-ban"></i> Dibatalkan
                                        </span>
                                    @else
                                        <span class="status-pill-modern status-pill-neutral">
                                            {{ $item->status }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Tindakan Cepat & Dropdown Status -->
                                <td style="text-align: center; vertical-align: middle;">
                                    <div class="pesanan-actions-group">
                                        <!-- Tombol Primary Action Workflow -->
                                        @if($item->status === 'Menunggu Konfirmasi')
                                            <form action="{{ route('admin.pesanan.update', $item->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pembayaran valid dan mulai proses pesanan ini?')">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="Diproses">
                                                <button type="submit" class="btn-action-primary-step btn-action-confirm">
                                                    <i class="fa-solid fa-check-double"></i> Konfirmasi &amp; Proses
                                                </button>
                                            </form>
                                        @elseif($item->status === 'Diproses')
                                            <form action="{{ route('admin.pesanan.update', $item->id) }}" method="POST" onsubmit="return confirm('Mulai pengiriman kurir GrabExpress untuk pesanan ini?')">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="Dikirim">
                                                <button type="submit" class="btn-action-primary-step btn-action-ship">
                                                    <i class="fa-solid fa-motorcycle"></i> Kirim via Grab
                                                </button>
                                            </form>
                                        @elseif($item->status === 'Dikirim')
                                            <form action="{{ route('admin.pesanan.update', $item->id) }}" method="POST" onsubmit="return confirm('Tandai pesanan telah sampai dan selesai?')">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="Selesai">
                                                <button type="submit" class="btn-action-primary-step btn-action-complete">
                                                    <i class="fa-solid fa-circle-check"></i> Selesaikan Pesanan
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Dropdown Ubah Status Manual & Tombol Nota Cetak -->
                                        <div class="action-sub-row">
                                            <form action="{{ route('admin.pesanan.update', $item->id) }}" method="POST" style="flex: 1;">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="action-status-select" onchange="this.form.submit()" title="Ubah status pesanan secara manual">
                                                    <option value="Belum Dibayar" {{ $item->status == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                                                    <option value="Menunggu Konfirmasi" {{ $item->status == 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                                    <option value="Diproses" {{ $item->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                                    <option value="Dikirim" {{ $item->status == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                                                    <option value="Selesai" {{ in_array($item->status, ['Selesai', 'Lunas']) ? 'selected' : '' }}>Selesai</option>
                                                    <option value="Dibatalkan" {{ in_array($item->status, ['Dibatalkan', 'Batal']) ? 'selected' : '' }}>Dibatalkan</option>
                                                </select>
                                            </form>

                                            <a href="{{ route('admin.transactions.nota', $item->id) }}" target="_blank" class="action-nota-btn" title="Download &amp; Cetak Nota">
                                                <i class="fa-solid fa-print"></i>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="pesanan-empty-wrapper">
                                        <i class="fa-solid fa-box-open pesanan-empty-icon"></i>
                                        <div class="pesanan-empty-title">Tidak Ada Pesanan Masuk</div>
                                        <div class="pesanan-empty-desc">Saat ini belum ada pesanan yang sesuai dengan filter atau kata kunci pencarian Anda.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($pesanan, 'links'))
                <div style="padding: 16px 20px; border-top: 1px solid #f1f5f9; background: #ffffff;">
                    {{ $pesanan->links() }}
                </div>
            @endif
        </div>

        <!-- Lightbox Modal Bukti Transfer (Alpine.js with blur & light dismiss) -->
        <div x-show="buktiModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="pesanan-modal-overlay" 
             @click="buktiModal = null" 
             x-cloak>
            
            <div class="pesanan-modal-box" 
                 @click.stop
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95">
                
                <div class="pesanan-modal-header">
                    <div class="pesanan-modal-title">
                        <i class="fa-solid fa-receipt text-primary"></i>
                        <span>Bukti Pembayaran</span>
                        <span x-text="buktiInvoice ? '(' + buktiInvoice + ')' : ''" style="font-size: 0.85rem; color: #64748b; font-weight: normal;"></span>
                    </div>
                    <button type="button" @click="buktiModal = null" class="pesanan-modal-close-btn" title="Tutup (Esc)">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="pesanan-modal-body">
                    <img :src="buktiModal" alt="Bukti Transfer Pembayaran" class="pesanan-modal-img">
                </div>

                <div class="pesanan-modal-footer">
                    <button type="button" @click="buktiModal = null" class="btn btn-secondary btn-sm" style="border-radius: 8px;">
                        Tutup
                    </button>
                    <a :href="buktiModal" target="_blank" class="btn btn-primary btn-sm" style="border-radius: 8px; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Resolusi Penuh
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>