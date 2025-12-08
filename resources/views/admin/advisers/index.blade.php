@extends('layouts.app')

@section('title', 'Gestión de Asesores')

@section('content')
<div class="container-fluid py-5" style="min-height: 100vh;">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color: rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 20px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: white;">Admin</a></li>
                    <li class="breadcrumb-item active" style="color: #f5f7fb;">Asesores</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-lg" style="border: none; border-radius: 12px;">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border-radius: 12px 12px 0 0; padding: 20px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="bi bi-person-check"></i> Asignación de Asesores a Proyectos</h4>
                    </div>
                </div>
                <div class="card-body" style="padding: 30px;">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead style="background-color: #f5f7fb; border-top: 2px solid #1B396A;">
                                <tr>
                                    <th style="color: #1B396A;"><strong>#</strong></th>
                                    <th style="color: #1B396A;"><strong>Proyecto</strong></th>
                                    <th style="color: #1B396A;"><strong>Descripción</strong></th>
                                    <th style="color: #1B396A;"><strong>Asesores</strong></th>
                                    <th style="color: #1B396A; text-align: center;"><strong>Acciones</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $project)
                                    <tr>
                                        <td><strong>{{ $project->id }}</strong></td>
                                        <td>{{ $project->title }}</td>
                                        <td>{{ Str::limit($project->description, 50) }}</td>
                                        <td>
                                            @if($project->adviser_count > 0)
                                                <span class="badge bg-success">{{ $project->adviser_count }} asignados</span>
                                            @else
                                                <span class="badge bg-warning">Sin asesores</span>
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            <a href="{{ route('admin.advisers.show', $project->id) }}" class="btn btn-sm btn-outline-info" title="Asignar/Ver">
                                                <i class="bi bi-pencil-square"></i> Gestionar
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                            <p>No hay proyectos registrados</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($projects->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $projects->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
