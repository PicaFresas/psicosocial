<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Evaluacion;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $encargados = User::where('role', 'encargado')->get();
        return view('admin.dashboard', compact('encargados'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREAR ENCARGADO
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'encargado',
            'active'   => true,
        ]);

        $this->registrarAuditoria(
            'Crear encargado',
            'Se creó el encargado: ' . $user->email
        );

        return redirect()->route('admin.dashboard')
            ->with('success', 'Encargado creado correctamente');
    }

    /*
    |--------------------------------------------------------------------------
    | EDITAR ENCARGADO
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-encargado', compact('user'));
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR ENCARGADO
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $this->registrarAuditoria(
            'Actualizar encargado',
            'Se actualizó el encargado: ' . $user->email
        );

        return redirect()->route('admin.dashboard')
            ->with('success', 'Encargado actualizado');
    }

    /*
    |--------------------------------------------------------------------------
    | ELIMINAR ENCARGADO
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $this->registrarAuditoria(
            'Eliminar encargado',
            'Se eliminó el encargado: ' . $user->email
        );

        $user->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Encargado eliminado');
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVAR / DESACTIVAR USUARIO
    |--------------------------------------------------------------------------
    */
    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes desactivarte a ti mismo');
        }

        $user->active = !$user->active;
        $user->save();

        $this->registrarAuditoria(
            'Cambiar estado usuario',
            'Se cambió el estado del usuario: ' . $user->email
        );

        return back()->with('success', 'Estado del usuario actualizado');
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORIAL DE ENCUESTAS
    |--------------------------------------------------------------------------
    */
    public function historial()
    {
        $evaluaciones = Evaluacion::latest()->get();
        return view('admin.encuestas.historial', compact('evaluaciones'));
    }

    /*
    |--------------------------------------------------------------------------
    | ELIMINAR EVALUACIÓN
    |--------------------------------------------------------------------------
    */
    public function destroyEvaluacion(Evaluacion $evaluacion)
    {
        $this->registrarAuditoria(
            'Eliminar evaluación',
            'Se eliminó la evaluación ID ' . $evaluacion->id
        );

        $evaluacion->delete();

        return redirect()
            ->route('admin.encuestas.historial')
            ->with('success', 'Evaluación eliminada correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | VER DETALLE DE ENCUESTA
    |--------------------------------------------------------------------------
    */
    public function show(Evaluacion $evaluacion)
    {
        $evaluacion->load(['respuestas.pregunta', 'encargado']);
        return view('admin.encuestas.show', compact('evaluacion'));
    }

    /*
    |--------------------------------------------------------------------------
    | GESTIÓN DE DATOS / AUDITORÍA
    |--------------------------------------------------------------------------
    */
            public function gestionDatos()
            {
                $auditorias = Auditoria::with('user')
                    ->latest()
                    ->get();

                return view('admin.encuestas.gestion_datos', compact('auditorias'));
            }



    /*
    |--------------------------------------------------------------------------
    | EXPORTAR AUDITORÍA JSON
    |--------------------------------------------------------------------------
    */
            public function exportarAuditoriaJson()
        {
            $auditorias = Auditoria::with('user')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($auditorias);
        }


    /*
    |--------------------------------------------------------------------------
    | LIMPIAR AUDITORÍA
    |--------------------------------------------------------------------------
    */
             public function limpiarAuditoria()
            {
                Auditoria::where('created_at', '<', now()->subMonths(6))->delete();

                $this->registrarAuditoria(
                    'Limpiar auditoría',
                    'Se eliminaron registros con más de 6 meses'
                );

                return back()->with('success', 'Auditoría depurada correctamente');
            }



    /*
    |--------------------------------------------------------------------------
    | MÉTODO CENTRAL DE AUDITORÍA
    |--------------------------------------------------------------------------
    */
   private function registrarAuditoria(string $accion, string $descripcion = null)
{
    Auditoria::create([
        'user_id' => auth()->id(),
        'accion' => $accion,
        'descripcion' => $descripcion,
    ]);
}


}
