<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TabelMasterKebajikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tabel_master_kebajikan')->insert([
            [
                'kode_kebajikan' => '2865000',
                'tanggal' => '2025-06-09',
                'nama_account' => 'Infak',
                'jumlah' => 0,
            ],
            [
                'kode_kebajikan' => '2862000',
                'tanggal' => '2025-07-09',
                'nama_account' => 'Sedekah',
                'jumlah' => 52953797,
            ],
                        [
                'kode_kebajikan' => '2864000',
                'tanggal' => '2025-07-09',
                'nama_account' => 'Denda',
                'jumlah' =>  0,
            ],
            [
                'kode_kebajikan' => '2862001',
                'tanggal' => '2025-07-09',
                'nama_account' => 'Pengembalian',
                'jumlah' => 0,
            ],
            [
                'kode_kebajikan' => '56212',
                'tanggal' => '2025-07-09',
                'nama_account' => 'Sumbangan',
                'jumlah' => 0,
            ],
                        [
                'kode_kebajikan' => '2865000',
                'tanggal' => '2025-07-09',
                'nama_account' => 'Saldo Akhir',
                'jumlah' => 53403797,
            ],
        ]);
    }
}
