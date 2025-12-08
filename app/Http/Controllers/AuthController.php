<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * GET /login
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * POST /login
     * Procesar login
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt(['id' => $credentials['id'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            // Verificar que el usuario esté activo
            if (!Auth::user()->activo) {
                Auth::logout();
                return back()->withErrors(['id' => 'Tu cuenta ha sido desactivada.']);
            }

            // Redirigir según rol
            return redirect()->route(
                Auth::user()->isAdmin() ? 'admin.dashboard' :
                (Auth::user()->isTeacher() ? 'teacher.dashboard' : 'student.dashboard')
            );
        }

        return back()
            ->withInput($request->only('id'))
            ->withErrors(['id' => 'Las credenciales no son válidas.']);
    }

    /**
     * POST /logout
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * GET /forgot-password
     * Mostrar formulario de recuperación de contraseña
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * POST /forgot-password
     * Enviar enlace de reset
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        // TODO: Implementar envío de email con token de reset
        // Por ahora, mensaje simple
        
        return back()->with('status', 'Se ha enviado un enlace de recuperación a tu email');
    }

    /**
     * GET /reset-password/{token}
     * Mostrar formulario de reset
     */
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * POST /reset-password
     * Procesar reset de contraseña
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // TODO: Verificar token y actualizar contraseña

        return redirect('/login')->with('status', 'Contraseña reestablecida exitosamente');
    }
}
