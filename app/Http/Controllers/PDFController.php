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
        $results = DB::table('temp_akad_mus')
            ->whereIn('tgl_akad', $dates)
            ->orderBy('code_kel')
            ->get();

        foreach ($results as $result) {
            $tglAkad = \Carbon\Carbon::parse($result->tgl_akad);
            $result->tanggal = $tglAkad->day;
            $result->bulan = $tglAkad->translatedFormat('F'); // (e.g., October)
            $result->tahun = $tglAkad->year;

            // Query anggota make cif
            $anggota = DB::table('anggota')->where('cif', $result->cif)->first();

            if ($anggota) {
                $result->ktp = $anggota->ktp;
                $result->desa = $anggota->desa;
                $result->kecamatan = $anggota->kecamatan;
            } else {
                $result->ktp = null;
                $result->desa = null;
                $result->kecamatan = null;
            }
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
