@extends('layouts.app')

@section('title', 'Gestionar Asesores del Proyecto')

@section('content')
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); min-height: 100vh;">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color: rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 20px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: white;">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.advisers.index') }}" style="color: white;">Asesores</a></li>
                    <li class="breadcrumb-item active" style="color: #f5f7fb;">{{ $project->title }}</li>
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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mt-4">
        <div class="col-12">
            <!-- Project Info -->
            <div class="card shadow-lg mb-4" style="border: none; border-radius: 12px;">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border-radius: 12px 12px 0 0; padding: 20px;">
                    <h4 class="mb-0"><i class="bi bi-diagram-3"></i> {{ $project->title }}</h4>
                </div>
                <div class="card-body" style="padding: 30px;">
                    <p><strong>Descripción:</strong> {{ $project->description ?: '—' }}</p>
                </div>
            </div>

            <!-- Assign Adviser Form -->
            <div class="card shadow-lg mb-4" style="border: none; border-radius: 12px;">
                <div class="card-header" style="background: linear-gradient(135deg, #2D5A96 0%, #1B396A 100%); color: white; border-radius: 12px 12px 0 0; padding: 20px;">
                    <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Asignar Asesor</h5>
                </div>
                <div class="card-body" style="padding: 30px;">
                    <form method="POST" action="{{ route('admin.advisers.store') }}" novalidate>
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label" style="color: #1B396A;"><strong>Docente</strong></label>
                                    <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                        <option value="">Seleccione un docente...</option>
                                        @php
                                            $teachers = DB::table('users')->where('perfil_id', 2)->get();
                                            $assignedTeachers = $advisers->pluck('user_id')->toArray();
                                        @endphp
                                        @foreach($teachers as $teacher)
                                            @if(!in_array($teacher->id, $assignedTeachers))
                                                <option value="{{ $teacher->id }}">
                                                    {{ $teacher->id }} - {{ $teacher->nombres }} {{ $teacher->apa }} {{ $teacher->ama }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rol_asesor" class="form-label" style="color: #1B396A;"><strong>Rol</strong></label>
                                    <select class="form-select @error('rol_asesor') is-invalid @enderror" id="rol_asesor" name="rol_asesor" required>
                                        <option value="">Seleccione un rol...</option>
                                        @php
                                            $hasPrimary = $advisers->where('rol_asesor', 'primario')->count() > 0;
                                            $hasSecondary = $advisers->where('rol_asesor', 'secundario')->count() > 0;
                                        @endphp
                                        <option value="primario" @disabled($hasPrimary)>
                                            Primario
                                            @if($hasPrimary) (ya asignado) @endif
                                        </option>
                                        <option value="secundario" @disabled($hasSecondary)>
                                            Secundario
                                            @if($hasSecondary) (ya asignado) @endif
                                        </option>
                                    </select>
                                    @error('rol_asesor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); border: none;">
                                <i class="bi bi-check-circle"></i> Asignar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Assigned Advisers -->
            <div class="card shadow-lg mb-4" style="border: none; border-radius: 12px;">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border-radius: 12px 12px 0 0; padding: 20px;">
                    <h5 class="mb-0"><i class="bi bi-person-check"></i> Asesores Asignados</h5>
                </div>
                <div class="card-body" style="padding: 30px;">
                    @if($advisers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead style="background-color: #f5f7fb; border-top: 2px solid #1B396A;">
                                    <tr>
                                        <th style="color: #1B396A;"><strong>Matrícula</strong></th>
                                        <th style="color: #1B396A;"><strong>Nombre</strong></th>
                                        <th style="color: #1B396A;"><strong>Rol</strong></th>
                                        <th style="color: #1B396A; text-align: center;"><strong>Acciones</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($advisers as $adviser)
                                        <tr>
                                            <td><strong>{{ $adviser->user_id }}</strong></td>
                                            <td>{{ $adviser->nombres }} {{ $adviser->apa }} {{ $adviser->ama }}</td>
                                            <td>
                                                <span class="badge" style="background-color: {{ $adviser->rol_asesor === 'primario' ? '#1B396A' : '#2D5A96' }};">
                                                    {{ ucfirst($adviser->rol_asesor) }}
                                                </span>
                                            </td>
                                            <td style="text-align: center;">
                                                <form method="POST" action="{{ route('admin.advisers.destroy', [$project->id, $adviser->user_id]) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Desasignar" onclick="return confirm('¿Desasignar este asesor?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p>No hay asesores asignados a este proyecto</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Project Students -->
            <div class="card shadow-lg" style="border: none; border-radius: 12px;">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border-radius: 12px 12px 0 0; padding: 20px;">
                    <h5 class="mb-0"><i class="bi bi-people"></i> Autores del Proyecto</h5>
                </div>
                <div class="card-body" style="padding: 30px;">
                    @if($students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead style="background-color: #f5f7fb; border-top: 2px solid #1B396A;">
                                    <tr>
                                        <th style="color: #1B396A;"><strong>Matrícula</strong></th>
                                        <th style="color: #1B396A;"><strong>Nombre</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr>
                                            <td><strong>{{ $student->user_id }}</strong></td>
                                            <td>{{ $student->nombres }} {{ $student->apa }} {{ $student->ama }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p>No hay autores asignados a este proyecto</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end mt-4">
                <a href="{{ route('admin.advisers.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
