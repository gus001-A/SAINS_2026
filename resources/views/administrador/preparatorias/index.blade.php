@extends('administrador.layouts.master')

@section('title', 'Gestión de Preparatorias - SAINS')

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
                                <i class="fas fa-institution fa-2x" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
                            </div>
                            <h1 class="display-5 fw-bold mb-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                                Gestión de Preparatorias
                            </h1>
                        </div>
                        <p class="text-muted fs-5 mb-0">Administre las instituciones educativas de nivel medio superior</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.preparatorias.create') }}" class="btn btn-primary-custom px-4 py-3 rounded-3 shadow-sm">
                            <i class="fas fa-plus-circle me-2"></i>Nueva Preparatoria
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTADORES RÁPIDOS - Tarjetas de estadísticas -->
        <div class="row mb-4 g-3">
            <!-- Tarjeta 1: Total Preparatorias -->
            <div class="col-sm-6 col-lg-4">
                <div class="stats-card stats-card-primary">
                    <div class="stats-card-body">
                        <div class="stats-card-header">
                            <p class="stats-card-label">Total Preparatorias</p>
                            <div class="stats-card-icon">
                                <i class="fas fa-institution"></i>
                            </div>
                        </div>
                        <h2 class="stats-card-value">{{ $totalPreparatorias ?? $preparatorias->total() ?? 0 }}</h2>
                        <p class="stats-card-footer">
                            <i class="fas fa-building me-1"></i> Instituciones registradas
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2: Públicas -->
            <div class="col-sm-6 col-lg-4">
                <div class="stats-card stats-card-success">
                    <div class="stats-card-body">
                        <div class="stats-card-header">
                            <p class="stats-card-label">Públicas</p>
                            <div class="stats-card-icon">
                                <i class="fas fa-landmark"></i>
                            </div>
                        </div>
                        <h2 class="stats-card-value">{{ $publicas ?? 0 }}</h2>
                        <p class="stats-card-footer">
                            <i class="fas fa-university me-1"></i> Instituciones públicas
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 3: Privadas -->
            <div class="col-sm-6 col-lg-4">
                <div class="stats-card stats-card-warning">
                    <div class="stats-card-body">
                        <div class="stats-card-header">
                            <p class="stats-card-label">Privadas</p>
                            <div class="stats-card-icon">
                                <i class="fas fa-building"></i>
                            </div>
                        </div>
                        <h2 class="stats-card-value">{{ $privadas ?? 0 }}</h2>
                        <p class="stats-card-footer">
                            <i class="fas fa-church me-1"></i> Instituciones privadas
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Filtros -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="filter-card">
                    <div class="filter-card-header">
                        <div class="filter-card-icon">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                        <h5 class="filter-card-title">Filtros de búsqueda</h5>
                    </div>
                    <div class="filter-card-body">
                        <form method="GET" action="{{ route('admin.preparatorias.index') }}" id="filtroForm">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4 col-lg-4">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-search me-1"></i>Buscar
                                    </label>
                                    <input type="text" name="search" class="form-control form-control-lg rounded-3" 
                                           placeholder="Nombre o clave..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>Estado
                                    </label>
                                    <select name="estado" class="form-select form-select-lg rounded-3">
                                        <option value="">Todos los estados</option>
                                        @foreach($estados ?? [] as $est)
                                            <option value="{{ $est }}" {{ request('estado') == $est ? 'selected' : '' }}>{{ $est }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-building me-1"></i>Tipo
                                    </label>
                                    <select name="tipo" class="form-select form-select-lg rounded-3">
                                        <option value="">Todos</option>
                                        <option value="PUBLICO" {{ request('tipo') == 'PUBLICO' ? 'selected' : '' }}>Pública</option>
                                        <option value="PRIVADO" {{ request('tipo') == 'PRIVADO' ? 'selected' : '' }}>Privada</option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary-custom w-100 py-2 rounded-3 shadow-sm">
                                            <i class="fas fa-filter me-2"></i>Filtrar
                                        </button>
                                        <a href="{{ route('admin.preparatorias.index') }}" class="btn btn-outline-secondary w-100 py-2 rounded-3">
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

        <!-- Tabla de preparatorias -->
        <div class="row">
            <div class="col-12">
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th class="col-id">
                                        <i class="fas fa-key me-1"></i> Clave
                                    </th>
                                    <th class="col-name">
                                        <i class="fas fa-institution me-1"></i> Centro Educativo
                                    </th>
                                    <th class="col-state">
                                        <i class="fas fa-map-marker-alt me-1"></i> Estado
                                    </th>
                                    <th class="col-city">
                                        <i class="fas fa-city me-1"></i> Municipio
                                    </th>
                                    <th class="col-locality">
                                        <i class="fas fa-location-dot me-1"></i> Localidad
                                    </th>
                                    <th class="col-shift">
                                        <i class="fas fa-clock me-1"></i> Turno
                                    </th>
                                    <th class="col-type">
                                        <i class="fas fa-tag me-1"></i> Tipo
                                    </th>
                                    <th class="col-actions">
                                        <i class="fas fa-cog me-1"></i> Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tablaBody">
                                @include('administrador.preparatorias.partials.table_rows', ['preparatorias' => $preparatorias])
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Footer con paginación -->
                    <div class="table-footer">
                        <div class="table-footer-info">
                            <i class="fas fa-chart-line me-1"></i>
                            Mostrando <span class="fw-semibold text-primary">{{ $preparatorias->firstItem() ?? 0 }}</span> - 
                            <span class="fw-semibold text-primary">{{ $preparatorias->lastItem() ?? 0 }}</span> 
                            de <span class="fw-semibold text-primary">{{ $preparatorias->total() }}</span> registros
                        </div>
                        <div id="paginationLinks">
                            {{ $preparatorias->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalles -->
<div class="modal fade" id="showPreparatoriaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-institution me-2"></i>Detalles de la Preparatoria
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="modalPreparatoriaContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando información...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ============================================
       ESTILOS MODERNOS PARA GESTIÓN DE PREPARATORIAS
       ============================================ */
    
    /* Stats Cards */
    .stats-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }
    
    .stats-card-body {
        padding: 1.25rem;
    }
    
    .stats-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .stats-card-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin: 0;
    }
    
    .stats-card-icon {
        width: 45px;
        height: 45px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }
    
    .stats-card-primary .stats-card-icon {
        background: linear-gradient(135deg, #667eea15, #764ba215);
        color: #667eea;
    }
    
    .stats-card-success .stats-card-icon {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    
    .stats-card-warning .stats-card-icon {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    
    .stats-card-value {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.25rem;
        line-height: 1;
    }
    
    .stats-card-primary .stats-card-value {
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    
    .stats-card-success .stats-card-value {
        color: #10b981;
    }
    
    .stats-card-warning .stats-card-value {
        color: #f59e0b;
    }
    
    .stats-card-footer {
        font-size: 0.75rem;
        color: #94a3b8;
        margin: 0;
    }
    
    /* Filter Card */
    .filter-card {
        border: none;
        border-radius: 20px;
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }
    
    .filter-card-header {
        padding: 1.25rem 1.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .filter-card-icon {
        width: 35px;
        height: 35px;
        background: rgba(102, 126, 234, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #667eea;
    }
    
    .filter-card-title {
        font-weight: 600;
        margin: 0;
        color: #1e293b;
        font-size: 1.1rem;
    }
    
    .filter-card-body {
        padding: 1.25rem 1.5rem 1.5rem;
    }
    
    /* Table Card */
    .table-card {
        border: none;
        border-radius: 20px;
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }
    
    .custom-table {
        width: 100%;
        margin-bottom: 0;
    }
    
    .custom-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }
    
    .custom-table thead th {
        padding: 1rem 1rem;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #475569;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .custom-table tbody td {
        padding: 1rem 1rem;
        vertical-align: middle;
        font-size: 0.95rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    /* Columnas específicas */
    .custom-table .col-id { width: 120px; }
    .custom-table .col-name { min-width: 300px; }
    .custom-table .col-state { width: 150px; }
    .custom-table .col-city { width: 150px; }
    .custom-table .col-locality { width: 150px; }
    .custom-table .col-shift { width: 100px; }
    .custom-table .col-type { width: 100px; }
    .custom-table .col-actions { width: 180px; text-align: center; }
    
    /* Badges */
    .badge-publica {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .badge-privada {
        background: #fff3e0;
        color: #e65100;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .badge-turno {
        background: #e3f2fd;
        color: #1565c0;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    /* Botones de acción */
    .btn-action {
        padding: 0.5rem 1.2rem;
        margin: 0 4px;
        font-size: 0.85rem;
        border-radius: 25px;
        transition: all 0.25s ease;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        text-decoration: none;
    }
    
    .btn-view {
        background: #e3f2fd;
        color: #1565c0;
    }
    
    .btn-view:hover {
        background: #1565c0;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(21, 101, 192, 0.3);
    }
    
    .btn-edit {
        background: #e8f5e9;
        color: #2e7d32;
    }
    
    .btn-edit:hover {
        background: #2e7d32;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    }
    
    .btn-delete {
        background: #ffebee;
        color: #c62828;
    }
    
    .btn-delete:hover {
        background: #c62828;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(198, 40, 40, 0.3);
    }
    
    /* Formularios */
    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .form-control-lg, .form-select-lg {
        font-size: 0.95rem;
        padding: 0.75rem 1rem;
        border: 2px solid #e9ecef;
        transition: all 0.2s ease;
        background-color: white;
        border-radius: 12px;
    }
    
    .form-control-lg:focus, .form-select-lg:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
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
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
    }
    
    /* Table Footer */
    .table-footer {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        gap: 1rem;
    }
    
    .table-footer-info {
        font-size: 0.85rem;
        font-weight: 500;
        color: #64748b;
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
        padding: 0.5rem 0.9rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s;
        background: transparent;
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
        color: #a0a0a0;
        background: transparent;
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
        margin: 0 auto 1rem;
        font-size: 2.5rem;
        color: #667eea;
    }
    
    .empty-state-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }
    
    .empty-state-text {
        font-size: 0.9rem;
        color: #64748b;
        margin-bottom: 1rem;
    }
    
    /* Tooltip */
    [data-tooltip] {
        position: relative;
        cursor: pointer;
    }
    
    [data-tooltip]:before {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        padding: 4px 8px;
        background: #1e293b;
        color: white;
        font-size: 0.7rem;
        border-radius: 6px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s;
        z-index: 1000;
    }
    
    [data-tooltip]:hover:before {
        opacity: 1;
    }
    
    /* Modal */
    .modal-content {
        background: white;
    }
    
    body.dark-mode .modal-content {
        background: #1e293b;
    }
    
    body.dark-mode .modal-body {
        color: #f1f5f9;
    }
    
    /* ============================================
       DARK MODE
       ============================================ */
    body.dark-mode .stats-card,
    body.dark-mode .filter-card,
    body.dark-mode .table-card {
        background: #1e293b;
    }
    
    body.dark-mode .filter-card-title {
        color: #f1f5f9;
    }
    
    body.dark-mode .custom-table thead {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    }
    
    body.dark-mode .custom-table thead th {
        color: #94a3b8;
        border-bottom-color: #334155;
    }
    
    body.dark-mode .custom-table tbody td {
        border-bottom-color: rgba(255, 255, 255, 0.05);
        color: #e0e0e0;
    }
    
    body.dark-mode .table-footer {
        background: #0f172a;
        border-top-color: #334155;
    }
    
    body.dark-mode .table-footer-info {
        color: #94a3b8;
    }
    
    body.dark-mode .form-control-lg,
    body.dark-mode .form-select-lg {
        background-color: #0f172a;
        border-color: #334155;
        color: #f1f5f9;
    }
    
    body.dark-mode .form-control-lg:focus,
    body.dark-mode .form-select-lg:focus {
        border-color: #667eea;
    }
    
    body.dark-mode .btn-outline-secondary {
        border-color: #334155;
        color: #94a3b8;
    }
    
    body.dark-mode .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
    }
    
    body.dark-mode .badge-publica {
        background: rgba(46, 125, 50, 0.2);
        color: #81c784;
    }
    
    body.dark-mode .badge-privada {
        background: rgba(230, 81, 0, 0.2);
        color: #ffa726;
    }
    
    body.dark-mode .badge-turno {
        background: rgba(21, 101, 192, 0.2);
        color: #64b5f6;
    }
    
    body.dark-mode .btn-view {
        background: rgba(21, 101, 192, 0.2);
        color: #64b5f6;
    }
    
    body.dark-mode .btn-view:hover {
        background: #1565c0;
        color: white;
    }
    
    body.dark-mode .btn-edit {
        background: rgba(46, 125, 50, 0.2);
        color: #81c784;
    }
    
    body.dark-mode .btn-edit:hover {
        background: #2e7d32;
        color: white;
    }
    
    body.dark-mode .btn-delete {
        background: rgba(198, 40, 40, 0.2);
        color: #ef9a9a;
    }
    
    body.dark-mode .btn-delete:hover {
        background: #c62828;
        color: white;
    }
    
    body.dark-mode .page-link {
        background: transparent;
        color: #818cf8;
    }
    
    body.dark-mode .page-item.disabled .page-link {
        color: #64748b;
    }
    
    body.dark-mode .empty-state-title {
        color: #f1f5f9;
    }
    
    body.dark-mode .empty-state-text {
        color: #94a3b8;
    }
    
    body.dark-mode [data-tooltip]:before {
        background: #334155;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .table-footer {
            flex-direction: column;
            text-align: center;
        }
        
        .btn-action span {
            display: none;
        }
        
        .btn-action {
            padding: 0.5rem 1rem;
        }
        
        .stats-card-value {
            font-size: 1.8rem;
        }
        
        .custom-table .col-id,
        .custom-table .col-state,
        .custom-table .col-city,
        .custom-table .col-locality,
        .custom-table .col-shift,
        .custom-table .col-type {
            width: auto;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Auto-submit del formulario de filtros
    document.querySelector('select[name="estado"]')?.addEventListener('change', function() {
        document.getElementById('filtroForm').submit();
    });
    
    document.querySelector('select[name="tipo"]')?.addEventListener('change', function() {
        document.getElementById('filtroForm').submit();
    });
    
    // Debounce para búsqueda automática
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

    // Ver preparatoria en modal
    window.verPreparatoria = function(id) {
        const modal = new bootstrap.Modal(document.getElementById('showPreparatoriaModal'));
        const modalContent = document.getElementById('modalPreparatoriaContent');
        
        modalContent.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2 text-muted">Cargando información...</p>
            </div>
        `;
        
        modal.show();
        
        fetch(`/administrador/preparatorias/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const prepa = data.data;
                    modalContent.innerHTML = `
                        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                            <div class="text-center p-4 rounded-4" style="background: rgba(102,126,234,0.05);">
                                <div class="d-inline-flex justify-content-center" style="font-size: 1.1rem; padding: 10px 20px; background: #f8f9fa; border-radius: 20px;">
                                    <i class="fas fa-key me-2 text-primary"></i>
                                    <strong>${escapeHtml(prepa.clave || 'Sin clave')}</strong>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="p-3 rounded-3" style="background: rgba(102,126,234,0.05);">
                                        <small class="text-muted d-block mb-1"><i class="fas fa-institution me-1"></i> Centro Educativo</small>
                                        <strong class="fs-5">${escapeHtml(prepa.centro_educativo)}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background: rgba(102,126,234,0.05);">
                                        <small class="text-muted d-block mb-1"><i class="fas fa-map-marker-alt me-1"></i> Ubicación</small>
                                        <strong>${escapeHtml(prepa.estado)}${prepa.municipio ? ', ' + escapeHtml(prepa.municipio) : ''}${prepa.localidad ? ' - ' + escapeHtml(prepa.localidad) : ''}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background: rgba(102,126,234,0.05);">
                                        <small class="text-muted d-block mb-1"><i class="fas fa-clock me-1"></i> Turno</small>
                                        <strong>${prepa.turno || 'No especificado'}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background: rgba(102,126,234,0.05);">
                                        <small class="text-muted d-block mb-1"><i class="fas fa-tag me-1"></i> Tipo</small>
                                        <span class="${prepa.tipo === 'PUBLICO' ? 'badge-publica' : 'badge-privada'}">
                                            ${prepa.tipo === 'PUBLICO' ? 'Pública' : 'Privada'}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 rounded-3" style="background: rgba(102,126,234,0.05);">
                                        <small class="text-muted d-block mb-1"><i class="fas fa-address-card me-1"></i> Dirección</small>
                                        <strong>${escapeHtml(prepa.direccion || 'No registrada')}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    modalContent.innerHTML = `<p class="text-center text-danger py-4"><i class="fas fa-exclamation-circle me-2"></i>Error al cargar los datos</p>`;
                }
            })
            .catch(error => {
                modalContent.innerHTML = `<p class="text-center text-danger py-4"><i class="fas fa-exclamation-circle me-2"></i>Error al cargar los datos</p>`;
            });
    };

    // Confirmación de eliminación
    window.confirmarEliminar = function(url, nombre) {
        Swal.fire({
            title: '¿Eliminar preparatoria?',
            html: `
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-institution fa-3x" style="color: #dc3545;"></i>
                    </div>
                    <p class="mb-2">Estás a punto de eliminar la preparatoria:</p>
                    <strong class="fs-4" style="background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; background-clip: text; color: transparent;">${escapeHtml(nombre)}</strong>
                    <div class="alert alert-danger mt-3" style="border-radius: 12px;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Esta acción es irreversible y eliminará todos los datos asociados.
                    </div>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-trash-alt me-2"></i>Sí, eliminar',
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
                form.action = url;
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
    };
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Notificaciones
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false,
            background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#f1f5f9' : '#1e293b'
        });
    @endif
    
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#f1f5f9' : '#1e293b'
        });
    @endif
</script>
@endpush