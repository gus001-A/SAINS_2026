@extends('administrador.layouts.master')

@section('title', 'Generación Masiva de Cupones - SAINS')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="display-5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                    <i class="fas fa-layer-group me-3"></i>Generación Masiva
                </h1>
                <p class="text-muted">Cree múltiples cupones con la misma configuración en un solo paso</p>
            </div>
            <a href="{{ route('admin.cupones.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>Volver al listado
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-modern p-4 p-lg-5">
            <form action="{{ route('admin.cupones.masivo') }}" method="POST" id="formGenerarMasivo">
                @csrf
                
                <!-- Header del formulario -->
                <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                    <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(23,162,184,0.1), rgba(19,132,150,0.1));">
                        <i class="fas fa-layer-group fa-2x" style="color: #17a2b8;"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-semibold">Configuración de Cupones</h4>
                        <p class="text-muted small mb-0">Todos los cupones generados tendrán la misma configuración</p>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Cantidad de Cupones -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-hashtag text-primary me-1"></i> Cantidad de Cupones <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-transparent"><i class="fas fa-ticket-alt text-primary"></i></span>
                            <input type="number" name="cantidad" id="cantidad_cupones" class="form-control" 
                                   value="10" min="1" max="100" required>
                        </div>
                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Máximo 100 cupones por lote</small>
                    </div>

                    <!-- Estatus -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-circle-info text-primary me-1"></i> Estatus <span class="text-danger">*</span>
                        </label>
                        <select name="estatus" class="form-select form-select-lg" required>
                            <option value="activo" selected>Activo</option>
                            <option value="inactivo">Inactivo</option>
                            <option value="expirado">Expirado</option>
                        </select>
                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Estado inicial de los cupones</small>
                    </div>

                    <!-- Tipo de Descuento -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-percent text-primary me-1"></i> Tipo de Descuento <span class="text-danger">*</span>
                        </label>
                        <select name="tipo_descuento" id="tipo_descuento_masivo" class="form-select form-select-lg" required>
                            <option value="porcentaje">Porcentaje (%)</option>
                            <option value="cantidad_fija">Cantidad fija ($)</option>
                        </select>
                    </div>

                    <!-- Valor del Descuento -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-dollar-sign text-primary me-1"></i> Valor del Descuento <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-transparent" id="simbolo_masivo">%</span>
                            <input type="number" name="valor_descuento" id="valor_descuento_masivo" 
                                   class="form-control" value="10" step="1" min="1" required>
                        </div>
                        <small class="text-muted" id="ayuda_masivo"><i class="fas fa-info-circle me-1"></i>Ingrese el porcentaje de descuento (máximo 100%)</small>
                    </div>

                    <!-- Fecha de Expiración -->
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-calendar-alt text-primary me-1"></i> Fecha de Expiración
                        </label>
                        <input type="date" name="fecha_expiracion" id="fecha_expiracion_masivo" 
                               class="form-control form-control-lg" min="{{ date('Y-m-d') }}">
                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Dejar en blanco si no expira</small>
                    </div>
                </div>

                <!-- Tarjeta de resumen -->
                <div class="mt-4 p-4 bg-light rounded-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="fas fa-chart-line text-primary fa-lg"></i>
                        <h5 class="fw-bold mb-0">Resumen de generación</h5>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-6 mb-2">
                            <small class="text-muted d-block">Cupones a generar</small>
                            <span class="fs-4 fw-bold text-primary" id="preview_cantidad">10</span>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <small class="text-muted d-block">Tipo de descuento</small>
                            <span class="fw-semibold" id="preview_tipo">Porcentaje (%)</span>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <small class="text-muted d-block">Valor del descuento</small>
                            <span class="fw-semibold" id="preview_valor">10%</span>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <small class="text-muted d-block">Códigos únicos</small>
                            <span class="fw-semibold text-success"><i class="fas fa-check-circle"></i> Automáticos</span>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top">
                    <a href="{{ route('admin.cupones.index') }}" class="btn btn-cancel px-4 py-3 rounded-pill">
                        <i class="fas fa-times me-2"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-save px-5 py-3 rounded-pill" id="btnGenerarMasivo">
                        <i class="fas fa-layer-group me-2"></i> Generar Cupones
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Reutilizamos los estilos del create */
    .form-label {
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    
    .form-control-lg, .form-select-lg, .input-group-lg .form-control {
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    
    .form-control-lg:focus, .form-select-lg:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
    }
    
    .input-group-text {
        border-radius: 12px 0 0 12px;
        border: 2px solid #e0e0e0;
        border-right: none;
    }
    
    .input-group .form-control {
        border-left: none;
    }
    
    .card-modern {
        background: white;
        border-radius: 20px;
        transition: transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    
    .card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(67,97,238,0.15);
    }
    
    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102,126,234,0.4);
        color: white;
    }
    
    .btn-cancel {
        background: #6c757d;
        border: none;
        color: white;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-cancel:hover {
        background: #dc3545;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(220,53,69,0.3);
        color: white;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    /* Dark mode */
    body.dark-mode .card-modern {
        background: #1e293b;
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
    
    body.dark-mode .input-group-text {
        background-color: #0f172a;
        border-color: #334155;
        color: #94a3b8;
    }
    
    body.dark-mode .btn-cancel {
        background: #475569;
    }
    
    body.dark-mode .btn-cancel:hover {
        background: #dc2626;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elementos del formulario
        const cantidadInput = document.getElementById('cantidad_cupones');
        const tipoSelect = document.getElementById('tipo_descuento_masivo');
        const valorInput = document.getElementById('valor_descuento_masivo');
        const simbolo = document.getElementById('simbolo_masivo');
        const ayuda = document.getElementById('ayuda_masivo');
        const fechaInput = document.getElementById('fecha_expiracion_masivo');
        
        // Elementos de preview
        const previewCantidad = document.getElementById('preview_cantidad');
        const previewTipo = document.getElementById('preview_tipo');
        const previewValor = document.getElementById('preview_valor');
        
        // Actualizar preview en tiempo real
        function actualizarPreview() {
            const cantidad = cantidadInput.value;
            const tipo = tipoSelect.value;
            const valor = valorInput.value;
            
            if (previewCantidad) previewCantidad.textContent = cantidad;
            if (previewTipo) previewTipo.textContent = tipo === 'porcentaje' ? 'Porcentaje (%)' : 'Cantidad fija ($)';
            
            if (previewValor) {
                if (tipo === 'porcentaje') {
                    previewValor.textContent = valor + '%';
                } else {
                    previewValor.textContent = '$' + parseFloat(valor).toFixed(2);
                }
            }
        }
        
        // Cambiar símbolo y validaciones según tipo de descuento
        function actualizarTipoDescuento() {
            const tipo = tipoSelect.value;
            if (tipo === 'porcentaje') {
                simbolo.innerHTML = '%';
                ayuda.innerHTML = '<i class="fas fa-info-circle me-1"></i>Ingrese el porcentaje de descuento (máximo 100%)';
                valorInput.placeholder = 'Ejemplo: 20';
                valorInput.max = 100;
                valorInput.step = 1;
                if (valorInput.value > 100) valorInput.value = 100;
            } else {
                simbolo.innerHTML = '$';
                ayuda.innerHTML = '<i class="fas fa-info-circle me-1"></i>Ingrese el monto fijo del descuento';
                valorInput.placeholder = 'Ejemplo: 50.00';
                valorInput.max = null;
                valorInput.step = 0.01;
            }
            actualizarPreview();
        }
        
        // Eventos
        if (cantidadInput) {
            cantidadInput.addEventListener('input', actualizarPreview);
        }
        if (tipoSelect) {
            tipoSelect.addEventListener('change', actualizarTipoDescuento);
        }
        if (valorInput) {
            valorInput.addEventListener('input', actualizarPreview);
        }
        
        // Fecha mínima = hoy
        if (fechaInput) {
            const hoy = new Date().toISOString().split('T')[0];
            fechaInput.min = hoy;
        }
        
        // Inicializar
        actualizarTipoDescuento();
        
        // Validación y envío del formulario
        const form = document.getElementById('formGenerarMasivo');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const cantidad = parseInt(cantidadInput.value);
                const tipo = tipoSelect.value;
                const valor = parseFloat(valorInput.value);
                const fecha = fechaInput.value;
                
                // Validaciones
                if (isNaN(cantidad) || cantidad < 1 || cantidad > 100) {
                    Swal.fire('Error', 'La cantidad debe ser entre 1 y 100 cupones', 'error');
                    return false;
                }
                
                if (isNaN(valor) || valor <= 0) {
                    Swal.fire('Error', 'Ingrese un valor de descuento válido', 'error');
                    return false;
                }
                
                if (tipo === 'porcentaje' && valor > 100) {
                    Swal.fire('Error', 'El porcentaje no puede ser mayor a 100%', 'error');
                    return false;
                }
                
                if (fecha) {
                    const fechaSel = new Date(fecha);
                    const hoy = new Date();
                    hoy.setHours(0, 0, 0, 0);
                    if (fechaSel <= hoy) {
                        Swal.fire('Error', 'La fecha de expiración debe ser mayor a la fecha actual', 'error');
                        return false;
                    }
                }
                
                const fechaTexto = fecha ? new Date(fecha).toLocaleDateString('es-MX') : 'Sin fecha de expiración';
                const valorTexto = tipo === 'porcentaje' ? valor + '%' : '$' + valor.toFixed(2);
                
                Swal.fire({
                    title: '¿Confirmar generación masiva?',
                    html: `<p>Se generarán <strong class="text-primary">${cantidad} cupones</strong> con las siguientes características:</p>
                           <div class="text-start bg-light p-3 rounded-3" style="background: ${document.body.classList.contains('dark-mode') ? '#0f172a' : '#f8f9fa'};">
                               <div class="mb-2"><strong>Tipo:</strong> ${tipo === 'porcentaje' ? 'Porcentaje (%)' : 'Cantidad fija ($)'}</div>
                               <div class="mb-2"><strong>Valor:</strong> ${valorTexto}</div>
                               <div class="mb-2"><strong>Estatus:</strong> Activo</div>
                               <div><strong>Fecha expiración:</strong> ${fechaTexto}</div>
                           </div>
                           <p class="mt-3 text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Esta acción no se puede deshacer</p>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-check me-2"></i>Sí, generar',
                    cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar',
                    background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
                    color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#1e293b'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const btn = document.getElementById('btnGenerarMasivo');
                        const originalText = btn.innerHTML;
                        btn.disabled = true;
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Generando cupones...';
                        
                        const formData = new FormData(form);
                        
                        fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: '¡Éxito!',
                                    html: `<i class="fas fa-check-circle fa-3x text-success mb-3 d-block"></i>
                                           <p>Se generaron <strong class="text-success">${data.generados}</strong> cupones correctamente</p>
                                           <p class="text-muted small mt-2">Redirigiendo al listado...</p>`,
                                    icon: 'success',
                                    timer: 3000,
                                    showConfirmButton: false,
                                    background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff'
                                });
                                setTimeout(() => {
                                    window.location.href = '{{ route("admin.cupones.index") }}';
                                }, 3000);
                            } else {
                                Swal.fire('Error', data.message || 'Error al generar los cupones', 'error');
                                btn.disabled = false;
                                btn.innerHTML = originalText;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Ocurrió un error al generar los cupones', 'error');
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        });
                    }
                });
            });
        }
    });
</script>
@endpush