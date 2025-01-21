<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\temp_akad_mus;
use Illuminate\Support\Facades\DB;

class tempAkadMus extends Seeder
{

    public function run(): void
    {
        DB::table('temp_akad_mus')->insert([
            [
                'buss_date' => '2020-09-29 14:09:58',
                'code_kel' => '003-0220',
                'no_anggota' => '00108612001',
                'cif' => '086120',
                'nama' => 'IMAS',
                'deal_type' => '3',
                'suffix' => '1',
                'bagi_hasil' => 360000,
                'tenor' => 25,
                'plafond' => 2000000,
                'os' => 2360000,
                'saldo_margin' => 360000,
                'angsuran' => 94400,
                'pokok' => 80000,
                'ijaroh' => 14400,
                'bulat' => 100000,
                'run_tenor' => '0',
                'ke' => '1',
                'usaha' => '30',
                'nama_usaha' => '(BENGKEL SEPATU)',
                'unit' => '001',
                'tgl_wakalah' => '2020-09-30 00:00:00',
                'tgl_akad' => '2020-10-07',
                'tgl_murab' => '2020-10-07 00:00:00',
                'next_schedule' => '2024-01-01 00:00:00',
                'maturity_date' => '2021-03-31 00:00:00',
                'last_payment' => '2024-01-01',
                'hari' => 'Rabu',
                'cao' => '00303',
                'userid' => '001',
                'status' => 'ANGGOTA',
                'status_usia' => 'NO',
                'status_app' => 'MUSYARAKAH',
                'gol' => '1',
                'deal_produk' => NULL,
                'persen_margin' => 0.075,

            ],
            [
                'buss_date' => '2020-09-29 14:09:58',
                'code_kel' => '003-0220',
                'no_anggota' => '00108612101',
                'cif' => '086121',
                'nama' => 'YULI',
                'deal_type' => '3',
                'suffix' => '1',
                'bagi_hasil' => 360000,
                'tenor' => 25,
                'plafond' => 2000000,
                'os' => 2360000,
                'saldo_margin' => 360000,
                'angsuran' => 94400,
                'pokok' => 80000,
                'ijaroh' => 14400,
                'bulat' => 100000,
                'run_tenor' => '0',
                'ke' => '1',
                'usaha' => '30',
                'nama_usaha' => '(BENGKEL SEPATU)',
                'unit' => '001',
                'tgl_wakalah' => '2020-09-30 00:00:00',
                'tgl_akad' => '2020-10-07',
                'tgl_murab' => '2020-10-07 00:00:00',
                'next_schedule' => '2024-01-01 00:00:00',
                'maturity_date' => '2021-03-31 00:00:00',
                'last_payment' => '2024-01-01',
                'hari' => 'Rabu',
                'cao' => '00303',
                'userid' => '001',
                'status' => 'ANGGOTA',
                'status_usia' => 'NO',
                'status_app' => 'MUSYARAKAH',
                'gol' => '1',
                'deal_produk' => NULL,
                'persen_margin' => 0.075,

            ],

            [
                'buss_date' => '2020-09-29 14:09:58',
                'code_kel' => '003-0220',
                'no_anggota' => '00108612201',
                'cif' => '086122',
                'nama' => 'IDA NURLELA',
                'deal_type' => '3',
                'suffix' => '1',
                'bagi_hasil' => 360000,
                'tenor' => 25,
                'plafond' => 2000000,
                'os' => 2360000,
                'saldo_margin' => 360000,
                'angsuran' => 94400,
                'pokok' => 80000,
                'ijaroh' => 14400,
                'bulat' => 100000,
                'run_tenor' => '0',
                'ke' => '1',
                'usaha' => '30',
                'nama_usaha' => '(BENGKEL SEPATU)',
                'unit' => '001',
                'tgl_wakalah' => '2020-09-30 00:00:00',
                'tgl_akad' => '2020-10-07',
                'tgl_murab' => '2020-10-07 00:00:00',
                'next_schedule' => '2024-01-01 00:00:00',
                'maturity_date' => '2021-03-31 00:00:00', // tgl jatuh tempo
                'last_payment' => '2024-01-01',
                'hari' => 'Rabu',
                'cao' => '00303',
                'userid' => '001',
                'status' => 'ANGGOTA',
                'status_usia' => 'NO',
                'status_app' => 'MUSYARAKAH',
                'gol' => '1',
                'deal_produk' => NULL,
                'persen_margin' => 0.075,


            ]

        ]);
    }
}
