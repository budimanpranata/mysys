<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\ao;
use App\Models\Kelompok;
use App\Models\Menu;
use App\Models\temp_akad_mus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ApprovalPengajuanController extends Controller
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

        $title = 'Approval Pengajuan';

        return view('al.approval_pengajuan.index', compact('menus', 'title'));
    
    }
    
    public function get_pengajuan(Request $request)
    {
        $data = temp_akad_mus::where('status_app', 'PENDING')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function get_pengajuan_detail($no_anggota)
    {
        $title = 'Approval Pengajuan';

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

        $ao = ao::all();
        $kelompok = Kelompok::all();
        
        $detail = temp_akad_mus::with('anggota')
        ->where('no_anggota', $no_anggota)
        ->first();

        return view('al.approval_pengajuan.detail_anggota', compact('menus', 'title', 'detail', 'ao', 'kelompok'));
    }

    public function update_pengajuan(Request $request, $no_anggota)
    {
        temp_akad_mus::where('no_anggota', $no_anggota)->update([
            'nama' => $request->nama,
            'cif' => $request->cif,
            'plafond' => $request->plafond,
            'saldo_margin' => $request->saldo_margin,
            'updated_at' => now()
        ]);

        // update anggota
        Anggota::where('no', $no_anggota)->update([
            'ibu_kandung' => $request->ibu_kandung,
            'updated_at' => now()
        ]);

        alert()->success('Berhasil!', 'Data berhasil diperbarui.');
        return redirect()->back()->with('success', 'Data pengajuan berhasil diperbarui.');
    }

    public function approvePengajuan($no_anggota)
    {
        $data = temp_akad_mus::where('no_anggota', $no_anggota)->firstOrFail();

        $data->status_app = 'APPROVED';
        $data->updated_at = now();
        $data->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Pengajuan berhasil di-approve',
            'redirect' => url('/al/approval-pengajuan')
        ]);
    }

    public function approveCheckbox(Request $request)
    {
        $request->validate([
            'no_anggota' => 'required|array'
        ]);

        temp_akad_mus::whereIn('no_anggota', $request->no_anggota)
            ->update([
                'status_app' => 'APPROVED',
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan berhasil di-approve'
        ]);
    }

    public function batalCheckbox(Request $request)
    {
        $request->validate([
            'no_anggota' => 'required|array'
        ]);

        temp_akad_mus::whereIn('no_anggota', $request->no_anggota)
            ->update([
                'status_app' => 'BATAL',
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan berhasil di-reject'
        ]);
    }

    public function getKtp(Request $request)
    {
        // Validasi input NIK
        $request->validate([
            'nik' => 'required|string'
        ]);

        $nik = $request->input('nik');

        // Lakukan request ke API eksternal
        $response = Http::get("http://mobcol.nurinsani.co.id/apimobcol/rmcKtp.php?ktp={$nik}");

        // Jika request gagal
        if (!$response->successful()) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
        }

        // Ambil data dari response
        $data = $response->json();

        // Kembalikan data sebagai response JSON
        return response()->json($data);
    }

    

    public function ajukanKembali(Request $request)
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

        $title = 'Ajukan Kembali';
        
        return view('al.approval_pengajuan.ajukan_kembali', compact('menus', 'title'));
        
    }

    public function getCifBatal(Request $request)
    {
        $search = $request->q;

        $data = DB::table('temp_akad_mus')
            ->where('status_app', 'batal')
            ->where(function ($query) use ($search) {
                $query->where('cif', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'id'   => $row->cif,
                'text' => $row->cif . ' - ' . $row->nama
            ];
        }

        return response()->json($result);
    }

    public function prosesAjukanKembali(Request $request)
    {
        $request->validate([
            'cif' => 'required'
        ]);

        DB::table('temp_akad_mus')
            ->where('cif', $request->cif)
            ->where('status_app', 'batal')
            ->update([
                'status_app' => 'PENDING',
                'updated_at' => now()
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Pengajuan berhasil diajukan kembali'
        ]);
    }

    public function hapusPengajuan(Request $request)
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

        $title = 'Hapus Pengajuan';
        
        return view('al.approval_pengajuan.hapus_pengajuan', compact('menus', 'title'));  
    }

    public function getCifHapus(Request $request)
    {
        $search = $request->q;

        $data = DB::table('temp_akad_mus')
            ->whereIn('status_app', ['BATAL', 'PENDING'])
            ->where(function ($query) use ($search) {
                $query->where('cif', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'id'   => $row->cif,
                'text' => $row->cif . ' - ' . $row->nama
            ];
        }

        return response()->json($result);
    }

    public function prosesHapusPengajuan(Request $request)
    {
        $request->validate([
            'cif' => 'required'
        ]);

        $deleted = DB::table('temp_akad_mus')
            ->where('cif', $request->cif)
            ->delete();

        if ($deleted == 0) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan atau status bukan batal'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Pengajuan berhasil dihapus'
        ]);
    }

    public function turunPlafond(Request $request)
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

        $title = 'Turun Plafond';
        
        return view('al.approval_pengajuan.turun_plafond', compact('menus', 'title'));  
    }

    public function getCifTurunPlafond(Request $request)
    {
        $search = $request->q;

        $data = DB::table('temp_akad_mus')
            ->where('status_app', 'PENDING')
            ->where(function ($query) use ($search) {
                $query->where('cif', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'id'   => $row->cif,
                'text' => $row->cif . ' - ' . $row->nama
            ];
        }

        return response()->json($result);
    }

    public function prosesTurunPlafond(Request $request)
    {
        $request->validate([
            'cif' => 'required',
            'harga_baru' => 'required|numeric|min:1'
        ]);

        $data = DB::table('temp_akad_mus')
            ->where('cif', $request->cif)
            ->first();

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        DB::table('temp_akad_mus')
            ->where('cif', $request->cif)
            ->update([
                // 'plafond_lama' => $data->plafond, // simpan histori (opsional)
                'plafond'      => $request->harga_baru,
                'status_app'   => 'PENDING',
                'updated_at'   => now()
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Turun plafond berhasil diproses'
        ]);
    }


}
