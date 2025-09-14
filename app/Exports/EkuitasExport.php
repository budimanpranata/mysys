<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EkuitasExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
        {
            $report_ekuitas = [
                [
                    'jenis_account' => 'Simpanan Pokok',
                    'saldo_awal' => 3021300000,
                    'penambahan' => 26750000,
                    'saldo_akhir' => 3048050000,
                ],
                [
                    'jenis_account' => 'Simpanan Wajib',
                    'saldo_awal' => 15469666929,
                    'penambahan' => 0,
                    'saldo_akhir' => 15469666929,
                ],
            ];

            return view('admin.report_ekuitas.export', [
                'report_ekuitas' => $report_ekuitas,
            ]);
        }
}
