@extends('estudiante.layouts.app')

@section('title', 'Clases Premium | SAINS')

@section('content')
<div class="aula-virtual-container">

    <!-- ========== HEADER ========== -->
    <div class="aula-header mb-5">
        <div class="aula-header-content">
            <div class="aula-header-icon">
                <i class="fas fa-chalkboard-user"></i>
            </div>
            <div class="aula-header-text">
                <h1>🎓 Clases Premium</h1>
                <p>Clases en video, recursos de apoyo y exámenes por materia</p>
                @if(isset($estudiante) && $estudiante)
                @if($estudiante->plan_activo)
                <div class="aula-user-badge active">
                    <i class="fas fa-check-circle"></i> Plan Activo - {{ $estudiante->nombre ?? 'Estudiante' }}
                </div>
                @else
                <div class="aula-user-badge inactive">
                    <i class="fas fa-lock"></i> Plan Inactivo - Vista previa limitada a 15 segundos
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>

    <!-- ========== ESTADÍSTICAS RÁPIDAS ========== -->
    @php
    if (!isset($asignaturas) || $asignaturas === null) {
    $asignaturas = collect([]);
    }

    $totalAsignaturas = $asignaturas->count();
    $totalVideos = 0;
    $totalRecursos = 0;
    $vistasIds = $vistasIds ?? [];
    $videosVistos = 0;

    foreach($asignaturas as $asignatura) {
    if ($asignatura && $asignatura->clases) {
    foreach($asignatura->clases as $clase) {
    if ($clase->link) {
    $totalVideos++;
    if (in_array($clase->id, $vistasIds)) {
    $videosVistos++;
    }
    }
    // Contar recursos adicionales
    if ($clase->recursos && $clase->recursos->count() > 0) {
    $totalRecursos += $clase->recursos->count();
    }
    // Material principal
    if ($clase->url) {
    $totalRecursos++;
    }
    }
    }
    }

    $examenesPorArea = $examenesPorArea ?? [];
    $colors = ['#4361ee', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4898'];
    $icons = ['fa-book', 'fa-calculator', 'fa-language', 'fa-brain', 'fa-flask', 'fa-history', 'fa-palette',
    'fa-music'];

    $planActivo = isset($estudiante) && $estudiante && $estudiante->plan_activo;
    @endphp

    <div class="stats-aula mb-5">
        <div class="stat-aula-card">
            <div class="stat-aula-icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-aula-info">
                <h3>{{ $totalAsignaturas }}</h3>
                <p>Asignaturas</p>
            </div>
        </div>
        <div class="stat-aula-card">
            <div class="stat-aula-icon">
                <i class="fas fa-video"></i>
            </div>
            <div class="stat-aula-info">
                <h3>{{ $totalVideos }}</h3>
                <p>Clases en video</p>
            </div>
        </div>
        <div class="stat-aula-card">
            <div class="stat-aula-icon">
                <i class="fas fa-download"></i>
            </div>
            <div class="stat-aula-info">
                <h3>{{ $totalRecursos }}</h3>
                <p>Recursos de apoyo</p>
            </div>
        </div>
        <div class="stat-aula-card">
            <div class="stat-aula-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-aula-info">
                <h3>{{ $totalVideos > 0 ? round(($videosVistos / $totalVideos) * 100) : 0 }}%</h3>
                <p>Progreso</p>
            </div>
        </div>
    </div>

    <!-- ========== BANNER PREMIUM PARA USUARIOS SIN PLAN ========== -->
    @if(!$planActivo)
    <div class="premium-upgrade-banner">
        <div class="premium-upgrade-content">
            <div class="premium-upgrade-icon">
                <i class="fas fa-crown"></i>
            </div>
            <div class="premium-upgrade-text">
                <h4>🚀 Desbloquea el acceso completo</h4>
                <p>Obtén acceso ilimitado a todas las clases, material descargable, simuladores y certificado por solo
                    $800 MXN</p>
            </div>
            <button class="premium-upgrade-btn" onclick="comprarCurso()">
                Obtener acceso ahora <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- ========== LISTA DE ASIGNATURAS ========== -->
    <div class="asignaturas-container">
        @foreach($asignaturas as $index => $asignatura)
        @if($asignatura && $asignatura->clases && $asignatura->clases->count() > 0)
        <div class="asignatura-block">
            <div class="asignatura-title" onclick="toggleAsignatura(this)">
                <div class="asignatura-title-left">
                    <div class="asignatura-icon"
                        style="background: {{ $colors[$index % count($colors)] ?? '#4361ee' }}">
                        <i class="fas {{ $icons[$index % count($icons)] }}"></i>
                    </div>
                    <div>
                        <h3>{{ $asignatura->nombre }}</h3>
                        <p>{{ $asignatura->clases->where('link', '!=', null)->count() }} clases •
                            {{ $asignatura->clases->sum(function($c) { return $c->recursos->count(); }) + $asignatura->clases->where('url', '!=', null)->count() }}
                            recursos</p>
                    </div>
                </div>
                <div class="asignatura-toggle-icon">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            <div class="asignatura-content">
                <!-- Clases Grid -->
                <div class="clases-grid">
                    @foreach($asignatura->clases as $clase)
                    @if($clase->link)
                    <div class="clase-card" data-clase-id="{{ $clase->id }}" data-clase-link="{{ $clase->link }}"
                        data-clase-nombre="{{ addslashes($clase->nombre_clase) }}"
                        data-asignatura-nombre="{{ addslashes($asignatura->nombre) }}"
                        data-recurso-url="{{ $clase->url }}" onclick="handleClaseClick(this)">
                        <div class="clase-thumb">
                            @php
                            $videoUrl = $clase->link;
                            $videoId = '';
                            $thumbnailUrl = 'https://via.placeholder.com/160x90/4361ee/ffffff?text=Video';

                            if($videoUrl) {
                            if(strpos($videoUrl, 'vimeo.com/') !== false) {
                            $videoId = substr($videoUrl, strrpos($videoUrl, '/') + 1);
                            if(strpos($videoId, '?') !== false) $videoId = substr($videoId, 0, strpos($videoId, '?'));
                            $thumbnailUrl = "https://vumbnail.com/{$videoId}.jpg";
                            } elseif(strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !==
                            false) {
                            preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&?]+)/', $videoUrl, $matches);
                            $videoId = $matches[1] ?? '';
                            if($videoId) {
                            $thumbnailUrl = "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg";
                            }
                            }
                            }
                            @endphp
                            <img src="{{ $thumbnailUrl }}" alt="{{ $clase->nombre_clase }}"
                                onerror="this.src='https://via.placeholder.com/160x90/4361ee/ffffff?text=Video'">
                            <div class="play-overlay">
                                <i class="fas fa-play"></i>
                            </div>
                            @if(in_array($clase->id, $vistasIds))
                            <div class="watched-indicator">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            @endif
                            @if(!$planActivo && !in_array($clase->id, $vistasIds))
                            <div class="preview-badge">
                                <i class="fas fa-eye"></i> Vista previa 15s
                            </div>
                            @endif
                        </div>
                        <div class="clase-info">
                            <div class="clase-tags">
                                @if($clase->num_clase)
                                <span class="clase-number">Clase {{ $clase->num_clase }}</span>
                                @endif
                                @if(in_array($clase->id, $vistasIds))
                                <span class="completed-tag"><i class="fas fa-check"></i> Completado</span>
                                @elseif($planActivo)
                                <span class="premium-tag"><i class="fas fa-crown"></i> Premium</span>
                                @else
                                <span class="preview-tag"><i class="fas fa-eye"></i> Vista previa (15s)</span>
                                @endif
                            </div>
                            <h4>{{ $clase->nombre_clase }}</h4>
                            <!-- Mostrar badge de recursos adicionales -->
                            @if($clase->recursos && $clase->recursos->count() > 0)
                            <div class="recursos-badge">
                                <i class="fas fa-paperclip"></i> {{ $clase->recursos->count() }} recursos
                            </div>
                            @elseif($clase->url)
                            <div class="recursos-badge">
                                <i class="fas fa-file-alt"></i> Material disponible
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>

                <!-- EXAMEN DE LA MATERIA -->
                @php
                $examenAsignatura = $examenesPorArea[$asignatura->id] ?? null;

                $intentosRealizados = 0;
                $mejorCalificacion = null;

                if($examenAsignatura && isset($estudiante)) {
                $resultados = \App\Models\ExamenRealizado::where('estudiante', $estudiante->id)
                ->where('examen', $examenAsignatura->id)
                ->get();
                $intentosRealizados = $resultados->count();
                $mejorCalificacion = $resultados->max('calificacion');
                }
                @endphp

                @if($examenAsignatura)
                <div class="examen-section">
                    <div class="examen-divider">
                        <span><i class="fas fa-star"></i> Evaluación de la materia</span>
                    </div>

                    @if($planActivo)
                    <div class="examen-card-final">
                        <div class="examen-icon-final">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="examen-info-final">
                            <h4>
                                Examen de {{ $asignatura->nombre }}
                                @if($mejorCalificacion && $mejorCalificacion >= 70)
                                <span class="aprobado-badge"><i class="fas fa-check-circle"></i> Aprobado</span>
                                @endif
                            </h4>
                            <p>Demuestra lo que has aprendido en esta materia</p>
                            <div class="examen-meta-final">
                                <span><i class="fas fa-question-circle"></i>
                                    {{ $examenAsignatura->numero_preguntas ?? $examenAsignatura->preguntas()->count() }}
                                    preguntas</span>
                                <span><i class="fas fa-clock"></i> {{ $examenAsignatura->tiempo ?? 'Sin' }}
                                    límite</span>
                                <span><i class="fas fa-chart-line"></i> Calificación mínima: 70%</span>
                                <span class="examen-tipo-badge"><i class="fas fa-tag"></i> Examen de materia</span>

                                @if($intentosRealizados > 0)
                                <span class="intentos-badge">
                                    <i class="fas fa-history"></i>
                                    Intentos realizados: {{ $intentosRealizados }}
                                    @if($mejorCalificacion)
                                    | Mejor nota: {{ $mejorCalificacion }}%
                                    @endif
                                </span>
                                @endif
                            </div>
                        </div>

                        <button class="examen-btn-final"
                            onclick="confirmarExamenMateria({{ $examenAsignatura->id }}, '{{ addslashes($asignatura->nombre) }}')">
                            <i class="fas fa-play-circle"></i>
                            @if($mejorCalificacion && $mejorCalificacion >= 70)
                            Volver a intentar
                            @elseif($intentosRealizados > 0)
                            Intentar nuevamente
                            @else
                            Comenzar Examen
                            @endif
                        </button>
                    </div>
                    @else
                    <div class="examen-card-final locked">
                        <div class="examen-icon-final">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="examen-info-final">
                            <h4>
                                Examen de {{ $asignatura->nombre }}
                                <span class="locked-badge"><i class="fas fa-lock"></i> Premium</span>
                            </h4>
                            <p>Desbloquea este examen con el plan premium</p>
                            <div class="examen-meta-final">
                                <span><i class="fas fa-question-circle"></i>
                                    {{ $examenAsignatura->numero_preguntas ?? $examenAsignatura->preguntas()->count() }}
                                    preguntas</span>
                                <span><i class="fas fa-clock"></i> {{ $examenAsignatura->tiempo ?? 'Sin' }}
                                    límite</span>
                                <span><i class="fas fa-chart-line"></i> Calificación mínima: 70%</span>
                            </div>
                        </div>
                        <button class="examen-btn-final locked" onclick="mostrarBloqueoExamen()">
                            <i class="fas fa-lock"></i> Desbloquear con Premium
                        </button>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif
        @endforeach

        <!-- ========== EXÁMENES FINALES DEL CURSO ========== -->
        @php
        $examenesCurso = \App\Models\ExamenGenerado::where('tipo_examen', 'Curso')
        ->orderBy('id', 'asc')
        ->get();

        $examenesCursoData = [];

        foreach($examenesCurso as $examenCursoItem) {
        $intentosCursoItem = 0;
        $mejorNotaCursoItem = null;
        $examenCursoItemAprobado = false;

        if(isset($estudiante)) {
        $resultadosCursoItem = \App\Models\ExamenRealizado::where('estudiante', $estudiante->id)
        ->where('examen', $examenCursoItem->id)
        ->get();
        $intentosCursoItem = $resultadosCursoItem->count();
        $mejorNotaCursoItem = $resultadosCursoItem->max('calificacion');
        $examenCursoItemAprobado = ($mejorNotaCursoItem && $mejorNotaCursoItem >= 70);
        }

        $examenesCursoData[] = [
        'examen' => $examenCursoItem,
        'intentos' => $intentosCursoItem,
        'mejor_nota' => $mejorNotaCursoItem,
        'aprobado' => $examenCursoItemAprobado
        ];
        }
        @endphp

        @if(count($examenesCursoData) > 0)
        <div class="examenes-curso-container">
            <div class="examenes-curso-header">
                <div class="examenes-curso-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div>
                    <h3>🏆 Exámenes Finales del Curso</h3>
                    <p>Demuestra todo lo que has aprendido en todas las materias</p>
                </div>
            </div>

            @foreach($examenesCursoData as $cursoData)
            <div class="examen-curso-card {{ !$planActivo ? 'locked' : '' }}">
                <div class="examen-curso-info">
                    <div class="examen-curso-badge">
                        <i class="fas {{ $planActivo ? 'fa-certificate' : 'fa-lock' }}"></i>
                        {{ $planActivo ? 'Certificación Final' : 'Contenido Premium' }}
                    </div>
                    <h4>{{ $cursoData['examen']->nombre ?? 'Examen General SAINS' }}</h4>
                    <p>{{ $cursoData['examen']->descripcion ?? 'Este examen evaluará tus conocimientos generales de todo el curso. ¡Prepárate!' }}
                    </p>

                    <div class="examen-curso-meta">
                        <div class="meta-item">
                            <i class="fas fa-question-circle"></i>
                            <span>{{ $cursoData['examen']->numero_preguntas ?? $cursoData['examen']->preguntas()->count() }}
                                preguntas</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>{{ $cursoData['examen']->tiempo ?? 'Sin' }} límite de tiempo</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-chart-line"></i>
                            <span>Calificación mínima: 70%</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-infinity"></i>
                            <span>Intentos ilimitados</span>
                        </div>
                    </div>

                    @if($planActivo && $cursoData['intentos'] > 0)
                    <div class="examen-curso-stats">
                        <div class="stat-badge">
                            <i class="fas fa-history"></i>
                            Intentos realizados: {{ $cursoData['intentos'] }}
                        </div>
                        @if($cursoData['mejor_nota'])
                        <div class="stat-badge {{ $cursoData['aprobado'] ? 'success' : 'warning' }}">
                            <i class="fas fa-star"></i>
                            Mejor puntuación: {{ $cursoData['mejor_nota'] }}%
                        </div>
                        @endif
                        @if($cursoData['aprobado'])
                        <div class="stat-badge success">
                            <i class="fas fa-check-circle"></i>
                            ¡Examen aprobado!
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                @if($planActivo)
                <button class="examen-curso-btn"
                    onclick="confirmarExamenCurso({{ $cursoData['examen']->id }}, '{{ addslashes($cursoData['examen']->nombre ?? 'Examen Final') }}')">
                    <i class="fas {{ $cursoData['aprobado'] ? 'fa-redo-alt' : 'fa-play-circle' }}"></i>
                    {{ $cursoData['aprobado'] ? 'Volver a intentar' : ($cursoData['intentos'] > 0 ? 'Intentar nuevamente' : 'Comenzar Examen Final') }}
                </button>
                @else
                <button class="examen-curso-btn locked" onclick="mostrarBloqueoExamen()">
                    <i class="fas fa-lock"></i> Desbloquear con Premium
                </button>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

<!-- ========== MODAL DE VIDEO ========== -->
<div class="modal fade" id="videoModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content video-modal-enhanced">

            <div class="modal-header video-modal-header-enhanced">
                <div class="modal-header-info">
                    <button type="button" class="btn-back" data-bs-dismiss="modal">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <div class="video-info-enhanced">
                        <h3 id="modalVideoTitle">Cargando clase...</h3>
                        <p id="modalAsignaturaName">Curso SAINS</p>
                    </div>
                </div>
                <div class="modal-header-actions">
                    <button type="button" class="action-btn" id="fullscreenModalBtn" title="Pantalla completa">
                        <i class="fas fa-expand"></i>
                    </button>
                    <button type="button" class="action-btn close-btn-enhanced" data-bs-dismiss="modal" title="Cerrar">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="modal-body p-0">
                <div class="modal-layout">
                    <div class="modal-video-column">
                        <div class="video-player-enhanced">
                            <iframe id="modalVideoIframe" src="" frameborder="0"
                                allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                            <!-- Overlay de bloqueo premium (se muestra cuando termina la preview) -->
                            <div class="premium-block-overlay" id="premiumBlockOverlay" style="display: none;">
                                <div class="premium-block-card">
                                    <div class="premium-block-icon">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <h3>✨ Contenido Premium</h3>
                                    <p>Has visto los primeros 15 segundos de esta clase.</p>
                                    <p class="premium-block-description">¡Desbloquea el acceso completo al curso premium
                                        y continúa aprendiendo!</p>

                                    <div class="premium-block-benefits">
                                        <div class="benefit-item">
                                            <i class="fas fa-check-circle"></i>
                                            <span>Todas las lecciones completas</span>
                                        </div>
                                        <div class="benefit-item">
                                            <i class="fas fa-check-circle"></i>
                                            <span>Material descargable</span>
                                        </div>
                                        <div class="benefit-item">
                                            <i class="fas fa-check-circle"></i>
                                            <span>Exámenes por materia</span>
                                        </div>
                                        <div class="benefit-item">
                                            <i class="fas fa-check-circle"></i>
                                            <span>Exámenes finales del curso</span>
                                        </div>
                                        <div class="benefit-item">
                                            <i class="fas fa-check-circle"></i>
                                            <span>Certificado de finalización</span>
                                        </div>
                                    </div>

                                    <div class="premium-block-price">
                                        <span class="price-badge">Precio especial</span>
                                        <div class="price-amount">$800 <span>MXN</span></div>
                                        <span class="price-note">Pago único · Acceso vitalicio</span>
                                    </div>

                                    <div class="premium-block-buttons">
                                        <button class="btn-premium" onclick="comprarCurso()">
                                            <i class="fas fa-shopping-cart"></i> Obtener acceso ahora
                                        </button>
                                        <button class="btn-close-preview" onclick="cerrarModalCompleto()">
                                            <i class="fas fa-times"></i> Cerrar
                                        </button>
                                    </div>

                                    <p class="premium-block-secure">
                                        <i class="fas fa-lock"></i> Pago 100% seguro
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-material-column">
                        <div class="material-header-enhanced">
                            <i class="fas fa-download"></i>
                            <h4>Material de Apoyo</h4>
                        </div>
                        <div class="material-content-enhanced" id="modalMaterialContent">
                            <div class="loading-material">
                                <div class="spinner"></div>
                                <p>Cargando materiales...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer video-modal-footer-enhanced">
                <div class="footer-info">
                    <i class="fas fa-info-circle"></i>
                    <span>Contenido exclusivo para miembros premium</span>
                </div>
                <div class="progress-indicator" id="progressIndicator">
                    <div class="progress-text">Esperando reproducción...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ========== ESTILOS PREMIUM ========== */
.aula-virtual-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0;
}

/* Header */
.aula-header {
    background: linear-gradient(135deg, #4361ee, #3a0ca3);
    border-radius: 24px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
}

.aula-header::before {
    content: '';
    position: absolute;
    top: -30%;
    right: -10%;
    width: 60%;
    height: 160%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
    transform: rotate(15deg);
}

.aula-header-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    position: relative;
    z-index: 1;
}

.aula-header-icon {
    width: 70px;
    height: 70px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
}

.aula-header-text h1 {
    color: white;
    font-size: 1.8rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.aula-header-text p {
    color: rgba(255, 255, 255, 0.9);
    margin: 0;
}

.aula-user-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.15);
    padding: 0.3rem 1rem;
    border-radius: 50px;
    font-size: 0.75rem;
    color: white;
    margin-top: 0.75rem;
}

.aula-user-badge.active {
    background: rgba(16, 185, 129, 0.3);
}

.aula-user-badge.inactive {
    background: rgba(239, 68, 68, 0.3);
}

/* Stats */
.stats-aula {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}

.stat-aula-card {
    background: white;
    border-radius: 20px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    border: 1px solid #e2e8f0;
    transition: all 0.3s;
}

.stat-aula-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
}

.stat-aula-icon {
    width: 50px;
    height: 50px;
    background: rgba(67, 97, 238, 0.1);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    color: #4361ee;
}

.stat-aula-info h3 {
    font-size: 1.5rem;
    font-weight: 800;
    margin: 0;
    color: #0f172a;
}

.stat-aula-info p {
    font-size: 0.7rem;
    color: #64748b;
    margin: 0;
}

/* Premium Upgrade Banner */
.premium-upgrade-banner {
    background: linear-gradient(135deg, #1e293b, #0f172a);
    border-radius: 20px;
    margin-bottom: 2rem;
    border: 1px solid #334155;
    position: relative;
    overflow: hidden;
}

.premium-upgrade-banner::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 60%;
    height: 200%;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.1) 0%, transparent 70%);
    transform: rotate(15deg);
}

.premium-upgrade-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem 2rem;
    flex-wrap: wrap;
    gap: 1.5rem;
    position: relative;
    z-index: 1;
}

.premium-upgrade-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: white;
}

.premium-upgrade-text {
    flex: 1;
}

.premium-upgrade-text h4 {
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
    color: white;
}

.premium-upgrade-text p {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

.premium-upgrade-btn {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border: none;
    padding: 0.7rem 1.5rem;
    border-radius: 40px;
    font-weight: 600;
    font-size: 0.85rem;
    color: white;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    white-space: nowrap;
}

.premium-upgrade-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(245, 158, 11, 0.4);
}

/* Preview Badge */
.preview-badge {
    position: absolute;
    top: 5px;
    left: 5px;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(4px);
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.6rem;
    font-weight: 600;
    color: #f59e0b;
    display: flex;
    align-items: center;
    gap: 0.2rem;
}

.preview-tag {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    font-size: 0.6rem;
    font-weight: 700;
    padding: 0.15rem 0.5rem;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
}

/* Badge de recursos */
.recursos-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    background: #f1f5f9;
    color: #10b981;
    font-size: 0.6rem;
    font-weight: 600;
    padding: 0.15rem 0.5rem;
    border-radius: 20px;
    margin-top: 0.5rem;
}

/* Overlay Premium Bloqueo */
.premium-block-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.85);
    backdrop-filter: blur(12px);
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeInUp 0.4s ease;
}

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

.premium-block-card {
    background: linear-gradient(135deg, #1a1a2e, #0f0f1a);
    border-radius: 28px;
    padding: 2rem;
    max-width: 480px;
    width: 90%;
    text-align: center;
    border: 1px solid rgba(245, 158, 11, 0.3);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

.premium-block-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border-radius: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.premium-block-icon i {
    font-size: 2.5rem;
    color: white;
}

.premium-block-card h3 {
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
    margin-bottom: 0.75rem;
}

.premium-block-card p {
    font-size: 0.9rem;
    color: #94a3b8;
    margin-bottom: 0.5rem;
}

.premium-block-description {
    margin-bottom: 1.5rem !important;
}

.premium-block-benefits {
    text-align: left;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 20px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    font-size: 0.8rem;
    color: #cbd5e1;
}

.benefit-item:last-child {
    margin-bottom: 0;
}

.benefit-item i {
    color: #10b981;
    font-size: 0.9rem;
    width: 20px;
}

.premium-block-price {
    background: linear-gradient(135deg, #fef3c7, #fffbeb);
    border-radius: 20px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.price-badge {
    font-size: 0.7rem;
    color: #d97706;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.price-amount {
    font-size: 2.5rem;
    font-weight: 800;
    color: #f59e0b;
    margin: 0.25rem 0;
}

.price-amount span {
    font-size: 0.9rem;
    font-weight: 400;
    color: #b45309;
}

.price-note {
    font-size: 0.7rem;
    color: #b45309;
}

.premium-block-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.btn-premium {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border: none;
    padding: 0.8rem 1.8rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.9rem;
    color: white;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
}

.btn-close-preview {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 0.8rem 1.8rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.9rem;
    color: white;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-close-preview:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.premium-block-secure {
    font-size: 0.7rem;
    color: #64748b;
    margin: 0;
}

.premium-block-secure i {
    margin-right: 0.25rem;
}

/* Asignaturas */
.asignaturas-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.asignatura-block {
    background: white;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
}

.asignatura-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    cursor: pointer;
    background: white;
    transition: background 0.3s;
}

.asignatura-title:hover {
    background: #f8fafc;
}

.asignatura-title-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.asignatura-icon {
    width: 50px;
    height: 50px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    color: white;
}

.asignatura-title-left h3 {
    font-size: 1rem;
    font-weight: 700;
    margin: 0;
    color: #0f172a;
}

.asignatura-title-left p {
    font-size: 0.7rem;
    color: #64748b;
    margin: 0;
    margin-top: 0.25rem;
}

.asignatura-toggle-icon {
    width: 35px;
    height: 35px;
    background: #f8fafc;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s;
    color: #64748b;
}

.asignatura-block.active .asignatura-toggle-icon {
    transform: rotate(180deg);
}

.asignatura-content {
    display: none;
    padding: 1rem;
    border-top: 1px solid #e2e8f0;
    background: #f8fafc;
}

.asignatura-block.active .asignatura-content {
    display: block;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.clases-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.clase-card {
    display: flex;
    gap: 1rem;
    padding: 0.75rem;
    background: white;
    border-radius: 14px;
    cursor: pointer;
    transition: all 0.3s;
    border: 1px solid #e2e8f0;
}

.clase-card:hover {
    transform: translateY(-3px);
    border-color: #4361ee;
    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.15);
}

.clase-thumb {
    width: 120px;
    height: 68px;
    border-radius: 10px;
    overflow: hidden;
    position: relative;
    flex-shrink: 0;
    background: #0f172a;
}

.clase-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.play-overlay {
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

.clase-card:hover .play-overlay {
    opacity: 1;
}

.play-overlay i {
    color: white;
    font-size: 0.8rem;
    margin-left: 2px;
}

.watched-indicator {
    position: absolute;
    bottom: 5px;
    right: 5px;
    background: #10b981;
    color: white;
    font-size: 0.7rem;
    border-radius: 20px;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.clase-info {
    flex: 1;
}

.clase-tags {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    flex-wrap: wrap;
}

.clase-number {
    background: #f8fafc;
    color: #64748b;
    font-size: 0.6rem;
    font-weight: 600;
    padding: 0.15rem 0.5rem;
    border-radius: 20px;
}

.premium-tag {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    font-size: 0.6rem;
    font-weight: 700;
    padding: 0.15rem 0.5rem;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
}

.completed-tag {
    background: #10b981;
    color: white;
    font-size: 0.6rem;
    font-weight: 600;
    padding: 0.15rem 0.5rem;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
}

.clase-info h4 {
    font-size: 0.85rem;
    font-weight: 600;
    margin: 0;
    color: #0f172a;
    line-height: 1.3;
}

/* Exámenes */
.examen-section {
    margin-top: 1.5rem;
    padding-top: 0.5rem;
}

.examen-divider {
    position: relative;
    text-align: center;
    margin-bottom: 1rem;
}

.examen-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
}

.examen-divider span {
    position: relative;
    background: #f8fafc;
    padding: 0 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: #4361ee;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.examen-card-final {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.25rem;
    background: linear-gradient(135deg, #fef3c7, #fffbeb);
    border-radius: 16px;
    border-left: 4px solid #f59e0b;
    transition: all 0.3s;
}

.examen-card-final:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(245, 158, 11, 0.15);
}

.examen-card-final.locked {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-left-color: #94a3b8;
    opacity: 0.8;
}

.examen-icon-final {
    width: 60px;
    height: 60px;
    background: rgba(245, 158, 11, 0.15);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.examen-icon-final i {
    font-size: 1.8rem;
    color: #f59e0b;
}

.examen-info-final {
    flex: 1;
}

.examen-info-final h4 {
    font-size: 1rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
    color: #92400e;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.aprobado-badge {
    background: #10b981;
    color: white;
    font-size: 0.65rem;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.locked-badge {
    background: #64748b;
    color: white;
    font-size: 0.65rem;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.examen-info-final p {
    font-size: 0.75rem;
    color: #b45309;
    margin: 0 0 0.5rem 0;
}

.examen-meta-final {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: center;
}

.examen-meta-final span {
    font-size: 0.65rem;
    color: #b45309;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.examen-tipo-badge {
    background: rgba(245, 158, 11, 0.15);
    color: #d97706 !important;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
}

.intentos-badge {
    background: rgba(16, 185, 129, 0.15);
    color: #059669 !important;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
}

.examen-btn-final {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border: none;
    padding: 0.6rem 1.25rem;
    border-radius: 30px;
    color: white;
    font-weight: 600;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.3s;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.examen-btn-final:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 10px rgba(245, 158, 11, 0.3);
}

.examen-btn-final.locked {
    background: #94a3b8;
    cursor: not-allowed;
}

/* Exámenes Finales del Curso */
.examenes-curso-container {
    margin-top: 2.5rem;
    padding-top: 1.5rem;
    border-top: 2px dashed #e2e8f0;
}

.examenes-curso-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.examenes-curso-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #f59e0b, #ef4444);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.examenes-curso-icon i {
    font-size: 1.8rem;
    color: white;
}

.examenes-curso-header h3 {
    font-size: 1.3rem;
    font-weight: 800;
    margin: 0;
    color: #0f172a;
}

.examenes-curso-header p {
    font-size: 0.8rem;
    color: #64748b;
    margin: 0;
    margin-top: 0.2rem;
}

.examen-curso-card {
    background: linear-gradient(135deg, #1e293b, #0f172a);
    border-radius: 24px;
    padding: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2rem;
    transition: all 0.3s;
    margin-bottom: 1.5rem;
}

.examen-curso-card:last-child {
    margin-bottom: 0;
}

.examen-curso-card.locked {
    opacity: 0.8;
}

.examen-curso-info {
    flex: 1;
}

.examen-curso-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.3rem 1rem;
    border-radius: 50px;
    margin-bottom: 1rem;
}

.examen-curso-info h4 {
    font-size: 1.3rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    color: white;
}

.examen-curso-info>p {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0 0 1rem 0;
    line-height: 1.4;
}

.examen-curso-meta {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.6);
}

.meta-item i {
    font-size: 0.8rem;
    color: #f59e0b;
}

.examen-curso-stats {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-top: 1rem;
}

.stat-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.1);
    padding: 0.3rem 1rem;
    border-radius: 50px;
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.8);
}

.stat-badge.success {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.stat-badge.warning {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
}

.examen-curso-btn {
    background: linear-gradient(135deg, #f59e0b, #ef4444);
    border: none;
    padding: 0.8rem 2rem;
    border-radius: 50px;
    color: white;
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    flex-shrink: 0;
}

.examen-curso-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(245, 158, 11, 0.4);
}

.examen-curso-btn.locked {
    background: #64748b;
}

/* Modal Video */
.modal-fullscreen {
    padding: 0 !important;
}

.modal-fullscreen .modal-content {
    border-radius: 0 !important;
    height: 100vh;
}

.video-modal-enhanced {
    background: #0f0f0f;
    height: 100vh;
    display: flex;
    flex-direction: column;
}

.video-modal-header-enhanced {
    background: #1a1a2e;
    padding: 0.75rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.modal-header-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.btn-back {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    color: white;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.05);
}

.video-info-enhanced h3 {
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
    color: white;
}

.video-info-enhanced p {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.6);
    margin: 0;
    margin-top: 0.2rem;
}

.modal-header-actions {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    color: white;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.05);
}

.close-btn-enhanced:hover {
    background: #ef4444;
}

.modal-layout {
    display: flex;
    height: calc(100vh - 120px);
}

.modal-video-column {
    flex: 1;
    background: #000;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.video-player-enhanced {
    width: 100%;
    height: 100%;
    position: relative;
}

.video-player-enhanced iframe {
    width: 100%;
    height: 100%;
    border: none;
}

.modal-material-column {
    width: 380px;
    background: white;
    display: flex;
    flex-direction: column;
    border-left: 1px solid #e2e8f0;
}

.material-header-enhanced {
    padding: 1rem 1.25rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-shrink: 0;
}

.material-header-enhanced i {
    font-size: 1.2rem;
    color: #10b981;
}

.material-header-enhanced h4 {
    font-size: 1rem;
    font-weight: 700;
    margin: 0;
    color: #0f172a;
}

.material-content-enhanced {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
}

.loading-material {
    text-align: center;
    padding: 2rem;
    color: #64748b;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 3px solid #f8fafc;
    border-top-color: #4361ee;
    border-radius: 50%;
    margin: 0 auto 1rem;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.material-item-enhanced {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 0.75rem;
    transition: all 0.3s;
    border: 1px solid #e2e8f0;
}

.material-item-enhanced:hover {
    transform: translateX(5px);
    border-color: #10b981;
}

.material-icon {
    width: 45px;
    height: 45px;
    background: rgba(16, 185, 129, 0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
}

.material-icon i {
    font-size: 1.3rem;
    color: #10b981;
}

.material-info h5 {
    font-size: 0.85rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    color: #0f172a;
}

.material-info p {
    font-size: 0.7rem;
    color: #64748b;
    margin: 0 0 0.5rem 0;
}

.material-number {
    display: inline-block;
    background: white;
    color: #64748b;
    font-size: 0.6rem;
    font-weight: 600;
    padding: 0.15rem 0.5rem;
    border-radius: 20px;
    margin-bottom: 0.5rem;
}

.download-material-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #10b981;
    color: white;
    border: none;
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    font-size: 0.7rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 0.5rem;
    width: 100%;
    justify-content: center;
}

.download-material-btn:hover {
    background: #059669;
    transform: translateY(-2px);
}

.empty-material-enhanced {
    text-align: center;
    padding: 2rem;
    color: #64748b;
}

.empty-material-enhanced i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-material-enhanced p {
    font-size: 0.85rem;
    margin: 0;
}

.video-modal-footer-enhanced {
    background: #1a1a2e;
    padding: 0.6rem 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.footer-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.6);
}

.footer-info i {
    color: #4361ee;
}

.progress-indicator {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.6);
}

.progress-text {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.progress-text.completed {
    color: #10b981;
}

.progress-text.completed i {
    color: #10b981;
}

/* Responsive */
@media (max-width: 1024px) {
    .stats-aula {
        grid-template-columns: repeat(2, 1fr);
    }

    .modal-material-column {
        width: 300px;
    }
}

@media (max-width: 768px) {
    .stats-aula {
        grid-template-columns: repeat(2, 1fr);
    }

    .modal-layout {
        flex-direction: column;
    }

    .modal-material-column {
        width: 100%;
        height: 300px;
    }

    .aula-header-content {
        flex-direction: column;
        text-align: center;
    }

    .aula-header-text h1 {
        font-size: 1.5rem;
    }

    .clase-card {
        flex-direction: column;
        text-align: center;
    }

    .clase-thumb {
        width: 100%;
        height: 140px;
    }

    .clase-tags {
        justify-content: center;
    }

    .examen-card-final {
        flex-direction: column;
        text-align: center;
    }

    .examen-meta-final {
        justify-content: center;
    }

    .examen-curso-card {
        flex-direction: column;
        text-align: center;
        padding: 1.5rem;
    }

    .examen-curso-meta {
        justify-content: center;
    }

    .examen-curso-stats {
        justify-content: center;
    }

    .examen-curso-btn {
        width: 100%;
        justify-content: center;
    }

    .premium-upgrade-content {
        flex-direction: column;
        text-align: center;
    }

    .premium-block-card {
        padding: 1.5rem;
        width: 95%;
    }

    .premium-block-buttons {
        flex-direction: column;
    }

    .btn-premium,
    .btn-close-preview {
        width: 100%;
        justify-content: center;
    }
}

/* Dark Mode */
body.dark-mode .stat-aula-card,
body.dark-mode .asignatura-block,
body.dark-mode .clase-card {
    background: #1e293b;
    border-color: #334155;
}

body.dark-mode .stat-aula-info h3,
body.dark-mode .asignatura-title-left h3,
body.dark-mode .clase-info h4 {
    color: #f1f5f9;
}

body.dark-mode .stat-aula-info p,
body.dark-mode .asignatura-title-left p {
    color: #94a3b8;
}

body.dark-mode .asignatura-content {
    background: #0f172a;
}

body.dark-mode .asignatura-title {
    background: #1e293b;
}

body.dark-mode .asignatura-title:hover {
    background: #0f172a;
}

body.dark-mode .modal-material-column {
    background: #1e293b;
}

body.dark-mode .material-header-enhanced {
    background: #0f172a;
    border-bottom-color: #334155;
}

body.dark-mode .material-header-enhanced h4 {
    color: #f1f5f9;
}

body.dark-mode .material-item-enhanced {
    background: #0f172a;
    border-color: #334155;
}

body.dark-mode .material-info h5 {
    color: #f1f5f9;
}

body.dark-mode .material-number {
    background: #334155;
    color: #94a3b8;
}

body.dark-mode .clase-number {
    background: #334155;
    color: #94a3b8;
}

body.dark-mode .examen-card-final {
    background: linear-gradient(135deg, #422800, #331d00);
}

body.dark-mode .examen-info-final h4 {
    color: #fbbf24;
}

body.dark-mode .examen-info-final p,
body.dark-mode .examen-meta-final span {
    color: #fcd34d;
}

body.dark-mode .examen-card-final.locked {
    background: linear-gradient(135deg, #1e293b, #0f172a);
}

body.dark-mode .examen-card-final.locked .examen-info-final h4 {
    color: #94a3b8;
}

body.dark-mode .examen-curso-card {
    background: linear-gradient(135deg, #1e293b, #0f172a);
}

body.dark-mode .examenes-curso-header h3 {
    color: #f1f5f9;
}

body.dark-mode .premium-block-card {
    background: linear-gradient(135deg, #1e293b, #0f172a);
}

body.dark-mode .premium-block-price {
    background: linear-gradient(135deg, #422800, #331d00);
}

body.dark-mode .recursos-badge {
    background: #334155;
    color: #34d399;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Variables
let previewTimeout = null;
let planActivo = {
    {
        isset($estudiante) && $estudiante && $estudiante - > plan_activo ? 'true' : 'false'
    }
};
let previewModeActivo = false;

// Función para comprar curso
function comprarCurso() {
    window.location.href = "{{ route('estudiante.checkout') }}";
}

// Función para cerrar el modal completo
function cerrarModalCompleto() {
    const modalElement = document.getElementById('videoModal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    }

    // Limpiar iframe
    const iframe = document.getElementById('modalVideoIframe');
    if (iframe) {
        iframe.src = '';
    }

    // Limpiar timeout
    if (previewTimeout) {
        clearTimeout(previewTimeout);
        previewTimeout = null;
    }

    // Remover blur
    const videoContainer = document.querySelector('.video-player-enhanced');
    if (videoContainer) {
        videoContainer.classList.remove('blurred');
    }

    // Ocultar overlay
    const overlay = document.getElementById('premiumBlockOverlay');
    if (overlay) {
        overlay.style.display = 'none';
    }

    previewModeActivo = false;
}

// Función para mostrar overlay de bloqueo premium (con blur)
function mostrarOverlayPremium() {
    const videoContainer = document.querySelector('.video-player-enhanced');
    const overlay = document.getElementById('premiumBlockOverlay');

    // Aplicar blur al video
    if (videoContainer) {
        videoContainer.classList.add('blurred');
    }

    // Mostrar overlay
    if (overlay) {
        overlay.style.display = 'flex';
    }

    // Limpiar timeout
    if (previewTimeout) {
        clearTimeout(previewTimeout);
        previewTimeout = null;
    }

    // Registrar progreso para que no vuelva a mostrar preview de este video
    if (videoActualId) {
        fetch('{{ route("estudiante.registrar.progreso.video") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                video_id: videoActualId,
                ultimo_segundo: '00:00:15',
                duracion_video: 120,
                porcentaje_visto: 13,
                es_primer_registro: true
            })
        }).catch(error => console.error('Error:', error));
    }

    previewModeActivo = false;
}

// Función para mostrar bloqueo de examen
function mostrarBloqueoExamen() {
    Swal.fire({
        title: '🔒 Contenido Bloqueado',
        html: `
            <div style="text-align: center">
                <i class="fas fa-crown" style="font-size: 3rem; color: #f59e0b; margin-bottom: 1rem;"></i>
                <p>Los exámenes de materia y los exámenes finales están disponibles solo para estudiantes con <strong>Plan Premium</strong>.</p>
                <p class="mt-2">¡Activa tu plan premium para desbloquear todos los exámenes y obtener tu certificado!</p>
                <div class="mt-3 p-3" style="background: linear-gradient(135deg, #fef3c7, #fffbeb); border-radius: 12px;">
                    <p style="font-size: 0.8rem;"><i class="fas fa-check-circle text-success"></i> Acceso a todas las lecciones completas</p>
                    <p style="font-size: 0.8rem;"><i class="fas fa-check-circle text-success"></i> Exámenes por materia</p>
                    <p style="font-size: 0.8rem;"><i class="fas fa-check-circle text-success"></i> Exámenes finales del curso</p>
                    <p style="font-size: 0.8rem;"><i class="fas fa-check-circle text-success"></i> Certificado de finalización</p>
                </div>
                <p class="mt-2"><strong class="text-warning">Solo $800 MXN - Pago único</strong></p>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-shopping-cart"></i> Obtener plan premium',
        cancelButtonText: 'Cerrar',
        background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
        color: document.body.classList.contains('dark-mode') ? '#f1f5f9' : '#0f172a'
    }).then((result) => {
        if (result.isConfirmed) {
            comprarCurso();
        }
    });
}

// Manejar click en clase
let videoActualId = null;

function handleClaseClick(element) {
    const videoId = element.dataset.claseId;
    const videoLink = element.dataset.claseLink;
    const videoTitle = element.dataset.claseNombre;
    const asignaturaNombre = element.dataset.asignaturaNombre;
    const recursoUrl = element.dataset.recursoUrl;

    videoActualId = videoId;

    // Si el video ya está completado, permitir acceso completo
    if (element.querySelector('.completed-tag')) {
        abrirModalVideo(videoId, videoTitle, asignaturaNombre, videoLink, recursoUrl, false);
        return;
    }

    // Si tiene plan activo, acceso completo
    if (planActivo) {
        abrirModalVideo(videoId, videoTitle, asignaturaNombre, videoLink, recursoUrl, false);
        return;
    }

    // Sin plan activo: mostrar preview de 15 segundos
    abrirModalVideo(videoId, videoTitle, asignaturaNombre, videoLink, recursoUrl, true);
}

// Abrir modal de video
function abrirModalVideo(videoId, titulo, asignatura, link, recursoUrl, esPreview = false) {
    // Limpiar cualquier timeout anterior
    if (previewTimeout) {
        clearTimeout(previewTimeout);
        previewTimeout = null;
    }

    // Resetear estado
    previewModeActivo = esPreview;

    // Remover blur y ocultar overlay si estaban visibles
    const videoContainer = document.querySelector('.video-player-enhanced');
    const overlay = document.getElementById('premiumBlockOverlay');

    if (videoContainer) {
        videoContainer.classList.remove('blurred');
    }
    if (overlay) {
        overlay.style.display = 'none';
    }

    // Actualizar título
    document.getElementById('modalVideoTitle').textContent = titulo;
    document.getElementById('modalAsignaturaName').textContent = asignatura + ' | Curso SAINS';

    // Configurar URL del video
    let videoUrl = link;

    if (link.includes('vimeo.com/')) {
        let videoIdUrl = link.split('/').pop().split('?')[0];
        videoUrl = `https://player.vimeo.com/video/${videoIdUrl}?autoplay=1&title=0&byline=0&portrait=0&badge=0`;
    } else if (link.includes('youtube.com') || link.includes('youtu.be')) {
        let match = link.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&?]+)/);
        if (match) {
            videoUrl = `https://www.youtube.com/embed/${match[1]}?autoplay=1&enablejsapi=1`;
        }
    }

    const iframe = document.getElementById('modalVideoIframe');
    iframe.src = videoUrl;

    // Cargar material de apoyo (incluyendo recursos adicionales)
    cargarMaterialDeApoyo(videoId, titulo, recursoUrl);

    // Actualizar texto del progreso
    const progressText = document.querySelector('.progress-text');
    if (progressText) {
        if (esPreview) {
            progressText.innerHTML = '<i class="fas fa-hourglass-half"></i> Vista previa: 15 segundos';
        } else {
            progressText.innerHTML = '<i class="fas fa-play-circle"></i> Reproduciendo clase completa';
        }
    }

    // Mostrar modal
    const modalElement = document.getElementById('videoModal');
    const modal = new bootstrap.Modal(modalElement, {
        backdrop: 'static',
        keyboard: true
    });
    modal.show();

    // Si es preview, configurar timeout para mostrar overlay después de 15 segundos
    if (esPreview) {
        previewTimeout = setTimeout(() => {
            mostrarOverlayPremium();
        }, 15000);
    }

    // Limpiar al cerrar modal
    modalElement.addEventListener('hidden.bs.modal', function() {
        if (previewTimeout) {
            clearTimeout(previewTimeout);
            previewTimeout = null;
        }
        iframe.src = '';
        videoActualId = null;

        if (videoContainer) {
            videoContainer.classList.remove('blurred');
        }
        if (overlay) {
            overlay.style.display = 'none';
        }

        previewModeActivo = false;
    }, {
        once: true
    });
}

// Función para cargar material de apoyo (incluye recurso principal y recursos adicionales)
function cargarMaterialDeApoyo(claseId, titulo, recursoPrincipalUrl) {
    const materialContent = document.getElementById('modalMaterialContent');

    // Mostrar loading
    materialContent.innerHTML = `
        <div class="loading-material">
            <div class="spinner"></div>
            <p>Cargando materiales...</p>
        </div>
    `;

    // Obtener recursos adicionales de la clase mediante fetch
    fetch(`/estudiante/clase-recursos/${claseId}`)
        .then(response => response.json())
        .then(data => {
            let html = '';

            // Recurso principal (material de apoyo de la clase)
            if (recursoPrincipalUrl && recursoPrincipalUrl !== 'null' && recursoPrincipalUrl !== '') {
                html += `
                    <div class="material-item-enhanced">
                        <div class="material-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="material-info">
                            <span class="material-number">Material principal</span>
                            <h5>${escapeHtml(titulo)}</h5>
                            <p>Material de apoyo para complementar tu aprendizaje</p>
                            <button class="download-material-btn" onclick="descargarMaterial('${escapeHtml(recursoPrincipalUrl)}', '${escapeHtml(titulo)}')">
                                <i class="fas fa-download"></i> Descargar material
                            </button>
                        </div>
                    </div>
                `;
            }

            // Recursos adicionales
            if (data.success && data.recursos && data.recursos.length > 0) {
                data.recursos.forEach(recurso => {
                    const icono = getIconoPorTipo(recurso.tipo);
                    const nombreTipo = getNombreTipo(recurso.tipo);

                    html += `
                        <div class="material-item-enhanced">
                            <div class="material-icon">
                                <i class="${icono}"></i>
                            </div>
                            <div class="material-info">
                                <span class="material-number">${nombreTipo}</span>
                                <h5>${escapeHtml(recurso.titulo)}</h5>
                                ${recurso.descripcion ? `<p>${escapeHtml(recurso.descripcion.substring(0, 100))}</p>` : ''}
                                <button class="download-material-btn" onclick="descargarMaterial('${escapeHtml(recurso.url)}', '${escapeHtml(recurso.titulo)}')">
                                    <i class="fas fa-download"></i> ${recurso.tipo === 'enlace' ? 'Abrir enlace' : 'Descargar recurso'}
                                </button>
                            </div>
                        </div>
                    `;
                });
            }

            if (html === '') {
                materialContent.innerHTML = `
                    <div class="empty-material-enhanced">
                        <i class="fas fa-folder-open"></i>
                        <p>No hay material de apoyo disponible para esta clase</p>
                    </div>
                `;
            } else {
                materialContent.innerHTML = html;
            }
        })
        .catch(error => {
            console.error('Error cargando recursos:', error);
            materialContent.innerHTML = `
                <div class="empty-material-enhanced">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Error al cargar los materiales</p>
                </div>
            `;
        });
}

// Función para obtener icono por tipo de recurso
function getIconoPorTipo(tipo) {
    const iconos = {
        'pdf': 'fas fa-file-pdf',
        'video_youtube': 'fab fa-youtube',
        'video_vimeo': 'fab fa-vimeo-v',
        'video_drive': 'fab fa-google-drive',
        'presentacion': 'fas fa-chalkboard',
        'documento': 'fas fa-file-alt',
        'podcast': 'fas fa-podcast',
        'imagen': 'fas fa-image',
        'enlace': 'fas fa-link',
        'otros': 'fas fa-file'
    };
    return iconos[tipo] || 'fas fa-file';
}

// Función para obtener nombre del tipo de recurso
function getNombreTipo(tipo) {
    const nombres = {
        'pdf': '📄 PDF',
        'video_youtube': '🎬 Video YouTube',
        'video_vimeo': '🎬 Video Vimeo',
        'video_drive': '☁️ Video Drive',
        'presentacion': '📊 Presentación',
        'documento': '📝 Documento',
        'podcast': '🎙️ Podcast',
        'imagen': '🖼️ Imagen',
        'enlace': '🔗 Enlace externo',
        'otros': '📁 Otros'
    };
    return nombres[tipo] || '📁 Otros';
}

// Descargar material
function descargarMaterial(url, titulo) {
    if (!url || url === 'null' || url === '') {
        Swal.fire({
            icon: 'warning',
            title: 'Material no disponible',
            text: 'Este material aún no está disponible',
            confirmButtonColor: '#10b981'
        });
        return;
    }
    window.open(url, '_blank');
    Swal.fire({
        icon: 'success',
        title: 'Abriendo',
        text: 'Se abrirá una nueva pestaña con el material',
        confirmButtonColor: '#10b981',
        timer: 2000,
        showConfirmButton: false
    });
}

function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// Toggle asignatura
function toggleAsignatura(element) {
    const block = element.closest('.asignatura-block');
    if (block) {
        block.classList.toggle('active');
    }
}

// Pantalla completa
document.getElementById('fullscreenModalBtn')?.addEventListener('click', function() {
    const container = document.querySelector('.video-player-enhanced');
    if (container.requestFullscreen) {
        container.requestFullscreen();
    } else if (container.webkitRequestFullscreen) {
        container.webkitRequestFullscreen();
    } else if (container.msRequestFullscreen) {
        container.msRequestFullscreen();
    }
});

// Confirmar inicio de examen de MATERIA
function confirmarExamenMateria(examenId, materiaNombre) {
    if (!planActivo) {
        mostrarBloqueoExamen();
        return;
    }

    Swal.fire({
        title: '📝 ¿Listo para el examen?',
        html: `
            <div style="text-align: left">
                <p><strong>📚 Examen de ${materiaNombre}</strong></p>
                <p>Este examen evaluará tus conocimientos sobre la materia.</p>
                <ul style="text-align: left; margin-top: 1rem;">
                    <li>✅ Preguntas de opción múltiple</li>
                    <li>✅ Sin límite de tiempo</li>
                    <li>✅ Calificación mínima: 70%</li>
                    <li>✅ <strong>Intentos ilimitados</strong></li>
                </ul>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-play"></i> Comenzar Examen',
        cancelButtonText: 'Cancelar',
        background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
        color: document.body.classList.contains('dark-mode') ? '#f1f5f9' : '#0f172a'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Cargando examen...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            window.location.href = '/estudiante/examen-materia/' + examenId;
        }
    });
}

// Confirmar inicio de examen de CURSO
function confirmarExamenCurso(examenId, nombreExamen) {
    if (!planActivo) {
        mostrarBloqueoExamen();
        return;
    }

    Swal.fire({
        title: '🎓 ¿Listo para el Examen Final?',
        html: `
            <div style="text-align: left">
                <p><strong>📚 ${nombreExamen}</strong></p>
                <p>Este examen evaluará todos tus conocimientos del curso completo.</p>
                <ul style="text-align: left; margin-top: 1rem;">
                    <li>✅ Preguntas de todas las materias</li>
                    <li>✅ Sin límite de tiempo</li>
                    <li>✅ Calificación mínima: 70%</li>
                    <li>✅ Intentos ilimitados</li>
                    <li>🏆 Certificación al aprobar</li>
                </ul>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-play"></i> Comenzar Examen Final',
        cancelButtonText: 'Cancelar',
        background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
        color: document.body.classList.contains('dark-mode') ? '#f1f5f9' : '#0f172a'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Cargando examen final...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            window.location.href = '/estudiante/examen-curso/' + examenId;
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Aula Virtual - Modo: ' + (planActivo ? 'PREMIUM' : 'PREVIEW (15s con blur)'));
});
</script>
@endsection