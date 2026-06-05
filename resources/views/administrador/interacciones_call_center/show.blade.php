@extends('administrador.layouts.master')

@section('title', 'Detalles de Interacción - Call Center - SAINS')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="display-5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                    <i class="fas fa-headset me-3"></i>Detalles de Interacción
                </h1>
                <p class="text-muted">Información completa de la comunicación con el estudiante</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.callcenter.edit', $interaccion->id) }}" class="btn btn-warning rounded-pill px-4">
                    <i class="fas fa-edit me-2"></i>Editar
                </a>
                <a href="{{ route('admin.callcenter.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i>Volver al listado
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-modern p-4">
            <!-- Perfil Header -->
            <div class="d-flex flex-column flex-md-row align-items-center gap-4 mb-5 pb-3 border-bottom">
                <div class="avatar-perfil">
                    <i class="fas fa-headset fa-4x"></i>
                </div>
                <div class="text-center text-md-start">
                    <h2 class="mb-1 fw-bold">Interacción #{{ $interaccion->id }}</h2>
                    <p class="text-muted mb-2">
                        <i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($interaccion->created_at)->translatedFormat('d \\d\\e F \\d\\e Y \\a \\l\\a\\s h:i A') }}
                    </p>
                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                        @php
                            $tipoConfig = [
                                'llamada' => ['icon' => 'fa-phone', 'text' => 'Llamada Telefónica', 'class' => 'badge-llamada'],
                                'email' => ['icon' => 'fa-envelope', 'text' => 'Correo Electrónico', 'class' => 'badge-email'],
                                'whatsapp' => ['icon' => 'fa-whatsapp', 'text' => 'WhatsApp', 'class' => 'badge-whatsapp']
                            ];
                            $tipo = $tipoConfig[$interaccion->tipo_contacto] ?? $tipoConfig['llamada'];
                        @endphp
                        <span class="badge-tipo {{ $tipo['class'] }}">
                            <i class="fas {{ $tipo['icon'] }} me-1"></i> {{ $tipo['text'] }}
                        </span>
                        @php
                            $estadoConfig = [
                                'pendiente' => ['icon' => 'fa-clock', 'text' => 'Pendiente', 'class' => 'badge-pendiente'],
                                'en_proceso' => ['icon' => 'fa-sync-alt', 'text' => 'En Proceso', 'class' => 'badge-proceso'],
                                'finalizado' => ['icon' => 'fa-check-circle', 'text' => 'Finalizado', 'class' => 'badge-finalizado']
                            ];
                            $estado = $estadoConfig[$interaccion->estado_seguimiento] ?? $estadoConfig['pendiente'];
                        @endphp
                        <span class="badge-estado {{ $estado['class'] }}">
                            <i class="fas {{ $estado['icon'] }} me-1"></i> {{ $estado['text'] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 1: INFORMACIÓN DEL ESTUDIANTE -->
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="info-section">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="fas fa-user-graduate me-2"></i>Información del Estudiante
                        </h5>
                        @php
                            $estudiante = $interaccion->estudiante;
                            $detalleEstudiante = $estudiante ? $estudiante->estudiante : null;
                            $nombreCompleto = $detalleEstudiante 
                                ? trim($detalleEstudiante->nombre . ' ' . $detalleEstudiante->paterno . ' ' . $detalleEstudiante->materno)
                                : ($estudiante->name ?? 'No registrado');
                            
                            // Obtener universidad y carrera de interés
                            $universidadInteres = $detalleEstudiante ? $detalleEstudiante->universidadInteres : null;
                            $carreraInteres = $universidadInteres ? $universidadInteres->carrera : null;
                        @endphp
                        
                        <!-- Datos personales del estudiante -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-card-icon" style="background: rgba(102,126,234,0.1);">
                                        <i class="fas fa-user" style="color: #667eea;"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <label>Nombre completo</label>
                                        <span class="fw-semibold">{{ $nombreCompleto }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-card-icon" style="background: rgba(16,185,129,0.1);">
                                        <i class="fas fa-envelope" style="color: #10b981;"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <label>Correo electrónico</label>
                                        <span>{{ $estudiante->correo ?? 'No registrado' }}</span>
                                    </div>
                                </div>
                            </div>
                            @if($detalleEstudiante)
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-card-icon" style="background: rgba(245,158,11,0.1);">
                                        <i class="fas fa-phone-alt" style="color: #f59e0b;"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <label>Teléfono</label>
                                        <span>{{ $detalleEstudiante->telefono ?? 'No registrado' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-card-icon" style="background: rgba(139,92,246,0.1);">
                                        <i class="fas fa-check-circle" style="color: #8b5cf6;"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <label>Plan activo</label>
                                        <span>
                                            @if($detalleEstudiante->plan_activo)
                                                <span class="badge-plan"><i class="fas fa-check-circle"></i> Activo</span>
                                            @else
                                                <span class="badge-plan-inactive"><i class="fas fa-times-circle"></i> Inactivo</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @if($detalleEstudiante->escuelaProcedencia)
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-card-icon" style="background: rgba(59,130,246,0.1);">
                                        <i class="fas fa-school" style="color: #3b82f6;"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <label>Escuela de procedencia</label>
                                        <span>{{ $detalleEstudiante->escuelaProcedencia->centro_educativo ?? 'No especificada' }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($universidadInteres)
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-card-icon" style="background: rgba(16,185,129,0.1);">
                                        <i class="fas fa-university" style="color: #10b981;"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <label>Universidad de interés</label>
                                        <span>{{ $universidadInteres->clave ?? 'No especificada' }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($carreraInteres)
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-card-icon" style="background: rgba(139,92,246,0.1);">
                                        <i class="fas fa-graduation-cap" style="color: #8b5cf6;"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <label>Carrera de interés</label>
                                        <span>{{ $carreraInteres->nombre ?? 'No especificada' }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: INFORMACIÓN DEL ASESOR -->
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="info-section">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="fas fa-user-tie me-2"></i>Información del Asesor
                        </h5>
                        @php
                            $asesor = $interaccion->administrador;
                            $detalleAsesor = $asesor ? $asesor->administrador : null;
                            $nombreAsesor = $detalleAsesor 
                                ? trim($detalleAsesor->nombre . ' ' . $detalleAsesor->apellido_paterno . ' ' . $detalleAsesor->apellido_materno)
                                : ($asesor->name ?? 'No registrado');
                        @endphp
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-card-icon" style="background: rgba(102,126,234,0.1);">
                                        <i class="fas fa-user" style="color: #667eea;"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <label>Nombre completo</label>
                                        <span class="fw-semibold">{{ $nombreAsesor }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-card-icon" style="background: rgba(16,185,129,0.1);">
                                        <i class="fas fa-envelope" style="color: #10b981;"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <label>Correo electrónico</label>
                                        <span>{{ $asesor->correo ?? 'No registrado' }}</span>
                                    </div>
                                </div>
                            </div>
                            @if($detalleAsesor)
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-card-icon" style="background: rgba(245,158,11,0.1);">
                                        <i class="fas fa-phone-alt" style="color: #f59e0b;"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <label>Teléfono</label>
                                        <span>{{ $detalleAsesor->telefono ?? 'No registrado' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-card-icon" style="background: rgba(139,92,246,0.1);">
                                        <i class="fas fa-user-tag" style="color: #8b5cf6;"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <label>Rol</label>
                                        <span>{{ ucfirst($asesor->rol ?? 'Administrador') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 3: DETALLES DE LA INTERACCIÓN -->
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="info-section">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="fas fa-info-circle me-2"></i>Detalles de la Interacción
                        </h5>
                        
                        <!-- Fecha y Hora de contacto -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-card-icon" style="background: rgba(102,126,234,0.1);">
                                        <i class="fas fa-calendar-day" style="color: #667eea;"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <label>Fecha de contacto</label>
                                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($interaccion->fecha_contacto)->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-card-icon" style="background: rgba(245,158,11,0.1);">
                                        <i class="fas fa-clock" style="color: #f59e0b;"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <label>Hora de contacto</label>
                                        <span>{{ \Carbon\Carbon::parse($interaccion->hora_contacto)->format('h:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                            @if($interaccion->proximo_contacto)
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-card-icon" style="background: rgba(139,92,246,0.1);">
                                        <i class="fas fa-calendar-week" style="color: #8b5cf6;"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <label>Próximo contacto</label>
                                        <span>{{ \Carbon\Carbon::parse($interaccion->proximo_contacto)->format('d/m/Y') }}
                                        @if($interaccion->proximo_contacto_hora)
                                            <span class="text-muted ms-1">a las {{ \Carbon\Carbon::parse($interaccion->proximo_contacto_hora)->format('h:i A') }}</span>
                                        @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Motivo -->
                        <div class="detail-card mb-3">
                            <div class="detail-header">
                                <div class="detail-icon" style="background: rgba(59,130,246,0.1);">
                                    <i class="fas fa-comment-dots" style="color: #3b82f6;"></i>
                                </div>
                                <h6 class="mb-0 fw-semibold">Motivo de Contacto</h6>
                            </div>
                            <p class="detail-content">{{ $interaccion->motivo_contacto ?? 'No especificado' }}</p>
                        </div>

                        <!-- Nota del Asesor -->
                        @if($interaccion->nota)
                        <div class="detail-card mb-3">
                            <div class="detail-header">
                                <div class="detail-icon" style="background: rgba(139,92,246,0.1);">
                                    <i class="fas fa-sticky-note" style="color: #8b5cf6;"></i>
                                </div>
                                <h6 class="mb-0 fw-semibold">Nota del Asesor</h6>
                            </div>
                            <p class="detail-content">{{ nl2br(e($interaccion->nota)) }}</p>
                        </div>
                        @endif

                        <!-- Resultado -->
                        @if($interaccion->resultado)
                        <div class="detail-card success-card">
                            <div class="detail-header">
                                <div class="detail-icon" style="background: rgba(16,185,129,0.1);">
                                    <i class="fas fa-chart-line" style="color: #10b981;"></i>
                                </div>
                                <h6 class="mb-0 fw-semibold">Resultado de la Interacción</h6>
                            </div>
                            <p class="detail-content text-success fw-semibold">
                                <i class="fas fa-check-circle me-2"></i>{{ $interaccion->resultado }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="d-flex justify-content-center gap-3 mt-5 pt-4 border-top">
                <button onclick="eliminarInteraccion({{ $interaccion->id }}, '{{ addslashes($nombreCompleto) }}')" 
                        class="btn btn-danger px-5 py-3 rounded-pill">
                    <i class="fas fa-trash-alt me-2"></i> Eliminar Interacción
                </button>
                <a href="{{ route('admin.callcenter.index') }}" class="btn btn-secondary px-5 py-3 rounded-pill">
                    <i class="fas fa-arrow-left me-2"></i> Volver al listado
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-modern {
        background: white;
        border-radius: 24px;
        transition: transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    
    .card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(67,97,238,0.15);
    }
    
    .avatar-perfil {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #667eea20, #764ba220);
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #667eea;
    }
    
    .info-section {
        background: #f8fafc;
        border-radius: 20px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .info-section:hover {
        background: #f1f5f9;
        transform: translateY(-3px);
    }
    
    /* Tarjetas de información */
    .info-card {
        background: white;
        border-radius: 16px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    
    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border-color: #cbd5e1;
    }
    
    .info-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    
    .info-card-content {
        flex: 1;
    }
    
    .info-card-content label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        margin-bottom: 0.25rem;
        display: block;
    }
    
    .info-card-content span {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1e293b;
        word-break: break-word;
    }
    
    /* Badges */
    .badge-tipo, .badge-estado {
        padding: 6px 16px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .badge-llamada {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        box-shadow: 0 2px 4px rgba(59,130,246,0.3);
    }
    
    .badge-email {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 2px 4px rgba(16,185,129,0.3);
    }
    
    .badge-whatsapp {
        background: linear-gradient(135deg, #25d366, #128c7e);
        color: white;
        box-shadow: 0 2px 4px rgba(37,211,102,0.3);
    }
    
    .badge-pendiente {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 2px 4px rgba(245,158,11,0.3);
    }
    
    .badge-proceso {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 2px 4px rgba(102,126,234,0.3);
    }
    
    .badge-finalizado {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 2px 4px rgba(16,185,129,0.3);
    }
    
    .badge-plan {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .badge-plan-inactive {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    /* Detail Cards */
    .detail-card {
        background: white;
        border-radius: 16px;
        padding: 1rem;
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0;
    }
    
    .detail-card:hover {
        transform: translateX(5px);
        border-color: #cbd5e1;
    }
    
    .success-card {
        border-left: 4px solid #10b981;
    }
    
    .detail-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }
    
    .detail-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .detail-content {
        margin-bottom: 0;
        color: #475569;
        line-height: 1.5;
        font-size: 0.9rem;
        padding-left: 3rem;
    }
    
    /* Botones */
    .btn-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border: none;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245,158,11,0.4);
        color: white;
    }
    
    .btn-outline-primary {
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        background: transparent;
    }
    
    .btn-outline-primary:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border: none;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239,68,68,0.4);
        color: white;
    }
    
    .btn-secondary {
        background: #64748b;
        border: none;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        transform: translateY(-2px);
        background: #475569;
        color: white;
    }
    
    /* Dark Mode */
    body.dark-mode .card-modern {
        background: #1e293b;
    }
    
    body.dark-mode .info-section {
        background: #0f172a;
    }
    
    body.dark-mode .info-section:hover {
        background: #1e293b;
    }
    
    body.dark-mode .info-card {
        background: #0f172a;
        border-color: #334155;
    }
    
    body.dark-mode .info-card:hover {
        border-color: #475569;
    }
    
    body.dark-mode .info-card-content span {
        color: #f1f5f9;
    }
    
    body.dark-mode .detail-card {
        background: #0f172a;
        border-color: #334155;
    }
    
    body.dark-mode .detail-card:hover {
        border-color: #475569;
    }
    
    body.dark-mode .detail-content {
        color: #cbd5e1;
    }
    
    body.dark-mode .border-top {
        border-top-color: #334155 !important;
    }
    
    body.dark-mode .border-bottom {
        border-bottom-color: #334155 !important;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .info-card {
            flex-direction: column;
            text-align: center;
        }
        
        .info-card-content label {
            text-align: center;
        }
        
        .detail-content {
            padding-left: 0;
            margin-top: 0.5rem;
        }
        
        .detail-header {
            flex-wrap: wrap;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function eliminarInteraccion(id, estudianteNombre) {
        Swal.fire({
            title: '¿Eliminar interacción?',
            html: `
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-headset fa-3x" style="color: #dc3545;"></i>
                    </div>
                    <p class="mb-2">Estás a punto de eliminar la interacción con:</p>
                    <strong class="fs-4" style="background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; background-clip: text; color: transparent;">${escapeHtml(estudianteNombre)}</strong>
                    <div class="alert alert-danger mt-3" style="border-radius: 12px;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Esta acción es irreversible y eliminará todos los datos asociados a esta interacción.
                    </div>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-trash-alt me-2"></i>Sí, eliminar permanentemente',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar',
            background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#f1f5f9' : '#1e293b',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Eliminando...',
                    text: 'Por favor espere',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.callcenter.destroy", "") }}/' + id;
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                form.style.display = 'none';
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    @if(session('success'))
        Swal.fire({
            title: 'Éxito',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#667eea',
            timer: 3000
        });
    @endif
    
    @if(session('error'))
        Swal.fire({
            title: 'Error',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonColor: '#dc3545'
        });
    @endif
</script>
@endpush