@extends('layouts.app')

@section('title', 'Ver Usuario')

@section('content')
<!-- Page Header with Background -->
<div style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%), url('{{ asset('img/ITSSMT/fondo.jpg') }}'); background-size: cover; background-position: center; background-blend-mode: overlay; color: white; padding: 50px 0; margin-bottom: 40px;">
    <div class="container-xl">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                    <i class="bi bi-person-circle" style="font-size: 2.5rem;"></i>
                    <h1 class="mb-0" style="font-size: 2.5rem;">{{ $user->full_name }}</h1>
                </div>
                <p class="mb-0 opacity-75">Matrícula: <code style="background: rgba(255,255,255,0.2); padding: 2px 6px; border-radius: 3px;">{{ $user->id }}</code></p>
                
                <!-- Breadcrumbs -->
                <nav aria-label="breadcrumb" class="mt-3">
                    <ol class="breadcrumb" style="background: rgba(255,255,255,0.1); border-radius: 5px; padding: 10px 15px; margin: 0;">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: white;">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}" style="color: white;">Usuarios</a></li>
                        <li class="breadcrumb-item active" style="color: rgba(255,255,255,0.7);">Ver</li>
                    </ol>
                </nav>
            </div>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-light">
                    <i class="bi bi-pencil"></i> Editar
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container-xl">
    <div class="row g-4">
        <!-- Información Principal -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 style="color: #1B396A; margin-bottom: 20px;">
                        <i class="bi bi-person"></i> Información Personal
                    </h5>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted">Nombre completo</label>
                            <p class="h6 text-dark">{{ $user->full_name }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Matrícula/Nómina</label>
                            <p class="h6 text-dark">
                                <code style="background: #f5f7fb; padding: 4px 8px; border-radius: 4px;">{{ $user->id }}</code>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Correo Electrónico</label>
                            <p class="h6 text-dark">
                                <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                    {{ $user->email }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 style="color: #1B396A; margin-bottom: 20px;">
                        <i class="bi bi-shield"></i> Información de Cuenta
                    </h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Rol</label>
                            @php
                                $rolColor = match($user->perfil_id) {
                                    1 => '#1B396A',
                                    2 => '#2D5A96',
                                    3 => '#28a745',
                                    default => '#6c757d'
                                };
                                $rolText = match($user->perfil_id) {
                                    1 => 'Administrador',
                                    2 => 'Docente',
                                    3 => 'Estudiante',
                                    default => 'Desconocido'
                                };
                            @endphp
                            <p>
                                <span class="badge" style="background: {{ $rolColor }}; font-size: 0.95rem; padding: 6px 12px;">
                                    {{ $rolText }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Estado</label>
                            <p>
                                @if ($user->activo)
                                    <span class="badge bg-success" style="font-size: 0.95rem; padding: 6px 12px;">
                                        <i class="bi bi-check-circle"></i> Activo
                                    </span>
                                @else
                                    <span class="badge bg-danger" style="font-size: 0.95rem; padding: 6px 12px;">
                                        <i class="bi bi-x-circle"></i> Inactivo
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 style="color: #1B396A; margin-bottom: 20px;">
                        <i class="bi bi-clock-history"></i> Fechas
                    </h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Fecha de Creación</label>
                            <p class="h6 text-dark">
                                {{ $user->created_at?->format('d/m/Y H:i:s') ?? 'No registrado' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="col-lg-4">
            <!-- Acciones Rápidas -->
            <div class="card border-0 shadow-sm mb-4" style="background: #f5f7fb;">
                <div class="card-body">
                    <h6 class="mb-3" style="color: #1B396A;">
                        <i class="bi bi-lightning"></i> Acciones
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil"></i> Editar Usuario
                        </a>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="card border-0 shadow-sm" style="background: #f5f7fb;">
                <div class="card-body">
                    <h6 class="mb-3" style="color: #1B396A;">
                        <i class="bi bi-bar-chart"></i> Información Adicional
                    </h6>

                    @if ($user->perfil_id == 1)
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">PERMISOS</small>
                            <small class="text-dark">Acceso total al sistema</small>
                        </div>
                    @elseif ($user->perfil_id == 2)
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">PROYECTOS ASESORADOS</small>
                            <small class="text-dark">
                                @if ($user->projects()->count() > 0)
                                    {{ $user->projects()->count() }} proyecto(s)
                                @else
                                    Sin proyectos asignados
                                @endif
                            </small>
                        </div>
                    @elseif ($user->perfil_id == 3)
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">PROYECTOS PARTICIPANDO</small>
                            <small class="text-dark">
                                @if ($user->projects()->count() > 0)
                                    {{ $user->projects()->count() }} proyecto(s)
                                @else
                                    Sin proyectos asignados
                                @endif
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle"></i> Eliminar Usuario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>
                    ¿Está seguro de que desea eliminar a <strong>{{ $user->full_name }}</strong> ({{ $user->id }})?
                </p>
                <p class="text-danger mb-0">
                    <i class="bi bi-exclamation-circle"></i> Esta acción no se puede deshacer.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
