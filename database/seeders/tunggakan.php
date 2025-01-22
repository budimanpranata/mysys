<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tunggakan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tunggakan')->insert([
            'tgl_tunggak' => now(),
            'norek' => '00108612001',
            'unit' => '001',
            'cif' => '086120',
            'code_kel' => '003-0220',
            'debet' => 0,
            'type' => '01',
            'kredit' => 100000,
            'userid' => '001',
            'ket' => 'SIMPANAN',
            'reff' => '1',
            'cao' => '00303',
            'blok' => 1,

        ]);
    }
}
