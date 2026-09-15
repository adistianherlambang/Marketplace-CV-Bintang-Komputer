<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kecamatans', function (Blueprint $table) {
            if (Schema::hasColumn('kecamatans', 'tarif_grab')) {
                $table->dropColumn('tarif_grab');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kecamatans', function (Blueprint $table) {
            $table->integer('tarif_grab')->default(0);
        });
    }
};