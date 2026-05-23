@extends('estudiante.layouts.app')

@section('title', 'Checkout - SAINS')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Tarjeta de Checkout -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <h3 class="mb-0 fw-bold text-center">
                        <i class="fas fa-shopping-cart me-2"></i>Finalizar compra
                    </h3>
                    <p class="text-center text-white-50 mb-0">Curso Premium SAINS 2026</p>
                </div>
                
                <div class="card-body p-4 p-lg-5">
                    <!-- Información del estudiante -->
                    <div class="mb-4">
                        <h5 class="fw-bold">
                            <i class="fas fa-user-graduate text-primary me-2"></i>Información del estudiante
                        </h5>
                        <div class="bg-light p-3 rounded-3">
                            <p class="mb-1"><strong>Nombre:</strong> {{ $estudiante->nombre_completo }}</p>
                            <p class="mb-1"><strong>Correo:</strong> {{ auth()->user()->correo }}</p>
                            <p class="mb-0"><strong>Teléfono:</strong> {{ $estudiante->telefono ?? 'No especificado' }}</p>
                        </div>
                    </div>
                    
                    <!-- Sección de cupón -->
                    <div class="mb-4">
                        <h5 class="fw-bold">
                            <i class="fas fa-ticket-alt text-primary me-2"></i>¿Tienes un cupón?
                        </h5>
                        
                        @if(session('error_cupon'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error_cupon') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        @if(session('success_cupon'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success_cupon') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        <form action="{{ route('estudiante.aplicar-cupon') }}" method="POST" class="row g-2">
                            @csrf
                            <div class="col-md-8">
                                <input type="text" name="codigo" class="form-control form-control-lg" 
                                       placeholder="Ingresa tu código de cupón" 
                                       value="{{ session('cupon_aplicado') }}" 
                                       {{ session('cupon_aplicado') ? 'disabled' : '' }}>
                            </div>
                            <div class="col-md-4">
                                @if(session('cupon_aplicado'))
                                    <a href="{{ route('estudiante.eliminar-cupon') }}" class="btn btn-outline-danger btn-lg w-100">
                                        <i class="fas fa-trash-alt me-2"></i>Eliminar
                                    </a>
                                @else
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-check-circle me-2"></i>Aplicar
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                    
                    <!-- Resumen del pedido -->
                    <div class="mb-4">
                        <h5 class="fw-bold">
                            <i class="fas fa-receipt text-primary me-2"></i>Resumen del pedido
                        </h5>
                        <div class="bg-light p-4 rounded-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Precio del curso:</span>
                                <span class="fw-bold" id="precioOriginalText">${{ number_format($precios['precio_original'], 2) }}</span>
                            </div>
                            
                            @if($precios['tiene_descuento'])
                            <div class="d-flex justify-content-between mb-2 text-success" id="descuentoRow">
                                <span>Descuento:</span>
                                <span class="fw-bold" id="descuentoMonto">-${{ number_format($precios['monto_descuento'], 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="fs-5 fw-bold">Total:</span>
                                <span class="fs-4 fw-bold text-primary" id="precioFinalText">${{ number_format($precios['precio_final'], 2) }}</span>
                            </div>
                            @if($precios['porcentaje_descuento'])
                                <div class="mt-2 text-success small">
                                    <i class="fas fa-tag me-1"></i> Cupón aplicado: {{ $precios['porcentaje_descuento'] }}% de descuento
                                </div>
                            @endif
                            @else
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="fs-5 fw-bold">Total:</span>
                                <span class="fs-4 fw-bold text-primary" id="precioFinalText">${{ number_format($precios['precio_final'], 2) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Métodos de pago -->
                    <div class="mb-4">
                        <h5 class="fw-bold">
                            <i class="fas fa-credit-card text-primary me-2"></i>Método de pago
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="metodo-pago-card" data-metodo="transferencia">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="metodo_pago" id="transferencia" value="transferencia" required>
                                        <label class="form-check-label w-100" for="transferencia">
                                            <i class="fas fa-university fa-2x d-block mb-2 text-primary"></i>
                                            <strong>Transferencia Bancaria</strong>
                                            <small class="d-block text-muted">BBVA / OXXO</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="metodo-pago-card" data-metodo="oxxo">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="metodo_pago" id="oxxo" value="oxxo">
                                        <label class="form-check-label w-100" for="oxxo">
                                            <i class="fas fa-store fa-2x d-block mb-2 text-success"></i>
                                            <strong>Pago en OXXO</strong>
                                            <small class="d-block text-muted">Pago en efectivo</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botón de pago -->
                    <form id="formPago" action="{{ route('estudiante.procesar-solicitud-pago') }}" method="POST">
                        @csrf
                        <input type="hidden" name="metodo_pago" id="metodoPagoHidden">
                        <button type="submit" class="btn btn-success btn-lg w-100 py-3" id="btnPagar" disabled>
                            <i class="fas fa-file-invoice me-2"></i>Generar Ficha de Pago
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .metodo-pago-card {
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .metodo-pago-card:hover {
        border-color: #667eea;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(102,126,234,0.1);
    }
    
    .metodo-pago-card.selected {
        border-color: #667eea;
        background: linear-gradient(135deg, #667eea10, #764ba210);
    }
    
    .form-check-input {
        display: none;
    }
    
    .form-check-label {
        cursor: pointer;
        margin-bottom: 0;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102,126,234,0.3);
    }
    
    .btn-outline-danger:hover {
        transform: translateY(-2px);
    }
    
    .alert {
        border-radius: 12px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Seleccionar método de pago
    document.querySelectorAll('.metodo-pago-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.metodo-pago-card').forEach(c => {
                c.classList.remove('selected');
            });
            this.classList.add('selected');
            
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                document.getElementById('metodoPagoHidden').value = radio.value;
                document.getElementById('btnPagar').disabled = false;
            }
        });
    });
    
    // Validar formulario antes de enviar
    document.getElementById('formPago').addEventListener('submit', function(e) {
        const metodoSeleccionado = document.getElementById('metodoPagoHidden').value;
        if (!metodoSeleccionado) {
            e.preventDefault();
            Swal.fire({
                title: 'Atención',
                text: 'Por favor selecciona un método de pago',
                icon: 'warning',
                confirmButtonColor: '#667eea'
            });
        }
    });
    
    // Auto-cerrar alertas después de 5 segundos
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            setTimeout(() => bsAlert.close(), 5000);
        });
    }, 1000);
</script>
@endpush
@endsection