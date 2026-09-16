<x-admin-layout>
    @section('header_title', 'Kelola Pesanan Masuk')

    <!-- Header Title & Description -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="font-bold text-xl text-dark">Kelola Pesanan Masuk Online</h3>
            <p class="text-secondary text-sm">Verifikasi bukti transfer pelanggan, atur pengiriman GrabExpress, dan pantau status pesanan.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-6" style="background-color: var(--success-light); border: 1px solid var(--success); color: #065f46; padding: 14px 18px; border-radius: var(--radius); display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <style>
        .metric-card-link { padding: 16px; text-decoration: none; border: 1px solid var(--border); transition: all 0.2s ease; }
        .metric-card-link:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .metric-active-primary { border: 2px solid var(--primary) !important; }
        .metric-active-warning { border: 2px solid #f59e0b !important; }
        .metric-active-purple { border: 2px solid #8b5cf6 !important; }
        .metric-active-success { border: 2px solid var(--success) !important; }
        .bg-menunggu-alert { background: #fffbeb !important; }
    </style>

    <!-- Status Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px;">
        <a href="{{ route('admin.pesanan.index') }}" class="chart-card metric-card-link {{ !request('status') ? 'metric-active-primary' : '' }}">
            <div class="text-xs text-secondary font-semibold">Semua Pesanan</div>
            <div class="text-xl font-bold text-dark mt-1">{{ $counts['all'] }}</div>
        </a>
        <a href="{{ route('admin.pesanan.index', ['status' => 'Menunggu Konfirmasi']) }}" class="chart-card metric-card-link {{ request('status') === 'Menunggu Konfirmasi' ? 'metric-active-warning' : '' }} {{ $counts['menunggu'] > 0 ? 'bg-menunggu-alert' : '' }}">
            <div class="text-xs font-semibold" style="color: #b45309;">
                <i class="fa-solid fa-clock mr-1"></i> Menunggu Konfirmasi
            </div>
            <div class="text-xl font-bold mt-1" style="color: #b45309;">{{ $counts['menunggu'] }}</div>
        </a>
        <a href="{{ route('admin.pesanan.index', ['status' => 'Diproses']) }}" class="chart-card metric-card-link {{ request('status') === 'Diproses' ? 'metric-active-primary' : '' }}">
            <div class="text-xs text-primary font-semibold">
                <i class="fa-solid fa-box mr-1"></i> Sedang Diproses
            </div>
            <div class="text-xl font-bold text-primary mt-1">{{ $counts['diproses'] }}</div>
        </a>
        <a href="{{ route('admin.pesanan.index', ['status' => 'Dikirim']) }}" class="chart-card metric-card-link {{ request('status') === 'Dikirim' ? 'metric-active-purple' : '' }}">
            <div class="text-xs font-semibold" style="color: #7c3aed;">
                <i class="fa-solid fa-motorcycle mr-1"></i> Dikirim (Grab)
            </div>
            <div class="text-xl font-bold mt-1" style="color: #7c3aed;">{{ $counts['dikirim'] }}</div>
        </a>
        <a href="{{ route('admin.pesanan.index', ['status' => 'Selesai']) }}" class="chart-card metric-card-link {{ request('status') === 'Selesai' ? 'metric-active-success' : '' }}">
            <div class="text-xs font-semibold" style="color: #059669;">
                <i class="fa-solid fa-circle-check mr-1"></i> Selesai
            </div>
            <div class="text-xl font-bold mt-1" style="color: #059669;">{{ $counts['selesai'] }}</div>
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <form method="GET" action="{{ route('admin.pesanan.index') }}" class="filter-bar mb-6" style="background: white; padding: 16px; border-radius: var(--radius); border: 1px solid var(--border); margin-bottom: 20px;">
        <div class="flex flex-wrap gap-3 items-center">
            <div style="flex: 1; min-width: 240px; position: relative;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 14px; top: 12px; color: var(--secondary);"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor invoice, nama pembeli, no HP..." class="form-control" style="padding-left: 38px;">
            </div>

            <select name="status" class="form-control" style="width: auto; min-width: 180px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="Menunggu Konfirmasi" {{ request('status') === 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                <option value="Diproses" {{ request('status') === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="Dikirim" {{ request('status') === 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Belum Dibayar" {{ request('status') === 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
            </select>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">Reset</a>
            @endif
        </div>
    </form>

    <!-- Table Container with Bukti TF Preview Modal -->
    <div class="table-container" x-data="{ buktiModal: null }">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 20%;">No. Invoice &amp; Pembeli</th>
                        <th style="width: 25%;">Rincian Produk &amp; Total</th>
                        <th style="width: 15%; text-align: center;">Bukti Transfer</th>
                        <th style="width: 15%; text-align: center;">Status Pesanan</th>
                        <th style="width: 25%; text-align: center;">Tindakan &amp; Konfirmasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pesanan as $item)
                        <tr>
                            <td>
                                <strong class="text-primary text-sm">{{ $item->invoice_number }}</strong>
                                <div class="font-bold text-dark mt-1">{{ $item->customer_display_name }}</div>
                                <div class="text-xs text-secondary"><i class="fa-solid fa-phone mr-1"></i>{{ $item->customer_phone ?? '-' }}</div>
                                <div class="text-xs text-secondary mt-1">
                                    <i class="fa-regular fa-calendar mr-1"></i>{{ $item->created_at->format('d/m/Y H:i') }}
                                </div>
                                @if($item->kecamatan || $item->kelurahan)
                                    <div class="text-xs text-secondary mt-1" style="background: #f8fafc; padding: 4px 6px; border-radius: 4px;">
                                        <i class="fa-solid fa-location-dot text-danger mr-1"></i> Kec. {{ optional($item->kecamatan)->nama_kecamatan }}, Kel. {{ optional($item->kelurahan)->nama_kelurahan }}
                                        @if($item->shareloc_link)
                                            <a href="{{ $item->shareloc_link }}" target="_blank" class="text-blue-600 font-semibold block mt-0.5 hover:underline">Buka Titik Maps</a>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td>
                                @foreach($item->items as $orderItem)
                                    <div class="text-sm font-semibold text-dark">
                                        {{ $orderItem->item_name }} <span class="text-xs font-normal text-secondary">({{ $orderItem->quantity }}x)</span>
                                    </div>
                                @endforeach
                                <div class="font-extrabold text-primary text-sm mt-1">
                                    Rp {{ number_format($item->total_amount, 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-secondary">
                                    Metode: <strong class="text-dark">{{ $item->payment_method ?? 'Transfer BNI' }}</strong>
                                    @if($item->shipping_cost > 0)
                                        <span class="block text-xs text-secondary">Ongkir: Rp {{ number_format($item->shipping_cost, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td style="text-align: center;">
                                @if($item->bukti_transfer)
                                    <button type="button" @click="buktiModal = '{{ asset('storage/' . $item->bukti_transfer) }}'" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 0.78rem; border-radius: var(--radius-sm); border: 1px solid var(--primary); color: var(--primary);">
                                        <i class="fa-solid fa-receipt mr-1"></i> Lihat Bukti
                                    </button>
                                @else
                                    <span class="badge" style="background: #f1f5f9; color: #94a3b8; font-size: 0.75rem;">Belum Upload</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @php $st = strtolower(trim($item->status)); @endphp
                                @if(in_array($st, ['selesai', 'lunas']))
                                    <span class="badge badge-success">Selesai</span>
                                @elseif($st === 'menunggu konfirmasi')
                                    <span class="badge" style="background: #fef3c7; color: #b45309; font-weight: 700;">Menunggu Konfirmasi</span>
                                @elseif($st === 'diproses')
                                    <span class="badge badge-warning">Diproses Toko</span>
                                @elseif($st === 'dikirim')
                                    <span class="badge" style="background: #ede9fe; color: #6d28d9; font-weight: 700;">Dalam Pengiriman</span>
                                @elseif(in_array($st, ['batal', 'dibatalkan']))
                                    <span class="badge badge-danger">Batal</span>
                                @else
                                    <span class="badge badge-secondary">{{ $item->status }}</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div class="flex flex-col gap-2 items-center justify-center">
                                    <!-- Aksi Cepat Berdasarkan Status -->
                                    @if($item->status === 'Menunggu Konfirmasi')
                                        <form action="{{ route('admin.pesanan.update', $item->id) }}" method="POST" class="w-full" onsubmit="return confirm('Konfirmasi pembayaran valid dan mulai proses pesanan?')">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Diproses">
                                            <button type="submit" class="btn btn-success btn-sm w-full" style="width: 100%; font-weight: 700; padding: 6px 10px; font-size: 0.78rem; background-color: #10b981; border: none; color: white;">
                                                <i class="fa-solid fa-circle-check mr-1"></i> Konfirmasi &amp; Proses
                                            </button>
                                        </form>
                                    @elseif($item->status === 'Diproses')
                                        <form action="{{ route('admin.pesanan.update', $item->id) }}" method="POST" class="w-full" onsubmit="return confirm('Kirim pesanan ini via kurir GrabExpress?')">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Dikirim">
                                            <button type="submit" class="btn btn-primary btn-sm w-full" style="width: 100%; font-weight: 700; padding: 6px 10px; font-size: 0.78rem;">
                                                <i class="fa-solid fa-motorcycle mr-1"></i> Kirim via Grab
                                            </button>
                                        </form>
                                    @elseif($item->status === 'Dikirim')
                                        <form action="{{ route('admin.pesanan.update', $item->id) }}" method="POST" class="w-full" onsubmit="return confirm('Tandai pesanan ini telah selesai diterima?')">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Selesai">
                                            <button type="submit" class="btn btn-success btn-sm w-full" style="width: 100%; font-weight: 700; padding: 6px 10px; font-size: 0.78rem; background-color: #059669; border: none; color: white;">
                                                <i class="fa-solid fa-flag-checkered mr-1"></i> Selesaikan Pesanan
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Dropdown Ubah Status Manual & Cetak Nota -->
                                    <div class="flex gap-1 items-center w-full">
                                        <form action="{{ route('admin.pesanan.update', $item->id) }}" method="POST" style="flex: 1;">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-control" style="padding: 4px 6px; font-size: 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--border);" onchange="this.form.submit()">
                                                <option value="Belum Dibayar" {{ $item->status == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                                                <option value="Menunggu Konfirmasi" {{ $item->status == 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                                <option value="Diproses" {{ $item->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                                <option value="Dikirim" {{ $item->status == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                                                <option value="Selesai" {{ in_array($item->status, ['Selesai', 'Lunas']) ? 'selected' : '' }}>Selesai</option>
                                                <option value="Dibatalkan" {{ in_array($item->status, ['Dibatalkan', 'Batal']) ? 'selected' : '' }}>Dibatalkan</option>
                                            </select>
                                        </form>

                                        <a href="{{ route('admin.transactions.nota', $item->id) }}" target="_blank" class="btn btn-secondary btn-sm" title="Download Nota Cetak" style="padding: 5px 8px; font-size: 0.75rem;">
                                            <i class="fa-solid fa-receipt text-success"></i>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="td-empty" style="text-align: center; padding: 36px; color: var(--secondary);">
                                <i class="fa-solid fa-box-open" style="font-size: 2.2rem; margin-bottom: 8px; display: block; opacity: 0.4;"></i>
                                Belum ada pesanan masuk yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($pesanan, 'links'))
            <div class="mt-4">
                {{ $pesanan->links() }}
            </div>
        @endif

        <!-- Modal Bukti Transfer -->
        <div x-show="buktiModal" class="modal-backdrop show" style="z-index: 99999; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); background: rgba(0,0,0,0.6);" @click="buktiModal = null" x-cloak>
            <div class="modal" @click.stop style="max-width: 540px; padding: 24px; background: white; border-radius: var(--radius); text-align: center;">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-bold text-dark">Bukti Pembayaran / Transfer</h4>
                    <button type="button" @click="buktiModal = null" class="btn btn-secondary btn-sm" style="border: none; background: #f1f5f9;">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <img :src="buktiModal" alt="Bukti Transfer" style="max-width: 100%; max-height: 70vh; border-radius: 8px; object-fit: contain; box-shadow: var(--shadow-md);">
                <div class="mt-4">
                    <a :href="buktiModal" target="_blank" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-up-right-from-square mr-1"></i> Buka Ukuran Penuh
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>