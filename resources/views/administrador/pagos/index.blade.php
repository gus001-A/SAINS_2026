@extends('administrador.layouts.master')

@section('title', 'Gestión de Pagos - SAINS')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Moderno -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="display-5 fw-bold mb-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                        <i class="fas fa-credit-card me-2"></i>Gestión de Pagos
                    </h1>
                    <p class="text-muted fs-5">Administre y supervise los pagos realizados por los estudiantes</p>
                </div>
                <div>
                    <a href="{{ route('admin.pagos.create') }}" class="btn btn-primary-custom px-4 py-2 rounded-3">
                        <i class="fas fa-plus-circle me-2"></i>Nuevo Pago
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="row mb-4 g-4">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold text-uppercase">Total Recaudado</p>
                            <h2 class="mb-0 fw-bold display-6" style="color: #27ae60;">${{ number_format($totalPagos ?? 0, 2) }}</h2>
                            <small class="text-muted">Monto total aprobado</small>
                        </div>
                        <div class="rounded-circle p-3" style="background: rgba(39, 174, 96, 0.1);">
                            <i class="fas fa-dollar-sign fa-2x" style="color: #27ae60;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold text-uppercase">Pendientes</p>
                            <h2 class="mb-0 fw-bold display-6" style="color: #f39c12;">{{ $pagosPendientes ?? 0 }}</h2>
                            <small class="text-muted">Por revisar</small>
                        </div>
                        <div class="rounded-circle p-3" style="background: rgba(243, 156, 18, 0.1);">
                            <i class="fas fa-clock fa-2x" style="color: #f39c12;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold text-uppercase">Aprobados</p>
                            <h2 class="mb-0 fw-bold display-6" style="color: #667eea;">{{ $pagosAprobados ?? 0 }}</h2>
                            <small class="text-muted">Pagos confirmados</small>
                        </div>
                        <div class="rounded-circle p-3" style="background: rgba(102, 126, 234, 0.1);">
                            <i class="fas fa-check-circle fa-2x" style="color: #667eea;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold text-uppercase">Rechazados</p>
                            <h2 class="mb-0 fw-bold display-6" style="color: #dc3545;">{{ $pagosRechazados ?? 0 }}</h2>
                            <small class="text-muted">No aprobados</small>
                        </div>
                        <div class="rounded-circle p-3" style="background: rgba(220, 53, 69, 0.1);">
                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-search me-1"></i>Buscar
                            </label>
                            <input type="text" id="searchInput" class="form-control form-control-lg rounded-3" 
                                   placeholder="Nombre del alumno o referencia..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-tag me-1"></i>Tipo de Pago
                            </label>
                            <select id="tipoFilter" class="form-select form-select-lg rounded-3">
                                <option value="">Todos los tipos</option>
                                <option value="Bancario" {{ request('tipo_pago') == 'Bancario' ? 'selected' : '' }}>Bancario</option>
                                <option value="Oxxo" {{ request('tipo_pago') == 'Oxxo' ? 'selected' : '' }}>Oxxo</option>
                                <option value="Transferencia" {{ request('tipo_pago') == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted mb-2">
                                <i class="fas fa-check-circle me-1"></i>Estado
                            </label>
                            <select id="estatusFilter" class="form-select form-select-lg rounded-3">
                                <option value="">Todos los estados</option>
                                <option value="pendiente" {{ request('estatus') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="aprobado" {{ request('estatus') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                                <option value="rechazado" {{ request('estatus') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                <option value="cancelado" {{ request('estatus') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="d-grid gap-2">
                                <button id="btnFiltrar" class="btn btn-primary-custom py-2 rounded-3">
                                    <i class="fas fa-filter me-2"></i>Filtrar
                                </button>
                                <button id="btnLimpiar" class="btn btn-outline-secondary py-2 rounded-3">
                                    <i class="fas fa-eraser me-2"></i>Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de pagos -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="py-3 px-4 text-white fw-semibold">ID</th>
                                <th class="py-3 px-4 text-white fw-semibold">Tipo</th>
                                <th class="py-3 px-4 text-white fw-semibold">Estudiante</th>
                                <th class="py-3 px-4 text-white fw-semibold">Monto</th>
                                <th class="py-3 px-4 text-white fw-semibold">Fecha</th>
                                <th class="py-3 px-4 text-white fw-semibold">Referencia</th>
                                <th class="py-3 px-4 text-white fw-semibold">Estado</th>
                                <th class="py-3 px-4 text-white fw-semibold">Revisión</th>
                                <th class="py-3 px-4 text-white fw-semibold text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaBody">
                            @include('administrador.pagos.partials.table_rows', ['pagos' => $pagos])
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center p-4 bg-light border-top flex-wrap gap-3">
                    <div class="text-muted small">
                        <i class="fas fa-chart-line me-1"></i>
                        Mostrando <strong>{{ $pagos->firstItem() ?? 0 }}</strong> - 
                        <strong>{{ $pagos->lastItem() ?? 0 }}</strong> 
                        de <strong>{{ $pagos->total() ?? 0 }}</strong> registros
                    </div>
                    <div>
                        {{ $pagos->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .container-fluid {
        max-width: 1600px;
        margin: 0 auto;
    }
    
    .table {
        min-width: 1000px;
    }
    
    .table td {
        padding: 1rem 1rem;
        vertical-align: middle;
        font-size: 0.9rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .table tbody tr {
        transition: all 0.2s ease-in-out;
    }
    
    .table tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.04);
        transform: translateX(2px);
    }
    
    .table thead th {
        color: white !important;
        font-weight: 600 !important;
        letter-spacing: 0.5px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    }
    
    /* Estilos para badges de tipo */
    .badge-tipo-contacto {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    
    /* Estilos para badges de estado */
    .badge-estado {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .badge-pendiente { background: #fff3e0; color: #e65100; }
    .badge-aprobado { background: #e8f5e9; color: #2e7d32; }
    .badge-rechazado { background: #ffebee; color: #c62828; }
    .badge-cancelado { background: #f5f5f5; color: #757575; }
    
    /* Estilos para botones de acción */
    .btn-accion {
        padding: 0.35rem 0.9rem;
        margin: 0 3px;
        font-size: 0.75rem;
        border-radius: 20px;
        transition: all 0.2s ease;
        font-weight: 500;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
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
    
    /* Estilos para información de usuarios */
    .usuario-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .avatar-mini {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.8rem;
    }
    
    .usuario-detalles {
        display: flex;
        flex-direction: column;
    }
    
    .usuario-nombre {
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    .usuario-email {
        font-size: 0.7rem;
        color: #6c757d;
    }
    
    .fecha-info {
        display: flex;
        flex-direction: column;
    }
    
    .fecha-fecha {
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    .fecha-hora {
        font-size: 0.7rem;
        color: #6c757d;
    }
    
    /* Hover lift effect */
    .hover-lift {
        transition: all 0.2s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    /* Formularios */
    .form-control-lg, .form-select-lg {
        font-size: 1rem;
        border: 2px solid #e9ecef;
        transition: all 0.2s ease;
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
    
    .btn-outline-secondary {
        transition: all 0.2s ease;
    }
    
    .btn-outline-secondary:hover {
        transform: translateY(-2px);
    }
    
    /* Paginación */
    .pagination {
        margin-bottom: 0;
    }
    
    .page-item .page-link {
        border-radius: 8px !important;
        margin: 0 3px;
        color: #667eea;
        border: none;
        padding: 0.5rem 0.85rem;
        font-size: 0.85rem;
    }
    
    .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    /* Modo oscuro */
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
    
    body.dark-mode .btn-outline-secondary {
        border-color: rgba(102, 126, 234, 0.5);
        color: #e0e0e0;
    }
    
    body.dark-mode .page-link {
        background-color: #1a1a2e;
        color: #667eea;
    }
    
    body.dark-mode .usuario-email,
    body.dark-mode .fecha-hora {
        color: #a0a0a0;
    }
    
    body.dark-mode .text-muted {
        color: #a0a0a0 !important;
    }
    
    body.dark-mode code {
        background-color: #2a2a3e !important;
        color: #a0a0a0;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funciones con SweetAlert
        window.verPago = function(id) {
            window.location.href = '{{ route("admin.pagos.show", "") }}/' + id;
        };
        
        window.editarPago = function(id) {
            window.location.href = '{{ route("admin.pagos.edit", "") }}/' + id;
        };
        
        window.eliminarPago = function(id, nombre) {
            Swal.fire({
                title: '¿Eliminar pago?',
                html: `Estás a punto de eliminar el pago de <strong>${nombre}</strong><br>Esta acción no se puede deshacer`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#667eea',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#333',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp animate__faster'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("admin.pagos.destroy", "") }}/' + id;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
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
        };
        
        // Filtrar
        function cargarTabla() {
            const search = document.getElementById('searchInput')?.value || '';
            const tipo = document.getElementById('tipoFilter')?.value || '';
            const estatus = document.getElementById('estatusFilter')?.value || '';
            
            const url = new URL(window.location.href);
            if (search) url.searchParams.set('search', search);
            else url.searchParams.delete('search');
            
            if (tipo) url.searchParams.set('tipo_pago', tipo);
            else url.searchParams.delete('tipo_pago');
            
            if (estatus) url.searchParams.set('estatus', estatus);
            else url.searchParams.delete('estatus');
            
            window.location.href = url.toString();
        }
        
        document.getElementById('btnFiltrar')?.addEventListener('click', cargarTabla);
        document.getElementById('btnLimpiar')?.addEventListener('click', function() {
            window.location.href = window.location.pathname;
        });
        
        document.getElementById('searchInput')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') cargarTabla();
        });
        
        // Mensajes flash
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#667eea',
                background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#333',
                timer: 3000,
                timerProgressBar: true
            });
        @endif
        
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33',
                background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#333'
            });
        @endif
    });
</script>
@endpush