<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RekLoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rek_loan')->insert([
            [
                'ref' => '11047766',
                'tgl_realisasi' => '2025-01-30 15:12:16',
                'unit' => '005',
                'no_anggota' => '00500010E01',
                'saldo_kredit' => 242250,
                'debet' => 0,
                'tipe' => 'L001',
                'ket' => 'Realisasi Murabahah ISNAWATI',
                'userid' => '005',
                'status' => 'REALISASI MURABAHAH',
                'cif' => '00010E',
                'ao' => '00506'
            ],
            [
                'ref' => '11047808',
                'tgl_realisasi' => '2025-01-31 15:12:16',
                'unit' => '005',
                'no_anggota' => '',
                'saldo_kredit' => 0,
                'debet' => 80000,
                'tipe' => '03',
                'ket' => 'Angsuran an ISNAWATI',
                'userid' => '005',
                'status' => 'RALISASI POKOK',
                'cif' => '00010E',
                'ao' => NULL
            ],
            [
                'ref' => '11047809',
                'tgl_realisasi' => '2025-01-31 15:12:16',
                'unit' => '005',
                'no_anggota' => '',
                'saldo_kredit' => 0,
                'debet' => 16900,
                'tipe' => '03',
                'ket' => 'Angsuran an ISNAWATI',
                'userid' => '005',
                'status' => 'REALISASI BAGHAS',
                'cif' => '00010E',
                'ao' => NULL
            ],
        ]);
    }
}
