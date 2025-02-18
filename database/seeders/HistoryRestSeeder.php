<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistoryRestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('history_rest')->insert([
            [
                'tgl_rest' => '2025-01-30',
                'code_kel' => '003-0220',
                'cif' => '00010E',
                'plafond' => 2000000,
                'pokok' => 80000,
                'margin' => 360000,
                'angsuran' => 94400,
                'tenor' => '25',
                'jenis_rest' => '1'
            ],
        ]);
    }
}
