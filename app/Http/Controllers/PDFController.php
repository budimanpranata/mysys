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
        $results = DB::table('temp_akad_mus')->whereIn('tgl_akad', $dates)->orderBy('code_kel')->get();

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
