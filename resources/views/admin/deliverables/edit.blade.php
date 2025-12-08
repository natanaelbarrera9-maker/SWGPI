@extends('layouts.app')

@section('title', 'Editar Entregable')

@section('content')
<div class="container-fluid py-5" style="min-height: 100vh;">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color: rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 20px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: white;">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.deliverables.index') }}" style="color: white;">Entregables</a></li>
                    <li class="breadcrumb-item active" style="color: #f5f7fb;">Editar</li>
                </ol>
            </nav>

            <div class="card shadow-lg mt-4" style="border: none; border-radius: 12px;">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border-radius: 12px 12px 0 0; padding: 20px;">
                    <h4 class="mb-0"><i class="bi bi-pencil"></i> Editar Entregable</h4>
                </div>
                <div class="card-body" style="padding: 30px;">
                    <form method="POST" action="{{ route('admin.deliverables.update', $deliverable->id) }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="competencia_id" class="form-label" style="color: #1B396A;"><strong>Competencia</strong></label>
                            <select class="form-select @error('competencia_id') is-invalid @enderror" id="competencia_id" name="competencia_id" required>
                                <option value="">Seleccione una competencia...</option>
                                @foreach($competencias as $c)
                                    <option value="{{ $c->id }}" @selected($deliverable->competencia_id == $c->id)>{{ $c->nombre }}</option>
                                @endforeach
                            </select>
                            @error('competencia_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nombre" class="form-label" style="color: #1B396A;"><strong>Nombre del entregable</strong></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $deliverable->nombre) }}">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="fecha_limite" class="form-label" style="color: #1B396A;"><strong>Fecha límite</strong></label>
                            <input type="datetime-local" class="form-control @error('fecha_limite') is-invalid @enderror" id="fecha_limite" name="fecha_limite" value="{{ old('fecha_limite', \Carbon\Carbon::parse($deliverable->fecha_limite)->format('Y-m-d\TH:i')) }}">
                            @error('fecha_limite')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formatos_aceptados" class="form-label" style="color: #1B396A;"><strong>Formatos aceptados</strong></label>
                            <input type="text" class="form-control @error('formatos_aceptados') is-invalid @enderror" id="formatos_aceptados" name="formatos_aceptados" value="{{ old('formatos_aceptados', $deliverable->formatos_aceptados) }}">
                            @error('formatos_aceptados')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.deliverables.index') }}" class="btn btn-outline-secondary">
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
