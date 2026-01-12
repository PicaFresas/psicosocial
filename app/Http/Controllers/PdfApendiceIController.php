<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfApendiceIController extends Controller
{
    public function generar(Evaluacion $evaluacion)
    {
        // 👇 CARGA RELACIONES (CLAVE)
        // evaluacion -> respuestas -> pregunta
        $evaluacion->load([
            'respuestas.pregunta'
        ]);

        $pdf = Pdf::loadView(
            'pdf.apendice_i',
            [
                'evaluacion' => $evaluacion
            ]
        )->setPaper('A4', 'portrait');

        return $pdf->download(
            'Apendice_I_NOM-036_' . $evaluacion->id . '.pdf'
        );
    }
}
