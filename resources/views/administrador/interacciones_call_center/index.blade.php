@extends('administrador.layouts.master')

@section('title', 'Gestión de Interacciones Call Center - SAINS')

@section('content')
<div class="container-fluid p-0 p-lg-2">
    <div class="px-2 px-xl-3 px-xxl-4">
        <!-- Header mejorado -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="rounded-3 p-2" style="background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);">
                                <i class="fas fa-headset fa-2x" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
                            </div>
                            <h1 class="display-5 fw-bold mb-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                                Gestión de Interacciones
                            </h1>
                        </div>
                        <p class="text-muted fs-5 mb-0">Administre y supervise todas las comunicaciones con estudiantes</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.callcenter.create') }}" class="btn btn-primary-custom px-4 py-3 rounded-3 shadow-sm">
                            <i class="fas fa-plus-circle me-2"></i>Nueva Interacción
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTADORES RÁPIDOS - Tarjetas de estadísticas -->
        <div class="row mb-4 g-3">
            <!-- Total Interacciones -->
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-primary">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Total Interacciones</p>
                                <h2 class="display-4 fw-bold mb-0">{{ $totalInteracciones ?? $interacciones->total() ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-database me-1"></i> Comunicaciones registradas
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-headset fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pendientes -->
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-warning">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">⏳ Pendientes</p>
                                <h2 class="display-4 fw-bold mb-0">{{ $pendientes ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-clock me-1"></i> Esperando seguimiento
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- En Proceso -->
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-info">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">🔄 En Proceso</p>
                                <h2 class="display-4 fw-bold mb-0">{{ $enProceso ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-sync-alt me-1"></i> En atención activa
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-sync-alt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Finalizados -->
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-success">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">✅ Finalizados</p>
                                <h2 class="display-4 fw-bold mb-0">{{ $finalizados ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-check-circle me-1"></i> Comunicaciones completadas
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Filtros Mejorado -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-sliders-h text-primary"></i>
                            <h5 class="fw-semibold mb-0">Filtros de búsqueda</h5>
                        </div>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <form method="GET" action="{{ route('admin.callcenter.index') }}" id="filtroForm" data-ajax="true">
                            <div class="row g-3 align-items-end">
                                <!-- Buscador -->
                                <div class="col-md-4 col-lg-3">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-search me-1"></i>Buscar
                                    </label>
                                    <input type="text" name="busqueda" class="form-control form-control-lg rounded-3" 
                                           placeholder="Administrador, estudiante..." value="{{ request('busqueda') }}" data-filter-input>
                                </div>

                                <!-- Estado -->
                                <div class="col-md-4 col-lg-2">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-tag me-1"></i>Estado
                                    </label>
                                    <select name="estado" class="form-select form-select-lg rounded-3" data-filter-select>
                                        <option value="">Todos</option>
                                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                                        <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>🔄 En Proceso</option>
                                        <option value="finalizado" {{ request('estado') == 'finalizado' ? 'selected' : '' }}>✅ Finalizado</option>
                                    </select>
                                </div>

                                <!-- Tipo Contacto -->
                                <div class="col-md-4 col-lg-2">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-phone-alt me-1"></i>Tipo
                                    </label>
                                    <select name="tipo_contacto" class="form-select form-select-lg rounded-3" data-filter-select>
                                        <option value="">Todos</option>
                                        <option value="llamada" {{ request('tipo_contacto') == 'llamada' ? 'selected' : '' }}>📞 Llamada</option>
                                        <option value="email" {{ request('tipo_contacto') == 'email' ? 'selected' : '' }}>✉️ Email</option>
                                        <option value="whatsapp" {{ request('tipo_contacto') == 'whatsapp' ? 'selected' : '' }}>💬 WhatsApp</option>
                                    </select>
                                </div>

                                <!-- Fecha Inicio -->
                                <div class="col-md-4 col-lg-2">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-calendar-alt me-1"></i>Fecha Inicio
                                    </label>
                                    <input type="date" name="fecha_inicio" class="form-control form-control-lg rounded-3" 
                                           value="{{ request('fecha_inicio') }}" data-filter-input>
                                </div>

                                <!-- Fecha Fin -->
                                <div class="col-md-4 col-lg-2">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-calendar-check me-1"></i>Fecha Fin
                                    </label>
                                    <input type="date" name="fecha_fin" class="form-control form-control-lg rounded-3" 
                                           value="{{ request('fecha_fin') }}" data-filter-input>
                                </div>

                                <!-- Botones -->
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary-custom w-100 py-2 rounded-3 shadow-sm">
                                            <i class="fas fa-search me-2"></i>Aplicar filtros
                                        </button>
                                        <a href="{{ route('admin.callcenter.index') }}" class="btn btn-outline-secondary w-100 py-2 rounded-3">
                                            <i class="fas fa-eraser me-2"></i>Limpiar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de interacciones -->
        <div class="row">
            <div class="col-12">
                <!-- Card para el header y la tabla -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-semibold mb-0">
                                <i class="fas fa-list me-2 text-primary"></i>Listado de Interacciones
                            </h5>
                            <span class="badge bg-primary rounded-pill">{{ $interacciones->total() }} registros</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="min-width: 1100px;">
                                <thead class="table-header">
                                    <tr>
                                        <th class="py-3 px-4 fw-semibold" style="min-width: 80px;">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="id">
                                                <i class="fas fa-hashtag me-1"></i> ID
                                                @if(request('orden_campo') == 'id')
                                                <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold" style="min-width: 160px;">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="fecha_contacto">
                                                <i class="fas fa-calendar-alt me-1"></i> Fecha/Hora
                                                @if(request('orden_campo') == 'fecha_contacto')
                                                <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold" style="min-width: 200px;">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="administrador">
                                                <i class="fas fa-user-tie me-1"></i> Administrador
                                                @if(request('orden_campo') == 'administrador')
                                                <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold" style="min-width: 200px;">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="estudiante">
                                                <i class="fas fa-user-graduate me-1"></i> Estudiante
                                                @if(request('orden_campo') == 'estudiante')
                                                <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold text-center" style="width: 120px;">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="tipo_contacto">
                                                <i class="fas fa-phone-alt me-1"></i> Tipo
                                                @if(request('orden_campo') == 'tipo_contacto')
                                                <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold" style="min-width: 180px;">
                                            <i class="fas fa-comment me-1"></i> Motivo
                                        </th>
                                        <th class="py-3 px-4 fw-semibold text-center" style="width: 130px;">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="estado_seguimiento">
                                                <i class="fas fa-chart-line me-1"></i> Estado
                                                @if(request('orden_campo') == 'estado_seguimiento')
                                                <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold" style="min-width: 160px;">
                                            <i class="fas fa-calendar-week me-1"></i> Próximo Contacto
                                        </th>
                                        <th class="py-3 px-4 fw-semibold text-center" style="width: 140px;">
                                            <i class="fas fa-cog me-1"></i> Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tablaBody">
                                    @include('administrador.interacciones_call_center.partials.table_rows', ['interacciones' => $interacciones])
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Paginación - SEPARADA de la card para evitar conflictos -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 p-3 bg-white rounded-4 shadow-sm gap-3">
                    <div class="text-muted small">
                        <i class="fas fa-chart-line me-1"></i>
                        Mostrando <span class="fw-semibold text-primary">{{ $interacciones->firstItem() ?? 0 }}</span> -
                        <span class="fw-semibold text-primary">{{ $interacciones->lastItem() ?? 0 }}</span>
                        de <span class="fw-semibold text-primary">{{ $interacciones->total() }}</span> registros
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
/* CONTENEDOR MÁS ANCHO */
.container-fluid {
    width: 100%;
    max-width: 100% !important;
}

@media (min-width: 1400px) {
    .px-xxl-4 {
        padding-right: 0.5rem !important;
        padding-left: 0.5rem !important;
    }

    .container-fluid {
        padding-right: 0.5rem !important;
        padding-left: 0.5rem !important;
    }
}

/* Tarjetas de estadísticas */
.stat-card-primary,
.stat-card-success,
.stat-card-danger,
.stat-card-info,
.stat-card-warning {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.stat-card-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.stat-card-success::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #10b981, #059669);
}

.stat-card-warning::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #f59e0b, #d97706);
}

.stat-card-info::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #3b82f6, #1d4ed8);
}

.stat-icon {
    width: 55px;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
}

.stat-card-primary .stat-icon {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
}

.stat-card-success .stat-icon {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.stat-card-warning .stat-icon {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.stat-card-info .stat-icon {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.hover-card {
    transition: all 0.3s ease-in-out;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
}

/* Links de ordenamiento */
.ordenar-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #1e293b;
    transition: all 0.2s ease;
}

.ordenar-link:hover {
    color: #667eea;
    transform: translateY(-1px);
}

/* Header de tabla */
.table-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 2px solid #e2e8f0;
}

.table-header th {
    color: #1e293b;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Badge de tipos de contacto */
.badge-tipo-contacto {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.badge-llamada {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
}

.badge-email {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.badge-whatsapp {
    background: linear-gradient(135deg, #25d366, #128c7e);
    color: white;
}

/* Badges de estado */
.badge-estado {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.badge-pendiente {
    background: #fff3e0;
    color: #ef6c00;
}

.badge-en-proceso {
    background: #e3f2fd;
    color: #1565c0;
}

.badge-finalizado {
    background: #e8f5e9;
    color: #2e7d32;
}

/* Avatar usuario */
.avatar-mini {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    flex-shrink: 0;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
}

.usuario-info {
    display: flex;
    align-items: center;
    gap: 12px;
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

.motivo-texto {
    font-size: 0.85rem;
    color: #475569;
    display: inline-block;
    max-width: 180px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Botones de acción */
.btn-accion {
    padding: 6px 14px;
    margin: 0 3px;
    font-size: 0.75rem;
    border-radius: 25px;
    transition: all 0.25s ease;
    font-weight: 600;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    text-decoration: none;
}

.btn-ver {
    background: #e3f2fd;
    color: #1565c0;
}

.btn-ver:hover {
    background: #1565c0;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(21, 101, 192, 0.3);
}

.btn-editar {
    background: #e8f5e9;
    color: #2e7d32;
}

.btn-editar:hover {
    background: #2e7d32;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
}

.btn-eliminar {
    background: #ffebee;
    color: #c62828;
}

.btn-eliminar:hover {
    background: #c62828;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(198, 40, 40, 0.3);
}

/* Botones principales */
.btn-primary-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
    border-radius: 12px;
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-outline-secondary {
    border: 2px solid #e9ecef;
    transition: all 0.2s ease;
    background: transparent;
    border-radius: 12px;
}

.btn-outline-secondary:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
    color: white;
    transform: translateY(-2px);
}

/* Formularios */
.form-control-lg,
.form-select-lg {
    font-size: 0.95rem;
    border: 2px solid #e9ecef;
    transition: all 0.2s ease;
    background-color: white;
    border-radius: 12px;
}

.form-control-lg:focus,
.form-select-lg:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Tabla */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table {
    min-width: 1100px;
    width: 100%;
    margin-bottom: 0;
}

.table td {
    padding: 1rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.table tbody tr {
    transition: all 0.2s ease-in-out;
}

.table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.04);
    transform: translateX(2px);
}

/* Paginación */
.pagination {
    margin-bottom: 0;
    gap: 4px;
}

.page-item .page-link {
    border-radius: 10px !important;
    margin: 0;
    color: #667eea;
    border: none;
    padding: 0.5rem 0.85rem;
    font-size: 0.85rem;
    transition: all 0.2s;
    background-color: transparent;
}

.page-item .page-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-2px);
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
}

.page-item.disabled .page-link {
    background-color: transparent;
    color: #a0a0a0;
}

/* Skeleton Loader */
.skeleton-box {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: 8px;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }

    100% {
        background-position: -200% 0;
    }
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2.5rem;
    color: #667eea;
}

.empty-state-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.empty-state-text {
    color: #64748b;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

/* Animaciones */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate__fadeInUp {
    animation: fadeInUp 0.4s ease-out forwards;
}

/* ============================================
   DARK MODE - Estilos corregidos
   ============================================ */
body.dark-mode .stat-card-primary .stat-icon {
    background: rgba(102, 126, 234, 0.2);
}

body.dark-mode .stat-card-success .stat-icon {
    background: rgba(16, 185, 129, 0.2);
}

body.dark-mode .stat-card-warning .stat-icon {
    background: rgba(245, 158, 11, 0.2);
}

body.dark-mode .stat-card-info .stat-icon {
    background: rgba(59, 130, 246, 0.2);
}

body.dark-mode .table-header {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border-bottom-color: #334155;
}

body.dark-mode .table-header th {
    color: #e2e8f0;
}

body.dark-mode .ordenar-link {
    color: #e2e8f0;
}

body.dark-mode .ordenar-link:hover {
    color: #818cf8;
}

body.dark-mode .card {
    background-color: #1a1a2e;
    border-color: rgba(255, 255, 255, 0.05);
}

body.dark-mode .bg-white {
    background-color: #1a1a2e !important;
}

body.dark-mode .table td {
    border-bottom-color: rgba(255, 255, 255, 0.05);
    color: #e0e0e0;
}

body.dark-mode .table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.08);
}

body.dark-mode .badge-pendiente {
    background: rgba(239, 108, 0, 0.2);
    color: #ffb74d;
}

body.dark-mode .badge-en-proceso {
    background: rgba(21, 101, 192, 0.2);
    color: #64b5f6;
}

body.dark-mode .badge-finalizado {
    background: rgba(46, 125, 50, 0.2);
    color: #81c784;
}

body.dark-mode .usuario-nombre {
    color: #e0e0e0;
}

body.dark-mode .usuario-email {
    color: #a0a0a0;
}

body.dark-mode .fecha-fecha {
    color: #e0e0e0;
}

body.dark-mode .fecha-hora {
    color: #a0a0a0;
}

body.dark-mode .motivo-texto {
    color: #cbd5e1;
}

body.dark-mode .btn-ver {
    background: rgba(21, 101, 192, 0.2);
    color: #64b5f6;
}

body.dark-mode .btn-ver:hover {
    background: #1565c0;
    color: white;
}

body.dark-mode .btn-editar {
    background: rgba(46, 125, 50, 0.2);
    color: #81c784;
}

body.dark-mode .btn-editar:hover {
    background: #2e7d32;
    color: white;
}

body.dark-mode .btn-eliminar {
    background: rgba(198, 40, 40, 0.2);
    color: #ef9a9a;
}

body.dark-mode .btn-eliminar:hover {
    background: #c62828;
    color: white;
}

body.dark-mode .form-control-lg,
body.dark-mode .form-select-lg {
    background-color: #0f0f1a;
    border-color: rgba(102, 126, 234, 0.3);
    color: #e0e0e0;
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

body.dark-mode .page-link {
    background-color: transparent;
    color: #818cf8;
}

body.dark-mode .page-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

body.dark-mode .page-item.disabled .page-link {
    color: #64748b;
}

body.dark-mode .skeleton-box {
    background: linear-gradient(90deg, #2a2a3e 25%, #1a1a2e 50%, #2a2a3e 75%);
    background-size: 200% 100%;
}

body.dark-mode .empty-state-title {
    color: #e2e8f0;
}

body.dark-mode .empty-state-text {
    color: #94a3b8;
}

body.dark-mode .text-muted {
    color: #94a3b8 !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Configuración global
let currentRequest = null;

// Función para mostrar loader
function showLoader() {
    const tbody = document.getElementById('tablaBody');
    if (!tbody) return;

    tbody.innerHTML = '';

    for (let i = 0; i < 5; i++) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-4 py-3"><div class="skeleton-box" style="width: 60px; height: 30px;"></div></td>
            <td class="px-4 py-3"><div class="skeleton-box" style="width: 130px; height: 50px;"></div></td>
            <td class="px-4 py-3"><div class="skeleton-box" style="width: 180px; height: 50px;"></div></td>
            <td class="px-4 py-3"><div class="skeleton-box" style="width: 180px; height: 50px;"></div></td>
            <td class="px-4 py-3"><div class="skeleton-box" style="width: 90px; height: 32px; margin: 0 auto;"></div></td>
            <td class="px-4 py-3"><div class="skeleton-box" style="width: 150px; height: 38px;"></div></td>
            <td class="px-4 py-3"><div class="skeleton-box" style="width: 100px; height: 32px; margin: 0 auto;"></div></td>
            <td class="px-4 py-3"><div class="skeleton-box" style="width: 130px; height: 50px;"></div></td>
            <td class="px-4 py-3"><div class="skeleton-box" style="width: 120px; height: 34px; margin: 0 auto;"></div></td>
        `;
        tbody.appendChild(row);
    }
}

// Función principal para cargar datos vía AJAX
async function loadData(url) {
    if (currentRequest) {
        currentRequest.abort();
    }

    showLoader();
    currentRequest = new AbortController();

    try {
        const response = await fetch(url, {
            signal: currentRequest.signal,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) throw new Error('Network response was not ok');

        const html = await response.text();
        document.getElementById('tablaBody').innerHTML = html;

    } catch (error) {
        if (error.name !== 'AbortError') {
            console.error('Error loading data:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar los datos. Intente nuevamente.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }
    } finally {
        currentRequest = null;
    }
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filtroForm');
    const isAjaxForm = form && form.dataset.ajax === 'true';

    if (isAjaxForm) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const url = new URL(form.action);
            const params = new URLSearchParams(new FormData(form));
            url.search = params.toString();
            loadData(url.toString());
            window.history.pushState({}, '', url.toString());
        });

        let timeoutId;
        const textInputs = form.querySelectorAll('[data-filter-input]');
        textInputs.forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => {
                    form.dispatchEvent(new Event('submit'));
                }, 500);
            });
        });

        const selects = form.querySelectorAll('[data-filter-select]');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                form.dispatchEvent(new Event('submit'));
            });
        });
    }

    window.addEventListener('popstate', function() {
        if (isAjaxForm) {
            loadData(window.location.href);
        } else {
            window.location.reload();
        }
    });
});

// Función global para ver interacción
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
            window.location.href = '{{ route("admin.callcenter.show", "") }}/' + id;
        }
    });
};

// Función global para editar interacción
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
            window.location.href = '{{ route("admin.callcenter.edit", "") }}/' + id;
        }
    });
};

// Función global para eliminar interacción
window.eliminarInteraccion = function(id) {
    Swal.fire({
        title: '¿Eliminar interacción?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-trash-alt me-2"></i>Sí, eliminar',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar',
        background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
        color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#1e293b'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.callcenter.destroy", "") }}/' + id;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);

            Swal.fire({
                title: 'Eliminando...',
                text: 'Procesando solicitud',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    form.submit();
                }
            });
        }
    });
};

// Mostrar mensajes con SweetAlert
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#667eea',
        background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
        color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#333',
        timer: 3000,
        timerProgressBar: true,
        toast: true,
        position: 'top-end',
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: '¡Error!',
        text: '{{ session('error') }}',
        confirmButtonColor: '#d33',
        background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
        color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#333'
    });
@endif
</script>
@endpush