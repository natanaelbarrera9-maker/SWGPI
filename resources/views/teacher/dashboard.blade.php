@extends('layouts.app')

@section('title', 'Panel de Docente')

@section('content')
<!-- Page Header with Background -->
<div style="background: url('{{ asset('img/ITSSMT/fondo.jpg') }}'); background-size: cover; background-position: center; background-blend-mode: overlay; padding: 50px 0; margin-bottom: 40px;">
    <div class="container-xl">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                    <img src="{{ asset('img/ITSSMT/ITSSMT.png') }}" alt="ITSSMT" style="height: 50px;">
                    <h1 class="mb-0" style="font-size: 2.5rem;">Panel de Docente</h1>
                </div>
                <p class="mb-0 opacity-75">Bienvenido, <strong>{{ Auth::user()->nombre ?? 'Docente' }}</strong> | Gestión de proyectos y evaluaciones</p>
            </div>
        </div>
        
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" class="mt-3">
            <ol class="breadcrumb" style="background: rgba(255,255,255,0.1); border-radius: 5px; padding: 10px 15px; margin: 0;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: white;">Inicio</a></li>
                <li class="breadcrumb-item active" style="color: rgba(255,255,255,0.7);">Panel de Docente</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-xl">

    <!-- Stats Section -->
    <div class="row g-4 mb-5">
        <div class="col-lg-4 col-md-6">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-folder-check" style="font-size: 3rem; color: #1B396A;"></i>
                    <h6 class="text-muted mt-3 mb-1">Proyectos Asesorados</h6>
                    <h2 class="mb-0" style="color: #1B396A; font-weight: 600;">{{ $projectsCount ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-check" style="font-size: 3rem; color: #28a745;"></i>
                    <h6 class="text-muted mt-3 mb-1">Entregas Recibidas</h6>
                    <h2 class="mb-0" style="color: #28a745; font-weight: 600;">{{ $deliveriesCount ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-chat-left-text" style="font-size: 3rem; color: #2D5A96;"></i>
                    <h6 class="text-muted mt-3 mb-1">Retroalimentaciones</h6>
                    <h2 class="mb-0" style="color: #2D5A96; font-weight: 600;">{{ $feedbackCount ?? 0 }}</h2>
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
                        <a href="#" class="list-group-item list-group-item-action px-4 py-3 d-flex align-items-center gap-3">
                            <i class="bi bi-file-earmark" style="font-size: 1.5rem; color: #1B396A;"></i>
                            <div>
                                <h6 class="mb-0">Ver Entregas</h6>
                                <small class="text-muted">Revisar entregas de estudiantes</small>
                            </div>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action px-4 py-3 d-flex align-items-center gap-3">
                            <i class="bi bi-chat-dots" style="font-size: 1.5rem; color: #2D5A96;"></i>
                            <div>
                                <h6 class="mb-0">Dar Retroalimentación</h6>
                                <small class="text-muted">Proporcionar feedback a estudiantes</small>
                            </div>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action px-4 py-3 d-flex align-items-center gap-3">
                            <i class="bi bi-graph-up" style="font-size: 1.5rem; color: #1B396A;"></i>
                            <div>
                                <h6 class="mb-0">Ver Progreso</h6>
                                <small class="text-muted">Monitorear avance de proyectos</small>
                            </div>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proyectos Asesorados -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border: 0;">
                    <h5 class="mb-0">
                        <i class="bi bi-folder-check"></i> Proyectos Asesorados
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Proyecto 1</h6>
                                    <small class="text-muted">Grupo: Equipo A • Estado: En Progreso</small>
                                </div>
                                <span class="badge" style="background: #2D5A96;">En curso</span>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Proyecto 2</h6>
                                    <small class="text-muted">Grupo: Equipo B • Estado: En Progreso</small>
                                </div>
                                <span class="badge" style="background: #2D5A96;">En curso</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Entregas Pendientes -->
    <div class="row g-4 mt-2">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border: 0;">
                    <h5 class="mb-0">
                        <i class="bi bi-inbox"></i> Entregas Pendientes de Revisar
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #f5f7fb; border-top: none;">
                            <tr>
                                <th style="color: #1B396A;">Entregable</th>
                                <th style="color: #1B396A;">Grupo</th>
                                <th style="color: #1B396A;">Fecha Entrega</th>
                                <th style="color: #1B396A;">Estado</th>
                                <th style="color: #1B396A;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Propuesta Inicial</strong></td>
                                <td>Equipo A</td>
                                <td>2025-01-15</td>
                                <td><span class="badge bg-warning">Pendiente Revisión</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a>
                                    <a href="#" class="btn btn-sm btn-outline-success"><i class="bi bi-pencil"></i> Comentar</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer" style="background: #f5f7fb;">
                    <small class="text-muted">Mostrando 1 entrega pendiente de 3 total</small>
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
</div>
@endsection
