<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use Illuminate\Http\Request;

class HistorialNom036Controller extends Controller
{
    public function index()
    {
        // Solo evaluaciones del encargado logueado
        $evaluaciones = Evaluacion::where(
            'encargado_id',
            auth()->id()
        )->latest()->get();

        return view('encargado.historial', compact('evaluaciones'));
    }

    public function destroy(Evaluacion $evaluacion)
    {
        // Seguridad: solo el encargado dueño puede borrar
        if ($evaluacion->encargado_id !== auth()->id()) {
            abort(403);
        }

        $evaluacion->delete();

        return redirect()
            ->route('encargado.historial')
            ->with('success', 'Evaluación eliminada correctamente.');
    }

}
