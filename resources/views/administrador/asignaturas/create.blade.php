@extends('administrador.layouts.master')

@section('title', 'Nueva Materia - SAINS')

@section('content')
<div class="container-fluid p-0 p-lg-2">
    <div class="px-2 px-xl-3 px-xxl-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h1 class="display-5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                            <i class="fas fa-plus-circle me-3"></i>Nueva Materia
                        </h1>
                        <p class="text-muted">Registre una nueva materia en el sistema</p>
                    </div>
                    <a href="{{ route('admin.asignaturas.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i>Volver al listado
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card-modern p-4 p-lg-5">
                    <form action="{{ route('admin.asignaturas.store') }}" method="POST" id="materiaForm">
                        @csrf
                        
                        <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));">
                                <i class="fas fa-book fa-2x" style="color: #4361ee;"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold">Información de la Materia</h4>
                                <p class="text-muted small mb-0">Complete los datos requeridos</p>
                            </div>
                        </div>
                        
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-book text-primary me-1"></i> Nombre de la Materia <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-transparent"><i class="fas fa-graduation-cap text-primary"></i></span>
                                    <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                                           value="{{ old('nombre') }}" placeholder="Ej: Matemáticas, Español, Ciencias..." required autofocus>
                                </div>
                                @error('nombre')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Ingrese el nombre completo de la materia</small>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top">
                            <a href="{{ route('admin.asignaturas.index') }}" class="btn btn-cancel px-5 py-3">
                                <i class="fas fa-times me-2"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-save px-5 py-3" id="submitBtn">
                                <i class="fas fa-save me-2"></i> Guardar Materia
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-modern {
        background: white;
        border-radius: 24px;
        transition: transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    
    .card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(67,97,238,0.15);
    }
    
    .form-label {
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    
    .form-control-lg, .input-group-lg .form-control {
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    
    .form-control-lg:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
    }
    
    .input-group-text {
        border-radius: 12px 0 0 12px;
        border: 2px solid #e0e0e0;
        border-right: none;
    }
    
    .input-group .form-control {
        border-left: none;
    }
    
    .input-group .form-control:focus {
        border-left: none;
    }
    
    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .btn-cancel {
        background: #6c757d;
        border: none;
        color: white;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-cancel:hover {
        background: #dc3545;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
        color: white;
    }
    
    .btn-outline-primary {
        border-radius: 50px;
        transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
        background: #4361ee;
        color: white;
        transform: translateY(-2px);
    }
    
    .invalid-feedback {
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }
    
    body.dark-mode .card-modern {
        background: #1e293b;
    }
    
    body.dark-mode .form-control-lg,
    body.dark-mode .input-group-text {
        background-color: #0f172a;
        border-color: #334155;
        color: #f1f5f9;
    }
    
    body.dark-mode .form-control-lg:focus {
        border-color: #667eea;
    }
    
    body.dark-mode .border-bottom {
        border-bottom-color: #334155 !important;
    }
    
    body.dark-mode .border-top {
        border-top-color: #334155 !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('materiaForm')?.addEventListener('submit', function(e) {
        const nombre = document.getElementById('nombre').value;
        
        if (!nombre.trim()) {
            e.preventDefault();
            Swal.fire('Error', 'El nombre de la materia es requerido', 'error');
            return false;
        }
        
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando...';
    });
    
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false,
            background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#f1f5f9' : '#1e293b'
        });
    @endif
    
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#f1f5f9' : '#1e293b'
        });
    @endif
</script>
@endpush