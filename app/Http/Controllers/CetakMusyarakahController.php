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

        // Ngambil dr database
        $results = DB::table('temp_akad_mus')
            ->where('tgl_akad', $tanggalCetak)
            ->get();

        // Kalo error
        if ($results->isEmpty()) {
            alert()->error('Oops!', 'Tidak ada data yang ditemukan untuk tanggal tersebut.');
            return redirect()->back();
        }

        // Kalo sukses
        alert()->success('Berhasil!', 'Data berhasil ditemukan.');
        return view('admin.cetak_musyarakah.result', ['results' => $results, 'menus' => $menus, 'title' => $title]);
    }
}
