<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TabelMasterEkuitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tabel_master_ekuitas')->insert([
            [
                'jenis_account' => 'Simpanan Pokok',
                'saldo_awal' => 3021300000,
                'penambahan' => 26750000,
                'saldo_akhir' => 3048050000,
            ],
            [
                'jenis_account' => 'Simpanan Wajib',
                'saldo_awal' => 15469666929,
                'penambahan' => -76064436,
                'saldo_akhir' => 15393602493,
            ],
            [
                'jenis_account' => 'Hibah',
                'saldo_awal' => 0,
                'penambahan' => 0,
                'saldo_akhir' => 0,
            ],
            [
                'jenis_account' => 'Cadangan',
                'saldo_awal' => 1779051862,
                'penambahan' => 0,
                'saldo_akhir' => 1779051862,
            ],
            [
                'jenis_account' => 'Akumulasi SHU',
                'saldo_awal' => 733939340,
                'penambahan' => 60190300,
                'saldo_akhir' => 794129640,
            ],
        ]);
    }
}
