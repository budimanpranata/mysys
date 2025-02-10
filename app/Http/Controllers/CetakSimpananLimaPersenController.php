<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\temp_akad_mus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

use function Laravel\Prompts\select;

class CetakSimpananLimaPersenController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $title = 'Cetak Simpanan 5%';

        return view('admin.cetak_simpanan_5_persen.index', compact('menus', 'title'));
    }

    public function filter(Request $request)
    {
        $code_kel = $request->input('code_kel');
        $tgl_akad = $request->input('tgl_akad');

        // query data berdasarkan kode kelompok dan tanggal akad
        $data = DB::table('temp_akad_mus')
            ->join('anggota', 'temp_akad_mus.no_anggota', '=', 'anggota.no') // Relasi antar tabel
            ->select(
                'temp_akad_mus.tgl_akad',
                'temp_akad_mus.code_kel',
                'anggota.cif',
                'anggota.ktp',
                'anggota.nama as nama_anggota'
            )
            ->where('temp_akad_mus.code_kel', $code_kel)
            ->whereDate('temp_akad_mus.tgl_akad', $tgl_akad)
            ->get();

        // dd($data);

        return response()->json(['data' => $data]);
    }

    public function cetakPDF(Request $request)
    {
        $code_kel = $request->input('code_kel');
        $tgl_akad = $request->input('tgl_akad');

        // query data berdasarkan kode kelompok dan tanggal akad yang ingin di cetak
        $data = DB::table('temp_akad_mus')
            ->join('anggota', 'temp_akad_mus.no_anggota', '=', 'anggota.no') // Relasi antar tabel anggota
            ->select(
                'temp_akad_mus.tgl_akad',
                'temp_akad_mus.code_kel',
                'temp_akad_mus.plafond',
                'anggota.cif',
                'anggota.ktp',
                'anggota.norek',
                'anggota.nama as nama_anggota',
            )
            ->where('temp_akad_mus.code_kel', $code_kel)
            ->whereDate('temp_akad_mus.tgl_akad', $tgl_akad)
            ->get();
        
        $data_kel = DB::table('temp_akad_mus')
            ->join('kelompok', 'temp_akad_mus.code_kel', '=', 'kelompok.code_kel')
            ->join('ao', 'kelompok.cao', '=', 'ao.cao') // Join ke tabel ao
            ->select(
                'temp_akad_mus.tgl_akad',
                'kelompok.code_kel',
                'kelompok.nama_kel',
                'ao.nama_ao',
                'ao.kode_unit'
            )
            ->where('temp_akad_mus.code_kel', $code_kel)
            ->whereDate('temp_akad_mus.tgl_akad', $tgl_akad)
            ->first();

        // dd($data_kel);

        if ($data->isEmpty()) {

            alert()->error('Oops!', 'Data tidak di temukan!');
            return redirect()->back();
        }

        // generate PDF
        $pdf = PDF::loadView('admin.cetak_simpanan_5_persen.pdf', compact('data', 'tgl_akad', 'data_kel'));

        // tampilkan preview di browser
        return $pdf->stream('Simpanan 5 persen - ' . $tgl_akad . '.pdf');
        // dd($data);
    }

}
