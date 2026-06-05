@forelse($asignaturas as $asignatura)
<tr class="fade-in">
    <td class="fw-bold" style="color: #667eea; font-size: 0.95rem;">
        #{{ $asignatura->id }}
    </td>
    <td>
        <div class="d-flex align-items-center gap-3">
            <div class="materia-icon">
                <i class="fas fa-book"></i>
            </div>
            <div>
                <span class="materia-nombre">{{ $asignatura->nombre }}</span>
            </div>
        </div>
    <td class="text-center">
    <div class="d-flex justify-content-center">
        @if($asignatura->total_clases > 0)
        <span class="badge-count badge-video">
            <i class="fas fa-video me-1"></i> {{ $asignatura->total_clases }}
        </span>
        @else
        <span class="badge-count badge-empty">
            <i class="fas fa-video me-1"></i> 0
        </span>
        @endif
    </div>
    </td>
    <td class="text-center">
        <div class="d-flex justify-content-center">
            @if($asignatura->total_carreras > 0)
            <span class="badge-count badge-career">
                <i class="fas fa-graduation-cap me-1"></i> {{ $asignatura->total_carreras }}
            </span>
            @else
            <span class="badge-count badge-empty">
                <i class="fas fa-graduation-cap me-1"></i> 0
            </span>
            @endif
        </div>
    </td>
    <td class="text-center">
        <div class="d-flex justify-content-center gap-2">
            <a href="{{ route('admin.asignaturas.edit', $asignatura) }}" class="btn-action btn-edit"
                data-tooltip="Editar materia">
                <i class="fas fa-edit"></i>
                <span class="d-none d-md-inline">Editar</span>
            </a>
            <button type="button"
                onclick="deleteMateria({{ $asignatura->id }}, '{{ addslashes($asignatura->nombre) }}')"
                class="btn-action btn-delete" data-tooltip="Eliminar materia">
                <i class="fas fa-trash-alt"></i>
                <span class="d-none d-md-inline">Eliminar</span>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center py-5">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <h5 class="empty-state-title">No hay materias registradas</h5>
            <p class="empty-state-text">Comienza creando tu primera materia en el sistema</p>
            <a href="{{ route('admin.asignaturas.create') }}" class="btn btn-primary-custom px-4 py-2 mt-2">
                <i class="fas fa-plus-circle me-2"></i>Crear primera materia
            </a>
        </div>
    </td>
</tr>
@endforelse