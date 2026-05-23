@extends('administrador.layouts.master')

@section('title', 'Editar Universidad - SAINS')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="display-5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                    <i class="fas fa-edit me-3"></i>Editar Universidad
                </h1>
                <p class="text-muted">Modifique la información de la universidad</p>
            </div>
            <a href="{{ route('admin.universidades.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>Volver al listado
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-modern p-4 p-lg-5">
            <form action="{{ route('admin.universidades.update', $universidad->id) }}" method="POST" id="universidadForm" novalidate>
                @csrf
                @method('PUT')
                
                <!-- Stepper Indicator -->
                <div class="stepper-wrapper mb-5">
                    <div class="stepper-item active" data-step="1">
                        <div class="step-counter">1</div>
                        <div class="step-name">Ubicación</div>
                    </div>
                    <div class="stepper-item" data-step="2">
                        <div class="step-counter">2</div>
                        <div class="step-name">Información Académica</div>
                    </div>
                </div>
                
                <!-- Step 1: Ubicación -->
                <div class="step-content" data-step="1">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));">
                            <i class="fas fa-map-marker-alt fa-2x" style="color: #4361ee;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Ubicación</h4>
                            <p class="text-muted small mb-0">Datos de ubicación de la universidad</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-map text-primary me-1"></i> Estado <span class="text-danger">*</span>
                            </label>
                            <select name="estado" id="estado" class="form-select form-select-lg @error('estado') is-invalid @enderror" required>
                                <option value="">Seleccionar estado</option>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado }}" {{ old('estado', $universidad->estado) == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                @endforeach
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Seleccione el estado donde se ubica la universidad</small>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-city text-primary me-1"></i> Municipio <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-building"></i></span>
                                <input type="text" name="municipio" id="municipio" 
                                       class="form-control @error('municipio') is-invalid @enderror" 
                                       value="{{ old('municipio', $universidad->municipio) }}" placeholder="Ej: Cuernavaca, Jiutepec..." required>
                            </div>
                            @error('municipio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-location-dot text-primary me-1"></i> Localidad
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-map-pin"></i></span>
                                <input type="text" name="localidad" id="localidad" 
                                       class="form-control @error('localidad') is-invalid @enderror" 
                                       value="{{ old('localidad', $universidad->localidad) }}" placeholder="Ej: Centro, Satélite...">
                            </div>
                            @error('localidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Opcional</small>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-address-card text-primary me-1"></i> Dirección
                            </label>
                            <textarea name="direccion" id="direccion" class="form-control form-control-lg @error('direccion') is-invalid @enderror" 
                                      rows="3" placeholder="Calle, número, colonia, código postal...">{{ old('direccion', $universidad->direccion) }}</textarea>
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Dirección completa de la universidad (opcional)</small>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: Información Académica -->
                <div class="step-content" data-step="2" style="display: none;">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(247,37,133,0.1), rgba(114,9,183,0.1));">
                            <i class="fas fa-graduation-cap fa-2x" style="color: #f72585;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Información Académica</h4>
                            <p class="text-muted small mb-0">Datos académicos de la universidad</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-hashtag text-primary me-1"></i> Clave <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-key"></i></span>
                                <input type="text" name="clave" id="clave" 
                                       class="form-control @error('clave') is-invalid @enderror" 
                                       value="{{ old('clave', $universidad->clave) }}" placeholder="Ej: UNAM, UAEM, IPN, BUAP" required>
                            </div>
                            @error('clave')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Siglas o identificador único de la universidad</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-tag text-primary me-1"></i> Tipo
                            </label>
                            <select name="tipo" id="tipo" class="form-select form-select-lg">
                                <option value="">Seleccionar tipo</option>
                                <option value="PUBLICA" {{ old('tipo', $universidad->tipo) == 'PUBLICA' ? 'selected' : '' }}>PÚBLICA</option>
                                <option value="PRIVADA" {{ old('tipo', $universidad->tipo) == 'PRIVADA' ? 'selected' : '' }}>PRIVADA</option>
                                <option value="AUTONOMA" {{ old('tipo', $universidad->tipo) == 'AUTONOMA' ? 'selected' : '' }}>AUTÓNOMA</option>
                            </select>
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Tipo de institución</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-book-open text-primary me-1"></i> Carrera
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-book"></i></span>
                                <select name="carrera_id" id="carrera_id" class="form-select form-select-lg">
                                    <option value="">Seleccionar carrera (opcional)</option>
                                    @foreach($carreras as $carrera)
                                        <option value="{{ $carrera->id }}" {{ old('carrera_id', $universidad->carrera_id) == $carrera->id ? 'selected' : '' }}>
                                            {{ $carrera->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Si la universidad ofrece una carrera específica</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-hourglass-half text-primary me-1"></i> Duración
                            </label>
                            <select name="duracion" id="duracion" class="form-select form-select-lg">
                                <option value="">Seleccionar duración</option>
                                <option value="4 AÑOS" {{ old('duracion', $universidad->duracion) == '4 AÑOS' ? 'selected' : '' }}>4 AÑOS (Licenciatura)</option>
                                <option value="5 AÑOS" {{ old('duracion', $universidad->duracion) == '5 AÑOS' ? 'selected' : '' }}>5 AÑOS (Ingeniería)</option>
                                <option value="3 AÑOS" {{ old('duracion', $universidad->duracion) == '3 AÑOS' ? 'selected' : '' }}>3 AÑOS (TSU)</option>
                            </select>
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Duración estándar de la carrera</small>
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
                    <button type="submit" class="btn btn-update px-5 py-3" id="submitBtn" style="display: none;">
                        <i class="fas fa-save me-2"></i> Actualizar Universidad
                    </button>
                    <a href="{{ route('admin.universidades.index') }}" class="btn btn-cancel px-4 py-3">
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
        background: white;
    }
    
    .input-group .form-control {
        border-left: none;
    }
    
    .input-group .form-control:focus {
        border-left: none;
    }
    
    .input-group .form-select {
        border-radius: 12px;
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
    .btn-update {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-update:hover {
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
</style>
@endpush

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 2;
    
    // Validar paso actual
    function validarPaso(step) {
        if (step === 1) {
            const estado = document.getElementById('estado').value;
            const municipio = document.getElementById('municipio').value.trim();
            
            if (!estado) {
                Swal.fire('Error', 'Debe seleccionar un estado', 'error');
                return false;
            }
            
            if (!municipio) {
                Swal.fire('Error', 'Debe ingresar el municipio', 'error');
                return false;
            }
            
            return true;
        }
        
        if (step === 2) {
            const clave = document.getElementById('clave').value.trim();
            
            if (!clave) {
                Swal.fire('Error', 'Debe ingresar la clave de la universidad', 'error');
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
    document.getElementById('universidadForm').addEventListener('submit', function(e) {
        if (!validarPaso(1) || !validarPaso(2)) {
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