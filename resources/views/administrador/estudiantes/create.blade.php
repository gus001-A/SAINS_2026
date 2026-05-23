@extends('administrador.layouts.master')

@section('title', 'Nuevo Estudiante - SAINS')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="display-5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                    <i class="fas fa-user-graduate me-3"></i>Nuevo Estudiante
                </h1>
                <p class="text-muted">Complete el formulario para registrar un nuevo estudiante en el sistema</p>
            </div>
            <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>Volver al listado
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-modern p-4 p-lg-5">
            <form action="{{ route('admin.estudiantes.store') }}" method="POST" id="estudianteForm" novalidate>
                @csrf
                
                <!-- Stepper Indicator -->
                <div class="stepper-wrapper mb-5">
                    <div class="stepper-item active" data-step="1">
                        <div class="step-counter">1</div>
                        <div class="step-name">Datos Personales</div>
                    </div>
                    <div class="stepper-item" data-step="2">
                        <div class="step-counter">2</div>
                        <div class="step-name">Datos Académicos</div>
                    </div>
                    <div class="stepper-item" data-step="3">
                        <div class="step-counter">3</div>
                        <div class="step-name">Datos de Acceso</div>
                    </div>
                    <div class="stepper-item" data-step="4">
                        <div class="step-counter">4</div>
                        <div class="step-name">Confirmación</div>
                    </div>
                </div>
                
                <!-- Step 1: Datos Personales -->
                <div class="step-content" data-step="1">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));">
                            <i class="fas fa-user-circle fa-2x" style="color: #4361ee;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Información Personal</h4>
                            <p class="text-muted small mb-0">Datos básicos del estudiante</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user text-primary me-1"></i> Nombre <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nombre" class="form-control form-control-lg @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre') }}" placeholder="Ej: Juan" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user-tie text-primary me-1"></i> Apellido Paterno <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="paterno" class="form-control form-control-lg @error('paterno') is-invalid @enderror" 
                                   value="{{ old('paterno') }}" placeholder="Ej: Pérez" required>
                            @error('paterno')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user-friends text-primary me-1"></i> Apellido Materno
                            </label>
                            <input type="text" name="materno" class="form-control form-control-lg @error('materno') is-invalid @enderror" 
                                   value="{{ old('materno') }}" placeholder="Ej: García">
                            @error('materno')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar-alt text-primary me-1"></i> Fecha de Nacimiento <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" 
                                class="form-control form-control-lg @error('fecha_nacimiento') is-invalid @enderror" 
                                value="{{ old('fecha_nacimiento') }}" 
                                max="{{ date('Y-m-d', strtotime('-15 years')) }}" required>
                            @error('fecha_nacimiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Debe ser mayor de 15 años</small>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-venus-mars text-primary me-1"></i> Sexo <span class="text-danger">*</span>
                            </label>
                            <select name="sexo" class="form-select form-select-lg @error('sexo') is-invalid @enderror" required>
                                <option value="">Seleccionar</option>
                                <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                            @error('sexo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-phone-alt text-primary me-1"></i> Teléfono Celular <span class="text-danger">*</span>
                            </label>
                            <input type="tel" name="telefono" id="telefono" class="form-control form-control-lg @error('telefono') is-invalid @enderror" 
                                   value="{{ old('telefono') }}" placeholder="7771234567" maxlength="10" 
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>      
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>10 dígitos, solo números</small>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-home text-primary me-1"></i> Teléfono Casa
                            </label>
                            <input type="tel" name="telefono_casa" id="telefono_casa" class="form-control form-control-lg @error('telefono_casa') is-invalid @enderror" 
                                   value="{{ old('telefono_casa') }}" placeholder="7771234567" maxlength="10" 
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">      
                            @error('telefono_casa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>10 dígitos, solo números (opcional)</small>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: Datos Académicos -->
                <div class="step-content" data-step="2" style="display: none;">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(247,37,133,0.1), rgba(114,9,183,0.1));">
                            <i class="fas fa-graduation-cap fa-2x" style="color: #f72585;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Información Académica</h4>
                            <p class="text-muted small mb-0">Datos de escuela y universidad</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <!-- Selección de Preparatoria -->
                        <div class="col-12">
                            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-school me-2"></i>Escuela de Procedencia</h6>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-map text-primary me-1"></i> Estado (Escuela)
                            </label>
                            <select id="estado_prepa" class="form-select form-select-lg">
                                <option value="">Seleccionar estado</option>
                                @foreach($estadosPrepa as $estado)
                                    <option value="{{ $estado }}" {{ old('estado_prepa') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-city text-primary me-1"></i> Municipio (Escuela)
                            </label>
                            <select id="municipio_prepa" class="form-select form-select-lg" disabled>
                                <option value="">Primero seleccione un estado</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-location-dot text-primary me-1"></i> Localidad (Escuela)
                            </label>
                            <select id="localidad_prepa" class="form-select form-select-lg" disabled>
                                <option value="">Primero seleccione un municipio</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-building text-primary me-1"></i> Escuela de Procedencia <span class="text-danger">*</span>
                            </label>
                            <select name="escuela_procedencia" id="escuela_procedencia" class="form-select form-select-lg @error('escuela_procedencia') is-invalid @enderror" required>
                                <option value="">Seleccione estado, municipio y localidad</option>
                            </select>
                            @error('escuela_procedencia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Selección de Universidad -->
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-university me-2"></i>Universidad de Interés</h6>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-map text-primary me-1"></i> Estado (Universidad)
                            </label>
                            <select id="estado_uni" class="form-select form-select-lg">
                                <option value="">Seleccionar estado</option>
                                @foreach($estadosUniversidad as $estado)
                                    <option value="{{ $estado }}" {{ old('estado_uni') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-city text-primary me-1"></i> Municipio (Universidad)
                            </label>
                            <select id="municipio_uni" class="form-select form-select-lg" disabled>
                                <option value="">Primero seleccione un estado</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-location-dot text-primary me-1"></i> Localidad (Universidad)
                            </label>
                            <select id="localidad_uni" class="form-select form-select-lg" disabled>
                                <option value="">Primero seleccione un municipio</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-university text-primary me-1"></i> Universidad de Interés <span class="text-danger">*</span>
                            </label>
                            <select name="universidad_interes" id="universidad_interes" class="form-select form-select-lg @error('universidad_interes') is-invalid @enderror" required>
                                <option value="">Seleccione estado, municipio y localidad</option>
                            </select>
                            @error('universidad_interes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Cupón y Plan -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-ticket-alt text-primary me-1"></i> Código de Cupón
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-ticket-alt text-primary"></i></span>
                                <select name="cupon_id" id="cupon_id" class="form-select @error('cupon_id') is-invalid @enderror">
                                    <option value="">Sin cupón</option>
                                    @foreach($cuponesDisponibles ?? [] as $cupon)
                                        <option value="{{ $cupon->id }}" {{ old('cupon_id') == $cupon->id ? 'selected' : '' }}>
                                            {{ $cupon->codigo }} - 
                                            @if($cupon->tipo_descuento == 'porcentaje')
                                                {{ $cupon->valor_descuento }}% de descuento
                                            @else
                                                ${{ number_format($cupon->valor_descuento, 2) }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-secondary" id="validarCuponBtn" title="Validar cupón">
                                    <i class="fas fa-check-circle"></i> Validar
                                </button>
                            </div>
                            @error('cupon_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" id="cuponMensaje"><i class="fas fa-info-circle me-1"></i>Seleccione un cupón de descuento si está disponible</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-star text-primary me-1"></i> Plan Activo
                            </label>
                            <div class="form-check form-switch mt-2">
                                <input type="checkbox" name="plan_activo" class="form-check-input" id="plan_activo" style="width: 50px; height: 25px;" 
                                       value="1" {{ old('plan_activo') ? 'checked' : '' }}>
                                <label class="form-check-label ms-2 fw-semibold" for="plan_activo" id="planActivoLabel">
                                    {{ old('plan_activo') ? 'Activo' : 'Inactivo' }}
                                </label>
                            </div>
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>¿El estudiante tiene plan activo?</small>
                        </div>
                    </div>
                </div>
                
                <!-- Step 3: Datos de Acceso -->
                <div class="step-content" data-step="3" style="display: none;">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(76,175,80,0.1), rgba(139,195,74,0.1));">
                            <i class="fas fa-key fa-2x" style="color: #4caf50;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Credenciales de Acceso</h4>
                            <p class="text-muted small mb-0">Configure las credenciales para ingresar al sistema</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-envelope text-primary me-1"></i> Correo Electrónico <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-envelope text-primary"></i></span>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" placeholder="estudiante@ejemplo.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-shield-alt text-primary me-1"></i> Rol
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-user-graduate text-primary"></i></span>
                                <input type="text" class="form-control" value="Estudiante" disabled>
                                <input type="hidden" name="rol" value="estudiante">
                            </div>
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>El rol se asigna automáticamente</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-lock text-primary me-1"></i> Contraseña <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-key text-primary"></i></span>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" placeholder="Mínimo 6 caracteres" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                                    <i class="far fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary generate-password" title="Generar contraseña aleatoria">
                                    <i class="fas fa-dice-d6"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="password-strength mt-2">
                                <div class="strength-bar"></div>
                                <small class="strength-text text-muted"></small>
                            </div>
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Mínimo 6 caracteres</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-check-circle text-primary me-1"></i> Confirmar Contraseña <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-check-circle text-primary"></i></span>
                                <input type="password" name="password_confirmation" class="form-control" 
                                       id="password_confirmation" placeholder="Repite la contraseña" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password_confirmation">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 4: Confirmación -->
                <div class="step-content" data-step="4" style="display: none;">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(255,152,0,0.1), rgba(255,87,34,0.1));">
                            <i class="fas fa-check-circle fa-2x" style="color: #ff9800;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Confirmar Registro</h4>
                            <p class="text-muted small mb-0">Revise los datos antes de guardar</p>
                        </div>
                    </div>
                    
                    <div class="confirmation-card p-4 bg-light rounded-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-user me-2"></i>Datos Personales</h6>
                                <div class="confirmation-item">
                                    <strong>Nombre completo:</strong>
                                    <span id="confirm_nombre_completo"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Fecha de nacimiento:</strong>
                                    <span id="confirm_fecha_nacimiento"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Sexo:</strong>
                                    <span id="confirm_sexo"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Teléfono celular:</strong>
                                    <span id="confirm_telefono"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Teléfono casa:</strong>
                                    <span id="confirm_telefono_casa"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-graduation-cap me-2"></i>Datos Académicos</h6>
                                <div class="confirmation-item">
                                    <strong>Escuela de procedencia:</strong>
                                    <span id="confirm_escuela"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Universidad de interés:</strong>
                                    <span id="confirm_universidad"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Cupón:</strong>
                                    <span id="confirm_cupon"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Plan activo:</strong>
                                    <span id="confirm_plan"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Correo electrónico:</strong>
                                    <span id="confirm_email"></span>
                                </div>
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
                        <i class="fas fa-save me-2"></i> Guardar Estudiante
                    </button>
                    <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-cancel px-4 py-3">
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
    
    .form-control-lg:focus, .form-select-lg:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
    }
    
    .input-group-text {
        border-radius: 12px 0 0 12px;
        border: 2px solid #e0e0e0;
        border-right: none;
    }
    
    .input-group .form-control, .input-group .form-select {
        border-left: none;
    }
    
    .input-group .form-control:focus, .input-group .form-select:focus {
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
    
    /* Password Strength */
    .password-strength {
        width: 100%;
    }
    
    .strength-bar {
        height: 4px;
        background: #e0e0e0;
        border-radius: 2px;
        transition: all 0.3s ease;
    }
    
    /* Confirmation Card */
    .confirmation-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 20px;
        border: 1px solid rgba(102,126,234,0.2);
    }
    
    .confirmation-item {
        padding: 8px 0;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .confirmation-item:last-child {
        border-bottom: none;
    }
    
    /* Switch */
    .form-switch .form-check-input {
        cursor: pointer;
        background-color: #e0e0e0;
        border-color: #e0e0e0;
        width: 50px;
        height: 25px;
    }
    
    .form-switch .form-check-input:checked {
        background-color: #4caf50;
        border-color: #4caf50;
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
    
    /* Invalid feedback */
    .invalid-feedback {
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }
    
    select:disabled {
        background-color: #f5f5f5;
        cursor: not-allowed;
    }
</style>
@endpush

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 4;
    
    // ========== FUNCIONES GENERALES ==========
    
    function generarContraseña(longitud = 12) {
        const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
        let contraseña = '';
        for (let i = 0; i < longitud; i++) {
            contraseña += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
        }
        return contraseña;
    }
    
    function calcularEdad(fechaNacimiento) {
        const hoy = new Date();
        const nacimiento = new Date(fechaNacimiento);
        let edad = hoy.getFullYear() - nacimiento.getFullYear();
        const mes = hoy.getMonth() - nacimiento.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
            edad--;
        }
        return edad;
    }
    
    function validarFechaNacimiento(fecha) {
        if (!fecha) return true;
        
        const hoy = new Date();
        const fechaSeleccionada = new Date(fecha);
        
        if (fechaSeleccionada > hoy) {
            Swal.fire('Error', 'La fecha de nacimiento no puede ser mayor a la fecha actual', 'error');
            return false;
        }
        
        const edad = calcularEdad(fecha);
        if (edad < 15) {
            Swal.fire('Error', 'Debe ser mayor de 15 años para registrarse como estudiante', 'error');
            return false;
        }
        
        return true;
    }
    
    function validarTelefono(telefono) {
        if (!telefono) return true;
        
        if (!/^\d+$/.test(telefono)) {
            Swal.fire('Error', 'El teléfono solo debe contener números', 'error');
            return false;
        }
        
        if (telefono.length !== 10) {
            Swal.fire('Error', 'El teléfono debe tener exactamente 10 dígitos', 'error');
            return false;
        }
        
        return true;
    }
    
    function validarContraseña(password) {
        const tieneMayuscula = /[A-Z]/.test(password);
        const tieneMinuscula = /[a-z]/.test(password);
        const tieneNumero = /[0-9]/.test(password);
        const tieneEspecial = /[!@#$%^&*]/.test(password);
        const longitudValida = password.length >= 6;
        
        return {
            valida: longitudValida && (tieneMayuscula || tieneMinuscula) && tieneNumero,
            tieneMayuscula,
            tieneMinuscula,
            tieneNumero,
            tieneEspecial,
            longitudValida
        };
    }
    
    function actualizarFuerzaContraseña() {
        const password = document.getElementById('password').value;
        const validation = validarContraseña(password);
        const strengthBar = document.querySelector('.strength-bar');
        const strengthText = document.querySelector('.strength-text');
        
        if (!password) {
            strengthBar.style.width = '0%';
            strengthBar.style.background = '#e0e0e0';
            strengthText.textContent = '';
            return;
        }
        
        let puntos = 0;
        if (validation.longitudValida) puntos++;
        if (validation.tieneMayuscula) puntos++;
        if (validation.tieneMinuscula) puntos++;
        if (validation.tieneNumero) puntos++;
        if (validation.tieneEspecial) puntos++;
        
        const porcentaje = (puntos / 5) * 100;
        strengthBar.style.width = porcentaje + '%';
        
        if (porcentaje < 40) {
            strengthBar.style.background = '#dc3545';
            strengthText.textContent = 'Contraseña débil';
            strengthText.style.color = '#dc3545';
        } else if (porcentaje < 70) {
            strengthBar.style.background = '#ffc107';
            strengthText.textContent = 'Contraseña media';
            strengthText.style.color = '#ffc107';
        } else {
            strengthBar.style.background = '#28a745';
            strengthText.textContent = 'Contraseña fuerte';
            strengthText.style.color = '#28a745';
        }
    }
    
    // ========== FILTROS PARA PREPARATORIA ==========
    const estadoPrepa = document.getElementById('estado_prepa');
    const municipioPrepa = document.getElementById('municipio_prepa');
    const localidadPrepa = document.getElementById('localidad_prepa');
    const escuelaSelect = document.getElementById('escuela_procedencia');
    
    estadoPrepa.addEventListener('change', function() {
        const estado = this.value;
        municipioPrepa.innerHTML = '<option value="">Seleccionar municipio</option>';
        localidadPrepa.innerHTML = '<option value="">Seleccionar localidad</option>';
        escuelaSelect.innerHTML = '<option value="">Seleccione estado, municipio y localidad</option>';
        escuelaSelect.disabled = true;
        localidadPrepa.disabled = true;
        
        if (!estado) {
            municipioPrepa.disabled = true;
            return;
        }
        
        municipioPrepa.disabled = false;
        municipioPrepa.innerHTML = '<option value="">Cargando municipios...</option>';
        
        fetch(`{{ route("admin.estudiantes.get.municipios.prepa") }}?estado=${encodeURIComponent(estado)}`)
            .then(response => response.json())
            .then(data => {
                municipioPrepa.innerHTML = '<option value="">Seleccionar municipio</option>';
                data.forEach(municipio => {
                    const option = document.createElement('option');
                    option.value = municipio;
                    option.textContent = municipio;
                    municipioPrepa.appendChild(option);
                });
            })
            .catch(error => console.error('Error:', error));
    });
    
    municipioPrepa.addEventListener('change', function() {
        const estado = estadoPrepa.value;
        const municipio = this.value;
        localidadPrepa.innerHTML = '<option value="">Seleccionar localidad</option>';
        escuelaSelect.innerHTML = '<option value="">Seleccione estado, municipio y localidad</option>';
        escuelaSelect.disabled = true;
        
        if (!municipio) {
            localidadPrepa.disabled = true;
            return;
        }
        
        localidadPrepa.disabled = false;
        localidadPrepa.innerHTML = '<option value="">Cargando localidades...</option>';
        
        fetch(`{{ route("admin.estudiantes.get.localidades.prepa") }}?estado=${encodeURIComponent(estado)}&municipio=${encodeURIComponent(municipio)}`)
            .then(response => response.json())
            .then(data => {
                localidadPrepa.innerHTML = '<option value="">Todas las localidades</option>';
                data.forEach(localidad => {
                    const option = document.createElement('option');
                    option.value = localidad;
                    option.textContent = localidad;
                    localidadPrepa.appendChild(option);
                });
                cargarPreparatorias();
            })
            .catch(error => console.error('Error:', error));
    });
    
    localidadPrepa.addEventListener('change', cargarPreparatorias);
    
    function cargarPreparatorias() {
        const estado = estadoPrepa.value;
        const municipio = municipioPrepa.value;
        const localidad = localidadPrepa.value;
        
        if (!estado || !municipio) return;
        
        escuelaSelect.disabled = true;
        escuelaSelect.innerHTML = '<option value="">Cargando preparatorias...</option>';
        
        let url = `{{ route("admin.estudiantes.get.preparatorias") }}?estado=${encodeURIComponent(estado)}&municipio=${encodeURIComponent(municipio)}`;
        if (localidad && localidad !== 'Todas las localidades') {
            url += `&localidad=${encodeURIComponent(localidad)}`;
        }
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                escuelaSelect.disabled = false;
                escuelaSelect.innerHTML = '<option value="">Seleccionar preparatoria</option>';
                data.forEach(prepa => {
                    const option = document.createElement('option');
                    option.value = prepa.id;
                    option.textContent = `${prepa.centro_educativo} ${prepa.turno ? `(${prepa.turno})` : ''}`;
                    escuelaSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error:', error));
    }
    
    // ========== FILTROS PARA UNIVERSIDAD ==========
    const estadoUni = document.getElementById('estado_uni');
    const municipioUni = document.getElementById('municipio_uni');
    const localidadUni = document.getElementById('localidad_uni');
    const universidadSelect = document.getElementById('universidad_interes');
    
    estadoUni.addEventListener('change', function() {
        const estado = this.value;
        municipioUni.innerHTML = '<option value="">Seleccionar municipio</option>';
        localidadUni.innerHTML = '<option value="">Seleccionar localidad</option>';
        universidadSelect.innerHTML = '<option value="">Seleccione estado, municipio y localidad</option>';
        universidadSelect.disabled = true;
        localidadUni.disabled = true;
        
        if (!estado) {
            municipioUni.disabled = true;
            return;
        }
        
        municipioUni.disabled = false;
        municipioUni.innerHTML = '<option value="">Cargando municipios...</option>';
        
        fetch(`{{ route("admin.estudiantes.get.municipios.universidad") }}?estado=${encodeURIComponent(estado)}`)
            .then(response => response.json())
            .then(data => {
                municipioUni.innerHTML = '<option value="">Seleccionar municipio</option>';
                data.forEach(municipio => {
                    const option = document.createElement('option');
                    option.value = municipio;
                    option.textContent = municipio;
                    municipioUni.appendChild(option);
                });
            })
            .catch(error => console.error('Error:', error));
    });
    
    municipioUni.addEventListener('change', function() {
        const estado = estadoUni.value;
        const municipio = this.value;
        localidadUni.innerHTML = '<option value="">Seleccionar localidad</option>';
        universidadSelect.innerHTML = '<option value="">Seleccione estado, municipio y localidad</option>';
        universidadSelect.disabled = true;
        
        if (!municipio) {
            localidadUni.disabled = true;
            return;
        }
        
        localidadUni.disabled = false;
        localidadUni.innerHTML = '<option value="">Cargando localidades...</option>';
        
        fetch(`{{ route("admin.estudiantes.get.localidades.universidad") }}?estado=${encodeURIComponent(estado)}&municipio=${encodeURIComponent(municipio)}`)
            .then(response => response.json())
            .then(data => {
                localidadUni.innerHTML = '<option value="">Todas las localidades</option>';
                data.forEach(localidad => {
                    const option = document.createElement('option');
                    option.value = localidad;
                    option.textContent = localidad;
                    localidadUni.appendChild(option);
                });
                cargarUniversidades();
            })
            .catch(error => console.error('Error:', error));
    });
    
    localidadUni.addEventListener('change', cargarUniversidades);
    
    function cargarUniversidades() {
        const estado = estadoUni.value;
        const municipio = municipioUni.value;
        const localidad = localidadUni.value;
        
        if (!estado || !municipio) return;
        
        universidadSelect.disabled = true;
        universidadSelect.innerHTML = '<option value="">Cargando universidades...</option>';
        
        let url = `{{ route("admin.estudiantes.get.universidades") }}?estado=${encodeURIComponent(estado)}&municipio=${encodeURIComponent(municipio)}`;
        if (localidad && localidad !== 'Todas las localidades') {
            url += `&localidad=${encodeURIComponent(localidad)}`;
        }
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                universidadSelect.disabled = false;
                universidadSelect.innerHTML = '<option value="">Seleccionar universidad</option>';
                data.forEach(uni => {
                    const option = document.createElement('option');
                    option.value = uni.id;
                    option.textContent = uni.nombre_completo;
                    universidadSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error:', error));
    }
    
    // ========== VALIDACIÓN DE CUPÓN ==========
    const validarCuponBtn = document.getElementById('validarCuponBtn');
    const cuponSelect = document.getElementById('cupon_id');
    const cuponMensaje = document.getElementById('cuponMensaje');
    
    if (validarCuponBtn) {
        validarCuponBtn.addEventListener('click', function() {
            const cuponId = cuponSelect.value;
            if (!cuponId) {
                Swal.fire('Aviso', 'Seleccione un cupón para validar', 'info');
                return;
            }
            
            Swal.fire({
                title: 'Validando cupón...',
                text: 'Por favor espere',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(`{{ route("admin.estudiantes.validar.cupon") }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ cupon_id: cuponId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.valido) {
                    Swal.fire('Cupón válido', data.mensaje, 'success');
                    cuponMensaje.innerHTML = `<i class="fas fa-check-circle text-success me-1"></i>${data.mensaje}`;
                    cuponMensaje.style.color = '#28a745';
                } else {
                    Swal.fire('Cupón inválido', data.mensaje, 'error');
                    cuponMensaje.innerHTML = `<i class="fas fa-times-circle text-danger me-1"></i>${data.mensaje}`;
                    cuponMensaje.style.color = '#dc3545';
                }
            })
            .catch(error => {
                Swal.fire('Error', 'No se pudo validar el cupón', 'error');
            });
        });
    }
    
    // ========== ACTUALIZAR CONFIRMACIÓN ==========
    function actualizarConfirmacion() {
        const nombre = document.querySelector('input[name="nombre"]').value;
        const paterno = document.querySelector('input[name="paterno"]').value;
        const materno = document.querySelector('input[name="materno"]').value;
        const fechaNacimiento = document.getElementById('fecha_nacimiento').value;
        const sexo = document.querySelector('select[name="sexo"]').value;
        const telefono = document.getElementById('telefono').value;
        const telefonoCasa = document.getElementById('telefono_casa').value;
        const email = document.querySelector('input[name="email"]').value;
        const planActivo = document.getElementById('plan_activo').checked;
        const cuponSelect2 = document.getElementById('cupon_id');
        const cuponTexto = cuponSelect2.options[cuponSelect2.selectedIndex]?.text || 'Sin cupón';
        
        // Escuela
        const escuelaSelect2 = document.getElementById('escuela_procedencia');
        const escuelaTexto = escuelaSelect2.options[escuelaSelect2.selectedIndex]?.text || 'No seleccionada';
        
        // Universidad
        const universidadSelect2 = document.getElementById('universidad_interes');
        const universidadTexto = universidadSelect2.options[universidadSelect2.selectedIndex]?.text || 'No seleccionada';
        
        document.getElementById('confirm_nombre_completo').textContent = `${nombre} ${paterno} ${materno}`.trim();
        document.getElementById('confirm_fecha_nacimiento').textContent = fechaNacimiento || 'No especificada';
        
        const sexoTexto = { 'M': 'Masculino', 'F': 'Femenino' };
        document.getElementById('confirm_sexo').textContent = sexoTexto[sexo] || 'No especificado';
        document.getElementById('confirm_telefono').textContent = telefono || 'No especificado';
        document.getElementById('confirm_telefono_casa').textContent = telefonoCasa || 'No especificado';
        document.getElementById('confirm_email').textContent = email || 'No especificado';
        document.getElementById('confirm_escuela').textContent = escuelaTexto;
        document.getElementById('confirm_universidad').textContent = universidadTexto;
        document.getElementById('confirm_cupon').textContent = cuponTexto;
        document.getElementById('confirm_plan').textContent = planActivo ? 'Activo' : 'Inactivo';
    }
    
    // ========== VALIDAR PASOS ==========
    function validarPaso(step) {
        if (step === 1) {
            const nombre = document.querySelector('input[name="nombre"]').value;
            const paterno = document.querySelector('input[name="paterno"]').value;
            const fechaNacimiento = document.getElementById('fecha_nacimiento').value;
            const sexo = document.querySelector('select[name="sexo"]').value;
            const telefono = document.getElementById('telefono').value;
            
            if (!nombre.trim()) { Swal.fire('Error', 'El nombre es requerido', 'error'); return false; }
            if (!paterno.trim()) { Swal.fire('Error', 'El apellido paterno es requerido', 'error'); return false; }
            if (!fechaNacimiento) { Swal.fire('Error', 'La fecha de nacimiento es requerida', 'error'); return false; }
            if (!sexo) { Swal.fire('Error', 'Debe seleccionar el sexo', 'error'); return false; }
            if (!telefono) { Swal.fire('Error', 'El teléfono celular es requerido', 'error'); return false; }
            
            if (!validarFechaNacimiento(fechaNacimiento)) return false;
            if (!validarTelefono(telefono)) return false;
            
            return true;
        }
        
        if (step === 2) {
            const escuela = document.getElementById('escuela_procedencia').value;
            const universidad = document.getElementById('universidad_interes').value;
            
            if (!escuela) { Swal.fire('Error', 'Debe seleccionar una escuela de procedencia', 'error'); return false; }
            if (!universidad) { Swal.fire('Error', 'Debe seleccionar una universidad de interés', 'error'); return false; }
            
            return true;
        }
        
        if (step === 3) {
            const email = document.querySelector('input[name="email"]').value;
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;
            
            if (!email.trim()) { Swal.fire('Error', 'El correo electrónico es requerido', 'error'); return false; }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { Swal.fire('Error', 'Ingrese un correo electrónico válido', 'error'); return false; }
            if (!password) { Swal.fire('Error', 'La contraseña es requerida', 'error'); return false; }
            if (password.length < 6) { Swal.fire('Error', 'La contraseña debe tener al menos 6 caracteres', 'error'); return false; }
            if (password !== passwordConfirm) { Swal.fire('Error', 'Las contraseñas no coinciden', 'error'); return false; }
            
            return true;
        }
        
        return true;
    }
    
    // ========== NAVEGACIÓN ENTRE PASOS ==========
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
            actualizarConfirmacion();
        } else {
            prevBtn.style.display = 'block';
            nextBtn.style.display = 'block';
            submitBtn.style.display = 'none';
        }
    }
    
    // ========== EVENT LISTENERS ==========
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
    
    // Toggle contraseña
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // Generar contraseña
    document.querySelectorAll('.generate-password').forEach(button => {
        button.addEventListener('click', function() {
            const nuevaContraseña = generarContraseña(12);
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            
            passwordInput.value = nuevaContraseña;
            confirmInput.value = nuevaContraseña;
            actualizarFuerzaContraseña();
            
            Swal.fire({
                title: 'Contraseña generada',
                html: `<p>Contraseña generada automáticamente:</p>
                       <div class="alert alert-success mt-2">
                           <code style="font-size: 1.2rem; word-break: break-all;">${nuevaContraseña}</code>
                       </div>`,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        });
    });
    
    // Switch plan activo
    const planActivoCheckbox = document.getElementById('plan_activo');
    if (planActivoCheckbox) {
        planActivoCheckbox.addEventListener('change', function() {
            const label = document.getElementById('planActivoLabel');
            if (this.checked) {
                label.textContent = 'Activo';
            } else {
                label.textContent = 'Inactivo';
            }
        });
    }
    
    // Escuchar cambios para actualizar confirmación
    document.getElementById('password').addEventListener('input', actualizarFuerzaContraseña);
    document.querySelectorAll('input, select').forEach(field => {
        field.addEventListener('change', actualizarConfirmacion);
        field.addEventListener('input', actualizarConfirmacion);
    });
    
    // Validación final
    document.getElementById('estudianteForm').addEventListener('submit', function(e) {
        if (!validarPaso(1) || !validarPaso(2) || !validarPaso(3)) {
            e.preventDefault();
            return false;
        }
        
        const telefonoCasa = document.getElementById('telefono_casa').value;
        if (telefonoCasa && !validarTelefono(telefonoCasa)) {
            e.preventDefault();
            return false;
        }
        
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando...';
    });
    
    // Inicializar
    mostrarPaso(1);
</script>
@endpush