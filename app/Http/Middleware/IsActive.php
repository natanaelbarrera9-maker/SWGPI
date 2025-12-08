<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsActive
{
    /**
     * Verificar que el usuario está activo (activo = 1).
     * Si no, cerrar sesión y redirigir a login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !$request->user()->activo) {
            \Illuminate\Support\Facades\Auth::logout();
            $request->session()->invalidate();
            
            return redirect()->route('login')
                ->withErrors(['error' => 'Tu cuenta ha sido desactivada. Contacta al administrador.']);
        }

        return $next($request);
    }
}
