@extends('layouts.app')

@section('title', 'Restablecer Contraseña')

@section('content')
<div class="container-xl py-5">
    <div class="row d-flex justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%);">
                    <h3 class="text-white mb-0 text-center">
                        <i class="bi bi-shield-lock"></i> Restablecer Contraseña
                    </h3>
                </div>

                <div class="card-body p-4">
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
                        Ingresa una nueva contraseña para tu cuenta
                    </p>

                    <form method="POST" action="{{ route('password.update') }}" novalidate>
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Correo Electrónico
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-control form-control-lg @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                required
                                readonly
                            >
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock"></i> Nueva Contraseña
                            </label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-control form-control-lg @error('password') is-invalid @enderror"
                                placeholder="Ingresa tu nueva contraseña"
                                required
                            >
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="bi bi-lock-check"></i> Confirmar Contraseña
                            </label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                class="form-control form-control-lg"
                                placeholder="Confirma tu nueva contraseña"
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-check-circle"></i> Restablecer Contraseña
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
