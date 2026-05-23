@extends('administrador.layouts.master')

@section('title', 'Nueva Interacción - Call Center SAINS')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="display-5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                    <i class="fas fa-phone-alt me-3"></i>Nueva Interacción
                </h1>
                <p class="text-muted">Registre un nuevo contacto con un estudiante (solo estudiantes sin plan activo)</p>
            </div>
            <a href="{{ route('admin.callcenter.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>Volver al listado
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-modern p-4 p-lg-5">
            <form action="{{ route('admin.callcenter.store') }}" method="POST" id="interaccionForm" novalidate>
                @csrf
                
                <!-- Stepper Indicator -->
                <div class="stepper-wrapper mb-5">
                    <div class="stepper-item active" data-step="1">
                        <div class="step-counter">1</div>
                        <div class="step-name">Datos del Contacto</div>
                    </div>
                    <div class="stepper-item" data-step="2">
                        <div class="step-counter">2</div>
                        <div class="step-name">Detalles de la Interacción</div>
                    </div>
                    <div class="stepper-item" data-step="3">
                        <div class="step-counter">3</div>
                        <div class="step-name">Seguimiento</div>
                    </div>
                </div>
                
                <!-- Step 1: Datos del Contacto -->
                <div class="step-content" data-step="1">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));">
                            <i class="fas fa-user-graduate fa-2x" style="color: #4361ee;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Datos del Contacto</h4>
                            <p class="text-muted small mb-0">Información básica de la interacción</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-graduation-cap text-primary me-1"></i> Estudiante <span class="text-danger">*</span>
                            </label>
                            <select name="id_estudiante" id="id_estudiante" class="form-select form-select-lg @error('id_estudiante') is-invalid @enderror" required>
                                <option value="">Seleccionar estudiante</option>
                                @foreach($estudiantes as $estudiante)
                                    <option value="{{ $estudiante->id }}" {{ old('id_estudiante') == $estudiante->id ? 'selected' : '' }}>
                                        {{ $estudiante->estudiante->nombre_completo ?? $estudiante->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_estudiante')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Solo se muestran estudiantes sin plan activo</small>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar-alt text-primary me-1"></i> Fecha Contacto <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="fecha_contacto" id="fecha_contacto" 
                                   class="form-control form-control-lg @error('fecha_contacto') is-invalid @enderror" 
                                   value="{{ old('fecha_contacto', date('Y-m-d')) }}" required>
                            @error('fecha_contacto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-clock text-primary me-1"></i> Hora Contacto <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="hora_contacto_fija" 
                                   class="form-control form-control-lg bg-light" 
                                   value="{{ date('H:i:s') }}" readonly disabled>
                            <input type="hidden" name="hora_contacto" id="hora_contacto" value="{{ date('H:i:s') }}">
                            <small class="text-muted">Hora actual automática (no editable)</small>
                            @error('hora_contacto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-phone-alt text-primary me-1"></i> Tipo de Contacto <span class="text-danger">*</span>
                            </label>
                            <select name="tipo_contacto" id="tipo_contacto" class="form-select form-select-lg @error('tipo_contacto') is-invalid @enderror" required>
                                <option value="">Seleccionar tipo</option>
                                @foreach($tiposContacto as $key => $label)
                                    <option value="{{ $key }}" {{ old('tipo_contacto') == $key ? 'selected' : '' }}>
                                        @if($key == 'llamada')
                                            <i class="fas fa-phone me-2"></i>
                                        @elseif($key == 'email')
                                            <i class="fas fa-envelope me-2"></i>
                                        @elseif($key == 'whatsapp')
                                            <i class="fab fa-whatsapp me-2"></i>
                                        @endif
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_contacto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-chart-line text-primary me-1"></i> Estado de Seguimiento <span class="text-danger">*</span>
                            </label>
                            <select name="estado_seguimiento" id="estado_seguimiento" class="form-select form-select-lg @error('estado_seguimiento') is-invalid @enderror" required>
                                <option value="">Seleccionar estado</option>
                                @foreach($estadosSeguimiento as $key => $label)
                                    <option value="{{ $key }}" {{ old('estado_seguimiento') == $key ? 'selected' : '' }}>
                                        @if($key == 'pendiente')
                                            <i class="fas fa-clock me-2"></i>
                                        @elseif($key == 'en_proceso')
                                            <i class="fas fa-sync-alt me-2"></i>
                                        @elseif($key == 'finalizado')
                                            <i class="fas fa-check-circle me-2"></i>
                                        @endif
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('estado_seguimiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Información adicional del estudiante (card informativa) -->
                        <div class="col-12">
                            <div class="info-card" id="infoEstudiante" style="display: none;">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle p-2 me-3" style="background: rgba(255,152,0,0.1);">
                                        <i class="fas fa-info-circle fa-2x" style="color: #ff9800;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <strong>Información del Estudiante</strong><br>
                                        <span id="infoEstudianteTexto"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: Detalles de la Interacción -->
                <div class="step-content" data-step="2" style="display: none;">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(247,37,133,0.1), rgba(114,9,183,0.1));">
                            <i class="fas fa-comment-dots fa-2x" style="color: #f72585;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Detalles de la Conversación</h4>
                            <p class="text-muted small mb-0">Registre el motivo, notas y resultados del contacto</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-question-circle text-primary me-1"></i> Motivo de Contacto <span class="text-danger">*</span>
                            </label>
                            <textarea name="motivo_contacto" id="motivo_contacto" rows="3" 
                                      class="form-control @error('motivo_contacto') is-invalid @enderror" 
                                      placeholder="Ej: Consulta sobre planes de estudio, seguimiento académico, información de pagos..." required>{{ old('motivo_contacto') }}</textarea>
                            @error('motivo_contacto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Describa el motivo principal del contacto</small>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-sticky-note text-primary me-1"></i> Nota (Detalles de la conversación)
                            </label>
                            <textarea name="nota" id="nota" rows="4" 
                                      class="form-control @error('nota') is-invalid @enderror" 
                                      placeholder="Registre los detalles importantes de la conversación, dudas del estudiante, acuerdos, etc.">{{ old('nota') }}</textarea>
                            @error('nota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Información detallada de lo tratado durante el contacto</small>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-clipboard-list text-primary me-1"></i> Resultado de la Interacción
                            </label>
                            <textarea name="resultado" id="resultado" rows="3" 
                                      class="form-control @error('resultado') is-invalid @enderror" 
                                      placeholder="Ej: Estudiante interesado en plan básico, solicita información adicional, se envía correo con detalles...">{{ old('resultado') }}</textarea>
                            @error('resultado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Resultado o cierre de la interacción</small>
                        </div>
                    </div>
                </div>
                
                <!-- Step 3: Seguimiento -->
                <div class="step-content" data-step="3" style="display: none;">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(76,175,80,0.1), rgba(76,175,80,0.1));">
                            <i class="fas fa-calendar-week fa-2x" style="color: #4caf50;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Programar Próximo Contacto</h4>
                            <p class="text-muted small mb-0">Si es necesario, programe una fecha para dar seguimiento</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar-plus text-primary me-1"></i> Próximo Contacto
                            </label>
                            <input type="datetime-local" name="proximo_contacto" id="proximo_contacto" 
                                   class="form-control form-control-lg @error('proximo_contacto') is-invalid @enderror" 
                                   value="{{ old('proximo_contacto') }}">
                            @error('proximo_contacto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Opcional - Fecha y hora para el próximo seguimiento</small>
                        </div>
                    </div>
                    
                    <!-- Resumen de la interacción -->
                    <div class="confirmation-card p-4 mt-4">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-file-alt me-2"></i>Resumen de la Interacción
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="confirmation-item">
                                    <strong>Estudiante:</strong>
                                    <span id="resumen_estudiante">-</span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Tipo de contacto:</strong>
                                    <span id="resumen_tipo">-</span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Estado:</strong>
                                    <span id="resumen_estado">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="confirmation-item">
                                    <strong>Fecha/Hora:</strong>
                                    <span id="resumen_fecha">-</span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Próximo contacto:</strong>
                                    <span id="resumen_proximo">No programado</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="confirmation-item">
                                <strong>Motivo:</strong>
                                <span id="resumen_motivo">-</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Botones de navegación -->
                <div class="d-flex justify-content-between gap-3 mt-5 pt-4 border-top">
                    <button type="button" class="btn btn-secondary px-5 py-3 rounded-pill" id="prevBtn" style="display: none;">
                        <i class="fas fa-arrow-left me-2"></i> Anterior
                    </button>
                    <button type="button" class="btn btn-primary px-5 py-3 rounded-pill" id="nextBtn">
                        Siguiente <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                    <button type="submit" class="btn btn-save px-5 py-3 rounded-pill" id="submitBtn" style="display: none;">
                        <i class="fas fa-save me-2"></i> Guardar Interacción
                    </button>
                    <a href="{{ route('admin.callcenter.index') }}" class="btn btn-cancel px-4 py-3 rounded-pill">
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
    
    .form-control-lg, .form-select-lg {
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
    
    textarea.form-control {
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    
    textarea.form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
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
    
    /* Info Card */
    .info-card {
        background: linear-gradient(135deg, #fff8e1 0%, #fff3e0 100%);
        border: 1px solid rgba(255,152,0,0.2);
        border-radius: 16px;
        padding: 1rem 1.5rem;
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
    
    /* Buttons */
    .btn-save {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        color: white;
    }
    
    .btn-cancel {
        background: #6c757d;
        border: none;
        color: white;
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
        font-weight: 600;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }
    
    .btn-secondary {
        background: #6c757d;
        border: none;
        font-weight: 600;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
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
        color: #dc3545;
    }
    
    input:read-only, input:disabled {
        background-color: #f5f5f5;
        cursor: not-allowed;
    }
    
    /* Dark Mode */
    body.dark-mode .card-modern {
        background-color: #1a1a2e;
    }
    
    body.dark-mode .form-control-lg,
    body.dark-mode .form-select-lg,
    body.dark-mode textarea.form-control {
        background-color: #0f0f1a;
        border-color: rgba(255, 255, 255, 0.1);
        color: #e0e0e0;
    }
    
    body.dark-mode .form-control-lg:focus,
    body.dark-mode .form-select-lg:focus,
    body.dark-mode textarea.form-control:focus {
        border-color: #667eea;
    }
    
    body.dark-mode .info-card {
        background: linear-gradient(135deg, #1a237e 0%, #0d1b3e 100%);
        border-color: rgba(255,152,0,0.3);
        color: #e0e0e0;
    }
    
    body.dark-mode .confirmation-card {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        border-color: rgba(102,126,234,0.3);
        color: #e0e0e0;
    }
    
    body.dark-mode .border-top,
    body.dark-mode .border-bottom {
        border-top-color: rgba(255, 255, 255, 0.1) !important;
        border-bottom-color: rgba(255, 255, 255, 0.1) !important;
    }
    
    body.dark-mode .text-muted {
        color: #a0a0a0 !important;
    }
    
    body.dark-mode input:read-only, 
    body.dark-mode input:disabled {
        background-color: #0a0a15;
        color: #a0a0a0;
    }
    
    body.dark-mode .stepper-wrapper::before {
        background: #333;
    }
    
    body.dark-mode .step-counter {
        background: #1a1a2e;
        border-color: #444;
        color: #e0e0e0;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let currentStep = 1;
    const totalSteps = 3;
    
    // Elementos del DOM
    const estudianteSelect = document.getElementById('id_estudiante');
    const tipoContactoSelect = document.getElementById('tipo_contacto');
    const estadoSelect = document.getElementById('estado_seguimiento');
    const fechaContactoInput = document.getElementById('fecha_contacto');
    const proximoContactoInput = document.getElementById('proximo_contacto');
    const motivoTextarea = document.getElementById('motivo_contacto');
    
    // Función para mostrar información del estudiante seleccionado
    function mostrarInfoEstudiante() {
        const select = estudianteSelect;
        const selectedOption = select.options[select.selectedIndex];
        const infoDiv = document.getElementById('infoEstudiante');
        const infoTexto = document.getElementById('infoEstudianteTexto');
        
        if (selectedOption && selectedOption.value) {
            const textoCompleto = selectedOption.textContent;
            infoTexto.innerHTML = `
                <strong>${textoCompleto.split('(')[0].trim()}</strong><br>
                <span style="color: #ff9800;">⚠️ Sin Plan Activo</span>
                <br><small>Este estudiante aún no tiene un plan activo</small>
            `;
            infoDiv.style.display = 'block';
        } else {
            infoDiv.style.display = 'none';
        }
    }
    
    // Actualizar la hora actual cada segundo
    function actualizarHoraActual() {
        const ahora = new Date();
        const horas = String(ahora.getHours()).padStart(2, '0');
        const minutos = String(ahora.getMinutes()).padStart(2, '0');
        const segundos = String(ahora.getSeconds()).padStart(2, '0');
        const horaActual = `${horas}:${minutos}:${segundos}`;
        
        const horaField = document.getElementById('hora_contacto_fija');
        const hiddenField = document.getElementById('hora_contacto');
        
        if (horaField) horaField.value = horaActual;
        if (hiddenField) hiddenField.value = horaActual;
    }
    
    // Actualizar resumen en el paso 3
    function actualizarResumen() {
        if (document.getElementById('resumen_estudiante')) {
            const selectedOption = estudianteSelect?.options[estudianteSelect.selectedIndex];
            document.getElementById('resumen_estudiante').textContent = selectedOption?.textContent.split('(')[0].trim() || 'No seleccionado';
            
            const tipoOption = tipoContactoSelect?.options[tipoContactoSelect.selectedIndex];
            document.getElementById('resumen_tipo').textContent = tipoOption?.textContent || 'No seleccionado';
            
            const estadoOption = estadoSelect?.options[estadoSelect.selectedIndex];
            document.getElementById('resumen_estado').textContent = estadoOption?.textContent || 'No seleccionado';
            
            const fecha = fechaContactoInput?.value || '';
            const hora = document.getElementById('hora_contacto_fija')?.value || '';
            document.getElementById('resumen_fecha').textContent = fecha ? `${fecha} ${hora}` : 'No seleccionada';
            
            const proximo = proximoContactoInput?.value;
            document.getElementById('resumen_proximo').textContent = proximo ? new Date(proximo).toLocaleString() : 'No programado';
            
            document.getElementById('resumen_motivo').textContent = motivoTextarea?.value || 'No especificado';
        }
    }
    
    // Validar pasos
    function validarPaso(step) {
        if (step === 1) {
            if (!estudianteSelect?.value) {
                Swal.fire('Error', 'Debe seleccionar un estudiante', 'error');
                return false;
            }
            if (!fechaContactoInput?.value) {
                Swal.fire('Error', 'Debe seleccionar una fecha de contacto', 'error');
                return false;
            }
            if (!tipoContactoSelect?.value) {
                Swal.fire('Error', 'Debe seleccionar un tipo de contacto', 'error');
                return false;
            }
            if (!estadoSelect?.value) {
                Swal.fire('Error', 'Debe seleccionar un estado de seguimiento', 'error');
                return false;
            }
            return true;
        }
        
        if (step === 2) {
            if (!motivoTextarea?.value.trim()) {
                Swal.fire('Error', 'Debe ingresar el motivo de contacto', 'error');
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
        const targetContent = document.querySelector(`.step-content[data-step="${step}"]`);
        if (targetContent) targetContent.style.display = 'block';
        
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
            if (prevBtn) prevBtn.style.display = 'none';
            if (nextBtn) nextBtn.style.display = 'block';
            if (submitBtn) submitBtn.style.display = 'none';
        } else if (step === totalSteps) {
            if (prevBtn) prevBtn.style.display = 'block';
            if (nextBtn) nextBtn.style.display = 'none';
            if (submitBtn) submitBtn.style.display = 'block';
            actualizarResumen();
        } else {
            if (prevBtn) prevBtn.style.display = 'block';
            if (nextBtn) nextBtn.style.display = 'block';
            if (submitBtn) submitBtn.style.display = 'none';
        }
    }
    
    // Event Listeners
    if (estudianteSelect) {
        estudianteSelect.addEventListener('change', () => {
            mostrarInfoEstudiante();
            actualizarResumen();
        });
    }
    
    if (tipoContactoSelect) tipoContactoSelect.addEventListener('change', actualizarResumen);
    if (estadoSelect) estadoSelect.addEventListener('change', actualizarResumen);
    if (fechaContactoInput) fechaContactoInput.addEventListener('change', actualizarResumen);
    if (proximoContactoInput) proximoContactoInput.addEventListener('change', actualizarResumen);
    if (motivoTextarea) motivoTextarea.addEventListener('input', actualizarResumen);
    
    // Botones de navegación
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            if (validarPaso(currentStep)) {
                currentStep++;
                mostrarPaso(currentStep);
            }
        });
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            if (currentStep > 1) {
                currentStep--;
                mostrarPaso(currentStep);
            }
        });
    }
    
    // Validar próximo contacto (no puede ser anterior a la fecha actual)
    if (proximoContactoInput) {
        proximoContactoInput.addEventListener('change', function() {
            const proximoContacto = new Date(this.value);
            const fechaActual = new Date();
            
            if (proximoContacto < fechaActual) {
                Swal.fire({
                    title: 'Advertencia',
                    text: 'La fecha de próximo contacto no puede ser anterior a la fecha actual',
                    icon: 'warning',
                    confirmButtonColor: '#667eea'
                });
                this.value = '';
            }
        });
    }
    
    // Confirmar cambios antes de salir si hay campos modificados
    let formModificado = false;
    const formCampos = document.querySelectorAll('#interaccionForm input, #interaccionForm select, #interaccionForm textarea');
    formCampos.forEach(campo => {
        if (campo.type !== 'hidden' && !campo.disabled && campo.name !== '_token') {
            campo.addEventListener('change', () => { formModificado = true; });
            campo.addEventListener('input', () => { formModificado = true; });
        }
    });
    
    window.addEventListener('beforeunload', function(e) {
        if (formModificado) {
            e.preventDefault();
            e.returnValue = 'Hay cambios sin guardar. ¿Estás seguro de que quieres salir?';
            return e.returnValue;
        }
    });
    
    document.getElementById('interaccionForm')?.addEventListener('submit', function() {
        formModificado = false;
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando interacción...';
    });
    
    // Actualizar hora cada segundo
    let intervaloHora = setInterval(actualizarHoraActual, 1000);
    
    // Limpiar intervalo al enviar
    document.getElementById('interaccionForm')?.addEventListener('submit', function() {
        clearInterval(intervaloHora);
    });
    
    // Inicializar
    mostrarPaso(1);
    mostrarInfoEstudiante();
</script>
@endpush