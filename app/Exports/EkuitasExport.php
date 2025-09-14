<?php

namespace App\Exports;

use App\Models\ReportEkuitas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EkuitasExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $report_ekuitas = ReportEkuitas::select('jenis_account', 'saldo_awal', 'penambahan', 'saldo_akhir')->get();

        return view('admin.report_ekuitas.export', [
            'report_ekuitas' => $report_ekuitas,
        ]);
    }
}
