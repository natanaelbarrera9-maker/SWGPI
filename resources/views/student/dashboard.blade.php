@extends('layouts.app')

@section('title', 'Panel de Estudiante')

@section('content')
<!-- Page Header with Background -->
<div style="background: url('{{ asset('img/ITSSMT/fondo.jpg') }}'); background-size: cover; background-position: center; background-blend-mode: overlay; padding: 50px 0; margin-bottom: 40px;">
    <div class="container-xl">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                    <img src="{{ asset('img/ITSSMT/ITSSMT.png') }}" alt="ITSSMT" style="height: 50px;">
                    <h1 class="mb-0" style="font-size: 2.5rem;">Panel de Estudiante</h1>
                </div>
                <p class="mb-0 opacity-75">Bienvenido, <strong>{{ Auth::user()->nombre ?? 'Estudiante' }}</strong> | Gestión de entregas y proyectos</p>
            </div>
        </div>
        
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" class="mt-3">
            <ol class="breadcrumb" style="background: rgba(255,255,255,0.1); border-radius: 5px; padding: 10px 15px; margin: 0;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: white;">Inicio</a></li>
                <li class="breadcrumb-item active" style="color: rgba(255,255,255,0.7);">Panel de Estudiante</li>
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
                    <i class="bi bi-folder" style="font-size: 3rem; color: #1B396A;"></i>
                    <h6 class="text-muted mt-3 mb-1">Mis Proyectos</h6>
                    <h2 class="mb-0" style="color: #1B396A; font-weight: 600;">{{ $projectsCount ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-arrow-up" style="font-size: 3rem; color: #28a745;"></i>
                    <h6 class="text-muted mt-3 mb-1">Entregas Realizadas</h6>
                    <h2 class="mb-0" style="color: #28a745; font-weight: 600;">{{ $deliveriesCount ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-chat-left-dots" style="font-size: 3rem; color: #2D5A96;"></i>
                    <h6 class="text-muted mt-3 mb-1">Retroalimentación Recibida</h6>
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
                            <i class="bi bi-folder-open" style="font-size: 1.5rem; color: #1B396A;"></i>
                            <div>
                                <h6 class="mb-0">Ver Mis Proyectos</h6>
                                <small class="text-muted">Visualiza tus proyectos asignados</small>
                            </div>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action px-4 py-3 d-flex align-items-center gap-3">
                            <i class="bi bi-cloud-arrow-up" style="font-size: 1.5rem; color: #2D5A96;"></i>
                            <div>
                                <h6 class="mb-0">Enviar Entrega</h6>
                                <small class="text-muted">Sube tus trabajos completados</small>
                            </div>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action px-4 py-3 d-flex align-items-center gap-3">
                            <i class="bi bi-chat-left-text" style="font-size: 1.5rem; color: #1B396A;"></i>
                            <div>
                                <h6 class="mb-0">Ver Retroalimentación</h6>
                                <small class="text-muted">Lee los comentarios del docente</small>
                            </div>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Próximas Entregas -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border: 0;">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event"></i> Próximas Entregas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Propuesta Inicial</h6>
                                    <small class="text-muted">Vencimiento: 2025-01-20</small>
                                </div>
                                <span class="badge bg-warning">3 días</span>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Documento Final</h6>
                                    <small class="text-muted">Vencimiento: 2025-02-10</small>
                                </div>
                                <span class="badge" style="background: #2D5A96;">24 días</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mis Entregas -->
    <div class="row g-4 mt-2">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border: 0;">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-arrow-up"></i> Mis Entregas
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #f5f7fb; border-top: none;">
                            <tr>
                                <th style="color: #1B396A;">Entregable</th>
                                <th style="color: #1B396A;">Fecha Entrega</th>
                                <th style="color: #1B396A;">Estado</th>
                                <th style="color: #1B396A;">Calificación</th>
                                <th style="color: #1B396A;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Propuesta Inicial</strong></td>
                                <td>2025-01-15</td>
                                <td><span class="badge bg-success">Entregado</span></td>
                                <td><strong style="color: #1B396A;">95%</strong></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a>
                                    <a href="#" class="btn btn-sm btn-outline-info"><i class="bi bi-chat"></i> Feedback</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer" style="background: #f5f7fb;">
                    <small class="text-muted">Mostrando 1 entrega de 3 total</small>
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
