@extends('layouts.app')

@section('title', 'Ver Proyecto')

@section('content')
<div class="container-fluid py-5" style="min-height: 100vh;">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color: rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 20px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: white;">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.projects.index') }}" style="color: white;">Proyectos</a></li>
                    <li class="breadcrumb-item active" style="color: #f5f7fb;">{{ $project->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-lg" style="border: none; border-radius: 12px;">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border-radius: 12px 12px 0 0; padding: 20px;">
                    <h4 class="mb-0"><i class="bi bi-diagram-3"></i> {{ $project->title }}</h4>
                </div>
                <div class="card-body" style="padding: 30px;">
                    <div class="mb-3">
                        <label><strong>Título</strong></label>
                        <p>{{ $project->title }}</p>
                    </div>

                    <div class="mb-3">
                        <label><strong>Descripción</strong></label>
                        <p>{{ $project->description ?: 'Sin descripción' }}</p>
                    </div>

                    <div class="mb-3">
                        <label><strong>Creado</strong></label>
                        <p>{{ \Carbon\Carbon::parse($project->created_at)->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="mb-3">
                        <label><strong>Creado Por</strong></label>
                        <p>
                            @if(!empty($creator))
                                {{ $creator->id }} - {{ $creator->nombres }} {{ $creator->apa }} {{ $creator->ama }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label><strong>Autores (Estudiantes)</strong></label>
                        @if(isset($students) && $students->count() > 0)
                            <ul>
                                @foreach($students as $s)
                                    <li>{{ $s->id }} - {{ $s->nombres }} {{ $s->apa }} {{ $s->ama }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">No hay estudiantes asignados</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label><strong>Asignaturas Vinculadas</strong></label>
                        @if(isset($subjects) && $subjects->count() > 0)
                            <ul>
                                @foreach($subjects as $sub)
                                    <li>{{ $sub->clave ?? $sub->id }} - {{ $sub->nombre }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">Sin asignaturas vinculadas</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label><strong>Asesores</strong></label>
                        <div>
                            <strong>Primario:</strong>
                            @if(!empty($primaryAdviser))
                                <span>{{ $primaryAdviser->id }} - {{ $primaryAdviser->nombres }} {{ $primaryAdviser->apa }} {{ $primaryAdviser->ama }}</span>
                            @else
                                <span class="text-muted">No asignado</span>
                            @endif
                        </div>
                        <div class="mt-2">
                            <strong>Secundario:</strong>
                            @if(!empty($secondaryAdviser))
                                <span>{{ $secondaryAdviser->id }} - {{ $secondaryAdviser->nombres }} {{ $secondaryAdviser->apa }} {{ $secondaryAdviser->ama }}</span>
                            @else
                                <span class="text-muted">No asignado</span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-primary" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); border: none;">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
