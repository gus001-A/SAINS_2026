@extends('administrador.layouts.master')

@section('title', 'Detalles del Video - SAINS')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="display-5 fw-bold" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                    <i class="fas fa-video me-3"></i>Detalles del Video
                </h1>
                <p class="text-muted">Información completa del video educativo</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.videos.edit', $video->id) }}" class="btn btn-warning btn-lg rounded-pill px-4">
                    <i class="fas fa-edit me-2"></i>Editar Video
                </a>
                <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-danger btn-lg rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i>Volver al listado
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Columna Izquierda: Video -->
    <div class="col-lg-7">
        <div class="card-modern p-0 overflow-hidden sticky-top" style="top: 80px;">
            <div class="ratio ratio-16x9">
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
                <iframe src="{{ $embedUrl }}" 
                        frameborder="0" 
                        allowfullscreen
                        class="w-100 h-100"
                        style="background: #000;"></iframe>
            </div>
            
            <!-- Badge de Plan debajo del video -->
            <div class="p-3 bg-white border-top">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex gap-2">
                        <span class="badge {{ $video->plan ? 'badge-gratuito' : 'badge-premium' }} px-3 py-2 rounded-pill">
                            <i class="fas {{ $video->plan ? 'fa-gratipay' : 'fa-crown' }} me-1"></i>
                            {{ $video->plan ? 'Plan Gratuito' : 'Plan Premium' }}
                        </span>
                        @if($video->duracion)
                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                <i class="fas fa-clock me-1"></i> {{ $video->duracion }}
                            </span>
                        @endif
                    </div>
                    <a href="{{ $video->link }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill">
                        <i class="fab fa-youtube me-1"></i>Ver en YouTube
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Columna Derecha: Información del Video -->
    <div class="col-lg-5">
        <div class="card-modern p-4">
            <!-- Título -->
            <div class="mb-4">
                <h2 class="fw-bold mb-3">{{ $video->titulo }}</h2>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                        <i class="fas fa-book me-1"></i> {{ $video->materia }}
                    </span>
                </div>
            </div>

            <!-- Información Detallada -->
            <div class="mb-4">
                <div class="info-card p-3 rounded-3 mb-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-circle p-2" style="background: rgba(239,68,68,0.1);">
                            <i class="fas fa-tag text-danger"></i>
                        </div>
                        <h6 class="fw-semibold mb-0">Tema del Video</h6>
                    </div>
                    <p class="text-muted mb-0 ps-4">{{ $video->tema }}</p>
                </div>

                <div class="info-card p-3 rounded-3 mb-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-circle p-2" style="background: rgba(239,68,68,0.1);">
                            <i class="fas fa-chart-line text-danger"></i>
                        </div>
                        <h6 class="fw-semibold mb-0">Estadísticas de Visualización</h6>
                    </div>
                    <div class="ps-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Usuarios que han visto:</span>
                            <span class="fw-bold text-danger fs-4">{{ $video->progresos()->count() }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-danger" style="width: {{ min(100, $video->progresos()->count() * 2) }}%;" 
                                 role="progressbar"></div>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="fas fa-info-circle me-1"></i>
                            Total de usuarios que han completado este video
                        </small>
                    </div>
                </div>

                <div class="info-card p-3 rounded-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-circle p-2" style="background: rgba(239,68,68,0.1);">
                            <i class="fas fa-link text-danger"></i>
                        </div>
                        <h6 class="fw-semibold mb-0">Enlace del Video</h6>
                    </div>
                    <div class="ps-4">
                        <div class="input-group">
                            <input type="text" id="videoLink" class="form-control form-control-sm bg-light" 
                                   value="{{ $video->link }}" readonly style="font-size: 0.85rem;">
                            <button onclick="copiarEnlace()" class="btn btn-outline-danger btn-sm" type="button">
                                <i class="fas fa-copy me-1"></i>Copiar
                            </button>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="fas fa-info-circle me-1"></i>
                            Haz clic en copiar para compartir este enlace
                        </small>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="d-flex gap-3 pt-3 border-top">
                <a href="{{ route('admin.videos.edit', $video->id) }}" class="btn btn-save px-4 py-2 rounded-pill flex-grow-1">
                    <i class="fas fa-edit me-2"></i>Editar Video
                </a>
                <button type="button" onclick="confirmarEliminar('{{ route('admin.videos.destroy', $video->id) }}', '{{ addslashes($video->titulo) }}')" class="btn btn-cancel px-4 py-2 rounded-pill flex-grow-1">
                    <i class="fas fa-trash-alt me-2"></i>Eliminar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-modern {
        background: white;
        border-radius: 20px;
        transition: transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    
    .card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(239,68,68,0.15);
    }
    
    .info-card {
        background: rgba(239,68,68,0.03);
        transition: all 0.3s ease;
        border: 1px solid rgba(239,68,68,0.1);
    }
    
    .info-card:hover {
        background: rgba(239,68,68,0.06);
        transform: translateX(5px);
    }
    
    .badge-gratuito {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .badge-premium {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    
    .btn-save {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239,68,68,0.4);
        color: white;
    }
    
    .btn-cancel {
        background: #6c757d;
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-cancel:hover {
        background: #dc3545;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
        color: white;
    }
    
    .btn-outline-danger {
        border-radius: 50px;
        transition: all 0.3s ease;
    }
    
    .btn-outline-danger:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
    }
    
    .ratio {
        border-radius: 20px 20px 0 0;
        overflow: hidden;
    }
    
    .progress {
        border-radius: 10px;
        background-color: rgba(239,68,68,0.1);
    }
    
    .progress-bar {
        border-radius: 10px;
        transition: width 0.5s ease;
    }
    
    .sticky-top {
        z-index: 1;
    }
    
    /* Dark Mode */
    body.dark-mode .card-modern {
        background-color: #1a1a2e;
    }
    
    body.dark-mode .info-card {
        background: rgba(239,68,68,0.08);
        border-color: rgba(239,68,68,0.2);
    }
    
    body.dark-mode .info-card:hover {
        background: rgba(239,68,68,0.12);
    }
    
    body.dark-mode .badge.bg-danger.bg-opacity-10 {
        background: rgba(239,68,68,0.2) !important;
        color: #f87171 !important;
    }
    
    body.dark-mode .badge.bg-secondary.bg-opacity-10 {
        background: rgba(100, 116, 139, 0.2) !important;
        color: #94a3b8 !important;
    }
    
    body.dark-mode .text-muted {
        color: #94a3b8 !important;
    }
    
    body.dark-mode .bg-white {
        background-color: #1a1a2e !important;
    }
    
    body.dark-mode .border-top {
        border-top-color: rgba(255, 255, 255, 0.1) !important;
    }
    
    body.dark-mode .form-control.bg-light {
        background-color: #0f0f1a !important;
        border-color: rgba(239,68,68,0.3);
        color: #e0e0e0;
    }
</style>
@endpush

@push('scripts')
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
                background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#1e293b'
            });
        }).catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo copiar el enlace',
                background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#1e293b'
            });
        });
    }

    function confirmarEliminar(url, titulo) {
        Swal.fire({
            title: '¿Eliminar video?',
            html: `El video <strong class="text-danger">${escapeHtml(titulo)}</strong> será eliminado permanentemente.<br><br>Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-trash-alt me-2"></i>Sí, eliminar',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar',
            background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#1e293b'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                Swal.fire({
                    title: 'Eliminando...',
                    html: 'Procesando solicitud',
                    timer: 1500,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                        form.submit();
                    }
                });
            }
        });
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
@endpush