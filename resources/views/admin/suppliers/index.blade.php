<x-admin-layout>
    @section('header_title', 'Kelola Supplier / Pemasok Barang')

    <div x-data="{ openCreateModal: false, openEditModal: false, currentSupplier: {id: '', name: '', contact_phone: '', email: '', address: ''} }">
        
        <!-- Action Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="font-bold" style="font-size: 1.25rem;">Daftar Pemasok / Supplier</h3>
                <p class="text-secondary text-sm">Kelola data vendor pemasok stok barang untuk toko Anda.</p>
            </div>
            
            <button @click="openCreateModal = true" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Tambah Supplier
            </button>
        </div>

        <!-- Table Grid -->
        <div class="table-container">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 80px;">No</th>
                            <th>Nama Supplier</th>
                            <th>No. Telepon</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th style="width: 200px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $index => $supplier)
                            <tr>
                                <td>{{ $suppliers->firstItem() + $index }}</td>
                                <td><strong>{{ $supplier->name }}</strong></td>
                                <td>{{ $supplier->contact_phone ?: '-' }}</td>
                                <td>{{ $supplier->email ?: '-' }}</td>
                                <td style="max-width: 240px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $supplier->address ?: '-' }}
                                </td>
                                <td style="text-align: center;">
                                    <div class="flex justify-center gap-2">
                                        <button @click="currentSupplier = {
                                            id: '{{ $supplier->id }}', 
                                            name: '{{ addslashes($supplier->name) }}',
                                            contact_phone: '{{ $supplier->contact_phone }}',
                                            email: '{{ $supplier->email }}',
                                            address: '{{ addslashes($supplier->address) }}'
                                        }; openEditModal = true" class="btn btn-secondary btn-sm">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </button>
                                        
                                        <form method="POST" action="{{ route('admin.suppliers.destroy', $supplier->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa-solid fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: var(--secondary);">
                                    <i class="fa-solid fa-truck-field" style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.5;"></i>
                                    <p>Belum ada data supplier. Klik 'Tambah Supplier' untuk membuat baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $suppliers->links() }}
        </div>

        <!-- Create Modal -->
        <div class="modal-backdrop" :class="{ 'show': openCreateModal }">
            <div class="modal">
                <div class="modal-header">
                    <h3 class="modal-title">Tambah Supplier Baru</h3>
                    <button @click="openCreateModal = false" class="modal-close">&times;</button>
                </div>
                <form method="POST" action="{{ route('admin.suppliers.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nama Supplier / Vendor</label>
                            <input type="text" name="name" class="form-control" required placeholder="Contoh: PT. Bintang Jaya Tech">
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. Telepon / WhatsApp</label>
                            <input type="text" name="contact_phone" class="form-control" placeholder="Contoh: 081234567890">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alamat Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Contoh: vendor@gmail.com">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alamat Kantor / Gudang</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Contoh: Jl. Diponegoro No. 12, Metro"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="openCreateModal = false" class="btn btn-secondary">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Supplier</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal-backdrop" :class="{ 'show': openEditModal }">
            <div class="modal">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Supplier</h3>
                    <button @click="openEditModal = false" class="modal-close">&times;</button>
                </div>
                <form method="POST" :action="'/admin/suppliers/' + currentSupplier.id">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nama Supplier / Vendor</label>
                            <input type="text" name="name" x-model="currentSupplier.name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. Telepon / WhatsApp</label>
                            <input type="text" name="contact_phone" x-model="currentSupplier.contact_phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alamat Email</label>
                            <input type="email" name="email" x-model="currentSupplier.email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alamat Kantor / Gudang</label>
                            <textarea name="address" x-model="currentSupplier.address" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="openEditModal = false" class="btn btn-secondary">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-admin-layout>
