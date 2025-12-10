<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class ApprovalPengajuanController extends Controller
{
    public function indexs()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $title = 'Approval Pengajuan';

        return view('al.approval_pengajuan.index', compact('menus', 'title'));
    }

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

    $title = 'Approval Pengajuan';


    return view('al.approval_pengajuan.index', compact('menus', 'title'));
    
    }
}
