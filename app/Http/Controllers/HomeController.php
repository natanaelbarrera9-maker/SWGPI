<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * GET /
     * Página de inicio (index)
     */
    public function index()
    {
        // Si el usuario está autenticado, redirigir al dashboard según su perfil
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isTeacher()) {
                return redirect()->route('teacher.dashboard');
            } else {
                return redirect()->route('student.dashboard');
            }
        }

        // Si no está autenticado, mostrar página de inicio pública
        return view('index');
    }
}
