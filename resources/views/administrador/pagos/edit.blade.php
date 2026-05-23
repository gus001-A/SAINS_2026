@extends('administrador.layouts.master')

@section('title', 'Editar Pago - SAINS')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="display-5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                    <i class="fas fa-edit me-3"></i>Editar Pago
                </h1>
                <p class="text-muted">Modifique la información del pago del estudiante</p>
            </div>
            <a href="{{ route('admin.pagos.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>Volver al listado
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-modern p-4 p-lg-5">
            <form action="{{ route('admin.pagos.update', $pago->id) }}" method="POST" id="pagoForm" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')
                
                <!-- Stepper Indicator -->
                <div class="stepper-wrapper mb-5">
                    <div class="stepper-item active" data-step="1">
                        <div class="step-counter">1</div>
                        <div class="step-name">Información del Pago</div>
                    </div>
                    <div class="stepper-item" data-step="2">
                        <div class="step-counter">2</div>
                        <div class="step-name">Comprobante</div>
                    </div>
                    <div class="stepper-item" data-step="3">
                        <div class="step-counter">3</div>
                        <div class="step-name">Confirmación</div>
                    </div>
                </div>
                
                <!-- Step 1: Información del Pago -->
                <div class="step-content" data-step="1">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));">
                            <i class="fas fa-credit-card fa-2x" style="color: #4361ee;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Información del Pago</h4>
                            <p class="text-muted small mb-0">Datos del pago realizado por el estudiante</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-tag text-primary me-1"></i> Tipo de Pago <span class="text-danger">*</span>
                            </label>
                            <select name="tipo_pago" id="tipo_pago" class="form-select form-select-lg @error('tipo_pago') is-invalid @enderror" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="Bancario" {{ old('tipo_pago', $pago->tipo_pago) == 'Bancario' ? 'selected' : '' }}>🏦 Bancario</option>
                                <option value="Oxxo" {{ old('tipo_pago', $pago->tipo_pago) == 'Oxxo' ? 'selected' : '' }}>🏪 Oxxo</option>
                                <option value="Transferencia" {{ old('tipo_pago', $pago->tipo_pago) == 'Transferencia' ? 'selected' : '' }}>💸 Transferencia</option>
                            </select>
                            @error('tipo_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user-graduate text-primary me-1"></i> Estudiante <span class="text-danger">*</span>
                            </label>
                            <select name="alumno_pago" id="alumno_pago" class="form-select form-select-lg @error('alumno_pago') is-invalid @enderror" required>
                                <option value="">Seleccionar estudiante</option>
                                @foreach($estudiantes as $estudiante)
                                    <option value="{{ $estudiante->id }}" {{ old('alumno_pago', $pago->alumno_pago) == $estudiante->id ? 'selected' : '' }}>
                                        {{ $estudiante->nombre }} {{ $estudiante->paterno }} {{ $estudiante->materno }}
                                    </option>
                                @endforeach
                            </select>
                            @error('alumno_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar-alt text-primary me-1"></i> Fecha de Pago <span class="text-danger">*</span>
                            </label>
                            <div class="position-relative">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-transparent"><i class="fas fa-calendar text-primary"></i></span>
                                    <input type="text" class="form-control bg-light" 
                                           value="{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}" 
                                           readonly disabled>
                                    <input type="hidden" name="fecha_pago" value="{{ $pago->fecha_pago }}">
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="fas fa-info-circle me-1"></i>
                                    La fecha del pago original no puede ser modificada
                                </small>
                            </div>
                            @error('fecha_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-dollar-sign text-primary me-1"></i> Monto <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent">$</span>
                                <input type="number" step="0.01" name="monto_pago" id="monto_pago" class="form-control @error('monto_pago') is-invalid @enderror" 
                                       value="{{ old('monto_pago', $pago->monto_pago) }}" placeholder="0.00" required>
                            </div>
                            @error('monto_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Monto en pesos mexicanos (MXN)</small>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-barcode text-primary me-1"></i> Referencia
                            </label>
                            <input type="text" name="referencia_pago" id="referencia_pago" class="form-control form-control-lg @error('referencia_pago') is-invalid @enderror" 
                                   value="{{ old('referencia_pago', $pago->referencia_pago) }}" placeholder="Número de referencia">
                            @error('referencia_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Opcional - Identificador del pago</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-check-circle text-primary me-1"></i> Estado del Pago <span class="text-danger">*</span>
                            </label>
                            <select name="estatus" id="estatus" class="form-select form-select-lg @error('estatus') is-invalid @enderror" required>
                                <option value="">Seleccionar estado</option>
                                <option value="pendiente" {{ old('estatus', $pago->estatus) == 'pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                                <option value="aprobado" {{ old('estatus', $pago->estatus) == 'aprobado' ? 'selected' : '' }}>✅ Aprobado</option>
                                <option value="rechazado" {{ old('estatus', $pago->estatus) == 'rechazado' ? 'selected' : '' }}>❌ Rechazado</option>
                                <option value="cancelado" {{ old('estatus', $pago->estatus) == 'cancelado' ? 'selected' : '' }}>🚫 Cancelado</option>
                            </select>
                            @error('estatus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user-check text-primary me-1"></i> Revisión
                            </label>
                            <div class="alert-info-modern mt-2 mb-0 py-2 px-3" id="alertInfo">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    @if($pago->usuario_revision && $pago->revisor)
                                        @php
                                            $admin = $pago->revisor->administrador;
                                            $revisorNombre = $admin ? $admin->nombre_completo : $pago->revisor->correo;
                                        @endphp
                                        Este pago fue revisado por: <strong>{{ $revisorNombre }}</strong>
                                        @if($pago->fecha_aprueba)
                                            el {{ \Carbon\Carbon::parse($pago->fecha_aprueba)->format('d/m/Y H:i') }}
                                        @endif
                                    @else
                                        Si selecciona <strong>"Aprobado"</strong> o <strong>"Rechazado"</strong>, se registrará como revisado por: 
                                        <strong>{{ auth()->user()->correo ?? auth()->user()->name ?? 'Usuario actual' }}</strong>
                                    @endif
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-sticky-note text-primary me-1"></i> Nota / Observación
                            </label>
                            <textarea name="nota_usuario" id="nota_usuario" class="form-control form-control-lg @error('nota_usuario') is-invalid @enderror" 
                                      rows="3" placeholder="Información adicional sobre el pago...">{{ old('nota_usuario', $pago->nota_usuario) }}</textarea>
                            @error('nota_usuario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Opcional - Detalles adicionales del pago (útil para especificar motivo de rechazo)</small>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: Comprobante de Pago -->
                <div class="step-content" data-step="2" style="display: none;">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(40,167,69,0.1), rgba(32,201,151,0.1));">
                            <i class="fas fa-receipt fa-2x" style="color: #28a745;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Comprobante de Pago</h4>
                            <p class="text-muted small mb-0">Actualice la imagen del comprobante (JPG, PNG, PDF)</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <!-- Comprobante actual -->
                        @if($pago->comprobante)
                        <div class="col-12 mb-2">
                            <div class="current-file-card" id="currentFileCard">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle p-2" style="background: rgba(67,97,238,0.1);">
                                            <i class="fas fa-file-alt fa-2x" style="color: #4361ee;"></i>
                                        </div>
                                        <div>
                                            <strong>Comprobante actual:</strong>
                                            <span class="ms-2">{{ basename($pago->comprobante) }}</span>
                                            <br>
                                            <small class="text-muted">Archivo subido anteriormente</small>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ asset('storage/' . $pago->comprobante) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                            <i class="fas fa-eye me-1"></i> Ver actual
                                        </a>
                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="marcarParaEliminar()">
                                            <i class="fas fa-trash me-1"></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="eliminar_comprobante" id="eliminar_comprobante" value="0">
                            </div>
                        </div>
                        @endif
                        
                        <div class="col-12">
                            <div class="upload-area" id="uploadArea">
                                <div class="upload-content text-center">
                                    <i class="fas fa-cloud-upload-alt fa-4x mb-3" style="color: #4361ee;"></i>
                                    <h5 class="mb-2">Subir Nuevo Comprobante</h5>
                                    <p class="text-muted mb-2">Arrastre y suelte su archivo aquí o haga clic para seleccionar</p>
                                    <small class="text-muted">Formatos permitidos: JPG, PNG, PDF (Máx. 5MB)</small>
                                    <input type="file" name="comprobante" id="comprobante" class="d-none" accept=".jpg,.jpeg,.png,.pdf">
                                    <button type="button" class="btn btn-outline-primary mt-3 px-4 rounded-pill" onclick="document.getElementById('comprobante').click()">
                                        <i class="fas fa-folder-open me-2"></i>Seleccionar Archivo
                                    </button>
                                </div>
                                <div id="filePreview" class="mt-3" style="display: none;">
                                    <div class="alert-success-card d-flex align-items-center justify-content-between">
                                        <div>
                                            <i class="fas fa-check-circle me-2" style="color: #28a745;"></i>
                                            <strong>Nuevo archivo seleccionado:</strong> <span id="fileName"></span>
                                        </div>
                                        <button type="button" class="btn-close-modern" onclick="limpiarArchivo()">×</button>
                                    </div>
                                </div>
                                <div id="imagePreviewContainer" class="mt-3" style="display: none;">
                                    <div class="preview-card">
                                        <div class="preview-header">
                                            <h6 class="mb-0"><i class="fas fa-image me-2"></i>Previsualización del nuevo comprobante</h6>
                                        </div>
                                        <div class="preview-body text-center">
                                            <img id="imagePreview" src="" alt="Vista previa" class="img-fluid rounded-3" style="max-height: 250px;">
                                            <div class="mt-2">
                                                <small class="text-muted" id="fileSize"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="pdfPreviewContainer" class="mt-3" style="display: none;">
                                    <div class="preview-card">
                                        <div class="preview-header">
                                            <h6 class="mb-0"><i class="fas fa-file-pdf me-2" style="color: #dc3545;"></i>Documento PDF</h6>
                                        </div>
                                        <div class="preview-body text-center">
                                            <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                                            <p class="text-muted small" id="pdfName"></p>
                                            <small class="text-muted" id="pdfFileSize"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('comprobante')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Si no selecciona un nuevo comprobante, se mantendrá el actual
                            </small>
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
                            <h4 class="mb-0 fw-semibold">Confirmar Actualización</h4>
                            <p class="text-muted small mb-0">Revise los datos antes de guardar los cambios</p>
                        </div>
                    </div>
                    
                    <div class="confirmation-card p-4">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-receipt me-2"></i>Resumen del Pago
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="confirmation-item">
                                    <strong>Tipo de pago:</strong>
                                    <span id="confirm_tipo">{{ $pago->tipo_pago ?: '-' }}</span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Estudiante:</strong>
                                    <span id="confirm_estudiante">
                                        @php
                                            $estudianteActual = $estudiantes->find($pago->alumno_pago);
                                        @endphp
                                        {{ $estudianteActual ? $estudianteActual->nombre . ' ' . $estudianteActual->paterno . ' ' . $estudianteActual->materno : 'No especificado' }}
                                    </span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Monto:</strong>
                                    <span id="confirm_monto" class="text-success fw-bold">${{ number_format($pago->monto_pago, 2) }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="confirmation-item">
                                    <strong>Fecha de pago:</strong>
                                    <span id="confirm_fecha">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Estado:</strong>
                                    <span id="confirm_estado">
                                        @php
                                            $estadoBadge = '';
                                            switch($pago->estatus) {
                                                case 'pendiente': $estadoBadge = 'badge-pendiente'; $estadoText = 'Pendiente'; break;
                                                case 'aprobado': $estadoBadge = 'badge-aprobado'; $estadoText = 'Aprobado'; break;
                                                case 'rechazado': $estadoBadge = 'badge-rechazado'; $estadoText = 'Rechazado'; break;
                                                case 'cancelado': $estadoBadge = 'badge-cancelado'; $estadoText = 'Cancelado'; break;
                                                default: $estadoBadge = ''; $estadoText = $pago->estatus ?: '-';
                                            }
                                        @endphp
                                        <span class="badge-estado {{ $estadoBadge }}">{{ $estadoText }}</span>
                                    </span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Referencia:</strong>
                                    <span id="confirm_referencia">{{ $pago->referencia_pago ?: 'No proporcionada' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="confirmation-item">
                                <strong>Nota / Observación:</strong>
                                <span id="confirm_nota" class="text-primary">{{ $pago->nota_usuario ?: 'Sin observaciones' }}</span>
                            </div>
                            <div class="confirmation-item">
                                <strong>Comprobante:</strong>
                                <span id="confirm_comprobante" class="{{ $pago->comprobante ? 'text-success' : 'text-muted' }}">
                                    @if($pago->comprobante)
                                        <i class="fas fa-check-circle me-1"></i>Archivo adjunto actual
                                    @else
                                        <i class="fas fa-times-circle me-1"></i>Sin comprobante
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert-info-modern mt-4 p-3" id="confirmChanges">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            <span id="cambiosText">Revise los cambios antes de guardar</span>
                        </small>
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
                        <i class="fas fa-save me-2"></i> Actualizar Pago
                    </button>
                    <a href="{{ route('admin.pagos.index') }}" class="btn btn-cancel px-4 py-3 rounded-pill">
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
    
    .input-group-lg .form-control {
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }
    
    .input-group-text {
        border-radius: 12px 0 0 12px;
        border: 2px solid #e0e0e0;
        border-right: none;
    }
    
    .input-group .form-control {
        border-radius: 0 12px 12px 0;
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
    
    .current-file-card {
        background: linear-gradient(135deg, #e3f2fd 0%, #e8eaf6 100%);
        border-radius: 16px;
        padding: 1rem 1.5rem;
        border-left: 4px solid #4361ee;
    }
    
    .upload-area {
        border: 2px dashed #4361ee;
        border-radius: 20px;
        padding: 40px 30px;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .upload-area:hover {
        background: #e9ecef;
        border-color: #764ba2;
        transform: translateY(-2px);
    }
    
    .upload-area.drag-over {
        background: rgba(67,97,238,0.1);
        border-color: #28a745;
    }
    
    .alert-info-modern {
        background: linear-gradient(135deg, rgba(102,126,234,0.08), rgba(118,75,162,0.08));
        border-radius: 12px;
        border-left: 4px solid #4361ee;
    }
    
    .alert-success-card {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-radius: 12px;
        padding: 12px 20px;
        color: #155724;
    }
    
    .btn-close-modern {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #155724;
        transition: all 0.2s ease;
    }
    
    .btn-close-modern:hover {
        color: #dc3545;
        transform: scale(1.1);
    }
    
    .preview-card {
        background: #f8f9fa;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .preview-header {
        background: rgba(0,0,0,0.03);
        padding: 12px 20px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .preview-body {
        padding: 20px;
    }
    
    .confirmation-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 20px;
        border: 1px solid rgba(102,126,234,0.2);
    }
    
    .confirmation-item {
        padding: 10px 0;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .confirmation-item:last-child {
        border-bottom: none;
    }
    
    .badge-estado {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .badge-pendiente {
        background-color: #ffc107;
        color: #856404;
    }
    
    .badge-aprobado {
        background-color: #28a745;
        color: white;
    }
    
    .badge-rechazado {
        background-color: #dc3545;
        color: white;
    }
    
    .badge-cancelado {
        background-color: #6c757d;
        color: white;
    }
    
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
    
    body.dark-mode .current-file-card {
        background: linear-gradient(135deg, #1a237e 0%, #0d1b3e 100%);
        color: #e0e0e0;
    }
    
    body.dark-mode .upload-area {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        border-color: rgba(102,126,234,0.5);
    }
    
    body.dark-mode .upload-area:hover {
        background: #0f0f1a;
    }
    
    body.dark-mode .preview-card {
        background: #0f0f1a;
        border-color: rgba(255,255,255,0.1);
    }
    
    body.dark-mode .confirmation-card {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        border-color: rgba(102,126,234,0.3);
        color: #e0e0e0;
    }
    
    body.dark-mode .alert-info-modern {
        background: linear-gradient(135deg, rgba(102,126,234,0.15), rgba(118,75,162,0.15));
    }
    
    body.dark-mode .border-top,
    body.dark-mode .border-bottom {
        border-top-color: rgba(255, 255, 255, 0.1) !important;
        border-bottom-color: rgba(255, 255, 255, 0.1) !important;
    }
    
    body.dark-mode .text-muted {
        color: #a0a0a0 !important;
    }
    
    body.dark-mode .stepper-wrapper::before {
        background: #333;
    }
    
    body.dark-mode .step-counter {
        background: #1a1a2e;
        border-color: #444;
        color: #e0e0e0;
    }
    
    body.dark-mode .bg-light {
        background-color: #0f0f1a !important;
        color: #e0e0e0 !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let currentStep = 1;
    const totalSteps = 3;
    let archivoSeleccionado = null;
    let comprobanteActual = {{ $pago->comprobante ? 'true' : 'false' }};
    let eliminandoComprobante = false;
    
    // Elementos del DOM
    const tipoPagoSelect = document.getElementById('tipo_pago');
    const estudianteSelect = document.getElementById('alumno_pago');
    const montoInput = document.getElementById('monto_pago');
    const estatusSelect = document.getElementById('estatus');
    const referenciaInput = document.getElementById('referencia_pago');
    const notaTextarea = document.getElementById('nota_usuario'); // <--- IMPORTANTE: Nota se guarda desde aquí
    
    function actualizarMensajeRevision() {
        const estado = estatusSelect?.value;
        const alertInfo = document.getElementById('alertInfo');
        
        if (!alertInfo) return;
        
        const usuarioActual = '{{ auth()->user()->correo ?? auth()->user()->name ?? "Usuario actual" }}';
        
        if (estado === 'aprobado') {
            alertInfo.style.background = 'linear-gradient(135deg, rgba(40,167,69,0.1), rgba(32,201,151,0.1))';
            alertInfo.style.borderLeftColor = '#28a745';
            alertInfo.innerHTML = `
                <small>
                    <i class="fas fa-check-circle me-1" style="color: #28a745;"></i>
                    Al seleccionar <strong>"Aprobado"</strong>, se registrará como aprobado por: 
                    <strong>${usuarioActual}</strong> en la fecha actual.
                </small>
            `;
        } else if (estado === 'rechazado') {
            alertInfo.style.background = 'linear-gradient(135deg, rgba(220,53,69,0.1), rgba(220,53,69,0.05))';
            alertInfo.style.borderLeftColor = '#dc3545';
            alertInfo.innerHTML = `
                <small>
                    <i class="fas fa-times-circle me-1" style="color: #dc3545;"></i>
                    Al seleccionar <strong>"Rechazado"</strong>, se registrará como rechazado por: 
                    <strong>${usuarioActual}</strong> en la fecha actual.
                    <br>Recomendación: Especifique el motivo en el campo <strong>"Nota / Observación"</strong>.
                </small>
            `;
        } else if (estado === 'pendiente') {
            alertInfo.style.background = 'linear-gradient(135deg, rgba(255,193,7,0.1), rgba(255,193,7,0.05))';
            alertInfo.style.borderLeftColor = '#ffc107';
            alertInfo.innerHTML = `
                <small>
                    <i class="fas fa-clock me-1" style="color: #ffc107;"></i>
                    Estado <strong>"Pendiente"</strong> - Sin revisión asignada.
                </small>
            `;
        } else if (estado === 'cancelado') {
            alertInfo.style.background = 'linear-gradient(135deg, rgba(108,117,125,0.1), rgba(108,117,125,0.05))';
            alertInfo.style.borderLeftColor = '#6c757d';
            alertInfo.innerHTML = `
                <small>
                    <i class="fas fa-ban me-1" style="color: #6c757d;"></i>
                    Estado <strong>"Cancelado"</strong> - El pago ha sido cancelado.
                </small>
            `;
        }
        
        const revisionPrevia = '{{ $pago->usuario_revision ? "1" : "0" }}';
        const revisorNombre = '{{ $pago->revisor && $pago->revisor->administrador ? addslashes($pago->revisor->administrador->nombre_completo) : ($pago->revisor ? addslashes($pago->revisor->correo) : "") }}';
        const fechaRevision = '{{ $pago->fecha_aprueba ? \Carbon\Carbon::parse($pago->fecha_aprueba)->format("d/m/Y H:i") : "" }}';
        
        if (revisionPrevia === '1' && revisorNombre && (estado === 'pendiente' || estado !== '{{ $pago->estatus }}')) {
            alertInfo.innerHTML += `
                <small class="d-block mt-1 text-muted">
                    <i class="fas fa-history me-1"></i>
                    Revisión anterior: <strong>${revisorNombre}</strong> (${fechaRevision})
                </small>
            `;
        }
    }
    
    function marcarParaEliminar() {
        Swal.fire({
            title: '¿Eliminar comprobante?',
            text: 'El comprobante actual será eliminado permanentemente',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#2c3e50'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('eliminar_comprobante').value = '1';
                const currentFileCard = document.getElementById('currentFileCard');
                if (currentFileCard) {
                    currentFileCard.style.display = 'none';
                }
                comprobanteActual = false;
                eliminandoComprobante = true;
                
                const comprobanteSpan = document.getElementById('confirm_comprobante');
                if (comprobanteSpan) {
                    comprobanteSpan.innerHTML = '<i class="fas fa-times-circle me-1"></i>Sin comprobante (será eliminado)';
                    comprobanteSpan.className = 'text-danger';
                }
                
                Swal.fire({
                    title: 'Eliminado',
                    text: 'El comprobante será eliminado al guardar los cambios',
                    icon: 'success',
                    confirmButtonColor: '#4361ee',
                    timer: 2000,
                    background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                    color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#2c3e50'
                });
            }
        });
    }
    
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('comprobante');
    
    if (uploadArea) {
        uploadArea.addEventListener('click', function(e) {
            if (e.target !== fileInput && !fileInput.contains(e.target)) {
                fileInput.click();
            }
        });
        
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('drag-over');
        });
        
        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('drag-over');
        });
        
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('drag-over');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                procesarArchivo(files[0]);
            }
        });
    }
    
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            if (this.files.length > 0) {
                procesarArchivo(this.files[0]);
            }
        });
    }
    
    function procesarArchivo(file) {
        const tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        if (!tiposPermitidos.includes(file.type)) {
            Swal.fire({
                title: 'Error',
                text: 'Formato no válido. Solo se permiten JPG, PNG y PDF',
                icon: 'error',
                confirmButtonColor: '#667eea'
            });
            limpiarArchivo();
            return;
        }
        
        if (file.size > 5 * 1024 * 1024) {
            Swal.fire({
                title: 'Error',
                text: 'El archivo no debe superar los 5MB',
                icon: 'error',
                confirmButtonColor: '#667eea'
            });
            limpiarArchivo();
            return;
        }
        
        archivoSeleccionado = file;
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('filePreview').style.display = 'block';
        if (uploadArea) uploadArea.style.borderColor = '#28a745';
        
        const comprobanteSpan = document.getElementById('confirm_comprobante');
        if (comprobanteSpan) {
            comprobanteSpan.innerHTML = '<i class="fas fa-check-circle me-1"></i>Nuevo archivo: ' + file.name;
            comprobanteSpan.className = 'text-success';
        }
        
        if (file.type === 'application/pdf') {
            document.getElementById('imagePreviewContainer').style.display = 'none';
            document.getElementById('pdfPreviewContainer').style.display = 'block';
            document.getElementById('pdfName').textContent = file.name;
            document.getElementById('pdfFileSize').textContent = `Tamaño: ${(file.size / 1024).toFixed(2)} KB`;
        } else {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('imagePreviewContainer').style.display = 'block';
                document.getElementById('pdfPreviewContainer').style.display = 'none';
                document.getElementById('fileSize').textContent = `Tamaño: ${(file.size / 1024).toFixed(2)} KB`;
            };
            reader.readAsDataURL(file);
        }
        
        if (currentStep === 3) {
            actualizarConfirmacion();
        }
    }
    
    function limpiarArchivo() {
        archivoSeleccionado = null;
        if (fileInput) fileInput.value = '';
        document.getElementById('filePreview').style.display = 'none';
        document.getElementById('imagePreviewContainer').style.display = 'none';
        document.getElementById('pdfPreviewContainer').style.display = 'none';
        if (uploadArea) uploadArea.style.borderColor = '#4361ee';
        
        const comprobanteSpan = document.getElementById('confirm_comprobante');
        if (comprobanteSpan) {
            if (comprobanteActual && !eliminandoComprobante) {
                comprobanteSpan.innerHTML = '<i class="fas fa-check-circle me-1"></i>Archivo adjunto actual';
                comprobanteSpan.className = 'text-success';
            } else if (eliminandoComprobante) {
                comprobanteSpan.innerHTML = '<i class="fas fa-times-circle me-1"></i>Sin comprobante (será eliminado)';
                comprobanteSpan.className = 'text-danger';
            } else {
                comprobanteSpan.innerHTML = '<i class="fas fa-times-circle me-1"></i>Sin comprobante';
                comprobanteSpan.className = 'text-muted';
            }
        }
        
        if (currentStep === 3) {
            actualizarConfirmacion();
        }
    }
    
    function actualizarConfirmacion() {
        const tipoText = tipoPagoSelect?.options[tipoPagoSelect.selectedIndex]?.text.replace(/[🏦🏪💸]/g, '').trim() || '-';
        const estudianteText = estudianteSelect?.options[estudianteSelect.selectedIndex]?.text || '-';
        const monto = parseFloat(montoInput?.value) || 0;
        const estadoValue = estatusSelect?.value || '';
        const estadoText = estatusSelect?.options[estatusSelect.selectedIndex]?.text.replace(/[⏳✅❌🚫]/g, '').trim() || '-';
        const referencia = referenciaInput?.value || 'No proporcionada';
        const nota = notaTextarea?.value || 'Sin observaciones'; // <--- NOTA se actualiza aquí
        
        document.getElementById('confirm_tipo').textContent = tipoText;
        document.getElementById('confirm_estudiante').textContent = estudianteText;
        document.getElementById('confirm_monto').innerHTML = `$${monto.toFixed(2)} MXN`;
        document.getElementById('confirm_referencia').textContent = referencia;
        document.getElementById('confirm_nota').innerHTML = `<span style="color: #4361ee; font-style: italic;">${nota}</span>`;
        
        let estadoHtml = '';
        switch(estadoValue) {
            case 'pendiente':
                estadoHtml = `<span class="badge-estado badge-pendiente">${estadoText}</span>`;
                break;
            case 'aprobado':
                estadoHtml = `<span class="badge-estado badge-aprobado">${estadoText}</span>`;
                break;
            case 'rechazado':
                estadoHtml = `<span class="badge-estado badge-rechazado">${estadoText}</span>`;
                break;
            case 'cancelado':
                estadoHtml = `<span class="badge-estado badge-cancelado">${estadoText}</span>`;
                break;
            default:
                estadoHtml = estadoText;
        }
        document.getElementById('confirm_estado').innerHTML = estadoHtml;
        
        const cambiosText = document.getElementById('cambiosText');
        let cambios = [];
        if (archivoSeleccionado) cambios.push('nuevo comprobante');
        if (eliminandoComprobante && !archivoSeleccionado) cambios.push('eliminación de comprobante');
        
        const estadoActual = '{{ $pago->estatus }}';
        if (estadoValue !== estadoActual && (estadoValue === 'aprobado' || estadoValue === 'rechazado')) {
            cambios.push(`cambio de estado a "${estadoText}" (se registrará revisión)`);
        }
        
        if (cambios.length > 0) {
            cambiosText.innerHTML = `Se aplicarán los siguientes cambios: ${cambios.join(', ')}`;
        } else {
            cambiosText.innerHTML = 'Revise los cambios antes de guardar';
        }
    }
    
    function validarPaso(step) {
        if (step === 1) {
            if (!tipoPagoSelect?.value) {
                Swal.fire('Error', 'Debe seleccionar un tipo de pago', 'error');
                return false;
            }
            if (!estudianteSelect?.value) {
                Swal.fire('Error', 'Debe seleccionar un estudiante', 'error');
                return false;
            }
            const monto = parseFloat(montoInput?.value);
            if (isNaN(monto) || monto <= 0) {
                Swal.fire('Error', 'El monto debe ser mayor a 0', 'error');
                return false;
            }
            if (monto > 999999.99) {
                Swal.fire('Error', 'El monto no puede ser mayor a $999,999.99', 'error');
                return false;
            }
            if (!estatusSelect?.value) {
                Swal.fire('Error', 'Debe seleccionar un estado para el pago', 'error');
                return false;
            }
            return true;
        }
        return true;
    }
    
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
            actualizarConfirmacion();
        } else {
            if (prevBtn) prevBtn.style.display = 'block';
            if (nextBtn) nextBtn.style.display = 'block';
            if (submitBtn) submitBtn.style.display = 'none';
        }
    }
    
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
    
    if (tipoPagoSelect) tipoPagoSelect.addEventListener('change', () => { if (currentStep === 3) actualizarConfirmacion(); });
    if (estudianteSelect) estudianteSelect.addEventListener('change', () => { if (currentStep === 3) actualizarConfirmacion(); });
    if (montoInput) montoInput.addEventListener('input', () => { if (currentStep === 3) actualizarConfirmacion(); });
    if (estatusSelect) estatusSelect.addEventListener('change', () => { 
        if (currentStep === 3) actualizarConfirmacion();
        actualizarMensajeRevision();
    });
    if (referenciaInput) referenciaInput.addEventListener('input', () => { if (currentStep === 3) actualizarConfirmacion(); });
    if (notaTextarea) notaTextarea.addEventListener('input', () => { if (currentStep === 3) actualizarConfirmacion(); });
    
    actualizarMensajeRevision();
    
    function enviarFormulario() {
        const btnSubmit = document.getElementById('submitBtn');
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Actualizando pago...';
        document.getElementById('pagoForm').submit();
    }
    
    document.getElementById('pagoForm')?.addEventListener('submit', function(e) {
        if (!validarPaso(1)) {
            e.preventDefault();
            return false;
        }
        
        const monto = parseFloat(montoInput?.value);
        const estadoSeleccionado = estatusSelect?.value;
        const estadoActual = '{{ $pago->estatus }}';
        const nota = notaTextarea?.value?.trim() || '';
        
        if (estadoSeleccionado === 'rechazado' && estadoSeleccionado !== estadoActual) {
            if (!nota) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Rechazar sin motivo?',
                    text: 'Es recomendable especificar el motivo del rechazo en el campo "Nota / Observación". ¿Desea continuar de todas formas?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, rechazar sin motivo',
                    cancelButtonText: 'Cancelar y agregar motivo',
                    background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                    color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#2c3e50'
                }).then((result) => {
                    if (result.isConfirmed) {
                        enviarFormulario();
                    }
                });
                return false;
            }
        }
        
        if (monto > 5000) {
            e.preventDefault();
            Swal.fire({
                title: 'Confirmar Monto Alto',
                text: `Está a punto de actualizar un pago por $${monto.toFixed(2)} MXN. ¿Desea continuar?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar',
                background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#2c3e50'
            }).then((result) => {
                if (result.isConfirmed) {
                    enviarFormulario();
                }
            });
            return false;
        }
        
        enviarFormulario();
    });
    
    mostrarPaso(1);
</script>
@endpush