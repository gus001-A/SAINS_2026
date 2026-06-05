{{-- resources/views/estudiante/resultados.blade.php --}}
@extends('estudiante.layouts.app')

@section('title', 'Resultados del Examen | SAINS')

@section('content')
<div class="resultados-container">
    <!-- Fondo animado con ondas -->
    <div class="animated-bg">
        <div class="wave wave1"></div>
        <div class="wave wave2"></div>
        <div class="wave wave3"></div>
    </div>

    @php
    // ✅ CALCULAR CORRECTAMENTE LOS ACIERTOS Y TOTAL DE PREGUNTAS
    $totalPreguntasRealizadas = count($preguntasConRespuestas);
    $totalAciertos = 0;
    foreach($preguntasConRespuestas as $pregunta) {
    if($pregunta->es_correcta) {
    $totalAciertos++;
    }
    }
    $totalIncorrectas = $totalPreguntasRealizadas - $totalAciertos;

    // Configurar variables de aprobación
    $aprobado = $examenRealizado->calificacion >= 70;
    $color = $aprobado ? '#10b981' : '#ef4444';
    $bgGradient = $aprobado ? 'linear-gradient(135deg, #10b981, #059669)' : 'linear-gradient(135deg, #ef4444, #dc2626)';
    $iconoResultado = $aprobado ? 'fa-trophy' : 'fa-book-open';
    $mensajeResultado = $aprobado ? '¡EXCELENTE TRABAJO!' : '¡SIGUE MEJORANDO!';
    $descripcionResultado = $aprobado ? 'Has demostrado un excelente dominio del tema' : 'Cada error es una oportunidad
    para aprender y crecer';

    // Configurar Carbon en español para toda la vista
    Carbon\Carbon::setLocale('es');
    @endphp

    <!-- Header con efecto 3D -->
    <div class="resultados-header">
        <div class="header-content">
            <div class="header-text">
                <span class="header-badge">
                    <i class="fas fa-star"></i>
                    Resultados del Examen
                </span>
                <h1 class="glitch-text" data-text="Análisis de Desempeño">Análisis de Desempeño</h1>
                <p>Descubre tu progreso y alcanza tus metas académicas</p>
            </div>
        </div>
        <div class="header-stats">
            <div class="stat-pill">
                <i class="fas fa-calendar"></i>
                <span>{{ \Carbon\Carbon::parse($examenRealizado->fecha_inicio)->locale('es')->isoFormat('dddd, D \\d\\e MMMM \\d\\e YYYY') }}</span>
            </div>
            <div class="stat-pill">
                <i class="fas fa-clock"></i>
                <span>{{ $examenRealizado->hora_inicio }}</span>
            </div>
        </div>
    </div>

    <!-- Tarjeta de resultado principal con efecto neón -->
    <div class="resultado-card {{ $aprobado ? 'aprobado' : 'reprobado' }}">
        <div class="card-glow"></div>
        <div class="resultado-badge">
            <i class="fas {{ $iconoResultado }}"></i>
            <span>{{ $mensajeResultado }}</span>
        </div>

        <div class="calificacion-wrapper">
            <div class="calificacion-3d">
                <div class="circle-chart">
                    <svg viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" class="circle-bg" />
                        <circle cx="50" cy="50" r="45" class="circle-progress" stroke="{{ $color }}"
                            stroke-dasharray="283"
                            stroke-dashoffset="{{ 283 - (283 * $examenRealizado->calificacion / 100) }}" />
                    </svg>
                    <div class="circle-center">
                        <span class="score-number">{{ round($examenRealizado->calificacion) }}</span>
                        <span class="score-percent">%</span>
                    </div>
                </div>
                <div class="calificacion-glow"
                    style="background: radial-gradient(circle, {{ $color }}40 0%, transparent 70%);"></div>
            </div>

            <div class="calificacion-info">
                <div class="resultado-message">
                    <div class="message-icon" style="background: {{ $color }}20;">
                        <i class="fas {{ $aprobado ? 'fa-check-circle' : 'fa-chart-line' }}"
                            style="color: {{ $color }};"></i>
                    </div>
                    <div>
                        <h3>{{ $aprobado ? '¡Felicidades! Has aprobado' : 'No te desanimes' }}</h3>
                        <p>{{ $aprobado ? 'Excelente trabajo, continúa así' : 'Sigue practicando, el esfuerzo vale la pena' }}
                        </p>
                    </div>
                </div>
                <div class="stats-mini">
                    <div class="stat-mini">
                        <i class="fas fa-check-circle" style="color: #10b981;"></i>
                        <span>{{ $totalAciertos }} correctas</span>
                    </div>
                    <div class="stat-mini">
                        <i class="fas fa-times-circle" style="color: #ef4444;"></i>
                        <span>{{ $totalIncorrectas }} incorrectas</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas avanzadas (sin el nivel) -->
        <div class="stats-advanced">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-data">
                    <span class="stat-label">Tiempo total</span>
                    <span class="stat-value">{{ $examenRealizado->tiempo }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-data">
                    <span class="stat-label">Eficiencia</span>
                    <span class="stat-value">{{ round(($examenRealizado->calificacion / 100) * 100, 0) }}%</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-data">
                    <span class="stat-label">Mejor intento</span>
                    <span
                        class="stat-value">{{ isset($mejorCalificacion) ? round($mejorCalificacion) : round($examenRealizado->calificacion) }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline de progreso -->
    @if(isset($intentosAnteriores) && $intentosAnteriores->count() > 0)
    <div class="timeline-card">
        <div class="timeline-header">
            <i class="fas fa-chart-simple"></i>
            <h3>Evolución de tu desempeño</h3>
            <span class="timeline-badge">{{ $intentosAnteriores->count() + 1 }} intentos</span>
        </div>
        <div class="timeline-progress">
            @php
            $todosIntentos = $intentosAnteriores->push($examenRealizado)->sortBy('intento');
            $maxCalificacion = $todosIntentos->max('calificacion');
            @endphp
            @foreach($todosIntentos as $intento)
            @php
            $intentoColor = $intento->calificacion >= 70 ? '#10b981' : ($intento->calificacion >= 50 ? '#f59e0b' :
            '#ef4444');
            $intentoWidth = ($intento->calificacion / 100) * 100;
            @endphp
            <div class="timeline-point {{ $intento->id == $examenRealizado->id ? 'current' : '' }}">
                <div class="point-marker" style="background: {{ $intentoColor }};"></div>
                <div class="point-bar" style="height: {{ $intentoWidth }}px; background: {{ $intentoColor }};"></div>
                <div class="point-label">
                    <span class="point-intento">Intento {{ $intento->intento }}</span>
                    <span class="point-score">{{ round($intento->calificacion) }}%</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Sección de preguntas con diseño acordeón moderno -->
    <div class="questions-accordion">
        <div class="accordion-header">
            <div class="header-info">
                <div class="icon-wrapper">
                    <i class="fas fa-list-check"></i>
                </div>
                <div>
                    <h3>Revisión de respuestas</h3>
                    <p>Analiza cada pregunta y aprende de tus errores</p>
                </div>
            </div>
            <div class="header-filters">
                <button class="filter-chip active" data-filter="all">
                    <i class="fas fa-list"></i>
                    <span>Todas</span>
                </button>
                <button class="filter-chip" data-filter="correct">
                    <i class="fas fa-check-circle"></i>
                    <span>Correctas ({{ $totalAciertos }})</span>
                </button>
                <button class="filter-chip" data-filter="incorrect">
                    <i class="fas fa-times-circle"></i>
                    <span>Incorrectas ({{ $totalIncorrectas }})</span>
                </button>
            </div>
        </div>

        <div class="questions-list">
            @foreach($preguntasConRespuestas as $index => $pregunta)
            <div class="question-accordion-item {{ $pregunta->es_correcta ? 'correct' : 'incorrect' }}"
                data-status="{{ $pregunta->es_correcta ? 'correct' : 'incorrect' }}">
                <div class="question-header" onclick="toggleQuestion(this)">
                    <div class="question-status">
                        <div class="status-icon {{ $pregunta->es_correcta ? 'correct' : 'incorrect' }}">
                            <i class="fas {{ $pregunta->es_correcta ? 'fa-check' : 'fa-times' }}"></i>
                        </div>
                        <div class="question-number">Pregunta {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div class="question-title">
                        <span>{{ Str::limit($pregunta->texto, 80) }}</span>
                    </div>
                    <div class="question-toggle">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>

                <div class="question-body" style="display: none;">
                    <div class="question-full-text">
                        <p>{{ $pregunta->texto }}</p>
                    </div>

                    @php
                    $opcionesLista = [];
                    if (!empty($pregunta->respuesta_correcta)) $opcionesLista['correcta'] =
                    $pregunta->respuesta_correcta;
                    if (!empty($pregunta->respuesta1)) $opcionesLista['incorrecta1'] = $pregunta->respuesta1;
                    if (!empty($pregunta->respuesta2)) $opcionesLista['incorrecta2'] = $pregunta->respuesta2;

                    $respuestaUsuarioNormalizada = trim(strtolower($pregunta->respuesta_usuario ?? ''));
                    $respuestaCorrectaNormalizada = trim(strtolower($pregunta->respuesta_correcta ?? ''));
                    $esCorrectaComparacion = ($respuestaUsuarioNormalizada !== '' && $respuestaUsuarioNormalizada ===
                    $respuestaCorrectaNormalizada);

                    $opcionSeleccionada = null;
                    foreach ($opcionesLista as $tipo => $texto) {
                    if (trim(strtolower($texto)) === $respuestaUsuarioNormalizada) {
                    $opcionSeleccionada = $texto;
                    break;
                    }
                    }
                    $esRespuestaPersonalizada = ($opcionSeleccionada === null && $respuestaUsuarioNormalizada !== '');
                    @endphp

                    <div class="options-container">
                        @foreach($opcionesLista as $tipo => $texto)
                        @php
                        $esOpcionCorrecta = ($tipo === 'correcta');
                        $esOpcionSeleccionada = ($opcionSeleccionada === $texto);
                        $opcionClase = 'option-item';
                        if ($esOpcionCorrecta) $opcionClase .= ' correct-option';
                        if ($esOpcionSeleccionada && $esCorrectaComparacion) $opcionClase .= ' selected-correct';
                        if ($esOpcionSeleccionada && !$esCorrectaComparacion && !$esOpcionCorrecta) $opcionClase .= '
                        selected-wrong';
                        @endphp
                        <div class="{{ $opcionClase }}">
                            <div class="option-marker">
                                @if($esOpcionCorrecta) ✓
                                @elseif($tipo === 'incorrecta1') A
                                @else B
                                @endif
                            </div>
                            <div class="option-text">{{ $texto }}</div>
                            @if($esOpcionCorrecta)
                            <div class="option-badge correct"><i class="fas fa-check"></i> Correcta</div>
                            @endif
                            @if($esOpcionSeleccionada && $esCorrectaComparacion)
                            <div class="option-badge your-correct"><i class="fas fa-star"></i> Tu respuesta</div>
                            @endif
                            @if($esOpcionSeleccionada && !$esCorrectaComparacion && !$esOpcionCorrecta)
                            <div class="option-badge your-wrong"><i class="fas fa-times"></i> Tu respuesta</div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    @if(!$esCorrectaComparacion && !empty($pregunta->respuesta_correcta))
                    <div class="correct-answer-box">
                        <div class="correct-answer-header">
                            <i class="fas fa-lightbulb"></i>
                            <strong>Respuesta correcta</strong>
                        </div>
                        <div class="correct-answer-text">
                            "{{ $pregunta->respuesta_correcta }}"
                        </div>
                    </div>
                    @endif

                    @if(!empty($pregunta->justificacion))
                    <div class="explanation-box">
                        <div class="explanation-header">
                            <i class="fas fa-graduation-cap"></i>
                            <strong>¿Por qué es correcta?</strong>
                        </div>
                        <div class="explanation-text">
                            {{ $pregunta->justificacion }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Tarjeta de recomendaciones premium -->
    <div class="recommendations-premium">
        <div class="recommendations-bg"></div>
        <div class="recommendations-content">
            <div class="recommendations-icon">
                <i class="fas fa-chalkboard-user"></i>
            </div>
            <div class="recommendations-text">
                <h3>Recomendaciones personalizadas</h3>
                <p>Basado en tu desempeño en este examen</p>
            </div>
        </div>

        @php
        $porcentajeAciertos = $totalPreguntasRealizadas > 0 ? (($totalAciertos / $totalPreguntasRealizadas) * 100) : 0;
        @endphp

        <div class="recommendations-grid">
            @if($porcentajeAciertos >= 90)
            <div class="rec-card">
                <div class="rec-icon gradient-green">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="rec-info">
                    <h4>¡Dominio excepcional!</h4>
                    <p>Eres un estudiante destacado. Te invitamos a compartir tus técnicas de estudio.</p>
                </div>
            </div>
            <div class="rec-card">
                <div class="rec-icon gradient-blue">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="rec-info">
                    <h4>Mantén el ritmo</h4>
                    <p>Continúa practicando con simuladores avanzados para seguir desafiándote.</p>
                </div>
            </div>
            @elseif($porcentajeAciertos >= 70)
            <div class="rec-card">
                <div class="rec-icon gradient-orange">
                    <i class="fas fa-thumbs-up"></i>
                </div>
                <div class="rec-info">
                    <h4>¡Buen trabajo!</h4>
                    <p>Has demostrado un buen dominio. Revisa los temas donde tuviste errores.</p>
                </div>
            </div>
            <div class="rec-card">
                <div class="rec-icon gradient-purple">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="rec-info">
                    <h4>Áreas de mejora</h4>
                    <p>Concéntrate en estudiar las preguntas que fallaste. ¡Cada error enseña!</p>
                </div>
            </div>
            @elseif($porcentajeAciertos >= 50)
            <div class="rec-card">
                <div class="rec-icon gradient-yellow">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="rec-info">
                    <h4>Vas por buen camino</h4>
                    <p>Tienes conocimientos básicos. Dedica más tiempo a los temas clave.</p>
                </div>
            </div>
            <div class="rec-card">
                <div class="rec-icon gradient-pink">
                    <i class="fas fa-video"></i>
                </div>
                <div class="rec-info">
                    <h4>Recursos recomendados</h4>
                    <p>Revisa los videos de las clases premium y toma notas para reforzar.</p>
                </div>
            </div>
            @else
            <div class="rec-card">
                <div class="rec-icon gradient-red">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="rec-info">
                    <h4>¡No te desanimes!</h4>
                    <p>Todos podemos mejorar con práctica. Este es solo el comienzo.</p>
                </div>
            </div>
            <div class="rec-card">
                <div class="rec-icon gradient-teal">
                    <i class="fas fa-chalkboard"></i>
                </div>
                <div class="rec-info">
                    <h4>Plan de estudio</h4>
                    <p>Repasa el material desde cero y practica con simuladores básicos.</p>
                </div>
            </div>
            @endif
        </div>

        <div class="recommendations-footer">
            <i class="fas fa-quote-left"></i>
            <p>"El éxito no es la clave de la felicidad. La felicidad es la clave del éxito. Si amas lo que haces,
                tendrás éxito."</p>
            <i class="fas fa-quote-right"></i>
        </div>
    </div>

    <!-- Botones de acción premium -->
    <div class="action-buttons">
        @php
        $rutaReintentar = '';
        if($examenRealizado->examenGenerado && in_array($examenRealizado->examenGenerado->tipo_examen, ['Simulación',
        'simulacion', 'Simulador', 'simulador'])) {
        $rutaReintentar = route('estudiante.simulador');
        } elseif($examenRealizado->examenGenerado && in_array($examenRealizado->examenGenerado->tipo_examen, ['Materia',
        'materia'])) {
        $rutaReintentar = route('estudiante.examen-materia', $examenRealizado->examen);
        } elseif($examenRealizado->examenGenerado && $examenRealizado->examenGenerado->tipo_examen == 'Curso') {
        $rutaReintentar = route('estudiante.examen-curso', $examenRealizado->examen);
        }
        @endphp

        @if($rutaReintentar)
        <a href="{{ $rutaReintentar }}" class="btn-primary">
            <i class="fas fa-redo-alt"></i>
            <span>Intentar de nuevo</span>
            <div class="btn-glow"></div>
        </a>
        @endif
        <a href="{{ route('estudiante.clases-premium') }}" class="btn-secondary">
            <i class="fas fa-graduation-cap"></i>
            <span>Seguir estudiando</span>
        </a>
        <a href="{{ route('estudiante.dashboard') }}" class="btn-outline">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
    </div>
</div>

<style>
/* ========== ESTILOS ULTRA PREMIUM ========== */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

* {
    font-family: 'Inter', sans-serif;
}

.resultados-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    position: relative;
    overflow-x: hidden;
}

/* Fondo animado con ondas */
.animated-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    overflow: hidden;
}

.wave {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100px;
    background: linear-gradient(90deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    border-radius: 100% 100% 0 0;
    animation: waveAnimation 10s ease-in-out infinite;
}

.wave1 {
    animation-delay: 0s;
}

.wave2 {
    animation-delay: -3s;
    opacity: 0.5;
}

.wave3 {
    animation-delay: -6s;
    opacity: 0.3;
}

@keyframes waveAnimation {

    0%,
    100% {
        transform: translateY(0) scaleX(1);
    }

    50% {
        transform: translateY(-30px) scaleX(1.05);
    }
}

/* Header 3D */
.resultados-header {
    position: relative;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 32px;
    padding: 35px 40px;
    margin-bottom: 30px;
    color: white;
    overflow: hidden;
    z-index: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.header-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.2);
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.8rem;
    margin-bottom: 15px;
}

.glitch-text {
    font-size: 2rem;
    font-weight: 800;
    margin: 0 0 8px;
    position: relative;
    animation: glitch 3s infinite;
}

@keyframes glitch {

    0%,
    100% {
        text-shadow: none;
    }

    95% {
        text-shadow: none;
    }

    96% {
        text-shadow: -2px 0 #ff00ff, 2px 0 #00ffff;
    }

    97% {
        text-shadow: none;
    }
}

.header-stats {
    display: flex;
    gap: 15px;
}

.stat-pill {
    background: rgba(255, 255, 255, 0.15);
    padding: 8px 18px;
    border-radius: 50px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    backdrop-filter: blur(10px);
}

/* Tarjeta de resultado principal */
.resultado-card {
    position: relative;
    background: white;
    border-radius: 40px;
    padding: 40px;
    margin-bottom: 30px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    z-index: 1;
}

.card-glow {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
}

.resultado-card.aprobado .card-glow {
    background: linear-gradient(90deg, #10b981, #059669, #10b981);
}

.resultado-card.reprobado .card-glow {
    background: linear-gradient(90deg, #ef4444, #dc2626, #ef4444);
}

.resultado-badge {
    position: absolute;
    top: 20px;
    right: 30px;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 20px;
    background: #f1f5f9;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.85rem;
}

.resultado-card.aprobado .resultado-badge {
    background: #d1fae5;
    color: #065f46;
}

.resultado-card.reprobado .resultado-badge {
    background: #fee2e2;
    color: #991b1b;
}

/* Calificación 3D */
.calificacion-wrapper {
    display: flex;
    align-items: center;
    gap: 50px;
    flex-wrap: wrap;
    margin-bottom: 40px;
}

.calificacion-3d {
    position: relative;
    width: 200px;
    height: 200px;
}

.circle-chart {
    position: relative;
    width: 100%;
    height: 100%;
}

.circle-chart svg {
    width: 100%;
    height: 100%;
    transform: rotate(-90deg);
}

.circle-bg {
    fill: none;
    stroke: #e2e8f0;
    stroke-width: 8;
}

.circle-progress {
    fill: none;
    stroke-width: 8;
    stroke-linecap: round;
    transition: stroke-dashoffset 1.5s ease;
}

.circle-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.score-number {
    font-size: 3rem;
    font-weight: 800;
    color: #1e293b;
}

.score-percent {
    font-size: 1.2rem;
    font-weight: 600;
    color: #64748b;
}

.resultado-card.aprobado .score-number {
    color: #10b981;
}

.resultado-card.reprobado .score-number {
    color: #ef4444;
}

.calificacion-glow {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 220px;
    height: 220px;
    border-radius: 50%;
    opacity: 0.4;
    pointer-events: none;
    animation: pulseGlow 2s ease-in-out infinite;
}

@keyframes pulseGlow {

    0%,
    100% {
        opacity: 0.2;
        transform: translate(-50%, -50%) scale(1);
    }

    50% {
        opacity: 0.4;
        transform: translate(-50%, -50%) scale(1.05);
    }
}

.resultado-message {
    display: flex;
    align-items: center;
    gap: 15px;
}

.message-icon {
    width: 55px;
    height: 55px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.message-icon i {
    font-size: 1.5rem;
}

.resultado-message h3 {
    font-size: 1.3rem;
    margin: 0 0 4px;
}

.resultado-message p {
    margin: 0;
    color: #64748b;
}

.stats-mini {
    display: flex;
    gap: 20px;
    margin-top: 15px;
}

.stat-mini {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
}

/* Estadísticas avanzadas */
.stats-advanced {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    padding-top: 30px;
    border-top: 1px solid #e2e8f0;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8fafc;
    border-radius: 20px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px -10px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon i {
    font-size: 1.3rem;
    color: white;
}

.stat-data {
    flex: 1;
}

.stat-label {
    display: block;
    font-size: 0.7rem;
    text-transform: uppercase;
    color: #64748b;
    margin-bottom: 4px;
}

.stat-value {
    display: block;
    font-size: 1.2rem;
    font-weight: 700;
    color: #1e293b;
}

/* Timeline */
.timeline-card {
    background: white;
    border-radius: 32px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.timeline-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 30px;
}

.timeline-header i {
    font-size: 1.5rem;
    color: #667eea;
}

.timeline-header h3 {
    font-size: 1.2rem;
    margin: 0;
    flex: 1;
}

.timeline-badge {
    padding: 4px 12px;
    background: #e2e8f0;
    border-radius: 50px;
    font-size: 0.8rem;
}

.timeline-progress {
    display: flex;
    justify-content: space-around;
    align-items: flex-end;
    gap: 20px;
    padding: 20px 0;
}

.timeline-point {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    position: relative;
}

.point-marker {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.point-bar {
    width: 4px;
    min-height: 20px;
    border-radius: 2px;
    transition: height 0.5s ease;
}

.point-label {
    text-align: center;
}

.point-intento {
    display: block;
    font-size: 0.7rem;
    color: #64748b;
}

.point-score {
    display: block;
    font-size: 0.9rem;
    font-weight: 700;
}

.timeline-point.current .point-marker {
    width: 16px;
    height: 16px;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
}

.timeline-point.current .point-score {
    color: #667eea;
}

/* Acordeón de preguntas */
.questions-accordion {
    background: white;
    border-radius: 32px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.accordion-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e2e8f0;
}

.header-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.icon-wrapper {
    width: 55px;
    height: 55px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-wrapper i {
    font-size: 1.5rem;
    color: white;
}

.header-info h3 {
    font-size: 1.2rem;
    margin: 0;
}

.header-info p {
    margin: 4px 0 0;
    font-size: 0.8rem;
    color: #64748b;
}

.header-filters {
    display: flex;
    gap: 10px;
}

.filter-chip {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: #f1f5f9;
    border: none;
    border-radius: 50px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-chip.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.filter-chip:hover:not(.active) {
    background: #e2e8f0;
}

.question-accordion-item {
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    margin-bottom: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.question-accordion-item.correct {
    border-left: 4px solid #10b981;
}

.question-accordion-item.incorrect {
    border-left: 4px solid #ef4444;
}

.question-header {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.question-header:hover {
    background: #f8fafc;
}

.question-status {
    display: flex;
    align-items: center;
    gap: 10px;
}

.status-icon {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.status-icon.correct {
    background: #d1fae5;
    color: #10b981;
}

.status-icon.incorrect {
    background: #fee2e2;
    color: #ef4444;
}

.question-number {
    font-weight: 600;
    font-size: 0.85rem;
    color: #64748b;
}

.question-title {
    flex: 1;
    font-weight: 500;
}

.question-toggle {
    color: #64748b;
    transition: transform 0.3s ease;
}

.question-accordion-item.open .question-toggle {
    transform: rotate(180deg);
}

.question-body {
    padding: 0 20px 20px;
    border-top: 1px solid #e2e8f0;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.question-full-text {
    margin: 20px 0;
    padding: 15px;
    background: #f8fafc;
    border-radius: 16px;
}

.question-full-text p {
    margin: 0;
    font-size: 1rem;
    line-height: 1.6;
}

.options-container {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}

.option-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: #f8fafc;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.option-item.correct-option {
    background: #f0fdf4;
    border-color: #86efac;
}

.option-item.selected-correct {
    background: #eff6ff;
    border-color: #93c5fd;
}

.option-item.selected-wrong {
    background: #fef2f2;
    border-color: #fca5a5;
}

.option-marker {
    width: 30px;
    height: 30px;
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    border: 1px solid #e2e8f0;
}

.option-text {
    flex: 1;
}

.option-badge {
    padding: 4px 10px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 500;
}

.option-badge.correct {
    background: #d1fae5;
    color: #065f46;
}

.option-badge.your-correct {
    background: #dbeafe;
    color: #1e40af;
}

.option-badge.your-wrong {
    background: #fee2e2;
    color: #991b1b;
}

.correct-answer-box {
    margin: 20px 0;
    padding: 16px;
    background: linear-gradient(135deg, #d1fae5, #ecfdf5);
    border-radius: 16px;
    border-left: 4px solid #10b981;
}

.correct-answer-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
    color: #065f46;
}

.correct-answer-text {
    font-size: 1rem;
    padding: 8px 12px;
    background: white;
    border-radius: 12px;
    color: #065f46;
}

.explanation-box {
    margin: 20px 0;
    padding: 16px;
    background: #fef3c7;
    border-radius: 16px;
    border-left: 4px solid #f59e0b;
}

.explanation-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
    color: #92400e;
}

.explanation-text {
    color: #78350f;
    line-height: 1.6;
}

/* Recomendaciones premium */
.recommendations-premium {
    position: relative;
    background: linear-gradient(135deg, #1e293b, #0f172a);
    border-radius: 40px;
    padding: 40px;
    margin-bottom: 30px;
    color: white;
    overflow: hidden;
}

.recommendations-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 100% 0%, rgba(102, 126, 234, 0.2), transparent 60%);
    pointer-events: none;
}

.recommendations-content {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
}

.recommendations-icon {
    width: 70px;
    height: 70px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}

.recommendations-icon i {
    font-size: 2rem;
}

.recommendations-text h3 {
    font-size: 1.4rem;
    margin: 0 0 5px;
}

.recommendations-text p {
    margin: 0;
    opacity: 0.8;
}

.recommendations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.rec-card {
    display: flex;
    gap: 15px;
    padding: 20px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 24px;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.rec-card:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.12);
}

.rec-icon {
    width: 50px;
    height: 50px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.rec-icon i {
    font-size: 1.3rem;
    color: white;
}

.gradient-green {
    background: linear-gradient(135deg, #10b981, #059669);
}

.gradient-blue {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
}

.gradient-orange {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.gradient-purple {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
}

.gradient-yellow {
    background: linear-gradient(135deg, #eab308, #ca8a04);
}

.gradient-pink {
    background: linear-gradient(135deg, #ec4899, #db2777);
}

.gradient-red {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.gradient-teal {
    background: linear-gradient(135deg, #14b8a6, #0d9488);
}

.rec-info h4 {
    margin: 0 0 5px;
    font-size: 1rem;
}

.rec-info p {
    margin: 0;
    font-size: 0.85rem;
    opacity: 0.8;
}

.recommendations-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    text-align: center;
}

.recommendations-footer i {
    color: #f59e0b;
    opacity: 0.5;
}

.recommendations-footer p {
    margin: 0;
    font-style: italic;
    font-size: 0.9rem;
}

/* Botones de acción */
.action-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.action-buttons a {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 32px;
    border-radius: 60px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    overflow: hidden;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
}

.btn-primary .btn-glow {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s ease;
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
}

.btn-primary:hover .btn-glow {
    left: 100%;
}

.btn-secondary {
    background: #334155;
    color: white;
}

.btn-secondary:hover {
    transform: translateY(-3px);
    background: #475569;
}

.btn-outline {
    border: 2px solid #e2e8f0;
    color: #64748b;
}

.btn-outline:hover {
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-3px);
}

/* Dark mode */
body.dark-mode .resultado-card,
body.dark-mode .timeline-card,
body.dark-mode .questions-accordion {
    background: #1e293b;
}

body.dark-mode .stat-card {
    background: #0f172a;
}

body.dark-mode .stat-value {
    color: #f1f5f9;
}

body.dark-mode .question-accordion-item {
    border-color: #334155;
}

body.dark-mode .question-header:hover {
    background: #0f172a;
}

body.dark-mode .option-item {
    background: #0f172a;
    border-color: #334155;
}

body.dark-mode .option-text {
    color: #cbd5e1;
}

body.dark-mode .question-full-text {
    background: #0f172a;
}

/* Responsive */
@media (max-width: 768px) {
    .resultados-container {
        padding: 15px;
    }

    .resultados-header {
        flex-direction: column;
        text-align: center;
    }

    .resultado-card {
        padding: 25px;
    }

    .calificacion-wrapper {
        justify-content: center;
        text-align: center;
    }

    .resultado-message {
        flex-direction: column;
        text-align: center;
    }

    .stats-advanced {
        grid-template-columns: repeat(2, 1fr);
    }

    .accordion-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .question-header {
        flex-wrap: wrap;
    }

    .question-title {
        width: 100%;
        order: 3;
        margin-top: 10px;
    }

    .recommendations-grid {
        grid-template-columns: 1fr;
    }

    .action-buttons {
        flex-direction: column;
    }

    .action-buttons a {
        justify-content: center;
    }
}
</style>
@endsection

@push('scripts')
<script>
function toggleQuestion(element) {
    const item = element.closest('.question-accordion-item');
    const body = item.querySelector('.question-body');
    const isOpen = body.style.display === 'block';

    // Cerrar todos
    document.querySelectorAll('.question-body').forEach(b => b.style.display = 'none');
    document.querySelectorAll('.question-accordion-item').forEach(i => i.classList.remove('open'));

    // Abrir el actual si estaba cerrado
    if (!isOpen) {
        body.style.display = 'block';
        item.classList.add('open');
    }
}

// Filtro de preguntas
const filterBtns = document.querySelectorAll('.filter-chip');
const questions = document.querySelectorAll('.question-accordion-item');

filterBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        const filter = this.dataset.filter;

        filterBtns.forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        questions.forEach(q => {
            if (filter === 'all') {
                q.style.display = 'block';
            } else {
                const status = q.dataset.status;
                q.style.display === status === filter ? 'block' : 'none';
            }
        });
    });
});

// Animación de entrada
document.querySelectorAll('.resultado-card, .timeline-card, .questions-accordion, .recommendations-premium').forEach((
    el, i) => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    setTimeout(() => {
        el.style.transition = 'all 0.6s ease-out';
        el.style.opacity = '1';
        el.style.transform = 'translateY(0)';
    }, i * 150);
});

// Animar barras del timeline
setTimeout(() => {
    document.querySelectorAll('.point-bar').forEach(bar => {
        const height = bar.style.height;
        bar.style.height = '0px';
        setTimeout(() => {
            bar.style.height = height;
        }, 100);
    });
}, 500);
</script>
@endpush