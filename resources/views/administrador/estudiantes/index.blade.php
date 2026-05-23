@extends('administrador.layouts.master')

@section('title', 'Gestión de Estudiantes - SAINS')

@section('content')
<div class="container-fluid px-4">
    <!-- Header con estadísticas integradas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-3 p-2" style="background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);">
                            <i class="fas fa-users fa-2x" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
                        </div>
                        <h1 class="display-5 fw-bold mb-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                            Estudiantes
                        </h1>
                    </div>
                    <p class="text-muted fs-5 mb-0">Gestión completa de estudiantes registrados en la plataforma</p>
                </div>
                <div>
                    <a href="{{ route('admin.estudiantes.create') }}" class="btn btn-primary-custom px-4 py-2 shadow-sm">
                        <i class="fas fa-user-plus me-2"></i>Nuevo Estudiante
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de estadísticas mejoradas -->
    <div class="row mb-4 g-3">
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Total Estudiantes</p>
                            <h4 class="fw-bold mb-0 text-primary">{{ $totalEstudiantes ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(102,126,234,0.1);">
                            <i class="fas fa-users text-primary fa-lg"></i>
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
                            <p class="text-muted mb-0 small">Plan Activo</p>
                            <h4 class="fw-bold mb-0 text-success">{{ $activos ?? 0 }}</h4>
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
                            <p class="text-muted mb-0 small">Plan Inactivo</p>
                            <h4 class="fw-bold mb-0 text-danger">{{ $inactivos ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(220,53,69,0.1);">
                            <i class="fas fa-times-circle text-danger fa-lg"></i>
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
                            <p class="text-muted mb-0 small">Con Cupón</p>
                            <h4 class="fw-bold mb-0 text-warning">{{ $conCupon ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(255,193,7,0.1);">
                            <i class="fas fa-ticket-alt text-warning fa-lg"></i>
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
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-sliders-h text-primary"></i>
                        <h5 class="fw-semibold mb-0">Filtros de búsqueda</h5>
                    </div>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-search me-1"></i>Búsqueda rápida
                            </label>
                            <input type="text" id="searchInput" class="form-control form-control-lg rounded-3" 
                                   placeholder="Nombre, correo, teléfono..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-venus-mars me-1"></i>Sexo
                            </label>
                            <select id="sexoFilter" class="form-select form-select-lg rounded-3">
                                <option value="">Todos</option>
                                <option value="M" {{ request('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ request('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-check-circle me-1"></i>Plan
                            </label>
                            <select id="planFilter" class="form-select form-select-lg rounded-3">
                                <option value="">Todos</option>
                                <option value="1" {{ request('plan_activo') === '1' ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ request('plan_activo') === '0' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-school me-1"></i>Estado (Prepa)
                            </label>
                            <select id="estadoPrepaFilter" class="form-select form-select-lg rounded-3">
                                <option value="">Todos los estados</option>
                                @foreach($estadosPrepa ?? [] as $estado)
                                    <option value="{{ $estado }}" {{ request('estado_prepa') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-ticket-alt me-1"></i>Cupón
                            </label>
                            <select id="cuponFilter" class="form-select form-select-lg rounded-3">
                                <option value="">Todos</option>
                                <option value="con" {{ request('cupon') == 'con' ? 'selected' : '' }}>Con cupón</option>
                                <option value="sin" {{ request('cupon') == 'sin' ? 'selected' : '' }}>Sin cupón</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex gap-2">
                                <button id="btnFiltrar" class="btn btn-primary-custom w-100 py-2 rounded-3 shadow-sm">
                                    <i class="fas fa-filter me-2"></i>Aplicar filtros
                                </button>
                                <button id="btnLimpiar" class="btn btn-outline-secondary w-100 py-2 rounded-3">
                                    <i class="fas fa-times me-2"></i>Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de estudiantes con diseño premium -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Estudiante</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Información de Contacto</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Sexo</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Fecha de nacimiento</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Preparatoria</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Plan</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Cupón</th>
                                <th class="py-3 px-4 text-white fw-semibold text-center" style="font-size: 0.85rem; width: 180px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaBody">
                            @include('administrador.estudiantes.partials.table_rows', ['estudiantes' => $estudiantes])
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-4 bg-light border-top gap-3">
                    <div class="text-muted small">
                        <i class="fas fa-chart-line me-1"></i>
                        Mostrando <span class="fw-semibold" id="desde">{{ $estudiantes->firstItem() ?? 0 }}</span> - 
                        <span class="fw-semibold" id="hasta">{{ $estudiantes->lastItem() ?? 0 }}</span> 
                        de <span class="fw-semibold" id="total">{{ $estudiantes->total() ?? 0 }}</span> registros
                    </div>
                    <div id="paginationLinks" class="d-flex justify-content-end">
                        {{ $estudiantes->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
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
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    /* Badges de sexo */
    .badge-sexo-m {
        background: linear-gradient(135deg, #2196f3, #1976d2);
        color: white;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .badge-sexo-f {
        background: linear-gradient(135deg, #ec407a, #c2185b);
        color: white;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    /* Badges de plan */
    .badge-plan-activo {
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
    
    .badge-plan-inactivo {
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
    
    /* Badge cupón */
    .badge-cupon {
        background: linear-gradient(135deg, #ff9800, #f57c00);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
        font-family: 'Courier New', monospace;
    }
    
    /* Avatar estudiante */
    .avatar-estudiante {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        flex-shrink: 0;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 4px 8px rgba(102,126,234,0.3);
    }
    
    .estudiante-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .estudiante-detalles {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    
    .estudiante-nombre {
        font-weight: 700;
        font-size: 0.9rem;
        color: #1e293b;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .estudiante-email {
        font-size: 0.7rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Botones de acción */
    .btn-accion {
        padding: 6px 14px;
        margin: 0 2px;
        font-size: 0.75rem;
        border-radius: 20px;
        transition: all 0.2s ease;
        font-weight: 500;
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
        border-radius: 12px;
    }
    
    .form-control-lg:focus, .form-select-lg:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    /* Tabla mejorada */
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
    
    .page-item .page-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-1px);
    }
    
    /* Dark Mode */
    body.dark-mode .badge-plan-activo {
        background: rgba(46, 125, 50, 0.2);
        color: #81c784;
    }
    
    body.dark-mode .badge-plan-inactivo {
        background: rgba(198, 40, 40, 0.2);
        color: #ef9a9a;
    }
    
    body.dark-mode .badge-cupon {
        background: linear-gradient(135deg, #ff9800, #f57c00);
    }
    
    body.dark-mode .estudiante-nombre {
        color: #e0e0e0;
    }
    
    body.dark-mode .estudiante-email {
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
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Definir rutas base desde Laravel
    const routes = {
        estudianteShow: "{{ route('admin.estudiantes.show', ['id' => ':id']) }}",
        estudianteEdit: "{{ route('admin.estudiantes.edit', ['id' => ':id']) }}",
        estudianteDestroy: "{{ route('admin.estudiantes.destroy', ['id' => ':id']) }}"
    };
    
    function replaceRouteId(route, id) {
        return route.replace(':id', id);
    }
    
    // Ver estudiante (redirige a la vista show)
    function verEstudiante(id) {
        window.location.href = replaceRouteId(routes.estudianteShow, id);
    }
    
    // Editar estudiante
    function editarEstudiante(id) {
        window.location.href = replaceRouteId(routes.estudianteEdit, id);
    }
    
    // Eliminar estudiante con confirmación
    function eliminarEstudiante(id, nombreCompleto) {
        Swal.fire({
            title: '¿Eliminar estudiante?',
            html: `El estudiante <strong class="text-primary">${escapeHtml(nombreCompleto)}</strong> será eliminado permanentemente.<br><br>Esta acción no se puede deshacer y eliminará todos sus datos asociados.`,
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
                form.action = replaceRouteId(routes.estudianteDestroy, id);
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
    
    // Cargar tabla con filtros
    function cargarTabla() {
        const search = document.getElementById('searchInput')?.value || '';
        const sexo = document.getElementById('sexoFilter')?.value || '';
        const plan_activo = document.getElementById('planFilter')?.value || '';
        const estado_prepa = document.getElementById('estadoPrepaFilter')?.value || '';
        const cupon = document.getElementById('cuponFilter')?.value || '';
        
        const url = new URL(window.location.href);
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        
        if (sexo) url.searchParams.set('sexo', sexo);
        else url.searchParams.delete('sexo');
        
        if (plan_activo) url.searchParams.set('plan_activo', plan_activo);
        else url.searchParams.delete('plan_activo');
        
        if (estado_prepa) url.searchParams.set('estado_prepa', estado_prepa);
        else url.searchParams.delete('estado_prepa');
        
        if (cupon) url.searchParams.set('cupon', cupon);
        else url.searchParams.delete('cupon');
        
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }
    
    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        const btnFiltrar = document.getElementById('btnFiltrar');
        const btnLimpiar = document.getElementById('btnLimpiar');
        const searchInput = document.getElementById('searchInput');
        
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
        
        // Auto-filtrar al cambiar selects
        document.querySelectorAll('#sexoFilter, #planFilter, #estadoPrepaFilter, #cuponFilter').forEach(select => {
            if (select) select.addEventListener('change', cargarTabla);
        });
    });
</script>
@endpush