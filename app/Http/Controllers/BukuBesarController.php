<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericExport;
use App\Exports\BukuBesarExport;
use App\Exports\BukuBesarMultiSheetExport;

class BukuBesarController extends Controller
{
       public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $data = DB::table('pull_data')->get();
        //dd($pembiayaan);
        $title = 'Buku Besar';

        return view('admin.buku_besar.index',compact('menus','title','data'));

    }



       public function proses(Request $request)
    {
        $jenis_pull   = $request->jenis_pull;
        $bulan        = $request->jenis_transaksi;
        $tahun        = $request->tahun;
        $coa          = $request->kode_rekening;
        $all_data     = $request->all_data;
         $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
          $title = 'Buku Besar';


        $unit = auth()->user()->unit ?? '1010';
        $query = DB::table('tabel_master');

        if ($coa) {
            $query->where('kode_rekening', 'like', "%$coa%")
                  ->where('unit',$unit)
                  ->orWhere('nama_rekening', 'like', "%$coa%");
        }

        $data = $query->select('kode_rekening','nama_rekening','saldo_awal','saldo_akhir')
                      ->get();

        return view('admin.buku_besar.index', compact('data'))
            ->with('tahun', $tahun)
            ->with('bulan', $bulan)
            ->with('menus', $menus)
            ->with('title', $title)
            ->with('all_data', $all_data);

    }

    public function download(Request $request, $no_perkiraan)
   {
        $tahun = $request->get('tahun');
        $bulan = $request->get('bulan');
        $all   = $request->get('all_data');

        // ambil transaksi
        $query = DB::table('tabel_transaksi')
            ->where('kode_rekening', $no_perkiraan);

        if (!$all) {
            if ($tahun) {
                $query->whereYear('tanggal_transaksi', $tahun);
            }
            if ($bulan) {
                $query->whereMonth('tanggal_transaksi', $bulan);
            }
        }

        $transactions = $query->orderBy('tanggal_transaksi', 'asc')
            ->get(['tanggal_transaksi','kode_transaksi','kode_rekening','keterangan_transaksi','debet','kredit']);

        // ambil info master akun
        $akun = DB::table('tabel_master')->where('kode_rekening', $no_perkiraan)->first();

        $info = [
            'unit'          => '004',
            'kode_rekening'  => $akun->kode_rekening ?? $no_perkiraan,
            'nama_rekening'=> $akun->nama_rekening ?? '-',
            'saldo_awal'    => $akun->saldo_awal ?? 0,
            'saldo_akhir'   => $akun->saldo_akhir ?? 0,
            'normal'    => $akun->normal,
        ];



        // tentukan judul
        $title = "Laporan Buku Besar: {$no_perkiraan}";
        if (!$all && $bulan && $tahun) {
            $title .= " - Periode {$bulan}/{$tahun}";
        } elseif (!$all && $tahun) {
            $title .= " - Tahun {$tahun}";
        } else {
            $title .= " - Semua Data";
        }

        return Excel::download(
            new BukuBesarMultiSheetExport($transactions, $info, $title),
            "buku_besar_{$no_perkiraan}.xlsx"
        );
    }

    public function suggest(Request $request)
    {
        $q = $request->get('q');
        $unit = auth()->user()->unit ?? '1010';

        $result = DB::table('tabel_master')
            ->where('kode_rekening', 'like', "%$q%")
            ->orWhere('nama_rekening', 'like', "%$q%")
            ->where('unit',$unit)
            ->limit(1)
            ->get(['kode_rekening','nama_rekening']);

        return response()->json($result);
    }

}
