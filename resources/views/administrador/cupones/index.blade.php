@extends('administrador.layouts.master')

@section('title', 'Cupones - SAINS')

@section('content')
<div class="container-fluid px-4">
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
                <div>
                    <a href="{{ route('admin.cupones.create') }}" class="btn btn-primary-custom px-4 py-2 shadow-sm">
                        <i class="fas fa-plus-circle me-2"></i>Nuevo Cupón
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de Filtros Avanzado -->
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
                        <div class="col-md-3 col-lg-3">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-search me-1"></i>Buscar cupón
                            </label>
                            <input type="text" id="searchInput" class="form-control form-control-lg rounded-3"
                                placeholder="Código del cupón..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-percent me-1"></i>Tipo de descuento
                            </label>
                            <select id="tipoFilter" class="form-select form-select-lg rounded-3">
                                <option value="">Todos</option>
                                <option value="porcentaje"
                                    {{ request('tipo_descuento') == 'porcentaje' ? 'selected' : '' }}>Porcentaje (%)
                                </option>
                                <option value="cantidad_fija"
                                    {{ request('tipo_descuento') == 'cantidad_fija' ? 'selected' : '' }}>Monto Fijo ($)
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-check-circle me-1"></i>Estado
                            </label>
                            <select id="estatusFilter" class="form-select form-select-lg rounded-3">
                                <option value="">Todos</option>
                                <option value="activo" {{ request('estatus') == 'activo' ? 'selected' : '' }}>Activos
                                </option>
                                <option value="inactivo" {{ request('estatus') == 'inactivo' ? 'selected' : '' }}>
                                    Inactivos</option>
                                <option value="usado" {{ request('estatus') == 'usado' ? 'selected' : '' }}>Usados
                                </option>
                                <option value="expirado" {{ request('estatus') == 'expirado' ? 'selected' : '' }}>
                                    Expirados</option>
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
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Total Cupones</p>
                            <h4 class="fw-bold mb-0 text-primary">{{ $totalCupones ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(102,126,234,0.1);">
                            <i class="fas fa-ticket-alt text-primary fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Activos</p>
                            <h4 class="fw-bold mb-0 text-success">{{ $cuponesActivos ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(40,167,69,0.1);">
                            <i class="fas fa-check-circle text-success fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Usados</p>
                            <h4 class="fw-bold mb-0 text-warning">{{ $cuponesUsados ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(255,193,7,0.1);">
                            <i class="fas fa-check-double text-warning fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Expirados</p>
                            <h4 class="fw-bold mb-0 text-danger">{{ $cuponesExpirados ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(220,53,69,0.1);">
                            <i class="fas fa-hourglass-end text-danger fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de cupones -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Código</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Descuento</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Generado por
                                </th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Usado por</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Creación</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Expiración</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Uso</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Estado</th>
                                <th class="py-3 px-4 text-white fw-semibold text-center"
                                    style="font-size: 0.85rem; width: 140px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaBody">
                            @include('administrador.cupones.partials.table_rows', ['cupones' => $cupones])
                        </tbody>
                    </table>
                </div>

                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-center p-4 bg-light border-top gap-3">
                    <div class="text-muted small">
                        <i class="fas fa-chart-line me-1"></i>
                        Mostrando <span class="fw-semibold" id="desde">{{ $cupones->firstItem() ?? 0 }}</span> -
                        <span class="fw-semibold" id="hasta">{{ $cupones->lastItem() ?? 0 }}</span>
                        de <span class="fw-semibold" id="total">{{ $cupones->total() }}</span> registros
                    </div>
                    <div id="paginationLinks" class="d-flex justify-content-end">
                        {{ $cupones->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalles Mejorado -->
<div class="modal fade" id="cuponModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-ticket-alt me-2"></i>Detalles del Cupón
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="modalContent">
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
/* Animaciones y efectos */
.hover-card {
    transition: all 0.2s ease-in-out;
}

.hover-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
}

/* Badges de tipos de descuento */
.badge-tipo-porcentaje {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.badge-tipo-fijo {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

/* Badges de estado */
.badge-estatus-activo {
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

.badge-estatus-usado {
    background: #fff3e0;
    color: #e65100;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.badge-estatus-expirado {
    background: #ffebee;
    color: #c62828;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.badge-estatus-inactivo {
    background: #e9ecef;
    color: #6c757d;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.badge-expirado-pronto {
    background: #fff8e1;
    color: #f57c00;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin-left: 5px;
}

/* Código del cupón */
.codigo-cupon {
    font-family: 'SF Mono', 'Courier New', monospace;
    font-weight: 700;
    font-size: 0.85rem;
    background: #f8f9fa;
    padding: 5px 12px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.2s;
}

.codigo-cupon:hover {
    border-color: #667eea;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
}

.btn-copiar {
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 4px 6px;
    border-radius: 6px;
    color: #667eea;
    transition: all 0.2s;
}

.btn-copiar:hover {
    background: rgba(102, 126, 234, 0.1);
    transform: scale(1.05);
}

/* Botones de acción */
.btn-accion {
    padding: 5px 12px;
    margin: 0 2px;
    font-size: 0.75rem;
    border-radius: 20px;
    transition: all 0.2s ease;
    font-weight: 500;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
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

/* Avatar de usuario */
.avatar-inicial {
    width: 36px;
    height: 36px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.8rem;
    flex-shrink: 0;
}

.usuario-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.usuario-detalles {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.usuario-nombre {
    font-weight: 600;
    font-size: 0.8rem;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.usuario-email {
    font-size: 0.65rem;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Fecha expiración */
.fecha-expiracion {
    font-size: 0.75rem;
    font-weight: 500;
}

.fecha-expirada {
    color: #c62828;
    text-decoration: line-through;
}

.fecha-proxima {
    color: #f57c00;
}

.fecha-normal {
    color: #2e7d32;
}

/* Botones principales */
.btn-primary-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-weight: 500;
    transition: all 0.2s ease;
    border-radius: 12px;
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-outline-secondary {
    border: 2px solid #e9ecef;
    transition: all 0.2s ease;
    background: transparent;
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
}

.form-control-lg:focus,
.form-select-lg:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Tabla mejorada */
.table {
    min-width: 1100px;
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

.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
}

/* Dark Mode */
body.dark-mode .badge-estatus-activo {
    background: rgba(46, 125, 50, 0.2);
    color: #81c784;
}

body.dark-mode .badge-estatus-usado {
    background: rgba(230, 81, 0, 0.2);
    color: #ffa726;
}

body.dark-mode .badge-estatus-expirado {
    background: rgba(198, 40, 40, 0.2);
    color: #ef9a9a;
}

body.dark-mode .badge-estatus-inactivo {
    background: rgba(108, 117, 125, 0.2);
    color: #adb5bd;
}

body.dark-mode .codigo-cupon {
    background: #2d2d44;
    border-color: #3d3d5c;
    color: #e0e0e0;
}

body.dark-mode .usuario-nombre {
    color: #e0e0e0;
}

body.dark-mode .usuario-email {
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

body.dark-mode .card {
    background-color: #1a1a2e;
}

body.dark-mode .bg-light {
    background-color: #0f0f1a !important;
}

body.dark-mode .modal-content {
    background-color: #1a1a2e;
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

body.dark-mode .page-item.disabled .page-link {
    background-color: #0f0f1a;
    color: #6c757d;
}


/* ==================== ESTILOS MEJORADOS ==================== */

/* Tarjeta de descuento */
.descuento-card {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    padding: 8px 16px;
    border-radius: 16px;
    background: linear-gradient(135deg, #667eea10, #764ba210);
    transition: all 0.3s ease;
}

.descuento-card.porcentaje {
    background: linear-gradient(135deg, #10b98120, #05966920);
    border-left: 3px solid #10b981;
}

.descuento-card.fijo {
    background: linear-gradient(135deg, #667eea20, #764ba220);
    border-left: 3px solid #667eea;
}

.descuento-valor {
    font-size: 1.3rem;
    font-weight: 800;
    color: #1e293b;
    line-height: 1;
}

.descuento-valor span {
    font-size: 0.8rem;
    font-weight: 600;
}

.descuento-tipo {
    font-size: 0.65rem;
    font-weight: 600;
    text-transform: uppercase;
    color: #64748b;
    letter-spacing: 1px;
}

/* Fecha información */
.fecha-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.fecha-info i {
    font-size: 1rem;
    width: 24px;
}

.fecha-info.uso i {
    color: #10b981;
}

/* Expiración mejorada */
.expiracion-info {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.fecha-expiracion-box {
    padding: 8px 12px;
    border-radius: 12px;
    background: #f8f9fa;
    transition: all 0.2s;
}

.fecha-expiracion-box.expirado {
    background: #ffebee;
    border-left: 3px solid #c62828;
}

.fecha-expiracion-box.proximo {
    background: #fff8e1;
    border-left: 3px solid #f57c00;
    animation: pulse 2s infinite;
}

.fecha-expiracion-box.normal {
    background: #e8f5e9;
    border-left: 3px solid #2e7d32;
}

@keyframes pulse {

    0%,
    100% {
        opacity: 1;
    }

    50% {
        opacity: 0.85;
    }
}

.fecha-header {
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 600;
    font-size: 0.85rem;
}

.fecha-hora {
    font-size: 0.7rem;
    color: #64748b;
    margin-top: 4px;
}

/* Tiempo restante */
.tiempo-restante {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 8px;
    border-radius: 8px;
    font-size: 0.7rem;
    font-weight: 500;
    background: #e3f2fd;
    color: #1565c0;
    width: fit-content;
}

.tiempo-restante.urgente {
    background: #ffebee;
    color: #c62828;
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {

    0%,
    100% {
        transform: translateX(0);
    }

    25% {
        transform: translateX(-2px);
    }

    75% {
        transform: translateX(2px);
    }
}

/* Sin expiración */
.sin-expiracion {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    padding: 8px;
    border-radius: 12px;
    background: #f8f9fa;
    color: #64748b;
}

.sin-expiracion i {
    font-size: 1.2rem;
}

.sin-expiracion span {
    font-size: 0.75rem;
    font-weight: 500;
}

.sin-expiracion small {
    font-size: 0.65rem;
}

/* Badges mejorados */
.badge-expirado-pronto {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    background: #fff8e1;
    color: #f57c00;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
    width: fit-content;
    animation: pulse 2s infinite;
}

.badge-vencido {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    background: #ffebee;
    color: #c62828;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
    width: fit-content;
}

.badge-utilizado {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    background: #e8f5e9;
    color: #2e7d32;
    border-radius: 20px;
    font-size: 0.6rem;
    font-weight: 600;
}

/* Estado badge mejorado */
.estado-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    background: white;
    border-left: 3px solid;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    position: relative;
}

.estado-badge.estado-activo {
    background: #e8f5e9;
    color: #2e7d32;
}

.estado-badge.estado-usado {
    background: #fff3e0;
    color: #e65100;
}

.estado-badge.estado-expirado {
    background: #ffebee;
    color: #c62828;
}

.estado-badge.estado-inactivo {
    background: #e9ecef;
    color: #6c757d;
}

.estado-alerta {
    position: absolute;
    top: -5px;
    right: -5px;
    font-size: 0.7rem;
    animation: bounce 1s infinite;
}

@keyframes bounce {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-3px);
    }
}

.estado-tooltip {
    position: relative;
    display: inline-flex;
    margin-left: 8px;
    cursor: help;
}

.estado-tooltip i {
    font-size: 0.7rem;
    color: #64748b;
}

.estado-tooltip span {
    visibility: hidden;
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: #1e293b;
    color: white;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.65rem;
    white-space: nowrap;
    z-index: 10;
    opacity: 0;
    transition: opacity 0.2s;
}

.estado-tooltip:hover span {
    visibility: visible;
    opacity: 1;
}

/* Disponible badge */
.disponible-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: #e3f2fd;
    color: #1565c0;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    width: fit-content;
}

.no-uso {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: #f1f3f4;
    color: #5f6368;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

/* Acciones buttons mejoradas */
.acciones-buttons {
    display: flex;
    gap: 6px;
    justify-content: center;
}

.btn-accion {
    padding: 6px 12px;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 500;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: none;
    cursor: pointer;
}

.btn-accion span {
    display: inline;
}

@media (max-width: 1200px) {
    .btn-accion span {
        display: none;
    }

    .btn-accion {
        padding: 6px 10px;
    }
}

.btn-ver {
    background: #e3f2fd;
    color: #1565c0;
}

.btn-ver:hover {
    background: #1565c0;
    color: white;
    transform: translateY(-2px);
}

.btn-editar {
    background: #e8f5e9;
    color: #2e7d32;
}

.btn-editar:hover {
    background: #2e7d32;
    color: white;
    transform: translateY(-2px);
}

.btn-eliminar {
    background: #ffebee;
    color: #c62828;
}

.btn-eliminar:hover {
    background: #c62828;
    color: white;
    transform: translateY(-2px);
}

/* Empty state mejorado */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, #667eea15, #764ba215);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-icon i {
    font-size: 3rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.empty-content h5 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 8px;
}

.empty-content p {
    color: #64748b;
    margin-bottom: 20px;
}

.btn-empty-action {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 24px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-empty-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    color: white;
}

/* Dark mode mejorado */
body.dark-mode .descuento-card {
    background: linear-gradient(135deg, #667eea08, #764ba208);
}

body.dark-mode .descuento-valor {
    color: #e0e0e0;
}

body.dark-mode .fecha-expiracion-box {
    background: #2d2d44;
}

body.dark-mode .sin-expiracion {
    background: #2d2d44;
    color: #9ca3af;
}

body.dark-mode .estado-badge {
    background: #1a1a2e;
}

body.dark-mode .disponible-badge {
    background: #1e3a5f;
    color: #64b5f6;
}

body.dark-mode .no-uso {
    background: #2d2d44;
    color: #9ca3af;
}

body.dark-mode .empty-state {
    background: #1a1a2e;
}

body.dark-mode .empty-content h5 {
    color: #e0e0e0;
}

body.dark-mode .empty-content p {
    color: #9ca3af;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Definir rutas base desde Laravel
const routes = {
    cuponShow: "{{ route('admin.cupones.show', ['id' => ':id']) }}",
    cuponEdit: "{{ route('admin.cupones.edit', ['id' => ':id']) }}",
    cuponDestroy: "{{ route('admin.cupones.destroy', ['id' => ':id']) }}"
};

function replaceRouteId(route, id) {
    return route.replace(':id', id);
}

// Copiar código al portapapeles
function copiarCodigo(codigo) {
    navigator.clipboard.writeText(codigo).then(() => {
        Swal.fire({
            icon: 'success',
            title: '¡Copiado!',
            text: `Código ${codigo} copiado al portapapeles`,
            timer: 1500,
            showConfirmButton: false,
            background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#1e293b'
        });
    });
}

// Ver detalles del cupón (SIN HORAS)
function verCupon(id) {
    const modal = new bootstrap.Modal(document.getElementById('cuponModal'));
    const modalContent = document.getElementById('modalContent');

    modalContent.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2 text-muted">Cargando información...</p>
        </div>
    `;

    modal.show();

    fetch(replaceRouteId(routes.cuponShow, id))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const valorDescuento = data.data.tipo_descuento === 'porcentaje' ?
                    data.data.valor_descuento + '%' :
                    (Math.floor(data.data.valor_descuento) == data.data.valor_descuento ?
                        '$' + Number(data.data.valor_descuento).toLocaleString() :
                        '$' + parseFloat(data.data.valor_descuento).toFixed(2));
                
                // Formatear fechas SIN HORA
                const formatoFecha = (fecha) => {
                    if (!fecha) return '—';
                    const date = new Date(fecha);
                    return date.toLocaleDateString('es-MX', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit'
                    });
                };
                
                const fechaCreacion = data.data.fecha_genero ? formatoFecha(data.data.fecha_genero) : '—';
                const fechaExpiracion = data.data.fecha_expiracion ? formatoFecha(data.data.fecha_expiracion) : 'Sin expiración';
                const fechaUso = data.data.fecha_uso ? formatoFecha(data.data.fecha_uso) : '—';

                modalContent.innerHTML = `
                    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                        <div class="text-center p-4 rounded-4" style="background: linear-gradient(135deg, #667eea10, #764ba210);">
                            <div class="codigo-cupon d-inline-flex justify-content-center" style="font-size: 1.1rem; padding: 10px 20px;">
                                <i class="fas fa-tag me-2 text-primary"></i>
                                <strong>${data.data.codigo}</strong>
                                <button class="btn-copiar" onclick="copiarCodigo('${data.data.codigo}')" title="Copiar código">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 rounded-3 border" style="background: rgba(102,126,234,0.05);">
                                    <small class="text-muted d-block mb-1"><i class="fas fa-percent me-1"></i> Descuento</small>
                                    <strong class="fs-4 text-primary">${valorDescuento}</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-3 border" style="background: rgba(102,126,234,0.05);">
                                    <small class="text-muted d-block mb-1"><i class="fas fa-user me-1"></i> Generado por</small>
                                    <strong>${data.data.usuario_genero?.name || data.data.usuario_genero?.correo || 'Sistema'}</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-3 border" style="background: rgba(102,126,234,0.05);">
                                    <small class="text-muted d-block mb-1"><i class="fas fa-calendar me-1"></i> Fecha creación</small>
                                    <strong>${fechaCreacion}</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-3 border" style="background: rgba(102,126,234,0.05);">
                                    <small class="text-muted d-block mb-1"><i class="fas fa-hourglass-half me-1"></i> Fecha expiración</small>
                                    <strong class="${data.data.esta_expirado ? 'text-danger' : ''}">
                                        ${fechaExpiracion}
                                    </strong>
                                    ${data.data.esta_expirado ? '<br><small class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>Este cupón ya expiró</small>' : ''}
                                </div>
                            </div>
                            ${data.data.fecha_uso ? `
                            <div class="col-6">
                                <div class="p-3 rounded-3 border" style="background: rgba(102,126,234,0.05);">
                                    <small class="text-muted d-block mb-1"><i class="fas fa-calendar-check me-1"></i> Fecha uso</small>
                                    <strong>${fechaUso}</strong>
                                </div>
                            </div>
                            ` : ''}
                            ${data.data.usuario_uso ? `
                            <div class="col-12">
                                <div class="p-3 rounded-3 border" style="background: rgba(102,126,234,0.05);">
                                    <small class="text-muted d-block mb-1"><i class="fas fa-user-check me-1"></i> Usado por</small>
                                    <div class="usuario-info mt-2">
                                        <div class="avatar-inicial" style="background: linear-gradient(135deg, #f59e0b, #ef4444);">
                                            ${(data.data.usuario_uso?.name || data.data.usuario_uso?.correo || 'U').charAt(0).toUpperCase()}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">${data.data.usuario_uso?.name || ''}</div>
                                            <div class="small text-muted">${data.data.usuario_uso?.correo || ''}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ` : ''}
                            <div class="col-12">
                                <div class="p-3 rounded-3 border" style="background: rgba(102,126,234,0.05);">
                                    <small class="text-muted d-block mb-1"><i class="fas fa-info-circle me-1"></i> Estado</small>
                                    <span class="badge ${data.data.usado ? 'badge-estatus-usado' : (data.data.estatus === 'expirado' || data.data.esta_expirado ? 'badge-estatus-expirado' : (data.data.estatus === 'inactivo' ? 'badge-estatus-inactivo' : 'badge-estatus-activo'))}">
                                        <i class="fas ${data.data.usado ? 'fa-check-double' : (data.data.estatus === 'expirado' || data.data.esta_expirado ? 'fa-hourglass-end' : (data.data.estatus === 'inactivo' ? 'fa-ban' : 'fa-check-circle'))} me-1"></i>
                                        ${data.data.usado ? 'Usado' : (data.data.estatus === 'expirado' || data.data.esta_expirado ? 'Expirado' : (data.data.estatus === 'inactivo' ? 'Inactivo' : 'Activo'))}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                modalContent.innerHTML =
                    `<p class="text-center text-danger py-4"><i class="fas fa-exclamation-circle me-2"></i>Error al cargar los datos</p>`;
            }
        })
        .catch(error => {
            modalContent.innerHTML =
                `<p class="text-center text-danger py-4"><i class="fas fa-exclamation-circle me-2"></i>Error al cargar los datos</p>`;
        });
}
// Editar cupón
function editarCupon(id) {
    window.location.href = replaceRouteId(routes.cuponEdit, id);
}

// Eliminar cupón con confirmación mejorada
function eliminarCupon(id, codigo) {
    Swal.fire({
        title: '¿Eliminar cupón?',
        html: `El cupón <strong class="text-primary">${codigo}</strong> será eliminado permanentemente.<br><br>Esta acción no se puede deshacer.`,
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
            form.action = replaceRouteId(routes.cuponDestroy, id);
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

// Cargar tabla con filtros
function cargarTabla() {
    const search = document.getElementById('searchInput')?.value || '';
    const tipo = document.getElementById('tipoFilter')?.value || '';
    const estatus = document.getElementById('estatusFilter')?.value || '';

    const url = new URL(window.location.href);
    if (search) url.searchParams.set('search', search);
    else url.searchParams.delete('search');

    if (tipo) url.searchParams.set('tipo_descuento', tipo);
    else url.searchParams.delete('tipo_descuento');

    if (estatus) url.searchParams.set('estatus', estatus);
    else url.searchParams.delete('estatus');

    url.searchParams.set('page', 1);
    window.location.href = url.toString();
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    const btnFiltrar = document.getElementById('btnFiltrar');
    const btnLimpiar = document.getElementById('btnLimpiar');
    const searchInput = document.getElementById('searchInput');
    const tipoFilter = document.getElementById('tipoFilter');
    const estatusFilter = document.getElementById('estatusFilter');

    if (btnFiltrar) {
        btnFiltrar.addEventListener('click', cargarTabla);
    }

    if (btnLimpiar) {
        btnLimpiar.addEventListener('click', function() {
            window.location.href = window.location.pathname;
        });
    }

    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') cargarTabla();
        });
    }

    if (tipoFilter) {
        tipoFilter.addEventListener('change', cargarTabla);
    }

    if (estatusFilter) {
        estatusFilter.addEventListener('change', cargarTabla);
    }
});
</script>
@endpush