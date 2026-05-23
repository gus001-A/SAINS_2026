@extends('administrador.layouts.master')

@section('title', 'Gestión de Administradores - SAINS')

@section('content')
<div class="container-fluid px-4">
    <!-- Header mejorado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-3 p-2" style="background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);">
                            <i class="fas fa-user-shield fa-2x" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
                        </div>
                        <h1 class="display-5 fw-bold mb-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                            Gestión de Administradores
                        </h1>
                    </div>
                    <p class="text-muted fs-5 mb-0">Administre los usuarios con acceso privilegiado al sistema</p>
                </div>
                <div>
                    <a href="{{ route('admin.administradores.create') }}" class="btn btn-primary-custom px-4 py-2 shadow-sm">
                        <i class="fas fa-user-plus me-2"></i>Nuevo Administrador
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
                        <i class="fas fa-sliders-h text-primary"></i>
                        <h5 class="fw-semibold mb-0">Filtros de búsqueda</h5>
                    </div>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6 col-lg-7">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-search me-1"></i>Buscar administrador
                            </label>
                            <input type="text" id="searchInput" class="form-control form-control-lg rounded-3" 
                                   placeholder="Nombre, apellido, email o teléfono..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-venus-mars me-1"></i>Sexo
                            </label>
                            <select id="sexoFilter" class="form-select form-select-lg rounded-3">
                                <option value="">Todos</option>
                                <option value="M" {{ request('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ request('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-lg-2">
                            <div class="d-flex gap-2">
                                <button id="btnFiltrar" class="btn btn-primary-custom w-100 py-2 rounded-3 shadow-sm">
                                    <i class="fas fa-filter me-2"></i>Filtrar
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

    <!-- Tabla de administradores -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem; width: 70px;">ID</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Administrador</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Correo Electrónico</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Teléfono</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Sexo</th>
                                <th class="py-3 px-4 text-white fw-semibold" style="font-size: 0.85rem;">Fecha de Registro</th>
                                <th class="py-3 px-4 text-white fw-semibold text-center" style="font-size: 0.85rem; width: 180px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaBody">
                            @include('administrador.administradores.partials.admin_rows', ['admins' => $admins])
                        </tbody>
                    </table>
                </div>
                
                <!-- Footer con paginación -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-4 bg-light border-top gap-3">
                    <div class="text-muted small">
                        <i class="fas fa-chart-line me-1"></i>
                        Mostrando <span class="fw-semibold" id="desde">{{ $admins->firstItem() ?? 0 }}</span> - 
                        <span class="fw-semibold" id="hasta">{{ $admins->lastItem() ?? 0 }}</span> 
                        de <span class="fw-semibold" id="total">{{ $admins->total() }}</span> registros
                    </div>
                    <div id="paginationLinks" class="d-flex justify-content-end">
                        {{ $admins->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Animaciones y estilos globales */
    .table {
        min-width: 900px;
    }
    
    .table td {
        padding: 1rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }
    
    .table tbody tr {
        transition: all 0.2s ease-in-out;
    }
    
    .table tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.04);
        transform: translateX(2px);
    }
    
    /* Avatar de usuario */
    .avatar-circle {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1rem;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        flex-shrink: 0;
    }

    /* Badge de rol */
    .badge-rol {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
        letter-spacing: 0.3px;
    }
    
    /* Botones de acción modernos */
    .btn-accion {
        padding: 0.4rem 1rem;
        margin: 0 3px;
        font-size: 0.75rem;
        border-radius: 25px;
        transition: all 0.2s ease;
        font-weight: 500;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
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
    
    /* Paginación mejorada */
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
    
    .page-item.disabled .page-link {
        color: #adb5bd;
        background-color: #f8f9fa;
        cursor: not-allowed;
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
    
    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    /* Tooltip personalizado */
    [data-tooltip] {
        position: relative;
        cursor: pointer;
    }
    
    [data-tooltip]:before {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: white;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.7rem;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s;
        z-index: 10;
    }
    
    [data-tooltip]:hover:before {
        opacity: 1;
    }
    
    /* Dark Mode */
    body.dark-mode .card {
        background-color: #1a1a2e;
        border-color: rgba(255, 255, 255, 0.05);
    }
    
    body.dark-mode .table td {
        border-bottom-color: rgba(255, 255, 255, 0.05);
        color: #e0e0e0;
    }
    
    body.dark-mode .table tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.08);
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
    
    body.dark-mode .btn-outline-secondary {
        border-color: rgba(102, 126, 234, 0.5);
        color: #e0e0e0;
    }
    
    body.dark-mode .btn-outline-secondary:hover {
        background-color: rgba(102, 126, 234, 0.2);
        border-color: #667eea;
        color: white;
    }
    
    body.dark-mode .btn-editar {
        background: rgba(46, 125, 50, 0.15);
        color: #81c784;
    }
    
    body.dark-mode .btn-editar:hover {
        background: #2e7d32;
        color: white;
    }
    
    body.dark-mode .btn-eliminar {
        background: rgba(198, 40, 40, 0.15);
        color: #ef9a9a;
    }
    
    body.dark-mode .btn-eliminar:hover {
        background: #c62828;
        color: white;
    }
    
    body.dark-mode .page-link {
        background-color: #0f0f1a;
        color: #667eea;
    }
    
    body.dark-mode .page-item.disabled .page-link {
        background-color: #0f0f1a;
        color: #6c757d;
    }
    
    body.dark-mode .avatar-circle {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Función para cargar tabla con filtros
    function cargarTablaConFiltros() {
        const search = document.getElementById('searchInput').value;
        const sexo = document.getElementById('sexoFilter').value;
        
        const url = new URL(window.location.href);
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        
        if (sexo) url.searchParams.set('sexo', sexo);
        else url.searchParams.delete('sexo');
        
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }

    // Función para limpiar filtros
    function limpiarFiltros() {
        window.location.href = window.location.pathname;
    }

    // Event Listeners
    document.getElementById('btnFiltrar')?.addEventListener('click', cargarTablaConFiltros);
    document.getElementById('btnLimpiar')?.addEventListener('click', limpiarFiltros);
    
    // Búsqueda al presionar Enter
    document.getElementById('searchInput')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') cargarTablaConFiltros();
    });

    // Confirmación de eliminación con SweetAlert2
    window.confirmarEliminar = function(url, nombre, email) {
        Swal.fire({
            title: '¿Eliminar administrador?',
            html: `Estás a punto de eliminar a <strong>${nombre}</strong><br><small>(${email})</small><br><br>Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="fas fa-trash-alt me-2"></i>Sí, eliminar',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar',
            background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#333'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                form.style.display = 'none';
                
                const csrfInput = document.createElement('input');
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                
                const methodInput = document.createElement('input');
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    };

    // Mostrar notificaciones con SweetAlert2
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false,
                background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#333'
            });
        @endif
        
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#333'
            });
        @endif
    });
</script>
@endpush