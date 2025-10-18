<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use App\Jobs\ExportBukuBesarJob;
use App\Models\Export;

class BukuBesarKpController extends Controller
{
    public function index()
    {
        $roleId = auth()->user()->role_id;
        $menus = Menu::whereNull('parent_id')
            ->where(function ($q) use ($roleId) {
                $q->where('role_id', $roleId)->orWhereNull('role_id');
            })
            ->with(['children' => function ($q) use ($roleId) {
                $q->where('role_id', $roleId)->orWhereNull('role_id');
            }])
            ->orderBy('order')
            ->get();

        $title = 'Buku Besar';
        $exports = Export::latest()->take(5)->get();

        return view('kp.buku_besar.index', compact('menus', 'title', 'exports'));
    }

    public function proses(Request $request)
    {
          if (!$request->has('_token')) {
        return response()->json(['error' => 'Token tidak terkirim!'], 419);
    }
        $no_perkiraan = $request->kode_rekening;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $all = $request->boolean('all_data');

        // simpan ke tabel exports
        $exportId = DB::table('exports')->insertGetId([
            'file_name' => 'buku_besar_' . $no_perkiraan . '_' . now()->format('Ymd_His') . '.zip',
            'status' => 'processing',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // kirim ke queue
        ExportBukuBesarJob::dispatch(
            $no_perkiraan,
            $tahun,
            $bulan,
            $all,
            auth()->user()->email ?? null,
            $exportId
        );

        return response()->json([
            'message' => 'Proses export sedang dijalankan di background. Silakan cek nanti di tabel hasil export.',
        ]);
    }

    public function suggest(Request $request)
    {
        $q = $request->get('q');
        $unit = auth()->user()->unit ?? '1010';

        $result = DB::table('tabel_master')
            ->where('unit', $unit)
            ->where(function ($query) use ($q) {
                $query->where('kode_rekening', 'like', "%$q%")
                    ->orWhere('nama_rekening', 'like', "%$q%");
            })
            ->limit(10)
            ->get(['kode_rekening', 'nama_rekening']);

        return response()->json($result);
    }

    public function list()
    {
        $exports = Export::latest()->take(5)->get();

        // render partial untuk tabel
        return view('kp.buku_besar.export_list', compact('exports'));
    }

    public function download($id)
    {
        $export = Export::findOrFail($id);
        $path = storage_path("app/exports/{$export->file_name}");

        if (file_exists($path)) {
            return response()->download($path);
        }

        return back()->with('error', 'File belum tersedia.');
    }
}
