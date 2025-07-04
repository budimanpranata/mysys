<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PelunasanKelompokController extends Controller
{
    public function index()
    {
        $title = 'Pelunasan Kelompok';
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('admin.pelunasan_kelompok.index', compact('title', 'menus'));
    }

    public function cari(Request $request)
    {
        $cari = $request->input('cari');

        $results = DB::table('kelompok')
            ->select('code_kel', 'nama_kel')
            ->where('code_unit', Auth::user()->unit)
            ->where(function ($query) use ($cari) {
                $query->where('code_kel', 'like', '%' . $cari . '%')
                    ->orWhere('nama_kel', 'like', '%' . $cari . '%');
            })
            ->limit(10)
            ->get();

        return response()->json($results);
    }

    public function filter(Request $request)
    {
        $code_kel = $request->input('code_kel');

        // Data kelompok
        $get_kelompok = DB::table('kelompok')
            ->where('code_kel', $code_kel)
            ->first();

        if (!$get_kelompok) {
            return response()->json(['message' => 'Kamu belum pilih kelompok'], 404);
        }

        // Data anggota
        $anggota = DB::table('anggota')
            ->join('pembiayaan', 'anggota.no', '=', 'pembiayaan.no_anggota')
            ->where('pembiayaan.code_kel', $code_kel)
            ->select(
                'anggota.*',
                'pembiayaan.plafond',
                'pembiayaan.angsuran',
                'pembiayaan.maturity_date as tgl_jatuh_tempo',
                'pembiayaan.os',
                DB::raw('(SELECT SUM(kredit - debet) FROM simpanan WHERE norek = anggota.norek) as saldo_rek')
            )
            ->get();
        // return response()->json($anggota);

        return response()->json([
            'kelompok' => $get_kelompok,
            'anggota' => $anggota,
        ]);
    }

    public function proses(Request $request, $code_kel)
    {
        DB::beginTransaction();

        try {
            $pilihAnggota = $request->input('pilih_anggota');

            if (!is_array($pilihAnggota) || empty($pilihAnggota)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada anggota yang dipilih'
                ], 400);
            }

            foreach ($pilihAnggota as $norek) {
                // Ambil saldo simpanan
                $saldo_rek = DB::table('simpanan')
                    ->where('norek', $norek)
                    ->selectRaw('COALESCE(SUM(kredit), 0) - COALESCE(SUM(debet), 0) as saldo')
                    ->value('saldo');

                $anggota = DB::table('anggota')
                    ->where('norek', $norek)
                    ->select('no', 'unit', 'nama', 'norek', 'cif', 'kode_kel', 'cao')
                    ->first();

                if (!$anggota) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data anggota tidak ditemukan'
                    ], 404);
                }

                $pembiayaan = DB::table('pembiayaan')
                    ->where('no_anggota', $anggota->no)
                    ->first();

                if (!$pembiayaan) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data pembiayaan tidak ditemukan'
                    ], 404);
                }

                // validasi saldo simpanan dan os
                if ($saldo_rek < $pembiayaan->os) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Saldo simpanan tidak mencukupi untuk pelunasan'
                    ], 400);
                }

                $jumlah_pelunasan = $pembiayaan->os;
                // $sisa_simpanan = $saldo_rek - $jumlah_pelunasan;

                // Data dasar transaksi
                $unit = $anggota->unit;
                $kodeTransaksi = 'BU/' . $unit . strtoupper(Str::random(8));
                $tgl_system = now()->format('Y-m-d H:i:s');
                $user_id = Auth::user()->id;
                $ket = 'Pelunasan an ' . $anggota->nama;
                $timestamp = date('YmdHis');
                $reff = $unit . $timestamp . strtoupper(Str::random(2));

                // Transaksi akuntansi
                $transaksi = [
                    [
                        'unit' => $unit,
                        'kode_transaksi' => $kodeTransaksi,
                        'kode_rekening' => '2101000',
                        'tanggal_transaksi' => $tgl_system,
                        'jenis_transaksi' => 'Bukti SYSTEM',
                        'keterangan_transaksi' => $ket,
                        'debet' => $jumlah_pelunasan,
                        'kredit' => 0,
                        'tanggal_posting' => $tgl_system,
                        'keterangan_posting' => 'Post',
                        'id_admin' => $user_id
                    ],
                    [
                        'unit' => $unit,
                        'kode_transaksi' => $kodeTransaksi,
                        'kode_rekening' => '1413000',
                        'tanggal_transaksi' => $tgl_system,
                        'jenis_transaksi' => 'Bukti SYSTEM',
                        'keterangan_transaksi' => $ket,
                        'debet' => 0,
                        'kredit' => $pembiayaan->os + $pembiayaan->bagi_hasil,
                        'tanggal_posting' => $tgl_system,
                        'keterangan_posting' => 'Post',
                        'id_admin' => $user_id
                    ],
                    [
                        'unit' => $unit,
                        'kode_transaksi' => $kodeTransaksi,
                        'kode_rekening' => '1423000',
                        'tanggal_transaksi' => $tgl_system,
                        'jenis_transaksi' => 'Bukti SYSTEM',
                        'keterangan_transaksi' => $ket,
                        'debet' => $pembiayaan->bagi_hasil,
                        'kredit' => 0,
                        'tanggal_posting' => $tgl_system,
                        'keterangan_posting' => 'Post',
                        'id_admin' => $user_id
                    ],
                    [
                        'unit' => $unit,
                        'kode_transaksi' => $kodeTransaksi,
                        'kode_rekening' => '41002',
                        'tanggal_transaksi' => $tgl_system,
                        'jenis_transaksi' => 'Bukti SYSTEM',
                        'keterangan_transaksi' => $ket,
                        'debet' => 0,
                        'kredit' => $pembiayaan->bagi_hasil,
                        'tanggal_posting' => $tgl_system,
                        'keterangan_posting' => 'Post',
                        'id_admin' => $user_id
                    ]
                ];

                DB::table('tabel_transaksi')->insert($transaksi);

                // Insert ke simpanan
                DB::table('simpanan')->insert([
                    'buss_date' => now(),
                    'norek' => $anggota->norek,
                    'unit' => $anggota->unit,
                    'cif' => $anggota->cif,
                    'code_kel' => $anggota->kode_kel,
                    'debet' => $jumlah_pelunasan,
                    'type' => '04',
                    'kredit' => 0,
                    'userid' => $user_id,
                    'ket' => $ket,
                    'reff' => $reff,
                    'cao' => $anggota->cao,
                    'blok' => '2',
                    'kode_transaksi' => $kodeTransaksi,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Update pembiayaan
                DB::table('pembiayaan')
                    ->where('no_anggota', $anggota->no)
                    ->update([
                        'os' => 0,
                        'saldo_margin' => 0,
                        'run_tenor' => 0,
                        'ke' => 0,
                        'last_payment' => now()
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pelunasan berhasil diproses'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
