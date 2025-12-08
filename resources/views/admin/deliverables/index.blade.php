@extends('layouts.app')

@section('title', 'Gestión de Entregables')

@section('content')
<div class="container-fluid py-5" style="min-height: 100vh;">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color: rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 20px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: white;">Admin</a></li>
                    <li class="breadcrumb-item active" style="color: #f5f7fb;">Entregables</li>
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
                        <h4 class="mb-0"><i class="bi bi-inbox-fill"></i> Gestión de Entregables</h4>
                        <a href="{{ route('admin.deliverables.create') }}" class="btn btn-light">
                            <i class="bi bi-plus-circle"></i> Nuevo Entregable
                        </a>
                    </div>
                </div>
                <div class="card-body" style="padding: 30px;">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead style="background-color: #f5f7fb; border-top: 2px solid #1B396A;">
                                <tr>
                                    <th style="color: #1B396A;"><strong>#</strong></th>
                                    <th style="color: #1B396A;"><strong>Competencia</strong></th>
                                    <th style="color: #1B396A;"><strong>Entregable</strong></th>
                                    <th style="color: #1B396A;"><strong>Fecha límite</strong></th>
                                    <th style="color: #1B396A; text-align: center;"><strong>Acciones</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deliverables as $deliverable)
                                    <tr>
                                        <td><strong>{{ $deliverable->id }}</strong></td>
                                        <td>{{ $deliverable->competencia_nombre ?? '—' }}</td>
                                        <td>{{ $deliverable->nombre }}</td>
                                        <td>{{ \Carbon\Carbon::parse($deliverable->fecha_limite)->format('d/m/Y H:i') }}</td>
                                        <td style="text-align: center;">
                                            <a href="{{ route('admin.deliverables.show', $deliverable->id) }}" class="btn btn-sm btn-outline-info" title="Ver">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.deliverables.edit', $deliverable->id) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.deliverables.destroy', $deliverable->id) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Está seguro?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                            <p>No hay entregables registrados</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($deliverables->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $deliverables->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
