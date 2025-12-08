@extends("layouts.auth")

@section("title", "Restablecer Contrasena")

@section("content")
<div class="modal-header-custom">
    <h5>
        <i class="bi bi-lock-fill"></i> Restablecer Contrasena
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

                <form method="POST" action="{{ route("password-recovery.reset") }}" novalidate>
                    @csrf

                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle"></i> Ingresa tu nueva contrasena. Debe tener minimo 8 caracteres.
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label" style="color: #1B396A; font-weight: 600;"><i class="bi bi-envelope"></i> Correo Electronico</label>
                        <input type="email" class="form-control @error("email") is-invalid @enderror" id="email" name="email" value="{{ old("email") }}" required readonly style="font-size: 15px;">
                        @error("email")
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label" style="color: #1B396A; font-weight: 600;"><i class="bi bi-key-fill"></i> Nueva Contrasena</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error("password") is-invalid @enderror" id="password" name="password" required placeholder="Minimo 8 caracteres" style="font-size: 15px;">
                            <button type="button" class="btn btn-outline-secondary" id="toggle-password" onclick="togglePasswordVisibility()">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error("password")
                                <div class="invalid-feedback d-block w-100">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label" style="color: #1B396A; font-weight: 600;"><i class="bi bi-key-fill"></i> Confirmar Contrasena</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error("password_confirmation") is-invalid @enderror" id="password_confirmation" name="password_confirmation" required placeholder="Repite la contrasena" style="font-size: 15px;">
                            <button type="button" class="btn btn-outline-secondary" id="toggle-confirm" onclick="toggleConfirmVisibility()">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error("password_confirmation")
                                <div class="invalid-feedback d-block w-100">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-2" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); border: none; font-weight: 600;">
                        <i class="bi bi-check-circle"></i> Restablecer Contrasena
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

@section('scripts')
<script>
    function togglePasswordVisibility() {
        const field = document.getElementById("password");
        const btn = document.getElementById("toggle-password");
        if (field.type === "password") {
            field.type = "text";
            btn.innerHTML = "<i class=\"bi bi-eye-slash\"></i>";
        } else {
            field.type = "password";
            btn.innerHTML = "<i class=\"bi bi-eye\"></i>";
        }
    }

    function toggleConfirmVisibility() {
        const field = document.getElementById("password_confirmation");
        const btn = document.getElementById("toggle-confirm");
        if (field.type === "password") {
            field.type = "text";
            btn.innerHTML = "<i class=\"bi bi-eye-slash\"></i>";
        } else {
            field.type = "password";
            btn.innerHTML = "<i class=\"bi bi-eye\"></i>";
        }
    }
</script>
@endsection