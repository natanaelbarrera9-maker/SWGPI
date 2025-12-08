<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Verificar que el usuario tenga el rol especificado.
     * 
     * Uso en rutas:
     *   Route::middleware(['auth', 'role:admin'])->group(...)
     *   Route::middleware(['auth', 'role:teacher'])->group(...)
     *   Route::middleware(['auth', 'role:student'])->group(...)
     * 
     * O múltiples roles:
     *   Route::middleware(['auth', 'role:admin,teacher'])->group(...)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Normalizar roles: admin → 1, teacher → 2, student → 3
        $allowedRoles = [];
        foreach ($roles as $role) {
            $allowedRoles[] = match($role) {
                'admin' => 1,
                'teacher' => 2,
                'student' => 3,
                default => null,
            };
        }
        $allowedRoles = array_filter($allowedRoles);

        // Verificar si el usuario tiene uno de los perfiles (perfil_id en la BD)
        if (!in_array($request->user()->perfil_id, $allowedRoles)) {
            abort(403, 'No tienes permiso para acceder a este recurso.');
        }

        return $next($request);
    }
}
