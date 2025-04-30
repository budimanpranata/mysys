<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class paramLiburSeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('param_libur')->insert([
            [
                'code_tgl' => '1',
                'tanggal' => '2025-01-01',
                'nama_param_tgl' => 'Tahun Baru',

            ],
            [
                'code_tgl' => '2',
                'tanggal' => '2025-01-15',
                'nama_param_tgl' => 'Imlek',
            ]
        ]);
    }
}
