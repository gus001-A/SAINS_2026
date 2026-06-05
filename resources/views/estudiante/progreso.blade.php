@extends('estudiante.layouts.app')

@section('title', 'Contenido Educativo | SAINS')

@section('content')
<div class="dashboard-container">

    <!-- ========== HERO SECTION ========== -->
    <div class="hero-premium">
        <div class="hero-premium-bg">
            <div class="hero-premium-gradient"></div>
            <div class="hero-premium-particles">
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
            </div>
        </div>
        <div class="hero-premium-content">
            <div class="hero-premium-left">
                <div class="hero-premium-badge">
                    <span class="badge-dot"></span>
                    <span>Curso completo</span>
                </div>
                <h1 class="hero-premium-title">
                    Domina el examen<br>
                    <span class="hero-premium-highlight">de admisión</span>
                </h1>
                <p class="hero-premium-description">
                    Prepárate con el contenido más completo y actualizado.
                    Más de 2.5 horas de video, ejercicios prácticos y simuladores.
                </p>
                <div class="hero-premium-stats">
                    <div class="hero-premium-stat">
                        <div class="stat-number" id="totalLessons">0</div>
                        <div class="stat-label">Lecciones</div>
                    </div>
                    <div class="hero-premium-stat">
                        <div class="stat-number">6</div>
                        <div class="stat-label">Módulos</div>
                    </div>
                    <div class="hero-premium-stat">
                        <div class="stat-number" id="totalHours">0</div>
                        <div class="stat-label">Horas de contenido</div>
                    </div>
                </div>
                <div class="hero-premium-actions">
                    <button class="hero-premium-btn primary" onclick="window.scrollTo({top: 600, behavior: 'smooth'})">
                        <i class="fas fa-play-circle"></i>
                        <span>Comenzar ahora</span>
                    </button>
                    <button class="hero-premium-btn secondary" onclick="irAClasesPremium()">
                        <i class="fas fa-crown"></i>
                        <span>Clases premium</span>
                    </button>
                </div>
            </div>
            <div class="hero-premium-right">
                <div class="hero-premium-card">
                    <div class="card-glow"></div>
                    <div class="card-content">
                        <div class="card-icon">
                            <i class="fas fa-chalkboard-user"></i>
                        </div>
                        <div class="card-text">
                            <p>Contenido actualizado</p>
                            <span>Preparación completa para el examen</span>
                        </div>
                        <div class="card-features">
                            <div class="card-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>9+ lecciones en video</span>
                            </div>
                            <div class="card-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Material descargable</span>
                            </div>
                            <div class="card-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Ejercicios prácticos</span>
                            </div>
                            <div class="card-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Simuladores incluidos</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== BANNER CLASES PREMIUM ========== -->
    <div class="premium-banner-premium">
        <div class="premium-banner-premium-content">
            <div class="banner-icon">
                <i class="fas fa-gem"></i>
            </div>
            <div class="banner-info">
                <h4>🎓 Curso completo SAINS</h4>
                <p>6 módulos · 9+ lecciones · Simuladores · Material descargable</p>
            </div>
            <button class="banner-btn" onclick="irAClasesPremium()">
                Ir a clases premium <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>

    <!-- ========== MÓDULOS DEL CURSO ========== -->
    <div class="modules-wrapper">
        <div class="modules-header-premium">
            <div class="modules-header-left">
                <div class="modules-header-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <div>
                    <h2>Plan de estudios</h2>
                    <p>6 módulos diseñados para cubrir todas las áreas del examen</p>
                </div>
            </div>
            <div class="modules-header-right">
                <div class="modules-badge-premium">
                    <i class="fas fa-check-circle"></i>
                    <span>6 módulos disponibles</span>
                </div>
            </div>
        </div>

        <div class="modules-timeline">
            <!-- Módulo 1 -->
            <div class="timeline-module">
                <div class="timeline-marker">
                    <div class="marker-dot"></div>
                    <div class="marker-line"></div>
                </div>
                <div class="module-card-premium">
                    <div class="module-card-header" onclick="toggleModule(this)">
                        <div class="module-card-left">
                            <div class="module-card-number">01</div>
                            <div class="module-card-info">
                                <h3>Introducción y Preparación</h3>
                                <p>Conoce la estructura del examen y las mejores estrategias</p>
                            </div>
                        </div>
                        <div class="module-card-right">
                            <div class="module-badge-info">
                                <i class="fas fa-video"></i>
                                <span>2 lecciones</span>
                            </div>
                            <i class="fas fa-chevron-down module-card-toggle"></i>
                        </div>
                    </div>
                    <div class="module-card-body">
                        <div class="lessons-premium-grid">
                            <div class="lesson-premium-card" data-video-id="366659859"
                                data-video-title="Recomendaciones previas" data-duration="15:30"
                                data-level="Principiante">
                                <div class="lesson-premium-thumb">
                                    <img src="https://vumbnail.com/366659859.jpg" alt="Lección">
                                    <div class="lesson-premium-overlay">
                                        <i class="fas fa-play"></i>
                                    </div>
                                    <div class="lesson-premium-duration">15:30</div>
                                </div>
                                <div class="lesson-premium-info">
                                    <h4>Recomendaciones previas al examen</h4>
                                    <div class="lesson-premium-meta">
                                        <span><i class="far fa-clock"></i> 15 min</span>
                                        <span><i class="fas fa-chart-line"></i> Principiante</span>
                                    </div>
                                </div>
                                <div class="lesson-premium-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                            <div class="lesson-premium-card" data-video-id="384619978"
                                data-video-title="Formato del examen" data-duration="12:45" data-level="Principiante">
                                <div class="lesson-premium-thumb">
                                    <img src="https://vumbnail.com/384619978.jpg" alt="Lección">
                                    <div class="lesson-premium-overlay">
                                        <i class="fas fa-play"></i>
                                    </div>
                                    <div class="lesson-premium-duration">12:45</div>
                                </div>
                                <div class="lesson-premium-info">
                                    <h4>Formato del examen de admisión</h4>
                                    <div class="lesson-premium-meta">
                                        <span><i class="far fa-clock"></i> 12 min</span>
                                        <span><i class="fas fa-chart-line"></i> Principiante</span>
                                    </div>
                                </div>
                                <div class="lesson-premium-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Módulo 2 -->
            <div class="timeline-module">
                <div class="timeline-marker">
                    <div class="marker-dot"></div>
                    <div class="marker-line"></div>
                </div>
                <div class="module-card-premium">
                    <div class="module-card-header" onclick="toggleModule(this)">
                        <div class="module-card-left">
                            <div class="module-card-number">02</div>
                            <div class="module-card-info">
                                <h3>Comprensión Lectora</h3>
                                <p>Mejora tus habilidades de lectura y comprensión</p>
                            </div>
                        </div>
                        <div class="module-card-right">
                            <div class="module-badge-info">
                                <i class="fas fa-video"></i>
                                <span>2 lecciones</span>
                            </div>
                            <i class="fas fa-chevron-down module-card-toggle"></i>
                        </div>
                    </div>
                    <div class="module-card-body">
                        <div class="lessons-premium-grid">
                            <div class="lesson-premium-card" data-video-id="360944019"
                                data-video-title="Comprensión lectora 1" data-duration="18:30" data-level="Intermedio">
                                <div class="lesson-premium-thumb">
                                    <img src="https://vumbnail.com/360944019.jpg" alt="Lección">
                                    <div class="lesson-premium-overlay">
                                        <i class="fas fa-play"></i>
                                    </div>
                                    <div class="lesson-premium-duration">18:30</div>
                                </div>
                                <div class="lesson-premium-info">
                                    <h4>Comprensión lectora - Parte 1</h4>
                                    <div class="lesson-premium-meta">
                                        <span><i class="far fa-clock"></i> 18 min</span>
                                        <span><i class="fas fa-chart-line"></i> Intermedio</span>
                                    </div>
                                </div>
                                <div class="lesson-premium-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                            <div class="lesson-premium-card" data-video-id="360944782"
                                data-video-title="Comprensión lectora 2" data-duration="16:45" data-level="Intermedio">
                                <div class="lesson-premium-thumb">
                                    <img src="https://vumbnail.com/360944782.jpg" alt="Lección">
                                    <div class="lesson-premium-overlay">
                                        <i class="fas fa-play"></i>
                                    </div>
                                    <div class="lesson-premium-duration">16:45</div>
                                </div>
                                <div class="lesson-premium-info">
                                    <h4>Comprensión lectora - Parte 2</h4>
                                    <div class="lesson-premium-meta">
                                        <span><i class="far fa-clock"></i> 16 min</span>
                                        <span><i class="fas fa-chart-line"></i> Intermedio</span>
                                    </div>
                                </div>
                                <div class="lesson-premium-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Módulo 3 -->
            <div class="timeline-module">
                <div class="timeline-marker">
                    <div class="marker-dot"></div>
                    <div class="marker-line"></div>
                </div>
                <div class="module-card-premium">
                    <div class="module-card-header" onclick="toggleModule(this)">
                        <div class="module-card-left">
                            <div class="module-card-number">03</div>
                            <div class="module-card-info">
                                <h3>Pensamiento Matemático</h3>
                                <p>Desarrolla tus habilidades matemáticas</p>
                            </div>
                        </div>
                        <div class="module-card-right">
                            <div class="module-badge-info">
                                <i class="fas fa-video"></i>
                                <span>1 lección</span>
                            </div>
                            <i class="fas fa-chevron-down module-card-toggle"></i>
                        </div>
                    </div>
                    <div class="module-card-body">
                        <div class="lessons-premium-grid">
                            <div class="lesson-premium-card" data-video-id="356291521"
                                data-video-title="Pensamiento matemático" data-duration="22:15" data-level="Intermedio">
                                <div class="lesson-premium-thumb">
                                    <img src="https://vumbnail.com/356291521.jpg" alt="Lección">
                                    <div class="lesson-premium-overlay">
                                        <i class="fas fa-play"></i>
                                    </div>
                                    <div class="lesson-premium-duration">22:15</div>
                                </div>
                                <div class="lesson-premium-info">
                                    <h4>Pensamiento matemático</h4>
                                    <div class="lesson-premium-meta">
                                        <span><i class="far fa-clock"></i> 22 min</span>
                                        <span><i class="fas fa-chart-line"></i> Intermedio</span>
                                    </div>
                                </div>
                                <div class="lesson-premium-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Módulo 4 -->
            <div class="timeline-module">
                <div class="timeline-marker">
                    <div class="marker-dot"></div>
                    <div class="marker-line"></div>
                </div>
                <div class="module-card-premium">
                    <div class="module-card-header" onclick="toggleModule(this)">
                        <div class="module-card-left">
                            <div class="module-card-number">04</div>
                            <div class="module-card-info">
                                <h3>Pensamiento Analítico</h3>
                                <p>Potencia tu capacidad de análisis y razonamiento</p>
                            </div>
                        </div>
                        <div class="module-card-right">
                            <div class="module-badge-info">
                                <i class="fas fa-video"></i>
                                <span>1 lección</span>
                            </div>
                            <i class="fas fa-chevron-down module-card-toggle"></i>
                        </div>
                    </div>
                    <div class="module-card-body">
                        <div class="lessons-premium-grid">
                            <div class="lesson-premium-card" data-video-id="362617177"
                                data-video-title="Pensamiento analítico" data-duration="20:00" data-level="Avanzado">
                                <div class="lesson-premium-thumb">
                                    <img src="https://vumbnail.com/362617177.jpg" alt="Lección">
                                    <div class="lesson-premium-overlay">
                                        <i class="fas fa-play"></i>
                                    </div>
                                    <div class="lesson-premium-duration">20:00</div>
                                </div>
                                <div class="lesson-premium-info">
                                    <h4>Pensamiento analítico</h4>
                                    <div class="lesson-premium-meta">
                                        <span><i class="far fa-clock"></i> 20 min</span>
                                        <span><i class="fas fa-chart-line"></i> Avanzado</span>
                                    </div>
                                </div>
                                <div class="lesson-premium-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Módulo 5 -->
            <div class="timeline-module">
                <div class="timeline-marker">
                    <div class="marker-dot"></div>
                    <div class="marker-line"></div>
                </div>
                <div class="module-card-premium">
                    <div class="module-card-header" onclick="toggleModule(this)">
                        <div class="module-card-left">
                            <div class="module-card-number">05</div>
                            <div class="module-card-info">
                                <h3>Lenguaje y Gramática</h3>
                                <p>Mejora tu redacción y conocimiento del idioma</p>
                            </div>
                        </div>
                        <div class="module-card-right">
                            <div class="module-badge-info">
                                <i class="fas fa-video"></i>
                                <span>2 lecciones</span>
                            </div>
                            <i class="fas fa-chevron-down module-card-toggle"></i>
                        </div>
                    </div>
                    <div class="module-card-body">
                        <div class="lessons-premium-grid">
                            <div class="lesson-premium-card" data-video-id="356512619"
                                data-video-title="Lenguaje escrito" data-duration="14:20" data-level="Intermedio">
                                <div class="lesson-premium-thumb">
                                    <img src="https://vumbnail.com/356512619.jpg" alt="Lección">
                                    <div class="lesson-premium-overlay">
                                        <i class="fas fa-play"></i>
                                    </div>
                                    <div class="lesson-premium-duration">14:20</div>
                                </div>
                                <div class="lesson-premium-info">
                                    <h4>Lenguaje escrito</h4>
                                    <div class="lesson-premium-meta">
                                        <span><i class="far fa-clock"></i> 14 min</span>
                                        <span><i class="fas fa-chart-line"></i> Intermedio</span>
                                    </div>
                                </div>
                                <div class="lesson-premium-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                            <div class="lesson-premium-card" data-video-id="357903613"
                                data-video-title="Estructura de la lengua" data-duration="19:15" data-level="Avanzado">
                                <div class="lesson-premium-thumb">
                                    <img src="https://vumbnail.com/357903613.jpg" alt="Lección">
                                    <div class="lesson-premium-overlay">
                                        <i class="fas fa-play"></i>
                                    </div>
                                    <div class="lesson-premium-duration">19:15</div>
                                </div>
                                <div class="lesson-premium-info">
                                    <h4>Estructura de la lengua</h4>
                                    <div class="lesson-premium-meta">
                                        <span><i class="far fa-clock"></i> 19 min</span>
                                        <span><i class="fas fa-chart-line"></i> Avanzado</span>
                                    </div>
                                </div>
                                <div class="lesson-premium-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Módulo 6 -->
            <div class="timeline-module">
                <div class="timeline-marker">
                    <div class="marker-dot"></div>
                </div>
                <div class="module-card-premium">
                    <div class="module-card-header" onclick="toggleModule(this)">
                        <div class="module-card-left">
                            <div class="module-card-number">06</div>
                            <div class="module-card-info">
                                <h3>Inglés para el Examen</h3>
                                <p>Vocabulario y comprensión básica del inglés</p>
                            </div>
                        </div>
                        <div class="module-card-right">
                            <div class="module-badge-info">
                                <i class="fas fa-video"></i>
                                <span>1 lección</span>
                            </div>
                            <i class="fas fa-chevron-down module-card-toggle"></i>
                        </div>
                    </div>
                    <div class="module-card-body">
                        <div class="lessons-premium-grid">
                            <div class="lesson-premium-card" data-video-id="383565235" data-video-title="Inglés"
                                data-duration="25:30" data-level="Principiante">
                                <div class="lesson-premium-thumb">
                                    <img src="https://vumbnail.com/383565235.jpg" alt="Lección">
                                    <div class="lesson-premium-overlay">
                                        <i class="fas fa-play"></i>
                                    </div>
                                    <div class="lesson-premium-duration">25:30</div>
                                </div>
                                <div class="lesson-premium-info">
                                    <h4>Inglés para el examen</h4>
                                    <div class="lesson-premium-meta">
                                        <span><i class="far fa-clock"></i> 25 min</span>
                                        <span><i class="fas fa-chart-line"></i> Principiante</span>
                                    </div>
                                </div>
                                <div class="lesson-premium-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- ========== MODAL DE VIDEO ========== -->
<div class="modal fade" id="videoModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content video-modal-premium">
            <div class="modal-header video-modal-premium-header">
                <div class="video-modal-premium-title">
                    <div class="video-modal-icon">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div class="video-modal-info">
                        <span id="videoTitle">Reproduciendo video</span>
                        <div class="video-modal-meta">
                            <span><i class="far fa-clock"></i> <span id="videoDuration">0:00</span></span>
                            <span><i class="fas fa-chart-line"></i> <span id="videoLevel">Principiante</span></span>
                        </div>
                    </div>
                </div>
                <div class="video-modal-actions">
                    <button class="video-modal-action" id="fullscreenBtn" title="Pantalla completa">
                        <i class="fas fa-expand"></i>
                    </button>
                    <button class="video-modal-action close-video" data-bs-dismiss="modal" title="Cerrar">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="video-premium-container" id="videoContainer">
                    <div class="video-premium-loading" id="videoLoading">
                        <div class="loading-spinner-premium"></div>
                        <p>Cargando video...</p>
                    </div>
                    <iframe id="videoFrame" src="" frameborder="0" allow="autoplay; fullscreen; picture-in-picture"
                        allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ========== ESTILOS ========== */
.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
}

/* Hero */
.hero-premium {
    position: relative;
    border-radius: 40px;
    overflow: hidden;
    margin-bottom: 40px;
}

.hero-premium-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
    overflow: hidden;
}

.hero-premium-gradient {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at 30% 50%, rgba(102, 126, 234, 0.3), transparent);
}

.hero-premium-particles {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.particle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    animation: float 15s infinite;
}

.particle:nth-child(1) {
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.particle:nth-child(2) {
    top: 60%;
    left: 20%;
    animation-delay: 2s;
    width: 6px;
    height: 6px;
}

.particle:nth-child(3) {
    top: 30%;
    left: 80%;
    animation-delay: 4s;
}

.particle:nth-child(4) {
    top: 70%;
    left: 70%;
    animation-delay: 1s;
    width: 3px;
    height: 3px;
}

.particle:nth-child(5) {
    top: 85%;
    left: 40%;
    animation-delay: 3s;
    width: 5px;
    height: 5px;
}

.particle:nth-child(6) {
    top: 10%;
    left: 50%;
    animation-delay: 5s;
}

@keyframes float {

    0%,
    100% {
        transform: translateY(0) translateX(0);
        opacity: 0.3;
    }

    50% {
        transform: translateY(-30px) translateX(20px);
        opacity: 0.8;
    }
}

.hero-premium-content {
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 60px;
    gap: 60px;
    z-index: 2;
}

.hero-premium-left {
    flex: 1;
}

.hero-premium-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 6px 16px;
    border-radius: 100px;
    margin-bottom: 24px;
}

.badge-dot {
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {

    0%,
    100% {
        opacity: 1;
        transform: scale(1);
    }

    50% {
        opacity: 0.5;
        transform: scale(1.2);
    }
}

.hero-premium-badge span {
    font-size: 0.75rem;
    font-weight: 500;
    color: #cbd5e1;
}

.hero-premium-title {
    font-size: 3rem;
    font-weight: 800;
    color: white;
    margin-bottom: 20px;
    line-height: 1.2;
}

.hero-premium-highlight {
    background: linear-gradient(135deg, #667eea, #764ba2);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-premium-description {
    font-size: 0.95rem;
    color: #94a3b8;
    line-height: 1.6;
    margin-bottom: 32px;
    max-width: 500px;
}

.hero-premium-stats {
    display: flex;
    gap: 40px;
    margin-bottom: 40px;
}

.hero-premium-stat {
    text-align: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: 800;
    color: white;
}

.stat-label {
    font-size: 0.7rem;
    color: #94a3b8;
}

.hero-premium-actions {
    display: flex;
    gap: 16px;
}

.hero-premium-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 28px;
    border-radius: 40px;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s;
    border: none;
}

.hero-premium-btn.primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.hero-premium-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
}

.hero-premium-btn.secondary {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.hero-premium-btn.secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.hero-premium-right {
    width: 320px;
}

.hero-premium-card {
    position: relative;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border-radius: 32px;
    padding: 30px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    overflow: hidden;
}

.card-glow {
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(102, 126, 234, 0.2), transparent);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

.card-content {
    position: relative;
    text-align: center;
    z-index: 1;
}

.card-icon {
    font-size: 3rem;
    color: white;
    margin-bottom: 20px;
}

.card-text p {
    font-size: 0.9rem;
    font-weight: 600;
    color: white;
    margin-bottom: 6px;
}

.card-text span {
    font-size: 0.7rem;
    color: #94a3b8;
}

.card-features {
    margin-top: 24px;
    text-align: left;
}

.card-feature {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
    font-size: 0.75rem;
    color: #cbd5e1;
}

.card-feature i {
    color: #10b981;
    font-size: 0.8rem;
    width: 18px;
}

/* Premium Banner */
.premium-banner-premium {
    background: linear-gradient(135deg, #fffbeb, #fef3c7);
    border-radius: 24px;
    margin-bottom: 48px;
    border: 1px solid #fde68a;
}

.premium-banner-premium-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 32px;
    flex-wrap: wrap;
    gap: 20px;
}

.banner-icon {
    width: 56px;
    height: 56px;
    background: rgba(245, 158, 11, 0.15);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #d97706;
}

.banner-info {
    flex: 1;
}

.banner-info h4 {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 4px;
    color: #0f172a;
}

.banner-info p {
    font-size: 0.75rem;
    color: #64748b;
}

.banner-btn {
    background: white;
    border: none;
    padding: 10px 24px;
    border-radius: 40px;
    font-size: 0.8rem;
    font-weight: 600;
    color: #d97706;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.banner-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Modules Wrapper */
.modules-wrapper {
    background: white;
    border-radius: 32px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
}

.modules-header-premium {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 28px 32px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    flex-wrap: wrap;
    gap: 16px;
}

.modules-header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.modules-header-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
}

.modules-header-left h2 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 4px;
    color: #0f172a;
}

.modules-header-left p {
    font-size: 0.8rem;
    color: #64748b;
}

.modules-badge-premium {
    background: #e8f0fe;
    padding: 8px 18px;
    border-radius: 40px;
    font-size: 0.75rem;
    font-weight: 600;
    color: #1e40af;
}

.modules-badge-premium i {
    margin-right: 8px;
    color: #10b981;
}

/* Timeline */
.modules-timeline {
    padding: 20px 0;
}

.timeline-module {
    position: relative;
}

.timeline-marker {
    position: absolute;
    left: 40px;
    top: 0;
    bottom: 0;
    width: 2px;
    z-index: 1;
}

.marker-dot {
    position: absolute;
    top: 32px;
    left: -5px;
    width: 12px;
    height: 12px;
    background: #667eea;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
}

.marker-line {
    position: absolute;
    top: 50px;
    left: 0;
    width: 2px;
    height: calc(100% - 50px);
    background: linear-gradient(to bottom, #667eea, #e2e8f0);
}

.timeline-module:last-child .marker-line {
    display: none;
}

/* Module Card */
.module-card-premium {
    margin-left: 80px;
    margin-bottom: 20px;
    background: white;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    transition: all 0.3s;
}

.module-card-premium:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
}

.module-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    cursor: pointer;
    transition: background 0.2s;
}

.module-card-header:hover {
    background: #f8fafc;
}

.module-card-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.module-card-number {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1.1rem;
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.module-card-info h3 {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 4px;
    color: #0f172a;
}

.module-card-info p {
    font-size: 0.75rem;
    color: #64748b;
}

.module-card-right {
    display: flex;
    align-items: center;
    gap: 20px;
}

.module-badge-info {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    color: #64748b;
}

.module-badge-info i {
    color: #667eea;
}

.module-card-toggle {
    color: #94a3b8;
    transition: transform 0.3s;
    font-size: 0.9rem;
}

.module-card-premium.open .module-card-toggle {
    transform: rotate(180deg);
}

.module-card-body {
    display: none;
    padding: 20px 24px;
    background: #fafbfc;
    border-top: 1px solid #e2e8f0;
}

.module-card-premium.open .module-card-body {
    display: block;
}

/* Lessons Grid */
.lessons-premium-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 16px;
}

.lesson-premium-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 12px;
    background: white;
    border-radius: 16px;
    cursor: pointer;
    transition: all 0.3s;
    border: 1px solid #e2e8f0;
}

.lesson-premium-card:hover {
    transform: translateX(8px);
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

.lesson-premium-thumb {
    position: relative;
    width: 100px;
    height: 65px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
}

.lesson-premium-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.lesson-premium-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 30px;
    height: 30px;
    background: rgba(0, 0, 0, 0.7);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.lesson-premium-card:hover .lesson-premium-overlay {
    opacity: 1;
}

.lesson-premium-overlay i {
    color: white;
    font-size: 0.7rem;
    margin-left: 2px;
}

.lesson-premium-duration {
    position: absolute;
    bottom: 6px;
    right: 6px;
    background: rgba(0, 0, 0, 0.7);
    padding: 2px 6px;
    border-radius: 6px;
    font-size: 0.6rem;
    font-weight: 600;
    color: white;
}

.lesson-premium-info {
    flex: 1;
}

.lesson-premium-info h4 {
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 6px;
    color: #0f172a;
}

.lesson-premium-meta {
    display: flex;
    gap: 12px;
    font-size: 0.6rem;
    color: #94a3b8;
}

.lesson-premium-meta i {
    margin-right: 4px;
}

.lesson-premium-arrow {
    color: #cbd5e1;
    transition: all 0.2s;
}

.lesson-premium-card:hover .lesson-premium-arrow {
    color: #667eea;
    transform: translateX(4px);
}

/* Modal Video */
.video-modal-premium {
    background: #0a0a0a;
    border-radius: 24px;
    overflow: hidden;
    border: none;
}

.video-modal-premium-header {
    background: #1a1a2e;
    padding: 20px 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #2a2a3e;
}

.video-modal-premium-title {
    display: flex;
    align-items: center;
    gap: 18px;
}

.video-modal-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.video-modal-icon i {
    font-size: 1.2rem;
    color: white;
}

.video-modal-info span {
    font-size: 1rem;
    font-weight: 600;
    color: white;
}

.video-modal-meta {
    display: flex;
    gap: 16px;
    margin-top: 4px;
    font-size: 0.7rem;
    color: #94a3b8;
}

.video-modal-meta i {
    margin-right: 4px;
}

.video-modal-actions {
    display: flex;
    gap: 10px;
}

.video-modal-action {
    background: rgba(255, 255, 255, 0.08);
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 12px;
    color: white;
    cursor: pointer;
    transition: all 0.2s;
}

.video-modal-action:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: scale(1.05);
}

.video-premium-container {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%;
    background: #000;
}

.video-premium-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
}

.video-premium-loading {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #0a0a0a;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 16px;
    z-index: 10;
}

.loading-spinner-premium {
    width: 50px;
    height: 50px;
    border: 3px solid rgba(102, 126, 234, 0.2);
    border-top-color: #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.video-premium-loading p {
    color: #94a3b8;
    font-size: 0.85rem;
}

/* Responsive */
@media (max-width: 968px) {
    .hero-premium-content {
        flex-direction: column;
        text-align: center;
        padding: 40px;
    }

    .hero-premium-right {
        width: 100%;
        max-width: 350px;
    }

    .hero-premium-stats {
        justify-content: center;
    }

    .hero-premium-actions {
        justify-content: center;
    }

    .timeline-marker {
        left: 20px;
    }

    .module-card-premium {
        margin-left: 50px;
    }

    .lessons-premium-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .dashboard-container {
        padding: 16px;
    }

    .hero-premium-title {
        font-size: 2rem;
    }

    .modules-header-premium {
        flex-direction: column;
        align-items: flex-start;
    }

    .module-card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .module-card-right {
        width: 100%;
        justify-content: space-between;
    }

    .module-card-body {
        padding: 16px;
    }

    .lesson-premium-card {
        flex-wrap: wrap;
    }

    .lesson-premium-thumb {
        width: 100%;
        height: 140px;
    }

    .video-modal-premium-header {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
        padding: 16px 20px;
    }

    .video-modal-premium-title {
        width: 100%;
    }

    .video-modal-actions {
        width: 100%;
        justify-content: flex-end;
    }

    .card-features {
        text-align: center;
    }

    .card-feature {
        justify-content: center;
    }
}

/* Dark Mode */
body.dark-mode .modules-wrapper {
    background: #1e293b;
    border-color: #334155;
}

body.dark-mode .modules-header-premium {
    background: #0f172a;
    border-color: #334155;
}

body.dark-mode .modules-header-left h2,
body.dark-mode .module-card-info h3,
body.dark-mode .lesson-premium-info h4 {
    color: #f1f5f9;
}

body.dark-mode .modules-header-left p,
body.dark-mode .module-card-info p,
body.dark-mode .lesson-premium-meta {
    color: #94a3b8;
}

body.dark-mode .module-card-premium {
    background: #1e293b;
    border-color: #334155;
}

body.dark-mode .module-card-body {
    background: #0f172a;
    border-color: #334155;
}

body.dark-mode .lesson-premium-card {
    background: #1e293b;
    border-color: #334155;
}

body.dark-mode .lesson-premium-card:hover {
    background: #0f172a;
}

body.dark-mode .modules-badge-premium {
    background: #1e3a8a;
    color: #bfdbfe;
}
</style>
@endsection

@push('scripts')
<script>
function toggleModule(element) {
    const module = element.closest('.module-card-premium');
    module.classList.toggle('open');
}

function actualizarEstadisticas() {
    const totalLecciones = document.querySelectorAll('.lesson-premium-card').length;
    document.getElementById('totalLessons').innerText = totalLecciones;
    document.getElementById('totalHours').innerText = 2.5;
}

function mostrarLoading(show) {
    const loading = document.getElementById('videoLoading');
    const iframe = document.getElementById('videoFrame');
    if (loading) loading.style.display = show ? 'flex' : 'none';
    if (iframe) iframe.style.display = show ? 'none' : 'block';
}

function reproducirVideo(videoId, titulo, duracion, nivel) {
    const modalElement = document.getElementById('videoModal');
    const titleSpan = document.getElementById('videoTitle');
    const durationSpan = document.getElementById('videoDuration');
    const levelSpan = document.getElementById('videoLevel');
    const iframe = document.getElementById('videoFrame');

    titleSpan.textContent = titulo;
    durationSpan.textContent = duracion;
    levelSpan.textContent = nivel;

    mostrarLoading(true);

    const videoUrl = `https://player.vimeo.com/video/${videoId}?autoplay=1&title=0&byline=0&portrait=0&badge=0`;

    iframe.src = videoUrl;

    iframe.onload = function() {
        mostrarLoading(false);
    };

    const modal = new bootstrap.Modal(modalElement, {
        backdrop: 'static',
        keyboard: true
    });
    modal.show();

    modalElement.addEventListener('hidden.bs.modal', function() {
        iframe.src = '';
    }, {
        once: true
    });
}

function irAClasesPremium() {
    window.location.href = "{{ route('estudiante.clases-premium') }}";
}

document.getElementById('fullscreenBtn')?.addEventListener('click', function() {
    const container = document.getElementById('videoContainer');
    if (container.requestFullscreen) {
        container.requestFullscreen();
    } else if (container.webkitRequestFullscreen) {
        container.webkitRequestFullscreen();
    } else if (container.msRequestFullscreen) {
        container.msRequestFullscreen();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    actualizarEstadisticas();

    document.querySelectorAll('.lesson-premium-card').forEach(lesson => {
        const videoId = lesson.dataset.videoId;
        const videoTitle = lesson.dataset.videoTitle;
        const duration = lesson.dataset.duration;
        const level = lesson.dataset.level;

        lesson.addEventListener('click', function(e) {
            e.stopPropagation();
            reproducirVideo(videoId, videoTitle, duration, level);
        });
    });
});
</script>
@endpush