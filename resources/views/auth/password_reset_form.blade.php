@extends('layouts.app')

@section('title', 'Cambiar Contrasena')

@section('content')
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); min-height: 100vh;">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color: rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 20px;">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}" style="color: white;">Login</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('auth.password-reset-request') }}" style="color: white;">Recuperar Contrasena</a></li>
                    <li class="breadcrumb-item active" style="color: #f5f7fb;">Cambiar Contrasena</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6 mx-auto">
            <div class="card shadow-lg" style="border: none; border-radius: 12px;">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border-radius: 12px 12px 0 0; padding: 20px;">
                    <h4 class="mb-0"><i class="bi bi-lock"></i> Establecer Nueva Contrasena</h4>
                </div>
                <div class="card-body" style="padding: 30px;">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="bi bi-exclamation-triangle"></i> Error:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('auth.reset-password') }}" novalidate>
                        @csrf

                        <input type="hidden" name="token" value="{{ $token ?? '' }}">
                        <input type="hidden" name="user_id" value="{{ $user_id ?? '' }}">

                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-circle"></i> Ingresa una contrasena segura con al menos 8 caracteres.
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label" style="color: #1B396A;"><strong>Nueva Contrasena</strong></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required minlength="8" placeholder="Ingresa tu nueva contrasena">
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small style="color: #666; display: block; margin-top: 5px;">Minimo 8 caracteres</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label" style="color: #1B396A;"><strong>Confirmar Contrasena</strong></label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required minlength="8" placeholder="Repite tu contrasena">
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="showPassword" onchange="togglePasswordVisibility()">
                                <label class="form-check-label" for="showPassword" style="color: #1B396A;">
                                    Mostrar contrasenas
                                </label>
                            </div>
                        </div>

                        <p style="color: #666; font-size: 13px; margin-bottom: 20px;">
                            <i class="bi bi-shield-lock"></i> Tu contrasena sera cifrada y almacenada de forma segura.
                        </p>

                        <div class="d-flex gap-2 justify-content-between">
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border: none;">
                                <i class="bi bi-check-circle"></i> Actualizar Contrasena
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const isChecked = document.getElementById('showPassword').checked;
        
        passwordInput.type = isChecked ? 'text' : 'password';
        confirmInput.type = isChecked ? 'text' : 'password';
    }
</script>
@endsection
