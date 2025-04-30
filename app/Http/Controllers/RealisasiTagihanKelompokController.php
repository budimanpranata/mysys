<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\pembiayaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Str;
use App\Models\simpanan;
use App\Models\temp_akad_mus;

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

        $data = DB::table('temp_akad_mus')
            ->join('anggota', 'temp_akad_mus.no_anggota', '=', 'anggota.no') // Relasi antar tabel
            ->leftjoin('simpanan', 'temp_akad_mus.cif', '=', 'simpanan.cif')
            ->select(
                'temp_akad_mus.*',
                'anggota.*',
                'anggota.nama as nama_anggota',
                DB::raw('((temp_akad_mus.bulat-temp_akad_mus.angsuran) * temp_akad_mus.run_tenor) as twm'),
                DB::raw('(((temp_akad_mus.bulat-temp_akad_mus.angsuran) - 2500) * temp_akad_mus.run_tenor) as saldo_twm'),
                DB::raw('((temp_akad_mus.bulat - temp_akad_mus.angsuran) * temp_akad_mus.run_tenor - 
                ((temp_akad_mus.bulat - temp_akad_mus.angsuran - 2500) * temp_akad_mus.run_tenor)) as sisa_twm')
            )
            ->where('temp_akad_mus.code_kel', $code_kel)
            ->groupBy('temp_akad_mus.cif')
            ->get();

        // dd($data);

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
            $entriesJurnal = [];
            
            foreach ($items as $item) {
                DB::table('temp_akad_mus')
                ->where('cif', $item['cif'])
                ->update([
                    'run_tenor' => DB::raw('run_tenor + 1')
                ]);

                $temp_akad_mus = DB::table('temp_akad_mus')
                ->join('anggota', 'temp_akad_mus.cif', '=', 'anggota.cif')
                ->where('temp_akad_mus.cif', $item['cif'])
                ->select(
                    'temp_akad_mus.*',
                    'anggota.norek',
                )
                ->first();

                $twm = $temp_akad_mus->bulat - $temp_akad_mus->angsuran;
                $saldo_twm = $temp_akad_mus->bulat - $temp_akad_mus->angsuran - 2500;

                $noTransaksi = 'BU/' . $temp_akad_mus->unit . strtoupper(\Str::random(8));
                $keterangan = 'Setor Tagihan ' . $temp_akad_mus->nama . ' ' . $temp_akad_mus->cif;
                $ketSimpanan = 'Setoran an ' . $temp_akad_mus->nama;


                $timestamp = date('YmdHis');

                // $reff = $unit . $timestamp . strtoupper(\Str::random(2));

                DB::table('simpanan')->insert([
                    'buss_date' => $tanggal,
                    'norek' => $temp_akad_mus->norek,
                    'unit' => $unit,
                    'cif' => $temp_akad_mus->cif,
                    'code_kel' => $temp_akad_mus->code_kel,
                    'debet' => 0,
                    'type' => '04',
                    'kredit' => $temp_akad_mus->angsuran,
                    'userid' => $user_id,
                    'ket' => $ketSimpanan,
                    'reff' => $unit . $timestamp . strtoupper(\Str::random(2)),
                    'cao' => $temp_akad_mus->cao,
                    'blok' => '2',
                    'kode_transaksi' => $noTransaksi,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                DB::table('simpanan')->insert([
                    'buss_date' => $tanggal,
                    'norek' => $temp_akad_mus->norek,
                    'unit' => $unit,
                    'cif' => $temp_akad_mus->cif,
                    'code_kel' => $temp_akad_mus->code_kel,
                    'debet' => 0,
                    'type' => '04',
                    'kredit' => $twm,
                    'userid' => $user_id,
                    'ket' => $ketSimpanan,
                    'reff' => $unit . $timestamp . strtoupper(\Str::random(2)),
                    'cao' => $temp_akad_mus->cao,
                    'blok' => '2',
                    'kode_transaksi' => $noTransaksi,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                DB::table('simpanan')->insert([
                    'buss_date' => $tanggal,
                    'norek' => $temp_akad_mus->norek,
                    'unit' => $unit,
                    'cif' => $temp_akad_mus->cif,
                    'code_kel' => $temp_akad_mus->code_kel,
                    'debet' => 0,
                    'type' => '04',
                    'kredit' => $saldo_twm,
                    'userid' => $user_id,
                    'ket' => $ketSimpanan,
                    'reff' => $unit . $timestamp . strtoupper(\Str::random(2)),
                    'cao' => $temp_akad_mus->cao,
                    'blok' => '2',
                    'kode_transaksi' => $noTransaksi,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                DB::table('simpanan')->insert([
                    'buss_date' => $tanggal,
                    'norek' => $temp_akad_mus->norek,
                    'unit' => $unit,
                    'cif' => $temp_akad_mus->cif,
                    'code_kel' => $temp_akad_mus->code_kel,
                    'debet' => $temp_akad_mus->bulat,
                    'type' => '04',
                    'kredit' => 0,
                    'userid' => $user_id,
                    'ket' => $ketSimpanan,
                    'reff' => $unit . $timestamp . strtoupper(\Str::random(2)),
                    'cao' => $temp_akad_mus->cao,
                    'blok' => '2',
                    'kode_transaksi' => $noTransaksi,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                $entriesJurnal[] = [
                    'unit' => $unit,
                    'kode_transaksi' => $noTransaksi,
                    'kode_rekening' => '1413000', // Piutang Murabahah Mingguan
                    'tanggal_transaksi' => $tanggal,
                    'jenis_transaksi' => 'Bukti SYSTEM',
                    'keterangan_transaksi' => $keterangan,
                    'debet' => 0,
                    'kredit' => $temp_akad_mus->angsuran,
                    'tanggal_posting' => $tanggal,
                    'keterangan_posting' => '',
                    'id_admin' => $user_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                $entriesJurnal[] = [
                    'unit' => $unit,
                    'kode_transaksi' => $noTransaksi,
                    'kode_rekening' => '1423000', // PMYD-PYD Murabahah Mingguan
                    'tanggal_transaksi' => $tanggal,
                    'jenis_transaksi' => 'Bukti SYSTEM',
                    'keterangan_transaksi' => $keterangan,
                    'debet' => $temp_akad_mus->bagi_hasil,
                    'kredit' => 0,
                    'tanggal_posting' => $tanggal,
                    'keterangan_posting' => '',
                    'id_admin' => $user_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                $entriesJurnal[] = [
                    'unit' => $unit,
                    'kode_transaksi' => $noTransaksi,
                    'kode_rekening' => '41002', // PM-Murabahah-Kelompok Mingguan
                    'tanggal_transaksi' => $tanggal,
                    'jenis_transaksi' => 'Bukti SYSTEM',
                    'keterangan_transaksi' => $keterangan,
                    'debet' => 0,
                    'kredit' => $temp_akad_mus->bagi_hasil,
                    'tanggal_posting' => $tanggal,
                    'keterangan_posting' => '',
                    'id_admin' => $user_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                $entriesJurnal[] = [
                    'unit' => $unit,
                    'kode_transaksi' => $noTransaksi,
                    'kode_rekening' => '2101000', // Simpanan Wadiah Kelompok
                    'tanggal_transaksi' => $tanggal,
                    'jenis_transaksi' => 'Bukti SYSTEM',
                    'keterangan_transaksi' => $keterangan,
                    'debet' => $temp_akad_mus->angsuran,
                    'kredit' => 0,
                    'tanggal_posting' => $tanggal,
                    'keterangan_posting' => '',
                    'id_admin' => $user_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            
            }
            
            if (!empty($entriesJurnal)) {
                DB::table('tabel_transaksi')->insert($entriesJurnal);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Realisasi berhasil diproses',
                'no_transaksi' => $noTransaksi,
                'total_rows' => count($entriesJurnal)
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
