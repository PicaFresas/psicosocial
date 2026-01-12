<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfNom036FinalController extends Controller
{
    public function generar(Evaluacion $evaluacion)
    {
        $pdf = Pdf::loadView(
            'pdf.nom036_final',
            compact('evaluacion')
        )->setPaper('A4');

        return $pdf->download('NOM-036_Evaluacion_Final.pdf');
    }

    public function adminPdf(Evaluacion $evaluacion)
{
    $pdf = PDF::loadView(
        'pdf.nom036_final',
        compact('evaluacion')
    );

    return $pdf->download(
        'Evaluacion_'.$evaluacion->id.'.pdf'
    );
}
}
