<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Procesar login
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // 🔴 VALIDAR SI ESTÁ DESACTIVADO
        if (!auth()->user()->active) {
            auth()->logout();

            return redirect()->route('login')
                ->withErrors(['email' => 'Tu cuenta está desactivada. Contacta al administrador.']);
        }

        $request->session()->regenerate();

        // 👇 BLOQUE AGREGADO: redirigir admin a inicio
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.home');
        }

        return redirect()->intended('/dashboard');
    }

    /**
     * Logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

