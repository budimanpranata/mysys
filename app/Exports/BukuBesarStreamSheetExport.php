<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class BukuBesarStreamSheetExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
{
    protected $no_perkiraan;
    protected $tahun;
    protected $bulan;
    protected $all;
    protected $index;
    protected $chunkSize;

    public function __construct($no_perkiraan, $tahun, $bulan, $all, $index, $chunkSize)
    {
        $this->no_perkiraan = $no_perkiraan;
        $this->tahun = $tahun;
        $this->bulan = $bulan;
        $this->all = $all;
        $this->index = $index;
        $this->chunkSize = $chunkSize;
    }

    public function query()
    {
        $offset = $this->index * $this->chunkSize;

        return DB::table('tabel_transaksi')
            ->where('kode_rekening', $this->no_perkiraan)
            ->when(!$this->all && $this->tahun, fn($q) => $q->whereYear('tanggal_transaksi', $this->tahun))
            ->when(!$this->all && $this->bulan, fn($q) => $q->whereMonth('tanggal_transaksi', $this->bulan))
            ->orderBy('tanggal_transaksi', 'asc')
            ->offset($offset)
            ->limit($this->chunkSize)
            ->select('tanggal_transaksi', 'kode_transaksi', 'kode_rekening', 'keterangan_transaksi', 'debet', 'kredit');
    }

    public function headings(): array
    {
        return ['Tanggal', 'Kode Transaksi', 'Kode Rekening', 'Keterangan', 'Debet', 'Kredit'];
    }

    public function map($row): array
    {
        return [
            $row->tanggal_transaksi,
            $row->kode_transaksi,
            $row->kode_rekening,
            $row->keterangan_transaksi,
            $row->debet,
            $row->kredit,
        ];
    }

    public function title(): string
    {
        return 'Sheet ' . ($this->index + 1);
    }
}
