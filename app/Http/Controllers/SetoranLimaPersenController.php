<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use App\Models\temp_akad_mus;

class SetoranLimaPersenController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $pembiayaan = DB::table('pembiayaan')
        ->selectRaw('SUM(os - saldo_margin) as os, COUNT(cif) as noa')
        ->first();
        //dd($pembiayaan);
        $title = 'Setoran Lima Persen';

        return view('admin.lima_persen.index',compact('menus','pembiayaan','title'));

    }

    public function getData(Request $request)
    {
        $query = temp_akad_mus::query()
        ->join('kelompok', 'temp_akad_mus.code_kel', '=', 'kelompok.code_kel')
        ->where('status_app', 'APPROVE')
        ->select(
            'temp_akad_mus.*',
            'kelompok.nama_kel',
        );



    if ($request->kode_kelompok) {
        $query->where('kelompok.code_kel', 'LIKE', '%' . $request->kode_kelompok . '%');
    }

    if ($request->tanggal_realisasi) {
        $query->where('tgl_wakalah', $request->tanggal_realisasi);
    }


    $data = $query->get();

    return response()->json($data);

    }
    public function realisasiLimaPersen(Request $request)
    {


            $cekbox =$request->ids;


        if (empty($cekbox) || !is_array($cekbox)) {
            return response()->json(['message' => 'Tidak ada data yang dipilih.'], 400);
        }

        $userid = auth()->user()->id;
        $tgl_system = date('Y-m-d H:i:s');
        $unit = auth()->user()->unit;



        if (!$cekbox) {
            return response()->json([
                'success' => true,
                'message' => 'Anda belum memilih data'
            ])->setStatusCode(400);

        }

        DB::beginTransaction();
        try {
            foreach ($cekbox as $value) {
                // Ambil data loan
                $loan = DB::table('temp_akad_mus')
                    ->leftJoin('anggota', 'anggota.cif', '=', 'temp_akad_mus.cif')
                    ->where('temp_akad_mus.cif', $value)
                    ->select([
                        DB::raw('DATE_ADD(tgl_wakalah, INTERVAL 7 DAY) as tgl_murab'),
                        'temp_akad_mus.*', 'anggota.nama'
                    ])
                    ->first();


                if (!$loan) continue;

                $kode_trans = 'BU/' . $loan->unit . strtoupper(\Str::random(8));
                $nama = $loan->nama;
                $unit = $loan->unit;
                $code_kel = $loan->code_kel;
                $no_anggota = $loan->no_anggota;
                $norek = $no_anggota;
                $cif = $loan->cif;
                $cao = $loan->cao;
                $plafond = $loan->plafond;
                $nominal = $plafond * 5 / 100;

                // Hitung simpanan pokok & wajib
                $pokok = DB::table('simpanan_pokok')->where('cif', $value)->sum(DB::raw('kredit - debet'));
                $wajib = DB::table('simpanan_wajib')->where('cif', $value)->sum(DB::raw('kredit - debet'));

                $timestamp = date('YmdHis');
                $urutPokok = DB::table('simpanan_pokok')->count() + 1;
                $urutWajib = DB::table('simpanan_wajib')->count() + 1;


                $urut_pokok = $unit . $timestamp . $urutPokok;
                $urut_wajib = $unit . $timestamp . $urutWajib;


                if ($pokok == 0 && $wajib >= 0) {
                    $in_pokok = 50000;
                    $ket = "Setoran simpanan Pokok an $nama";

                    $simpanPokok=[
                        'buss_date' => $tgl_system,
                        'norek' => $norek,
                        'unit' => $unit,
                        'cif' => $value,
                        'code_kel' => $code_kel,
                        'debet' => 0,
                        'type' => '04',
                        'kredit' => $in_pokok,
                        'userid' => $userid,
                        'ket' => 'Simpanan Pokok',
                        'reff' => $urut_pokok,
                        'cao' => $cao,
                        'blok' => '4',
                        'kode_transaksi' => $kode_trans
                    ];

                    DB::table('simpanan_pokok')->insert($simpanPokok);

                    $simpanTransaksi=[
                        [
                            'unit' => $unit,
                            'kode_transaksi' => $kode_trans,
                            'kode_rekening' => '1120000',
                            'tanggal_transaksi' => $tgl_system,
                            'jenis_transaksi' => 'Bukti SYSTEM',
                            'keterangan_transaksi' => $ket,
                            'debet' => $in_pokok,
                            'kredit' => '0',
                            'tanggal_posting' => $tgl_system,
                            'keterangan_posting' => '',
                            'id_admin' => $userid
                        ],
                        [
                            'unit' => $unit,
                            'kode_transaksi' => $kode_trans,
                            'kode_rekening' => '3102000',
                            'tanggal_transaksi' => $tgl_system,
                            'jenis_transaksi' => 'Bukti SYSTEM',
                            'keterangan_transaksi' => $ket,
                            'kredit' => $in_pokok,
                            'debet' => '0',
                            'tanggal_posting' => $tgl_system,
                            'keterangan_posting' => '',
                            'id_admin' => $userid
                        ]
                    ];
                    DB::table('tabel_transaksi')->insert($simpanTransaksi);

                    $sisa = $nominal - $in_pokok;
                    if ($sisa > 0) {
                        $ket = "Setoran simpanan 5% an $nama";

                        $simpanWajib=[
                            'buss_date' => $tgl_system,
                            'norek' => $norek,
                            'unit' => $unit,
                            'cif' => $value,
                            'code_kel' => $code_kel,
                            'debet' => 0,
                            'type' => '05',
                            'kredit' => $sisa,
                            'userid' => $userid,
                            'ket' => 'PB Simpanan Wajib',
                            'reff' => $urut_wajib,
                            'cao' => $cao,
                            'blok' => '1',
                            'kode_transaksi' => $kode_trans
                        ];
                        DB::table('simpanan_wajib')->insert($simpanWajib);

                        $simpanTransaksi=[
                            [
                                'unit' => $unit,
                                'kode_transaksi' => $kode_trans,
                                'kode_rekening' => '1120000',
                                'tanggal_transaksi' => $tgl_system,
                                'jenis_transaksi' => 'Bukti SYSTEM',
                                'keterangan_transaksi' => $ket,
                                'debet' => $sisa,
                                'kredit' => '0',
                                'tanggal_posting' => $tgl_system,
                                'keterangan_posting' => '',
                                'id_admin' => $userid
                            ],
                            [
                                'unit' => $unit,
                                'kode_transaksi' => $kode_trans,
                                'kode_rekening' => '3202000',
                                'tanggal_transaksi' => $tgl_system,
                                'jenis_transaksi' => 'Bukti SYSTEM',
                                'keterangan_transaksi' => $ket,
                                'kredit' => $sisa,
                                'debet' => '0',
                                'tanggal_posting' => $tgl_system,
                                'keterangan_posting' => '',
                                'id_admin' => $userid
                            ]
                        ];
                        DB::table('tabel_transaksi')->insert($simpanTransaksi);

                    }
                }elseif ($pokok == 50000 && $wajib >= 0){
                    $ket="Setoran simpanan 5% an $nama";
                    $simpanWajib=[
                        'buss_date' => $tgl_system,
                        'norek' => $norek,
                        'unit' => $unit,
                        'cif' => $value,
                        'code_kel' => $code_kel,
                        'debet' => 0,
                        'type' => '05',
                        'kredit' => $nominal,
                        'userid' => $userid,
                        'ket' => 'PB Simpanan Wajib',
                        'reff' => $urut_wajib,
                        'cao' => $cao,
                        'blok' => '1',
                        'kode_transaksi' => $kode_trans
                    ];
                    DB::table('simpanan_wajib')->insert($simpanWajib);

                    $simpanTransaksi = [
                        [
                            'unit' => $unit,
                            'kode_transaksi' => $kode_trans,
                            'kode_rekening' => '1120000',
                            'tanggal_transaksi' => $tgl_system,
                            'jenis_transaksi' => 'Bukti SYSTEM',
                            'keterangan_transaksi' => $ket,
                            'debet' => $nominal,
                            'kredit' => '0',
                            'tanggal_posting' => $tgl_system,
                            'keterangan_posting' => '',
                            'id_admin' => $userid
                        ],
                        [
                            'unit' => $unit,
                            'kode_transaksi' => $kode_trans,
                            'kode_rekening' => '3202000',
                            'tanggal_transaksi' => $tgl_system,
                            'jenis_transaksi' => 'Bukti SYSTEM',
                            'keterangan_transaksi' => $ket,
                            'kredit' => $nominal,
                            'debet' => '0',
                            'tanggal_posting' => $tgl_system,
                            'keterangan_posting' => '',
                            'id_admin' => $userid
                        ]
                    ];
                    DB::table('tabel_transaksi')->insert($simpanTransaksi);



                }else{
                    $ket="Setoran simpanan 5% an $nama";
                    $simpanWajib=[
                        'buss_date' => $tgl_system,
                        'norek' => $norek,
                        'unit' => $unit,
                        'cif' => $value,
                        'code_kel' => $code_kel,
                        'debet' => 0,
                        'type' => '05',
                        'kredit' => $nominal,
                        'userid' => $userid,
                        'ket' => 'PB Simpanan Wajib Kedua',
                        'reff' => $urut_wajib,
                        'cao' => $cao,
                        'blok' => '1',
                        'kode_transaksi' => $kode_trans
                    ];
                    DB::table('simpanan_wajib')->insert($simpanWajib);

                    $simpanTransaksi = [
                        [
                            'unit' => $unit,
                            'kode_transaksi' => $kode_trans,
                            'kode_rekening' => '1120000',
                            'tanggal_transaksi' => $tgl_system,
                            'jenis_transaksi' => 'Bukti SYSTEM',
                            'keterangan_transaksi' => $ket,
                            'debet' => $nominal,
                            'kredit' => '0',
                            'tanggal_posting' => $tgl_system,
                            'keterangan_posting' => '',
                            'id_admin' => $userid
                        ],
                        [
                            'unit' => $unit,
                            'kode_transaksi' => $kode_trans,
                            'kode_rekening' => '3202000',
                            'tanggal_transaksi' => $tgl_system,
                            'jenis_transaksi' => 'Bukti SYSTEM',
                            'keterangan_transaksi' => $ket,
                            'kredit' => $nominal,
                            'debet' => '0',
                            'tanggal_posting' => $tgl_system,
                            'keterangan_posting' => '',
                            'id_admin' => $userid
                        ]
                    ];
                    DB::table('tabel_transaksi')->insert($simpanTransaksi);

                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Setoran Lima Persen berhasil direalisasikan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }





    public function getSetKelompok(Request $request)
    {
        $search = $request->q;
        $kelompok = DB::table('kelompok')
        ->select('code_kel', 'nama_kel')
        ->when($search, function ($query, $search) {
            return $query->where('code_kel', 'like', "%$search%")
                         ->orWhere('nama_kel', 'like', "%$search%");
        })
        ->limit(20)
        ->get();

        return response()->json($kelompok);
    }

}
