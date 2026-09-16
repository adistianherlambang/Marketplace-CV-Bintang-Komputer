<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'customer_user_id')) {
                $table->foreignId('customer_user_id')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'customer_user_id')) {
                $table->dropForeign(['customer_user_id']);
                $table->dropColumn('customer_user_id');
            }
        });
    }
};
