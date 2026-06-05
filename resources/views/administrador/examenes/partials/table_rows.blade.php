@forelse($examenes as $examen)
<tr class="animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.03 }}s;">
    <td class="px-4 py-3 text-center">
        <span class="fw-semibold text-primary">#{{ $examen->id }}</span>
    </td>
    <td class="px-4 py-3">
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
    <td class="px-4 py-3 text-center">
        <span class="pregunta-badge">
            <i class="fas fa-question-circle me-1"></i>
            {{ $examen->numero_preguntas }} preguntas
        </span>
    </td>
    <td class="px-4 py-3 text-center">
        <span class="tiempo-badge">
            <i class="fas fa-clock me-1"></i>
            {{ $examen->tiempo }} minutos
        </span>
    </td>
    <td class="px-4 py-3">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-calendar-alt text-muted fa-sm"></i>
            <span>{{ $examen->created_at ? $examen->created_at->format('d/m/Y H:i') : '—' }}</span>
        </div>
    </td>
    <td class="px-4 py-3 text-center">
        <div class="d-flex justify-content-center gap-2">
            <a href="{{ route('admin.examenes.show', $examen->id) }}" 
               class="btn-accion btn-ver" 
               title="Ver detalles">
                <i class="fas fa-eye"></i>
                <span>Ver</span>
            </a>
            <button type="button" 
                    onclick="duplicarExamen({{ $examen->id }}, '{{ addslashes($examen->tipo_examen) }}', {{ $examen->numero_preguntas }})" 
                    class="btn-accion btn-duplicar" 
                    title="Duplicar">
                <i class="fas fa-copy"></i>
                <span>Duplicar</span>
            </button>
            <button type="button" 
                    onclick="eliminarExamen({{ $examen->id }}, '{{ addslashes($examen->tipo_examen) }}')" 
                    class="btn-accion btn-eliminar" 
                    title="Eliminar">
                <i class="fas fa-trash-alt"></i>
                <span>Eliminar</span>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center py-5">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <h5 class="empty-state-title">No hay exámenes registrados</h5>
            <p class="empty-state-text">Comienza creando tu primer examen</p>
            <a href="{{ route('admin.examenes.create') }}" class="btn btn-primary-custom mt-3 px-4">
                <i class="fas fa-plus me-2"></i>Crear primer examen
            </a>
        </div>
    </td>
</tr>
@endforelse