<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;

class CetakMusyarakahController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $title = 'Cetak Musyarakah';

        return view("admin.cetak_musyarakah.index", compact("menus", "title"));
    }

    public function hasil(Request $request)
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $title = 'Cetak Musyarakah';

        $request->validate([
            'tanggal_cetak' => 'required|date_format:Y-m-d',
        ]);

        $tanggalCetak = \Carbon\Carbon::createFromFormat('Y-m-d', $request->tanggal_cetak)->format('Y-m-d');

        $results = DB::table('temp_akad_mus')
            ->where('tgl_akad', $tanggalCetak)
            ->get();

        if ($results->isEmpty()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang ditemukan untuk tanggal tersebut.',
                ]);
            }

            alert()->error('Oops!', 'Tidak ada data yang ditemukan untuk tanggal tersebut.');
            return redirect()->back();
        }

        if ($request->ajax()) {
            $iframeUrl = route('pdf.generate', [
                'feature' => 'cetak_musyarakah',
                'date' => $tanggalCetak,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditemukan.',
                'iframe_url' => $iframeUrl,
            ]);
        }

        alert()->success('Berhasil!', 'Data berhasil ditemukan.');
        return view('admin.cetak_musyarakah.result', compact('results', 'menus', 'title'));
    }
}
