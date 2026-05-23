@extends('estudiante.layouts.app')

@section('title', '¡Comprobante Recibido! | SAINS')

@section('content')
<div class="pago-exitoso-wrapper">
    <div class="pago-exitoso-card">
        <!-- Icono de éxito -->
        <div class="success-icon-wrapper">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="success-pulse"></div>
        </div>
        
        <!-- Título -->
        <h1 class="success-title">¡Comprobante recibido!</h1>
        <p class="success-message">
            Hemos recibido tu comprobante de pago correctamente.
        </p>
        
        <!-- Tarjeta de información -->
        <div class="info-card">
            <div class="info-card-header">
                <i class="fas fa-receipt"></i>
                <span>Detalles de tu solicitud</span>
            </div>
            <div class="info-card-body">
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-hashtag"></i>
                        <span>Referencia</span>
                    </div>
                    <div class="info-value">
                        <code class="reference-code">{{ $pago->referencia_pago }}</code>
                        <button class="copy-btn" onclick="copiarReferencia()" title="Copiar referencia">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Monto</span>
                    </div>
                    <div class="info-value">
                        <span class="monto">${{ number_format($pago->monto_pago, 2) }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-credit-card"></i>
                        <span>Método de pago</span>
                    </div>
                    <div class="info-value">
                        @if($pago->tipo_pago == 'transferencia')
                            <span class="badge-transferencia">
                                <i class="fas fa-university"></i> Transferencia bancaria
                            </span>
                        @else
                            <span class="badge-oxxo">
                                <i class="fas fa-store"></i> Pago en OXXO
                            </span>
                        @endif
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Fecha de solicitud</span>
                    </div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y \a \l\a\s H:i') }} hrs
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-tag"></i>
                        <span>Estatus</span>
                    </div>
                    <div class="info-value">
                        <span class="status-badge status-pendiente">
                            <i class="fas fa-clock"></i> Pendiente de validación
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Siguientes pasos -->
        <div class="next-steps">
            <h3>
                <i class="fas fa-question-circle"></i>
                ¿Qué sigue?
            </h3>
            <div class="steps-grid">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Validación de pago</h4>
                        <p>Nuestro equipo revisará tu comprobante en un plazo de <strong>24-48 horas hábiles</strong></p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Activación del plan</h4>
                        <p>Recibirás un correo electrónico cuando tu plan premium esté activo</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h4>Acceso total</h4>
                        <p>Podrás acceder a todas las clases premium y simuladores</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recomendaciones adicionales -->
        <div class="recomendaciones">
            <div class="recomendacion-item">
                <i class="fas fa-envelope-open-text"></i>
                <div>
                    <strong>Revisa tu correo</strong>
                    <p>Te enviaremos una notificación cuando tu pago sea validado</p>
                </div>
            </div>
            <div class="recomendacion-item">
                <i class="fas fa-file-invoice"></i>
                <div>
                    <strong>Guarda tu referencia</strong>
                    <p>Guarda el código <code>{{ $pago->referencia_pago }}</code> para cualquier aclaración</p>
                </div>
            </div>
            <div class="recomendacion-item">
                <i class="fas fa-headset"></i>
                <div>
                    <strong>¿Necesitas ayuda?</strong>
                    <p>Contacta a soporte en <a href="mailto:soporte@sains.com">soporte@sains.com</a></p>
                </div>
            </div>
        </div>
        
        <!-- Botones de acción -->
        <div class="action-buttons">
            <a href="{{ route('estudiante.dashboard') }}" class="btn-primary-custom">
                <i class="fas fa-tachometer-alt"></i>
                Ir al dashboard
            </a>
            <a href="{{ route('estudiante.mis-pagos') }}" class="btn-secondary-custom">
                <i class="fas fa-history"></i>
                Ver historial de pagos
            </a>
            <a href="{{ route('estudiante.clases-premium') }}" class="btn-outline-custom">
                <i class="fas fa-graduation-cap"></i>
                Explorar contenido
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Wrapper sin fondos extraños */
.pago-exitoso-wrapper {
    max-width: 800px;
    margin: 40px auto;
    padding: 0 20px;
}

.pago-exitoso-card {
    background: white;
    border-radius: 40px;
    padding: 48px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    text-align: center;
}

/* Icono de éxito */
.success-icon-wrapper {
    position: relative;
    display: inline-block;
    margin-bottom: 24px;
}

.success-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #10b981, #059669);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 2;
}

.success-icon i {
    font-size: 50px;
    color: white;
}

.success-pulse {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 120px;
    height: 120px;
    background: rgba(16, 185, 129, 0.3);
    border-radius: 50%;
    animation: pulse 2s infinite;
    z-index: 1;
}

@keyframes pulse {
    0% {
        transform: translate(-50%, -50%) scale(0.8);
        opacity: 1;
    }
    100% {
        transform: translate(-50%, -50%) scale(1.4);
        opacity: 0;
    }
}

.success-title {
    font-size: 1.8rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 12px;
}

.success-message {
    font-size: 1rem;
    color: #64748b;
    margin-bottom: 32px;
}

/* Tarjeta de información */
.info-card {
    background: #f8fafc;
    border-radius: 24px;
    margin-bottom: 32px;
    overflow: hidden;
    text-align: left;
}

.info-card-header {
    background: linear-gradient(135deg, #667eea, #764ba2);
    padding: 16px 24px;
    color: white;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-card-header i {
    font-size: 1.2rem;
}

.info-card-body {
    padding: 20px 24px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #e2e8f0;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #475569;
    font-size: 0.9rem;
}

.info-label i {
    width: 20px;
    color: #667eea;
}

.info-value {
    font-weight: 600;
    color: #1e293b;
}

.reference-code {
    background: #e2e8f0;
    padding: 6px 12px;
    border-radius: 10px;
    font-family: monospace;
    font-size: 0.85rem;
    letter-spacing: 1px;
}

.copy-btn {
    background: none;
    border: none;
    cursor: pointer;
    color: #667eea;
    margin-left: 8px;
    transition: all 0.2s;
}

.copy-btn:hover {
    color: #5a67d8;
    transform: scale(1.05);
}

.monto {
    font-size: 1.2rem;
    font-weight: 700;
    color: #10b981;
}

.badge-transferencia, .badge-oxxo {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.badge-transferencia {
    background: #dbeafe;
    color: #1e40af;
}

.badge-oxxo {
    background: #fed7aa;
    color: #92400e;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-pendiente {
    background: #fef3c7;
    color: #b45309;
}

/* Siguientes pasos */
.next-steps {
    text-align: left;
    margin-bottom: 32px;
}

.next-steps h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.next-steps h3 i {
    color: #667eea;
}

.steps-grid {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.step-item {
    flex: 1;
    min-width: 180px;
    display: flex;
    gap: 12px;
    background: #f8fafc;
    padding: 16px;
    border-radius: 20px;
    transition: all 0.3s;
}

.step-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.step-number {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.step-content h4 {
    font-size: 0.9rem;
    font-weight: 700;
    margin-bottom: 6px;
    color: #1e293b;
}

.step-content p {
    font-size: 0.75rem;
    color: #64748b;
    margin: 0;
}

/* Recomendaciones */
.recomendaciones {
    background: #f0fdf4;
    border-radius: 20px;
    padding: 20px;
    margin-bottom: 32px;
    text-align: left;
}

.recomendacion-item {
    display: flex;
    gap: 12px;
    padding: 10px 0;
}

.recomendacion-item i {
    font-size: 1.2rem;
    color: #10b981;
    flex-shrink: 0;
    margin-top: 3px;
}

.recomendacion-item strong {
    font-size: 0.85rem;
    display: block;
    margin-bottom: 4px;
    color: #1e293b;
}

.recomendacion-item p {
    font-size: 0.75rem;
    color: #64748b;
    margin: 0;
}

.recomendacion-item code {
    background: #e2e8f0;
    padding: 2px 6px;
    border-radius: 6px;
    font-size: 0.7rem;
}

.recomendacion-item a {
    color: #10b981;
    text-decoration: none;
}

.recomendacion-item a:hover {
    text-decoration: underline;
}

/* Botones de acción */
.action-buttons {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-primary-custom, .btn-secondary-custom, .btn-outline-custom {
    padding: 12px 28px;
    border-radius: 40px;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

.btn-primary-custom {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-secondary-custom {
    background: #f1f5f9;
    color: #475569;
}

.btn-secondary-custom:hover {
    background: #e2e8f0;
    transform: translateY(-2px);
    color: #1e293b;
}

.btn-outline-custom {
    border: 1px solid #e2e8f0;
    background: white;
    color: #667eea;
}

.btn-outline-custom:hover {
    border-color: #667eea;
    background: #f8fafc;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .pago-exitoso-wrapper {
        margin: 20px auto;
    }
    
    .pago-exitoso-card {
        padding: 32px 24px;
    }
    
    .success-title {
        font-size: 1.5rem;
    }
    
    .info-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .steps-grid {
        flex-direction: column;
    }
    
    .step-item {
        min-width: auto;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-primary-custom, .btn-secondary-custom, .btn-outline-custom {
        justify-content: center;
    }
}

/* Dark mode */
body.dark-mode .pago-exitoso-card {
    background: #1e293b;
}

body.dark-mode .success-title {
    color: #f1f5f9;
}

body.dark-mode .info-card {
    background: #0f172a;
}

body.dark-mode .info-row {
    border-bottom-color: #334155;
}

body.dark-mode .info-label {
    color: #94a3b8;
}

body.dark-mode .info-value {
    color: #e2e8f0;
}

body.dark-mode .reference-code {
    background: #334155;
    color: #e2e8f0;
}

body.dark-mode .step-item {
    background: #0f172a;
}

body.dark-mode .step-content h4 {
    color: #f1f5f9;
}

body.dark-mode .recomendaciones {
    background: #0f172a;
}

body.dark-mode .recomendacion-item strong {
    color: #f1f5f9;
}

body.dark-mode .btn-secondary-custom {
    background: #334155;
    color: #e2e8f0;
}

body.dark-mode .btn-secondary-custom:hover {
    background: #475569;
}

body.dark-mode .btn-outline-custom {
    background: #1e293b;
    border-color: #334155;
    color: #a78bfa;
}

body.dark-mode .btn-outline-custom:hover {
    background: #334155;
}
</style>
@endpush

@push('scripts')
<script>
function copiarReferencia() {
    const referencia = document.querySelector('.reference-code').innerText;
    navigator.clipboard.writeText(referencia).then(() => {
        Swal.fire({
            icon: 'success',
            title: '¡Copiado!',
            text: 'Referencia copiada al portapapeles',
            showConfirmButton: false,
            timer: 2000,
            background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#f1f5f9' : '#1e293b'
        });
    }).catch(() => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo copiar la referencia',
            confirmButtonColor: '#667eea'
        });
    });
}
</script>
@endpush
@endsection