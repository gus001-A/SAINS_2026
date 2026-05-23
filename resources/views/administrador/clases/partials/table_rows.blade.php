@forelse($clases as $index => $clase)
<tr class="border-bottom hover-row">
    <td class="px-4 py-3">
        <span class="fw-semibold text-muted">{{ $clases->firstItem() + $index }}</span>
    </td>
    <td class="px-4 py-3">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar-materia">
                <i class="fas fa-book-open"></i>
            </div>
            <span class="fw-semibold text-dark">{{ Str::limit($clase->asignatura->nombre ?? 'N/A', 30) }}</span>
        </div>
    </td>
    <td class="px-4 py-3">
        <div class="badge-clase">
            <i class="fas fa-hashtag me-1"></i>
            Clase {{ $clase->num_clase }}
        </div>
    </td>
    <td class="px-4 py-3">
        <div class="d-flex flex-column">
            <span class="fw-semibold text-dark">{{ Str::limit($clase->nombre_clase, 40) }}</span>
        </div>
    </td>
    <td class="px-4 py-3">
        @if($clase->link)
            <a href="{{ $clase->link }}" target="_blank" class="btn-video-link">
                <i class="fab fa-youtube me-1"></i> Ver video
            </a>
        @else
            <span class="badge-empty">
                <i class="fas fa-ban me-1"></i> Sin video
            </span>
        @endif
    </td>
    <td class="px-4 py-3">
        @if($clase->url)
            <a href="{{ $clase->url }}" target="_blank" class="btn-material-link">
                <i class="fas fa-file-alt me-1"></i> Ver material
            </a>
        @else
            <span class="badge-empty">
                <i class="fas fa-ban me-1"></i> Sin material
            </span>
        @endif
    </td>
    <td class="px-4 py-3 text-end">
        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('admin.clases.show', $clase->id) }}" 
               class="btn-icon btn-view" 
               title="Ver detalles">
                <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('admin.clases.edit', $clase->id ) }}" 
               class="btn-icon btn-edit" 
               title="Editar">
                <i class="fas fa-pencil-alt"></i>
            </a>
            <button type="button" 
                    class="btn-icon btn-delete" 
                    onclick="eliminarClase({{ $clase->id }}, '{{ addslashes($clase->nombre_clase) }}')" 
                    title="Eliminar">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center py-5">
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-chalkboard"></i>
            </div>
            <h5 class="empty-state-title">No hay clases registradas</h5>
            <p class="empty-state-text">Comienza creando tu primera clase</p>
            <a href="{{ route('admin.clases.create') }}" class="btn btn-primary mt-3">
                <i class="fas fa-plus me-2"></i>Crear primera clase
            </a>
        </div>
    </td>
</tr>
@endforelse