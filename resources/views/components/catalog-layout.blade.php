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
    }" style="position: fixed; bottom: 24px; right: 24px; z-index: 99999; font-family: var(--font);">
        
        <!-- Style tag for micro-interactions & pulsing animation -->
        <style>
            @keyframes pulse-indicator-dot {
                0% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
                70% { transform: scale(1.1); box-shadow: 0 0 0 6px rgba(245, 158, 11, 0); }
                100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
            }
            .pulse-dot {
                animation: pulse-indicator-dot 2s infinite;
            }
            .floating-btn {
                position: relative;
                width: 62px;
                height: 62px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                border: none;
                cursor: pointer;
                color: var(--white);
                background: var(--primary-gradient);
                box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .floating-btn:hover {
                transform: translateY(-4px) scale(1.06);
                box-shadow: 0 10px 24px rgba(37, 99, 235, 0.45) !important;
            }
            .floating-btn.widget-open {
                transform: rotate(90deg) !important;
                background: var(--secondary) !important;
                box-shadow: 0 6px 20px rgba(100, 116, 139, 0.35) !important;
            }
            .complaint-input-field:focus {
                border-color: var(--primary) !important;
                box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
                background-color: var(--white) !important;
            }
        </style>

        <!-- Floating Trigger Button -->
        <button
            type="button"
            @click="widgetOpen = !widgetOpen"
            class="floating-btn"
            :class="{ 'widget-open': widgetOpen }"
            title="Layanan Pengaduan"
        >
            <i class="fa-solid fa-headset" style="font-size: 1.55rem;" x-show="!widgetOpen"></i>
            <i class="fa-solid fa-xmark" style="font-size: 1.55rem;" x-show="widgetOpen" x-cloak></i>
            <!-- Pulsing Notification Dot -->
            <span class="pulse-dot" style="position: absolute; top: 0; right: 0; width: 15px; height: 15px; background-color: var(--warning); border-radius: 50%; border: 2.5px solid var(--white);" x-show="!widgetOpen"></span>
        </button>

        <!-- Complaint Form Card -->
        <div x-show="widgetOpen" x-cloak style="position: absolute; bottom: 82px; right: 0; width: 360px; background: var(--white); border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); border: 1px solid var(--border); overflow: hidden; display: flex; flex-direction: column; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);" x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 translate-y-6 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-6 scale-95">
            
            <!-- Widget Header -->
            <div style="background: var(--primary-gradient); color: var(--white); padding: 22px 20px; display: flex; align-items: center; gap: 14px;">
                <div style="width: 46px; height: 46px; background: rgba(255, 255, 255, 0.18); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; box-shadow: var(--shadow-sm);">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <div style="display: flex; flex-direction: column; gap: 2px;">
                    <h4 style="margin: 0; font-weight: 700; font-size: 1.05rem; letter-spacing: -0.2px;">Layanan Pengaduan</h4>
                    <p style="margin: 0; font-size: 0.775rem; opacity: 0.88; line-height: 1.35;">Ada kendala? Laporkan kepada admin kami.</p>
                </div>
            </div>

            <!-- Widget Body -->
            <div style="padding: 22px 20px; max-height: 420px; overflow-y: auto; background-color: #fafbfc;">
                <!-- Success Message -->
                <div x-show="submitSuccess" style="text-align: center; padding: 24px 10px;" x-cloak>
                    <div style="width: 68px; height: 68px; background: var(--success-light); color: var(--success); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; box-shadow: var(--shadow-sm);">
                        <i class="fa-solid fa-check" style="font-size: 2.2rem;"></i>
                    </div>
                    <h5 style="font-weight: 700; margin-bottom: 8px; font-size: 1.1rem; color: var(--dark);">Laporan Terkirim!</h5>
                    <p style="font-size: 0.85rem; color: var(--secondary); line-height: 1.45; margin-bottom: 24px;" x-text="submitMessage"></p>
                    <button type="button" @click="submitSuccess = false" class="btn btn-secondary" style="font-size: 0.85rem; padding: 10px 16px; width: 100%; border-radius: var(--radius-sm); font-weight: 600;">Kirim Pengaduan Baru</button>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitComplaint()" x-show="!submitSuccess">
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        
                        <!-- Name Input -->
                        <div style="display: flex; flex-direction: column; gap: 5px;">
                            <label style="font-size: 0.775rem; font-weight: 650; color: var(--secondary); display: flex; align-items: center; gap: 6px;">
                                <i class="fa-regular fa-user" style="font-size: 0.85rem; color: var(--primary);"></i> Nama Lengkap
                            </label>
                            <input type="text" x-model="name" class="complaint-input-field" style="width: 100%; padding: 11px 14px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.875rem; outline: none; background: #f1f5f9; color: var(--dark); transition: all 0.2s ease-in-out;" required placeholder="Masukkan nama Anda">
                        </div>
                        
                        <!-- Phone Input -->
                        <div style="display: flex; flex-direction: column; gap: 5px;">
                            <label style="font-size: 0.775rem; font-weight: 650; color: var(--secondary); display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-phone" style="font-size: 0.85rem; color: var(--primary);"></i> No. Telepon / WhatsApp
                            </label>
                            <input type="text" x-model="contact" class="complaint-input-field" style="width: 100%; padding: 11px 14px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.875rem; outline: none; background: #f1f5f9; color: var(--dark); transition: all 0.2s ease-in-out;" required placeholder="Contoh: 0812XXXXXXXX">
                        </div>

                        <!-- Invoice Number Input (Optional) -->
                        <div style="display: flex; flex-direction: column; gap: 5px;">
                            <label style="font-size: 0.775rem; font-weight: 650; color: var(--secondary); display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-file-invoice" style="font-size: 0.85rem; color: var(--primary);"></i> Nomor Invoice (Opsional)
                            </label>
                            <input type="text" x-model="invoice" class="complaint-input-field" style="width: 100%; padding: 11px 14px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.875rem; outline: none; background: #f1f5f9; color: var(--dark); transition: all 0.2s ease-in-out;" placeholder="Contoh: INV-2026XXXXXXXX">
                        </div>

                        <!-- Message Textarea -->
                        <div style="display: flex; flex-direction: column; gap: 5px;">
                            <label style="font-size: 0.775rem; font-weight: 650; color: var(--secondary); display: flex; align-items: center; gap: 6px;">
                                <i class="fa-regular fa-comment-dots" style="font-size: 0.85rem; color: var(--primary);"></i> Detail Komplain
                            </label>
                            <textarea x-model="message" rows="3" class="complaint-input-field" style="width: 100%; padding: 11px 14px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.875rem; outline: none; resize: vertical; background: #f1f5f9; color: var(--dark); transition: all 0.2s ease-in-out; line-height: 1.4;" required placeholder="Jelaskan secara rinci kendala produk atau layanan..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary" style="background: var(--primary-gradient); border: none; padding: 12px; font-weight: 700; font-size: 0.925rem; border-radius: var(--radius-sm); color: var(--white); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 6px; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25); transition: all 0.2s;" :disabled="isSubmitting">
                            <span x-show="!isSubmitting"><i class="fa-solid fa-paper-plane"></i> Kirim Pengaduan</span>
                            <span x-show="isSubmitting" x-cloak><i class="fa-solid fa-spinner fa-spin"></i> Mengirim...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

