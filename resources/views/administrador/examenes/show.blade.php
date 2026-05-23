@extends('administrador.layouts.master')

@section('title', 'Detalles del Examen - SAINS')

@section('content')
<div class="container-fluid px-4">
    <!-- Header con estadísticas mejorado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-3 p-2" style="background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);">
                            <i class="fas fa-file-alt fa-2x" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
                        </div>
                        <div>
                            <h1 class="display-5 fw-bold mb-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                                Examen #{{ $examen->id }}
                            </h1>
                            <p class="text-muted fs-6 mb-0 mt-1">
                                <i class="fas fa-calendar-alt me-1"></i> Creado: {{ $examen->created_at ? \Carbon\Carbon::parse($examen->created_at)->format('d/m/Y H:i') : '—' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.examenes.edit', $examen->id) }}" class="btn btn-primary-custom px-4 py-2 shadow-sm">
                        <i class="fas fa-edit me-2"></i>Editar Examen
                    </a>
                    <a href="{{ route('admin.examenes.index') }}" class="btn btn-outline-secondary px-4 py-2">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de estadísticas mejoradas -->
    <div class="row mb-4 g-3">
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Preguntas Totales</p>
                            <h4 class="fw-bold mb-0 text-primary">{{ $examen->numero_preguntas ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(102,126,234,0.1);">
                            <i class="fas fa-question-circle text-primary fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Tiempo Límite</p>
                            <h4 class="fw-bold mb-0 text-success">{{ $examen->tiempo ?? 0 }} min</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(40,167,69,0.1);">
                            <i class="fas fa-clock text-success fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Tipo de Examen</p>
                            @php
                                $tipoConfig = [
                                    'Materia' => ['color' => 'primary', 'icon' => 'fa-book', 'bg' => 'rgba(102,126,234,0.1)'],
                                    'General del curso' => ['color' => 'success', 'icon' => 'fa-graduation-cap', 'bg' => 'rgba(40,167,69,0.1)'],
                                    'Simulación' => ['color' => 'warning', 'icon' => 'fa-chart-line', 'bg' => 'rgba(255,193,7,0.1)']
                                ];
                                $config = $tipoConfig[$examen->tipo_examen] ?? $tipoConfig['General del curso'];
                            @endphp
                            <h4 class="fw-bold mb-0 text-{{ $config['color'] }}">{{ $examen->tipo_examen }}</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: {{ $config['bg'] }};">
                            <i class="fas {{ $config['icon'] }} text-{{ $config['color'] }} fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">ID del Examen</p>
                            <h4 class="fw-bold mb-0 text-info">#{{ $examen->id }}</h4>
                        </div>
                        <div class="rounded-circle p-2" style="background: rgba(23,162,184,0.1);">
                            <i class="fas fa-hashtag text-info fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Columna de información - Tarjeta mejorada -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-lg-top" style="top: 20px;">
                <div class="card-body p-4">
                    <!-- Tipo de examen con badge animado -->
                    <div class="text-center mb-4">
                        <div class="rounded-circle p-3 d-inline-flex mb-3" style="background: {{ $config['bg'] ?? 'rgba(102,126,234,0.1)' }};">
                            <i class="fas {{ $config['icon'] }} fa-3x text-{{ $config['color'] }}"></i>
                        </div>
                        <h3 class="mb-2">{{ $examen->tipo_examen }}</h3>
                        <div class="d-flex justify-content-center gap-2">
                            <span class="badge bg-{{ $config['color'] }} bg-opacity-10 text-{{ $config['color'] }} px-3 py-2 rounded-pill">
                                <i class="fas fa-tag me-1"></i>{{ $examen->tipo_examen }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Información adicional mejorada -->
                    <div class="border-top pt-3 mt-3">
                        <h6 class="fw-semibold mb-3">
                            <i class="fas fa-info-circle me-2 text-primary"></i>Información del Examen
                        </h6>
                        <div class="info-item">
                            <label>Fecha de creación:</label>
                            <span>{{ $examen->created_at ? \Carbon\Carbon::parse($examen->created_at)->format('d/m/Y H:i') : '—' }}</span>
                        </div>
                        <div class="info-item">
                            <label>Última actualización:</label>
                            <span>{{ $examen->updated_at ? \Carbon\Carbon::parse($examen->updated_at)->format('d/m/Y H:i') : '—' }}</span>
                        </div>
                        <div class="info-item">
                            <label>Preguntas asignadas:</label>
                            <span class="fw-bold text-primary">{{ $totalPreguntas ?? 0 }}</span>
                        </div>
                    </div>
                    
                    <!-- Distribución por área -->
                    @if(isset($preguntasPorArea) && count($preguntasPorArea) > 0)
                    <div class="border-top pt-3 mt-3">
                        <h6 class="fw-semibold mb-3">
                            <i class="fas fa-chart-pie me-2 text-primary"></i>Distribución por Área
                        </h6>
                        @foreach($preguntasPorArea as $area => $cantidad)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small fw-semibold">{{ $area }}</span>
                                    <span class="small text-muted">{{ $cantidad }} preguntas</span>
                                </div>
                                <div class="progress rounded-pill" style="height: 6px;">
                                    <div class="progress-bar rounded-pill" style="width: {{ ($cantidad / $examen->numero_preguntas) * 100 }}%; background: linear-gradient(90deg, #667eea, #764ba2);"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Columna de preguntas - Mejorada con acordeón (SOLO 3 OPCIONES) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h4 class="mb-0 fw-semibold">
                                <i class="fas fa-list-check me-2 text-primary"></i>Lista de Preguntas
                            </h4>
                            <p class="text-muted small mt-1">Haz clic en cada pregunta para ver las respuestas</p>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                <i class="fas fa-database me-1"></i>
                                Total: <strong>{{ $totalPreguntas ?? 0 }}</strong> preguntas
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if(isset($examen->preguntas_lista) && $examen->preguntas_lista->count() > 0)
                        <div class="accordion" id="preguntasAccordion">
                            @foreach($examen->preguntas_lista as $index => $pregunta)
                                <div class="accordion-item mb-3 border-0 shadow-sm rounded-4 overflow-hidden">
                                    <h2 class="accordion-header" id="heading{{ $index }}">
                                        <button class="accordion-button collapsed rounded-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}" style="background: linear-gradient(135deg, #667eea05 0%, #764ba205 100%);">
                                            <div class="d-flex align-items-center gap-3 w-100">
                                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    {{ $index + 1 }}
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold mb-1">{{ Str::limit($pregunta->texto_pregunta ?? 'Pregunta sin texto', 100) }}</div>
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        @if($pregunta->area_nombre)
                                                            <span class="badge" style="background: rgba(102, 126, 234, 0.15); color: #667eea;">
                                                                <i class="fas fa-layer-group me-1"></i>{{ $pregunta->area_nombre }}
                                                            </span>
                                                        @endif
                                                        <span class="badge bg-success bg-opacity-10 text-success">
                                                            <i class="fas fa-check-circle me-1"></i>Correcta: {{ $pregunta->respuesta_correcta ?? 'N/A' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <i class="fas fa-chevron-down text-muted"></i>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#preguntasAccordion">
                                        <div class="accordion-body p-4">
                                            <div class="row g-3">
                                                <!-- Opción 1 -->
                                                <div class="col-md-4">
                                                    <div class="card border-0 rounded-4 h-100 {{ $pregunta->respuesta_correcta == 1 ? 'border-success border-2' : '' }}" style="background: rgba(102, 126, 234, 0.05);">
                                                        <div class="card-body p-3">
                                                            <div class="d-flex align-items-start gap-2">
                                                                <div class="rounded-circle {{ $pregunta->respuesta_correcta == 1 ? 'bg-success text-white' : 'bg-primary bg-opacity-10 text-primary' }} fw-bold" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                                    1
                                                                </div>
                                                                <div>
                                                                    <strong>Opción 1</strong>
                                                                    <p class="mb-0 mt-1 small">{{ $pregunta->respuesta1 ?? 'N/A' }}</p>
                                                                    @if($pregunta->respuesta_correcta == 1)
                                                                        <span class="badge bg-success mt-2"><i class="fas fa-check me-1"></i>Respuesta correcta</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Opción 2 -->
                                                <div class="col-md-4">
                                                    <div class="card border-0 rounded-4 h-100 {{ $pregunta->respuesta_correcta == 2 ? 'border-success border-2' : '' }}" style="background: rgba(118, 75, 162, 0.05);">
                                                        <div class="card-body p-3">
                                                            <div class="d-flex align-items-start gap-2">
                                                                <div class="rounded-circle {{ $pregunta->respuesta_correcta == 2 ? 'bg-success text-white' : 'bg-warning bg-opacity-10 text-warning' }} fw-bold" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                                    2
                                                                </div>
                                                                <div>
                                                                    <strong>Opción 2</strong>
                                                                    <p class="mb-0 mt-1 small">{{ $pregunta->respuesta2 ?? 'N/A' }}</p>
                                                                    @if($pregunta->respuesta_correcta == 2)
                                                                        <span class="badge bg-success mt-2"><i class="fas fa-check me-1"></i>Respuesta correcta</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Opción 3 -->
                                                <div class="col-md-4">
                                                    <div class="card border-0 rounded-4 h-100 {{ $pregunta->respuesta_correcta == 3 ? 'border-success border-2' : '' }}" style="background: rgba(245, 124, 0, 0.05);">
                                                        <div class="card-body p-3">
                                                            <div class="d-flex align-items-start gap-2">
                                                                <div class="rounded-circle {{ $pregunta->respuesta_correcta == 3 ? 'bg-success text-white' : 'bg-info bg-opacity-10 text-info' }} fw-bold" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                                    3
                                                                </div>
                                                                <div>
                                                                    <strong>Opción 3</strong>
                                                                    <p class="mb-0 mt-1 small">{{ $pregunta->respuesta_correcta ?? 'N/A' }}</p>
                                                                    @if($pregunta->respuesta_correcta == 3)
                                                                        <span class="badge bg-success mt-2"><i class="fas fa-check me-1"></i>Respuesta correcta</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-file-excel fa-4x text-muted"></i>
                            </div>
                            <h5>No hay preguntas asignadas</h5>
                            <p class="text-muted mb-4">Este examen aún no tiene preguntas registradas.</p>
                            <a href="{{ route('admin.examenes.edit', $examen->id) }}" class="btn btn-primary-custom px-5 py-2">
                                <i class="fas fa-plus-circle me-2"></i>Agregar preguntas
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Animaciones y efectos */
    .hover-card {
        transition: all 0.2s ease-in-out;
    }
    
    .hover-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    .accordion-button:not(.collapsed) {
        background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%) !important;
        box-shadow: none !important;
    }
    
    .accordion-button:focus {
        box-shadow: none;
        border-color: transparent;
    }
    
    .accordion-button::after {
        background-size: 1rem;
        display: none;
    }
    
    .accordion-button .fa-chevron-down {
        transition: transform 0.3s ease;
    }
    
    .accordion-button:not(.collapsed) .fa-chevron-down {
        transform: rotate(180deg);
    }
    
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .sticky-lg-top {
        position: sticky;
        top: 20px;
    }
    
    /* Info items */
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-item label {
        font-weight: 600;
        color: #64748b;
        font-size: 0.8rem;
        margin-bottom: 0;
    }
    
    .info-item span {
        color: #1e293b;
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    /* Botones principales */
    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 500;
        transition: all 0.2s ease;
        border-radius: 12px;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .btn-outline-secondary {
        border: 2px solid #e9ecef;
        transition: all 0.2s ease;
        background: transparent;
        border-radius: 12px;
        font-weight: 500;
    }
    
    .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
    }
    
    /* Progress bar */
    .progress {
        background-color: rgba(102, 126, 234, 0.1);
    }
    
    /* Dark Mode */
    body.dark-mode .card {
        background-color: #1a1a2e;
    }
    
    body.dark-mode .accordion-item {
        background: #1a1a2e;
        border-color: rgba(255,255,255,0.1);
    }
    
    body.dark-mode .accordion-button {
        background: #1a1a2e;
        color: #e0e0e0;
    }
    
    body.dark-mode .accordion-button:not(.collapsed) {
        background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%) !important;
        color: #e0e0e0;
    }
    
    body.dark-mode .info-item {
        border-bottom-color: rgba(255, 255, 255, 0.05);
    }
    
    body.dark-mode .info-item label {
        color: #9ca3af;
    }
    
    body.dark-mode .info-item span {
        color: #e0e0e0;
    }
    
    body.dark-mode .btn-outline-secondary {
        border-color: rgba(102, 126, 234, 0.5);
        color: #e0e0e0;
    }
    
    body.dark-mode .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
    }
    
    body.dark-mode .progress {
        background-color: rgba(102, 126, 234, 0.2);
    }
    
    /* Scroll suave */
    html {
        scroll-behavior: smooth;
    }
</style>
@endpush