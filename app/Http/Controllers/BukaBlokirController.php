<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;

class BukaBlokirController extends Controller
{
    public function index()
    {
        $roleId = auth()->user()->role_id;
        
        $menus = Menu::whereNull('parent_id')
            ->where(function ($q) use ($roleId) {
                $q->where('role_id', $roleId)->orWhereNull('role_id');
            })
            ->with(['children' => function ($q) use ($roleId) {
                $q->where('role_id', $roleId)->orWhereNull('role_id');
            }])
            ->orderBy('order')
            ->get();

        $title = 'Buka Blokir Simpanan';

        return view('al.buka_blokir.index', compact('menus', 'title'));
    }

    public function getKelompok(Request $request)
    {
        $search = $request->term;

        $data = DB::table('kelompok') // Sesuaikan tabel sumber data kelompok Anda
            ->select('code_kel', 'nama_kel')
            ->where(function($q) use ($search) {
                $q->where('code_kel', 'LIKE', '%' . $search . '%')
                ->orWhere('nama_kel', 'LIKE', '%' . $search . '%');
            })
            ->distinct()
            ->limit(20)
            ->get();

        $formatted = [];
        foreach ($data as $row) {
            $formatted[] = [
                'id' => $row->code_kel,
                'text' => $row->code_kel . ' - ' . $row->nama_kel
            ];
        }

        return response()->json($formatted);
    }

    public function getCif(Request $request)
    {
        $search = $request->term;

        $data = DB::table('pembiayaan')
            ->select('cif', 'nama')
            ->where(function($q) use ($search) {
                $q->where('cif', 'LIKE', '%' . $search . '%')
                ->orWhere('nama', 'LIKE', '%' . $search . '%');
            })
            ->limit(20)
            ->get();

        $formatted = [];
        foreach ($data as $row) {
            $formatted[] = [
                'id' => $row->cif,
                'text' => $row->cif . ' - ' . $row->nama
            ];
        }

        return response()->json($formatted);
    }

    public function updateBlokirSimpanan(Request $request)
    {
        $grupBlokir  = $request->grup_blokir;
        $cif         = $request->cif;
        $kelompok    = $request->kelompok;
        $jenisBlokir = $request->jenis_blokir;

        $query = DB::table('simpanan');

        if ($grupBlokir === 'individu') {
            $query->where('simpanan.cif', $cif);
        } else if ($grupBlokir === 'kelompok') {
            $query->where('simpanan.code_kel', $kelompok);
        }

        $updateData = [];
        if ($jenisBlokir === 'buka') {
            $updateData = ['blok' => 1];
        } else if ($jenisBlokir === 'tutup') {
            $updateData = ['blok' => 0];
        }

        if (!empty($updateData)) {
            DB::table('simpanan');
            if ($grupBlokir === 'individu') {
                DB::table('simpanan')->where('cif', $cif)->update($updateData);
            } else if ($grupBlokir === 'kelompok') {
                DB::table('simpanan')->where('code_kel', $kelompok)->update($updateData);
            }
        }

        $dataSimpanan = DB::table('simpanan')
            ->leftJoin('pembiayaan', 'simpanan.cif', '=', 'pembiayaan.cif')
            ->select('simpanan.*', 'pembiayaan.nama as nama')
            ->when($grupBlokir === 'individu', function($q) use ($cif) {
                return $q->where('simpanan.cif', $cif);
            })
            ->when($grupBlokir === 'kelompok', function($q) use ($kelompok) {
                return $q->where('simpanan.code_kel', $kelompok);
            })
            ->get();

        $saldoBerjalan = 0;
        $dataSimpanan->transform(function ($item) use (&$saldoBerjalan) {
            $debet = $item->debet ?? 0;
            $kredit = $item->kredit ?? 0;
            
            $saldoBerjalan = $saldoBerjalan + $kredit - $debet;
            $item->saldo = $saldoBerjalan;
            return $item;
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Status blokir simpanan berhasil diperbarui!',
            'data'    => $dataSimpanan
        ]);
    }
}
