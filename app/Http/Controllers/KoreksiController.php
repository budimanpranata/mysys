<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KoreksiController extends Controller
{
    public function index()
    {
        $roleId = Auth::user()->role_id;

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

        $title = 'Koreksi';

        return view('al.koreksi.index', compact('menus', 'title'));
    
    }

    public function anyData()
    {
        $unitUser = Auth::user()->unit;

        $transaksi = DB::table('tabel_transaksi')->select([
            'id_transaksi', 'tabel_transaksi.unit', 'kode_transaksi', 'kode_rekening', 
            'tanggal_transaksi', 'jenis_transaksi', 'keterangan_transaksi', 
            'debet', 'kredit', 'tanggal_posting'
        ])
        ->join('branch', 'tabel_transaksi.unit', '=', 'branch.kode_branch')
        ->where('branch.code_area', $unitUser); // Filter berdasarkan code_area dari branch

        return DataTables::of($transaksi)
            ->addIndexColumn()
            ->editColumn('debet', function($row){
                return number_format($row->debet, 0, ',', '.');
            })
            ->editColumn('kredit', function($row){
                return number_format($row->kredit, 0, ',', '.');
            })
            ->addColumn('aksi', function($row){
                $url = route('transaksi.destroy', $row->id_transaksi);
                return '
                    <form id="delete-form-'.$row->id_transaksi.'" action="'.$url.'" method="POST" style="display:none;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                    </form>
                    <button onclick="deleteData('.$row->id_transaksi.')" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function destroy($id_transaksi)
    {
        $transaksi = DB::table('tabel_transaksi')->where('id_transaksi', $id_transaksi)->first();
        if ($transaksi) {
            DB::table('tabel_transaksi')->where('id_transaksi', $id_transaksi)->delete();
        }

        return redirect()->back()->with('success', 'Data transaksi berhasil dihapus.');
    }
}
