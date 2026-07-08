<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArusKasKategoriSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['11000', 'HEADING', 'KEGIATAN OPERASI', '11000', 10],
            ['11200', 'DETAIL', 'Penerimaan simpanan dari anggota dan koperasi lain', '11000', 20],
            ['11300', 'DETAIL', 'Penyaluran pembiayaan kepada anggota dan koperasi lain', '11000', 30],
            ['11400', 'DETAIL', 'Penerimaan dari pembiayaan kepada anggota dan koperasi lain', '11000', 40],
            ['11500', 'DETAIL', 'Penerimaan pendapatan dari pembiayaan kepada anggota dan koperasi lain', '11000', 50],
            ['11600', 'DETAIL', 'Pembayaran bagi hasil kepada anggota dan koperasi lain', '11000', 60],
            ['11700', 'DETAIL', 'Penerimaan pembiayaan dari pihak lain', '11000', 70],
            ['11800', 'DETAIL', 'Pembayaran pembiayaan dari pihak lain', '11000', 80],
            ['11900', 'DETAIL', 'Pembayaran bagi hasil kepada pihak lain', '11000', 90],
            ['11910', 'DETAIL', 'Biaya imbalan kerja', '11000', 100],
            ['11920', 'DETAIL', 'Biaya operasional', '11000', 110],
            ['12000', 'HEADING', 'KEGIATAN INVESTASI', '12000', 120],
            ['12200', 'DETAIL', 'Penempatan Deposito Jangka Panjang', '12000', 130],
            ['12300', 'DETAIL', 'Penerimaan Bunga Deposito', '12000', 140],
            ['12400', 'DETAIL', 'Pencairan Deposito', '12000', 150],
            ['12500', 'DETAIL', 'Perolehan aset tetap', '12000', 160],
            ['12600', 'DETAIL', 'Pelepasan aset tetap', '12000', 170],
            ['12700', 'DETAIL', 'Perolehan aset tak berwujud', '12000', 180],
            ['12800', 'DETAIL', 'Pelepasan aset tak berwujud', '12000', 190],
            ['13000', 'HEADING', 'KEGIATAAN PENDANAAN', '13000', 200],
            ['13200', 'SUB HEADING', 'Penambahan modal :', '13000', 210],
            ['13300', 'DETAIL', 'Simpanan pokok/modal tetap', '13000', 220],
            ['13400', 'DETAIL', 'Simpanan wajib/modal tambahan', '13000', 230],
            ['13500', 'DETAIL', 'Penempatan Dana Investasi', '13000', 240],
            ['13600', 'DETAIL', 'Penerimaan pinjaman bank/lembaga keuangan lain', '13000', 250],
            ['13700', 'SUB HEADING', 'Pengurangan modal :', '13000', 260],
            ['13800', 'DETAIL', 'Simpanan pokok/modal tetap', '13000', 270],
            ['13900', 'DETAIL', 'Simpanan wajib/modal tambahan', '13000', 280],
            ['13910', 'DETAIL', 'Pencairan Dana Investasi', '13000', 290],
            ['13920', 'DETAIL', 'Pembayaran pinjaman bank / lembaga keuangan lain', '13000', 300],
            ['13930', 'DETAIL', 'Pembayaran Bagi Hasil anggota Dana Investasi', '13000', 310],
            ['13940', 'DETAIL', 'Penerimaan dana antar kantor (RAK)', '13000', 320],
            ['13950', 'DETAIL', 'Pengiriman dana antar kantor (RAK)', '13000', 330],
        ];

        foreach ($rows as [$code, $line, $nama, $groupHeading, $urutan]) {
            DB::table('arus_kas_kategori')->updateOrInsert(
                ['code_arus_kas' => $code],
                [
                    'line' => $line,
                    'nama' => $nama,
                    'group_heading' => $groupHeading,
                    'urutan' => $urutan,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
