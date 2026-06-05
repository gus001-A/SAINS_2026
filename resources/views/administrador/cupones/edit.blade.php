@extends('administrador.layouts.master')

@section('title', 'Editar Cupón - SAINS')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="display-5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                    <i class="fas fa-edit me-3"></i>Editar Cupón
                </h1>
                <p class="text-muted">Modifique la información del cupón de descuento</p>
            </div>
            <a href="{{ route('admin.cupones.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>Volver al listado
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-modern p-4 p-lg-5">
            <form action="{{ route('admin.cupones.update', $cupon->id) }}" method="POST" id="cuponForm" novalidate>
                @csrf
                @method('PUT')
                
                <!-- Stepper Indicator -->
                <div class="stepper-wrapper mb-5">
                    <div class="stepper-item active" data-step="1">
                        <div class="step-counter">1</div>
                        <div class="step-name">Información General</div>
                    </div>
                    <div class="stepper-item" data-step="2">
                        <div class="step-counter">2</div>
                        <div class="step-name">Detalles del Descuento</div>
                    </div>
                    <div class="stepper-item" data-step="3">
                        <div class="step-counter">3</div>
                        <div class="step-name">Confirmación</div>
                    </div>
                </div>
                
                <!-- Step 1: Información General -->
                <div class="step-content" data-step="1">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));">
                            <i class="fas fa-ticket-alt fa-2x" style="color: #4361ee;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Información General</h4>
                            <p class="text-muted small mb-0">Configuración básica del cupón</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-barcode text-primary me-1"></i> Código del Cupón <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-ticket-alt text-primary"></i></span>
                                <input type="text" name="codigo" id="codigo" class="form-control @error('codigo') is-invalid @enderror" 
                                       value="{{ old('codigo', $cupon->codigo) }}" placeholder="Código del cupón" required style="font-family: monospace; font-size: 1.1rem; letter-spacing: 1px;">
                                <button type="button" class="btn btn-outline-secondary" id="generarCodigoAleatorio" title="Generar código aleatorio">
                                    <i class="fas fa-dice-d6"></i> Generar
                                </button>
                            </div>
                            @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Código único del cupón</small>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-circle-info text-primary me-1"></i> Estatus <span class="text-danger">*</span>
                            </label>
                            <select name="estatus" class="form-select form-select-lg @error('estatus') is-invalid @enderror" required>
                                <option value="">Seleccionar estatus</option>
                                <option value="activo" {{ old('estatus', $cupon->estatus) == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estatus', $cupon->estatus) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                <option value="expirado" {{ old('estatus', $cupon->estatus) == 'expirado' ? 'selected' : '' }}>Expirado</option>
                            </select>
                            @error('estatus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar-alt text-primary me-1"></i> Fecha de Expiración
                            </label>
                            <input type="date" name="fecha_expiracion" id="fecha_expiracion" 
                                   class="form-control form-control-lg @error('fecha_expiracion') is-invalid @enderror" 
                                   value="{{ old('fecha_expiracion', $cupon->fecha_expiracion ? \Carbon\Carbon::parse($cupon->fecha_expiracion)->format('Y-m-d') : '') }}"
                                   min="{{ date('Y-m-d') }}">
                            @error('fecha_expiracion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Dejar en blanco si no expira</small>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-check-circle text-primary me-1"></i> ¿Usado?
                            </label>
                            <div class="form-check form-switch mt-2">
                                <input type="checkbox" name="usado_checkbox" class="form-check-input" id="usado" style="width: 50px; height: 25px;" 
                                       value="1" {{ old('usado', $cupon->usado) ? 'checked' : '' }}>
                                <label class="form-check-label ms-2 fw-semibold" for="usado" id="usadoLabel">
                                    {{ $cupon->usado ? 'Sí, usado' : 'No, disponible' }}
                                </label>
                            </div>
                            <input type="hidden" name="usado" value="{{ old('usado', $cupon->usado) ? 1 : 0 }}" id="usadoHidden">
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Indica si el cupón ya fue utilizado</small>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user-plus text-primary me-1"></i> Generado por
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-user-shield text-primary"></i></span>
                                <select name="usuario_genero" class="form-select @error('usuario_genero') is-invalid @enderror">
                                    <option value="">Seleccionar usuario (opcional)</option>
                                    @foreach($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}" {{ old('usuario_genero', $cupon->usuario_genero) == $usuario->id ? 'selected' : '' }}>{{ $usuario->correo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('usuario_genero')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar-alt text-primary me-1"></i> Fecha de generación
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-calendar text-primary"></i></span>
                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($cupon->fecha_genero)->format('d/m/Y') }}" disabled>
                            </div>
                        </div>
                        
                        @if($cupon->fecha_uso)
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-clock text-primary me-1"></i> Fecha de uso
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-calendar-check text-primary"></i></span>
                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($cupon->fecha_uso)->format('d/m/Y H:i') }}" disabled>
                            </div>
                        </div>
                        @endif
                        
                        @if($cupon->usuario_uso)
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user-check text-primary me-1"></i> Usado por
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-user-graduate text-primary"></i></span>
                                <input type="text" class="form-control" value="{{ $cupon->usuarioUso->correo ?? 'N/A' }}" disabled>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Step 2: Detalles del Descuento -->
                <div class="step-content" data-step="2" style="display: none;">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(247,37,133,0.1), rgba(114,9,183,0.1));">
                            <i class="fas fa-percent fa-2x" style="color: #f72585;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Detalles del Descuento</h4>
                            <p class="text-muted small mb-0">Configure el tipo y monto del descuento</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-percent text-primary me-1"></i> Tipo de Descuento <span class="text-danger">*</span>
                            </label>
                            <select name="tipo_descuento" id="tipo_descuento" class="form-select form-select-lg @error('tipo_descuento') is-invalid @enderror" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="porcentaje" {{ old('tipo_descuento', $cupon->tipo_descuento) == 'porcentaje' ? 'selected' : '' }}>Porcentaje (%)</option>
                                <option value="cantidad_fija" {{ old('tipo_descuento', $cupon->tipo_descuento) == 'cantidad_fija' ? 'selected' : '' }}>Cantidad fija ($)</option>
                            </select>
                            @error('tipo_descuento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-dollar-sign text-primary me-1"></i> Valor del Descuento <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent" id="valorSimbolo">
                                    {{ $cupon->tipo_descuento == 'porcentaje' ? '%' : '$' }}
                                </span>
                                <input type="number" step="{{ $cupon->tipo_descuento == 'porcentaje' ? '1' : '0.01' }}" 
                                       name="valor_descuento" id="valor_descuento" 
                                       class="form-control @error('valor_descuento') is-invalid @enderror" 
                                       value="{{ old('valor_descuento', $cupon->valor_descuento) }}" 
                                       placeholder="{{ $cupon->tipo_descuento == 'porcentaje' ? 'Ejemplo: 20' : 'Ejemplo: 50.00' }}" 
                                       {{ $cupon->tipo_descuento == 'porcentaje' ? 'max="100"' : '' }} required>
                            </div>
                            @error('valor_descuento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" id="valorAyuda">
                                <i class="fas fa-info-circle me-1"></i>{{ $cupon->tipo_descuento == 'porcentaje' ? 'Ingrese el porcentaje de descuento (máximo 100%)' : 'Ingrese el monto fijo del descuento' }}
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Step 3: Confirmación -->
                <div class="step-content" data-step="3" style="display: none;">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(76,175,80,0.1), rgba(139,195,74,0.1));">
                            <i class="fas fa-check-circle fa-2x" style="color: #4caf50;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Confirmar Cambios</h4>
                            <p class="text-muted small mb-0">Revise los datos antes de actualizar</p>
                        </div>
                    </div>
                    
                    <div class="confirmation-card p-4 bg-light rounded-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-ticket-alt me-2"></i>Información General</h6>
                                <div class="confirmation-item">
                                    <strong>Código:</strong>
                                    <span id="confirm_codigo" style="font-family: monospace;"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Estatus:</strong>
                                    <span id="confirm_estatus"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Fecha Expiración:</strong>
                                    <span id="confirm_fecha_expiracion"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>¿Usado?:</strong>
                                    <span id="confirm_usado"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-percent me-2"></i>Detalles del Descuento</h6>
                                <div class="confirmation-item">
                                    <strong>Tipo de descuento:</strong>
                                    <span id="confirm_tipo"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Valor del descuento:</strong>
                                    <span id="confirm_valor"></span>
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
                    <button type="submit" class="btn btn-update px-5 py-3" id="submitBtn" style="display: none;">
                        <i class="fas fa-save me-2"></i> Actualizar Cupón
                    </button>
                    <a href="{{ route('admin.cupones.index') }}" class="btn btn-cancel px-4 py-3">
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
    }
    
    .form-switch .form-check-input:checked {
        background-color: #4caf50;
        border-color: #4caf50;
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
</style>
@endpush

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 3;
    
    // Función para generar código aleatorio
    function generarCodigoAleatorio(longitud = 15) {
        const caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        let codigo = '';
        for (let i = 0; i < longitud; i++) {
            codigo += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
        }
        return codigo;
    }
    
    // Formatear fecha para mostrar (solo fecha)
    function formatearFecha(fecha) {
        if (!fecha) return 'Sin fecha de expiración';
        const date = new Date(fecha);
        return date.toLocaleDateString('es-MX', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
    
    // Cambiar símbolo según tipo de descuento
    const tipoDescuento = document.getElementById('tipo_descuento');
    const valorSimbolo = document.getElementById('valorSimbolo');
    const valorAyuda = document.getElementById('valorAyuda');
    const valorInput = document.getElementById('valor_descuento');
    
    tipoDescuento.addEventListener('change', function() {
        if (this.value === 'porcentaje') {
            valorSimbolo.innerHTML = '%';
            valorAyuda.innerHTML = '<i class="fas fa-info-circle me-1"></i>Ingrese el porcentaje de descuento (máximo 100%)';
            valorInput.placeholder = 'Ejemplo: 20';
            valorInput.max = 100;
            valorInput.step = 1;
        } else if (this.value === 'cantidad_fija') {
            valorSimbolo.innerHTML = '$';
            valorAyuda.innerHTML = '<i class="fas fa-info-circle me-1"></i>Ingrese el monto fijo del descuento';
            valorInput.placeholder = 'Ejemplo: 50.00';
            valorInput.max = null;
            valorInput.step = 0.01;
        } else {
            valorSimbolo.innerHTML = '$';
            valorAyuda.innerHTML = '<i class="fas fa-info-circle me-1"></i>Ingrese el valor del descuento';
            valorInput.placeholder = 'Ingrese el valor del descuento';
        }
    });
    
    // Switch para marcar usado/no usado
    const usadoCheckbox = document.getElementById('usado');
    const usadoHidden = document.getElementById('usadoHidden');
    const usadoLabel = document.getElementById('usadoLabel');
    
    if (usadoCheckbox) {
        usadoCheckbox.addEventListener('change', function() {
            if (this.checked) {
                usadoLabel.textContent = 'Sí, usado';
                usadoHidden.value = 1;
            } else {
                usadoLabel.textContent = 'No, disponible';
                usadoHidden.value = 0;
            }
            actualizarConfirmacion();
        });
    }
    
    // Actualizar datos de confirmación
    function actualizarConfirmacion() {
        const codigo = document.getElementById('codigo').value;
        const estatusSelect = document.querySelector('select[name="estatus"]');
        const estatus = estatusSelect.options[estatusSelect.selectedIndex]?.text || 'No seleccionado';
        const tipo = tipoDescuento.options[tipoDescuento.selectedIndex]?.text || 'No seleccionado';
        const valor = valorInput.value;
        const usado = usadoCheckbox ? (usadoCheckbox.checked ? 'Sí' : 'No') : 'No';
        const fechaExpiracion = document.getElementById('fecha_expiracion').value;
        
        document.getElementById('confirm_codigo').textContent = codigo || 'No especificado';
        document.getElementById('confirm_estatus').textContent = estatus;
        document.getElementById('confirm_fecha_expiracion').textContent = formatearFecha(fechaExpiracion);
        document.getElementById('confirm_usado').textContent = usado;
        document.getElementById('confirm_tipo').textContent = tipo;
        
        const simbolo = tipoDescuento.value === 'porcentaje' ? '%' : '$';
        document.getElementById('confirm_valor').textContent = valor ? `${simbolo} ${valor}` : 'No especificado';
    }
    
    // Validar paso actual
    function validarPaso(step) {
        if (step === 1) {
            const codigo = document.getElementById('codigo').value;
            const estatus = document.querySelector('select[name="estatus"]').value;
            
            if (!codigo.trim()) {
                Swal.fire('Error', 'El código del cupón es requerido', 'error');
                return false;
            }
            
            if (!estatus) {
                Swal.fire('Error', 'Debe seleccionar un estatus', 'error');
                return false;
            }
            
            return true;
        }
        
        if (step === 2) {
            const tipo = tipoDescuento.value;
            const valor = parseFloat(valorInput.value);
            
            if (!tipo) {
                Swal.fire('Error', 'Debe seleccionar un tipo de descuento', 'error');
                return false;
            }
            
            if (!valor || isNaN(valor) || valor <= 0) {
                Swal.fire('Error', 'Debe ingresar un valor de descuento válido', 'error');
                return false;
            }
            
            if (tipo === 'porcentaje' && valor > 100) {
                Swal.fire('Error', 'El porcentaje de descuento no puede ser mayor a 100%', 'error');
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
            actualizarConfirmacion();
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
    
    // Generar código aleatorio
    document.getElementById('generarCodigoAleatorio').addEventListener('click', function() {
        const nuevoCodigo = generarCodigoAleatorio(15);
        document.getElementById('codigo').value = nuevoCodigo;
        
        Swal.fire({
            title: 'Código generado',
            html: `Código generado: <strong class="text-primary" style="font-family: monospace; font-size: 1.2rem;">${nuevoCodigo}</strong>`,
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
        actualizarConfirmacion();
    });
    
    // Escuchar cambios en los campos para actualizar confirmación
    document.querySelectorAll('input, select').forEach(field => {
        field.addEventListener('change', actualizarConfirmacion);
        field.addEventListener('input', actualizarConfirmacion);
    });
    
    // Validación del formulario
    document.getElementById('cuponForm').addEventListener('submit', function(e) {
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
    actualizarConfirmacion();
</script>
@endpush