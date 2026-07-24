<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Pembiayaan;
use App\Models\Simpanan;
use Illuminate\Support\Facades\Auth;

class ApprovalDeviasiController extends Controller
{
    public function index()
    {
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

        $title = 'Cari Data Approve';

        return view('al.approval_deviasi.index', compact('menus', 'title'));
    }

    public function getDeviasiUmurData(Request $request)
    {
        $tanggalPencairan = $request->tanggal;

        // Ambil data dari tabel pembiayaan berdasarkan tgl_wakalah
        $data = Pembiayaan::whereDate('tgl_wakalah', $tanggalPencairan)
                    // ->where('status', 'pending') // Tambahkan kondisi jika perlu
                    ->get();

        $html = '';
        foreach ($data as $item) {
            $html .= '<tr>';
            $html .= '<td><input type="checkbox" name="selected_ids[]" value="'.$item->cif.'"></td>';
            $html .= '<td>'.$item->unit.'</td>';
            $html .= '<td>'.$item->cif.'</td>';
            $html .= '<td>'.$item->nama.'</td>';
            $html .= '<td>'.$item->tgl_wakalah.'</td>';
            $html .= '<td>'.$item->no_anggota.'</td>';
            $html .= '<td>'.number_format($item->os, 0, ',', '.').'</td>';
            $html .= '';
            $html .= '</tr>';
        }

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    public function getPenarikanTabunganData(Request $request)
    {
        $tanggalPenarikan = $request->tanggal;

        // Ambil data dari tabel simpanan berdasarkan tgl_input
        $data = Simpanan::whereDate('tgl_input', $tanggalPenarikan)
                    // ->where('jenis_transaksi', 'penarikan') // Tambahkan filter jika diperlukan
                    ->get();

        $html = '';
        foreach ($data as $item) {
            $html .= '<tr>';
            $html .= '<td><input type="checkbox" name="selected_ids[]" value="'.$item->reff.'"></td>';
            $html .= '<td>'.$item->unit.'</td>';
            $html .= '<td>'.$item->cif.'</td>';
            $html .= '<td>'.$item->nama.'</td>';
            $html .= '<td>'.$item->tgl_input.'</td>';
            $html .= '<td>'.$item->norek.'</td>';
            $html .= '<td>'.number_format($item->debet, 0, ',', '.').'</td>';
            $html .= '<td>'.$item->blok.'</td>';
            $html .= '</tr>';
        }

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    public function processApproval(Request $request)
    {
        $jenisPencarian = $request->jenis_pencarian;
        $action = $request->action; // 'approve' atau 'reject'
        $ids = $request->ids; // Array ID dari checkbox yang dicentang

        if (!$ids || count($ids) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data yang dipilih!'
            ]);
        }

        $userId = Auth::id(); // Ambil ID user yang sedang login

        if ($jenisPencarian === 'deviasi_umur') {
            // Logika untuk Deviasi Umur Anggota
            if ($action === 'approve') {
                // Update status_usia menjadi 'DEVIASI' pada tabel pembiayaan
                Pembiayaan::whereIn('cif', $ids)->update([
                    'status_usia' => 'DEVIASI'
                ]);
            } else {
                // Jika Reject (Opsional: sesuaikan jika ada status lain, misal 'DITOLAK')
                Pembiayaan::whereIn('cif', $ids)->update([
                    'status_usia' => 'TOLAK'
                ]);
            }
        } else if ($jenisPencarian === 'penarikan_tabungan') {
            // Logika untuk Penarikan Tabungan
            if ($action === 'approve') {
                // Update field blok dengan id user login
                Simpanan::whereIn('reff', $ids)->update([
                    'blok' => $userId
                ]);
            } else {
                // Jika Reject (Opsional: kosongkan blok atau beri nilai lain)
                Simpanan::whereIn('reff', $ids)->update([
                    'blok' => null 
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diproses!'
        ]);
    }
}
