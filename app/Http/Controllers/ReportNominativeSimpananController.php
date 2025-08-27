<?php

namespace App\Http\Controllers;

use App\Exports\SimpananExport;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportNominativeSimpananController extends Controller
{
    public function index()
    {
        $title = 'Report Nominative Simpanan';
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();

        return view('admin.report_nominative_simpanan.index', compact('menus', 'title'));
    }

    public function getData(Request $request)
    {
        $status = $request->input('status_nominative');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $query = DB::table('simpanan')
        ->select(
            'unit',
            DB::raw('COUNT(DISTINCT norek) as total_noa'),
            DB::raw('SUM(kredit) - SUM(debet) as total_saldo')
        )
        ->groupBy('unit');


        if ($status === 'current') {
            $today = Carbon::now()->format('Y-m-d');
            $query->whereDate('buss_date', $today);
        } elseif ($status === 'eom') {
            $bulanAngka = match(strtolower($bulan)) {
                'januari' => '01',
                'februari' => '02',
                'maret' => '03',
                'april' => '04',
                'mei' => '05',
                'juni' => '06',
                'juli' => '07',
                'agustus' => '08',
                'september' => '09',
                'oktober' => '10',
                'november' => '11',
                'desember' => '12',
                default => null,
            };

            $startDate = Carbon::createFromDate($tahun, $bulanAngka, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($tahun, $bulanAngka, 1)->endOfMonth();

            $query->whereBetween('buss_date', [$startDate, $endDate]);
        }

        $data = $query->get();

        return response()->json(['data' => $data]);
    }

    public function export(Request $request)
    {
        $status = $request->input('status_nominative');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        return Excel::download(new SimpananExport($status, $bulan, $tahun), 'simpanan.xlsx');
    }
}
