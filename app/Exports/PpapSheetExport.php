<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class PpapSheetExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths, WithColumnFormatting
{
    protected $data;
    protected $title;

    public function __construct($data, $title)
    {
        $this->data = $data;
        $this->title = $title;
    }

    public function collection()
    {
        return $this->data->map(function($item, $index) {
            return [
                'unit' => $item->unit,
                'no_rekening' => $item->no_anggota,
                'nama' => $item->nama_anggota,
                'kelompok' => $item->code_kel,
                'tanggal_pk' => $item->tanggal_pk
                ? \Carbon\Carbon::parse($item->tanggal_pk)->locale('id')->isoFormat('DD MMMM YYYY')
                : '',
                'tenor' => $item->tenor,
                'jatuh_tempo' => $item->maturity_date
                ? \Carbon\Carbon::parse($item->maturity_date)->locale('id')->isoFormat('DD MMMM YYYY')
                : '',
                'plafond' => $item->plafond,
                'saldo_pinjaman' => $item->os,
                'saldo_os' => $item->os - $item->saldo_margin,
                'saldo_margin' => $item->saldo_margin ?? 0,
                'tunggakan_pokok' => $item->tunggakan_pokok ?? 0,
                'tunggakan_margin' => $item->tunggakan_margin ?? 0,
                'gol' => $item->gol,
                'ao' => $item->cao,
                'nama_ao' => $item->nama_ao,
                'nama_kelompok' => $item->nama_kelompok,
            ];
        });
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
            'TUNGGAKAN POKOK',
            'TUNGGAKAN MARGIN',
            'GOL',
            'AO',
            'NAMA AO',
            'NAMA KELOMPOK',
        ];
    }

    public function title(): string
    {
        return $this->title;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4CAF50']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // UNIT
            'B' => 20,  // NO. REKENING
            'C' => 30,  // NAMA
            'D' => 20,  // KELOMPOK
            'E' => 15,  // TANGGAL_PK
            'F' => 10,  // TENOR
            'G' => 15,  // JATUH TEMPO
            'H' => 15,  // PLAFOND
            'I' => 18,  // SALDO PINJAMAN
            'J' => 15,  // SALDO OS
            'K' => 15,  // SALDO MARGIN
            'L' => 18,  // TUNGGAKAN POKOK
            'M' => 18,
            'N' => 18,  
            'O' => 18,  
            'P' => 18,  
            'Q' => 18,

        ];
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_NUMBER,
            'K' => NumberFormat::FORMAT_NUMBER,
            'L' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}