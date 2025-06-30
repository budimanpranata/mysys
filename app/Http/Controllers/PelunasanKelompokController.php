<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PelunasanKelompokController extends Controller
{
    public function index()
    {
        $title = 'Pelunasan Kelompok';
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('admin.pelunasan_kelompok.index', compact('title', 'menus'));
    }

    public function cari(Request $request)
    {
        $cari = $request->input('cari');

        $results = DB::table('kelompok')
            ->select('code_kel', 'nama_kel')
            ->where('code_unit', Auth::user()->unit)
            ->where(function ($query) use ($cari) {
                $query->where('code_kel', 'like', '%' . $cari . '%')
                    ->orWhere('nama_kel', 'like', '%' . $cari . '%');
            })
            ->limit(10)
            ->get();

        return response()->json($results);
    }

    public function filter(Request $request)
    {
        $code_kel = $request->input('code_kel');

        // Data kelompok
        $get_kelompok = DB::table('kelompok')
            ->where('code_kel', $code_kel)
            ->first();

        if (!$get_kelompok) {
            return response()->json(['message' => 'Kamu belum pilih kelompok'], 404);
        }

        // Data anggota
        $get_anggota = DB::table('anggota')
            ->join('pembiayaan', 'anggota.no', '=', 'pembiayaan.no_anggota')
            ->where('pembiayaan.code_kel', $code_kel)
            ->where('pembiayaan.run_tenor', '<', DB::raw('pembiayaan.tenor')) // anggota yang masih memiliki angsuran
            ->select(
                'anggota.*',
                'pembiayaan.*',
            )
            ->get();

        return response()->json([
            'kelompok' => $get_kelompok,
            'anggota' => $get_anggota,
        ]);
    }
}
