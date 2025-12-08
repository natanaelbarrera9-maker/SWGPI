@extends('layouts.app')

@section('title', 'Verificar Token')

@section('content')
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); min-height: 100vh;">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color: rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 20px;">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}" style="color: white;">Login</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('auth.password-reset-request') }}" style="color: white;">Recuperar Contrasena</a></li>
                    <li class="breadcrumb-item active" style="color: #f5f7fb;">Verificar Token</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6 mx-auto">
            <div class="card shadow-lg" style="border: none; border-radius: 12px;">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border-radius: 12px 12px 0 0; padding: 20px;">
                    <h4 class="mb-0"><i class="bi bi-shield-check"></i> Verificar Codigo</h4>
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

                    <form method="POST" action="{{ route('auth.password-reset-check') }}" novalidate>
                        @csrf

                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle"></i> Revisa tu correo electronico y copia el codigo que recibiste.
                        </div>

                        <div class="mb-3">
                            <label for="token" class="form-label" style="color: #1B396A;"><strong>Codigo de Verificacion</strong></label>
                            <input type="text" autofocus class="form-control @error('token') is-invalid @enderror" id="token" name="token" value="{{ old('token') }}" required placeholder="Pega el codigo aqui" style="font-family: monospace; letter-spacing: 2px;">
                            @error('token')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="user_id" class="form-label" style="color: #1B396A;"><strong>Identificacion de Usuario</strong></label>
                            <input type="text" class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" value="{{ old('user_id', session('user_id')) }}" required placeholder="Tu ID o matricula">
                            @error('user_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <p style="color: #666; font-size: 13px; margin-bottom: 20px;">
                            <i class="bi bi-clock"></i> El codigo expira en 1 hora desde el envio del email.
                        </p>

                        <div class="d-flex gap-2 justify-content-between">
                            <a href="{{ route('auth.password-reset-request') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Atras
                            </a>
                            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); border: none;">
                                <i class="bi bi-check-circle"></i> Verificar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
