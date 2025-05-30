<?php

namespace Database\Seeders;

use App\Models\Coa;
use App\Models\RekLoan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        Role::create([
            'role_name' => 'admin',
        ]);

        Role::create([
            'role_name' => 'al',
        ]);



        $this->call([
            UsersTableSeeder::class,
            MenuSeeder::class,
            ParamTgl::class,
            TabelParamBiaya::class,
            tabelAo::class,
            kelompok::class,
            AnggotaSeeder::class,
            tempAkadMus::class,
            MmSeeder::class,
            simpanan::class,
            simpanan_pokok::class,
            simpanan_wajib::class,
            tunggakan::class,
            branch::class,
            pembiayaan::class,
            PembiayaanDetailSeeder::class,
            RekLoanSeeder::class,
            RekomendasiSeeder::class,
            CoaSeeder::class,
            PembiayaanWoSeeder::class,
            paramLiburSeder::class,

        ]);


    }
}
