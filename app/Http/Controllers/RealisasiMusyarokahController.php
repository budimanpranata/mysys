<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;
use App\Models\temp_akad_mus;
use App\Models\Pembiayaan;
use Carbon\Carbon;


class RealisasiMusyarokahController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $pembiayaan = DB::table('pembiayaan')
        ->selectRaw('SUM(os - saldo_margin) as os, COUNT(cif) as noa')
        ->first();
        //dd($pembiayaan);
        $title = 'Setoran Lima Persen';

        return view('admin.realisasi_musyarokah.index',compact('menus','pembiayaan','title'));

    }

    public function getData(Request $request)
    {
        $query = temp_akad_mus::query()
        ->join('kelompok', 'temp_akad_mus.code_kel', '=', 'kelompok.code_kel')
        ->where('status_app', 'MUSYARAKAH')
        ->select(
            'temp_akad_mus.*',
            'kelompok.nama_kel',
        );



    if ($request->kode_kelompok) {
        $query->where('kelompok.code_kel', 'LIKE', '%' . $request->kode_kelompok . '%');
    }

    if ($request->tanggal_realisasi) {
        $query->where('tgl_akad', $request->tanggal_realisasi);
    }


    $data = $query->get();

    return response()->json($data);

    }
    public function realisasiMusyarokah(Request $request)
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
                $pembiayaan = DB::table('pembiayaan')
                ->where('cif', $value)
                ->first();


                if ($pembiayaan && $pembiayaan->os > 0) {

                    $batal[] = 'CIF ' . $value . ' nama '.$pembiayaan->nama .' masih ada pembiayaan '.$pembiayaan->os.' ';

                    continue;
                }else{


                $kode_trans = 'BU/' . $loan->unit . strtoupper(\Str::random(8));
                $nama = $loan->nama;
                $unit = $loan->unit;
                $code_kel = $loan->code_kel;
                $no_anggota = $loan->no_anggota;
                $norek = $no_anggota;
                $cif = $loan->cif;
                $cao = $loan->cao;
                $tenor = $loan->tenor;
                $pokok = $loan->pokok;
                $margin = $loan->ijaroh;
                $angsuran = $loan->angsuran;
                $bulat = $loan->bulat;
                $plafond = $loan->plafond;
                $os = $loan->os;
                $tgl_skeep=['2025-01-01','2025-01-07'];
                $ket = 'Realisasi Musyarokah atas nama ' . $nama . ' CIF ' . $cif . ' No Anggota ' . $no_anggota;

                $tgl_skeep = DB::table('param_libur')
                ->pluck('tanggal')
                ->map(function ($item) {
                    return Carbon::parse($item)->format('Y-m-d');
                })
                ->toArray();
                $data = [];
                $tgl_bayar = Carbon::parse($loan->next_schedule);
                $angsuran_ke = 1;

                while ($angsuran_ke <= $tenor) {
                    // Lewati jika tanggal termasuk dalam libur
                    if (in_array($tgl_bayar->format('Y-m-d'), $tgl_skeep)) {
                        $tgl_bayar->addDays(7);
                        continue;
                    }

                    $data[] = [
                        'id_pinjam' => $no_anggota,
                        'angsuran_ke' => $angsuran_ke,
                        'omzet' => 0,
                        'setoran' => $bulat,
                        'angsuran_pokok' => $pokok,
                        'angsuran_margin' => $margin,
                        'tgl_jatpo' => $tgl_bayar->format('Y-m-d'),
                        'tgl_bayar' => $tgl_bayar->format('Y-m-d'),
                        'margin_nisbah' => 0,
                        'cif' => $value,
                        'unit' => $unit,
                        'ao' => $cao,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $angsuran_ke++;
                    $tgl_bayar->addDays(7);
                }





            DB::table('musyarokah_detail')->insert($data);

            //proses insert jurnal
            $simpanTransaksi=[
                [
                    'unit' => $unit,
                    'kode_transaksi' => $kode_trans,
                    'kode_rekening' => '1472000',
                    'tanggal_transaksi' => $tgl_system,
                    'jenis_transaksi' => 'Bukti SYSTEM',
                    'keterangan_transaksi' => $ket,
                    'debet' => $plafond,
                    'kredit' => '0',
                    'tanggal_posting' => $tgl_system,
                    'keterangan_posting' => '',
                    'id_admin' => $userid,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'unit' => $unit,
                    'kode_transaksi' => $kode_trans,
                    'kode_rekening' => '1120000',
                    'tanggal_transaksi' => $tgl_system,
                    'jenis_transaksi' => 'Bukti SYSTEM',
                    'keterangan_transaksi' => $ket,
                    'kredit' => $plafond,
                    'debet' => '0',
                    'tanggal_posting' => $tgl_system,
                    'keterangan_posting' => '',
                    'id_admin' => $userid,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];
            $rekloan = [
                'tgl_realisasi' => $tgl_system,
                'unit' => $unit,
                'no_anggota' => $no_anggota,
                'saldo_kredit' => $os,
                'debet' => 0,
                'tipe' => 'M001',
                'ket' => "Realisasi Murabahah AN {$nama}",
                'userid' => $userid,
                'status' => 'REALISASI MUSYAROKAH',
                'cif' => $cif,
                'ao' => $cao
            ];

            DB::table('rek_loan')->insert($rekloan);


            $timestamp = date('YmdHis');
            $urutSimpanan = DB::table('simpanan')->count() + 1;
            $reff = $unit . $timestamp . $urutSimpanan;
            $reff2 = $unit . $timestamp . $urutSimpanan+1;



            $simpanan = [
                [
                    'reff' => $reff,
                    'buss_date' => $tgl_system,
                    'norek' => $no_anggota,
                    'unit' => $unit,
                    'cif' => $cif,
                    'code_kel' => $code_kel,
                    'debet' => $plafond,
                    'type' => '01',
                    'kredit' => 0,
                    'userid' => $userid,
                    'ket' => "Realisasi Musyarakah AN {$nama}",
                    'cao' => $cao,
                    'blok' => 0,
                    'tgl_input' => date('Y-m-d'),
                    'kode_transaksi' => $kode_trans,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                   'reff' => $reff2,
                    'buss_date' => $tgl_system,
                    'norek' => $no_anggota,
                    'unit' => $unit,
                    'cif' => $cif,
                    'code_kel' => $code_kel,
                    'debet' => 0,
                    'type' => '01',
                    'kredit' => $plafond,
                    'userid' => $userid,
                    'ket' => "Realisasi Musyarakah AN {$nama}",
                    'cao' => $cao,
                    'blok' => 0,
                    'tgl_input' => date('Y-m-d'),
                    'kode_transaksi' => $kode_trans,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];
            DB::table('simpanan')->insert($simpanan);
            DB::table('jurnal_umum')->insert([
                'nomor_jurnal' => null,
                'kode_transaksi' => $kode_trans,
                'tanggal_selesai' => $tgl_system,
                'unit' => $unit
            ]);


            DB::table('tabel_transaksi')->insert($simpanTransaksi);
            DB::statement("delete from pembiayaan where cif = '$value'");
            DB::statement("INSERT INTO pembiayaan SELECT * FROM temp_akad_mus where cif = '$value'");
            //DB::statement("delete from temp_akad_mus where cif = '$value'");




            }
        }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Musyarokah berhasil direalisasikan',
                'batal' => $batal ?? []
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
        ->where('code_unit', Auth()->user()->unit)
        ->when($search, function ($query, $search) {
            return $query->where('code_kel', 'like', "%$search%")
                         ->orWhere('nama_kel', 'like', "%$search%");
        })
        ->limit(20)
        ->get();

        return response()->json($kelompok);
    }

}

