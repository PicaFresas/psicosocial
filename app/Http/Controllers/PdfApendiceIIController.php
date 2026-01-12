<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Pregunta;
use App\Models\Respuesta;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfApendiceIIController extends Controller
{
    public function generar(Evaluacion $evaluacion)
    {
        // Preguntas del Apéndice II
        $preguntas = Pregunta::where('seccion', 'apendice_ii')
            ->orderBy('orden')
            ->get();

        // Respuestas de esta evaluación
        $respuestas = Respuesta::where('evaluacion_id', $evaluacion->id)
            ->get()
            ->keyBy('pregunta_id');

        $pdf = Pdf::loadView(
            'pdf.apendice_ii',
            compact('evaluacion', 'preguntas', 'respuestas')
        )->setPaper('A4', 'portrait');

        return $pdf->download(
            'Apendice_II_NOM_036_' . $evaluacion->id . '.pdf'
        );
    }
}
