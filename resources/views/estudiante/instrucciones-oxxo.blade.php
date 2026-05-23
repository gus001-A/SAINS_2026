@extends('estudiante.layouts.app')

@section('title', 'Pago en OXXO - SAINS')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <h3 class="mb-0 fw-bold text-center">
                        <i class="fas fa-store me-2"></i>Pago en OXXO
                    </h3>
                    <p class="text-center text-white-50 mb-0">Genera tu ficha de pago</p>
                </div>
                
                <div class="card-body p-4 p-lg-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-barcode fa-4x text-primary mb-3"></i>
                        <h4>Tu referencia de pago</h4>
                        <div class="bg-light p-3 rounded-3 d-inline-block">
                            <code class="fs-3 fw-bold">{{ $pago->referencia_pago }}</code>
                        </div>
                        <p class="mt-3 text-muted">Monto a pagar: <strong class="fs-4 text-primary">${{ number_format($pago->monto_pago, 2) }}</strong></p>
                    </div>
                    
                    <div class="alert alert-info text-start">
                        <h6 class="fw-bold mb-2"><i class="fas fa-info-circle me-2"></i>Instrucciones:</h6>
                        <ol class="mb-0">
                            <li>Acude a cualquier tienda OXXO.</li>
                            <li>Indica al cajero que deseas realizar un pago de servicio.</li>
                            <li>Proporciona la referencia: <strong>{{ $pago->referencia_pago }}</strong></li>
                            <li>Realiza el pago en efectivo por el monto indicado.</li>
                            <li>Guarda tu comprobante y envíalo a pagos@sains.com</li>
                        </ol>
                    </div>
                    
                    <div class="alert alert-warning text-start mt-3">
                        <i class="fas fa-clock me-2"></i>
                        <strong>Importante:</strong> El pago en OXXO puede tardar hasta 24 horas en reflejarse.
                    </div>
                    
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
        padding: 12px 24px;
        border-radius: 12px;
        font-family: monospace;
        font-size: 1.1rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102,126,234,0.3);
    }
    
    .alert-info {
        background: #e0f2fe;
        border-color: #bae6fd;
    }
    
    .alert-warning {
        background: #fef3c7;
        border-color: #fde68a;
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