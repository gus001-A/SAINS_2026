@forelse($videos as $video)
<tr class="fade-in">
    <td class="fw-bold" style="color: #667eea; font-size: 0.95rem;">
        #{{ $video->id }}
    </td>
    <td>
        <div class="video-info">
            <div class="video-icon">
                <i class="fas fa-play"></i>
            </div>
            <div class="video-detalles">
                <span class="video-titulo">{{ Str::limit($video->titulo, 50) }}</span>
                <span class="video-tema">{{ Str::limit($video->tema, 40) }}</span>
            </div>
        </div>
    </td>
    <td>
        <span class="badge-materia">
            <i class="fas fa-book me-1"></i> {{ Str::limit($video->materia, 35) }}
        </span>
    </td>
    <td class="text-center">
        @if($video->duracion)
            <span class="badge-count badge-duration">
                <i class="fas fa-clock me-1"></i> {{ $video->duracion }}
            </span>
        @else
            <span class="badge-count badge-empty">
                <i class="fas fa-ban me-1"></i> Sin duración
            </span>
        @endif
    </td>
    <td class="text-center">
        @if($video->plan)
            <span class="badge-count badge-gratuito">
                <i class="fas fa-gratipay me-1"></i> Gratuito
            </span>
        @else
            <span class="badge-count badge-premium">
                <i class="fas fa-crown me-1"></i> Premium
            </span>
        @endif
    </td>
    <td class="text-center">
        <div class="d-flex justify-content-center gap-2">
            <a href="{{ route('admin.videos.show', $video->id) }}" 
               class="btn-action btn-view"
               data-tooltip="Ver detalles">
                <i class="fas fa-eye"></i>
                <span class="d-none d-md-inline">Ver</span>
            </a>
            <a href="{{ route('admin.videos.edit', $video->id) }}" 
               class="btn-action btn-edit"
               data-tooltip="Editar video">
                <i class="fas fa-edit"></i>
                <span class="d-none d-md-inline">Editar</span>
            </a>
            <button type="button" 
                    onclick="confirmarEliminar('{{ route('admin.videos.destroy', $video->id) }}', '{{ addslashes($video->titulo) }}')" 
                    class="btn-action btn-delete"
                    data-tooltip="Eliminar video">
                <i class="fas fa-trash-alt"></i>
                <span class="d-none d-md-inline">Eliminar</span>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center py-5">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-video-slash"></i>
            </div>
            <h5 class="empty-state-title">No hay videos registrados</h5>
            <p class="empty-state-text">Comienza creando tu primer video educativo</p>
            <a href="{{ route('admin.videos.create') }}" class="btn btn-primary-custom px-4 py-2 mt-2">
                <i class="fas fa-plus-circle me-2"></i>Crear primer video
            </a>
        </div>
    </td>
</tr>
@endforelse