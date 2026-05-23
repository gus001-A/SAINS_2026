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
    }

    /* Hero Section Moderno */
    .hero-modern {
        background: var(--gradient-primary);
        border-radius: 28px;
        padding: 2rem 2rem;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .hero-modern::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -5%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .hero-modern::after {
        content: '';
        position: absolute;
        bottom: -40%;
        left: -5%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }

    .hero-stats {
        display: flex;
        gap: 2rem;
        margin-top: 1rem;
        flex-wrap: wrap;
    }

    .hero-stat {
        background: rgba(255,255,255,0.12);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 0.75rem 1.5rem;
        text-align: center;
        min-width: 120px;
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

    /* Cards Grid */
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
        border: 1px solid rgba(0,0,0,0.04);
        position: relative;
        overflow: hidden;
    }

    .card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gradient-primary);
    }

    .card-modern:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 35px -12px rgba(0,0,0,0.12);
    }

    .card-header-icon {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .card-title {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--gray);
        margin-bottom: 0.25rem;
    }

    .card-value {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 0.5rem;
    }

    .card-trend {
        font-size: 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
    }

    /* Sección de métricas */
    .metrics-wrapper {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .metric-card {
        background: white;
        border-radius: 20px;
        padding: 1rem 1.25rem;
        transition: all 0.2s ease;
        border: 1px solid rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -8px rgba(0,0,0,0.1);
    }

    .metric-info .metric-value {
        font-size: 1.5rem;
        font-weight: 700;
    }

    .metric-info .metric-label {
        font-size: 0.7rem;
        color: var(--gray);
    }

    .metric-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Tablas modernas */
    .table-modern {
        width: 100%;
    }

    .table-modern thead th {
        background: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.08);
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
        border-bottom: 1px solid rgba(0,0,0,0.04);
    }

    .table-modern tbody tr {
        transition: all 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background: rgba(79, 70, 229, 0.03);
    }

    /* Badge de calificación */
    .grade-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.25rem 0.75rem;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .grade-excellent {
        background: linear-gradient(135deg, rgba(16,185,129,0.15) 0%, rgba(16,185,129,0.05) 100%);
        color: #10b981;
    }

    .grade-good {
        background: linear-gradient(135deg, rgba(245,158,11,0.15) 0%, rgba(245,158,11,0.05) 100%);
        color: #f59e0b;
    }

    .grade-poor {
        background: linear-gradient(135deg, rgba(239,68,68,0.15) 0%, rgba(239,68,68,0.05) 100%);
        color: #ef4444;
    }

    /* Top students */
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
        background: var(--gray-light);
        border-radius: 16px;
        transition: all 0.2s ease;
    }

    .student-row:hover {
        transform: translateX(5px);
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .student-rank {
        width: 36px;
        height: 36px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1rem;
    }

    /* Dashboard sections */
    .dashboard-section {
        background: white;
        border-radius: 24px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(0,0,0,0.04);
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Dark mode */
    body.dark-mode .card-modern,
    body.dark-mode .metric-card,
    body.dark-mode .dashboard-section {
        background: #1e293b;
        border-color: rgba(255,255,255,0.05);
    }

    body.dark-mode .student-row {
        background: #334155;
    }

    body.dark-mode .student-row:hover {
        background: #3b4a6b;
    }

    body.dark-mode .student-rank {
        background: #1e293b;
    }

    body.dark-mode .table-modern thead th {
        border-bottom-color: rgba(255,255,255,0.08);
        color: #94a3b8;
    }

    body.dark-mode .table-modern tbody td {
        border-bottom-color: rgba(255,255,255,0.04);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .cards-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .metrics-wrapper {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .cards-grid {
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
    }
</style>

<div class="container-fluid px-4">
    <!-- Hero Section Moderno -->
    <div class="hero-modern">
        <div class="row align-items-center" style="position: relative; z-index: 2;">
            <div class="col-lg-7 mb-3 mb-lg-0">
                <h1 class="fw-bold mb-2" style="font-size: 1.75rem;">
                    <i class="fas fa-chart-pie me-2"></i>Panel de Control
                </h1>
                <p class="mb-3 opacity-90">
                    {{ now()->format('l, d F Y') }} | Bienvenido de vuelta, <strong>{{ session('MM_Username', 'Administrador') }}</strong>
                </p>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="number">{{ number_format($examenesRealizados) }}</div>
                        <div class="label">Exámenes Completados</div>
                    </div>
                    <div class="hero-stat">
                        <div class="number">{{ number_format($totalUsuarios) }}</div>
                        <div class="label">Estudiantes Activos</div>
                    </div>
                    <div class="hero-stat">
                        <div class="number">{{ $tasaAprobacion }}%</div>
                        <div class="label">Tasa de Aprobación</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 text-lg-end">
                <button class="btn btn-light" onclick="window.location.reload()" style="border-radius: 50px; padding: 0.5rem 1.25rem; font-size: 0.875rem;">
                    <i class="fas fa-sync-alt me-2"></i>Actualizar Datos
                </button>
            </div>
        </div>
    </div>

    <!-- Cards Grid - 4 Principales -->
    <div class="cards-grid">
        <div class="card-modern">
            <div class="card-header-icon" style="background: linear-gradient(135deg, rgba(79,70,229,0.1) 0%, rgba(79,70,229,0.05) 100%);">
                <i class="fas fa-users fa-2x" style="color: var(--primary);"></i>
            </div>
            <div class="card-title">Total Estudiantes</div>
            <div class="card-value">{{ number_format($totalUsuarios) }}</div>
            <span class="card-trend trend-up">
                <i class="fas fa-arrow-up me-1"></i>+{{ $nuevosUsuariosMes }} este mes
            </span>
        </div>
        <div class="card-modern">
            <div class="card-header-icon" style="background: linear-gradient(135deg, rgba(16,185,129,0.1) 0%, rgba(16,185,129,0.05) 100%);">
                <i class="fas fa-file-alt fa-2x" style="color: var(--success);"></i>
            </div>
            <div class="card-title">Exámenes Generados</div>
            <div class="card-value">{{ number_format($totalExamenes) }}</div>
            <span class="card-trend trend-up">
                <i class="fas fa-arrow-up me-1"></i>+{{ $nuevosExamenes }} nueva(s)
            </span>
        </div>
        <div class="card-modern">
            <div class="card-header-icon" style="background: linear-gradient(135deg, rgba(245,158,11,0.1) 0%, rgba(245,158,11,0.05) 100%);">
                <i class="fas fa-dollar-sign fa-2x" style="color: var(--warning);"></i>
            </div>
            <div class="card-title">Ingresos Totales</div>
            <div class="card-value">${{ number_format($totalPagos, 0) }}</div>
            <span class="card-trend trend-up">
                <i class="fas fa-arrow-up me-1"></i>${{ number_format($ingresosMes, 0) }} este mes
            </span>
        </div>
        <div class="card-modern">
            <div class="card-header-icon" style="background: linear-gradient(135deg, rgba(6,182,212,0.1) 0%, rgba(6,182,212,0.05) 100%);">
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
                <div class="metric-value">{{ number_format($calificacionPromedio, 1) }}<span style="font-size: 0.875rem;">/100</span></div>
                <div class="metric-label">Calificación Promedio</div>
            </div>
            <div class="metric-icon" style="background: rgba(245,158,11,0.1);">
                <i class="fas fa-star fa-lg" style="color: var(--warning);"></i>
            </div>
        </div>
        <div class="metric-card">
            <div class="metric-info">
                <div class="metric-value">{{ number_format($cuponesCanjeados) }}</div>
                <div class="metric-label">Cupones Canjeados</div>
            </div>
            <div class="metric-icon" style="background: rgba(16,185,129,0.1);">
                <i class="fas fa-ticket-alt fa-lg" style="color: var(--success);"></i>
            </div>
        </div>
        <div class="metric-card">
            <div class="metric-info">
                <div class="metric-value">{{ number_format($estudiantesActivos) }}</div>
                <div class="metric-label">Estudiantes con Exámenes</div>
            </div>
            <div class="metric-icon" style="background: rgba(79,70,229,0.1);">
                <i class="fas fa-user-graduate fa-lg" style="color: var(--primary);"></i>
            </div>
        </div>
        <div class="metric-card">
            <div class="metric-info">
                <div class="metric-value">{{ $examenesRealizadosSemana }}</div>
                <div class="metric-label">Exámenes Esta Semana</div>
            </div>
            <div class="metric-icon" style="background: rgba(6,182,212,0.1);">
                <i class="fas fa-calendar-week fa-lg" style="color: var(--info);"></i>
            </div>
        </div>
    </div>

    <!-- Sección Principal: Últimos Exámenes + Top Estudiantes -->
    <div class="row g-4">
        <!-- Últimos Exámenes Realizados -->
        <div class="col-lg-7">
            <div class="dashboard-section">
                <div class="section-title">
                    <i class="fas fa-clipboard-list text-primary"></i>
                    <span>Últimos Exámenes Realizados</span>
                </div>
                
                @if($ultimosExamenes->count() > 0)
                    <div class="table-responsive">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Examen</th>
                                    <th>Calificación</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimosExamenes as $examen)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, rgba(79,70,229,0.1) 0%, rgba(79,70,229,0.05) 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-user fa-sm" style="color: var(--primary);"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold small">{{ $examen->estudiante_nombre }}</div>
                                                <div class="text-muted" style="font-size: 0.7rem;">{{ $examen->estudiante_email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-muted small">{{ Str::limit($examen->examen_titulo, 35) }}</td>
                                    <td>
                                        @php
                                            $calif = $examen->calificacion ?? 0;
                                            $gradeClass = $calif >= 80 ? 'grade-excellent' : ($calif >= 60 ? 'grade-good' : 'grade-poor');
                                        @endphp
                                        <span class="grade-badge {{ $gradeClass }}">
                                            {{ number_format($calif, 1) }}%
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ $examen->fecha_formateada }}
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
                </div>
                
                @if($topEstudiantes->count() > 0)
                    <div class="top-students-grid">
                        @foreach($topEstudiantes as $index => $estudiante)
                            <div class="student-row">
                                <div class="student-rank" style="background: {{ $index == 0 ? 'linear-gradient(135deg, #f59e0b20, #f59e0b10)' : '#f8fafc' }};">
                                    @if($index == 0)
                                        <i class="fas fa-crown text-warning"></i>
                                    @elseif($index == 1)
                                        <i class="fas fa-medal" style="color: #c0c0c0;"></i>
                                    @elseif($index == 2)
                                        <i class="fas fa-medal" style="color: #cd7f32;"></i>
                                    @else
                                        <span style="color: var(--gray);">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small">{{ $estudiante->nombre_completo }}</div>
                                    <div class="d-flex gap-3 mt-1">
                                        <span class="text-primary fw-bold" style="font-size: 0.85rem;">{{ number_format($estudiante->promedio, 1) }}%</span>
                                        <span class="text-muted" style="font-size: 0.7rem;">
                                            <i class="fas fa-file-alt me-1"></i>{{ $estudiante->total_examenes }} exámenes
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-chart-line fa-2x mb-2 opacity-25"></i>
                        <p class="mb-0">No hay datos disponibles</p>
                    </div>
                @endif
            </div>

            <!-- Últimas Interacciones (Compacto) -->
            <div class="dashboard-section">
                <div class="section-title">
                    <i class="fas fa-phone-alt text-primary"></i>
                    <span>Últimas Interacciones</span>
                    <a href="{{ route('admin.callcenter.index') }}" class="btn btn-sm btn-link ms-auto" style="font-size: 0.7rem;">Ver todas →</a>
                </div>
                
                @if($ultimasInteracciones->count() > 0)
                    <div style="max-height: 320px; overflow-y: auto;">
                        @foreach($ultimasInteracciones as $interaccion)
                            <div class="d-flex align-items-center gap-3 mb-3 pb-2" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                                <div style="width: 36px; height: 36px; background: rgba(79,70,229,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-user fa-sm" style="color: var(--primary);"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small">{{ $interaccion->estudiante_nombre }}</div>
                                    <div class="d-flex gap-2 mt-1">
                                        @php
                                            $tipoIcons = ['llamada' => 'fa-phone-alt', 'whatsapp' => 'fa-whatsapp', 'email' => 'fa-envelope'];
                                            $tipo = strtolower($interaccion->tipo_contacto ?? 'llamada');
                                        @endphp
                                        <span class="badge" style="background: rgba(79,70,229,0.1); color: var(--primary); font-size: 0.65rem;">
                                            <i class="fab {{ $tipoIcons[$tipo] ?? 'fa-comment' }} me-1"></i>
                                            {{ ucfirst($tipo) }}
                                        </span>
                                        <span class="text-muted" style="font-size: 0.65rem;">
                                            <i class="far fa-clock me-1"></i>{{ $interaccion->fecha_humana }}
                                        </span>
                                    </div>
                                    <div class="text-muted small mt-1">{{ Str::limit($interaccion->motivo_contacto ?? 'Sin motivo', 50) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2 opacity-25"></i>
                        <p class="mb-0">No hay interacciones registradas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection