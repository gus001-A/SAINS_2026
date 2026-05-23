@extends('estudiante.layouts.app')

@section('title', 'Instrucciones de Pago - SAINS')

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
                                <i class="fas fa-clock me-1"></i> Fecha de solicitud: {{ $pago->fecha_pago->format('d/m/Y H:i') }}
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
                                <li class="mb-2">Sube tu comprobante en el siguiente formulario.</li>
                                <li class="mb-2">Espera la validación de nuestro equipo (24-48 hrs hábiles).</li>
                                <li>Recibirás un correo de confirmación al activar tu plan.</li>
                            </ol>
                        </div>
                    </div>
                    
                    <!-- Formulario para subir comprobante -->
                    <div class="mb-4">
                        <h5 class="fw-bold">
                            <i class="fas fa-cloud-upload-alt text-primary me-2"></i>Subir comprobante
                        </h5>
                        <div class="bg-light p-4 rounded-3">
                            <form id="formComprobante" action="{{ route('estudiante.registrar-pago') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="pago_id" value="{{ $pago->id }}">
                                <div class="mb-3">
                                    <label class="form-label">Comprobante de pago</label>
                                    <input type="file" name="comprobante" class="form-control" accept="image/*,.pdf" required>
                                    <small class="text-muted">Formatos permitidos: JPG, PNG, PDF (máx. 5MB)</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nota adicional (opcional)</label>
                                    <textarea name="nota_usuario" class="form-control" rows="2" placeholder="Ej: Transferencia desde BBVA, referencia..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    <i class="fas fa-paper-plane me-2"></i>Enviar comprobante
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('estudiante.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver al dashboard
                        </a>
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
        padding: 4px 8px;
        border-radius: 6px;
    }
</style>
@endpush

@push('scripts')
<script>
    $('#formComprobante').on('submit', function(e) {
        const btn = $(this).find('button[type="submit"]');
        btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Enviando...').prop('disabled', true);
        
        // El formulario se enviará normalmente
        $(this).unbind('submit').submit();
    });
</script>
@endpush
@endsection