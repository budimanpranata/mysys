<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class RestrukturisasiJatuhTempoController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $title = 'Restrukturisasi Jatuh Tempo';

        return view("admin.restrukturisasi_jatuh_tempo.index", compact("menus", "title"));
    }

    public function searchKelompok(Request $request)
    {
        $request->validate([
            'kode_kelompok' => 'required',
            'unit' => 'required',
        ]);

        $results = DB::table('pembiayaan')
            ->where('code_kel', $request->kode_kelompok)
            ->where('unit', $request->unit)
            ->get();

        return response()->json(['results' => $results]);
    }

    public function restrukturisasi(Request $request)
    {
        $request->validate([
            'cifs' => 'required|array',
            'unit' => 'required',
            'kode_kelompok' => 'required',
        ]);

        $tanggalLibur = DB::table('param_tgl')->pluck('param_tgl')->toArray();

        foreach ($request->cifs as $cif) {
            $akad = DB::table('pembiayaan')->where('cif', $cif)->first();
            if (!$akad)
                continue;
            $pembayaranKe = (int) $akad->ke;
            $tenor = (int) $akad->tenor;
            $newTenor = $pembayaranKe + $tenor;

            // totalkan sisa jumlah bayar sebelum di spread
            $deletedRows = DB::table('pembiayaan_detail')
                ->where('cif', $cif)
                ->whereRaw('CAST(cicilan AS UNSIGNED) > ?', [$pembayaranKe]) // jaddiin unsigned int biar bisa dibandingin secara numerik bukan lexic
                ->get();
            $sisaPembayaran = $deletedRows->sum('jumlah_bayar');

            // delete semua cicilan yang lebih besar dari apa yang sudah di bayarkan
            DB::table('pembiayaan_detail')
                ->where('cif', $cif)
                ->whereRaw('CAST(cicilan AS UNSIGNED) > ?', [$pembayaranKe]) // jaddiin unsigned int biar bisa dibandingin secara numerik bukan lexic
                ->delete();

            // sperad total pembiayaan berdasarkan jumlah tenor
            $angsuranBaru = $newTenor > 0 ? floor($sisaPembayaran / $tenor) : 0;
            $sisaBagi = $newTenor > 0 ? $sisaPembayaran - ($angsuranBaru * $tenor) : 0;

            // ambil tanggal jatuh tempo yang trakhir
            $lastDetail = DB::table('pembiayaan_detail')
                ->where('cif', $cif)
                ->whereRaw('CAST(cicilan AS UNSIGNED) = ?', [$pembayaranKe]) // jaddiin unsigned int biar bisa dibandingin secara numerik bukan lexic
                ->orderByDesc('tgl_jatuh_tempo')
                ->first();
            $startDate = $lastDetail ? \Carbon\Carbon::parse($lastDetail->tgl_jatuh_tempo)->addDays(7) : \Carbon\Carbon::now()->addDays(7);

            // logic yang sama di realisasi murabahah dalam proses jurnalnya
            $adjustedTglJatuhTempo = [];
            for ($i = 0; $i < $tenor; $i++) {
                $date = $startDate->copy()->addDays($i * 7);
                $formattedDate = $date->format('Y-m-d');
                // Adjust for holidays
                while (in_array($formattedDate, $tanggalLibur)) {
                    $date->addDays(7);
                    $formattedDate = $date->format('Y-m-d');
                }
                $adjustedTglJatuhTempo[] = $date->format('Y-m-d H:i:s');
            }

            // insert cicilan yang udah di spread
            for ($i = 0; $i < $tenor; $i++) {
                $cicilan = $pembayaranKe + 1 + $i;
                $jumlah_bayar = $angsuranBaru + ($i == 0 ? $sisaBagi : 0);
                DB::table('pembiayaan_detail')->insert([
                    'id' => null,
                    'id_pinjam' => $akad->no_anggota,
                    'cicilan' => $cicilan,
                    'angsuran_pokok' => $akad->pokok,
                    'margin' => $akad->ijaroh,
                    'tgl_jatuh_tempo' => $adjustedTglJatuhTempo[$i],
                    'tgl_bayar' => null,
                    'jumlah_bayar' => $jumlah_bayar,
                    'keterangan' => 'Restrukturisasi jatuh tempo',
                    'cif' => $akad->cif,
                    'unit' => $request->unit,
                    'ao' => $akad->cao,
                    'code_kel' => $akad->code_kel,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        return response()->json(['message' => 'Restrukturisasi berhasil dilakukan.']);
    }
}
