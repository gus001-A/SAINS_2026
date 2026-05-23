@extends('administrador.layouts.master')

@section('title', 'Gestión de Preparatorias - SAINS')

@section('content')
<div class="container-fluid px-4">
    <!-- Header mejorado (estilo cupones) -->
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
                    <a href="{{ route('admin.preparatorias.create') }}" class="btn btn-primary-custom px-4 py-2 shadow-sm">
                        <i class="fas fa-plus-circle me-2"></i>Nueva Preparatoria
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de Filtros (estilo cupones) -->
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
                                   placeholder="Nombre o clave..." value="{{ request('search') }}">
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
                        <div class="col-md-2 col-lg-2">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-building me-1"></i>Tipo
                            </label>
                            <select id="tipoFilter" class="form-select form-select-lg rounded-3">
                                <option value="">Todos</option>
                                <option value="PUBLICO" {{ request('tipo') == 'PUBLICO' ? 'selected' : '' }}>Pública</option>
                                <option value="PRIVADO" {{ request('tipo') == 'PRIVADO' ? 'selected' : '' }}>Privada</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-lg-2">
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

    <!-- Tarjetas de estadísticas (solo 3) -->
    <div class="row mb-4 g-3">
        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Total Preparatorias</p>
                            <h4 class="fw-bold mb-0 text-primary">{{ $totalPreparatorias ?? $preparatorias->total() ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(102,126,234,0.1);">
                            <i class="fas fa-institution text-primary fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Públicas</p>
                            <h4 class="fw-bold mb-0 text-success">{{ $publicas ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(40,167,69,0.1);">
                            <i class="fas fa-landmark text-success fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Privadas</p>
                            <h4 class="fw-bold mb-0 text-warning">{{ $privadas ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(255,193,7,0.1);">
                            <i class="fas fa-building text-warning fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de preparatorias -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Clave</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Centro Educativo</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Estado</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Municipio</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Localidad</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Turno</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Tipo</th>
                                <th class="py-3 px-4 text-white fw-semibold text-center" style="font-size: 0.85rem; width: 140px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaBody">
                            @include('administrador.preparatorias.partials.table_rows', ['preparatorias' => $preparatorias])
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-4 bg-light border-top gap-3">
                    <div class="text-muted small">
                        <i class="fas fa-chart-line me-1"></i>
                        Mostrando <span class="fw-semibold" id="desde">{{ $preparatorias->firstItem() ?? 0 }}</span> - 
                        <span class="fw-semibold" id="hasta">{{ $preparatorias->lastItem() ?? 0 }}</span> 
                        de <span class="fw-semibold" id="total">{{ $preparatorias->total() }}</span> registros
                    </div>
                    <div id="paginationLinks" class="d-flex justify-content-end">
                        {{ $preparatorias->appends(request()->query())->links('pagination::bootstrap-4') }}
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
    /* Estilos consistentes con otros módulos */
    .hover-card {
        transition: all 0.2s ease-in-out;
    }
    
    .hover-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    /* Badges */
    .badge-publica {
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
    
    .badge-privada {
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
    
    .badge-turno {
        background: #e3f2fd;
        color: #1565c0;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .badge-estado {
        background: #f3e5f5;
        color: #6a1b9a;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
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
    
    /* Información de usuario */
    .usuario-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .avatar-mini {
        width: 36px;
        height: 36px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        flex-shrink: 0;
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
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
    .form-control-lg, .form-select-lg {
        font-size: 0.95rem;
        border: 2px solid #e9ecef;
        transition: all 0.2s ease;
        background-color: white;
    }
    
    .form-control-lg:focus, .form-select-lg:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    /* Tabla */
    .table {
        min-width: 1000px;
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
        box-shadow: 0 2px 8px rgba(102,126,234,0.4);
    }
    
    /* Dark Mode */
    body.dark-mode .card {
        background-color: #1a1a2e;
    }
    
    body.dark-mode .bg-light {
        background-color: #0f0f1a !important;
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
    
    body.dark-mode .badge-estado {
        background: rgba(106, 27, 154, 0.2);
        color: #ce93d8;
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
    
    body.dark-mode .modal-header {
        border-bottom-color: rgba(255, 255, 255, 0.1);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function verPreparatoria(id) {
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
                                <div class="codigo-cupon d-inline-flex justify-content-center" style="font-size: 1.1rem; padding: 10px 20px; background: #f8f9fa; border-radius: 20px;">
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
                                        <span class="badge ${prepa.tipo === 'PUBLICO' ? 'badge-publica' : 'badge-privada'}">
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
    }

    function confirmarEliminar(url, nombre) {
        Swal.fire({
            title: '¿Eliminar preparatoria?',
            html: `La preparatoria <strong class="text-primary">${escapeHtml(nombre)}</strong> será eliminada permanentemente.<br><br>Esta acción no se puede deshacer.`,
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
                    html: 'Procesando solicitud',
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

    function cargarTabla() {
        const search = document.getElementById('searchInput')?.value || '';
        const estado = document.getElementById('estadoFilter')?.value || '';
        const tipo = document.getElementById('tipoFilter')?.value || '';
        const turno = document.getElementById('turnoFilter')?.value || '';
        
        const url = new URL(window.location.href);
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        
        if (estado) url.searchParams.set('estado', estado);
        else url.searchParams.delete('estado');
        
        if (tipo) url.searchParams.set('tipo', tipo);
        else url.searchParams.delete('tipo');
        
        if (turno) url.searchParams.set('turno', turno);
        else url.searchParams.delete('turno');
        
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }

    document.getElementById('btnFiltrar')?.addEventListener('click', cargarTabla);
    document.getElementById('btnLimpiar')?.addEventListener('click', () => window.location.href = window.location.pathname);
    document.getElementById('searchInput')?.addEventListener('keypress', e => e.key === 'Enter' && cargarTabla());
    document.getElementById('estadoFilter')?.addEventListener('change', cargarTabla);
    document.getElementById('tipoFilter')?.addEventListener('change', cargarTabla);
    document.getElementById('turnoFilter')?.addEventListener('change', cargarTabla);
</script>
@endpush