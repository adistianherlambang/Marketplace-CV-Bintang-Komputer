<?php

use App\Http\Controllers\GuestCatalogController;
use App\Http\Controllers\ProfileController;
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
use Illuminate\Support\Facades\Route;

// --- Public Guest Catalog ---
Route::get('/', [GuestCatalogController::class, 'index'])->name('catalog.index');
Route::get('/products/{product}', [GuestCatalogController::class, 'show'])->name('catalog.show');

// --- Admin Section (Protected by Authentication) ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // CRUD Resource Controllers
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

});

// Profile Management (keeps standard Breeze route names, but uses /admin prefix)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Root /dashboard redirect to admin panel
Route::get('/dashboard', function() {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
