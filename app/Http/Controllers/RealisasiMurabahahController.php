<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Menu;

class RealisasiMurabahahController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $title = 'Realisasi Murabahah';

        return view("admin.realisasi_murabahah.index", compact("menus", "title"));
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'kode_kel' => 'required|string',
            'tgl_akad' => 'required|date',
            'unit' => 'required|string'
        ]);

        $records = DB::table('temp_akad_mus')
            ->leftJoin('kelompok', 'temp_akad_mus.code_kel', '=', 'kelompok.code_kel')
            ->where('temp_akad_mus.code_kel', $validated['kode_kel'])
            ->where('temp_akad_mus.tgl_akad', $validated['tgl_akad'])
            ->where('temp_akad_mus.unit', $validated['unit'])
            ->where('temp_akad_mus.status_app', 'WAKALAH')
            ->select(
                'temp_akad_mus.*',
                'kelompok.nama_kel AS nama_kelompok'
            )
            ->get();

        return response()->json($records);
    }

    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'cifs' => 'required|array',
            'kode_kel' => 'required|string',
            'tgl_akad' => 'required|date',
            'unit' => 'required|string',
            'id' => 'required|string',
            'param_tanggal' => 'required|date',
        ]);

        $uniqueId = Str::random(7);
        $failedCifs = [];

        try {
            DB::beginTransaction();

            // ngambil dlu dr tabel temp_akad_mus
            $akadRecords = DB::table('temp_akad_mus')
                ->whereIn('cif', $validated['cifs'])
                ->get();

            // ngambil list tanggal merah
            $tanggalLibur = DB::table('param_tgl')
                ->pluck('param_tgl')
                ->toArray();

            foreach ($akadRecords as $akad) {
                try {
                    $existingRecord = DB::table('pembiayaan')
                        ->where('cif', $akad->cif)
                        ->first();

                    if ($existingRecord) {
                        if ($existingRecord->os > 0) {
                            // skip iterasi loop yg skrg
                            continue;
                        } else {
                            // update record yg udah ada dgn value terbaru dr temp akad mus
                            DB::table('pembiayaan')
                                ->where('cif', $existingRecord->cif) // Assuming 'id' is the primary key
                                ->update(array_merge((array) $akad, ['status_app' => 'MURAB']));
                        }
                    } else {
                        // klo gaada record di pembiayaan, lgsg di insert aja
                        DB::table('pembiayaan')->insert(array_merge((array) $akad, ['status_app' => 'MURAB']));
                    }

                    $tglJatuhTempo = [];
                    // buat array untuk tanggal jatuh tempo
                    $currentDate = Carbon::now()->addDays(7);
                    for ($i = 0; $i < $akad->tenor; $i++) {
                        $tglJatuhTempo[] = $currentDate->format('Y-m-d H:i:s');
                        $currentDate->addDays(7);
                    }

                    $adjustedTglJatuhTempo = [];
                    // cek apakah ada yg tabrakan dengan tanggal merah
                    foreach ($tglJatuhTempo as $date) {
                        $formattedDate = Carbon::parse($date)->format('Y-m-d'); // Convert to YYYY-MM-DD

                        while (in_array($formattedDate, $tanggalLibur)) {
                            $date = Carbon::parse(end($adjustedTglJatuhTempo))->addDays(7)->format('Y-m-d H:i:s');
                            $formattedDate = Carbon::parse($date)->format('Y-m-d'); // Reformat to compare again
                        }

                        $adjustedTglJatuhTempo[] = $date;
                    }

                    $transaksiData = [
                        [
                            'unit' => $validated['unit'],
                            'kode_transaksi' => "BS-{$validated['unit']}-{$uniqueId}",
                            'kode_rekening' => 1481000,
                            'tanggal_transaksi' => now(),
                            'jenis_transaksi' => 'bukti SYSTEM',
                            'keterangan_transaksi' => '',
                            'debet' => $akad->plafond,
                            'kredit' => 0,
                            'tanggal_posting' => $validated['tgl_akad'],
                            'keterangan_posting' => "Persediaan Murabahah AN {$akad->nama}",
                            'id_admin' => $validated['id']
                        ],
                        [
                            'unit' => $validated['unit'],
                            'kode_transaksi' => "BS-{$validated['unit']}-{$uniqueId}",
                            'kode_rekening' => 1431000,
                            'tanggal_transaksi' => now(),
                            'jenis_transaksi' => 'bukti SYSTEM',
                            'keterangan_transaksi' => '',
                            'debet' => 0,
                            'kredit' => $akad->plafond,
                            'tanggal_posting' => $validated['tgl_akad'],
                            'keterangan_posting' => "Piutang Wakalah AN {$akad->nama}",
                            'id_admin' => $validated['id']
                        ],
                        [
                            'unit' => $validated['unit'],
                            'kode_transaksi' => "BS-{$validated['unit']}-{$uniqueId}",
                            'kode_rekening' => 1413000,
                            'tanggal_transaksi' => now(),
                            'jenis_transaksi' => 'bukti SYSTEM',
                            'keterangan_transaksi' => '',
                            'debet' => $akad->plafond + $akad->saldo_margin,
                            'kredit' => 0,
                            'tanggal_posting' => $validated['tgl_akad'],
                            'keterangan_posting' => "Piutang Murabahah Mingguan AN {$akad->nama}",
                            'id_admin' => $validated['id']
                        ],
                        [
                            'unit' => $validated['unit'],
                            'kode_transaksi' => "BS-{$validated['unit']}-{$uniqueId}",
                            'kode_rekening' => 1481000,
                            'tanggal_transaksi' => now(),
                            'jenis_transaksi' => 'bukti SYSTEM',
                            'keterangan_transaksi' => '',
                            'debet' => 0,
                            'kredit' => $akad->plafond,
                            'tanggal_posting' => $validated['tgl_akad'],
                            'keterangan_posting' => "Persediaan Murabahah AN {$akad->nama}",
                            'id_admin' => $validated['id']
                        ],
                        [
                            'unit' => $validated['unit'],
                            'kode_transaksi' => "BS-{$validated['unit']}-{$uniqueId}",
                            'kode_rekening' => 1423000,
                            'tanggal_transaksi' => now(),
                            'jenis_transaksi' => 'bukti SYSTEM',
                            'keterangan_transaksi' => '',
                            'debet' => 0,
                            'kredit' => $akad->saldo_margin,
                            'tanggal_posting' => $validated['tgl_akad'],
                            'keterangan_posting' => "PMYD Murabahah Mingguan AN {$akad->nama}",
                            'id_admin' => $validated['id']
                        ]
                    ];
                    DB::table('tabel_transaksi')->insert($transaksiData);

                    DB::table('rek_loan')->insert([
                        'tgl_realisasi' => '',
                        'unit' => $validated['unit'],
                        'no_anggota' => $akad->no_anggota,
                        'saldo_kredit' => $akad->os,
                        'debet' => 0,
                        'tipe' => 'L001',
                        'ket' => "Realisasi Murabahah AN {$akad->nama}",
                        'userid' => $validated['id'],
                        'status' => 'REALISASI MURABAHAH',
                        'cif' => $akad->cif,
                        'ao' => $akad->cao
                    ]);

                    foreach ($adjustedTglJatuhTempo as $date) {
                        DB::table('pembiayaan_detail')->insert([
                            'id' => '',
                            'id_pinjam' => '',
                            'cicilan' => $akad->run_tenor,
                            'angsuran_pokok' => $akad->pokok,
                            'margin' => $akad->ijaroh,
                            'tgl_jatuh_tempo' => $date,
                            'tgl_bayar' => '',
                            'jumlah_bayar' => $akad->bulat,
                            'keterangan' => '',
                            'cif' => $akad->cif,
                            'unit' => $validated['unit'],
                            'ao' => $akad->cao,
                            'code_kel' => $validated['kode_kel']
                        ]);
                    }

                    DB::table('jurnal_umum')->insert([
                        'nomor_jurnal' => '1',
                        'kode_transaksi' => "BS-{$validated['unit']}-{$uniqueId}",
                        'tanggal_selesai' => '',
                        'unit' => $validated['unit']
                    ]);

                    $simpananData = [
                        [
                            'buss_date' => $validated['param_tanggal'],
                            'no_rek' => $akad->no_anggota,
                            'unit' => $validated['unit'],
                            'cif' => $akad->cif,
                            'code_kel' => $validated['kode_kel'],
                            'debet' => $akad->plafond,
                            'type' => '01',
                            'kredit' => 0,
                            'userId' => $validated['id'],
                            'ket' => "Realisasi Murabahah AN {$akad->nama}",
                            'cao' => $akad->cao,
                            'blok' => 0,
                            'tgl_input' => today(),
                            'kode_transaksi' => "BS-{$validated['unit']}-{$uniqueId}",
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        [
                            'buss_date' => $validated['param_tanggal'],
                            'no_rek' => $akad->no_anggota,
                            'unit' => $validated['unit'],
                            'cif' => $akad->cif,
                            'code_kel' => $validated['kode_kel'],
                            'debet' => 0,
                            'type' => '01',
                            'kredit' => $akad->plafond,
                            'userId' => $validated['id'],
                            'ket' => "Realisasi Murabahah AN {$akad->nama}",
                            'cao' => $akad->cao,
                            'blok' => 0,
                            'tgl_input' => today(),
                            'kode_transaksi' => "BS-{$validated['unit']}-{$uniqueId}",
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    ];
                    DB::table('simpanan')->insert($simpananData);

                    DB::table('temp_akad_mus')
                        ->where('cif', $akad->cif)
                        ->update([
                            'deleted_at' => DB::raw('NOW()')
                        ]);

                } catch (\Exception $e) {
                    // Catch error for this iteration and store failed CIF
                    $failedCifs[] = ['cif' => $akad->cif, 'nama' => $akad->nama];
                }
            }

            // If all CIFs failed
            if (count($failedCifs) === count($akadRecords)) {
                DB::rollBack();
                return response()->json(['error' => 'Everything failed'], 500);
            }

            // If some CIFs failed
            if (!empty($failedCifs)) {
                DB::commit();
                return response()->json([
                    'message' => 'Partial success',
                    'failed_cifs' => $failedCifs
                ], 207);
            }

            DB::commit();
            return response()->json(['message' => 'Everything was successful']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
