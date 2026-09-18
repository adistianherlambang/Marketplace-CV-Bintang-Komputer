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
            // Riwayat pesanan bagian pelanggan (customer_user_id) TIDAK BISA dihapus
            $posOrderIds = DB::table('orders')->whereNull('customer_user_id')->pluck('id');

            if ($posOrderIds->isNotEmpty()) {
                DB::table('order_items')->whereIn('order_id', $posOrderIds)->delete();
                $this->line('✓ Tabel order_items (POS) dibersihkan.');

                DB::table('payments')->whereIn('order_id', $posOrderIds)->delete();
                $this->line('✓ Tabel payments (POS) dibersihkan.');

                DB::table('complaints')->whereIn('order_id', $posOrderIds)->delete();
                $this->line('✓ Tabel complaints (POS) dibersihkan.');

                DB::table('returns')->whereIn('order_id', $posOrderIds)->delete();
                $this->line('✓ Tabel returns (POS) dibersihkan.');

                DB::table('orders')->whereIn('id', $posOrderIds)->delete();
                $this->line('✓ Tabel orders (POS) dibersihkan.');
            }

            if (Schema::hasTable('monthly_reports')) {
                DB::table('monthly_reports')->truncate();
                $this->line('✓ Tabel monthly_reports dikosongkan.');
            }

            if (Schema::hasTable('stock_histories')) {
                DB::table('stock_histories')->whereIn('type', ['out', 'return'])->delete();
                $this->line('✓ Histori stok transaksi dibersihkan.');
            }

            $this->info('Sukses! Riwayat transaksi kasir toko telah bersih. Riwayat pesanan pelanggan tetap aman!');
        } catch (\Throwable $e) {
            $this->error('Gagal mengosongkan data: ' . $e->getMessage());
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        return 0;
    }
}
