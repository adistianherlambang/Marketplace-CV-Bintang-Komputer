<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            if (!Schema::hasColumn('complaints', 'customer_id')) {
                $table->unsignedBigInteger('customer_id')->nullable()->after('order_id');
            }
            if (!Schema::hasColumn('complaints', 'customer_phone')) {
                $table->string('customer_phone')->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('complaints', 'complaint_type')) {
                $table->string('complaint_type')->nullable()->after('customer_phone');
            }
            if (!Schema::hasColumn('complaints', 'description')) {
                $table->text('description')->nullable()->after('complaint_type');
            }
            if (!Schema::hasColumn('complaints', 'nota_bukti')) {
                $table->string('nota_bukti')->nullable()->after('description');
            }
            if (!Schema::hasColumn('complaints', 'product_bukti')) {
                $table->string('product_bukti')->nullable()->after('nota_bukti');
            }
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn([
                'customer_id',
                'customer_phone',
                'complaint_type',
                'description',
                'nota_bukti',
                'product_bukti',
            ]);
        });
    }
};
