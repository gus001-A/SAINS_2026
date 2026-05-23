@extends('administrador.layouts.master')

@section('title', 'Gestión de Videos - SAINS')

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
                            <i class="fas fa-video fa-2x"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
                        </div>
                        <h1 class="display-5 fw-bold mb-0"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                            Gestión de Videos
                        </h1>
                    </div>
                    <p class="text-muted fs-5 mb-0">Administre los videos educativos del sistema</p>
                </div>
                <div>
                    <a href="{{ route('admin.videos.create') }}" class="btn btn-primary-custom px-4 py-2 shadow-sm">
                        <i class="fas fa-plus-circle me-2"></i>Nuevo Video
                    </a>
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
                        <i class="fas fa-sliders-h" style="color: #667eea;"></i>
                        <h5 class="fw-semibold mb-0">Filtros de búsqueda</h5>
                    </div>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8 col-lg-9">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-search me-1"></i>Buscar
                            </label>
                            <input type="text" id="searchInput" class="form-control form-control-lg rounded-3"
                                placeholder="Título, materia o tema..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-gratipay me-1"></i>Plan
                            </label>
                            <select id="planFilter" class="form-select form-select-lg rounded-3">
                                <option value="">Todos</option>
                                <option value="1" {{ request('plan') === '1' ? 'selected' : '' }}>Gratuito</option>
                                <option value="0" {{ request('plan') === '0' ? 'selected' : '' }}>Premium</option>
                            </select>
                        </div>
                        <div class="col-md-12 col-lg-12">
                            <div class="d-flex gap-2">
                                <button id="btnFiltrar" class="btn btn-primary-custom px-4 py-2 rounded-3 shadow-sm">
                                    <i class="fas fa-filter me-2"></i>Filtrar
                                </button>
                                <button id="btnLimpiar" class="btn btn-outline-secondary px-4 py-2 rounded-3">
                                    <i class="fas fa-times me-2"></i>Limpiar
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
            <div class="card border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Total Videos</p>
                            <h4 class="fw-bold mb-0" style="color: #667eea;">{{ $totalVideos }}</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(102,126,234,0.1);">
                            <i class="fas fa-video" style="color: #667eea; font-size: 1.2rem;"></i>
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
                            <p class="text-muted mb-0 small">Con Progresos</p>
                            <h4 class="fw-bold mb-0 text-success">{{ $conProgresos }}</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(40,167,69,0.1);">
                            <i class="fas fa-chart-line text-success fa-lg"></i>
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
                            <p class="text-muted mb-0 small">Plan Gratuito</p>
                            <h4 class="fw-bold mb-0" style="color: #f59e0b;">{{ $planGratuito }}</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(245,158,11,0.1);">
                            <i class="fas fa-gratipay" style="color: #f59e0b; font-size: 1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de videos -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">ID</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Información</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Materia</th>
                                <th class="py-3 px-4 text-white fw-semibold text-center" style="font-size: 0.85rem;">Duración</th>
                                <th class="py-3 px-4 text-white fw-semibold text-center" style="font-size: 0.85rem;">Plan</th>
                                <th class="py-3 px-4 text-white fw-semibold text-center" style="font-size: 0.85rem; width: 160px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaBody">
                            @include('administrador.videos.partials.table_rows', ['videos' => $videos])
                        </tbody>
                    </table>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-4 bg-light border-top gap-3">
                    <div class="text-muted small">
                        <i class="fas fa-chart-line me-1"></i>
                        Mostrando <span class="fw-semibold" id="desde">{{ $videos->firstItem() ?? 0 }}</span> -
                        <span class="fw-semibold" id="hasta">{{ $videos->lastItem() ?? 0 }}</span>
                        de <span class="fw-semibold" id="total">{{ $videos->total() }}</span> registros
                    </div>
                    <div id="paginationLinks" class="d-flex justify-content-end">
                        {{ $videos->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.hover-card {
    transition: all 0.2s ease-in-out;
}

.hover-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
}

/* Badges */
.badge-gratuito {
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

.badge-premium {
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

.badge-progresos {
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

.badge-sin-recurso {
    background: #f3f4f6;
    color: #6b7280;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 500;
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

/* Información del video */
.video-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.video-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));
    color: #667eea;
    flex-shrink: 0;
}

.video-detalles {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.video-titulo {
    font-weight: 600;
    font-size: 0.9rem;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.video-tema {
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
    box-shadow: 0 4px 15px rgba(102,126,234,0.4);
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
    box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
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

.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(102,126,234,0.4);
}

.fade-in {
    animation: fadeIn 0.5s ease-in;
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

/* Dark Mode */
body.dark-mode .card {
    background-color: #1a1a2e;
}

body.dark-mode .bg-light {
    background-color: #0f0f1a !important;
}

body.dark-mode .badge-gratuito {
    background: rgba(46, 125, 50, 0.2);
    color: #81c784;
}

body.dark-mode .badge-premium {
    background: rgba(230, 81, 0, 0.2);
    color: #ffa726;
}

body.dark-mode .badge-progresos {
    background: rgba(21, 101, 192, 0.2);
    color: #64b5f6;
}

body.dark-mode .badge-sin-recurso {
    background: rgba(107, 114, 128, 0.2);
    color: #9ca3af;
}

body.dark-mode .video-titulo {
    color: #e0e0e0;
}

body.dark-mode .video-tema {
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

.badge.bg-light {
    background-color: #f8f9fa !important;
    padding: 5px 10px;
    font-weight: 500;
}

body.dark-mode .badge.bg-light {
    background-color: #2d3748 !important;
    color: #e2e8f0 !important;
}

body.dark-mode .badge.bg-light i {
    color: #a78bfa !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function cargarTabla() {
    const search = document.getElementById('searchInput')?.value || '';
    const plan = document.getElementById('planFilter')?.value || '';

    const url = new URL(window.location.href);
    if (search) url.searchParams.set('search', search);
    else url.searchParams.delete('search');

    if (plan !== '') url.searchParams.set('plan', plan);
    else url.searchParams.delete('plan');

    url.searchParams.set('page', 1);
    window.location.href = url.toString();
}

function confirmarEliminar(url, titulo) {
    Swal.fire({
        title: '¿Eliminar video?',
        html: `El video <strong class="text-danger">${escapeHtml(titulo)}</strong> será eliminado permanentemente.<br><br>Esta acción no se puede deshacer.`,
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

document.getElementById('btnFiltrar')?.addEventListener('click', cargarTabla);
document.getElementById('btnLimpiar')?.addEventListener('click', () => window.location.href = window.location.pathname);
document.getElementById('searchInput')?.addEventListener('keypress', e => e.key === 'Enter' && cargarTabla());
document.getElementById('planFilter')?.addEventListener('change', cargarTabla);
</script>
@endpush