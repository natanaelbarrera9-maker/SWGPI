@extends('layouts.app')

@section('title', 'Editar Proyecto')

@section('content')
<div class="container-fluid py-5" style="min-height: 100vh;">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color: rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 20px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: white;">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.projects.index') }}" style="color: white;">Proyectos</a></li>
                    <li class="breadcrumb-item active" style="color: #f5f7fb;">Editar</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-lg" style="border: none; border-radius: 12px;">
                <div class="card-header" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; border-radius: 12px 12px 0 0; padding: 20px;">
                    <h4 class="mb-0"><i class="bi bi-pencil"></i> Editar Proyecto</h4>
                </div>
                <div class="card-body" style="padding: 30px;">
                    <form method="POST" action="{{ route('admin.projects.update', $project->id) }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label"><strong>Título</strong></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ $project->title }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label"><strong>Descripción</strong></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ $project->description }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_ids" class="form-label"><strong>Estudiantes</strong></label>
                            <input type="text" id="student_filter" class="form-control mb-2" placeholder="Buscar por matrícula o nombre..." oninput="filterStudents()">

                            <div class="student-checkbox-list @error('student_ids') is-invalid @enderror" style="max-height: 240px; overflow:auto; border:1px solid #e9ecef; padding:10px; border-radius:6px;">
                                @foreach($students as $student)
                                    @php $checked = in_array($student->id, $assigned ?? []) ? 'checked' : ''; @endphp
                                    <div class="form-check student-item" data-search="{{ strtolower($student->id . ' ' . $student->nombres . ' ' . $student->apa . ' ' . $student->ama) }}">
                                        <input class="form-check-input" type="checkbox" name="student_ids[]" value="{{ $student->id }}" id="student_{{ $student->id }}" {{ $checked }}>
                                        <label class="form-check-label" for="student_{{ $student->id }}">{{ $student->id }} - {{ $student->nombres }} {{ $student->apa }} {{ $student->ama }}</label>
                                    </div>
                                @endforeach
                            </div>

                            @error('student_ids')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Marca las casillas para asignar varios estudiantes.</small>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); border: none;">
                                <i class="bi bi-check-circle"></i> Guardar
                            </button>
                        </div>
                    </form>
                    <script>
                        function filterStudents() {
                            const q = document.getElementById('student_filter').value.trim().toLowerCase();
                            const items = document.querySelectorAll('.student-item');
                            items.forEach(it => {
                                const hay = it.getAttribute('data-search') || '';
                                if (!q || hay.indexOf(q) !== -1) {
                                    it.style.display = '';
                                } else {
                                    it.style.display = 'none';
                                }
                            });
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
