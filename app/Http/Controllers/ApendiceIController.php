<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Pregunta;
use App\Models\Respuesta;
use Illuminate\Http\Request;

class ApendiceIController extends Controller
{
    public function index()
    {
        $preguntas = Pregunta::where('seccion', 'apendice_i')
            ->orderBy('orden')
            ->get();

        return view('encargado.apendice_i', compact('preguntas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empresa'   => 'required',
            'area'      => 'required',
            'puesto'    => 'required',
            'fecha'     => 'required|date',
            'respuestas'=> 'required|array'
        ]);

        $evaluacion = Evaluacion::create([
            'empresa'       => $request->empresa,
            'area'          => $request->area,
            'puesto'        => $request->puesto,
            'fecha'         => $request->fecha,
            'encargado_id'  => auth()->id(),
        ]);

        $hayRiesgo = false;

        foreach ($request->respuestas as $preguntaId => $respuesta) {
            $pregunta = Pregunta::findOrFail($preguntaId);

            $valor = $respuesta === 'si' ? 1 : 0;

            if ($valor === 1) {
                $hayRiesgo = true;
            }

            Respuesta::create([
                'evaluacion_id' => $evaluacion->id,
                'pregunta_id'   => $preguntaId,
                'respuesta'     => $respuesta,
                'valor'         => $valor,
            ]);
        }

        $evaluacion->update([
            'resultado_apendice_i' => $hayRiesgo,
        ]);

        return redirect()->route(
            'encargado.apendice_i.resultado',
            $evaluacion->id
        );
    }
}
