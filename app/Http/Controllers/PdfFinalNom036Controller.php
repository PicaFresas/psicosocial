<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Pregunta;
use App\Models\Respuesta;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfFinalNom036Controller extends Controller
{
    public function generar(Evaluacion $evaluacion)
    {
        // Preguntas por apéndice
        $preguntasI = Pregunta::where('seccion', 'apendice_i')->orderBy('orden')->get();
        $preguntasII = Pregunta::where('seccion', 'apendice_ii')->orderBy('orden')->get();
        $preguntasIII = Pregunta::where('seccion', 'apendice_iii')->orderBy('orden')->get();

        // Respuestas de la evaluación
        $respuestas = Respuesta::where('evaluacion_id', $evaluacion->id)
            ->get()
            ->keyBy('pregunta_id');

        $pdf = Pdf::loadView(
            'pdf.nom036_final',
            compact(
                'evaluacion',
                'preguntasI',
                'preguntasII',
                'preguntasIII',
                'respuestas'
            )
        )->setPaper('A4', 'portrait');

        return $pdf->download(
            'NOM_036_Evaluacion_' . $evaluacion->id . '.pdf'
        );
    }
}
