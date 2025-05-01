<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HapusBukuController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $title = 'Hapus Buku';

        return view("admin.hapus_buku.index", compact("menus", "title"));
    }

    public function data(Request $request)
    {
        try {
            $cif = $request->input('cif');
            $unit = $request->input('unit');

            // First get the pembiayaan data
            $data = DB::table('pembiayaan')
                ->where('pembiayaan.cif', $cif)
                ->where('pembiayaan.unit', $unit)
                ->select(
                    'pembiayaan.cif',
                    'pembiayaan.no_anggota',
                    'pembiayaan.nama',
                    'pembiayaan.plafond as debit',
                    'pembiayaan.saldo_margin as kredit',
                    'pembiayaan.status as keterangan',
                    DB::raw('CAST(pembiayaan.run_tenor AS INTEGER) as minggu_ke')
                )
                ->get();

            if ($data->isNotEmpty()) {
                // Get the kredit value from the latest simpanan record for this CIF
                $latestSimpananDebet = DB::table('simpanan')
                    ->where('cif', $cif)
                    ->orderBy('created_at', 'asc')
                    ->latest()
                    ->value('debet');

                // Generate nomor bukti for each row and add simpanan data
                $data = $data->map(function ($item) use ($unit, $latestSimpananDebet) {
                    $item->nomor_bukti = "BS-" . $unit . "-" . Str::random(7);
                    $item->simpanan = $latestSimpananDebet ?? 0;
                    $item->minggu_ke = (int) $item->minggu_ke ?? 0;
                    return $item;
                });
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function jurnal(Request $request)
    {
        try {
            // \Log::info('Starting hapus buku process', ['request' => $request->all()]);

            $validated = $request->validate([
                'nomor_bukti' => 'required|string',
                'tanggal' => 'required|date',
                'cif' => 'required|string',
                'pokok' => 'required|numeric',
                'margin' => 'required|numeric',
                'minggu_ke' => 'required|integer',
                'simpanan' => 'required|numeric',
                'jenis_wo' => 'required|in:NPF,Meninggal Dunia',
                'no_anggota' => 'required|string',
                'userUnit' => 'required|string',
                'userId' => 'required|string',
                'userDate' => 'required|date'
            ]);

            // \Log::info('Validation passed', ['validated_data' => $validated]);

            $pembiayaan = DB::table('pembiayaan')
                ->where('cif', $validated['cif'])
                ->first();

            // \Log::info('Found pembiayaan record', ['pembiayaan' => $pembiayaan]);

            // kalkulasi jumlah pokok berdasarkan jenis wo
            $finalPokok = $validated['pokok'];
            $runTenor = (int) $pembiayaan->run_tenor;

            // \Log::info('Initial calculation values', [
            //     'initial_pokok' => $finalPokok,
            //     'run_tenor' => $runTenor,
            //     'jenis_wo' => $validated['jenis_wo']
            // ]);

            if ($validated['jenis_wo'] === 'NPF') {
                // kalo run_tenor > 13
                if ($runTenor <= 13) {
                    throw new \Exception('Run tenor must be greater than 13 for NPF type');
                }
                $finalPokok = $validated['pokok'] - $validated['simpanan'];
            } elseif ($validated['jenis_wo'] === 'Meninggal Dunia') {
                // 50% tenor
                $halfTenor = round($pembiayaan->tenor / 2);
                if ($runTenor < $halfTenor) {
                    $finalPokok = $validated['pokok'] - $validated['simpanan'];
                }
            }

            $totalAmount = $finalPokok + $validated['margin'];
            // \Log::info('Final amount calculated', [
            //     'final_pokok' => $finalPokok,
            //     'margin' => $validated['margin'],
            //     'total_amount' => $totalAmount
            // ]);

            $formattedDate = date('Y-m-d H:i:s', strtotime($validated['userDate']));

            DB::beginTransaction();
            try {
                // \Log::info('Starting database transaction');

                $transactionRecords = [
                    [
                        'unit' => $validated['userUnit'],
                        'kode_transaksi' => $validated['nomor_bukti'],
                        'kode_rekening' => $validated['no_anggota'],
                        'tanggal_transaksi' => $formattedDate,
                        'jenis_transaksi' => 'bukti SYSTEM',
                        'keterangan_transaksi' => "Pendapatan pokok AN {$pembiayaan->nama}",
                        'debet' => $finalPokok,
                        'kredit' => 0,
                        'tanggal_posting' => $validated['tanggal'],
                        'keterangan_posting' => "Pendapatan pokok AN {$pembiayaan->nama}",
                        'id_admin' => $validated['userId']
                    ],
                    [
                        'unit' => $validated['userUnit'],
                        'kode_transaksi' => $validated['nomor_bukti'],
                        'kode_rekening' => $validated['no_anggota'],
                        'tanggal_transaksi' => $formattedDate,
                        'jenis_transaksi' => 'bukti SYSTEM',
                        'keterangan_transaksi' => "Piutang pokok AN {$pembiayaan->nama}",
                        'debet' => 0,
                        'kredit' => $finalPokok,
                        'tanggal_posting' => $validated['tanggal'],
                        'keterangan_posting' => "Piutang pokok AN {$pembiayaan->nama}",
                        'id_admin' => $validated['userId']
                    ],
                    [
                        'unit' => $validated['userUnit'],
                        'kode_transaksi' => $validated['nomor_bukti'],
                        'kode_rekening' => $validated['no_anggota'],
                        'tanggal_transaksi' => $formattedDate,
                        'jenis_transaksi' => 'bukti SYSTEM',
                        'keterangan_transaksi' => "Pendapatan margin AN {$pembiayaan->nama}",
                        'debet' => $validated['margin'],
                        'kredit' => 0,
                        'tanggal_posting' => $validated['tanggal'],
                        'keterangan_posting' => "Pendapatan margin AN {$pembiayaan->nama}",
                        'id_admin' => $validated['userId']
                    ],
                    [
                        'unit' => $validated['userUnit'],
                        'kode_transaksi' => $validated['nomor_bukti'],
                        'kode_rekening' => $validated['no_anggota'],
                        'tanggal_transaksi' => $formattedDate,
                        'jenis_transaksi' => 'bukti SYSTEM',
                        'keterangan_transaksi' => "Piutang margin AN {$pembiayaan->nama}",
                        'debet' => 0,
                        'kredit' => $validated['margin'],
                        'tanggal_posting' => $validated['tanggal'],
                        'keterangan_posting' => "Piutang margin AN {$pembiayaan->nama}",
                        'id_admin' => $validated['userId']
                    ]
                ];

                DB::table('tabel_transaksi')->insert($transactionRecords);
                // \Log::info('Inserted transaction records', ['count' => count($transactionRecords)]);

                // Insert into simpanan
                $simpananRecords = [
                    [
                        'reff' => 'WO-' . Str::random(10),
                        'buss_date' => $formattedDate,
                        'norek' => $validated['no_anggota'],
                        'unit' => $validated['userUnit'],
                        'cif' => $validated['cif'],
                        'code_kel' => $pembiayaan->code_kel,
                        'debet' => 0,
                        'kredit' => $totalAmount,
                        'type' => 'WO',
                        'ket' => "Hapus buku {$validated['jenis_wo']} AN {$pembiayaan->nama}",
                        'cao' => $pembiayaan->cao,
                        'tgl_input' => $validated['tanggal'],
                        'kode_transaksi' => $validated['nomor_bukti']
                    ],
                    [
                        'reff' => 'WO-' . Str::random(10),
                        'buss_date' => $formattedDate,
                        'norek' => $validated['no_anggota'],
                        'unit' => $validated['userUnit'],
                        'cif' => $validated['cif'],
                        'code_kel' => $pembiayaan->code_kel,
                        'debet' => $totalAmount,
                        'kredit' => 0,
                        'type' => 'WO',
                        'ket' => "Hapus buku {$validated['jenis_wo']} AN {$pembiayaan->nama}",
                        'cao' => $pembiayaan->cao,
                        'tgl_input' => $validated['tanggal'],
                        'kode_transaksi' => $validated['nomor_bukti']
                    ]
                ];

                DB::table('simpanan')->insert($simpananRecords);
                // \Log::info('Inserted simpanan records', ['count' => count($simpananRecords)]);

                // Insert into rek_loan
                DB::table('rek_loan')->insert([
                    'tgl_realisasi' => $formattedDate,
                    'unit' => $validated['userUnit'],
                    'no_anggota' => $validated['no_anggota'],
                    'saldo_kredit' => $totalAmount,
                    'debet' => 0,
                    'tipe' => 'WO',
                    'ket' => "Hapus buku {$validated['jenis_wo']} AN {$pembiayaan->nama}",
                    'userid' => $validated['userId'],
                    'status' => "HAPUS BUKU {$validated['jenis_wo']}",
                    'cif' => $validated['cif'],
                    'ao' => $pembiayaan->cao
                ]);

                // Insert into pembiayaan_wos
                $pembiayaanData = (array) $pembiayaan;
                $pembiayaanData['os'] = $totalAmount;
                DB::table('pembiayaan_wos')->insert($pembiayaanData);
                // \Log::info('Inserted pembiayaan_wos record');

                // Delete from pembiayaan
                DB::table('pembiayaan')
                    ->where('cif', $validated['cif'])
                    ->delete();

                DB::commit();
                // \Log::info('Transaction committed successfully');

                return response()->json([
                    'success' => true,
                    'message' => 'Hapus buku processed successfully'
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                // \Log::error('Error in transaction, rolling back', [
                //     'error' => $e->getMessage(),
                //     'trace' => $e->getTraceAsString()
                // ]);
                throw $e;
            }
        } catch (\Exception $e) {
            // \Log::error('Error processing hapus buku', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString()
            // ]);
            return response()->json([
                'success' => false,
                'message' => 'Error processing hapus buku: ' . $e->getMessage()
            ], 500);
        }
    }
}
