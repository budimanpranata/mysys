<?php

namespace App\Exports;

use App\Models\simpanan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SimpananSheet implements FromCollection, WithTitle, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('simpanan')
            ->join('anggota', 'simpanan.norek', '=', 'anggota.no')
            ->select([
                'simpanan.unit',
                'simpanan.norek as NO_REKENING',
                'anggota.nama',
                'anggota.alamat',
                DB::raw('SUM(simpanan.kredit) as saldo_awal'),
                DB::raw('SUM(simpanan.kredit) as total_kredit'),
                DB::raw('SUM(simpanan.debet) as total_debet'),
                DB::raw('SUM(simpanan.kredit - simpanan.debet) as saldo_akhir')
            ])
            ->groupBy(
                'simpanan.unit',
                'simpanan.norek',
            )
            ->get();
    }


    public function headings(): array
    {
        return [
            'UNIT',
            'NO. REKENING',
            'NAMA',
            'ALAMAT',
            'SALDO AWAL',
            'MUTASI DEBET',
            'MUTASI KREDIT',
            'SALDO AKHIR'
        ];
    }

    public function title(): string
    {
        return 'SIMPANAN';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'] // white text
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF008000'] // green
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                ]
            ]
        ];
    }

}
