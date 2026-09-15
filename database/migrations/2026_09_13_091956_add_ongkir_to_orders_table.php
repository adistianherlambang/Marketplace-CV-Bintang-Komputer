<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('kecamatan_id')->nullable()->constrained('kecamatans')->onDelete('set null');
            $table->foreignId('kelurahan_id')->nullable()->constrained('kelurahans')->onDelete('set null');
            $table->integer('shipping_cost')->default(0); 
            $table->text('shareloc_link')->nullable(); 
        });
    }
    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_id']);
            $table->dropForeign(['kelurahan_id']);
            $table->dropColumn(['kecamatan_id', 'kelurahan_id', 'shipping_cost', 'shareloc_link']);
        });
    }
};