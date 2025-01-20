<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class kelompok extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        DB::table('kelompok')->insert([
            [
                'code_kel' => '003-0220',
                'code_unit' => '001',
                'nama_kel' => 'RB ADR KP MUNCANG',
                'alamat' => 'KP. LEGOK MUNCANG',
                'cao' => '00303', // join ke table anggota untuk ambil nama nya saja.
                'cif' => '0',
            ],
        ]);
    }
}
