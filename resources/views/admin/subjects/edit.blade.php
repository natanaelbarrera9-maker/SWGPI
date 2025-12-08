@extends('layouts.app')

@section('title', 'Editar Asignatura')

@section('content')
<div class="container-fluid py-5" style="min-height: 100vh;">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color: rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 20px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: white;">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.subjects.index') }}" style="color: white;">Asignaturas</a></li>
                    <li class="breadcrumb-item active" style="color: #f5f7fb;">Editar</li>
                </ol>
            </nav>

            <div class="card shadow-lg mt-4" style="border: none; border-radius: 12px;">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border-radius: 12px 12px 0 0; padding: 20px;">
                    <h4 class="mb-0"><i class="bi bi-pencil"></i> Editar Asignatura</h4>
                </div>
                <div class="card-body" style="padding: 30px;">
                    <form method="POST" action="{{ route('admin.subjects.update', $subject->id) }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="clave" class="form-label" style="color: #1B396A;"><strong>Código/Clave</strong></label>
                            <input type="text" class="form-control @error('clave') is-invalid @enderror" id="clave" name="clave" value="{{ $subject->clave }}" required>
                            @error('clave')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nombre" class="form-label" style="color: #1B396A;"><strong>Nombre</strong></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ $subject->nombre }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label" style="color: #1B396A;"><strong>Descripción</strong></label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="5">{{ $subject->descripcion }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
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
