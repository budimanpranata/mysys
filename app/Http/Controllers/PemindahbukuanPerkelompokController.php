<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\Menu;
use App\Models\simpanan;
use App\Models\simpanan_pokok;
use App\Models\simpanan_wajib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PemindahbukuanPerkelompokController extends Controller
{
    public function index ()
    {
        $title = 'PB Perkelompok';
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('admin.pemindahbukuan_perkelompok.index', compact('title', 'menus'));
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
        $get_anggota = DB::table('anggota')
            ->join('pembiayaan', 'anggota.no', '=', 'pembiayaan.no_anggota')
            ->leftJoin(DB::raw("(SELECT norek, SUM(kredit) - SUM(debet) AS saldo_pokok FROM simpanan_pokok GROUP BY norek) as spokok"), 'anggota.norek', '=', 'spokok.norek')
            ->leftJoin(DB::raw("(SELECT norek, SUM(kredit) - SUM(debet) AS saldo_wajib FROM simpanan_wajib GROUP BY norek) as swajib"), 'anggota.norek', '=', 'swajib.norek')
            ->where('pembiayaan.code_kel', $code_kel)
            // ->where('pembiayaan.run_tenor', '<', DB::raw('pembiayaan.tenor')) // anggota yang masih memiliki angsuran
            ->select(
                'anggota.*',
                'pembiayaan.*',
                DB::raw('COALESCE(spokok.saldo_pokok, 0) as pokok'),
                DB::raw('COALESCE(swajib.saldo_wajib, 0) as wajib')
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
            $nominalPB = request()->input('input_nyata_setor', []);
            $jenisPemindahan = request()->input('jenis_pemindahan');
            $jenisSimpanan = request()->input('jenis_simpanan');

            // Jika tidak ada anggota yang dipilih
            if (empty($pilihAnggota)) {
                return response()->json(['message' => 'Tidak ada anggota yang dipilih'], 400);
            }

            // Ambil data PB anggota yang dipilih
            $pb = DB::table('pembiayaan')
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
            foreach ($pb as $item) {
                $nominalPB[$item->no_anggota] ?? $item->bulat;
                $jenisPemindahan = request()->input('jenis_pemindahan');
                $jenisSimpanan = request()->input('jenis_simpanan');


                $unit = $item->unit;
                $kodeTransaksi = 'BU/' . $unit . strtoupper(Str::random(8));
                $tgl_system = now()->format('Y-m-d H:i:s');
                $user_id = Auth::user()->id;
                $ket = 'Setoran PB an ' . $item->nama;
                $timestamp = date('YmdHis');
                $reff = $unit . $timestamp . strtoupper(Str::random(2));

                $nominal = isset($nominalPB[$item->no_anggota]) ? floatval($nominalPB[$item->no_anggota]) : 0;

                // Cek kondisi untuk debet pokok
                if ($jenisPemindahan === 'debet' && $jenisSimpanan === 'pokok') {
                    simpanan::create([
                        'reff' => $reff,
                        'buss_date' => now(),
                        'norek' => $item->norek,
                        'unit' => $unit,
                        'cif' => $item->cif,
                        'code_kel' => $item->code_kel,
                        'debet' => 0,
                        'type' => '01',
                        'kredit' => $nominal,
                        'userid' => $user_id,
                        'ket' => $ket,
                        'cao' => $item->cao,
                        'blok' => '1',
                        'tgl_input' => now(),
                        'kode_transaksi' => $kodeTransaksi,
                    ]);

                    simpanan_pokok::create([
                        'reff' => $reff,
                        'buss_date' => now(),
                        'norek' => $item->norek,
                        'unit' => $unit,
                        'cif' => $item->cif,
                        'code_kel' => $item->code_kel,
                        'debet' => $nominal,
                        'type' => '01',
                        'kredit' => 0,
                        'userid' => $user_id,
                        'ket' => $ket,
                        'cao' => $item->cao,
                        'blok' => '1',
                        'tgl_input' => now(),
                        'kode_transaksi' => $kodeTransaksi,
                    ]);

                    $transaksi_pemindahbukuan = [
                        [
                            'unit' => $unit,
                            'kode_transaksi' => $kodeTransaksi,
                            'kode_rekening' => '3102000', // SP-Anggota
                            'tanggal_transaksi' => $tgl_system,
                            'jenis_transaksi' => 'Bukti SYSTEM',
                            'keterangan_transaksi' => $ket,
                            'debet' => $nominal,
                            'kredit' => '0',
                            'tanggal_posting' => $tgl_system,
                            'keterangan_posting' => 'Post',
                            'id_admin' => $user_id
                        ],
                        [
                            'unit' => $unit,
                            'kode_transaksi' => $kodeTransaksi,
                            'kode_rekening' => '2101000', // Simpanan Wadiah Kelompok
                            'tanggal_transaksi' => $tgl_system,
                            'jenis_transaksi' => 'Bukti SYSTEM',
                            'keterangan_transaksi' => $ket,
                            'debet' => 0,
                            'kredit' => $nominal,
                            'tanggal_posting' => $tgl_system,
                            'keterangan_posting' => 'Post',
                            'id_admin' => $user_id
                            ]
                        ];

                        DB::table('tabel_transaksi')->insert($transaksi_pemindahbukuan);

                } elseif ($jenisPemindahan === 'debet' && $jenisSimpanan === 'wajib') {
                    simpanan::create([
                        'reff' => $reff,
                        'buss_date' => now(),
                        'norek' => $item->norek,
                        'unit' => $unit,
                        'cif' => $item->cif,
                        'code_kel' => $item->code_kel,
                        'debet' => 0,
                        'type' => '01',
                        'kredit' => $nominal,
                        'userid' => $user_id,
                        'ket' => $ket,
                        'cao' => $item->cao,
                        'blok' => '1',
                        'tgl_input' => now(),
                        'kode_transaksi' => $kodeTransaksi,
                    ]);

                    simpanan_wajib::create([
                        'reff' => $reff,
                        'buss_date' => now(),
                        'norek' => $item->norek,
                        'unit' => $unit,
                        'cif' => $item->cif,
                        'code_kel' => $item->code_kel,
                        'debet' => $nominal,
                        'type' => '01',
                        'kredit' => 0,
                        'userid' => $user_id,
                        'ket' => $ket,
                        'cao' => $item->cao,
                        'blok' => '1',
                        'tgl_input' => now(),
                        'kode_transaksi' => $kodeTransaksi,
                    ]);

                    $transaksi_pemindahbukuan = [
                        [
                            'unit' => $unit,
                            'kode_transaksi' => $kodeTransaksi,
                            'kode_rekening' => '3202000', // SW-Anggota
                            'tanggal_transaksi' => $tgl_system,
                            'jenis_transaksi' => 'Bukti SYSTEM',
                            'keterangan_transaksi' => $ket,
                            'debet' => $nominal,
                            'kredit' => '0',
                            'tanggal_posting' => $tgl_system,
                            'keterangan_posting' => '',
                            'id_admin' => $user_id
                        ],
                        [
                            'unit' => $unit,
                            'kode_transaksi' => $kodeTransaksi,
                            'kode_rekening' => '2101000', // Simpanan Wadiah Kelompok
                            'tanggal_transaksi' => $tgl_system,
                            'jenis_transaksi' => 'Bukti SYSTEM',
                            'keterangan_transaksi' => $ket,
                            'debet' => 0,
                            'kredit' => $nominal,
                            'tanggal_posting' => $tgl_system,
                            'keterangan_posting' => '',
                            'id_admin' => $user_id
                        ]
                    ];

                    DB::table('tabel_transaksi')->insert($transaksi_pemindahbukuan);


                } elseif ($jenisPemindahan === 'kredit' && $jenisSimpanan === 'pokok') {
                    simpanan::create([
                        'reff' => $reff,
                        'buss_date' => now(),
                        'norek' => $item->norek,
                        'unit' => $unit,
                        'cif' => $item->cif,
                        'code_kel' => $item->code_kel,
                        'debet' => $nominal,
                        'type' => '01',
                        'kredit' => 0,
                        'userid' => $user_id,
                        'ket' => $ket,
                        'cao' => $item->cao,
                        'blok' => '1',
                        'tgl_input' => now(),
                        'kode_transaksi' => $kodeTransaksi,
                    ]);

                    simpanan_pokok::create([
                        'reff' => $reff,
                        'buss_date' => now(),
                        'norek' => $item->norek,
                        'unit' => $unit,
                        'cif' => $item->cif,
                        'code_kel' => $item->code_kel,
                        'debet' => 0,
                        'type' => '01',
                        'kredit' => $nominal,
                        'userid' => $user_id,
                        'ket' => $ket,
                        'cao' => $item->cao,
                        'blok' => '1',
                        'tgl_input' => now(),
                        'kode_transaksi' => $kodeTransaksi,
                    ]);

                    $transaksi_pemindahbukuan = [
                        [
                            'unit' => $unit,
                            'kode_transaksi' => $kodeTransaksi,
                            'kode_rekening' => '3102000', // SP-Anggota
                            'tanggal_transaksi' => $tgl_system,
                            'jenis_transaksi' => 'Bukti SYSTEM',
                            'keterangan_transaksi' => $ket,
                            'debet' => $nominal,
                            'kredit' => '0',
                            'tanggal_posting' => $tgl_system,
                            'keterangan_posting' => '',
                            'id_admin' => $user_id
                        ],
                        [
                            'unit' => $unit,
                            'kode_transaksi' => $kodeTransaksi,
                            'kode_rekening' => '2101000', // Simpanan Wadiah Kelompok
                            'tanggal_transaksi' => $tgl_system,
                            'jenis_transaksi' => 'Bukti SYSTEM',
                            'keterangan_transaksi' => $ket,
                            'debet' => 0,
                            'kredit' => $nominal,
                            'tanggal_posting' => $tgl_system,
                            'keterangan_posting' => '',
                            'id_admin' => $user_id
                        ]
                    ];

                    DB::table('tabel_transaksi')->insert($transaksi_pemindahbukuan);

                } elseif ($jenisPemindahan === 'kredit' && $jenisSimpanan === 'wajib') {
                    simpanan::create([
                        'reff' => $reff,
                        'buss_date' => now(),
                        'norek' => $item->norek,
                        'unit' => $unit,
                        'cif' => $item->cif,
                        'code_kel' => $item->code_kel,
                        'debet' => $nominal,
                        'type' => '01',
                        'kredit' => 0,
                        'userid' => $user_id,
                        'ket' => $ket,
                        'cao' => $item->cao,
                        'blok' => '1',
                        'tgl_input' => now(),
                        'kode_transaksi' => $kodeTransaksi,
                    ]);

                    simpanan_wajib::create([
                        'reff' => $reff,
                        'buss_date' => now(),
                        'norek' => $item->norek,
                        'unit' => $unit,
                        'cif' => $item->cif,
                        'code_kel' => $item->code_kel,
                        'debet' => 0,
                        'type' => '01',
                        'kredit' => $nominal,
                        'userid' => $user_id,
                        'ket' => $ket,
                        'cao' => $item->cao,
                        'blok' => '1',
                        'tgl_input' => now(),
                        'kode_transaksi' => $kodeTransaksi,
                    ]);

                    $transaksi_pemindahbukuan = [
                        [
                            'unit' => $unit,
                            'kode_transaksi' => $kodeTransaksi,
                            'kode_rekening' => '3102000', // SP-Anggota
                            'tanggal_transaksi' => $tgl_system,
                            'jenis_transaksi' => 'Bukti SYSTEM',
                            'keterangan_transaksi' => $ket,
                            'debet' => $nominal,
                            'kredit' => '0',
                            'tanggal_posting' => $tgl_system,
                            'keterangan_posting' => '',
                            'id_admin' => $user_id
                        ],
                        [
                            'unit' => $unit,
                            'kode_transaksi' => $kodeTransaksi,
                            'kode_rekening' => '2101000', // Simpanan Wadiah Kelompok
                            'tanggal_transaksi' => $tgl_system,
                            'jenis_transaksi' => 'Bukti SYSTEM',
                            'keterangan_transaksi' => $ket,
                            'debet' => 0,
                            'kredit' => $nominal,
                            'tanggal_posting' => $tgl_system,
                            'keterangan_posting' => '',
                            'id_admin' => $user_id
                        ]
                    ];

                    DB::table('tabel_transaksi')->insert($transaksi_pemindahbukuan);
                }

            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Proses PB ' . $code_kel . ' berhasil',
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
