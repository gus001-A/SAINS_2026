@extends('administrador.layouts.master')

@section('title', 'Detalles de la Clase - SAINS')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="display-5 fw-bold"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                    <i class="fas fa-chalkboard me-3"></i>Detalles de la Clase
                </h1>
                <p class="text-muted">Información completa de la clase educativa</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.clases.edit', $clase->id) }}" class="btn btn-warning btn-lg rounded-pill px-4">
                    <i class="fas fa-edit me-2"></i>Editar Clase
                </a>
                <a href="{{ route('admin.clases.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4">
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
            @if($clase->link)
            <div class="ratio ratio-16x9">
                @php
                $embedUrl = $clase->link;
                $platform = null;
                $videoId = null;

                // YouTube
                if (strpos($clase->link, 'youtube.com/watch?v=') !== false) {
                $embedUrl = str_replace('watch?v=', 'embed/', $clase->link);
                $embedUrl = explode('&', $embedUrl)[0];
                $platform = 'youtube';
                } elseif (strpos($clase->link, 'youtu.be/') !== false) {
                $videoId = explode('youtu.be/', $clase->link)[1];
                $embedUrl = "https://www.youtube.com/embed/{$videoId}";
                $platform = 'youtube';
                }
                // Vimeo
                elseif (strpos($clase->link, 'vimeo.com/') !== false) {
                $videoId = explode('vimeo.com/', $clase->link)[1];
                $embedUrl = "https://player.vimeo.com/video/{$videoId}";
                $platform = 'vimeo';
                }
                @endphp
                <iframe src="{{ $embedUrl }}" frameborder="0" allowfullscreen class="w-100 h-100"
                    style="background: #000;"></iframe>
            </div>

            <!-- Badge de plataforma debajo del video -->
            <div class="p-3 bg-white border-top">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex gap-2">
                        <span
                            class="badge {{ $platform == 'youtube' ? 'badge-youtube' : 'badge-vimeo' }} px-3 py-2 rounded-pill">
                            <i class="fab {{ $platform == 'youtube' ? 'fa-youtube' : 'fa-vimeo' }} me-1"></i>
                            {{ $platform == 'youtube' ? 'YouTube' : 'Vimeo' }}
                        </span>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                            <i class="fas fa-hashtag me-1"></i> Clase #{{ $clase->num_clase }}
                        </span>
                    </div>
                    <a href="{{ $clase->link }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                        <i class="fab {{ $platform == 'youtube' ? 'fa-youtube' : 'fa-vimeo' }} me-1"></i>
                        Ver en {{ $platform == 'youtube' ? 'YouTube' : 'Vimeo' }}
                    </a>
                </div>
            </div>
            @else
            <div class="text-center py-5 bg-light rounded-3">
                <div class="empty-state-icon mx-auto mb-3">
                    <i class="fas fa-video-slash fa-3x text-muted"></i>
                </div>
                <h5 class="text-muted">No hay video disponible</h5>
                <p class="text-muted small">Esta clase no tiene un video asociado</p>
                <a href="{{ route('admin.clases.edit', $clase->id) }}" class="btn btn-sm btn-primary mt-2">
                    <i class="fas fa-plus me-1"></i>Agregar video
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Columna Derecha: Información de la Clase -->
    <div class="col-lg-5">
        <div class="card-modern p-4">
            <!-- Título -->
            <div class="mb-4">
                <div class="clase-number-badge-sm mb-3">
                    <i class="fas fa-chalkboard-user"></i>
                </div>
                <h2 class="fw-bold mb-3">{{ $clase->nombre_clase }}</h2>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                        <i class="fas fa-book me-1"></i> {{ $clase->asignatura->nombre ?? 'Sin materia' }}
                    </span>
                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                        <i class="fas fa-sort-numeric-up me-1"></i> Clase {{ $clase->num_clase }}
                    </span>
                </div>
            </div>

            <!-- Información Detallada -->
            <div class="mb-4">
                <div class="info-card p-3 rounded-3 mb-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-circle p-2" style="background: rgba(102,126,234,0.1);">
                            <i class="fas fa-graduation-cap text-primary"></i>
                        </div>
                        <h6 class="fw-semibold mb-0">Materia</h6>
                    </div>
                    <div class="ps-4">
                        <p class="text-muted mb-0">{{ $clase->asignatura->nombre ?? 'No asignada' }}</p>
                        <small class="text-muted">ID de materia: {{ $clase->id_asignatura }}</small>
                    </div>
                </div>

                <div class="info-card p-3 rounded-3 mb-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-circle p-2" style="background: rgba(102,126,234,0.1);">
                            <i class="fas fa-heading text-primary"></i>
                        </div>
                        <h6 class="fw-semibold mb-0">Descripción</h6>
                    </div>
                    <div class="ps-4">
                        <p class="text-muted mb-0">
                            {{ $clase->nombre_clase }}
                        </p>
                        <small class="text-muted">ID de clase: #{{ $clase->id }}</small>
                    </div>
                </div>

                @if($clase->url)
                <div class="info-card p-3 rounded-3 mb-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-circle p-2" style="background: rgba(59,130,246,0.1);">
                            <i class="fas fa-file-alt text-info"></i>
                        </div>
                        <h6 class="fw-semibold mb-0">Material de Apoyo</h6>
                    </div>
                    <div class="ps-4">
                        <div class="input-group">
                            <input type="text" id="materialLink" class="form-control form-control-sm bg-light"
                                value="{{ $clase->url }}" readonly style="font-size: 0.85rem;">
                            <button onclick="copiarMaterial()" class="btn btn-outline-info btn-sm" type="button">
                                <i class="fas fa-copy me-1"></i>Copiar
                            </button>
                        </div>
                        <div class="mt-2">
                            <a href="{{ $clase->url }}" target="_blank"
                                class="btn btn-sm btn-info w-100 mt-2 rounded-pill">
                                <i class="fas fa-external-link-alt me-1"></i>Abrir material
                            </a>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="fas fa-info-circle me-1"></i>
                            Material complementario para esta clase
                        </small>
                    </div>
                </div>
                @endif

                <div class="info-card p-3 rounded-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-circle p-2" style="background: rgba(102,126,234,0.1);">
                            <i class="fas fa-link text-primary"></i>
                        </div>
                        <h6 class="fw-semibold mb-0">Enlace del Video</h6>
                    </div>
                    <div class="ps-4">
                        @if($clase->link)
                        <div class="input-group">
                            <input type="text" id="videoLink" class="form-control form-control-sm bg-light"
                                value="{{ $clase->link }}" readonly style="font-size: 0.85rem;">
                            <button onclick="copiarEnlace()" class="btn btn-outline-primary btn-sm" type="button">
                                <i class="fas fa-copy me-1"></i>Copiar
                            </button>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="fas fa-info-circle me-1"></i>
                            Haz clic en copiar para compartir este enlace
                        </small>
                        @else
                        <p class="text-muted mb-0">No hay video asociado</p>
                        <a href="{{ route('admin.clases.edit', $clase->id) }}"
                            class="btn btn-sm btn-outline-primary mt-2">
                            <i class="fas fa-plus me-1"></i>Agregar video
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="d-flex gap-3 pt-3 border-top">
                <a href="{{ route('admin.clases.edit', $clase->id) }}"
                    class="btn btn-save px-4 py-2 rounded-pill flex-grow-1">
                    <i class="fas fa-edit me-2"></i>Editar Clase
                </a>
                <button type="button"
                    onclick="eliminarClase({{ $clase->id }}, '{{ addslashes($clase->nombre_clase) }}')"
                    class="btn btn-cancel px-4 py-2 rounded-pill flex-grow-1">
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
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.15);
}

.info-card {
    background: rgba(102, 126, 234, 0.03);
    transition: all 0.3s ease;
    border: 1px solid rgba(102, 126, 234, 0.1);
}

.info-card:hover {
    background: rgba(102, 126, 234, 0.06);
    transform: translateX(5px);
}

.clase-number-badge-sm {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.15));
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #667eea;
}

.badge-youtube {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.badge-vimeo {
    background: linear-gradient(135deg, #1ab7ea, #0d8fc5);
    color: white;
}

.badge-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.btn-save {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
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

.btn-outline-primary {
    border-radius: 50px;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: #667eea;
    border-color: #667eea;
    color: white;
    transform: translateY(-2px);
}

.btn-outline-info {
    border-radius: 50px;
    transition: all 0.3s ease;
}

.btn-outline-info:hover {
    transform: translateY(-2px);
}

.btn-info {
    transition: all 0.3s ease;
}

.btn-info:hover {
    transform: translateY(-2px);
}

.ratio {
    border-radius: 20px 20px 0 0;
    overflow: hidden;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    background: rgba(102, 126, 234, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sticky-top {
    z-index: 1;
}

/* Dark Mode */
body.dark-mode .card-modern {
    background-color: #1a1a2e;
}

body.dark-mode .info-card {
    background: rgba(102, 126, 234, 0.08);
    border-color: rgba(102, 126, 234, 0.2);
}

body.dark-mode .info-card:hover {
    background: rgba(102, 126, 234, 0.12);
}

body.dark-mode .badge.bg-primary.bg-opacity-10 {
    background: rgba(102, 126, 234, 0.2) !important;
    color: #a78bfa !important;
}

body.dark-mode .badge.bg-info.bg-opacity-10 {
    background: rgba(59, 130, 246, 0.2) !important;
    color: #60a5fa !important;
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

body.dark-mode .bg-light {
    background-color: #0f0f1a !important;
}

body.dark-mode .form-control.bg-light {
    background-color: #0f0f1a !important;
    border-color: rgba(102, 126, 234, 0.3);
    color: #e0e0e0;
}

body.dark-mode .empty-state-icon {
    background: rgba(102, 126, 234, 0.15);
}

body.dark-mode .empty-state-icon i {
    color: #667eea;
}
</style>
@endpush

@push('scripts')
<script>
function copiarEnlace() {
    const enlaceInput = document.getElementById('videoLink');
    if (!enlaceInput) return;

    enlaceInput.select();
    enlaceInput.setSelectionRange(0, 99999);

    navigator.clipboard.writeText(enlaceInput.value).then(() => {
        Swal.fire({
            icon: 'success',
            title: '¡Copiado!',
            text: 'El enlace del video ha sido copiado al portapapeles',
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

function copiarMaterial() {
    const materialInput = document.getElementById('materialLink');
    if (!materialInput) return;

    materialInput.select();
    materialInput.setSelectionRange(0, 99999);

    navigator.clipboard.writeText(materialInput.value).then(() => {
        Swal.fire({
            icon: 'success',
            title: '¡Copiado!',
            text: 'El enlace del material ha sido copiado al portapapeles',
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

function eliminarClase(id, nombre) {
    Swal.fire({
        title: '¿Eliminar clase?',
        html: `La clase <strong class="text-danger">${escapeHtml(nombre)}</strong> será eliminada permanentemente.<br><br>Esta acción no se puede deshacer.`,
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
            fetch(`/administrador/clases/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminada!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false,
                            background: document.body.classList.contains('dark-mode') ? '#1a1a2e' :
                                '#fff',
                            color: document.body.classList.contains('dark-mode') ? '#e0e0e0' :
                                '#1e293b'
                        });
                        setTimeout(() => {
                            window.location.href = "{{ route('admin.clases.index') }}";
                        }, 2000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            background: document.body.classList.contains('dark-mode') ? '#1a1a2e' :
                                '#fff',
                            color: document.body.classList.contains('dark-mode') ? '#e0e0e0' :
                                '#1e293b'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al eliminar la clase',
                        background: document.body.classList.contains('dark-mode') ? '#1a1a2e' :
                            '#fff',
                        color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#1e293b'
                    });
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