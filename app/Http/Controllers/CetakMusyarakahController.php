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
            'tanggal_cetak' => 'required|date_format:d-m-Y',
        ]);

        // Convert date to yyyy-mm-dd format
        $tanggalCetak = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal_cetak)->format('Y-m-d');

        $results = DB::table('temp_akad_mus')
            ->where('tgl_akad', $tanggalCetak)
            ->get();

        // Error handling
        if ($results->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data yang ditemukan untuk tanggal tersebut.');
        }

        // Kalo success
        return view('admin.cetak_musyarakah.result', ['results' => $results, 'menus' => $menus, 'title' => $title]);
    }
}
