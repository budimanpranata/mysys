<?php

namespace App\Http\Controllers;

use App\Models\ao;
use App\Models\Kelompok;
use App\Models\Anggota;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KelompokController extends Controller
{
    public function data()
    {
        $kelompok = DB::table('kelompok')->latest()->get();

        return datatables()
            ->of($kelompok)
            ->addIndexColumn()
            ->addColumn('aksi', function($kelompok) {
                return '
                    <button onclick="editForm(`'. route('kelompok.update', $kelompok->code_kel) .'`)" class="btn btn-sm btn-primary">Edit</button>
                    <button onclick="hapusData(`'. route('kelompok.destroy', $kelompok->code_kel) .'`)" class="btn btn-sm btn-danger">Hapus</button>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Master Kelompok';
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $ao = ao::all();
        $anggota = Anggota::all();
        return view('admin.master_kelompok.index', compact('menus', 'title', 'ao', 'anggota'));
    }

    public function getAnggotaByCif($cif)
    {
        // Cari anggota berdasarkan cif
        $anggota = Anggota::where('cif', $cif)->first();

        // Jika data ditemukan, kembalikan response JSON
        if ($anggota) {
            return response()->json([
                'success' => true,
                'data' => $anggota
            ]);
        }

        // Jika data tidak ditemukan
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // tambahkan validasi
            $validated = $request->validate([
                'code_unit' => 'required',
                'nama_kel' => 'required|string|max:255',
                'alamat' => 'required',
                'cao' => 'required',
                'cif' => 'required',
                'no_tlp' => 'required|max:13|min:11',
            ]);

            // generate kode kelompok
            $unit = Auth::user()->unit;
            $lastKelompok = Kelompok::where('code_kel', 'LIKE', $unit.'-%')
                                ->latest()
                                ->first();

            $sequence = $lastKelompok ? intval(substr($lastKelompok->code_kel, -4)) + 1 : 1;
            $sequenceFormatted = str_pad($sequence, 4, '0', STR_PAD_LEFT);

            $validated['code_kel'] = $request->code_unit . '-' . $sequenceFormatted;

            $validated['code_unit'] = strtoupper($validated['code_unit']);
            $validated['nama_kel'] = strtoupper($validated['nama_kel']);
            $validated['alamat'] = strtoupper($validated['alamat']);
            $validated['cao'] = strtoupper($validated['cao']);
            $validated['cif'] = strtoupper($validated['cif']);
            $validated['no_tlp'] = strtoupper($validated['no_tlp']);

            Kelompok::create($validated);

            return response()->json(['message' => 'Data berhasil disimpan'], 200);
        } catch (\Exception $e) {
            // log untuk debugging
            Log::error('Error saat menyimpan data: ' . $e->getMessage());

            return response()->json(['message' => 'Terjadi kesalahan'], 500);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kelompok = Kelompok::where('code_kel', $id)->first();

        return response()->json($kelompok);
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
        $kelompok = Kelompok::where('code_kel', $id)->first();
        $kelompok->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Cari produk berdasarkan code_kel
            $kelompok = Kelompok::where('code_kel', $id)->first();
    
            // Jika data tidak ditemukan, kembalikan respon error
            if (!$kelompok) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }
    
            // Hapus data
            $kelompok->delete();
    
            // Kembalikan respon sukses
            return response()->json(['message' => 'Data berhasil dihapus.'], 204);
        } catch (\Exception $e) {
            // Tangani error dan log untuk debugging
            Log::error('Error saat menghapus data: ' . $e->getMessage());
    
            // Kembalikan respon error
            return response()->json(['message' => 'Terjadi kesalahan'], 500);
        }
    }
    
}
