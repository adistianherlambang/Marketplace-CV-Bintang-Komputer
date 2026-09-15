<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Support\Facades\Schema;

class WilayahMetroSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Kelurahan::truncate();
        Kecamatan::truncate();
        Schema::enableForeignKeyConstraints();

        $dataWilayah = [
            [
                'nama_kecamatan' => 'Metro Pusat',
                'kelurahan' => [
                    ['nama' => 'Metro', 'tarif' => 10000],
                    ['nama' => 'Imopuro', 'tarif' => 12000],
                    ['nama' => 'Pusat Mulyo', 'tarif' => 13000],
                    ['nama' => 'Yosomulyo', 'tarif' => 14000],
                    ['nama' => 'Hadimulyo Barat', 'tarif' => 12000],
                    ['nama' => 'Hadimulyo Timur', 'tarif' => 13000],
                ]
            ],
            [
                'nama_kecamatan' => 'Metro Timur',
                'kelurahan' => [
                    ['nama' => 'Yosorejo', 'tarif' => 15000],
                    ['nama' => 'Yosodadi', 'tarif' => 14000],
                    ['nama' => 'Tejosari', 'tarif' => 16000],
                    ['nama' => 'Tejo Agung', 'tarif' => 15000],
                    ['nama' => 'Iringmulyo', 'tarif' => 13000],
                ]
            ],
            [
                'nama_kecamatan' => 'Metro Selatan',
                'kelurahan' => [
                    ['nama' => 'Margodadi', 'tarif' => 18000],
                    ['nama' => 'Margorejo', 'tarif' => 19000],
                    ['nama' => 'Rejomulyo', 'tarif' => 20000],
                    ['nama' => 'Sumbersari', 'tarif' => 21000],
                ]
            ],
            [
                'nama_kecamatan' => 'Metro Barat',
                'kelurahan' => [
                    ['nama' => 'Mulyojaya', 'tarif' => 17000],
                    ['nama' => 'Mulyosari', 'tarif' => 18000],
                    ['nama' => 'Ganjar Agung', 'tarif' => 16000],
                    ['nama' => 'Ganjar Asri', 'tarif' => 17000],
                ]
            ],
            [
                'nama_kecamatan' => 'Metro Utara',
                'kelurahan' => [
                    ['nama' => 'Karangrejo', 'tarif' => 22000],
                    ['nama' => 'Purwoasri', 'tarif' => 20000],
                    ['nama' => 'Banjarsari', 'tarif' => 23000],
                    ['nama' => 'Asahan', 'tarif' => 24000],
                ]
            ],
        ];

        foreach ($dataWilayah as $item) {
            $kecamatan = Kecamatan::create([
                'nama_kecamatan' => $item['nama_kecamatan']
            ]);

            foreach ($item['kelurahan'] as $kel) {
                Kelurahan::create([
                    'kecamatan_id' => $kecamatan->id,
                    'nama_kelurahan' => $kel['nama'],
                    'tarif_grab' => $kel['tarif']
                ]);
            }
        }
    }
}