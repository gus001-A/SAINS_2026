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

    <!-- ========== HERO SECTION ========== -->
    <div class="hero-section mb-5">
        <div class="hero-content">
            <div class="hero-left">
                <div class="hero-badge">
                    @if(isset($estudiante) && $estudiante && $estudiante->plan_activo)
                        <i class="fas fa-crown me-2"></i> Plan Premium Activo
                    @else
                        <i class="fas fa-graduation-cap me-2"></i> Estudiante Registrado
                    @endif
                </div>
                <h1 class="hero-title">
                    ¡Hola, {{ isset($estudiante) ? $estudiante->nombre : 'Estudiante' }}! 👋
                </h1>
                <p class="hero-subtitle">
                    @if(isset($estudiante) && $estudiante && $estudiante->plan_activo)
                        Prepárate para ingresar a la universidad con nuestro simulador de examen
                    @else
                        Adquiere el plan SAINS y desbloquea todo el contenido educativo
                    @endif
                </p>
                @if(isset($estudiante) && $estudiante && $estudiante->plan_activo)
                <div class="hero-stats">
                    <div class="hero-stat">
                        <span class="hero-stat-value" id="heroExamenes">0</span>
                        <span class="hero-stat-label">Exámenes</span>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat">
                        <span class="hero-stat-value" id="heroMejorPuntaje">0</span>
                        <span class="hero-stat-label">Mejor puntaje</span>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat">
                        <span class="hero-stat-value" id="heroTiempoHoy">0</span>
                        <span class="hero-stat-label">Tiempo de estudio</span>
                    </div>
                </div>
                @else
                <button class="hero-btn" onclick="window.location.href='{{ route('estudiante.checkout') }}'">
                    <i class="fas fa-rocket me-2"></i> Comenzar ahora
                </button>
                @endif
            </div>
            <div class="hero-right">
                <div class="hero-avatar">
                    @if(isset($estudiante) && $estudiante && $estudiante->foto)
                        <img src="{{ Storage::url($estudiante->foto) }}" alt="Foto">
                    @else
                        <i class="fas fa-user-graduate"></i>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ========== CONTENIDO PARA PLAN ACTIVO ========== -->
    @if(isset($estudiante) && $estudiante && $estudiante->plan_activo)
    
    <!-- Tarjetas de estadísticas -->
    <div class="stats-grid mb-5">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value" id="statExamenes">0</h3>
                <p class="stat-label">Exámenes Realizados</p>
            </div>
            <div class="stat-trend">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-green">
                <i class="fas fa-video"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value" id="statLecciones">0</h3>
                <p class="stat-label">Lecciones Completadas</p>
            </div>
            <div class="stat-trend">
                <i class="fas fa-play"></i>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-orange">
                <i class="fas fa-trophy"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value" id="statMejorPuntaje">0</h3>
                <p class="stat-label">Mejor Puntaje</p>
            </div>
            <div class="stat-trend">
                <i class="fas fa-star"></i>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-purple">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value" id="statTiempoHoy">0</h3>
                <p class="stat-label">Tiempo hoy</p>
            </div>
            <div class="stat-trend">
                <i class="fas fa-hourglass-half"></i>
            </div>
        </div>
    </div>

    <!-- Sección principal: Simulador + Rendimiento -->
    <div class="row g-4 mb-5">
        <div class="col-lg-6">
            <div class="feature-card simulator-card">
                <div class="feature-card-header">
                    <div class="feature-icon">
                        <i class="fas fa-play"></i>
                    </div>
                    <div>
                        <h4>Simulador de Examen</h4>
                        <p>Pon a prueba tus conocimientos</p>
                    </div>
                </div>
                <div class="feature-card-body">
                    <div class="simulator-info">
                        <div class="simulator-stats">
                            <div class="simulator-stat">
                                <i class="fas fa-brain"></i>
                                <span>Preguntas variadas</span>
                            </div>
                            <div class="simulator-stat">
                                <i class="fas fa-clock"></i>
                                <span>Sin límite de intentos</span>
                            </div>
                        </div>
                        <button class="btn-simulator" onclick="iniciarSimulador()">
                            <i class="fas fa-play me-2"></i> Comenzar simulacro
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="feature-card performance-card">
                <div class="feature-card-header">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h4>Rendimiento General</h4>
                        <p>Tu progreso y estadísticas</p>
                    </div>
                </div>
                <div class="feature-card-body">
                    <div class="progress-item mb-4">
                        <div class="progress-label">
                            <span>Progreso General</span>
                            <span id="progresoTexto">0%</span>
                        </div>
                        <div class="progress-bar-modern">
                            <div class="progress-fill" id="progresoBar" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="progress-item mb-4">
                        <div class="progress-label">
                            <span>Puntaje Promedio</span>
                            <span id="promedioTexto">0%</span>
                        </div>
                        <div class="progress-bar-modern">
                            <div class="progress-fill progress-fill-info" id="promedioBar" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="summary-cards">
                        <div class="summary-card summary-approved">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <h4 id="aprobadosTexto">0</h4>
                                <p>Exámenes Aprobados</p>
                            </div>
                        </div>
                        <div class="summary-card summary-pending">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div>
                                <h4 id="reprobadosTexto">0</h4>
                                <p>Por Mejorar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de exámenes -->
    <div class="feature-card history-card">
        <div class="feature-card-header">
            <div class="feature-icon">
                <i class="fas fa-history"></i>
            </div>
            <div class="flex-grow-1">
                <h4>Últimos Exámenes</h4>
                <p>Tu actividad reciente</p>
            </div>
            <button class="btn-link-custom" onclick="redirigirAExamenes()">
                Ver todos <i class="fas fa-arrow-right ms-2"></i>
            </button>
        </div>
        <div class="feature-card-body">
            <div id="listaUltimosExamenes" class="exams-list">
                <div class="skeleton-item">
                    <div class="skeleton-icon"></div>
                    <div class="skeleton-content">
                        <div class="skeleton-line"></div>
                        <div class="skeleton-line short"></div>
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
.dashboard-container { padding: 0; max-width: 1400px; margin: 0 auto; }
.alert-card { border-radius: 20px; padding: 1rem 1.5rem; }
.alert-warning-card { background: linear-gradient(135deg, #fef3c7, #fffbeb); border-left: 4px solid #f59e0b; }
.alert-info-card { background: linear-gradient(135deg, #dbeafe, #eff6ff); border-left: 4px solid #3b82f6; }
.alert-photo-card { background: linear-gradient(135deg, #e0e7ff, #eff6ff); border-left: 4px solid #667eea; }
.alert-card-content { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
.alert-icon { width: 48px; height: 48px; background: white; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
.alert-warning-card .alert-icon i { color: #f59e0b; }
.alert-info-card .alert-icon i { color: #3b82f6; }
.alert-photo-card .alert-icon i { color: #667eea; }
.alert-message { flex: 1; }
.alert-message h6 { font-size: 0.9rem; font-weight: 700; margin: 0 0 4px 0; color: #1f2937; }
.alert-message p { font-size: 0.8rem; color: #6b7280; margin: 0; }
.btn-alert { background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 8px 20px; border-radius: 10px; font-weight: 600; font-size: 0.8rem; cursor: pointer; }
.btn-alert-primary { background: #667eea; }
.btn-alert-close { background: rgba(0,0,0,0.05); border: none; width: 32px; height: 32px; border-radius: 8px; margin-left: 8px; cursor: pointer; }
.hero-section { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 28px; padding: 2rem; position: relative; overflow: hidden; }
.hero-section::before { content: ''; position: absolute; top: -30%; right: -10%; width: 60%; height: 160%; background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%); transform: rotate(15deg); }
.hero-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 2rem; position: relative; z-index: 1; }
.hero-left { flex: 1; }
.hero-badge { display: inline-flex; align-items: center; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); padding: 6px 16px; border-radius: 50px; font-size: 0.75rem; font-weight: 600; color: white; margin-bottom: 1rem; }
.hero-title { font-size: 2rem; font-weight: 800; color: white; margin-bottom: 0.75rem; }
.hero-subtitle { font-size: 1rem; color: rgba(255,255,255,0.9); margin-bottom: 1.5rem; max-width: 500px; }
.hero-stats { display: flex; gap: 2rem; margin-top: 1rem; }
.hero-stat { text-align: center; }
.hero-stat-value { display: block; font-size: 1.75rem; font-weight: 800; color: white; }
.hero-stat-label { font-size: 0.7rem; color: rgba(255,255,255,0.7); }
.hero-stat-divider { width: 1px; background: rgba(255,255,255,0.3); }
.hero-btn { background: white; color: #667eea; border: none; padding: 12px 28px; border-radius: 12px; font-weight: 700; font-size: 0.9rem; transition: all 0.3s; cursor: pointer; }
.hero-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
.hero-right { width: 100px; height: 100px; }
.hero-avatar { width: 100px; height: 100px; background: rgba(255,255,255,0.2); border-radius: 30px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
.hero-avatar i { font-size: 3rem; color: white; }
.hero-avatar img { width: 100%; height: 100%; object-fit: cover; }
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }
.stat-card { background: white; border-radius: 20px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; transition: all 0.3s; border: 1px solid #e2e8f0; }
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
.stat-icon { width: 55px; height: 55px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
.stat-icon-blue { background: rgba(102,126,234,0.1); color: #667eea; }
.stat-icon-green { background: rgba(16,185,129,0.1); color: #10b981; }
.stat-icon-orange { background: rgba(245,158,11,0.1); color: #f59e0b; }
.stat-icon-purple { background: rgba(139,92,246,0.1); color: #8b5cf6; }
.stat-info { flex: 1; }
.stat-value { font-size: 1.6rem; font-weight: 800; margin: 0; color: #1e293b; }
.stat-label { font-size: 0.7rem; color: #64748b; margin: 0; }
.stat-trend { width: 32px; height: 32px; background: #f1f5f9; border-radius: 10px; display: flex; align-items: center; justify-content: center; opacity: 0.6; }
.feature-card { background: white; border-radius: 24px; padding: 1.5rem; border: 1px solid #e2e8f0; transition: all 0.3s; height: 100%; }
.feature-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
.feature-card-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
.feature-icon { width: 48px; height: 48px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 14px; display: flex; align-items: center; justify-content: center; }
.feature-icon i { font-size: 1.3rem; color: white; }
.feature-card-header h4 { font-size: 1rem; font-weight: 700; margin: 0; color: #1e293b; }
.feature-card-header p { font-size: 0.7rem; color: #64748b; margin: 0; }
.flex-grow-1 { flex: 1; }
.simulator-info { text-align: center; }
.simulator-stats { display: flex; justify-content: center; gap: 2rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
.simulator-stat { text-align: center; }
.simulator-stat i { font-size: 1.3rem; color: #667eea; display: block; margin-bottom: 0.5rem; }
.simulator-stat span { font-size: 0.7rem; color: #64748b; }
.btn-simulator { background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 12px 32px; border-radius: 12px; font-weight: 700; width: 100%; transition: all 0.3s; cursor: pointer; }
.btn-simulator:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(102,126,234,0.3); }
.progress-item { margin-bottom: 1rem; }
.progress-label { display: flex; justify-content: space-between; font-size: 0.75rem; margin-bottom: 0.5rem; color: #64748b; }
.progress-bar-modern { height: 8px; background: #e2e8f0; border-radius: 10px; overflow: hidden; }
.progress-fill { height: 100%; background: linear-gradient(90deg, #667eea, #764ba2); border-radius: 10px; width: 0%; transition: width 0.5s ease; }
.progress-fill-info { background: linear-gradient(90deg, #3b82f6, #2563eb); }
.summary-cards { display: flex; gap: 1rem; margin-top: 1.5rem; flex-wrap: wrap; }
.summary-card { flex: 1; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; border-radius: 16px; }
.summary-approved { background: #d1fae5; }
.summary-approved i { color: #10b981; font-size: 1.5rem; }
.summary-pending { background: #fed7aa; }
.summary-pending i { color: #f59e0b; font-size: 1.5rem; }
.summary-card h4 { font-size: 1.2rem; font-weight: 800; margin: 0; color: #1f2937; }
.summary-card p { font-size: 0.65rem; margin: 0; color: #6b7280; }
.btn-link-custom { background: transparent; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 10px; font-size: 0.75rem; font-weight: 600; transition: all 0.3s; cursor: pointer; }
.btn-link-custom:hover { background: #f8fafc; border-color: #667eea; }
.exams-list { display: flex; flex-direction: column; gap: 0.75rem; }
.skeleton-item { display: flex; gap: 1rem; padding: 1rem; background: #f8fafc; border-radius: 16px; }
.skeleton-icon { width: 40px; height: 40px; background: #e2e8f0; border-radius: 10px; animation: pulse 1.5s infinite; }
.skeleton-content { flex: 1; }
.skeleton-line { height: 12px; background: #e2e8f0; border-radius: 6px; margin-bottom: 8px; animation: pulse 1.5s infinite; }
.skeleton-line.short { width: 60%; }
.plan-card-modern { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 28px; padding: 2rem; position: relative; overflow: hidden; }
.plan-badge { position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); padding: 6px 14px; border-radius: 50px; font-size: 0.7rem; font-weight: 600; color: white; }
.plan-grid { display: flex; flex-wrap: wrap; gap: 2rem; }
.plan-info { flex: 1; }
.plan-info h2 { font-size: 1.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem; }
.plan-price { margin-bottom: 0.5rem; }
.price-currency { font-size: 1rem; color: white; vertical-align: top; }
.price-amount { font-size: 3rem; font-weight: 800; color: white; }
.price-period { font-size: 0.8rem; color: rgba(255,255,255,0.7); }
.plan-description { color: rgba(255,255,255,0.8); margin-bottom: 1.5rem; }
.plan-button { background: white; color: #667eea; border: none; padding: 12px 28px; border-radius: 12px; font-weight: 700; cursor: pointer; transition: all 0.3s; }
.plan-button:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
.plan-features-grid { flex: 1; display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
.feature-item { display: flex; align-items: center; gap: 0.5rem; color: white; font-size: 0.85rem; }
.feature-item i { font-size: 0.9rem; }
.modal-content-custom { background: white; border-radius: 24px; overflow: hidden; }
.modal-header-custom { background: linear-gradient(135deg, #667eea, #764ba2); padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
.modal-header-left { display: flex; align-items: center; gap: 1rem; }
.modal-header-left i { font-size: 1.5rem; color: white; }
.modal-header-left h5 { font-size: 1rem; font-weight: 700; margin: 0; color: white; }
.modal-header-left p { font-size: 0.7rem; margin: 0; color: rgba(255,255,255,0.8); }
.modal-close { background: rgba(255,255,255,0.2); border: none; width: 32px; height: 32px; border-radius: 8px; color: white; cursor: pointer; }
.modal-body-custom { padding: 1.5rem; }
.modal-footer-custom { padding: 1rem 1.5rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 1rem; }
.foto-preview { display: flex; justify-content: center; margin-bottom: 1.5rem; }
.preview-circle { width: 100px; height: 100px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden; }
.preview-circle i { font-size: 2.5rem; color: #94a3b8; }
.preview-circle img { width: 100%; height: 100%; object-fit: cover; }
.upload-area { border: 2px dashed #cbd5e1; border-radius: 16px; padding: 2rem; text-align: center; cursor: pointer; transition: all 0.3s; }
.upload-area:hover { border-color: #667eea; background: #f8fafc; }
.upload-content i { font-size: 2rem; color: #94a3b8; margin-bottom: 0.5rem; }
.upload-content h6 { font-size: 0.9rem; margin-bottom: 0.25rem; }
.upload-content p { font-size: 0.7rem; color: #94a3b8; margin-bottom: 1rem; }
.btn-upload { background: #f1f5f9; border: none; padding: 8px 20px; border-radius: 10px; font-size: 0.8rem; cursor: pointer; }
.progress-upload { margin-top: 1rem; }
.progress-info { display: flex; justify-content: space-between; font-size: 0.7rem; margin-bottom: 0.5rem; }
.btn-cancel { background: #f1f5f9; border: none; padding: 10px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; }
.btn-submit { background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 10px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; }
.foto-grande img { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin: 1rem auto; }
.foto-actions { display: flex; gap: 1rem; justify-content: center; margin-top: 1rem; }
.action-btn { padding: 8px 20px; border-radius: 10px; font-weight: 600; border: none; cursor: pointer; }
.action-btn.primary { background: #667eea; color: white; }
.action-btn.danger { background: #ef4444; color: white; }
@keyframes pulse { 0%, 100% { opacity: 0.5; } 50% { opacity: 1; } }
@media (max-width: 1024px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 768px) { .stats-grid { grid-template-columns: 1fr; } .hero-title { font-size: 1.5rem; } .hero-stats { gap: 1rem; } .hero-stat-value { font-size: 1.25rem; } .simulator-stats { flex-direction: column; gap: 0.75rem; } .plan-features-grid { grid-template-columns: 1fr; } .hero-right { display: none; } }
body.dark-mode .stat-card, body.dark-mode .feature-card, body.dark-mode .modal-content-custom { background: #1e293b; border-color: #334155; }
body.dark-mode .stat-value, body.dark-mode .feature-card-header h4, body.dark-mode .modal-header-left h5 { color: #f1f5f9; }
body.dark-mode .stat-label, body.dark-mode .feature-card-header p, body.dark-mode .progress-label { color: #94a3b8; }
body.dark-mode .stat-trend, body.dark-mode .btn-link-custom { background: #0f172a; border-color: #334155; }
body.dark-mode .upload-area { border-color: #334155; }
body.dark-mode .upload-area:hover { background: #0f172a; }
body.dark-mode .btn-cancel { background: #334155; color: #f1f5f9; }
body.dark-mode .skeleton-item { background: #0f172a; }
body.dark-mode .skeleton-icon, body.dark-mode .skeleton-line { background: #334155; }
</style>
@endsection

@push('scripts')
<script>
(function() {
    'use strict';
    
    // Variables
    let modalSubirFoto = null;
    let modalVerFoto = null;
    let heartbeatIntervalGlobal = null;
    
    // ========== FUNCIÓN PARA REDIRIGIR A EXÁMENES ==========
    window.redirigirAExamenes = function() {
        window.location.href = '{{ route("estudiante.examenes") }}';
    };
    
    // ========== FUNCIÓN PARA VER MIS PAGOS ==========
    window.verMisPagos = function() {
        window.location.href = '{{ route("estudiante.mis-pagos") }}';
    };
    
    // ========== FUNCIÓN PARA FORMATEAR TIEMPO ==========
    function formatearTiempo(minutos) {
        if (!minutos || minutos === 0) return '0 min';
        
        const horas = Math.floor(minutos / 60);
        const mins = minutos % 60;
        
        if (horas > 0 && mins > 0) return `${horas}h ${mins}min`;
        if (horas > 0) return `${horas}h`;
        return `${mins}min`;
    }
    
    // ========== FUNCIONES DE CARGA DE DATOS ==========
    async function cargarTodosLosDatos() {
        console.log('🚀 Iniciando carga de datos del dashboard...');
        
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!token) {
            console.error('❌ No se encontró el token CSRF');
            return;
        }
        
        const headers = {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        };
        
        // 1. Cargar estadísticas
        try {
            console.log('📊 Cargando estadísticas...');
            const statsRes = await fetch('/estudiante/api/estadisticas', { headers });
            const statsData = await statsRes.json();
            console.log('📊 Estadísticas:', statsData);
            
            if (statsData.success) {
                const statExamenes = document.getElementById('statExamenes');
                const statLecciones = document.getElementById('statLecciones');
                const statMejorPuntaje = document.getElementById('statMejorPuntaje');
                const aprobadosTexto = document.getElementById('aprobadosTexto');
                const reprobadosTexto = document.getElementById('reprobadosTexto');
                const heroExamenes = document.getElementById('heroExamenes');
                const heroMejorPuntaje = document.getElementById('heroMejorPuntaje');
                const progresoTexto = document.getElementById('progresoTexto');
                const progresoBar = document.getElementById('progresoBar');
                const promedioTexto = document.getElementById('promedioTexto');
                const promedioBar = document.getElementById('promedioBar');
                
                if (statExamenes) statExamenes.innerText = statsData.total_examenes || 0;
                if (statLecciones) statLecciones.innerText = statsData.lecciones_vistas || 0;
                if (statMejorPuntaje) statMejorPuntaje.innerText = statsData.mejor_puntaje || 0;
                if (aprobadosTexto) aprobadosTexto.innerText = statsData.aprobados || 0;
                if (reprobadosTexto) reprobadosTexto.innerText = statsData.reprobados || 0;
                if (heroExamenes) heroExamenes.innerText = statsData.total_examenes || 0;
                if (heroMejorPuntaje) heroMejorPuntaje.innerText = statsData.mejor_puntaje || 0;
                
                const progreso = statsData.progreso || 0;
                if (progresoTexto) progresoTexto.innerText = progreso + '%';
                if (progresoBar) progresoBar.style.width = progreso + '%';
                
                const promedio = statsData.promedio || 0;
                if (promedioTexto) promedioTexto.innerText = promedio + '%';
                if (promedioBar) promedioBar.style.width = promedio + '%';
            }
        } catch (error) {
            console.error('❌ Error cargando estadísticas:', error);
        }
        
        // 2. Cargar últimos exámenes
        try {
            console.log('📋 Cargando últimos exámenes...');
            const examenesRes = await fetch('/estudiante/api/ultimos-examenes', { headers });
            const examenesData = await examenesRes.json();
            console.log('📋 Últimos exámenes:', examenesData);
            
            const container = document.getElementById('listaUltimosExamenes');
            if (container) {
                if (examenesData.success && examenesData.examenes && examenesData.examenes.length > 0) {
                    container.innerHTML = examenesData.examenes.map(examen => `
                        <div class="exam-item" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #f8fafc; border-radius: 16px;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                                    <i class="fas fa-calendar-alt" style="color: #667eea; font-size: 12px;"></i>
                                    <strong style="font-size: 0.85rem;">${examen.fecha}</strong>
                                </div>
                                <small style="color: #6b7280;">Calificación: ${examen.calificacion}%</small>
                            </div>
                            <span class="badge" style="padding: 6px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 600; ${examen.calificacion >= 70 ? 'background: #d1fae5; color: #065f46;' : 'background: #fed7aa; color: #92400e;'}">
                                ${examen.calificacion >= 70 ? '✓ Aprobado' : '○ Mejorable'}
                            </span>
                        </div>
                    `).join('');
                } else {
                    container.innerHTML = '<div class="text-center py-4 text-muted">No hay exámenes registrados aún</div>';
                }
            }
        } catch (error) {
            console.error('❌ Error cargando últimos exámenes:', error);
            const container = document.getElementById('listaUltimosExamenes');
            if (container) container.innerHTML = '<div class="text-center text-danger py-4">Error al cargar los exámenes</div>';
        }
        
        // 3. Cargar tiempo de estudio
        try {
            console.log('⏱️ Cargando tiempo de estudio...');
            const tiempoRes = await fetch('/estudiante/api/tiempo-estudio', { headers });
            const tiempoData = await tiempoRes.json();
            console.log('⏱️ Tiempo de estudio:', tiempoData);
            
            if (tiempoData.success) {
                const statTiempoHoy = document.getElementById('statTiempoHoy');
                const heroTiempoHoy = document.getElementById('heroTiempoHoy');
                const tiempoFormateado = formatearTiempo(tiempoData.hoy?.minutos || 0);
                if (statTiempoHoy) statTiempoHoy.innerText = tiempoFormateado;
                if (heroTiempoHoy) heroTiempoHoy.innerText = tiempoFormateado;
            }
        } catch (error) {
            console.error('❌ Error cargando tiempo de estudio:', error);
        }
        
        console.log('✅ Carga de datos completada!');
    }
    
    function iniciarHeartbeat() {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!token) return;
        
        if (heartbeatIntervalGlobal) clearInterval(heartbeatIntervalGlobal);
        heartbeatIntervalGlobal = setInterval(() => {
            fetch('/estudiante/heartbeat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ timestamp: Date.now() })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    console.log('💓 Heartbeat OK');
                    fetch('/estudiante/api/tiempo-estudio', {
                        headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
                    })
                    .then(res => res.json())
                    .then(tiempoData => {
                        if (tiempoData.success) {
                            const statTiempoHoy = document.getElementById('statTiempoHoy');
                            const heroTiempoHoy = document.getElementById('heroTiempoHoy');
                            const tiempoFormateado = formatearTiempo(tiempoData.hoy?.minutos || 0);
                            if (statTiempoHoy) statTiempoHoy.innerText = tiempoFormateado;
                            if (heroTiempoHoy) heroTiempoHoy.innerText = tiempoFormateado;
                        }
                    })
                    .catch(err => console.error('Error recargando tiempo:', err));
                }
            })
            .catch(err => console.error('Heartbeat error:', err));
        }, 60000);
    }
    
    // ========== FUNCIONES DE MODALES ==========
    window.mostrarModalPago = function() {
        window.location.href = '{{ route("estudiante.checkout") }}';
    };
    
    window.iniciarSimulador = function() {
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
    };
    
    window.cerrarAlertaFoto = function() {
        const alerta = document.getElementById('alertaFoto');
        if (alerta) alerta.style.display = 'none';
        localStorage.setItem('alertaFotoCerrada', 'true');
    };
    
    window.mostrarModalSubirFoto = function() {
        const form = document.getElementById('formSubirFoto');
        if (form) form.reset();
        const previewImg = document.getElementById('previewImg');
        const previewPlaceholder = document.getElementById('previewPlaceholder');
        if (previewImg) previewImg.style.display = 'none';
        if (previewPlaceholder) previewPlaceholder.style.display = 'flex';
        const progressUpload = document.getElementById('progressUpload');
        if (progressUpload) progressUpload.style.display = 'none';
        if (modalSubirFoto) modalSubirFoto.show();
    };
    
    window.subirFotoPerfil = function() {
        const file = document.getElementById('fotoInput').files[0];
        if (!file) {
            Swal.fire('Error', 'Selecciona una foto', 'error');
            return;
        }
        if (!file.type.match('image.*')) {
            Swal.fire('Error', 'Solo se permiten imágenes', 'error');
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire('Error', 'La imagen no debe superar los 2MB', 'error');
            return;
        }
        
        const formData = new FormData();
        formData.append('foto', file);
        const btn = document.getElementById('btnSubirFoto');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Subiendo...';
        
        fetch('/estudiante/subir-foto', {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire('¡Éxito!', data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire('Error', data.message, 'error');
                btn.disabled = false;
                btn.innerHTML = 'Subir foto';
            }
        })
        .catch(() => {
            Swal.fire('Error', 'Error al subir la foto', 'error');
            btn.disabled = false;
            btn.innerHTML = 'Subir foto';
        });
    };
    
    window.cambiarFoto = function() {
        if (modalVerFoto) modalVerFoto.hide();
        window.mostrarModalSubirFoto();
    };
    
    window.eliminarFoto = function() {
        Swal.fire({
            title: '¿Eliminar foto?',
            text: '¿Estás seguro de que quieres eliminar tu foto de perfil?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#667eea',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/estudiante/eliminar-foto', {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('¡Éxito!', data.message, 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'Error al eliminar la foto', 'error'));
            }
        });
    };
    
    // ========== INICIALIZAR ==========
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 DOM Content Loaded - Inicializando dashboard...');
        
        // Inicializar modales de Bootstrap
        const modalSubirFotoElement = document.getElementById('modalSubirFoto');
        const modalVerFotoElement = document.getElementById('modalVerFoto');
        if (modalSubirFotoElement) modalSubirFoto = new bootstrap.Modal(modalSubirFotoElement);
        if (modalVerFotoElement) modalVerFoto = new bootstrap.Modal(modalVerFotoElement);
        
        // Cargar datos solo si tiene plan activo
        @if(isset($estudiante) && $estudiante && $estudiante->plan_activo)
            console.log('🎉 Plan activo detectado - Cargando datos...');
            cargarTodosLosDatos();
            iniciarHeartbeat();
        @else
            console.log('⚠️ Plan inactivo - No se cargarán datos del dashboard');
        @endif
        
        // Preview de foto
        const fotoInput = document.getElementById('fotoInput');
        const previewImg = document.getElementById('previewImg');
        const previewPlaceholder = document.getElementById('previewPlaceholder');
        
        if (fotoInput) {
            fotoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        if (previewImg) previewImg.src = event.target.result;
                        if (previewImg) previewImg.style.display = 'block';
                        if (previewPlaceholder) previewPlaceholder.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
        
        // Verificar alerta de foto cerrada
        const alertaFoto = document.getElementById('alertaFoto');
        const alertaFotoCerrada = localStorage.getItem('alertaFotoCerrada');
        if (alertaFoto && alertaFotoCerrada === 'true') {
            alertaFoto.style.display = 'none';
        }
    });
})();
</script>
@endpush