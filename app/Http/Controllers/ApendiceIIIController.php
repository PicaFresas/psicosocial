<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Evaluacion;
use App\Models\Pregunta;
use App\Models\Respuesta;
use Illuminate\Http\Request;

class ApendiceIIIController extends Controller
{
    /**
     * Mostrar Apéndice III (Violencia laboral)
     */
    public function create(Evaluacion $evaluacion)
    {
        $preguntas = Pregunta::where('seccion', 'apendice_iii')
            ->orderBy('orden')
            ->get();

        return view('encargado.apendice_iii', compact('evaluacion', 'preguntas'));
    }

    /**
     * Guardar respuestas del Apéndice III
     */
    public function store(Request $request, Evaluacion $evaluacion)
    {
        $respuestas = $request->input('respuestas', []);

        if (empty($respuestas)) {
            return back()->withErrors('Debe responder todas las preguntas.');
        }

        $hayViolencia = false;

        foreach ($respuestas as $preguntaId => $respuesta) {

            $pregunta = Pregunta::findOrFail($preguntaId);

            $valor = $respuesta === 'si'
                ? $pregunta->valor_si
                : $pregunta->valor_no;

            if ($valor === 1) {
                $hayViolencia = true;
            }

            Respuesta::create([
                'evaluacion_id' => $evaluacion->id,
                'pregunta_id'   => $preguntaId,
                'respuesta'     => $respuesta,
                'valor'         => $valor,
                'user_id'       => auth()->id(),
            ]);
        }

        $evaluacion->update([
            'resultado_apendice_iii' => $hayViolencia ? 1 : 0,
        ]);
            
             Auditoria::create([
                'user_id' => auth()->id(),
                'accion' => 'Enviar encuesta',
                'descripcion' => 'El encargado finalizó la encuesta con ID ' . $evaluacion->id,
        ]);

        return redirect()->route('encargado.apendice_iii.resultado', $evaluacion->id);
    }

    /**
     * Mostrar resultado
     */
    public function resultado(Evaluacion $evaluacion)
    {
        return view('encargado.resultado_apendice_iii', compact('evaluacion'));
    }
}
