{{-- resources/views/administrador/carreras/index.blade.php --}}
@extends('administrador.layouts.master')

@section('title', 'Gestión de Carreras - SAINS')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-3 p-2" style="background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);">
                            <i class="fas fa-graduation-cap fa-2x" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
                        </div>
                        <h1 class="display-5 fw-bold mb-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                            Gestión de Carreras
                        </h1>
                    </div>
                    <p class="text-muted fs-5 mb-0">Administre las carreras universitarias y sus materias asociadas</p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary-custom px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#createCarreraModal">
                        <i class="fas fa-plus-circle me-2"></i>Nueva Carrera
                    </button>
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
                        <div class="col-md-5 col-lg-5">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-search me-1"></i>Buscar carrera
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 rounded-start-3">
                                    <i class="fas fa-graduation-cap text-primary"></i>
                                </span>
                                <input type="text" id="searchInput" class="form-control border-start-0 rounded-end-3" 
                                       placeholder="Nombre de la carrera..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-layer-group me-1"></i>Filtrar por Tronco
                            </label>
                            <select id="troncoFilter" class="form-select rounded-3">
                                <option value="">Todos los troncos</option>
                                @foreach($troncos as $tronco)
                                    <option value="{{ $tronco->id }}" {{ request('tronco_id') == $tronco->id ? 'selected' : '' }}>
                                        {{ $tronco->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="d-flex gap-2">
                                <button id="btnFiltrar" class="btn btn-primary-custom w-100 py-2 rounded-3 shadow-sm">
                                    <i class="fas fa-filter me-2"></i>Filtrar
                                </button>
                                <button id="btnLimpiar" class="btn btn-outline-secondary w-100 py-2 rounded-3">
                                    <i class="fas fa-times me-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="row mb-4 g-3">
        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 hover-card stat-card stat-card-primary">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small text-uppercase fw-semibold">Total Carreras</p>
                            <h3 class="fw-bold mb-0 mt-1">{{ $totalCarreras }}</h3>
                        </div>
                        <div class="stat-icon rounded-3">
                            <i class="fas fa-graduation-cap fa-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 hover-card stat-card stat-card-success">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small text-uppercase fw-semibold">Con Tronco Asignado</p>
                            <h3 class="fw-bold mb-0 mt-1">{{ $conTronco }}</h3>
                        </div>
                        <div class="stat-icon rounded-3">
                            <i class="fas fa-layer-group fa-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 hover-card stat-card stat-card-warning">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small text-uppercase fw-semibold">Con Universidades</p>
                            <h3 class="fw-bold mb-0 mt-1">{{ $conUniversidades }}</h3>
                        </div>
                        <div class="stat-icon rounded-3">
                            <i class="fas fa-university fa-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de carreras -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-semibold mb-0">
                            <i class="fas fa-list me-2 text-primary"></i>Listado de Carreras
                        </h5>
                        <span class="badge bg-primary rounded-pill">{{ $carreras->total() }} registros</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-header">
                                <tr>
                                    <th class="py-3 px-4 fw-semibold" style="width: 80px;">
                                        <i class="fas fa-hashtag me-1"></i> ID
                                    </th>
                                    <th class="py-3 px-4 fw-semibold">
                                        <i class="fas fa-graduation-cap me-1"></i> Carrera
                                    </th>
                                    <th class="py-3 px-4 fw-semibold" style="width: 200px;">
                                        <i class="fas fa-layer-group me-1"></i> Tronco
                                    </th>
                                    <th class="py-3 px-4 fw-semibold text-center" style="width: 100px;">
                                        <i class="fas fa-university me-1"></i> Universidades
                                    </th>
                                    <th class="py-3 px-4 fw-semibold text-center" style="width: 230px;">
                                        <i class="fas fa-cog me-1"></i> Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tablaBody">
                                @include('administrador.carreras.partials.table_rows', ['carreras' => $carreras])
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Paginación -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-4 bg-light border-top gap-3">
                    <div class="text-muted small">
                        <i class="fas fa-chart-line me-1"></i>
                        Mostrando <span class="fw-semibold text-primary">{{ $carreras->firstItem() ?? 0 }}</span> - 
                        <span class="fw-semibold text-primary">{{ $carreras->lastItem() ?? 0 }}</span> 
                        de <span class="fw-semibold text-primary">{{ $carreras->total() }}</span> registros
                    </div>
                    <div id="paginationLinks" class="d-flex justify-content-end">
                        {{ $carreras->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Carrera -->
<div class="modal fade" id="createCarreraModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-white bg-opacity-20 p-2">
                        <i class="fas fa-plus-circle text-white fa-lg"></i>
                    </div>
                    <h5 class="modal-title text-white fw-bold">Nueva Carrera</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="createCarreraForm">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted mb-2">
                            <i class="fas fa-graduation-cap me-2 text-primary"></i>Nombre de la carrera
                        </label>
                        <input type="text" class="form-control form-control-lg rounded-3" 
                               id="nombre" name="nombre" 
                               placeholder="Ej: Ingeniería en Sistemas Computacionales" 
                               required autofocus>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted mb-2">
                            <i class="fas fa-layer-group me-2 text-primary"></i>Tronco Común
                        </label>
                        <select class="form-select form-select-lg rounded-3" id="tronco_id" name="tronco_id">
                            <option value="">Seleccione un tronco (opcional)</option>
                            @foreach($troncos as $tronco)
                                <option value="{{ $tronco->id }}">{{ $tronco->nombre }}</option>
                            @endforeach
                        </select>
                        <div class="form-text text-muted small mt-1">El tronco común es opcional</div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-book me-2 text-primary"></i>Materia 1
                            </label>
                            <select class="form-select rounded-3" id="id_asignatura_1" name="id_asignatura_1">
                                <option value="">Seleccione una materia</option>
                                @foreach($asignaturas as $asignatura)
                                    <option value="{{ $asignatura->id }}">{{ $asignatura->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-book me-2 text-primary"></i>Materia 2
                            </label>
                            <select class="form-select rounded-3" id="id_asignatura_2" name="id_asignatura_2">
                                <option value="">Seleccione una materia</option>
                                @foreach($asignaturas as $asignatura)
                                    <option value="{{ $asignatura->id }}">{{ $asignatura->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-book me-2 text-primary"></i>Materia 3
                            </label>
                            <select class="form-select rounded-3" id="id_asignatura_3" name="id_asignatura_3">
                                <option value="">Seleccione una materia</option>
                                @foreach($asignaturas as $asignatura)
                                    <option value="{{ $asignatura->id }}">{{ $asignatura->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="alert alert-info small rounded-3 mt-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Las materias son opcionales y se pueden asignar posteriormente.
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary-custom px-4 rounded-3" id="saveCarreraBtn">
                    <i class="fas fa-save me-2"></i>Guardar Carrera
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Carrera -->
<div class="modal fade" id="editCarreraModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-white bg-opacity-20 p-2">
                        <i class="fas fa-edit text-white fa-lg"></i>
                    </div>
                    <h5 class="modal-title text-white fw-bold">Editar Carrera</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editCarreraForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id" name="id">
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted mb-2">
                            <i class="fas fa-graduation-cap me-2 text-primary"></i>Nombre de la carrera
                        </label>
                        <input type="text" class="form-control form-control-lg rounded-3" 
                               id="edit_nombre" name="nombre" 
                               placeholder="Ej: Ingeniería en Sistemas Computacionales" 
                               required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted mb-2">
                            <i class="fas fa-layer-group me-2 text-primary"></i>Tronco Común
                        </label>
                        <select class="form-select form-select-lg rounded-3" id="edit_tronco_id" name="tronco_id">
                            <option value="">Seleccione un tronco (opcional)</option>
                            @foreach($troncos as $tronco)
                                <option value="{{ $tronco->id }}">{{ $tronco->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-book me-2 text-primary"></i>Materia 1
                            </label>
                            <select class="form-select rounded-3" id="edit_id_asignatura_1" name="id_asignatura_1">
                                <option value="">Seleccione una materia</option>
                                @foreach($asignaturas as $asignatura)
                                    <option value="{{ $asignatura->id }}">{{ $asignatura->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-book me-2 text-primary"></i>Materia 2
                            </label>
                            <select class="form-select rounded-3" id="edit_id_asignatura_2" name="id_asignatura_2">
                                <option value="">Seleccione una materia</option>
                                @foreach($asignaturas as $asignatura)
                                    <option value="{{ $asignatura->id }}">{{ $asignatura->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-book me-2 text-primary"></i>Materia 3
                            </label>
                            <select class="form-select rounded-3" id="edit_id_asignatura_3" name="id_asignatura_3">
                                <option value="">Seleccione una materia</option>
                                @foreach($asignaturas as $asignatura)
                                    <option value="{{ $asignatura->id }}">{{ $asignatura->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary-custom px-4 rounded-3" id="updateCarreraBtn">
                    <i class="fas fa-save me-2"></i>Actualizar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Detalles -->
<div class="modal fade" id="showCarreraModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-white bg-opacity-20 p-2">
                        <i class="fas fa-eye text-white fa-lg"></i>
                    </div>
                    <h5 class="modal-title text-white fw-bold">Detalles de la Carrera</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="modalCarreraContent">
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
    .stat-card {
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
    }
    
    .stat-card-primary::before {
        background: linear-gradient(90deg, #667eea, #764ba2);
    }
    
    .stat-card-success::before {
        background: linear-gradient(90deg, #10b981, #059669);
    }
    
    .stat-card-warning::before {
        background: linear-gradient(90deg, #f59e0b, #d97706);
    }
    
    .stat-card .stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(102, 126, 234, 0.1);
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
    
    .hover-card {
        transition: all 0.3s ease-in-out;
    }
    
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
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
    
    .btn-accion {
        padding: 6px 14px;
        margin: 0 3px;
        font-size: 0.75rem;
        border-radius: 30px;
        transition: all 0.3s ease;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }
    
    .btn-ver {
        background: linear-gradient(135deg, #e3f2fd, #bbdef5);
        color: #1565c0;
    }
    
    .btn-ver:hover {
        background: linear-gradient(135deg, #1565c0, #0d47a1);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(21,101,192,0.3);
    }
    
    .btn-editar {
        background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
        color: #2e7d32;
    }
    
    .btn-editar:hover {
        background: linear-gradient(135deg, #2e7d32, #1b5e20);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(46,125,50,0.3);
    }
    
    .btn-eliminar {
        background: linear-gradient(135deg, #ffebee, #ffcdd2);
        color: #c62828;
    }
    
    .btn-eliminar:hover {
        background: linear-gradient(135deg, #c62828, #b71c1c);
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
    
    .table {
        min-width: 800px;
    }
    
    .table td {
        padding: 1rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.04), rgba(118, 75, 162, 0.04));
        transform: scale(1.01);
    }
    
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
        transition: all 0.3s;
    }
    
    .page-item .page-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-2px);
    }
    
    .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(102,126,234,0.4);
    }
    
    .input-group-text {
        background: white;
        border: 2px solid #e2e8f0;
        border-right: none;
    }
    
    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    /* Dark Mode */
    body.dark-mode .table-header {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-bottom-color: #334155;
    }
    
    body.dark-mode .table-header th {
        color: #e2e8f0;
    }
    
    body.dark-mode .table td {
        border-bottom-color: rgba(255, 255, 255, 0.05);
        color: #cbd5e1;
    }
    
    body.dark-mode .table tbody tr:hover {
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.08), rgba(118, 75, 162, 0.08));
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
    
    body.dark-mode .input-group-text {
        background: #1e293b;
        border-color: #334155;
        color: #94a3b8;
    }
    
    body.dark-mode .form-control,
    body.dark-mode .form-select {
        background-color: #1e293b;
        border-color: #334155;
        color: #e2e8f0;
    }
    
    body.dark-mode .form-control:focus,
    body.dark-mode .form-select:focus {
        border-color: #667eea;
        background-color: #1e293b;
    }
    
    body.dark-mode .page-link {
        background-color: #1e293b;
        color: #818cf8;
    }
    
    body.dark-mode .page-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    body.dark-mode .modal-content {
        background-color: #1e293b;
    }
    
    body.dark-mode .alert-info {
        background-color: rgba(6, 182, 212, 0.1);
        border-color: rgba(6, 182, 212, 0.3);
        color: #67e8f9;
    }
    
    body.dark-mode .bg-light {
        background-color: #0f172a !important;
    }
    
    body.dark-mode .text-muted {
        color: #94a3b8 !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let editId = null;
    
    function verCarrera(id) {
        const modal = new bootstrap.Modal(document.getElementById('showCarreraModal'));
        const modalContent = document.getElementById('modalCarreraContent');
        
        modalContent.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2 text-muted">Cargando información...</p>
            </div>
        `;
        
        modal.show();
        
        fetch(`/administrador/carreras/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const carrera = data.data;
                    modalContent.innerHTML = `
                        <div class="d-flex flex-column gap-3">
                            <div class="text-center p-3 rounded-4" style="background: rgba(102,126,234,0.05);">
                                <i class="fas fa-graduation-cap fa-3x text-primary mb-2"></i>
                                <h4 class="fw-bold mb-0">${escapeHtml(carrera.nombre)}</h4>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="p-3 rounded-3" style="background: rgba(102,126,234,0.05);">
                                        <small class="text-muted d-block mb-1"><i class="fas fa-layer-group me-1"></i> Tronco Común</small>
                                        <strong>${escapeHtml(carrera.tronco)}</strong>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 text-center" style="background: rgba(102,126,234,0.05);">
                                        <small class="text-muted d-block mb-1"><i class="fas fa-book me-1"></i> Materia 1</small>
                                        <strong>${escapeHtml(carrera.asignatura_1)}</strong>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 text-center" style="background: rgba(102,126,234,0.05);">
                                        <small class="text-muted d-block mb-1"><i class="fas fa-book me-1"></i> Materia 2</small>
                                        <strong>${escapeHtml(carrera.asignatura_2)}</strong>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 text-center" style="background: rgba(102,126,234,0.05);">
                                        <small class="text-muted d-block mb-1"><i class="fas fa-book me-1"></i> Materia 3</small>
                                        <strong>${escapeHtml(carrera.asignatura_3)}</strong>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 rounded-3 text-center" style="background: rgba(102,126,234,0.05);">
                                        <small class="text-muted d-block mb-1"><i class="fas fa-university me-1"></i> Universidades Asociadas</small>
                                        <strong>${carrera.total_universidades} universidad(es)</strong>
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
    }
    
    function editCarrera(id, nombre, troncoId, asignatura1Id, asignatura2Id, asignatura3Id) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_tronco_id').value = troncoId || '';
        document.getElementById('edit_id_asignatura_1').value = asignatura1Id || '';
        document.getElementById('edit_id_asignatura_2').value = asignatura2Id || '';
        document.getElementById('edit_id_asignatura_3').value = asignatura3Id || '';
        
        const modal = new bootstrap.Modal(document.getElementById('editCarreraModal'));
        modal.show();
    }
    
    function deleteCarrera(id, nombre) {
        Swal.fire({
            title: '¿Eliminar carrera?',
            html: `La carrera <strong class="text-primary">${escapeHtml(nombre)}</strong> será eliminada permanentemente.<br><br>Esta acción no se puede deshacer.`,
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
                Swal.fire({
                    title: 'Eliminando...',
                    text: 'Procesando solicitud',
                    timer: 1500,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                        
                        fetch(`/administrador/carreras/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: '¡Eliminada!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonColor: '#4f46e5'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'No se puede eliminar',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonColor: '#ef4444'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al eliminar la carrera',
                                icon: 'error',
                                confirmButtonColor: '#ef4444'
                            });
                        });
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
    
    function cargarTabla() {
        const search = document.getElementById('searchInput')?.value || '';
        const troncoId = document.getElementById('troncoFilter')?.value || '';
        const url = new URL(window.location.href);
        
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        
        if (troncoId) url.searchParams.set('tronco_id', troncoId);
        else url.searchParams.delete('tronco_id');
        
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }
    
    // Eventos
    document.getElementById('btnFiltrar')?.addEventListener('click', cargarTabla);
    document.getElementById('btnLimpiar')?.addEventListener('click', () => window.location.href = window.location.pathname);
    document.getElementById('searchInput')?.addEventListener('keypress', e => e.key === 'Enter' && cargarTabla());
    document.getElementById('troncoFilter')?.addEventListener('change', cargarTabla);
    
    // Crear carrera
    document.getElementById('saveCarreraBtn')?.addEventListener('click', function() {
        const nombre = document.getElementById('nombre').value;
        
        if (!nombre.trim()) {
            Swal.fire('Error', 'El nombre de la carrera es requerido', 'error');
            return;
        }
        
        const formData = {
            nombre: nombre,
            tronco_id: document.getElementById('tronco_id').value,
            id_asignatura_1: document.getElementById('id_asignatura_1').value,
            id_asignatura_2: document.getElementById('id_asignatura_2').value,
            id_asignatura_3: document.getElementById('id_asignatura_3').value
        };
        
        const btn = this;
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
        btn.disabled = true;
        
        fetch('{{ route("admin.carreras.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#4f46e5',
                    timer: 1500,
                    showConfirmButton: true
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message,
                    icon: 'error',
                    confirmButtonColor: '#ef4444'
                });
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al guardar la carrera',
                icon: 'error',
                confirmButtonColor: '#ef4444'
            });
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    });
    
    // Actualizar carrera
    document.getElementById('updateCarreraBtn')?.addEventListener('click', function() {
        const id = document.getElementById('edit_id').value;
        const nombre = document.getElementById('edit_nombre').value;
        
        if (!nombre.trim()) {
            Swal.fire('Error', 'El nombre de la carrera es requerido', 'error');
            return;
        }
        
        const formData = {
            nombre: nombre,
            tronco_id: document.getElementById('edit_tronco_id').value,
            id_asignatura_1: document.getElementById('edit_id_asignatura_1').value,
            id_asignatura_2: document.getElementById('edit_id_asignatura_2').value,
            id_asignatura_3: document.getElementById('edit_id_asignatura_3').value
        };
        
        const btn = this;
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Actualizando...';
        btn.disabled = true;
        
        fetch(`/administrador/carreras/${id}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#4f46e5',
                    timer: 1500,
                    showConfirmButton: true
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message,
                    icon: 'error',
                    confirmButtonColor: '#ef4444'
                });
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al actualizar la carrera',
                icon: 'error',
                confirmButtonColor: '#ef4444'
            });
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    });
</script>
@endpush