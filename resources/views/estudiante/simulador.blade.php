@extends('estudiante.layouts.app')

@section('title', 'Simulador de Examen | SAINS')

@section('content')
<div class="simulator-container">
    <!-- Header del examen -->
    <div class="exam-header">
        <div class="exam-header-content">
            <div class="exam-info">
                <div class="exam-badge">
                    <span class="badge-status {{ $estudiante->plan_activo ? 'active' : 'inactive' }}">
                        <i class="fas {{ $estudiante->plan_activo ? 'fa-crown' : 'fa-lock' }} me-1"></i>
                        {{ $estudiante->plan_activo ? 'Premium Activo' : 'Plan Básico' }}
                    </span>
                </div>
                <h1 class="exam-title" id="examenNombre">
                    <i class="fas fa-play-circle me-2"></i>
                    {{ $examen->nombre ?? $examen->tipo_examen ?? 'Simulador de Examen' }}
                </h1>
                <div class="exam-meta">
                    <span class="meta-item">
                        <i class="fas fa-question-circle"></i>
                        {{ $examen->numero_preguntas ?? 0 }} preguntas
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-clock"></i>
                        {{ $examen->tiempo ?? 0 }} minutos
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-chart-line"></i>
                        Intento #{{ $intento ?? 1 }}
                    </span>
                </div>
            </div>
            <div class="timer-box" id="timerBox">
                <div class="timer-icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="timer-content">
                    <span class="timer-label">Tiempo restante</span>
                    <span class="timer-value" id="timer">00:00</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== SOLO AGREGAR ESTO - ADVERTENCIA PARA PLAN BÁSICO ========== -->
    @if(!$estudiante->plan_activo && isset($intentosRestantes))
    <div style="background: linear-gradient(135deg, #fff3e0, #ffe0b2); border-left: 4px solid #f9ab00; border-radius: 12px; padding: 15px 20px; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <i class="fas fa-info-circle" style="font-size: 24px; color: #f9ab00;"></i>
            <div style="flex: 1;">
                <strong>⚠️ Modo Básico</strong><br>
                <small>Intentos restantes: {{ $intentosRestantes }} de 3 | Máximo {{ $maxPreguntas ?? 5 }} preguntas por simulador</small>
            </div>
            <a href="{{ route('estudiante.checkout') }}" style="background: #f9ab00; color: #000; padding: 8px 15px; border-radius: 8px; text-decoration: none; font-size: 0.85rem;">
                <i class="fas fa-crown"></i> Mejorar Plan
            </a>
        </div>
    </div>
    @endif

    <!-- Selector de examen -->
    @if(isset($examenes) && count($examenes) > 1)
    <div class="exam-selector">
        <div class="selector-header">
            <i class="fas fa-layer-group"></i>
            <span>Selecciona un simulador</span>
        </div>
        <div class="selector-grid">
            @foreach($examenes as $exam)
            <button class="exam-card-btn {{ $exam->id == $examen->id ? 'active' : '' }}"
                    onclick="cambiarExamen({{ $exam->id }}, {{ $exam->tiempo }}, {{ $exam->numero_preguntas }}, '{{ $exam->nombre ?? $exam->tipo_examen ?? 'Simulador' }}')">
                <div class="exam-card-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="exam-card-info">
                    <h4>{{ $exam->nombre ?? $exam->tipo_examen ?? 'Simulador' }}</h4>
                    <div class="exam-card-stats">
                        <span><i class="fas fa-list"></i> {{ $exam->numero_preguntas }} preg</span>
                        <span><i class="fas fa-clock"></i> {{ $exam->tiempo }} min</span>
                    </div>
                </div>
                @if($exam->id == $examen->id)
                <i class="fas fa-check-circle active-icon"></i>
                @endif
            </button>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Barra de progreso -->
    <div class="progress-section">
        <div class="progress-header">
            <div class="progress-title">
                <i class="fas fa-chart-simple"></i>
                <span>Progreso del examen</span>
            </div>
            <div class="progress-stats">
                <span id="respondidasTexto">0</span> / <span id="totalPreguntasSpan">{{ count($preguntas ?? []) }}</span>
                <span class="progress-percent" id="progresoPorcentaje">0%</span>
            </div>
        </div>
        <div class="progress-bar-custom">
            <div class="progress-fill" id="progresoBar" style="width: 0%;"></div>
        </div>
    </div>

    <!-- Navegación rápida -->
    <div class="question-nav">
        <div class="nav-header">
            <i class="fas fa-grid-2"></i>
            <span>Navegación rápida</span>
            <button class="nav-toggle" onclick="toggleQuestionNav()">
                <i class="fas fa-chevron-up" id="navToggleIcon"></i>
            </button>
        </div>
        <div class="nav-grid" id="navGrid">
            @if(isset($preguntas) && count($preguntas) > 0)
                @foreach($preguntas as $index => $pregunta)
                <button class="nav-question-btn" 
                        data-index="{{ $index }}" 
                        onclick="irAPregunta({{ $index }})">
                    {{ $loop->iteration }}
                </button>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Preguntas -->
    <form id="formSimulador" style="display: none;">
        @csrf
        <input type="hidden" name="examen_id" value="{{ $examen->id ?? '' }}" id="examenIdInput">
    </form>

    <div id="preguntasContainer" class="questions-container">
        @if(isset($preguntas) && count($preguntas) > 0)
            @php
                function mezclarOpciones($pregunta) {
                    $opciones = [];
                    
                    if ($pregunta->respuesta_correcta) {
                        $opciones[] = [
                            'texto' => $pregunta->respuesta_correcta,
                            'tipo' => 'correcta',
                            'valor' => 'correcta'
                        ];
                    }
                    
                    if ($pregunta->respuesta1) {
                        $opciones[] = [
                            'texto' => $pregunta->respuesta1,
                            'tipo' => 'incorrecta1',
                            'valor' => 'incorrecta1'
                        ];
                    }
                    
                    if ($pregunta->respuesta2) {
                        $opciones[] = [
                            'texto' => $pregunta->respuesta2,
                            'tipo' => 'incorrecta2',
                            'valor' => 'incorrecta2'
                        ];
                    }
                    
                    if ($pregunta->respuesta3) {
                        $opciones[] = [
                            'texto' => $pregunta->respuesta3,
                            'tipo' => 'incorrecta3',
                            'valor' => 'incorrecta3'
                        ];
                    }
                    
                    shuffle($opciones);
                    return $opciones;
                }
            @endphp
            
            @foreach($preguntas as $index => $pregunta)
            @php
                $opcionesMezcladas = mezclarOpciones($pregunta);
            @endphp
            <div class="question-card" data-index="{{ $index }}" id="pregunta{{ $loop->iteration }}" style="display: {{ $index == 0 ? 'block' : 'none' }};">
                <div class="question-header">
                    <div class="question-number">
                        <span class="number">Pregunta {{ $loop->iteration }}</span>
                        <span class="total">de {{ count($preguntas) }}</span>
                    </div>
                    <div class="question-points">
                        <i class="fas fa-star"></i>
                        <span>1 punto</span>
                    </div>
                </div>
                
                <div class="question-text">
                    <p>{{ $pregunta->pregunta }}</p>
                </div>
                
                <div class="options-container" data-pregunta-id="{{ $pregunta->id }}">
                    @foreach($opcionesMezcladas as $opcion)
                    <label class="option-item" data-pregunta-id="{{ $pregunta->id }}" data-respuesta="{{ $opcion['valor'] }}">
                        <input type="radio" name="respuestas[{{ $pregunta->id }}]" value="{{ $opcion['valor'] }}" class="option-radio">
                        <span class="option-marker"></span>
                        <span class="option-text">{{ $opcion['texto'] }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No hay preguntas disponibles</h3>
                <p>No se encontraron preguntas para este simulador.</p>
            </div>
        @endif
    </div>

    <!-- Botones de navegación -->
    @if(isset($preguntas) && count($preguntas) > 0)
    <div class="navigation-buttons">
        <button class="nav-btn prev-btn" id="btnAnterior" onclick="preguntaAnterior()" disabled>
            <i class="fas fa-arrow-left"></i>
            <span>Anterior</span>
        </button>
        <button class="nav-btn next-btn" id="btnSiguiente" onclick="preguntaSiguiente()">
            <span>Siguiente</span>
            <i class="fas fa-arrow-right"></i>
        </button>
    </div>
    
    <div class="submit-section">
        <button type="button" class="submit-btn" id="btnFinalizar" onclick="finalizarExamen()">
            <i class="fas fa-check-circle me-2"></i>
            Finalizar examen
        </button>
    </div>
    @endif
</div>

<style>
:root {
    --primary-color: #1a73e8;
    --primary-dark: #1557b0;
    --success-color: #0d7c3f;
    --warning-color: #f9ab00;
    --danger-color: #d93025;
    --text-primary: #202124;
    --text-secondary: #5f6368;
    --border-color: #e0e0e0;
    --bg-card: #ffffff;
    --bg-hover: #f8f9fa;
}

.simulator-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
}

.exam-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border-radius: 20px;
    padding: 24px 32px;
    margin-bottom: 24px;
    color: white;
}

.exam-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.exam-badge {
    margin-bottom: 12px;
}

.badge-status {
    display: inline-flex;
    align-items: center;
    padding: 6px 14px;
    background: rgba(255,255,255,0.2);
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    backdrop-filter: blur(10px);
}

.exam-title {
    font-size: 1.75rem;
    font-weight: 600;
    margin: 0 0 12px 0;
}

.exam-meta {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.meta-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    opacity: 0.9;
}

.timer-box {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 12px 24px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.timer-icon {
    font-size: 2rem;
}

.timer-content {
    text-align: center;
}

.timer-label {
    font-size: 0.7rem;
    opacity: 0.8;
    display: block;
}

.timer-value {
    font-size: 2rem;
    font-weight: 700;
    letter-spacing: 2px;
}

.exam-selector {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 24px;
    border: 1px solid var(--border-color);
}

.selector-header {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    margin-bottom: 16px;
    color: var(--text-secondary);
}

.selector-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 12px;
}

.exam-card-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: var(--bg-hover);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
    text-align: left;
    width: 100%;
}

.exam-card-btn:hover {
    background: #e8f0fe;
    border-color: var(--primary-color);
    transform: translateY(-2px);
}

.exam-card-btn.active {
    background: #e8f0fe;
    border-color: var(--primary-color);
}

.exam-card-icon {
    width: 48px;
    height: 48px;
    background: #e8f0fe;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
}

.exam-card-info h4 {
    font-size: 0.95rem;
    font-weight: 600;
    margin: 0 0 4px 0;
    color: var(--text-primary);
}

.exam-card-stats {
    display: flex;
    gap: 12px;
    font-size: 0.7rem;
    color: var(--text-secondary);
}

.exam-card-stats i {
    margin-right: 4px;
}

.active-icon {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary-color);
    font-size: 1.2rem;
}

.progress-section {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 24px;
    border: 1px solid var(--border-color);
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.progress-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    color: var(--text-secondary);
}

.progress-stats {
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.progress-percent {
    font-weight: 600;
    color: var(--primary-color);
    margin-left: 8px;
}

.progress-bar-custom {
    background: #e8eaed;
    border-radius: 8px;
    height: 8px;
    overflow: hidden;
}

.progress-fill {
    background: linear-gradient(90deg, var(--primary-color), #4c9aff);
    height: 100%;
    border-radius: 8px;
    transition: width 0.3s ease;
}

.question-nav {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 16px 20px;
    margin-bottom: 24px;
    border: 1px solid var(--border-color);
}

.nav-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    color: var(--text-secondary);
}

.nav-header span {
    flex: 1;
}

.nav-toggle {
    background: none;
    border: none;
    cursor: pointer;
    color: var(--text-secondary);
    padding: 4px 8px;
    border-radius: 8px;
}

.nav-toggle:hover {
    background: var(--bg-hover);
}

.nav-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.nav-question-btn {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    border: 1px solid var(--border-color);
    background: white;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.nav-question-btn:hover {
    background: var(--bg-hover);
    transform: scale(1.05);
}

.nav-question-btn.respondida {
    background: var(--success-color);
    border-color: var(--success-color);
    color: white;
}

.nav-question-btn.actual {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
    box-shadow: 0 2px 8px rgba(26,115,232,0.3);
}

.questions-container {
    margin-bottom: 24px;
}

.question-card {
    background: var(--bg-card);
    border-radius: 20px;
    border: 1px solid var(--border-color);
    overflow: hidden;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.question-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    background: #f8f9fa;
    border-bottom: 1px solid var(--border-color);
}

.question-number {
    font-weight: 600;
    color: var(--text-primary);
}

.question-number .total {
    color: var(--text-secondary);
    font-weight: normal;
    margin-left: 4px;
}

.question-points {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #f9ab00;
    font-size: 0.85rem;
}

.question-text {
    padding: 24px;
    font-size: 1.1rem;
    line-height: 1.5;
    color: var(--text-primary);
    border-bottom: 1px solid var(--border-color);
}

.options-container {
    padding: 16px 24px 24px;
}

.option-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    margin-bottom: 8px;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s;
    border: 1px solid var(--border-color);
    background: white;
}

.option-item:hover {
    background: #f8f9fa;
    transform: translateX(4px);
}

.option-item.selected {
    background: #e8f0fe;
    border-color: var(--primary-color);
}

.option-radio {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.option-marker {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid var(--border-color);
    background: white;
    transition: all 0.2s;
    flex-shrink: 0;
}

.option-item.selected .option-marker {
    border-color: var(--primary-color);
    background: var(--primary-color);
    box-shadow: inset 0 0 0 3px white;
}

.option-text {
    flex: 1;
    font-size: 0.95rem;
    line-height: 1.4;
    color: var(--text-primary);
}

.navigation-buttons {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 24px;
}

.nav-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 40px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    background: white;
    border: 1px solid var(--border-color);
}

.nav-btn:hover:not(:disabled) {
    background: var(--bg-hover);
    transform: translateY(-2px);
}

.nav-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.prev-btn {
    color: var(--text-secondary);
}

.next-btn {
    background: var(--primary-color);
    color: white;
    border: none;
}

.next-btn:hover {
    background: var(--primary-dark);
}

.submit-section {
    text-align: center;
    padding: 20px 0;
    border-top: 1px solid var(--border-color);
}

.submit-btn {
    background: linear-gradient(135deg, var(--success-color), #0a5c2e);
    color: white;
    border: none;
    padding: 14px 40px;
    border-radius: 40px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.2s;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13,124,63,0.3);
}

.timer-box.warning {
    background: var(--danger-color);
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.9; transform: scale(1.02); }
}

@media (max-width: 768px) {
    .simulator-container { padding: 12px; }
    .exam-header-content { flex-direction: column; text-align: center; }
    .exam-title { font-size: 1.3rem; }
    .timer-value { font-size: 1.3rem; }
    .timer-icon { font-size: 1.5rem; }
    .selector-grid { grid-template-columns: 1fr; }
    .question-header { flex-direction: column; gap: 8px; text-align: center; }
    .nav-btn { padding: 10px 20px; }
    .nav-btn span { display: none; }
    .nav-btn i { margin: 0; }
}

body.dark-mode .exam-selector,
body.dark-mode .progress-section,
body.dark-mode .question-nav,
body.dark-mode .question-card {
    background: #1e293b;
    border-color: #334155;
}

body.dark-mode .question-header {
    background: #0f172a;
}

body.dark-mode .option-item {
    background: #1e293b;
    border-color: #334155;
}

body.dark-mode .option-item:hover {
    background: #334155;
}

body.dark-mode .nav-question-btn {
    background: #0f172a;
    border-color: #334155;
    color: #e2e8f0;
}
</style>

<script>
    // ========== VARIABLES GLOBALES ==========
    let minutosTotales = {{ $examen->tiempo ?? 60 }};
    let tiempoRestanteSegundos = minutosTotales * 60;
    let timerInterval = null;
    let examStarted = true;
    let preguntaActual = 0;
    let totalPreguntas = {{ count($preguntas ?? []) }};
    let respuestasRegistradas = {};
    let examenActualId = {{ $examen->id ?? 0 }};
    let recargando = false;
    
    // Variables para validación de plan básico (con valores por defecto seguros)
    let esUsuarioPremium = {{ isset($estudiante) && $estudiante->plan_activo ? 'true' : 'false' }};
    let maxPreguntasBasico = {{ isset($maxPreguntas) ? $maxPreguntas : 5 }};
    
    console.log('=== INICIALIZACIÓN ===');
    console.log('minutosTotales:', minutosTotales);
    console.log('totalPreguntas:', totalPreguntas);
    console.log('esUsuarioPremium:', esUsuarioPremium);
    console.log('maxPreguntasBasico:', maxPreguntasBasico);
    
    // ========== TIMER ==========
    function iniciarTimer() {
        if (timerInterval) clearInterval(timerInterval);
        actualizarDisplayTimer();
        timerInterval = setInterval(() => {
            if (!examStarted || recargando) return;
            
            if (tiempoRestanteSegundos > 0) {
                tiempoRestanteSegundos--;
                actualizarDisplayTimer();
            }
            
            if (tiempoRestanteSegundos <= 0) {
                clearInterval(timerInterval);
                Swal.fire({
                    title: '⏰ Tiempo agotado',
                    text: 'El tiempo del examen ha terminado. Enviando respuestas...',
                    icon: 'info',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                enviarExamen();
            }
        }, 1000);
    }
    
    function actualizarDisplayTimer() {
        const minutos = Math.floor(tiempoRestanteSegundos / 60);
        const segundos = tiempoRestanteSegundos % 60;
        const timerElement = document.getElementById('timer');
        const timerBox = document.getElementById('timerBox');
        
        if (timerElement) {
            timerElement.innerText = `${String(minutos).padStart(2, '0')}:${String(segundos).padStart(2, '0')}`;
        }
        
        if (tiempoRestanteSegundos <= 300) {
            timerBox?.classList.add('warning');
        } else {
            timerBox?.classList.remove('warning');
        }
        
        if (tiempoRestanteSegundos === 300) {
            Swal.fire({
                title: '⚠️ 5 minutos restantes',
                text: 'Asegúrate de responder todas las preguntas',
                icon: 'warning',
                confirmButtonColor: '#1a73e8',
                timer: 5000
            });
        }
        
        if (tiempoRestanteSegundos === 60) {
            Swal.fire({
                title: '⏰ 1 minuto restante',
                text: 'Último minuto para completar tu examen',
                icon: 'warning',
                confirmButtonColor: '#1a73e8',
                timer: 5000
            });
        }
    }
    
    // ========== PROGRESO ==========
    function actualizarProgreso() {
        const respondidas = Object.keys(respuestasRegistradas).length;
        const porcentaje = totalPreguntas > 0 ? (respondidas / totalPreguntas) * 100 : 0;
        
        const progresoBar = document.getElementById('progresoBar');
        const progresoPorcentaje = document.getElementById('progresoPorcentaje');
        const respondidasTexto = document.getElementById('respondidasTexto');
        
        if (progresoBar) progresoBar.style.width = porcentaje + '%';
        if (progresoPorcentaje) progresoPorcentaje.innerText = Math.round(porcentaje) + '%';
        if (respondidasTexto) respondidasTexto.innerText = respondidas;
        
        document.querySelectorAll('.nav-question-btn').forEach(btn => {
            const idx = parseInt(btn.dataset.index);
            const preguntaCard = document.querySelector(`.question-card[data-index="${idx}"]`);
            if (preguntaCard) {
                const hasAnswer = preguntaCard.querySelector('input[type="radio"]:checked') !== null;
                if (hasAnswer) btn.classList.add('respondida');
                else btn.classList.remove('respondida');
            }
        });
    }
    
    // ========== NAVEGACIÓN ==========
    function irAPregunta(index) {
        document.querySelectorAll('.question-card').forEach(card => card.style.display = 'none');
        const targetCard = document.getElementById(`pregunta${index + 1}`);
        if (targetCard) targetCard.style.display = 'block';
        preguntaActual = index;
        
        const btnAnterior = document.getElementById('btnAnterior');
        const btnSiguiente = document.getElementById('btnSiguiente');
        
        if (btnAnterior) btnAnterior.disabled = (preguntaActual === 0);
        if (btnSiguiente) {
            btnSiguiente.innerHTML = preguntaActual === totalPreguntas - 1 ? 
                '<span>Finalizar</span><i class="fas fa-check-circle"></i>' : 
                '<span>Siguiente</span><i class="fas fa-arrow-right"></i>';
        }
        
        document.querySelectorAll('.nav-question-btn').forEach(btn => {
            btn.classList.remove('actual');
            if (parseInt(btn.dataset.index) === index) btn.classList.add('actual');
        });
    }
    
    function preguntaAnterior() {
        if (preguntaActual > 0) irAPregunta(preguntaActual - 1);
    }
    
    function preguntaSiguiente() {
        if (preguntaActual < totalPreguntas - 1) {
            irAPregunta(preguntaActual + 1);
        } else {
            finalizarExamen();
        }
    }
    
    function toggleQuestionNav() {
        const grid = document.getElementById('navGrid');
        const icon = document.getElementById('navToggleIcon');
        if (grid.style.display === 'none') {
            grid.style.display = 'flex';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            grid.style.display = 'none';
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }
    
    // ========== RESPUESTAS CON VALIDACIÓN PARA BÁSICO ==========
    function guardarRespuesta(preguntaId, valor) {
        // Validar límite para usuarios básicos
        if (!esUsuarioPremium) {
            const respondidas = Object.keys(respuestasRegistradas).length;
            // Si ya alcanzó el límite y no está editando una respuesta existente
            if (respondidas >= maxPreguntasBasico && !respuestasRegistradas[preguntaId]) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Límite alcanzado',
                    html: `Solo puedes responder máximo ${maxPreguntasBasico} preguntas en modo Básico.<br><br>
                           <a href="{{ route('estudiante.checkout') }}" class="btn btn-warning" style="background: #f9ab00; color: #000; padding: 8px 16px; border-radius: 8px; text-decoration: none;">
                               <i class="fas fa-crown"></i> Actualizar a Premium
                           </a>`,
                    confirmButtonText: 'Aceptar'
                });
                return false;
            }
        }
        
        respuestasRegistradas[preguntaId] = valor;
        actualizarProgreso();
        localStorage.setItem(`simulador_${examenActualId}_respuestas`, JSON.stringify(respuestasRegistradas));
        localStorage.setItem(`simulador_${examenActualId}_tiempo`, tiempoRestanteSegundos);
        return true;
    }
    
    // ========== CAMBIAR EXAMEN ==========
    function cambiarExamen(id, tiempo, preguntas, nombre) {
        if (examenActualId == id || recargando) return;
        
        const respondidas = Object.keys(respuestasRegistradas).length;
        const confirmar = () => {
            recargando = true;
            window.location.href = '{{ route("estudiante.simulador.cargar", "") }}/' + id;
        };
        
        if (respondidas > 0) {
            Swal.fire({
                title: '¿Cambiar de examen?',
                text: 'Perderás todas tus respuestas actuales',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#1a73e8',
                confirmButtonText: 'Sí, cambiar',
                cancelButtonText: 'Cancelar'
            }).then(result => { if (result.isConfirmed) confirmar(); });
        } else {
            confirmar();
        }
    }
    
    // ========== FINALIZAR Y ENVIAR ==========
    function finalizarExamen() {
        const respondidas = Object.keys(respuestasRegistradas).length;
        
        // Validar límite para usuarios básicos
        if (!esUsuarioPremium && respondidas > maxPreguntasBasico) {
            Swal.fire({
                icon: 'error',
                title: 'Límite excedido',
                html: `Has respondido <strong>${respondidas}</strong> preguntas.<br>
                       Los usuarios sin plan Premium solo pueden responder máximo <strong>${maxPreguntasBasico}</strong> preguntas.<br><br>
                       <a href="{{ route('estudiante.checkout') }}" class="btn btn-warning" style="background: #f9ab00; color: #000; padding: 8px 16px; border-radius: 8px; text-decoration: none;">
                           <i class="fas fa-crown"></i> Actualizar a Premium
                       </a>`,
                confirmButtonText: 'Entendido'
            });
            return;
        }
        
        if (respondidas < totalPreguntas) {
            Swal.fire({
                title: '⚠️ Preguntas sin responder',
                html: `Has respondido <strong>${respondidas}</strong> de <strong>${totalPreguntas}</strong> preguntas`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1a73e8',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, finalizar',
                cancelButtonText: 'Seguir respondiendo'
            }).then(result => {
                if (result.isConfirmed) enviarExamen();
            });
        } else {
            enviarExamen();
        }
    }
    
    function enviarExamen() {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        formData.append('examen_id', examenActualId);
        
        for (const [preguntaId, valor] of Object.entries(respuestasRegistradas)) {
            formData.append(`respuestas[${preguntaId}]`, valor);
        }
        
        const tiempoLimiteSegundos = minutosTotales * 60;
        const tiempoUtilizadoSegundos = Math.max(0, tiempoLimiteSegundos - tiempoRestanteSegundos);
        
        formData.append('tiempo_utilizado_segundos', tiempoUtilizadoSegundos);
        
        Swal.fire({
            title: 'Enviando respuestas...',
            text: 'Por favor espera',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
        
        fetch('{{ route("estudiante.simulador.responder") }}', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            console.log('Respuesta servidor:', data);
            
            if (data.success) {
                localStorage.removeItem(`simulador_${examenActualId}_respuestas`);
                localStorage.removeItem(`simulador_${examenActualId}_tiempo`);
                
                Swal.fire({
                    icon: 'success',
                    title: '¡Examen completado!',
                    html: `<div class="text-center">
                        <div class="display-1 fw-bold" style="color: #1a73e8;">${data.calificacion}%</div>
                        <p class="mt-3">Has obtenido ${data.calificacion}% de calificación</p>
                        <div class="progress mx-auto" style="height: 10px; max-width: 200px;">
                            <div class="progress-bar bg-success" style="width: ${data.calificacion}%"></div>
                        </div>
                        <p class="mt-2">Aciertos: ${data.aciertos} / ${data.total}</p>
                        <p class="mt-2">⏱️ Tiempo utilizado: ${data.tiempo_utilizado}</p>
                    </div>`,
                    confirmButtonText: 'Ver resultados',
                    confirmButtonColor: '#1a73e8'
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                Swal.fire('Error', data.message, 'error');
                examStarted = true;
                iniciarTimer();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error al enviar el examen', 'error');
            examStarted = true;
            iniciarTimer();
        });
    }
    
    // ========== INICIALIZACIÓN ==========
    document.addEventListener('DOMContentLoaded', function() {
        if (totalPreguntas > 0) {
            const saved = localStorage.getItem(`simulador_${examenActualId}_respuestas`);
            if (saved) {
                const respuestas = JSON.parse(saved);
                for (const [id, valor] of Object.entries(respuestas)) {
                    const input = document.querySelector(`input[name="respuestas[${id}]"][value="${valor}"]`);
                    if (input) {
                        input.checked = true;
                        input.closest('.option-item')?.classList.add('selected');
                        respuestasRegistradas[id] = valor;
                    }
                }
                actualizarProgreso();
            }
            
            const savedTime = localStorage.getItem(`simulador_${examenActualId}_tiempo`);
            if (savedTime && parseInt(savedTime) > 0 && parseInt(savedTime) < tiempoRestanteSegundos) {
                tiempoRestanteSegundos = parseInt(savedTime);
                console.log('Tiempo cargado de localStorage:', tiempoRestanteSegundos);
            }
            
            iniciarTimer();
            irAPregunta(0);
            
            document.querySelectorAll('.option-item').forEach(option => {
                const radio = option.querySelector('input[type="radio"]');
                if (radio) {
                    const preguntaId = radio.name.match(/\[(.*?)\]/)[1];
                    radio.addEventListener('change', function() {
                        const container = this.closest('.options-container');
                        container.querySelectorAll('.option-item').forEach(opt => opt.classList.remove('selected'));
                        this.closest('.option-item').classList.add('selected');
                        guardarRespuesta(preguntaId, this.value);
                    });
                }
                
                option.addEventListener('click', function(e) {
                    if (e.target.tagName !== 'INPUT') {
                        const radio = this.querySelector('input[type="radio"]');
                        if (radio && !radio.checked) {
                            radio.checked = true;
                            radio.dispatchEvent(new Event('change'));
                        }
                    }
                });
            });
        }
    });
</script>
@endsection