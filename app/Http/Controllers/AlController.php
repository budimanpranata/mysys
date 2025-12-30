<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlController extends Controller
{
    public function index()
    {
    $roleId = auth()->user()->role_id;
    $menus = Menu::whereNull('parent_id')
    ->where(function ($query) use ($roleId) {
        $query->where('role_id', $roleId)
              ->orWhereNull('role_id');
    })
    ->with(['children' => function ($query) use ($roleId) {
        $query->where('role_id', $roleId)
              ->orWhereNull('role_id');
    }])
    ->orderBy('order')
    ->get();

        $pembiayaan = DB::table('pembiayaan')
            ->selectRaw('SUM(os - saldo_margin) as os, COUNT(cif) as noa')
            ->first();

        return view('kp.index', compact('menus', 'pembiayaan'));

    }
}
