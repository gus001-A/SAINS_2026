@extends('estudiante.layouts.app')

@section('title', 'Instrucciones de Transferencia - SAINS')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <h3 class="mb-0 fw-bold text-center">
                        <i class="fas fa-university me-2"></i>Instrucciones de Transferencia
                    </h3>
                    <p class="text-center text-white-50 mb-0">Completa tu pago para activar tu plan premium</p>
                </div>
                
                <div class="card-body p-4 p-lg-5">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <!-- Información del pago -->
                    <div class="mb-4">
                        <h5 class="fw-bold">
                            <i class="fas fa-info-circle text-primary me-2"></i>Información de tu pago
                        </h5>
                        <div class="bg-light p-3 rounded-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Referencia:</strong></p>
                                    <p class="mb-3"><code class="fs-5">{{ $pago->referencia_pago }}</code></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Monto a pagar:</strong></p>
                                    <p class="mb-3 fs-4 fw-bold text-primary">${{ number_format($pago->monto_pago, 2) }}</p>
                                </div>
                            </div>
                            <p class="mb-0 text-muted small">
                                <i class="fas fa-clock me-1"></i> Fecha de solicitud: {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Datos bancarios -->
                    <div class="mb-4">
                        <h5 class="fw-bold">
                            <i class="fas fa-building-columns text-primary me-2"></i>Datos bancarios
                        </h5>
                        <div class="bg-light p-4 rounded-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted small">Banco</p>
                                    <p class="fw-bold">{{ $datosBancarios['banco'] }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted small">Número de cuenta</p>
                                    <p class="fw-bold">{{ $datosBancarios['cuenta'] }}</p>
                                </div>
                                <div class="col-md-12">
                                    <p class="mb-1 text-muted small">CLABE interbancaria</p>
                                    <p class="fw-bold">{{ $datosBancarios['clabe'] }}</p>
                                </div>
                                <div class="col-md-12">
                                    <p class="mb-1 text-muted small">Beneficiario</p>
                                    <p class="fw-bold">{{ $datosBancarios['beneficiario'] }}</p>
                                </div>
                                <div class="col-md-12">
                                    <p class="mb-1 text-muted small">Concepto de pago</p>
                                    <div class="alert alert-warning py-2">
                                        <code class="fs-6">{{ $datosBancarios['concepto'] }}</code>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pasos a seguir -->
                    <div class="mb-4">
                        <h5 class="fw-bold">
                            <i class="fas fa-list-check text-primary me-2"></i>Pasos a seguir
                        </h5>
                        <div class="bg-light p-4 rounded-3">
                            <ol class="mb-0">
                                <li class="mb-2">Realiza la transferencia o depósito bancario con los datos anteriores.</li>
                                <li class="mb-2">Guarda tu comprobante de pago (captura de pantalla o foto).</li>
                                <li class="mb-2">Envía tu comprobante al correo <strong>pagos@sains.com</strong> o súbelo más abajo.</li>
                                <li class="mb-2">Espera la validación de nuestro equipo (24-48 hrs hábiles).</li>
                                <li>Recibirás un correo de confirmación al activar tu plan.</li>
                            </ol>
                        </div>
                    </div>
                    
                    <!-- Botones de acción -->
                    <div class="d-flex justify-content-between gap-3 mt-4">
                        <a href="{{ route('estudiante.dashboard') }}" class="btn btn-outline-secondary flex-grow-1">
                            <i class="fas fa-arrow-left me-2"></i>Volver al dashboard
                        </a>
                        <button class="btn btn-primary flex-grow-1" onclick="copiarReferencia()">
                            <i class="fas fa-copy me-2"></i>Copiar referencia
                        </button>
                    </div>
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
    
    code {
        background: #f0f2f5;
        padding: 8px 12px;
        border-radius: 8px;
        font-family: monospace;
        font-size: 0.9rem;
        word-break: break-all;
    }
    
    .alert-warning {
        background: #fff3cd;
        border-color: #ffecb5;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102,126,234,0.3);
    }
</style>
@endpush

@push('scripts')
<script>
    function copiarReferencia() {
        const referencia = '{{ $pago->referencia_pago }}';
        navigator.clipboard.writeText(referencia).then(() => {
            Swal.fire({
                title: '¡Copiado!',
                text: 'Referencia copiada al portapapeles',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }
</script>
@endpush
@endsection