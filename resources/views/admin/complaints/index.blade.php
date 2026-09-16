<x-admin-layout>
    @section('header_title', 'Komplain Pelanggan')

    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="font-bold text-xl text-dark">Daftar Komplain Pelanggan</h3>
            <p class="text-secondary text-sm">Kelola keluhan, komplain barang, dan bukti nota / kerusakan dari pelanggan.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-6" style="background-color: var(--success-light); border: 1px solid var(--success); color: #065f46; padding: 14px 18px; border-radius: var(--radius); display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="table-container" x-data="{ previewImage: null }">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 22%;">Invoice &amp; Pelanggan</th>
                        <th style="width: 30%;">Kendala &amp; Keterangan</th>
                        <th style="width: 20%; text-align: center;">Bukti Foto</th>
                        <th style="width: 13%; text-align: center;">Status</th>
                        <th style="width: 15%; text-align: center;">Ubah Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($complaints as $complaint)
                        <tr>
                            <td>
                                <strong class="text-primary">{{ optional($complaint->order)->invoice_number ?? 'Tanpa Invoice' }}</strong>
                                <div class="font-semibold text-dark mt-1">{{ $complaint->customer_name }}</div>
                                <div class="text-xs text-secondary"><i class="fa-solid fa-phone mr-1"></i>{{ $complaint->customer_phone ?? $complaint->contact ?? '-' }}</div>
                                <div class="text-xs text-secondary mt-1">{{ $complaint->created_at ? $complaint->created_at->format('d M Y H:i') : '-' }}</div>
                            </td>
                            <td>
                                @if($complaint->complaint_type)
                                    <span class="badge badge-danger text-xs mb-1">{{ $complaint->complaint_type }}</span>
                                @endif
                                <p class="text-sm text-dark" style="margin-top: 4px; line-height: 1.4;">
                                    {{ $complaint->description ?? $complaint->complaint_text }}
                                </p>
                            </td>
                            <td style="text-align: center;">
                                <div class="flex flex-col gap-1 items-center justify-center">
                                    @if($complaint->nota_bukti)
                                        <button type="button" @click="previewImage = '{{ asset('storage/' . $complaint->nota_bukti) }}'" class="btn btn-secondary btn-sm" style="padding: 4px 10px; font-size: 0.75rem; border-radius: 6px; width: 130px;">
                                            <i class="fa-solid fa-receipt text-primary mr-1"></i> Foto Nota
                                        </button>
                                    @endif
                                    @if($complaint->product_bukti)
                                        <button type="button" @click="previewImage = '{{ asset('storage/' . $complaint->product_bukti) }}'" class="btn btn-secondary btn-sm" style="padding: 4px 10px; font-size: 0.75rem; border-radius: 6px; width: 130px;">
                                            <i class="fa-solid fa-image text-danger mr-1"></i> Produk Rusak
                                        </button>
                                    @endif
                                    @if(!$complaint->nota_bukti && !$complaint->product_bukti)
                                        <span class="text-xs text-secondary">-</span>
                                    @endif
                                </div>
                            </td>
                            <td style="text-align: center;">
                                @if ($complaint->status === 'Selesai')
                                    <span class="badge badge-success">Selesai</span>
                                @elseif ($complaint->status === 'Diproses')
                                    <span class="badge badge-warning">Diproses</span>
                                @elseif ($complaint->status === 'Ditolak')
                                    <span class="badge badge-danger">Ditolak</span>
                                @else
                                    <span class="badge" style="background: #fef3c7; color: #b45309;">{{ $complaint->status ?? 'Pending' }}</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <form action="{{ route('admin.complaints.status', $complaint->id) }}" method="POST">
                                    @csrf
                                    <select name="status" class="form-control" style="padding: 6px 10px; font-size: 0.8rem; border-radius: var(--radius-sm); border: 1px solid var(--border);" onchange="this.form.submit()">
                                        <option value="Pending" {{ in_array($complaint->status, ['Pending', 'Menunggu']) ? 'selected' : '' }}>Pending</option>
                                        <option value="Diproses" {{ $complaint->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="Selesai" {{ $complaint->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="Ditolak" {{ $complaint->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="td-empty" style="text-align: center; padding: 32px; color: var(--secondary);">
                                <i class="fa-regular fa-comment-dots" style="font-size: 2rem; margin-bottom: 8px; display: block; opacity: 0.5;"></i>
                                Belum ada komplain dari pelanggan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($complaints, 'links'))
            <div class="mt-4">
                {{ $complaints->links() }}
            </div>
        @endif

        <!-- Image Preview Modal -->
        <div x-show="previewImage" class="modal-backdrop show" style="z-index: 99999; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); background: rgba(0,0,0,0.6);" @click="previewImage = null" x-cloak>
            <div class="modal" @click.stop style="max-width: 600px; padding: 20px; background: white; border-radius: var(--radius); text-align: center;">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="font-bold text-dark">Bukti Komplain</h4>
                    <button type="button" @click="previewImage = null" class="btn btn-secondary btn-sm" style="border: none; background: #f1f5f9;">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <img :src="previewImage" alt="Bukti Foto" style="max-width: 100%; max-height: 70vh; border-radius: 8px; object-fit: contain; box-shadow: var(--shadow-md);">
            </div>
        </div>
    </div>
</x-admin-layout>