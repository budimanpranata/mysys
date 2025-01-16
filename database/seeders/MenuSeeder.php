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
        Menu::create([
            'name' => 'Dahsboard',
            'icon' => 'nav-icon fas fa-tachometer-alt',
            'parent_id' => null,
            'url' => '/admin', // Contoh URL
            'left' => 'null',
            'order' => 0,
        ]);

        $masterData = Menu::create([
            'name' => 'Master Data Anggota',
            'icon' => 'nav-icon fas fa-users-cog', //
            'parent_id' => null,
            'url' => null,
            'left' => 'right fas fa-angle-left',
            'order' => 1,
        ]);

        // Submenu
        Menu::create([
            'name' => 'Input Data Anggota',
            'icon' => 'far fa-circle nav-icon',
            'parent_id' => $masterData->id,
            'url' => '/anggota-baru', // Contoh URL
            'left' => 'null',
            'order' => 1,
        ]);

        Menu::create([
            'name' => 'Input Data Pembiayaan',
            'icon' => 'far fa-circle nav-icon',
            'parent_id' => $masterData->id,
            'url' => '/dashboard-v2',
            'left' => 'null',
            'order' => 2,
        ]);

        Menu::create([
            'name' => 'Input Data Kelompok',
            'icon' => 'far fa-circle nav-icon',
            'parent_id' => $masterData->id,
            'url' => '/dashboard-v3',
            'left' => 'null',
            'order' => 3,
        ]);

        $CetakData = Menu::create([
            'name' => 'Cetak Dokumen',
            'icon' => 'nav-icon fas fa-print', //
            'parent_id' => null,
            'url' => null,
            'left' => 'right fas fa-angle-left',
            'order' => 4,
        ]);

        // Submenu
        Menu::create([
            'name' => 'Cetak Wakalah',
            'icon' => 'far fa-circle nav-icon',
            'parent_id' => $CetakData->id,
            'url' => '/anggota-baru', // Contoh URL
            'left' => 'null',
            'order' => 4,
        ]);

        Menu::create([
            'name' => 'Cetak Murabahah',
            'icon' => 'far fa-circle nav-icon',
            'parent_id' => $CetakData->id,
            'url' => '/dashboard-v2',
            'left' => 'null',
            'order' => 5,
        ]);

        Menu::create([
            'name' => 'Cetak Musyarokah',
            'icon' => 'far fa-circle nav-icon',
            'parent_id' => $CetakData->id,
            'url' => '/admin/cetak/musyarokah',
            'left' => 'null',
            'order' => 6,
        ]);


        $Realisasi = Menu::create([
            'name' => 'Realisasi',
            'icon' => 'nav-icon fas fa-edit', //
            'parent_id' => null,
            'url' => null,
            'left' => 'right fas fa-angle-left',
            'order' => 7,
        ]);

        // Submenu
        Menu::create([
            'name' => 'Realiasi Wakalah',
            'icon' => 'far fa-circle nav-ico',
            'parent_id' => $Realisasi->id,
            'url' => '/realisasi_wakalah',
            'left' => 'null',
            'order' => 8,
        ]);

        Menu::create([
            'name' => 'Murabahah',
            'icon' => 'far fa-circle nav-ico',
            'parent_id' => $Realisasi->id,
            'url' => '/dashboard-v2',
            'left' => 'null',
            'order' => 9,
        ]);

        Menu::create([
            'name' => 'Pembatalan Wakalah',
            'icon' => 'far fa-circle nav-ico',
            'parent_id' => $Realisasi->id,
            'url' => '/dashboard-v3',
            'left' => 'null',
            'order' => 10,
        ]);

        Menu::create([
            'name' => 'Tagihan Kelompok',
            'icon' => 'far fa-circle nav-ico',
            'parent_id' => $Realisasi->id,
            'url' => '/dashboard-v3',
            'left' => 'null',
            'order' => 11,
        ]);
        Menu::create([
            'name' => 'Hapus Buku',
            'icon' => 'far fa-circle nav-ico',
            'parent_id' => $Realisasi->id,
            'url' => '/dashboard-v3',
            'left' => 'null',
            'order' => 12,
        ]);
    }
}
