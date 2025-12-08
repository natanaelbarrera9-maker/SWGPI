@extends('layouts.app')

@section('title', 'Recuperar Contrasena')

@section('content')
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); min-height: 100vh;">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color: rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 20px;">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}" style="color: white;">Login</a></li>
                    <li class="breadcrumb-item active" style="color: #f5f7fb;">Recuperar Contrasena</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6 mx-auto">
            <div class="card shadow-lg" style="border: none; border-radius: 12px;">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border-radius: 12px 12px 0 0; padding: 20px;">
                    <h4 class="mb-0"><i class="bi bi-key"></i> Recuperar Contrasena</h4>
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

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('auth.password-reset-request') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label" style="color: #1B396A;"><strong>Correo Electronico</strong></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="tu@correo.com">
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <p style="color: #666; font-size: 14px; margin-bottom: 20px;">
                            <i class="bi bi-info-circle"></i> Ingresa el correo asociado a tu cuenta. Recibirás un email con un codigo de verificacion para restablecer tu contrasena.
                        </p>

                        <div class="d-flex gap-2 justify-content-between">
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); border: none;">
                                <i class="bi bi-send"></i> Enviar Instrucciones
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
