<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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

        // Ambil data anggota berdasarkan CIF atau rekening
        $anggota = DB::table('pembiayaan')
            ->where('cif', $cif)
            ->orWhere('no_anggota', $cif)
            ->first();

            // dd($anggota);

        if (!$anggota) {
            return abort(404, 'Data tidak ditemukan');
        }

        // Pilih view berdasarkan jenis
        $view = $jenis == 1 ? 'admin/report_mutasi/cetak_pdf' : 'pdf.kartu_angsuran';

        // Generate PDF
        $pdf = Pdf::loadView($view, compact('anggota'));
        return $pdf->stream('data_anggota.pdf');
    }
}
