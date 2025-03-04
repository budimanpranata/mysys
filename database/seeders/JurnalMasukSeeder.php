<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurnalMasukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jurnal_masuk')->insert([
            [
                'nomor_jurnal' => '1',
                'kode_transaksi' => 'BU240225001',
                'tanggal_selesai' => '2025-02-24',
                'unit' => '001'
            ],
        ]);
    }
}
