<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TabelMasterZakatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tabel_master_zakat')->insert([
            [
                'kode_zakat' => '2861001',
                'tanggal' => '2025-05-09',
                'nama_account' => 'Zakat Anggota',
                'jumlah' => 54645371,
            ],
            [
                'kode_zakat' => '2861002',
                'tanggal' => '2025-06-09',
                'nama_account' => 'Zakat Non Anggota',
                'jumlah' => 0,
            ],
            [
                'kode_zakat' => '2861001',
                'tanggal' => '2025-07-09',
                'nama_account' => 'Penyaluran',
                'jumlah' => 3987500,
            ],
            [
                'kode_zakat' => '2861001',
                'tanggal' => '2025-07-09',
                'nama_account' => 'Saldo Awal',
                'jumlah' => 21020000,
            ]
        ]);
    }
}
