@extends('administrador.layouts.master')

@section('title', 'Gestión de Pagos - SAINS')

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
                                <i class="fas fa-credit-card fa-2x" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
                            </div>
                            <h1 class="display-5 fw-bold mb-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                                Gestión de Pagos
                            </h1>
                        </div>
                        <p class="text-muted fs-5 mb-0">Administre y supervise los pagos realizados por los estudiantes</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.pagos.create') }}" class="btn btn-primary-custom px-4 py-2 shadow-sm">
                            <i class="fas fa-plus-circle me-2"></i>Nuevo Pago
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTADORES RÁPIDOS -->
        <div class="row mb-4 g-3">
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-primary">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Total Recaudado</p>
                                <h2 class="display-4 fw-bold mb-0" style="color: #667eea;">${{ number_format($totalPagos ?? 0, 2) }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-dollar-sign me-1"></i> Monto total aprobado
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-dollar-sign fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-warning">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Pendientes</p>
                                <h2 class="display-4 fw-bold mb-0" style="color: #f59e0b;">{{ $pagosPendientes ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-clock me-1"></i> Por revisar
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-success">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Aprobados</p>
                                <h2 class="display-4 fw-bold mb-0" style="color: #10b981;">{{ $pagosAprobados ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-check-circle me-1"></i> Pagos confirmados
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-danger">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Rechazados</p>
                                <h2 class="display-4 fw-bold mb-0" style="color: #ef4444;">{{ $pagosRechazados ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-times-circle me-1"></i> No aprobados
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-times-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Filtros -->
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
                        <form method="GET" action="{{ route('admin.pagos.index') }}" id="filtroForm">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4 col-lg-3">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-search me-1"></i>Buscar pago
                                    </label>
                                    <input type="text" name="search" class="form-control form-control-lg rounded-3"
                                        placeholder="Nombre del alumno o referencia..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-4 col-lg-3">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-tag me-1"></i>Tipo de pago
                                    </label>
                                    <select name="tipo_pago" class="form-select form-select-lg rounded-3">
                                        <option value="">Todos los tipos</option>
                                        <option value="Bancario" {{ request('tipo_pago') == 'Bancario' ? 'selected' : '' }}>🏦 Bancario</option>
                                        <option value="Oxxo" {{ request('tipo_pago') == 'Oxxo' ? 'selected' : '' }}>🏪 Oxxo</option>
                                        <option value="Transferencia" {{ request('tipo_pago') == 'Transferencia' ? 'selected' : '' }}>💸 Transferencia</option>
                                    </select>
                                </div>
                                <div class="col-md-4 col-lg-3">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-check-circle me-1"></i>Estado
                                    </label>
                                    <select name="estatus" class="form-select form-select-lg rounded-3">
                                        <option value="">Todos los estados</option>
                                        <option value="pendiente" {{ request('estatus') == 'pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                                        <option value="aprobado" {{ request('estatus') == 'aprobado' ? 'selected' : '' }}>✅ Aprobado</option>
                                        <option value="rechazado" {{ request('estatus') == 'rechazado' ? 'selected' : '' }}>❌ Rechazado</option>
                                        <option value="cancelado" {{ request('estatus') == 'cancelado' ? 'selected' : '' }}>🚫 Cancelado</option>
                                    </select>
                                </div>
                                <input type="hidden" name="orden_campo" id="orden_campo" value="{{ request('orden_campo', 'id') }}">
                                <input type="hidden" name="orden_direccion" id="orden_direccion" value="{{ request('orden_direccion', 'desc') }}">
                                <div class="col-md-12 col-lg-3">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary-custom w-100 py-2 rounded-3 shadow-sm">
                                            <i class="fas fa-filter me-2"></i>Filtrar
                                        </button>
                                        <a href="{{ route('admin.pagos.index') }}" class="btn btn-outline-secondary w-100 py-2 rounded-3">
                                            <i class="fas fa-times me-2"></i>Limpiar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de pagos (INCLUYE EL PARTIAL) -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-semibold mb-0">
                                <i class="fas fa-list me-2 text-primary"></i>Listado de Pagos
                            </h5>
                            <span class="badge bg-primary rounded-pill">{{ $pagos->total() }} registros</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="min-width: 1200px;">
                                <thead class="table-header">
                                    <tr>
                                        <th class="py-3 px-4 fw-semibold text-center" style="width: 70px;">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="id">
                                                <i class="fas fa-hashtag me-1"></i> ID
                                                @if(request('orden_campo') == 'id')
                                                    <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                    <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="tipo_pago">
                                                <i class="fas fa-tag me-1"></i> Tipo
                                                @if(request('orden_campo') == 'tipo_pago')
                                                    <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                    <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="alumno_pago">
                                                <i class="fas fa-user-graduate me-1"></i> Estudiante
                                                @if(request('orden_campo') == 'alumno_pago')
                                                    <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                    <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold text-center">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="monto_pago">
                                                <i class="fas fa-dollar-sign me-1"></i> Monto
                                                @if(request('orden_campo') == 'monto_pago')
                                                    <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                    <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="fecha_pago">
                                                <i class="fas fa-calendar-alt me-1"></i> Fecha
                                                @if(request('orden_campo') == 'fecha_pago')
                                                    <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                    <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold">
                                            <i class="fas fa-barcode me-1"></i> Referencia
                                        </th>
                                        <th class="py-3 px-4 fw-semibold">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="estatus">
                                                <i class="fas fa-check-circle me-1"></i> Estado
                                                @if(request('orden_campo') == 'estatus')
                                                    <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                    <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold">
                                            <i class="fas fa-user-check me-1"></i> Revisión
                                        </th>
                                        <th class="py-3 px-4 fw-semibold text-center" style="width: 220px;">
                                            <i class="fas fa-cog me-1"></i> Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- INCLUIMOS EL ARCHIVO PARCIAL --}}
                                    @include('administrador.pagos.partials.table_rows', ['pagos' => $pagos])
                                </tbody>
                            66
                        </div>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-4 bg-light border-top gap-3">
                        <div class="text-muted small">
                            <i class="fas fa-chart-line me-1"></i>
                            Mostrando <span class="fw-semibold text-primary">{{ $pagos->firstItem() ?? 0 }}</span> - 
                            <span class="fw-semibold text-primary">{{ $pagos->lastItem() ?? 0 }}</span> 
                            de <span class="fw-semibold text-primary">{{ $pagos->total() }}</span> registros
                        </div>
                        <div>
                            {{ $pagos->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Cambiar Estado -->
<div class="modal fade" id="cambiarEstadoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-white bg-opacity-20 p-2">
                        <i class="fas fa-exchange-alt text-white fa-lg"></i>
                    </div>
                    <h5 class="modal-title text-white fw-bold">Cambiar Estado del Pago</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="cambiarEstadoForm" method="POST">
                <div class="modal-body p-4">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted mb-2">
                            <i class="fas fa-info-circle me-2" style="color: #667eea;"></i>Estado actual
                        </label>
                        <input type="text" id="estadoActual" class="form-control rounded-3 bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted mb-2">
                            <i class="fas fa-exchange-alt me-2" style="color: #667eea;"></i>Nuevo estado
                        </label>
                        <select name="estatus" id="nuevoEstado" class="form-select rounded-3" required>
                            <option value="pendiente">⏳ Pendiente</option>
                            <option value="aprobado">✅ Aprobado</option>
                            <option value="rechazado">❌ Rechazado</option>
                            <option value="cancelado">🚫 Cancelado</option>
                        </select>
                    </div>
                    <div class="alert alert-info rounded-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Al cambiar el estado, se notificará al estudiante automáticamente.
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 gap-2">
                    <button type="button" class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary-custom px-4 rounded-3">Cambiar Estado</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ===== ESTILOS COMPLETOS (incluir todos los CSS de la respuesta anterior) ===== */
    .stat-card-primary, .stat-card-success, .stat-card-warning, .stat-card-danger {
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
    .stat-card-danger::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #ef4444, #dc2626);
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
    .stat-card-danger .stat-icon {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    .hover-card {
        transition: all 0.3s ease-in-out;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }
    .ordenar-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #1e293b;
        transition: all 0.2s ease;
        font-weight: 600;
    }
    .ordenar-link:hover {
        color: #667eea;
        transform: translateY(-1px);
    }
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
    .badge-tipo {
        padding: 6px 16px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .badge-bancario {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }
    .badge-oxxo {
        background: linear-gradient(135deg, #fed7aa 0%, #fdba74 100%);
        color: #9a3412;
    }
    .badge-transferencia {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }
    .badge-estado {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }
    .badge-pendiente { 
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }
    .badge-aprobado { 
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }
    .badge-rechazado { 
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }
    .badge-cancelado { 
        background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
        color: #374151;
    }
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
    }
    .btn-ver {
        background: #e3f2fd;
        color: #1565c0;
    }
    .btn-ver:hover {
        background: #1565c0;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(21,101,192,0.3);
    }
    .btn-estado {
        background: #fff3e0;
        color: #e65100;
    }
    .btn-estado:hover {
        background: #e65100;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(230,81,0,0.3);
    }
    .btn-eliminar {
        background: #ffebee;
        color: #c62828;
    }
    .btn-eliminar:hover {
        background: #c62828;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(198,40,40,0.3);
    }
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
    .ref-code {
        font-size: 0.75rem;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        padding: 4px 10px;
        border-radius: 8px;
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: #4b5563;
        border: 1px solid #e5e7eb;
    }
    .table td {
        padding: 1rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    .table tbody tr {
        transition: all 0.2s ease-in-out;
        border-left: 3px solid transparent;
    }
    .table tbody tr:hover {
        background-color: rgba(102,126,234,0.04);
        border-left-color: #667eea;
        transform: translateX(2px);
    }
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
    }
    .page-item .page-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-2px);
    }
    .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(102,126,234,0.4);
    }
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }
    .empty-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));
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
    .form-control-lg, .form-select-lg {
        font-size: 0.95rem;
        border: 2px solid #e9ecef;
        transition: all 0.2s ease;
        background-color: white;
        border-radius: 12px;
    }
    .form-control-lg:focus, .form-select-lg:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    /* Dark Mode */
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
    }
    body.dark-mode .bg-light {
        background-color: #0f0f1a !important;
    }
    body.dark-mode .badge-bancario {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        color: #bfdbfe;
    }
    body.dark-mode .badge-oxxo {
        background: linear-gradient(135deg, #7c2d12 0%, #9a3412 100%);
        color: #fed7aa;
    }
    body.dark-mode .badge-transferencia {
        background: linear-gradient(135deg, #064e3b 0%, #047857 100%);
        color: #a7f3d0;
    }
    body.dark-mode .badge-pendiente {
        background: linear-gradient(135deg, #451a03 0%, #78350f 100%);
        color: #fde68a;
    }
    body.dark-mode .badge-aprobado {
        background: linear-gradient(135deg, #022c22 0%, #064e3b 100%);
        color: #a7f3d0;
    }
    body.dark-mode .badge-rechazado {
        background: linear-gradient(135deg, #450a0a 0%, #7f1d1d 100%);
        color: #fecaca;
    }
    body.dark-mode .badge-cancelado {
        background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
        color: #9ca3af;
    }
    body.dark-mode .btn-ver {
        background: rgba(21, 101, 192, 0.2);
        color: #64b5f6;
    }
    body.dark-mode .btn-ver:hover {
        background: #1565c0;
        color: white;
    }
    body.dark-mode .btn-estado {
        background: rgba(230, 81, 0, 0.2);
        color: #ffa726;
    }
    body.dark-mode .btn-estado:hover {
        background: #e65100;
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
    body.dark-mode .btn-outline-secondary {
        border-color: rgba(102,126,234,0.5);
        color: #e0e0e0;
    }
    body.dark-mode .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
    }
    body.dark-mode .form-control-lg,
    body.dark-mode .form-select-lg {
        background-color: #0f0f1a;
        border-color: rgba(102,126,234,0.3);
        color: #e0e0e0;
    }
    body.dark-mode .table td {
        border-bottom-color: rgba(255, 255, 255, 0.05);
        color: #e0e0e0;
    }
    body.dark-mode .table tbody tr:hover {
        background-color: rgba(102,126,234,0.08);
    }
    body.dark-mode .page-link {
        background-color: #0f0f1a;
        color: #667eea;
    }
    body.dark-mode .ref-code {
        background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
        color: #d1d5db;
        border-color: #4b5563;
    }
    body.dark-mode .modal-content {
        background-color: #1a1a2e;
    }
    body.dark-mode .alert-info {
        background-color: rgba(6, 182, 212, 0.1);
        border-color: rgba(6, 182, 212, 0.3);
        color: #67e8f9;
    }
    body.dark-mode .empty-state-title {
        color: #e2e8f0;
    }
    body.dark-mode .empty-state-text {
        color: #94a3b8;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Ordenamiento dinámico
    document.querySelectorAll('.ordenar-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const campo = this.dataset.campo;
            const direccionActual = document.getElementById('orden_direccion').value;
            const campoActual = document.getElementById('orden_campo').value;

            let nuevaDireccion = 'asc';
            if (campoActual === campo && direccionActual === 'asc') {
                nuevaDireccion = 'desc';
            }

            document.getElementById('orden_campo').value = campo;
            document.getElementById('orden_direccion').value = nuevaDireccion;
            document.getElementById('filtroForm').submit();
        });
    });

    // Auto-submit con debounce
    let timeoutId;
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                document.getElementById('filtroForm').submit();
            }, 500);
        });
    }

    // Auto-submit al cambiar selects
    document.querySelectorAll('select[name="tipo_pago"], select[name="estatus"]').forEach(select => {
        if (select) select.addEventListener('change', () => document.getElementById('filtroForm').submit());
    });

    function verPago(id) {
        window.location.href = '{{ route("admin.pagos.show", "") }}/' + id;
    }

    function cambiarEstado(id, estadoActual) {
        const estadoText = {
            'pendiente': 'Pendiente',
            'aprobado': 'Aprobado',
            'rechazado': 'Rechazado',
            'cancelado': 'Cancelado'
        };
        
        document.getElementById('estadoActual').value = estadoText[estadoActual] || estadoActual;
        
        const form = document.getElementById('cambiarEstadoForm');
        form.action = '{{ route("admin.pagos.cambiar.estado", "") }}/' + id;
        
        const modal = new bootstrap.Modal(document.getElementById('cambiarEstadoModal'));
        modal.show();
    }

    function eliminarPago(id, nombre) {
        Swal.fire({
            title: '¿Eliminar pago?',
            html: `<div class="text-center">
                        <p class="mb-2">El pago de <strong class="text-primary">${escapeHtml(nombre)}</strong> será eliminado permanentemente.</p>
                        <div class="alert alert-danger mt-3" style="border-radius: 12px;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Esta acción no se puede deshacer.
                        </div>
                    </div>`,
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
                form.action = '{{ route("admin.pagos.destroy", "") }}/' + id;
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

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Notificaciones con SweetAlert
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#667eea',
            background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#333',
            timer: 3000,
            timerProgressBar: true
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#dc2626',
            background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#333'
        });
    @endif
</script>
@endpush