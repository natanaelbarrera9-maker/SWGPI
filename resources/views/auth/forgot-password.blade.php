@extends('layouts.app')

@section('title', 'Recuperar Contraseña')

@section('content')
<div class="container-xl py-5">
    <div class="row d-flex justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%);">
                    <h3 class="text-white mb-0 text-center">
                        <i class="bi bi-key"></i> Recuperar Contraseña
                    </h3>
                </div>

                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="bi bi-exclamation-triangle"></i> Error</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <p class="text-muted text-center mb-4">
                        Ingresa tu correo electrónico para recibir un enlace de recuperación
                    </p>

                    <form method="POST" action="{{ route('password.email') }}" novalidate>
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Correo Electrónico
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-control form-control-lg @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="tu@email.com"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="bi bi-send"></i> Enviar Enlace de Recuperación
                        </button>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted mb-0">
                            <a href="{{ route('login') }}" class="text-decoration-none">
                                <i class="bi bi-arrow-left"></i> Volver a Iniciar Sesión
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
