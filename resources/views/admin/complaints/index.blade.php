<x-admin-layout>
    @section('header_title', 'Kelola Komplain Pelanggan')

    <div class="grid" style="grid-template-columns: 1fr 2fr; gap: 24px; align-items: start;">
        
        <!-- Left: Log Complaint Form -->
        <div class="chart-card">
            <h3 class="font-bold mb-4" style="font-size: 1.1rem;"><i class="fa-solid fa-comments text-primary mr-2"></i>Catat Komplain Baru</h3>
            
            <form method="POST" action="{{ route('admin.complaints.store') }}">
                @csrf
                
                <!-- Order selection -->
                <div class="form-group">
                    <label class="form-label">Nomor Invoice Terkait</label>
                    <select name="order_id" class="form-control tom-select" required>
                        <option value="">-- Pilih Invoice --</option>
                        @foreach ($orders as $order)
                            <option value="{{ $order->id }}">
                                {{ $order->invoice_number }} ({{ $order->customer ? $order->customer->name : 'Guest' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Customer Name -->
                <div class="form-group">
                    <label class="form-label">Nama Pengadu / Pelanggan</label>
                    <input type="text" name="customer_name" class="form-control" required placeholder="Contoh: Andi Wijaya">
                </div>

                <!-- Contact phone / WhatsApp -->
                <div class="form-group">
                    <label class="form-label">No. Telepon / WhatsApp Pengadu</label>
                    <input type="text" name="contact" class="form-control" required placeholder="Contoh: 08129876543">
                </div>

                <!-- Complaint Details -->
                <div class="form-group">
                    <label class="form-label">Detail Masalah / Komplain</label>
                    <textarea name="complaint_text" class="form-control" rows="4" required placeholder="Jelaskan secara rinci permasalahan produk atau layanan..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Komplain
                </button>
            </form>
        </div>

        <!-- Right: Complaints Log Table -->
        <div class="chart-card" style="padding: 24px 0;">
            <h3 class="font-bold mb-4" style="font-size: 1.1rem; padding: 0 24px;"><i class="fa-solid fa-clock-rotate-left text-secondary mr-2"></i>Audit Log Komplain</h3>
            
            <div class="table-responsive">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="background: none;">Invoice / Pelanggan</th>
                            <th style="background: none;">Kontak</th>
                            <th style="background: none;">Isi Masalah</th>
                            <th style="background: none; text-align: center;">Status</th>
                            <th style="background: none; text-align: center;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($complaints as $comp)
                            <tr>
                                <td>
                                    <strong>{{ $comp->order->invoice_number }}</strong>
                                    <div class="text-xs text-secondary">Pengadu: {{ $comp->customer_name }}</div>
                                </td>
                                <td>{{ $comp->contact }}</td>
                                <td style="font-size: 0.85rem; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $comp->complaint_text }}">
                                    {{ $comp->complaint_text }}
                                </td>
                                <td style="text-align: center;">
                                    @if ($comp->status === 'Selesai')
                                        <span class="badge badge-success">Selesai</span>
                                    @elseif ($comp->status === 'Diproses')
                                        <span class="badge badge-warning">Diproses</span>
                                    @else
                                        <span class="badge badge-danger">Menunggu</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <form method="POST" action="{{ route('admin.complaints.status', $comp->id) }}" class="flex items-center gap-1 justify-center">
                                        @csrf
                                        <select name="status" class="form-control tom-select" style="padding: 4px 8px; font-size: 0.75rem; border-radius: var(--radius-sm); max-width: 110px;" onchange="this.form.submit()">
                                            <option value="Menunggu" {{ $comp->status === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="Diproses" {{ $comp->status === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option value="Selesai" {{ $comp->status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px; color: var(--secondary);">
                                    <i class="fa-solid fa-comments" style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.5;"></i>
                                    <p>Belum ada data komplain pelanggan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-4" style="padding: 0 24px;">
                {{ $complaints->links() }}
            </div>
        </div>

    </div>
</x-admin-layout>
