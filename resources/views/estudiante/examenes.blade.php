{{-- resources/views/estudiante/examenes.blade.php --}}
@extends('estudiante.layouts.app')

@section('title', 'Mis Exámenes | SAINS')

@section('content')
<div class="examenes-container">
    <!-- Header con estadísticas -->
    <div class="examenes-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="header-title">
                    <i class="fas fa-file-alt me-3"></i>
                    Mis Exámenes
                </h1>
                <p class="header-subtitle">Revisa el historial completo de todos tus intentos de examen</p>
            </div>
            <div class="header-stats" id="headerStats">
                <div class="stat-card-mini">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Promedio general</span>
                        <span class="stat-value" id="promedioGeneral">0%</span>
                    </div>
                </div>
                <div class="stat-card-mini">
                    <div class="stat-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Mejor puntaje</span>
                        <span class="stat-value" id="mejorPuntaje">0%</span>
                    </div>
                </div>
                <div class="stat-card-mini">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Intentos aprobados</span>
                        <span class="stat-value" id="aprobados">0</span>
                    </div>
                </div>
                <div class="stat-card-mini">
                    <div class="stat-icon">
                        <i class="fas fa-chart-simple"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Total intentos</span>
                        <span class="stat-value" id="totalExamenes">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="filtros-section">
        <div class="filtros-container">
            <div class="filtros-left">
                <button class="filtro-btn active" data-filtro="todos" onclick="filtrarExamenes('todos')">
                    <i class="fas fa-list me-2"></i>Todos los intentos
                </button>
                <button class="filtro-btn" data-filtro="aprobados" onclick="filtrarExamenes('aprobados')">
                    <i class="fas fa-check-circle me-2"></i>Solo aprobados
                </button>
                <button class="filtro-btn" data-filtro="reprobados" onclick="filtrarExamenes('reprobados')">
                    <i class="fas fa-times-circle me-2"></i>Solo por mejorar
                </button>
            </div>
            <div class="filtros-right">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Buscar por fecha, materia o curso..." onkeyup="buscarExamen()">
                </div>
            </div>
        </div>
    </div>

    <!-- Loading state -->
    <div id="loadingState" class="loading-state">
        <div class="loading-spinner"></div>
        <p>Cargando tu historial de exámenes...</p>
    </div>

    <!-- Empty state -->
    <div id="emptyState" class="empty-state" style="display: none;">
        <div class="empty-icon">
            <i class="fas fa-inbox"></i>
        </div>
        <h3>No hay exámenes registrados</h3>
        <p>Aún no has realizado ningún examen. ¡Es momento de empezar!</p>
        <a href="{{ route('estudiante.simulador') }}" class="btn-empty-action">
            <i class="fas fa-play me-2"></i>Iniciar simulador
        </a>
    </div>

    <!-- Error state -->
    <div id="errorState" class="error-state" style="display: none;">
        <div class="error-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3>Error al cargar los exámenes</h3>
        <p>Hubo un problema al cargar tu historial. Por favor, intenta de nuevo.</p>
        <button class="btn-error-action" onclick="cargarExamenes()">
            <i class="fas fa-sync-alt me-2"></i>Reintentar
        </button>
    </div>

    <!-- Tarjetas de exámenes -->
    <div id="examenesGrid" class="examenes-grid" style="display: none;"></div>
</div>

<!-- MODAL DE RESULTADOS -->
<div id="resultadosModal" class="custom-modal" style="display: none;">
    <div class="custom-modal-overlay"></div>
    <div class="custom-modal-container">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <div class="custom-modal-header-left">
                    <div class="header-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h5 id="modalTituloExamen">Resultados del Examen</h5>
                        <p id="modalFechaResultado"></p>
                    </div>
                </div>
                <button type="button" class="custom-modal-close" id="closeModalBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="custom-modal-body">
                <!-- Calificación principal -->
                <div class="resultado-principal">
                    <div class="calificacion-circle" id="modalCalificacionResultado">
                        <span class="calificacion-value">0%</span>
                    </div>
                    <h4 id="modalMensajeResultado"></h4>
                </div>

                <!-- Grid de información -->
                <div class="info-grid">
                    <div class="info-card">
                        <i class="fas fa-calendar-alt"></i>
                        <div class="info-card-content">
                            <span class="info-card-label">Fecha</span>
                            <span class="info-card-value" id="modalFechaInfo">--</span>
                        </div>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-clock"></i>
                        <div class="info-card-content">
                            <span class="info-card-label">Hora inicio</span>
                            <span class="info-card-value" id="modalHoraInfo">--</span>
                        </div>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-stop-circle"></i>
                        <div class="info-card-content">
                            <span class="info-card-label">Hora fin</span>
                            <span class="info-card-value" id="modalHoraFinInfo">--</span>
                        </div>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-hourglass-half"></i>
                        <div class="info-card-content">
                            <span class="info-card-label">Tiempo utilizado</span>
                            <span class="info-card-value" id="modalTiempoInfo">--</span>
                        </div>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-hashtag"></i>
                        <div class="info-card-content">
                            <span class="info-card-label">Número de intento</span>
                            <span class="info-card-value" id="modalIntentoInfo">--</span>
                        </div>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-trophy"></i>
                        <div class="info-card-content">
                            <span class="info-card-label">Mejor puntaje</span>
                            <span class="info-card-value" id="modalMejorPuntaje">0%</span>
                        </div>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-chart-line"></i>
                        <div class="info-card-content">
                            <span class="info-card-label">Promedio general</span>
                            <span class="info-card-value" id="modalPromedio">0%</span>
                        </div>
                    </div>
                </div>

                <!-- Barra de progreso -->
                <div class="progreso-section">
                    <h6><i class="fas fa-chart-simple me-2"></i>Progreso alcanzado</h6>
                    <div class="progress-bar-container">
                        <div class="progress-bar-custom" id="modalProgressBar" style="width: 0%">
                            <span id="modalProgressText">0%</span>
                        </div>
                    </div>
                </div>

                <!-- Recomendaciones -->
                <div class="recomendaciones-section" id="modalRecomendaciones">
                </div>

                <!-- Historial de intentos (colapsable) -->
                <div class="historial-section" id="modalHistorialIntentos" style="display: none;">
                    <div class="historial-header" onclick="toggleHistorial()">
                        <i class="fas fa-history me-2"></i>
                        <span>Otros intentos de este examen</span>
                        <i class="fas fa-chevron-down ms-auto" id="historialIcon"></i>
                    </div>
                    <div class="historial-body" id="historialBody">
                        <div class="table-responsive">
                            <table class="historial-table">
                                <thead>
                                    <tr>
                                        <th># Intento</th>
                                        <th>Fecha</th>
                                        <th>Calificación</th>
                                        <th>Tiempo</th>
                                    </tr>
                                </thead>
                                <tbody id="modalTablaIntentos">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button class="btn-cancel" id="cancelModalBtn">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
                <button class="btn-submit" id="btnNuevoIntentoModal">
                    <i class="fas fa-redo-alt me-2"></i>Nuevo intento
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Contenedor principal */
.examenes-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

/* Header */
.examenes-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 24px;
    padding: 32px;
    margin-bottom: 24px;
    color: white;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.header-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0 0 8px 0;
}

.header-subtitle {
    opacity: 0.9;
    margin: 0;
}

.header-stats {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.stat-card-mini {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.3s;
    min-width: 130px;
}

.stat-card-mini:hover {
    background: rgba(255,255,255,0.25);
    transform: translateY(-2px);
}

.stat-icon {
    font-size: 1.5rem;
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-label {
    font-size: 0.7rem;
    opacity: 0.8;
}

.stat-value {
    font-size: 1.3rem;
    font-weight: 700;
}

/* Filtros */
.filtros-section {
    background: white;
    border-radius: 16px;
    padding: 16px 20px;
    margin-bottom: 24px;
    border: 1px solid #e2e8f0;
}

.filtros-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.filtros-left {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.filtro-btn {
    background: #f1f5f9;
    border: none;
    padding: 8px 20px;
    border-radius: 40px;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s;
    cursor: pointer;
}

.filtro-btn:hover {
    background: #e2e8f0;
    transform: translateY(-1px);
}

.filtro-btn.active {
    background: #667eea;
    color: white;
}

.search-box {
    display: flex;
    align-items: center;
    background: #f1f5f9;
    border-radius: 40px;
    padding: 8px 16px;
    gap: 8px;
}

.search-box i {
    color: #94a3b8;
}

.search-box input {
    border: none;
    background: none;
    outline: none;
    font-size: 0.85rem;
    width: 220px;
}

.search-box input::placeholder {
    color: #94a3b8;
}

/* Grid de exámenes */
.examenes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 20px;
}

/* Tarjeta de examen */
.examen-card {
    background: white;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    transition: all 0.3s;
    cursor: pointer;
}

.examen-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.1);
}

.card-header {
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    border-bottom: 1px solid #e2e8f0;
    flex-wrap: wrap;
    gap: 10px;
}

.card-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.card-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
}

.card-title h3 {
    font-size: 1rem;
    font-weight: 700;
    margin: 0 0 4px 0;
    color: #1e293b;
}

.card-title p {
    font-size: 0.7rem;
    color: #64748b;
    margin: 0;
}

.card-badges {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
}

.tipo-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

.materia-badge {
    background: #dbeafe;
    color: #1e40af;
}

.curso-badge {
    background: #d1fae5;
    color: #065f46;
}

.simulacion-badge {
    background: #e9d5ff;
    color: #6b21a5;
}

.card-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.badge-aprobado {
    background: #d1fae5;
    color: #065f46;
}

.badge-reprobado {
    background: #fee2e2;
    color: #991b1b;
}

.card-body {
    padding: 20px;
    display: flex;
    justify-content: space-between;
    gap: 16px;
}

.card-info {
    flex: 1;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    font-size: 0.85rem;
}

.info-item i {
    width: 20px;
    color: #667eea;
}

.info-label {
    color: #64748b;
}

.info-value {
    color: #1e293b;
    font-weight: 500;
}

.card-score {
    text-align: center;
    min-width: 80px;
}

.score-circle {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
    font-weight: 700;
    font-size: 1.1rem;
    margin: 0 auto 8px;
}

.score-aprobado {
    background: #d1fae5;
    color: #065f46;
}

.score-reprobado {
    background: #fee2e2;
    color: #991b1b;
}

.score-label {
    font-size: 0.7rem;
    color: #64748b;
}

.card-footer {
    padding: 16px 20px;
    background: #f8fafc;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-ver-detalle {
    background: none;
    border: none;
    color: #667eea;
    font-weight: 500;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-ver-detalle:hover {
    color: #5a67d8;
}

/* Estados de carga */
.loading-state {
    text-align: center;
    padding: 60px;
    background: white;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
}

.loading-spinner {
    width: 50px;
    height: 50px;
    border: 3px solid #e2e8f0;
    border-top-color: #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 16px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.empty-state, .error-state {
    text-align: center;
    padding: 60px;
    background: white;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
}

.empty-icon, .error-icon {
    font-size: 4rem;
    margin-bottom: 16px;
}

.empty-icon { color: #cbd5e1; }
.error-icon { color: #ef4444; }

.empty-state h3, .error-state h3 {
    font-size: 1.3rem;
    margin-bottom: 8px;
    color: #1e293b;
}

.empty-state p, .error-state p {
    color: #64748b;
    margin-bottom: 24px;
}

.btn-empty-action, .btn-error-action {
    padding: 12px 28px;
    border-radius: 40px;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s;
    text-decoration: none;
}

.btn-empty-action {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.btn-error-action {
    background: #ef4444;
    color: white;
    border: none;
    cursor: pointer;
}

.btn-empty-action:hover, .btn-error-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    color: white;
}

/* MODAL */
.custom-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1050;
    display: none;
}

.custom-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
}

.custom-modal-container {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.custom-modal-content {
    position: relative;
    max-width: 900px;
    width: 100%;
    background: white;
    border-radius: 32px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
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

.custom-modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 24px 32px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
}

.custom-modal-header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.header-icon {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.2);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.header-icon i {
    font-size: 1.5rem;
}

.custom-modal-header-left h5 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.custom-modal-header-left p {
    margin: 4px 0 0;
    font-size: 0.75rem;
    opacity: 0.9;
}

.custom-modal-close {
    background: rgba(255,255,255,0.2);
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 12px;
    color: white;
    transition: all 0.2s;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.custom-modal-close:hover {
    background: rgba(255,255,255,0.3);
    transform: scale(1.05);
}

.custom-modal-body {
    padding: 32px;
    max-height: calc(90vh - 140px);
    overflow-y: auto;
}

.custom-modal-body::-webkit-scrollbar {
    width: 6px;
}

.custom-modal-body::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.custom-modal-body::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.custom-modal-body::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.resultado-principal {
    text-align: center;
    margin-bottom: 32px;
}

.calificacion-circle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 140px;
    height: 140px;
    border-radius: 50%;
    font-weight: 700;
    font-size: 2rem;
    margin-bottom: 16px;
    transition: all 0.3s;
}

.calificacion-value {
    font-size: 2rem;
}

#modalMensajeResultado {
    margin: 0;
    font-size: 1.1rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
    margin-bottom: 32px;
}

.info-card {
    background: #f8fafc;
    border-radius: 16px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.2s;
}

.info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.info-card i {
    font-size: 1.5rem;
    color: #667eea;
    width: 32px;
}

.info-card-content {
    flex: 1;
}

.info-card-label {
    display: block;
    font-size: 0.7rem;
    color: #64748b;
    margin-bottom: 4px;
}

.info-card-value {
    display: block;
    font-size: 1rem;
    font-weight: 600;
    color: #1e293b;
}

.progreso-section {
    margin-bottom: 32px;
}

.progreso-section h6 {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 12px;
    color: #1e293b;
}

.progress-bar-container {
    background: #e2e8f0;
    border-radius: 20px;
    overflow: hidden;
    height: 40px;
}

.progress-bar-custom {
    background: linear-gradient(90deg, #10b981, #34d399);
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    transition: width 0.5s ease;
    border-radius: 20px;
}

.recomendaciones-section {
    background: #fef3c7;
    border-left: 4px solid #f59e0b;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 24px;
    color: #92400e;
}

.historial-section {
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
}

.historial-header {
    background: #f8fafc;
    padding: 16px 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    transition: all 0.2s;
}

.historial-header:hover {
    background: #f1f5f9;
}

.historial-header i:first-child {
    color: #667eea;
}

.historial-header span {
    flex: 1;
    font-weight: 500;
    color: #1e293b;
}

.historial-header i:last-child {
    transition: transform 0.3s;
    color: #94a3b8;
}

.historial-body {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out;
}

.historial-body.active {
    max-height: 300px;
    overflow-y: auto;
}

.historial-table {
    width: 100%;
    border-collapse: collapse;
}

.historial-table thead {
    background: #f8fafc;
}

.historial-table th,
.historial-table td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.historial-table th {
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748b;
}

.historial-table td {
    font-size: 0.85rem;
    color: #1e293b;
}

.badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.bg-success {
    background: #d1fae5;
    color: #065f46;
}

.bg-warning {
    background: #fed7aa;
    color: #92400e;
}

.custom-modal-footer {
    padding: 20px 32px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    background: white;
}

.btn-cancel, .btn-submit {
    padding: 10px 24px;
    border-radius: 12px;
    font-weight: 500;
    transition: all 0.2s;
    cursor: pointer;
    border: none;
}

.btn-cancel {
    background: #f1f5f9;
    color: #64748b;
}

.btn-cancel:hover {
    background: #e2e8f0;
    transform: translateY(-1px);
}

.btn-submit {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102,126,234,0.4);
}

/* Responsive */
@media (max-width: 768px) {
    .examenes-container {
        padding: 12px;
    }
    
    .examenes-grid {
        grid-template-columns: 1fr;
    }
    
    .header-content {
        flex-direction: column;
        text-align: center;
    }
    
    .filtros-container {
        flex-direction: column;
    }
    
    .filtros-left {
        justify-content: center;
    }
    
    .header-stats {
        justify-content: center;
    }
    
    .stat-card-mini {
        min-width: 110px;
        padding: 8px 12px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .custom-modal-content {
        margin: 16px;
    }
    
    .custom-modal-body {
        padding: 20px;
    }
    
    .custom-modal-header {
        padding: 20px;
    }
}

/* Dark mode */
body.dark-mode .filtros-section,
body.dark-mode .examen-card,
body.dark-mode .empty-state,
body.dark-mode .loading-state,
body.dark-mode .error-state,
body.dark-mode .custom-modal-content {
    background: #1e293b;
    border-color: #334155;
}

body.dark-mode .card-title h3,
body.dark-mode .info-value,
body.dark-mode .info-card-value,
body.dark-mode .historial-header span,
body.dark-mode .historial-table td,
body.dark-mode .progreso-section h6 {
    color: #f1f5f9;
}

body.dark-mode .card-footer,
body.dark-mode .historial-header,
body.dark-mode .historial-table thead {
    background: #0f172a;
}

body.dark-mode .filtro-btn {
    background: #334155;
    color: #e2e8f0;
}

body.dark-mode .search-box {
    background: #334155;
}

body.dark-mode .search-box input {
    color: white;
}

body.dark-mode .info-card {
    background: #334155;
}

body.dark-mode .info-card-label {
    color: #94a3b8;
}

body.dark-mode .custom-modal-footer {
    border-top-color: #334155;
    background: #1e293b;
}

body.dark-mode .btn-cancel {
    background: #334155;
    color: #e2e8f0;
}

body.dark-mode .btn-cancel:hover {
    background: #475569;
}

body.dark-mode .recomendaciones-section {
    background: #451a03;
    border-left-color: #f59e0b;
    color: #fed7aa;
}

body.dark-mode .progress-bar-container {
    background: #334155;
}

body.dark-mode .custom-modal-body::-webkit-scrollbar-track {
    background: #334155;
}

body.dark-mode .custom-modal-body::-webkit-scrollbar-thumb {
    background: #475569;
}

body.dark-mode .materia-badge {
    background: #1e3a8a;
    color: #bfdbfe;
}

body.dark-mode .curso-badge {
    background: #064e3b;
    color: #a7f3d0;
}

body.dark-mode .simulacion-badge {
    background: #4c1d95;
    color: #e9d5ff;
}
</style>
@endsection

@push('scripts')
<script>
// Variables globales
let todosExamenes = [];
let filtroActual = 'todos';
let examenesAgrupados = {};

// Funciones del modal manual
function abrirModal() {
    const modal = document.getElementById('resultadosModal');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    const modal = document.getElementById('resultadosModal');
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

function toggleHistorial() {
    const body = document.getElementById('historialBody');
    const icon = document.getElementById('historialIcon');
    
    if (body.classList.contains('active')) {
        body.classList.remove('active');
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    } else {
        body.classList.add('active');
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    }
}

// Función para obtener icono según tipo
function getIconoTipo(tipo) {
    const iconos = {
        'Materia': 'fa-book',
        'materia': 'fa-book',
        'Curso': 'fa-graduation-cap',
        'curso': 'fa-graduation-cap',
        'Simulación': 'fa-chart-line',
        'simulacion': 'fa-chart-line',
        'Simulador': 'fa-chart-line',
        'simulador': 'fa-chart-line'
    };
    return iconos[tipo] || 'fa-file-alt';
}

// Función para obtener color según tipo
function getColorTipo(tipo) {
    const colores = {
        'Materia': 'linear-gradient(135deg, #3b82f6, #1d4ed8)',
        'materia': 'linear-gradient(135deg, #3b82f6, #1d4ed8)',
        'Curso': 'linear-gradient(135deg, #10b981, #047857)',
        'curso': 'linear-gradient(135deg, #10b981, #047857)',
        'Simulación': 'linear-gradient(135deg, #667eea, #764ba2)',
        'simulacion': 'linear-gradient(135deg, #667eea, #764ba2)',
        'Simulador': 'linear-gradient(135deg, #667eea, #764ba2)',
        'simulador': 'linear-gradient(135deg, #667eea, #764ba2)'
    };
    return colores[tipo] || 'linear-gradient(135deg, #667eea, #764ba2)';
}

// Función para obtener badge según tipo
function getBadgeTipo(tipo) {
    const badges = {
        'Materia': '<span class="tipo-badge materia-badge"><i class="fas fa-book me-1"></i>Materia</span>',
        'materia': '<span class="tipo-badge materia-badge"><i class="fas fa-book me-1"></i>Materia</span>',
        'Curso': '<span class="tipo-badge curso-badge"><i class="fas fa-graduation-cap me-1"></i>Curso</span>',
        'curso': '<span class="tipo-badge curso-badge"><i class="fas fa-graduation-cap me-1"></i>Curso</span>',
        'Simulación': '<span class="tipo-badge simulacion-badge"><i class="fas fa-chart-line me-1"></i>Simulador</span>',
        'simulacion': '<span class="tipo-badge simulacion-badge"><i class="fas fa-chart-line me-1"></i>Simulador</span>',
        'Simulador': '<span class="tipo-badge simulacion-badge"><i class="fas fa-chart-line me-1"></i>Simulador</span>',
        'simulador': '<span class="tipo-badge simulacion-badge"><i class="fas fa-chart-line me-1"></i>Simulador</span>'
    };
    return badges[tipo] || '<span class="tipo-badge simulacion-badge"><i class="fas fa-chart-line me-1"></i>Simulador</span>';
}

// Función para obtener nombre del examen según tipo
function getNombreExamen(tipo) {
    const nombres = {
        'Materia': 'Examen por Materia',
        'materia': 'Examen por Materia',
        'Curso': 'Examen por Curso',
        'curso': 'Examen por Curso',
        'Simulación': 'Simulador SAINS',
        'simulacion': 'Simulador SAINS',
        'Simulador': 'Simulador SAINS',
        'simulador': 'Simulador SAINS'
    };
    return nombres[tipo] || 'Examen SAINS';
}

// Cargar exámenes
function cargarExamenes() {
    const loadingState = document.getElementById('loadingState');
    const examenesGrid = document.getElementById('examenesGrid');
    const emptyState = document.getElementById('emptyState');
    const errorState = document.getElementById('errorState');
    
    loadingState.style.display = 'block';
    examenesGrid.style.display = 'none';
    emptyState.style.display = 'none';
    errorState.style.display = 'none';
    
    fetch('{{ route("estudiante.historial.examenes") }}', {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        loadingState.style.display = 'none';
        
        if (data.success && data.examenes && data.examenes.length > 0) {
            todosExamenes = data.examenes;
            console.log('Total de intentos cargados:', todosExamenes.length);
            agruparExamenes();
            actualizarEstadisticas();
            renderizarExamenes();
            examenesGrid.style.display = 'grid';
        } else {
            emptyState.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        loadingState.style.display = 'none';
        errorState.style.display = 'block';
    });
}

// Agrupar exámenes por examen_id para el historial
function agruparExamenes() {
    examenesAgrupados = {};
    todosExamenes.forEach(examen => {
        const examenId = examen.examen_id;
        if (!examenesAgrupados[examenId]) {
            examenesAgrupados[examenId] = [];
        }
        examenesAgrupados[examenId].push(examen);
    });
    
    // Ordenar cada grupo por intento
    for (let key in examenesAgrupados) {
        examenesAgrupados[key].sort((a, b) => (a.intento || 1) - (b.intento || 1));
    }
}

// Actualizar estadísticas del header - AHORA CON TODOS LOS INTENTOS
function actualizarEstadisticas() {
    if (!todosExamenes || todosExamenes.length === 0) {
        document.getElementById('promedioGeneral').innerText = '0%';
        document.getElementById('mejorPuntaje').innerText = '0%';
        document.getElementById('aprobados').innerText = '0';
        document.getElementById('totalExamenes').innerText = '0';
        return;
    }
    
    // Usar TODOS los exámenes registrados
    const calificaciones = todosExamenes.map(e => parseFloat(e.calificacion) || 0);
    const promedio = calificaciones.reduce((a, b) => a + b, 0) / calificaciones.length;
    const mejor = Math.max(...calificaciones);
    const aprobados = todosExamenes.filter(e => (parseFloat(e.calificacion) || 0) >= 70).length;
    
    document.getElementById('promedioGeneral').innerText = Math.round(promedio) + '%';
    document.getElementById('mejorPuntaje').innerText = Math.round(mejor) + '%';
    document.getElementById('aprobados').innerText = aprobados;
    document.getElementById('totalExamenes').innerText = todosExamenes.length;
}

// Formatear fecha
function formatearFecha(fechaStr) {
    if (!fechaStr || fechaStr === '—' || fechaStr === 'Invalid Date') {
        return 'Fecha no disponible';
    }
    
    try {
        if (fechaStr.match(/^\d{4}-\d{2}-\d{2}$/)) {
            const [year, month, day] = fechaStr.split('-');
            const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
            return `${parseInt(day)} de ${meses[parseInt(month) - 1]} de ${year}`;
        }
        
        const fecha = new Date(fechaStr);
        if (!isNaN(fecha.getTime())) {
            const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
            return `${fecha.getDate()} de ${meses[fecha.getMonth()]} de ${fecha.getFullYear()}`;
        }
        
        return 'Fecha no disponible';
    } catch (e) {
        return 'Fecha no disponible';
    }
}

// Formatear hora
function formatearHora(horaStr) {
    if (!horaStr || horaStr === '—') return '--:--';
    if (horaStr.includes(':')) {
        const partes = horaStr.split(':');
        return `${partes[0].padStart(2, '0')}:${partes[1].padStart(2, '0')}`;
    }
    return horaStr;
}

// Renderizar tarjetas - VERSIÓN CORREGIDA (Muestra TODOS los intentos)
function renderizarExamenes() {
    const grid = document.getElementById('examenesGrid');
    let examenesFiltrados = [...todosExamenes];
    
    console.log('Total exámenes originales:', todosExamenes.length);
    
    if (filtroActual === 'aprobados') {
        examenesFiltrados = examenesFiltrados.filter(e => (parseFloat(e.calificacion) || 0) >= 70);
    } else if (filtroActual === 'reprobados') {
        examenesFiltrados = examenesFiltrados.filter(e => (parseFloat(e.calificacion) || 0) < 70);
    }
    
    const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
    if (searchTerm) {
        examenesFiltrados = examenesFiltrados.filter(e => 
            (e.fecha && e.fecha.toLowerCase().includes(searchTerm)) ||
            (e.tipo_examen && e.tipo_examen.toLowerCase().includes(searchTerm)) ||
            (e.nombre_examen && e.nombre_examen.toLowerCase().includes(searchTerm))
        );
    }
    
    console.log('Exámenes después de filtros:', examenesFiltrados.length);
    
    if (examenesFiltrados.length === 0) {
        grid.innerHTML = `
            <div style="grid-column: 1/-1;">
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>No se encontraron resultados</h3>
                    <p>Intenta con otros filtros o busca diferente</p>
                </div>
            </div>
        `;
        return;
    }
    
    // Ordenar por fecha descendente (más reciente primero)
    examenesFiltrados.sort((a, b) => {
        const fechaA = new Date(a.fecha + ' ' + (a.hora_inicio || '00:00:00'));
        const fechaB = new Date(b.fecha + ' ' + (b.hora_inicio || '00:00:00'));
        return fechaB - fechaA;
    });
    
    grid.innerHTML = examenesFiltrados.map(examen => {
        const calificacion = parseFloat(examen.calificacion) || 0;
        const aprobado = calificacion >= 70;
        const fechaFormateada = formatearFecha(examen.fecha);
        const horaInicio = formatearHora(examen.hora_inicio);
        const iconoTipo = getIconoTipo(examen.tipo_examen);
        const colorTipo = getColorTipo(examen.tipo_examen);
        const badgeTipo = getBadgeTipo(examen.tipo_examen);
        const nombreExamen = examen.nombre_examen || getNombreExamen(examen.tipo_examen);
        
        return `
            <div class="examen-card" data-id="${examen.id}" data-examen-id="${examen.examen_id}">
                <div class="card-header">
                    <div class="card-header-left">
                        <div class="card-icon" style="background: ${colorTipo};">
                            <i class="fas ${iconoTipo}"></i>
                        </div>
                        <div class="card-title">
                            <h3>${nombreExamen}</h3>
                            <p>Intento #${examen.intento || 1}</p>
                        </div>
                    </div>
                    <div class="card-badges">
                        ${badgeTipo}
                        <span class="card-badge ${aprobado ? 'badge-aprobado' : 'badge-reprobado'}">
                            ${aprobado ? '✓ Aprobado' : '○ Por mejorar'}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-info">
                        <div class="info-item">
                            <i class="fas fa-calendar"></i>
                            <span class="info-label">Fecha:</span>
                            <span class="info-value">${fechaFormateada}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <span class="info-label">Hora inicio:</span>
                            <span class="info-value">${horaInicio}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-hourglass-half"></i>
                            <span class="info-label">Tiempo usado:</span>
                            <span class="info-value">${examen.tiempo || '00:00:00'}</span>
                        </div>
                    </div>
                    <div class="card-score">
                        <div class="score-circle ${aprobado ? 'score-aprobado' : 'score-reprobado'}">
                            ${Math.round(calificacion)}%
                        </div>
                        <span class="score-label">Calificación</span>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn-ver-detalle" onclick="event.stopPropagation(); mostrarResultados(${examen.id})">
                        <i class="fas fa-eye me-1"></i> Ver resultados
                    </button>
                    <i class="fas fa-chevron-right" style="color: #cbd5e1;"></i>
                </div>
            </div>
        `;
    }).join('');
    
    // Agregar evento click a las tarjetas
    document.querySelectorAll('.examen-card').forEach(card => {
        const examenId = parseInt(card.getAttribute('data-id'));
        card.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-ver-detalle')) return;
            mostrarResultados(examenId);
        });
    });
}

// Mostrar resultados en el modal
function mostrarResultados(examenId) {
    const examenActual = todosExamenes.find(e => e.id == examenId);
    if (!examenActual) return;
    
    const examenKey = examenActual.examen_id;
    const todosIntentos = examenesAgrupados[examenKey] || [examenActual];
    
    const calificaciones = todosIntentos.map(e => parseFloat(e.calificacion) || 0);
    const mejorPuntaje = Math.max(...calificaciones);
    const promedio = calificaciones.reduce((a, b) => a + b, 0) / calificaciones.length;
    const calificacionActual = parseFloat(examenActual.calificacion) || 0;
    const aprobado = calificacionActual >= 70;
    const nombreExamen = examenActual.nombre_examen || getNombreExamen(examenActual.tipo_examen);
    
    // Configurar título del modal
    document.getElementById('modalTituloExamen').innerText = nombreExamen;
    
    // Configurar calificación
    const modalCalificacion = document.getElementById('modalCalificacionResultado');
    modalCalificacion.innerHTML = `<span class="calificacion-value">${Math.round(calificacionActual)}%</span>`;
    modalCalificacion.style.background = aprobado ? '#d1fae5' : '#fee2e2';
    modalCalificacion.style.color = aprobado ? '#065f46' : '#991b1b';
    
    // Configurar mensaje
    const mensajeDiv = document.getElementById('modalMensajeResultado');
    if (aprobado) {
        mensajeDiv.innerHTML = '<i class="fas fa-check-circle me-2" style="color:#10b981"></i>¡Felicidades! Has aprobado este examen';
    } else {
        mensajeDiv.innerHTML = '<i class="fas fa-exclamation-triangle me-2" style="color:#f59e0b"></i>Sigue practicando para mejorar tu puntaje';
    }
    
    // Configurar información básica
    document.getElementById('modalFechaResultado').innerHTML = `<i class="fas fa-calendar me-1"></i> ${formatearFecha(examenActual.fecha)}`;
    document.getElementById('modalFechaInfo').innerText = formatearFecha(examenActual.fecha);
    document.getElementById('modalHoraInfo').innerText = formatearHora(examenActual.hora_inicio);
    document.getElementById('modalHoraFinInfo').innerText = formatearHora(examenActual.hora_fin);
    document.getElementById('modalTiempoInfo').innerText = examenActual.tiempo || '00:00:00';
    document.getElementById('modalIntentoInfo').innerText = examenActual.intento || 1;
    
    // Configurar estadísticas adicionales
    document.getElementById('modalMejorPuntaje').innerText = Math.round(mejorPuntaje) + '%';
    document.getElementById('modalPromedio').innerText = Math.round(promedio) + '%';
    
    // Configurar barra de progreso
    const progressBar = document.getElementById('modalProgressBar');
    const progressText = document.getElementById('modalProgressText');
    progressBar.style.width = calificacionActual + '%';
    progressBar.style.background = aprobado ? 
        'linear-gradient(90deg, #10b981, #34d399)' : 
        'linear-gradient(90deg, #f59e0b, #fbbf24)';
    progressText.innerText = Math.round(calificacionActual) + '%';
    
    // Configurar recomendaciones
    const recomendacionesDiv = document.getElementById('modalRecomendaciones');
    if (calificacionActual >= 90) {
        recomendacionesDiv.innerHTML = '<i class="fas fa-star me-2"></i>¡Excelente trabajo! Tu conocimiento es sobresaliente. Sigue así para mantener tu nivel.';
        recomendacionesDiv.style.background = '#d1fae5';
        recomendacionesDiv.style.borderLeftColor = '#10b981';
        recomendacionesDiv.style.color = '#065f46';
    } else if (calificacionActual >= 70) {
        recomendacionesDiv.innerHTML = '<i class="fas fa-thumbs-up me-2"></i>Buen trabajo. Te recomendamos repasar los temas donde tuviste errores y practicar más para alcanzar la excelencia.';
        recomendacionesDiv.style.background = '#dbeafe';
        recomendacionesDiv.style.borderLeftColor = '#3b82f6';
        recomendacionesDiv.style.color = '#1e3a8a';
    } else if (calificacionActual >= 50) {
        recomendacionesDiv.innerHTML = '<i class="fas fa-book-open me-2"></i>Has mostrado conocimientos básicos. Te sugerimos estudiar más a fondo los temas y volver a intentar el examen.';
        recomendacionesDiv.style.background = '#fed7aa';
        recomendacionesDiv.style.borderLeftColor = '#f59e0b';
        recomendacionesDiv.style.color = '#92400e';
    } else {
        recomendacionesDiv.innerHTML = '<i class="fas fa-heart me-2"></i>No te desanimes. Te recomendamos revisar el material de estudio, tomar notas y practicar nuevamente. ¡El esfuerzo vale la pena!';
        recomendacionesDiv.style.background = '#fee2e2';
        recomendacionesDiv.style.borderLeftColor = '#ef4444';
        recomendacionesDiv.style.color = '#991b1b';
    }
    
    // Configurar historial de intentos (otros intentos del mismo examen)
    const otrosIntentos = todosIntentos.filter(i => i.id != examenId);
    if (otrosIntentos.length > 0) {
        document.getElementById('modalHistorialIntentos').style.display = 'block';
        const tbody = document.getElementById('modalTablaIntentos');
        tbody.innerHTML = otrosIntentos.map(intento => {
            const calif = parseFloat(intento.calificacion) || 0;
            const badgeClass = calif >= 70 ? 'bg-success' : 'bg-warning';
            return `
                <tr>
                    <td>#${intento.intento || 1}</td>
                    <td>${formatearFecha(intento.fecha)}</td>
                    <td><span class="badge ${badgeClass}">${Math.round(calif)}%</span></td>
                    <td>${intento.tiempo || '00:00:00'}</td>
                </tr>
            `;
        }).join('');
        
        // Resetear el estado del historial (cerrado)
        const historialBody = document.getElementById('historialBody');
        const historialIcon = document.getElementById('historialIcon');
        if (historialBody.classList.contains('active')) {
            historialBody.classList.remove('active');
            historialIcon.classList.remove('fa-chevron-up');
            historialIcon.classList.add('fa-chevron-down');
        }
    } else {
        document.getElementById('modalHistorialIntentos').style.display = 'none';
    }
    
    // Configurar botón de nuevo intento según el tipo
    const nuevoIntentoBtn = document.getElementById('btnNuevoIntentoModal');
    const tipoExamen = examenActual.tipo_examen;
    
    if (tipoExamen === 'Materia' || tipoExamen === 'materia') {
        nuevoIntentoBtn.onclick = function() {
            cerrarModal();
            setTimeout(() => {
                window.location.href = '/estudiante/examen-materia/' + examenActual.examen_id;
            }, 200);
        };
    } else if (tipoExamen === 'Curso' || tipoExamen === 'curso') {
        nuevoIntentoBtn.onclick = function() {
            cerrarModal();
            setTimeout(() => {
                window.location.href = '/estudiante/examen-curso/' + examenActual.examen_id;
            }, 200);
        };
    } else {
        nuevoIntentoBtn.onclick = function() {
            cerrarModal();
            setTimeout(() => {
                window.location.href = '{{ route("estudiante.simulador") }}';
            }, 200);
        };
    }
    
    // Abrir modal
    abrirModal();
}

function filtrarExamenes(filtro) {
    filtroActual = filtro;
    document.querySelectorAll('.filtro-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.getAttribute('data-filtro') === filtro) {
            btn.classList.add('active');
        }
    });
    renderizarExamenes();
}

function buscarExamen() {
    renderizarExamenes();
}

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    cargarExamenes();
    
    // Configurar eventos del modal
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelModalBtn = document.getElementById('cancelModalBtn');
    const modalOverlay = document.querySelector('.custom-modal-overlay');
    
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', cerrarModal);
    }
    
    if (cancelModalBtn) {
        cancelModalBtn.addEventListener('click', cerrarModal);
    }
    
    if (modalOverlay) {
        modalOverlay.addEventListener('click', cerrarModal);
    }
    
    // Prevenir que el click dentro del contenido cierre el modal
    const modalContent = document.querySelector('.custom-modal-content');
    if (modalContent) {
        modalContent.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
});
</script>
@endpush