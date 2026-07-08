<x-admin-layout>
    @section('header_title', 'Kelola Pesanan Produk')

    <div class="chart-card">
        <!-- Top Toolbar -->
        <div class="flex justify-between items-center mb-6 flex-wrap gap-4" style="border-bottom: 1px solid var(--border); padding-bottom: 20px;">
            <div>
                <h3 class="font-bold text-lg" style="color: var(--dark); margin: 0;">Daftar Pesanan Tamu</h3>
                <p class="text-xs text-secondary" style="margin: 4px 0 0 0;">Kelola pemesanan produk oleh calon pelanggan/tamu toko.</p>
            </div>
            
            <!-- Filters -->
            <form method="GET" action="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 flex-wrap">
                <!-- Search bar -->
                <div class="form-group" style="margin-bottom: 0;">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama, telp, produk..." style="padding: 8px 16px; min-width: 240px;">
                </div>

                <!-- Status Filter -->
                <div class="form-group" style="margin-bottom: 0;">
                    <select name="status" class="form-control" onchange="this.form.submit()" style="padding: 8px 16px;">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="Menunggu" {{ request('status') === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Diproses" {{ request('status') === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Dibatalkan" {{ request('status') === 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 8px 16px;">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari
                </button>

                @if (request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary" style="padding: 8px 16px;">Clear</a>
                @endif
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="table-responsive">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="background: none; text-align: left; padding: 12px 16px;">Produk</th>
                        <th style="background: none; text-align: left; padding: 12px 16px;">Nama & Kontak</th>
                        <th style="background: none; text-align: left; padding: 12px 16px;">Waktu Pengambilan</th>
                        <th style="background: none; text-align: left; padding: 12px 16px;">Catatan Pelanggan</th>
                        <th style="background: none; text-align: left; padding: 12px 16px;">Catatan Admin</th>
                        <th style="background: none; text-align: center; padding: 12px 16px;">Status</th>
                        <th style="background: none; text-align: center; padding: 12px 16px;">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 16px;">
                                <div class="font-semibold" style="font-size: 0.95rem; color: var(--dark);">{{ $booking->product->name }}</div>
                                <div class="text-xs text-secondary" style="margin-top: 2px;">SKU: {{ $booking->product->sku }} | Harga: Rp {{ number_format($booking->product->price_jual, 0, ',', '.') }}</div>
                            </td>
                            <td style="padding: 16px;">
                                <div class="font-semibold" style="font-size: 0.9rem;">{{ $booking->customer_name }}</div>
                                <div class="flex items-center gap-1 mt-1 text-sm text-secondary">
                                    <i class="fa-solid fa-phone" style="font-size: 0.8rem;"></i>
                                    <span>{{ $booking->customer_phone }}</span>
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $booking->customer_phone);
                                        if (str_starts_with($cleanPhone, '08')) {
                                            $cleanPhone = '628' . substr($cleanPhone, 2);
                                        }
                                    @endphp
                                    <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="text-success hover:underline" style="margin-left: 6px; display: inline-flex; align-items: center; gap: 2px;" title="Chat via WhatsApp">
                                        <i class="fa-brands fa-whatsapp" style="font-size: 0.95rem;"></i> WhatsApp
                                    </a>
                                </div>
                            </td>
                            <td style="padding: 16px; font-size: 0.9rem;">
                                <div>{{ $booking->pickup_time->translatedFormat('d M Y') }}</div>
                                <div class="text-xs text-secondary font-semibold" style="margin-top: 2px;">
                                    <i class="fa-regular fa-clock"></i> Pukul {{ $booking->pickup_time->format('H:i') }} WIB
                                </div>
                            </td>
                            <td style="padding: 16px; font-size: 0.85rem; max-width: 180px; color: var(--secondary); word-break: break-word;">
                                {{ $booking->notes ?: '-' }}
                            </td>
                            <td style="padding: 16px; font-size: 0.85rem; max-width: 180px; color: #1e3c72; word-break: break-word;">
                                {{ $booking->notes_internal ?: '-' }}
                            </td>
                            <td style="padding: 16px; text-align: center;">
                                @if ($booking->status === 'Selesai')
                                    <span class="badge badge-success">Selesai</span>
                                @elseif ($booking->status === 'Diproses')
                                    <span class="badge badge-warning" style="background-color: #dbeafe; color: #1e40af;">Diproses</span>
                                @elseif ($booking->status === 'Dibatalkan')
                                    <span class="badge badge-danger" style="background-color: var(--border); color: var(--secondary);">Dibatalkan</span>
                                @else
                                    <span class="badge badge-danger">Menunggu</span>
                                @endif
                            </td>
                            <td style="padding: 16px; text-align: center;" x-data="{ editOpen: false }">
                                <button type="button" @click="editOpen = true" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 0.8rem; border-radius: var(--radius-sm);">
                                    <i class="fa-solid fa-edit"></i> Tindaklanjut
                                </button>

                                <!-- Edit Popup Modal -->
                                <div x-show="editOpen" class="modal-backdrop" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.5); z-index: 99999; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); padding: 16px;" x-transition x-cloak>
                                    <div class="modal-container" @click.away="editOpen = false" style="background: var(--white); border-radius: var(--radius-lg); max-width: 450px; width: 100%; padding: 28px; box-shadow: var(--shadow-lg); border: 1px solid var(--border); position: relative; text-align: left;">
                                        <button type="button" @click="editOpen = false" style="position: absolute; top: 16px; right: 16px; background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--secondary); display: flex; align-items: center; justify-content: center;">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                        
                                        <h3 class="font-bold mb-2" style="font-size: 1.2rem; color: var(--dark); margin: 0 0 4px 0;">Tindaklanjut Pesanan</h3>
                                        <p style="font-size: 0.825rem; color: var(--secondary); margin: 0 0 20px 0; line-height: 1.4;">
                                            Perbarui status pemesanan untuk pelanggan <strong>{{ $booking->customer_name }}</strong>.
                                        </p>
                                        
                                        <form method="POST" action="{{ route('admin.bookings.status', $booking->id) }}">
                                            @csrf
                                            
                                            <!-- Status Select -->
                                            <div class="form-group" style="display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; text-align: left;">
                                                <label class="form-label font-semibold" style="font-size: 0.85rem; color: var(--dark);">Status Pesanan</label>
                                                <select name="status" class="form-control" style="width: 100%; padding: 10px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.9rem;" required>
                                                    <option value="Menunggu" {{ $booking->status === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                    <option value="Diproses" {{ $booking->status === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                                    <option value="Selesai" {{ $booking->status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                                    <option value="Dibatalkan" {{ $booking->status === 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                                </select>
                                            </div>

                                            <!-- Notes -->
                                            <div class="form-group" style="display: flex; flex-direction: column; gap: 6px; margin-bottom: 20px; text-align: left;">
                                                <label class="form-label font-semibold" style="font-size: 0.85rem; color: var(--dark);">Catatan Internal Admin (Opsional)</label>
                                                <textarea name="notes" class="form-control" rows="3" style="width: 100%; padding: 10px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.9rem; resize: vertical;" placeholder="Contoh: Sudah dihubungi, barang siap diambil...">{{ $booking->notes_internal }}</textarea>
                                            </div>

                                            <div style="display: flex; gap: 10px; justify-content: flex-end; border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px;">
                                                <button type="button" @click="editOpen = false" class="btn btn-secondary" style="padding: 8px 16px; font-size: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border); cursor: pointer;">Batal</button>
                                                <button type="submit" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.85rem; border-radius: var(--radius-sm); border: none; cursor: pointer;">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 48px; color: var(--secondary);">
                                <i class="fa-solid fa-basket-shopping" style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.5;"></i>
                                <p style="margin: 0;">Belum ada data pesanan produk dari tamu.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-6">
            {{ $bookings->links() }}
        </div>
    </div>
</x-admin-layout>
