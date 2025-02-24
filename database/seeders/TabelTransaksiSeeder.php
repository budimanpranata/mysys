<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TabelTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tabel_transaksi')->insert([
            [
                'id_transaksi' => '1',
                'unit' => '001',
                'kode_transaksi' => 'BU240225001',
                'kode_rekening' => 2500000,
                'tanggal_transaksi' => '2025-02-24 19:52:16',
                'jenis_transaksi' => 'bukti SYSTEM',
                'keterangan_transaksi' => 'Transkasi A',
                'debet' => 200000,
                'kredit' => 200000,
                'tanggal_posting' => '2025-02-24',
                'keterangan_posting' => 'Transkasi Posting A',
                'id_admin' => '1'
            ],
        ]);
    }
}
