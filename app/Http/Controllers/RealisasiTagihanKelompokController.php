<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Str;

class RealisasiTagihanKelompokController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $title = 'Realisasi Tagihan Kelompok';
        return view('admin.realisasi_tagihan_kelompok.index', compact('menus', 'title'));
    }

    public function getKelompok(Request $request)
    {
        $code_kel = $request->input('code_kel');

        $data = DB::table('pembiayaan')
            ->join('anggota', 'pembiayaan.no_anggota', '=', 'anggota.no')
            ->leftjoin('simpanan', 'pembiayaan.cif', '=', 'simpanan.cif')
            ->select(
                'pembiayaan.*',
                DB::raw('((pembiayaan.bulat - pembiayaan.angsuran) * pembiayaan.run_tenor) as twm'),
                DB::raw('
                    CASE 
                        WHEN pembiayaan.deal_produk = 2 THEN 
                            ((pembiayaan.bulat - pembiayaan.angsuran - 2500) * pembiayaan.run_tenor)
                        ELSE 
                            (pembiayaan.bulat - pembiayaan.angsuran) * pembiayaan.run_tenor
                    END as saldo_twm
                '),
                DB::raw('
                    CASE 
                        WHEN pembiayaan.deal_produk = 2 THEN 
                            ((pembiayaan.bulat - pembiayaan.angsuran) * pembiayaan.run_tenor - 
                            ((pembiayaan.bulat - pembiayaan.angsuran - 2500) * pembiayaan.run_tenor))
                        ELSE 
                            ((pembiayaan.bulat - pembiayaan.angsuran) * pembiayaan.run_tenor - 
                            ((pembiayaan.bulat - pembiayaan.angsuran) * pembiayaan.run_tenor))
                    END as sisa_twm
                ')
            )
            ->where('pembiayaan.code_kel', $code_kel)
            ->groupBy('pembiayaan.cif')
            ->get();

        return response()->json(['data' => $data]);
    }

    public function processRealisasi(Request $request)
    {

        $unit = auth()->user()->unit;
        $user_id = auth()->user()->id;
        $tanggal = date('Y-m-d');

        DB::beginTransaction();
        
        try {
            $items = $request->items;
            $BuatJurnal = [];
            
            foreach ($items as $item) {
                DB::table('pembiayaan')
                ->where('cif', $item['cif'])
                ->update([
                    'run_tenor' => DB::raw('run_tenor + 1')
                ]);

                $pembiayaan = DB::table('pembiayaan')
                ->join('anggota', 'pembiayaan.cif', '=', 'anggota.cif')
                ->where('pembiayaan.cif', $item['cif'])
                ->select(
                    'pembiayaan.*',
                    'anggota.norek',
                )
                ->first();

                $twm = $pembiayaan->bulat - $pembiayaan->angsuran;
                if ($pembiayaan->deal_type == 2) {
                    $saldo_twm = $pembiayaan->bulat - $pembiayaan->angsuran - 2500;
                } else {
                    $saldo_twm = $pembiayaan->bulat - $pembiayaan->angsuran;
                }

                $total_twm = ($pembiayaan->bulat - $pembiayaan->angsuran) * $pembiayaan->run_tenor;
                $angsuran = $pembiayaan->angsuran;

                $noTransaksi = 'BU/' . $pembiayaan->unit . strtoupper(\Str::random(8));
                $keterangan = 'Setor Tagihan ' . $pembiayaan->nama . ' ' . $pembiayaan->cif;
                $ketSimpanan = 'Setoran an ' . $pembiayaan->nama;

                $timestamp = date('YmdHis');

                // insert ke tabel simpanan
                DB::table('simpanan')->insert([
                    'buss_date' => $tanggal,
                    'norek' => $pembiayaan->norek,
                    'unit' => $unit,
                    'cif' => $pembiayaan->cif,
                    'code_kel' => $pembiayaan->code_kel,
                    'debet' => 0,
                    'type' => '04',
                    'kredit' => $pembiayaan->angsuran,
                    'userid' => $user_id,
                    'ket' => $ketSimpanan,
                    'reff' => $unit . $timestamp . strtoupper(\Str::random(2)),
                    'cao' => $pembiayaan->cao,
                    'blok' => '2',
                    'kode_transaksi' => $noTransaksi,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                DB::table('simpanan')->insert([
                    'buss_date' => $tanggal,
                    'norek' => $pembiayaan->norek,
                    'unit' => $unit,
                    'cif' => $pembiayaan->cif,
                    'code_kel' => $pembiayaan->code_kel,
                    'debet' => 0,
                    'type' => '04',
                    'kredit' => $twm,
                    'userid' => $user_id,
                    'ket' => $ketSimpanan,
                    'reff' => $unit . $timestamp . strtoupper(\Str::random(2)),
                    'cao' => $pembiayaan->cao,
                    'blok' => '2',
                    'kode_transaksi' => $noTransaksi,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                DB::table('simpanan')->insert([
                    'buss_date' => $tanggal,
                    'norek' => $pembiayaan->norek,
                    'unit' => $unit,
                    'cif' => $pembiayaan->cif,
                    'code_kel' => $pembiayaan->code_kel,
                    'debet' => 0,
                    'type' => '04',
                    'kredit' => $saldo_twm,
                    'userid' => $user_id,
                    'ket' => $ketSimpanan,
                    'reff' => $unit . $timestamp . strtoupper(\Str::random(2)),
                    'cao' => $pembiayaan->cao,
                    'blok' => '2',
                    'kode_transaksi' => $noTransaksi,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                DB::table('simpanan')->insert([
                    'buss_date' => $tanggal,
                    'norek' => $pembiayaan->norek,
                    'unit' => $unit,
                    'cif' => $pembiayaan->cif,
                    'code_kel' => $pembiayaan->code_kel,
                    'debet' => $pembiayaan->bulat,
                    'type' => '04',
                    'kredit' => 0,
                    'userid' => $user_id,
                    'ket' => $ketSimpanan,
                    'reff' => $unit . $timestamp . strtoupper(\Str::random(2)),
                    'cao' => $pembiayaan->cao,
                    'blok' => '2',
                    'kode_transaksi' => $noTransaksi,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                // end insert ke tabel simpanan

                // insert ke tabel rek_loan
                DB::table('rek_loan')->insert([
                    'tgl_realisasi' => $tanggal,
                    'unit' => $unit,
                    'no_anggota' => $pembiayaan->no_anggota,
                    'saldo_kredit' => 0,
                    'cif' => $pembiayaan->cif,
                    'debet' => $pembiayaan->bulat,
                    'tipe' => '04',
                    'ket' => $ketSimpanan,
                    'userid' => $user_id,
                    'status' => 'REALISASI TAGIHAN KELOMPOK',
                    'ao' => $pembiayaan->cao,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // insert ke tabel tunggakan
                DB::table('tunggakan')->insert([
                    'tgl_tunggak' => $tanggal,
                    'norek' => $pembiayaan->norek,
                    'unit' => $unit,
                    'cif' => $pembiayaan->cif,
                    'code_kel' => $pembiayaan->code_kel,
                    'debet' => 0,
                    'type' => '04',
                    'kredit' => $pembiayaan->angsuran,
                    'userid' => $user_id,
                    'ket' => 'TUNGGAKAN TAGIHAN KELOMPOK',
                    'cao' => $pembiayaan->cao,
                    'blok' => '2',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // junal
                $BuatJurnal = [
                    [
                        'unit' => $unit,
                        'kode_transaksi' => $noTransaksi,
                        'kode_rekening' => '1101000', // Kas Unit
                        'tanggal_transaksi' => $tanggal,
                        'jenis_transaksi' => 'Bukti SYSTEM',
                        'keterangan_transaksi' => $keterangan,
                        'debet' => 0,
                        'kredit' => $pembiayaan->bulat,
                        'tanggal_posting' => $tanggal,
                        'keterangan_posting' => '',
                        'id_admin' => $user_id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'unit' => $unit,
                        'kode_transaksi' => $noTransaksi,
                        'kode_rekening' => '1423000', // PMYD-PYD Murabahah Mingguan
                        'tanggal_transaksi' => $tanggal,
                        'jenis_transaksi' => 'Bukti SYSTEM',
                        'keterangan_transaksi' => $keterangan,
                        'debet' => $pembiayaan->bagi_hasil,
                        'kredit' => 0,
                        'tanggal_posting' => $tanggal,
                        'keterangan_posting' => '',
                        'id_admin' => $user_id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'unit' => $unit,
                        'kode_transaksi' => $noTransaksi,
                        'kode_rekening' => '41002', // PM-Murabahah-Kelompok Mingguan
                        'tanggal_transaksi' => $tanggal,
                        'jenis_transaksi' => 'Bukti SYSTEM',
                        'keterangan_transaksi' => $keterangan,
                        'debet' => 0,
                        'kredit' => $pembiayaan->bagi_hasil,
                        'tanggal_posting' => $tanggal,
                        'keterangan_posting' => '',
                        'id_admin' => $user_id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'unit' => $unit,
                        'kode_transaksi' => $noTransaksi,
                        'kode_rekening' => '2101000', // Simpanan Wadiah Kelompok
                        'tanggal_transaksi' => $tanggal,
                        'jenis_transaksi' => 'Bukti SYSTEM',
                        'keterangan_transaksi' => $keterangan,
                        'debet' => $pembiayaan->angsuran,
                        'kredit' => 0,
                        'tanggal_posting' => $tanggal,
                        'keterangan_posting' => '',
                        'id_admin' => $user_id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ];
            
            }
            
            if (!empty($BuatJurnal)) {
                DB::table('tabel_transaksi')->insert($BuatJurnal);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Realisasi berhasil diproses',
                // 'no_transaksi' => $noTransaksi,
                // 'total_rows' => count($BuatJurnal)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses realisasi: ' . $e->getMessage()
            ], 500);
        }
    }
}
