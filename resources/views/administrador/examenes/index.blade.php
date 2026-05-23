@extends('administrador.layouts.master')

@section('title', 'Gestión de Exámenes - SAINS')

@section('content')
<div class="container-fluid px-4">
    <!-- Header con estadísticas integradas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3 mb-4">
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
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.examenes.create') }}" class="btn btn-primary-custom px-4 py-2 shadow-sm">
                        <i class="fas fa-plus-circle me-2"></i>Nuevo Examen
                    </a>
                    <button class="btn btn-outline-secondary px-4 py-2" onclick="window.location.reload()">
                        <i class="fas fa-sync-alt me-2"></i>Actualizar
                    </button>
                </div>
            </div>

            <!-- Tarjetas de estadísticas -->
            <div class="row g-3">
                <div class="col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 hover-card">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-0 small text-uppercase fw-semibold">Total</p>
                                    <h3 class="fw-bold mb-0 text-primary">{{ $totalExamenes ?? 0 }}</h3>
                                    <small class="text-muted">Exámenes creados</small>
                                </div>
                                <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #667eea20, #764ba220);">
                                    <i class="fas fa-database fa-2x text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 hover-card">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-0 small text-uppercase fw-semibold">Materia</p>
                                    <h3 class="fw-bold mb-0 text-info">{{ $examenesMateria ?? 0 }}</h3>
                                    <small class="text-muted">Por materia</small>
                                </div>
                                <div class="rounded-circle p-3" style="background: rgba(13,202,240,0.1);">
                                    <i class="fas fa-book fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 hover-card">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-0 small text-uppercase fw-semibold">Simulación</p>
                                    <h3 class="fw-bold mb-0 text-warning">{{ $examenesSimulacion ?? 0 }}</h3>
                                    <small class="text-muted">Tipo simulación</small>
                                </div>
                                <div class="rounded-circle p-3" style="background: rgba(255,193,7,0.1);">
                                    <i class="fas fa-chart-line fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 hover-card">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-0 small text-uppercase fw-semibold">General</p>
                                    <h3 class="fw-bold mb-0 text-success">{{ $examenesGeneral ?? 0 }}</h3>
                                    <small class="text-muted">Curso general</small>
                                </div>
                                <div class="rounded-circle p-3" style="background: rgba(40,167,69,0.1);">
                                    <i class="fas fa-globe fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de Filtros Avanzado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-sliders-h text-primary"></i>
                            <h5 class="fw-semibold mb-0">Filtros de búsqueda</h5>
                        </div>
                        <div>
                            <button id="btnLimpiar" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-eraser me-1"></i>Limpiar todo
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-search me-1"></i>Búsqueda rápida
                            </label>
                            <input type="text" id="searchInput" class="form-control form-control-lg rounded-3" 
                                   placeholder="ID, tipo de examen..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-tag me-1"></i>Tipo de examen
                            </label>
                            <select id="tipoFilter" class="form-select form-select-lg rounded-3">
                                <option value="">Todos</option>
                                <option value="Materia" {{ request('tipo') == 'Materia' ? 'selected' : '' }}>Materia</option>
                                <option value="General del curso" {{ request('tipo') == 'General del curso' ? 'selected' : '' }}>General del curso</option>
                                <option value="Simulación" {{ request('tipo') == 'Simulación' ? 'selected' : '' }}>Simulación</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-sort-amount-down me-1"></i>Ordenar por
                            </label>
                            <select id="ordenFilter" class="form-select form-select-lg rounded-3">
                                <option value="reciente" {{ request('orden') == 'reciente' ? 'selected' : '' }}>Más reciente</option>
                                <option value="antiguo" {{ request('orden') == 'antiguo' ? 'selected' : '' }}>Más antiguo</option>
                                <option value="preguntas" {{ request('orden') == 'preguntas' ? 'selected' : '' }}>Más preguntas</option>
                                <option value="tiempo" {{ request('orden') == 'tiempo' ? 'selected' : '' }}>Más tiempo</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-filter me-1"></i>Rango preguntas
                            </label>
                            <select id="rangoFilter" class="form-select form-select-lg rounded-3">
                                <option value="">Todos</option>
                                <option value="0-20" {{ request('rango') == '0-20' ? 'selected' : '' }}>0 - 20 preguntas</option>
                                <option value="21-50" {{ request('rango') == '21-50' ? 'selected' : '' }}>21 - 50 preguntas</option>
                                <option value="51-100" {{ request('rango') == '51-100' ? 'selected' : '' }}>51 - 100 preguntas</option>
                                <option value="100+" {{ request('rango') == '100+' ? 'selected' : '' }}>Más de 100</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <button id="btnFiltrar" class="btn btn-primary-custom px-4 py-2 rounded-3 shadow-sm w-100">
                                <i class="fas fa-filter me-2"></i>Aplicar filtros
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de exámenes -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 premium-table">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="py-3 px-4 text-white fw-semibold" style="width: 80px;">ID</th>
                                <th class="py-3 px-4 text-white fw-semibold">Tipo de Examen</th>
                                <th class="py-3 px-4 text-white fw-semibold text-center">Preguntas</th>
                                <th class="py-3 px-4 text-white fw-semibold text-center">Tiempo</th>
                                <th class="py-3 px-4 text-white fw-semibold">Fecha de Creación</th>
                                <th class="py-3 px-4 text-white fw-semibold text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaBody">
                            @include('administrador.examenes.partials.table_rows', ['examenes' => $examenes])
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-4 bg-light border-top gap-3">
                    <div class="text-muted small">
                        <i class="fas fa-chart-line me-1"></i>
                        Mostrando <span class="fw-semibold" id="desde">{{ $examenes->firstItem() ?? 0 }}</span> - 
                        <span class="fw-semibold" id="hasta">{{ $examenes->lastItem() ?? 0 }}</span> 
                        de <span class="fw-semibold" id="total">{{ $examenes->total() ?? 0 }}</span> registros
                    </div>
                    <div id="paginationLinks" class="d-flex justify-content-end">
                        {{ $examenes->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalles del Examen -->
<div class="modal fade" id="showExamenModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-file-alt me-2"></i>Detalles del Examen
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="modalExamenContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando información del examen...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Duplicar Examen -->
<div class="modal fade" id="duplicarExamenModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-copy me-2"></i>Duplicar Examen
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="duplicarForm" method="POST">
                <div class="modal-body p-4">
                    @csrf
                    @method('POST')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipo de examen actual</label>
                        <input type="text" id="tipoActual" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Número de preguntas</label>
                        <input type="number" id="numPreguntas" class="form-control" readonly>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        El examen será duplicado con las mismas características.
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary-custom px-4">
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
    /* Animaciones y efectos premium */
    .hover-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.12) !important;
    }
    
    /* Tabla premium */
    .premium-table {
        min-width: 800px;
        width: 100%;
    }
    
    .premium-table td {
        padding: 1rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        font-size: 0.85rem;
    }
    
    .premium-table tbody tr {
        transition: all 0.25s ease-in-out;
    }
    
    .premium-table tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
        transform: translateX(3px);
    }
    
    .premium-table thead th {
        color: white !important;
        font-weight: 600 !important;
        letter-spacing: 0.5px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        font-size: 0.85rem;
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
    
    /* Botones de acción premium */
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
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 2px 6px rgba(102,126,234,0.3);
    }
    
    .btn-ver:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(102,126,234,0.4);
        color: white;
    }
    
    .btn-duplicar {
        background: linear-gradient(135deg, #f093fb, #f5576c);
        color: white;
        box-shadow: 0 2px 6px rgba(240,147,251,0.3);
    }
    
    .btn-duplicar:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(240,147,251,0.4);
        color: white;
    }
    
    .btn-eliminar {
        background: linear-gradient(135deg, #fa709a, #fee140);
        color: white;
        box-shadow: 0 2px 6px rgba(250,112,154,0.3);
    }
    
    .btn-eliminar:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(250,112,154,0.4);
        color: white;
    }
    
    /* Paginación mejorada */
    .pagination {
        margin-bottom: 0;
        gap: 6px;
    }
    
    .page-item .page-link {
        border-radius: 12px !important;
        margin: 0;
        color: #667eea;
        border: none;
        padding: 0.5rem 0.9rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(102,126,234,0.4);
    }
    
    .page-item .page-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-1px);
    }
    
    /* Formularios mejorados */
    .form-control-lg, .form-select-lg {
        font-size: 0.9rem;
        border: 2px solid #e2e8f0;
        transition: all 0.2s ease;
        background-color: white;
        border-radius: 12px;
    }
    
    .form-control-lg:focus, .form-select-lg:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.25s ease;
        border-radius: 12px;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .btn-outline-secondary {
        border: 2px solid #e2e8f0;
        transition: all 0.2s ease;
        background: transparent;
        border-radius: 12px;
        font-weight: 500;
    }
    
    .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
        transform: translateY(-1px);
    }
    
    /* Indicadores visuales */
    .pregunta-badge {
        background: rgba(102,126,234,0.1);
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
        color: #667eea;
    }
    
    .tiempo-badge {
        background: rgba(76,175,80,0.1);
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
        color: #4caf50;
    }
    
    /* Dark Mode */
    body.dark-mode .premium-table td {
        border-bottom-color: rgba(255, 255, 255, 0.05);
        color: #e2e8f0;
    }
    
    body.dark-mode .premium-table tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.08);
    }
    
    body.dark-mode .bg-light {
        background-color: #0f172a !important;
    }
    
    body.dark-mode .form-control-lg,
    body.dark-mode .form-select-lg {
        background-color: #1e293b;
        border-color: #334155;
        color: #f1f5f9;
    }
    
    body.dark-mode .btn-outline-secondary {
        border-color: #334155;
        color: #cbd5e1;
    }
    
    body.dark-mode .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
    }
    
    body.dark-mode .page-link {
        background-color: #1e293b;
        color: #818cf8;
    }
    
    body.dark-mode .page-item.disabled .page-link {
        background-color: #1e293b;
        color: #64748b;
    }
    
    body.dark-mode .modal-content {
        background-color: #0f172a;
    }
    
    body.dark-mode .pregunta-badge {
        background: rgba(102,126,234,0.2);
        color: #a5b4fc;
    }
    
    body.dark-mode .tiempo-badge {
        background: rgba(76,175,80,0.2);
        color: #81c784;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Variables globales para el modal de duplicar
    let examenIdADuplicar = null;
    
    function verExamen(id) {
        const modal = new bootstrap.Modal(document.getElementById('showExamenModal'));
        const contentDiv = document.getElementById('modalExamenContent');
        
        contentDiv.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2 text-muted">Cargando información del examen...</p>
            </div>
        `;
        
        modal.show();
        
        fetch(`/administrador/examenes/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const e = data.data;
                    const tipoClass = e.tipo_examen === 'Materia' ? 'badge-materia' : (e.tipo_examen === 'General del curso' ? 'badge-general' : 'badge-simulacion');
                    
                    contentDiv.innerHTML = `
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="p-4 rounded-4" style="background: linear-gradient(135deg, #667eea15, #764ba215);">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                        <div>
                                            <h4 class="mb-1 fw-bold">Examen #${e.id}</h4>
                                            <span class="badge-tipo ${tipoClass} mt-2">
                                                <i class="fas fa-tag me-1"></i>${e.tipo_examen}
                                            </span>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted d-block">ID del examen</small>
                                            <strong class="fs-3 text-primary">#${e.id}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 border" style="background: rgba(102,126,234,0.03);">
                                    <small class="text-muted d-block mb-2"><i class="fas fa-question-circle me-1"></i> Total de preguntas</small>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="pregunta-badge">
                                            <i class="fas fa-list me-1"></i> ${e.numero_preguntas} preguntas
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 border" style="background: rgba(102,126,234,0.03);">
                                    <small class="text-muted d-block mb-2"><i class="fas fa-clock me-1"></i> Tiempo asignado</small>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="tiempo-badge">
                                            <i class="fas fa-hourglass-half me-1"></i> ${e.tiempo} minutos
                                        </span>
                                        <small class="text-muted">(${Math.floor(e.tiempo / 60)}h ${e.tiempo % 60}m)</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 border" style="background: rgba(102,126,234,0.03);">
                                    <small class="text-muted d-block mb-2"><i class="fas fa-calendar-alt me-1"></i> Fecha de creación</small>
                                    <strong>${e.created_at ? new Date(e.created_at).toLocaleString('es-MX') : '—'}</strong>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 border" style="background: rgba(102,126,234,0.03);">
                                    <small class="text-muted d-block mb-2"><i class="fas fa-chart-line me-1"></i> Estadísticas</small>
                                    <div class="d-flex flex-column gap-1">
                                        <div><i class="fas fa-users text-primary me-2"></i> Realizado por: <strong>${e.veces_realizado || 0}</strong> estudiantes</div>
                                        <div><i class="fas fa-star text-warning me-2"></i> Promedio: <strong>${e.promedio_calificacion ? e.promedio_calificacion.toFixed(1) : 'N/A'}</strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    contentDiv.innerHTML = `
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3 d-block"></i>
                            <p class="text-muted">No se pudo cargar la información del examen</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                contentDiv.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-circle fa-3x text-danger mb-3 d-block"></i>
                        <p class="text-muted">Error al cargar los datos</p>
                    </div>
                `;
            });
    }
    
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
                    <i class="fas fa-file-alt fa-4x mb-3" style="color: #dc3545;"></i>
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
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash-alt me-2"></i>Sí, eliminar permanentemente',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar',
            background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#f1f5f9' : '#1e293b',
            reverseButtons: true
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
    
    function cargarTabla() {
        const search = document.getElementById('searchInput')?.value || '';
        const tipo = document.getElementById('tipoFilter')?.value || '';
        const orden = document.getElementById('ordenFilter')?.value || '';
        const rango = document.getElementById('rangoFilter')?.value || '';
        
        const url = new URL(window.location.href);
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        
        if (tipo) url.searchParams.set('tipo', tipo);
        else url.searchParams.delete('tipo');
        
        if (orden) url.searchParams.set('orden', orden);
        else url.searchParams.delete('orden');
        
        if (rango) url.searchParams.set('rango', rango);
        else url.searchParams.delete('rango');
        
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const btnFiltrar = document.getElementById('btnFiltrar');
        const btnLimpiar = document.getElementById('btnLimpiar');
        const searchInput = document.getElementById('searchInput');
        
        if (btnFiltrar) btnFiltrar.addEventListener('click', cargarTabla);
        if (btnLimpiar) btnLimpiar.addEventListener('click', () => window.location.href = window.location.pathname);
        if (searchInput) searchInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') cargarTabla(); });
        
        // Auto-filtrar al cambiar selects
        document.querySelectorAll('#tipoFilter, #ordenFilter, #rangoFilter').forEach(select => {
            if (select) select.addEventListener('change', cargarTabla);
        });
    });
</script>
@endpush