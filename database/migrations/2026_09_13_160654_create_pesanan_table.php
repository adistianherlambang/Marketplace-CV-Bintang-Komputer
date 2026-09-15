<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_harga', 12, 2);
            $table->string('status_pembayaran')->default('pending'); // pending, lunas, batal
            $table->string('bukti_transfer')->nullable(); // Path foto bukti transfer dari pelanggan
            $table->string('status_pengiriman')->default('diproses'); // diproses, dikirim, selesai
            $table->string('nomor_resi')->nullable(); // No resi dari kurir
            $table->text('alamat_pengiriman');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};