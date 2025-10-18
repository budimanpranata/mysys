<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Pembiayaan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
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
        //dd($pembiayaan);
        $title = 'Dashboard';

        return view('admin.index',compact('menus','pembiayaan','title'));
    }
}
