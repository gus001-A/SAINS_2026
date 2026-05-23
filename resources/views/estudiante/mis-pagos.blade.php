@extends('estudiante.layouts.app')

@section('title', 'Mi Pago - SAINS')

@section('content')
<div class="mi-pago-wrapper">
    <!-- Header -->
    <div class="pago-header">
        <div class="pago-header-content">
            <div class="pago-header-icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <div class="pago-header-text">
                <h1>Mi Pago</h1>
                <p>Estado de tu solicitud de activación</p>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="alert-custom alert-success-custom">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button class="alert-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert-custom alert-error-custom">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
            <button class="alert-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Si NO hay pago -->
    @if(!$pago)
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-receipt"></i>
            </div>
            <h3>No tienes pagos registrados</h3>
            <p>Adquiere tu plan Premium para comenzar a estudiar</p>
            <a href="{{ route('estudiante.checkout') }}" class="btn-primary-custom">
                <i class="fas fa-shopping-cart me-2"></i>Adquirir plan
            </a>
        </div>
    @else
        @php
            $estadoClass = match($pago->estatus) {
                'pendiente' => 'status-pending',
                'aprobado' => 'status-approved',
                'rechazado' => 'status-rejected',
                'cancelado' => 'status-cancelled',
                default => 'status-default'
            };
            $estadoIcono = match($pago->estatus) {
                'pendiente' => 'fa-clock',
                'aprobado' => 'fa-check-circle',
                'rechazado' => 'fa-times-circle',
                'cancelado' => 'fa-ban',
                default => 'fa-question-circle'
            };
            $estadoTexto = match($pago->estatus) {
                'pendiente' => 'Pendiente de validación',
                'aprobado' => '¡Pago aprobado!',
                'rechazado' => 'Pago rechazado',
                'cancelado' => 'Cancelado',
                default => ucfirst($pago->estatus)
            };
            $estadoDescripcion = match($pago->estatus) {
                'pendiente' => 'Tu pago está siendo revisado por nuestro equipo',
                'aprobado' => 'Tu plan premium ya está activo. ¡Disfruta de todo el contenido!',
                'rechazado' => 'Hubo un problema con tu comprobante. Por favor, súbelo nuevamente',
                'cancelado' => 'Este pago fue cancelado',
                default => ''
            };
        @endphp

        <!-- Tarjeta principal -->
        <div class="pago-card">
            <div class="pago-card-header">
                <div class="pago-status {{ $estadoClass }}">
                    <i class="fas {{ $estadoIcono }}"></i>
                    <div>
                        <span class="status-title">{{ $estadoTexto }}</span>
                        <span class="status-desc">{{ $estadoDescripcion }}</span>
                    </div>
                </div>
            </div>
            
            <div class="pago-card-body">
                <!-- Referencia destacada -->
                <div class="referencia-destacada">
                    <div class="referencia-label">
                        <i class="fas fa-hashtag"></i>
                        <span>Referencia de pago</span>
                    </div>
                    <div class="referencia-value">
                        <code>{{ $pago->referencia_pago }}</code>
                        <button class="copy-btn" onclick="copiarReferencia('{{ $pago->referencia_pago }}')" title="Copiar referencia">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>

                <!-- Grid de información -->
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Monto</span>
                        </div>
                        <div class="info-value monto">
                            ${{ number_format($pago->monto_pago, 2) }} MXN
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-credit-card"></i>
                            <span>Método de pago</span>
                        </div>
                        <div class="info-value">
                            @if($pago->tipo_pago == 'Transferencia')
                                <span class="method-badge method-transferencia">
                                    <i class="fas fa-university"></i> Transferencia bancaria
                                </span>
                            @elseif($pago->tipo_pago == 'Oxxo')
                                <span class="method-badge method-oxxo">
                                    <i class="fas fa-store"></i> Pago en OXXO
                                </span>
                            @else
                                <span class="method-badge method-card">
                                    <i class="fas fa-credit-card"></i> Tarjeta
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Fecha de solicitud</span>
                        </div>
                        <div class="info-value">
                            {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                            <small class="time-text">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('H:i') }} hrs</small>
                        </div>
                    </div>
                    
                    @if($pago->estatus == 'pendiente' && $pago->fecha_pago)
                        @php
                            $diasTranscurridos = \Carbon\Carbon::parse($pago->fecha_pago)->diffInDays(now());
                        @endphp
                        @if($diasTranscurridos > 3)
                        <div class="info-item warning-item">
                            <div class="info-label">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Atención</span>
                            </div>
                            <div class="info-value warning-text">
                                {{ $diasTranscurridos }} días en espera de validación
                            </div>
                        </div>
                        @endif
                    @endif
                </div>

                <!-- Comprobante -->
                <div class="comprobante-section">
                    <div class="comprobante-title">
                        <i class="fas fa-file-invoice"></i>
                        <span>Comprobante de pago</span>
                    </div>
                    
                    @if($pago->comprobante)
                        <div class="comprobante-adjunto">
                            <div class="file-info">
                                <i class="fas fa-file-pdf"></i>
                                <div>
                                    <strong>Comprobante adjunto</strong>
                                    <p>Archivo subido el {{ \Carbon\Carbon::parse($pago->updated_at)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="comprobante-actions">
                                <a href="{{ Storage::url($pago->comprobante) }}" target="_blank" class="action-btn primary" title="Ver comprobante">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <a href="{{ Storage::url($pago->comprobante) }}" download class="action-btn secondary" title="Descargar">
                                    <i class="fas fa-download"></i> Descargar
                                </a>
                                <button class="action-btn outline" onclick="copiarEnlace('{{ Storage::url($pago->comprobante) }}')" title="Copiar enlace">
                                    <i class="fas fa-link"></i> Copiar
                                </button>
                            </div>
                        </div>
                    @else
                        @if($pago->estatus == 'pendiente')
                            <div class="comprobante-faltante">
                                <div class="faltante-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="faltante-info">
                                    <strong>No has subido tu comprobante</strong>
                                    <p>Para activar tu plan, necesitas subir el comprobante de pago</p>
                                </div>
                                <button class="btn-upload" onclick="abrirModalSubirComprobante({{ $pago->id }}, '{{ $pago->referencia_pago }}')">
                                    <i class="fas fa-upload"></i> Subir comprobante
                                </button>
                            </div>
                        @elseif($pago->estatus == 'rechazado')
                            <div class="comprobante-rechazado">
                                <div class="rechazado-icon">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="rechazado-info">
                                    <strong>Comprobante rechazado</strong>
                                    <p>{{ $pago->nota_usuario ?? 'Por favor, vuelve a subir un comprobante válido' }}</p>
                                </div>
                                <button class="btn-upload" onclick="abrirModalSubirComprobante({{ $pago->id }}, '{{ $pago->referencia_pago }}')">
                                    <i class="fas fa-redo-alt"></i> Subir nuevo comprobante
                                </button>
                            </div>
                        @else
                            <div class="comprobante-sin">
                                <i class="fas fa-clock"></i>
                                <span>Sin comprobante registrado</span>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Acciones adicionales -->
                @if($pago->estatus == 'pendiente')
                    <div class="acciones-adicionales">
                        <a href="{{ route('estudiante.ficha-pago', $pago->id) }}" class="btn-ficha">
                            <i class="fas fa-file-invoice"></i>
                            <span>Descargar ficha de pago</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Información adicional -->
        <div class="info-adicional">
            <div class="info-card">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>¿Qué sigue?</strong>
                    @if($pago->estatus == 'pendiente')
                        <p>Una vez que subas tu comprobante, nuestro equipo lo revisará en un plazo de <strong>24-48 horas hábiles</strong>. Recibirás una notificación cuando tu plan esté activo.</p>
                    @elseif($pago->estatus == 'aprobado')
                        <p>¡Tu plan ya está activo! Ahora puedes acceder a todas las clases premium, simuladores y materiales de estudio.</p>
                    @elseif($pago->estatus == 'rechazado')
                        <p>Vuelve a subir un comprobante válido para continuar con la activación de tu plan.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Botón volver -->
        <div class="back-button">
            <a href="{{ route('estudiante.dashboard') }}" class="btn-secondary-custom">
                <i class="fas fa-arrow-left me-2"></i>Volver al dashboard
            </a>
        </div>
    @endif
</div>

<!-- Modal para subir comprobante -->
<div class="modal-custom" id="modalSubirComprobante" style="display: none;">
    <div class="modal-custom-overlay"></div>
    <div class="modal-custom-container">
        <div class="modal-custom-content">
            <div class="modal-custom-header">
                <div class="modal-header-left">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <div>
                        <h5>Subir comprobante de pago</h5>
                        <p>Adjunta tu comprobante para validar tu pago</p>
                    </div>
                </div>
                <button class="modal-custom-close" onclick="cerrarModalSubirComprobante()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="formSubirComprobante" action="{{ route('estudiante.subir-comprobante') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-custom-body">
                    <input type="hidden" name="pago_id" id="modalPagoId">
                    
                    <div class="form-group-custom">
                        <label>Referencia del pago</label>
                        <div class="reference-display">
                            <code id="modalPagoRef"></code>
                            <button type="button" onclick="copiarReferencia(document.getElementById('modalPagoRef').innerText)" class="copy-ref-modal">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group-custom">
                        <label>Comprobante de pago <span class="required">*</span></label>
                        <div class="upload-area-custom" id="uploadAreaCustom">
                            <input type="file" name="comprobante" id="comprobanteInput" accept="image/*,.pdf" style="display: none;" required>
                            <div class="upload-content-custom">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <h6>Arrastra o haz clic para subir</h6>
                                <p>JPG, PNG o PDF (Máx. 5MB)</p>
                                <button type="button" class="btn-upload-custom" onclick="document.getElementById('comprobanteInput').click()">
                                    Seleccionar archivo
                                </button>
                            </div>
                            <div class="file-preview" id="filePreview" style="display: none;">
                                <i class="fas fa-file-pdf"></i>
                                <span id="fileName"></span>
                                <button type="button" onclick="limpiarArchivo()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group-custom">
                        <label>Nota adicional (opcional)</label>
                        <textarea name="nota_usuario" class="form-control-custom" rows="3" 
                                  placeholder="Ej: Transferencia realizada el día..."></textarea>
                    </div>
                    
                    <div class="info-alert">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>¿Qué sigue?</strong>
                            <p>Tu pago será revisado por nuestro equipo en un plazo de 24-48 horas hábiles. Recibirás una notificación cuando sea aprobado.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-custom-footer">
                    <button type="button" class="btn-cancel-custom" onclick="cerrarModalSubirComprobante()">Cancelar</button>
                    <button type="submit" class="btn-submit-custom" id="btnEnviarComprobante">
                        <i class="fas fa-paper-plane me-2"></i>Enviar comprobante
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Variables */
:root {
    --primary: #667eea;
    --primary-dark: #5a67d8;
    --secondary: #764ba2;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --dark: #1e293b;
    --gray: #64748b;
    --light: #f8fafc;
    --border: #e2e8f0;
}

/* Wrapper */
.mi-pago-wrapper {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
}

/* Header */
.pago-header {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: 24px;
    padding: 32px;
    margin-bottom: 32px;
    color: white;
}

.pago-header-content {
    text-align: center;
}

.pago-header-icon {
    width: 70px;
    height: 70px;
    background: rgba(255,255,255,0.2);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto 16px;
}

.pago-header-text h1 {
    font-size: 1.8rem;
    font-weight: 800;
    margin-bottom: 8px;
}

.pago-header-text p {
    opacity: 0.9;
    margin: 0;
}

/* Alertas */
.alert-custom {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border-radius: 16px;
    margin-bottom: 24px;
    animation: slideIn 0.3s ease;
}

.alert-success-custom {
    background: #d1fae5;
    color: #065f46;
    border-left: 4px solid #10b981;
}

.alert-error-custom {
    background: #fee2e2;
    color: #991b1b;
    border-left: 4px solid #ef4444;
}

.alert-close {
    margin-left: auto;
    background: none;
    border: none;
    cursor: pointer;
    opacity: 0.6;
}

@keyframes slideIn {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 60px;
    background: white;
    border-radius: 24px;
    border: 1px solid var(--border);
}

.empty-state-icon {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 16px;
}

.empty-state h3 {
    font-size: 1.3rem;
    margin-bottom: 8px;
    color: var(--dark);
}

.empty-state p {
    color: var(--gray);
    margin-bottom: 24px;
}

/* Tarjeta principal */
.pago-card {
    background: white;
    border-radius: 24px;
    border: 1px solid var(--border);
    overflow: hidden;
    margin-bottom: 24px;
}

.pago-card-header {
    padding: 24px;
    background: var(--light);
    border-bottom: 1px solid var(--border);
}

.pago-status {
    display: flex;
    align-items: center;
    gap: 16px;
}

.pago-status i {
    font-size: 2rem;
}

.status-title {
    display: block;
    font-size: 1.2rem;
    font-weight: 700;
}

.status-desc {
    display: block;
    font-size: 0.8rem;
    opacity: 0.8;
}

.status-pending i { color: var(--warning); }
.status-approved i { color: var(--success); }
.status-rejected i { color: var(--danger); }
.status-cancelled i { color: var(--gray); }

.status-pending .status-title { color: var(--warning); }
.status-approved .status-title { color: var(--success); }
.status-rejected .status-title { color: var(--danger); }

.pago-card-body {
    padding: 24px;
}

/* Referencia destacada */
.referencia-destacada {
    background: var(--light);
    border-radius: 16px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.referencia-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.8rem;
    color: var(--gray);
}

.referencia-value {
    display: flex;
    align-items: center;
    gap: 8px;
}

.referencia-value code {
    background: white;
    padding: 8px 16px;
    border-radius: 12px;
    font-family: monospace;
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: 1px;
    border: 1px solid var(--border);
}

.copy-btn {
    background: white;
    border: 1px solid var(--border);
    width: 36px;
    height: 36px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
    color: var(--gray);
}

.copy-btn:hover {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

/* Info grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.7rem;
    color: var(--gray);
    text-transform: uppercase;
}

.info-value {
    font-weight: 600;
    color: var(--dark);
}

.monto {
    font-size: 1.3rem;
    font-weight: 800;
    color: var(--primary);
}

.time-text {
    font-size: 0.7rem;
    font-weight: normal;
    color: var(--gray);
    margin-left: 6px;
}

.warning-text {
    color: var(--warning);
}

.method-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 0.8rem;
    font-weight: 600;
}

.method-transferencia {
    background: #dbeafe;
    color: #1e40af;
}

.method-oxxo {
    background: #fed7aa;
    color: #92400e;
}

.method-card {
    background: #e0e7ff;
    color: #3730a3;
}

/* Comprobante */
.comprobante-section {
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid var(--border);
}

.comprobante-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 16px;
    color: var(--dark);
}

.comprobante-adjunto {
    background: #d1fae5;
    border-radius: 16px;
    padding: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.file-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.file-info i {
    font-size: 2rem;
    color: var(--success);
}

.file-info strong {
    display: block;
    font-size: 0.9rem;
}

.file-info p {
    font-size: 0.7rem;
    margin: 0;
    color: var(--gray);
}

.comprobante-actions {
    display: flex;
    gap: 8px;
}

.action-btn {
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.action-btn.primary {
    background: var(--success);
    color: white;
}

.action-btn.secondary {
    background: var(--gray);
    color: white;
}

.action-btn.outline {
    background: white;
    border: 1px solid var(--border);
    color: var(--gray);
}

.action-btn:hover {
    transform: translateY(-2px);
}

.comprobante-faltante, .comprobante-rechazado {
    background: #fef3c7;
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.comprobante-rechazado {
    background: #fee2e2;
}

.faltante-icon, .rechazado-icon {
    font-size: 2rem;
}

.faltante-icon { color: var(--warning); }
.rechazado-icon { color: var(--danger); }

.faltante-info, .rechazado-info {
    flex: 1;
}

.faltante-info strong, .rechazado-info strong {
    display: block;
    font-size: 0.9rem;
    margin-bottom: 4px;
}

.faltante-info p, .rechazado-info p {
    font-size: 0.75rem;
    margin: 0;
}

.btn-upload {
    background: white;
    border: none;
    padding: 10px 20px;
    border-radius: 30px;
    font-weight: 600;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--primary);
}

.btn-upload:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.comprobante-sin {
    background: var(--light);
    border-radius: 16px;
    padding: 20px;
    text-align: center;
    color: var(--gray);
}

/* Acciones adicionales */
.acciones-adicionales {
    margin-top: 24px;
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-ficha, .btn-contacto {
    padding: 10px 20px;
    border-radius: 30px;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-ficha {
    background: var(--primary);
    color: white;
}

.btn-ficha:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

.btn-contacto {
    background: var(--light);
    border: 1px solid var(--border);
    color: var(--gray);
}

.btn-contacto:hover {
    background: var(--border);
    transform: translateY(-2px);
}

/* Información adicional */
.info-adicional {
    margin-bottom: 24px;
}

.info-card {
    background: #dbeafe;
    border-radius: 16px;
    padding: 16px 20px;
    display: flex;
    gap: 12px;
}

.info-card i {
    font-size: 1.2rem;
    color: var(--primary);
}

.info-card strong {
    display: block;
    font-size: 0.85rem;
    margin-bottom: 4px;
}

.info-card p {
    font-size: 0.75rem;
    margin: 0;
    color: #1e40af;
}

/* Botón volver */
.back-button {
    text-align: center;
}

.btn-secondary-custom {
    display: inline-flex;
    align-items: center;
    padding: 12px 28px;
    background: var(--light);
    border: 1px solid var(--border);
    border-radius: 40px;
    font-weight: 600;
    text-decoration: none;
    color: var(--gray);
    transition: all 0.3s;
}

.btn-secondary-custom:hover {
    background: var(--border);
    transform: translateY(-2px);
    color: var(--dark);
}

.btn-primary-custom {
    display: inline-flex;
    align-items: center;
    padding: 12px 28px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border: none;
    border-radius: 40px;
    font-weight: 600;
    text-decoration: none;
    color: white;
    transition: all 0.3s;
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102,126,234,0.4);
    color: white;
}

/* Modal */
.modal-custom {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1050;
    display: none;
}

.modal-custom-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(8px);
}

.modal-custom-container {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.modal-custom-content {
    position: relative;
    max-width: 500px;
    width: 100%;
    background: white;
    border-radius: 24px;
    animation: modalSlideIn 0.3s ease-out;
    overflow: hidden;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-30px) scale(0.95);
        opacity: 0;
    }
    to {
        transform: translateY(0) scale(1);
        opacity: 1;
    }
}

.modal-custom-header {
    padding: 20px 24px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.modal-header-left i {
    font-size: 1.5rem;
}

.modal-header-left h5 {
    margin: 0;
    font-size: 1rem;
    font-weight: 700;
}

.modal-header-left p {
    margin: 4px 0 0;
    font-size: 0.7rem;
    opacity: 0.8;
}

.modal-custom-close {
    background: rgba(255,255,255,0.2);
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 10px;
    color: white;
    cursor: pointer;
}

.modal-custom-body {
    padding: 24px;
}

.modal-custom-footer {
    padding: 16px 24px;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    background: var(--light);
}

.form-group-custom {
    margin-bottom: 20px;
}

.form-group-custom label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--dark);
}

.required {
    color: var(--danger);
}

.reference-display {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--light);
    padding: 10px 12px;
    border-radius: 12px;
    border: 1px solid var(--border);
}

.reference-display code {
    flex: 1;
    background: none;
}

.upload-area-custom {
    border: 2px dashed var(--border);
    border-radius: 16px;
    padding: 24px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
}

.upload-area-custom:hover {
    border-color: var(--primary);
    background: var(--light);
}

.upload-content-custom i {
    font-size: 2rem;
    color: var(--gray);
    margin-bottom: 12px;
}

.btn-upload-custom {
    background: var(--light);
    border: 1px solid var(--border);
    padding: 8px 20px;
    border-radius: 30px;
    font-size: 0.8rem;
    cursor: pointer;
}

.file-preview {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.form-control-custom {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid var(--border);
    border-radius: 12px;
    font-size: 0.85rem;
}

.form-control-custom:focus {
    outline: none;
    border-color: var(--primary);
}

.info-alert {
    background: #dbeafe;
    border-radius: 12px;
    padding: 16px;
    display: flex;
    gap: 12px;
    margin-top: 20px;
}

.info-alert i {
    color: var(--primary);
}

.btn-cancel-custom {
    background: var(--light);
    border: 1px solid var(--border);
    padding: 10px 24px;
    border-radius: 30px;
    font-weight: 600;
    cursor: pointer;
}

.btn-submit-custom {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border: none;
    padding: 10px 24px;
    border-radius: 30px;
    font-weight: 600;
    color: white;
    cursor: pointer;
}

/* Responsive */
@media (max-width: 768px) {
    .mi-pago-wrapper {
        padding: 12px;
    }
    
    .pago-header {
        padding: 24px;
    }
    
    .pago-header-text h1 {
        font-size: 1.4rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .referencia-destacada {
        flex-direction: column;
        text-align: center;
    }
    
    .comprobante-adjunto {
        flex-direction: column;
        text-align: center;
    }
    
    .comprobante-actions {
        justify-content: center;
    }
    
    .comprobante-faltante, .comprobante-rechazado {
        flex-direction: column;
        text-align: center;
    }
    
    .modal-custom-container {
        padding: 16px;
    }
}

/* Dark mode */
body.dark-mode .pago-card,
body.dark-mode .empty-state {
    background: #1e293b;
    border-color: #334155;
}

body.dark-mode .pago-card-header {
    background: #0f172a;
    border-color: #334155;
}

body.dark-mode .referencia-destacada {
    background: #0f172a;
}

body.dark-mode .referencia-value code {
    background: #1e293b;
    border-color: #334155;
    color: #e2e8f0;
}

body.dark-mode .copy-btn {
    background: #1e293b;
    border-color: #334155;
    color: #e2e8f0;
}

body.dark-mode .info-value {
    color: #f1f5f9;
}

body.dark-mode .empty-state h3 {
    color: #f1f5f9;
}

body.dark-mode .modal-custom-content {
    background: #1e293b;
}

body.dark-mode .modal-custom-footer {
    background: #0f172a;
    border-top-color: #334155;
}

body.dark-mode .form-group-custom label {
    color: #e2e8f0;
}

body.dark-mode .reference-display {
    background: #0f172a;
    border-color: #334155;
}

body.dark-mode .form-control-custom {
    background: #0f172a;
    border-color: #334155;
    color: #e2e8f0;
}

body.dark-mode .btn-cancel-custom {
    background: #334155;
    color: #e2e8f0;
}
</style>

<script>
let modalSubir = null;

function abrirModalSubirComprobante(pagoId, referencia) {
    document.getElementById('modalPagoId').value = pagoId;
    document.getElementById('modalPagoRef').innerText = referencia;
    const modal = document.getElementById('modalSubirComprobante');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarModalSubirComprobante() {
    const modal = document.getElementById('modalSubirComprobante');
    modal.style.display = 'none';
    document.body.style.overflow = '';
    document.getElementById('formSubirComprobante').reset();
    document.getElementById('filePreview').style.display = 'none';
    document.getElementById('uploadAreaCustom').style.display = 'block';
}

function copiarReferencia(referencia) {
    navigator.clipboard.writeText(referencia).then(() => {
        Swal.fire({
            icon: 'success',
            title: '¡Copiado!',
            text: 'Referencia copiada al portapapeles',
            showConfirmButton: false,
            timer: 2000
        });
    });
}

function copiarEnlace(enlace) {
    navigator.clipboard.writeText(enlace).then(() => {
        Swal.fire({
            icon: 'success',
            title: '¡Enlace copiado!',
            showConfirmButton: false,
            timer: 2000
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('comprobanteInput');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('fileName').innerText = file.name;
                document.getElementById('filePreview').style.display = 'flex';
                document.getElementById('uploadAreaCustom').style.display = 'none';
            }
        });
    }
});

function limpiarArchivo() {
    document.getElementById('comprobanteInput').value = '';
    document.getElementById('filePreview').style.display = 'none';
    document.getElementById('uploadAreaCustom').style.display = 'block';
}

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-custom-overlay')) {
        cerrarModalSubirComprobante();
    }
});

document.getElementById('formSubirComprobante')?.addEventListener('submit', function(e) {
    const btn = document.getElementById('btnEnviarComprobante');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando...';
});
</script>
@endsection