<x-admin-layout>
    @section('header_title', 'Kelola Pesanan Masuk')

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules/pesanan-index.module.css') }}">
    @endpush

    <!-- Header Title & Description -->
    <div class="pesanan-header">
        <h3 class="pesanan-title">Kelola Pesanan Masuk Online</h3>
        <p class="pesanan-subtitle">Verifikasi bukti transfer pelanggan, atur pengiriman GrabExpress, dan pantau status pesanan.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Status Metric Cards -->
    <div class="pesanan-stats-grid">
        <a href="{{ route('admin.pesanan.index') }}" class="stat-card {{ !request('status') ? 'active' : '' }}">
            <div class="stat-card-label">Semua Pesanan</div>
            <div class="stat-card-value">{{ $counts['all'] }}</div>
        </a>
        <a href="{{ route('admin.pesanan.index', ['status' => 'Menunggu Konfirmasi']) }}" class="stat-card stat-card-warning {{ request('status') === 'Menunggu Konfirmasi' ? 'active-warning' : '' }} {{ $counts['menunggu'] > 0 ? 'has-pending' : '' }}">
            <div class="stat-card-label">
                <i class="fa-solid fa-clock"></i> Menunggu Konfirmasi
            </div>
            <div class="stat-card-value">{{ $counts['menunggu'] }}</div>
        </a>
        <a href="{{ route('admin.pesanan.index', ['status' => 'Diproses']) }}" class="stat-card stat-card-primary {{ request('status') === 'Diproses' ? 'active' : '' }}">
            <div class="stat-card-label">
                <i class="fa-solid fa-box"></i> Sedang Diproses
            </div>
            <div class="stat-card-value">{{ $counts['diproses'] }}</div>
        </a>
        <a href="{{ route('admin.pesanan.index', ['status' => 'Dikirim']) }}" class="stat-card stat-card-purple {{ request('status') === 'Dikirim' ? 'active-purple' : '' }}">
            <div class="stat-card-label">
                <i class="fa-solid fa-motorcycle"></i> Dikirim (Grab)
            </div>
            <div class="stat-card-value">{{ $counts['dikirim'] }}</div>
        </a>
        <a href="{{ route('admin.pesanan.index', ['status' => 'Selesai']) }}" class="stat-card stat-card-success {{ request('status') === 'Selesai' ? 'active-success' : '' }}">
            <div class="stat-card-label">
                <i class="fa-solid fa-circle-check"></i> Selesai
            </div>
            <div class="stat-card-value">{{ $counts['selesai'] }}</div>
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <form method="GET" action="{{ route('admin.pesanan.index') }}" class="pesanan-filter-card">
        <div class="pesanan-filter-row">
            <div class="filter-search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor invoice, nama pembeli, no HP...">
            </div>

            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="Menunggu Konfirmasi" {{ request('status') === 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                <option value="Diproses" {{ request('status') === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="Dikirim" {{ request('status') === 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Belum Dibayar" {{ request('status') === 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
            </select>

            <button type="submit" class="btn btn-primary filter-submit-btn">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary filter-reset-btn">Reset</a>
            @endif
        </div>
    </form>

    <!-- Table Container with Bukti TF Preview Modal -->
    <div x-data="{ buktiModal: null }">
        <div class="pesanan-table-container">
            <div class="table-responsive">
                <table class="pesanan-table">
                    <thead>
                        <tr>
                            <th style="width: 22%;">No. Invoice &amp; Pembeli</th>
                            <th style="width: 26%;">Rincian Produk &amp; Total</th>
                            <th style="width: 14%; text-align: center;">Bukti Transfer</th>
                            <th style="width: 16%; text-align: center;">Status Pesanan</th>
                            <th style="width: 22%; text-align: center;">Tindakan &amp; Konfirmasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesanan as $item)
                            <tr>
                                <td>
                                    <div class="invoice-badge">
                                        <i class="fa-solid fa-receipt"></i> {{ $item->invoice_number }}
                                    </div>
                                    <div style="font-weight: 700; color: var(--dark); margin-top: 6px;">{{ $item->customer_display_name }}</div>
                                    <div style="font-size: 0.78rem; color: var(--secondary); margin-top: 2px;">
                                        <i class="fa-solid fa-phone me-1" style="font-size: 0.72rem;"></i>{{ $item->customer_phone ?? '-' }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--secondary); margin-top: 3px;">
                                        <i class="fa-regular fa-calendar me-1" style="font-size: 0.72rem;"></i>{{ $item->created_at->format('d/m/Y H:i') }} WIB
                                    </div>
                                    @if($item->kecamatan || $item->kelurahan)
                                        <div class="address-box">
                                            <div>
                                                <i class="fa-solid fa-location-dot text-danger me-1"></i>
                                                Kec. {{ optional($item->kecamatan)->nama_kecamatan }}, Kel. {{ optional($item->kelurahan)->nama_kelurahan }}
                                            </div>
                                            @if($item->shareloc_link)
                                                <a href="{{ $item->shareloc_link }}" target="_blank" rel="noopener noreferrer">
                                                    <i class="fa-solid fa-map-pin"></i> Buka Titik Maps
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 4px;">
                                        @foreach($item->items as $orderItem)
                                            <div style="font-size: 0.875rem; font-weight: 600; color: var(--dark);">
                                                {{ $orderItem->item_name }} <span style="font-weight: 400; color: var(--secondary); font-size: 0.78rem;">({{ $orderItem->quantity }}x)</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div style="font-size: 1rem; font-weight: 800; color: var(--primary); margin-top: 8px;">
                                        Rp {{ number_format($item->total_amount, 0, ',', '.') }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--secondary); margin-top: 2px;">
                                        Metode: <strong style="color: var(--dark);">{{ $item->payment_method ?? 'Transfer BNI' }}</strong>
                                        @if($item->shipping_cost > 0)
                                            <span style="display: block; margin-top: 2px;">Ongkir: Rp {{ number_format($item->shipping_cost, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    @if($item->bukti_transfer)
                                        <button type="button" @click="buktiModal = '{{ asset('storage/' . $item->bukti_transfer) }}'" class="btn-view-bukti">
                                            <i class="fa-solid fa-receipt"></i> Lihat Bukti
                                        </button>
                                    @else
                                        <span class="status-pill status-pill-secondary">Belum Upload</span>
                                    @endif
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    @php $st = strtolower(trim($item->status)); @endphp
                                    @if(in_array($st, ['selesai', 'lunas']))
                                        <span class="status-pill status-pill-selesai">
                                            <i class="fa-solid fa-circle-check"></i> Selesai
                                        </span>
                                    @elseif($st === 'menunggu konfirmasi')
                                        <span class="status-pill status-pill-menunggu">
                                            <i class="fa-solid fa-clock"></i> Menunggu Konfirmasi
                                        </span>
                                    @elseif($st === 'diproses')
                                        <span class="status-pill status-pill-diproses">
                                            <i class="fa-solid fa-box"></i> Diproses Toko
                                        </span>
                                    @elseif($st === 'dikirim')
                                        <span class="status-pill status-pill-dikirim">
                                            <i class="fa-solid fa-motorcycle"></i> Dalam Pengiriman
                                        </span>
                                    @elseif(in_array($st, ['batal', 'dibatalkan']))
                                        <span class="status-pill status-pill-batal">
                                            <i class="fa-solid fa-ban"></i> Batal
                                        </span>
                                    @else
                                        <span class="status-pill status-pill-secondary">{{ $item->status }}</span>
                                    @endif
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <div class="action-box">
                                        {{-- Aksi Cepat Berdasarkan Status --}}
                                        @if($item->status === 'Menunggu Konfirmasi')
                                            <form action="{{ route('admin.pesanan.update', $item->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pembayaran valid dan mulai proses pesanan?')">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="Diproses">
                                                <button type="submit" class="action-btn-main action-btn-confirm">
                                                    <i class="fa-solid fa-circle-check"></i> Konfirmasi &amp; Proses
                                                </button>
                                            </form>
                                        @elseif($item->status === 'Diproses')
                                            <form action="{{ route('admin.pesanan.update', $item->id) }}" method="POST" onsubmit="return confirm('Kirim pesanan ini via kurir GrabExpress?')">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="Dikirim">
                                                <button type="submit" class="action-btn-main action-btn-send">
                                                    <i class="fa-solid fa-motorcycle"></i> Kirim via Grab
                                                </button>
                                            </form>
                                        @elseif($item->status === 'Dikirim')
                                            <form action="{{ route('admin.pesanan.update', $item->id) }}" method="POST" onsubmit="return confirm('Tandai pesanan ini telah selesai diterima?')">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="Selesai">
                                                <button type="submit" class="action-btn-main action-btn-complete">
                                                    <i class="fa-solid fa-flag-checkered"></i> Selesaikan Pesanan
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Dropdown Ubah Status Manual & Cetak Nota --}}
                                        <div class="action-subgroup">
                                            <form action="{{ route('admin.pesanan.update', $item->id) }}" method="POST" style="flex: 1;">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="action-select-status" onchange="this.form.submit()">
                                                    <option value="Belum Dibayar" {{ $item->status == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                                                    <option value="Menunggu Konfirmasi" {{ $item->status == 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                                    <option value="Diproses" {{ $item->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                                    <option value="Dikirim" {{ $item->status == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                                                    <option value="Selesai" {{ in_array($item->status, ['Selesai', 'Lunas']) ? 'selected' : '' }}>Selesai</option>
                                                    <option value="Dibatalkan" {{ in_array($item->status, ['Dibatalkan', 'Batal']) ? 'selected' : '' }}>Dibatalkan</option>
                                                </select>
                                            </form>

                                            <a href="{{ route('admin.transactions.nota', $item->id) }}" target="_blank" class="action-btn-nota" title="Download Nota Cetak">
                                                <i class="fa-solid fa-receipt"></i> Nota
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 48px 20px; color: var(--secondary);">
                                    <i class="fa-solid fa-box-open" style="font-size: 2.5rem; margin-bottom: 12px; display: block; opacity: 0.35;"></i>
                                    <div style="font-weight: 600; font-size: 0.95rem; color: var(--dark);">Belum ada pesanan masuk</div>
                                    <div style="font-size: 0.8rem; margin-top: 4px;">Tidak ada data yang sesuai dengan kriteria filter saat ini.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if(method_exists($pesanan, 'links'))
            <div class="mt-4">
                {{ $pesanan->links() }}
            </div>
        @endif

        <!-- Modal Bukti Transfer -->
        <div x-show="buktiModal" class="tf-modal-overlay" @click="buktiModal = null" @keydown.escape.window="buktiModal = null" x-cloak>
            <div class="tf-modal-dialog" @click.stop>
                <div class="tf-modal-header">
                    <h4 class="tf-modal-title">Bukti Pembayaran / Transfer</h4>
                    <button type="button" @click="buktiModal = null" class="tf-modal-close" title="Tutup">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="tf-modal-body">
                    <img :src="buktiModal" alt="Bukti Transfer" class="tf-modal-img">
                </div>
                <div class="tf-modal-footer">
                    <button type="button" @click="buktiModal = null" class="btn btn-secondary btn-sm">Tutup</button>
                    <a :href="buktiModal" target="_blank" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-up-right-from-square me-1"></i> Buka Ukuran Penuh
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>