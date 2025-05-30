<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mm')->insert([
            [
                'nik' => '013010512',
                'nama' => 'JEHAN ISKANDAR',
                'alamat' => '-',
                'tgl_lahir' => '2024-12-16 09:01:28',
                'jabatan' => 'Marketing Manager',
                'no_tlp' => '-',
                'tmt' => '2024-12-16 09:01:28',
                'unit' => '001',
                'foto' => NULL,
            ],
        ]);
    }
}
