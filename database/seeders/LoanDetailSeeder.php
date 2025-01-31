<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('loan_detail')->insert([
            [
                'code_kel' => '003-0253',
                'buss_date' => '2025-01-30 15:12:16',
                'no_anggota' => '00500010E01',
                'cif' => '00010E',
                'suffix' => 1,
                'nama' => 'ISNAWATI',
                'deal_type' => '2',
                'bagi_hasil' => '360000',
                'tenor' => '25',
                'plafond' => 2000000,
                'unit' => '003',
                'contract_date' => '2025-01-30 15:12:16',
                'hari_tagih' => 'kamis',
                'angsuran' => 94400,
                'setoran' => 100000,
                'cao' => '00506',
                'userid' => '005',
            ],
        ]);
    }
}
