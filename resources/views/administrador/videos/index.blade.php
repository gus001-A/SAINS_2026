@extends('administrador.layouts.master')

@section('title', 'Gestión de Videos - SAINS')

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
                                <i class="fas fa-video fa-2x" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
                            </div>
                            <h1 class="display-5 fw-bold mb-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                                Gestión de Videos
                            </h1>
                        </div>
                        <p class="text-muted fs-5 mb-0">Administre los videos educativos del sistema</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.videos.create') }}" class="btn btn-primary-custom px-4 py-3 rounded-3 shadow-sm">
                            <i class="fas fa-plus-circle me-2"></i>Nuevo Video
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTADORES RÁPIDOS - Tarjetas de estadísticas -->
        <div class="row mb-4 g-3">
            <div class="col-sm-6 col-lg-4">
                <div class="stats-card stats-card-primary">
                    <div class="stats-card-body">
                        <div class="stats-card-header">
                            <p class="stats-card-label">Total Videos</p>
                            <div class="stats-card-icon">
                                <i class="fas fa-video"></i>
                            </div>
                        </div>
                        <h2 class="stats-card-value">{{ $totalVideos ?? 0 }}</h2>
                        <p class="stats-card-footer">
                            <i class="fas fa-database me-1"></i> Videos registrados
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="stats-card stats-card-success">
                    <div class="stats-card-body">
                        <div class="stats-card-header">
                            <p class="stats-card-label">Con Progresos</p>
                            <div class="stats-card-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                        <h2 class="stats-card-value">{{ $conProgresos ?? 0 }}</h2>
                        <p class="stats-card-footer">
                            <i class="fas fa-play-circle me-1"></i> Videos con actividad
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="stats-card stats-card-warning">
                    <div class="stats-card-body">
                        <div class="stats-card-header">
                            <p class="stats-card-label">Plan Gratuito</p>
                            <div class="stats-card-icon">
                                <i class="fas fa-gratipay"></i>
                            </div>
                        </div>
                        <h2 class="stats-card-value">{{ $planGratuito ?? 0 }}</h2>
                        <p class="stats-card-footer">
                            <i class="fas fa-gift me-1"></i> Videos gratuitos
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
                        <form method="GET" action="{{ route('admin.videos.index') }}" id="filtroForm">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-6 col-lg-7">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-search me-1"></i>Buscar video
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0 rounded-start-3">
                                            <i class="fas fa-video text-primary"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control border-start-0 rounded-end-3 form-control-lg" 
                                               placeholder="Título, materia o tema..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-gratipay me-1"></i>Plan
                                    </label>
                                    <select name="plan" class="form-select form-select-lg rounded-3">
                                        <option value="">Todos</option>
                                        <option value="1" {{ request('plan') == '1' ? 'selected' : '' }}>Gratuito</option>
                                        <option value="0" {{ request('plan') == '0' ? 'selected' : '' }}>Premium</option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-lg-2">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary-custom w-100 py-2 rounded-3 shadow-sm">
                                            <i class="fas fa-filter me-2"></i>Filtrar
                                        </button>
                                        <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-secondary w-100 py-2 rounded-3">
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

        <!-- Tabla de videos -->
        <div class="row">
            <div class="col-12">
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th class="col-id">
                                        <a href="#" class="ordenar-link" data-campo="id">
                                            <i class="fas fa-hashtag me-1"></i> ID
                                            @if(request('orden_campo') == 'id')
                                                <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort opacity-50"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="col-info">
                                        <a href="#" class="ordenar-link" data-campo="titulo">
                                            <i class="fas fa-info-circle me-1"></i> Información
                                            @if(request('orden_campo') == 'titulo')
                                                <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort opacity-50"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="col-materia">
                                        <a href="#" class="ordenar-link" data-campo="materia">
                                            <i class="fas fa-book me-1"></i> Materia
                                            @if(request('orden_campo') == 'materia')
                                                <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort opacity-50"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="col-duration text-center">
                                        <a href="#" class="ordenar-link" data-campo="duracion">
                                            <i class="fas fa-clock me-1"></i> Duración
                                            @if(request('orden_campo') == 'duracion')
                                                <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort opacity-50"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="col-plan text-center">
                                        <a href="#" class="ordenar-link" data-campo="plan">
                                            <i class="fas fa-gratipay me-1"></i> Plan
                                            @if(request('orden_campo') == 'plan')
                                                <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort opacity-50"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="col-actions text-center">
                                        <i class="fas fa-cog me-1"></i> Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tablaBody">
                                @include('administrador.videos.partials.table_rows', ['videos' => $videos])
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    <div class="table-footer">
                        <div class="table-footer-info">
                            <i class="fas fa-chart-line me-1"></i>
                            Mostrando <span class="fw-semibold text-primary">{{ $videos->firstItem() ?? 0 }}</span> - 
                            <span class="fw-semibold text-primary">{{ $videos->lastItem() ?? 0 }}</span> 
                            de <span class="fw-semibold text-primary">{{ $videos->total() }}</span> registros
                        </div>
                        <div id="paginationLinks">
                            {{ $videos->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ============================================
       ESTILOS MODERNOS PARA GESTIÓN DE VIDEOS
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
    .custom-table .col-id { width: 80px; }
    .custom-table .col-info { min-width: 320px; }
    .custom-table .col-materia { min-width: 200px; }
    .custom-table .col-duration { width: 120px; text-align: center; }
    .custom-table .col-plan { width: 120px; text-align: center; }
    .custom-table .col-actions { width: 220px; text-align: center; }
    
    .ordenar-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #475569;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 0.85rem;
        font-weight: 700;
    }
    
    .ordenar-link:hover {
        color: #667eea;
        transform: translateY(-1px);
    }
    
    /* Video Icon */
    .video-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #667eea15, #764ba215);
    }
    
    tr:hover .video-icon {
        transform: scale(1.05);
    }
    
    .video-icon i {
        font-size: 1.2rem;
        color: #667eea;
    }
    
    .video-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .video-detalles {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    
    .video-titulo {
        font-weight: 700;
        font-size: 0.95rem;
        color: #1e293b;
    }
    
    .video-tema {
        font-size: 0.7rem;
        color: #94a3b8;
    }
    
    /* Badges */
    .badge-count {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .badge-gratuito {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .badge-premium {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    
    .badge-duration {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }
    
    .badge-empty {
        background: linear-gradient(135deg, #64748b, #475569);
        color: white;
        opacity: 0.6;
    }
    
    .badge-materia {
        background: #f1f5f9;
        color: #475569;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
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
    
    .input-group-text {
        border: 2px solid #e9ecef;
        border-right: none;
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
    
    /* Animación fade-in */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .fade-in {
        animation: fadeIn 0.3s ease-out forwards;
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
    
    body.dark-mode .ordenar-link {
        color: #94a3b8;
    }
    
    body.dark-mode .ordenar-link:hover {
        color: #818cf8;
    }
    
    body.dark-mode .video-titulo {
        color: #f1f5f9;
    }
    
    body.dark-mode .badge-materia {
        background: #334155;
        color: #94a3b8;
    }
    
    body.dark-mode .table-footer {
        background: #0f172a;
        border-top-color: #334155;
    }
    
    body.dark-mode .table-footer-info {
        color: #94a3b8;
    }
    
    body.dark-mode .form-control-lg,
    body.dark-mode .form-select-lg,
    body.dark-mode .input-group-text {
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
        
        .video-icon {
            width: 40px;
            height: 40px;
        }
        
        .video-icon i {
            font-size: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ============================================
    // SISTEMA DE ORDENAMIENTO DE TABLAS
    // ============================================
    
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener todos los enlaces de ordenamiento
        const ordenarLinks = document.querySelectorAll('.ordenar-link');
        
        ordenarLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Obtener el campo por el cual ordenar
                const campo = this.getAttribute('data-campo');
                
                // Obtener la dirección actual del ordenamiento
                const urlParams = new URLSearchParams(window.location.search);
                const ordenCampoActual = urlParams.get('orden_campo');
                const ordenDireccionActual = urlParams.get('orden_direccion');
                
                // Determinar la nueva dirección
                let nuevaDireccion = 'asc';
                if (ordenCampoActual === campo && ordenDireccionActual === 'asc') {
                    nuevaDireccion = 'desc';
                }
                
                // Preservar los filtros existentes
                const search = urlParams.get('search') || '';
                const plan = urlParams.get('plan') || '';
                const page = urlParams.get('page') || '1';
                
                // Construir la nueva URL
                let url = window.location.pathname + '?';
                url += `orden_campo=${campo}`;
                url += `&orden_direccion=${nuevaDireccion}`;
                
                if (search) {
                    url += `&search=${encodeURIComponent(search)}`;
                }
                if (plan !== '') {
                    url += `&plan=${plan}`;
                }
                if (page !== '1') {
                    url += `&page=${page}`;
                }
                
                // Redirigir a la nueva URL
                window.location.href = url;
            });
        });
        
        // Auto-submit del formulario de filtros
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
        
        document.querySelector('select[name="plan"]')?.addEventListener('change', function() {
            document.getElementById('filtroForm').submit();
        });
    });
    
    // Función para eliminar video
    window.confirmarEliminar = function(url, titulo) {
        Swal.fire({
            title: '¿Eliminar video?',
            html: `
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-video fa-3x" style="color: #dc3545;"></i>
                    </div>
                    <p class="mb-2">Estás a punto de eliminar el video:</p>
                    <strong class="fs-4" style="background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; background-clip: text; color: transparent;">${escapeHtml(titulo)}</strong>
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