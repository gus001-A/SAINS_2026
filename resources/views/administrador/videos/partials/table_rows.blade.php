@forelse($videos as $video)
<tr class="fade-in">
    <td class="fw-semibold">
        <span class="text-muted">#</span>{{ $video->id }}
    </td>
    <td>
        <div class="video-info">
            <div class="video-icon">
                <i class="fas fa-play"></i>
            </div>
            <div class="video-detalles">
                <div class="video-titulo">{{ Str::limit($video->titulo, 45) }}</div>
                <div class="video-tema">{{ Str::limit($video->tema, 35) }}</div>
            </div>
        </div>
    </td>
    <td>
        <div class="d-flex flex-column gap-1">
            <span class="badge bg-light text-dark d-inline-flex align-items-center gap-1" style="width: fit-content;">
                <i class="fas fa-book" style="color: #667eea;"></i> {{ Str::limit($video->materia, 35) }}
            </span>
        </div>
    </td>
    <td class="text-center">
        @if($video->duracion)
            <span class="badge-progresos">
                <i class="fas fa-clock me-1"></i> {{ $video->duracion }}
            </span>
        @else
            <span class="badge-sin-recurso">
                <i class="fas fa-ban me-1"></i> Sin duración
            </span>
        @endif
    </td>
    <td class="text-center">
        <span class="badge {{ $video->plan ? 'badge-gratuito' : 'badge-premium' }}">
            <i class="fas {{ $video->plan ? 'fa-gratipay' : 'fa-crown' }} me-1"></i>
            {{ $video->plan ? 'Gratuito' : 'Premium' }}
        </span>
    </td>
    <td class="text-center">
        <div class="d-flex justify-content-center gap-2">
            <a href="{{ route('admin.videos.show', $video->id) }}" class="btn-accion btn-ver" title="Ver detalles">
                <i class="fas fa-eye me-1"></i>Ver
            </a>
            <a href="{{ route('admin.videos.edit', $video->id) }}" class="btn-accion btn-editar" title="Editar video">
                <i class="fas fa-edit me-1"></i>Editar
            </a>
            <button type="button" onclick="confirmarEliminar('{{ route('admin.videos.destroy', $video->id) }}', '{{ addslashes($video->titulo) }}')" class="btn-accion btn-eliminar" title="Eliminar video">
                <i class="fas fa-trash-alt me-1"></i>Eliminar
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center py-5">
        <div class="text-center">
            <div class="mb-3">
                <i class="fas fa-video-slash fa-4x text-muted opacity-25"></i>
            </div>
            <h6 class="text-muted mb-2">No hay videos registrados</h6>
            <p class="text-muted small mb-3">Comienza creando tu primer video educativo</p>
            <a href="{{ route('admin.videos.create') }}" class="btn btn-primary-custom">
                <i class="fas fa-plus me-2"></i>Crear primer video
            </a>
        </div>
    </td>
</tr>
@endforelse