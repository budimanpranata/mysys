<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class PostingJurnalKpController extends PostingJurnalController
{
    public function index()
    {
        $title = 'Posting Jurnal';
        $roleId = Auth::user()->role_id;
        $menus = Menu::whereNull('parent_id')
            ->where(function ($q) use ($roleId) {
                $q->where('role_id', $roleId)->orWhereNull('role_id');
            })
            ->with(['children' => function ($q) use ($roleId) {
                $q->where('role_id', $roleId)->orWhereNull('role_id');
            }])
            ->orderBy('order')
            ->get();

        $data = $this->buildData(Auth::user()->unit, Auth::user()->param_tanggal);

        return view('kp.posting_jurnal.index', array_merge(compact('title', 'menus'), $data));
    }

    public function posting()
    {
        $this->jalankanPosting(Auth::user()->unit, Auth::user()->param_tanggal);

        return redirect()->route('posting-jurnal-kp.index')->with('success', 'Proses Posting Selesai');
    }
}
