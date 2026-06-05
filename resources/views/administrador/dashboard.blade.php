@extends('administrador.layouts.master')

@section('title', 'Dashboard - SAINS')

@section('content')
<style>
:root {
    --primary: #4f46e5;
    --primary-dark: #4338ca;
    --primary-light: #818cf8;
    --secondary: #ec4899;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #06b6d4;
    --dark: #0f172a;
    --gray: #64748b;
    --gray-light: #f8fafc;
    --gradient-primary: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
    --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    --gradient-info: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    --gradient-secondary: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
    --gradient-danger: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

/* Animaciones */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {

    0%,
    100% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.05);
    }
}

.animate-in {
    animation: fadeInUp 0.5s ease-out forwards;
}

/* Hero Section Mejorado */
.hero-modern {
    background: var(--gradient-primary);
    border-radius: 32px;
    padding: 2rem 2rem;
    color: white;
    position: relative;
    overflow: hidden;
    margin-bottom: 2rem;
    animation: fadeInUp 0.5s ease-out;
}

.hero-modern::before {
    content: '';
    position: absolute;
    top: -40%;
    right: -5%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
    border-radius: 50%;
}

.hero-modern::after {
    content: '';
    position: absolute;
    bottom: -40%;
    left: -5%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    border-radius: 50%;
}

.hero-stats {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
    flex-wrap: wrap;
}

.hero-stat {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 0.75rem 1.25rem;
    text-align: center;
    min-width: 120px;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
    flex: 1;
}

.hero-stat:hover {
    transform: translateY(-3px);
    background: rgba(255, 255, 255, 0.25);
}

.hero-stat .number {
    font-size: 1.75rem;
    font-weight: 800;
    line-height: 1.2;
}

.hero-stat .label {
    font-size: 0.7rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Cards Grid - UNA SOLA LÍNEA */
.cards-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
    margin-bottom: 2rem;
}

.card-modern {
    background: white;
    border-radius: 24px;
    padding: 1.25rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0, 0, 0, 0.04);
    position: relative;
    overflow: hidden;
    animation: fadeInUp 0.5s ease-out;
    animation-fill-mode: both;
}

.card-modern:nth-child(1) {
    animation-delay: 0.1s;
}

.card-modern:nth-child(2) {
    animation-delay: 0.2s;
}

.card-modern:nth-child(3) {
    animation-delay: 0.3s;
}

.card-modern:nth-child(4) {
    animation-delay: 0.4s;
}

.card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-primary);
}

.card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.12);
}

.card-header-icon {
    width: 50px;
    height: 50px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.875rem;
    transition: all 0.3s ease;
}

.card-modern:hover .card-header-icon {
    transform: scale(1.05);
}

.card-title {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--gray);
    margin-bottom: 0.25rem;
}

.card-value {
    font-size: 1.9rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 0.5rem;
}

.card-trend {
    font-size: 0.7rem;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.7rem;
    border-radius: 20px;
    background: rgba(0, 0, 0, 0.04);
}

.trend-up {
    color: var(--success);
    background: rgba(16, 185, 129, 0.1);
}

.trend-down {
    color: var(--danger);
    background: rgba(239, 68, 68, 0.1);
}

/* Métricas Secundarias */
.metrics-wrapper {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
    margin-bottom: 2rem;
}

.metric-card {
    background: white;
    border-radius: 20px;
    padding: 1rem 1.25rem;
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.04);
    display: flex;
    align-items: center;
    justify-content: space-between;
    animation: fadeInUp 0.5s ease-out;
    animation-fill-mode: both;
}

.metric-card:nth-child(1) {
    animation-delay: 0.15s;
}

.metric-card:nth-child(2) {
    animation-delay: 0.25s;
}

.metric-card:nth-child(3) {
    animation-delay: 0.35s;
}

.metric-card:nth-child(4) {
    animation-delay: 0.45s;
}

.metric-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px -8px rgba(0, 0, 0, 0.1);
}

.metric-info .metric-value {
    font-size: 1.6rem;
    font-weight: 700;
    line-height: 1.2;
}

.metric-info .metric-label {
    font-size: 0.7rem;
    color: var(--gray);
    margin-top: 0.25rem;
}

.metric-icon {
    width: 45px;
    height: 45px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.metric-card:hover .metric-icon {
    transform: scale(1.1);
}

/* Secciones Dashboard */
.dashboard-section {
    background: white;
    border-radius: 24px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
    animation: fadeInUp 0.5s ease-out;
}

.dashboard-section:hover {
    box-shadow: 0 10px 30px -12px rgba(0, 0, 0, 0.08);
}

.section-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid rgba(79, 70, 229, 0.1);
}

/* Tablas Mejoradas */
.table-modern {
    width: 100%;
}

.table-modern thead th {
    background: transparent;
    border-bottom: 2px solid rgba(0, 0, 0, 0.06);
    color: var(--gray);
    font-weight: 600;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
}

.table-modern tbody td {
    padding: 0.875rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid rgba(0, 0, 0, 0.04);
}

.table-modern tbody tr {
    transition: all 0.2s ease;
}

.table-modern tbody tr:hover {
    background: rgba(79, 70, 229, 0.03);
    transform: translateX(3px);
}

/* Badges de calificación */
.grade-badge-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.35rem 0.9rem;
    border-radius: 40px;
    font-weight: 700;
    font-size: 0.85rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.grade-badge-modern:hover {
    transform: scale(1.05);
}

.grade-badge-modern i {
    font-size: 0.9rem;
}

.grade-excellent-modern {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.grade-good-modern {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.grade-poor-modern {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

/* Top Students */
.top-students-grid {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.student-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.03) 0%, rgba(79, 70, 229, 0.01) 100%);
    border-radius: 16px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.student-row:hover {
    transform: translateX(5px);
    background: white;
    box-shadow: 0 8px 20px -8px rgba(79, 70, 229, 0.15);
}

.student-rank {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

/* Progress Bar */
.progress-custom {
    height: 5px;
    background: rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    overflow: hidden;
}

.progress-custom-bar {
    height: 100%;
    border-radius: 10px;
    transition: width 0.5s ease;
}

/* Badge Status */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 500;
}

/* Avatar */
.avatar-sm {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.8rem;
    font-weight: 600;
}

/* Fecha completa en español */
.fecha-completa {
    font-size: 0.7rem;
    color: var(--gray);
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    line-height: 1.4;
}

.fecha-completa i {
    font-size: 0.65rem;
    opacity: 0.7;
}

/* Responsive */
@media (max-width: 1200px) {

    .cards-grid,
    .metrics-wrapper {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {

    .cards-grid,
    .metrics-wrapper {
        grid-template-columns: 1fr;
    }

    .hero-stats {
        flex-direction: column;
        gap: 0.75rem;
    }

    .hero-stat {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
    }

    .hero-stat .number {
        font-size: 1.25rem;
    }

    .grade-badge-modern {
        padding: 0.25rem 0.6rem;
        font-size: 0.7rem;
    }

    .fecha-completa {
        font-size: 0.6rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.15rem;
    }
}

@media (max-width: 576px) {
    .table-modern thead th {
        font-size: 0.6rem;
        padding: 0.5rem;
    }

    .table-modern tbody td {
        padding: 0.5rem;
    }

    .grade-badge-modern {
        padding: 0.2rem 0.5rem;
        font-size: 0.65rem;
    }

    .avatar-sm {
        width: 30px;
        height: 30px;
        font-size: 0.7rem;
    }
}

/* Dark Mode */
body.dark-mode .card-modern,
body.dark-mode .metric-card,
body.dark-mode .dashboard-section {
    background: #1e293b;
    border-color: rgba(255, 255, 255, 0.05);
}

body.dark-mode .student-row {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.08) 0%, rgba(79, 70, 229, 0.03) 100%);
}

body.dark-mode .student-row:hover {
    background: #334155;
}

body.dark-mode .student-rank {
    background: #1e293b;
    color: white;
}

body.dark-mode .table-modern thead th {
    border-bottom-color: rgba(255, 255, 255, 0.08);
    color: #94a3b8;
}

body.dark-mode .progress-custom {
    background: rgba(255, 255, 255, 0.1);
}
</style>

<div class="container-fluid px-4">
    <!-- Hero Section -->
    <div class="hero-modern">
        <div class="row align-items-center" style="position: relative; z-index: 2;">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="avatar-sm"
                        style="width: 50px; height: 50px; font-size: 1.2rem; background: rgba(255,255,255,0.2);">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div>
                        <h1 class="fw-bold mb-1" style="font-size: 1.85rem;">Panel de Control</h1>
                        <p class="mb-0 opacity-90">
                            <i class="far fa-calendar-alt me-2"></i>
                            @php
                            Carbon\Carbon::setLocale('es');
                            $fecha_espanol = Carbon\Carbon::now()->translatedFormat('l, j \d\e F \d\e Y');
                            $fecha_espanol = ucfirst($fecha_espanol);
                            @endphp
                            {{ $fecha_espanol }} |
                            <i class="fas fa-user-check me-2 ms-2"></i>Bienvenido,
                            <strong>{{ session('MM_Username', 'Administrador') }}</strong>
                        </p>
                    </div>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="number">{{ number_format($examenesRealizados) }}</div>
                        <div class="label">📝 Exámenes Completados</div>
                    </div>
                    <div class="hero-stat">
                        <div class="number">{{ number_format($totalUsuarios) }}</div>
                        <div class="label">👨‍🎓 Estudiantes Activos</div>
                    </div>
                    <div class="hero-stat">
                        <div class="number">{{ $tasaAprobacion }}%</div>
                        <div class="label">⭐ Tasa de Aprobación</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <button class="btn btn-light" onclick="window.location.reload()"
                    style="border-radius: 50px; padding: 0.6rem 1.25rem; font-size: 0.875rem; font-weight: 500;">
                    <i class="fas fa-sync-alt me-2"></i>Actualizar
                </button>
            </div>
        </div>
    </div>

    <!-- Cards Grid - 4 cards en una sola línea -->
    <div class="cards-grid">
        <div class="card-modern">
            <div class="card-header-icon"
                style="background: linear-gradient(135deg, rgba(79,70,229,0.12) 0%, rgba(79,70,229,0.05) 100%);">
                <i class="fas fa-users fa-2x" style="color: var(--primary);"></i>
            </div>
            <div class="card-title">Total Estudiantes</div>
            <div class="card-value">{{ number_format($totalUsuarios) }}</div>
            <span class="card-trend trend-up">
                <i class="fas fa-arrow-up me-1"></i>+{{ $nuevosUsuariosMes }} este mes
            </span>
        </div>
        <div class="card-modern">
            <div class="card-header-icon"
                style="background: linear-gradient(135deg, rgba(16,185,129,0.12) 0%, rgba(16,185,129,0.05) 100%);">
                <i class="fas fa-file-alt fa-2x" style="color: var(--success);"></i>
            </div>
            <div class="card-title">Exámenes Generados</div>
            <div class="card-value">{{ number_format($totalExamenes) }}</div>
            <span class="card-trend trend-up">
                <i class="fas fa-arrow-up me-1"></i>+{{ $nuevosExamenes }} nuevo(s)
            </span>
        </div>
        <div class="card-modern">
            <div class="card-header-icon"
                style="background: linear-gradient(135deg, rgba(245,158,11,0.12) 0%, rgba(245,158,11,0.05) 100%);">
                <i class="fas fa-dollar-sign fa-2x" style="color: var(--warning);"></i>
            </div>
            <div class="card-title">Ingresos Totales</div>
            <div class="card-value">${{ number_format($totalPagos, 0) }}</div>
            <span class="card-trend trend-up">
                <i class="fas fa-arrow-up me-1"></i>${{ number_format($ingresosMes, 0) }} este mes
            </span>
        </div>
        <div class="card-modern">
            <div class="card-header-icon"
                style="background: linear-gradient(135deg, rgba(6,182,212,0.12) 0%, rgba(6,182,212,0.05) 100%);">
                <i class="fas fa-headset fa-2x" style="color: var(--info);"></i>
            </div>
            <div class="card-title">Interacciones</div>
            <div class="card-value">{{ number_format($totalInteracciones) }}</div>
            <span class="card-trend trend-up">
                <i class="fas fa-arrow-up me-1"></i>+{{ $interaccionesMes }} este mes
            </span>
        </div>
    </div>

    <!-- Métricas Secundarias -->
    <div class="metrics-wrapper">
        <div class="metric-card">
            <div class="metric-info">
                <div class="metric-value">{{ number_format($calificacionPromedio, 1) }}<span
                        style="font-size: 0.875rem;">/100</span></div>
                <div class="metric-label">
                    <i class="fas fa-star text-warning me-1"></i>Calificación Promedio
                </div>
            </div>
            <div class="metric-icon"
                style="background: linear-gradient(135deg, rgba(245,158,11,0.12) 0%, rgba(245,158,11,0.05) 100%);">
                <i class="fas fa-chart-line fa-lg" style="color: var(--warning);"></i>
            </div>
        </div>
        <div class="metric-card">
            <div class="metric-info">
                <div class="metric-value">{{ number_format($cuponesCanjeados) }}</div>
                <div class="metric-label">
                    <i class="fas fa-ticket-alt text-success me-1"></i>Cupones Canjeados
                </div>
            </div>
            <div class="metric-icon"
                style="background: linear-gradient(135deg, rgba(16,185,129,0.12) 0%, rgba(16,185,129,0.05) 100%);">
                <i class="fas fa-percent fa-lg" style="color: var(--success);"></i>
            </div>
        </div>
        <div class="metric-card">
            <div class="metric-info">
                <div class="metric-value">{{ number_format($estudiantesActivos) }}</div>
                <div class="metric-label">
                    <i class="fas fa-user-graduate text-primary me-1"></i>Con Exámenes
                </div>
            </div>
            <div class="metric-icon"
                style="background: linear-gradient(135deg, rgba(79,70,229,0.12) 0%, rgba(79,70,229,0.05) 100%);">
                <i class="fas fa-check-circle fa-lg" style="color: var(--primary);"></i>
            </div>
        </div>
        <div class="metric-card">
            <div class="metric-info">
                <div class="metric-value">{{ $examenesRealizadosSemana }}</div>
                <div class="metric-label">
                    <i class="fas fa-calendar-week text-info me-1"></i>Exámenes/Semana
                </div>
            </div>
            <div class="metric-icon"
                style="background: linear-gradient(135deg, rgba(6,182,212,0.12) 0%, rgba(6,182,212,0.05) 100%);">
                <i class="fas fa-tachometer-alt fa-lg" style="color: var(--info);"></i>
            </div>
        </div>
    </div>

    <!-- Sección Principal -->
    <div class="row g-4">
        <!-- Últimos Exámenes Realizados -->
        <div class="col-lg-7">
            <div class="dashboard-section">
                <div class="section-title">
                    <i class="fas fa-clipboard-list text-primary"></i>
                    <span>Últimos Exámenes Realizados</span>
                    <span class="ms-2 badge bg-primary bg-opacity-10 text-primary" style="font-size: 0.7rem;">
                        <i class="fas fa-clock me-1"></i>Actualizado en tiempo real
                    </span>
                    <a href="{{ route('admin.examenes-realizados.index') }}" class="btn btn-sm btn-link ms-auto"
                        style="font-size: 0.7rem; text-decoration: none;">
                        Ver todos <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                @if($ultimosExamenes->count() > 0)
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th>Examen</th>
                                <th>Calificación</th>
                                <th>Fecha y Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ultimosExamenes as $examen)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-sm"
                                            style="width: 36px; height: 36px; background: linear-gradient(135deg, rgba(79,70,229,0.1) 0%, rgba(79,70,229,0.05) 100%);">
                                            <i class="fas fa-user fa-sm" style="color: var(--primary);"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold small">{{ $examen->estudiante_nombre }}</div>
                                            <div class="text-muted" style="font-size: 0.65rem;">
                                                {{ $examen->estudiante_email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small fw-medium">{{ Str::limit($examen->examen_titulo, 30) }}</div>
                                </td>
                                <td>
                                    @php
                                    $calif = $examen->calificacion ?? 0;
                                    if ($calif >= 80) {
                                    $gradeClass = 'grade-excellent-modern';
                                    $gradeIcon = 'fa-star';
                                    $gradeText = 'Excelente';
                                    } elseif ($calif >= 60) {
                                    $gradeClass = 'grade-good-modern';
                                    $gradeIcon = 'fa-smile';
                                    $gradeText = 'Aprobado';
                                    } else {
                                    $gradeClass = 'grade-poor-modern';
                                    $gradeIcon = 'fa-frown';
                                    $gradeText = 'Reprobado';
                                    }
                                    @endphp
                                    <div class="grade-badge-modern {{ $gradeClass }}">
                                        <i class="fas {{ $gradeIcon }}"></i>
                                        <span>{{ number_format($calif, 1) }}%</span>
                                        <span style="font-size: 0.65rem; opacity: 0.9;">{{ $gradeText }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="fecha-completa">
                                        <i class="far fa-calendar-alt"></i>
                                        @php
                                        $fechaFormateada = 'Fecha no disponible';

                                        if (!empty($examen->fecha_inicio)) {
                                        try {
                                        // fecha_inicio ya es un objeto Carbon por el $casts
                                        $fechaObj = $examen->fecha_inicio;

                                        // Si tenemos hora_inicio, la agregamos
                                        if (!empty($examen->hora_inicio)) {
                                        // Parsear la hora correctamente
                                        $horaObj = \Carbon\Carbon::parse($examen->hora_inicio);
                                        $fechaFormateada = $fechaObj->translatedFormat('j \d\e F') . ', ' .
                                        $horaObj->format('H:i');
                                        } else {
                                        $fechaFormateada = $fechaObj->translatedFormat('j \d\e F');
                                        }
                                        } catch (\Exception $e) {
                                        $fechaFormateada = 'Fecha inválida';
                                        }
                                        }
                                        @endphp
                                        <span>{{ $fechaFormateada }}</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-muted py-5">
                    <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                    <p class="mb-0">No hay exámenes realizados aún</p>
                    <small>Los exámenes aparecerán aquí cuando los estudiantes los completen</small>
                </div>
                @endif
            </div>
        </div>

        <!-- Top 5 Estudiantes + Interacciones Recientes -->
        <div class="col-lg-5">
            <!-- Top Estudiantes -->
            <div class="dashboard-section" style="margin-bottom: 1.5rem;">
                <div class="section-title">
                    <i class="fas fa-trophy text-warning"></i>
                    <span>Top 5 Mejores Promedios</span>
                    <span class="ms-2 badge bg-warning bg-opacity-10 text-warning" style="font-size: 0.65rem;">
                        <i class="fas fa-chart-simple me-1"></i>Ranking General
                    </span>
                </div>

                @if($topEstudiantes->count() > 0)
                <div class="top-students-grid">
                    @foreach($topEstudiantes as $index => $estudiante)
                    <div class="student-row" data-tooltip="Promedio: {{ number_format($estudiante->promedio, 1) }}%">
                        <div class="student-rank"
                            style="background: {{ $index == 0 ? 'linear-gradient(135deg, #f59e0b20, #f59e0b10)' : '#f8fafc' }};">
                            @if($index == 0)
                            <i class="fas fa-crown text-warning"></i>
                            @elseif($index == 1)
                            <i class="fas fa-medal" style="color: #c0c0c0;"></i>
                            @elseif($index == 2)
                            <i class="fas fa-medal" style="color: #cd7f32;"></i>
                            @else
                            <span style="color: var(--gray); font-weight: 700;">{{ $index + 1 }}</span>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold small">{{ $estudiante->nombre_completo }}</div>
                            <div class="d-flex gap-3 mt-1 align-items-center">
                                @php
                                $promColor = $estudiante->promedio >= 80 ? '#10b981' : ($estudiante->promedio >= 60 ?
                                '#f59e0b' : '#ef4444');
                                @endphp
                                <span class="fw-bold"
                                    style="font-size: 0.85rem; color: {{ $promColor }};">{{ number_format($estudiante->promedio, 1) }}%</span>
                                <span class="text-muted" style="font-size: 0.65rem;">
                                    <i class="fas fa-file-alt me-1"></i>{{ $estudiante->total_examenes }} exámenes
                                </span>
                            </div>
                            <div class="progress-custom mt-2">
                                <div class="progress-custom-bar"
                                    style="width: {{ $estudiante->promedio }}%; background: linear-gradient(90deg, {{ $promColor }}, {{ $promColor }}80);">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-chart-line fa-2x mb-2 opacity-25"></i>
                    <p class="mb-0">No hay datos disponibles</p>
                    <small>Los rankings aparecerán cuando los estudiantes completen exámenes</small>
                </div>
                @endif
            </div>

            <!-- Últimas Interacciones -->
            <div class="dashboard-section">
                <div class="section-title">
                    <i class="fas fa-phone-alt text-primary"></i>
                    <span>Últimas Interacciones</span>
                    <a href="{{ route('admin.callcenter.index') }}" class="btn btn-sm btn-link ms-auto"
                        style="font-size: 0.7rem; text-decoration: none;">
                        Ver todas <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                @if($ultimasInteracciones->count() > 0)
                <div style="max-height: 360px; overflow-y: auto; padding-right: 5px;">
                    @foreach($ultimasInteracciones as $interaccion)
                    <div class="d-flex align-items-center gap-3 mb-3 pb-3"
                        style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                        <div class="avatar-sm"
                            style="width: 40px; height: 40px; background: linear-gradient(135deg, rgba(79,70,229,0.1) 0%, rgba(79,70,229,0.05) 100%); flex-shrink: 0;">
                            <i class="fas fa-user fa-sm" style="color: var(--primary);"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold small">{{ $interaccion->estudiante_nombre }}</div>
                            <div class="d-flex gap-2 mt-1 flex-wrap">
                                @php
                                $tipoIcons = [
                                'llamada' => ['icon' => 'fa-phone-alt', 'color' => '#4f46e5'],
                                'whatsapp' => ['icon' => 'fa-whatsapp', 'color' => '#25D366'],
                                'email' => ['icon' => 'fa-envelope', 'color' => '#ea4335'],
                                'default' => ['icon' => 'fa-comment', 'color' => '#64748b']
                                ];
                                $tipo = strtolower($interaccion->tipo_contacto ?? 'default');
                                $tipoInfo = $tipoIcons[$tipo] ?? $tipoIcons['default'];
                                @endphp
                                <span class="status-badge"
                                    style="background: {{ $tipoInfo['color'] }}10; color: {{ $tipoInfo['color'] }};">
                                    <i class="fab {{ $tipoInfo['icon'] }} me-1"></i>
                                    {{ ucfirst($tipo) }}
                                </span>
                                <span class="text-muted" style="font-size: 0.65rem;">
                                    <i class="far fa-clock me-1"></i>
                                    @php
                                    $ts = strtotime($interaccion->created_at);
                                    $fecha_interaccion = ucfirst(strftime('%d %b, %H:%M', $ts));
                                    @endphp
                                    {{ $fecha_interaccion }}
                                </span>
                            </div>
                            <div class="text-muted small mt-1">
                                {{ Str::limit($interaccion->motivo_contacto ?? 'Sin motivo registrado', 45) }}</div>
                        </div>
                        <a href="{{ route('admin.callcenter.show', $interaccion->id ?? '#') }}" class="text-muted"
                            style="font-size: 0.7rem;">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2 opacity-25"></i>
                    <p class="mb-0">No hay interacciones registradas</p>
                    <small>Las interacciones del Call Center aparecerán aquí</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tooltips dinámicos
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    tooltipElements.forEach(el => {
        el.addEventListener('mouseenter', function() {
            const tooltip = this.getAttribute('data-tooltip');
            if (tooltip) {
                let tooltipEl = this.querySelector('.custom-tooltip');
                if (!tooltipEl) {
                    tooltipEl = document.createElement('div');
                    tooltipEl.className = 'custom-tooltip';
                    tooltipEl.textContent = tooltip;
                    this.style.position = 'relative';
                    this.appendChild(tooltipEl);
                }
            }
        });

        el.addEventListener('mouseleave', function() {
            const tooltipEl = this.querySelector('.custom-tooltip');
            if (tooltipEl) tooltipEl.remove();
        });
    });
});
</script>

<style>
.custom-tooltip {
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: var(--dark);
    color: white;
    padding: 0.25rem 0.6rem;
    border-radius: 6px;
    font-size: 0.7rem;
    white-space: nowrap;
    z-index: 100;
    margin-bottom: 8px;
    pointer-events: none;
}

.custom-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border-width: 5px;
    border-style: solid;
    border-color: var(--dark) transparent transparent transparent;
}
</style>
@endsection