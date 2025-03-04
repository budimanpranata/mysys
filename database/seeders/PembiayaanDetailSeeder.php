<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembiayaanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pembiayaan_detail')->insert([
            [
                'id' => '1',
                'id_pinjam' => '25001',
                'cicilan' => '12',
                'angsuran_pokok' => 100000,
                'margin' => '1',
                'tgl_jatuh_tempo' => '2025-02-24 15:12:16',
                'tgl_bayar' => '2025-02-24 15:12:16',
                'jumlah_bayar' => 100000,
                'keterangan' => 'Pembiayaan A',
                'cif' => '086120',
                'unit' => '001',
                'ao' => '00101',
                'code_kel' => '003-0220'
            ],
        ]);
    }
}
