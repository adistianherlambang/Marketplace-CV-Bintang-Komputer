<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable();
            }
            if (!Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone')->nullable();
            }
            if (!Schema::hasColumn('orders', 'bukti_transfer')) {
                $table->string('bukti_transfer')->nullable();
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            if (!Schema::hasColumn('orders', 'total_amount')) {
                $table->decimal('total_amount', 12, 2)->default(0);
            }
            if (!Schema::hasColumn('orders', 'kecamatan_id')) {
                $table->unsignedBigInteger('kecamatan_id')->nullable();
            }
            if (!Schema::hasColumn('orders', 'kelurahan_id')) {
                $table->unsignedBigInteger('kelurahan_id')->nullable();
            }
            if (!Schema::hasColumn('orders', 'shipping_cost')) {
                $table->decimal('shipping_cost', 12, 2)->default(0);
            }
            if (!Schema::hasColumn('orders', 'shareloc_link')) {
                $table->text('shareloc_link')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name', 
                'customer_phone', 
                'bukti_transfer', 
                'payment_method', 
                'total_amount', 
                'kecamatan_id', 
                'kelurahan_id', 
                'shipping_cost', 
                'shareloc_link'
            ]);
        });
    }
};