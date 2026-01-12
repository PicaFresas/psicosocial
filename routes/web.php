<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApendiceIController;
use App\Http\Controllers\ApendiceIIController;
use App\Http\Controllers\ApendiceIIIController;
use App\Http\Controllers\PdfApendiceIController;
use App\Http\Controllers\PdfApendiceIIController;
use App\Http\Controllers\PdfApendiceIIIController;
use App\Http\Controllers\PdfNom036FinalController;
use App\Http\Controllers\HistorialNom036Controller;
use App\Models\Evaluacion;

/*
|--------------------------------------------------------------------------
| PÁGINA PÚBLICA
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('nom036_public'));

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | REDIRECCIÓN POST LOGIN
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'encargado' => redirect()->route('encargado.dashboard'),
            default     => abort(403),
        };
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | PERFIL
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            /* DASHBOARD */
            Route::get('/', [AdminController::class, 'index'])->name('dashboard');
            Route::get('inicio', fn () => view('admin.home'))->name('home');

            /* ENCARGADOS */
            Route::post('encargados', [AdminController::class, 'store'])->name('encargados.store');
            Route::get('encargados/{id}/edit', [AdminController::class, 'edit'])->name('encargados.edit');
            Route::put('encargados/{id}', [AdminController::class, 'update'])->name('encargados.update');
            Route::delete('encargados/{id}', [AdminController::class, 'destroy'])->name('encargados.destroy');
            Route::patch('usuarios/{user}/toggle', [AdminController::class, 'toggleActive'])->name('users.toggle');

            /*
            |--------------------------------------------------------------------------
            | ENCUESTAS
            |--------------------------------------------------------------------------
            */

            // LISTADO PRINCIPAL
            Route::get('encuestas', function () {
                $encuestas = Evaluacion::all();
                return view('admin.encuestas.index', compact('encuestas'));
            })->name('encuestas.index');

            // HISTORIAL
            Route::get('encuestas/historial', [AdminController::class, 'historial'])
                ->name('encuestas.historial');

            // GESTIÓN DE DATOS
            Route::get('encuestas/gestion-datos', [AdminController::class, 'gestionDatos'])
                ->name('encuestas.gestion_datos');

            // AUDITORÍA
            Route::get('encuestas/auditoria/json', [AdminController::class, 'exportarAuditoriaJson'])
                ->name('encuestas.auditoria.json');

            Route::post('encuestas/auditoria/limpiar', [AdminController::class, 'limpiarAuditoria'])
                ->name('encuestas.auditoria.limpiar');

            // VER ENCUESTA
            Route::get('encuestas/{evaluacion}', [AdminController::class, 'show'])
                ->name('encuestas.show');

            // ✅ EXPORTAR PDF (ADMIN – CORRECTO)
            Route::get(
                'encuestas/{evaluacion}/pdf',
                [PdfNom036FinalController::class, 'adminPdf']
            )->name('encuestas.pdf');

            // ELIMINAR / DEPURAR
            Route::delete('evaluacion/{evaluacion}', [AdminController::class, 'destroyEvaluacion'])
                ->name('evaluacion.destroy');
        });

    /*
    |--------------------------------------------------------------------------
    | ENCARGADO
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:encargado')
        ->prefix('encargado')
        ->name('encargado.')
        ->group(function () {

            /* DASHBOARD */
            Route::get('/', fn () => view('encargado.dashboard'))
                ->name('dashboard');

            /* SELECCIONAR APÉNDICE */
            Route::get('seleccionar-apendice', fn () =>
                view('encargado.seleccionar_apendice')
            )->name('seleccionar_apendice');

            /* APÉNDICE I */
            Route::get('apendice-i', [ApendiceIController::class, 'index'])->name('apendice_i');
            Route::post('apendice-i', [ApendiceIController::class, 'store'])->name('apendice_i.store');
            Route::get('apendice-i/resultado/{evaluacion}', fn (Evaluacion $evaluacion) =>
                view('encargado.resultado_apendice_i', compact('evaluacion'))
            )->name('apendice_i.resultado');
            Route::get('apendice-i/pdf/{evaluacion}', [PdfApendiceIController::class, 'generar'])
                ->name('apendice_i.pdf');

            /* APÉNDICE II */
            Route::get('apendice-ii/{evaluacion}', [ApendiceIIController::class, 'create'])
                ->name('apendice_ii.create');
            Route::post('apendice-ii/{evaluacion}', [ApendiceIIController::class, 'store'])
                ->name('apendice_ii.store');
            Route::get('apendice-ii/resultado/{evaluacion}', fn (Evaluacion $evaluacion) =>
                view('encargado.resultado_apendice_ii', compact('evaluacion'))
            )->name('apendice_ii.resultado');
            Route::get('apendice-ii/pdf/{evaluacion}', [PdfApendiceIIController::class, 'generar'])
                ->name('apendice_ii.pdf');

            /* APÉNDICE III */
            Route::get('apendice-iii/{evaluacion}', [ApendiceIIIController::class, 'create'])
                ->name('apendice_iii.create');
            Route::post('apendice-iii/{evaluacion}', [ApendiceIIIController::class, 'store'])
                ->name('apendice_iii.store');
            Route::get('apendice-iii/resultado/{evaluacion}', [ApendiceIIIController::class, 'resultado'])
                ->name('apendice_iii.resultado');
            Route::get('apendice-iii/pdf/{evaluacion}', [PdfApendiceIIIController::class, 'generar'])
                ->name('apendice_iii.pdf');

            /* HISTORIAL */
            Route::get('historial', [HistorialNom036Controller::class, 'index'])
                ->name('historial');

            Route::delete('evaluacion/{evaluacion}', [HistorialNom036Controller::class, 'destroy'])
                ->name('evaluacion.destroy');

            /* PDF FINAL */
            Route::get('nom036/pdf-final/{evaluacion}', [PdfNom036FinalController::class, 'generar'])
                ->name('nom036.pdf_final');
        });
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
