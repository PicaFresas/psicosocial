<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Pregunta;
use App\Models\Respuesta;
use Illuminate\Http\Request;

class ApendiceIIController extends Controller
{
    /**
     * Mostrar formulario del Apéndice II
     */
    public function create(Evaluacion $evaluacion)
    {
        $preguntas = Pregunta::where('seccion', 'apendice_ii')
            ->orderBy('orden')
            ->get();

        return view('encargado.apendice_ii', compact('evaluacion', 'preguntas'));
    }

    /**
     * Guardar respuestas del Apéndice II
     */
    public function store(Request $request, Evaluacion $evaluacion)
    {
        // ✅ Validación correcta
        $request->validate([
            'respuestas' => 'required|array',
        ]);

        // ✅ Guardar respuestas (solo SI / NO)
        foreach ($request->respuestas as $preguntaId => $respuesta) {
            Respuesta::updateOrCreate(
                [
                    'evaluacion_id' => $evaluacion->id,
                    'pregunta_id'   => $preguntaId,
                ],
                [
                    'respuesta' => strtolower(trim($respuesta)),
                ]
            );
        }

        // ==================================================
        // ✅ CÁLCULO REAL DEL PUNTAJE (APÉNDICE II)
        // ==================================================

        $puntaje = $evaluacion->calcularPuntajeApendiceII();

$evaluacion->update([
    'puntaje_apendice_ii'   => $puntaje,
    'resultado_apendice_ii' => $puntaje > 0 ? 1 : 0,
]);

        // ==================================================
        // ✅ REDIRECCIÓN CORRECTA
        // ==================================================

        return redirect()->route(
            'encargado.apendice_ii.resultado',
            $evaluacion->id
        );
    }

    /**
     * Mostrar resultado Apéndice II
     */
    public function resultado(Evaluacion $evaluacion)
    {
        return view('encargado.resultado_apendice_ii', compact('evaluacion'));
    }
}
