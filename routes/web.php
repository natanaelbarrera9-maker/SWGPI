<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\AdvisersController;
use App\Http\Controllers\DeliverablesController;
use App\Http\Controllers\CompetenciesController;
use App\Http\Controllers\GraphsController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PasswordRecoveryController;

// Ruta raíz: Página de inicio pública
Route::get('/', [HomeController::class, 'index'])->name('home');

// ========================
// AUTENTICACIÓN
// ========================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    
    // Recuperación de Contraseña - Nuevo Flujo Limpio
    Route::get('/password-recovery', [PasswordRecoveryController::class, 'showRequest'])->name('password-recovery.request');
    Route::post('/password-recovery', [PasswordRecoveryController::class, 'handleRequest']);
    Route::get('/password-recovery-verify', [PasswordRecoveryController::class, 'showVerify'])->name('password-recovery.verify');
    Route::post('/password-recovery-verify', [PasswordRecoveryController::class, 'handleVerify']);
    Route::get('/password-recovery-reset', [PasswordRecoveryController::class, 'showReset'])->name('password-recovery.reset');
    Route::post('/password-recovery-reset', [PasswordRecoveryController::class, 'handleReset']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ========================
// DASHBOARDS SEGÚN ROL
// ========================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Dashboard Admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::resource('admin/users', UsersController::class, ['as' => 'admin']);
        Route::post('admin/users/{user}/toggle-active', [UsersController::class, 'toggleActive'])->name('admin.users.toggle-active');
        Route::get('admin/inactive-users', [UsersController::class, 'getInactive'])->name('admin.inactive-users');
        Route::get('admin/users/search', [UsersController::class, 'search'])->name('admin.users.search');
        
        // Rutas CRUD adicionales
        Route::resource('admin/projects', ProjectsController::class, ['as' => 'admin']);
        Route::resource('admin/subjects', SubjectsController::class, ['as' => 'admin']);
        Route::resource('admin/advisers', AdvisersController::class, ['as' => 'admin']);
        // Ruta personalizada para destroy de asesores con dos parámetros (projectId, userId)
        Route::delete('admin/advisers/{projectId}/{userId}', [AdvisersController::class, 'destroy'])->name('admin.advisers.destroy');
        Route::resource('admin/deliverables', DeliverablesController::class, ['as' => 'admin']);
        Route::resource('admin/competencies', CompetenciesController::class, ['as' => 'admin']);
        Route::resource('admin/graphs', GraphsController::class, ['as' => 'admin']);
    });
    
    // Dashboard Docente
    Route::middleware('role:teacher')->group(function () {
        Route::get('/teacher', [DashboardController::class, 'teacherDashboard'])->name('teacher.dashboard');
    });
    
    // Dashboard Estudiante
    Route::middleware('role:student')->group(function () {
        Route::get('/student', [DashboardController::class, 'studentDashboard'])->name('student.dashboard');
    });
});
