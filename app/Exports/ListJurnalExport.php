<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ListJurnalExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    protected $unit, $awal, $akhir;
    protected $totalDebet = 0;
    protected $totalKredit = 0;
    protected $data;

    public function __construct($unit, $awal, $akhir)
    {
        $this->unit = $unit;
        $this->awal = $awal;
        $this->akhir = $akhir;
    }

    public function collection()
    {
        $this->data = DB::table('tabel_transaksi')
            ->where('unit', $this->unit)
            ->whereBetween('tanggal_transaksi', [$this->awal, $this->akhir])
            ->select(
                DB::raw("DATE_FORMAT(tanggal_transaksi, '%Y-%m-%d') as tanggal_transaksi"),
                'kode_transaksi',
                'kode_rekening',
                'keterangan_transaksi',
                'jenis_transaksi',
                'debet',
                'kredit'
            )
            ->get();

        // Hitung total debet dan kredit
        $this->totalDebet = $this->data->sum('debet');
        $this->totalKredit = $this->data->sum('kredit');

        return $this->data;
    }

    public function headings(): array
    {
        return [
            ['Periode: ' . date('Y-m-d', strtotime($this->awal)) . ' s.d. ' . date('Y-m-d', strtotime($this->akhir))],
            ['Tanggal', 'Nomor Bukti', 'Kode Rekening', 'Keterangan', 'Jenis Transaksi', 'Debet', 'Kredit']
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Merge baris 1 untuk judul periode
                $event->sheet->mergeCells('A1:G1');
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $event->sheet->getRowDimension(1)->setRowHeight(25);

                // Style untuk header tabel
                $event->sheet->getStyle('A2:G2')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => 'center'],
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                ]);

                // Hitung jumlah baris data
                $rowCount = $event->sheet->getDelegate()->getHighestRow();

                // Apply border ke semua data
                $event->sheet->getStyle('A2:G' . $rowCount)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                ]);

                $lastRow = $rowCount + 1;

                // Merge kolom A sampai E
                $event->sheet->mergeCells("A{$lastRow}:E{$lastRow}");
                $event->sheet->setCellValue("A{$lastRow}", 'Total');

                // Isi nilai total Debet & Kredit
                $event->sheet->setCellValue("F{$lastRow}", $this->totalDebet);
                $event->sheet->setCellValue("G{$lastRow}", $this->totalKredit);

                // Style baris total keseluruhan
                $event->sheet->getStyle("A{$lastRow}:G{$lastRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                ]);

                // Khusus buat cell A (yang di-merge), kita set agar tengah
                $event->sheet->getStyle("A{$lastRow}")->applyFromArray([
                    'alignment' => ['horizontal' => 'center'],
                ]);

                // Kolom Debet & Kredit tetap rata kanan
                $event->sheet->getStyle("F{$lastRow}:G{$lastRow}")->applyFromArray([
                    'alignment' => ['horizontal' => 'right'],
                ]);

                // Format angka Debet & Kredit
                $event->sheet->getStyle("F{$lastRow}:G{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');
            }

        ];
    }
}
