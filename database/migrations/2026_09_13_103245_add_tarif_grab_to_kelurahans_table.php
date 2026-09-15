<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelurahans', function (Blueprint $table) {
            if (!Schema::hasColumn('kelurahans', 'tarif_grab')) {
                $table->integer('tarif_grab')->default(0)->after('nama_kelurahan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kelurahans', function (Blueprint $table) {
            $table->dropColumn('tarif_grab');
        });
    }
};