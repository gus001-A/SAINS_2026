@extends('administrador.layouts.master')

@section('title', 'Detalles de Interacción - Call Center - SAINS')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-3 p-2" style="background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);">
                            <i class="fas fa-headset fa-2x" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
                        </div>
                        <div>
                            <h1 class="display-5 fw-bold mb-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                                Detalles de Interacción
                            </h1>
                            <p class="text-muted fs-6 mb-0 mt-1">
                                <i class="fas fa-hashtag me-1"></i>ID: #{{ $interaccion->id }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.callcenter.edit', $interaccion->id) }}" class="btn btn-primary-custom px-4 py-2 shadow-sm">
                        <i class="fas fa-edit me-2"></i>Editar Interacción
                    </a>
                    <a href="{{ route('admin.callcenter.index') }}" class="btn btn-outline-secondary px-4 py-2">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de estadísticas mejoradas -->
    <div class="row mb-4 g-3">
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Tipo de Contacto</p>
                            @php
                                $tipoConfig = [
                                    'llamada' => ['icon' => 'fa-phone', 'color' => 'primary', 'bg' => 'rgba(102,126,234,0.1)'],
                                    'email' => ['icon' => 'fa-envelope', 'color' => 'success', 'bg' => 'rgba(40,167,69,0.1)'],
                                    'whatsapp' => ['icon' => 'fab fa-whatsapp', 'color' => 'success', 'bg' => 'rgba(37,211,102,0.1)']
                                ];
                                $config = $tipoConfig[$interaccion->tipo_contacto] ?? $tipoConfig['llamada'];
                            @endphp
                            <h4 class="fw-bold mb-0 text-{{ $config['color'] }}">
                                <i class="{{ $config['icon'] }} me-1"></i>
                                {{ ucfirst($interaccion->tipo_contacto) }}
                            </h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: {{ $config['bg'] }};">
                            <i class="{{ $config['icon'] }} text-{{ $config['color'] }} fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Estado</p>
                            @php
                                $estadoConfig = [
                                    'pendiente' => ['icon' => 'fa-clock', 'color' => 'warning', 'text' => 'Pendiente', 'bg' => 'rgba(255,193,7,0.1)'],
                                    'en_proceso' => ['icon' => 'fa-spinner', 'color' => 'primary', 'text' => 'En Proceso', 'bg' => 'rgba(102,126,234,0.1)'],
                                    'finalizado' => ['icon' => 'fa-check-circle', 'color' => 'success', 'text' => 'Finalizado', 'bg' => 'rgba(40,167,69,0.1)']
                                ];
                                $estConfig = $estadoConfig[$interaccion->estado_seguimiento] ?? $estadoConfig['pendiente'];
                            @endphp
                            <h4 class="fw-bold mb-0 text-{{ $estConfig['color'] }}">
                                <i class="{{ $estConfig['icon'] }} me-1"></i>
                                {{ $estConfig['text'] }}
                            </h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: {{ $estConfig['bg'] }};">
                            <i class="{{ $estConfig['icon'] }} text-{{ $estConfig['color'] }} fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Fecha de Contacto</p>
                            <h4 class="fw-bold mb-0 text-info">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ \Carbon\Carbon::parse($interaccion->fecha_contacto)->format('d/m/Y') }}
                            </h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(23,162,184,0.1);">
                            <i class="fas fa-calendar-alt text-info fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Hora de Contacto</p>
                            <h4 class="fw-bold mb-0 text-success">
                                <i class="fas fa-clock me-1"></i>
                                {{ \Carbon\Carbon::parse($interaccion->hora_contacto)->format('h:i A') }}
                            </h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(40,167,69,0.1);">
                            <i class="fas fa-clock text-success fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Información del Estudiante -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 hover-card h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-user-graduate text-primary me-2"></i>Información del Estudiante
                    </h5>
                </div>
                <div class="card-body p-4 pt-0">
                    @php
                        $estudiante = $interaccion->estudiante;
                        $detalleEstudiante = $estudiante ? $estudiante->estudiante : null;
                    @endphp
                    <div class="info-grid">
                        <div class="info-item">
                            <label><i class="fas fa-user me-2 text-primary"></i>Nombre completo</label>
                            <span class="fw-semibold">
                                {{ $detalleEstudiante ? $detalleEstudiante->nombre . ' ' . $detalleEstudiante->paterno . ' ' . $detalleEstudiante->materno : ($estudiante->name ?? 'N/A') }}
                            </span>
                        </div>
                        <div class="info-item">
                            <label><i class="fas fa-envelope me-2 text-primary"></i>Correo electrónico</label>
                            <span>{{ $estudiante->correo ?? 'No registrado' }}</span>
                        </div>
                        @if($detalleEstudiante)
                        <div class="info-item">
                            <label><i class="fas fa-phone me-2 text-primary"></i>Teléfono</label>
                            <span>{{ $detalleEstudiante->telefono ?? 'No registrado' }}</span>
                        </div>
                        <div class="info-item">
                            <label><i class="fas fa-check-circle me-2 text-primary"></i>Plan activo</label>
                            <span>
                                @if($detalleEstudiante->plan_activo)
                                    <span class="badge-plan-activo"><i class="fas fa-check-circle"></i> Activo</span>
                                @else
                                    <span class="badge-plan-inactivo"><i class="fas fa-times-circle"></i> Inactivo</span>
                                @endif
                            </span>
                        </div>
                        @if($detalleEstudiante->escuelaProcedencia)
                        <div class="info-item">
                            <label><i class="fas fa-school me-2 text-primary"></i>Escuela de procedencia</label>
                            <span>{{ $detalleEstudiante->escuelaProcedencia->centro_educativo ?? 'N/A' }}</span>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Asesor -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 hover-card h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-user-tie text-primary me-2"></i>Información del Asesor
                    </h5>
                </div>
                <div class="card-body p-4 pt-0">
                    @php
                        $asesor = $interaccion->administrador;
                        $detalleAsesor = $asesor ? $asesor->administrador : null;
                    @endphp
                    <div class="info-grid">
                        <div class="info-item">
                            <label><i class="fas fa-user me-2 text-primary"></i>Nombre completo</label>
                            <span class="fw-semibold">
                                {{ $detalleAsesor ? $detalleAsesor->nombre . ' ' . $detalleAsesor->apellido_paterno . ' ' . $detalleAsesor->apellido_materno : ($asesor->name ?? 'N/A') }}
                            </span>
                        </div>
                        <div class="info-item">
                            <label><i class="fas fa-envelope me-2 text-primary"></i>Correo electrónico</label>
                            <span>{{ $asesor->correo ?? 'No registrado' }}</span>
                        </div>
                        @if($detalleAsesor)
                        <div class="info-item">
                            <label><i class="fas fa-phone me-2 text-primary"></i>Teléfono</label>
                            <span>{{ $detalleAsesor->telefono ?? 'No registrado' }}</span>
                        </div>
                        <div class="info-item">
                            <label><i class="fas fa-user-tag me-2 text-primary"></i>Rol</label>
                            <span>{{ ucfirst($asesor->rol ?? 'Administrador') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Detalles de la Interacción -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 hover-card">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-info-circle text-primary me-2"></i>Detalles de la Interacción
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="info-grid">
                        <div class="info-item">
                            <label><i class="fas fa-tag me-2 text-primary"></i>Motivo de contacto</label>
                            <span class="fw-semibold">{{ $interaccion->motivo_contacto }}</span>
                        </div>
                        @if($interaccion->nota)
                        <div class="info-item">
                            <label><i class="fas fa-sticky-note me-2 text-primary"></i>Nota del asesor</label>
                            <span>{{ nl2br(e($interaccion->nota)) }}</span>
                        </div>
                        @endif
                        @if($interaccion->resultado)
                        <div class="info-item resultado-item">
                            <label><i class="fas fa-chart-line me-2 text-success"></i>Resultado de la interacción</label>
                            <span class="fw-semibold text-success">{{ $interaccion->resultado }}</span>
                        </div>
                        @endif
                        @if($interaccion->proximo_contacto)
                        <div class="info-item proximo-item">
                            <label><i class="fas fa-calendar-week me-2 text-warning"></i>Próximo contacto programado</label>
                            <span class="fw-semibold text-warning">{{ \Carbon\Carbon::parse($interaccion->proximo_contacto)->format('d/m/Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de acción -->
    <div class="d-flex justify-content-center gap-3 mt-5 pt-4 border-top">
        <button onclick="eliminarInteraccion({{ $interaccion->id }}, '{{ addslashes($detalleEstudiante->nombre ?? $estudiante->name ?? 'este estudiante') }}')" 
                class="btn btn-danger px-5 py-3 shadow-sm">
            <i class="fas fa-trash-alt me-2"></i> Eliminar Interacción
        </button>
        <a href="{{ route('admin.callcenter.index') }}" class="btn btn-outline-secondary px-5 py-3">
            <i class="fas fa-arrow-left me-2"></i> Volver al listado
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Animaciones y efectos */
    .hover-card {
        transition: all 0.2s ease-in-out;
    }
    
    .hover-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    /* Info items */
    .info-grid {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-item label {
        font-weight: 600;
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 0;
        min-width: 180px;
    }
    
    .info-item span {
        color: #1e293b;
        font-weight: 500;
        font-size: 0.9rem;
        text-align: right;
        flex: 1;
    }
    
    .resultado-item {
        background: rgba(40, 167, 69, 0.05);
        border-radius: 12px;
        padding: 1rem;
        margin-top: 0.5rem;
        border-left: 4px solid #28a745;
    }
    
    .proximo-item {
        background: rgba(255, 193, 7, 0.05);
        border-radius: 12px;
        padding: 1rem;
        margin-top: 0.5rem;
        border-left: 4px solid #ffc107;
    }
    
    /* Badges de plan */
    .badge-plan-activo {
        background: linear-gradient(135deg, #4caf50, #388e3c);
        color: white;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 2px 4px rgba(76,175,80,0.3);
    }
    
    .badge-plan-inactivo {
        background: linear-gradient(135deg, #ef5350, #d32f2f);
        color: white;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 2px 4px rgba(239,83,80,0.3);
    }
    
    /* Botones principales */
    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 500;
        transition: all 0.2s ease;
        border-radius: 12px;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .btn-outline-secondary {
        border: 2px solid #e9ecef;
        transition: all 0.2s ease;
        background: transparent;
        border-radius: 12px;
        font-weight: 500;
    }
    
    .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #ef5350, #d32f2f);
        border: none;
        color: white;
        font-weight: 500;
        transition: all 0.2s ease;
        border-radius: 12px;
    }
    
    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(239, 83, 80, 0.4);
        color: white;
    }
    
    /* Dark Mode */
    body.dark-mode .card {
        background-color: #1a1a2e;
    }
    
    body.dark-mode .info-item {
        border-bottom-color: rgba(255, 255, 255, 0.05);
    }
    
    body.dark-mode .info-item label {
        color: #9ca3af;
    }
    
    body.dark-mode .info-item span {
        color: #e0e0e0;
    }
    
    body.dark-mode .resultado-item {
        background: rgba(40, 167, 69, 0.1);
    }
    
    body.dark-mode .proximo-item {
        background: rgba(255, 193, 7, 0.1);
    }
    
    body.dark-mode .btn-outline-secondary {
        border-color: rgba(102, 126, 234, 0.5);
        color: #e0e0e0;
    }
    
    body.dark-mode .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
    }
    
    body.dark-mode .border-top {
        border-top-color: rgba(255, 255, 255, 0.1) !important;
    }
    
    /* Scroll suave */
    html {
        scroll-behavior: smooth;
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
                    <i class="fas fa-headset fa-3x mb-3" style="color: #dc3545;"></i>
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
            background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#1e293b',
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
                form.action = `/administrador/callcenter/${id}`;
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
</script>
@endpush