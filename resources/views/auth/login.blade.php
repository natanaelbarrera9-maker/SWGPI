@extends("layouts.auth")

@section("title", "Iniciar Sesion")

@section("content")
<div class="modal-header-custom">
    <h5>
        <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesion
    </h5>
</div>

<div class="modal-body-custom">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="bi bi-exclamation-triangle"></i> Error de Autenticacion</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session("status"))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session("status") }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route("login.post") }}" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="id" class="form-label" style="color: #1B396A; font-weight: 600;">
                            <i class="bi bi-person"></i> Matricula / Nomina
                        </label>
                        <input type="text" id="id" name="id" class="form-control form-control-lg @error("id") is-invalid @enderror" value="{{ old("id") }}" placeholder="Ej: 0000000001" required autofocus style="font-size: 15px;">
                        @error("id")
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label" style="color: #1B396A; font-weight: 600;">
                            <i class="bi bi-lock"></i> Contrasena
                        </label>
                        <input type="password" id="password" name="password" class="form-control form-control-lg @error("password") is-invalid @enderror" placeholder="Ingresa tu contrasena" required style="font-size: 15px;">
                        @error("password")
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-2" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); border: none; font-weight: 600;">
                        <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesion
                    </button>
                </form>

                <hr class="my-3">

                <div class="text-center mb-2">
                    <a href="{{ route("password-recovery.request") }}" class="text-decoration-none" style="color: #1B396A; font-size: 14px;">
                        <i class="bi bi-question-circle"></i> Olvidaste tu contrasena?
                    </a>
                </div>

                <div class="text-center">
                    <a href="{{ route("home") }}" class="text-decoration-none" style="color: #666; font-size: 14px;">
                        <i class="bi bi-house"></i> Volver al inicio
                    </a>
                </div>
            </div>
@endsection