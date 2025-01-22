<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use NumberFormatter;

class PDFController extends Controller
{
    /**
     * Generate a PDF for inline viewing.
     *
     * @param string $feature
     * @param string $date
     * @return \Illuminate\Http\Response
     */
    public function generateMusyarakahPdf(Request $request, $feature, $date)
    {
        $viewPath = "admin.{$feature}.template";

        if (!view()->exists($viewPath)) {
            abort(404, 'Template not found');
        }

        $tanggalCetak = \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');

        // Query menggunakan join
        $results = DB::table('temp_akad_mus')
            ->leftJoin('anggota', 'temp_akad_mus.cif', '=', 'anggota.cif')
            ->leftJoin('ao', 'anggota.cao', '=', 'ao.cao') // Join the ao table to get atasan
            ->leftJoin('mm', 'ao.atasan', '=', 'mm.nik') // Join the mm table to get details using atasan
            ->where('temp_akad_mus.tgl_akad', $tanggalCetak)
            ->orderBy('temp_akad_mus.code_kel')
            ->select(
                'temp_akad_mus.*',
                'anggota.ktp as ktp',
                'anggota.desa as desa',
                'anggota.kecamatan as kecamatan',
                'anggota.nama as anggota_nama',
                'ao.atasan as atasan',
                'mm.nama as mm_nama',
                'mm.*'
            )
            ->get();

        foreach ($results as $result) {
            // tgl_akad related variables
            $tglAkad = \Carbon\Carbon::parse($result->tgl_akad)->locale('id');
            $result->tanggal = $tglAkad->day;
            $result->bulan = $tglAkad->translatedFormat('F'); // e.g., October
            $result->tahun = $tglAkad->year;
            $dayNames = [
                0 => 'Minggu',
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
            ];
            $result->namaHari = $dayNames[$tglAkad->dayOfWeek];

            // maturity_date related variables
            $maturityDate = \Carbon\Carbon::parse($result->maturity_date)->locale('id');
            $result->maturityTanggal = $maturityDate->day;
            $result->maturityBulan = $maturityDate->translatedFormat('F'); // e.g., Maret
            $result->maturityTahun = $maturityDate->year;

            // Currency formatting buat plafond & bulat
            $result->totalPinjaman = 'Rp. ' . number_format($result->plafond, 0, ',', '.'); // e.g., Rp.2,000,000
            $result->nominalAngsuran = 'Rp. ' . number_format($result->bulat, 0, ',', '.'); // e.g., Rp.100,000

            // Textual conversion utk plafond & bulat
            $result->totalPinjamanText = $this->convertToRupiahText($result->plafond); // e.g., Dua Juta Rupiah
            $result->nominalAngsuranText = $this->convertToRupiahText($result->bulat); // e.g., Seratus Ribu Rupiah

            $result->persentaseMarginNI = ($result->persen_margin * 100) . '%'; // e.g., 7.5%

            $marginValue = $result->persen_margin * 100; // e.g., 7.5
            $result->persentaseMargin = (100 - $marginValue) . '%'; // e.g., 92.5%

            // Error handling utk optional fields
            $result->ktp = $result->ktp ?? null;
            $result->desa = $result->desa ?? null;
            $result->kecamatan = $result->kecamatan ?? null;
            $result->namaMM = $result->mm_nama ?? null;
            $result->anggotaNama = $result->anggota_nama ?? null;
        }

        if ($results->isEmpty()) {
            abort(404, 'No records found for the specified dates.');
        }

        // Pass results ke view
        $data = ['results' => $results];

        // Generate PDF
        $pdf = PDF::loadView($viewPath, $data);

        return $pdf->stream("{$feature}_{$tanggalCetak}_combined.pdf");
    }

    public function generateLaRisywahPdf(Request $request, $feature, $kelompok, $date)
    {
        $viewPath = "admin.{$feature}.template";

        if (!view()->exists($viewPath)) {
            abort(404, 'Template not found');
        }

        $tanggalCetak = \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');

        $results = DB::table('temp_akad_mus')
            ->leftJoin('kelompok', 'temp_akad_mus.code_kel', '=', 'kelompok.code_kel')
            ->where('temp_akad_mus.tgl_akad', $tanggalCetak)
            ->where('temp_akad_mus.code_kel', $kelompok)
            ->where('temp_akad_mus.status_app', 'MUSYARAKAH')
            ->select(
                'temp_akad_mus.*',
                'kelompok.nama_kel as kelompok_name'
            )
            ->get();

        foreach ($results as $result) {
            $result->formattedPlafond = 'Rp. ' . number_format($result->plafond, 0, ',', '.'); // e.g., Rp.2,000,000
        }

        // Handle empty results
        if ($results->isEmpty()) {
            abort(404, 'No records found for the specified date and kelompok.');
        }

        $namaKel = $results->first()->kelompok_name;

        $totalPlafond = $results->sum('plafond'); // total biaya plafond
        // Format totalPlafond value
        $formattedTotalPlafond = 'Rp. ' . number_format($totalPlafond, 0, ',', '.');

        // Prepare data utk PDF view
        $data = [
            'results' => $results,
            'totalPlafond' => $formattedTotalPlafond,
            'namaKelompok' => $namaKel
        ];

        $pdf = PDF::loadView($viewPath, $data);

        return $pdf->stream("{$feature}_{$kelompok}_{$tanggalCetak}.pdf");
    }

    private function convertToRupiahText($number)
    {
        $f = new NumberFormatter('id_ID', NumberFormatter::SPELLOUT);
        $text = $f->format($number); // Converts number to Indonesian words
        return ucfirst($text) . ' Rupiah'; // Capitalize first letter and append "Rupiah"
    }

}
