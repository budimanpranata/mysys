<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\ao;
use App\Models\Kelompok;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function Illuminate\Log\log;

class AnggotaController extends Controller
{
    public function data()
    {
        $anggota = DB::table('anggota')->latest()->get();

        return datatables()
            ->of($anggota)
            ->addIndexColumn()
            ->addColumn('aksi', function($anggota) {
                // dd($anggota);
                return '
                    <button onclick="editForm(`'. route('anggota.update', $anggota->no) .'`)" class="btn btn-sm btn-primary">Edit</button>
                    <button onclick="hapusData(`'. route('anggota.destroy', $anggota->no) .'`)" class="btn btn-sm btn-danger">Hapus</button>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);

            // dd($kelompok);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Master Anggota';
        $ao = ao::all();
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('admin.master_anggota.index', compact('title', 'ao', 'menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Master Anggota';
        $ao = ao::all();
        $kelompok = Kelompok::all();
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('admin.master_anggota.create', compact('title', 'ao', 'menus', 'kelompok'));
    }

    public function getKelompokData(Request $request)
    {
        $code_kel = $request->input('code_kel');

        $kelompok = DB::table('kelompok')
        ->join('ao', 'kelompok.cao', '=', 'ao.cao')
        ->where('kelompok.code_kel', (int) $code_kel)
        ->select(
            'kelompok.cao',
            'kelompok.no_tlp',
            'ao.nama_ao' // Ambil nama_ao dari tabel ao
        )
        ->first();
    
        if ($kelompok) {
            return response()->json([
                'nama_ao' => $kelompok->nama_ao,
                'no_tlp' => $kelompok->no_tlp
            ]);
        }
    
        return response()->json([]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // dd($request);
        //validate form
        // $request->validate([
        //     'kode_kel' => 'required',
        //     'nama'   => 'required',
        //     'alamat' => 'required',
        //     'rtrw' => 'required',
        //     'desa' => 'required',
        //     'kecamatan' => 'required',
        //     'kota' => 'required',
        //     'ho_hp' => 'required|numeric',
        //     'hp_pasangan' => 'required|numeric',
        //     'tgl_lahir' => 'required',
        //     'ktp' => 'required',
        //     'kewarganegaraan' => 'required',
        //     'status_menikah' => 'required',
        //     'agama' => 'required',
        //     'ibu_kandung' => 'required',
        //     'pendidikan' => 'required',
        //     'tempat_lahir' => 'required',
        //     'waris' => 'required',
        //     'cao' => 'required',
        // ]);

        try {
            // Log data yang diterima
            Log::info('Data yang diterima:', $request->all());

            $anggota = Anggota::create([
                'unit' => Auth::id(),
                'no' => '123456',
                'kode_kel' => $request->code_kel,
                'norek' => '123456',
                'tgl_join' => Carbon::now(),
                'cif' => '123456',
                'nama' => $request->nama,
                'deal_type' => '1',
                'alamat' => $request->alamat,
                'desa' => $request->desa,
                'kecamatan' => $request->kecamatan,
                'kota' => $request->kota,
                'rtrw' => $request->rtrw,
                'no_hp' => $request->no_hp,
                'hp_pasangan' => $request->hp_pasangan,
                'kelamin' => 'P',
                'tgl_lahir' => $request->tgl_lahir,
                'ktp' => $request->ktp,
                'kewarganegaraan' => $request->kewarganegaraan,
                'status_menikah' => $request->status_menikah,
                'agama' => $request->agama,
                'ibu_kandung' => $request->ibu_kandung,
                'npwp' => 0,
                'source_income' => 1,
                'pendidikan' => $request->pendidikan,
                'tempat_lahir' => $request->tempat_lahir,
                'id_expired' => 0,
                'waris' => $request->waris,
                'cao' => $request->cao,
                'userid' => Auth::id(),
                'status' => 'ANGGOTA',
                'pekerjaan_pasangan' => $request->pekerjaan_pasangan,
                'kode_pos' => $request->kode_pos,
            ]);

            Log::info('Data anggota berhasil disimpan:', $anggota->toArray());
        
            alert()->success('Berhasil!', 'Data Berhasil Disimpan.');
            return redirect()->route('anggota.index');

        } catch (\Throwable $th) {
            // Log error yang terjadi
            Log::error('Error saat menyimpan data anggota:', [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);

            // Redirect dengan pesan error
            alert()->error('Gagal!', 'Gagal saat menyimpan data.');
            return redirect()->back()->withInput()->with(['error' => 'Terjadi kesalahan: ' . $th->getMessage()]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
