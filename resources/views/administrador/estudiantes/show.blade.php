@extends('administrador.layouts.master')

@section('title', 'Detalles del Estudiante - SAINS')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="display-5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                    <i class="fas fa-user-graduate me-3"></i>Detalles del Estudiante
                </h1>
                <p class="text-muted">Información completa del estudiante registrado en el sistema</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.estudiantes.edit', $usuario->id) }}" class="btn btn-warning rounded-pill px-4">
                    <i class="fas fa-edit me-2"></i>Editar
                </a>
                <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i>Volver al listado
                </a>
                <a href="{{ route('admin.estudiantes.reporte-pdf', $usuario->id) }}" class="btn btn-danger rounded-pill px-4" target="_blank">
                    <i class="fas fa-file-pdf me-2"></i>Reporte PDF
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-modern p-4">
            <!-- Perfil Header -->
            <div class="d-flex flex-column flex-md-row align-items-center gap-4 mb-5 pb-3 border-bottom">
                <div class="avatar-perfil">
                    <i class="fas fa-user-graduate fa-4x"></i>
                </div>
                <div class="text-center text-md-start">
                    <h2 class="mb-1 fw-bold">{{ $estudiante->nombre }} {{ $estudiante->paterno }} {{ $estudiante->materno }}</h2>
                    <p class="text-muted mb-2">
                        <i class="fas fa-envelope me-1"></i> {{ $usuario->correo }}
                    </p>
                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                        @if($estudiante->plan_activo)
                            <span class="badge-plan-activo"><i class="fas fa-check-circle"></i> Plan Activo</span>
                        @else
                            <span class="badge-plan-inactivo"><i class="fas fa-times-circle"></i> Plan Inactivo</span>
                        @endif
                        @if($estudiante->cupon)
                            <span class="badge-cupon"><i class="fas fa-ticket-alt me-1"></i> Cupón: {{ $estudiante->cupon }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Dashboard de Progreso - Tarjetas de resumen -->
            <div class="row g-3 mb-5">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: rgba(102,126,234,0.1);">
                            <i class="fas fa-clock" style="color: #667eea;"></i>
                        </div>
                        <div class="stats-info">
                            @php
                                $horasMostrar = floor($tiempoTotalHoras ?? 0);
                                $minutosMostrar = round(($tiempoTotalHoras - $horasMostrar) * 60);
                                if ($minutosMostrar >= 60) {
                                    $horasMostrar += floor($minutosMostrar / 60);
                                    $minutosMostrar = $minutosMostrar % 60;
                                }
                            @endphp
                            <h3 class="stats-number">{{ $horasMostrar }}h {{ $minutosMostrar }}min</h3>
                            <p class="stats-label">Tiempo total de estudio</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: rgba(76,175,80,0.1);">
                            <i class="fas fa-video" style="color: #4caf50;"></i>
                        </div>
                        <div class="stats-info">
                            <h3 class="stats-number">{{ number_format($vistosCompletos ?? 0) }}/{{ number_format($totalVideos ?? 0) }}</h3>
                            <p class="stats-label">Videos completados</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: rgba(255,152,0,0.1);">
                            <i class="fas fa-chart-line" style="color: #ff9800;"></i>
                        </div>
                        <div class="stats-info">
                            <h3 class="stats-number">{{ number_format($promedioCalificaciones ?? 0) }}%</h3>
                            <p class="stats-label">Promedio general</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: rgba(156,39,176,0.1);">
                            <i class="fas fa-calendar-alt" style="color: #9c27b0;"></i>
                        </div>
                        <div class="stats-info">
                            <h3 class="stats-number">{{ number_format($diasActivos ?? 0) }}</h3>
                            <p class="stats-label">Días activos</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 1: INFORMACIÓN DE CATÁLOGOS (Fija, misma altura) -->
            <div class="row g-4 mb-4">
                <!-- Información Personal -->
                <div class="col-md-4">
                    <div class="info-section h-100">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="fas fa-user-circle me-2"></i>Información Personal
                        </h5>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Nombre completo:</label>
                                <span>{{ $estudiante->nombre }} {{ $estudiante->paterno }} {{ $estudiante->materno }}</span>
                            </div>
                            <div class="info-item">
                                <label>Fecha de nacimiento:</label>
                                <span>{{ \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->format('d/m/Y') }} 
                                    ({{ \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->age }} años)</span>
                            </div>
                            <div class="info-item">
                                <label>Sexo:</label>
                                <span>{{ $estudiante->sexo == 'M' ? 'Masculino' : 'Femenino' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Teléfono celular:</label>
                                <span>{{ $estudiante->telefono ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Teléfono casa:</label>
                                <span>{{ $estudiante->telefono_casa ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Académica (Escuela Procedencia) -->
                <div class="col-md-4">
                    <div class="info-section h-100">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="fas fa-graduation-cap me-2"></i>Escuela de Procedencia
                        </h5>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Centro educativo:</label>
                                <span class="text-wrap">{{ $estudiante->escuelaProcedencia->centro_educativo ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Clave:</label>
                                <span>{{ $estudiante->escuelaProcedencia->clave ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Estado:</label>
                                <span>{{ $estudiante->escuelaProcedencia->estado ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Municipio:</label>
                                <span>{{ $estudiante->escuelaProcedencia->municipio ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Turno:</label>
                                <span>{{ $estudiante->escuelaProcedencia->turno ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Universidad de Interés -->
                <div class="col-md-4">
                    <div class="info-section h-100">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="fas fa-university me-2"></i>Universidad de Interés
                        </h5>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Universidad:</label>
                                <span class="text-wrap">{{ $estudiante->universidadInteres->clave ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Dirección:</label>
                                <span class="text-wrap">{{ $estudiante->universidadInteres->direccion ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Estado:</label>
                                <span>{{ $estudiante->universidadInteres->estado ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Municipio:</label>
                                <span>{{ $estudiante->universidadInteres->municipio ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Tipo/Duración:</label>
                                <span>{{ $estudiante->universidadInteres->tipo ?? '—' }} / {{ $estudiante->universidadInteres->duracion ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: ESTADÍSTICAS Y PROGRESO (Información que varía) -->
            <div class="row g-4">
                <!-- Tiempo de Estudio -->
                <div class="col-md-6">
                    <div class="info-section">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="fas fa-chart-line me-2"></i>Tiempo de Estudio
                        </h5>
                        
                        <div class="row g-2 mb-4">
                            <div class="col-4">
                                <div class="tiempo-resumen-card">
                                    <div class="tiempo-resumen-icon">
                                        <i class="fas fa-hourglass-half"></i>
                                    </div>
                                    <div class="tiempo-resumen-info">
                                        @php
                                            $horasTotales = floor($tiempoTotalHoras ?? 0);
                                            $minutosTotales = round(($tiempoTotalHoras - $horasTotales) * 60);
                                            if ($minutosTotales >= 60) {
                                                $horasTotales += floor($minutosTotales / 60);
                                                $minutosTotales = $minutosTotales % 60;
                                            }
                                        @endphp
                                        <span class="tiempo-resumen-valor">{{ $horasTotales }}h {{ $minutosTotales }}min</span>
                                        <span class="tiempo-resumen-label">Horas totales</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="tiempo-resumen-card">
                                    <div class="tiempo-resumen-icon" style="background: rgba(16,185,129,0.1);">
                                        <i class="fas fa-calendar-check" style="color: #10b981;"></i>
                                    </div>
                                    <div class="tiempo-resumen-info">
                                        <span class="tiempo-resumen-valor">{{ number_format($diasActivos ?? 0) }}</span>
                                        <span class="tiempo-resumen-label">Días activos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="tiempo-resumen-card">
                                    <div class="tiempo-resumen-icon" style="background: rgba(245,158,11,0.1);">
                                        <i class="fas fa-clock" style="color: #f59e0b;"></i>
                                    </div>
                                    <div class="tiempo-resumen-info">
                                        <span class="tiempo-resumen-valor">{{ number_format($totalSesiones ?? 0) }}</span>
                                        <span class="tiempo-resumen-label">Sesiones</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tiempo-grafica-container mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="fw-semibold mb-0">
                                    <i class="fas fa-chart-simple me-1"></i> Actividad diaria
                                </label>
                                <span class="text-muted small">Últimos 7 días</span>
                            </div>
                            
                            <div class="grafica-barras">
                                @foreach($estudioDiario ?? [] as $dia)
                                    @php
                                        $horas = is_numeric($dia->horas_estudiadas) ? floatval($dia->horas_estudiadas) : 0;
                                        $porcentajeBarra = min(100, ($horas / 5) * 100);
                                        $colorBarra = $horas >= 3 ? '#10b981' : ($horas >= 1 ? '#f59e0b' : '#ef4444');
                                    @endphp
                                    <div class="barra-item">
                                        <div class="barra-info">
                                            <span class="barra-dia">{{ $dia->dia }}</span>
                                            <span class="barra-horas">{{ number_format($horas, 1) }}h</span>
                                        </div>
                                        <div class="barra-contenedor">
                                            <div class="barra-progreso" style="width: {{ $porcentajeBarra }}%; background: {{ $colorBarra }};">
                                                <div class="barra-herramienta">
                                                    {{ number_format($horas, 1) }} horas
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="tiempo-stats-grid">
                            <div class="tiempo-stat-item">
                                <div class="stat-circle" style="background: rgba(79,70,229,0.1);">
                                    <i class="fas fa-chart-simple" style="color: #4f46e5;"></i>
                                </div>
                                <div>
                                    @php
                                        $promedioHoras = ($tiempoTotalHoras ?? 0) / max(1, $diasActivos ?? 1);
                                        $promHoras = floor($promedioHoras);
                                        $promMinutos = round(($promedioHoras - $promHoras) * 60);
                                        if ($promMinutos >= 60) {
                                            $promHoras += floor($promMinutos / 60);
                                            $promMinutos = $promMinutos % 60;
                                        }
                                    @endphp
                                    <div class="stat-number">{{ $promHoras }}h {{ $promMinutos }}min</div>
                                    <div class="stat-label">Horas/día promedio</div>
                                </div>
                            </div>
                            <div class="tiempo-stat-item">
                                <div class="stat-circle" style="background: rgba(16,185,129,0.1);">
                                    <i class="fas fa-fire" style="color: #10b981;"></i>
                                </div>
                                <div>
                                    <div class="stat-number" 
                                        @if(isset($fechaUltimaActividad) && $fechaUltimaActividad)
                                            title="{{ $fechaUltimaActividad instanceof \Carbon\Carbon ? $fechaUltimaActividad->format('d/m/Y H:i:s') : $fechaUltimaActividad }}"
                                            data-bs-toggle="tooltip"
                                        @endif
                                    >
                                        @if(isset($fechaUltimaActividad) && $fechaUltimaActividad instanceof \Carbon\Carbon)
                                            @php
                                                $fecha = $fechaUltimaActividad;
                                                $ahora = \Carbon\Carbon::now();
                                                $diferenciaDias = $fecha->diffInDays($ahora);
                                                
                                                if($diferenciaDias == 0) {
                                                    // Hoy: mostrar "Hoy a las HH:MM"
                                                    echo 'Hoy a las ' . $fecha->format('H:i');
                                                } elseif($diferenciaDias == 1) {
                                                    // Ayer: mostrar "Ayer a las HH:MM"
                                                    echo 'Ayer a las ' . $fecha->format('H:i');
                                                } elseif($diferenciaDias <= 7) {
                                                    // Esta semana: mostrar "Día a las HH:MM"
                                                    \Carbon\Carbon::setLocale('es');
                                                    echo ucfirst($fecha->isoFormat('dddd')) . ' a las ' . $fecha->format('H:i');
                                                } else {
                                                    // Más de una semana: mostrar fecha completa
                                                    echo $fecha->format('d/m/Y H:i');
                                                }
                                            @endphp
                                        @else
                                            {{ $ultimaActividad ?? '—' }}
                                        @endif
                                    </div>
                                    <div class="stat-label">Última actividad</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas por Tipo de Examen -->
                <div class="col-md-6">
                    <div class="info-section">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="fas fa-chart-pie me-2"></i>Estadísticas por Tipo de Examen
                        </h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="tipo-examen-card" style="border-left: 4px solid #ef4444;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="tipo-examen-icon"><i class="fas fa-flask"></i></span>
                                            <span class="tipo-examen-label">Simulación</span>
                                        </div>
                                        <span class="tipo-examen-count">{{ $examenesPorTipo['simulacion'] ?? 0 }}</span>
                                    </div>
                                    <div class="mt-2">
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-danger" style="width: {{ ($promedioPorTipo['simulacion'] ?? 0) }}%;"></div>
                                        </div>
                                        <div class="text-end mt-1">
                                            <span class="tipo-examen-promedio">Promedio: {{ number_format($promedioPorTipo['simulacion'] ?? 0, 1) }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="tipo-examen-card" style="border-left: 4px solid #3b82f6;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="tipo-examen-icon"><i class="fas fa-book"></i></span>
                                            <span class="tipo-examen-label">Materia</span>
                                        </div>
                                        <span class="tipo-examen-count">{{ $examenesPorTipo['materia'] ?? 0 }}</span>
                                    </div>
                                    <div class="mt-2">
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-primary" style="width: {{ ($promedioPorTipo['materia'] ?? 0) }}%;"></div>
                                        </div>
                                        <div class="text-end mt-1">
                                            <span class="tipo-examen-promedio">Promedio: {{ number_format($promedioPorTipo['materia'] ?? 0, 1) }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="tipo-examen-card" style="border-left: 4px solid #10b981;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="tipo-examen-icon"><i class="fas fa-graduation-cap"></i></span>
                                            <span class="tipo-examen-label">Curso</span>
                                        </div>
                                        <span class="tipo-examen-count">{{ $examenesPorTipo['curso'] ?? 0 }}</span>
                                    </div>
                                    <div class="mt-2">
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: {{ ($promedioPorTipo['curso'] ?? 0) }}%;"></div>
                                        </div>
                                        <div class="text-end mt-1">
                                            <span class="tipo-examen-promedio">Promedio: {{ number_format($promedioPorTipo['curso'] ?? 0, 1) }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Resumen rápido de exámenes -->
                        <div class="mt-3 pt-2 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Total de exámenes:</span>
                                <strong class="fs-4">{{ $examenes->count() }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="text-muted">Promedio general:</span>
                                <strong class="fs-4 {{ ($promedioCalificaciones ?? 0) >= 70 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($promedioCalificaciones ?? 0, 1) }}%
                                </strong>
                            </div>
                            <div class="progress mt-2" style="height: 8px;">
                                @php
                                    $anchoBarra = min(100, $promedioCalificaciones ?? 0);
                                @endphp
                                <div class="progress-bar {{ ($promedioCalificaciones ?? 0) >= 70 ? 'bg-success' : 'bg-danger' }}" 
                                     style="width: {{ $anchoBarra }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 3: TABLAS Y LISTADOS DETALLADOS -->
            <div class="row g-4 mt-2">
                <!-- Calificaciones de Exámenes -->
                <div class="col-12">
                    <div class="info-section">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="fas fa-file-alt me-2"></i>Detalle de Exámenes Realizados
                        </h5>
                        
                        @if($examenes->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Examen</th>
                                            <th>Tipo</th>
                                            <th>Referencia</th>
                                            <th>Fecha</th>
                                            <th>Calificación</th>
                                            <th>Tiempo</th>
                                            <th>Intento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($examenes as $examen)
                                        <tr>
                                            <td>
                                                <strong>{{ $examen->examen_nombre ?? 'Examen' }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge-examen-tipo badge-{{ $examen->badge_color }}">
                                                    <i class="fas {{ $examen->badge_icon }} me-1"></i>
                                                    {{ $examen->tipo_texto }}
                                                </span>
                                            </td>
                                            <td class="text-muted small">
                                                @if($examen->nombre_referencia && $examen->nombre_referencia != 'Examen')
                                                    {{ $examen->nombre_referencia }}
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="text-muted small">
                                                {{ $examen->fecha_fin ? \Carbon\Carbon::parse($examen->fecha_fin)->format('d/m/Y') : '—' }}
                                                @if($examen->hora_fin)
                                                    <br><small>{{ \Carbon\Carbon::parse($examen->hora_fin)->format('H:i') }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $calificacion = is_numeric($examen->calificacion) ? floatval($examen->calificacion) : 0;
                                                @endphp
                                                <span class="badge {{ $calificacion >= 70 ? 'badge-aprobado' : 'badge-reprobado' }}">
                                                    {{ number_format($calificacion, 1) }}%
                                                </span>
                                            </td>
                                            <td class="text-muted small">{{ $examen->tiempo ?? '—' }}</td>
                                            <td class="text-muted small">{{ $examen->intento ?? 1 }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-file-alt fa-3x text-muted opacity-25 mb-2"></i>
                                <p class="text-muted mb-0">No hay exámenes realizados aún</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Progreso de Videos y Videos Pendientes -->
                <div class="col-md-6">
                    <div class="info-section">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="fas fa-play-circle me-2"></i>Progreso de Videos
                        </h5>
                        
                        <div class="progreso-videos mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Progreso general:</span>
                                <strong>{{ number_format($porcentajeProgreso ?? 0) }}%</strong>
                            </div>
                            <div class="progress mb-3" style="height: 10px;">
                                @php
                                    $anchoProgreso = min(100, $porcentajeProgreso ?? 0);
                                @endphp
                                <div class="progress-bar bg-success" style="width: {{ $anchoProgreso }}%;"></div>
                            </div>
                        </div>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="small-stats-card">
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Completados</span>
                                    <strong class="fs-4">{{ number_format($vistosCompletos ?? 0) }}</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="small-stats-card">
                                    <span class="text-warning"><i class="fas fa-play-circle"></i> En progreso</span>
                                    <strong class="fs-4">{{ number_format($videosEnProgreso ?? 0) }}</strong>
                                </div>
                            </div>
                        </div>
                        
                        <label class="fw-semibold mb-2">Últimos videos vistos:</label>
                        @if($ultimosVideos->isNotEmpty())
                            <div class="list-group list-group-flush" style="max-height: 250px; overflow-y: auto;">
                                @foreach($ultimosVideos as $progreso)
                                    <div class="list-group-item bg-transparent px-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-video text-primary me-2"></i>
                                                <strong>{{ $progreso->video->titulo ?? 'Video #' . $progreso->video_id }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $progreso->fecha_visto ? \Carbon\Carbon::parse($progreso->fecha_visto)->diffForHumans() : 'Fecha no disponible' }}
                                                </small>
                                            </div>
                                            @if($progreso->completado)
                                                <span class="badge bg-success">Completado</span>
                                            @else
                                                @php
                                                    $ultimoSegundo = is_numeric($progreso->ultimo_segundo) ? intval($progreso->ultimo_segundo) : 0;
                                                    $porcentajeVideo = $ultimoSegundo > 0 ? round(($ultimoSegundo / ($duracionEstimada ?? 3600)) * 100) : 0;
                                                @endphp
                                                <span class="badge bg-warning">{{ $porcentajeVideo }}%</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center py-3">No ha visto ningún video aún</p>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-section">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="fas fa-hourglass-half me-2"></i>Videos Pendientes
                        </h5>
                        
                        @if(isset($videosFaltantes) && $videosFaltantes->isNotEmpty())
                            <div class="list-group list-group-flush" style="max-height: 350px; overflow-y: auto;">
                                @foreach($videosFaltantes as $video)
                                    <div class="list-group-item bg-transparent px-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-play-circle text-warning me-2"></i>
                                                <strong>{{ $video->titulo }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $video->materia }} - {{ $video->tema }}</small>
                                            </div>
                                            <span class="badge bg-secondary">Pendiente</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-3 text-center">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Le faltan <strong>{{ $videosFaltantes->count() }}</strong> videos por completar
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-trophy fa-3x text-success mb-2"></i>
                                <p class="text-success fw-semibold mb-0">¡Felicidades!</p>
                                <p class="text-muted small">Ha completado todos los videos disponibles</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="d-flex justify-content-center gap-3 mt-5 pt-4 border-top">
                <a href="{{ route('admin.estudiantes.edit', $usuario->id) }}" class="btn btn-warning px-5 py-3 rounded-pill">
                    <i class="fas fa-edit me-2"></i> Editar Estudiante
                </a>
                <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary px-5 py-3 rounded-pill">
                    <i class="fas fa-arrow-left me-2"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-modern {
        background: white;
        border-radius: 24px;
        transition: transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    
    .card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(67,97,238,0.15);
    }
    
    .avatar-perfil {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #667eea20, #764ba220);
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #667eea;
    }
    
    .info-section {
        background: #f8fafc;
        border-radius: 20px;
        padding: 1.5rem;
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .info-section:hover {
        background: #f1f5f9;
        transform: translateY(-3px);
    }
    
    .info-grid {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .info-item label {
        font-weight: 600;
        color: #64748b;
        font-size: 0.8rem;
        margin-bottom: 0;
        min-width: 110px;
    }
    
    .info-item span {
        color: #1e293b;
        font-weight: 500;
        text-align: right;
        max-width: 60%;
        word-break: break-word;
    }
    
    .text-wrap {
        word-break: break-word;
        white-space: normal;
    }
    
    /* Stats Cards */
    .stats-card {
        background: #f8fafc;
        border-radius: 16px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .stats-icon {
        width: 50px;
        height: 50px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .stats-info {
        flex: 1;
    }
    
    .stats-number {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.2;
    }
    
    .stats-label {
        font-size: 0.75rem;
        color: #64748b;
        margin: 0;
    }
    
    /* Badges */
    .badge-plan-activo {
        background: linear-gradient(135deg, #4caf50, #388e3c);
        color: white;
        padding: 6px 16px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 4px rgba(76,175,80,0.3);
    }
    
    .badge-plan-inactivo {
        background: linear-gradient(135deg, #ef5350, #d32f2f);
        color: white;
        padding: 6px 16px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 4px rgba(239,83,80,0.3);
    }
    
    .badge-cupon {
        background: linear-gradient(135deg, #ff9800, #f57c00);
        color: white;
        padding: 6px 16px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'Courier New', monospace;
        box-shadow: 0 2px 4px rgba(255,152,0,0.3);
    }
    
    .badge-aprobado {
        background: #4caf50;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
    }
    
    .badge-reprobado {
        background: #ef5350;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
    }
    
    .badge-examen-tipo {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }
    
    .badge-primary {
        background: rgba(59,130,246,0.15);
        color: #3b82f6;
    }
    
    .badge-success {
        background: rgba(16,185,129,0.15);
        color: #10b981;
    }
    
    .badge-danger {
        background: rgba(239,68,68,0.15);
        color: #ef4444;
    }
    
    .badge-secondary {
        background: rgba(100,116,139,0.15);
        color: #64748b;
    }
    
    .small-stats-card {
        background: white;
        border-radius: 12px;
        padding: 0.75rem;
        text-align: center;
        border: 1px solid #e2e8f0;
    }
    
    .list-group-item {
        background-color: transparent !important;
    }
    
    /* Tarjetas de tipo de examen */
    .tipo-examen-card {
        background: white;
        border-radius: 16px;
        padding: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    
    .tipo-examen-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    
    .tipo-examen-icon {
        font-size: 1.5rem;
        margin-right: 0.5rem;
    }
    
    .tipo-examen-label {
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .tipo-examen-count {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
    }
    
    .tipo-examen-promedio {
        font-size: 0.7rem;
        color: #64748b;
    }
    
    /* Estilos para tiempo de estudio */
    .tiempo-resumen-card {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border-radius: 16px;
        padding: 0.75rem;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.04);
    }
    
    .tiempo-resumen-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px -8px rgba(0,0,0,0.1);
    }
    
    .tiempo-resumen-icon {
        width: 40px;
        height: 40px;
        background: rgba(79,70,229,0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
    }
    
    .tiempo-resumen-icon i {
        font-size: 1.25rem;
        color: #4f46e5;
    }
    
    .tiempo-resumen-info {
        display: flex;
        flex-direction: column;
    }
    
    .tiempo-resumen-valor {
        font-size: 1.2rem;
        font-weight: 800;
        line-height: 1.2;
        color: #1e293b;
    }
    
    .tiempo-resumen-label {
        font-size: 0.7rem;
        color: #64748b;
    }
    
    .tiempo-grafica-container {
        background: #f8fafc;
        border-radius: 20px;
        padding: 1rem;
    }
    
    .grafica-barras {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .barra-item {
        width: 100%;
    }
    
    .barra-info {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 0.25rem;
    }
    
    .barra-dia {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
    }
    
    .barra-horas {
        font-size: 0.7rem;
        font-weight: 500;
        color: #4f46e5;
    }
    
    .barra-contenedor {
        background: #e2e8f0;
        border-radius: 20px;
        height: 28px;
        overflow: hidden;
        position: relative;
    }
    
    .barra-progreso {
        height: 100%;
        border-radius: 20px;
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-right: 8px;
        color: white;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .barra-herramienta {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.7rem;
        font-weight: 600;
        color: white;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    
    .barra-progreso:hover .barra-herramienta {
        opacity: 1;
    }
    
    .tiempo-stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-top: 1rem;
    }
    
    .tiempo-stat-item {
        background: #f8fafc;
        border-radius: 16px;
        padding: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.2s ease;
    }
    
    .tiempo-stat-item:hover {
        background: #f1f5f9;
        transform: translateX(3px);
    }
    
    .stat-circle {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .stat-number {
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.2;
        color: #1e293b;
    }
    
    .stat-label {
        font-size: 0.65rem;
        color: #64748b;
    }
    
    /* Dark Mode */
    body.dark-mode .card-modern {
        background: #1e293b;
    }
    
    body.dark-mode .info-section {
        background: #0f172a;
    }
    
    body.dark-mode .info-section:hover {
        background: #1e293b;
    }
    
    body.dark-mode .info-item {
        border-bottom-color: #334155;
    }
    
    body.dark-mode .info-item label {
        color: #94a3b8;
    }
    
    body.dark-mode .info-item span {
        color: #f1f5f9;
    }
    
    body.dark-mode .stats-card {
        background: #0f172a;
    }
    
    body.dark-mode .small-stats-card {
        background: #0f172a;
        border-color: #334155;
    }
    
    body.dark-mode .tipo-examen-card {
        background: #0f172a;
    }
    
    body.dark-mode .tipo-examen-count {
        color: #f1f5f9;
    }
    
    body.dark-mode .tiempo-resumen-card {
        background: linear-gradient(135deg, #1e293b, #0f172a);
        border-color: rgba(255,255,255,0.05);
    }
    
    body.dark-mode .tiempo-resumen-valor {
        color: #f1f5f9;
    }
    
    body.dark-mode .tiempo-grafica-container {
        background: #0f172a;
    }
    
    body.dark-mode .barra-contenedor {
        background: #334155;
    }
    
    body.dark-mode .barra-dia {
        color: #94a3b8;
    }
    
    body.dark-mode .tiempo-stat-item {
        background: #0f172a;
    }
    
    body.dark-mode .tiempo-stat-item:hover {
        background: #1e293b;
    }
    
    body.dark-mode .stat-number {
        color: #f1f5f9;
    }
    
    body.dark-mode .list-group-item {
        border-color: #334155;
    }
    
    body.dark-mode .alert-info {
        background-color: rgba(102,126,234,0.1);
        border-color: rgba(102,126,234,0.2);
        color: #a78bfa;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .tiempo-resumen-valor {
            font-size: 1rem;
        }
        
        .tiempo-resumen-icon {
            width: 32px;
            height: 32px;
        }
        
        .tiempo-resumen-icon i {
            font-size: 1rem;
        }
        
        .stat-number {
            font-size: 0.85rem;
        }
        
        .stat-circle {
            width: 36px;
            height: 36px;
        }
        
        .stats-number {
            font-size: 1rem;
        }
        
        .stats-icon {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }
        
        .info-item label {
            min-width: 90px;
            font-size: 0.75rem;
        }
        
        .info-item span {
            font-size: 0.8rem;
            max-width: 55%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    @if(session('success'))
        Swal.fire({
            title: 'Éxito',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#667eea',
            timer: 3000
        });
    @endif
    
    @if(session('error'))
        Swal.fire({
            title: 'Error',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonColor: '#dc3545'
        });
    @endif
    
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush