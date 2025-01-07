<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $masterData = Menu::create([
            'name' => 'Master Data Anggota',
            'icon' => 'nav-icon fas fa-edit', //
            'parent_id' => null,
            'url' => null,
            'order' => 1,
        ]);

        // Submenu
        Menu::create([
            'name' => 'Input Data Anggota',
            'icon' => 'far fa-circle nav-ico',
            'parent_id' => $masterData->id,
            'url' => '/anggota-baru', // Contoh URL
            'order' => 1,
        ]);

        Menu::create([
            'name' => 'Input Data Pembiayaan',
            'icon' => 'far fa-circle nav-ico',
            'parent_id' => $masterData->id,
            'url' => '/dashboard-v2',
            'order' => 2,
        ]);

        Menu::create([
            'name' => 'Input Data Kelompok',
            'icon' => 'far fa-circle nav-ico',
            'parent_id' => $masterData->id,
            'url' => '/dashboard-v3',
            'order' => 3,
        ]);

        $CetakData = Menu::create([
            'name' => 'Cetak Dokumen',
            'icon' => 'nav-icon fas fa-edit', //
            'parent_id' => null,
            'url' => null,
            'order' => 4,
        ]);

        // Submenu
        Menu::create([
            'name' => 'Cetak Wakalah',
            'icon' => 'far fa-circle nav-ico',
            'parent_id' => $CetakData->id,
            'url' => '/anggota-baru', // Contoh URL
            'order' => 4,
        ]);

        Menu::create([
            'name' => 'Cetak Murabahah',
            'icon' => 'far fa-circle nav-ico',
            'parent_id' => $CetakData->id,
            'url' => '/dashboard-v2',
            'order' => 5,
        ]);

        Menu::create([
            'name' => 'Cetak Musyarokah',
            'icon' => 'far fa-circle nav-ico',
            'parent_id' => $CetakData->id,
            'url' => '/dashboard-v3',
            'order' => 6,
        ]);
    }
}
