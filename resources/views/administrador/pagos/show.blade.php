@extends('administrador.layouts.master')

@section('title', 'Detalle del Pago - SAINS')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Mejorado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="animate__animated animate__fadeInLeft">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #667eea20, #764ba220);">
                            <i class="fas fa-receipt fa-2x" style="background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
                        </div>
                        <div>
                            <h1 class="display-5 fw-bold mb-1" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                                Detalle del Pago
                            </h1>
                            <p class="text-muted fs-5 mb-0">
                                <i class="fas fa-hashtag me-1"></i>Información completa del pago #{{ $pago->id }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="animate__animated animate__fadeInRight">
                    <a href="{{ route('admin.pagos.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4 shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i>Volver al listado
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Columna principal: Información del Pago -->
        <div class="col-lg-8">
            <!-- Tarjeta Principal Mejorada -->
            <div class="card-modern p-4 mb-4 hover-lift">
                <!-- Encabezado con estado mejorado -->
                <div class="d-flex justify-content-between align-items-start mb-4 pb-3 border-bottom-modern">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="rounded-circle p-2" style="background: rgba(102,126,234,0.1);">
                                <i class="fas fa-receipt text-primary"></i>
                            </div>
                            <h3 class="fw-bold mb-0">Pago #{{ $pago->id }}</h3>
                        </div>
                    </div>
                    <div>
                        @php
                            $estadoConfig = [
                                'pendiente' => ['color' => '#f39c12', 'bg' => '#f39c1220', 'icon' => 'clock', 'text' => 'Pendiente'],
                                'aprobado' => ['color' => '#27ae60', 'bg' => '#27ae6020', 'icon' => 'check-circle', 'text' => 'Aprobado'],
                                'rechazado' => ['color' => '#e74c3c', 'bg' => '#e74c3c20', 'icon' => 'times-circle', 'text' => 'Rechazado'],
                                'cancelado' => ['color' => '#95a5a6', 'bg' => '#95a5a620', 'icon' => 'ban', 'text' => 'Cancelado']
                            ];
                            $config = $estadoConfig[$pago->estatus] ?? $estadoConfig['pendiente'];
                        @endphp
                        <span class="badge-estado-modern" style="background: {{ $config['bg'] }}; color: {{ $config['color'] }}; border: 1px solid {{ $config['color'] }}40;">
                            <i class="fas fa-{{ $config['icon'] }} me-2"></i>
                            {{ $config['text'] }}
                        </span>
                    </div>
                </div>

                <!-- Grid de Información Mejorada -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="info-card-modern">
                            <div class="info-icon" style="background: linear-gradient(135deg, #667eea20, #764ba220);">
                                <i class="fas fa-tag" style="color: #667eea;"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Tipo de Pago</span>
                                <span class="info-value">{{ $pago->tipo_pago }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-card-modern">
                            <div class="info-icon" style="background: #f39c1220;">
                                <i class="fas fa-calendar-alt" style="color: #f39c12;"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Fecha de Pago</span>
                                <span class="info-value">
                                    {{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-card-modern highlight-monto">
                            <div class="info-icon" style="background: #27ae6020;">
                                <i class="fas fa-dollar-sign" style="color: #27ae60;"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Monto</span>
                                <span class="info-value monto">${{ number_format($pago->monto_pago, 2) }} <small style="font-size: 0.8rem;">MXN</small></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-card-modern">
                            <div class="info-icon" style="background: #3498db20;">
                                <i class="fas fa-barcode" style="color: #3498db;"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Referencia</span>
                                <span class="info-value font-monospace">{{ $pago->referencia_pago ?? 'No especificada' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="info-card-modern">
                            <div class="info-icon" style="background: #9b59b620;">
                                <i class="fas fa-user-graduate" style="color: #9b59b6;"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Estudiante</span>
                                <span class="info-value fw-bold">{{ $pago->alumno ? $pago->alumno->nombre_completo : 'No especificado' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    @if($pago->nota_usuario)
                    <div class="col-12">
                        <div class="info-card-modern nota-card">
                            <div class="info-icon" style="background: #ff980020;">
                                <i class="fas fa-sticky-note" style="color: #ff9800;"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Nota / Observación</span>
                                <span class="info-value">{{ $pago->nota_usuario }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Información de Aprobación/Rechazo Mejorada -->
                @if($pago->estatus == 'aprobado' || $pago->estatus == 'rechazado')
                <div class="mt-4 pt-3 border-top-modern">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-user-check text-primary me-2"></i>Información de Revisión
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-card-modern revision-card">
                                <div class="info-icon small-icon" style="background: #27ae6020;">
                                    <i class="fas fa-user-check" style="color: #27ae60;"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Revisado por</span>
                                    <span class="info-value">{{ $pago->revisor ? ($pago->revisor->administrador->nombre_completo ?? $pago->revisor->correo) : 'Sistema' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card-modern revision-card">
                                <div class="info-icon small-icon" style="background: #27ae6020;">
                                    <i class="fas fa-calendar-check" style="color: #27ae60;"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Fecha de Revisión</span>
                                    <span class="info-value">
                                        {{ $pago->fecha_aprueba ? \Carbon\Carbon::parse($pago->fecha_aprueba)->format('d/m/Y H:i:s') : 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Columna derecha: Comprobante y Acciones Mejorada -->
        <div class="col-lg-4">
            <!-- Tarjeta del Comprobante Mejorada -->
            <div class="card-modern p-4 mb-4 hover-lift">
                <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom-modern">
                    <div class="rounded-circle p-2" style="background: linear-gradient(135deg, #27ae6020, #20c99720);">
                        <i class="fas fa-image fa-2x" style="color: #27ae60;"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-semibold">Comprobante de Pago</h5>
                        <p class="text-muted small mb-0">Documento adjunto</p>
                    </div>
                </div>
                
                @php
                    use Illuminate\Support\Facades\Storage;
                    
                    $existeArchivo = $pago->comprobante && Storage::disk('public')->exists($pago->comprobante);
                    $urlArchivo = $existeArchivo ? Storage::url($pago->comprobante) : null;
                @endphp
                
                @if($existeArchivo)
                    <div class="comprobante-container text-center">
                        <div class="comprobante-preview mb-3" onclick="verImagenCompleta('{{ $urlArchivo }}')">
                            @php
                                $extension = pathinfo($pago->comprobante, PATHINFO_EXTENSION);
                            @endphp
                            @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                <img src="{{ $urlArchivo }}" alt="Comprobante" class="img-fluid rounded-3 shadow-sm">
                                <div class="preview-overlay">
                                    <i class="fas fa-search-plus fa-3x"></i>
                                    <span class="mt-2">Ver completo</span>
                                </div>
                            @else
                                <div class="pdf-preview">
                                    <i class="fas fa-file-pdf fa-5x text-danger"></i>
                                    <p class="mt-2 fw-semibold">Documento PDF</p>
                                    <small class="text-muted">Haga clic para visualizar</small>
                                </div>
                            @endif
                        </div>
                        
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <a href="{{ $urlArchivo }}" target="_blank" class="btn btn-primary btn-sm rounded-pill px-3">
                                <i class="fas fa-eye me-1"></i>Ver
                            </a>
                            <a href="{{ $urlArchivo }}" download class="btn btn-success btn-sm rounded-pill px-3">
                                <i class="fas fa-download me-1"></i>Descargar
                            </a>
                            <button type="button" class="btn btn-info btn-sm rounded-pill px-3" onclick="copiarEnlace('{{ $urlArchivo }}')">
                                <i class="fas fa-link me-1"></i>Copiar
                            </button>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-file-image fa-4x text-muted mb-3"></i>
                            <p class="text-muted mb-2">No se ha subido ningún comprobante</p>
                            @if($pago->comprobante)
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-database me-1"></i>Archivo esperado: {{ $pago->comprobante }}
                                </small>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Tarjeta de Acciones Mejorada CON BOTONES DE APROBAR/RECHAZAR -->
            <div class="card-modern p-4 hover-lift">
                <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom-modern">
                    <div class="rounded-circle p-2" style="background: linear-gradient(135deg, #667eea20, #764ba220);">
                        <i class="fas fa-cog fa-2x" style="color: #667eea;"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-semibold">Acciones Rápidas</h5>
                        <p class="text-muted small mb-0">Gestionar este pago</p>
                    </div>
                </div>
                
                <!-- BOTONES DE APROBAR Y RECHAZAR (solo si está pendiente) -->
                @if($pago->estatus == 'pendiente')
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <form action="{{ route('admin.pagos.aprobar', $pago->id) }}" method="POST" class="d-inline w-100">
                            @csrf
                            <button type="submit" class="btn-accion btn-aprobar w-100" onclick="return confirmarAprobacion(event)">
                                <i class="fas fa-check-circle me-2"></i>Aprobar Pago
                            </button>
                        </form>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn-accion btn-rechazar w-100" onclick="abrirModalRechazo({{ $pago->id }})">
                            <i class="fas fa-times-circle me-2"></i>Rechazar Pago
                        </button>
                    </div>
                </div>
                @endif
                
                <div class="d-grid gap-3">
                    <a href="{{ route('admin.pagos.edit', $pago->id) }}" class="btn-accion btn-editar">
                        <i class="fas fa-edit me-2"></i>Editar Pago
                    </a>
                    @if($pago->estatus != 'aprobado')
                        <button type="button" class="btn-accion btn-eliminar" onclick="eliminarPago({{ $pago->id }})">
                            <i class="fas fa-trash-alt me-2"></i>Eliminar Pago
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver imagen completa -->
<div class="modal fade" id="modalImagen" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content modal-modern">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-receipt text-primary me-2"></i>Comprobante de Pago #{{ $pago->id }}
                </h5>
                <button type="button" class="btn-close-modern" data-bs-dismiss="modal">×</button>
            </div>
            <div class="modal-body text-center p-4 bg-light">
                <img id="imagenModal" src="" alt="Comprobante" class="img-fluid rounded-3 shadow-lg" style="max-height: 70vh;">
                <div id="pdfModalContainer" class="d-none w-100">
                    <iframe id="pdfModalFrame" src="" style="width: 100%; height: 70vh;" frameborder="0"></iframe>
                </div>
            </div>
            <div class="modal-footer border-0 pb-4 px-4">
                <a id="btnDescargarModal" href="#" download class="btn btn-success rounded-pill px-4">
                    <i class="fas fa-download me-2"></i>Descargar
                </a>
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========== MODAL DE RECHAZO MEJORADO - MÁS ANCHO ========== -->
<div class="modal fade" id="modalMotivoRechazo" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-modern modal-rechazo">
            <form action="" method="POST" id="formRechazo">
                @csrf
                <input type="hidden" name="motivo_rechazo" id="motivoRechazoInput">
                
                <div class="modal-header-rechazo">
                    <div class="modal-header-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="modal-header-text">
                        <h4 class="mb-1">Rechazar Pago</h4>
                        <p class="mb-0">Indique el motivo del rechazo para notificar al estudiante</p>
                    </div>
                    <button type="button" class="btn-close-modern" data-bs-dismiss="modal">×</button>
                </div>
                
                <div class="modal-body p-4">
                    <!-- Información del pago a rechazar -->
                    <div class="pago-info-card mb-4">
                        <div class="row g-3">
                            <div class="col-md-3 col-6">
                                <div class="info-item">
                                    <i class="fas fa-hashtag text-muted"></i>
                                    <span><strong>Pago #</strong> <span id="rechazoPagoId">-</span></span>
                                </div>
                            </div>
                            <div class="col-md-5 col-6">
                                <div class="info-item">
                                    <i class="fas fa-user text-muted"></i>
                                    <span><strong>Estudiante:</strong> {{ $pago->alumno ? $pago->alumno->nombre_completo : 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="info-item">
                                    <i class="fas fa-dollar-sign text-muted"></i>
                                    <span><strong>Monto:</strong> ${{ number_format($pago->monto_pago, 2) }}</span>
                                </div>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="info-item">
                                    <i class="fas fa-calendar text-muted"></i>
                                    <span><strong>Fecha:</strong> {{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Motivos predefinidos - Grid de 2 columnas -->
                    <div class="motivos-predefinidos mb-4">
                        <label class="form-label fw-semibold mb-3">
                            <i class="fas fa-list me-2 text-primary"></i>Seleccione un motivo común:
                        </label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <button type="button" class="btn-motivo" data-motivo="Comprobante ilegible o dañado">
                                    <i class="fas fa-file-image"></i> Comprobante ilegible o dañado
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn-motivo" data-motivo="El monto pagado no corresponde al curso ($800 MXN)">
                                    <i class="fas fa-dollar-sign"></i> Monto incorrecto
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn-motivo" data-motivo="No se encontró el registro del pago en el banco">
                                    <i class="fas fa-search"></i> No se encontró el registro
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn-motivo" data-motivo="Los datos del comprobante no coinciden con el estudiante">
                                    <i class="fas fa-user-check"></i> Datos no coinciden
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn-motivo" data-motivo="Comprobante fuera de fecha límite de pago">
                                    <i class="fas fa-calendar-times"></i> Fuera de fecha límite
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn-motivo" data-motivo="El comprobante no corresponde al estudiante registrado">
                                    <i class="fas fa-id-card"></i> Comprobante de otra persona
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Campo para motivo personalizado -->
                    <div class="motivo-custom">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-pencil-alt me-2 text-primary"></i>O escriba su propio motivo:
                        </label>
                        <textarea id="motivoRechazoTextarea" class="form-control-textarea" rows="3" placeholder="Ej: El comprobante está incompleto, falta la fecha de pago..."></textarea>
                        <div class="form-text mt-2">
                            <i class="fas fa-info-circle"></i> Este motivo será notificado al estudiante por correo electrónico
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer-rechazo">
                    <button type="button" class="btn-cancelar" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn-confirmar-rechazo" onclick="confirmarRechazo()">
                        <i class="fas fa-check-circle me-2"></i>Rechazar Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Animaciones */
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
    }
    
    /* Card Modern */
    .card-modern {
        background: white;
        border-radius: 24px;
        transition: all 0.3s ease;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    
    /* Borders */
    .border-bottom-modern {
        border-bottom: 2px solid rgba(102,126,234,0.15);
    }
    
    .border-top-modern {
        border-top: 2px solid rgba(102,126,234,0.15);
    }
    
    /* Info Card Modern */
    .info-card-modern {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 16px;
        transition: all 0.2s ease;
        border: 1px solid rgba(0,0,0,0.03);
    }
    
    .info-card-modern:hover {
        transform: translateX(5px);
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-color: rgba(102,126,234,0.2);
    }
    
    .info-icon {
        width: 45px;
        height: 45px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }
    
    .info-icon.small-icon {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
    
    .info-content {
        flex: 1;
    }
    
    .info-label {
        display: block;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .info-value.monto {
        font-size: 1.4rem;
        color: #27ae60;
    }
    
    .nota-card {
        background: linear-gradient(135deg, #fff8e1 0%, #fff3e0 100%);
        border-left: 4px solid #ff9800;
    }
    
    /* Badge Estado */
    .badge-estado-modern {
        display: inline-flex;
        align-items: center;
        padding: 10px 24px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        backdrop-filter: blur(10px);
    }
    
    /* Comprobante Preview */
    .comprobante-container {
        position: relative;
    }
    
    .comprobante-preview {
        position: relative;
        cursor: pointer;
        overflow: hidden;
        border-radius: 16px;
    }
    
    .comprobante-preview img {
        width: 100%;
        max-height: 250px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .comprobante-preview:hover img {
        transform: scale(1.05);
    }
    
    .preview-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        color: white;
        border-radius: 16px;
    }
    
    .comprobante-preview:hover .preview-overlay {
        opacity: 1;
    }
    
    .pdf-preview {
        background: #f8f9fa;
        padding: 2rem;
        border-radius: 16px;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .pdf-preview:hover {
        background: #e9ecef;
        transform: scale(1.02);
    }
    
    .empty-state {
        padding: 2rem;
        background: #f8f9fa;
        border-radius: 16px;
    }
    
    /* Botones de Acción */
    .btn-accion {
        padding: 12px 20px;
        border: none;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-accion:hover {
        transform: translateY(-3px);
    }
    
    .btn-aprobar {
        background: linear-gradient(135deg, #27ae60, #20c997);
        color: white;
    }
    
    .btn-aprobar:hover {
        box-shadow: 0 8px 25px rgba(39, 174, 96, 0.4);
        color: white;
    }
    
    .btn-rechazar {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: white;
    }
    
    .btn-rechazar:hover {
        box-shadow: 0 8px 25px rgba(231, 76, 60, 0.4);
        color: white;
    }
    
    .btn-editar {
        background: linear-gradient(135deg, #f39c12, #e67e22);
        color: white;
    }
    
    .btn-editar:hover {
        box-shadow: 0 8px 25px rgba(243, 156, 18, 0.4);
        color: white;
    }
    
    .btn-eliminar {
        background: linear-gradient(135deg, #7f8c8d, #6c5ce7);
        color: white;
    }
    
    .btn-eliminar:hover {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        box-shadow: 0 8px 25px rgba(231, 76, 60, 0.4);
        color: white;
    }
    
    /* ========== MODAL DE RECHAZO MEJORADO ========== */
    .modal-rechazo {
        overflow: hidden;
    }
    
    .modal-header-rechazo {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        padding: 1.5rem 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
    }
    
    .modal-header-icon {
        width: 55px;
        height: 55px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modal-header-icon i {
        font-size: 2rem;
        color: white;
    }
    
    .modal-header-text {
        flex: 1;
    }
    
    .modal-header-text h4 {
        color: white;
        font-weight: 700;
        font-size: 1.4rem;
        margin-bottom: 0.25rem;
    }
    
    .modal-header-text p {
        color: rgba(255,255,255,0.9);
        font-size: 0.85rem;
        margin: 0;
    }
    
    .modal-header-rechazo .btn-close-modern {
        color: white;
        font-size: 1.5rem;
        background: rgba(255,255,255,0.1);
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modal-header-rechazo .btn-close-modern:hover {
        color: #ffeb3b;
        background: rgba(255,255,255,0.2);
    }
    
    .pago-info-card {
        background: #f8f9fa;
        border-radius: 16px;
        padding: 1rem;
        border: 1px solid #e9ecef;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        padding: 0.25rem 0;
    }
    
    .info-item i {
        width: 20px;
    }
    
    .motivos-predefinidos {
        background: #f0f4ff;
        border-radius: 16px;
        padding: 1.25rem;
    }
    
    .btn-motivo {
        width: 100%;
        text-align: left;
        padding: 0.75rem 1rem;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .btn-motivo:hover {
        background: #667eea;
        border-color: #667eea;
        color: white;
        transform: translateX(5px);
    }
    
    .btn-motivo:hover i {
        color: white;
    }
    
    .btn-motivo i {
        color: #667eea;
        width: 20px;
        font-size: 1rem;
    }
    
    .btn-motivo.selected {
        background: #667eea;
        border-color: #667eea;
        color: white;
    }
    
    .btn-motivo.selected i {
        color: white;
    }
    
    .motivo-custom {
        margin-top: 1rem;
    }
    
    .form-control-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        resize: vertical;
        font-family: inherit;
    }
    
    .form-control-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    }
    
    .modal-footer-rechazo {
        padding: 1rem 1.5rem 1.5rem;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        border-top: 1px solid #e2e8f0;
    }
    
    .btn-cancelar {
        background: #f1f5f9;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        color: #64748b;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .btn-cancelar:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }
    
    .btn-confirmar-rechazo {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        border: none;
        padding: 0.75rem 1.8rem;
        border-radius: 50px;
        font-weight: 600;
        color: white;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .btn-confirmar-rechazo:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
    }
    
    /* Modal Modern */
    .modal-modern {
        background: white;
        border-radius: 28px;
        border: none;
    }
    
    .btn-close-modern {
        background: none;
        border: none;
        font-size: 1.8rem;
        cursor: pointer;
        color: #6c757d;
        transition: all 0.2s ease;
    }
    
    .btn-close-modern:hover {
        color: #e74c3c;
        transform: scale(1.1);
    }
    
    /* Dark Mode */
    body.dark-mode .card-modern {
        background: #1a1a2e;
    }
    
    body.dark-mode .info-card-modern {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        border-color: rgba(255,255,255,0.05);
    }
    
    body.dark-mode .info-card-modern:hover {
        background: linear-gradient(135deg, #1f1f3a 0%, #1a1a2e 100%);
    }
    
    body.dark-mode .info-value {
        color: #e0e0e0;
    }
    
    body.dark-mode .info-label {
        color: #a0a0a0;
    }
    
    body.dark-mode .nota-card {
        background: linear-gradient(135deg, #1a237e 0%, #0d1b3e 100%);
    }
    
    body.dark-mode .pdf-preview,
    body.dark-mode .empty-state {
        background: #0f0f1a;
    }
    
    body.dark-mode .modal-modern {
        background: #1a1a2e;
    }
    
    body.dark-mode .modal-body.bg-light {
        background: #0f0f1a !important;
    }
    
    body.dark-mode .btn-close-modern {
        color: #a0a0a0;
    }
    
    body.dark-mode .border-bottom-modern {
        border-bottom-color: rgba(102,126,234,0.2);
    }
    
    body.dark-mode .border-top-modern {
        border-top-color: rgba(102,126,234,0.2);
    }
    
    body.dark-mode .pago-info-card {
        background: #0f0f1a;
        border-color: #1e293b;
    }
    
    body.dark-mode .motivos-predefinidos {
        background: #0f172a;
    }
    
    body.dark-mode .btn-motivo {
        background: #1a1a2e;
        border-color: #334155;
        color: #e0e0e0;
    }
    
    body.dark-mode .btn-motivo:hover {
        background: #667eea;
        border-color: #667eea;
        color: white;
    }
    
    body.dark-mode .form-control-textarea {
        background: #0f0f1a;
        border-color: #334155;
        color: #e0e0e0;
    }
    
    body.dark-mode .modal-footer-rechazo {
        border-top-color: #334155;
    }
    
    body.dark-mode .btn-cancelar {
        background: #334155;
        color: #e0e0e0;
    }
    
    body.dark-mode .btn-cancelar:hover {
        background: #475569;
    }
</style>
@endpush

@push('scripts')
<script>
    let pagoIdActual = null;
    
    function verImagenCompleta(url) {
        const extension = url.split('.').pop().toLowerCase();
        const isPDF = extension === 'pdf';
        
        const imgModal = document.getElementById('imagenModal');
        const pdfModalContainer = document.getElementById('pdfModalContainer');
        const pdfModalFrame = document.getElementById('pdfModalFrame');
        
        if (isPDF) {
            imgModal.classList.add('d-none');
            pdfModalContainer.classList.remove('d-none');
            pdfModalFrame.src = url;
        } else {
            imgModal.classList.remove('d-none');
            pdfModalContainer.classList.add('d-none');
            imgModal.src = url;
        }
        
        document.getElementById('btnDescargarModal').href = url;
        new bootstrap.Modal(document.getElementById('modalImagen')).show();
    }
    
    function copiarEnlace(url) {
        navigator.clipboard.writeText(url).then(() => {
            Swal.fire({
                title: '¡Enlace copiado!',
                text: 'El enlace del comprobante ha sido copiado al portapapeles',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#2c3e50'
            });
        });
    }
    
    // Confirmar aprobación
    function confirmarAprobacion(event) {
        event.preventDefault();
        
        Swal.fire({
            title: 'Aprobar Pago',
            text: '¿Está seguro de aprobar este pago? El estudiante recibirá acceso al curso.',
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#27ae60',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, aprobar',
            cancelButtonText: 'Cancelar',
            background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#2c3e50'
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.closest('form').submit();
            }
        });
        
        return false;
    }
    
    // Función para abrir el modal de rechazo mejorado
    function abrirModalRechazo(id) {
        pagoIdActual = id;
        document.getElementById('rechazoPagoId').innerHTML = id;
        document.getElementById('motivoRechazoTextarea').value = '';
        
        // Resetear estilos de los botones de motivo
        document.querySelectorAll('.btn-motivo').forEach(btn => {
            btn.classList.remove('selected');
        });
        
        // Configurar la acción del formulario
        const form = document.getElementById('formRechazo');
        form.action = `/administrador/pagos/${id}/rechazar`;
        
        new bootstrap.Modal(document.getElementById('modalMotivoRechazo')).show();
    }
    
    // Seleccionar un motivo predefinido
    document.querySelectorAll('.btn-motivo').forEach(btn => {
        btn.addEventListener('click', function() {
            const motivo = this.getAttribute('data-motivo');
            const textarea = document.getElementById('motivoRechazoTextarea');
            textarea.value = motivo;
            
            // Resaltar el botón seleccionado
            document.querySelectorAll('.btn-motivo').forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
            
            // Animar el textarea
            textarea.style.transform = 'scale(1.02)';
            setTimeout(() => {
                textarea.style.transform = '';
            }, 200);
        });
    });
    
    function confirmarRechazo() {
        const motivo = document.getElementById('motivoRechazoTextarea').value.trim();
        
        if (!motivo) {
            Swal.fire({
                title: 'Motivo requerido',
                text: 'Por favor, indique el motivo del rechazo para notificar al estudiante',
                icon: 'warning',
                confirmButtonColor: '#e74c3c',
                background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#2c3e50'
            });
            return;
        }
        
        // Asignar el motivo al input hidden
        document.getElementById('motivoRechazoInput').value = motivo;
        
        Swal.fire({
            title: 'Rechazar Pago',
            html: `
                <div style="text-align: left;">
                    <p><strong>¿Está seguro de rechazar este pago?</strong></p>
                    <p style="color: #e74c3c; font-size: 0.9rem; background: rgba(231,76,60,0.1); padding: 0.5rem; border-radius: 8px;">
                        <i class="fas fa-comment me-2"></i> "${motivo}"
                    </p>
                    <p class="text-muted" style="font-size: 0.8rem; margin-top: 0.5rem;">
                        <i class="fas fa-envelope me-1"></i> El estudiante recibirá una notificación con este motivo.
                    </p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check-circle me-2"></i>Sí, rechazar',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar',
            background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#2c3e50'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formRechazo').submit();
            }
        });
    }
    
    function eliminarPago(id) {
        Swal.fire({
            title: 'Eliminar Pago',
            text: '¿Está seguro de eliminar este pago? Esta acción no se puede deshacer.',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#2c3e50'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/administrador/pagos/${id}`;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush