<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SumberdanaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sumber_dana')->insert([
            [
                'sumber' => 'NI'
            ],
            [
                'sumber' => 'PIHAK KETIGA'
            ],
            [
                'sumber' => 'BANK VICTORIA'
            ],
            [
                'sumber' => 'BSI'
            ],
            [
                'sumber' => 'BAV'
            ],
            [
                'sumber' => 'BJB'
            ],
            [
                'sumber' => 'BRIS'
            ],
            [
                'sumber' => 'LPDB'
            ],
        ]);
    }
}
