@extends('administrador.layouts.master')

@section('title', 'Editar Clase - SAINS')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="display-5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                    <i class="fas fa-edit me-3"></i>Editar Clase
                </h1>
                <p class="text-muted">Modifique la información de la clase</p>
            </div>
            <a href="{{ route('admin.clases.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>Volver al listado
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-modern p-4 p-lg-5">
            <form action="{{ route('admin.clases.update', $clase->id) }}" method="POST" id="claseForm" novalidate>
                @csrf
                @method('PUT')
                
                <!-- Stepper Indicator -->
                <div class="stepper-wrapper mb-5">
                    <div class="stepper-item active" data-step="1">
                        <div class="step-counter">1</div>
                        <div class="step-name">Información Básica</div>
                    </div>
                    <div class="stepper-item" data-step="2">
                        <div class="step-counter">2</div>
                        <div class="step-name">Recursos Multimedia</div>
                    </div>
                </div>
                
                <!-- Step 1: Información Básica -->
                <div class="step-content" data-step="1">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));">
                            <i class="fas fa-info-circle fa-2x" style="color: #4361ee;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Información Básica</h4>
                            <p class="text-muted small mb-0">Datos principales de la clase</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-book text-primary me-1"></i> Materia <span class="text-danger">*</span>
                            </label>
                            <select name="id_asignatura" id="id_asignatura" class="form-select form-select-lg @error('id_asignatura') is-invalid @enderror" required>
                                <option value="">Seleccione una materia</option>
                                @foreach($asignaturas as $asignatura)
                                    <option value="{{ $asignatura->id }}" {{ old('id_asignatura', $clase->id_asignatura) == $asignatura->id ? 'selected' : '' }}>
                                        {{ $asignatura->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_asignatura')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-hashtag text-primary me-1"></i> Número de Clase <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-sort-numeric-up"></i></span>
                                <input type="number" name="num_clase" id="num_clase" 
                                       class="form-control @error('num_clase') is-invalid @enderror" 
                                       value="{{ old('num_clase', $clase->num_clase) }}" placeholder="Ej: 1, 2, 3..." min="1" required>
                            </div>
                            @error('num_clase')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Orden de la clase dentro de la materia</small>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-heading text-primary me-1"></i> Nombre de la Clase <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-tag"></i></span>
                                <input type="text" name="nombre_clase" id="nombre_clase" 
                                       class="form-control @error('nombre_clase') is-invalid @enderror" 
                                       value="{{ old('nombre_clase', $clase->nombre_clase) }}" placeholder="Ej: Introducción a la Física" required>
                            </div>
                            @error('nombre_clase')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: Recursos Multimedia -->
                <div class="step-content" data-step="2" style="display: none;">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(247,37,133,0.1), rgba(114,9,183,0.1));">
                            <i class="fas fa-video fa-2x" style="color: #f72585;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Recursos Multimedia</h4>
                            <p class="text-muted small mb-0">Seleccione un video del catálogo o agregue un enlace personalizado</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <!-- Opción 1: Seleccionar video del catálogo -->
                        <div class="col-12">
                            <div class="card bg-light border-0 rounded-3 p-3">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="fas fa-database text-primary"></i>
                                    <h6 class="mb-0 fw-semibold">Seleccionar video del catálogo</h6>
                                </div>
                                <select name="video_selected" id="video_selected" class="form-select form-select-lg">
                                    <option value="">-- Seleccione un video --</option>
                                    @foreach($videos as $video)
                                        <option value="{{ $video->link }}" 
                                                data-materia="{{ $video->materia }}"
                                                data-tema="{{ $video->tema }}"
                                                data-titulo="{{ $video->titulo }}"
                                                data-duracion="{{ $video->duracion }}"
                                                {{ old('video_selected', $clase->link) == $video->link ? 'selected' : '' }}>
                                            [{{ $video->materia }}] {{ $video->tema }} - {{ $video->titulo }} ({{ $video->duracion ?? 'Sin duración' }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Al seleccionar un video, el enlace se cargará automáticamente en el campo de abajo.
                                    Si no existe el video, <a href="{{ route('admin.videos.create') }}" target="_blank">cree uno nuevo aquí</a>
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="position-relative">
                                <div class="text-center my-3">
                                    <span class="bg-white px-3 text-muted">o</span>
                                    <hr class="my-0">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Opción 2: Enlace personalizado -->
                        <div class="col-12">
                            <div class="card bg-light border-0 rounded-3 p-3">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="fab fa-youtube text-danger"></i>
                                    <h6 class="mb-0 fw-semibold">Enlace personalizado</h6>
                                </div>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-transparent"><i class="fab fa-youtube text-danger"></i></span>
                                    <input type="url" name="link" id="link" 
                                           class="form-control @error('link') is-invalid @enderror" 
                                           value="{{ old('link', $clase->link) }}" 
                                           placeholder="https://www.youtube.com/watch?v=... o https://youtu.be/...">
                                </div>
                                @error('link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Puedes pegar directamente el enlace del video (YouTube, Vimeo, etc.)
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-file-alt text-info me-1"></i> Material de Apoyo (enlace externo)
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-link"></i></span>
                                <input type="url" name="url" id="url" 
                                       class="form-control @error('url') is-invalid @enderror" 
                                       value="{{ old('url', $clase->url) }}" 
                                       placeholder="https://drive.google.com/... o enlace externo">
                            </div>
                            @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Enlace a material complementario (Google Drive, PDF, etc.) - Opcional</small>
                        </div>
                    </div>
                </div>
                
                <!-- Botones de navegación -->
                <div class="d-flex justify-content-between gap-3 mt-5 pt-4 border-top">
                    <button type="button" class="btn btn-secondary px-5 py-3" id="prevBtn" style="display: none;">
                        <i class="fas fa-arrow-left me-2"></i> Anterior
                    </button>
                    <button type="button" class="btn btn-primary px-5 py-3" id="nextBtn">
                        Siguiente <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                    <button type="submit" class="btn btn-save px-5 py-3" id="submitBtn" style="display: none;">
                        <i class="fas fa-save me-2"></i> Actualizar Clase
                    </button>
                    <a href="{{ route('admin.clases.index') }}" class="btn btn-cancel px-4 py-3">
                        <i class="fas fa-times me-2"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stepper-wrapper {
        display: flex;
        justify-content: space-between;
        position: relative;
        background: transparent;
    }
    
    .stepper-wrapper::before {
        content: '';
        position: absolute;
        top: 25px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e0e0e0;
        z-index: 1;
    }
    
    .stepper-item {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }
    
    .step-counter {
        width: 50px;
        height: 50px;
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: bold;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }
    
    .stepper-item.active .step-counter {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-color: #667eea;
        color: white;
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(102,126,234,0.3);
    }
    
    .stepper-item.completed .step-counter {
        background: #4caf50;
        border-color: #4caf50;
        color: white;
    }
    
    .step-name {
        font-size: 0.85rem;
        color: #666;
        font-weight: 500;
    }
    
    .stepper-item.active .step-name {
        color: #667eea;
        font-weight: 600;
    }
    
    .form-label {
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    
    .form-control-lg, .form-select-lg, .input-group-lg .form-control {
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    
    .form-control-lg:focus, .form-select-lg:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
    }
    
    .input-group-text {
        border-radius: 12px 0 0 12px;
        border: 2px solid #e0e0e0;
        border-right: none;
        background: white;
    }
    
    .input-group .form-control {
        border-left: none;
    }
    
    .card-modern {
        background: white;
        border-radius: 20px;
        transition: transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    
    .card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(67,97,238,0.15);
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
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        border-radius: 50px;
        font-weight: 600;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }
    
    .btn-secondary {
        background: #6c757d;
        border: none;
        border-radius: 50px;
        font-weight: 600;
    }
    
    .btn-secondary:hover {
        transform: translateY(-2px);
        background: #5a6268;
    }
    
    .step-content {
        animation: fadeIn 0.5s ease;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    hr {
        opacity: 0.5;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endpush

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 2;
    
    // Auto-completar el campo link al seleccionar un video del catálogo
    document.getElementById('video_selected')?.addEventListener('change', function() {
        const linkInput = document.getElementById('link');
        if (this.value) {
            linkInput.value = this.value;
            linkInput.dispatchEvent(new Event('input'));
            
            // Mostrar información del video seleccionado
            const selectedOption = this.options[this.selectedIndex];
            const titulo = selectedOption.getAttribute('data-titulo') || '';
            const materia = selectedOption.getAttribute('data-materia') || '';
            const tema = selectedOption.getAttribute('data-tema') || '';
            
            if (titulo) {
                Swal.fire({
                    icon: 'success',
                    title: 'Video seleccionado',
                    text: `Se cargó: ${materia} - ${tema} - ${titulo}`,
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        }
    });
    
    // Validar paso actual
    function validarPaso(step) {
        if (step === 1) {
            const idAsignatura = document.getElementById('id_asignatura').value;
            const numClase = document.getElementById('num_clase').value.trim();
            const nombreClase = document.getElementById('nombre_clase').value.trim();
            
            if (!idAsignatura) {
                Swal.fire('Error', 'Debe seleccionar una materia', 'error');
                return false;
            }
            
            if (!numClase) {
                Swal.fire('Error', 'Debe ingresar el número de clase', 'error');
                return false;
            }
            
            if (!nombreClase) {
                Swal.fire('Error', 'Debe ingresar el nombre de la clase', 'error');
                return false;
            }
            
            return true;
        }
        
        return true;
    }
    
    // Navegación entre pasos
    function mostrarPaso(step) {
        document.querySelectorAll('.step-content').forEach(content => {
            content.style.display = 'none';
        });
        document.querySelector(`.step-content[data-step="${step}"]`).style.display = 'block';
        
        document.querySelectorAll('.stepper-item').forEach((item, index) => {
            const stepNum = index + 1;
            if (stepNum === step) {
                item.classList.add('active');
                item.classList.remove('completed');
            } else if (stepNum < step) {
                item.classList.add('completed');
                item.classList.remove('active');
            } else {
                item.classList.remove('active', 'completed');
            }
        });
        
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');
        
        if (step === 1) {
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'block';
            submitBtn.style.display = 'none';
        } else if (step === totalSteps) {
            prevBtn.style.display = 'block';
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'block';
        } else {
            prevBtn.style.display = 'block';
            nextBtn.style.display = 'block';
            submitBtn.style.display = 'none';
        }
    }
    
    // Event Listeners
    document.getElementById('nextBtn').addEventListener('click', function() {
        if (validarPaso(currentStep)) {
            currentStep++;
            mostrarPaso(currentStep);
        }
    });
    
    document.getElementById('prevBtn').addEventListener('click', function() {
        if (currentStep > 1) {
            currentStep--;
            mostrarPaso(currentStep);
        }
    });
    
    // Validación del formulario
    document.getElementById('claseForm').addEventListener('submit', function(e) {
        if (!validarPaso(1)) {
            e.preventDefault();
            return false;
        }
        
        const btnSubmit = document.getElementById('submitBtn');
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Actualizando...';
    });
    
    // Inicializar
    mostrarPaso(1);
</script>
@endpush