@forelse($examenes as $examen)
<tr>
    <td class="px-4">
        <span class="fw-semibold">#{{ $examen->id }}</span>
    </td>
    <td class="px-4">
        @php
            $tipoClasses = [
                'Materia' => 'badge-materia',
                'General del curso' => 'badge-general',
                'Simulación' => 'badge-simulacion'
            ];
            $tipoClass = $tipoClasses[$examen->tipo_examen] ?? 'badge-materia';
        @endphp
        <span class="badge-tipo {{ $tipoClass }}">
            <i class="fas fa-tag me-1"></i>{{ $examen->tipo_examen }}
        </span>
    </td>
    <td class="px-4">
        <i class="fas fa-question-circle text-primary me-1"></i>
        {{ $examen->numero_preguntas }} preguntas
    </td>
    <td class="px-4">
        <i class="fas fa-clock text-primary me-1"></i>
        {{ $examen->tiempo }} minutos
    </td>
    <td class="px-4">
        <i class="fas fa-calendar-alt text-muted me-1"></i>
        @if($examen->created_at)
            <span title="{{ $examen->created_at->format('d/m/Y H:i:s') }}">
                {{ $examen->created_at->format('d/m/Y H:i') }}
            </span>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    <td class="px-4 text-center">
        <div class="d-flex justify-content-center gap-2 flex-wrap">
            <a href="{{ route('admin.examenes.show', $examen->id) }}" class="btn-accion btn-ver" title="Ver detalles">
                <i class="fas fa-eye"></i>
                <span>Ver</span>
            </a>
            <button type="button" onclick="duplicarExamen({{ $examen->id }}, '{{ addslashes($examen->tipo_examen) }}', {{ $examen->numero_preguntas }})" 
                    class="btn-accion btn-duplicar" title="Duplicar">
                <i class="fas fa-copy"></i>
                <span>Duplicar</span>
            </button>
            <button type="button" onclick="eliminarExamen({{ $examen->id }}, '{{ addslashes($examen->tipo_examen) }}')" 
                    class="btn-accion btn-eliminar" title="Eliminar">
                <i class="fas fa-trash-alt"></i>
                <span>Eliminar</span>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center py-5">
        <div class="text-center">
            <i class="fas fa-file-alt fa-3x text-muted mb-3 d-block"></i>
            <h5 class="text-muted">No hay exámenes registrados</h5>
            <p class="text-muted small">Comienza creando un nuevo examen</p>
            <div class="btn-group">
                <a href="{{ route('admin.examenes.create') }}" class="btn btn-primary-custom mt-2">
                    <i class="fas fa-plus-circle me-2"></i>Crear primer examen
                </a>
                <button type="button" class="btn btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#generarAutomaticoModal">
                    <i class="fas fa-random me-2"></i>Generar automático
                </button>
            </div>
        </div>
    </td>
</tr>
@endforelse