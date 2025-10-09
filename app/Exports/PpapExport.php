<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PpapExport implements WithMultipleSheets
{
    protected $data;
    protected $jenisKolek;

    public function __construct($data, $jenisKolek)
    {
        $this->data = $data;
        $this->jenisKolek = $jenisKolek;
    }

    public function sheets(): array
    {
        $sheets = [];

        if ($this->jenisKolek == 'semua' || $this->jenisKolek == 'lancar') {
            $sheets[] = new PpapSheetExport($this->data['lancar'], 'Kolek 1');
        }

        if ($this->jenisKolek == 'semua' || $this->jenisKolek == 'kurang_lancar') {
            $sheets[] = new PpapSheetExport($this->data['kurang_lancar'], 'Kolek 2');
        }

        if ($this->jenisKolek == 'semua' || $this->jenisKolek == 'diragukan') {
            $sheets[] = new PpapSheetExport($this->data['diragukan'], 'Kolek 3');
        }

        if ($this->jenisKolek == 'semua' || $this->jenisKolek == 'macet') {
            $sheets[] = new PpapSheetExport($this->data['macet'], 'Kolek 4');
        }

        return $sheets;
    }
}