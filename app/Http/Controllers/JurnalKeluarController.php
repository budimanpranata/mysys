<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JurnalKeluarController extends Controller
{
    public function index()
    {
        $title = 'Jurnal Keluar';
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();

        $kodeUnit = Auth::user()->unit;
        $random = strtoupper(Str::random(7));
        $kodeTransaksi = 'KK/' . $kodeUnit . $random;

        $kodeGL = DB::table('branch')
            ->where('kode_branch', $kodeUnit)
            ->value('GL');

        return view('admin.jurnal_keluar.index', compact('menus', 'title', 'kodeTransaksi', 'kodeGL'));
    }

    public function store(Request $request)
    {
        $data = $request->input('transaksi');

        DB::beginTransaction();
        try {
            foreach ($data as $item) {
                DB::table('tabel_transaksi')->insert([
                    'unit' => Auth::user()->unit,
                    'kode_transaksi' => $item['kode_transaksi'],
                    'kode_rekening' => $item['kode_rekening'],
                    'tanggal_transaksi' => $item['tanggal_transaksi'],
                    'jenis_transaksi' => 'Jurnal UMUM',
                    'keterangan_transaksi' => $item['keterangan_transaksi'],
                    'debet' => $item['jenis'] === 'debet' ? $item['jumlah'] : 0,
                    'kredit' => $item['jenis'] === 'kredit' ? $item['jumlah'] : 0,
                    'tanggal_posting' => $item['tanggal_transaksi'],
                    'keterangan_posting' => 'Post',
                    'id_admin' => Auth::user()->id,
                    'arus_kas' => Auth::user()->unit,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                if ($item['jenis'] === 'debet' && $item['jenis_transaksi'] === 'lainnya') {
                    DB::table('tabel_transaksi')->insert([
                        'unit' => Auth::user()->unit,
                        'kode_transaksi' => $item['kode_transaksi'],
                        'kode_rekening' => '9999999', // <- REKENING LAWAN DEFAULT, ganti sesuai kebutuhan
                        'tanggal_transaksi' => $item['tanggal_transaksi'],
                        'jenis_transaksi' => 'Jurnal UMUM',
                        'keterangan_transaksi' => '[Auto Kredit] ' . $item['keterangan_transaksi'],
                        'debet' => 0,
                        'kredit' => $item['jumlah'],
                        'tanggal_posting' => $item['tanggal_transaksi'],
                        'keterangan_posting' => 'Post Auto',
                        'id_admin' => Auth::user()->id,
                        'arus_kas' => Auth::user()->unit,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Data berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
