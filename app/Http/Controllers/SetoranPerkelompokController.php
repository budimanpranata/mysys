<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SetoranPerkelompokController extends Controller
{
        public function index()
    {
        $title = 'Setoran Perkelompok';
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('admin.setoran_perkelompok.index', compact('title', 'menus'));
    }

    public function cari(Request $request)
    {
        $cari = $request->input('cari');
        
        $results = DB::table('kelompok')
            ->select('code_kel', 'nama_kel')
            ->where('code_kel', 'like', '%'.$cari.'%')
            ->orWhere('nama_kel', 'like', '%'.$cari.'%')
            ->limit(10)
            ->get();

        return response()->json($results);
    }

    public function filter(Request $request)
    {
        $code_kel = $request->input('code_kel');
        
        $data = DB::table('kelompok')
        ->where('code_kel', $code_kel)
        ->first();
        
        $jumlah_anggota = DB::table('anggota')
            ->where('kode_kel', $code_kel)
            ->count();

        $setoran = DB::table('pembiayaan')
            ->where('code_kel', $code_kel)
            ->sum('angsuran');

        return response()->json([
            'data' => $data,
            'jumlah_anggota' => $jumlah_anggota,
            'setoran' => $setoran,
            'tgl' => now()->format('d-m-Y'),
        ]);
    }

public function proses($code_kel)
{
    DB::beginTransaction();
    try {
        // validasi kelompok
        $kelompok = DB::table('pembiayaan')
            ->where('code_kel', $code_kel)
            ->first();
        
        if (!$kelompok) {
            return response()->json(['message' => 'Kelompok tidak ditemukan'], 404);
        }

        // ambil data setoran anggota kelompok
        $setoran = DB::table('pembiayaan')
            ->where('pembiayaan.code_kel', $code_kel)
            ->select('pembiayaan.*')
            ->get();

        DB::table('pembiayaan')
            ->where('code_kel', $code_kel)
            ->update([
                'run_tenor' => DB::raw('run_tenor + 1'),
                'ke' => DB::raw('ke + 1'),
                'last_payment' => now(),
                'os' => DB::raw('os - angsuran'),
                'saldo_margin' => DB::raw('saldo_margin - ijaroh'),
            ]);

        // proses jurnal
        foreach ($setoran as $item) {
            $unit = $item->unit;
            $kodeTransaksi = 'BU/' . $unit . strtoupper(Str::random(8));
            $tgl_system = now()->format('Y-m-d');
            $user_id = auth()->user()->id;

            DB::table('tabel_transaksi')->insert(
                [
                    'unit' => $unit,
                    'kode_transaksi' => $kodeTransaksi,
                    'kode_rekening' => '1413000', // Piutang Murabahah Mingguan
                    'tanggal_transaksi' => $tgl_system,
                    'jenis_transaksi' => 'Bukti SYSTEM',
                    'keterangan_transaksi' => 'Setoran Kelompok An ' . $item->nama,
                    'debet' => '0',
                    'kredit' => $item->angsuran,
                    'tanggal_posting' => $tgl_system,
                    'keterangan_posting' => '',
                    'id_admin' => $user_id
                    
                ],
            );

            DB::table('tabel_transaksi')->insert(
                [
                    'unit' => $unit,
                    'kode_transaksi' => $kodeTransaksi,
                    'kode_rekening' => '1423000', // PMYD-PYD Murabahah Mingguan -/-
                    'tanggal_transaksi' => $tgl_system,
                    'jenis_transaksi' => 'Bukti SYSTEM',
                    'keterangan_transaksi' => 'Setoran Kelompok An ' . $item->nama,
                    'debet' => $item->ijaroh,
                    'kredit' => '0',
                    'tanggal_posting' => $tgl_system,
                    'keterangan_posting' => '',
                    'id_admin' => $user_id
                    
                ],
            );

            DB::table('tabel_transaksi')->insert(
                [
                    'unit' => $unit,
                    'kode_transaksi' => $kodeTransaksi,
                    'kode_rekening' => '41002', // PM-Murabahah-Kelompok Mingguan
                    'tanggal_transaksi' => $tgl_system,
                    'jenis_transaksi' => 'Bukti SYSTEM',
                    'keterangan_transaksi' => 'Setoran Kelompok An ' . $item->nama,
                    'kredit' => $item->ijaroh,
                    'debet' => '0',
                    'tanggal_posting' => $tgl_system,
                    'keterangan_posting' => '',
                    'id_admin' => $user_id
                    
                ],
            );

            DB::table('tabel_transaksi')->insert(
                [
                    'unit' => $unit,
                    'kode_transaksi' => $kodeTransaksi,
                    'kode_rekening' => '2101000', // Simpanan Wadiah Kelompok
                    'tanggal_transaksi' => $tgl_system,
                    'jenis_transaksi' => 'Bukti SYSTEM',
                    'keterangan_transaksi' => 'Setoran Kelompok An ' . $item->nama,
                    'debet' => $item->angsuran,
                    'kredit' => '0',
                    'tanggal_posting' => $tgl_system,
                    'keterangan_posting' => '',
                    'id_admin' => $user_id
                ]
            );
        }

        DB::commit();

        return response()->json([
            'message' => 'Proses kelompok ' . $code_kel . ' berhasil',
            'total_diproses' => count($setoran)
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Gagal memproses: ' . $e->getMessage()
        ], 500);
    }
}


}
