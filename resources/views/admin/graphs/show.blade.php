@extends('layouts.app')

@section('title', 'Ver Grafo')

@section('content')
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); min-height: 100vh;">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color: rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 20px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: white;">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.graphs.index') }}" style="color: white;">Grafos</a></li>
                    <li class="breadcrumb-item active" style="color: #f5f7fb;">{{ $graph->nombre }}</li>
                </ol>
            </nav>

            <div class="card shadow-lg mt-4" style="border: none; border-radius: 12px;">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border-radius: 12px 12px 0 0; padding: 20px;">
                    <h4 class="mb-0"><i class="bi bi-diagram-3"></i> {{ $graph->nombre }}</h4>
                </div>
                <div class="card-body" style="padding: 30px;">
                    <div class="mb-3">
                        <label style="color: #1B396A;"><strong>Nombre</strong></label>
                        <p>{{ $graph->nombre }}</p>
                    </div>

                    <div class="mb-3">
                        <label style="color: #1B396A;"><strong>Proyecto ID</strong></label>
                        <p>{{ $graph->proyecto_id ?: 'Sin asignar' }}</p>
                    </div>

                    <div class="mb-3">
                        <label style="color: #1B396A;"><strong>Descripción</strong></label>
                        <p>{{ $graph->descripcion ?: 'Sin descripción' }}</p>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.graphs.edit', $graph->id) }}" class="btn btn-primary" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); border: none;">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="{{ route('admin.graphs.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
