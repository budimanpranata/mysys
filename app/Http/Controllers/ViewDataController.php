<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\AnggotaDetail;
use App\Models\ao;
use App\Models\Kelompok;
use App\Models\Menu;
use App\Models\pembiayaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ViewDataController extends Controller
{
    public function data()
    {
        // $anggota = DB::table('anggota')->latest()->get();
        $anggota = DB::table('anggota')
        ->join('pembiayaan', 'anggota.no', '=', 'pembiayaan.no_anggota')
        ->select(
            'anggota.*',
            'anggota.kode_kel as kode_kel_anggota',
            'anggota.nama as nama_anggota',
            'anggota.cif as cif_anggota',
            'pembiayaan.*',
        );

        return datatables()
            ->of($anggota)
            ->addIndexColumn()
            ->addColumn('aksi', function($anggota) {
                return '
                <a href="'. route('view-data.edit', $anggota->no) .'" class="btn btn-sm btn-warning">Edit</a>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function index()
    {
        $title = 'View Data';
        $ao = ao::all();
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('admin.view_data.index', compact('title', 'ao', 'menus'));
    }

    public function edit(string $id)
    {
        $title = 'Edit data Anggota';
        // $anggota = Anggota::where('no', $id)->firstOrFail();
        $anggota = DB::table('anggota')
        ->join('pembiayaan', 'anggota.no', '=', 'pembiayaan.no_anggota')
        ->select(
            'anggota.*',
            'pembiayaan.*',
        )
        ->where('no', $id)->firstOrFail();
        $anggota_detail = AnggotaDetail::where('no_anggota', $id)->first();
        $ao = ao::all();
        $kelompok = Kelompok::all();
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('admin.view_data.edit', compact('anggota','title','ao', 'kelompok', 'menus', 'anggota_detail'));
    }

    public function update(Request $request, string $id)
    {

        $unit = Auth::user()->unit;

        try {
            // Log data yang diterima
            Log::info('Data yang diterima:', $request->all());
    
            $anggota = Anggota::where('no', $id)->firstOrFail();
    
            $anggota->update([
                'unit' => $unit,
                'kode_kel' => strtoupper($request->kode_kel),
                'cif' => strtoupper($request->cif),
                'nama' => strtoupper($request->nama),
                'deal_type' => '1',
                'alamat' => strtoupper($request->alamat),
                'desa' => strtoupper($request->desa),
                'kecamatan' => strtoupper($request->kecamatan),
                'kota' => strtoupper($request->kota),
                'rtrw' => strtoupper($request->rtrw),
                'no_hp' => strtoupper($request->no_hp),
                'hp_pasangan' => strtoupper($request->hp_pasangan),
                'kelamin' => 'P',
                'tgl_lahir' => $request->tgl_lahir,
                'ktp' => strtoupper($request->ktp),
                'kewarganegaraan' => strtoupper($request->kewarganegaraan),
                'status_menikah' => strtoupper($request->status_menikah),
                'agama' => strtoupper($request->agama),
                'ibu_kandung' => strtoupper($request->ibu_kandung),
                'npwp' => 0,
                'source_income' => 1,
                'pendidikan' => strtoupper($request->pendidikan),
                'tempat_lahir' => strtoupper($request->tempat_lahir),
                'id_expired' => 0,
                'waris' => strtoupper($request->waris),
                'cao' => strtoupper($request->cao),
                'userid' => Auth::id(),
                'status' => 'ANGGOTA',
                'pekerjaan_pasangan' => strtoupper($request->pekerjaan_pasangan),
                'kode_pos' => strtoupper($request->kode_pos),
            ]);

            $anggotaDetail = AnggotaDetail::where('no_anggota', $id)->first();
            if ($anggotaDetail) {
                $anggotaDetail->update([
                    'alamat_domisili' => strtoupper($request->alamat_domisili ?? $request->alamat),
                    'rtrw_domisili' => strtoupper($request->rtrw_domisili ?? $request->rtrw),
                    'desa_domisili' => strtoupper($request->desa_domisili ?? $request->desa),
                    'kecamatan_domisili' => strtoupper($request->kecamatan_domisili ?? $request->kecamatan),
                    'kota_domisili' => strtoupper($request->kota_domisili ?? $request->kota),
                    'kode_pos_domisili' => strtoupper($request->kode_pos_domisili ?? $request->kode_pos),
                ]);
            } else {
                AnggotaDetail::create([
                    'no_anggota' => $id,
                    'alamat_domisili' => strtoupper($request->alamat_domisili ?? $request->alamat),
                    'rtrw_domisili' => strtoupper($request->rtrw_domisili ?? $request->rtrw),
                    'desa_domisili' => strtoupper($request->desa_domisili ?? $request->desa),
                    'kecamatan_domisili' => strtoupper($request->kecamatan_domisili ?? $request->kecamatan),
                    'kota_domisili' => strtoupper($request->kota_domisili ?? $request->kota),
                    'kode_pos_domisili' => strtoupper($request->kode_pos_domisili ?? $request->kode_pos),
                ]);
            }

            $anggotaPembiayaan = pembiayaan::where('no_anggota', $id)->first();
            if ($anggotaPembiayaan) {
                $anggotaPembiayaan->update([
                    'tgl_wakalah' => ($request->tgl_wakalah ?? ''),
                    'maturity_date' => ($request->maturity_date ?? ''),
                    'nama_usaha' => strtoupper($request->nama_usaha ?? ''),
                    'hari' => $request->hari,
                ]);
            }
    
            Log::info('Data anggota berhasil diperbarui:', $anggota->toArray());
        
            alert()->success('Berhasil!', 'Data Berhasil Diperbarui.');
            return redirect()->route('view-data.index');
    
        } catch (\Throwable $th) {
            // Log error yang terjadi
            Log::error('Error saat memperbarui data anggota:', [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
    
            // Redirect dengan pesan error
            alert()->error('Gagal!', 'Gagal saat memperbarui data.');
            return redirect()->back()->withInput()->with(['error' => 'Terjadi kesalahan: ' . $th->getMessage()]);
        }
    }
}
