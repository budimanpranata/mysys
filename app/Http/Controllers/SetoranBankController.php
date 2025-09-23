<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;

class SetoranBankController extends Controller
{
    public function index()
    {
         $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $title = 'Setoran Bank';
        return view('admin.setoran_bank.index', compact('menus', 'title'));
    }

    public function getData(Request $request)
    {
        $userid = auth()->user()->unit;



    $data = DB::connection('cs')->select("

           SELECT tgl_setor,kode_unit,nama_ao,bank,name,image
         FROM bukti_setor left join ao on ao.cao = bukti_setor.cao
        WHERE kode_unit = ?
    ", [$userid]);

    return response()->json([
        'data' => $data
    ]);
    }
}
