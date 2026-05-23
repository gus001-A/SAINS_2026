@extends('estudiante.layouts.app')

@section('title', 'Ficha de Pago - SAINS')

@section('content')
<div class="ficha-pago-wrapper">
    <div class="ficha-pago-card">
        <!-- Header -->
        <div class="ficha-header">
            <div class="ficha-header-icon">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div class="ficha-header-text">
                <h1>Ficha de Pago</h1>
                <p>Realiza tu pago y activa tu plan premium</p>
            </div>
        </div>

        <!-- Información del pago -->
        <div class="info-pago-section">
            <div class="section-title">
                <i class="fas fa-info-circle"></i>
                <span>Información de tu pago</span>
            </div>
            <div class="info-pago-grid">
                <div class="info-pago-item">
                    <div class="info-label">
                        <i class="fas fa-hashtag"></i>
                        <span>Referencia de pago</span>
                    </div>
                    <div class="info-value referencia-box">
                        <code id="referenciaCode">{{ $pago->referencia_pago }}</code>
                        <button class="copy-referencia-btn" onclick="copiarReferencia()" title="Copiar referencia">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                <div class="info-pago-item">
                    <div class="info-label">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Monto a pagar</span>
                    </div>
                    <div class="info-value monto-valor">
                        ${{ number_format($pago->monto_pago, 2) }} <span class="monto-moneda">MXN</span>
                    </div>
                </div>
                <div class="info-pago-item">
                    <div class="info-label">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Fecha de solicitud</span>
                    </div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                        <span class="time-text">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('H:i') }} hrs</span>
                    </div>
                </div>
                <div class="info-pago-item">
                    <div class="info-label">
                        <i class="fas fa-credit-card"></i>
                        <span>Método de pago</span>
                    </div>
                    <div class="info-value">
                        @if($pago->tipo_pago == 'Transferencia')
                            <span class="method-badge method-transferencia">
                                <i class="fas fa-university"></i> Transferencia bancaria
                            </span>
                        @elseif($pago->tipo_pago == 'Oxxo')
                            <span class="method-badge method-oxxo">
                                <i class="fas fa-store"></i> Pago en OXXO
                            </span>
                        @else
                            <span class="method-badge method-card">
                                <i class="fas fa-credit-card"></i> Tarjeta
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Ficha de pago (imagen) -->
        <div class="ficha-imagen-section">
            <div class="section-title">
                <i class="fas fa-file-image"></i>
                <span>Ficha de pago</span>
            </div>
            <div class="ficha-imagen-container">
                <div class="imagen-wrapper">
                    <img src="{{ asset('images/FICHA_PAGO.jpeg') }}" alt="Ficha de Pago" id="fichaImagen">
                    <div class="imagen-overlay">
                        <button class="overlay-btn" onclick="verImagenCompleta()">
                            <i class="fas fa-search-plus"></i> Ver completa
                        </button>
                    </div>
                </div>
                <div class="imagen-info">
                    <i class="fas fa-info-circle"></i>
                    <span>La ficha es la misma para todos los pagos. Usa tu referencia única al realizar la transferencia.</span>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="acciones-section">
            <div class="section-title">
                <i class="fas fa-tasks"></i>
                <span>Acciones</span>
            </div>
            <div class="acciones-grid">
                <button class="accion-card" onclick="descargarFicha()">
                    <div class="accion-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="accion-info">
                        <h4>Descargar ficha</h4>
                        <p>Guarda la ficha en tu dispositivo</p>
                    </div>
                    <i class="fas fa-arrow-right"></i>
                </button>
                <button class="accion-card" onclick="copiarReferencia()">
                    <div class="accion-icon">
                        <i class="fas fa-copy"></i>
                    </div>
                    <div class="accion-info">
                        <h4>Copiar referencia</h4>
                        <p>Copia tu referencia de pago</p>
                    </div>
                    <i class="fas fa-arrow-right"></i>
                </button>
                <button class="accion-card" onclick="window.location.href='{{ route('estudiante.mis-pagos') }}'">
                    <div class="accion-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="accion-info">
                        <h4>Ver mi pago</h4>
                        <p>Consulta el estado de tu pago</p>
                    </div>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Subir comprobante -->
        <div class="subir-comprobante-section">
            <div class="section-title">
                <i class="fas fa-cloud-upload-alt"></i>
                <span>¿Ya realizaste el pago?</span>
            </div>
            <div class="subir-card">
                <div class="subir-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="subir-info">
                    <h4>Sube tu comprobante de pago</h4>
                    <p>Una vez que realices el pago, sube tu comprobante para que nuestro equipo lo valide</p>
                </div>
                <button class="btn-subir" onclick="abrirModalComprobante()">
                    <i class="fas fa-cloud-upload-alt"></i> Subir comprobante
                </button>
            </div>
        </div>

        <!-- Instrucciones de pago -->
        <div class="instrucciones-section">
            <div class="section-title">
                <i class="fas fa-lightbulb"></i>
                <span>Instrucciones de pago</span>
            </div>
            <div class="instrucciones-grid">
                <div class="instruccion-item">
                    <div class="instruccion-number">1</div>
                    <div class="instruccion-content">
                        <h5>Descarga la ficha</h5>
                        <p>Guarda la ficha de pago en tu dispositivo</p>
                    </div>
                </div>
                <div class="instruccion-item">
                    <div class="instruccion-number">2</div>
                    <div class="instruccion-content">
                        <h5>Realiza el pago</h5>
                        <p>Usa la referencia <strong>{{ $pago->referencia_pago }}</strong> al hacer tu transferencia o pago en OXXO</p>
                    </div>
                </div>
                <div class="instruccion-item">
                    <div class="instruccion-number">3</div>
                    <div class="instruccion-content">
                        <h5>Sube tu comprobante</h5>
                        <p>Adjunta el comprobante de pago para validación</p>
                    </div>
                </div>
                <div class="instruccion-item">
                    <div class="instruccion-number">4</div>
                    <div class="instruccion-content">
                        <h5>Espera la validación</h5>
                        <p>Tu pago será validado en 24-48 horas hábiles</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos bancarios (si aplica) -->
        @if($pago->tipo_pago == 'transferencia')
        <div class="datos-bancarios-section">
            <div class="section-title">
                <i class="fas fa-university"></i>
                <span>Datos bancarios</span>
            </div>
            <div class="datos-bancarios-card">
                <div class="dato-item">
                    <span class="dato-label">Banco:</span>
                    <span class="dato-value">BBVA México</span>
                </div>
                <div class="dato-item">
                    <span class="dato-label">Cuenta:</span>
                    <span class="dato-value">1234 5678 9012 3456</span>
                </div>
                <div class="dato-item">
                    <span class="dato-label">CLABE:</span>
                    <span class="dato-value">012 345 6789 012345678</span>
                </div>
                <div class="dato-item">
                    <span class="dato-label">Beneficiario:</span>
                    <span class="dato-value">SAINS Educación</span>
                </div>
                <div class="dato-item">
                    <span class="dato-label">Concepto:</span>
                    <span class="dato-value">Pago Plan SAINS - {{ $pago->referencia_pago }}</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Botón volver -->
        <div class="back-button">
            <a href="{{ route('estudiante.dashboard') }}" class="btn-secondary-custom">
                <i class="fas fa-arrow-left me-2"></i>Volver al dashboard
            </a>
        </div>
    </div>
</div>

<!-- MODAL MEJORADO PARA SUBIR COMPROBANTE (sin notas) -->
<div class="modal-custom" id="modalComprobante" style="display: none;">
    <div class="modal-custom-overlay"></div>
    <div class="modal-custom-container">
        <div class="modal-custom-content">
            <div class="modal-custom-header">
                <div class="modal-header-left">
                    <div class="header-icon-circle">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div>
                        <h5>Subir comprobante de pago</h5>
                        <p>Adjunta tu comprobante para validar tu pago</p>
                    </div>
                </div>
                <button class="modal-custom-close" onclick="cerrarModalComprobante()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('estudiante.subir-comprobante') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-custom-body">
                    <input type="hidden" name="pago_id" value="{{ $pago->id }}">
                    
                    <!-- Referencia destacada -->
                    <div class="referencia-modal">
                        <div class="referencia-modal-label">
                            <i class="fas fa-hashtag"></i>
                            <span>Referencia de pago</span>
                        </div>
                        <div class="referencia-modal-value">
                            <code>{{ $pago->referencia_pago }}</code>
                            <button type="button" class="copy-ref-modal" onclick="copiarReferenciaModal()">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Área de subida de archivo MEJORADA -->
                    <div class="upload-area-premium" id="uploadAreaPremium">
                        <input type="file" name="comprobante" id="comprobanteInputPremium" accept="image/*,.pdf" style="display: none;" required>
                        
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <h6>Arrastra o haz clic para subir</h6>
                        <p>Formatos permitidos: JPG, PNG, PDF (Máx. 5MB)</p>
                        
                        <div class="upload-buttons">
                            <button type="button" class="btn-upload-premium" onclick="document.getElementById('comprobanteInputPremium').click()">
                                <i class="fas fa-folder-open"></i> Seleccionar archivo
                            </button>
                        </div>
                        
                        <!-- Preview del archivo seleccionado -->
                        <div class="file-preview-premium" id="filePreviewPremium" style="display: none;">
                            <div class="preview-card">
                                <div class="preview-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="preview-info">
                                    <span class="file-name" id="fileNamePremium"></span>
                                    <span class="file-size" id="fileSizePremium"></span>
                                </div>
                                <button type="button" class="preview-remove" onclick="limpiarArchivoPremium()">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mensaje de ayuda -->
                    <div class="info-modal">
                        <div class="info-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="info-text">
                            <strong>¿Qué sigue?</strong>
                            <p>Tu comprobante será revisado por nuestro equipo en un plazo de 24-48 horas hábiles. Recibirás una notificación cuando tu plan esté activo.</p>
                        </div>
                    </div>
                </div>
                
                <div class="modal-custom-footer">
                    <button type="button" class="btn-cancel-modal" onclick="cerrarModalComprobante()">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn-submit-modal" id="btnEnviarComprobante">
                        <i class="fas fa-paper-plane me-2"></i>Enviar comprobante
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver imagen completa -->
<div class="modal-custom" id="modalImagenCompleta" style="display: none;">
    <div class="modal-custom-overlay"></div>
    <div class="modal-custom-container modal-imagen">
        <div class="modal-custom-content imagen-completa">
            <div class="modal-custom-header minimal">
                <h5>Ficha de pago</h5>
                <button class="modal-custom-close" onclick="cerrarImagenCompleta()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-custom-body">
                <img src="{{ asset('images/FICHA_PAGO.jpeg') }}" alt="Ficha de Pago" class="imagen-completa-img">
                <div class="imagen-actions">
                    <button class="btn-download" onclick="descargarFicha()">
                        <i class="fas fa-download"></i> Descargar
                    </button>
                    <button class="btn-close" onclick="cerrarImagenCompleta()">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Variables */
:root {
    --primary: #667eea;
    --primary-dark: #5a67d8;
    --secondary: #764ba2;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --dark: #1e293b;
    --gray: #64748b;
    --light: #f8fafc;
    --border: #e2e8f0;
}

/* Wrapper */
.ficha-pago-wrapper {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
}

.ficha-pago-card {
    background: white;
    border-radius: 32px;
    box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

/* Header */
.ficha-header {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    padding: 40px;
    text-align: center;
    color: white;
}

.ficha-header-icon {
    width: 80px;
    height: 80px;
    background: rgba(255,255,255,0.2);
    border-radius: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 2rem;
}

.ficha-header-text h1 {
    font-size: 1.8rem;
    font-weight: 800;
    margin-bottom: 8px;
}

.ficha-header-text p {
    opacity: 0.9;
    margin: 0;
}

/* Secciones generales */
.section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid var(--border);
}

.section-title i {
    font-size: 1.2rem;
    color: var(--primary);
}

.section-title span {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--dark);
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Info pago */
.info-pago-section {
    padding: 24px 32px;
    border-bottom: 1px solid var(--border);
}

.info-pago-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.info-pago-item {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.info-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.7rem;
    color: var(--gray);
    text-transform: uppercase;
}

.info-value {
    font-weight: 600;
    color: var(--dark);
    font-size: 1rem;
}

.referencia-box {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--light);
    padding: 8px 12px;
    border-radius: 12px;
    width: fit-content;
}

.referencia-box code {
    font-size: 1rem;
    font-weight: 700;
    letter-spacing: 1px;
    background: none;
}

.copy-referencia-btn {
    background: white;
    border: 1px solid var(--border);
    width: 32px;
    height: 32px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    color: var(--gray);
}

.copy-referencia-btn:hover {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.monto-valor {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--primary);
}

.monto-moneda {
    font-size: 0.8rem;
    font-weight: normal;
    color: var(--gray);
}

.time-text {
    font-size: 0.7rem;
    font-weight: normal;
    color: var(--gray);
    margin-left: 6px;
}

.method-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.method-transferencia {
    background: #dbeafe;
    color: #1e40af;
}

.method-oxxo {
    background: #fed7aa;
    color: #92400e;
}

.method-card {
    background: #e0e7ff;
    color: #3730a3;
}

/* Ficha imagen */
.ficha-imagen-section {
    padding: 24px 32px;
    border-bottom: 1px solid var(--border);
}

.ficha-imagen-container {
    background: var(--light);
    border-radius: 20px;
    padding: 20px;
}

.imagen-wrapper {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    cursor: pointer;
}

.imagen-wrapper img {
    width: 100%;
    height: auto;
    display: block;
    transition: transform 0.3s;
}

.imagen-wrapper:hover img {
    transform: scale(1.02);
}

.imagen-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.imagen-wrapper:hover .imagen-overlay {
    opacity: 1;
}

.overlay-btn {
    background: white;
    border: none;
    padding: 10px 20px;
    border-radius: 30px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.overlay-btn:hover {
    transform: scale(1.05);
}

.imagen-info {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 12px;
    padding: 8px 12px;
    background: #dbeafe;
    border-radius: 12px;
    font-size: 0.7rem;
    color: #1e40af;
}

/* Acciones */
.acciones-section {
    padding: 24px 32px;
    border-bottom: 1px solid var(--border);
}

.acciones-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.accion-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: var(--light);
    border: 1px solid var(--border);
    border-radius: 16px;
    cursor: pointer;
    transition: all 0.3s;
    text-align: left;
    width: 100%;
}

.accion-card:hover {
    transform: translateY(-3px);
    border-color: var(--primary);
    box-shadow: 0 5px 15px rgba(102,126,234,0.1);
}

.accion-icon {
    width: 48px;
    height: 48px;
    background: white;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    color: var(--primary);
}

.accion-info {
    flex: 1;
}

.accion-info h4 {
    font-size: 0.85rem;
    font-weight: 700;
    margin: 0 0 4px 0;
    color: var(--dark);
}

.accion-info p {
    font-size: 0.7rem;
    color: var(--gray);
    margin: 0;
}

.accion-card i:last-child {
    color: var(--gray);
    transition: transform 0.2s;
}

.accion-card:hover i:last-child {
    transform: translateX(5px);
    color: var(--primary);
}

/* Subir comprobante */
.subir-comprobante-section {
    padding: 24px 32px;
    border-bottom: 1px solid var(--border);
}

.subir-card {
    display: flex;
    align-items: center;
    gap: 20px;
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    border-radius: 20px;
    padding: 20px;
}

.subir-icon {
    width: 60px;
    height: 60px;
    background: white;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--success);
}

.subir-info {
    flex: 1;
}

.subir-info h4 {
    font-size: 1rem;
    font-weight: 700;
    margin: 0 0 4px 0;
    color: #065f46;
}

.subir-info p {
    font-size: 0.75rem;
    margin: 0;
    color: #047857;
}

.btn-subir {
    background: var(--success);
    border: none;
    padding: 12px 24px;
    border-radius: 40px;
    font-weight: 600;
    color: white;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-subir:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(16,185,129,0.3);
}

/* Instrucciones */
.instrucciones-section {
    padding: 24px 32px;
    border-bottom: 1px solid var(--border);
}

.instrucciones-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

.instruccion-item {
    display: flex;
    gap: 12px;
}

.instruccion-number {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    flex-shrink: 0;
}

.instruccion-content h5 {
    font-size: 0.8rem;
    font-weight: 700;
    margin: 0 0 4px 0;
    color: var(--dark);
}

.instruccion-content p {
    font-size: 0.7rem;
    margin: 0;
    color: var(--gray);
}

/* Datos bancarios */
.datos-bancarios-section {
    padding: 24px 32px;
    border-bottom: 1px solid var(--border);
}

.datos-bancarios-card {
    background: var(--light);
    border-radius: 20px;
    padding: 20px;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.dato-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.dato-label {
    font-size: 0.7rem;
    color: var(--gray);
    text-transform: uppercase;
}

.dato-value {
    font-weight: 600;
    color: var(--dark);
}

/* Botón volver */
.back-button {
    padding: 24px 32px;
    text-align: center;
    background: var(--light);
    border-top: 1px solid var(--border);
}

.btn-secondary-custom {
    display: inline-flex;
    align-items: center;
    padding: 12px 28px;
    background: white;
    border: 1px solid var(--border);
    border-radius: 40px;
    font-weight: 600;
    text-decoration: none;
    color: var(--gray);
    transition: all 0.3s;
}

.btn-secondary-custom:hover {
    background: var(--border);
    transform: translateY(-2px);
    color: var(--dark);
}

/* ========== MODAL MEJORADO ========== */
.modal-custom {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1050;
    display: none;
}

.modal-custom-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(8px);
}

.modal-custom-container {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.modal-custom-content {
    position: relative;
    max-width: 500px;
    width: 100%;
    background: white;
    border-radius: 32px;
    animation: modalSlideIn 0.3s ease-out;
    overflow: hidden;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-30px) scale(0.95);
        opacity: 0;
    }
    to {
        transform: translateY(0) scale(1);
        opacity: 1;
    }
}

.modal-custom-header {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    padding: 24px 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.header-icon-circle {
    width: 50px;
    height: 50px;
    background: rgba(255,255,255,0.2);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.header-icon-circle i {
    font-size: 1.5rem;
    color: white;
}

.modal-header-left h5 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 700;
    color: white;
}

.modal-header-left p {
    margin: 4px 0 0;
    font-size: 0.7rem;
    color: rgba(255,255,255,0.8);
}

.modal-custom-close {
    background: rgba(255,255,255,0.2);
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 12px;
    color: white;
    cursor: pointer;
    transition: all 0.2s;
}

.modal-custom-close:hover {
    background: rgba(255,255,255,0.3);
    transform: scale(1.05);
}

.modal-custom-body {
    padding: 28px;
}

/* Referencia en modal */
.referencia-modal {
    background: var(--light);
    border-radius: 16px;
    padding: 16px;
    margin-bottom: 24px;
}

.referencia-modal-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.7rem;
    color: var(--gray);
    text-transform: uppercase;
    margin-bottom: 8px;
}

.referencia-modal-value {
    display: flex;
    align-items: center;
    gap: 12px;
}

.referencia-modal-value code {
    flex: 1;
    background: white;
    padding: 10px 14px;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 700;
    letter-spacing: 1px;
    border: 1px solid var(--border);
}

.copy-ref-modal {
    background: white;
    border: 1px solid var(--border);
    width: 40px;
    height: 40px;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s;
    color: var(--gray);
}

.copy-ref-modal:hover {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

/* Área de subida premium */
.upload-area-premium {
    border: 2px dashed var(--border);
    border-radius: 20px;
    padding: 32px 24px;
    text-align: center;
    transition: all 0.3s;
    margin-bottom: 24px;
    background: var(--light);
}

.upload-area-premium:hover {
    border-color: var(--primary);
    background: rgba(102,126,234,0.05);
}

.upload-icon i {
    font-size: 3rem;
    color: var(--gray);
    margin-bottom: 16px;
}

.upload-area-premium h6 {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--dark);
}

.upload-area-premium p {
    font-size: 0.7rem;
    color: var(--gray);
    margin-bottom: 20px;
}

.upload-buttons {
    display: flex;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
}

.btn-upload-premium {
    background: white;
    border: 1px solid var(--border);
    padding: 10px 24px;
    border-radius: 40px;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--primary);
}

.btn-upload-premium:hover {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

/* Preview de archivo */
.file-preview-premium {
    margin-top: 20px;
}

.preview-card {
    display: flex;
    align-items: center;
    gap: 12px;
    background: white;
    border: 1px solid var(--success);
    border-radius: 16px;
    padding: 12px 16px;
}

.preview-icon i {
    font-size: 1.8rem;
    color: var(--success);
}

.preview-info {
    flex: 1;
}

.file-name {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--dark);
}

.file-size {
    display: block;
    font-size: 0.65rem;
    color: var(--gray);
}

.preview-remove {
    background: none;
    border: none;
    cursor: pointer;
    color: var(--danger);
    transition: all 0.2s;
}

.preview-remove:hover {
    transform: scale(1.1);
}

/* Info modal */
.info-modal {
    background: #dbeafe;
    border-radius: 16px;
    padding: 16px;
    display: flex;
    gap: 12px;
}

.info-icon i {
    font-size: 1.2rem;
    color: var(--primary);
}

.info-text strong {
    display: block;
    font-size: 0.8rem;
    margin-bottom: 4px;
    color: #1e40af;
}

.info-text p {
    font-size: 0.7rem;
    margin: 0;
    color: #1e3a8a;
}

/* Footer del modal */
.modal-custom-footer {
    padding: 20px 28px;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    background: var(--light);
}

.btn-cancel-modal {
    background: white;
    border: 1px solid var(--border);
    padding: 10px 24px;
    border-radius: 40px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    color: var(--gray);
}

.btn-cancel-modal:hover {
    background: var(--border);
}

.btn-submit-modal {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border: none;
    padding: 10px 28px;
    border-radius: 40px;
    font-weight: 600;
    color: white;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
}

.btn-submit-modal:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102,126,234,0.4);
}

.btn-submit-modal:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Modal imagen completa */
.modal-imagen .modal-custom-container {
    padding: 40px;
}

.imagen-completa {
    max-width: 800px;
}

.imagen-completa-img {
    width: 100%;
    height: auto;
    border-radius: 16px;
}

.modal-custom-header.minimal {
    background: white;
    color: var(--dark);
    border-bottom: 1px solid var(--border);
    justify-content: space-between;
}

.modal-custom-header.minimal h5 {
    margin: 0;
    color: var(--dark);
}

.imagen-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    margin-top: 20px;
}

.btn-download, .btn-close {
    padding: 10px 24px;
    border-radius: 30px;
    font-weight: 600;
    cursor: pointer;
    border: none;
}

.btn-download {
    background: var(--primary);
    color: white;
}

.btn-close {
    background: var(--light);
    color: var(--gray);
}

/* Responsive */
@media (max-width: 768px) {
    .ficha-pago-wrapper {
        padding: 12px;
    }
    
    .ficha-header {
        padding: 24px;
    }
    
    .info-pago-grid {
        grid-template-columns: 1fr;
    }
    
    .acciones-grid {
        grid-template-columns: 1fr;
    }
    
    .instrucciones-grid {
        grid-template-columns: 1fr;
    }
    
    .datos-bancarios-card {
        grid-template-columns: 1fr;
    }
    
    .subir-card {
        flex-direction: column;
        text-align: center;
    }
    
    .info-pago-section,
    .ficha-imagen-section,
    .acciones-section,
    .subir-comprobante-section,
    .instrucciones-section,
    .datos-bancarios-section {
        padding: 20px;
    }
    
    .modal-custom-body {
        padding: 20px;
    }
    
    .modal-custom-header {
        padding: 20px;
    }
    
    .modal-custom-footer {
        padding: 16px 20px;
    }
}

/* Dark mode */
body.dark-mode .ficha-pago-card {
    background: #1e293b;
}

body.dark-mode .section-title span {
    color: #f1f5f9;
}

body.dark-mode .info-value {
    color: #e2e8f0;
}

body.dark-mode .referencia-box {
    background: #0f172a;
}

body.dark-mode .copy-referencia-btn {
    background: #1e293b;
    border-color: #334155;
    color: #e2e8f0;
}

body.dark-mode .accion-card {
    background: #0f172a;
    border-color: #334155;
}

body.dark-mode .accion-info h4 {
    color: #f1f5f9;
}

body.dark-mode .instruccion-content h5 {
    color: #f1f5f9;
}

body.dark-mode .datos-bancarios-card {
    background: #0f172a;
}

body.dark-mode .dato-value {
    color: #e2e8f0;
}

body.dark-mode .back-button {
    background: #0f172a;
    border-top-color: #334155;
}

body.dark-mode .btn-secondary-custom {
    background: #1e293b;
    border-color: #334155;
    color: #e2e8f0;
}

body.dark-mode .modal-custom-content {
    background: #1e293b;
}

body.dark-mode .referencia-modal {
    background: #0f172a;
}

body.dark-mode .referencia-modal-value code {
    background: #1e293b;
    border-color: #334155;
    color: #e2e8f0;
}

body.dark-mode .copy-ref-modal {
    background: #1e293b;
    border-color: #334155;
    color: #e2e8f0;
}

body.dark-mode .upload-area-premium {
    background: #0f172a;
    border-color: #334155;
}

body.dark-mode .upload-area-premium h6 {
    color: #e2e8f0;
}

body.dark-mode .btn-upload-premium {
    background: #1e293b;
    border-color: #334155;
    color: #a78bfa;
}

body.dark-mode .preview-card {
    background: #0f172a;
    border-color: #10b981;
}

body.dark-mode .file-name {
    color: #e2e8f0;
}

body.dark-mode .modal-custom-footer {
    background: #0f172a;
    border-top-color: #334155;
}

body.dark-mode .btn-cancel-modal {
    background: #1e293b;
    border-color: #334155;
    color: #e2e8f0;
}

body.dark-mode .modal-custom-header.minimal {
    background: #1e293b;
    border-bottom-color: #334155;
}

body.dark-mode .modal-custom-header.minimal h5 {
    color: #f1f5f9;
}
</style>

<script>
function copiarReferencia() {
    const referencia = document.getElementById('referenciaCode').innerText;
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
    });
}

function copiarReferenciaModal() {
    const referencia = document.querySelector('.referencia-modal-value code').innerText;
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
    });
}

function descargarFicha() {
    const link = document.createElement('a');
    link.href = '{{ asset("images/FICHA_PAGO.jpeg") }}';
    link.download = 'ficha_pago_sains.jpg';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    Swal.fire({
        icon: 'success',
        title: 'Descargando...',
        text: 'La ficha de pago se está descargando',
        showConfirmButton: false,
        timer: 2000,
        background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
        color: document.body.classList.contains('dark-mode') ? '#f1f5f9' : '#1e293b'
    });
}

function verImagenCompleta() {
    const modal = document.getElementById('modalImagenCompleta');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarImagenCompleta() {
    const modal = document.getElementById('modalImagenCompleta');
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

function abrirModalComprobante() {
    const modal = document.getElementById('modalComprobante');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarModalComprobante() {
    const modal = document.getElementById('modalComprobante');
    modal.style.display = 'none';
    document.body.style.overflow = '';
    limpiarArchivoPremium();
}

function limpiarArchivoPremium() {
    document.getElementById('comprobanteInputPremium').value = '';
    document.getElementById('filePreviewPremium').style.display = 'none';
    document.getElementById('uploadAreaPremium').style.display = 'block';
}

// Preview de archivo
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('comprobanteInputPremium');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('fileNamePremium').innerText = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                document.getElementById('fileSizePremium').innerText = fileSize + ' MB';
                document.getElementById('filePreviewPremium').style.display = 'block';
                document.getElementById('uploadAreaPremium').style.display = 'none';
            }
        });
    }
});

// Cerrar modales con click en overlay
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-custom-overlay')) {
        cerrarModalComprobante();
        cerrarImagenCompleta();
    }
});

// Loading en envío
document.querySelector('#modalComprobante form')?.addEventListener('submit', function(e) {
    const btn = document.getElementById('btnEnviarComprobante');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando...';
});
</script>
@endsection