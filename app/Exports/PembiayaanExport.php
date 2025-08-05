<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PembiayaanExport implements FromCollection, WithTitle, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('pembiayaan')
            ->join('anggota', 'pembiayaan.no_anggota', '=', 'anggota.no')
            ->join('ao', 'pembiayaan.cao', '=', 'ao.cao')
            ->leftJoin('kelompok', 'pembiayaan.code_kel', '=', 'kelompok.code_kel')
            ->leftJoin('tunggakan', 'pembiayaan.no_anggota', '=', 'tunggakan.norek')
            ->select([
                DB::raw('MAX(pembiayaan.unit) as unit'),
                'pembiayaan.no_anggota',
                DB::raw('MAX(pembiayaan.nama) as nama'),
                DB::raw('MAX(pembiayaan.code_kel) as code_kel'),
                DB::raw('MAX(pembiayaan.tgl_murab) as tgl_murab'),
                DB::raw('MAX(pembiayaan.tenor) as tenor'),
                DB::raw('MAX(pembiayaan.maturity_date) as maturity_date'),
                DB::raw('MAX(pembiayaan.plafond) as plafond'),
                DB::raw('MAX(pembiayaan.os - pembiayaan.saldo_margin) as saldo_pinjaman'),
                DB::raw('MAX(pembiayaan.os) as os'),
                DB::raw('MAX(pembiayaan.saldo_margin) as saldo_margin'),
                DB::raw('MAX(pembiayaan.ke) as ke'),
                DB::raw('SUM(tunggakan.debet) as tunggakan_pokok'),
                DB::raw('SUM(tunggakan.kredit) as tunggakan_margin'),
                DB::raw('MAX(pembiayaan.gol) as gol'),
                DB::raw('MAX(pembiayaan.cao) as cao'),
                DB::raw('MAX(ao.nama_ao) as nama_ao'),
                DB::raw('MAX(kelompok.nama_kel) as nama_kelompok'),
                DB::raw('MAX(pembiayaan.cif) as cif'),
                DB::raw('MAX(anggota.alamat) as alamat'),
                DB::raw('MAX(pembiayaan.nama_usaha) as nama_usaha'),
                DB::raw('MAX(pembiayaan.status_app) as status_app'),

            ])
            ->groupBy(
                'pembiayaan.no_anggota',
            )
            ->get();
    }


    public function headings(): array
    {
        return [
            'UNIT',
            'NO. REKENING',
            'NAMA',
            'KELOMPOK',
            'TANGGAL PK',
            'TENOR',
            'JATUH TEMPO',
            'PLAFOND',
            'SALDO PINJAMAN',
            'SALDO OS',
            'SALDO MARGIN',
            'KE',
            'TUNGGAKAN POKOK',
            'TUNGGAKAN MARGIN',
            'GOL',
            'AO',
            'NAMA AO',
            'NAMA KELOMPOK',
            'CIF',
            'ALAMAT',
            'BIDANG USAHA',
            'NO KTP',
            'STATUS',
            'SUMBER DANA',
            'TWM',
            'SALDO TWM',
            'SISA TWM'
        ];
    }

    public function title(): string
    {
        return 'Nominative';
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

