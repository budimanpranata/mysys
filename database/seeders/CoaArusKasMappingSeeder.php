<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoaArusKasMappingSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['kode_rekening', '1413000', 'debet', '11300', 'Piutang Murabahah Mingguan - penyaluran'],
            ['kode_rekening', '1413000', 'kredit', '11400', 'Piutang Murabahah Mingguan - penerimaan pokok'],
            ['kode_rekening', '1411000', 'debet', '11300', 'Piutang Murabahah Restrukturisasi - penyaluran'],
            ['kode_rekening', '1411000', 'kredit', '11400', 'Piutang Murabahah Restrukturisasi - penerimaan pokok'],
            ['kode_rekening', '1431000', 'debet', '11300', 'Piutang Wakalah - penyaluran'],
            ['kode_rekening', '1431000', 'kredit', '11400', 'Piutang Wakalah - penerimaan pokok'],
            ['kode_rekening', '1472000', 'debet', '11300', 'Pembiayaan Musyarakah - penyaluran'],
            ['kode_rekening', '1472000', 'kredit', '11400', 'Pembiayaan Musyarakah - penerimaan pokok'],
            ['kode_rekening', '41002', 'kredit', '11500', 'PM-Murabahah-Kelompok Mingguan'],
            ['kode_rekening', '42356', 'kredit', '11500', 'POL-Penerimaan Kembali HB'],
            ['kode_rekening', '2101000', 'both', '11200', 'Simpanan Wadiah Kelompok'],
            ['kode_rekening', '3102000', 'kredit', '13300', 'SP-Anggota - penambahan'],
            ['kode_rekening', '3102000', 'debet', '13800', 'SP-Anggota - pengurangan'],
            ['kode_rekening', '3202000', 'kredit', '13400', 'SW-Anggota - penambahan'],
            ['kode_rekening', '3202000', 'debet', '13900', 'SW-Anggota - pengurangan'],
            ['kode_rekening', '1600000', 'debet', '12500', 'Aktiva Tetap Tanah/Gedung - perolehan'],
            ['kode_rekening', '1600000', 'kredit', '12600', 'Aktiva Tetap Tanah/Gedung - pelepasan'],
            ['kode_rekening', '1650000', 'debet', '12500', 'Aktiva Tetap Inventaris - perolehan'],
            ['kode_rekening', '1650000', 'kredit', '12600', 'Aktiva Tetap Inventaris - pelepasan'],
            ['prefix', '1010-', 'kredit', '13940', 'RAK - penerimaan dana antar kantor'],
            ['prefix', '1010-', 'debet', '13950', 'RAK - pengiriman dana antar kantor'],
            ['kode_rekening', '2500000', 'kredit', '13940', 'RAK PASIVA - KP - penerimaan dana antar kantor'],
            ['kode_rekening', '2500000', 'debet', '13950', 'RAK PASIVA - KP - pengiriman dana antar kantor'],
            ['kode_rekening', '41004', 'debet', '11600', 'BH-Mudharabah - pembayaran bagi hasil anggota'],
            ['kode_rekening', '1953000', 'both', '11920', 'Persediaan-Materai'],
            ['kode_rekening', '57202', 'kredit', '13940', 'PNO-Bg Hsl/Bns/Margin Antar Kantor-RAK AP'],
            ['gr_head', '40000', 'kredit', '11500', 'Pendapatan Operasional (fallback)'],
            ['gr_head', '50000', 'debet', '11920', 'Biaya Operasional (fallback)'],
            ['gr_head', '57000', 'kredit', '12300', 'Pendapatan Non Operasional (fallback)'],
            ['gr_head', '58000', 'debet', '11920', 'Beban Non Operasional (fallback)'],
        ];

        foreach ($rows as [$matchType, $matchValue, $arah, $codeArusKas, $keterangan]) {
            DB::table('coa_arus_kas_mappings')->updateOrInsert(
                ['match_type' => $matchType, 'match_value' => $matchValue, 'arah' => $arah],
                ['code_arus_kas' => $codeArusKas, 'keterangan' => $keterangan, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}
