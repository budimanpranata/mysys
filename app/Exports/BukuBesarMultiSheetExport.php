<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BukuBesarMultiSheetExport implements WithMultipleSheets
{
    protected $transaksi;
    protected $info;
    protected $title;
    protected $chunkSize;

    public function __construct($transaksi, $info, $title, $chunkSize = 1000000)
    {
        $this->transaksi = $transaksi;
        $this->info      = $info;
        $this->title     = $title;
        $this->chunkSize = $chunkSize;
    }

    public function sheets(): array
    {
        $sheets = [];

        // pecah data jadi beberapa chunk
        $chunks = $this->transaksi->chunk($this->chunkSize);

        foreach ($chunks as $index => $chunk) {
            $sheets[] = new BukuBesarExport($chunk, $this->info, $this->title . ' - Part ' . ($index+1));
        }

        return $sheets;
    }
}
