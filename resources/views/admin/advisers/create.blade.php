@extends('layouts.app')

@section('title', 'Crear Asesor')

@section('content')
<div class="container-fluid py-5" style="min-height: 100vh;">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color: rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 20px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: white;">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.advisers.index') }}" style="color: white;">Asesores</a></li>
                    <li class="breadcrumb-item active" style="color: #f5f7fb;">Crear</li>
                </ol>
            </nav>

            <div class="card shadow-lg mt-4" style="border: none; border-radius: 12px;">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border-radius: 12px 12px 0 0; padding: 20px;">
                    <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Nuevo Asesor</h4>
                </div>
                <div class="card-body" style="padding: 30px;">
                    <form method="POST" action="{{ route('admin.advisers.store') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="user_id" class="form-label"><strong>Usuario (Docente)</strong></label>
                            <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">Seleccione un docente...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->nombres }} {{ $user->apa }} - {{ $user->id }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="especialidad" class="form-label"><strong>Especialidad</strong></label>
                            <input type="text" class="form-control @error('especialidad') is-invalid @enderror" id="especialidad" name="especialidad">
                            @error('especialidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.advisers.index') }}" class="btn btn-outline-secondary">
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
