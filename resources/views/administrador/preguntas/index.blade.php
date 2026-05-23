@extends('administrador.layouts.master')

@section('title', 'Gestión de Preguntas - SAINS')

@section('content')
<div class="container-fluid px-4">
    <!-- Header mejorado (estilo cupones) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-3 p-2" style="background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);">
                            <i class="fas fa-brain fa-2x" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
                        </div>
                        <h1 class="display-5 fw-bold mb-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                            Banco de Preguntas
                        </h1>
                    </div>
                    <p class="text-muted fs-5 mb-0">Gestiona las preguntas para los exámenes de los estudiantes</p>
                </div>
                <div>
                    <a href="{{ route('admin.preguntas.create') }}" class="btn btn-primary-custom px-4 py-2 shadow-sm">
                        <i class="fas fa-plus-circle me-2"></i>Nueva Pregunta
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
                        <div class="col-md-5 col-lg-5">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-search me-1"></i>Buscar pregunta
                            </label>
                            <input type="text" id="searchInput" class="form-control form-control-lg rounded-3" 
                                   placeholder="Pregunta o contenido..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-layer-group me-1"></i>Área
                            </label>
                            <select id="areaFilter" class="form-select form-select-lg rounded-3">
                                <option value="">Todas las áreas</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ request('id_area') == $area->id ? 'selected' : '' }}>{{ $area->nombre ?? $area->area }}</option>
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

    <!-- Tarjetas de estadísticas (estilo cupones) - SIN TASA DE APROBACIÓN -->
    <div class="row mb-4 g-3">
        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Total Preguntas</p>
                            <h4 class="fw-bold mb-0 text-primary">{{ $totalPreguntas ?? $preguntas->total() ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(102,126,234,0.1);">
                            <i class="fas fa-database text-primary fa-lg"></i>
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
                            <p class="text-muted mb-0 small">Áreas Registradas</p>
                            <h4 class="fw-bold mb-0 text-success">{{ $totalAreas ?? $areas->count() ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(40,167,69,0.1);">
                            <i class="fas fa-layer-group text-success fa-lg"></i>
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
                            <p class="text-muted mb-0 small">Preguntas Activas</p>
                            <h4 class="fw-bold mb-0 text-warning">{{ $preguntasActivas ?? $preguntas->count() ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(255,193,7,0.1);">
                            <i class="fas fa-check-circle text-warning fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de preguntas (estilo cupones) -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">ID</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Pregunta</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Área</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Respuestas</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Correcta</th>
                                <th class="py-3 px-4 text-white fw-semibold text-center" style="font-size: 0.85rem; width: 140px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaBody">
                            @include('administrador.preguntas.partials.table_rows', ['preguntas' => $preguntas])
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-4 bg-light border-top gap-3">
                    <div class="text-muted small">
                        <i class="fas fa-chart-line me-1"></i>
                        Mostrando <span class="fw-semibold" id="desde">{{ $preguntas->firstItem() ?? 0 }}</span> - 
                        <span class="fw-semibold" id="hasta">{{ $preguntas->lastItem() ?? 0 }}</span> 
                        de <span class="fw-semibold" id="total">{{ $preguntas->total() }}</span> registros
                    </div>
                    <div id="paginationLinks" class="d-flex justify-content-end">
                        {{ $preguntas->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalles (estilo cupones) -->
<div class="modal fade" id="showPreguntaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-question-circle me-2"></i>Detalles de la Pregunta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
    /* Estilos consistentes con cupones */
    .hover-card {
        transition: all 0.2s ease-in-out;
    }
    
    .hover-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    /* Badge área */
    .badge-area {
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
        gap: 4px;
    }
    
    .answer-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        background: #f1f5f9;
        border-radius: 12px;
        font-size: 0.7rem;
        color: #475569;
        max-width: 200px;
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
        min-width: 900px;
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
    
    .pregunta-texto {
        max-width: 350px;
        white-space: normal;
        word-wrap: break-word;
        line-height: 1.4;
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
    
    /* Modal */
    .modal-content {
        border-radius: 16px;
    }
    
    /* Dark Mode */
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
    
    body.dark-mode .modal-header {
        border-bottom-color: rgba(255, 255, 255, 0.1);
    }
    
    body.dark-mode .btn-close-white {
        filter: invert(1);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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
        
        fetch(`/administrador/preguntas/${id}`)
            .then(response => response.json())
            .then(data => {
                const pregunta = data.data || data;
                modalContent.innerHTML = `
                    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                        <div class="p-3 rounded-3" style="background: rgba(102,126,234,0.05);">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-hashtag me-1"></i>ID: ${pregunta.id}
                                </small>
                                <span class="badge-area">
                                    <i class="fas fa-layer-group fa-xs"></i>
                                    ${escapeHtml(pregunta.area?.nombre || pregunta.area?.area || 'Sin área')}
                                </span>
                            </div>
                            <small class="text-muted d-block mb-2">
                                <i class="fas fa-question-circle me-1"></i>Pregunta
                            </small>
                            <strong class="fs-5 d-block">${escapeHtml(pregunta.pregunta)}</strong>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 rounded-3" style="background: rgba(102,126,234,0.05);">
                                    <small class="text-muted d-block mb-2">
                                        <i class="fas fa-arrow-right me-1"></i>Respuesta 1
                                    </small>
                                    <div class="answer-detail p-2 rounded-3" style="background: #f8f9fa;">
                                        ${escapeHtml(pregunta.respuesta1 || 'No registrada')}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3" style="background: rgba(102,126,234,0.05);">
                                    <small class="text-muted d-block mb-2">
                                        <i class="fas fa-arrow-right me-1"></i>Respuesta 2
                                    </small>
                                    <div class="answer-detail p-2 rounded-3" style="background: #f8f9fa;">
                                        ${escapeHtml(pregunta.respuesta2 || 'No registrada')}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 rounded-3" style="background: rgba(102,126,234,0.05);">
                            <small class="text-muted d-block mb-2">
                                <i class="fas fa-check-circle me-1"></i>Respuesta Correcta
                            </small>
                            <span class="badge-correcta">
                                <i class="fas fa-check-circle fa-xs"></i>
                                ${escapeHtml(pregunta.respuesta_correcta)}
                            </span>
                        </div>
                    </div>
                `;
            })
            .catch(error => {
                modalContent.innerHTML = `<p class="text-center text-danger py-4"><i class="fas fa-exclamation-circle me-2"></i>Error al cargar los datos</p>`;
            });
    }

    function confirmarEliminar(url, idPregunta, textoPregunta) {
        Swal.fire({
            title: '¿Eliminar pregunta?',
            html: `La pregunta <strong class="text-primary">${escapeHtml(textoPregunta.substring(0, 100))}${textoPregunta.length > 100 ? '...' : ''}</strong> será eliminada permanentemente.<br><br>Esta acción no se puede deshacer.`,
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
        const area = document.getElementById('areaFilter')?.value || '';
        
        const url = new URL(window.location.href);
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        
        if (area && area !== '') url.searchParams.set('id_area', area);
        else url.searchParams.delete('id_area');
        
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }

    document.getElementById('btnFiltrar')?.addEventListener('click', cargarTabla);
    document.getElementById('btnLimpiar')?.addEventListener('click', () => window.location.href = window.location.pathname);
    document.getElementById('searchInput')?.addEventListener('keypress', e => e.key === 'Enter' && cargarTabla());
    document.getElementById('areaFilter')?.addEventListener('change', cargarTabla);
</script>
@endpush