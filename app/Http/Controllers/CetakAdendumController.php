<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CetakAdendumController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $title = 'Cetak Adendum';

        return view('admin.cetak_adendum.index', compact('menus', 'title'));
    }

    public function filter(Request $request)
    {
        $code_kel = $request->input('code_kel');
        $jenis_rest = $request->input('jenis_rest');

        $data = DB::table('history_rest')
            ->join('anggota', 'history_rest.cif', '=', 'anggota.cif') // Relasi antar tabel
            ->join('pembiayaan', 'history_rest.cif', '=', 'pembiayaan.cif') // Relasi antar tabel
            ->select(
                'history_rest.*',
                'anggota.*',
                'anggota.nama as nama_anggota',
                'pembiayaan.*'
            )
            ->where('history_rest.code_kel', $code_kel)
            ->where('history_rest.jenis_rest', $jenis_rest)
            ->where('history_rest.status', 'proses')
            ->get();

        // dd($data);

        return response()->json(['data' => $data]);
    }

    public function cetakPDF(Request $request)
    {
        $hari_ini = Carbon::now()->locale('id')->isoFormat('dddd');
        $today = Carbon::now()->locale('id')->isoFormat('D MMMM Y');

        $code_kel = $request->input('code_kel');
        $jenis_rest = $request->input('jenis_rest');

        // Ambil data berdasarkan tanggal
        $data = DB::table('history_rest')
            ->join('anggota', 'history_rest.cif', '=', 'anggota.cif') // Relasi antar tabel
            ->join('kelompok', 'history_rest.code_kel', '=', 'kelompok.code_kel') // Relasi antar tabel
            ->join('pembiayaan', 'history_rest.cif', '=', 'pembiayaan.cif') // Relasi antar tabel
            ->join('ao', 'kelompok.cao', '=', 'ao.cao') // Relasi antar tabel
            ->join('mm', 'ao.atasan', '=', 'mm.nik') // Relasi antar tabel
            ->select(
                'history_rest.pokok as rest_pokok',
                'history_rest.margin as rest_margin',
                'history_rest.tenor as rest_tenor',
                'history_rest.angsuran as rest_angsuran',
                'anggota.*',
                'anggota.nama as nama_anggota',
                'pembiayaan.*',
                'pembiayaan.pokok as pokok_pembiayaan',
                'pembiayaan.saldo_margin as margin_pembiayaan',
                'pembiayaan.tenor as tenor_pembiayaan',
                'pembiayaan.angsuran as angsuran_pembiayaan',
                'kelompok.*',
                'ao.*',
                'mm.nama as nama_mm',
                'mm.jabatan',
            )
            ->where('history_rest.code_kel', $code_kel)
            ->where('history_rest.jenis_rest', $jenis_rest)
            ->where('history_rest.status', 'proses')
            ->get();

            // dd($hari_ini, $today);

        if ($data->isEmpty()) {

            alert()->error('Oops!', 'Data tidak di temukan!');
            return redirect()->back();
        }

        // Update status menjadi "done"
        DB::table('history_rest')
            ->where('code_kel', $code_kel)
            ->where('jenis_rest', $jenis_rest)
            ->update(['status' => 'done']);

        // Generate PDF
        $pdf = PDF::loadView('admin.cetak_adendum.pdf', compact('data', 'jenis_rest', 'hari_ini', 'today'));

        // // Unduh PDF
        // return $pdf->download('Murabahah-' . $tglMurab . '.pdf');

        // Tampilkan preview di browser
        return $pdf->stream('Adendum-' . $code_kel . '.pdf');
        // dd($data);
    }
}
