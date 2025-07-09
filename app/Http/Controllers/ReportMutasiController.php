<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\PembiayaanDetail;
use App\Models\simpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Database\Seeders\PembiayaanDetailSeeder;

class ReportMutasiController extends Controller
{
    public function index()
    {
        $title = 'Repoer Mutasi';
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('admin.report_mutasi.index', compact('title', 'menus'));
    }

    public function cetakPdf(Request $request)
    {
        $cif = $request->query('cif');
        $jenis = $request->query('jenis');

        $anggota = DB::table('pembiayaan')
            ->where('cif', $cif)
            ->orWhere('no_anggota', $cif)
            ->first();
        
        $mutasiSimpanan = simpanan::where('cif', $cif)
        ->orderBy('buss_date', 'asc')
        ->get();

        $mutasiKartuAngsuran = PembiayaanDetail::where('cif', $cif)
        ->orderBy('tgl_bayar', 'asc')
        ->get();

            // dd($anggota);

        if (!$anggota) {
            return abort(404, 'Data tidak ditemukan');
        }

        // Pilih view berdasarkan jenis
        $view = $jenis == 1 ? 'admin/report_mutasi/cetak_simpanan' : 'admin/report_mutasi/cetak_kartu_angsuran';

        // Generate PDF
        $pdf = Pdf::loadView($view, compact('anggota', 'mutasiSimpanan', 'mutasiKartuAngsuran'));
        return $pdf->stream('mutasi_' . $cif . '.pdf');
    }
}
