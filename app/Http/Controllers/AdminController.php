<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Pembiayaan;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $pembiayaan = DB::table('pembiayaan')
        ->selectRaw('SUM(os - saldo_margin) as os, COUNT(cif) as noa')
        ->first();
        //dd($pembiayaan);

        return view('admin.index',compact('menus','pembiayaan'));
    }
}
