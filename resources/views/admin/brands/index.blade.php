<x-admin-layout>
    @section('header_title', 'Kelola Merk / Brand Produk')

    <div x-data="{ openCreateModal: false, openEditModal: false, currentBrand: {id: '', name: ''} }">
        
        <!-- Action Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="font-bold" style="font-size: 1.25rem;">Daftar Merk / Brand</h3>
                <p class="text-secondary text-sm">Kelola merk dagang produk yang terdaftar pada sistem.</p>
            </div>
            
            <button @click="openCreateModal = true" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Tambah Merk
            </button>
        </div>

        <!-- Table Grid -->
        <div class="table-container">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 80px;">No</th>
                            <th>Nama Merk</th>
                            <th>Slug Merk (URL)</th>
                            <th style="width: 200px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($brands as $index => $brand)
                            <tr>
                                <td>{{ $brands->firstItem() + $index }}</td>
                                <td><strong>{{ $brand->name }}</strong></td>
                                <td><code>{{ $brand->slug }}</code></td>
                                <td style="text-align: center;">
                                    <div class="flex justify-center gap-2">
                                        <button @click="currentBrand = {id: '{{ $brand->id }}', name: '{{ addslashes($brand->name) }}'}; openEditModal = true" class="btn btn-secondary btn-sm">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </button>
                                        
                                        <form method="POST" action="{{ route('admin.brands.destroy', $brand->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus merk ini?')">
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
                                <td colspan="4" style="text-align: center; padding: 40px; color: var(--secondary);">
                                    <i class="fa-solid fa-copyright" style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.5;"></i>
                                    <p>Belum ada data merk. Klik 'Tambah Merk' untuk membuat baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $brands->links() }}
        </div>

        <!-- Create Modal -->
        <div class="modal-backdrop" :class="{ 'show': openCreateModal }">
            <div class="modal">
                <div class="modal-header">
                    <h3 class="modal-title">Tambah Merk Baru</h3>
                    <button @click="openCreateModal = false" class="modal-close">&times;</button>
                </div>
                <form method="POST" action="{{ route('admin.brands.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nama Merk</label>
                            <input type="text" name="name" class="form-control" required placeholder="Contoh: Asus, Lenovo, Samsung, Apple">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="openCreateModal = false" class="btn btn-secondary">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Merk</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal-backdrop" :class="{ 'show': openEditModal }">
            <div class="modal">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Merk</h3>
                    <button @click="openEditModal = false" class="modal-close">&times;</button>
                </div>
                <form method="POST" :action="'/admin/brands/' + currentBrand.id">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nama Merk</label>
                            <input type="text" name="name" x-model="currentBrand.name" class="form-control" required>
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
