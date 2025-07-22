<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JurnalKeluarController extends Controller
{
    public function index()
    {
        $title = 'Jurnal Keluar';
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();

        $kodeUnit = Auth::user()->unit;
        $random = strtoupper(Str::random(7));
        $kodeTransaksi = 'KK/' . $kodeUnit . $random;

        $kodeGL = DB::table('branch')
            ->where('kode_branch', $kodeUnit)
            ->value('GL');

        return view('admin.jurnal_keluar.index', compact('menus', 'title', 'kodeTransaksi', 'kodeGL'));
    }
}
