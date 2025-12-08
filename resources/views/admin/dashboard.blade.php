@extends('layouts.app')

@section('title', 'Panel de Administrador')

@section('content')
<!-- Page Header with Background -->
<div style="background: url('{{ asset('img/ITSSMT/fondo.jpg') }}'); background-size: cover; background-position: center; background-blend-mode: overlay; padding: 50px 0; margin-bottom: 40px;">
    <div class="container-xl">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                    <img src="{{ asset('img/ITSSMT/ITSSMT.png') }}" alt="ITSSMT" style="height: 50px;">
                    <h1 class="mb-0" style="font-size: 2.5rem;">Panel de Administrador</h1>
                </div>
                <p class="mb-0 opacity-75">Bienvenido, <strong>{{ Auth::user()->nombre ?? 'Admin' }}</strong> | Gestión integral del sistema</p>
            </div>
        </div>
        
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" class="mt-3">
            <ol class="breadcrumb" style="background: rgba(255,255,255,0.1); border-radius: 5px; padding: 10px 15px; margin: 0;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: white;">Inicio</a></li>
                <li class="breadcrumb-item active" style="color: rgba(255,255,255,0.7);">Panel Administrativo</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-xl">
    <!-- Stats Section -->
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-people" style="font-size: 3rem; color: #1B396A;"></i>
                    <h6 class="text-muted mt-3 mb-1">Total de Usuarios</h6>
                    <h2 class="mb-0" style="color: #1B396A; font-weight: 600;">{{ $totalUsers ?? 8 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle" style="font-size: 3rem; color: #28a745;"></i>
                    <h6 class="text-muted mt-3 mb-1">Usuarios Activos</h6>
                    <h2 class="mb-0" style="color: #28a745; font-weight: 600;">{{ $activeUsers ?? 7 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-folder2-open" style="font-size: 3rem; color: #1B396A;"></i>
                    <h6 class="text-muted mt-3 mb-1">Proyectos</h6>
                    <h2 class="mb-0" style="color: #1B396A; font-weight: 600;">{{ $totalProjects ?? 3 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-mortarboard" style="font-size: 3rem; color: #2D5A96;"></i>
                    <h6 class="text-muted mt-3 mb-1">Asignaturas</h6>
                    <h2 class="mb-0" style="color: #2D5A96; font-weight: 600;">{{ $totalAsignaturas ?? 3 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Acciones Rápidas -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border: 0;">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning"></i> Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action px-4 py-3 d-flex align-items-center gap-3">
                            <i class="bi bi-people-fill" style="font-size: 1.5rem; color: #2D5A96;"></i>
                            <div>
                                <h6 class="mb-0">Gestionar Usuarios</h6>
                                <small class="text-muted">Ver, editar y crear usuarios</small>
                            </div>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                        <a href="{{ route('admin.projects.index') }}" class="list-group-item list-group-item-action px-4 py-3 d-flex align-items-center gap-3">
                            <i class="bi bi-diagram-3" style="font-size: 1.5rem; color: #1B396A;"></i>
                            <div>
                                <h6 class="mb-0">Proyectos</h6>
                                <small class="text-muted">Gestionar proyectos</small>
                            </div>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                        <a href="{{ route('admin.subjects.index') }}" class="list-group-item list-group-item-action px-4 py-3 d-flex align-items-center gap-3">
                            <i class="bi bi-book" style="font-size: 1.5rem; color: #2D5A96;"></i>
                            <div>
                                <h6 class="mb-0">Asignaturas</h6>
                                <small class="text-muted">Gestionar asignaturas</small>
                            </div>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                        <a href="{{ route('admin.advisers.index') }}" class="list-group-item list-group-item-action px-4 py-3 d-flex align-items-center gap-3">
                            <i class="bi bi-person-check" style="font-size: 1.5rem; color: #1B396A;"></i>
                            <div>
                                <h6 class="mb-0">Asesores</h6>
                                <small class="text-muted">Gestionar asesores</small>
                            </div>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                        <a href="{{ route('admin.deliverables.index') }}" class="list-group-item list-group-item-action px-4 py-3 d-flex align-items-center gap-3">
                            <i class="bi bi-inbox-fill" style="font-size: 1.5rem; color: #2D5A96;"></i>
                            <div>
                                <h6 class="mb-0">Entregables</h6>
                                <small class="text-muted">Gestionar entregables</small>
                            </div>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                        <a href="{{ route('admin.competencies.index') }}" class="list-group-item list-group-item-action px-4 py-3 d-flex align-items-center gap-3">
                            <i class="bi bi-lightbulb" style="font-size: 1.5rem; color: #1B396A;"></i>
                            <div>
                                <h6 class="mb-0">Competencias</h6>
                                <small class="text-muted">Gestionar competencias</small>
                            </div>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                        <a href="{{ route('admin.graphs.index') }}" class="list-group-item list-group-item-action px-4 py-3 d-flex align-items-center gap-3">
                            <i class="bi bi-diagram-3" style="font-size: 1.5rem; color: #2D5A96;"></i>
                            <div>
                                <h6 class="mb-0">Grafos</h6>
                                <small class="text-muted">Gestionar grafos</small>
                            </div>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen General -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border: 0;">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart"></i> Resumen General
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Usuarios Activos</span>
                            <strong>{{ $activeUsers ?? 7 }} / {{ $totalUsers ?? 8 }}</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" style="background: #1B396A; width: {{ ($activeUsers ?? 7) / ($totalUsers ?? 8) * 100 }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Docentes</span>
                            <strong>{{ $totalTeachers ?? 2 }} usuarios</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" style="background: #2D5A96; width: 25%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Estudiantes</span>
                            <strong>{{ $totalStudents ?? 5 }} usuarios</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" style="background: #28a745; width: 62%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Usuarios Recientes -->
    <div class="row g-4 mt-2">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border: 0;">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Usuarios Recientes
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #f5f7fb; border-top: none;">
                            <tr>
                                <th style="color: #1B396A;">Nombre</th>
                                <th style="color: #1B396A;">Email</th>
                                <th style="color: #1B396A;">Rol</th>
                                <th style="color: #1B396A;">Estado</th>
                                <th style="color: #1B396A;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>Admin User</strong>
                                </td>
                                <td>admin@sw.test</td>
                                <td><span class="badge" style="background: #1B396A;">Administrador</span></td>
                                <td><span class="badge bg-success">Activo</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Docente Uno</strong>
                                </td>
                                <td>docente1@sw.test</td>
                                <td><span class="badge" style="background: #2D5A96;">Docente</span></td>
                                <td><span class="badge bg-success">Activo</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer" style="background: #f5f7fb;">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">Ver todos los usuarios →</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    transition: all 0.3s ease;
    background: #f5f7fb;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(27, 57, 106, 0.15) !important;
}

.progress-bar {
    transition: width 0.3s ease;
}

.list-group-item {
    border: none;
    border-bottom: 1px solid #e0e0e0;
}

.list-group-item:hover {
    background: #f5f7fb;
}

.list-group-item:last-child {
    border-bottom: none;
}
</style>
@endsection