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

    public function data(Request $request)
    {
        try {
            $kodeKelompok = $request->get('kode_kelompok');

            if (!$kodeKelompok) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kode Kelompok is required'
                ], 400);
            }

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
                ->where('kelompok.code_kel', $kodeKelompok)
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

            return response()->json([
                'status' => 'success',
                'data' => $anggota
            ]);

        } catch (\Exception $e) {
            Log::error('Data Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function edit($cif)
    {
        $title = 'Edit Pembiayaan';
        $menus = Menu::whereNull('parent_id')
            ->with('children')
            ->orderBy('order')
            ->get();

        $pembiayaan = DB::table('anggota')
            ->leftJoin('kelompok', 'anggota.kode_kel', '=', 'kelompok.code_kel')
            ->leftJoin('pembiayaan', 'anggota.cif', '=', 'pembiayaan.cif')
            ->leftJoin('anggota_details', 'anggota.no', '=', 'anggota_details.no_anggota')
            ->where('anggota.cif', $cif)
            ->select(
                'kelompok.nama_kel as nama_kelompok',
                'anggota.kode_kel',
                'anggota.no as no_anggota',
                'anggota.unit as unit_anggota',
                'anggota.cif as anggota_cif',
                'anggota.cao as anggota_cao',
                'anggota.nama as nama_anggota',
                'anggota.tgl_lahir as tanggal_lahir',
                'anggota.ktp as ktp',
                'anggota.alamat as alamat',
                'anggota.rtrw as rtrw',
                'anggota.desa as desa',
                'anggota.kecamatan as kecamatan',
                'anggota.kota as kota',
                'anggota.kode_pos as kode_pos',
                'anggota.no_hp as no_hp',
                'anggota.hp_pasangan as hp_pasangan',
                'anggota.status_menikah as status_menikah',
                'anggota.agama as agama',
                'anggota.pendidikan as pendidikan',
                'anggota.kewarganegaraan as kewarganegaraan',
                'anggota.waris as waris',
                'anggota.pekerjaan_pasangan as pekerjaan_pasangan',
                'anggota.ibu_kandung as ibu_kandung',
                'anggota.tempat_lahir as tempat_lahir',
                'anggota_details.alamat_domisili as alamat_domisili',
                'anggota_details.rtrw_domisili as rtrw_domisili',
                'anggota_details.desa_domisili as desa_domisili',
                'anggota_details.kecamatan_domisili as kecamatan_domisili',
                'anggota_details.kota_domisili as kota_domisili',
                'anggota_details.kode_pos_domisili as kode_pos_domisili',
                'pembiayaan.plafond',
                'pembiayaan.os',
                'pembiayaan.tenor',
                'pembiayaan.suffix',
                'pembiayaan.deal_produk'
            )
            ->first();

        if (!$pembiayaan) {
            return redirect()
                ->route('pembiayaan.index')
                ->with('error', 'Data pembiayaan tidak ditemukan');
        }

        // ============================
        // LOGIC PEMBIAYAAN LANJUTAN
        // ============================
        $omzetMusyarakah = null;

        if ($pembiayaan->os > 0) {
            // ambil omzet TERKECIL berdasarkan CIF
            $omzetMusyarakah = DB::table('omzet')
                ->where('cif', $cif)
                ->min('nominal');
        }

        return view('admin.master_pembiayaan.edit', compact('title', 'menus', 'pembiayaan', 'omzetMusyarakah'));
    }


    public function addPembiayaan(Request $request)
    {
        $validated = $request->validate([
            'unit' => 'required|string',
            'jenis_pembiayaan' => 'required|integer',
            'no_rek' => 'required|string',
            'cif' => 'required|string',
            'pengajuan' => 'required|integer',
            'tenor' => 'required|integer',
            'disetujui' => 'required|integer',
            'tgl_wakalah' => 'required|date',
            'tgl_akad' => 'required|date',
            'bidang_usaha' => 'required|string',
            'keterangan_usaha' => 'required|string',
            'id' => 'required|integer',
            'param_tanggal' => 'required|date',
            'cao' => 'required|string',
            'kode_kel' => 'required|string',
            'nama' => 'required|string',
            'tgl_lahir' => 'required|date',
            'omzet' => 'nullable|numeric|min:1'
        ]);

        try {

            // hitung suffix
            $lastSuffix = DB::table('temp_akad_mus')
                ->where('cif', $validated['cif'])
                ->max('suffix');

            $suffix = $lastSuffix ? ((int)$lastSuffix + 1) : 1;

            $existingRecord = DB::table('temp_akad_mus')
                ->where(function ($q) use ($validated) {
                    $q->where('cif', $validated['cif'])
                    ->orWhere('no_anggota', $validated['no_rek']);
                })
                ->select('unit')
                ->first();

            if ($existingRecord) {
                return response()->json([
                    'status' => 'error',
                    'message' => $existingRecord->unit !== $validated['unit']
                        ? 'NIK / CIF already used in another unit'
                        : 'Record already exist'
                ], 400);
            }

            $param = DB::table('param_biaya')
                ->where('pla', (int) $validated['disetujui'])
                ->where('jw', (int) $validated['tenor'])
                ->first();

            if (!$param) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Approved amount & tenor not found in param biaya'
                ], 400);
            }

            $persenMargin = $param->margin / 100;
            $saldoMargin = $param->pla * $persenMargin;
            $outStanding = $param->pla + $saldoMargin;
            $pokok = $param->pla / $validated['tenor'];
            $ijaroh = $saldoMargin / $validated['tenor'];
            $angsuran = $pokok + $ijaroh;
            $bulat = $param->tab + $angsuran;

            // ================================
            // JIKA AKAD MUSYARAKAH
            // ================================
            if ((int) $validated['jenis_pembiayaan'] === 2) {

                if (empty($validated['omzet'])) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Omzet wajib diisi untuk pembiayaan Musyarakah'
                    ], 400);
                }

                $omzet = (float) $validated['omzet'];

                if ($omzet <= 0) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Omzet tidak valid'
                    ], 400);
                }

                // cek pembiayaan lanjutan
                $isPembiayaanLanjutan = DB::table('pembiayaan')
                    ->where('cif', $validated['cif'])
                    ->where('os', '>', 0)
                    ->exists();

                $basisOmzet = $isPembiayaanLanjutan ? $omzet : ($omzet / 2);

                if ($basisOmzet <= 0) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Basis omzet tidak valid'
                    ], 400);
                }

                // hitung persen margin musyarokah
                $persenMarginMusyarokah = round(($ijaroh / $basisOmzet) * 100, 2);

                // simpan data omzet
                if (!$isPembiayaanLanjutan) {
                    DB::table('omzet')->insert([
                        'tanggal' => now()->toDateString(),
                        'cif' => $validated['cif'],
                        'nominal' => $omzet,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }


            $wakalahDate = Carbon::parse($validated['tgl_wakalah']);
            if ($wakalahDate->isFriday()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jumat bukan hari pengumpulan'
                ], 400);
            }

            $dayOfWeek = $wakalahDate->locale('id')->isoFormat('dddd');

            // validasi usia
            $age = Carbon::parse($validated['tgl_lahir'])->age;
            $statusUsia = $age > 50 ? 'DEVIASI' : 'NO';

            // ambil tanggal libur
            $tanggalLibur = DB::table('param_tgl')->pluck('param_tgl')->toArray();

            $tglMurab = $wakalahDate->copy()->addDays(7);
            $current  = $tglMurab->copy()->addDays(7);

            // jadwal angsuran
            $jadwal = [];

            for ($i = 0; $i < $validated['tenor']; $i++) {

                $date = $current->copy();

                while (in_array($date->format('Y-m-d'), $tanggalLibur)) {
                    $date->addDays(7);
                }

                $jadwal[] = $date->format('Y-m-d H:i:s');
                $current->addDays(7);
            }

            $nextPayment     = $jadwal[0];
            $maturityPayment = end($jadwal);

            DB::table('temp_akad_mus')->insert([
                'buss_date' => Carbon::parse($validated['param_tanggal']),
                'code_kel' => $validated['kode_kel'],
                'no_anggota' => $validated['no_rek'],
                'cif' => $validated['cif'],
                'nama' => $validated['nama'],
                'deal_type' => '1',
                'suffix' => (string) $suffix,
                'bagi_hasil'     => $saldoMargin,
                'tenor' => $validated['tenor'],
                'plafond' => $validated['disetujui'],
                'os' => $outStanding,
                'saldo_margin' => $validated['jenis_pembiayaan'] == 2 ? '0' : $saldoMargin,
                'angsuran' => $angsuran,
                'pokok' => $pokok,
                'ijaroh' => $ijaroh,
                'bulat' => $bulat,
                'run_tenor' => 0,
                'ke' => 1,
                'usaha' => $validated['bidang_usaha'],
                'nama_usaha' => $validated['keterangan_usaha'],
                'unit' => $validated['unit'],
                'tgl_wakalah' => $wakalahDate,
                'tgl_akad' => Carbon::parse($validated['tgl_akad']),
                'tgl_murab' => $tglMurab,
                'next_schedule' => $nextPayment,
                'maturity_date' => $maturityPayment,
                'last_payment' => null,
                'hari' => $dayOfWeek,
                'cao' => $validated['cao'],
                'userid' => (int) $validated['id'],
                'status' => 'ANGGOTA',
                'status_usia' => $statusUsia,
                'status_app' => 'APPROVE',
                'gol' => 1,
                'deal_produk' => $validated['jenis_pembiayaan'] == 2 ? '3' : $validated['jenis_pembiayaan'],
                'persen_margin' => $validated['jenis_pembiayaan'] == 2 ? $persenMarginMusyarokah : $persenMargin,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pembiayaan successfully added.'
            ]);

        } catch (\Throwable $e) {
            
            Log::error('Add Pembiayaan Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
