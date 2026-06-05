@extends('administrador.layouts.master')

@section('title', 'Cupones - SAINS')

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
                                <i class="fas fa-ticket-alt fa-2x"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
                            </div>
                            <h1 class="display-5 fw-bold mb-0"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                                Cupones de Descuento
                            </h1>
                        </div>
                        <p class="text-muted fs-5 mb-0">Gestione códigos promocionales y ofertas especiales</p>
                    </div>
                    <div class="d-flex gap-3">
                        <a href="{{ route('admin.cupones.generar-masivo') }}"
                            class="btn btn-success px-4 py-2 shadow-sm"
                            style="background: linear-gradient(135deg, #28a745, #1e7e34); border: none; border-radius: 12px;">
                            <i class="fas fa-layer-group me-2"></i>Generar Múltiples
                        </a>
                        <a href="{{ route('admin.cupones.create') }}"
                            class="btn btn-primary-custom px-4 py-2 shadow-sm">
                            <i class="fas fa-plus-circle me-2"></i>Nuevo Cupón
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
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Total Cupones</p>
                                <h2 class="display-4 fw-bold mb-0">{{ $totalCupones ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0"><i class="fas fa-database me-1"></i> Registros
                                    activos</p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon"><i class="fas fa-ticket-alt fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-success">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Activos</p>
                                <h2 class="display-4 fw-bold mb-0">{{ $cuponesActivos ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0"><i class="fas fa-check-circle me-1"></i> Cupones
                                    disponibles</p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon"><i class="fas fa-check-circle fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-warning">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Usados</p>
                                <h2 class="display-4 fw-bold mb-0">{{ $cuponesUsados ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0"><i class="fas fa-check-double me-1"></i> Ya
                                    utilizados</p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon"><i class="fas fa-check-double fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-danger">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-semibold text-uppercase">Expirados</p>
                                <h2 class="display-4 fw-bold mb-0">{{ $cuponesExpirados ?? 0 }}</h2>
                                <p class="text-muted small mt-2 mb-0"><i class="fas fa-hourglass-end me-1"></i> Cupones
                                    vencidos</p>
                            </div>
                            <div class="rounded-3 p-3 stat-icon"><i class="fas fa-hourglass-end fa-2x"></i></div>
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
                        <form method="GET" action="{{ route('admin.cupones.index') }}" id="filtroForm">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3 col-lg-3">
                                    <label class="form-label fw-semibold text-muted mb-2"><i
                                            class="fas fa-search me-1"></i>Buscar cupón</label>
                                    <input type="text" name="search" class="form-control form-control-lg rounded-3"
                                        placeholder="Código del cupón..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <label class="form-label fw-semibold text-muted mb-2"><i
                                            class="fas fa-percent me-1"></i>Tipo de descuento</label>
                                    <select name="tipo_descuento" class="form-select form-select-lg rounded-3">
                                        <option value="">Todos</option>
                                        <option value="porcentaje"
                                            {{ request('tipo_descuento') == 'porcentaje' ? 'selected' : '' }}>Porcentaje
                                            (%)</option>
                                        <option value="cantidad_fija"
                                            {{ request('tipo_descuento') == 'cantidad_fija' ? 'selected' : '' }}>Monto
                                            Fijo ($)</option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <label class="form-label fw-semibold text-muted mb-2"><i
                                            class="fas fa-check-circle me-1"></i>Estado</label>
                                    <select name="estatus" class="form-select form-select-lg rounded-3">
                                        <option value="">Todos</option>
                                        <option value="activo" {{ request('estatus') == 'activo' ? 'selected' : '' }}>
                                            Activos</option>
                                        <option value="inactivo"
                                            {{ request('estatus') == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                                        <option value="usado" {{ request('estatus') == 'usado' ? 'selected' : '' }}>
                                            Usados</option>
                                        <option value="expirado"
                                            {{ request('estatus') == 'expirado' ? 'selected' : '' }}>Expirados</option>
                                    </select>
                                </div>
                                <input type="hidden" name="orden_campo" id="orden_campo"
                                    value="{{ request('orden_campo', 'id') }}">
                                <input type="hidden" name="orden_direccion" id="orden_direccion"
                                    value="{{ request('orden_direccion', 'desc') }}">
                                <div class="col-md-3 col-lg-3">
                                    <div class="d-flex gap-2">
                                        <button type="submit"
                                            class="btn btn-primary-custom w-100 py-2 rounded-3 shadow-sm"><i
                                                class="fas fa-filter me-2"></i>Filtrar</button>
                                        <a href="{{ route('admin.cupones.index') }}"
                                            class="btn btn-outline-secondary w-100 py-2 rounded-3"><i
                                                class="fas fa-times me-2"></i>Limpiar</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de cupones -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-semibold mb-0"><i class="fas fa-list me-2 text-primary"></i>Listado de Cupones
                            </h5>
                            <span class="badge bg-primary rounded-pill">{{ $cupones->total() }} registros</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="min-width: 1300px;">
                                <thead class="table-header">
                                    <tr>
                                        <th class="py-3 px-4 fw-semibold"><a href="#" class="ordenar-link"
                                                data-campo="codigo"><i class="fas fa-tag me-1"></i> Código</a></th>
                                        <th class="py-3 px-4 fw-semibold"><a href="#" class="ordenar-link"
                                                data-campo="descuento"><i class="fas fa-percent me-1"></i> Descuento</a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold"><a href="#" class="ordenar-link"
                                                data-campo="generador"><i class="fas fa-user me-1"></i> Generado por</a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold"><i class="fas fa-user-check me-1"></i> Usado
                                            por</th>
                                        <th class="py-3 px-4 fw-semibold"><a href="#" class="ordenar-link"
                                                data-campo="fecha_creacion"><i class="fas fa-calendar-plus me-1"></i>
                                                Creación</a></th>
                                        <th class="py-3 px-4 fw-semibold"><a href="#" class="ordenar-link"
                                                data-campo="fecha_expiracion"><i class="fas fa-hourglass-half me-1"></i>
                                                Expiración</a></th>
                                        <th class="py-3 px-4 fw-semibold"><i class="fas fa-calendar-check me-1"></i> Uso
                                        </th>
                                        <th class="py-3 px-4 fw-semibold"><a href="#" class="ordenar-link"
                                                data-campo="estado"><i class="fas fa-check-circle me-1"></i> Estado</a>
                                        </th>
                                        <th class="py-3 px-4 fw-semibold text-center"><i class="fas fa-cog me-1"></i>
                                            Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaBody">
                                    @include('administrador.cupones.partials.table_rows', ['cupones' => $cupones])
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-center p-4 bg-light border-top gap-3">
                        <div class="text-muted small"><i class="fas fa-chart-line me-1"></i> Mostrando <span
                                class="fw-semibold text-primary">{{ $cupones->firstItem() ?? 0 }}</span> - <span
                                class="fw-semibold text-primary">{{ $cupones->lastItem() ?? 0 }}</span> de <span
                                class="fw-semibold text-primary">{{ $cupones->total() }}</span> registros</div>
                        <div class="d-flex justify-content-end">
                            {{ $cupones->appends(request()->query())->links('pagination::bootstrap-4') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalles -->
<div class="modal fade" id="cuponModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 p-4"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-white bg-opacity-20 p-2"><i
                            class="fas fa-ticket-alt text-white fa-lg"></i></div>
                    <h5 class="modal-title text-white fw-bold">Detalles del Cupón</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="modalContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2">Cargando información...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ============================================ */
/* ESTILOS GENERALES - TARJETAS Y FILTROS */
/* ============================================ */

/* Tarjetas de estadísticas */
.stat-card-primary,
.stat-card-success,
.stat-card-warning,
.stat-card-danger {
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
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
}

/* Tabla header */
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

.ordenar-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #1e293b;
    text-decoration: none;
    transition: all 0.2s ease;
}

.ordenar-link:hover {
    color: #667eea;
    transform: translateY(-1px);
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

/* ============================================ */
/* ESTILOS PARA LA TABLA - CUPONES (PARTIALS) */
/* ============================================ */

/* Código del cupón */
.codigo-cupon {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    padding: 6px 12px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}

.codigo-cupon:hover {
    border-color: #667eea;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
}

.btn-copiar {
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 4px;
    border-radius: 6px;
    color: #667eea;
    transition: all 0.2s;
}

.btn-copiar:hover {
    background: rgba(102, 126, 234, 0.1);
    transform: scale(1.05);
}

/* Tarjeta de descuento */
.descuento-card {
    text-align: center;
    padding: 8px 12px;
    border-radius: 12px;
    display: inline-block;
    min-width: 80px;
}

.descuento-card.porcentaje {
    background: linear-gradient(135deg, #667eea15, #764ba215);
    border-left: 3px solid #667eea;
}

.descuento-card.fijo {
    background: linear-gradient(135deg, #10b98115, #05966915);
    border-left: 3px solid #10b981;
}

.descuento-valor {
    font-size: 1.1rem;
    font-weight: 800;
    color: #1e293b;
}

.descuento-valor span {
    font-size: 0.7rem;
    font-weight: 400;
}

.descuento-tipo {
    font-size: 0.6rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #94a3b8;
}

/* Información de usuario */
.usuario-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.avatar-mini {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    color: white;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.usuario-detalles {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.usuario-nombre {
    font-weight: 600;
    font-size: 0.85rem;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.usuario-email {
    font-size: 0.7rem;
    color: #94a3b8;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Badge disponible */
.badge-disponible {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #e3f2fd;
    color: #1565c0;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

/* Fechas */
.fecha-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.fecha-info.uso i {
    color: #10b981;
}

/* Fecha expiración */
.expiracion-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.fecha-expiracion-box {
    padding: 6px 12px;
    border-radius: 10px;
    display: inline-block;
    background: #f8fafc;
}

.fecha-header {
    display: flex;
    align-items: center;
    gap: 8px;
}

.fecha-expiracion-box.expirado {
    background: #ffebee;
    color: #c62828;
}

.fecha-expiracion-box.proximo {
    background: #fff3e0;
    color: #e65100;
}

.fecha-expiracion-box.normal {
    background: #e3f2fd;
    color: #1565c0;
}

.badge-vencido {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #ffebee;
    color: #c62828;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
}

.sin-expiracion {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #94a3b8;
}

.no-uso {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #94a3b8;
}

/* Badges de estado */
.badge-state {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 25px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-activo {
    background: #e8f5e9;
    color: #2e7d32;
}

.badge-usado {
    background: #fff3e0;
    color: #e65100;
}

.badge-expirado {
    background: #ffebee;
    color: #c62828;
}

.badge-inactivo {
    background: #f1f5f9;
    color: #64748b;
}

/* Botones de acción */
.btn-action {
    padding: 0.5rem 1rem;
    margin: 0 2px;
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

/* Empty state */
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
    color: #94a3b8;
    margin-bottom: 1rem;
}

/* Animaciones */
.fade-in {
    animation: fadeIn 0.3s ease-out forwards;
}

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

/* Modal */
.modal {
    z-index: 1050;
}

.modal-backdrop {
    z-index: 1040;
}

.modal-content {
    z-index: 1051;
}

.modal.show {
    display: block !important;
    background-color: rgba(0, 0, 0, 0.5);
}

/* ============================================ */
/* DARK MODE */
/* ============================================ */

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

body.dark-mode .card {
    background-color: #1e293b;
}

body.dark-mode .bg-light {
    background-color: #0f172a !important;
}

body.dark-mode .form-control-lg,
body.dark-mode .form-select-lg {
    background-color: #0f172a;
    border-color: #334155;
    color: #e2e8f0;
}

body.dark-mode .btn-outline-secondary {
    border-color: #475569;
    color: #cbd5e1;
}

body.dark-mode .page-link {
    background-color: #0f172a;
    color: #818cf8;
}

body.dark-mode .table td {
    border-bottom-color: rgba(255, 255, 255, 0.05);
    color: #e0e0e0;
}

body.dark-mode .table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.08);
}

body.dark-mode .alert-info {
    background-color: #1e3a5f;
    border-color: #1e3a8a;
    color: #a5f3fc;
}

/* Dark Mode - Estilos de la tabla */
body.dark-mode .codigo-cupon {
    background: #1e293b;
    border-color: #334155;
    color: #e0e0e0;
}

body.dark-mode .descuento-valor {
    color: #f1f5f9;
}

body.dark-mode .usuario-nombre {
    color: #f1f5f9;
}

body.dark-mode .usuario-email {
    color: #94a3b8;
}

body.dark-mode .badge-activo {
    background: #064e3b;
    color: #34d399;
}

body.dark-mode .badge-usado {
    background: #451a03;
    color: #fbbf24;
}

body.dark-mode .badge-expirado {
    background: #450a0a;
    color: #f87171;
}

body.dark-mode .badge-inactivo {
    background: #1e293b;
    color: #94a3b8;
}

body.dark-mode .badge-disponible {
    background: #1e3a5f;
    color: #7ab7ef;
}

body.dark-mode .btn-view {
    background: #0c4a6e;
    color: #7ab7ef;
}

body.dark-mode .btn-view:hover {
    background: #1565c0;
    color: white;
}

body.dark-mode .btn-edit {
    background: #064e3b;
    color: #4ade80;
}

body.dark-mode .btn-edit:hover {
    background: #2e7d32;
    color: white;
}

body.dark-mode .btn-delete {
    background: #450a0a;
    color: #f87171;
}

body.dark-mode .btn-delete:hover {
    background: #c62828;
    color: white;
}

body.dark-mode .fecha-expiracion-box {
    background: #1e293b;
}

body.dark-mode .fecha-expiracion-box.expirado {
    background: #450a0a;
    color: #f87171;
}

body.dark-mode .fecha-expiracion-box.proximo {
    background: #451a03;
    color: #fbbf24;
}

body.dark-mode .fecha-expiracion-box.normal {
    background: #1e3a5f;
    color: #7ab7ef;
}

body.dark-mode .empty-state-title {
    color: #f1f5f9;
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
        const direccionActual = '{{ request("orden_direccion", "desc") }}';
        const campoActual = '{{ request("orden_campo", "id") }}';
        let nuevaDireccion = (campoActual === campo && direccionActual === 'asc') ? 'desc' : 'asc';
        document.getElementById('orden_campo').value = campo;
        document.getElementById('orden_direccion').value = nuevaDireccion;
        document.getElementById('filtroForm').submit();
    });
});

// Auto-submit del formulario de filtros
let timeoutId;
const searchInput = document.querySelector('input[name="search"]');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => document.getElementById('filtroForm').submit(), 500);
    });
}

document.querySelector('select[name="tipo_descuento"]')?.addEventListener('change', () => document.getElementById(
    'filtroForm').submit());
document.querySelector('select[name="estatus"]')?.addEventListener('change', () => document.getElementById('filtroForm')
    .submit());

// Copiar código
function copiarCodigo(codigo) {
    navigator.clipboard.writeText(codigo).then(() => {
        Swal.fire({
            icon: 'success',
            title: '¡Copiado!',
            text: `Código ${codigo} copiado`,
            timer: 1500,
            showConfirmButton: false
        });
    });
}

// Ver cupón
function verCupon(id) {
    const modalElement = document.getElementById('cuponModal');
    if (!modalElement) return;

    const modal = new bootstrap.Modal(modalElement);
    const modalContent = document.getElementById('modalContent');
    modalContent.innerHTML =
        `<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2">Cargando información...</p></div>`;
    modal.show();

    fetch(`/administrador/cupones/${id}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const cupon = data.data;
                const valorDesc = cupon.tipo_descuento === 'porcentaje' ? cupon.valor_descuento + '%' : '$' +
                    Number(cupon.valor_descuento).toLocaleString();
                modalContent.innerHTML = `
                    <div class="d-flex flex-column gap-3">
                        <div class="text-center p-4 rounded-4" style="background: linear-gradient(135deg, #667eea10, #764ba210);">
                            <div class="codigo-cupon d-inline-flex"><i class="fas fa-tag me-2 text-primary"></i><strong>${escapeHtml(cupon.codigo)}</strong><button class="btn-copiar" onclick="copiarCodigo('${cupon.codigo}')"><i class="fas fa-copy"></i></button></div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6"><div class="p-3 rounded-3 bg-light"><small class="text-muted d-block mb-1">Descuento</small><strong class="fs-4 text-primary">${valorDesc}</strong></div></div>
                            <div class="col-6"><div class="p-3 rounded-3 bg-light"><small class="text-muted d-block mb-1">Generado por</small><strong>${escapeHtml(cupon.usuario_genero?.name || cupon.usuario_genero?.correo || 'Sistema')}</strong></div></div>
                            <div class="col-6"><div class="p-3 rounded-3 bg-light"><small class="text-muted d-block mb-1">Creación</small><strong>${cupon.fecha_genero || '—'}</strong></div></div>
                            <div class="col-6"><div class="p-3 rounded-3 bg-light"><small class="text-muted d-block mb-1">Expiración</small><strong>${cupon.fecha_expiracion || 'Sin expiración'}</strong></div></div>
                            ${cupon.usuario_uso ? `<div class="col-12"><div class="p-3 rounded-3 bg-light"><small class="text-muted d-block mb-1">Usado por</small><strong>${escapeHtml(cupon.usuario_uso?.name || cupon.usuario_uso?.correo || '—')}</strong></div></div>` : ''}
                        </div>
                    </div>
                `;
            } else {
                modalContent.innerHTML = `<p class="text-center text-danger py-4">Error al cargar los datos</p>`;
            }
        })
        .catch(() => {
            modalContent.innerHTML = `<p class="text-center text-danger py-4">Error al cargar los datos</p>`;
        });
}

// Eliminar cupón
function eliminarCupon(id, codigo) {
    Swal.fire({
        title: '¿Eliminar cupón?',
        html: `El cupón <strong class="text-primary">${escapeHtml(codigo)}</strong> será eliminado permanentemente.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-trash-alt me-2"></i>Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/administrador/cupones/${id}`;
            form.innerHTML =
                `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.content}"><input type="hidden" name="_method" value="DELETE">`;
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