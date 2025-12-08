@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<!-- Page Header with Background -->
<div style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%), url('{{ asset('img/ITSSMT/fondo.jpg') }}'); background-size: cover; background-position: center; background-blend-mode: overlay; color: white; padding: 50px 0; margin-bottom: 40px;">
    <div class="container-xl">
        <div>
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                <i class="bi bi-pencil-square" style="font-size: 2.5rem;"></i>
                <h1 class="mb-0" style="font-size: 2.5rem;">Editar Usuario</h1>
            </div>
            <p class="mb-0 opacity-75">Matrícula: <code style="background: rgba(255,255,255,0.2); padding: 2px 6px; border-radius: 3px;">{{ $user->id }}</code></p>
            
            <!-- Breadcrumbs -->
            <nav aria-label="breadcrumb" class="mt-3">
                <ol class="breadcrumb" style="background: rgba(255,255,255,0.1); border-radius: 5px; padding: 10px 15px; margin: 0;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: white;">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}" style="color: white;">Usuarios</a></li>
                    <li class="breadcrumb-item active" style="color: rgba(255,255,255,0.7);">Editar</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container-xl">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="bi bi-exclamation-triangle"></i> Errores en el formulario:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- Información Personal -->
                        <h5 class="mb-3" style="color: #1B396A; border-bottom: 2px solid #1B396A; padding-bottom: 10px;">
                            <i class="bi bi-person"></i> Información Personal
                        </h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="id" class="form-label">
                                    <i class="bi bi-card-text"></i> Matrícula/Nómina <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="id" 
                                    value="{{ $user->id }}"
                                    readonly
                                >
                                <small class="text-muted">No se puede cambiar</small>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> Correo Electrónico <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    id="email" 
                                    name="email"
                                    value="{{ old('email', $user->email) }}"
                                    required
                                >
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="nombres" class="form-label">
                                    <i class="bi bi-person-badge"></i> Nombres <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control @error('nombres') is-invalid @enderror" 
                                    id="nombres" 
                                    name="nombres"
                                    value="{{ old('nombres', $user->nombres) }}"
                                    required
                                >
                                @error('nombres')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="apa" class="form-label">
                                    <i class="bi bi-person-badge"></i> Apellido Paterno <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control @error('apa') is-invalid @enderror" 
                                    id="apa" 
                                    name="apa"
                                    value="{{ old('apa', $user->apa) }}"
                                    required
                                >
                                @error('apa')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="ama" class="form-label">
                                    <i class="bi bi-person-badge"></i> Apellido Materno <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control @error('ama') is-invalid @enderror" 
                                    id="ama" 
                                    name="ama"
                                    value="{{ old('ama', $user->ama) }}"
                                    required
                                >
                                @error('ama')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Información de Cuenta -->
                        <h5 class="mb-3 mt-4" style="color: #1B396A; border-bottom: 2px solid #1B396A; padding-bottom: 10px;">
                            <i class="bi bi-lock"></i> Información de Cuenta
                        </h5>

                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle"></i> 
                            Deja los campos de contraseña vacíos si no deseas cambiarla
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">
                                    <i class="bi bi-key"></i> Nueva Contraseña
                                </label>
                                <input 
                                    type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password"
                                    placeholder="Déjalo en blanco para mantener la actual"
                                >
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-key"></i> Confirmar Contraseña
                                </label>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="password_confirmation" 
                                    name="password_confirmation"
                                    placeholder="Repite la contraseña"
                                >
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="perfil_id" class="form-label">
                                <i class="bi bi-shield"></i> Rol <span class="text-danger">*</span>
                            </label>
                            <select 
                                class="form-select @error('perfil_id') is-invalid @enderror" 
                                id="perfil_id" 
                                name="perfil_id"
                                required
                            >
                                <option value="">-- Selecciona un rol --</option>
                                <option value="1" @if(old('perfil_id', $user->perfil_id) == 1) selected @endif>
                                    Administrador
                                </option>
                                <option value="2" @if(old('perfil_id', $user->perfil_id) == 2) selected @endif>
                                    Docente
                                </option>
                                <option value="3" @if(old('perfil_id', $user->perfil_id) == 3) selected @endif>
                                    Estudiante
                                </option>
                            </select>
                            @error('perfil_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Estado -->
                        <h5 class="mb-3 mt-4" style="color: #1B396A; border-bottom: 2px solid #1B396A; padding-bottom: 10px;">
                            <i class="bi bi-toggle-on"></i> Estado
                        </h5>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    id="activo" 
                                    name="activo"
                                    value="1"
                                    @if(old('activo', $user->activo) == 1) checked @endif
                                >
                                <label class="form-check-label" for="activo">
                                    <strong>Usuario Activo</strong>
                                    <small class="text-muted d-block">Desactiva para inhabilitar el usuario</small>
                                </label>
                            </div>
                        </div>

                        <!-- Información Adicional -->
                        <div class="alert alert-secondary" role="alert">
                            <small>
                                <i class="bi bi-calendar"></i> Creado: {{ $user->created_at?->format('d/m/Y H:i') }}<br>
                            </small>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Guardar Cambios
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Datos del Usuario -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="background: #f5f7fb;">
                <div class="card-body">
                    <h5 style="color: #1B396A; margin-bottom: 15px;">
                        <i class="bi bi-person-circle"></i> Datos Actuales
                    </h5>

                    <div class="mb-3">
                        <small class="text-muted">MATRÍCULA</small>
                        <h6 class="text-dark" style="color: #1B396A;">{{ $user->id }}</h6>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">ROL ACTUAL</small>
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
                        <div class="mt-1">
                            <span class="badge" style="background: {{ $rolColor }}; font-size: 0.9rem;">
                                {{ $rolText }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">ESTADO</small>
                        <h6 class="text-dark mt-1">
                            @if ($user->activo)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Activo
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle"></i> Inactivo
                                </span>
                            @endif
                        </h6>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <small class="text-muted">EMAIL</small>
                        <p class="text-dark mb-0">{{ $user->email }}</p>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">NOMBRE COMPLETO</small>
                        <p class="text-dark mb-0">{{ $user->full_name }}</p>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash"></i> Eliminar Usuario
                        </button>
                    </div>
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
