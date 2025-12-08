@extends('layouts.app')

@section('title', 'Editar Competencia')

@section('content')
<div class="container-fluid py-5" style="min-height: 100vh;">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color: rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 20px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: white;">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.competencies.index') }}" style="color: white;">Competencias</a></li>
                    <li class="breadcrumb-item active" style="color: #f5f7fb;">Editar</li>
                </ol>
            </nav>

            <div class="card shadow-lg mt-4" style="border: none; border-radius: 12px;">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border-radius: 12px 12px 0 0; padding: 20px;">
                    <h4 class="mb-0"><i class="bi bi-pencil"></i> Editar Competencia</h4>
                </div>
                <div class="card-body" style="padding: 30px;">
                    <form method="POST" action="{{ route('admin.competencies.update', $competency->id) }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="asignatura_id" class="form-label"><strong>Asignatura</strong></label>
                            <select id="asignatura_id" name="asignatura_id" class="form-select @error('asignatura_id') is-invalid @enderror" required>
                                <option value="">-- Selecciona una asignatura --</option>
                                @foreach($asignaturas as $asig)
                                    <option value="{{ $asig->id }}" @selected($asig->id == $competency->asignatura_id)>{{ $asig->clave ?? $asig->nombre }} - {{ $asig->nombre }}</option>
                                @endforeach
                            </select>
                            @error('asignatura_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nombre" class="form-label"><strong>Nombre</strong></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $competency->nombre) }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="fecha_inicio" class="form-label"><strong>Fecha Inicio</strong></label>
                                <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', $competency->fecha_inicio) }}" required>
                                @error('fecha_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_fin" class="form-label"><strong>Fecha Fin</strong></label>
                                <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin', $competency->fecha_fin) }}" required>
                                @error('fecha_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.competencies.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); border: none;">
                                <i class="bi bi-check-circle"></i> Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
