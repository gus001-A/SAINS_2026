@extends('administrador.layouts.master')

@section('title', 'Gestión de Interacciones Call Center - SAINS')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Mejorado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="animate__animated animate__fadeInLeft">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #667eea20, #764ba220);">
                            <i class="fas fa-headset fa-2x" style="background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
                        </div>
                        <div>
                            <h1 class="display-5 fw-bold mb-1" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                                Gestión de Interacciones
                            </h1>
                            <p class="text-muted fs-5 mb-0">Administre y supervise todas las comunicaciones con estudiantes</p>
                        </div>
                    </div>
                </div>
                <div class="animate__animated animate__fadeInRight">
                    <a href="{{ route('admin.callcenter.create') }}" class="btn btn-primary-custom px-4 py-3 rounded-3 shadow-sm">
                        <i class="fas fa-plus-circle me-2"></i>
                        <span>Nueva Interacción</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de Filtros Mejorado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="fas fa-sliders-h text-primary"></i>
                        <h6 class="mb-0 fw-semibold">Filtros de búsqueda</h6>
                    </div>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-search me-1"></i>Buscar
                            </label>
                            <input type="text" id="searchInput" class="form-control form-control-lg rounded-3 border-2" 
                                   placeholder="Administrador, estudiante..." value="{{ request('busqueda') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-tag me-1"></i>Estado
                            </label>
                            <select id="estadoFilter" class="form-select form-select-lg rounded-3 border-2">
                                <option value="">Todos</option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                                <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>🔄 En Proceso</option>
                                <option value="finalizado" {{ request('estado') == 'finalizado' ? 'selected' : '' }}>✅ Finalizado</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-calendar-alt me-1"></i>Fecha Inicio
                            </label>
                            <input type="date" id="fechaInicio" class="form-control form-control-lg rounded-3 border-2" value="{{ request('fecha_inicio') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-calendar-check me-1"></i>Fecha Fin
                            </label>
                            <input type="date" id="fechaFin" class="form-control form-control-lg rounded-3 border-2" value="{{ request('fecha_fin') }}">
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button id="btnFiltrar" class="btn btn-primary-custom px-4 py-2 rounded-3 flex-grow-1">
                                    <i class="fas fa-filter me-2"></i>Aplicar filtros
                                </button>
                                <button id="btnLimpiar" class="btn btn-outline-secondary px-4 py-2 rounded-3">
                                    <i class="fas fa-redo-alt me-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de estadísticas Mejoradas -->
    <div class="row mb-4 g-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 hover-lift stat-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold text-uppercase">Total Interacciones</p>
                            <h2 class="mb-0 fw-bold display-6" style="color: #667eea;">{{ $totalInteracciones ?? $interacciones->total() ?? 0 }}</h2>
                        </div>
                        <div class="rounded-circle p-3 stat-icon" style="background: linear-gradient(135deg, #667eea20, #764ba220);">
                            <i class="fas fa-chart-line fa-2x" style="color: #667eea;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 hover-lift stat-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold text-uppercase">Pendientes</p>
                            <h2 class="mb-0 fw-bold display-6" style="color: #f39c12;">{{ $pendientes ?? 0 }}</h2>
                        </div>
                        <div class="rounded-circle p-3 stat-icon" style="background: #f39c1220;">
                            <i class="fas fa-clock fa-2x" style="color: #f39c12;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 hover-lift stat-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold text-uppercase">En Proceso</p>
                            <h2 class="mb-0 fw-bold display-6" style="color: #3498db;">{{ $enProceso ?? 0 }}</h2>
                        </div>
                        <div class="rounded-circle p-3 stat-icon" style="background: #3498db20;">
                            <i class="fas fa-sync-alt fa-2x" style="color: #3498db;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 hover-lift stat-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold text-uppercase">Finalizados</p>
                            <h2 class="mb-0 fw-bold display-6" style="color: #27ae60;">{{ $finalizados ?? 0 }}</h2>
                        </div>
                        <div class="rounded-circle p-3 stat-icon" style="background: #27ae6020;">
                            <i class="fas fa-check-circle fa-2x" style="color: #27ae60;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de interacciones Mejorada -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 modern-table">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="py-4 px-4 text-white fw-semibold">ID</th>
                                <th class="py-4 px-4 text-white fw-semibold">Fecha/Hora</th>
                                <th class="py-4 px-4 text-white fw-semibold">Administrador</th>
                                <th class="py-4 px-4 text-white fw-semibold">Estudiante</th>
                                <th class="py-4 px-4 text-white fw-semibold">Tipo</th>
                                <th class="py-4 px-4 text-white fw-semibold">Motivo</th>
                                <th class="py-4 px-4 text-white fw-semibold">Estado</th>
                                <th class="py-4 px-4 text-white fw-semibold">Próximo Contacto</th>
                                <th class="py-4 px-4 text-white fw-semibold text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaBody">
                            @forelse($interacciones as $interaccion)
                            <tr class="animate__animated animate__fadeInUp animate__fast" style="animation-delay: {{ $loop->index * 0.03 }}s;">
                                <td class="px-3 py-2" data-label="ID">
                                    <span class="fw-bold" style="background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; background-clip: text; color: transparent; font-size: 0.9rem;">#{{ $interaccion->id }}</span>
                                </td>
                                
                                <td class="px-3 py-2" data-label="Fecha/Hora">
                                    @if($interaccion->fecha_contacto)
                                        <div class="fecha-info">
                                            <span class="fecha-fecha">{{ \Carbon\Carbon::parse($interaccion->fecha_contacto)->format('d/m/Y') }}</span>
                                            <span class="fecha-hora">
                                                <i class="far fa-clock fa-xs me-1"></i>
                                                {{ $interaccion->hora_contacto ? \Carbon\Carbon::parse($interaccion->hora_contacto)->format('h:i A') : 'N/A' }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                
                                <td class="px-3 py-2" data-label="Administrador">
                                    <div class="usuario-info">
                                        <div class="avatar-mini" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                                            {{ substr($interaccion->admin_nombre_completo ?? 'A', 0, 1) }}
                                        </div>
                                        <div class="usuario-detalles">
                                            <span class="usuario-nombre">{{ $interaccion->admin_nombre_completo ?? 'N/A' }}</span>
                                            @if($interaccion->administrador && $interaccion->administrador->email)
                                                <span class="usuario-email">{{ $interaccion->administrador->email }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-3 py-2" data-label="Estudiante">
                                    <div class="usuario-info">
                                        <div class="avatar-mini" style="background: linear-gradient(135deg, #f59e0b, #ef4444);">
                                            {{ substr($interaccion->est_nombre_completo ?? 'E', 0, 1) }}
                                        </div>
                                        <div class="usuario-detalles">
                                            <span class="usuario-nombre">{{ $interaccion->est_nombre_completo ?? 'N/A' }}</span>
                                            @if($interaccion->estudiante && $interaccion->estudiante->email)
                                                <span class="usuario-email">{{ $interaccion->estudiante->email }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-3 py-2 text-center" data-label="Tipo">
                                    @php
                                        $tipoClasses = [
                                            'llamada' => 'badge-llamada',
                                            'email' => 'badge-email',
                                            'whatsapp' => 'badge-whatsapp'
                                        ];
                                        $tipoClass = $tipoClasses[$interaccion->tipo_contacto] ?? 'badge-llamada';
                                        $tipoIconos = [
                                            'llamada' => 'fa-phone',
                                            'email' => 'fa-envelope',
                                            'whatsapp' => 'fa-whatsapp'
                                        ];
                                        $tipoIcono = $tipoIconos[$interaccion->tipo_contacto] ?? 'fa-phone';
                                    @endphp
                                    <span class="badge-tipo-contacto {{ $tipoClass }}">
                                        <i class="{{ $interaccion->tipo_contacto == 'whatsapp' ? 'fab' : 'fas' }} {{ $tipoIcono }} fa-sm"></i>
                                        {{ ucfirst($interaccion->tipo_contacto) }}
                                    </span>
                                </td>
                                
                                <td class="px-3 py-2" data-label="Motivo">
                                    <span class="motivo-texto" title="{{ $interaccion->motivo_contacto ?? 'N/A' }}">
                                        {{ Str::limit($interaccion->motivo_contacto ?? 'N/A', 35) }}
                                    </span>
                                </td>
                                
                                <td class="px-3 py-2 text-center" data-label="Estado">
                                    @php
                                        $estadoClasses = [
                                            'pendiente' => 'badge-pendiente',
                                            'en_proceso' => 'badge-en_proceso',
                                            'finalizado' => 'badge-finalizado'
                                        ];
                                        $estadoClass = $estadoClasses[$interaccion->estado_seguimiento] ?? 'badge-pendiente';
                                        $estadoIconos = [
                                            'pendiente' => 'fa-clock',
                                            'en_proceso' => 'fa-sync-alt',
                                            'finalizado' => 'fa-check-circle'
                                        ];
                                        $estadoIcono = $estadoIconos[$interaccion->estado_seguimiento] ?? 'fa-clock';
                                    @endphp
                                    <span class="badge-estado {{ $estadoClass }}">
                                        <i class="fas {{ $estadoIcono }} fa-sm"></i>
                                        {{ ucfirst(str_replace('_', ' ', $interaccion->estado_seguimiento ?? 'N/A')) }}
                                    </span>
                                </td>
                                
                                <td class="px-3 py-2" data-label="Próximo Contacto">
                                    @if($interaccion->proximo_contacto)
                                        <div class="fecha-info">
                                            <span class="fecha-fecha">{{ \Carbon\Carbon::parse($interaccion->proximo_contacto)->format('d/m/Y') }}</span>
                                            @if($interaccion->proximo_contacto_hora)
                                                <span class="fecha-hora">
                                                    <i class="far fa-clock fa-xs me-1"></i>
                                                    {{ \Carbon\Carbon::parse($interaccion->proximo_contacto_hora)->format('h:i A') }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                
                                <td class="px-3 py-2 text-center" data-label="Acciones">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button onclick="verInteraccion({{ $interaccion->id }})" 
                                                class="btn-accion btn-ver" 
                                                title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button onclick="editarInteraccion({{ $interaccion->id }})" 
                                                class="btn-accion btn-editar" 
                                                title="Editar interacción">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="eliminarInteraccion({{ $interaccion->id }})" 
                                                class="btn-accion btn-eliminar" 
                                                title="Eliminar interacción">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center gap-3 animate__animated animate__fadeIn">
                                        <div class="rounded-circle p-4" style="background: linear-gradient(135deg, #667eea10 0%, #764ba210 100%);">
                                            <i class="fas fa-phone-slash fa-4x" style="background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
                                        </div>
                                        <div>
                                            <h5 class="text-muted mb-2">✨ No hay interacciones registradas</h5>
                                            <p class="text-muted small">Comienza registrando la primera interacción con un estudiante</p>
                                        </div>
                                        <a href="{{ route('admin.callcenter.create') }}" class="btn btn-primary-custom px-4 py-2">
                                            <i class="fas fa-plus-circle me-2"></i>Registrar primera interacción
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center p-4 bg-light border-top">
                    <div class="text-muted small">
                        <i class="fas fa-chart-line me-1"></i>
                        Mostrando <span class="fw-semibold" id="desde">{{ $interacciones->firstItem() ?? 0 }}</span> - 
                        <span class="fw-semibold" id="hasta">{{ $interacciones->lastItem() ?? 0 }}</span> 
                        de <span class="fw-semibold" id="total">{{ $interacciones->total() ?? 0 }}</span> registros
                    </div>
                    <div id="paginationLinks" class="d-flex justify-content-end">
                        {{ $interacciones->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Animaciones y efectos mejorados */
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
    }
    
    .stat-card {
        transition: all 0.3s ease;
    }
    
    .stat-card:hover .stat-icon {
        transform: scale(1.1);
        transition: transform 0.3s ease;
    }
    
    .modern-table {
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .modern-table tbody tr {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .modern-table tbody tr:hover {
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.04), rgba(118, 75, 162, 0.04));
        transform: translateX(5px);
    }
    
    .modern-table td {
        padding: 1rem 1rem;
        vertical-align: middle;
        font-size: 0.9rem;
    }
    
    /* Badges mejorados */
    .badge-tipo-contacto {
        padding: 6px 14px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }
    
    .badge-tipo-contacto:hover {
        transform: scale(1.05);
    }
    
    .badge-llamada { background: linear-gradient(135deg, #e3f2fd, #bbdef5); color: #1565c0; }
    .badge-email { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); color: #2e7d32; }
    .badge-whatsapp { background: linear-gradient(135deg, #e0f7fa, #b2ebf2); color: #00838f; }
    
    .badge-estado {
        padding: 6px 14px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }
    
    .badge-estado:hover {
        transform: scale(1.05);
    }
    
    .badge-pendiente { background: linear-gradient(135deg, #fff3e0, #ffe0b2); color: #e65100; }
    .badge-en_proceso { background: linear-gradient(135deg, #e3f2fd, #bbdef5); color: #1565c0; }
    .badge-finalizado { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); color: #2e7d32; }
    
    /* Botones de acción mejorados */
    .btn-accion {
        width: 36px;
        height: 36px;
        padding: 0;
        margin: 0 4px;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 500;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .btn-accion::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        transform: translate(-50%, -50%);
        transition: width 0.4s, height 0.4s;
    }
    
    .btn-accion:hover::before {
        width: 100%;
        height: 100%;
    }
    
    .btn-accion i {
        position: relative;
        z-index: 1;
        font-size: 0.9rem;
    }
    
    .btn-editar {
        background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
        color: #2e7d32;
    }
    
    .btn-editar:hover {
        background: linear-gradient(135deg, #2e7d32, #1b5e20);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(46, 125, 50, 0.3);
    }
    
    .btn-eliminar {
        background: linear-gradient(135deg, #ffebee, #ffcdd2);
        color: #c62828;
    }
    
    .btn-eliminar:hover {
        background: linear-gradient(135deg, #c62828, #b71c1c);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(198, 40, 40, 0.3);
    }
    
    .btn-ver {
        background: linear-gradient(135deg, #e3f2fd, #bbdef5);
        color: #1565c0;
    }
    
    .btn-ver:hover {
        background: linear-gradient(135deg, #1565c0, #0d47a1);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(21, 101, 192, 0.3);
    }
    
    /* Info de usuario mejorada */
    .usuario-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .avatar-mini {
        width: 40px;
        height: 40px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .usuario-info:hover .avatar-mini {
        transform: scale(1.05) rotate(5deg);
    }
    
    .usuario-detalles {
        display: flex;
        flex-direction: column;
    }
    
    .usuario-nombre {
        font-weight: 600;
        font-size: 0.9rem;
        color: #2c3e50;
    }
    
    .usuario-email {
        font-size: 0.7rem;
        color: #7f8c8d;
    }
    
    /* Fechas mejoradas */
    .fecha-info {
        display: flex;
        flex-direction: column;
    }
    
    .fecha-fecha {
        font-weight: 600;
        font-size: 0.85rem;
        color: #2c3e50;
    }
    
    .fecha-hora {
        font-size: 0.7rem;
        color: #7f8c8d;
    }
    
    /* Formularios mejorados */
    .form-control-lg, .form-select-lg {
        font-size: 0.95rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        background-color: white;
    }
    
    .form-control-lg:focus, .form-select-lg:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .btn-primary-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-primary-custom:hover::before {
        left: 100%;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }
    
    /* Paginación mejorada */
    .pagination {
        margin-bottom: 0;
        gap: 5px;
    }
    
    .page-item .page-link {
        border-radius: 10px !important;
        margin: 0;
        color: #667eea;
        border: none;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .page-item .page-link:hover {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        transform: translateY(-2px);
    }
    
    .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
    }
    
    /* Modo oscuro mejorado */
    body.dark-mode .card {
        background-color: #1a1a2e;
        border-color: rgba(255, 255, 255, 0.05);
    }
    
    body.dark-mode .modern-table td {
        border-bottom-color: rgba(255, 255, 255, 0.05);
        color: #e0e0e0;
    }
    
    body.dark-mode .modern-table tbody tr:hover {
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.08), rgba(118, 75, 162, 0.08));
    }
    
    body.dark-mode .bg-light {
        background-color: #0f0f1a !important;
    }
    
    body.dark-mode .usuario-nombre {
        color: #e0e0e0;
    }
    
    body.dark-mode .usuario-email,
    body.dark-mode .fecha-hora {
        color: #a0a0a0;
    }
    
    body.dark-mode .form-control-lg,
    body.dark-mode .form-select-lg {
        background-color: #2a2a3e;
        border-color: #3a3a4e;
        color: #e0e0e0;
    }
    
    body.dark-mode .form-control-lg:focus,
    body.dark-mode .form-select-lg:focus {
        border-color: #667eea;
        background-color: #2a2a3e;
    }
    
    /* Scroll personalizado */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #764ba2, #667eea);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Función auxiliar para construir URLs con ID
        const buildRoute = (routePattern, id) => {
            return routePattern.replace('__ID__', id);
        };
        
        // ✅ CORREGIDO: Ver interacción
        window.verInteraccion = function(id) {
            Swal.fire({
                title: 'Cargando...',
                html: 'Redirigiendo a los detalles',
                timer: 1000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                },
                willClose: () => {
                    const url = buildRoute('{{ route("admin.callcenter.show", "__ID__") }}', id);
                    window.location.href = url;
                }
            });
        };
        
        // ✅ CORREGIDO: Editar interacción
        window.editarInteraccion = function(id) {
            Swal.fire({
                title: 'Editando interacción',
                html: 'Preparando formulario de edición...',
                timer: 1000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                },
                willClose: () => {
                    const url = buildRoute('{{ route("admin.callcenter.edit", "__ID__") }}', id);
                    window.location.href = url;
                }
            });
        };
        
        // ✅ CORREGIDO: Eliminar interacción
        window.eliminarInteraccion = function(id) {
            Swal.fire({
                title: '¿Eliminar interacción?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#667eea',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#333',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp animate__faster'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = buildRoute('{{ route("admin.callcenter.destroy", "__ID__") }}', id);
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                    `;
                    document.body.appendChild(form);
                    
                    Swal.fire({
                        title: 'Eliminando...',
                        html: 'Procesando solicitud',
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading();
                            form.submit();
                        }
                    });
                }
            });
        };
        
        // Filtrar con animación
        function cargarTabla() {
            const btnFiltrar = document.getElementById('btnFiltrar');
            btnFiltrar.innerHTML = '<i class="fas fa-spinner fa-pulse me-2"></i>Filtrando...';
            btnFiltrar.disabled = true;
            
            setTimeout(() => {
                const search = document.getElementById('searchInput')?.value || '';
                const estado = document.getElementById('estadoFilter')?.value || '';
                const fechaInicio = document.getElementById('fechaInicio')?.value || '';
                const fechaFin = document.getElementById('fechaFin')?.value || '';
                
                const url = new URL(window.location.href);
                if (search) url.searchParams.set('busqueda', search);
                else url.searchParams.delete('busqueda');
                
                if (estado) url.searchParams.set('estado', estado);
                else url.searchParams.delete('estado');
                
                if (fechaInicio) url.searchParams.set('fecha_inicio', fechaInicio);
                else url.searchParams.delete('fecha_inicio');
                
                if (fechaFin) url.searchParams.set('fecha_fin', fechaFin);
                else url.searchParams.delete('fecha_fin');
                
                window.location.href = url.toString();
            }, 500);
        }
        
        document.getElementById('btnFiltrar')?.addEventListener('click', cargarTabla);
        document.getElementById('btnLimpiar')?.addEventListener('click', function() {
            Swal.fire({
                title: 'Limpiando filtros',
                text: 'Recargando la página...',
                icon: 'info',
                timer: 1000,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                willClose: () => {
                    window.location.href = window.location.pathname;
                }
            });
        });
        
        document.getElementById('searchInput')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') cargarTabla();
        });
        
        // Mostrar mensajes con SweetAlert mejorado
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#667eea',
                background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#333',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                timer: 3000,
                timerProgressBar: true
            });
        @endif
        
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33',
                background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#333',
                showClass: {
                    popup: 'animate__animated animate__shakeX'
                }
            });
        @endif
    });
</script>
@endpush