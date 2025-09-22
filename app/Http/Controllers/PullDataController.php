<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\pull_data;
use Illuminate\Support\Facades\DB;

class PullDataController extends Controller
{
       public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $pembiayaan = DB::table('pull_data')
        ->selectRaw('SUM(os - saldo_margin) as os, COUNT(cif) as noa')
        ->first();
        $data = DB::table('pull_data')->get();
        //dd($pembiayaan);
        $title = 'Pull Data';

        return view('admin.pull-data.index',compact('menus','pembiayaan','title','data'));

    }

    public function data(Request $request)
{


    // ambil data sesuai filter dari request
     $jenisPull      = $request->jenis_pull;   // 01, 02, dst
        $transaksi   = $request->jenis_transaksi;    // lima, lebaran, pelunasan, dll
        $cifKel      = $request->kode_kelompok;
        $tglTagih    = $request->tanggal_tagih;
        $userid = auth()->user()->id;// contoh ambil dari user login



        switch ($jenisPull) {
            case "01": // kelompok
                if ($transaksi == 'lima') {
                    $rows = DB::table('temp_akad_mus')
                        ->leftJoin('kelompok', 'temp_akad_mus.code_kel', '=', 'kelompok.code_kel')
                        ->select(
                            'temp_akad_mus.unit',
                            'temp_akad_mus.code_kel',
                            'temp_akad_mus.cif',
                            'temp_akad_mus.cao',
                            'temp_akad_mus.saldo_margin',
                            'temp_akad_mus.Plafond',
                            'temp_akad_mus.no_anggota as norek',
                            'temp_akad_mus.pokok',
                            'temp_akad_mus.ijaroh',
                            'temp_akad_mus.angsuran',
                            'temp_akad_mus.hari',
                            'temp_akad_mus.bulat',
                            'temp_akad_mus.os',
                            'temp_akad_mus.nama',
                            'kelompok.nama_kel'
                        )
                        ->where('temp_akad_mus.code_kel', $cifKel)
                        ->get();

                        \Log::info('Rows hasil query:', $rows->toArray());


                    if ($rows->count() > 0) {
                        $dataLima = [];
                        $dataPull = [];

                        foreach ($rows as $row) {
                            $bayar = $row->Plafond * (5 / 100);

                            $record = [
                                'unit'             => $row->unit,
                                'tgl_tagih'        => $tglTagih,
                                'code_kel'         => $row->code_kel,
                                'cif'              => $row->cif,
                                'cao'              => $row->cao,
                                'norek'            => $row->norek,
                                'angsuran_pokok'   => $row->pokok,
                                'angsuran_margin'  => $row->ijaroh,
                                'angsuran'         => $row->angsuran,
                                'bayar'            => $bayar,
                                'status_realisasi' => '',
                                'pb'               => 0,
                                'ke'               => 0,
                                'tunggakan'        => 0,
                                'hari'             => $row->hari,
                                'twm'              => 0,
                                'bulat'            => $bayar,
                                'simpanan_wajib'   => 0,
                                'simpanan_pokok'   => 0,
                                'os'               => $row->os,
                                'nama'             => $row->nama,
                                'nama_kel'         => $row->nama_kel,
                                'saldo_margin'     => $row->saldo_margin,
                                'plafond'          => $row->Plafond,
                                'jenis_pull'       => 'lima',
                            ];

                            $dataLima[] = $record;
                            $dataPull[] = $record;
                        }

                        // insert ke tagihan_lima_persen
                        DB::table('tagihan_lima_persen')->insert($dataLima);

                        // insert ke pull_data
                        DB::table('pull_data')->insert($dataPull);

                       $inserted = DB::table('pull_data')->where('code_kel', $cifKel)->get();

                        return response()->json([
                            'success' => true,
                            'message' => 'Pull Data 5% sukses',
                            'data'    => $inserted
                        ]);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Gagal Pull Data !!!']);
                    }
                }elseif ($transaksi == 'lebaran') {
                   $rows = DB::table('temp_akad_mus')
                        ->leftJoin('kelompok', 'temp_akad_mus.code_kel', '=', 'kelompok.code_kel')
                        ->select(
                            'temp_akad_mus.unit',
                            'temp_akad_mus.code_kel',
                            'temp_akad_mus.cif',
                            'temp_akad_mus.cao',
                            'temp_akad_mus.saldo_margin',
                            'temp_akad_mus.Plafond',
                            'temp_akad_mus.no_anggota as norek',
                            'temp_akad_mus.pokok',
                            'temp_akad_mus.ijaroh',
                            'temp_akad_mus.angsuran',
                            'temp_akad_mus.hari',
                            'temp_akad_mus.bulat',
                            'temp_akad_mus.os',
                            'temp_akad_mus.nama',
                            'kelompok.nama_kel'
                        )
                        ->where('temp_akad_mus.code_kel', $cifKel)
                        ->get();



                    if ($rows->count() > 0) {
                        $dataLima = [];
                        $dataPull = [];

                        foreach ($rows as $row) {
                            $bayar = $row->Plafond * (5 / 100);

                            $record = [
                                'unit'             => $row->unit,
                                'tgl_tagih'        => $tglTagih,
                                'code_kel'         => $row->code_kel,
                                'cif'              => $row->cif,
                                'cao'              => $row->cao,
                                'norek'            => $row->norek,
                                'angsuran_pokok'   => $row->pokok,
                                'angsuran_margin'  => $row->ijaroh,
                                'angsuran'         => $row->angsuran,
                                'bayar'            => $bayar,
                                'status_realisasi' => '',
                                'pb'               => 0,
                                'ke'               => 0,
                                'tunggakan'        => 0,
                                'hari'             => $row->hari,
                                'twm'              => 0,
                                'bulat'            => $bayar,
                                'simpanan_wajib'   => 0,
                                'simpanan_pokok'   => 0,
                                'os'               => $row->os,
                                'nama'             => $row->nama,
                                'nama_kel'         => $row->nama_kel,
                                'saldo_margin'     => $row->saldo_margin,
                                'plafond'          => $row->Plafond,
                                'jenis_pull'       => 'lebaran',
                            ];

                            $dataLima[] = $record;
                            $dataPull[] = $record;
                        }

                        // insert ke tagihan_lima_persen
                        DB::table('tagihan_lebaran')->insert($dataLima);

                        // insert ke pull_data
                        DB::table('pull_data')->insert($dataPull);

                       $inserted = DB::table('pull_data')->where('code_kel', $cifKel)->get();

                        return response()->json([
                            'success' => true,
                            'message' => 'Pull Data Tagihan Lebaran sukses',
                            'data'    => $inserted
                        ]);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Gagal Pull Data !!!']);
                    }
                }elseif ($transaksi == 'pelunasan') {
                     $rows = DB::table('temp_akad_mus')
                        ->leftJoin('kelompok', 'temp_akad_mus.code_kel', '=', 'kelompok.code_kel')
                        ->select(
                            'temp_akad_mus.unit',
                            'temp_akad_mus.code_kel',
                            'temp_akad_mus.cif',
                            'temp_akad_mus.cao',
                            'temp_akad_mus.saldo_margin',
                            'temp_akad_mus.Plafond',
                            'temp_akad_mus.no_anggota as norek',
                            'temp_akad_mus.pokok',
                            'temp_akad_mus.ijaroh',
                            'temp_akad_mus.angsuran',
                            'temp_akad_mus.hari',
                            'temp_akad_mus.bulat',
                            'temp_akad_mus.os',
                            'temp_akad_mus.nama',
                            'kelompok.nama_kel'
                        )
                        ->where('temp_akad_mus.code_kel', $cifKel)
                        ->get();



                    if ($rows->count() > 0) {
                        $dataLima = [];
                        $dataPull = [];

                        foreach ($rows as $row) {
                            $bayar = $row->Plafond * (5 / 100);

                            $record = [
                                'unit'             => $row->unit,
                                'tgl_tagih'        => $tglTagih,
                                'code_kel'         => $row->code_kel,
                                'cif'              => $row->cif,
                                'cao'              => $row->cao,
                                'norek'            => $row->norek,
                                'angsuran_pokok'   => $row->pokok,
                                'angsuran_margin'  => $row->ijaroh,
                                'angsuran'         => $row->angsuran,
                                'bayar'            => $bayar,
                                'status_realisasi' => '',
                                'pb'               => 0,
                                'ke'               => 0,
                                'tunggakan'        => 0,
                                'hari'             => $row->hari,
                                'twm'              => 0,
                                'bulat'            => $bayar,
                                'simpanan_wajib'   => 0,
                                'simpanan_pokok'   => 0,
                                'os'               => $row->os,
                                'nama'             => $row->nama,
                                'nama_kel'         => $row->nama_kel,
                                'saldo_margin'     => $row->saldo_margin,
                                'plafond'          => $row->Plafond,
                                'jenis_pull'       => 'pelunasan',
                            ];

                            $dataLima[] = $record;
                            $dataPull[] = $record;
                        }

                        // insert ke tagihan_lima_persen
                        DB::table('tagihan_pelunasan')->insert($dataLima);

                        // insert ke pull_data
                        DB::table('pull_data')->insert($dataPull);

                       $inserted = DB::table('pull_data')->where('code_kel', $cifKel)->get();

                        return response()->json([
                            'success' => true,
                            'message' => 'Pull Data Tagihan Pelunasan sukses',
                            'data'    => $inserted
                        ]);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Gagal Pull Data !!!']);
                    }
                }elseif ($transaksi == 'pelunasan19') {
                   $rows = DB::table('temp_akad_mus')
                        ->leftJoin('kelompok', 'temp_akad_mus.code_kel', '=', 'kelompok.code_kel')
                        ->select(
                            'temp_akad_mus.unit',
                            'temp_akad_mus.code_kel',
                            'temp_akad_mus.cif',
                            'temp_akad_mus.cao',
                            'temp_akad_mus.saldo_margin',
                            'temp_akad_mus.Plafond',
                            'temp_akad_mus.no_anggota as norek',
                            'temp_akad_mus.pokok',
                            'temp_akad_mus.ijaroh',
                            'temp_akad_mus.angsuran',
                            'temp_akad_mus.hari',
                            'temp_akad_mus.bulat',
                            'temp_akad_mus.os',
                            'temp_akad_mus.nama',
                            'kelompok.nama_kel'
                        )
                        ->where('temp_akad_mus.code_kel', $cifKel)
                        ->get();



                    if ($rows->count() > 0) {
                        $dataLima = [];
                        $dataPull = [];

                        foreach ($rows as $row) {
                            $bayar = $row->Plafond * (5 / 100);

                            $record = [
                                'unit'             => $row->unit,
                                'tgl_tagih'        => $tglTagih,
                                'code_kel'         => $row->code_kel,
                                'cif'              => $row->cif,
                                'cao'              => $row->cao,
                                'norek'            => $row->norek,
                                'angsuran_pokok'   => $row->pokok,
                                'angsuran_margin'  => $row->ijaroh,
                                'angsuran'         => $row->angsuran,
                                'bayar'            => $bayar,
                                'status_realisasi' => '',
                                'pb'               => 0,
                                'ke'               => 0,
                                'tunggakan'        => 0,
                                'hari'             => $row->hari,
                                'twm'              => 0,
                                'bulat'            => $bayar,
                                'simpanan_wajib'   => 0,
                                'simpanan_pokok'   => 0,
                                'os'               => $row->os,
                                'nama'             => $row->nama,
                                'nama_kel'         => $row->nama_kel,
                                'saldo_margin'     => $row->saldo_margin,
                                'plafond'          => $row->Plafond,
                                'jenis_pull'       => 'pelunasan19',
                            ];

                            $dataLima[] = $record;
                            $dataPull[] = $record;
                        }

                        // insert ke tagihan_lima_persen
                        DB::table('tagihan_pelunasan')->insert($dataLima);

                        // insert ke pull_data
                        DB::table('pull_data')->insert($dataPull);

                       $inserted = DB::table('pull_data')->where('code_kel', $cifKel)->get();

                        return response()->json([
                            'success' => true,
                            'message' => 'Pull Data Tagihan Pelunasan19 sukses',
                            'data'    => $inserted
                        ]);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Gagal Pull Data !!!']);
                    }
                }elseif ($transaksi == 'pelunasanRestMargin') {
                        $rows = DB::table('temp_akad_mus')
                        ->leftJoin('kelompok', 'temp_akad_mus.code_kel', '=', 'kelompok.code_kel')
                        ->select(
                            'temp_akad_mus.unit',
                            'temp_akad_mus.code_kel',
                            'temp_akad_mus.cif',
                            'temp_akad_mus.cao',
                            'temp_akad_mus.saldo_margin',
                            'temp_akad_mus.Plafond',
                            'temp_akad_mus.no_anggota as norek',
                            'temp_akad_mus.pokok',
                            'temp_akad_mus.ijaroh',
                            'temp_akad_mus.angsuran',
                            'temp_akad_mus.hari',
                            'temp_akad_mus.bulat',
                            'temp_akad_mus.os',
                            'temp_akad_mus.nama',
                            'kelompok.nama_kel'
                        )
                        ->where('temp_akad_mus.code_kel', $cifKel)
                        ->get();



                    if ($rows->count() > 0) {
                        $dataLima = [];
                        $dataPull = [];

                        foreach ($rows as $row) {
                            $bayar = $row->Plafond * (5 / 100);

                            $record = [
                                'unit'             => $row->unit,
                                'tgl_tagih'        => $tglTagih,
                                'code_kel'         => $row->code_kel,
                                'cif'              => $row->cif,
                                'cao'              => $row->cao,
                                'norek'            => $row->norek,
                                'angsuran_pokok'   => $row->pokok,
                                'angsuran_margin'  => $row->ijaroh,
                                'angsuran'         => $row->angsuran,
                                'bayar'            => $bayar,
                                'status_realisasi' => '',
                                'pb'               => 0,
                                'ke'               => 0,
                                'tunggakan'        => 0,
                                'hari'             => $row->hari,
                                'twm'              => 0,
                                'bulat'            => $bayar,
                                'simpanan_wajib'   => 0,
                                'simpanan_pokok'   => 0,
                                'os'               => $row->os,
                                'nama'             => $row->nama,
                                'nama_kel'         => $row->nama_kel,
                                'saldo_margin'     => $row->saldo_margin,
                                'plafond'          => $row->Plafond,
                                'jenis_pull'       => 'pelunasanRestMargin',
                            ];

                            $dataLima[] = $record;
                            $dataPull[] = $record;
                        }

                        // insert ke tagihan_lima_persen
                        DB::table('tagihan_pelunasan')->insert($dataLima);

                        // insert ke pull_data
                        DB::table('pull_data')->insert($dataPull);

                       $inserted = DB::table('pull_data')->where('code_kel', $cifKel)->get();

                        return response()->json([
                            'success' => true,
                            'message' => 'Pull Data Tagihan Pelunasan19 sukses',
                            'data'    => $inserted
                        ]);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Gagal Pull Data !!!']);
                    }
                }elseif ($transaksi == 'penarikan') {
                     $rows = DB::table('temp_akad_mus')
                        ->leftJoin('kelompok', 'temp_akad_mus.code_kel', '=', 'kelompok.code_kel')
                        ->select(
                            'temp_akad_mus.unit',
                            'temp_akad_mus.code_kel',
                            'temp_akad_mus.cif',
                            'temp_akad_mus.cao',
                            'temp_akad_mus.saldo_margin',
                            'temp_akad_mus.Plafond',
                            'temp_akad_mus.no_anggota as norek',
                            'temp_akad_mus.pokok',
                            'temp_akad_mus.ijaroh',
                            'temp_akad_mus.angsuran',
                            'temp_akad_mus.hari',
                            'temp_akad_mus.bulat',
                            'temp_akad_mus.os',
                            'temp_akad_mus.nama',
                            'kelompok.nama_kel'
                        )
                        ->where('temp_akad_mus.code_kel', $cifKel)
                        ->get();
                    if ($rows->count() > 0) {
                        $dataLima = [];
                        $dataPull = [];
                        foreach ($rows as $row) {
                            $bayar = $row->Plafond * (5 / 100);

                            $record = [
                                'unit'             => $row->unit,
                                'tgl_tagih'        => $tglTagih,
                                'code_kel'         => $row->code_kel,
                                'cif'              => $row->cif,
                                'cao'              => $row->cao,
                                'norek'            => $row->norek,
                                'angsuran_pokok'   => $row->pokok,
                                'angsuran_margin'  => $row->ijaroh,
                                'angsuran'         => $row->angsuran,
                                'bayar'            => $bayar,
                                'status_realisasi' => '',
                                'pb'               => 0,
                                'ke'               => 0,
                                'tunggakan'        => 0,
                                'hari'             => $row->hari,
                                'twm'              => 0,
                                'bulat'            => $bayar,
                                'simpanan_wajib'   => 0,
                                'simpanan_pokok'   => 0,
                                'os'               => $row->os,
                                'nama'             => $row->nama,
                                'nama_kel'         => $row->nama_kel,
                                'saldo_margin'     => $row->saldo_margin,
                                'plafond'          => $row->Plafond,
                                'jenis_pull'       => 'penarikan',
                            ];

                            $dataLima[] = $record;
                            $dataPull[] = $record;
                        }
                        // insert ke tagihan_lima_persen
                        DB::table('tagihan_penarikan')->insert($dataLima);
                        // insert ke pull_data
                        DB::table('pull_data')->insert($dataPull);
                          $inserted = DB::table('pull_data')->where('code_kel', $cifKel)->get();
                        return response()->json([
                            'success' => true,
                            'message' => 'Pull Data Tagihan Penarikan sukses',
                            'data'    => $inserted
                        ]);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Gagal Pull Data !!!']);
                    }
                }

                // kamu bisa lanjutkan else if untuk "lebaran", "pelunasan", dll
                break;

            case "02": // individu
               if ($transaksi == 'lima') {
                    $rows = DB::table('temp_akad_mus')
                        ->leftJoin('kelompok', 'temp_akad_mus.code_kel', '=', 'kelompok.code_kel')
                        ->select(
                            'temp_akad_mus.unit',
                            'temp_akad_mus.code_kel',
                            'temp_akad_mus.cif',
                            'temp_akad_mus.cao',
                            'temp_akad_mus.saldo_margin',
                            'temp_akad_mus.Plafond',
                            'temp_akad_mus.no_anggota as norek',
                            'temp_akad_mus.pokok',
                            'temp_akad_mus.ijaroh',
                            'temp_akad_mus.angsuran',
                            'temp_akad_mus.hari',
                            'temp_akad_mus.bulat',
                            'temp_akad_mus.os',
                            'temp_akad_mus.nama',
                            'kelompok.nama_kel'
                        )
                        ->where('temp_akad_mus.cif', $cifKel)
                        ->get();

                        \Log::info('Rows hasil query:', $rows->toArray());
                        //\Log::info('CIF hasil seelct:', $cifKel);


                    if ($rows->count() > 0) {
                        $dataLima = [];
                        $dataPull = [];

                        foreach ($rows as $row) {
                            $bayar = $row->Plafond * (5 / 100);

                            $record = [
                                'unit'             => $row->unit,
                                'tgl_tagih'        => $tglTagih,
                                'code_kel'         => $row->code_kel,
                                'cif'              => $row->cif,
                                'cao'              => $row->cao,
                                'norek'            => $row->norek,
                                'angsuran_pokok'   => $row->pokok,
                                'angsuran_margin'  => $row->ijaroh,
                                'angsuran'         => $row->angsuran,
                                'bayar'            => $bayar,
                                'status_realisasi' => '',
                                'pb'               => 0,
                                'ke'               => 0,
                                'tunggakan'        => 0,
                                'hari'             => $row->hari,
                                'twm'              => 0,
                                'bulat'            => $bayar,
                                'simpanan_wajib'   => 0,
                                'simpanan_pokok'   => 0,
                                'os'               => $row->os,
                                'nama'             => $row->nama,
                                'nama_kel'         => $row->nama_kel,
                                'saldo_margin'     => $row->saldo_margin,
                                'plafond'          => $row->Plafond,
                                'jenis_pull'       => 'lima',
                            ];

                            $dataLima[] = $record;
                            $dataPull[] = $record;
                        }

                        // insert ke tagihan_lima_persen
                        DB::table('tagihan_lima_persen')->insert($dataLima);

                        // insert ke pull_data
                        DB::table('pull_data')->insert($dataPull);

                       $inserted = DB::table('pull_data')->get();

                        return response()->json([
                            'success' => true,
                            'message' => 'Pull Data 5% sukses',
                            'data'    => $inserted
                        ]);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Gagal Pull Data !!!']);
                    }
                }elseif ($transaksi == 'lebaran') {

                    $rows = DB::table('pembiayaan')
                        ->leftJoin('kelompok', 'pembiayaan.code_kel', '=', 'kelompok.code_kel')
                        ->select(
                            'pembiayaan.unit',
                            'pembiayaan.code_kel',
                            'pembiayaan.cif',
                            'pembiayaan.cao',
                            'pembiayaan.saldo_margin',
                            'pembiayaan.Plafond',
                            'pembiayaan.no_anggota as norek',
                            'pembiayaan.pokok',
                            'pembiayaan.ijaroh',
                            'pembiayaan.angsuran',
                            'pembiayaan.hari',
                            'pembiayaan.bulat',
                            'pembiayaan.os',
                            'pembiayaan.nama',
                            'pembiayaan.nama_kel'
                        )
                        ->where('pembiayaan.cif', $cifKel)
                        ->get();

                        \Log::info('Rows hasil query:', $rows->toArray());
                        //\Log::info('CIF hasil seelct:', $cifKel);


                    if ($rows->count() > 0) {
                        $dataLima = [];
                        $dataPull = [];

                        foreach ($rows as $row) {
                            $bayar = $row->bulat;
                            $nama = $row->nama;

                            $record = [
                                'unit'             => $row->unit,
                                'tgl_tagih'        => $tglTagih,
                                'code_kel'         => $row->code_kel,
                                'cif'              => $row->cif,
                                'cao'              => $row->cao,
                                'norek'            => $row->norek,
                                'angsuran_pokok'   => $row->pokok,
                                'angsuran_margin'  => $row->ijaroh,
                                'angsuran'         => $row->angsuran,
                                'bayar'            => $bayar,
                                'status_realisasi' => '',
                                'pb'               => 0,
                                'ke'               => 0,
                                'tunggakan'        => 0,
                                'hari'             => $row->hari,
                                'twm'              => 0,
                                'bulat'            => $bayar,
                                'simpanan_wajib'   => 0,
                                'simpanan_pokok'   => 0,
                                'os'               => $row->os,
                                'nama'             => $row->nama,
                                'nama_kel'         => $row->nama_kel,
                                'saldo_margin'     => $row->saldo_margin,
                                'plafond'          => $row->Plafond,
                                'jenis_pull'       => 'lebaran',
                            ];

                            $dataLima[] = $record;
                            $dataPull[] = $record;
                        }

                        // insert ke tagihan_lima_persen
                        DB::table('tagihan_lebaran')->insert($dataLima);

                        // insert ke pull_data
                        DB::table('pull_data')->insert($dataPull);

                       $inserted = DB::table('pull_data')->get();

                        return response()->json([
                            'success' => true,
                            'message' => 'Pull Data Lebaran sukses',
                            'data'    => $inserted
                        ]);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Gagal Pull Data !!!']);
                    }

                   // logika untuk lebaran individu
                }elseif ($transaksi == 'pelunasan') {

                    $rows = DB::table('pembiayaan')
                        ->leftJoin('kelompok', 'pembiayaan.code_kel', '=', 'kelompok.code_kel')
                        ->select(
                            'pembiayaan.unit',
                            'pembiayaan.code_kel',
                            'pembiayaan.cif',
                            'pembiayaan.cao',
                            'pembiayaan.saldo_margin',
                            'pembiayaan.Plafond',
                            'pembiayaan.no_anggota as norek',
                            'pembiayaan.pokok',
                            'pembiayaan.ijaroh',
                            'pembiayaan.angsuran',
                            'pembiayaan.hari',
                            'pembiayaan.bulat',
                            'pembiayaan.os',
                            'pembiayaan.nama',
                            'pembiayaan.nama_kel'
                        )
                        ->where('pembiayaan.cif', $cifKel)
                        ->get();

                        \Log::info('Rows hasil query:', $rows->toArray());
                        //\Log::info('CIF hasil seelct:', $cifKel);


                    if ($rows->count() > 0) {
                        $dataLima = [];
                        $dataPull = [];

                        foreach ($rows as $row) {
                       $simpanan = DB::table('simpanan')
                        ->where('cif', $row->cif)
                        ->selectRaw('COALESCE(SUM(kredit), 0) - COALESCE(SUM(debet), 0) as saldo')
                        ->value('saldo');

                          $sisa = $simpanan - $os;
                         if  ($simpanan < $row->os ){
                             $bayar = 0;
                         }else{
                             $bayar = $sisa;
                         }
                            $record = [
                                'unit'             => $row->unit,
                                'tgl_tagih'        => $tglTagih,
                                'code_kel'         => $row->code_kel,
                                'cif'              => $row->cif,
                                'cao'              => $row->cao,
                                'norek'            => $row->norek,
                                'angsuran_pokok'   => $row->pokok,
                                'angsuran_margin'  => $row->ijaroh,
                                'angsuran'         => $row->angsuran,
                                'bayar'            => $bayar,
                                'status_realisasi' => '',
                                'pb'               => 0,
                                'ke'               => 0,
                                'tunggakan'        => 0,
                                'hari'             => $row->hari,
                                'twm'              => 0,
                                'bulat'            => $bayar,
                                'simpanan_wajib'   => 0,
                                'simpanan_pokok'   => 0,
                                'os'               => $row->os,
                                'nama'             => $row->nama,
                                'nama_kel'         => $row->nama_kel,
                                'saldo_margin'     => $row->saldo_margin,
                                'plafond'          => $row->Plafond,
                                'jenis_pull'       => 'pelunasan',
                            ];

                            $dataLima[] = $record;
                            $dataPull[] = $record;
                        }

                        // insert ke tagihan_lima_persen
                        DB::table('tagihan_pelunasan')->insert($dataLima);

                        // insert ke pull_data
                        DB::table('pull_data')->insert($dataPull);

                       $inserted = DB::table('pull_data')->get();

                        return response()->json([
                            'success' => true,
                            'message' => 'Pull Data Lebaran sukses',
                            'data'    => $inserted
                        ]);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Gagal Pull Data !!!']);
                    }

                   // logika untuk pelunasan individu
                }elseif ($transaksi == 'pelunasan19') {


                    $rows = DB::table('pembiayaan')
                        ->leftJoin('kelompok', 'pembiayaan.code_kel', '=', 'kelompok.code_kel')
                        ->select(
                            'pembiayaan.unit',
                            'pembiayaan.code_kel',
                            'pembiayaan.cif',
                            'pembiayaan.cao',
                            'pembiayaan.saldo_margin',
                            'pembiayaan.Plafond',
                            'pembiayaan.no_anggota as norek',
                            'pembiayaan.pokok',
                            'pembiayaan.ijaroh',
                            'pembiayaan.angsuran',
                            'pembiayaan.hari',
                            'pembiayaan.bulat',
                            'pembiayaan.os',
                            'pembiayaan.nama',
                            'pembiayaan.nama_kel'
                        )
                        ->where('pembiayaan.cif', $cifKel)
                        ->get();

                        \Log::info('Rows hasil query:', $rows->toArray());
                        //\Log::info('CIF hasil seelct:', $cifKel);


                    if ($rows->count() > 0) {
                        $dataLima = [];
                        $dataPull = [];

                        foreach ($rows as $row) {
                       $simpanan = DB::table('simpanan')
                        ->where('cif', $row->cif)
                        ->selectRaw('COALESCE(SUM(kredit), 0) - COALESCE(SUM(debet), 0) as saldo')
                        ->value('saldo');

                          $sisa = $simpanan - $os;
                         if  ($simpanan < $row->os ){
                             $bayar = 0;
                         }else{
                             $bayar = $sisa;
                         }
                            $record = [
                                'unit'             => $row->unit,
                                'tgl_tagih'        => $tglTagih,
                                'code_kel'         => $row->code_kel,
                                'cif'              => $row->cif,
                                'cao'              => $row->cao,
                                'norek'            => $row->norek,
                                'angsuran_pokok'   => $row->pokok,
                                'angsuran_margin'  => $row->ijaroh,
                                'angsuran'         => $row->angsuran,
                                'bayar'            => $bayar,
                                'status_realisasi' => '',
                                'pb'               => 0,
                                'ke'               => 0,
                                'tunggakan'        => 0,
                                'hari'             => $row->hari,
                                'twm'              => 0,
                                'bulat'            => $bayar,
                                'simpanan_wajib'   => 0,
                                'simpanan_pokok'   => 0,
                                'os'               => $row->os,
                                'nama'             => $row->nama,
                                'nama_kel'         => $row->nama_kel,
                                'saldo_margin'     => $row->saldo_margin,
                                'plafond'          => $row->Plafond,
                                'jenis_pull'       => 'pelunasan19',
                            ];

                            $dataLima[] = $record;
                            $dataPull[] = $record;
                        }

                        // insert ke tagihan_lima_persen
                        DB::table('tagihan_pelunasan')->insert($dataLima);

                        // insert ke pull_data
                        DB::table('pull_data')->insert($dataPull);

                       $inserted = DB::table('pull_data')->get();

                        return response()->json([
                            'success' => true,
                            'message' => 'Pull Data Lebaran sukses',
                            'data'    => $inserted
                        ]);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Gagal Pull Data !!!']);
                    }

                }elseif ($transaksi == 'pelunasanRestMargin') {


                    $rows = DB::table('pembiayaan')
                        ->leftJoin('kelompok', 'pembiayaan.code_kel', '=', 'kelompok.code_kel')
                        ->select(
                            'pembiayaan.unit',
                            'pembiayaan.code_kel',
                            'pembiayaan.cif',
                            'pembiayaan.cao',
                            'pembiayaan.saldo_margin',
                            'pembiayaan.Plafond',
                            'pembiayaan.no_anggota as norek',
                            'pembiayaan.pokok',
                            'pembiayaan.ijaroh',
                            'pembiayaan.angsuran',
                            'pembiayaan.hari',
                            'pembiayaan.bulat',
                            'pembiayaan.os',
                            'pembiayaan.nama',
                            'pembiayaan.nama_kel'
                        )
                        ->where('pembiayaan.cif', $cifKel)
                        ->get();

                        \Log::info('Rows hasil query:', $rows->toArray());
                        //\Log::info('CIF hasil seelct:', $cifKel);


                    if ($rows->count() > 0) {
                        $dataLima = [];
                        $dataPull = [];

                        foreach ($rows as $row) {
                       $simpanan = DB::table('simpanan')
                        ->where('cif', $row->cif)
                        ->selectRaw('COALESCE(SUM(kredit), 0) - COALESCE(SUM(debet), 0) as saldo')
                        ->value('saldo');

                          $sisa = $simpanan - $os;
                         if  ($simpanan < $row->os ){
                             $bayar = 0;
                         }else{
                             $bayar = $sisa;
                         }
                            $record = [
                                'unit'             => $row->unit,
                                'tgl_tagih'        => $tglTagih,
                                'code_kel'         => $row->code_kel,
                                'cif'              => $row->cif,
                                'cao'              => $row->cao,
                                'norek'            => $row->norek,
                                'angsuran_pokok'   => $row->pokok,
                                'angsuran_margin'  => $row->ijaroh,
                                'angsuran'         => $row->angsuran,
                                'bayar'            => $bayar,
                                'status_realisasi' => '',
                                'pb'               => 0,
                                'ke'               => 0,
                                'tunggakan'        => 0,
                                'hari'             => $row->hari,
                                'twm'              => 0,
                                'bulat'            => $bayar,
                                'simpanan_wajib'   => 0,
                                'simpanan_pokok'   => 0,
                                'os'               => $row->os,
                                'nama'             => $row->nama,
                                'nama_kel'         => $row->nama_kel,
                                'saldo_margin'     => $row->saldo_margin,
                                'plafond'          => $row->Plafond,
                                'jenis_pull'       => 'pelunasanRestMargin',
                            ];

                            $dataLima[] = $record;
                            $dataPull[] = $record;
                        }

                        // insert ke tagihan_lima_persen
                        DB::table('tagihan_pelunasan')->insert($dataLima);

                        // insert ke pull_data
                        DB::table('pull_data')->insert($dataPull);

                       $inserted = DB::table('pull_data')->get();

                        return response()->json([
                            'success' => true,
                            'message' => 'Pull Data Lebaran sukses',
                            'data'    => $inserted
                        ]);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Gagal Pull Data !!!']);
                    }

                   // logika untuk pelunasan19 individu
                }elseif ($transaksi == 'penarikan') {



                    $rows = DB::table('pembiayaan')
                        ->leftJoin('kelompok', 'pembiayaan.code_kel', '=', 'kelompok.code_kel')
                        ->select(
                            'pembiayaan.unit',
                            'pembiayaan.code_kel',
                            'pembiayaan.cif',
                            'pembiayaan.cao',
                            'pembiayaan.saldo_margin',
                            'pembiayaan.Plafond',
                            'pembiayaan.no_anggota as norek',
                            'pembiayaan.pokok',
                            'pembiayaan.ijaroh',
                            'pembiayaan.angsuran',
                            'pembiayaan.hari',
                            'pembiayaan.bulat',
                            'pembiayaan.os',
                            'pembiayaan.nama',
                            'pembiayaan.nama_kel'
                        )
                        ->where('pembiayaan.cif', $cifKel)
                        ->get();

                        \Log::info('Rows hasil query:', $rows->toArray());
                        //\Log::info('CIF hasil seelct:', $cifKel);


                    if ($rows->count() > 0) {
                        $dataLima = [];
                        $dataPull = [];

                        foreach ($rows as $row) {
                       $simpanan = DB::table('simpanan')
                        ->where('cif', $row->cif)
                        ->selectRaw('COALESCE(SUM(kredit), 0) - COALESCE(SUM(debet), 0) as saldo')
                        ->value('saldo');

                          $sisa = $simpanan - $request->nominal;
                         if  ($simpanan < $nominal ){
                             $bayar = $simpanan;
                         }else{
                             $bayar = $nominal;
                         }
                            $record = [
                                'unit'             => $row->unit,
                                'tgl_tagih'        => $tglTagih,
                                'code_kel'         => $row->code_kel,
                                'cif'              => $row->cif,
                                'cao'              => $row->cao,
                                'norek'            => $row->norek,
                                'angsuran_pokok'   => $row->pokok,
                                'angsuran_margin'  => $row->ijaroh,
                                'angsuran'         => $row->angsuran,
                                'bayar'            => $bayar,
                                'status_realisasi' => '',
                                'pb'               => 0,
                                'ke'               => 0,
                                'tunggakan'        => 0,
                                'hari'             => $row->hari,
                                'twm'              => 0,
                                'bulat'            => $bayar,
                                'simpanan_wajib'   => 0,
                                'simpanan_pokok'   => 0,
                                'os'               => $row->os,
                                'nama'             => $row->nama,
                                'nama_kel'         => $row->nama_kel,
                                'saldo_margin'     => $row->saldo_margin,
                                'plafond'          => $row->Plafond,
                                'jenis_pull'       => 'penarikan',
                            ];

                            $dataLima[] = $record;
                            $dataPull[] = $record;
                        }

                        // insert ke tagihan_lima_persen
                        DB::table('tagihan_penarikan')->insert($dataLima);

                        // insert ke pull_data
                        DB::table('pull_data')->insert($dataPull);

                       $inserted = DB::table('pull_data')->get();

                        return response()->json([
                            'success' => true,
                            'message' => 'Pull Data Lebaran sukses',
                            'data'    => $inserted
                        ]);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Gagal Pull Data !!!']);
                    }

                }



                break;

            default:
                return response()->json(['success' => false, 'message' => 'Jenis pull tidak dikenali']);
        }
    }


public function destroy($id)
{
    $item = pull_data::findOrFail($id);
    $item->delete();


                       $deleted = DB::table('pull_data')->get();

                        return response()->json([
                            'success' => true,
                            'message' => 'Pull Data 5% sukses',
                            'data'    => $deleted
                        ]);
}

public function suggest(Request $request)
{
    $q = $request->get('q');
    $data = DB::table('anggota')
        ->leftJoin('kelompok', 'kelompok.code_kel', '=', 'anggota.kode_kel')
        ->select('anggota.CIF as cif', 'anggota.nama as nama', 'kelompok.nama_kel')
        ->where('anggota.CIF', 'like', $q.'%')
        ->orWhere('anggota.nama', 'like', $q.'%')
        ->limit(10)
        ->get();

    if ($data->isEmpty()) {
        $data = DB::table('pembiayaan')
            ->leftJoin('kelompok', 'kelompok.code_kel', '=', 'pembiayaan.code_kel')
            ->select('pembiayaan.cif as cif', 'pembiayaan.nama as nama', 'kelompok.nama_kel')
            ->where('pembiayaan.cif', 'like', $q.'%')
            ->orWhere('pembiayaan.nama', 'like', $q.'%')
            ->limit(10)
            ->get();
    }

    if ($data->isEmpty()) {
        $data = DB::table('anggota')
            ->leftJoin('kelompok', 'kelompok.code_kel', '=', 'anggota.kode_kel')
            ->select('anggota.kode_kel as cif', 'kelompok.nama_kel as nama', 'kelompok.nama_kel')
            ->where('anggota.kode_kel', 'like', $q.'%')
            ->orWhere('kelompok.nama_kel', 'like', $q.'%')
            ->groupBy('anggota.kode_kel', 'kelompok.nama_kel')
            ->limit(10)
            ->get();
    }

    return response()->json($data);
}

public function list()
{
    $data = DB::table('pull_data')->get();

    return response()->json(['data' => $data]);
}


}
