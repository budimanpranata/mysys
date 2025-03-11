<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PembiayaanController extends Controller
{
    public function index()
    {
        $title = 'Master Pembiayaan';
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('admin.master_pembiayaan.index', compact('title', 'menus'));
    }

    public function data()
    {
        try {
            $anggota = DB::table('anggota')
                ->leftJoin('kelompok', 'anggota.kode_kel', '=', 'kelompok.code_kel')
                ->leftJoin('pembiayaan', 'anggota.cif', '=', 'pembiayaan.cif')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('temp_akad_mus')
                        ->whereColumn('temp_akad_mus.no_anggota', 'anggota.no')
                        ->whereColumn('temp_akad_mus.cif', 'anggota.cif')
                        ->whereColumn('temp_akad_mus.unit', 'anggota.unit');
                })
                ->select(
                    'kelompok.nama_kel as nama_kelompok',
                    'anggota.kode_kel as kode_kel',
                    'anggota.no as no_anggota',
                    'anggota.unit as unit_anggota',
                    'anggota.cif as anggota_cif',
                    'anggota.cao as anggota_cao',
                    'anggota.nama as nama_anggota',
                    'anggota.tgl_lahir as tanggal_lahir',
                    'pembiayaan.suffix as suffix',
                    DB::raw('COALESCE(pembiayaan.plafond, 0) as plafond'),
                    DB::raw('COALESCE(pembiayaan.os, 0) as os'),
                    DB::raw('COALESCE(pembiayaan.tenor, 0) as tenor')
                )
                ->latest('anggota.no')
                ->get();

            return datatables()
                ->of($anggota)
                ->addIndexColumn()
                ->addColumn('nama_kelompok', function ($anggota) {
                    return $anggota->nama_kelompok;
                })
                ->addColumn('kode_kel', function ($anggota) {
                    return $anggota->kode_kel;
                })
                ->addColumn('no_anggota', function ($anggota) {
                    return $anggota->no_anggota;
                })
                ->addColumn('cif', function ($anggota) {
                    return $anggota->anggota_cif;
                })
                ->addColumn('nama_anggota', function ($anggota) {
                    return $anggota->nama_anggota;
                })
                ->addColumn('plafond', function ($anggota) {
                    return $anggota->plafond;
                })
                ->addColumn('os', function ($anggota) {
                    return $anggota->os;
                })
                ->addColumn('tenor', function ($anggota) {
                    return $anggota->tenor;
                })
                ->addColumn('aksi', function ($anggota) {
                    $url = route('pembiayaan.add');
                    $cif = $anggota->anggota_cif;
                    $cao = $anggota->anggota_cao;
                    $noAnggota = $anggota->no_anggota;
                    $unitAnggota = $anggota->unit_anggota;
                    $code_kel = $anggota->kode_kel;
                    $nama = $anggota->nama_anggota;
                    $tgl_lahir = $anggota->tanggal_lahir;
                    $suffix = $anggota->suffix;

                    return '<button onclick="addForm(`' . $url . '`, `' . $cif . '`, `' . $noAnggota . '`, `' . $unitAnggota . '`, `' . $cao . '`, `' . $code_kel . '`, `' . $nama . '`, `' . $tgl_lahir . '`, `' . $suffix . '`)" 
                    class="btn btn-sm btn-primary">Edit</button>';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        } catch (\Exception $e) {
            \Log::error('DataTables Error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    public function addPembiayaan(Request $request)
    {
        try {
            $validated = $request->validate([
                'unit' => 'required|string',
                'produk' => 'required|integer',
                'no_rek' => 'required|string',
                'cif' => 'required|string',
                'pengajuan' => 'required|integer',
                'tenor' => 'required|integer',
                'disetujui' => 'required|integer',
                'tgl_wakalah' => 'required|date',
                'tgl_akad' => 'required|date',
                'bidang_usaha' => 'required|string',
                'keterangan_usaha' => 'required|string',
                'id' => 'required|string',
                'param_tanggal' => 'required|date',
                'cao' => 'required|string',
                'code_kel' => 'required|string',
                'nama' => 'required|string',
                'tgl_lahir' => 'required|date',
                'suffix' => 'required|string'
            ]);

            $validated['suffix'] = (int) $validated['suffix'] + 1;
            $validated['suffix'] = (string) $validated['suffix'];

            $existingRecord = DB::table('temp_akad_mus')
                ->where(function ($query) use ($validated) {
                    $query->where('cif', $validated['cif'])
                        ->orWhere('no_anggota', $validated['no_rek']);
                })
                ->select('unit')
                ->first();

            if ($existingRecord) {
                // Check if the existing record has a different unit
                if ($existingRecord->unit !== $validated['unit']) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'NIK / CIF already used in another unit'
                    ], 400);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Record already exist'
                    ], 400);
                }
            }

            $paramRecord = DB::table('param_biaya')
                ->where('pla', $validated['disetujui'])
                ->select('pla', 'margin', 'tab')
                ->first();

            if (!$paramRecord) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The approved amount does not match any allowed values in our system.'
                ], 400);
            }

            $persenMargin = $paramRecord->margin / 100;
            $saldoMargin = $paramRecord->pla * $persenMargin;
            $outStanding = $paramRecord->pla + $saldoMargin;
            $pokokAmount = $paramRecord->pla / $validated['tenor'];
            $ijaroh = $saldoMargin / $validated['tenor'];
            $angsuran = $ijaroh + $pokokAmount;
            $bulatAmount = $paramRecord->tab + $angsuran;

            $wakalahDate = new \DateTime($validated['tgl_wakalah']);
            if ($wakalahDate->format('N') == 5) { // hari ke-5
                return response()->json([
                    'status' => 'error',
                    'message' => 'Friday is not a collection date'
                ], 400);
            }
            $dayOfWeek = $wakalahDate->format('l');

            $birthDate = new \DateTime($validated['tgl_lahir']);
            $today = new \DateTime();
            $age = $today->diff($birthDate)->y;

            $statusUsia = ($age > 50) ? 'DEVIASI' : 'NO';
            $warningMessage = ($age > 50) ? 'Customer age is over 50 years' : null;

            // Get all holidays from database
            $tanggalLibur = DB::table('param_tgl')
                ->pluck('param_tgl')
                ->toArray();

            $tglMurab = Carbon::parse($validated['tgl_wakalah'])->addDays(7);
            $currentDate = (clone $tglMurab)->addDays(7);

            // Generate initial payment dates
            $tglJatuhTempo = [];
            for ($i = 0; $i < $validated['tenor']; $i++) {
                $tglJatuhTempo[] = $currentDate->format('Y-m-d H:i:s');
                $currentDate->addDays(7);
            }

            $adjustedTglJatuhTempo = [];
            foreach ($tglJatuhTempo as $date) {
                $formattedDate = Carbon::parse($date)->format('Y-m-d'); // Convert to YYYY-MM-DD

                while (in_array($formattedDate, $tanggalLibur)) {
                    $date = Carbon::parse(end($adjustedTglJatuhTempo))->addDays(7)->format('Y-m-d H:i:s');
                    $formattedDate = Carbon::parse($date)->format('Y-m-d'); // Reformat to compare again
                }

                $adjustedTglJatuhTempo[] = $date;
            }

            $nextPayment = reset($adjustedTglJatuhTempo);
            $maturityPayment = end($adjustedTglJatuhTempo);

            // Use one database operation for insert
            DB::table('temp_akad_mus')->insert([
                'buss_date' => date('Y-m-d H:i:s', strtotime($validated['param_tanggal'])),
                'code_kel' => $validated['code_kel'],
                'no_anggota' => $validated['no_rek'],
                'cif' => $validated['cif'],
                'nama' => $validated['nama'],
                'deal_type' => '1',
                'suffix' => $validated['suffix'],
                'bagi_hasil' => $saldoMargin,
                'tenor' => $validated['tenor'],
                'plafond' => $validated['disetujui'],
                'os' => $outStanding,
                'saldo_margin' => $saldoMargin,
                'angsuran' => $angsuran,
                'pokok' => $pokokAmount,
                'ijaroh' => $ijaroh,
                'bulat' => $bulatAmount,
                'run_tenor' => '0',
                'ke' => '1',
                'usaha' => $validated['bidang_usaha'],
                'nama_usaha' => $validated['keterangan_usaha'],
                'unit' => $validated['unit'],
                'tgl_wakalah' => date('Y-m-d H:i:s', strtotime($validated['tgl_wakalah'])),
                'tgl_akad' => $validated['tgl_akad'],
                'tgl_murab' => $tglMurab,
                'next_schedule' => $nextPayment,
                'maturity_date' => $maturityPayment,
                'last_payment' => null,
                'hari' => $dayOfWeek,
                'cao' => $validated['cao'],
                'userid' => $validated['id'],
                'status' => 'ANGGOTA',
                'status_usia' => $statusUsia,
                'status_app' => 'APPROVE',
                'gol' => '1',
                'deal_produk' => $validated['produk'],
                'persen_margin' => $persenMargin,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Return appropriate response based on warnings
            if ($warningMessage) {
                return response()->json([
                    'status' => 'warning',
                    'message' => $warningMessage,
                    'data' => 'Pembiayaan successfully added.'
                ], 207);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Pembiayaan successfully added.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.'
            ], 500);
        }
    }
}
