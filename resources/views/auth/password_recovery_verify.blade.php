@extends("layouts.auth")

@section("title", "Verificar Token")

@section("content")
<div class="modal-header-custom">
    <h5>
        <i class="bi bi-shield-check"></i> Verificar Token
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

                <form method="POST" action="{{ route("password-recovery.verify") }}" novalidate>
                    @csrf

                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle"></i> Ingresa el correo y el codigo que recibiste por email. El codigo expira en 1 hora.
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label" style="color: #1B396A; font-weight: 600;"><i class="bi bi-envelope"></i> Correo Electronico</label>
                        <input type="email" autofocus class="form-control @error("email") is-invalid @enderror" id="email" name="email" value="{{ old("email") }}" required placeholder="tu.correo@ejemplo.com" style="font-size: 15px;">
                        @error("email")
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="token" class="form-label" style="color: #1B396A; font-weight: 600;"><i class="bi bi-key"></i> Codigo de Verificacion</label>
                        <input type="text" class="form-control @error("token") is-invalid @enderror" id="token" name="token" value="{{ old("token") }}" required placeholder="Pegalo aqui" style="font-family: monospace; font-size: 13px; font-weight: 600;">
                        @error("token")
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small style="color: #666; display: block; margin-top: 5px;">Revisar spam o promociones si no lo encuentras.</small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-2" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); border: none; font-weight: 600;">
                        <i class="bi bi-check-circle"></i> Verificar
                    </button>
                </form>

                <hr class="my-3">

                <div class="text-center">
                    <a href="{{ route("password-recovery.request") }}" class="text-decoration-none" style="color: #666; font-size: 14px; margin-right: 15px;">
                        <i class="bi bi-arrow-left"></i> Volver atras
                    </a>
                    <a href="{{ route("login") }}" class="text-decoration-none" style="color: #666; font-size: 14px;">
                        <i class="bi bi-house"></i> Al login
                    </a>
                </div>
            </div>
@endsection