<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class RealisasiMurabahahController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $title = 'Realisasi Murabahah';

        return view("admin.realisasi_murabahah.index", compact("menus", "title"));
    }
}
