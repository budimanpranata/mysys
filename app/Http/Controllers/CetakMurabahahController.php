<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\temp_akad_mus;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $tglMurab = $request->input('tgl_murab');

        // Ambil data berdasarkan tanggal
        $data = temp_akad_mus::whereDate('tgl_murab', $tglMurab)->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function cetakPDF(Request $request)
    {
        $tglMurab = $request->input('tgl_murab');

        // Ambil data berdasarkan tanggal
        $data = temp_akad_mus::whereDate('tgl_murab', $tglMurab)->get();

        if ($data->isEmpty()) {

            alert()->error('Oops!', 'Data tidak di temukan!');
            return redirect()->back();
        }

        // Generate PDF
        $pdf = PDF::loadView('admin.cetak_murabahah.pdf', compact('data', 'tglMurab'));

        // // Unduh PDF
        // return $pdf->download('Murabahah-' . $tglMurab . '.pdf');

        // Tampilkan preview di browser
        return $pdf->stream('Murabahah-' . $tglMurab . '.pdf');
        // dd($data);
    }
}
