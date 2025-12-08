@extends('layouts.app')

@section('title', 'Gestionar Usuarios')

@section('content')
<!-- Page Header with Background -->
<div style="background: url('{{ asset('img/ITSSMT/fondo.jpg') }}'); background-size: cover; background-position: center; background-blend-mode: overlay; padding: 50px 0; margin-bottom: 40px;">
    <div class="container-xl">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                    <i class="bi bi-people" style="font-size: 2.5rem;"></i>
                    <h1 class="mb-0" style="font-size: 2.5rem;">Gestionar Usuarios</h1>
                </div>
                <p class="mb-0 opacity-75">Administra y supervisa todos los usuarios del sistema</p>
            </div>
            <a href="#" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="bi bi-person-plus"></i> Nuevo Usuario
            </a>
        </div>
        
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" class="mt-3">
            <ol class="breadcrumb" style="background: rgba(255,255,255,0.1); border-radius: 5px; padding: 10px 15px; margin: 0;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: white;">Dashboard</a></li>
                <li class="breadcrumb-item active" style="color: rgba(255,255,255,0.7);">Gestionar Usuarios</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-xl">
    <!-- Filters -->
    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="input-group">
                <span class="input-group-text" style="background: #f5f7fb; border-color: #ddd;">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" class="form-control" id="searchInput" placeholder="Buscar por nombre, email o matrícula...">
            </div>
        </div>
        <div class="col-lg-3">
            <select class="form-select" id="roleFilter" style="border-color: #ddd;">
                <option value="">Todos los Roles</option>
                <option value="1">Administrador</option>
                <option value="2">Docente</option>
                <option value="3">Estudiante</option>
            </select>
        </div>
        <div class="col-lg-3">
            <select class="form-select" id="statusFilter" style="border-color: #ddd;">
                <option value="">Todos los Estados</option>
                <option value="1">Activos</option>
                <option value="0">Inactivos</option>
            </select>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="usersTable">
                <thead style="background: #f5f7fb; border-top: none;">
                    <tr>
                        <th style="color: #1B396A;">Matrícula</th>
                        <th style="color: #1B396A;">Nombre</th>
                        <th style="color: #1B396A;">Email</th>
                        <th style="color: #1B396A;">Rol</th>
                        <th style="color: #1B396A;">Estado</th>
                        <th style="color: #1B396A;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                <code style="color: #1B396A; background: #f5f7fb; padding: 4px 8px; border-radius: 4px;">
                                    {{ $user->id }}
                                </code>
                            </td>
                            <td>
                                <strong>{{ $user->nombres ?? 'Sin nombre' }}</strong>
                                @if ($user->apa || $user->ama)
                                    <br><small class="text-muted">{{ trim(($user->apa ?? '') . ' ' . ($user->ama ?? '')) }}</small>
                                @endif
                            </td>
                            <td>
                                <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                    {{ $user->email ?? 'Sin email' }}
                                </a>
                            </td>
                            <td>
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
                                <span class="badge" style="background: {{ $rolColor }};">
                                    {{ $rolText }}
                                </span>
                            </td>
                            <td>
                                @if ($user->activo)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Activo
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-x-circle"></i> Inactivo
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-info" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Está seguro?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer" style="background: #f5f7fb;">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Mostrando <strong>{{ $users->count() }}</strong> de <strong>{{ $users->total() }}</strong> usuarios
                </small>
                @if ($users->hasPages())
                    <div>
                        {{ $users->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
table tbody tr:hover {
    background: #f5f7fb;
}

.btn-outline-primary:hover {
    background: #1B396A;
    border-color: #1B396A;
    color: white;
}

.btn-outline-info:hover {
    background: #2D5A96;
    border-color: #2D5A96;
    color: white;
}
</style>

<!-- Modal para crear nuevo usuario -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border: none;">
                <h5 class="modal-title" id="createUserLabel">
                    <i class="bi bi-person-plus"></i> Crear Nuevo Usuario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
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

                <form method="POST" action="{{ route('admin.users.store') }}" novalidate id="createUserForm">
                    @csrf

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

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="curp" class="form-label">
                                <i class="bi bi-fingerprint"></i> CURP
                            </label>
                            <input 
                                type="text" 
                                class="form-control @error('curp') is-invalid @enderror" 
                                id="curp" 
                                name="curp"
                                value="{{ old('curp') }}"
                                placeholder="Ej: ABCD123456HDFNLL09"
                                maxlength="18"
                            >
                            @error('curp')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="telefonos" class="form-label">
                                <i class="bi bi-telephone"></i> Teléfono
                            </label>
                            <input 
                                type="text" 
                                class="form-control @error('telefonos') is-invalid @enderror" 
                                id="telefonos" 
                                name="telefonos"
                                value="{{ old('telefonos') }}"
                                placeholder="Ej: 961 123 4567 o +52 9611234567"
                            >
                            @error('telefonos')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="direccion" class="form-label">
                                <i class="bi bi-geo-alt"></i> Dirección
                            </label>
                            <input 
                                type="text" 
                                class="form-control @error('direccion') is-invalid @enderror" 
                                id="direccion" 
                                name="direccion"
                                value="{{ old('direccion') }}"
                                placeholder="Calle, número, colonia, ciudad"
                            >
                            @error('direccion')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

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

                    <div class="row mb-3">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <label class="form-label" style="color: #1B396A;">
                                <i class="bi bi-toggle-on"></i> Estado
                            </label>
                            <div class="form-check form-switch">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    id="activo" 
                                    name="activo" 
                                    value="1"
                                    @if(old('activo', true)) checked @endif
                                >
                                <label class="form-check-label" for="activo">
                                    Usuario Activo
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 1rem; margin-top: 1.5rem;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); border: none;">
                            <i class="bi bi-check-circle"></i> Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    const table = document.getElementById('usersTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedRole = roleFilter.value;
        const selectedStatus = statusFilter.value;

        Array.from(rows).forEach(row => {
            let show = true;

            // Search filter
            if (searchTerm) {
                const text = row.textContent.toLowerCase();
                show = text.includes(searchTerm);
            }

            // Role filter
            if (show && selectedRole) {
                const roleCell = row.querySelector('td:nth-child(4)').textContent;
                const roleBadge = row.querySelector('td:nth-child(4) .badge');
                show = roleBadge.textContent.includes(
                    selectedRole === '1' ? 'Administrador' : 
                    selectedRole === '2' ? 'Docente' : 
                    'Estudiante'
                );
            }

            // Status filter
            if (show && selectedStatus !== '') {
                const statusCell = row.querySelector('td:nth-child(5) .badge');
                const isActive = statusCell.classList.contains('bg-success');
                show = (selectedStatus === '1' && isActive) || (selectedStatus === '0' && !isActive);
            }

            row.style.display = show ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    roleFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);
});
</script>
@endsection
