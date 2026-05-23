@extends('administrador.layouts.master')

@section('title', 'Editar Materia - SAINS')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                <i class="fas fa-edit me-2"></i>Editar Materia
            </h1>
            <p class="text-muted mb-0">Modifica los datos de la materia</p>
        </div>
        <a href="{{ route('admin.asignaturas.index') }}" class="btn btn-outline-secondary" style="border-radius: 50px;">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>
    
    <div class="dashboard-card p-4">
        <form id="editMateriaForm" action="{{ route('admin.asignaturas.update', $asignatura->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="form-floating mb-4">
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" placeholder="Nombre de la materia" 
                               value="{{ old('nombre', $asignatura->nombre) }}" required>
                        <label><i class="fas fa-book me-2 text-primary"></i>Nombre de la materia</label>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.asignaturas.index') }}" class="btn btn-light" style="border-radius: 50px;">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary" style="border-radius: 50px;">
                            <i class="fas fa-save me-2"></i>Actualizar Materia
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection