<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class SimpananPokokSheet implements FromCollection, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('simpanan')
            ->select('unit', 'norek', 'debet', 'kredit', 'buss_date')
            ->get();
    }

    public function title(): string
    {
        return 'SIMPANAN POKOK';
    }
}
