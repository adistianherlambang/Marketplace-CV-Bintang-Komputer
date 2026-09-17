<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClearTransactionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:clear {--force : Force the operation to run without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kosongkan seluruh riwayat transaksi, invoice, pesanan, pembayaran, komplain, dan retur untuk keperluan pengujian (testing)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('Apakah Anda yakin ingin mengosongkan seluruh data transaksi & pesanan?')) {
            $this->info('Operasi dibatalkan.');
            return 0;
        }

        $this->info('Mengosongkan tabel transaksi...');

        Schema::disableForeignKeyConstraints();

        try {
            DB::table('order_items')->truncate();
            $this->line('✓ Tabel order_items dikosongkan.');

            DB::table('payments')->truncate();
            $this->line('✓ Tabel payments dikosongkan.');

            DB::table('complaints')->truncate();
            $this->line('✓ Tabel complaints dikosongkan.');

            DB::table('returns')->truncate();
            $this->line('✓ Tabel returns dikosongkan.');

            DB::table('orders')->truncate();
            $this->line('✓ Tabel orders dikosongkan.');

            if (Schema::hasTable('monthly_reports')) {
                DB::table('monthly_reports')->truncate();
                $this->line('✓ Tabel monthly_reports dikosongkan.');
            }

            if (Schema::hasTable('stock_histories')) {
                DB::table('stock_histories')->whereIn('type', ['out', 'return'])->delete();
                $this->line('✓ Histori stok transaksi dibersihkan.');
            }

            $this->info('Sukses! Seluruh riwayat transaksi telah bersih dan siap untuk pengetesan.');
        } catch (\Throwable $e) {
            $this->error('Gagal mengosongkan data: ' . $e->getMessage());
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        return 0;
    }
}
