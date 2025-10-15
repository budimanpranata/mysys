<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KpController extends Controller
{
    public function index()
    {
        $menus = Menu::where(function ($query) {
            $query->whereNull('role_id')
                ->orWhere('role_id', Auth::user()->role_id);
        })
        ->orderBy('order', 'asc')
        ->get();

        $pembiayaan = DB::table('pembiayaan')
            ->selectRaw('SUM(os - saldo_margin) as os, COUNT(cif) as noa')
            ->first();

        return view('kp.index', compact('menus', 'pembiayaan'));
    
    }
}
