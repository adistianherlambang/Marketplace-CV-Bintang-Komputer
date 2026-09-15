<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4">Daftar Komplain Pelanggan</h2>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
<<<<<<< HEAD
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice & Pelanggan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis & Detail Kendala</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Bukti (Nota / Produk)</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
=======
                                <td>
                                    @if ($comp->order)
                                        <strong>{{ $comp->order->invoice_number }}</strong>
                                    @else
                                        <span class="badge badge-secondary" style="background-color: var(--border); color: var(--secondary); font-size: 0.75rem;">Umum / Tanpa Invoice</span>
                                    @endif
                                    <div class="text-xs text-secondary" style="margin-top: 2px;">Pengadu: {{ $comp->customer_name }}</div>
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
>>>>>>> b4fa47c28bfaa5fee06e55f9358ca8bbe9db5d89
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm">
                            @forelse ($complaints as $complaint)
                                <tr>
                                    <td class="px-6 py-4">
                                        <strong>{{ optional($complaint->order)->invoice_number ?? '-' }}</strong><br>
                                        <span class="text-gray-600">{{ $complaint->customer_name }}</span><br>
                                        <small class="text-gray-400">{{ $complaint->customer_phone }}</small>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-red-600 text-xs">{{ $complaint->complaint_type }}</span>
                                        <p class="text-gray-600 text-xs mt-1">{{ $complaint->description }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center text-xs space-y-1">
                                        @if($complaint->nota_bukti)
                                            <div><a href="{{ asset('storage/' . $complaint->nota_bukti) }}" target="_blank" class="text-blue-600 underline">Lihat Nota</a></div>
                                        @endif
                                        @if($complaint->product_bukti)
                                            <div><a href="{{ asset('storage/' . $complaint->product_bukti) }}" target="_blank" class="text-blue-600 underline">Lihat Produk Rusak</a></div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-1 text-xs rounded font-semibold 
                                            {{ $complaint->status === 'Selesai' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $complaint->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $complaint->status === 'Diproses' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $complaint->status === 'Ditolak' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ $complaint->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('admin.complaints.status', $complaint->id) }}" method="POST" class="inline-flex flex-col gap-1">
                                            @csrf
                                            <select name="status" class="text-xs border-gray-300 rounded shadow-sm" onchange="this.form.submit()">
                                                <option value="Pending" {{ $complaint->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Diproses" {{ $complaint->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                                <option value="Selesai" {{ $complaint->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                                <option value="Ditolak" {{ $complaint->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data komplain dari pelanggan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $complaints->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>