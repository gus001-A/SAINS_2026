@extends('administrador.layouts.master')

@section('title', 'Nueva Pregunta - SAINS')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="display-5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                    <i class="fas fa-question-circle me-3"></i>Nueva Pregunta
                </h1>
                <p class="text-muted">Registre una nueva pregunta para los exámenes</p>
            </div>
            <a href="{{ route('admin.preguntas.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>Volver al listado
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-modern p-4 p-lg-5">
            <form action="{{ route('admin.preguntas.store') }}" method="POST" id="preguntaForm" novalidate>
                @csrf
                
                <!-- Stepper Indicator -->
                <div class="stepper-wrapper mb-5">
                    <div class="stepper-item active" data-step="1">
                        <div class="step-counter">1</div>
                        <div class="step-name">Información General</div>
                    </div>
                    <div class="stepper-item" data-step="2">
                        <div class="step-counter">2</div>
                        <div class="step-name">Opciones de Respuesta</div>
                    </div>
                    <div class="stepper-item" data-step="3">
                        <div class="step-counter">3</div>
                        <div class="step-name">Justificación</div>
                    </div>
                </div>
                
                <!-- Step 1: Información General -->
                <div class="step-content" data-step="1">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));">
                            <i class="fas fa-question-circle fa-2x" style="color: #4361ee;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Información General</h4>
                            <p class="text-muted small mb-0">Datos básicos de la pregunta</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-layer-group text-primary me-1"></i> Área de Conocimiento <span class="text-danger">*</span>
                            </label>
                            <select name="id_area" id="id_area" class="form-select form-select-lg @error('id_area') is-invalid @enderror" required>
                                <option value="">Seleccionar área</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ old('id_area') == $area->id ? 'selected' : '' }}>{{ $area->nombre }}</option>
                                @endforeach
                            </select>
                            @error('id_area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Seleccione el área a la que pertenece la pregunta</small>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-pen-alt text-primary me-1"></i> Pregunta <span class="text-danger">*</span>
                            </label>
                            <textarea name="pregunta" id="pregunta" class="form-control form-control-lg @error('pregunta') is-invalid @enderror" 
                                      rows="5" placeholder="Escriba la pregunta aquí..." required>{{ old('pregunta') }}</textarea>
                            @error('pregunta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Escriba el enunciado de la pregunta de forma clara y precisa</small>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: Opciones de Respuesta -->
                <div class="step-content" data-step="2" style="display: none;">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(247,37,133,0.1), rgba(114,9,183,0.1));">
                            <i class="fas fa-check-double fa-2x" style="color: #f72585;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Opciones de Respuesta</h4>
                            <p class="text-muted small mb-0">Defina las opciones y seleccione la correcta</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-circle-check text-success me-1"></i> Respuesta Correcta <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-check-circle text-success"></i></span>
                                <input type="text" name="respuesta_correcta" id="respuesta_correcta" 
                                       class="form-control @error('respuesta_correcta') is-invalid @enderror" 
                                       value="{{ old('respuesta_correcta') }}" placeholder="Ej: 42" required>
                            </div>
                            @error('respuesta_correcta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Respuesta válida y correcta</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-circle-xmark text-danger me-1"></i> Opción Incorrecta 1
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-times-circle text-danger"></i></span>
                                <input type="text" name="respuesta1" id="respuesta1" 
                                       class="form-control @error('respuesta1') is-invalid @enderror" 
                                       value="{{ old('respuesta1') }}" placeholder="Ej: 41">
                            </div>
                            @error('respuesta1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Primera opción incorrecta (opcional)</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-circle-xmark text-danger me-1"></i> Opción Incorrecta 2
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-times-circle text-danger"></i></span>
                                <input type="text" name="respuesta2" id="respuesta2" 
                                       class="form-control @error('respuesta2') is-invalid @enderror" 
                                       value="{{ old('respuesta2') }}" placeholder="Ej: 43">
                            </div>
                            @error('respuesta2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Segunda opción incorrecta (opcional)</small>
                        </div>
                    </div>
                </div>
                
                <!-- Step 3: Justificación -->
                <div class="step-content" data-step="3" style="display: none;">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(76,175,80,0.1), rgba(33,150,243,0.1));">
                            <i class="fas fa-file-alt fa-2x" style="color: #4caf50;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Justificación de la Respuesta</h4>
                            <p class="text-muted small mb-0">Explique por qué la respuesta es correcta</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-lightbulb text-warning me-1"></i> Justificación
                            </label>
                            <textarea name="justificacion" id="justificacion" 
                                      class="form-control form-control-lg @error('justificacion') is-invalid @enderror" 
                                      rows="6" 
                                      placeholder="Ej: La respuesta es correcta porque...&#10;&#10;Explique detalladamente el razonamiento detrás de la respuesta correcta. Esto ayudará a los estudiantes a comprender mejor el tema.">{{ old('justificacion') }}</textarea>
                            @error('justificacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                La justificación se mostrará al estudiante después de responder la pregunta. 
                                Si se deja vacío, se mostrará un mensaje genérico indicando la respuesta correcta.
                            </small>
                        </div>
                        
                        <div class="col-12">
                            <div class="alert alert-info border-0 rounded-4" role="alert">
                                <i class="fas fa-tip me-2"></i>
                                <strong>Consejo:</strong> Una buena justificación debe explicar el razonamiento, 
                                mencionar conceptos clave y ayudar al estudiante a entender por qué esa es la respuesta correcta.
                            </div>
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
                        <i class="fas fa-save me-2"></i> Guardar Pregunta
                    </button>
                    <a href="{{ route('admin.preguntas.index') }}" class="btn btn-cancel px-4 py-3">
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
    /* Stepper Styles */
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
    
    /* Form Styles */
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
    
    textarea.form-control-lg {
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    
    .form-control-lg:focus, .form-select-lg:focus, textarea.form-control-lg:focus {
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
    
    .input-group .btn {
        border-radius: 0 12px 12px 0;
        border: 2px solid #e0e0e0;
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
    
    /* Invalid feedback */
    .invalid-feedback {
        font-size: 0.8rem;
        margin-top: 0.25rem;
        color: #dc3545;
    }
    
    /* Buttons */
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
    
    .btn-secondary {
        background: #6c757d;
        border: none;
        border-radius: 50px;
        font-weight: 600;
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
    
    /* Animations */
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
    
    /* Alert styles */
    .alert-info {
        background: linear-gradient(135deg, rgba(33,150,243,0.1), rgba(33,150,243,0.05));
        border-left: 4px solid #2196f3;
    }
</style>
@endpush

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 3;
    
    // Validar paso actual
    function validarPaso(step) {
        if (step === 1) {
            const idArea = document.getElementById('id_area').value;
            const pregunta = document.getElementById('pregunta').value.trim();
            
            if (!idArea) {
                Swal.fire('Error', 'Debe seleccionar un área de conocimiento', 'error');
                return false;
            }
            
            if (!pregunta) {
                Swal.fire('Error', 'Debe ingresar el texto de la pregunta', 'error');
                return false;
            }
            
            return true;
        }
        
        if (step === 2) {
            const respuestaCorrecta = document.getElementById('respuesta_correcta').value.trim();
            
            if (!respuestaCorrecta) {
                Swal.fire('Error', 'Debe ingresar la respuesta correcta', 'error');
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
    document.getElementById('preguntaForm').addEventListener('submit', function(e) {
        if (!validarPaso(1) || !validarPaso(2)) {
            e.preventDefault();
            return false;
        }
        
        const btnSubmit = document.getElementById('submitBtn');
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando...';
    });
    
    // Inicializar
    mostrarPaso(1);
</script>
@endpush