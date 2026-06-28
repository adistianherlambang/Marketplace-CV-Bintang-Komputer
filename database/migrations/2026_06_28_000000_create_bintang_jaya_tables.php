<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Customers Table
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Categories Table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 3. Brands Table
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 4. Suppliers Table
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // 5. Products Table
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->string('barcode')->nullable()->unique();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->decimal('price_modal', 12, 2);
            $table->decimal('price_jual', 12, 2);
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(5);
            $table->text('description')->nullable();
            $table->text('specs')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('name');
            $table->index('sku');
        });

        // 6. Product Images Table
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('path');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // 7. Stock Histories Table
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('type'); // in, out, edit, delete, return
            $table->integer('quantity');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // admin who updated
            $table->timestamp('date');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // 8. Orders Table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // admin cashier
            $table->string('status')->default('Belum Dibayar'); // Belum Dibayar, Lunas, Dibatalkan
            $table->decimal('total_amount', 12, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 9. Order Items Table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('item_name'); // product name (copied at purchase time or manually typed)
            $table->decimal('price', 12, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // 10. Payments Table
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('payment_method')->default('Cash'); // Cash, Transfer, dll
            $table->decimal('amount_paid', 12, 2);
            $table->string('payment_status')->default('Lunas'); // Lunas, Belum Lunas
            $table->timestamp('payment_date');
            $table->timestamps();
        });

        // 11. Complaints Table
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('customer_name');
            $table->string('contact');
            $table->text('complaint_text');
            $table->string('status')->default('Menunggu'); // Menunggu, Diproses, Selesai
            $table->timestamp('date');
            $table->timestamps();
        });

        // 12. Returns Table
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->text('reason');
            $table->string('status')->default('Menunggu'); // Menunggu, Disetujui, Ditolak
            $table->timestamp('date');
            $table->timestamps();
        });

        // 13. Monthly Reports Table
        Schema::create('monthly_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_month'); // e.g. 2026-06
            $table->decimal('total_sales', 12, 2)->default(0);
            $table->decimal('total_earnings', 12, 2)->default(0);
            $table->integer('total_transactions')->default(0);
            $table->timestamp('generated_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function up_reverse_methods(): void
    {
        // Not used, using down() method instead
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_reports');
        Schema::dropIfExists('returns');
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('stock_histories');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('customers');
    }
};
