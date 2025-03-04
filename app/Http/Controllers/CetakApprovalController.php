<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;

class CetakApprovalController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $title = 'Cetak Approval';

        return view("admin.cetak_approval.index", compact("menus", "title"));
    }

    public function hasil(Request $request)
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $title = 'Cetak Approval';

        $request->validate([
            'tanggal_murab' => 'required|date_format:Y-m-d',
            'unit' => 'required|string'
        ]);

        $tanggalMurab = \Carbon\Carbon::createFromFormat('Y-m-d', $request->tanggal_murab)
            ->startOfDay()
            ->format('Y-m-d H:i:s');

        $unit = $request->unit;

        $results = DB::table('temp_akad_mus')
            ->where('tgl_murab', $tanggalMurab)
            ->where('unit', $unit)
            ->get();

        // Error handling if no data is found
        if ($results->isEmpty()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang ditemukan untuk tanggal dan unit tersebut.',
                ]);
            }

            alert()->error('Oops!', 'Tidak ada data yang ditemukan untuk tanggal dan unit tersebut.');
            return redirect()->back();
        }

        // AJAX response
        if ($request->ajax()) {
            $iframeUrl = route('pdf.generateApproval', [
                'feature' => 'cetak_approval',
                'date' => $tanggalMurab,
                'unit' => $unit,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditemukan.',
                'iframe_url' => $iframeUrl,
            ]);
        }

        alert()->success('Berhasil!', 'Data berhasil ditemukan.');
        return view('admin.cetak_approval.result', compact('results', 'menus', 'title'));
    }
}
