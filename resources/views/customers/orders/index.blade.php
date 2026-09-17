<x-guest-layout>
    <x-slot name="title">
        Riwayat &amp; Pelacakan Pesanan - CV Bintang Jaya Komputer
    </x-slot>

    @push('styles')
    <style>
        :root {
            --bj-primary: #dc2626;
            --bj-primary-dark: #b91c1c;
            --bj-primary-light: #fef2f2;
            --bj-border: #e2e8f0;
            --bj-card-bg: #ffffff;
            --bj-text-main: #0f172a;
            --bj-text-muted: #64748b;
        }

        body {
            background-color: #f8fafc;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            color: var(--bj-text-main);
        }

        .order-page-header {
            background: #ffffff;
            border-bottom: 1px solid var(--bj-border);
            padding: 24px 0;
            margin-bottom: 24px;
        }

        .metric-card {
            background: #ffffff;
            border: 1px solid var(--bj-border);
            border-radius: 16px;
            padding: 16px 20px;
            transition: all 0.2s ease;
            cursor: pointer;
            height: 100%;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.06);
        }

        .metric-card.active {
            border-color: var(--bj-primary);
            background: #fffafa;
            box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.15);
        }

        .filter-pill {
            border: 1px solid var(--bj-border);
            background: #ffffff;
            color: var(--bj-text-muted);
            font-size: 0.825rem;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 9999px;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .filter-pill:hover {
            background: #f1f5f9;
            color: var(--bj-text-main);
        }

        .filter-pill.active {
            background: var(--bj-primary);
            color: #ffffff;
            border-color: var(--bj-primary);
        }

        .order-card {
            background: #ffffff;
            border: 1px solid var(--bj-border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            margin-bottom: 20px;
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .order-card:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.07);
        }

        .order-card-header {
            background: #fcfdfe;
            border-bottom: 1px solid #f1f5f9;
            padding: 16px 20px;
        }

        .order-card-body {
            padding: 20px;
        }

        .order-card-footer {
            background: #fcfdfe;
            border-top: 1px solid #f1f5f9;
            padding: 16px 20px;
        }

        .product-thumb {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            object-fit: contain;
            padding: 4px;
            flex-shrink: 0;
        }

        .delivery-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px;
        }

        .badge-status {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            letter-spacing: 0.02em;
        }

        .badge-status-selesai {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .badge-status-dikirim {
            background: #faf5ff;
            color: #7e22ce;
            border: 1px solid #e9d5ff;
        }

        .badge-status-diproses {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .badge-status-menunggu {
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .badge-status-batal {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .btn-action-primary {
            background: var(--bj-primary);
            color: #ffffff;
            border: none;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 8px 14px;
            border-radius: 10px;
            transition: all 0.15s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-action-primary:hover {
            background: var(--bj-primary-dark);
            color: #ffffff;
        }

        .btn-action-secondary {
            background: #ffffff;
            color: var(--bj-text-main);
            border: 1px solid #cbd5e1;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 8px 14px;
            border-radius: 10px;
            transition: all 0.15s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-action-secondary:hover {
            background: #f8fafc;
            color: var(--bj-text-main);
            border-color: #94a3b8;
        }

        .btn-action-warning {
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 8px 14px;
            border-radius: 10px;
            transition: all 0.15s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-action-warning:hover {
            background: #fef3c7;
            color: #92400e;
        }

        .btn-action-success {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 8px 14px;
            border-radius: 10px;
            transition: all 0.15s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-action-success:hover {
            background: #d1fae5;
            color: #065f46;
        }

        /* Native Dialog modal according to modern-web-guidance */
        dialog#modalBukti {
            border: none;
            border-radius: 16px;
            padding: 0;
            max-width: 520px;
            width: 90%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        dialog#modalBukti::backdrop {
            background-color: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(4px);
        }

        .copy-btn {
            background: none;
            border: none;
            color: #94a3b8;
            padding: 2px 6px;
            border-radius: 4px;
            cursor: pointer;
            transition: color 0.15s ease;
        }

        .copy-btn:hover {
            color: #0f172a;
        }
    </style>
    @endpush

    @php
        $totalOrders = $pesanan->count();
        $waitingCount = $pesanan->filter(fn($o) => in_array(strtolower(trim($o->status)), ['menunggu konfirmasi', 'belum dibayar']))->count();
        $processingCount = $pesanan->filter(fn($o) => in_array(strtolower(trim($o->status)), ['diproses']))->count();
        $shippingCount = $pesanan->filter(fn($o) => in_array(strtolower(trim($o->status)), ['dikirim']))->count();
        $completedCount = $pesanan->filter(fn($o) => in_array(strtolower(trim($o->status)), ['selesai', 'lunas']))->count();
    @endphp

    {{-- Page Header --}}
    <div class="order-page-header">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <div class="small fw-bold text-danger text-uppercase mb-1">
                        <i class="fa-solid fa-user-check me-1"></i> Area Pelanggan
                    </div>
                    <h2 class="h3 fw-bold text-dark mb-1">Riwayat &amp; Pelacakan Pesanan</h2>
                    <p class="text-muted small mb-0">Pantau status pesanan, pembayaran, serta estimasi pengiriman kurir toko secara langsung.</p>
                </div>
                <div>
                    <a href="{{ route('catalog.index') }}" class="btn btn-danger fw-bold rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-cart-shopping"></i> Belanja Lagi di Katalog
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        
        {{-- Flash Success Alert --}}
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 d-flex align-items-center justify-content-between mb-4 p-3" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-check fs-5 text-success"></i>
                    <span class="fw-semibold small">{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close small" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Metric Summary Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="metric-card active" data-filter="all" onclick="selectFilter('all', this)">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-bold text-muted text-uppercase">Semua Pesanan</span>
                        <i class="fa-solid fa-boxes-stacked text-muted"></i>
                    </div>
                    <div class="fs-3 fw-bold text-dark">{{ $totalOrders }}</div>
                    <div class="text-muted" style="font-size: 0.72rem;">Total riwayat belanja</div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="metric-card" data-filter="menunggu" onclick="selectFilter('menunggu', this)">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-bold text-warning text-uppercase">Menunggu</span>
                        <i class="fa-solid fa-clock text-warning"></i>
                    </div>
                    <div class="fs-3 fw-bold text-warning">{{ $waitingCount }}</div>
                    <div class="text-warning" style="font-size: 0.72rem;">Menunggu konfirmasi admin</div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="metric-card" data-filter="pengiriman" onclick="selectFilter('pengiriman', this)">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-bold text-purple text-uppercase" style="color: #7e22ce;">Pengiriman</span>
                        <i class="fa-solid fa-motorcycle" style="color: #7e22ce;"></i>
                    </div>
                    <div class="fs-3 fw-bold" style="color: #7e22ce;">{{ $shippingCount + $processingCount }}</div>
                    <div class="text-muted" style="font-size: 0.72rem;">{{ $shippingCount }} di kurir, {{ $processingCount }} diproses</div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="metric-card" data-filter="selesai" onclick="selectFilter('selesai', this)">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-bold text-success text-uppercase">Selesai</span>
                        <i class="fa-solid fa-circle-check text-success"></i>
                    </div>
                    <div class="fs-3 fw-bold text-success">{{ $completedCount }}</div>
                    <div class="text-success" style="font-size: 0.72rem;">Barang sukses diterima</div>
                </div>
            </div>
        </div>

        {{-- Filter Toolbar & Search --}}
        <!-- <div class="card border-0 shadow-sm rounded-4 p-3 mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                
                {{-- Status Pills --}}
                <div class="d-flex flex-wrap gap-2" id="filterPillContainer">
                    <button type="button" class="filter-pill active" onclick="selectFilter('all')">
                        Semua <span class="badge bg-light text-dark rounded-pill">{{ $totalOrders }}</span>
                    </button>
                    <button type="button" class="filter-pill" onclick="selectFilter('menunggu')">
                        <i class="fa-solid fa-clock"></i> Menunggu Konfirmasi 
                        @if($waitingCount > 0)
                            <span class="badge bg-warning text-dark rounded-pill">{{ $waitingCount }}</span>
                        @endif
                    </button>
                    <button type="button" class="filter-pill" onclick="selectFilter('diproses')">
                        <i class="fa-solid fa-box"></i> Diproses 
                        @if($processingCount > 0)
                            <span class="badge bg-primary rounded-pill">{{ $processingCount }}</span>
                        @endif
                    </button>
                    <button type="button" class="filter-pill" onclick="selectFilter('dikirim')">
                        <i class="fa-solid fa-motorcycle"></i> Pengiriman 
                        @if($shippingCount > 0)
                            <span class="badge bg-purple rounded-pill" style="background-color: #7e22ce;">{{ $shippingCount }}</span>
                        @endif
                    </button>
                    <button type="button" class="filter-pill" onclick="selectFilter('selesai')">
                        <i class="fa-solid fa-circle-check"></i> Selesai 
                        @if($completedCount > 0)
                            <span class="badge bg-success rounded-pill">{{ $completedCount }}</span>
                        @endif
                    </button>
                </div>

                {{-- Search Box --}}
                <div class="position-relative" style="min-width: 260px;">
                    <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted small"></i>
                    <input type="text" id="orderSearchInput" oninput="filterOrders()" placeholder="Cari invoice atau produk..." class="form-control form-control-sm ps-5 rounded-pill border-secondary-subtle">
                </div>
            </div>
        </div> -->

        {{-- Order Cards List --}}
        <div id="orderCardsList">
            @forelse($pesanan as $item)
                @php
                    $st = strtolower(trim($item->status));
                    $catStatus = 'lainnya';
                    if (in_array($st, ['menunggu konfirmasi', 'belum dibayar'])) $catStatus = 'menunggu';
                    elseif ($st === 'diproses') $catStatus = 'diproses';
                    elseif ($st === 'dikirim') $catStatus = 'dikirim pengiriman';
                    elseif (in_array($st, ['selesai', 'lunas'])) $catStatus = 'selesai';

                    $itemNames = $item->items->pluck('item_name')->join(' ');
                @endphp

                <div class="order-card" data-status="{{ $catStatus }}" data-invoice="{{ strtolower($item->invoice_number) }}" data-items="{{ strtolower($itemNames) }}">
                    
                    {{-- Header --}}
                    <div class="order-card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="d-flex flex-column align-items-center gap-2">
                            <span class="badge bg-danger-subtle text-danger fw-bold rounded-pill px-2.5 py-1">
                                <i class="fa-solid fa-receipt me-1"></i> {{ $item->invoice_number }}
                            </span>
                            <!-- <button type="button" class="copy-btn" data-invoice="{{ $item->invoice_number }}" onclick="copyInvoice(this.dataset.invoice, this)" title="Salin Invoice">
                                <i class="fa-regular fa-copy"></i>
                            </button> -->
                            <span class="text-muted small">
                                <!-- <i class="fa-regular fa-calendar me-1"></i> --> {{ $item->created_at->format('d M Y, H:i') }} WIB
                            </span>
                        </div>

                        <div>
                            @if($st === 'selesai' || $st === 'lunas')
                                <span class="badge-status badge-status-selesai">
                                    <i class="fa-solid fa-circle-check"></i> Selesai
                                </span>
                            @elseif($st === 'dikirim')
                                <span class="badge-status badge-status-dikirim">
                                    <i class="fa-solid fa-motorcycle"></i> Dalam Pengiriman
                                </span>
                            @elseif($st === 'diproses')
                                <span class="badge-status badge-status-diproses">
                                    <i class="fa-solid fa-box"></i> Sedang Diproses
                                </span>
                            @elseif($st === 'menunggu konfirmasi' || $st === 'belum dibayar')
                                <span class="badge-status badge-status-menunggu">
                                    <i class="fa-solid fa-hourglass-half"></i> Menunggu Konfirmasi
                                </span>
                            @elseif($st === 'dibatalkan')
                                <span class="badge-status badge-status-batal">
                                    <i class="fa-solid fa-circle-xmark"></i> Dibatalkan
                                </span>
                            @else
                                <span class="badge-status bg-light text-dark border">
                                    {{ $item->status }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="order-card-body">
                        <div class="row g-4 align-items-center">
                            
                            {{-- Products list (Left) --}}
                            <div class="col-lg-7">
                                <div class="small fw-bold text-muted text-uppercase mb-2">
                                    Rincian Produk ({{ $item->items->count() }})
                                </div>
                                
                                <div class="d-flex flex-column gap-3">
                                    @foreach($item->items as $orderItem)
                                        @php
                                            $prod = $orderItem->product;
                                            $imagePath = $prod && $prod->primaryImage ? asset('storage/' . $prod->primaryImage->path) : null;
                                        @endphp
                                        <div class="d-flex align-items-center gap-3">
                                            @if($imagePath)
                                                <img src="{{ $imagePath }}" alt="{{ $orderItem->item_name }}" class="product-thumb">
                                            @else
                                                <div class="product-thumb d-flex align-items-center justify-content-center text-muted">
                                                    <i class="fa-solid fa-laptop fs-4"></i>
                                                </div>
                                            @endif

                                            <div class="flex-grow-1 min-w-0">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    @if($prod && $prod->brand)
                                                        <span class="badge bg-secondary-subtle text-secondary small py-0.5 px-1.5 rounded">{{ $prod->brand->name }}</span>
                                                    @endif
                                                    <h6 class="fw-bold mb-0 text-dark text-truncate" style="font-size: 0.9rem;">
                                                        {{ $orderItem->item_name }}
                                                    </h6>
                                                </div>
                                                <div class="small text-muted">
                                                    {{ $orderItem->quantity }}x @ Rp {{ number_format($orderItem->price, 0, ',', '.') }}
                                                </div>
                                            </div>

                                            <div class="text-end fw-bold text-dark" style="font-size: 0.9rem;">
                                                Rp {{ number_format($orderItem->price * $orderItem->quantity, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Delivery Info (Right) --}}
                            <div class="col-lg-5">
                                <div class="delivery-box">
                                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                        <span class="small fw-bold text-dark">
                                            <i class="fa-solid fa-truck-fast text-danger me-1"></i> Pengiriman Toko
                                        </span>
                                        <span class="badge bg-success-subtle text-success small">Kurir GrabExpress</span>
                                    </div>

                                    <div class="small mb-2">
                                        <div class="text-muted" style="font-size: 0.75rem;">Alamat Tujuan:</div>
                                        <div class="fw-bold text-dark">
                                            Kec. {{ optional($item->kecamatan)->nama_kecamatan ?? '-' }}, 
                                            Kel. {{ optional($item->kelurahan)->nama_kelurahan ?? '-' }}
                                        </div>
                                        @if($item->customer_name)
                                            <div class="text-muted" style="font-size: 0.78rem;">
                                                Penerima: {{ $item->customer_name }} {{ $item->customer_phone ? '('.$item->customer_phone.')' : '' }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top small">
                                        <span class="text-muted">Ongkos Kirim:</span>
                                        <span class="fw-bold text-dark">Rp {{ number_format($item->shipping_cost, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top small">
                                        <span class="text-muted">Metode Bayar:</span>
                                        <span class="badge bg-light text-dark border">{{ $item->payment_method }}</span>
                                    </div>

                                    @if($item->shareloc_link || $item->bukti_transfer)
                                        <div class="d-flex gap-2 mt-2 pt-2 border-top">
                                            @if($item->shareloc_link)
                                                <a href="{{ $item->shareloc_link }}" target="_blank" class="btn btn-outline-danger btn-sm w-100 fw-bold" style="font-size: 0.75rem;">
                                                    <i class="fa-solid fa-location-dot me-1"></i> Titik Maps
                                                </a>
                                            @endif
                                            @if($item->bukti_transfer)
                                                <button type="button" class="btn btn-outline-secondary btn-sm w-100 fw-bold" style="font-size: 0.75rem;" data-bukti="{{ asset('storage/' . $item->bukti_transfer) }}" onclick="openBuktiModal(this.dataset.bukti)">
                                                    <i class="fa-solid fa-image me-1"></i> Bukti Transfer
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="order-card-footer d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <span class="small text-muted">Total Pembayaran:</span>
                            <div class="fs-5 fw-bold text-danger">
                                Rp {{ number_format($item->total_amount, 0, ',', '.') }}
                                @if($item->shipping_cost > 0)
                                    <span class="text-muted fw-normal" style="font-size: 0.75rem;">(Termasuk Ongkir)</span>
                                @endif
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex flex-wrap gap-2">
                            {{-- Download Nota --}}
                            <a href="{{ route('customer.orders.nota', $item->id) }}" target="_blank" class="btn-action-secondary" title="Unduh Nota PDF">
                                <i class="fa-solid fa-file-invoice text-danger"></i> Download Nota
                            </a>

                            {{-- Pesanan Diterima (Jika status Dikirim) --}}
                            @if($st === 'dikirim')
                                <form action="{{ route('customer.orders.selesai', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-action-success" onclick="return confirm('Konfirmasi bahwa paket barang telah sampai dan Anda terima dengan baik?')">
                                        <i class="fa-solid fa-check"></i> Pesanan Diterima
                                    </button>
                                </form>
                            @endif

                            {{-- Ajukan Komplain --}}
                            <a href="{{ route('customer.complaints.create', $item->id) }}" class="btn-action-warning" title="Ajukan klaim komplain barang">
                                <i class="fa-solid fa-triangle-exclamation"></i> Ajukan Komplain
                            </a>

                            {{-- Chat WhatsApp Bantuan --}}
                            <a href="https://wa.me/6281234567890?text={{ urlencode('Halo CV Bintang Jaya Komputer, saya ingin menanyakan pesanan saya dengan nomor invoice ' . $item->invoice_number) }}" target="_blank" class="btn-action-secondary text-success border-success-subtle" title="Chat CS Toko">
                                <i class="fa-brands fa-whatsapp fs-6"></i> Bantuan CS
                            </a>
                        </div>
                    </div>

                </div>
            @empty
                {{-- Empty State --}}
                <div class="card border-0 shadow-sm rounded-4 text-center py-5 px-3">
                    <div class="mb-3">
                        <div class="d-inline-flex p-4 rounded-circle bg-danger-subtle text-danger fs-1">
                            <i class="fa-solid fa-cart-arrow-down"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-dark">Belum Ada Riwayat Pesanan</h4>
                    <p class="text-muted small mx-auto mb-4" style="max-width: 400px;">
                        Anda belum melakukan pembelian apapun. Temukan laptop, komputer PC, dan aksesoris bergaransi resmi di katalog kami sekarang!
                    </p>
                    <div>
                        <a href="{{ route('catalog.index') }}" class="btn btn-danger fw-bold rounded-pill px-4 py-2">
                            <i class="fa-solid fa-store me-1"></i> Buka Katalog Produk
                        </a>
                    </div>
                </div>
            @endforelse
            
            {{-- No Results from Filter State --}}
            <div id="noMatchMessage" class="card border-0 shadow-sm rounded-4 text-center py-5 px-3 d-none">
                <i class="fa-solid fa-magnifying-glass fs-2 text-muted mb-2"></i>
                <h5 class="fw-bold text-muted">Tidak Ada Pesanan Ditemukan</h5>
                <p class="text-muted small mb-0">Coba ubah kata kunci pencarian atau ganti kategori status.</p>
            </div>
        </div>

    </div>

    {{-- Modern HTML Dialog Modal for Proof of Payment (modern-web-guidance) --}}
    <dialog id="modalBukti" closedby="any" aria-labelledby="dialogBuktiTitle">
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark" id="dialogBuktiTitle">
                <i class="fa-solid fa-receipt text-danger me-1"></i> Bukti Transfer Pembayaran
            </h6>
            <button type="button" class="btn-close" onclick="closeBuktiModal()" aria-label="Close"></button>
        </div>
        <div class="p-3 text-center bg-light">
            <img id="modalBuktiImg" src="" alt="Bukti Transfer" class="img-fluid rounded-3 border shadow-sm" style="max-height: 70vh; object-fit: contain;">
        </div>
        <div class="p-3 border-top d-flex justify-content-end">
            <button type="button" class="btn btn-secondary btn-sm fw-bold px-3 rounded-3" onclick="closeBuktiModal()">Tutup</button>
        </div>
    </dialog>

    @push('scripts')
    <script>
        let currentFilter = 'all';

        function selectFilter(filter, el = null) {
            currentFilter = filter;

            // Update pills
            document.querySelectorAll('#filterPillContainer .filter-pill').forEach(pill => {
                pill.classList.remove('active');
            });
            const matchingPill = Array.from(document.querySelectorAll('#filterPillContainer .filter-pill')).find(p => p.textContent.toLowerCase().includes(filter.toLowerCase()));
            if (matchingPill) {
                matchingPill.classList.add('active');
            } else if (filter === 'all') {
                document.querySelector('#filterPillContainer .filter-pill').classList.add('active');
            }

            // Update metric cards
            document.querySelectorAll('.metric-card').forEach(c => c.classList.remove('active'));
            if (el) {
                el.classList.add('active');
            } else {
                const card = document.querySelector(`.metric-card[data-filter="${filter}"]`);
                if (card) card.classList.add('active');
            }

            filterOrders();
        }

        function filterOrders() {
            const query = (document.getElementById('orderSearchInput').value || '').toLowerCase().trim();
            const cards = document.querySelectorAll('.order-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const status = card.getAttribute('data-status') || '';
                const invoice = card.getAttribute('data-invoice') || '';
                const items = card.getAttribute('data-items') || '';

                let matchStatus = false;
                if (currentFilter === 'all') {
                    matchStatus = true;
                } else if (status.includes(currentFilter)) {
                    matchStatus = true;
                }

                let matchQuery = true;
                if (query.length > 0) {
                    matchQuery = invoice.includes(query) || items.includes(query);
                }

                if (matchStatus && matchQuery) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            const noMatchMsg = document.getElementById('noMatchMessage');
            if (cards.length > 0) {
                if (visibleCount === 0) {
                    noMatchMsg.classList.remove('d-none');
                } else {
                    noMatchMsg.classList.add('d-none');
                }
            }
        }

        function copyInvoice(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const icon = btn.querySelector('i');
                icon.className = 'fa-solid fa-check text-success';
                setTimeout(() => {
                    icon.className = 'fa-regular fa-copy';
                }, 1800);
            });
        }

        // Native Dialog handler with light-dismiss fallback (modern-web-guidance)
        const dialog = document.getElementById('modalBukti');
        function openBuktiModal(imgUrl) {
            document.getElementById('modalBuktiImg').src = imgUrl;
            dialog.showModal();
        }
        function closeBuktiModal() {
            dialog.close();
        }

        // Light-dismiss fallback for browsers without native closedBy support
        if (!('closedBy' in HTMLDialogElement.prototype)) {
            dialog.addEventListener('click', (event) => {
                if (event.target !== dialog) return;
                const rect = dialog.getBoundingClientRect();
                const isDialogContent = (
                    rect.top <= event.clientY &&
                    event.clientY <= rect.top + rect.height &&
                    rect.left <= event.clientX &&
                    event.clientX <= rect.left + rect.width
                );
                if (isDialogContent) return;
                dialog.close();
            });
        }
    </script>
    @endpush
</x-guest-layout>
