<x-admin-layout>
    @section('header_title', 'Kelola Kategori Produk')

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules/products.module.css') }}">
    @endpush

    <div x-data="{ openCreateModal: false, openEditModal: false, currentCategory: {id: '', name: ''} }">
        
        <!-- Action Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="title">
                <h3 class="font-bold" style="font-size: 1.25rem;">Daftar Kategori</h3>
                <p class="text-secondary text-sm">Grup produk Anda agar mudah dicari oleh pembeli.</p>
            </div>
            
            <button @click="openCreateModal = true" class="btn btn-primary button">
                <i class="fa-solid fa-plus"></i> Tambah Kategori
            </button>
        </div>

        <!-- Table Grid -->
        <div class="table-container">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 80px;">No</th>
                            <th>Nama Kategori</th>
                            <th>Slug Kategori (URL)</th>
                            <th style="width: 200px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $index => $category)
                            <tr>
                                <td>{{ $categories->firstItem() + $index }}</td>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td style="text-align: center;">
                                    <div class="flex justify-center gap-2">
                                        <button @click="currentCategory = {id: '{{ $category->id }}', name: '{{ addslashes($category->name) }}'}; openEditModal = true" class="btn btn-secondary btn-sm">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </button>
                                        
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
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
                                    <i class="fa-solid fa-tags" style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.5;"></i>
                                    <p>Belum ada data kategori. Klik 'Tambah Kategori' untuk membuat baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $categories->links() }}
        </div>

        <!-- Create Modal -->
        <div class="modal-backdrop" :class="{ 'show': openCreateModal }">
            <div class="modal">
                <div class="modal-header">
                    <h3 class="modal-title">Tambah Kategori Baru</h3>
                    <button @click="openCreateModal = false" class="modal-close">&times;</button>
                </div>
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="name" class="form-control" required placeholder="Contoh: Laptop, Smartphone, Aksesoris">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="openCreateModal = false" class="btn btn-secondary">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal-backdrop" :class="{ 'show': openEditModal }">
            <div class="modal">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Kategori</h3>
                    <button @click="openEditModal = false" class="modal-close">&times;</button>
                </div>
                <!-- Dynamic form action updated by Alpine -->
                <form method="POST" :action="'/admin/categories/' + currentCategory.id">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="name" x-model="currentCategory.name" class="form-control" required>
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
