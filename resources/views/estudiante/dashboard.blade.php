@extends('estudiante.layouts.app')

@section('title', 'Dashboard | SAINS')

@section('content')
<div class="dashboard-container">
    
    <!-- ========== ALERTAS ========== -->
    
    <!-- Alerta de plan inactivo -->
    @if(isset($estudiante) && $estudiante && !$estudiante->plan_activo)
    <div class="alert-card alert-warning-card mb-4">
        <div class="alert-card-content">
            <div class="alert-icon">
                <i class="fas fa-lock"></i>
            </div>
            <div class="alert-message">
                <h6>Acceso restringido</h6>
                <p>Necesitas adquirir el plan SAINS para acceder a todo el contenido educativo.</p>
            </div>
            <button class="btn-alert" onclick="window.location.href='{{ route('estudiante.checkout') }}'">
                <i class="fas fa-shopping-cart me-2"></i> Adquirir plan ($800)
            </button>
        </div>
    </div>
    
    <!-- Alerta de pagos pendientes -->
    @php
        $pagosPendientes = \App\Models\Pago::where('alumno_pago', $estudiante->id)
                            ->where('estatus', 'pendiente')
                            ->orderBy('fecha_pago', 'desc')
                            ->get();
    @endphp
    
    @if($pagosPendientes->count() > 0)
    <div class="alert-card alert-info-card mb-4">
        <div class="alert-card-content">
            <div class="alert-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="alert-message">
                <h6>Tienes pagos pendientes de validación</h6>
                <p>Tus comprobantes están siendo revisados por nuestro equipo.</p>
            </div>
            <button class="btn-alert" onclick="verMisPagos()">
                <i class="fas fa-eye me-2"></i> Ver mis pagos
            </button>
        </div>
    </div>
    @endif
    @endif

    <!-- Alerta de foto de perfil -->
    @if(isset($estudiante) && $estudiante && $estudiante->plan_activo && (!$tieneFoto || !$estudiante->foto))
    <div class="alert-card alert-photo-card mb-4" id="alertaFoto">
        <div class="alert-card-content">
            <div class="alert-icon">
                <i class="fas fa-camera"></i>
            </div>
            <div class="alert-message">
                <h6>¡Completa tu perfil!</h6>
                <p>Sube una foto para personalizar tu cuenta</p>
            </div>
            <div>
                <button class="btn-alert btn-alert-primary" onclick="mostrarModalSubirFoto()">
                    <i class="fas fa-upload me-2"></i> Subir foto
                </button>
                <button class="btn-alert-close" onclick="cerrarAlertaFoto()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- ========== HERO SECTION MEJORADA ========== -->
    <div class="hero-section-modern mb-5">
        <div class="hero-bg-pattern"></div>
        <div class="hero-content-modern">
            <div class="hero-left-modern">
                <div class="hero-badge-modern">
                    @if(isset($estudiante) && $estudiante && $estudiante->plan_activo)
                        <i class="fas fa-crown me-2"></i> Plan Premium Activo
                    @else
                        <i class="fas fa-graduation-cap me-2"></i> Estudiante Registrado
                    @endif
                </div>
                <h1 class="hero-title-modern">
                    ¡Bienvenido, <span class="hero-name">{{ isset($estudiante) ? trim($estudiante->nombre . ' ' . ($estudiante->apellido ?? '')) : 'Estudiante' }}</span>! 👋
                </h1>
                <p class="hero-subtitle-modern">
                    @if(isset($estudiante) && $estudiante && $estudiante->plan_activo)
                        Prepárate para ingresar a la universidad con nuestro simulador de examen. 
                        Tu éxito académico comienza aquí.
                    @else
                        Adquiere el plan SAINS y desbloquea todo el contenido educativo
                    @endif
                </p>
                @if(isset($estudiante) && $estudiante && $estudiante->plan_activo)
                <div class="hero-stats-modern">
                    <div class="hero-stat-modern">
                        <span class="hero-stat-value-modern" id="heroExamenes">0</span>
                        <span class="hero-stat-label-modern">Exámenes</span>
                    </div>
                    <div class="hero-stat-divider-modern"></div>
                    <div class="hero-stat-modern">
                        <span class="hero-stat-value-modern" id="heroMejorPuntaje">0</span>
                        <span class="hero-stat-label-modern">Mejor puntaje</span>
                    </div>
                    <div class="hero-stat-divider-modern"></div>
                    <div class="hero-stat-modern">
                        <span class="hero-stat-value-modern" id="heroTiempoHoy">0</span>
                        <span class="hero-stat-label-modern">Tiempo de estudio</span>
                    </div>
                </div>
                @else
                <button class="hero-btn-modern" onclick="window.location.href='{{ route('estudiante.checkout') }}'">
                    <i class="fas fa-rocket me-2"></i> Comenzar ahora
                </button>
                @endif
            </div>
            <div class="hero-right-modern">
                <div class="hero-avatar-modern" onclick="mostrarModalSubirFoto()">
                    @if(isset($estudiante) && $estudiante && $estudiante->foto)
                        <img src="{{ Storage::url($estudiante->foto) }}" alt="Foto de perfil">
                    @else
                        <i class="fas fa-user-graduate"></i>
                    @endif
                    <div class="avatar-edit-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== CONTENIDO PARA PLAN ACTIVO ========== -->
    @if(isset($estudiante) && $estudiante && $estudiante->plan_activo)
    
    <!-- ========== ESTADÍSTICAS RÁPIDAS ========== -->
    <div class="stats-grid-modern mb-5">
        <div class="stat-card-modern">
            <div class="stat-icon-modern stat-icon-blue-modern">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info-modern">
                <h3 class="stat-value-modern" id="statExamenes">0</h3>
                <p class="stat-label-modern">Exámenes Realizados</p>
            </div>
        </div>
        <div class="stat-card-modern">
            <div class="stat-icon-modern stat-icon-green-modern">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-info-modern">
                <h3 class="stat-value-modern" id="statPromedio">0</h3>
                <p class="stat-label-modern">Promedio General</p>
            </div>
        </div>
        <div class="stat-card-modern">
            <div class="stat-icon-modern stat-icon-orange-modern">
                <i class="fas fa-trophy"></i>
            </div>
            <div class="stat-info-modern">
                <h3 class="stat-value-modern" id="statMejorPuntaje">0</h3>
                <p class="stat-label-modern">Mejor Puntaje</p>
            </div>
        </div>
        <div class="stat-card-modern">
            <div class="stat-icon-modern stat-icon-purple-modern">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info-modern">
                <h3 class="stat-value-modern" id="statTiempoHoy">0</h3>
                <p class="stat-label-modern">Tiempo de estudio</p>
            </div>
        </div>
    </div>

    <!-- ========== TARJETA DE META UNIVERSITARIA ========== -->
    <div class="feature-card-modern mb-5" id="metaUniversitariaCard">
        <div class="feature-card-header-modern">
            <div class="feature-icon-modern">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div>
                <h4>🎯 Mi Meta Universitaria</h4>
                <p>Seguimiento hacia la carrera de tus sueños</p>
            </div>
        </div>
        <div class="feature-card-body-modern" id="metaUniversitariaContent">
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-muted">Cargando tu progreso...</p>
            </div>
        </div>
    </div>

    <!-- ========== ACCESO RÁPIDO Y ÚLTIMOS EXÁMENES ========== -->
    <div class="row g-4 mb-5">
        <div class="col-lg-5">
            <div class="feature-card-modern quick-access-card">
                <div class="feature-card-header-modern">
                    <div class="feature-icon-modern">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div>
                        <h4>Acceso Rápido</h4>
                        <p>Comienza a practicar ahora</p>
                    </div>
                </div>
                <div class="feature-card-body-modern">
                    <div class="quick-access-buttons">
                        <button class="quick-access-btn primary" onclick="iniciarSimulador()">
                            <i class="fas fa-play-circle me-2"></i>
                            <div>
                                <strong>Iniciar Simulacro</strong>
                                <small>Pon a prueba tus conocimientos</small>
                            </div>
                        </button>
                        <button class="quick-access-btn secondary" onclick="redirigirAExamenes()">
                            <i class="fas fa-history me-2"></i>
                            <div>
                                <strong>Ver Historial</strong>
                                <small>Todos tus exámenes</small>
                            </div>
                        </button>
                        <button class="quick-access-btn tertiary" onclick="window.location.href='{{ route('estudiante.perfil') }}'">
                            <i class="fas fa-user-edit me-2"></i>
                            <div>
                                <strong>Mi Perfil</strong>
                                <small>Actualiza tus datos</small>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="feature-card-modern history-card-modern">
                <div class="feature-card-header-modern">
                    <div class="feature-icon-modern">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4>Últimos Exámenes</h4>
                        <p>Tu actividad reciente</p>
                    </div>
                    <button class="btn-link-modern" onclick="redirigirAExamenes()">
                        Ver todos <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
                <div class="feature-card-body-modern">
                    <div id="listaUltimosExamenes" class="exams-list-modern">
                        <div class="skeleton-item-modern">
                            <div class="skeleton-icon-modern"></div>
                            <div class="skeleton-content-modern">
                                <div class="skeleton-line-modern"></div>
                                <div class="skeleton-line-modern short"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- ========== PLAN CARD PARA USUARIOS SIN PREMIUM ========== -->
    <div class="plan-card-modern">
        <div class="plan-badge">⭐ OFERTA ESPECIAL</div>
        <div class="plan-grid">
            <div class="plan-info">
                <h2>Plan SAINS Premium</h2>
                <div class="plan-price">
                    <span class="price-currency">$</span>
                    <span class="price-amount">800</span>
                    <span class="price-period">MXN</span>
                </div>
                <p class="plan-description">Pago único · Acceso de por vida · Actualizaciones gratuitas</p>
                <button class="plan-button" onclick="window.location.href='{{ route('estudiante.checkout') }}'">
                    <i class="fas fa-shopping-cart me-2"></i> Adquirir plan ahora
                </button>
            </div>
            <div class="plan-features-grid">
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Guías de estudio completas</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Simulador de examen ilimitado</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Videos educativos</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Estadísticas de rendimiento</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Soporte prioritario 24/7</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Certificado de finalización</span>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<!-- ========== MODALES ========== -->

<!-- Modal subir foto -->
<div class="modal fade" id="modalSubirFoto" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content-custom">
            <div class="modal-header-custom">
                <div class="modal-header-left">
                    <i class="fas fa-camera"></i>
                    <div>
                        <h5>Subir foto de perfil</h5>
                        <p>Elige una foto que te represente</p>
                    </div>
                </div>
                <button type="button" class="modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body-custom">
                <div class="foto-preview">
                    <div class="preview-circle" id="previewCircle">
                        <img id="previewImg" src="#" alt="Preview" style="display: none;">
                        <i class="fas fa-user" id="previewPlaceholder"></i>
                    </div>
                </div>
                <form id="formSubirFoto" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-area" id="uploadArea">
                        <input type="file" id="fotoInput" name="foto" accept="image/*" hidden>
                        <div class="upload-content">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <h6>Arrastra o haz clic para subir</h6>
                            <p>JPG, PNG o GIF (Máx. 2MB)</p>
                            <button type="button" class="btn-upload" onclick="document.getElementById('fotoInput').click()">
                                Seleccionar archivo
                            </button>
                        </div>
                    </div>
                    <div class="progress-upload" id="progressUpload" style="display: none;">
                        <div class="progress-info">
                            <span>Subiendo foto...</span>
                            <span id="progressPercent">0%</span>
                        </div>
                        <div class="progress-bar-custom">
                            <div class="progress-fill" id="progressFill"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer-custom">
                <button class="btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn-submit" onclick="subirFotoPerfil()" id="btnSubirFoto">Subir foto</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal ver foto -->
<div class="modal fade" id="modalVerFoto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content-custom text-center">
            <div class="modal-header-custom minimal">
                <button type="button" class="modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body-custom">
                <div class="foto-grande">
                    <img id="fotoActual" src="" alt="Foto perfil">
                </div>
                <div class="foto-actions">
                    <button class="action-btn primary" onclick="cambiarFoto()">Cambiar</button>
                    <button class="action-btn danger" onclick="eliminarFoto()">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Variables y estilos base */
.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0;
}

/* Alertas modernas */
.alert-card {
    border-radius: 20px;
    padding: 1rem 1.5rem;
    transition: all 0.3s ease;
}

.alert-warning-card {
    background: linear-gradient(135deg, #fef3c7, #fffbeb);
    border-left: 4px solid #f59e0b;
}

.alert-info-card {
    background: linear-gradient(135deg, #dbeafe, #eff6ff);
    border-left: 4px solid #3b82f6;
}

.alert-photo-card {
    background: linear-gradient(135deg, #e0e7ff, #eff6ff);
    border-left: 4px solid #667eea;
}

.alert-card-content {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.alert-icon {
    width: 48px;
    height: 48px;
    background: white;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}

.alert-warning-card .alert-icon i { color: #f59e0b; }
.alert-info-card .alert-icon i { color: #3b82f6; }
.alert-photo-card .alert-icon i { color: #667eea; }

.alert-message {
    flex: 1;
}

.alert-message h6 {
    font-size: 0.9rem;
    font-weight: 700;
    margin: 0 0 4px 0;
    color: #1f2937;
}

.alert-message p {
    font-size: 0.8rem;
    color: #6b7280;
    margin: 0;
}

.btn-alert {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-alert:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.btn-alert-primary {
    background: #667eea;
}

.btn-alert-close {
    background: rgba(0,0,0,0.05);
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    margin-left: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-alert-close:hover {
    background: rgba(0,0,0,0.1);
}

/* Hero section moderna */
.hero-section-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 28px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
}

.hero-bg-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.1;
}

.hero-content-modern {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 2rem;
    position: relative;
    z-index: 1;
}

.hero-left-modern {
    flex: 1;
}

.hero-badge-modern {
    display: inline-flex;
    align-items: center;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
    margin-bottom: 1rem;
}

.hero-title-modern {
    font-size: 2rem;
    font-weight: 800;
    color: white;
    margin-bottom: 0.75rem;
}

.hero-name {
    background: linear-gradient(120deg, #fff, #fbbf24);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.hero-subtitle-modern {
    font-size: 1rem;
    color: rgba(255,255,255,0.95);
    margin-bottom: 1.5rem;
    max-width: 500px;
    line-height: 1.5;
}

.hero-stats-modern {
    display: flex;
    gap: 2rem;
    margin-top: 1rem;
}

.hero-stat-modern {
    text-align: center;
}

.hero-stat-value-modern {
    display: block;
    font-size: 1.75rem;
    font-weight: 800;
    color: white;
}

.hero-stat-label-modern {
    font-size: 0.7rem;
    color: rgba(255,255,255,0.8);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.hero-stat-divider-modern {
    width: 1px;
    background: rgba(255,255,255,0.3);
}

.hero-btn-modern {
    background: white;
    color: #667eea;
    border: none;
    padding: 12px 28px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9rem;
    transition: all 0.3s;
    cursor: pointer;
}

.hero-btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.hero-right-modern {
    position: relative;
}

.hero-avatar-modern {
    width: 100px;
    height: 100px;
    background: rgba(255,255,255,0.2);
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    cursor: pointer;
    position: relative;
    transition: all 0.3s;
}

.hero-avatar-modern:hover {
    transform: scale(1.05);
}

.hero-avatar-modern:hover .avatar-edit-icon {
    opacity: 1;
}

.hero-avatar-modern i {
    font-size: 3rem;
    color: white;
}

.hero-avatar-modern img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-edit-icon {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0,0,0,0.6);
    padding: 8px;
    text-align: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.avatar-edit-icon i {
    font-size: 0.8rem;
    color: white;
}

/* Estadísticas grid */
.stats-grid-modern {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}

.stat-card-modern {
    background: white;
    border-radius: 20px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s;
    border: 1px solid #e2e8f0;
}

.stat-card-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.stat-icon-modern {
    width: 55px;
    height: 55px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
}

.stat-icon-blue-modern {
    background: rgba(102,126,234,0.1);
    color: #667eea;
}

.stat-icon-green-modern {
    background: rgba(16,185,129,0.1);
    color: #10b981;
}

.stat-icon-orange-modern {
    background: rgba(245,158,11,0.1);
    color: #f59e0b;
}

.stat-icon-purple-modern {
    background: rgba(139,92,246,0.1);
    color: #8b5cf6;
}

.stat-info-modern {
    flex: 1;
}

.stat-value-modern {
    font-size: 1.6rem;
    font-weight: 800;
    margin: 0;
    color: #1e293b;
}

.stat-label-modern {
    font-size: 0.7rem;
    color: #64748b;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Feature cards */
.feature-card-modern {
    background: white;
    border-radius: 24px;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
    transition: all 0.3s;
    height: 100%;
}

.feature-card-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.feature-card-header-modern {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.feature-icon-modern {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.feature-icon-modern i {
    font-size: 1.3rem;
    color: white;
}

.feature-card-header-modern h4 {
    font-size: 1rem;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
}

.feature-card-header-modern p {
    font-size: 0.7rem;
    color: #64748b;
    margin: 0;
}

.flex-grow-1 {
    flex: 1;
}

/* Quick access buttons */
.quick-access-buttons {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.quick-access-btn {
    display: flex;
    align-items: center;
    padding: 1rem;
    border: none;
    border-radius: 16px;
    cursor: pointer;
    transition: all 0.3s;
    text-align: left;
    width: 100%;
}

.quick-access-btn i {
    font-size: 1.5rem;
    margin-right: 1rem;
}

.quick-access-btn strong {
    display: block;
    font-size: 0.9rem;
    margin-bottom: 4px;
}

.quick-access-btn small {
    font-size: 0.7rem;
    opacity: 0.8;
}

.quick-access-btn.primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.quick-access-btn.primary:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(102,126,234,0.3);
}

.quick-access-btn.secondary {
    background: #f1f5f9;
    color: #1e293b;
}

.quick-access-btn.secondary:hover {
    background: #e2e8f0;
    transform: translateX(5px);
}

.quick-access-btn.tertiary {
    background: #fff;
    border: 1px solid #e2e8f0;
    color: #1e293b;
}

.quick-access-btn.tertiary:hover {
    background: #f8fafc;
    transform: translateX(5px);
    border-color: #667eea;
}

/* Exams list */
.exams-list-modern {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.exam-item-modern {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 16px;
    cursor: pointer;
    transition: all 0.3s;
    border: 1px solid #e2e8f0;
}

.exam-item-modern:hover {
    background: white;
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.btn-link-modern {
    background: transparent;
    border: 1px solid #e2e8f0;
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: 600;
    transition: all 0.3s;
    cursor: pointer;
    color: #1e293b;
}

.btn-link-modern:hover {
    background: #f8fafc;
    border-color: #667eea;
    color: #667eea;
}

/* Skeleton loading */
.skeleton-item-modern {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 16px;
}

.skeleton-icon-modern {
    width: 40px;
    height: 40px;
    background: #e2e8f0;
    border-radius: 10px;
    animation: pulse 1.5s infinite;
}

.skeleton-content-modern {
    flex: 1;
}

.skeleton-line-modern {
    height: 12px;
    background: #e2e8f0;
    border-radius: 6px;
    margin-bottom: 8px;
    animation: pulse 1.5s infinite;
}

.skeleton-line-modern.short {
    width: 60%;
}

/* Meta universitaria styles */
.universidad-card-modern {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 16px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    color: white;
}

.universidad-info-modern {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.universidad-icon-modern {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}

.universidad-nombre-modern {
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.universidad-ubicacion-modern {
    font-size: 0.7rem;
    opacity: 0.9;
}

.universidad-badge-modern {
    background: rgba(255,255,255,0.2);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.carrera-progreso-card-modern {
    border-radius: 16px;
    padding: 1.25rem;
    margin-bottom: 1rem;
}

.carrera-progreso-card-modern.cumple { background: #d1fae5; border-left: 4px solid #10b981; }
.carrera-progreso-card-modern.cerca { background: #fed7aa; border-left: 4px solid #f59e0b; }
.carrera-progreso-card-modern.lejos { background: #fee2e2; border-left: 4px solid #ef4444; }
.carrera-progreso-card-modern.sin_requisito { background: #f1f5f9; border-left: 4px solid #64748b; }

.carrera-header-modern {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.carrera-titulo-modern { font-weight: 700; margin-bottom: 0.25rem; }
.carrera-requisito-modern { font-size: 0.7rem; color: #6b7280; }

.carrera-badge-modern {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.carrera-badge-modern.cumple { background: #10b98120; color: #10b981; }
.carrera-badge-modern.cerca { background: #f59e0b20; color: #f59e0b; }
.carrera-badge-modern.lejos { background: #ef444420; color: #ef4444; }

.progreso-container-modern { margin-bottom: 1rem; }
.progreso-header-modern {
    display: flex;
    justify-content: space-between;
    font-size: 0.7rem;
    margin-bottom: 0.5rem;
}
.progreso-bar-modern {
    height: 8px;
    background: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
}
.progreso-fill-modern {
    height: 100%;
    border-radius: 10px;
    transition: width 0.5s ease;
}
.progreso-fill-modern.cumple { background: linear-gradient(90deg, #10b981, #059669); }
.progreso-fill-modern.cerca { background: linear-gradient(90deg, #f59e0b, #d97706); }
.progreso-fill-modern.lejos { background: linear-gradient(90deg, #ef4444, #dc2626); }

.mensaje-recomendacion-modern {
    background: rgba(0,0,0,0.05);
    border-radius: 12px;
    padding: 0.75rem;
    font-size: 0.75rem;
    margin-bottom: 1rem;
}

.btn-mejorar-modern {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    padding: 10px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.8rem;
    width: 100%;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-mejorar-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102,126,234,0.3);
}

.felicitacion-modern {
    text-align: center;
    padding: 1rem;
    background: rgba(16,185,129,0.1);
    border-radius: 12px;
    color: #10b981;
    font-weight: 600;
    font-size: 0.8rem;
}

/* Resources section */
.resources-section {
    margin-top: 2rem;
}

.resources-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

.resource-card {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s;
    border: 1px solid #e2e8f0;
}

.resource-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    border-color: #667eea;
}

.resource-card i {
    font-size: 2rem;
    color: #667eea;
    margin-bottom: 1rem;
}

.resource-card h5 {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #1e293b;
}

.resource-card p {
    font-size: 0.75rem;
    color: #64748b;
    margin-bottom: 1rem;
}

.resource-link {
    color: #667eea;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s;
}

.resource-link:hover {
    color: #764ba2;
    text-decoration: underline;
}

/* Plan card */
.plan-card-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 28px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
}

.plan-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 600;
    color: white;
}

.plan-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
}

.plan-info {
    flex: 1;
}

.plan-info h2 {
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
    margin-bottom: 0.5rem;
}

.plan-price {
    margin-bottom: 0.5rem;
}

.price-currency {
    font-size: 1rem;
    color: white;
    vertical-align: top;
}

.price-amount {
    font-size: 3rem;
    font-weight: 800;
    color: white;
}

.price-period {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.7);
}

.plan-description {
    color: rgba(255,255,255,0.8);
    margin-bottom: 1.5rem;
}

.plan-button {
    background: white;
    color: #667eea;
    border: none;
    padding: 12px 28px;
    border-radius: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
}

.plan-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.plan-features-grid {
    flex: 1;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: white;
    font-size: 0.85rem;
}

.feature-item i {
    font-size: 0.9rem;
}

/* Modales */
.modal-content-custom {
    background: white;
    border-radius: 24px;
    overflow: hidden;
}

.modal-header-custom {
    background: linear-gradient(135deg, #667eea, #764ba2);
    padding: 1.25rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.modal-header-left i {
    font-size: 1.5rem;
    color: white;
}

.modal-header-left h5 {
    font-size: 1rem;
    font-weight: 700;
    margin: 0;
    color: white;
}

.modal-header-left p {
    font-size: 0.7rem;
    margin: 0;
    color: rgba(255,255,255,0.8);
}

.modal-close {
    background: rgba(255,255,255,0.2);
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    color: white;
    cursor: pointer;
    transition: all 0.3s;
}

.modal-close:hover {
    background: rgba(255,255,255,0.3);
}

.modal-body-custom {
    padding: 1.5rem;
}

.modal-footer-custom {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

.foto-preview {
    display: flex;
    justify-content: center;
    margin-bottom: 1.5rem;
}

.preview-circle {
    width: 100px;
    height: 100px;
    background: #f1f5f9;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.preview-circle i {
    font-size: 2.5rem;
    color: #94a3b8;
}

.preview-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.upload-area {
    border: 2px dashed #cbd5e1;
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
}

.upload-area:hover {
    border-color: #667eea;
    background: #f8fafc;
}

.upload-content i {
    font-size: 2rem;
    color: #94a3b8;
    margin-bottom: 0.5rem;
}

.upload-content h6 {
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.upload-content p {
    font-size: 0.7rem;
    color: #94a3b8;
    margin-bottom: 1rem;
}

.btn-upload {
    background: #f1f5f9;
    border: none;
    padding: 8px 20px;
    border-radius: 10px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-upload:hover {
    background: #e2e8f0;
}

.progress-upload {
    margin-top: 1rem;
}

.progress-info {
    display: flex;
    justify-content: space-between;
    font-size: 0.7rem;
    margin-bottom: 0.5rem;
}

.progress-bar-custom {
    height: 6px;
    background: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea, #764ba2);
    width: 0%;
    transition: width 0.3s ease;
}

.btn-cancel {
    background: #f1f5f9;
    border: none;
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-cancel:hover {
    background: #e2e8f0;
}

.btn-submit {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102,126,234,0.3);
}

.foto-grande img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    margin: 1rem auto;
}

.foto-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 1rem;
}

.action-btn {
    padding: 8px 20px;
    border-radius: 10px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
}

.action-btn.primary {
    background: #667eea;
    color: white;
}

.action-btn.primary:hover {
    background: #5a67d8;
}

.action-btn.danger {
    background: #ef4444;
    color: white;
}

.action-btn.danger:hover {
    background: #dc2626;
}

/* Animaciones */
@keyframes pulse {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 1; }
}

/* Responsive */
@media (max-width: 1024px) {
    .stats-grid-modern {
        grid-template-columns: repeat(2, 1fr);
    }
    .resources-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .stats-grid-modern {
        grid-template-columns: 1fr;
    }
    .hero-title-modern {
        font-size: 1.5rem;
    }
    .hero-stats-modern {
        gap: 1rem;
    }
    .hero-stat-value-modern {
        font-size: 1.25rem;
    }
    .resources-grid {
        grid-template-columns: 1fr;
    }
    .hero-right-modern {
        display: none;
    }
    .plan-features-grid {
        grid-template-columns: 1fr;
    }
}

/* Dark mode support */
body.dark-mode .stat-card-modern,
body.dark-mode .feature-card-modern,
body.dark-mode .resource-card,
body.dark-mode .modal-content-custom {
    background: #1e293b;
    border-color: #334155;
}

body.dark-mode .stat-value-modern,
body.dark-mode .feature-card-header-modern h4,
body.dark-mode .resource-card h5 {
    color: #f1f5f9;
}

body.dark-mode .stat-label-modern,
body.dark-mode .feature-card-header-modern p,
body.dark-mode .resource-card p {
    color: #94a3b8;
}

body.dark-mode .quick-access-btn.secondary {
    background: #334155;
    color: #f1f5f9;
}

body.dark-mode .quick-access-btn.tertiary {
    background: #1e293b;
    border-color: #334155;
    color: #f1f5f9;
}

body.dark-mode .exam-item-modern {
    background: #0f172a;
    border-color: #334155;
}

body.dark-mode .exam-item-modern:hover {
    background: #1e293b;
}

body.dark-mode .upload-area {
    border-color: #334155;
}

body.dark-mode .upload-area:hover {
    background: #0f172a;
}

body.dark-mode .btn-cancel {
    background: #334155;
    color: #f1f5f9;
}

body.dark-mode .skeleton-item-modern {
    background: #0f172a;
}

body.dark-mode .skeleton-icon-modern,
body.dark-mode .skeleton-line-modern {
    background: #334155;
}

body.dark-mode .carrera-progreso-card-modern {
    background: #0f172a;
}

body.dark-mode .carrera-requisito-modern {
    color: #94a3b8;
}

body.dark-mode .progreso-bar-modern {
    background: #334155;
}

body.dark-mode .mensaje-recomendacion-modern {
    background: rgba(255,255,255,0.05);
}
</style>
@endsection

@push('scripts')
<script>
// Funciones globales
function redirigirAExamenes() {
    window.location.href = '{{ route("estudiante.examenes") }}';
}

function verMisPagos() {
    window.location.href = '{{ route("estudiante.mis-pagos") }}';
}

function verDetalleExamen(examenId) {
    window.location.href = '/estudiante/resultados/' + examenId;
}

function iniciarSimulador() {
    @if(isset($estudiante) && $estudiante && $estudiante->plan_activo)
        window.location.href = '{{ route("estudiante.simulador") }}';
    @else
        Swal.fire({ 
            title: 'Plan requerido', 
            text: 'Necesitas el plan SAINS para acceder al simulador', 
            icon: 'warning', 
            confirmButtonColor: '#667eea' 
        });
    @endif
}

function cerrarAlertaFoto() {
    var alerta = document.getElementById('alertaFoto');
    if (alerta) alerta.style.display = 'none';
    localStorage.setItem('alertaFotoCerrada', 'true');
}

function mostrarModalSubirFoto() {
    var form = document.getElementById('formSubirFoto');
    if (form) form.reset();
    var previewImg = document.getElementById('previewImg');
    var previewPlaceholder = document.getElementById('previewPlaceholder');
    if (previewImg) previewImg.style.display = 'none';
    if (previewPlaceholder) previewPlaceholder.style.display = 'flex';
    if (window.modalSubirFoto) window.modalSubirFoto.show();
}

function subirFotoPerfil() {
    var file = document.getElementById('fotoInput').files[0];
    if (!file) return Swal.fire('Error', 'Selecciona una foto', 'error');
    if (!file.type.match('image.*')) return Swal.fire('Error', 'Solo imágenes', 'error');
    if (file.size > 2 * 1024 * 1024) return Swal.fire('Error', 'Máximo 2MB', 'error');
    
    var formData = new FormData();
    formData.append('foto', file);
    var btn = document.getElementById('btnSubirFoto');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Subiendo...';
    
    fetch('/estudiante/subir-foto', {
        method: 'POST',
        body: formData,
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            Swal.fire('¡Éxito!', data.message, 'success');
            setTimeout(function() { location.reload(); }, 1500);
        } else {
            Swal.fire('Error', data.message, 'error');
            btn.disabled = false;
            btn.innerHTML = 'Subir foto';
        }
    })
    .catch(function() {
        Swal.fire('Error', 'Error al subir la foto', 'error');
        btn.disabled = false;
        btn.innerHTML = 'Subir foto';
    });
}

function cambiarFoto() {
    if (window.modalVerFoto) window.modalVerFoto.hide();
    mostrarModalSubirFoto();
}

function eliminarFoto() {
    Swal.fire({
        title: '¿Eliminar foto?',
        text: '¿Estás seguro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#667eea',
        confirmButtonText: 'Sí, eliminar'
    }).then(function(result) {
        if (result.isConfirmed) {
            fetch('/estudiante/eliminar-foto', {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.success) {
                    Swal.fire('¡Éxito!', data.message, 'success');
                    setTimeout(function() { location.reload(); }, 1500);
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(function() { Swal.fire('Error', 'Error al eliminar', 'error'); });
        }
    });
}

function formatearTiempo(minutos) {
    if (!minutos || minutos === 0) return '0 min';
    var horas = Math.floor(minutos / 60);
    var mins = minutos % 60;
    if (horas > 0 && mins > 0) return horas + 'h ' + mins + 'min';
    if (horas > 0) return horas + 'h';
    return mins + 'min';
}

async function cargarMetaUniversitaria() {
    var container = document.getElementById('metaUniversitariaContent');
    if (!container) return;
    
    try {
        var response = await fetch('/estudiante/recomendaciones');
        var data = await response.json();
        
        if (!data.success) {
            container.innerHTML = '<div class="alert alert-warning text-center py-4">Error al cargar tu progreso</div>';
            return;
        }
        
        if (!data.tiene_universidad_interes) {
            container.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-building fs-1 text-muted mb-3 d-block"></i>
                    <p class="mb-3">No has seleccionado una universidad de interés</p>
                    <button class="btn-meta" onclick="window.location.href='{{ route('estudiante.perfil') }}'" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 10px 24px; border-radius: 12px;">
                        <i class="fas fa-edit me-2"></i>Configurar mi meta
                    </button>
                </div>
            `;
            return;
        }
        
        if (data.total_examenes === 0) {
            container.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-chart-line fs-1 text-muted mb-3 d-block"></i>
                    <p class="mb-3">Realiza tu primer simulador para ver tu progreso</p>
                    <button class="btn-meta" onclick="iniciarSimulador()" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 10px 24px; border-radius: 12px;">
                        <i class="fas fa-play me-2"></i>Comenzar Simulador
                    </button>
                </div>
            `;
            return;
        }
        
        var uni = data.universidad_interes;
        var carrera = data.carrera_interes;
        
        if (!carrera) {
            container.innerHTML = '<div class="text-center py-4 text-muted">No hay información de carrera disponible</div>';
            return;
        }
        
        var estadoClass = carrera.estado;
        var badgeText = '';
        var progressClass = '';
        
        if (estadoClass === 'cumple') {
            badgeText = '¡CUMPLES EL REQUISITO!';
            progressClass = 'cumple';
        } else if (estadoClass === 'cerca') {
            badgeText = '¡ESTÁS CERCA! (' + carrera.diferencia_faltante + ' pts)';
            progressClass = 'cerca';
        } else if (estadoClass === 'lejos') {
            badgeText = 'NECESITAS MEJORAR (' + carrera.diferencia_faltante + ' pts)';
            progressClass = 'lejos';
        } else {
            badgeText = 'SIN REQUISITO';
            progressClass = 'sin_requisito';
        }
        
        container.innerHTML = `
            <div class="universidad-card-modern">
                <div class="universidad-info-modern">
                    <div class="universidad-icon-modern">
                        <i class="fas fa-university"></i>
                    </div>
                    <div>
                        <div class="universidad-nombre-modern">${uni.nombre}</div>
                        <div class="universidad-ubicacion-modern"><i class="fas fa-map-marker-alt me-1"></i>${uni.ubicacion}</div>
                    </div>
                </div>
                <div class="universidad-badge-modern">
                    <i class="fas fa-flag-checkered me-1"></i>Tu meta
                </div>
            </div>
            
            <div class="carrera-progreso-card-modern ${estadoClass}">
                <div class="carrera-header-modern">
                    <div>
                        <div class="carrera-titulo-modern">🎓 ${carrera.nombre}</div>
                        <div class="carrera-requisito-modern">
                            <i class="fas fa-tachometer-alt me-1"></i>
                            Requisito: <strong>${carrera.calificacion_minima_formateada}</strong>
                        </div>
                    </div>
                    <div class="carrera-badge-modern ${estadoClass}">
                        <i class="fas ${carrera.icono} me-1"></i>${badgeText}
                    </div>
                </div>
                
                ${carrera.calificacion_minima > 0 ? `
                    <div class="progreso-container-modern">
                        <div class="progreso-header-modern">
                            <span>Tu progreso</span>
                            <span class="fw-bold">${carrera.porcentaje_progreso}%</span>
                        </div>
                        <div class="progreso-bar-modern">
                            <div class="progreso-fill-modern ${progressClass}" style="width: ${carrera.porcentaje_progreso}%"></div>
                        </div>
                        <div class="progreso-header-modern mt-1">
                            <span>Tu calificación: ${data.promedio}%</span>
                            <span>Meta: ${carrera.calificacion_minima}%</span>
                        </div>
                    </div>
                ` : ''}
                
                <div class="mensaje-recomendacion-modern">
                    <i class="fas ${carrera.icono} me-2"></i>
                    ${carrera.recomendacion}
                </div>
                
                ${estadoClass !== 'cumple' ? `
                    <button class="btn-mejorar-modern" onclick="iniciarSimulador()">
                        <i class="fas fa-chart-line me-2"></i>Mejorar mi calificación
                    </button>
                ` : `
                    <div class="felicitacion-modern">
                        <i class="fas fa-trophy me-2"></i>¡Estás preparado para esta carrera!
                    </div>
                `}
            </div>
        `;
        
    } catch (error) {
        console.error('Error:', error);
        container.innerHTML = '<div class="alert alert-danger text-center py-4">Error al cargar tu progreso</div>';
    }
}

async function cargarTodosLosDatos() {
    var token = document.querySelector('meta[name="csrf-token"]').content;
    var headers = { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' };
    
    await cargarMetaUniversitaria();
    
    try {
        var res = await fetch('/estudiante/api/estadisticas', { headers: headers });
        var data = await res.json();
        if (data.success) {
            if (document.getElementById('statExamenes')) document.getElementById('statExamenes').innerText = data.total_examenes || 0;
            if (document.getElementById('statPromedio')) document.getElementById('statPromedio').innerText = (data.promedio || 0) + '%';
            if (document.getElementById('statMejorPuntaje')) document.getElementById('statMejorPuntaje').innerText = data.mejor_puntaje || 0;
            if (document.getElementById('heroExamenes')) document.getElementById('heroExamenes').innerText = data.total_examenes || 0;
            if (document.getElementById('heroMejorPuntaje')) document.getElementById('heroMejorPuntaje').innerText = data.mejor_puntaje || 0;
        }
    } catch (error) { console.error('Error estadísticas:', error); }
    
    try {
        var res = await fetch('/estudiante/api/ultimos-examenes', { headers: headers });
        var data = await res.json();
        var container = document.getElementById('listaUltimosExamenes');
        
        if (container) {
            if (data.success && data.examenes && data.examenes.length > 0) {
                var examsHtml = '';
                for (var i = 0; i < data.examenes.length; i++) {
                    var ex = data.examenes[i];
                    var esAprobado = ex.calificacion >= 70;
                    var badgeClass = esAprobado ? 'bg-success' : 'bg-warning';
                    var badgeText = esAprobado ? 'Aprobado' : 'Por mejorar';
                    var fecha = ex.fecha || 'Fecha no disponible';
                    
                    examsHtml += `
                        <div class="exam-item-modern" onclick="verDetalleExamen(${ex.id})">
                            <div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                                    <i class="fas fa-calendar-alt" style="color: #667eea; font-size: 12px;"></i>
                                    <strong style="font-size: 0.85rem;">${fecha}</strong>
                                </div>
                                <small style="color: #6b7280;">Calificación: ${ex.calificacion}%</small>
                            </div>
                            <span class="badge ${badgeClass}" style="padding: 6px 12px; border-radius: 20px; font-size: 0.7rem;">
                                ${badgeText}
                            </span>
                        </div>
                    `;
                }
                container.innerHTML = examsHtml;
            } else {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fs-1 text-muted mb-2 d-block"></i>
                        <p class="text-muted">No hay exámenes registrados</p>
                        <button class="btn btn-sm btn-primary mt-2" onclick="iniciarSimulador()">
                            <i class="fas fa-play me-1"></i>Comenzar Simulador
                        </button>
                    </div>
                `;
            }
        }
    } catch (error) { 
        console.error('Error exámenes:', error);
        var container = document.getElementById('listaUltimosExamenes');
        if (container) {
            container.innerHTML = '<div class="text-center py-4 text-danger">Error al cargar los exámenes</div>';
        }
    }
    
    try {
        var res = await fetch('/estudiante/api/tiempo-estudio', { headers: headers });
        var data = await res.json();
        if (data.success) {
            var tiempo = formatearTiempo(data.hoy?.minutos || 0);
            if (document.getElementById('statTiempoHoy')) document.getElementById('statTiempoHoy').innerText = tiempo;
            if (document.getElementById('heroTiempoHoy')) document.getElementById('heroTiempoHoy').innerText = tiempo;
        }
    } catch (error) { console.error('Error tiempo:', error); }
}

function iniciarHeartbeat() {
    var token = document.querySelector('meta[name="csrf-token"]').content;
    if (!token) return;
    if (window.heartbeatIntervalGlobal) clearInterval(window.heartbeatIntervalGlobal);
    window.heartbeatIntervalGlobal = setInterval(function() {
        fetch('/estudiante/heartbeat', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ timestamp: Date.now() })
        }).catch(function(err) { console.error('Heartbeat error:', err); });
    }, 60000);
}

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    var modalSubirFotoElement = document.getElementById('modalSubirFoto');
    var modalVerFotoElement = document.getElementById('modalVerFoto');
    if (modalSubirFotoElement) window.modalSubirFoto = new bootstrap.Modal(modalSubirFotoElement);
    if (modalVerFotoElement) window.modalVerFoto = new bootstrap.Modal(modalVerFotoElement);
    
    @if(isset($estudiante) && $estudiante && $estudiante->plan_activo)
        cargarTodosLosDatos();
        iniciarHeartbeat();
    @endif
    
    var fotoInput = document.getElementById('fotoInput');
    var previewImg = document.getElementById('previewImg');
    var previewPlaceholder = document.getElementById('previewPlaceholder');
    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    if (previewImg) previewImg.src = event.target.result;
                    if (previewImg) previewImg.style.display = 'block';
                    if (previewPlaceholder) previewPlaceholder.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    var alertaFoto = document.getElementById('alertaFoto');
    if (alertaFoto && localStorage.getItem('alertaFotoCerrada') === 'true') {
        alertaFoto.style.display = 'none';
    }
});
</script>
@endpush