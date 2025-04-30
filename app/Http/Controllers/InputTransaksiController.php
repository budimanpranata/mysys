<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\temp_akad_mus;
use Illuminate\Http\Request;

class InputTransaksiController extends Controller
{
    public function index()
    {
        $title = 'Input Transaksi';
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('admin.input_transaksi.index', compact('menus', 'title'));
    }

    public function getByCif($cif)
    {
        try {
            $cif = temp_akad_mus::where('cif', $cif)->first();
            
            if (!$cif) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nama dengan CIF tersebut tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'nama' => $cif->nama
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }
}
