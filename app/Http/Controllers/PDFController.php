<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

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

        // Fetch records berdasarkan state
        $dates = explode(',', $request->id);

        // Query menggunakan join
        $results = DB::table('temp_akad_mus')
            ->leftJoin('anggota', 'temp_akad_mus.cif', '=', 'anggota.cif')
            ->whereIn('temp_akad_mus.tgl_akad', $dates)
            ->orderBy('temp_akad_mus.code_kel')
            ->select(
                'temp_akad_mus.*',
                'anggota.ktp as ktp',
                'anggota.desa as desa',
                'anggota.kecamatan as kecamatan'
            )
            ->get();

        foreach ($results as $result) {
            $tglAkad = \Carbon\Carbon::parse($result->tgl_akad);
            $result->tanggal = $tglAkad->day;
            $result->bulan = $tglAkad->translatedFormat('F'); // e.g., October
            $result->tahun = $tglAkad->year;

            $result->ktp = $result->ktp ?? null;
            $result->desa = $result->desa ?? null;
            $result->kecamatan = $result->kecamatan ?? null;
        }

        if ($results->isEmpty()) {
            abort(404, 'No records found for the specified dates.');
        }

        // Pass results ke view
        $data = ['results' => $results];

        // Generate PDF
        $pdf = PDF::loadView($viewPath, $data);

        return $pdf->stream("{$feature}_combined.pdf");
    }

}
