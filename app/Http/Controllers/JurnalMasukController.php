<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class JurnalMasukController extends Controller
{
    public function index()
    {
        $title = 'Jurnal Masuk';
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();

        $kodeUnit = Auth::user()->unit;
        $random = strtoupper(Str::random(8));
        $kodeTransaksi = 'KM/' . $kodeUnit . $random;

        return view('admin.jurnal_masuk.index', compact('menus', 'title', 'kodeTransaksi'));
    }
}
