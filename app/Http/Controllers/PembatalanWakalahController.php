<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PembatalanWakalahController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $title = 'Pembatalan Wakalah';

        return view("admin.pembatalan_wakalah.index", compact("menus", "title"));
    }

    public function data()
    {
        try {
            $wakalahData = DB::table('temp_akad_mus')
                ->leftJoin('anggota', 'temp_akad_mus.cif', '=', 'anggota.cif')
                ->leftJoin('kelompok', 'temp_akad_mus.code_kel', '=', 'kelompok.code_kel')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('temp_akad_mus')
                        ->whereColumn('temp_akad_mus.status_app', 'BATAL');
                })
                ->select(
                    'temp_akad_mus.cif',
                    'kelompok.nama_kel as nama_kelompok',
                    'anggota.nama',
                    'temp_akad_mus.plafond as pembiayaan',
                    'temp_akad_mus.persen_margin as margin',
                    'temp_akad_mus.tgl_murab as tgl_murabahah',
                    'temp_akad_mus.maturity_date as tgl_jatuh_tempo'
                )
                ->get();

            return datatables()
                ->of($wakalahData)
                ->addIndexColumn()
                ->addColumn('pilih', function ($data) {
                    return '<div class="form-check">
                              <input class="form-check-input select-row" type="checkbox" value="' . $data->cif . '" data-id="' . $data->cif . '">
                            </div>';
                })
                ->addColumn('nama_kelompok', function ($data) {
                    return $data->nama_kelompok;
                })
                ->addColumn('nama', function ($data) {
                    return $data->nama;
                })
                ->addColumn('pembiayaan', function ($data) {
                    return 'Rp. ' . number_format($data->pembiayaan, 0, ',', '.');
                })
                ->addColumn('margin', function ($data) {
                    return number_format($data->margin * 100, 2) . '%';
                })
                ->addColumn('tgl_murabahah', function ($data) {
                    return $data->tgl_murabahah ? date('m-d-Y', strtotime($data->tgl_murabahah)) : '';
                })
                ->addColumn('tgl_jatuh_tempo', function ($data) {
                    return $data->tgl_jatuh_tempo ? date('m-d-Y', strtotime($data->tgl_jatuh_tempo)) : '';
                })
                ->rawColumns(['pilih'])
                ->make(true);
        } catch (\Exception $e) {
            \Log::error('DataTables Error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    public function realisasi(Request $request)
    {
        // Validate request
        $request->validate([
            'cifs' => 'required|array',
            'cifs.*' => 'string',
            'param_tanggal' => 'required|date',
            'id' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->cifs as $cif) {
                // Update status_app to BATAL
                DB::table('temp_akad_mus')
                    ->where('cif', $cif)
                    ->update(['status_app' => 'BATAL']);

                // Get the record details
                $record = DB::table('temp_akad_mus')
                    ->where('cif', $cif)
                    ->first();

                if ($record) {
                    // Insert into jurnal_umum
                    DB::table('jurnal_umum')->insert([
                        'kode_transaksi' => "BS-{$record->unit}-" . Str::random(7),
                        'tanggal_selesai' => $request->param_tanggal,
                        'unit' => $record->unit
                    ]);

                    // Insert into tabel_transaksi
                    $transaksiData = [
                        [
                            'unit' => $record->unit,
                            'kode_transaksi' => "BS-{$record->unit}-" . Str::random(7),
                            'kode_rekening' => 1481000,
                            'tanggal_transaksi' => date('Y-m-d H:i:s', strtotime($request->param_tanggal)),
                            'jenis_transaksi' => 'bukti SYSTEM',
                            'keterangan_transaksi' => "Persediaan Murabahah AN {$record->nama}",
                            'debet' => $record->plafond,
                            'kredit' => 0,
                            'tanggal_posting' => date('Y-m-d'),
                            'keterangan_posting' => '',
                            'id_admin' => $request->id
                        ],
                        [
                            'unit' => $record->unit,
                            'kode_transaksi' => "BS-{$record->unit}-" . Str::random(7),
                            'kode_rekening' => 1431000,
                            'tanggal_transaksi' => date('Y-m-d H:i:s', strtotime($request->param_tanggal)),
                            'jenis_transaksi' => 'bukti SYSTEM',
                            'keterangan_transaksi' => "Piutang Wakalah AN {$record->nama}",
                            'debet' => 0,
                            'kredit' => $record->plafond,
                            'tanggal_posting' => date('Y-m-d'),
                            'keterangan_posting' => '',
                            'id_admin' => $request->id
                        ]
                    ];
                    DB::table('tabel_transaksi')->insert($transaksiData);
                }
            }

            DB::commit();

            \Log::info('Realisasi Pembatalan Wakalah', [
                'cifs' => $request->cifs,
                'param_tanggal' => $request->param_tanggal,
                'id' => $request->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembatalan Wakalah berhasil direalisasikan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in realisasi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
