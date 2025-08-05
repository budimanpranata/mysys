<?php

namespace App\Http\Controllers;

use App\Exports\PembiayaanExport;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportNominativePembiayaanController extends Controller
{
    public function index()
    {
        $title = 'Report Nominative Pembiayaan';
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();

        return view('admin.report_nominative_pembiayaan.index', compact('menus', 'title'));
    }

    public function getData(Request $request)
    {
        $status = $request->input('status_nominative');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $query = DB::table('pembiayaan')
        ->select(
            'unit',
            DB::raw('COUNT(DISTINCT no_anggota) as total_noa'),
            DB::raw('SUM(os) as total_saldo')
        )
        ->where('unit', Auth::user()->unit)
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

    public function export()
    {
        return Excel::download(new PembiayaanExport, 'Pembiayan.xlsx');
    }
}
