<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Pregunta;
use App\Models\Respuesta;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfApendiceIIIController extends Controller
{
    public function generar(Evaluacion $evaluacion)
    {
        // Preguntas del Apéndice III
        $preguntas = Pregunta::where('seccion', 'apendice_iii')
            ->orderBy('orden')
            ->get();

        // Respuestas de la evaluación
        $respuestas = Respuesta::where('evaluacion_id', $evaluacion->id)
            ->get()
            ->keyBy('pregunta_id');

        $pdf = Pdf::loadView(
            'pdf.apendice_iii',
            compact('evaluacion', 'preguntas', 'respuestas')
        )->setPaper('A4', 'portrait');

        return $pdf->download(
            'Apendice_III_NOM_036_' . $evaluacion->id . '.pdf'
        );
    }
}
