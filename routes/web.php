<?php

use App\Http\Controllers\GuestCatalogController;
<<<<<<< HEAD
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerAuthController;
=======
use App\Http\Controllers\GuestActionController;
>>>>>>> b4fa47c28bfaa5fee06e55f9358ca8bbe9db5d89
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\CustomerComplaintController; // <-- TAMBAHKAN INI DI BAGIAN ATAS
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\Admin\ReportController;
<<<<<<< HEAD
use App\Http\Controllers\Admin\KelolaPesananController;
=======
use App\Http\Controllers\Admin\BookingController;
>>>>>>> b4fa47c28bfaa5fee06e55f9358ca8bbe9db5d89
use Illuminate\Support\Facades\Route;

// --- Public Guest Catalog ---
Route::get('/', [GuestCatalogController::class, 'index'])->name('catalog.index');
Route::get('/products/{product}', [GuestCatalogController::class, 'show'])->name('catalog.show');
Route::post('/products/{product}/book', [GuestActionController::class, 'storeBooking'])->name('catalog.book');
Route::post('/complaints/send', [GuestActionController::class, 'storeComplaint'])->name('complaints.guest.store');

// --- Protected Checkout & Customer Area (Wajib Login) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
    
    // Riwayat & Pelacakan Pesanan Pelanggan
    Route::get('/riwayat-pesanan', [CustomerOrderController::class, 'index'])->name('customer.orders.index');
    
    // --- TAMBAHKAN RUTE KOMPLAIN PELANGGAN DI SINI ---
    Route::get('/riwayat-pesanan/{id}/komplain', [CustomerComplaintController::class, 'create'])->name('customer.complaints.create');
    Route::post('/riwayat-pesanan/{id}/komplain', [CustomerComplaintController::class, 'store'])->name('customer.complaints.store');
});
// Konfirmasi Pesanan Selesai oleh Customer
Route::post('/riwayat-pesanan/{id}/selesai', [CustomerOrderController::class, 'konfirmasiSelesai'])->name('customer.orders.selesai');

// --- Admin Section (Protected by Authentication) ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // CRUD Resource Controllers (Lengkap)
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('brands', BrandController::class)->except(['create', 'show', 'edit']);
    Route::resource('suppliers', SupplierController::class)->except(['show']);
    Route::resource('customers', CustomerController::class)->except(['show']);

    // Stock Management & History
    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::post('/stocks/adjust', [StockController::class, 'adjust'])->name('stocks.adjust');

    // POS cashier and Invoice actions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{order}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{order}/pay', [TransactionController::class, 'updatePaymentStatus'])->name('transactions.pay');
    Route::post('/transactions/{order}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');
    Route::get('/transactions/{order}/invoice', [TransactionController::class, 'invoicePdf'])->name('transactions.invoice');
    Route::get('/transactions/{order}/nota', [TransactionController::class, 'notaPdf'])->name('transactions.nota');
    Route::get('/transactions/{order}/nota-online', [TransactionController::class, 'notaOnlinePdf'])->name('transactions.nota.online');

    // Kelola Pesanan (E-commerce Order & Shipping Management)
    Route::get('/pesanan', [KelolaPesananController::class, 'index'])->name('pesanan.index');
    Route::put('/pesanan/{id}', [KelolaPesananController::class, 'updateStatus'])->name('pesanan.update');

    // Complaints Log
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::post('/complaints/{complaint}/status', [ComplaintController::class, 'updateStatus'])->name('complaints.status');

    // Returns Log & Approval
    Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
    Route::post('/returns', [ReturnController::class, 'store'])->name('returns.store');
    Route::post('/returns/{returnLog}/approve', [ReturnController::class, 'approve'])->name('returns.approve');
    Route::post('/returns/{returnLog}/reject', [ReturnController::class, 'reject'])->name('returns.reject');

    // Reports PDF Export
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/preview', [ReportController::class, 'preview'])->name('reports.preview');
    Route::get('/reports/download', [ReportController::class, 'downloadPdf'])->name('reports.download');

    // Product Bookings Management
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.status');

});

// Profile Management
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Root /dashboard redirect dinamis (Admin ke dashboard admin, Customer ke katalog)
Route::get('/dashboard', function() {
    $user = \Illuminate\Support\Facades\Auth::user();
    if ($user && strtolower(trim($user->email)) === 'admin@bintangkomputer.com') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('catalog.index');
})->middleware(['auth'])->name('dashboard');

// --- AUTHENTICATION (SATU PINTU) ---
Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login']);

Route::get('/customer/register', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
Route::post('/customer/register', [CustomerAuthController::class, 'register']);
Route::post('/customer/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');