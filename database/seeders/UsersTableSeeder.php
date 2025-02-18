<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@ni',
                'password' => Hash::make('123456'),
                'role_id' => '1',
                'param_tanggal' => '2025-01-15',
                'unit' => '001'
            ],
            [
                'name' => 'Al User',
                'email' => 'al@ni',
                'password' => Hash::make('123456'),
                'role_id' => '2',
                'param_tanggal' => '2025-01-15',
                'unit' => '001'
            ]
        ]);
    }
}
