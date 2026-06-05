@extends('administrador.layouts.master')

@section('title', 'Gestión de Universidades - SAINS')

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
                                <i class="fas fa-university fa-2x" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
                            </div>
                            <h1 class="display-5 fw-bold mb-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                                Gestión de Universidades
                            </h1>
                        </div>
                        <p class="text-muted fs-5 mb-0">Administre las universidades de interés para los estudiantes</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.universidades.create') }}" class="btn btn-primary-custom px-4 py-2 shadow-sm">
                            <i class="fas fa-plus-circle me-2"></i>Nueva Universidad
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
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Total Universidades</p>
                                <h2 class="display-4 fw-bold mb-0" style="color: #667eea;">{{ $totalUniversidades ?? $universidades->total() ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-database me-1"></i> Registros activos
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-university fa-2x"></i>
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
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Públicas</p>
                                <h2 class="display-4 fw-bold mb-0" style="color: #10b981;">{{ $publicas ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-landmark me-1"></i> Universidades públicas
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-landmark fa-2x"></i>
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
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Privadas</p>
                                <h2 class="display-4 fw-bold mb-0" style="color: #f59e0b;">{{ $privadas ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-building me-1"></i> Universidades privadas
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-building fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-purple">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Autónomas</p>
                                <h2 class="display-4 fw-bold mb-0" style="color: #6a1b9a;">{{ $autonomas ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-balance-scale me-1"></i> Universidades autónomas
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-balance-scale fa-2x"></i>
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
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4 col-lg-4">
                                <label class="form-label fw-semibold text-muted mb-2">
                                    <i class="fas fa-search me-1"></i>Buscar
                                </label>
                                <input type="text" id="searchInput" class="form-control form-control-lg rounded-3" 
                                       placeholder="Clave, dirección o nombre..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4 col-lg-4">
                                <label class="form-label fw-semibold text-muted mb-2">
                                    <i class="fas fa-map-marker-alt me-1"></i>Estado
                                </label>
                                <select id="estadoFilter" class="form-select form-select-lg rounded-3">
                                    <option value="">Todos los estados</option>
                                    @foreach($estados as $est)
                                        <option value="{{ $est }}" {{ request('estado') == $est ? 'selected' : '' }}>{{ $est }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-lg-4">
                                <div class="d-flex gap-2">
                                    <button id="btnFiltrar" class="btn btn-primary-custom w-100 py-2 rounded-3 shadow-sm">
                                        <i class="fas fa-filter me-2"></i>Filtrar
                                    </button>
                                    <button id="btnLimpiar" class="btn btn-outline-secondary w-100 py-2 rounded-3">
                                        <i class="fas fa-times me-2"></i>Limpiar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de universidades - ESTRUCTURA CORREGIDA -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-semibold mb-0">
                                <i class="fas fa-list me-2 text-primary"></i>Listado de Universidades
                            </h5>
                            <span class="badge bg-primary rounded-pill">{{ $universidades->total() }} registros</span>
                        </div>
                    </div>
                    
                    <!-- Tabla - Sin card-body para que ocupe todo el ancho -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="min-width: 1100px;">
                            <thead class="table-header">
                                <tr>
                                    <th class="py-3 px-4 fw-semibold text-center" style="width: 100px;">
                                        <i class="fas fa-key me-1"></i> Clave
                                    </th>
                                    <th class="py-3 px-4 fw-semibold" style="min-width: 250px;">
                                        <i class="fas fa-university me-1"></i> Universidad / Dirección
                                    </th>
                                    <th class="py-3 px-4 fw-semibold">
                                        <i class="fas fa-map-marker-alt me-1"></i> Estado
                                    </th>
                                    <th class="py-3 px-4 fw-semibold">
                                        <i class="fas fa-city me-1"></i> Municipio
                                    </th>
                                    <th class="py-3 px-4 fw-semibold">
                                        <i class="fas fa-graduation-cap me-1"></i> Carrera
                                    </th>
                                    <th class="py-3 px-4 fw-semibold text-center">
                                        <i class="fas fa-clock me-1"></i> Duración
                                    </th>
                                    <th class="py-3 px-4 fw-semibold text-center">
                                        <i class="fas fa-tag me-1"></i> Tipo
                                    </th>
                                    <th class="py-3 px-4 fw-semibold text-center" style="width: 220px;">
                                        <i class="fas fa-cog me-1"></i> Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @include('administrador.universidades.partials.table_rows', ['universidades' => $universidades])
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación - Footer CORRECTAMENTE UBICADO fuera del card-body -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-4 bg-light border-top">
                        <div class="text-muted small mb-2 mb-md-0">
                            <i class="fas fa-chart-line me-1"></i>
                            Mostrando <span class="fw-semibold text-primary">{{ $universidades->firstItem() ?? 0 }}</span> - 
                            <span class="fw-semibold text-primary">{{ $universidades->lastItem() ?? 0 }}</span> 
                            de <span class="fw-semibold text-primary">{{ $universidades->total() }}</span> registros
                        </div>
                        <div>
                            {{ $universidades->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalles -->
<div class="modal fade" id="showUniversidadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-white bg-opacity-20 p-2">
                        <i class="fas fa-university text-white fa-lg"></i>
                    </div>
                    <h5 class="modal-title text-white fw-bold">Detalles de la Universidad</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="modalUniversidadContent">
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
    /* ===== ESTILOS COMPLETOS CORREGIDOS ===== */
    
    /* Tarjetas de estadísticas */
    .stat-card-primary, 
    .stat-card-success, 
    .stat-card-warning, 
    .stat-card-purple {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        border: none !important;
    }
    
    .stat-card-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        z-index: 1;
    }
    
    .stat-card-success::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #059669);
        z-index: 1;
    }
    
    .stat-card-warning::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f59e0b, #d97706);
        z-index: 1;
    }
    
    .stat-card-purple::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #6a1b9a, #9c27b0);
        z-index: 1;
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
    
    .stat-card-purple .stat-icon {
        background: rgba(106, 27, 154, 0.1);
        color: #6a1b9a;
    }
    
    /* Efecto hover para tarjetas */
    .hover-card {
        transition: all 0.3s ease-in-out;
        cursor: pointer;
    }
    
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
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
    
    /* Badges */
    .badge-publica {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        padding: 6px 16px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        border: none;
    }
    
    .badge-privada {
        background: linear-gradient(135deg, #fed7aa 0%, #fdba74 100%);
        color: #9a3412;
        padding: 6px 16px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        border: none;
    }
    
    .badge-autonoma {
        background: linear-gradient(135deg, #e9d5ff 0%, #d8b4fe 100%);
        color: #4c1d95;
        padding: 6px 16px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        border: none;
    }
    
    .badge-duracion {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        padding: 6px 14px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        border: none;
    }
    
    .badge-clave {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border: none;
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
        box-shadow: 0 4px 12px rgba(21,101,192,0.3);
    }
    
    .btn-editar {
        background: #e8f5e9;
        color: #2e7d32;
    }
    
    .btn-editar:hover {
        background: #2e7d32;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(46,125,50,0.3);
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
        outline: none;
    }
    
    /* Tabla */
    .table {
        margin-bottom: 0;
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
        background-color: rgba(102, 126, 234, 0.04);
        border-left-color: #667eea;
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
        box-shadow: 0 2px 8px rgba(102,126,234,0.4);
    }
    
    /* Empty State */
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
    
    .animate__fast {
        animation-duration: 0.3s;
    }
    
    /* ===== DARK MODE ===== */
    body.dark-mode .table-header {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-bottom-color: #334155;
    }
    
    body.dark-mode .table-header th {
        color: #e2e8f0;
    }
    
    body.dark-mode .card {
        background-color: #1e1e2e;
    }
    
    body.dark-mode .bg-light {
        background-color: #0f0f1a !important;
    }
    
    body.dark-mode .badge-publica {
        background: linear-gradient(135deg, #022c22 0%, #064e3b 100%);
        color: #a7f3d0;
    }
    
    body.dark-mode .badge-privada {
        background: linear-gradient(135deg, #7c2d12 0%, #9a3412 100%);
        color: #fed7aa;
    }
    
    body.dark-mode .badge-autonoma {
        background: linear-gradient(135deg, #2e1065 0%, #4c1d95 100%);
        color: #d8b4fe;
    }
    
    body.dark-mode .badge-duracion {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        color: #bfdbfe;
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
        background-color: transparent;
        color: #667eea;
    }
    
    body.dark-mode .modal-content {
        background-color: #1e1e2e;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function verUniversidad(id) {
        const modal = new bootstrap.Modal(document.getElementById('showUniversidadModal'));
        const modalContent = document.getElementById('modalUniversidadContent');
        
        modalContent.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2 text-muted">Cargando información...</p>
            </div>
        `;
        
        modal.show();
        
        fetch(`/administrador/universidades/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const uni = data.data;
                    modalContent.innerHTML = `
                        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                            <div class="text-center p-4 rounded-4" style="background: rgba(102,126,234,0.05);">
                                <div class="d-inline-flex justify-content-center" style="font-size: 1.1rem; padding: 10px 20px; background: #f8f9fa; border-radius: 20px;">
                                    <i class="fas fa-key me-2 text-primary"></i>
                                    <strong>${escapeHtml(uni.clave || 'Sin clave')}</strong>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="p-3 rounded-3" style="background: rgba(102,126,234,0.05);">
                                        <small class="text-muted d-block mb-1"><i class="fas fa-university me-1"></i> Universidad / Dirección</small>
                                        <strong class="fs-5">${escapeHtml(uni.direccion)}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background: rgba(102,126,234,0.05);">
                                        <small class="text-muted d-block mb-1"><i class="fas fa-map-marker-alt me-1"></i> Ubicación</small>
                                        <strong>${escapeHtml(uni.estado)}${uni.municipio ? ', ' + escapeHtml(uni.municipio) : ''}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background: rgba(102,126,234,0.05);">
                                        <small class="text-muted d-block mb-1"><i class="fas fa-graduation-cap me-1"></i> Carrera</small>
                                        <strong>${escapeHtml(uni.carrera?.nombre || 'No especificada')}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background: rgba(102,126,234,0.05);">
                                        <small class="text-muted d-block mb-1"><i class="fas fa-clock me-1"></i> Duración</small>
                                        <strong>${escapeHtml(uni.duracion || 'No especificada')}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background: rgba(102,126,234,0.05);">
                                        <small class="text-muted d-block mb-1"><i class="fas fa-tag me-1"></i> Tipo</small>
                                        <span class="badge ${uni.tipo === 'PUBLICA' ? 'badge-publica' : (uni.tipo === 'AUTONOMA' ? 'badge-autonoma' : 'badge-privada')}" style="display: inline-flex;">
                                            ${uni.tipo === 'PUBLICA' ? '🏛️ Pública' : (uni.tipo === 'AUTONOMA' ? '⚖️ Autónoma' : '🏢 Privada')}
                                        </span>
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
                console.error('Error:', error);
                modalContent.innerHTML = `<p class="text-center text-danger py-4"><i class="fas fa-exclamation-circle me-2"></i>Error al cargar los datos</p>`;
            });
    }

    function confirmarEliminar(url, nombre) {
        Swal.fire({
            title: '¿Eliminar universidad?',
            html: `<div class="text-center">
                        <p class="mb-2">La universidad <strong class="text-primary">${escapeHtml(nombre)}</strong> será eliminada permanentemente.</p>
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
                form.action = url;
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
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

    function cargarTabla() {
        const search = document.getElementById('searchInput')?.value || '';
        const estado = document.getElementById('estadoFilter')?.value || '';
        
        const url = new URL(window.location.href);
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        
        if (estado) url.searchParams.set('estado', estado);
        else url.searchParams.delete('estado');
        
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }

    // Event Listeners
    document.getElementById('btnFiltrar')?.addEventListener('click', cargarTabla);
    document.getElementById('btnLimpiar')?.addEventListener('click', () => window.location.href = window.location.pathname);
    document.getElementById('searchInput')?.addEventListener('keypress', e => e.key === 'Enter' && cargarTabla());
    document.getElementById('estadoFilter')?.addEventListener('change', cargarTabla);
    
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
            showConfirmButton: false
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