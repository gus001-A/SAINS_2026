@extends('administrador.layouts.master')

@section('title', 'Gestión de Exámenes - SAINS')

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
                                <i class="fas fa-file-alt fa-2x" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
                            </div>
                            <h1 class="display-5 fw-bold mb-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                                Gestión de Exámenes
                            </h1>
                        </div>
                        <p class="text-muted fs-5 mb-0">Administre los exámenes disponibles en la plataforma</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.examenes.create') }}" class="btn btn-primary-custom px-4 py-2 shadow-sm">
                            <i class="fas fa-plus-circle me-2"></i>Nuevo Examen
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTADORES RÁPIDOS - Tarjetas de estadísticas -->
        <div class="row mb-4 g-3">
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-primary">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Total Exámenes</p>
                                <h2 class="display-4 fw-bold mb-0">{{ $totalExamenes ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-database me-1"></i> Registros activos
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-file-alt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-info">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Por Materia</p>
                                <h2 class="display-4 fw-bold mb-0">{{ $examenesMateria ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-book me-1"></i> Exámenes de materia
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-book fa-2x"></i>
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
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Simulación</p>
                                <h2 class="display-4 fw-bold mb-0">{{ $examenesSimulacion ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-chart-line me-1"></i> Tipo simulación
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-chart-line fa-2x"></i>
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
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">General</p>
                                <h2 class="display-4 fw-bold mb-0">{{ $examenesGeneral ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-globe me-1"></i> Curso general
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-globe fa-2x"></i>
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
                        <form method="GET" action="{{ route('admin.examenes.index') }}" id="filtroForm">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3 col-lg-3">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-search me-1"></i>Buscar
                                    </label>
                                    <input type="text" name="search" class="form-control form-control-lg rounded-3" 
                                           placeholder="ID, tipo de examen..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-tag me-1"></i>Tipo de examen
                                    </label>
                                    <select name="tipo" class="form-select form-select-lg rounded-3">
                                        <option value="">Todos</option>
                                        <option value="Materia" {{ request('tipo') == 'Materia' ? 'selected' : '' }}>Materia</option>
                                        <option value="General del curso" {{ request('tipo') == 'General del curso' ? 'selected' : '' }}>General del curso</option>
                                        <option value="Simulación" {{ request('tipo') == 'Simulación' ? 'selected' : '' }}>Simulación</option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-sort-amount-down me-1"></i>Rango preguntas
                                    </label>
                                    <select name="rango" class="form-select form-select-lg rounded-3">
                                        <option value="">Todos</option>
                                        <option value="0-20" {{ request('rango') == '0-20' ? 'selected' : '' }}>0 - 20 preguntas</option>
                                        <option value="21-50" {{ request('rango') == '21-50' ? 'selected' : '' }}>21 - 50 preguntas</option>
                                        <option value="51-100" {{ request('rango') == '51-100' ? 'selected' : '' }}>51 - 100 preguntas</option>
                                        <option value="100+" {{ request('rango') == '100+' ? 'selected' : '' }}>Más de 100</option>
                                    </select>
                                </div>
                                <!-- Campos ocultos para mantener el ordenamiento -->
                                <input type="hidden" name="orden_campo" id="orden_campo" value="{{ request('orden_campo', 'id') }}">
                                <input type="hidden" name="orden_direccion" id="orden_direccion" value="{{ request('orden_direccion', 'desc') }}">
                                <div class="col-md-3 col-lg-3">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary-custom w-100 py-2 rounded-3 shadow-sm">
                                            <i class="fas fa-filter me-2"></i>Filtrar
                                        </button>
                                        <a href="{{ route('admin.examenes.index') }}" class="btn btn-outline-secondary w-100 py-2 rounded-3">
                                            <i class="fas fa-times me-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de exámenes -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-semibold mb-0">
                                <i class="fas fa-list me-2 text-primary"></i>Listado de Exámenes
                            </h5>
                            <span class="badge bg-primary rounded-pill">{{ $examenes->total() }} registros</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="min-width: 900px;">
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
                                        <th class="py-3 px-4 fw-semibold" style="min-width: 200px;">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="tipo_examen">
                                                <i class="fas fa-tag me-1"></i> Tipo de Examen
                                                @if(request('orden_campo') == 'tipo_examen')
                                                    <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                    <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold text-center" style="width: 130px;">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="numero_preguntas">
                                                <i class="fas fa-question-circle me-1"></i> Preguntas
                                                @if(request('orden_campo') == 'numero_preguntas')
                                                    <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                    <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold text-center" style="width: 120px;">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="tiempo">
                                                <i class="fas fa-clock me-1"></i> Tiempo
                                                @if(request('orden_campo') == 'tiempo')
                                                    <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                    <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold" style="min-width: 160px;">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="created_at">
                                                <i class="fas fa-calendar-alt me-1"></i> Fecha de Creación
                                                @if(request('orden_campo') == 'created_at')
                                                    <i class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                    <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold text-center" style="width: 220px;">
                                            <i class="fas fa-cog me-1"></i> Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tablaBody">
                                    @include('administrador.examenes.partials.table_rows', ['examenes' => $examenes])
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Paginación -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-4 bg-light border-top gap-3">
                        <div class="text-muted small">
                            <i class="fas fa-chart-line me-1"></i>
                            Mostrando <span class="fw-semibold text-primary">{{ $examenes->firstItem() ?? 0 }}</span> - 
                            <span class="fw-semibold text-primary">{{ $examenes->lastItem() ?? 0 }}</span> 
                            de <span class="fw-semibold text-primary">{{ $examenes->total() }}</span> registros
                        </div>
                        <div id="paginationLinks" class="d-flex justify-content-end">
                            {{ $examenes->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Duplicar Examen -->
<div class="modal fade" id="duplicarExamenModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-white bg-opacity-20 p-2">
                        <i class="fas fa-copy text-white fa-lg"></i>
                    </div>
                    <h5 class="modal-title text-white fw-bold">Duplicar Examen</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="duplicarForm" method="POST">
                <div class="modal-body p-4">
                    @csrf
                    @method('POST')
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted mb-2">
                            <i class="fas fa-tag me-2 text-primary"></i>Tipo de examen actual
                        </label>
                        <input type="text" id="tipoActual" class="form-control rounded-3 bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted mb-2">
                            <i class="fas fa-question-circle me-2 text-primary"></i>Número de preguntas
                        </label>
                        <input type="number" id="numPreguntas" class="form-control rounded-3 bg-light" readonly>
                    </div>
                    <div class="alert alert-info rounded-3">
                        <i class="fas fa-info-circle me-2"></i>
                        El examen será duplicado con las mismas características.
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary-custom px-4 rounded-3">
                        <i class="fas fa-copy me-2"></i>Duplicar Examen
                    </button>
                </div>
            </form>
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
    
    @media (min-width: 1600px) {
        .container-fluid {
            padding-right: 0.25rem !important;
            padding-left: 0.25rem !important;
        }
    }
    
    /* Tarjetas de estadísticas */
    .stat-card-primary, .stat-card-success, .stat-card-warning, .stat-card-info {
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
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
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
    
    /* Badges de tipos de examen */
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
    
    .badge-materia {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    
    .badge-general {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        color: white;
    }
    
    .badge-simulacion {
        background: linear-gradient(135deg, #fa709a, #fee140);
        color: white;
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
    
    .btn-duplicar {
        background: #fff3e0;
        color: #e65100;
    }
    
    .btn-duplicar:hover {
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
    
    /* Badges de indicadores */
    .pregunta-badge {
        background: rgba(102,126,234,0.1);
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
        color: #667eea;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .tiempo-badge {
        background: rgba(76,175,80,0.1);
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
        color: #4caf50;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    /* Formularios */
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
    
    /* Tabla */
    .table td {
        padding: 1rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .table tbody tr {
        transition: all 0.2s ease-in-out;
    }
    
    .table tbody tr:hover {
        background-color: rgba(102,126,234,0.04);
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
    
    body.dark-mode .badge-materia {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }
    
    body.dark-mode .badge-general {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
    }
    
    body.dark-mode .badge-simulacion {
        background: linear-gradient(135deg, #fa709a, #fee140);
    }
    
    body.dark-mode .btn-ver {
        background: rgba(21, 101, 192, 0.2);
        color: #64b5f6;
    }
    
    body.dark-mode .btn-ver:hover {
        background: #1565c0;
        color: white;
    }
    
    body.dark-mode .btn-duplicar {
        background: rgba(230, 81, 0, 0.2);
        color: #ffa726;
    }
    
    body.dark-mode .btn-duplicar:hover {
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
    
    body.dark-mode .modal-content {
        background-color: #1a1a2e;
    }
    
    body.dark-mode .pregunta-badge {
        background: rgba(102,126,234,0.2);
        color: #a5b4fc;
    }
    
    body.dark-mode .tiempo-badge {
        background: rgba(76,175,80,0.2);
        color: #81c784;
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
    // Variables globales para el modal de duplicar
    let examenIdADuplicar = null;
    
    // Ordenamiento dinámico
    document.querySelectorAll('.ordenar-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const campo = this.dataset.campo;
            const direccionActual = '{{ request("orden_direccion", "desc") }}';
            const campoActual = '{{ request("orden_campo", "id") }}';
            
            let nuevaDireccion = 'asc';
            if (campoActual === campo && direccionActual === 'asc') {
                nuevaDireccion = 'desc';
            }
            
            // Actualizar los campos ocultos del formulario
            document.getElementById('orden_campo').value = campo;
            document.getElementById('orden_direccion').value = nuevaDireccion;
            
            // Enviar el formulario
            document.getElementById('filtroForm').submit();
        });
    });
    
    // Auto-submit del formulario de filtros (debounce)
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
    document.querySelector('select[name="tipo"]')?.addEventListener('change', function() {
        document.getElementById('filtroForm').submit();
    });
    
    document.querySelector('select[name="rango"]')?.addEventListener('change', function() {
        document.getElementById('filtroForm').submit();
    });
    
    function duplicarExamen(id, tipo, numPreguntas) {
        examenIdADuplicar = id;
        document.getElementById('tipoActual').value = tipo;
        document.getElementById('numPreguntas').value = numPreguntas;
        
        const form = document.getElementById('duplicarForm');
        form.action = `/administrador/examenes/${id}/duplicar`;
        
        const modal = new bootstrap.Modal(document.getElementById('duplicarExamenModal'));
        modal.show();
    }
    
    function eliminarExamen(id, tipo) {
        Swal.fire({
            title: '¿Eliminar examen?',
            html: `
                <div class="text-center">
                    <p class="mb-2">Estás a punto de eliminar el examen:</p>
                    <strong class="fs-4" style="color: #667eea;">"${escapeHtml(tipo)}"</strong>
                    <div class="alert alert-danger mt-3" style="border-radius: 12px;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Esta acción eliminará el examen y todas sus preguntas asociadas.
                    </div>
                </div>
            `,
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
                form.action = `/administrador/examenes/${id}`;
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
</script>
@endpush