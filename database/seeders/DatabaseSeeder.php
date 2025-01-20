<?php

namespace Database\Seeders;

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
            tempAkadMus::class,
        ]);


    }
}
