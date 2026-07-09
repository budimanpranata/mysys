<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostingJurnalController extends Controller
{
    public function index()
    {
        $title = 'Posting Jurnal';
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $unit = Auth::user()->unit;
        $tglSystem = Auth::user()->param_tanggal;

        $rows = DB::table('tabel_transaksi')
            ->leftJoin('coa', 'coa.kode_rek', '=', 'tabel_transaksi.kode_rekening')
            ->where('tabel_transaksi.unit', $unit)
            ->whereBetween('tabel_transaksi.tanggal_transaksi', ["{$tglSystem} 00:00:00", "{$tglSystem} 23:59:59"])
            ->where('tabel_transaksi.keterangan_posting', '')
            ->select(
                'tabel_transaksi.kode_rekening',
                'coa.nama_rek',
                DB::raw('SUM(tabel_transaksi.debet) as debet'),
                DB::raw('SUM(tabel_transaksi.kredit) as kredit')
            )
            ->groupBy('tabel_transaksi.kode_rekening', 'coa.nama_rek')
            ->orderBy('tabel_transaksi.kode_rekening')
            ->get();

        $total = DB::table('tabel_transaksi')
            ->where('unit', $unit)
            ->whereBetween('tanggal_transaksi', ["{$tglSystem} 00:00:00", "{$tglSystem} 23:59:59"])
            ->where('keterangan_posting', '')
            ->selectRaw('SUM(debet) as tot_debet, SUM(kredit) as tot_kredit')
            ->first();

        return view('admin.posting_jurnal.index', [
            'title' => $title,
            'menus' => $menus,
            'rows' => $rows,
            'total' => $total,
            'tglSystem' => $tglSystem,
        ]);
    }

    public function posting()
    {
        $unit = Auth::user()->unit;
        $tglSystem = Auth::user()->param_tanggal;

        DB::transaction(function () use ($unit, $tglSystem) {
            $mutasi = DB::table('tabel_transaksi')
                ->where('unit', $unit)
                ->whereBetween('tanggal_transaksi', ["{$tglSystem} 00:00:00", "{$tglSystem} 23:59:59"])
                ->where('keterangan_posting', '')
                ->select('kode_rekening', DB::raw('SUM(debet) as debet'), DB::raw('SUM(kredit) as kredit'))
                ->groupBy('kode_rekening')
                ->get();

            foreach ($mutasi as $row) {
                if ($row->debet > 0) {
                    DB::table('tabel_master')
                        ->where('unit', $unit)
                        ->where('kode_rekening', $row->kode_rekening)
                        ->increment('mut_debet', $row->debet);
                }

                if ($row->kredit > 0) {
                    DB::table('tabel_master')
                        ->where('unit', $unit)
                        ->where('kode_rekening', $row->kode_rekening)
                        ->increment('mut_kredit', $row->kredit);
                }
            }

            $akun = DB::table('tabel_master')
                ->where('unit', $unit)
                ->select('kode_rekening', 'normal', 'awal_debet', 'awal_kredit', 'mut_debet', 'mut_kredit')
                ->get();

            foreach ($akun as $row) {
                if ($row->normal === 'debet') {
                    $sisa = ($row->awal_debet + $row->mut_debet) - $row->mut_kredit;
                    $sisaDebet = $sisa < 0 ? 0 : $sisa;
                    $sisaKredit = $sisa < 0 ? abs($sisa) : 0;
                } elseif ($row->normal === 'kredit') {
                    $sisa = ($row->awal_kredit - $row->mut_debet) + $row->mut_kredit;
                    $sisaDebet = $sisa < 0 ? abs($sisa) : 0;
                    $sisaKredit = $sisa < 0 ? 0 : $sisa;
                } else {
                    continue;
                }

                DB::table('tabel_master')
                    ->where('unit', $unit)
                    ->where('kode_rekening', $row->kode_rekening)
                    ->update(['sisa_debet' => $sisaDebet, 'sisa_kredit' => $sisaKredit]);
            }

            DB::table('tabel_transaksi')
                ->where('unit', $unit)
                ->whereBetween('tanggal_transaksi', ["{$tglSystem} 00:00:00", "{$tglSystem} 23:59:59"])
                ->where('keterangan_posting', '')
                ->update(['tanggal_posting' => now()->format('y-m-d'), 'keterangan_posting' => 'Post']);
        });

        return redirect()->route('posting-jurnal.index')->with('success', 'Proses Posting Selesai');
    }
}
