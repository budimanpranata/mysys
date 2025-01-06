<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $masterData = Menu::create([
            'name' => 'Master Data Anggota',
            'icon' => 'fa-clock-o', // Contoh ikon (FontAwesome class)
            'parent_id' => null,
            'url' => null,
            'order' => 1,
        ]);

        // Submenu
        Menu::create([
            'name' => 'Input Data Anggota',
            'icon' => null,
            'parent_id' => $masterData->id,
            'url' => '/anggota-baru', // Contoh URL
            'order' => 1,
        ]);

        Menu::create([
            'name' => 'Input Data Pembiayaan',
            'icon' => null,
            'parent_id' => $masterData->id,
            'url' => '/dashboard-v2',
            'order' => 2,
        ]);

        Menu::create([
            'name' => 'Input Data Kelompok',
            'icon' => null,
            'parent_id' => $masterData->id,
            'url' => '/dashboard-v3',
            'order' => 3,
        ]);
        //
    }
}
