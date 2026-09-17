<x-admin-layout>
    @section('header_title', 'Kelola Komplain Pelanggan')

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules/complaints-index.module.css') }}">
    @endpush

    <!-- Header Title & Description -->
    <div class="complaints-header">
        <h3 class="complaints-title">Kelola Komplain Pelanggan</h3>
        <p class="complaints-subtitle">Pantau keluhan barang bermasalah, verifikasi foto nota &amp; fisik produk, serta tindak lanjuti klaim garansi toko.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Status Metric Cards -->
    <div class="complaints-stats-grid">
        <a href="{{ route('admin.complaints.index') }}" class="stat-card {{ !request('status') ? 'active' : '' }}">
            <div class="stat-card-label">Semua Komplain</div>
            <div class="stat-card-value">{{ $counts['all'] ?? 0 }}</div>
        </a>
        <a href="{{ route('admin.complaints.index', ['status' => 'Pending']) }}" class="stat-card stat-card-warning {{ request('status') === 'Pending' ? 'active-warning' : '' }} {{ ($counts['pending'] ?? 0) > 0 ? 'has-pending' : '' }}">
            <div class="stat-card-label">
                <i class="fa-solid fa-clock"></i> Perlu Tindakan
            </div>
            <div class="stat-card-value">{{ $counts['pending'] ?? 0 }}</div>
        </a>
        <a href="{{ route('admin.complaints.index', ['status' => 'Diproses']) }}" class="stat-card stat-card-primary {{ request('status') === 'Diproses' ? 'active-primary' : '' }}">
            <div class="stat-card-label">
                <i class="fa-solid fa-gears"></i> Sedang Diproses
            </div>
            <div class="stat-card-value">{{ $counts['diproses'] ?? 0 }}</div>
        </a>
        <a href="{{ route('admin.complaints.index', ['status' => 'Selesai']) }}" class="stat-card stat-card-success {{ request('status') === 'Selesai' ? 'active-success' : '' }}">
            <div class="stat-card-label">
                <i class="fa-solid fa-circle-check"></i> Selesai
            </div>
            <div class="stat-card-value">{{ $counts['selesai'] ?? 0 }}</div>
        </a>
        <a href="{{ route('admin.complaints.index', ['status' => 'Ditolak']) }}" class="stat-card stat-card-danger {{ request('status') === 'Ditolak' ? 'active-danger' : '' }}">
            <div class="stat-card-label">
                <i class="fa-solid fa-circle-xmark"></i> Ditolak
            </div>
            <div class="stat-card-value">{{ $counts['ditolak'] ?? 0 }}</div>
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <form method="GET" action="{{ route('admin.complaints.index') }}" class="complaints-filter-card">
        <div class="complaints-filter-row">
            <div class="filter-search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari invoice, nama pelanggan, no HP, jenis kendala...">
            </div>

            <div class="filter-select-wrapper">
                <select name="status" class="form-control" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending / Menunggu</option>
                    <option value="Diproses" {{ request('status') === 'Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary filter-submit-btn">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if (request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.complaints.index') }}" class="btn btn-secondary filter-reset-btn">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Table Grid with Image Preview Modal (Alpine.js) -->
    <div class="complaints-table-container" x-data="{ previewImage: null, previewTitle: 'Bukti Foto' }">
        <div class="table-responsive">
            <table class="complaints-table">
                <thead>
                    <tr>
                        <th style="width: 20%;">Invoice &amp; Waktu</th>
                        <th style="width: 20%;">Pelanggan</th>
                        <th style="width: 30%;">Kendala &amp; Detail</th>
                        <th style="width: 14%; text-align: center;">Bukti Foto</th>
                        <th style="width: 16%; text-align: center;">Status &amp; Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($complaints as $complaint)
                        <tr>
                            {{-- 1. Invoice & Waktu --}}
                            <td>
                                @if($complaint->order)
                                    <a href="{{ route('admin.pesanan.index', ['search' => $complaint->order->invoice_number]) }}" class="invoice-link-pill" title="Lihat detail pesanan">
                                        <i class="fa-solid fa-receipt"></i> {{ $complaint->order->invoice_number }}
                                    </a>
                                @else
                                    <span class="text-secondary small fw-bold">Tanpa Invoice</span>
                                @endif
                                <div class="text-secondary mt-1" style="font-size: 0.75rem;">
                                    <i class="fa-regular fa-clock me-1"></i>
                                    {{ $complaint->created_at ? $complaint->created_at->format('d M Y, H:i') : '-' }} WIB
                                </div>
                            </td>

                            {{-- 2. Data Pelanggan --}}
                            <td>
                                <div class="customer-name-row">
                                    {{ $complaint->customer_name ?? optional($complaint->order)->customer_display_name ?? 'Pelanggan' }}
                                </div>
                                @php
                                    $phone = $complaint->customer_phone ?? $complaint->contact ?? optional($complaint->order)->customer_phone;
                                    $cleanPhone = preg_replace('/[^0-9]/', '', (string)$phone);
                                    if (str_starts_with($cleanPhone, '0')) {
                                        $cleanPhone = '62' . substr($cleanPhone, 1);
                                    }
                                @endphp
                                @if($phone && $phone !== '-')
                                    <div class="customer-phone-meta">
                                        <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="text-secondary text-decoration-none" title="Chat WhatsApp Pelanggan">
                                            <i class="fa-brands fa-whatsapp text-success me-1"></i> {{ $phone }}
                                        </a>
                                    </div>
                                @else
                                    <span class="text-secondary" style="font-size: 0.75rem;">-</span>
                                @endif
                            </td>

                            {{-- 3. Kendala & Keterangan --}}
                            <td>
                                @if($complaint->complaint_type)
                                    <span class="complaint-type-badge">
                                        {{ $complaint->complaint_type }}
                                    </span>
                                @endif
                                <div class="complaint-desc-text">
                                    {{ $complaint->description ?? $complaint->complaint_text }}
                                </div>

                                {{-- Ringkasan Item Pesanan Terkait jika ada --}}
                                @if($complaint->order && $complaint->order->items->isNotEmpty())
                                    <div class="order-items-summary">
                                        @foreach($complaint->order->items as $item)
                                            <span class="mini-product-tag" title="Item dalam pesanan ini">
                                                <i class="fa-solid fa-box"></i> {{ $item->item_name ?? optional($item->product)->name }} ({{ $item->quantity }}x)
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>

                            {{-- 4. Bukti Foto --}}
                            <td style="text-align: center;">
                                <div class="photo-btn-group">
                                    @if($complaint->nota_bukti)
                                        <button type="button" @click="previewImage = '{{ asset('storage/' . $complaint->nota_bukti) }}'; previewTitle = 'Bukti Nota Pembelian - {{ $complaint->order->invoice_number ?? $complaint->customer_name }}'" class="photo-preview-btn btn-nota" title="Klik untuk lihat foto nota">
                                            <i class="fa-solid fa-receipt"></i> Foto Nota
                                        </button>
                                    @endif

                                    @if($complaint->product_bukti)
                                        <button type="button" @click="previewImage = '{{ asset('storage/' . $complaint->product_bukti) }}'; previewTitle = 'Bukti Fisik Kerusakan - {{ $complaint->order->invoice_number ?? $complaint->customer_name }}'" class="photo-preview-btn btn-product" title="Klik untuk lihat foto kerusakan">
                                            <i class="fa-solid fa-camera"></i> Foto Produk
                                        </button>
                                    @endif

                                    @if(!$complaint->nota_bukti && !$complaint->product_bukti)
                                        <span class="text-secondary" style="font-size: 0.75rem;">Tidak ada foto</span>
                                    @endif
                                </div>
                            </td>

                            {{-- 5. Status & Ubah Status --}}
                            <td style="text-align: center;">
                                <div class="mb-2">
                                    @if ($complaint->status === 'Selesai')
                                        <span class="badge-status badge-status-selesai">
                                            <i class="fa-solid fa-circle-check"></i> Selesai
                                        </span>
                                    @elseif ($complaint->status === 'Diproses')
                                        <span class="badge-status badge-status-diproses">
                                            <i class="fa-solid fa-gears"></i> Diproses
                                        </span>
                                    @elseif ($complaint->status === 'Ditolak')
                                        <span class="badge-status badge-status-ditolak">
                                            <i class="fa-solid fa-circle-xmark"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="badge-status badge-status-pending">
                                            <i class="fa-solid fa-clock"></i> {{ $complaint->status ?? 'Pending' }}
                                        </span>
                                    @endif
                                </div>

                                <form action="{{ route('admin.complaints.status', $complaint->id) }}" method="POST">
                                    @csrf
                                    <div class="action-select-wrapper">
                                        <select name="status" class="form-control" onchange="this.form.submit()">
                                            <option value="Pending" {{ in_array($complaint->status, ['Pending', 'Menunggu']) ? 'selected' : '' }}>Set Pending</option>
                                            <option value="Diproses" {{ $complaint->status == 'Diproses' ? 'selected' : '' }}>Set Diproses</option>
                                            <option value="Selesai" {{ $complaint->status == 'Selesai' ? 'selected' : '' }}>Set Selesai</option>
                                            <option value="Ditolak" {{ $complaint->status == 'Ditolak' ? 'selected' : '' }}>Set Ditolak</option>
                                        </select>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 48px 20px; color: var(--secondary);">
                                <i class="fa-regular fa-comment-dots" style="font-size: 2.5rem; margin-bottom: 12px; display: block; opacity: 0.35;"></i>
                                <div class="fw-bold text-dark mb-1">Tidak Ada Data Komplain</div>
                                <div class="small">Belum ada pengajuan komplain pelanggan yang sesuai dengan filter pencarian.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($complaints, 'links'))
            <div class="p-3 border-top">
                {{ $complaints->links() }}
            </div>
        @endif

        <!-- Image Preview Modal (Alpine.js) -->
        <div x-show="previewImage" class="preview-modal-backdrop" @click="previewImage = null" x-cloak style="display: none;">
            <div class="preview-modal-box" @click.stop>
                <div class="preview-modal-header">
                    <h5 class="preview-modal-title">
                        <i class="fa-solid fa-image text-primary"></i>
                        <span x-text="previewTitle"></span>
                    </h5>
                    <button type="button" @click="previewImage = null" class="preview-modal-close-btn" aria-label="Tutup">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="preview-modal-body">
                    <img :src="previewImage" alt="Bukti Foto Komplain" class="preview-modal-img">
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>