@extends('administrador.layouts.master')

@section('title', 'Gestión de Preguntas - SAINS')

@section('content')
<div class="container-fluid p-0 p-lg-2">
    <div class="px-2 px-xl-3 px-xxl-4">

        <!-- Header mejorado -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="rounded-3 p-2"
                                style="background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);">
                                <i class="fas fa-brain fa-2x"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
                            </div>
                            <h1 class="display-5 fw-bold mb-0"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                                Banco de Preguntas
                            </h1>
                        </div>
                        <p class="text-muted fs-5 mb-0">Gestiona las preguntas para los exámenes de los estudiantes</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.preguntas.create') }}"
                            class="btn btn-primary-custom px-4 py-2 shadow-sm">
                            <i class="fas fa-plus-circle me-2"></i>Nueva Pregunta
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
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Total Preguntas</p>
                                <h2 class="display-4 fw-bold mb-0">{{ $totalPreguntas ?? $preguntas->total() ?? 0 }}
                                </h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-database me-1"></i> Registros activos
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-database fa-2x"></i>
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
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Áreas Registradas</p>
                                <h2 class="display-4 fw-bold mb-0">{{ $totalAreas ?? $areas->count() ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-layer-group me-1"></i> Categorías
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-layer-group fa-2x"></i>
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
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Con Justificación</p>
                                <h2 class="display-4 fw-bold mb-0">{{ $preguntasConJustificacion ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-file-alt me-1"></i> Retroalimentación
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
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Sin Justificación</p>
                                <h2 class="display-4 fw-bold mb-0">
                                    {{ ($totalPreguntas ?? $preguntas->total() ?? 0) - ($preguntasConJustificacion ?? 0) }}
                                </h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-file-excel me-1"></i> Sin retroalimentación
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-file-excel fa-2x"></i>
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
                        <form method="GET" action="{{ route('admin.preguntas.index') }}" id="filtroForm">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4 col-lg-4">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-search me-1"></i>Buscar pregunta
                                    </label>
                                    <input type="text" name="search" class="form-control form-control-lg rounded-3"
                                        placeholder="Pregunta o contenido..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-layer-group me-1"></i>Área
                                    </label>
                                    <select name="id_area" class="form-select form-select-lg rounded-3">
                                        <option value="">Todas las áreas</option>
                                        @foreach($areas as $area)
                                        <option value="{{ $area->id }}"
                                            {{ request('id_area') == $area->id ? 'selected' : '' }}>{{ $area->nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-file-alt me-1"></i>Justificación
                                    </label>
                                    <select name="has_justificacion" class="form-select form-select-lg rounded-3">
                                        <option value="">Todas</option>
                                        <option value="si" {{ request('has_justificacion') == 'si' ? 'selected' : '' }}>
                                            Con justificación</option>
                                        <option value="no" {{ request('has_justificacion') == 'no' ? 'selected' : '' }}>
                                            Sin justificación</option>
                                    </select>
                                </div>
                                <!-- Campos ocultos para mantener el ordenamiento -->
                                <input type="hidden" name="sort_by" id="sort_by" value="{{ request('sort_by', 'id') }}">
                                <input type="hidden" name="sort_order" id="sort_order"
                                    value="{{ request('sort_order', 'desc') }}">
                                <div class="col-md-2 col-lg-2">
                                    <div class="d-flex gap-2">
                                        <button type="submit"
                                            class="btn btn-primary-custom w-100 py-2 rounded-3 shadow-sm">
                                            <i class="fas fa-filter me-2"></i>Filtrar
                                        </button>
                                        <a href="{{ route('admin.preguntas.index') }}"
                                            class="btn btn-outline-secondary w-100 py-2 rounded-3">
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

        <!-- Tabla de preguntas -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-semibold mb-0">
                                <i class="fas fa-list me-2 text-primary"></i>Listado de Preguntas
                            </h5>
                            <span class="badge bg-primary rounded-pill">{{ $preguntas->total() }} registros</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="min-width: 1000px;">
                                <thead class="table-header">
                                    <tr>
                                        <th class="py-3 px-4 fw-semibold text-center" style="width: 70px;">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="id">
                                                <i class="fas fa-hashtag me-1"></i> ID
                                                @if(request('sort_by') == 'id' || !request('sort_by'))
                                                <i
                                                    class="fas fa-sort-{{ request('sort_order', 'desc') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold" style="min-width: 300px;">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="pregunta">
                                                <i class="fas fa-question-circle me-1"></i> Pregunta
                                                @if(request('sort_by') == 'pregunta')
                                                <i
                                                    class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold" style="width: 150px;">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="id_area">
                                                <i class="fas fa-layer-group me-1"></i> Área
                                                @if(request('sort_by') == 'id_area')
                                                <i
                                                    class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold" style="min-width: 180px;">
                                            <i class="fas fa-list-ul me-1"></i> Respuestas
                                        </th>
                                        <th class="py-3 px-4 fw-semibold" style="width: 150px;">
                                            <a href="#" class="ordenar-link text-decoration-none"
                                                data-campo="respuesta_correcta">
                                                <i class="fas fa-check-circle me-1"></i> Correcta
                                                @if(request('sort_by') == 'respuesta_correcta')
                                                <i
                                                    class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold text-center" style="width: 120px;">
                                            <a href="#" class="ordenar-link text-decoration-none"
                                                data-campo="justificacion">
                                                <i class="fas fa-file-alt me-1"></i> Justificación
                                                @if(request('sort_by') == 'justificacion')
                                                <i
                                                    class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold text-center" style="width: 160px;">
                                            <i class="fas fa-cog me-1"></i> Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tablaBody">
                                    @include('administrador.preguntas.partials.table_rows', ['preguntas' => $preguntas])
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Paginación -->
                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-center p-4 bg-light border-top gap-3">
                        <div class="text-muted small">
                            <i class="fas fa-chart-line me-1"></i>
                            Mostrando <span class="fw-semibold text-primary">{{ $preguntas->firstItem() ?? 0 }}</span> -
                            <span class="fw-semibold text-primary">{{ $preguntas->lastItem() ?? 0 }}</span>
                            de <span class="fw-semibold text-primary">{{ $preguntas->total() }}</span> registros
                        </div>
                        <div id="paginationLinks" class="d-flex justify-content-end">
                            {{ $preguntas->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalles -->
<div class="modal fade" id="showPreguntaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 p-4"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-white bg-opacity-20 p-2">
                        <i class="fas fa-question-circle text-white fa-lg"></i>
                    </div>
                    <h5 class="modal-title text-white fw-bold">Detalles de la Pregunta</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="modalPreguntaContent">
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
.stat-card-warning,
.stat-card-info {
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

/* Badge área */
.badge-area {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

/* Badge respuesta correcta */
.badge-correcta {
    background: #e8f5e9;
    color: #2e7d32;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

/* Contenedor de respuestas */
.answers-container {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.answer-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    background: #f1f5f9;
    border-radius: 12px;
    font-size: 0.75rem;
    color: #475569;
    max-width: 220px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.answer-item i {
    color: #667eea;
    flex-shrink: 0;
}

/* Botones de acción */
.btn-accion {
    padding: 6px 14px;
    margin: 0 2px;
    font-size: 0.75rem;
    border-radius: 25px;
    transition: all 0.2s ease;
    font-weight: 600;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
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

.pregunta-texto {
    max-width: 350px;
    white-space: normal;
    word-wrap: break-word;
    line-height: 1.4;
}

/* Modal */
.justificacion-text {
    white-space: pre-wrap;
    word-wrap: break-word;
    line-height: 1.7;
    max-height: 400px;
    overflow-y: auto;
    padding: 1rem;
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
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
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

body.dark-mode .badge-correcta {
    background: rgba(46, 125, 50, 0.2);
    color: #81c784;
}

body.dark-mode .answer-item {
    background: #0f172a;
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

body.dark-mode .btn-outline-secondary {
    border-color: rgba(102, 126, 234, 0.5);
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
    border-color: rgba(102, 126, 234, 0.3);
    color: #e0e0e0;
}

body.dark-mode .table td {
    border-bottom-color: rgba(255, 255, 255, 0.05);
    color: #e0e0e0;
}

body.dark-mode .table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.08);
}

body.dark-mode .page-link {
    background-color: #0f0f1a;
    color: #667eea;
}

body.dark-mode .modal-content {
    background-color: #1a1a2e;
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
        const sortByActual = document.getElementById('sort_by').value;
        const sortOrderActual = document.getElementById('sort_order').value;

        let nuevoOrden = 'asc';
        if (sortByActual === campo && sortOrderActual === 'asc') {
            nuevoOrden = 'desc';
        }

        document.getElementById('sort_by').value = campo;
        document.getElementById('sort_order').value = nuevoOrden;
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
document.querySelector('select[name="id_area"]')?.addEventListener('change', function() {
    document.getElementById('filtroForm').submit();
});

document.querySelector('select[name="has_justificacion"]')?.addEventListener('change', function() {
    document.getElementById('filtroForm').submit();
});

// Función para ver pregunta completa
function verPregunta(id) {
    const modal = new bootstrap.Modal(document.getElementById('showPreguntaModal'));
    const modalContent = document.getElementById('modalPreguntaContent');

    modalContent.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2 text-muted">Cargando información...</p>
            </div>
        `;

    modal.show();

    fetch(`/administrador/preguntas/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const pregunta = data.data || data;

            const justificacionHtml = (pregunta.justificacion && pregunta.justificacion.trim() !== '') ? `
                <div class="mt-3 p-3 rounded-3" style="background: rgba(76,175,80,0.08); border-left: 4px solid #4caf50;">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas fa-lightbulb text-warning"></i>
                        <small class="text-muted fw-semibold">JUSTIFICACIÓN</small>
                    </div>
                    <div class="mt-2 justificacion-text" style="white-space: pre-wrap; line-height: 1.6; font-size: 0.9rem;">
                        ${escapeHtml(pregunta.justificacion)}
                    </div>
                </div>
            ` : `
                <div class="mt-3 p-3 rounded-3 text-center" style="background: rgba(108,117,125,0.05);">
                    <i class="fas fa-info-circle text-muted me-2"></i>
                    <span class="text-muted">Esta pregunta no tiene justificación registrada</span>
                </div>
            `;

            const areaNombre = pregunta.area?.nombre || pregunta.area?.area || 'Sin área';

            modalContent.innerHTML = `
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div class="p-3 rounded-3" style="background: rgba(102,126,234,0.05);">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <small class="text-muted">
                                <i class="fas fa-hashtag me-1"></i>ID: ${pregunta.id}
                            </small>
                            <span class="badge-area">
                                <i class="fas fa-layer-group fa-xs"></i>
                                ${escapeHtml(areaNombre)}
                            </span>
                        </div>
                        <small class="text-muted d-block mb-2">
                            <i class="fas fa-question-circle me-1"></i>PREGUNTA
                        </small>
                        <strong class="fs-5 d-block">${escapeHtml(pregunta.pregunta)}</strong>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(102,126,234,0.05);">
                                <small class="text-muted d-block mb-2">
                                    <i class="fas fa-arrow-right me-1"></i>OPCIÓN A
                                </small>
                                <div class="p-2 rounded-3" style="background: #f8f9fa;">
                                    ${escapeHtml(pregunta.respuesta1 || 'No registrada')}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(102,126,234,0.05);">
                                <small class="text-muted d-block mb-2">
                                    <i class="fas fa-arrow-right me-1"></i>OPCIÓN B
                                </small>
                                <div class="p-2 rounded-3" style="background: #f8f9fa;">
                                    ${escapeHtml(pregunta.respuesta2 || 'No registrada')}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-3 rounded-3" style="background: rgba(102,126,234,0.05);">
                        <small class="text-muted d-block mb-2">
                            <i class="fas fa-check-circle text-success me-1"></i>RESPUESTA CORRECTA
                        </small>
                        <span class="badge-correcta d-inline-flex">
                            <i class="fas fa-check-circle fa-xs me-2"></i>
                            ${escapeHtml(pregunta.respuesta_correcta)}
                        </span>
                    </div>
                    
                    ${justificacionHtml}
                </div>
            `;

            if (document.body.classList.contains('dark-mode')) {
                const darkElements = modalContent.querySelectorAll('[style*="background: #f8f9fa;"]');
                darkElements.forEach(el => {
                    el.style.background = '#0f0f1a';
                    el.style.color = '#e0e0e0';
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modalContent.innerHTML = `
                <div class="text-center py-4 text-danger">
                    <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                    <p>Error al cargar los datos de la pregunta</p>
                    <button class="btn btn-primary mt-3" onclick="location.reload()">Recargar página</button>
                </div>
            `;
        });
}

function confirmarEliminar(url, idPregunta, textoPregunta) {
    Swal.fire({
        title: '¿Eliminar pregunta?',
        html: `<div class="text-center">
                        <p class="mb-2">Estás a punto de eliminar la pregunta:</p>
                        <strong class="fs-5" style="color: #667eea;">"${escapeHtml(textoPregunta.substring(0, 100))}${textoPregunta.length > 100 ? '...' : ''}"</strong>
                        <div class="alert alert-danger mt-3" style="border-radius: 12px;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Esta acción eliminará la pregunta de todos los exámenes asociados.
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
            Swal.fire({
                title: 'Eliminando...',
                text: 'Procesando solicitud',
                timer: 1500,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                    form.submit();
                }
            });
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