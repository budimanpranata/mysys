<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SetoranBedaHariController extends Controller
{
    public function index()
    {
        $title = 'Setoran Beda Hari';
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('admin.setoran_beda_hari.index', compact('title', 'menus'));
    }

    public function cari(Request $request)
    {
        $cari = $request->input('cari');

        $results = DB::table('kelompok')
            ->select('code_kel', 'nama_kel')
            ->where('code_unit', Auth::user()->unit)
            ->where('code_kel', 'like', '%'.$cari.'%')
            ->orWhere('nama_kel', 'like', '%'.$cari.'%')
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
        $get_anggota = DB::table('anggota')
            ->join('pembiayaan', 'anggota.no', '=', 'pembiayaan.no_anggota')
            ->where('pembiayaan.code_kel', $code_kel)
            ->where('pembiayaan.run_tenor', '<', DB::raw('pembiayaan.tenor')) // anggota yang masih memiliki angsuran
            ->select(
                'anggota.*',
                'pembiayaan.*',
            )
            ->get();

        return response()->json([
            'kelompok' => $get_kelompok,
            'anggota' => $get_anggota,
        ]);
    }

    public function proses($code_kel)
    {
        DB::beginTransaction();
        try {
            // Validasi kelompok
            $kelompok = DB::table('pembiayaan')
                ->where('code_kel', $code_kel)
                ->first();

            if (!$kelompok) {
                return response()->json(['message' => 'Kelompok tidak ditemukan'], 404);
            }

            // Ambil data anggota yang dipilih dari request
            $pilihAnggota = request()->input('pilih_anggota', []);
            $ambilNilaiNyataSetor = request()->input('input_nyata_setor', []);
            $inputDebet = request()->input('input_debet', []);

            // Jika tidak ada anggota yang dipilih
            if (empty($pilihAnggota)) {
                return response()->json(['message' => 'Tidak ada anggota yang dipilih'], 400);
            }

            // Ambil data setoran anggota yang dipilih
            $setoran = DB::table('pembiayaan')
                ->join('anggota', 'pembiayaan.no_anggota', '=', 'anggota.no')
                ->where('code_kel', $code_kel)
                ->whereIn('no_anggota', $pilihAnggota)
                ->select(
                    'pembiayaan.*',
                    'anggota.norek',
                )
                ->get();

            $anggotaDilewati = [];


            // Proses update untuk setiap anggota yang dipilih
            foreach ($setoran as $item) {
                $jumlahDebet = $inputDebet[$item->no_anggota] ?? 1;
                $nyataSetorPerDebet = $ambilNilaiNyataSetor[$item->no_anggota] ?? $item->bulat;
                $nyataSetor = $nyataSetorPerDebet * $jumlahDebet;

                $unit = $item->unit;
                $kodeTransaksi = 'BU/' . $unit . strtoupper(Str::random(8));
                $tgl_system = now()->format('Y-m-d');
                $user_id = auth()->user()->id;
                $ket = 'Setoran an ' . $item->nama;
                $timestamp = date('YmdHis');

                 // Cek tunggakan
                $tunggakan = DB::table('tunggakan')
                    ->where('cif', $item->cif)
                    ->first();

                if($tunggakan) {
                    // $jumlahTunggakan = $tunggakan->kredit;

                    if ($nyataSetor >= $tunggakan->kredit) {
                        // insert pelunasan ke tunggakan (dengan posisi debet)
                        DB::table('tunggakan')->insert([
                            'tgl_tunggak' => $tunggakan->tgl_tunggak,
                            'norek' => $item->norek,
                            'unit' => $unit,
                            'cif' => $item->cif,
                            'code_kel' => $item->code_kel,
                            'debet' => $tunggakan->kredit,
                            'type' => '04',
                            'kredit' => 0,
                            'userid' => $user_id,
                            'ket' => $ket,
                            'cao' => $item->cao,
                            'blok' => '2',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);

                        $sisa = $nyataSetor - $tunggakan->kredit;

                        // Jika ada sisa, masukkan ke simpanan
                        if ($sisa > 0) {
                            DB::table('simpanan')->insert([
                                'buss_date' => now(),
                                'norek' => $item->norek,
                                'unit' => $item->unit,
                                'cif' => $item->cif,
                                'code_kel' => $item->code_kel,
                                'debet' => 0,
                                'type' => '04',
                                'kredit' => $sisa,
                                'userid' => $user_id,
                                'ket' => $ket,
                                'reff' => $unit . $timestamp . strtoupper(\Str::random(2)),
                                'cao' => $item->cao,
                                'blok' => '2',
                                'kode_transaksi' => $kodeTransaksi,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                    } else {
                        continue; // skip proses pembiayaan
                    }
                }

                // Jika nyata setor lebih kecil dari angsuran
                if ($nyataSetorPerDebet < $item->bulat) {
                    DB::table('simpanan')->insert([
                    'buss_date' => now(),
                    'norek' => $item->norek,
                    'unit' => $unit,
                    'cif' => $item->cif,
                    'code_kel' => $item->code_kel,
                    'debet' => 0,
                    'type' => '04',
                    'kredit' => $nyataSetor,
                    'userid' => $user_id,
                    'ket' => $ket,
                    'reff' => $unit . $timestamp . strtoupper(\Str::random(2)),
                    'cao' => $item->cao,
                    'blok' => '2',
                    'kode_transaksi' => $kodeTransaksi,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                    continue; // Lewati proses pembiayaan & jurnal
                }

                // VALIDASI jika setoran melebihi OS
                if ($nyataSetor > $item->os) {
                    DB::table('tunggakan')->insert([
                        'tgl_tunggak' => now(),
                        'norek' => $item->norek,
                        'unit' => $unit,
                        'cif' => $item->cif,
                        'code_kel' => $item->code_kel,
                        'debet' => 0,
                        'type' => '04',
                        'kredit' => $nyataSetor,
                        'userid' => $user_id,
                        'ket' => $ket,
                        'cao' => $item->cao,
                        'blok' => '2',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    continue;
                }
                
                DB::table('pembiayaan')
                    ->where('no_anggota', $item->no_anggota)
                    ->update([
                        'run_tenor' => DB::raw("run_tenor + $jumlahDebet"),
                        'ke' => DB::raw("ke + $jumlahDebet"),
                        'last_payment' => now(),
                        'os' => DB::raw("os - $nyataSetor"),
                        'next_schedule' => now()->addDays(7),
                        // 'saldo_margin' => DB::raw("saldo_margin - (ijaroh * $jumlahDebet)")

                    ]);

                // Proses jurnal

                DB::table('tabel_transaksi')->insert([
                    [
                        'unit' => $unit,
                        'kode_transaksi' => $kodeTransaksi,
                        'kode_rekening' => '1413000',
                        'tanggal_transaksi' => $tgl_system,
                        'jenis_transaksi' => 'Bukti SYSTEM',
                        'keterangan_transaksi' => 'Setoran Beda Hari An ' . $item->nama,
                        'debet' => 0,
                        'kredit' => $nyataSetor,
                        'tanggal_posting' => $tgl_system,
                        'keterangan_posting' => '',
                        'id_admin' => $user_id
                    ],
                    [
                        'unit' => $unit,
                        'kode_transaksi' => $kodeTransaksi,
                        'kode_rekening' => '1423000',
                        'tanggal_transaksi' => $tgl_system,
                        'jenis_transaksi' => 'Bukti SYSTEM',
                        'keterangan_transaksi' => 'Setoran Beda Hari An ' . $item->nama,
                        'debet' => $item->ijaroh * $jumlahDebet,
                        'kredit' => 0,
                        'tanggal_posting' => $tgl_system,
                        'keterangan_posting' => '',
                        'id_admin' => $user_id
                    ],
                    [
                        'unit' => $unit,
                        'kode_transaksi' => $kodeTransaksi,
                        'kode_rekening' => '41002',
                        'tanggal_transaksi' => $tgl_system,
                        'jenis_transaksi' => 'Bukti SYSTEM',
                        'keterangan_transaksi' => 'Setoran Beda Hari An ' . $item->nama,
                        'debet' => 0,
                        'kredit' => $item->ijaroh * $jumlahDebet,
                        'tanggal_posting' => $tgl_system,
                        'keterangan_posting' => '',
                        'id_admin' => $user_id
                    ],
                    [
                        'unit' => $unit,
                        'kode_transaksi' => $kodeTransaksi,
                        'kode_rekening' => '2101000',
                        'tanggal_transaksi' => $tgl_system,
                        'jenis_transaksi' => 'Bukti SYSTEM',
                        'keterangan_transaksi' => 'Setoran Beda Hari An ' . $item->nama,
                        'debet' => $nyataSetor,
                        'kredit' => 0,
                        'tanggal_posting' => $tgl_system,
                        'keterangan_posting' => '',
                        'id_admin' => $user_id
                    ]
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Proses kelompok ' . $code_kel . ' berhasil',
                'total_diproses' => count($setoran) - count($anggotaDilewati),
                'anggota_dilewati' => $anggotaDilewati
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal memproses: ' . $e->getMessage()
            ], 500);
        }
    }
}
