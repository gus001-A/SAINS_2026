@extends('administrador.layouts.master')

@section('title', 'Exámenes Realizados - SAINS')

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
                                <i class="fas fa-file-alt fa-2x"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
                            </div>
                            <h1 class="display-5 fw-bold mb-0"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                                Exámenes Realizados
                            </h1>
                        </div>
                        <p class="text-muted fs-5 mb-0">Gestión completa de todos los exámenes completados por los
                            estudiantes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTADORES RÁPIDOS - Tarjetas de estadísticas SIMPLIFICADAS -->
        <div class="row mb-4 g-3">
            <!-- Total Exámenes -->
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-primary">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Total Exámenes</p>
                                <h2 class="display-4 fw-bold mb-0">{{ $stats['total'] ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-database me-1"></i> Exámenes contestados
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-file-alt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Promedio General -->
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-info">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Promedio General</p>
                                <h2 class="display-4 fw-bold mb-0">{{ $stats['promedio'] ?? 0 }}<span
                                        class="fs-2">%</span></h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-chart-line me-1"></i> Calificación promedio
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-chart-line fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aprobados (≥60%) -->
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-success">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">✅ Aprobados</p>
                                <h2 class="display-4 fw-bold mb-0">
                                    {{ ($stats['excelentes'] ?? 0) + ($stats['aprobados'] ?? 0) }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-check-circle me-1"></i> Calificación ≥ 60%
                                </p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reprobados (<60%) -->
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-danger">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">❌ Reprobados</p>
                                <h2 class="display-4 fw-bold mb-0">{{ $stats['reprobados'] ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-times-circle me-1"></i> Calificación &lt; 60%
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

        <!-- Panel de Filtros SIMPLIFICADO -->
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
                        <form method="GET" action="{{ route('admin.examenes-realizados.index') }}" id="filtroForm"
                            data-ajax="true">
                            <div class="row g-3 align-items-end">
                                <!-- Estudiante -->
                                <div class="col-md-4 col-lg-3">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-user-graduate me-1"></i>Estudiante
                                    </label>
                                    <input type="text" name="estudiante" class="form-control form-control-lg rounded-3"
                                        placeholder="Nombre del estudiante..." value="{{ request('estudiante') }}"
                                        data-filter-input>
                                </div>

                                <!-- Tipo de Examen -->
                                <div class="col-md-4 col-lg-3">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-tag me-1"></i>Tipo de Examen
                                    </label>
                                    <select name="tipo_examen" class="form-select form-select-lg rounded-3"
                                        data-filter-select>
                                        <option value="">Todos los tipos</option>
                                        <option value="Materia"
                                            {{ request('tipo_examen') == 'Materia' ? 'selected' : '' }}>📚 Materia
                                        </option>
                                        <option value="Curso" {{ request('tipo_examen') == 'Curso' ? 'selected' : '' }}>
                                            🎓 Curso</option>
                                        <option value="Simulación"
                                            {{ request('tipo_examen') == 'Simulación' ? 'selected' : '' }}>🎯 Simulación
                                        </option>
                                    </select>
                                </div>

                                <!-- Calificación -->
                                <div class="col-md-4 col-lg-2">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-chart-line me-1"></i>Calificación
                                    </label>
                                    <select name="calificacion" class="form-select form-select-lg rounded-3"
                                        data-filter-select>
                                        <option value="">Todas</option>
                                        <option value="excelente"
                                            {{ request('calificacion') == 'excelente' ? 'selected' : '' }}>⭐ Excelente
                                            (≥80%)</option>
                                        <option value="aprobado"
                                            {{ request('calificacion') == 'aprobado' ? 'selected' : '' }}>✅ Aprobado
                                            (60-79%)</option>
                                        <option value="reprobado"
                                            {{ request('calificacion') == 'reprobado' ? 'selected' : '' }}>❌ Reprobado
                                            (&lt;60%)</option>
                                    </select>
                                </div>

                                <!-- Intento -->
                                <div class="col-md-4 col-lg-2">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-redo-alt me-1"></i>Intento
                                    </label>
                                    <select name="intento" class="form-select form-select-lg rounded-3"
                                        data-filter-select>
                                        <option value="">Todos</option>
                                        <option value="1" {{ request('intento') == '1' ? 'selected' : '' }}>1° Intento
                                        </option>
                                        <option value="2" {{ request('intento') == '2' ? 'selected' : '' }}>2° Intento
                                        </option>
                                        <option value="3" {{ request('intento') == '3' ? 'selected' : '' }}>3° Intento
                                        </option>
                                    </select>
                                </div>

                                <!-- Fecha -->
                                <div class="col-md-4 col-lg-2">
                                    <label class="form-label fw-semibold text-muted mb-2">
                                        <i class="fas fa-calendar-alt me-1"></i>Fecha
                                    </label>
                                    <input type="date" name="fecha" class="form-control form-control-lg rounded-3"
                                        value="{{ request('fecha') }}" data-filter-input>
                                </div>

                                <!-- Campos ocultos para ordenamiento -->
                                <input type="hidden" name="orden_campo" id="orden_campo"
                                    value="{{ request('orden_campo', 'fecha') }}">
                                <input type="hidden" name="orden_direccion" id="orden_direccion"
                                    value="{{ request('orden_direccion', 'desc') }}">

                                <!-- Botones -->
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit"
                                            class="btn btn-primary-custom w-100 py-2 rounded-3 shadow-sm">
                                            <i class="fas fa-search me-2"></i>Buscar
                                        </button>
                                        <a href="{{ route('admin.examenes-realizados.index') }}"
                                            class="btn btn-outline-secondary w-100 py-2 rounded-3">
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

        <!-- Tabla de exámenes -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-semibold mb-0">
                                <i class="fas fa-list me-2 text-primary"></i>Listado de Exámenes Realizados
                            </h5>
                            <span class="badge bg-primary rounded-pill">{{ $examenes->total() }} registros</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="min-width: 1100px;">
                                <thead class="table-header">
                                    <tr>
                                        <th class="py-3 px-4 fw-semibold" style="min-width: 200px;">
                                            <a href="#" class="ordenar-link text-decoration-none"
                                                data-campo="estudiante">
                                                <i class="fas fa-user-graduate me-1"></i> Estudiante
                                                @if(request('orden_campo') == 'estudiante')
                                                <i
                                                    class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold" style="min-width: 220px;">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="examen">
                                                <i class="fas fa-file-alt me-1"></i> Examen
                                                @if(request('orden_campo') == 'examen')
                                                <i
                                                    class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold text-center" style="width: 130px;">
                                            <a href="#" class="ordenar-link text-decoration-none"
                                                data-campo="calificacion">
                                                <i class="fas fa-chart-line me-1"></i> Calificación
                                                @if(request('orden_campo') == 'calificacion')
                                                <i
                                                    class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold text-center" style="width: 80px;">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="intento">
                                                <i class="fas fa-redo-alt me-1"></i> Intento
                                                @if(request('orden_campo') == 'intento')
                                                <i
                                                    class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold" style="min-width: 160px;">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="fecha">
                                                <i class="fas fa-calendar-alt me-1"></i> Fecha
                                                @if(request('orden_campo') == 'fecha')
                                                <i
                                                    class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold text-center" style="width: 100px;">
                                            <a href="#" class="ordenar-link text-decoration-none" data-campo="tiempo">
                                                <i class="fas fa-hourglass-half me-1"></i> Tiempo
                                                @if(request('orden_campo') == 'tiempo')
                                                <i
                                                    class="fas fa-sort-{{ request('orden_direccion') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                <i class="fas fa-sort ms-1 opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold text-center" style="width: 140px;">
                                            <i class="fas fa-cog me-1"></i> Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tablaBody">
                                    @include('administrador.examenes_realizados.partials.table_rows', ['examenes' =>
                                    $examenes])
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Paginación -->
                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-center p-4 bg-light border-top gap-3">
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

.stat-card-danger::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #ef4444, #dc2626);
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

.stat-card-danger .stat-icon {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
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

/* Badge de tipos de examen */
.badge-tipo {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.badge-materia {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.badge-curso {
    background: linear-gradient(135deg, #11998e, #38ef7d);
    color: white;
}

.badge-simulacion {
    background: linear-gradient(135deg, #f093fb, #f5576c);
    color: white;
}

.badge-secondary {
    background: #6c757d;
    color: white;
}

/* Badges de calificación */
.grade-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.8rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.grade-excellent {
    background: #e8f5e9;
    color: #2e7d32;
    border-left: 3px solid #4caf50;
}

.grade-good {
    background: #fff3e0;
    color: #ef6c00;
    border-left: 3px solid #ff9800;
}

.grade-poor {
    background: #ffebee;
    color: #c62828;
    border-left: 3px solid #ef5350;
}

/* Badge intentos */
.attempt-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9rem;
}

.attempt-1 {
    background: #e8f5e9;
    color: #2e7d32;
}

.attempt-2 {
    background: #fff3e0;
    color: #ef6c00;
}

.attempt-3 {
    background: #ffebee;
    color: #c62828;
}

/* Avatar estudiante */
.avatar-estudiante {
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

/* Tooltips */
[data-tooltip] {
    position: relative;
    cursor: help;
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

body.dark-mode .grade-excellent {
    background: rgba(46, 125, 50, 0.2);
    color: #81c784;
}

body.dark-mode .grade-good {
    background: rgba(239, 108, 0, 0.2);
    color: #ffb74d;
}

body.dark-mode .grade-poor {
    background: rgba(198, 40, 40, 0.2);
    color: #ef9a9a;
}

body.dark-mode .attempt-1 {
    background: rgba(46, 125, 50, 0.2);
    color: #81c784;
}

body.dark-mode .attempt-2 {
    background: rgba(239, 108, 0, 0.2);
    color: #ffb74d;
}

body.dark-mode .attempt-3 {
    background: rgba(198, 40, 40, 0.2);
    color: #ef9a9a;
}

body.dark-mode .btn-ver {
    background: rgba(21, 101, 192, 0.2);
    color: #64b5f6;
}

body.dark-mode .btn-ver:hover {
    background: #1565c0;
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

body.dark-mode .skeleton-box {
    background: linear-gradient(90deg, #2a2a3e 25%, #1a1a2e 50%, #2a2a3e 75%);
    background-size: 200% 100%;
}

body.dark-mode [data-tooltip]:before {
    background: #334155;
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
            <td class="px-4 py-3"><div class="skeleton-box" style="width: 200px; height: 50px;"></div></td>
            <td class="px-4 py-3"><div class="skeleton-box" style="width: 250px; height: 40px;"></div></td>
            <td class="px-4 py-3"><div class="skeleton-box" style="width: 100px; height: 38px; margin: 0 auto;"></div></td>
            <td class="px-4 py-3"><div class="skeleton-box" style="width: 50px; height: 36px; margin: 0 auto;"></div></td>
            <td class="px-4 py-3"><div class="skeleton-box" style="width: 140px; height: 40px;"></div></td>
            <td class="px-4 py-3"><div class="skeleton-box" style="width: 80px; height: 32px; margin: 0 auto;"></div></td>
            <td class="px-4 py-3"><div class="skeleton-box" style="width: 100px; height: 34px; margin: 0 auto;"></div></td>
        `;
        tbody.appendChild(row);
    }
}

// Función para actualizar estadísticas vía AJAX
async function updateStatistics() {
    try {
        const form = document.getElementById('filtroForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);

        const response = await fetch('{{ route("admin.examenes-realizados.estadisticas") }}?' + params.toString());
        const stats = await response.json();

        const statsContainer = document.querySelector('.row.mb-4.g-3');
        if (statsContainer) {
            statsContainer.innerHTML = `
                <div class="col-sm-6 col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-primary">
                        <div class="card-body p-3 p-xl-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-1 small fw-semibold text-uppercase">Total Exámenes</p>
                                    <h2 class="display-4 fw-bold mb-0">${stats.total}</h2>
                                    <p class="text-muted small mt-2 mb-0"><i class="fas fa-database me-1"></i> Exámenes contestados</p>
                                </div>
                                <div class="rounded-3 p-3 stat-icon"><i class="fas fa-file-alt fa-2x"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-info">
                        <div class="card-body p-3 p-xl-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-1 small fw-semibold text-uppercase">Promedio General</p>
                                    <h2 class="display-4 fw-bold mb-0">${stats.promedio}<span class="fs-2">%</span></h2>
                                    <p class="text-muted small mt-2 mb-0"><i class="fas fa-chart-line me-1"></i> Calificación promedio</p>
                                </div>
                                <div class="rounded-3 p-3 stat-icon"><i class="fas fa-chart-line fa-2x"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-success">
                        <div class="card-body p-3 p-xl-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-1 small fw-semibold text-uppercase">✅ Aprobados</p>
                                    <h2 class="display-4 fw-bold mb-0">${stats.aprobados}</h2>
                                    <p class="text-muted small mt-2 mb-0"><i class="fas fa-check-circle me-1"></i> Calificación ≥ 60%</p>
                                </div>
                                <div class="rounded-3 p-3 stat-icon"><i class="fas fa-check-circle fa-2x"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-danger">
                        <div class="card-body p-3 p-xl-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-1 small fw-semibold text-uppercase">❌ Reprobados</p>
                                    <h2 class="display-4 fw-bold mb-0">${stats.reprobados}</h2>
                                    <p class="text-muted small mt-2 mb-0"><i class="fas fa-times-circle me-1"></i> Calificación &lt; 60%</p>
                                </div>
                                <div class="rounded-3 p-3 stat-icon"><i class="fas fa-times-circle fa-2x"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error updating statistics:', error);
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
        await updateStatistics();

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

            if (isAjaxForm) {
                form.dispatchEvent(new Event('submit'));
            } else {
                form.submit();
            }
        });
    });

    window.addEventListener('popstate', function() {
        if (isAjaxForm) {
            loadData(window.location.href);
        } else {
            window.location.reload();
        }
    });
});

// Función global para eliminar
window.confirmarEliminar = function(url) {
    Swal.fire({
        title: '¿Eliminar registro?',
        text: 'Esta acción no se puede revertir',
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
                text: 'Por favor espere',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: data.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        const form = document.getElementById('filtroForm');
                        if (form && form.dataset.ajax === 'true') {
                            form.dispatchEvent(new Event('submit'));
                        } else {
                            window.location.reload();
                        }
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'No se pudo eliminar el registro',
                        confirmButtonColor: '#d33'
                    });
                });
        }
    });
};
</script>
@endpush