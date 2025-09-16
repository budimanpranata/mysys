<?php

namespace App\Http\Controllers;

use App\Exports\EkuitasExport;
use App\Models\Menu;
use App\Models\ReportEkuitas;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportEkuitasController extends Controller
{
    public function index()
    {
        $title = 'Report Ekuitas';
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $report_ekuitas = ReportEkuitas::all();
        return view('admin.report_ekuitas.index', compact('title', 'menus', 'report_ekuitas'));
    }

    public function exportEkuitas()
    {
        return Excel::download(new EkuitasExport, 'laporan_ekuitas.xlsx');
    }
}
