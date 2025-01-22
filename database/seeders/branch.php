<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class branch extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        DB::table('branch')->insert([
            'kode_branch' => '001',
            'unit' => 'CIAWI',
            'code_area' => '1101',
            'area' => 'JABAR 1',
            'code_region' => '1111',
            'region' => 'JABAR',
            'alamat' => 'CLUSTER MUTIARA RESIDENCE JALAN VETERAN III RT 002 RW 002 DESA BANJARWARU KECAMATAN CIAWI KABUPATEN BOGOR',
            'GL' => '1711000',
            'tgl_open' => '2012-07-01 00:00:00',
            'status_aktif' => 'aktif',
            'status_approve' => 'YA',
        ]);
    }
}
