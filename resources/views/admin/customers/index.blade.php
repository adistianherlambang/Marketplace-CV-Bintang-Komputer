<x-admin-layout>
    @section('header_title', 'Kelola Pelanggan')

    <div x-data="{ openCreateModal: false, openEditModal: false, currentCustomer: {id: '', name: '', phone: '', email: '', address: ''} }">
        
        <!-- Action Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="font-bold" style="font-size: 1.25rem;">Daftar Pelanggan</h3>
                <p class="text-secondary text-sm">Kelola data pelanggan yang berbelanja di toko Anda.</p>
            </div>
            
            <button @click="openCreateModal = true" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Tambah Pelanggan
            </button>
        </div>

        <!-- Table Grid -->
        <div class="table-container">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 80px;">No</th>
                            <th>Nama Pelanggan</th>
                            <th>No. HP / Telepon</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th style="width: 200px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $index => $customer)
                            <tr>
                                <td>{{ $customers->firstItem() + $index }}</td>
                                <td><strong>{{ $customer->name }}</strong></td>
                                <td>{{ $customer->phone ?: '-' }}</td>
                                <td>{{ $customer->email ?: '-' }}</td>
                                <td style="max-width: 240px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $customer->address ?: '-' }}
                                </td>
                                <td style="text-align: center;">
                                    <div class="flex justify-center gap-2">
                                        <button @click="currentCustomer = {
                                            id: '{{ $customer->id }}', 
                                            name: '{{ addslashes($customer->name) }}',
                                            phone: '{{ $customer->phone }}',
                                            email: '{{ $customer->email }}',
                                            address: '{{ addslashes($customer->address) }}'
                                        }; openEditModal = true" class="btn btn-secondary btn-sm">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </button>
                                        
                                        <form method="POST" action="{{ route('admin.customers.destroy', $customer->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
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
                                    <i class="fa-solid fa-users" style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.5;"></i>
                                    <p>Belum ada data pelanggan. Klik 'Tambah Pelanggan' untuk membuat baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $customers->links() }}
        </div>

        <!-- Create Modal -->
        <div class="modal-backdrop" :class="{ 'show': openCreateModal }">
            <div class="modal">
                <div class="modal-header">
                    <h3 class="modal-title">Tambah Pelanggan Baru</h3>
                    <button @click="openCreateModal = false" class="modal-close">&times;</button>
                </div>
                <form method="POST" action="{{ route('admin.customers.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" required placeholder="Contoh: Budi Santoso">
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. HP / Telepon</label>
                            <input type="text" name="phone" class="form-control" placeholder="Contoh: 085712345678">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Contoh: budi@gmail.com">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alamat Rumah / Kantor</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Contoh: Jl. Ki Hajar Dewantara No. 5, Metro Barat"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="openCreateModal = false" class="btn btn-secondary">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Pelanggan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal-backdrop" :class="{ 'show': openEditModal }">
            <div class="modal">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Pelanggan</h3>
                    <button @click="openEditModal = false" class="modal-close">&times;</button>
                </div>
                <form method="POST" :action="'/admin/customers/' + currentCustomer.id">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" x-model="currentCustomer.name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. HP / Telepon</label>
                            <input type="text" name="phone" x-model="currentCustomer.phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" x-model="currentCustomer.email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alamat Rumah / Kantor</label>
                            <textarea name="address" x-model="currentCustomer.address" class="form-control" rows="3"></textarea>
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
