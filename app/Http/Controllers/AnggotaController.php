<?php

namespace App\Http\Controllers;

use App\Exports\AnggotaExport;
use App\Models\Anggota;
use App\Models\AnggotaDetail;
use App\Models\ao;
use App\Models\Kelompok;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
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
                return '
                <a href="'. route('anggota.edit', $anggota->no) .'" class="btn btn-sm btn-warning">Edit</a>
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
        $title = 'Input data Anggota';
        $ao = ao::all();
        $kelompok = Kelompok::all();
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('admin.master_anggota.create', compact('title', 'ao', 'menus', 'kelompok'));
    }

    public function getKelompokData(Request $request)
    {

        $kelompok = DB::table('kelompok')
        ->join('ao', 'kelompok.cao', '=', 'ao.cao') // Relasi ke tabel ao
        ->where('kelompok.code_kel', $request->code_kel)
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

    public function cariKtp(Request $request)
    {
        // Validasi input NIK
        $request->validate([
            'nik' => 'required|string'
        ]);

        $nik = $request->input('nik');

        // Lakukan request ke API eksternal
        $response = Http::get("http://mobcol.nurinsani.co.id/apimobcol/rmcKtp.php?ktp={$nik}");

        // Jika request gagal
        if (!$response->successful()) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
        }

        // Ambil data dari response
        $data = $response->json();

        // Kembalikan data sebagai response JSON
        return response()->json($data);
    }

    public function getKelompokByCao($cao)
    {
        $kelompok = Kelompok::where('cao', $cao)->get();
        return response()->json($kelompok);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // dd($request);
        $unit = Auth::user()->unit;

        $request->validate([
            'cao' => 'required',
            'kode_kel' => 'required',
            'nama'   => 'required',
            'alamat' => 'required',
            'rtrw' => 'required',
            'desa' => 'required',
            'kecamatan' => 'required',
            'kota' => 'required',
            'tgl_lahir' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $umur = Carbon::parse($value)->age; // Hitung umur
                    if ($umur > 60) {
                        $fail('Umur tidak boleh lebih dari 60 tahun.'); // Validasi umur
                    }
                },
            ],
            'ktp' => [
                'required',
                'digits:16',
                function ($attribute, $value, $fail) use ($unit) {
                    $existingAnggota = Anggota::where('ktp', $value)->first();
                    if ($existingAnggota && $existingAnggota->unit == $unit) {
                        $fail('NIK sudah ada di unit lain.');
                    }
                },
            ],
            'kewarganegaraan' => 'required',
            'status_menikah' => 'required',
            'agama' => 'required',
            'no_hp' => 'required|min:11',
            'hp_pasangan' => 'required|min:11',
            'ibu_kandung' => 'required',
            'pendidikan' => 'required',
            'tempat_lahir' => 'required',
            'waris' => 'required',
            'pekerjaan_pasangan' => 'required',
            'kode_pos' => 'required',
        ], [
            'cao.required' => 'Nama AO tidak boleh kosong.',
            'kode_kel.required' => 'Nama Kelompok tidak boleh kosong.',
            'nama.required' => 'Nama tidak boleh kosong.',
            'alamat.required' => 'Alamat tidak boleh kosong.',
            'rtrw.required' => 'RT/RW tidak boleh kosong.',
            'desa.required' => 'Desa tidak boleh kosong.',
            'kecamatan.required' => 'Kecamatan tidak boleh kosong.',
            'kota.required' => 'Kabupaten tidak boleh kosong.',
            'kode_pos.required' => 'Kode Pos tidak boleh kosong.',
            'tgl_lahir.required' => 'Tanggal Lahir tidak boleh kosong.',
            'ktp.required' => 'NIK tidak boleh kosong.',
            'ktp.digits' => 'NIK harus tepat 16 digit.',
            'kewarganegaraan.required' => 'Kewarganegaraan tidak boleh kosong.',
            'status_menikah.required' => 'Status Menikah tidak boleh kosong.',
            'agama.required' => 'Agama tidak boleh kosong.',
            'no_hp.required' => 'No. Hp tidak boleh kosong.',
            'no_hp.min' => 'No Hp minimal 11 karakter.',
            'hp_pasangan.required' => 'No Hp Pasangan tidak boleh kosong.',
            'hp_pasangan.min' => 'No Hp Pasangan minimal 11 karakter.',
            'ibu_kandung.required' => 'Ibu Kandung tidak boleh kosong.',
            'pendidikan.required' => 'Pendidikan tidak boleh kosong.',
            'tempat_lahir.required' => 'Tempat Lahir tidak boleh kosong.',
            'waris.required' => 'Waris tidak boleh kosong.',
            'pekerjaan_pasangan.required' => 'Pekerjaan Pasangan tidak boleh kosong.',
        ]);

        try {
            // Log data yang diterima
            Log::info('Data yang diterima:', $request->all());

            // Generate no anggota
            // $unit = Auth::user()->unit;
            $date = Carbon::now()->format('ymd'); // Format tanggal: TahunBulanTanggal (20231025)
            // $lastAnggota = Anggota::whereDate('created_at', Carbon::today())->latest()->first();
            $lastAnggota = Anggota::latest()->first(); // Ambil record terakhir

            // Nomor urut
            $sequence = $lastAnggota ? intval(substr($lastAnggota->no, -3)) + 1 : 1;
            $sequenceFormatted = str_pad($sequence, 3, '0', STR_PAD_LEFT); // Format urutan (001, 002, dst.)

            // Gabungkan no anggota
            $noAnggota = "{$unit}{$date}{$sequenceFormatted}";

            $anggota = Anggota::create([
                'no' => $noAnggota,
                'unit' => Auth::user()->unit,
                'kode_kel' => strtoupper($request->kode_kel),
                'norek' => $noAnggota,
                'tgl_join' => Carbon::now(),
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
    
            AnggotaDetail::create([
                'no_anggota' => $noAnggota,
                'alamat_domisili' => strtoupper($request->alamat_domisili ?? $request->alamat),
                'rtrw_domisili' => strtoupper($request->rtrw_domisili ?? $request->rtrw),
                'desa_domisili' => strtoupper($request->desa_domisili ?? $request->desa),
                'kecamatan_domisili' => strtoupper($request->kecamatan_domisili ?? $request->kecamatan),
                'kota_domisili' => strtoupper($request->kota_domisili ?? $request->kota),
                'kode_pos_domisili' => strtoupper($request->kode_pos_domisili ?? $request->kode_pos),
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
        $title = 'Edit data Anggota';
        $anggota = Anggota::where('no', $id)->firstOrFail();
        $anggota_detail = AnggotaDetail::where('no_anggota', $id)->first();
        $ao = ao::all();
        $kelompok = Kelompok::all();
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('admin.master_anggota.edit', compact('anggota','title','ao', 'kelompok', 'menus', 'anggota_detail'));
    }

    /**
     * Update the specified resource in storage.
     */
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
    
            Log::info('Data anggota berhasil diperbarui:', $anggota->toArray());
        
            alert()->success('Berhasil!', 'Data Berhasil Diperbarui.');
            return redirect()->route('anggota.index');
    
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function export()
    {
        return Excel::download(new AnggotaExport, 'anggota.xlsx');
    }
}
