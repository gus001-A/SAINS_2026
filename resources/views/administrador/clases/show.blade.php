@extends('administrador.layouts.master')

@section('title', 'Detalles de la Clase - SAINS')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="display-5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                    <i class="fas fa-chalkboard me-3"></i>Detalles de la Clase
                </h1>
                <p class="text-muted">Información completa de la clase educativa y sus recursos</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.clases.edit', $clase->id) }}" class="btn btn-warning rounded-pill px-4">
                    <i class="fas fa-edit me-2"></i>Editar Clase
                </a>
                <a href="{{ route('admin.clases.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i>Volver al listado
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-modern p-4">
            <!-- Header de la Clase -->
            <div class="d-flex flex-column flex-md-row align-items-center gap-4 mb-5 pb-3 border-bottom">
                <div class="avatar-perfil">
                    <i class="fas fa-chalkboard-user fa-4x"></i>
                </div>
                <div class="text-center text-md-start">
                    <h2 class="mb-1 fw-bold">{{ $clase->nombre_clase }}</h2>
                    <p class="text-muted mb-2">
                        <i class="fas fa-book me-1"></i> {{ $clase->asignatura->nombre ?? 'Sin materia' }}
                    </p>
                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                        <span class="badge-clase"><i class="fas fa-hashtag me-1"></i> Clase #{{ $clase->num_clase }}</span>
                        <span class="badge-recursos"><i class="fas fa-paperclip me-1"></i> {{ $clase->recursos->count() }} Recursos</span>
                        @if($clase->link)
                            <span class="badge-video"><i class="fas fa-video me-1"></i> Con video</span>
                        @else
                            <span class="badge-sin-video"><i class="fas fa-video-slash me-1"></i> Sin video</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Dashboard de Métricas -->
            <div class="row g-3 mb-5">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: rgba(102,126,234,0.1);">
                            <i class="fas fa-hashtag" style="color: #667eea;"></i>
                        </div>
                        <div class="stats-info">
                            <h3 class="stats-number">{{ $clase->num_clase }}</h3>
                            <p class="stats-label">Número de clase</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: rgba(16,185,129,0.1);">
                            <i class="fas fa-paperclip" style="color: #10b981;"></i>
                        </div>
                        <div class="stats-info">
                            <h3 class="stats-number">{{ $clase->recursos->count() }}</h3>
                            <p class="stats-label">Recursos adicionales</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: rgba(245,158,11,0.1);">
                            <i class="fas fa-calendar-alt" style="color: #f59e0b;"></i>
                        </div>
                        <div class="stats-info">
                            <h3 class="stats-number">{{ $clase->created_at->format('d/m/Y') }}</h3>
                            <p class="stats-label">Fecha creación</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: rgba(139,92,246,0.1);">
                            <i class="fas fa-edit" style="color: #8b5cf6;"></i>
                        </div>
                        <div class="stats-info">
                            <h3 class="stats-number">{{ $clase->updated_at->format('d/m/Y') }}</h3>
                            <p class="stats-label">Última actualización</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Video e Información Principal -->
            <div class="row g-4 mb-4">
                <!-- Video Principal -->
                <div class="col-lg-7">
                    <div class="info-section h-100">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="fas fa-video me-2"></i>Video de la Clase
                        </h5>
                        
                        @if($clase->link)
                            @php
                                $embedUrl = $clase->link;
                                $platform = null;
                                $platformIcon = null;
                                if (strpos($clase->link, 'youtube.com/watch?v=') !== false) {
                                    $embedUrl = str_replace('watch?v=', 'embed/', $clase->link);
                                    $embedUrl = explode('&', $embedUrl)[0];
                                    $platform = 'YouTube';
                                    $platformIcon = 'fab fa-youtube';
                                } elseif (strpos($clase->link, 'youtu.be/') !== false) {
                                    $videoId = explode('youtu.be/', $clase->link)[1];
                                    $embedUrl = "https://www.youtube.com/embed/{$videoId}";
                                    $platform = 'YouTube';
                                    $platformIcon = 'fab fa-youtube';
                                } elseif (strpos($clase->link, 'vimeo.com/') !== false) {
                                    $videoId = explode('vimeo.com/', $clase->link)[1];
                                    $embedUrl = "https://player.vimeo.com/video/{$videoId}";
                                    $platform = 'Vimeo';
                                    $platformIcon = 'fab fa-vimeo-v';
                                } else {
                                    $platform = 'Externo';
                                    $platformIcon = 'fas fa-play-circle';
                                }
                            @endphp
                            <div class="video-wrapper mb-3">
                                <iframe src="{{ $embedUrl }}" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <div class="video-info">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <span class="badge-platform">
                                        <i class="{{ $platformIcon }} me-1"></i> {{ $platform }}
                                    </span>
                                    <button onclick="copiarEnlace()" class="btn-copy-video">
                                        <i class="fas fa-copy me-1"></i> Copiar enlace
                                    </button>
                                </div>
                                <div class="video-link-text mt-2">
                                    <i class="fas fa-link text-muted"></i>
                                    <span class="ms-2 text-muted small">{{ Str::limit($clase->link, 60) }}</span>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="empty-state-icon mx-auto mb-3">
                                    <i class="fas fa-video-slash fa-3x text-muted"></i>
                                </div>
                                <p class="text-muted mb-2">No hay video disponible para esta clase</p>
                                <a href="{{ route('admin.clases.edit', $clase->id) }}" class="btn btn-sm btn-primary rounded-pill">
                                    <i class="fas fa-plus me-1"></i>Agregar video
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Información General -->
                <div class="col-lg-5">
                    <div class="info-section h-100">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="fas fa-info-circle me-2"></i>Información General
                        </h5>
                        <div class="info-grid">
                            <div class="info-item">
                                <label><i class="fas fa-hashtag me-1"></i>ID de clase:</label>
                                <span>#{{ $clase->id }}</span>
                            </div>
                            <div class="info-item">
                                <label><i class="fas fa-sort-numeric-up me-1"></i>Número de clase:</label>
                                <span>{{ $clase->num_clase }}</span>
                            </div>
                            <div class="info-item">
                                <label><i class="fas fa-book me-1"></i>Materia:</label>
                                <span>{{ $clase->asignatura->nombre ?? 'No asignada' }}</span>
                            </div>
                            <div class="info-item">
                                <label><i class="fas fa-calendar-plus me-1"></i>Fecha creación:</label>
                                <span>{{ $clase->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="info-item">
                                <label><i class="fas fa-edit me-1"></i>Última actualización:</label>
                                <span>{{ $clase->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Material de Apoyo - Ahora fuera de la columna derecha -->
            @if($clase->url)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="info-section">
                        <h5 class="fw-bold text-info mb-3">
                            <i class="fas fa-file-alt me-2"></i>Material de Apoyo
                        </h5>
                        <div class="material-content">
                            <div class="material-link">
                                <i class="fas fa-link"></i>
                                <span class="flex-grow-1">{{ Str::limit($clase->url, 60) }}</span>
                                <button onclick="copiarMaterial()" class="btn-copy-sm" title="Copiar enlace">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <a href="{{ $clase->url }}" target="_blank" class="btn-open-material">
                                <i class="fas fa-external-link-alt me-2"></i>Abrir material
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recursos Adicionales -->
            @if($clase->recursos && $clase->recursos->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="info-section">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-success mb-0">
                                <i class="fas fa-paperclip me-2"></i>Recursos Adicionales
                            </h5>
                            <span class="recursos-count-badge">{{ $clase->recursos->count() }} archivos</span>
                        </div>
                        
                        <div class="resources-grid">
                            @foreach($clase->recursos as $recurso)
                            @php
                                $config = [
                                    'pdf' => ['icon' => 'fa-file-pdf', 'color' => '#ef4444', 'bg' => '#fee2e2', 'name' => 'PDF'],
                                    'video_youtube' => ['icon' => 'fa-youtube', 'color' => '#ff0000', 'bg' => '#ffe5e5', 'name' => 'YouTube'],
                                    'video_vimeo' => ['icon' => 'fa-vimeo-v', 'color' => '#1ab7ea', 'bg' => '#e5f4fb', 'name' => 'Vimeo'],
                                    'video_drive' => ['icon' => 'fa-google-drive', 'color' => '#0f9d58', 'bg' => '#e5f5ec', 'name' => 'Google Drive'],
                                    'presentacion' => ['icon' => 'fa-chalkboard', 'color' => '#f59e0b', 'bg' => '#fef3c7', 'name' => 'Presentación'],
                                    'documento' => ['icon' => 'fa-file-word', 'color' => '#3b82f6', 'bg' => '#e5f0ff', 'name' => 'Documento'],
                                    'podcast' => ['icon' => 'fa-podcast', 'color' => '#8b5cf6', 'bg' => '#ede9fe', 'name' => 'Podcast'],
                                    'imagen' => ['icon' => 'fa-image', 'color' => '#10b981', 'bg' => '#e5f9f0', 'name' => 'Imagen'],
                                    'enlace' => ['icon' => 'fa-link', 'color' => '#6366f1', 'bg' => '#e5e5ff', 'name' => 'Enlace'],
                                    'otros' => ['icon' => 'fa-file', 'color' => '#6b7280', 'bg' => '#f3f4f6', 'name' => 'Otro']
                                ];
                                $tipo = $config[$recurso->tipo] ?? $config['otros'];
                            @endphp
                            <div class="resource-card">
                                <div class="resource-icon" style="background: {{ $tipo['bg'] }}; color: {{ $tipo['color'] }};">
                                    <i class="fab {{ $tipo['icon'] }}"></i>
                                </div>
                                <div class="resource-info">
                                    <h4 class="resource-title">{{ $recurso->titulo }}</h4>
                                    <span class="resource-type" style="color: {{ $tipo['color'] }};">{{ $tipo['name'] }}</span>
                                    @if($recurso->descripcion)
                                        <p class="resource-description">{{ Str::limit($recurso->descripcion, 80) }}</p>
                                    @endif
                                    <div class="resource-links">
                                        <button onclick="copiarRecurso('{{ $recurso->url }}')" class="resource-btn copy" title="Copiar enlace">
                                            <i class="fas fa-copy"></i> Copiar
                                        </button>
                                        <a href="{{ $recurso->url }}" target="_blank" class="resource-btn open" title="Abrir recurso">
                                            <i class="fas fa-external-link-alt"></i> Abrir
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Botones de Acción -->
            <div class="d-flex justify-content-center gap-3 mt-5 pt-4 border-top">
                <a href="{{ route('admin.clases.edit', $clase->id) }}" class="btn btn-warning px-5 py-3 rounded-pill">
                    <i class="fas fa-edit me-2"></i> Editar Clase
                </a>
                <a href="{{ route('admin.clases.index') }}" class="btn btn-secondary px-5 py-3 rounded-pill">
                    <i class="fas fa-arrow-left me-2"></i> Volver al listado
                </a>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="videoLinkValue" value="{{ $clase->link }}">
<input type="hidden" id="materialLinkValue" value="{{ $clase->url }}">
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
    
    /* Badges */
    .badge-clase {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 6px 16px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 4px rgba(102,126,234,0.3);
    }
    
    .badge-recursos {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 6px 16px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 4px rgba(16,185,129,0.3);
    }
    
    .badge-video {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        padding: 6px 16px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 4px rgba(59,130,246,0.3);
    }
    
    .badge-sin-video {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        padding: 6px 16px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 4px rgba(239,68,68,0.3);
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
    
    /* Info Section */
    .info-section {
        background: #f8fafc;
        border-radius: 20px;
        padding: 1.5rem;
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
    }
    
    .info-item span {
        color: #1e293b;
        font-weight: 500;
    }
    
    /* Video */
    .video-wrapper {
        position: relative;
        aspect-ratio: 16 / 9;
        background: #000;
        border-radius: 16px;
        overflow: hidden;
    }
    
    .video-wrapper iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    
    .badge-platform {
        background: #1e293b;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    
    .btn-copy-video {
        background: #f1f5f9;
        border: none;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .btn-copy-video:hover {
        background: #667eea;
        color: white;
    }
    
    .video-link-text {
        background: #f8fafc;
        padding: 8px 12px;
        border-radius: 12px;
        font-family: monospace;
        font-size: 0.7rem;
        word-break: break-all;
    }
    
    /* Material */
    .material-content {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .material-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: white;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }
    
    .material-link i {
        color: #94a3b8;
    }
    
    .material-link span {
        font-size: 0.85rem;
        color: #475569;
        font-family: monospace;
        word-break: break-all;
    }
    
    .btn-copy-sm {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.25rem 0.5rem;
        border-radius: 8px;
        color: #94a3b8;
        transition: all 0.2s ease;
    }
    
    .btn-copy-sm:hover {
        background: #e2e8f0;
        color: #667eea;
    }
    
    .btn-open-material {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 0.6rem;
        border-radius: 12px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s ease;
        width: 100%;
    }
    
    .btn-open-material:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102,126,234,0.3);
        color: white;
    }
    
    /* Recursos Grid */
    .resources-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1rem;
        margin-top: 0.5rem;
    }
    
    .recursos-count-badge {
        background: #e2e8f0;
        color: #475569;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .resource-card {
        background: white;
        border-radius: 16px;
        padding: 1rem;
        display: flex;
        gap: 1rem;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    
    .resource-card:hover {
        transform: translateX(5px);
        border-color: #667eea;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    
    .resource-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.3rem;
    }
    
    .resource-info {
        flex: 1;
    }
    
    .resource-title {
        font-size: 0.9rem;
        font-weight: 700;
        margin: 0 0 0.25rem 0;
        color: #1e293b;
    }
    
    .resource-type {
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .resource-description {
        font-size: 0.7rem;
        color: #64748b;
        margin: 0.5rem 0 0 0;
        line-height: 1.4;
    }
    
    .resource-links {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }
    
    .resource-btn {
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .resource-btn.copy {
        background: #f1f5f9;
        color: #475569;
    }
    
    .resource-btn.copy:hover {
        background: #667eea;
        color: white;
    }
    
    .resource-btn.open {
        background: #10b981;
        color: white;
    }
    
    .resource-btn.open:hover {
        background: #059669;
        transform: translateY(-1px);
    }
    
    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
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
    
    body.dark-mode .stats-number {
        color: #f1f5f9;
    }
    
    body.dark-mode .resource-card {
        background: #0f172a;
        border-color: #334155;
    }
    
    body.dark-mode .resource-card:hover {
        background: #1e293b;
    }
    
    body.dark-mode .resource-title {
        color: #f1f5f9;
    }
    
    body.dark-mode .material-link {
        background: #0f172a;
        border-color: #334155;
    }
    
    body.dark-mode .video-link-text {
        background: #0f172a;
    }
    
    body.dark-mode .recursos-count-badge {
        background: #334155;
        color: #cbd5e1;
    }
    
    body.dark-mode .resource-btn.copy {
        background: #1e293b;
        color: #cbd5e1;
    }
    
    body.dark-mode .btn-copy-video {
        background: #1e293b;
        color: #cbd5e1;
    }
    
    body.dark-mode .btn-copy-video:hover {
        background: #667eea;
        color: white;
    }
    
    @media (max-width: 768px) {
        .resources-grid {
            grid-template-columns: 1fr;
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
            font-size: 0.75rem;
        }
        
        .info-item span {
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function copiarEnlace() {
        const url = document.getElementById('videoLinkValue')?.value;
        if (!url) return;
        navigator.clipboard.writeText(url).then(() => {
            Swal.fire({ icon: 'success', title: 'Copiado', text: 'Enlace del video copiado', timer: 1500, showConfirmButton: false });
        });
    }
    
    function copiarMaterial() {
        const url = document.getElementById('materialLinkValue')?.value;
        if (!url) return;
        navigator.clipboard.writeText(url).then(() => {
            Swal.fire({ icon: 'success', title: 'Copiado', text: 'Enlace del material copiado', timer: 1500, showConfirmButton: false });
        });
    }
    
    function copiarRecurso(url) {
        navigator.clipboard.writeText(url).then(() => {
            Swal.fire({ icon: 'success', title: 'Copiado', text: 'Enlace del recurso copiado', timer: 1000, showConfirmButton: false, toast: true, position: 'top-end' });
        });
    }
</script>
@endpush