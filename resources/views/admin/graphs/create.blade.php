@extends('layouts.app')

@section('title', 'Crear Grafo')

@section('content')
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); min-height: 100vh;">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color: rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 20px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: white;">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.graphs.index') }}" style="color: white;">Grafos</a></li>
                    <li class="breadcrumb-item active" style="color: #f5f7fb;">Crear</li>
                </ol>
            </nav>

            <div class="card shadow-lg mt-4" style="border: none; border-radius: 12px;">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border-radius: 12px 12px 0 0; padding: 20px;">
                    <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Nuevo Grafo</h4>
                </div>
                <div class="card-body" style="padding: 30px;">
                    <form method="POST" action="{{ route('admin.graphs.store') }}" enctype="multipart/form-data" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="titulo" class="form-label" style="color: #1B396A;"><strong>Título</strong></label>
                            <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="proyecto_id" class="form-label" style="color: #1B396A;"><strong>Proyecto (Opcional)</strong></label>
                            <select class="form-select @error('proyecto_id') is-invalid @enderror" id="proyecto_id" name="proyecto_id">
                                <option value="">Seleccione un proyecto...</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->title }}</option>
                                @endforeach
                            </select>
                            @error('proyecto_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label" style="color: #1B396A;"><strong>Descripción</strong></label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="5">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="imagen" class="form-label" style="color: #1B396A;"><strong>Imagen (JPG/JPEG)</strong></label>
                            <input type="file" class="form-control @error('imagen') is-invalid @enderror" id="imagen" name="imagen" accept="image/jpg,image/jpeg">
                            <small style="color: #666;">Formatos permitidos: JPG, JPEG. Tamaño máximo: 2MB</small>
                            @error('imagen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label" style="color: #1B396A;"><strong>Estado</strong></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="activo" @selected(old('status') == 'activo')>Activo</option>
                                <option value="inactivo" @selected(old('status') == 'inactivo')>Inactivo</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.graphs.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); border: none;">
                                <i class="bi bi-check-circle"></i> Crear
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
