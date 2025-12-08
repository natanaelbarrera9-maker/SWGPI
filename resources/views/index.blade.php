@extends('layouts.app')

@section('title', 'Bienvenido')

@section('content')
<!-- Hero Section with Background -->
<div style="background: url('{{ asset('img/ITSSMT/fondo.jpg') }}'); background-size: cover; background-position: center; background-blend-mode: overlay; padding: 100px 0; position: relative; overflow: hidden;">
    <div class="container-xl">
        <div class="row d-flex align-items-center">
            <div class="col-lg-8">
                <div style="animation: fadeInUp 1s ease-in;">
                    <h1 class="display-3 fw-bold mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                        Sistema de Gestión de Proyectos Integradores
                    </h1>
                    <p class="lead mb-4" style="font-size: 1.3rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                        Administra, califica y monitorea proyectos académicos de forma eficiente en el ITSSMT
                    </p>
                    @guest
                        <div>
                            <a href="{{ route('login') }}" class="btn btn-light btn-lg me-3" style="color: #1B396A;">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </a>
                        </div>
                    @endguest
                </div>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block">
                <img src="{{ asset('img/ITSSMT/ITSSMT.png') }}" alt="ITSSMT" style="height: 150px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));">
            </div>
        </div>
    </div>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</div>

<section class="section">
    <div class="container-xl">
        @auth
            <!-- Usuario autenticado: Mostrar acceso directo -->
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="section-title">¡Bienvenido, {{ auth()->user()->nombres }}!</h2>
                    <p class="section-subtitle">Perfil: <strong>{{ auth()->user()->getPerfilName() }}</strong></p>
                </div>
            </div>

            <!-- Cards de Acciones Rápidas -->
            <div class="row g-4 mb-5">
                @if(auth()->user()->isAdmin())
                    <div class="col-lg-4">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="bi bi-speedometer2" style="font-size: 3rem; color: #1B396A;"></i>
                                <h5 class="card-title mt-3">Panel Administrativo</h5>
                                <p class="card-text text-muted">Gestiona usuarios, proyectos y configuración del sistema</p>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Ir al Panel</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="bi bi-people" style="font-size: 3rem; color: #1B396A;"></i>
                                <h5 class="card-title mt-3">Gestión de Usuarios</h5>
                                <p class="card-text text-muted">Crea, edita y administra cuentas de usuario</p>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Ver Usuarios</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="bi bi-box-arrow-right" style="font-size: 3rem; color: #1B396A;"></i>
                                <h5 class="card-title mt-3">Cerrar Sesión</h5>
                                <p class="card-text text-muted">Salir de tu cuenta</p>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger">Cerrar Sesión</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @elseif(auth()->user()->isTeacher())
                    <div class="col-lg-6">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="bi bi-speedometer2" style="font-size: 3rem; color: #1B396A;"></i>
                                <h5 class="card-title mt-3">Mi Dashboard Docente</h5>
                                <p class="card-text text-muted">Ver mis proyectos y entregas por calificar</p>
                                <a href="{{ route('teacher.dashboard') }}" class="btn btn-primary">Ir al Dashboard</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="bi bi-box-arrow-right" style="font-size: 3rem; color: #1B396A;"></i>
                                <h5 class="card-title mt-3">Cerrar Sesión</h5>
                                <p class="card-text text-muted">Salir de tu cuenta</p>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger">Cerrar Sesión</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-lg-6">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="bi bi-speedometer2" style="font-size: 3rem; color: #1B396A;"></i>
                                <h5 class="card-title mt-3">Mi Dashboard Estudiante</h5>
                                <p class="card-text text-muted">Ver mis proyectos y entregas</p>
                                <a href="{{ route('student.dashboard') }}" class="btn btn-primary">Ir al Dashboard</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="bi bi-box-arrow-right" style="font-size: 3rem; color: #1B396A;"></i>
                                <h5 class="card-title mt-3">Cerrar Sesión</h5>
                                <p class="card-text text-muted">Salir de tu cuenta</p>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger">Cerrar Sesión</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- Usuario no autenticado: Mostrar opciones de acceso -->
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="section-title">Acceso al Sistema</h2>
                    <p class="section-subtitle">Inicia sesión con tus credenciales para continuar</p>
                </div>
            </div>

            <!-- Cards de Features -->
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <i class="bi bi-shield-lock" style="font-size: 3rem; color: #1B396A;"></i>
                            <h5 class="card-title mt-3">Para Administradores</h5>
                            <p class="card-text text-muted">Gestiona usuarios, proyectos, asignaturas y competencias. Supervisa el progreso general del sistema.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <i class="bi bi-person-badge" style="font-size: 3rem; color: #1B396A;"></i>
                            <h5 class="card-title mt-3">Para Docentes</h5>
                            <p class="card-text text-muted">Asesora proyectos, califica entregas, proporciona feedback y monitorea el desempeño estudiantil.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <i class="bi bi-book" style="font-size: 3rem; color: #1B396A;"></i>
                            <h5 class="card-title mt-3">Para Estudiantes</h5>
                            <p class="card-text text-muted">Accede a tus proyectos, envía entregas, recibe calificaciones y monitorea tus plazos de entrega.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border: none;">
                        <div class="card-body text-center p-5">
                            <h5 class="card-title text-white mb-3">¿Listo para comenzar?</h5>
                            <p class="card-text mb-4">Ingresa con tus credenciales para acceder al sistema de gestión de proyectos integradores.</p>
                            <a href="{{ route('login') }}" class="btn btn-light me-2">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </a>
                            <a href="{{ route('forgot-password') }}" class="btn btn-outline-light">
                                <i class="bi bi-question-circle"></i> ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Demo Info -->
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <div class="alert alert-warning" role="alert">
                        <h5 class="alert-heading"><i class="bi bi-info-circle"></i> Información</h5>
                        <p class="mb-0">Si aún no tienes cuenta, contacta con un administrador para que te proporcione tus credenciales de acceso al sistema.</p>
                    </div>
                </div>
            </div>
        @endauth
    </div>
</section>
@endsection
