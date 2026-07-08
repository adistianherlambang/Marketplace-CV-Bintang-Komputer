<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Katalog - CV Bintang Jaya Komputer</title>

    <!-- FontAwesome & Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/global-utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules/catalog-index.module.css') }}">
    @stack('styles')

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    <!-- Header -->
    <header class="guest-header">
        <div class="container guest-navbar">
            <a href="{{ route('catalog.index') }}" class="brand-logo">
                <span>Bintang Komputer</span>
            </a>
            
            <div class="flex items-center gap-6">
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm">
                        Admin Panel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">
                        Login Admin
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Content -->
    <main>
        @if (session('success'))
            <div class="container" style="margin-top: 20px;">
                <div class="alert alert-success" style="background-color: var(--success-light); border: 1px solid var(--success); color: #065f46; padding: 16px; border-radius: var(--radius); display: flex; align-items: center; gap: 12px; font-weight: 500; box-shadow: var(--shadow-sm);">
                    <i class="fa-solid fa-circle-check" style="font-size: 1.25rem; color: var(--success);"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if (session('error'))
            <div class="container" style="margin-top: 20px;">
                <div class="alert alert-danger" style="background-color: var(--danger-light); border: 1px solid var(--danger); color: #991b1b; padding: 16px; border-radius: var(--radius); display: flex; align-items: center; gap: 12px; font-weight: 500; box-shadow: var(--shadow-sm);">
                    <i class="fa-solid fa-circle-exclamation" style="font-size: 1.25rem; color: var(--danger);"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="catalog-footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} CV Bintang Jaya Komputer. All rights reserved.</p>
            <p class="catalog-footer-address">Jl. Ahmad Yani No.68, Iringmulyo, Kota Metro, Lampung</p>
        </div>
    </footer>
    <!-- Floating Complaint Widget -->
    <div x-data="{ 
        widgetOpen: false, 
        name: '', 
        contact: '', 
        invoice: '', 
        message: '',
        isSubmitting: false,
        submitSuccess: false,
        submitMessage: '',
        
        async submitComplaint() {
            if (!this.name || !this.contact || !this.message) {
                alert('Nama, Kontak, dan Detail Komplain harus diisi.');
                return;
            }
            this.isSubmitting = true;
            try {
                let response = await fetch('{{ route('complaints.guest.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        customer_name: this.name,
                        contact: this.contact,
                        invoice_number: this.invoice,
                        complaint_text: this.message
                    })
                });
                
                let result = await response.json();
                if (response.ok && result.success) {
                    this.submitSuccess = true;
                    this.submitMessage = result.message;
                    // Reset form
                    this.name = '';
                    this.contact = '';
                    this.invoice = '';
                    this.message = '';
                } else {
                    alert(result.message || 'Terjadi kesalahan saat mengirim komplain.');
                }
            } catch (err) {
                console.error(err);
                alert('Gagal mengirim komplain. Periksa koneksi Anda.');
            } finally {
                this.isSubmitting = false;
            }
        }
    }" style="position: fixed; bottom: 24px; right: 24px; z-index: 99999;">
        <!-- Floating Trigger Button -->
        <button type="button" @click="widgetOpen = !widgetOpen" style="background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%); color: var(--white); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 18px rgba(220, 38, 38, 0.4); border: none; cursor: pointer; transition: all 0.3s ease; position: relative;" :style="widgetOpen ? 'transform: rotate(90deg); background: var(--secondary); box-shadow: 0 4px 18px rgba(100, 116, 139, 0.4);' : ''" title="Komplain Pelanggan">
            <i class="fa-solid fa-headset" style="font-size: 1.5rem;" x-show="!widgetOpen"></i>
            <i class="fa-solid fa-xmark" style="font-size: 1.5rem;" x-show="widgetOpen" x-cloak></i>
            <!-- Small indicator dot -->
            <span style="position: absolute; top: 0; right: 0; width: 14px; height: 14px; background-color: var(--warning); border-radius: 50%; border: 2px solid var(--white);" x-show="!widgetOpen"></span>
        </button>

        <!-- Complaint Form Card -->
        <div x-show="widgetOpen" x-cloak style="position: absolute; bottom: 80px; right: 0; width: 350px; background: var(--white); border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); border: 1px solid var(--border); overflow: hidden; display: flex; flex-direction: column; transition: all 0.3s ease;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-4 scale-95">
            <!-- Widget Header -->
            <div style="background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%); color: var(--white); padding: 20px; display: flex; flex-direction: column; gap: 4px;">
                <h4 style="margin: 0; font-weight: 700; font-size: 1.1rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-comments"></i> Layanan Pengaduan
                </h4>
                <p style="margin: 0; font-size: 0.8rem; opacity: 0.9; line-height: 1.3;">Ada kendala dengan produk atau layanan kami? Sampaikan di sini, admin kami akan segera membantu.</p>
            </div>

            <!-- Widget Body -->
            <div style="padding: 20px; max-height: 400px; overflow-y: auto;">
                <!-- Success Message -->
                <div x-show="submitSuccess" style="text-align: center; padding: 20px 10px;" x-cloak>
                    <div style="width: 64px; height: 64px; background: var(--success-light); color: var(--success); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <i class="fa-solid fa-check" style="font-size: 2rem;"></i>
                    </div>
                    <h5 style="font-weight: 700; margin-bottom: 8px; font-size: 1rem; color: var(--dark);">Terima Kasih!</h5>
                    <p style="font-size: 0.85rem; color: var(--secondary); line-height: 1.4; margin-bottom: 20px;" x-text="submitMessage"></p>
                    <button type="button" @click="submitSuccess = false" class="btn btn-secondary" style="font-size: 0.85rem; padding: 8px 16px; width: 100%;">Kirim Pengaduan Baru</button>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitComplaint()" x-show="!submitSuccess">
                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <label style="font-size: 0.775rem; font-weight: 600; color: var(--secondary);">Nama Lengkap</label>
                            <input type="text" x-model="name" style="width: 100%; padding: 10px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.875rem; outline: none; background: var(--white); color: var(--dark);" required placeholder="Masukkan nama Anda">
                        </div>
                        
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <label style="font-size: 0.775rem; font-weight: 600; color: var(--secondary);">No. Telepon / WhatsApp</label>
                            <input type="text" x-model="contact" style="width: 100%; padding: 10px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.875rem; outline: none; background: var(--white); color: var(--dark);" required placeholder="Contoh: 0812XXXXXXXX">
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <label style="font-size: 0.775rem; font-weight: 600; color: var(--secondary);">Nomor Invoice (Opsional)</label>
                            <input type="text" x-model="invoice" style="width: 100%; padding: 10px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.875rem; outline: none; background: var(--white); color: var(--dark);" placeholder="Contoh: INV-2026XXXXXXXX">
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <label style="font-size: 0.775rem; font-weight: 600; color: var(--secondary);">Detail Komplain</label>
                            <textarea x-model="message" rows="3" style="width: 100%; padding: 10px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.875rem; outline: none; resize: vertical; background: var(--white); color: var(--dark);" required placeholder="Jelaskan secara rinci kendala Anda..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%); border: none; padding: 12px; font-weight: 600; font-size: 0.9rem; border-radius: var(--radius-sm); color: var(--white); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 8px; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);" :disabled="isSubmitting">
                            <span x-show="!isSubmitting"><i class="fa-solid fa-paper-plane"></i> Kirim Komplain</span>
                            <span x-show="isSubmitting" x-cloak><i class="fa-solid fa-spinner fa-spin"></i> Mengirim...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

