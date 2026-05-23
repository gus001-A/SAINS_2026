{{-- resources/views/estudiante/resultados.blade.php --}}
@extends('estudiante.layouts.app')

@section('title', 'Resultados del Examen | SAINS')

@section('content')
<div class="resultados-container">
    <!-- Header con gradiente -->
    <div class="resultados-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="header-text">
                <h1>Resultados del Examen</h1>
                <p>Revisa tu desempeño y sigue mejorando</p>
            </div>
        </div>
    </div>

    <!-- Tarjeta principal de resultados -->
    <div class="resultado-card">
        @php
            $aprobado = $examenRealizado->calificacion >= 70;
            $color = $aprobado ? '#10b981' : '#ef4444';
            $bgColor = $aprobado ? '#d1fae5' : '#fee2e2';
            $icono = $aprobado ? 'fa-check-circle' : 'fa-exclamation-triangle';
            $mensaje = $aprobado ? '¡Felicidades! Has aprobado' : 'Sigue practicando';
            $submensaje = $aprobado ? 'Excelente trabajo, continúa así' : 'No te desanimes, el esfuerzo vale la pena';
        @endphp
        
        <!-- Calificación circular -->
        <div class="calificacion-wrapper">
            <div class="calificacion-circular" style="--porcentaje: {{ $examenRealizado->calificacion }};">
                <svg class="circular-chart" viewBox="0 0 36 36">
                    <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="circle" stroke-dasharray="{{ $examenRealizado->calificacion }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <text x="18" y="20.35" class="percentage">{{ round($examenRealizado->calificacion) }}%</text>
                </svg>
            </div>
            <div class="calificacion-info">
                <div class="calificacion-mensaje">
                    <i class="fas {{ $icono }}" style="color: {{ $color }};"></i>
                    <span style="color: {{ $color }};">{{ $mensaje }}</span>
                </div>
                <p class="calificacion-submensaje">{{ $submensaje }}</p>
            </div>
        </div>

        <!-- Grid de estadísticas -->
        <div class="estadisticas-grid">
            <div class="estadistica-item">
                <div class="estadistica-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="estadistica-info">
                    <span class="estadistica-label">Fecha</span>
                    <span class="estadistica-valor">{{ \Carbon\Carbon::parse($examenRealizado->fecha_inicio)->format('d/m/Y') }}</span>
                </div>
            </div>
            <div class="estadistica-item">
                <div class="estadistica-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="estadistica-info">
                    <span class="estadistica-label">Hora</span>
                    <span class="estadistica-valor">{{ $examenRealizado->hora_inicio }}</span>
                </div>
            </div>
            <div class="estadistica-item">
                <div class="estadistica-icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="estadistica-info">
                    <span class="estadistica-label">Tiempo usado</span>
                    <span class="estadistica-valor">{{ $examenRealizado->tiempo }}</span>
                </div>
            </div>
            <div class="estadistica-item">
                <div class="estadistica-icon">
                    <i class="fas fa-hashtag"></i>
                </div>
                <div class="estadistica-info">
                    <span class="estadistica-label">Número de intento</span>
                    <span class="estadistica-valor">#{{ $examenRealizado->intento }}</span>
                </div>
            </div>
        </div>

        <!-- Logros y récords -->
        @if(isset($mejorCalificacion))
        <div class="logros-section">
            @if($mejorCalificacion > $examenRealizado->calificacion)
            <div class="logro-card info">
                <i class="fas fa-star"></i>
                <div class="logro-content">
                    <span class="logro-label">Mejor calificación</span>
                    <span class="logro-valor">{{ round($mejorCalificacion) }}%</span>
                </div>
            </div>
            @endif
            
            @if($mejorCalificacion == $examenRealizado->calificacion && $examenRealizado->calificacion >= 70)
            <div class="logro-card success">
                <i class="fas fa-crown"></i>
                <div class="logro-content">
                    <span class="logro-label">¡Nuevo récord personal!</span>
                    <span class="logro-valor">Sigue así</span>
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Sección de recomendaciones -->
    <div class="recomendaciones-card">
        <div class="recomendaciones-header">
            <i class="fas fa-lightbulb"></i>
            <h3>Recomendaciones para ti</h3>
        </div>
        <div class="recomendaciones-body">
            @if($examenRealizado->calificacion >= 90)
                <p><i class="fas fa-star"></i> ¡Excelente trabajo! Tu conocimiento es sobresaliente. Sigue así para mantener tu nivel.</p>
                <p><i class="fas fa-arrow-up"></i> Te recomendamos ayudar a otros compañeros que puedan tener dificultades.</p>
            @elseif($examenRealizado->calificacion >= 70)
                <p><i class="fas fa-thumbs-up"></i> Buen trabajo. Has demostrado un buen dominio del tema.</p>
                <p><i class="fas fa-book-open"></i> Te recomendamos repasar los temas donde tuviste errores y practicar más.</p>
            @elseif($examenRealizado->calificacion >= 50)
                <p><i class="fas fa-chart-line"></i> Has mostrado conocimientos básicos del tema.</p>
                <p><i class="fas fa-graduation-cap"></i> Te sugerimos estudiar más a fondo los temas y volver a intentar el simulador.</p>
            @else
                <p><i class="fas fa-heart"></i> No te desanimes, todos podemos mejorar con práctica.</p>
                <p><i class="fas fa-video"></i> Te recomendamos revisar el material de estudio, tomar notas y practicar nuevamente.</p>
            @endif
        </div>
    </div>

    <!-- Historial de intentos anteriores -->
    @if(isset($intentosAnteriores) && $intentosAnteriores->count() > 0)
    <div class="historial-card">
        <div class="historial-header">
            <i class="fas fa-history"></i>
            <h3>Historial de intentos</h3>
            <span class="historial-count">{{ $intentosAnteriores->count() }} intentos previos</span>
        </div>
        <div class="historial-body">
            <div class="timeline">
                @foreach($intentosAnteriores as $index => $intento)
                <div class="timeline-item">
                    <div class="timeline-marker {{ $intento->calificacion >= 70 ? 'success' : 'warning' }}">
                        <i class="fas {{ $intento->calificacion >= 70 ? 'fa-check' : 'fa-chart-line' }}"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <span class="timeline-intento">Intento #{{ $intento->intento }}</span>
                            <span class="timeline-fecha">{{ \Carbon\Carbon::parse($intento->fecha_inicio)->format('d/m/Y') }}</span>
                        </div>
                        <div class="timeline-body">
                            <div class="timeline-info">
                                <i class="fas fa-clock"></i>
                                <span>{{ $intento->hora_inicio }}</span>
                            </div>
                            <div class="timeline-info">
                                <i class="fas fa-hourglass-half"></i>
                                <span>{{ $intento->tiempo }}</span>
                            </div>
                            <div class="timeline-calificacion {{ $intento->calificacion >= 70 ? 'success' : 'warning' }}">
                                {{ round($intento->calificacion) }}%
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Botones de acción -->
    <div class="acciones-buttons">
        <a href="{{ route('estudiante.examenes') }}" class="btn-secondary">
            <i class="fas fa-chart-simple"></i>
            <span>Ver todos mis exámenes</span>
        </a>
        <a href="{{ route('estudiante.dashboard') }}" class="btn-outline">
            <i class="fas fa-home"></i>
            <span>Ir al Dashboard</span>
        </a>
    </div>
</div>

<style>
.resultados-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Header */
.resultados-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 24px;
    padding: 30px;
    margin-bottom: 30px;
    color: white;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 20px;
}

.header-icon {
    width: 60px;
    height: 60px;
    background: rgba(255,255,255,0.2);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.header-icon i {
    font-size: 2rem;
}

.header-text h1 {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0 0 5px 0;
}

.header-text p {
    margin: 0;
    opacity: 0.9;
}

/* Tarjeta de resultado */
.resultado-card {
    background: white;
    border-radius: 24px;
    padding: 40px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.calificacion-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 40px;
    flex-wrap: wrap;
    margin-bottom: 40px;
}

.calificacion-circular {
    width: 180px;
    height: 180px;
}

.circular-chart {
    display: block;
    width: 100%;
    height: 100%;
}

.circle-bg {
    fill: none;
    stroke: #e2e8f0;
    stroke-width: 3;
}

.circle {
    fill: none;
    stroke-width: 3;
    stroke-linecap: round;
    stroke: {{ $aprobado ? '#10b981' : '#ef4444' }};
    animation: progress 1s ease-out forwards;
}

@keyframes progress {
    0% {
        stroke-dasharray: 0, 100;
    }
}

.percentage {
    fill: {{ $aprobado ? '#065f46' : '#991b1b' }};
    font-size: 8px;
    text-anchor: middle;
    font-weight: bold;
}

.calificacion-info {
    text-align: center;
}

.calificacion-mensaje {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.calificacion-submensaje {
    color: #64748b;
    margin: 0;
}

/* Estadísticas grid */
.estadisticas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
    padding-top: 30px;
    border-top: 1px solid #e2e8f0;
}

.estadistica-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8fafc;
    border-radius: 16px;
    transition: all 0.3s;
}

.estadistica-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.estadistica-icon {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.estadistica-icon i {
    font-size: 1.3rem;
    color: white;
}

.estadistica-info {
    flex: 1;
}

.estadistica-label {
    display: block;
    font-size: 0.7rem;
    color: #64748b;
    margin-bottom: 4px;
}

.estadistica-valor {
    display: block;
    font-size: 1rem;
    font-weight: 600;
    color: #1e293b;
}

/* Logros */
.logros-section {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.logro-card {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 20px;
    border-radius: 16px;
    background: #f8fafc;
}

.logro-card.info {
    background: #dbeafe;
    border-left: 4px solid #3b82f6;
}

.logro-card.success {
    background: #d1fae5;
    border-left: 4px solid #10b981;
}

.logro-card i {
    font-size: 1.5rem;
}

.logro-card.info i {
    color: #3b82f6;
}

.logro-card.success i {
    color: #f59e0b;
}

.logro-content {
    flex: 1;
}

.logro-label {
    display: block;
    font-size: 0.7rem;
    color: #64748b;
}

.logro-valor {
    display: block;
    font-size: 1rem;
    font-weight: 700;
}

.logro-card.info .logro-valor {
    color: #1e3a8a;
}

.logro-card.success .logro-valor {
    color: #065f46;
}

/* Recomendaciones */
.recomendaciones-card {
    background: white;
    border-radius: 24px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.recomendaciones-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.recomendaciones-header i {
    font-size: 1.8rem;
    color: #f59e0b;
}

.recomendaciones-header h3 {
    font-size: 1.3rem;
    font-weight: 600;
    margin: 0;
    color: #1e293b;
}

.recomendaciones-body p {
    margin: 10px 0;
    color: #475569;
    line-height: 1.6;
}

.recomendaciones-body i {
    margin-right: 10px;
    color: #667eea;
}

/* Historial con timeline */
.historial-card {
    background: white;
    border-radius: 24px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.historial-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.historial-header i {
    font-size: 1.5rem;
    color: #667eea;
}

.historial-header h3 {
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0;
    color: #1e293b;
}

.historial-count {
    margin-left: auto;
    font-size: 0.8rem;
    padding: 4px 12px;
    background: #e2e8f0;
    border-radius: 20px;
    color: #475569;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 12px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e2e8f0;
}

.timeline-item {
    position: relative;
    margin-bottom: 25px;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border: 2px solid;
}

.timeline-marker.success {
    border-color: #10b981;
    background: #d1fae5;
}

.timeline-marker.warning {
    border-color: #f59e0b;
    background: #fed7aa;
}

.timeline-marker i {
    font-size: 0.7rem;
}

.timeline-marker.success i {
    color: #065f46;
}

.timeline-marker.warning i {
    color: #92400e;
}

.timeline-content {
    background: #f8fafc;
    border-radius: 16px;
    padding: 15px 20px;
    transition: all 0.3s;
}

.timeline-content:hover {
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    flex-wrap: wrap;
    gap: 10px;
}

.timeline-intento {
    font-weight: 700;
    color: #1e293b;
}

.timeline-fecha {
    font-size: 0.8rem;
    color: #64748b;
}

.timeline-body {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.timeline-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    color: #64748b;
}

.timeline-info i {
    font-size: 0.8rem;
}

.timeline-calificacion {
    margin-left: auto;
    font-weight: 700;
    font-size: 1.1rem;
}

.timeline-calificacion.success {
    color: #10b981;
}

.timeline-calificacion.warning {
    color: #f59e0b;
}

/* Botones de acción */
.acciones-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.acciones-buttons a {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 28px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102,126,234,0.4);
}

.btn-secondary {
    background: #f1f5f9;
    color: #1e293b;
}

.btn-secondary:hover {
    background: #e2e8f0;
    transform: translateY(-2px);
}

.btn-outline {
    border: 2px solid #e2e8f0;
    color: #64748b;
}

.btn-outline:hover {
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .resultados-container {
        padding: 15px;
    }
    
    .resultado-card {
        padding: 25px;
    }
    
    .calificacion-wrapper {
        gap: 20px;
    }
    
    .calificacion-circular {
        width: 140px;
        height: 140px;
    }
    
    .calificacion-mensaje {
        font-size: 1.2rem;
    }
    
    .estadisticas-grid {
        grid-template-columns: 1fr;
    }
    
    .timeline {
        padding-left: 20px;
    }
    
    .timeline-marker {
        left: -20px;
    }
    
    .acciones-buttons a {
        flex: 1;
        justify-content: center;
    }
}

/* Dark mode */
body.dark-mode .resultado-card,
body.dark-mode .recomendaciones-card,
body.dark-mode .historial-card {
    background: #1e293b;
}

body.dark-mode .estadistica-item {
    background: #334155;
}

body.dark-mode .estadistica-valor {
    color: #f1f5f9;
}

body.dark-mode .recomendaciones-header h3,
body.dark-mode .historial-header h3 {
    color: #f1f5f9;
}

body.dark-mode .recomendaciones-body p {
    color: #cbd5e1;
}

body.dark-mode .timeline-content {
    background: #334155;
}

body.dark-mode .timeline-intento {
    color: #f1f5f9;
}

body.dark-mode .historial-count {
    background: #475569;
    color: #cbd5e1;
}

body.dark-mode .timeline::before {
    background: #475569;
}

body.dark-mode .btn-secondary {
    background: #334155;
    color: #f1f5f9;
}

body.dark-mode .btn-secondary:hover {
    background: #475569;
}

body.dark-mode .btn-outline {
    border-color: #475569;
    color: #94a3b8;
}

body.dark-mode .btn-outline:hover {
    border-color: #667eea;
    color: #667eea;
}
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animación de entrada para las tarjetas
        const cards = document.querySelectorAll('.resultado-card, .recomendaciones-card, .historial-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 150);
        });
        
        // Animación para los botones
        const buttons = document.querySelectorAll('.acciones-buttons a');
        buttons.forEach((btn, index) => {
            setTimeout(() => {
                btn.style.animation = 'fadeInUp 0.5s ease forwards';
            }, 400 + (index * 100));
        });
    });
</script>

<style>
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
    
    .acciones-buttons a {
        opacity: 0;
    }
</style>
@endpush