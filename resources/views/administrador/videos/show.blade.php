@extends('administrador.layouts.master')

@section('title', 'Detalles del Video - SAINS')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="display-5 fw-bold"
                    style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                    <i class="fas fa-video me-3"></i>Detalles del Video
                </h1>
                <p class="text-muted">Información completa del video educativo</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.videos.edit', $video->id) }}" class="btn btn-warning rounded-pill px-4">
                    <i class="fas fa-edit me-2"></i>Editar
                </a>
                <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i>Volver al listado
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
                    <i class="fas fa-video fa-4x"></i>
                </div>
                <div class="text-center text-md-start">
                    <h2 class="mb-1 fw-bold">{{ $video->titulo }}</h2>
                    <p class="text-muted mb-2">
                        <i class="fas fa-book me-1"></i> {{ $video->materia }} |
                        <i class="fas fa-tag me-1"></i> {{ $video->tema }}
                    </p>
                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                        @if($video->plan)
                        <span class="badge-video-gratuito"><i class="fas fa-gift me-1"></i> Plan Gratuito</span>
                        @else
                        <span class="badge-video-premium"><i class="fas fa-crown me-1"></i> Plan Premium</span>
                        @endif
                        @if($video->duracion)
                        <span class="badge-duracion"><i class="fas fa-hourglass-half me-1"></i>
                            {{ $video->duracion }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Dashboard de estadísticas - Tarjetas de resumen -->
            <div class="row g-3 mb-5">
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: rgba(239,68,68,0.1);">
                            <i class="fas fa-eye" style="color: #ef4444;"></i>
                        </div>
                        <div class="stats-info">
                            <h3 class="stats-number">{{ number_format($video->progresos()->count()) }}</h3>
                            <p class="stats-label">Visualizaciones</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: rgba(59,130,246,0.1);">
                            <i class="fas fa-hourglass-half" style="color: #3b82f6;"></i>
                        </div>
                        <div class="stats-info">
                            <h3 class="stats-number">{{ $video->duracion ?? 'N/A' }}</h3>
                            <p class="stats-label">Duración</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: rgba(16,185,129,0.1);">
                            <i class="fas fa-chart-line" style="color: #10b981;"></i>
                        </div>
                        <div class="stats-info">
                            <h3 class="stats-number">{{ $porcentajeCompletado ?? 0 }}%</h3>
                            <p class="stats-label">Tasa de completado</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 1: INFORMACIÓN DEL VIDEO -->
            <div class="row g-4 mb-4">
                <!-- Reproductor de Video -->
                <div class="col-md-6">
                    <div class="info-section h-100">
                        <h5 class="fw-bold text-danger mb-3">
                            <i class="fas fa-play-circle me-2"></i>Reproductor
                        </h5>
                        <div class="ratio ratio-16x9 mb-3">
                            @php
                            $embedUrl = $video->link;
                            if (strpos($video->link, 'youtube.com/watch?v=') !== false) {
                            $embedUrl = str_replace('watch?v=', 'embed/', $video->link);
                            $embedUrl = explode('&', $embedUrl)[0];
                            } elseif (strpos($video->link, 'youtu.be/') !== false) {
                            $videoId = explode('youtu.be/', $video->link)[1];
                            $embedUrl = "https://www.youtube.com/embed/{$videoId}";
                            } elseif (strpos($video->link, 'vimeo.com/') !== false) {
                            $videoId = explode('vimeo.com/', $video->link)[1];
                            $embedUrl = "https://player.vimeo.com/video/{$videoId}";
                            }
                            @endphp
                            <iframe src="{{ $embedUrl }}" frameborder="0" allowfullscreen class="rounded-3"
                                style="background: #000;"></iframe>
                        </div>
                        <a href="{{ $video->link }}" target="_blank" class="btn btn-outline-danger w-100 rounded-pill">
                            <i class="fab fa-youtube me-2"></i>Ver en YouTube
                        </a>
                    </div>
                </div>

                <!-- Información Detallada -->
                <div class="col-md-6">
                    <div class="info-section h-100">
                        <h5 class="fw-bold text-danger mb-3">
                            <i class="fas fa-info-circle me-2"></i>Información del Video
                        </h5>
                        <div class="info-grid">
                            <div class="info-item">
                                <label><i class="fas fa-heading me-2 text-danger"></i>Título:</label>
                                <span>{{ $video->titulo }}</span>
                            </div>
                            <div class="info-item">
                                <label><i class="fas fa-tag me-2 text-danger"></i>Tema:</label>
                                <span>{{ $video->tema }}</span>
                            </div>
                            <div class="info-item">
                                <label><i class="fas fa-book me-2 text-danger"></i>Materia:</label>
                                <span>{{ $video->materia }}</span>
                            </div>
                            <div class="info-item">
                                <label><i class="fas fa-hourglass-half me-2 text-danger"></i>Duración:</label>
                                <span>{{ $video->duracion ?? 'No especificada' }}</span>
                            </div>
                            <div class="info-item">
                                <label><i class="fas fa-crown me-2 text-danger"></i>Plan:</label>
                                <span>{{ $video->plan ? 'Gratuito' : 'Premium' }}</span>
                            </div>
                            <div class="info-item">
                                <label><i class="fas fa-calendar-alt me-2 text-danger"></i>Fecha de creación:</label>
                                <span>{{ \Carbon\Carbon::parse($video->created_at)->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="info-item">
                                <label><i class="fas fa-clock me-2 text-danger"></i>Última actualización:</label>
                                <span>{{ \Carbon\Carbon::parse($video->updated_at)->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: ENLACE DEL VIDEO -->
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="info-section">
                        <h5 class="fw-bold text-danger mb-3">
                            <i class="fas fa-link me-2"></i>Enlace del Video
                        </h5>
                        <div class="input-group">
                            <input type="text" id="videoLink" class="form-control bg-light" value="{{ $video->link }}"
                                readonly style="font-family: monospace;">
                            <button onclick="copiarEnlace()" class="btn btn-danger" type="button">
                                <i class="fas fa-copy me-2"></i>Copiar enlace
                            </button>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="fas fa-info-circle me-1"></i>Haz clic en copiar para compartir este enlace
                        </small>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 3: ESTADÍSTICAS DE VISUALIZACIÓN MEJORADA -->
            <div class="row g-4">
                <div class="col-12">
                    <div class="info-section">
                        <h5 class="fw-bold text-danger mb-4">
                            <i class="fas fa-chart-line me-2"></i>Estadísticas de Visualización
                        </h5>

                        <div class="row g-4">
                            <!-- Tarjeta de Visualizaciones Totales -->
                            <div class="col-md-6 col-lg-4">
                                <div class="visualizacion-card text-center">
                                    <div class="visualizacion-icon-circle bg-danger bg-opacity-10">
                                        <i class="fas fa-eye fa-2x text-danger"></i>
                                    </div>
                                    <h4 class="mt-3 mb-1 fw-bold">{{ number_format($video->progresos()->count()) }}</h4>
                                    <p class="text-muted small mb-0">Visualizaciones totales</p>
                                    <div class="mt-2">
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-chart-simple me-1"></i>
                                            {{ $video->progresos()->count() > 0 ? 'Activo' : 'Sin actividad' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Tarjeta de Tasa de Completado -->
                            <div class="col-md-6 col-lg-4">
                                <div class="visualizacion-card text-center">
                                    <div class="visualizacion-icon-circle bg-success bg-opacity-10">
                                        <i class="fas fa-check-circle fa-2x text-success"></i>
                                    </div>
                                    <h4 class="mt-3 mb-1 fw-bold">{{ $porcentajeCompletado ?? 0 }}%</h4>
                                    <p class="text-muted small mb-0">Tasa de completado</p>
                                    <div class="mt-2">
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success"
                                                style="width: {{ $porcentajeCompletado ?? 0 }}%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tarjeta de Progreso Promedio -->
                            <div class="col-md-6 col-lg-4">
                                <div class="visualizacion-card text-center">
                                    <div class="visualizacion-icon-circle bg-info bg-opacity-10">
                                        <i class="fas fa-chart-line fa-2x text-info"></i>
                                    </div>
                                    @php
                                    $promedioProgreso = $video->progresos()->avg('ultimo_segundo');
                                    $promedioPorcentaje = $video->duracion ? round(($promedioProgreso / max(1,
                                    $video->duracionSegundos)) * 100) : 0;
                                    @endphp
                                    <h4 class="mt-3 mb-1 fw-bold">{{ $promedioPorcentaje }}%</h4>
                                    <p class="text-muted small mb-0">Progreso promedio</p>
                                    <div class="mt-2">
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-percent me-1"></i>
                                            Nivel medio
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resumen adicional de estadísticas -->
                        <div class="mt-4 pt-3 border-top">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                                        <i class="fas fa-users fa-2x text-primary"></i>
                                        <div>
                                            <small class="text-muted d-block">Estudiantes únicos</small>
                                            <strong
                                                class="fs-5">{{ $video->progresos()->distinct('estudiante_id')->count('estudiante_id') }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                                        <i class="fas fa-calendar-week fa-2x text-success"></i>
                                        <div>
                                            <small class="text-muted d-block">Última visualización</small>
                                            <strong class="fs-6">
                                                @php
                                                $ultimaVista = $video->progresos()->max('fecha_visto');
                                                @endphp
                                                {{ $ultimaVista ? \Carbon\Carbon::parse($ultimaVista)->diffForHumans() : 'Ninguna' }}
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="d-flex justify-content-center gap-3 mt-5 pt-4 border-top">
                <a href="{{ route('admin.videos.edit', $video->id) }}" class="btn btn-warning px-5 py-3 rounded-pill">
                    <i class="fas fa-edit me-2"></i> Editar Video
                </a>
                <button type="button"
                    onclick="confirmarEliminarVideo('{{ route('admin.videos.destroy', $video->id) }}', '{{ addslashes($video->titulo) }}')"
                    class="btn btn-danger px-5 py-3 rounded-pill">
                    <i class="fas fa-trash-alt me-2"></i> Eliminar Video
                </button>
                <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary px-5 py-3 rounded-pill">
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
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(239, 68, 68, 0.15);
}

.avatar-perfil {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #ef444420, #dc262620);
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ef4444;
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
    width: 100%;
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
    min-width: 140px;
    flex-shrink: 0;
}

.info-item span {
    color: #1e293b;
    font-weight: 500;
    text-align: right;
    flex: 1;
}

/* Badges de Plan - solo para el header */
.badge-video-gratuito {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 6px 16px;
    border-radius: 25px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
}

.badge-video-premium {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    padding: 6px 16px;
    border-radius: 25px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);
}

.badge-duracion {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    padding: 6px 16px;
    border-radius: 25px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
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
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
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

/* Visualización Cards mejoradas */
.visualizacion-card {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    height: 100%;
}

.visualizacion-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.visualizacion-icon-circle {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    transition: transform 0.3s ease;
}

.visualizacion-card:hover .visualizacion-icon-circle {
    transform: scale(1.1);
}

/* Ratio */
.ratio {
    border-radius: 16px;
    overflow: hidden;
}

/* Progress */
.progress {
    border-radius: 10px;
    background-color: #e2e8f0;
}

.progress-bar {
    border-radius: 10px;
    transition: width 0.5s ease;
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

body.dark-mode .visualizacion-card {
    background: #0f172a;
}

body.dark-mode .bg-light {
    background-color: #0f172a !important;
}

body.dark-mode .text-muted {
    color: #94a3b8 !important;
}

body.dark-mode .border-top {
    border-top-color: #334155 !important;
}

body.dark-mode .border-bottom {
    border-bottom-color: #334155 !important;
}

body.dark-mode .form-control.bg-light {
    background-color: #0f172a !important;
    border-color: #334155;
    color: #f1f5f9;
}

body.dark-mode .badge.bg-light {
    background-color: #334155 !important;
    color: #f1f5f9 !important;
}

/* Responsive */
@media (max-width: 768px) {
    .stats-number {
        font-size: 1rem;
    }

    .stats-icon {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }

    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .info-item label {
        min-width: auto;
    }

    .info-item span {
        text-align: left;
    }

    .visualizacion-icon-circle {
        width: 50px;
        height: 50px;
    }

    .visualizacion-icon-circle i {
        font-size: 1.5rem !important;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function copiarEnlace() {
    const enlaceInput = document.getElementById('videoLink');
    enlaceInput.select();
    enlaceInput.setSelectionRange(0, 99999);

    navigator.clipboard.writeText(enlaceInput.value).then(() => {
        Swal.fire({
            icon: 'success',
            title: '¡Copiado!',
            text: 'El enlace ha sido copiado al portapapeles',
            timer: 2000,
            showConfirmButton: false,
            background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#f1f5f9' : '#1e293b'
        });
    }).catch(() => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo copiar el enlace',
            background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#f1f5f9' : '#1e293b'
        });
    });
}

function confirmarEliminarVideo(url, titulo) {
    Swal.fire({
        title: '¿Eliminar video?',
        html: `
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-video fa-3x" style="color: #dc3545;"></i>
                    </div>
                    <p class="mb-2">Estás a punto de eliminar el video:</p>
                    <strong class="fs-4" style="background: linear-gradient(135deg, #ef4444, #dc2626); -webkit-background-clip: text; background-clip: text; color: transparent;">${escapeHtml(titulo)}</strong>
                    <div class="alert alert-danger mt-3" style="border-radius: 12px;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Esta acción es irreversible y eliminará todos los datos asociados.
                    </div>
                </div>
            `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-trash-alt me-2"></i>Sí, eliminar',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar',
        background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
        color: document.body.classList.contains('dark-mode') ? '#f1f5f9' : '#1e293b',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Eliminando...',
                text: 'Por favor espere',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
            form.style.display = 'none';
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

@if(session('success'))
Swal.fire({
    title: 'Éxito',
    text: '{{ session('
    success ') }}',
    icon: 'success',
    confirmButtonColor: '#ef4444',
    timer: 3000
});
@endif

@if(session('error'))
Swal.fire({
    title: 'Error',
    text: '{{ session('
    error ') }}',
    icon: 'error',
    confirmButtonColor: '#dc3545'
});
@endif
</script>
@endpush