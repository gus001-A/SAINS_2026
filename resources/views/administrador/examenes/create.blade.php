@extends('administrador.layouts.master')

@section('title', 'Crear Examen - SAINS')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="display-5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                    <i class="fas fa-file-alt me-3"></i>Crear Nuevo Examen
                </h1>
                <p class="text-muted">Configure el examen y seleccione las preguntas del banco</p>
            </div>
            <a href="{{ route('admin.examenes.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>Volver al listado
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-modern p-4 p-lg-5">
            <form action="{{ route('admin.examenes.store') }}" method="POST" id="examenForm" novalidate>
                @csrf
                
                <!-- Stepper Indicator -->
                <div class="stepper-wrapper mb-5">
                    <div class="stepper-item active" data-step="1">
                        <div class="step-counter">1</div>
                        <div class="step-name">Configuración</div>
                    </div>
                    <div class="stepper-item" data-step="2">
                        <div class="step-counter">2</div>
                        <div class="step-name">Selección de Preguntas</div>
                    </div>
                    <div class="stepper-item" data-step="3">
                        <div class="step-counter">3</div>
                        <div class="step-name">Confirmación</div>
                    </div>
                </div>
                
                <!-- Step 1: Configuración General -->
                <div class="step-content" data-step="1">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));">
                            <i class="fas fa-sliders-h fa-2x" style="color: #4361ee;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Configuración General</h4>
                            <p class="text-muted small mb-0">Parámetros básicos del examen</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-tag text-primary me-1"></i> Tipo de Examen <span class="text-danger">*</span>
                            </label>
                            <select name="tipo_examen" class="form-select form-select-lg @error('tipo_examen') is-invalid @enderror" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="Materia" {{ old('tipo_examen') == 'Materia' ? 'selected' : '' }}>📚 Materia</option>
                                <option value="General del curso" {{ old('tipo_examen') == 'General del curso' ? 'selected' : '' }}>🎓 General del curso</option>
                                <option value="Simulación" {{ old('tipo_examen') == 'Simulación' ? 'selected' : '' }}>🎯 Simulación</option>
                            </select>
                            @error('tipo_examen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-question-circle text-primary me-1"></i> Número de Preguntas <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="numero_preguntas" id="numero_preguntas" 
                                   class="form-control form-control-lg @error('numero_preguntas') is-invalid @enderror" 
                                   value="{{ old('numero_preguntas', 10) }}" min="1" max="200" required>
                            @error('numero_preguntas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Mínimo 1, máximo 200 preguntas</small>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-clock text-primary me-1"></i> Tiempo (minutos) <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="tiempo" class="form-control form-control-lg @error('tiempo') is-invalid @enderror" 
                                   value="{{ old('tiempo', 60) }}" min="1" max="180" required>
                            @error('tiempo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Mínimo 1, máximo 180 minutos</small>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: Selección de Preguntas -->
                <div class="step-content" data-step="2" style="display: none;">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(247,37,133,0.1), rgba(114,9,183,0.1));">
                            <i class="fas fa-list-check fa-2x" style="color: #f72585;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Selección de Preguntas</h4>
                            <p class="text-muted small mb-0">Elija las preguntas que formarán parte del examen</p>
                        </div>
                    </div>
                    
                    <!-- Smart Selection Alert -->
                    <div class="smart-alert mb-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle p-2" style="background: rgba(102,126,234,0.1);">
                                    <i class="fas fa-magic fa-2x" style="color: #667eea;"></i>
                                </div>
                                <div>
                                    <strong class="d-block">Selección inteligente</strong>
                                    <small class="text-muted">Selecciona algunas preguntas y el sistema completará las restantes automáticamente</small>
                                </div>
                            </div>
                            <button type="button" id="btnCompletarPreguntas" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-random me-2"></i>Completar faltantes
                            </button>
                        </div>
                    </div>
                    
                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-filter text-primary me-1"></i> Filtrar por Área
                            </label>
                            <select id="filtroArea" class="form-select form-select-lg">
                                <option value="">🌐 Todas las áreas</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}">
                                        {{ $area->nombre }} 
                                        <span class="text-muted">({{ $area->preguntas->count() ?? 0 }} preguntas)</span>
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Tabla de preguntas -->
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="fas fa-database me-2 text-primary"></i>Banco de Preguntas
                                </h5>
                                <div class="d-flex gap-2">
                                    <span id="contadorSeleccionadas" class="badge bg-primary rounded-pill px-3 py-2">0 seleccionadas</span>
                                    <span class="badge bg-secondary rounded-pill px-3 py-2">Total: {{ $preguntas->count() }}</span>
                                    <button type="button" id="btnLimpiarSeleccion" class="btn btn-sm btn-outline-danger rounded-pill">
                                        <i class="fas fa-trash-alt me-1"></i>Limpiar
                                    </button>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 8px;" id="progressBarContainer">
                                <div id="progressBar" class="progress-bar" style="width: 0%; background: linear-gradient(90deg, #667eea, #764ba2);"></div>
                            </div>
                            <p class="text-muted small mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Seleccione exactamente <strong id="requeridasText">0</strong> preguntas
                            </p>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-hover mb-0">
                                    <thead style="background: #f8f9fa; position: sticky; top: 0; z-index: 10;">
                                        <tr>
                                            <th width="50">
                                                <input type="checkbox" id="seleccionarTodos" class="form-check-input">
                                            </th>
                                            <th width="60">ID</th>
                                            <th>Pregunta</th>
                                            <th width="200">Área</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaPreguntas">
                                        @if($preguntas->count() > 0)
                                            @foreach($preguntas as $pregunta)
                                            <tr data-area="{{ $pregunta->id_area ?? '' }}" data-id="{{ $pregunta->id }}" class="fila-pregunta">
                                                <td class="text-center">
                                                    <input type="checkbox" name="preguntas_seleccionadas[]" 
                                                           value="{{ $pregunta->id }}" 
                                                           class="form-check-input pregunta-checkbox">
                                                </td>
                                                <td class="text-muted">#{{ $pregunta->id }}</td>
                                                <td class="pregunta-texto">{{ $pregunta->pregunta ?? 'Sin pregunta' }}</td>
                                                <td>
                                                    <span class="badge" style="background: {{ $pregunta->area->color ?? '#667eea' }}20; color: {{ $pregunta->area->color ?? '#667eea' }}; padding: 6px 12px;">
                                                        <i class="fas fa-layer-group me-1"></i>
                                                        {{ $pregunta->area->nombre ?? 'Sin área' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center py-5">
                                                    <div class="text-muted">
                                                        <i class="fas fa-info-circle fa-3x mb-3 d-block"></i>
                                                        <h5>No hay preguntas disponibles</h5>
                                                        <p class="mb-0">Debes crear preguntas primero en el módulo de preguntas.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 3: Confirmación -->
                <div class="step-content" data-step="3" style="display: none;">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(255,152,0,0.1), rgba(255,87,34,0.1));">
                            <i class="fas fa-check-circle fa-2x" style="color: #ff9800;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Confirmar Creación</h4>
                            <p class="text-muted small mb-0">Revise los datos antes de guardar el examen</p>
                        </div>
                    </div>
                    
                    <div class="confirmation-card p-4 bg-light rounded-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-cog me-2"></i>Configuración</h6>
                                <div class="confirmation-item">
                                    <strong>Tipo de examen:</strong>
                                    <span id="confirm_tipo"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Número de preguntas:</strong>
                                    <span id="confirm_numero"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Tiempo límite:</strong>
                                    <span id="confirm_tiempo"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-list me-2"></i>Preguntas Seleccionadas</h6>
                                <div class="confirmation-item">
                                    <strong>Total seleccionadas:</strong>
                                    <span id="confirm_total_preguntas"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Por áreas:</strong>
                                    <span id="confirm_areas"></span>
                                </div>
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
                        <i class="fas fa-save me-2"></i> Guardar Examen
                    </button>
                    <a href="{{ route('admin.examenes.index') }}" class="btn btn-cancel px-4 py-3 rounded-pill">
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
    
    /* Smart Alert */
    .smart-alert {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 1px solid rgba(102,126,234,0.2);
        border-radius: 16px;
        padding: 1rem 1.5rem;
    }
    
    /* Table Styles */
    .table td {
        vertical-align: middle;
    }
    
    .form-check-input {
        cursor: pointer;
        width: 1.2rem;
        height: 1.2rem;
    }
    
    .fila-pregunta {
        transition: background-color 0.2s ease;
    }
    
    .fila-pregunta:hover {
        background-color: rgba(102, 126, 234, 0.05);
    }
    
    .pregunta-texto {
        max-width: 500px;
        white-space: normal;
        word-wrap: break-word;
    }
    
    .progress {
        border-radius: 4px;
    }
    
    .progress-bar {
        transition: width 0.3s ease;
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
    
    select:disabled {
        background-color: #f5f5f5;
        cursor: not-allowed;
    }
    
    /* Badge colors */
    .badge.bg-primary {
        background: linear-gradient(135deg, #667eea, #764ba2) !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let currentStep = 1;
    const totalSteps = 3;
    
    const numeroPreguntasInput = document.getElementById('numero_preguntas');
    const requeridasText = document.getElementById('requeridasText');
    const contadorSpan = document.getElementById('contadorSeleccionadas');
    const progressBar = document.getElementById('progressBar');
    const checkboxes = document.querySelectorAll('.pregunta-checkbox');
    const seleccionarTodosCheckbox = document.getElementById('seleccionarTodos');
    const filtroArea = document.getElementById('filtroArea');
    const btnLimpiarSeleccion = document.getElementById('btnLimpiarSeleccion');
    const btnCompletarPreguntas = document.getElementById('btnCompletarPreguntas');
    
    // Actualizar contador y barra de progreso
    function actualizarContador() {
        const seleccionadas = document.querySelectorAll('.pregunta-checkbox:checked').length;
        const requeridas = parseInt(numeroPreguntasInput?.value) || 0;
        
        if (contadorSpan) contadorSpan.textContent = `${seleccionadas} / ${requeridas} seleccionadas`;
        
        if (requeridas > 0) {
            const porcentaje = (seleccionadas / requeridas) * 100;
            if (progressBar) progressBar.style.width = `${Math.min(porcentaje, 100)}%`;
            
            if (seleccionadas === requeridas && requeridas > 0) {
                progressBar.style.background = 'linear-gradient(90deg, #28a745, #20c997)';
                contadorSpan.className = 'badge bg-success rounded-pill px-3 py-2';
            } else if (seleccionadas > requeridas) {
                progressBar.style.background = 'linear-gradient(90deg, #dc3545, #ff6b6b)';
                progressBar.style.width = '100%';
                contadorSpan.className = 'badge bg-danger rounded-pill px-3 py-2';
            } else if (seleccionadas > 0) {
                contadorSpan.className = 'badge bg-warning rounded-pill px-3 py-2';
            } else {
                contadorSpan.className = 'badge bg-primary rounded-pill px-3 py-2';
            }
        }
    }
    
    // Actualizar texto de requeridas
    if (numeroPreguntasInput) {
        numeroPreguntasInput.addEventListener('input', function() {
            requeridasText.textContent = this.value;
            actualizarContador();
            actualizarConfirmacion();
        });
    }
    
    // Evento para cada checkbox
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            actualizarContador();
            actualizarSeleccionarTodos();
            actualizarConfirmacion();
        });
    });
    
    function actualizarSeleccionarTodos() {
        if (seleccionarTodosCheckbox) {
            const visibles = document.querySelectorAll('.pregunta-checkbox:not([style*="display: none"])');
            const checkedVisibles = document.querySelectorAll('.pregunta-checkbox:not([style*="display: none"]):checked');
            seleccionarTodosCheckbox.checked = visibles.length > 0 && visibles.length === checkedVisibles.length;
            seleccionarTodosCheckbox.indeterminate = checkedVisibles.length > 0 && checkedVisibles.length < visibles.length;
        }
    }
    
    // Seleccionar/Deseleccionar todos
    if (seleccionarTodosCheckbox) {
        seleccionarTodosCheckbox.addEventListener('change', function() {
            const visibles = document.querySelectorAll('.pregunta-checkbox:not([style*="display: none"])');
            visibles.forEach(checkbox => {
                checkbox.checked = seleccionarTodosCheckbox.checked;
            });
            actualizarContador();
            actualizarConfirmacion();
        });
    }
    
    // Filtrar por área
    if (filtroArea) {
        filtroArea.addEventListener('change', function() {
            const areaId = this.value;
            const filas = document.querySelectorAll('#tablaPreguntas tr');
            
            filas.forEach(fila => {
                if (areaId === '' || fila.dataset.area == areaId) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
            
            if (seleccionarTodosCheckbox) {
                seleccionarTodosCheckbox.checked = false;
                seleccionarTodosCheckbox.indeterminate = false;
            }
            actualizarContador();
            actualizarSeleccionarTodos();
        });
    }
    
    // Limpiar selección
    if (btnLimpiarSeleccion) {
        btnLimpiarSeleccion.addEventListener('click', function() {
            document.querySelectorAll('.pregunta-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            if (seleccionarTodosCheckbox) {
                seleccionarTodosCheckbox.checked = false;
                seleccionarTodosCheckbox.indeterminate = false;
            }
            actualizarContador();
            actualizarConfirmacion();
        });
    }
    
    // Completar preguntas faltantes automáticamente
    if (btnCompletarPreguntas) {
        btnCompletarPreguntas.addEventListener('click', function() {
            const requeridas = parseInt(numeroPreguntasInput?.value) || 0;
            const seleccionadasActuales = document.querySelectorAll('.pregunta-checkbox:checked').length;
            const faltantes = requeridas - seleccionadasActuales;
            
            if (requeridas <= 0) {
                Swal.fire({ title: 'Error', text: 'Primero ingrese el número de preguntas deseadas', icon: 'error', confirmButtonColor: '#667eea' });
                return;
            }
            
            if (seleccionadasActuales > requeridas) {
                Swal.fire({ title: 'Demasiadas preguntas', text: `Has seleccionado ${seleccionadasActuales} preguntas, pero el examen requiere ${requeridas}. Limpia la selección y vuelve a intentar.`, icon: 'warning', confirmButtonColor: '#667eea' });
                return;
            }
            
            if (faltantes === 0) {
                Swal.fire({ title: 'Completado', text: 'Ya tienes el número exacto de preguntas seleccionadas', icon: 'success', timer: 1500, showConfirmButton: false });
                return;
            }
            
            const noSeleccionadas = Array.from(document.querySelectorAll('.pregunta-checkbox:not(:checked)'));
            let disponibles = noSeleccionadas;
            
            if (filtroArea.value) {
                const areaId = filtroArea.value;
                disponibles = noSeleccionadas.filter(cb => {
                    const fila = cb.closest('tr');
                    return fila && fila.dataset.area == areaId;
                });
            }
            
            if (disponibles.length < faltantes) {
                Swal.fire({ title: 'Error', text: `No hay suficientes preguntas disponibles. Faltan ${faltantes} pero solo hay ${disponibles.length} disponibles.`, icon: 'error', confirmButtonColor: '#667eea' });
                return;
            }
            
            const shuffled = [...disponibles];
            for (let i = shuffled.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
            }
            
            for (let i = 0; i < faltantes; i++) {
                if (shuffled[i]) shuffled[i].checked = true;
            }
            
            actualizarContador();
            actualizarSeleccionarTodos();
            actualizarConfirmacion();
            
            Swal.fire({ title: 'Preguntas completadas', html: `Se han seleccionado <strong>${faltantes}</strong> preguntas adicionales.<br>Total: <strong>${requeridas}</strong>/${requeridas}`, icon: 'success', timer: 2000, showConfirmButton: false });
        });
    }
    
    // Actualizar confirmación
    function actualizarConfirmacion() {
        const tipoExamen = document.querySelector('select[name="tipo_examen"]');
        const tiempo = document.querySelector('input[name="tiempo"]');
        const seleccionadas = document.querySelectorAll('.pregunta-checkbox:checked').length;
        const requeridas = parseInt(numeroPreguntasInput?.value) || 0;
        
        const tipoText = tipoExamen.options[tipoExamen.selectedIndex]?.text || 'No seleccionado';
        document.getElementById('confirm_tipo').textContent = tipoText;
        document.getElementById('confirm_numero').textContent = `${requeridas} preguntas`;
        document.getElementById('confirm_tiempo').textContent = `${tiempo?.value || 0} minutos`;
        document.getElementById('confirm_total_preguntas').textContent = `${seleccionadas} / ${requeridas} seleccionadas`;
        
        // Contar por áreas
        const areasSeleccionadas = {};
        document.querySelectorAll('.pregunta-checkbox:checked').forEach(cb => {
            const fila = cb.closest('tr');
            const areaNombre = fila.querySelector('td:last-child .badge')?.textContent?.trim() || 'Sin área';
            areasSeleccionadas[areaNombre] = (areasSeleccionadas[areaNombre] || 0) + 1;
        });
        
        const areasText = Object.entries(areasSeleccionadas).map(([area, count]) => `${area}: ${count}`).join(', ') || 'Ninguna';
        document.getElementById('confirm_areas').textContent = areasText;
    }
    
    // Validar pasos
    function validarPaso(step) {
        if (step === 1) {
            const tipoExamen = document.querySelector('select[name="tipo_examen"]').value;
            const numeroPreguntas = numeroPreguntasInput?.value;
            const tiempo = document.querySelector('input[name="tiempo"]').value;
            
            if (!tipoExamen) { Swal.fire('Error', 'Debe seleccionar un tipo de examen', 'error'); return false; }
            if (!numeroPreguntas || numeroPreguntas < 1) { Swal.fire('Error', 'El número de preguntas debe ser mayor a 0', 'error'); return false; }
            if (numeroPreguntas > 200) { Swal.fire('Error', 'El número de preguntas no puede exceder 200', 'error'); return false; }
            if (!tiempo || tiempo < 1) { Swal.fire('Error', 'El tiempo debe ser mayor a 0', 'error'); return false; }
            if (tiempo > 180) { Swal.fire('Error', 'El tiempo no puede exceder 180 minutos', 'error'); return false; }
            
            return true;
        }
        
        if (step === 2) {
            const seleccionadas = document.querySelectorAll('.pregunta-checkbox:checked').length;
            const requeridas = parseInt(numeroPreguntasInput?.value) || 0;
            
            if (seleccionadas === 0) { Swal.fire('Error', 'Debe seleccionar al menos una pregunta', 'error'); return false; }
            if (seleccionadas !== requeridas) { Swal.fire('Error', `Debe seleccionar exactamente ${requeridas} preguntas. Actual: ${seleccionadas}`, 'error'); return false; }
            
            return true;
        }
        
        return true;
    }
    
    // Navegación
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
    
    // Escuchar cambios para confirmación
    document.querySelector('select[name="tipo_examen"]')?.addEventListener('change', actualizarConfirmacion);
    document.querySelector('input[name="tiempo"]')?.addEventListener('input', actualizarConfirmacion);
    
    // Envío del formulario
    document.getElementById('examenForm').addEventListener('submit', function(e) {
        if (!validarPaso(1) || !validarPaso(2)) {
            e.preventDefault();
            return false;
        }
        
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando examen...';
    });
    
    // Inicializar
    mostrarPaso(1);
    actualizarContador();
    requeridasText.textContent = numeroPreguntasInput?.value || 0;
</script>
@endpush