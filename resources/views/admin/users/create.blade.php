@extends('layouts.app')

@section('title', 'Crear Nuevo Usuario')

@section('content')
<!-- Page Header with Background -->
<div style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%), url('{{ asset('img/ITSSMT/fondo.jpg') }}'); background-size: cover; background-position: center; background-blend-mode: overlay; color: white; padding: 50px 0; margin-bottom: 40px;">
    <div class="container-xl">
        <div>
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                <i class="bi bi-person-plus" style="font-size: 2.5rem;"></i>
                <h1 class="mb-0" style="font-size: 2.5rem;">Crear Nuevo Usuario</h1>
            </div>
            <p class="mb-0 opacity-75">Registra un nuevo usuario en el sistema SGPI ITSSMT</p>
            
            <!-- Breadcrumbs -->
            <nav aria-label="breadcrumb" class="mt-3">
                <ol class="breadcrumb" style="background: rgba(255,255,255,0.1); border-radius: 5px; padding: 10px 15px; margin: 0;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: white;">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}" style="color: white;">Usuarios</a></li>
                    <li class="breadcrumb-item active" style="color: rgba(255,255,255,0.7);">Crear Nuevo</li>
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

                    <form method="POST" action="{{ route('admin.users.store') }}" novalidate>
                        @csrf

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
                                    class="form-control @error('id') is-invalid @enderror" 
                                    id="id" 
                                    name="id"
                                    value="{{ old('id') }}"
                                    placeholder="Ej: 0000000001"
                                    required
                                >
                                @error('id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
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
                                    value="{{ old('email') }}"
                                    placeholder="usuario@ejemplo.com"
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
                                    value="{{ old('nombres') }}"
                                    placeholder="Juan"
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
                                    value="{{ old('apa') }}"
                                    placeholder="Pérez"
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
                                    value="{{ old('ama') }}"
                                    placeholder="García"
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

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">
                                    <i class="bi bi-key"></i> Contraseña <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password"
                                    placeholder="Mínimo 8 caracteres"
                                    required
                                >
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-1">
                                    <i class="bi bi-info-circle"></i> La contraseña debe tener al menos 8 caracteres
                                </small>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-key"></i> Confirmar Contraseña <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="password_confirmation" 
                                    name="password_confirmation"
                                    placeholder="Repite la contraseña"
                                    required
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
                                <option value="1" @if(old('perfil_id') == 1) selected @endif>
                                    Administrador
                                </option>
                                <option value="2" @if(old('perfil_id') == 2) selected @endif>
                                    Docente
                                </option>
                                <option value="3" @if(old('perfil_id') == 3) selected @endif>
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
                                    @if(old('activo', 1) == 1) checked @endif
                                >
                                <label class="form-check-label" for="activo">
                                    <strong>Usuario Activo</strong>
                                    <small class="text-muted d-block">Desactiva esta opción para crear un usuario inactivo</small>
                                </label>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-circle"></i> Crear Usuario
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ayuda -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="background: #f5f7fb;">
                <div class="card-body">
                    <h5 style="color: #1B396A; margin-bottom: 15px;">
                        <i class="bi bi-lightbulb"></i> Información de Ayuda
                    </h5>

                    <div class="mb-3">
                        <h6 class="text-dark">Roles Disponibles:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <span class="badge" style="background: #1B396A;">Admin</span>
                                <small class="ms-2">Acceso total al sistema</small>
                            </li>
                            <li class="mb-2">
                                <span class="badge" style="background: #2D5A96;">Docente</span>
                                <small class="ms-2">Asesora proyectos de estudiantes</small>
                            </li>
                            <li>
                                <span class="badge" style="background: #28a745;">Estudiante</span>
                                <small class="ms-2">Realiza entregas en proyectos</small>
                            </li>
                        </ul>
                    </div>

                    <hr>

                    <div>
                        <h6 class="text-dark">Requerimientos:</h6>
                        <ul class="list-unstyled text-muted small">
                            <li class="mb-1">
                                <i class="bi bi-check-circle"></i> Matrícula única
                            </li>
                            <li class="mb-1">
                                <i class="bi bi-check-circle"></i> Email válido
                            </li>
                            <li class="mb-1">
                                <i class="bi bi-check-circle"></i> Contraseña mínimo 8 caracteres
                            </li>
                            <li>
                                <i class="bi bi-check-circle"></i> Las contraseñas deben coincidir
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
