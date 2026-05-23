@extends('estudiante.layouts.app')

@section('title', 'Contenido Educativo | SAINS')

@section('content')
<div class="dashboard-container">
    
    <!-- ========== HERO SECTION ========== -->
    <div class="hero-section mb-5">
        <div class="hero-content">
            <div class="hero-left">
                <div class="hero-badge">
                    @if(isset($estudiante) && $estudiante && $estudiante->plan_activo)
                        <i class="fas fa-crown me-2"></i> Plan Activo
                    @else
                        <i class="fas fa-graduation-cap me-2"></i> Estudiante Registrado
                    @endif
                </div>
                <h1 class="hero-title">
                    Contenido Educativo 📚
                </h1>
                <p class="hero-subtitle">
                    Prepárate con nuestro material de estudio completo
                </p>
                @if(isset($estudiante) && $estudiante && $estudiante->plan_activo)
                <div class="hero-stats">
                    <div class="hero-stat">
                        <span class="hero-stat-value" id="heroLecciones">0</span>
                        <span class="hero-stat-label">Lecciones</span>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat">
                        <span class="hero-stat-value">6</span>
                        <span class="hero-stat-label">Módulos</span>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat">
                        <span class="hero-stat-value" id="heroHoras">0</span>
                        <span class="hero-stat-label">Horas de contenido</span>
                    </div>
                </div>
                @endif
            </div>
            <div class="hero-right">
                <div class="hero-avatar">
                    @if(isset($estudiante) && $estudiante && $estudiante->foto)
                        <img src="{{ Storage::url($estudiante->foto) }}" alt="Foto">
                    @else
                        <i class="fas fa-graduation-cap"></i>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ========== CURSOS BÁSICOS GRATUITOS ========== -->
    <div class="section-header">
        <div class="section-title-wrapper">
            <i class="fas fa-graduation-cap section-icon"></i>
            <div>
                <h3 class="section-title">Contenido del Curso</h3>
                <p class="section-subtitle">Material completo para tu preparación</p>
            </div>
        </div>
        <span class="badge-modern success">Acceso Completo</span>
    </div>

    <div class="accordion-container">
        <!-- Módulo 1 -->
        <div class="accordion-item">
            <div class="accordion-header" onclick="toggleAccordion(this)">
                <div class="accordion-header-left">
                    <div class="accordion-icon" style="background: linear-gradient(135deg, #4361ee, #3a0ca3);">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <h4>Módulo 1: Introducción y Preparación</h4>
                        <p>Conoce la estructura del examen y las mejores estrategias</p>
                    </div>
                </div>
                <div class="accordion-badge">
                    <span>2 lecciones</span>
                    <i class="fas fa-chevron-down accordion-arrow"></i>
                </div>
            </div>
            <div class="accordion-body">
                <div class="videos-grid">
                    <div class="video-card" data-video-id="366659859" data-video-title="Recomendaciones previas">
                        <div class="video-thumb">
                            <img src="https://vumbnail.com/366659859.jpg" alt="Video">
                            <div class="video-play-overlay">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        <div class="video-details">
                            <h5>Recomendaciones previas al examen</h5>
                            <p>Consejos y estrategias antes del examen</p>
                            <div class="video-metadata">
                                <span><i class="far fa-clock"></i> 15:30 min</span>
                                <span><i class="fas fa-signal"></i> Principiante</span>
                            </div>
                        </div>
                    </div>
                    <div class="video-card" data-video-id="384619978" data-video-title="Formato del examen">
                        <div class="video-thumb">
                            <img src="https://vumbnail.com/384619978.jpg" alt="Video">
                            <div class="video-play-overlay">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        <div class="video-details">
                            <h5>Formato del examen de admisión</h5>
                            <p>Conoce la estructura del examen</p>
                            <div class="video-metadata">
                                <span><i class="far fa-clock"></i> 12:45 min</span>
                                <span><i class="fas fa-signal"></i> Principiante</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Módulo 2 -->
        <div class="accordion-item">
            <div class="accordion-header" onclick="toggleAccordion(this)">
                <div class="accordion-header-left">
                    <div class="accordion-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <div>
                        <h4>Módulo 2: Comprensión Lectora</h4>
                        <p>Mejora tus habilidades de lectura y comprensión</p>
                    </div>
                </div>
                <div class="accordion-badge">
                    <span>2 lecciones</span>
                    <i class="fas fa-chevron-down accordion-arrow"></i>
                </div>
            </div>
            <div class="accordion-body">
                <div class="videos-grid">
                    <div class="video-card" data-video-id="360944019" data-video-title="Comprensión lectora 1">
                        <div class="video-thumb">
                            <img src="https://vumbnail.com/360944019.jpg" alt="Video">
                            <div class="video-play-overlay">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        <div class="video-details">
                            <h5>Comprensión lectora - Parte 1</h5>
                            <p>Técnicas básicas de comprensión de textos</p>
                            <div class="video-metadata">
                                <span><i class="far fa-clock"></i> 18:30 min</span>
                                <span><i class="fas fa-signal"></i> Intermedio</span>
                            </div>
                        </div>
                    </div>
                    <div class="video-card" data-video-id="360944782" data-video-title="Comprensión lectora 2">
                        <div class="video-thumb">
                            <img src="https://vumbnail.com/360944782.jpg" alt="Video">
                            <div class="video-play-overlay">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        <div class="video-details">
                            <h5>Comprensión lectora - Parte 2</h5>
                            <p>Estrategias de lectura avanzada</p>
                            <div class="video-metadata">
                                <span><i class="far fa-clock"></i> 16:45 min</span>
                                <span><i class="fas fa-signal"></i> Intermedio</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Módulo 3 -->
        <div class="accordion-item">
            <div class="accordion-header" onclick="toggleAccordion(this)">
                <div class="accordion-header-left">
                    <div class="accordion-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div>
                        <h4>Módulo 3: Pensamiento Matemático</h4>
                        <p>Desarrolla tus habilidades matemáticas</p>
                    </div>
                </div>
                <div class="accordion-badge">
                    <span>1 lección</span>
                    <i class="fas fa-chevron-down accordion-arrow"></i>
                </div>
            </div>
            <div class="accordion-body">
                <div class="videos-grid">
                    <div class="video-card" data-video-id="356291521" data-video-title="Pensamiento matemático">
                        <div class="video-thumb">
                            <img src="https://vumbnail.com/356291521.jpg" alt="Video">
                            <div class="video-play-overlay">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        <div class="video-details">
                            <h5>Pensamiento matemático</h5>
                            <p>Desarrolla tus habilidades matemáticas</p>
                            <div class="video-metadata">
                                <span><i class="far fa-clock"></i> 22:15 min</span>
                                <span><i class="fas fa-signal"></i> Intermedio</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Módulo 4 -->
        <div class="accordion-item">
            <div class="accordion-header" onclick="toggleAccordion(this)">
                <div class="accordion-header-left">
                    <div class="accordion-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fas fa-brain"></i>
                    </div>
                    <div>
                        <h4>Módulo 4: Pensamiento Analítico</h4>
                        <p>Potencia tu capacidad de análisis y razonamiento</p>
                    </div>
                </div>
                <div class="accordion-badge">
                    <span>1 lección</span>
                    <i class="fas fa-chevron-down accordion-arrow"></i>
                </div>
            </div>
            <div class="accordion-body">
                <div class="videos-grid">
                    <div class="video-card" data-video-id="362617177" data-video-title="Pensamiento analítico">
                        <div class="video-thumb">
                            <img src="https://vumbnail.com/362617177.jpg" alt="Video">
                            <div class="video-play-overlay">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        <div class="video-details">
                            <h5>Pensamiento analítico</h5>
                            <p>Desarrolla tu capacidad de análisis</p>
                            <div class="video-metadata">
                                <span><i class="far fa-clock"></i> 20:00 min</span>
                                <span><i class="fas fa-signal"></i> Avanzado</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Módulo 5 -->
        <div class="accordion-item">
            <div class="accordion-header" onclick="toggleAccordion(this)">
                <div class="accordion-header-left">
                    <div class="accordion-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                        <i class="fas fa-language"></i>
                    </div>
                    <div>
                        <h4>Módulo 5: Lenguaje y Gramática</h4>
                        <p>Mejora tu redacción y conocimiento del idioma</p>
                    </div>
                </div>
                <div class="accordion-badge">
                    <span>2 lecciones</span>
                    <i class="fas fa-chevron-down accordion-arrow"></i>
                </div>
            </div>
            <div class="accordion-body">
                <div class="videos-grid">
                    <div class="video-card" data-video-id="356512619" data-video-title="Lenguaje escrito">
                        <div class="video-thumb">
                            <img src="https://vumbnail.com/356512619.jpg" alt="Video">
                            <div class="video-play-overlay">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        <div class="video-details">
                            <h5>Lenguaje escrito</h5>
                            <p>Mejora tu redacción y ortografía</p>
                            <div class="video-metadata">
                                <span><i class="far fa-clock"></i> 14:20 min</span>
                                <span><i class="fas fa-signal"></i> Intermedio</span>
                            </div>
                        </div>
                    </div>
                    <div class="video-card" data-video-id="357903613" data-video-title="Estructura de la lengua">
                        <div class="video-thumb">
                            <img src="https://vumbnail.com/357903613.jpg" alt="Video">
                            <div class="video-play-overlay">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        <div class="video-details">
                            <h5>Estructura de la lengua</h5>
                            <p>Gramática y sintaxis esencial</p>
                            <div class="video-metadata">
                                <span><i class="far fa-clock"></i> 19:15 min</span>
                                <span><i class="fas fa-signal"></i> Avanzado</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Módulo 6 -->
        <div class="accordion-item">
            <div class="accordion-header" onclick="toggleAccordion(this)">
                <div class="accordion-header-left">
                    <div class="accordion-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                        <i class="fas fa-language"></i>
                    </div>
                    <div>
                        <h4>Módulo 6: Inglés para el Examen</h4>
                        <p>Vocabulario y comprensión básica del inglés</p>
                    </div>
                </div>
                <div class="accordion-badge">
                    <span>1 lección</span>
                    <i class="fas fa-chevron-down accordion-arrow"></i>
                </div>
            </div>
            <div class="accordion-body">
                <div class="videos-grid">
                    <div class="video-card" data-video-id="383565235" data-video-title="Inglés">
                        <div class="video-thumb">
                            <img src="https://vumbnail.com/383565235.jpg" alt="Video">
                            <div class="video-play-overlay">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        <div class="video-details">
                            <h5>Inglés para el examen</h5>
                            <p>Vocabulario y comprensión básica</p>
                            <div class="video-metadata">
                                <span><i class="far fa-clock"></i> 25:30 min</span>
                                <span><i class="fas fa-signal"></i> Principiante</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== BOTÓN PARA ADQUIRIR EL CURSO ========== -->
    <div class="premium-access-card mt-4">
        <div class="premium-access-content">
            <div class="premium-access-icon">
                <i class="fas fa-gem"></i>
            </div>
            <div class="premium-access-text">
                <h3>🎓 Curso Completo SAINS</h3>
                <p>Accede a todas las lecciones, material descargable y simuladores por solo $800 MXN</p>
            </div>
            @if(isset($estudiante) && $estudiante && $estudiante->plan_activo)
                <a href="{{ route('estudiante.clases-premium') }}" class="premium-access-btn active">
                    <i class="fas fa-crown me-2"></i>Acceder al Curso
                    <i class="fas fa-arrow-right ms-2"></i>
                </a>
            @else
                <button class="premium-access-btn locked" onclick="mostrarModalCurso()">
                    <i class="fas fa-shopping-cart me-2"></i>Adquirir Curso Completo ($800 MXN)
                    <i class="fas fa-graduation-cap ms-2"></i>
                </button>
            @endif
        </div>
    </div>

</div>

<!-- ========== MODAL DE VIDEO - TAMAÑO PERFECTO ========== -->
<div class="modal fade" id="videoModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content video-modal-content-custom">
            <div class="modal-header video-modal-header-custom">
                <div class="video-title-custom">
                    <i class="fas fa-play-circle"></i>
                    <span id="videoModalTitleText">Reproduciendo video</span>
                </div>
                <div class="video-controls-custom">
                    <button type="button" class="video-btn-custom" id="fullscreenVideoBtn" title="Pantalla completa">
                        <i class="fas fa-expand"></i>
                    </button>
                    <button type="button" class="video-btn-custom close-video-btn" data-bs-dismiss="modal" title="Cerrar">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="video-wrapper-custom" id="videoWrapperCustom">
                    <iframe id="videoIframe" src="" frameborder="0" 
                            allow="autoplay; fullscreen; picture-in-picture" 
                            allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========== MODAL DE COMPRA DEL CURSO (ÚNICO PLAN: $800) ========== -->
<div class="modal fade" id="modalCompraCurso" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content-custom">
            <div class="modal-header-custom">
                <div class="modal-header-left">
                    <i class="fas fa-crown"></i>
                    <div>
                        <h5>Acceso Completo al Curso</h5>
                        <p>Obtén todo el contenido educativo por solo $800 MXN</p>
                    </div>
                </div>
                <button type="button" class="modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body-custom p-4">
                <div class="single-plan-container">
                    <div class="plan-card-unico">
                        <div class="plan-icon-wrapper">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3>Curso Completo SAINS</h3>
                        <div class="plan-price">$800 <span>MXN</span></div>
                        <ul>
                            <li><i class="fas fa-check"></i> <strong>6 Módulos Completos</strong> (Matemáticas, Lectura, etc.)</li>
                            <li><i class="fas fa-check"></i> <strong>9+ Lecciones</strong> en video</li>
                            <li><i class="fas fa-check"></i> Material descargable y guías de estudio</li>
                            <li><i class="fas fa-check"></i> Acceso de por vida</li>
                            <li><i class="fas fa-check"></i> Simuladores y ejercicios prácticos</li>
                            <li><i class="fas fa-check"></i> Certificado de finalización</li>
                        </ul>
                        <button class="btn-comprar-curso" onclick="comprarCurso()">
                            <i class="fas fa-shopping-cart me-2"></i>Obtener Acceso Ahora - $800
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                        <p class="pago-seguro-text">
                            <i class="fas fa-lock"></i> Pago 100% seguro
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ========== ESTILOS MODERNOS ========== */

.dashboard-container {
    padding: 0;
    max-width: 1400px;
    margin: 0 auto;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 28px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: -30%;
    right: -10%;
    width: 60%;
    height: 160%;
    background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
    transform: rotate(15deg);
}

.hero-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 2rem;
    position: relative;
    z-index: 1;
}

.hero-left {
    flex: 1;
}

.hero-badge {
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

.hero-title {
    font-size: 2rem;
    font-weight: 800;
    color: white;
    margin-bottom: 0.75rem;
}

.hero-subtitle {
    font-size: 1rem;
    color: rgba(255,255,255,0.9);
    margin-bottom: 1.5rem;
    max-width: 500px;
}

.hero-stats {
    display: flex;
    gap: 2rem;
    margin-top: 1rem;
}

.hero-stat {
    text-align: center;
}

.hero-stat-value {
    display: block;
    font-size: 1.75rem;
    font-weight: 800;
    color: white;
}

.hero-stat-label {
    font-size: 0.7rem;
    color: rgba(255,255,255,0.7);
}

.hero-stat-divider {
    width: 1px;
    background: rgba(255,255,255,0.3);
}

.hero-right {
    width: 100px;
    height: 100px;
}

.hero-avatar {
    width: 100px;
    height: 100px;
    background: rgba(255,255,255,0.2);
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.hero-avatar i {
    font-size: 3rem;
    color: white;
}

.hero-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Section Header */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.section-title-wrapper {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-icon {
    font-size: 1.5rem;
    color: #667eea;
}

.section-title {
    font-size: 1.3rem;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
}

.section-subtitle {
    font-size: 0.8rem;
    color: #64748b;
    margin: 0;
}

.badge-modern {
    background: #e2e8f0;
    padding: 0.3rem 1rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    color: #475569;
}

.badge-modern.success {
    background: #d1fae5;
    color: #065f46;
}

/* Premium Access Card */
.premium-access-card {
    background: linear-gradient(135deg, #1a1a2e, #16213e);
    border-radius: 24px;
    overflow: hidden;
    margin: 2rem 0;
}

.premium-access-content {
    padding: 1.5rem 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.premium-access-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #ffc107, #ff9800);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: #1a1a2e;
}

.premium-access-text {
    flex: 1;
}

.premium-access-text h3 {
    color: white;
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.premium-access-text p {
    color: rgba(255,255,255,0.8);
    margin-bottom: 0;
    font-size: 0.85rem;
}

.premium-access-btn {
    background: linear-gradient(135deg, #ffc107, #ff9800);
    border: none;
    border-radius: 50px;
    padding: 0.8rem 1.8rem;
    font-weight: 700;
    color: #1a1a2e;
    transition: all 0.3s;
    white-space: nowrap;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    cursor: pointer;
}

.premium-access-btn.active {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.premium-access-btn.locked {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.premium-access-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255,193,7,0.3);
}

.premium-access-btn.active:hover {
    box-shadow: 0 8px 20px rgba(16,185,129,0.3);
}

/* Accordion */
.accordion-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.accordion-item {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
}

.accordion-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    cursor: pointer;
    transition: background 0.3s;
}

.accordion-header:hover {
    background: #f8fafc;
}

.accordion-header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.accordion-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    color: white;
    flex-shrink: 0;
}

.accordion-header-left h4 {
    font-size: 0.9rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: #1e293b;
}

.accordion-header-left p {
    font-size: 0.7rem;
    color: #64748b;
    margin: 0;
}

.accordion-badge {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-shrink: 0;
}

.accordion-badge span {
    background: #e2e8f0;
    padding: 0.25rem 0.8rem;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
    color: #475569;
}

.accordion-arrow {
    font-size: 0.75rem;
    color: #94a3b8;
    transition: transform 0.3s;
}

.accordion-item.active .accordion-arrow {
    transform: rotate(180deg);
}

.accordion-body {
    display: none;
    padding: 1rem 1.25rem;
    border-top: 1px solid #e2e8f0;
    background: #fafbfc;
}

.accordion-item.active .accordion-body {
    display: block;
}

/* Videos Grid */
.videos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
}

.video-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s;
    border: 1px solid #e2e8f0;
}

.video-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.video-thumb {
    position: relative;
    height: 140px;
    overflow: hidden;
}

.video-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.video-card:hover .video-thumb img {
    transform: scale(1.05);
}

.video-play-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 45px;
    height: 45px;
    background: rgba(0,0,0,0.7);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.video-card:hover .video-play-overlay {
    opacity: 1;
}

.video-play-overlay i {
    color: white;
    font-size: 1rem;
    margin-left: 2px;
}

.video-details {
    padding: 0.75rem;
}

.video-details h5 {
    font-size: 0.85rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: #1e293b;
}

.video-details p {
    font-size: 0.65rem;
    color: #64748b;
    margin-bottom: 0.5rem;
}

.video-metadata {
    display: flex;
    gap: 0.75rem;
    font-size: 0.6rem;
    color: #94a3b8;
}

.video-metadata i {
    margin-right: 0.2rem;
}

/* ========== MODAL DE VIDEO ========== */
.modal-xl {
    max-width: 1000px;
}

@media (min-width: 992px) {
    .modal-xl {
        max-width: 1000px;
    }
}

.video-modal-content-custom {
    background: #0f0f0f;
    border-radius: 20px;
    overflow: hidden;
    border: none;
}

.video-modal-header-custom {
    background: linear-gradient(135deg, #1a1a2e, #16213e);
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.video-title-custom {
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.video-title-custom i {
    font-size: 1.3rem;
    color: #ffc107;
}

.video-title-custom span {
    color: white;
    font-weight: 600;
    font-size: 1rem;
}

.video-controls-custom {
    display: flex;
    gap: 0.5rem;
}

.video-btn-custom {
    background: rgba(255,255,255,0.1);
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    color: white;
    cursor: pointer;
    transition: all 0.3s;
}

.video-btn-custom:hover {
    background: rgba(255,255,255,0.2);
    transform: scale(1.05);
}

.close-video-btn:hover {
    background: #dc2626;
}

.video-wrapper-custom {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%;
    background: #000;
}

.video-wrapper-custom iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
}

/* Fullscreen mode */
.video-wrapper-custom:fullscreen {
    padding-bottom: 0;
    height: 100vh;
}

.video-wrapper-custom:fullscreen iframe {
    height: 100vh;
}

/* ========== MODAL DE COMPRA - ESTILOS ========== */
.modal-content-custom {
    background: white;
    border-radius: 24px;
    overflow: hidden;
}

.modal-header-custom {
    background: linear-gradient(135deg, #667eea, #764ba2);
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header-left {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.modal-header-left i {
    font-size: 1.3rem;
    color: white;
}

.modal-header-left h5 {
    color: white;
    font-weight: 600;
    margin: 0;
}

.modal-header-left p {
    font-size: 0.7rem;
    color: rgba(255,255,255,0.8);
    margin: 0;
}

.modal-close {
    background: rgba(255,255,255,0.2);
    border: none;
    width: 38px;
    height: 38px;
    border-radius: 10px;
    color: white;
    transition: all 0.3s;
}

.modal-close:hover {
    background: rgba(255,255,255,0.3);
    transform: scale(1.05);
}

.modal-body-custom {
    padding: 1.5rem;
}

/* Tarjeta de compra única */
.single-plan-container {
    display: flex;
    justify-content: center;
}

.plan-card-unico {
    background: linear-gradient(135deg, #f8fafc, #ffffff);
    border-radius: 28px;
    padding: 2rem 1.8rem;
    text-align: center;
    width: 100%;
    border: 1px solid rgba(102,126,234,0.2);
    transition: all 0.3s;
    box-shadow: 0 12px 24px -12px rgba(0,0,0,0.1);
}

body.dark-mode .plan-card-unico {
    background: linear-gradient(135deg, #1e293b, #0f172a);
    border-color: rgba(102,126,234,0.3);
}

.plan-icon-wrapper {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2rem;
    color: #667eea;
}

.plan-card-unico h3 {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #1e293b;
}

body.dark-mode .plan-card-unico h3 {
    color: #f1f5f9;
}

.plan-card-unico .plan-price {
    font-size: 2.5rem;
    font-weight: 800;
    color: #667eea;
    margin: 1rem 0;
}

.plan-card-unico .plan-price span {
    font-size: 0.8rem;
    font-weight: 400;
    color: #64748b;
}

.plan-card-unico ul {
    list-style: none;
    padding: 0;
    margin: 1.5rem 0;
    text-align: left;
}

.plan-card-unico ul li {
    font-size: 0.85rem;
    margin-bottom: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.8rem;
    color: #334155;
}

body.dark-mode .plan-card-unico ul li {
    color: #cbd5e1;
}

.plan-card-unico ul li i {
    color: #10b981;
    font-size: 0.9rem;
    width: 20px;
}

.btn-comprar-curso {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    width: 100%;
    padding: 0.9rem;
    border-radius: 50px;
    font-weight: 700;
    color: white;
    font-size: 1rem;
    transition: all 0.3s;
    margin-top: 0.5rem;
    cursor: pointer;
}

.btn-comprar-curso:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(102,126,234,0.4);
}

.pago-seguro-text {
    font-size: 0.7rem;
    color: #64748b;
    margin-top: 1rem;
    margin-bottom: 0;
}

body.dark-mode .pago-seguro-text {
    color: #94a3b8;
}

/* Responsive */
@media (max-width: 1024px) {
    .plans-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 1.5rem;
    }
    
    .hero-stats {
        gap: 1rem;
    }
    
    .hero-stat-value {
        font-size: 1.25rem;
    }
    
    .hero-right {
        display: none;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .premium-access-content {
        flex-direction: column;
        text-align: center;
    }
    
    .modal-xl {
        margin: 1rem;
        max-width: calc(100% - 2rem);
    }
    
    .plan-card-unico {
        padding: 1.5rem;
    }
}

/* Dark Mode */
body.dark-mode .accordion-item,
body.dark-mode .video-card,
body.dark-mode .modal-content-custom:not(.video-modal-content-custom) {
    background: #1e293b;
    border-color: #334155;
}

body.dark-mode .accordion-header-left h4,
body.dark-mode .video-details h5,
body.dark-mode .section-title {
    color: #f1f5f9;
}

body.dark-mode .accordion-header-left p,
body.dark-mode .video-details p,
body.dark-mode .section-subtitle {
    color: #94a3b8;
}

body.dark-mode .accordion-body {
    background: #0f172a;
    border-top-color: #334155;
}

body.dark-mode .accordion-header:hover {
    background: #0f172a;
}

body.dark-mode .accordion-badge span {
    background: #334155;
    color: #94a3b8;
}

body.dark-mode .badge-modern {
    background: #334155;
    color: #94a3b8;
}

body.dark-mode .badge-modern.success {
    background: rgba(16,185,129,0.2);
    color: #34d399;
}
</style>
@endsection

@push('scripts')
<script>
    function toggleAccordion(element) {
        const accordion = element.closest('.accordion-item');
        accordion.classList.toggle('active');
    }
    
    function actualizarEstadisticas() {
        // Calcular total de lecciones
        const totalLecciones = document.querySelectorAll('.video-card').length;
        const heroLecciones = document.getElementById('heroLecciones');
        if (heroLecciones) heroLecciones.innerText = totalLecciones;
        
        // Calcular horas totales aproximadas (estimado)
        const horasTotales = 2.5; // Aproximadamente 2.5 horas de contenido
        const heroHoras = document.getElementById('heroHoras');
        if (heroHoras) heroHoras.innerText = horasTotales;
    }
    
    function reproducirVideo(videoId, titulo) {
        const modalElement = document.getElementById('videoModal');
        const titleSpan = document.getElementById('videoModalTitleText');
        const iframe = document.getElementById('videoIframe');
        
        titleSpan.textContent = titulo;
        iframe.src = `https://player.vimeo.com/video/${videoId}?autoplay=1&title=0&byline=0&portrait=0&badge=0`;
        
        const modal = new bootstrap.Modal(modalElement, {
            backdrop: 'static',
            keyboard: true
        });
        modal.show();
        
        modalElement.addEventListener('hidden.bs.modal', function() {
            iframe.src = '';
        }, { once: true });
    }
    
    // Pantalla completa para el video
    document.getElementById('fullscreenVideoBtn')?.addEventListener('click', function() {
        const container = document.getElementById('videoWrapperCustom');
        if (container.requestFullscreen) {
            container.requestFullscreen();
        } else if (container.webkitRequestFullscreen) {
            container.webkitRequestFullscreen();
        } else if (container.msRequestFullscreen) {
            container.msRequestFullscreen();
        }
    });
    
    // Muestra el modal de compra del curso único
    function mostrarModalCurso() {
        const modal = new bootstrap.Modal(document.getElementById('modalCompraCurso'));
        modal.show();
    }
    
    // Función de compra - Usando tu ruta existente checkout
    function comprarCurso() {
        // Cerrar el modal actual
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalCompraCurso'));
        if (modal) modal.hide();
        
        // Redirigir directamente a tu página de checkout
        window.location.href = "{{ route('estudiante.checkout') }}";
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        actualizarEstadisticas();
        
        document.querySelectorAll('.video-card').forEach(video => {
            const videoId = video.dataset.videoId;
            const videoTitle = video.dataset.videoTitle;
            
            video.addEventListener('click', function() {
                reproducirVideo(videoId, videoTitle);
            });
        });
    });
</script>
@endpush