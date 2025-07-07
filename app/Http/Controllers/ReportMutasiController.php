<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class ReportMutasiController extends Controller
{
    public function index()
    {
        $title = 'Setoran Perkelompok';
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('admin.report_mutasi.index', compact('title', 'menus'));
    }
}
