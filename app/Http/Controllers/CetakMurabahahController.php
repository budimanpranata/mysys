<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class CetakMurabahahController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $title = 'Cetak Murabahah';

        return view('admin.cetak_murabahah.index', compact('menus', 'title'));
    }

    public function filter(Request $request)
    {
        $code_kel = $request->input('code_kel');
        $tgl_murab = $request->input('tgl_murab');

        // Ambil data berdasarkan tanggal
        $data = DB::table('temp_akad_mus')
            ->join('anggota', 'temp_akad_mus.no_anggota', '=', 'anggota.no') // Relasi antar tabel
            ->select(
                'temp_akad_mus.tgl_murab',
                'temp_akad_mus.code_kel',
                'temp_akad_mus.tenor',
                'anggota.cif',
                'anggota.ktp',
                'anggota.nama as nama_anggota'
            )
            ->where('temp_akad_mus.code_kel', $code_kel)
            ->whereDate('temp_akad_mus.tgl_murab', $tgl_murab)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function cetakPDF(Request $request)
    {
        $code_kel = $request->input('code_kel');
        $tgl_murab = $request->input('tgl_murab');

        // Ambil data berdasarkan tanggal
        $data = DB::table('temp_akad_mus')
            ->join('anggota', 'temp_akad_mus.no_anggota', '=', 'anggota.no') // Relasi antar tabel
            ->join('kelompok', 'temp_akad_mus.code_kel', '=', 'kelompok.code_kel') // Relasi antar tabel
            ->join('ao', 'kelompok.cao', '=', 'ao.cao') // Relasi antar tabel
            ->join('mm', 'ao.atasan', '=', 'mm.nik') // Relasi antar tabel
            ->select(
                'temp_akad_mus.*',
                'anggota.*',
                'anggota.nama as nama_anggota',
                'mm.nama as nama_mm',
                'mm.jabatan',
            )
            ->where('temp_akad_mus.code_kel', $code_kel)
            ->whereDate('temp_akad_mus.tgl_murab', $tgl_murab)
            ->get();

        if ($data->isEmpty()) {

            alert()->error('Oops!', 'Data tidak di temukan!');
            return redirect()->back();
        }

        // Generate PDF
        $pdf = PDF::loadView('admin.cetak_murabahah.pdf', compact('data', 'tgl_murab'));

        // // Unduh PDF
        // return $pdf->download('Murabahah-' . $tglMurab . '.pdf');

        // Tampilkan preview di browser
        return $pdf->stream('Murabahah-' . $tgl_murab . '.pdf');
        // dd($data);
    }
}
