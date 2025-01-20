<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ao;

class tabelAo extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        DB::table('ao')->insert([
            [
                'cao' => '00101',
                'nama_ao' => 'IRVAN NOVANSKA',
                'no_tlp' => '',
                'kode_unit' => '001',
                'atasan' => '016010512',
                'nik_ao' => '240800204',
            ],
            [
                'cao' => '00102',
                'nama_ao' => 'RIYAN DWI PRASETIO',
                'no_tlp' => '',
                'kode_unit' => '001',
                'atasan' => '016010512',
                'nik_ao' => '1004261021',
            ],
            [
                'cao' => '00103',
                'nama_ao' => 'BAYU PRASETYO',
                'no_tlp' => '',
                'kode_unit' => '001',
                'atasan' => '016010512',
                'nik_ao' => '148100117',
            ],
            [
                'cao' => '00104',
                'nama_ao' => 'TINA EPENDI',
                'no_tlp' => '',
                'kode_unit' => '001',
                'atasan' => '016010512',
                'nik_ao' => '2412110105',
            ],
            [
                'cao' => '00301',
                'nama_ao' => 'SURYANA',
                'no_tlp' => '',
                'kode_unit' => '001',
                'atasan' => '013010512',
                'nik_ao' => '147100117',
            ],
            [
                'cao' => '00302',
                'nama_ao' => 'RIFKI PRADANA',
                'no_tlp' => '',
                'kode_unit' => '001',
                'atasan' => '013010512',
                'nik_ao' => '230100302',
            ],
            [
                'cao' => '00303',
                'nama_ao' => 'INDRA PRASETYO',
                'no_tlp' => '',
                'kode_unit' => '001',
                'atasan' => '013010512',
                'nik_ao' => '',
            ]
        ]);

    }
}
