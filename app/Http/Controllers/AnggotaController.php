<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\ao;
use App\Models\Kelompok;
use App\Models\Menu;
use Illuminate\Http\Request;
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
        $code_kel = $request->code_kel;
        Log::info("Menerima request untuk kode_kel: " . $code_kel); // Debug

        // $kelompok = DB::table('kelompok')->where('code_kel', $code_kel)->first();
    // $kelompok = Kelompok::where('code_kel', $code_kel)->first();
    $kelompok = Kelompok::where('code_kel', (int) $code_kel)->first();


        if ($kelompok) {
            Log::info("Data ditemukan:", $kelompok->toArray()); // Debug
            return response()->json([
                'cif' => $kelompok->cif, 
                'no_tlp' => $kelompok->no_tlp
            ]);
        } else {
            Log::info("Data tidak ditemukan untuk kode_kel: " . $code_kel);
            return response()->json([
                'cif' => '',
                'np_tlp' => ''
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */

     public function store(Request $request)
     {

        dd($request);
         try {
             // Validasi input
            //  $request->validate([
            //      'no_anggota' => 'required',
            //      'cif' => 'required',
            //      'nama' => 'required',
            //      'alamat' => 'required',
            //      'tempat_lahir' => 'required',
            //      'tgl_lahir' => 'required|date',
            //      'unit' => 'required',
            //  ]);
     
             // Simpan data
             Anggota::create($request->all());
     
             // Response sukses
             return response()->json([
                 'message' => 'Data berhasil disimpan!',
             ], 200);
     
         } catch (\Illuminate\Validation\ValidationException $e) {
             // Tangani error validasi
             return response()->json([
                 'message' => 'Validasi gagal.',
                 'errors' => $e->errors(),
             ], 422);
     
         } catch (\Exception $e) {
             // Tangani error lainnya
             return response()->json([
                 'message' => 'Terjadi kesalahan saat menyimpan data.',
                 'error' => $e->getMessage(),
             ], 500);
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
