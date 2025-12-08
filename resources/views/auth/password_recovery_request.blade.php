@extends("layouts.auth")

@section("title", "Recuperar Contrasena")

@section("content")
<div class="modal-header-custom">
    <h5>
        <i class="bi bi-key"></i> Recuperar Contrasena
    </h5>
</div>

<div class="modal-body-custom">
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

                @if(session("success"))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session("success") }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route("password-recovery.request") }}" novalidate>
                    @csrf

                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle"></i> Ingresa el correo electronico registrado en tu cuenta.
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label" style="color: #1B396A; font-weight: 600;"><i class="bi bi-envelope"></i> Correo Electronico</label>
                        <input type="email" autofocus class="form-control @error("email") is-invalid @enderror" id="email" name="email" value="{{ old("email") }}" required placeholder="tu.correo@ejemplo.com" style="font-size: 15px;">
                        @error("email")
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small style="color: #666; display: block; margin-top: 5px;">Debe ser el correo registrado en el sistema.</small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-2" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); border: none; font-weight: 600;">
                        <i class="bi bi-send"></i> Enviar Instrucciones
                    </button>
                </form>

                <hr class="my-3">

                <div class="text-center">
                    <a href="{{ route("login") }}" class="text-decoration-none" style="color: #666; font-size: 14px;">
                        <i class="bi bi-arrow-left"></i> Volver al login
                    </a>
                </div>
            </div>
@endsection