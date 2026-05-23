{{-- resources/views/administrador/asignaturas/create.blade.php --}}
@extends('administrador.layouts.master')

@section('title', 'Nueva Materia - SAINS')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                <i class="fas fa-plus-circle me-2"></i>Nueva Materia
            </h1>
            <p class="text-muted mb-0">Registra una nueva materia en el sistema</p>
        </div>
        <a href="{{ route('admin.asignaturas.index') }}" class="btn btn-outline-secondary" style="border-radius: 50px;">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>
    
    <div class="dashboard-card p-4">
        <form id="createMateriaForm" action="{{ route('admin.asignaturas.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="form-floating mb-4">
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" placeholder="Nombre de la materia" 
                               value="{{ old('nombre') }}" required>
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
                            <i class="fas fa-save me-2"></i>Guardar Materia
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection