<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParamTgl extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('param_tgl')->insert([
            [
                'code_tgl' => '1',
                'param_tgl' => '2025-01-06',
                'nama_param_tgl' => 'EOD',

            ],
            [
                'code_tgl' => '2',
                'param_tgl' => '2025-01-06',
                'nama_param_tgl' => 'KPEOD',
            ]
        ]);
    }
}
