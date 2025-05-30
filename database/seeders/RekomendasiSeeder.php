<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RekomendasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rekomendasi')->insert([
            [
                'norek' => '00108612001',
                'cif' => '086120',
                'nominal' => '1000000'
            ],
            [
                'norek' => '00108612101',
                'cif' => '086121',
                'nominal' => '1000000'
            ],
            [
                'norek' => '00108612201',
                'cif' => '086122',
                'nominal' => '1000000'
            ],
        ]);
    }
}
