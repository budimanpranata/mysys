<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class simpanan_wajib extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('simpanan_wajib')->insert([
            'reff' => 'REF001',
            'buss_date' => now(),
            'norek' => '00108612001',
            'unit' => '001',
            'cif' => '086120',
            'code_kel' => '003-0220',
            'debet' => 0,
            'type' => '04',
            'kredit' => 25000,
            'userid' => '001',
            'ket' => 'SImpanan Wajib',
            'cao' => '00303',
            'blok' => 0,
            'tgl_input' => now(),
            'kode_transaksi' => 'TRX001',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
