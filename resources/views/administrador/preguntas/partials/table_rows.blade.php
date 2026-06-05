@forelse($preguntas as $pregunta)
@php
    $areaNombre = $pregunta->area->nombre ?? $pregunta->area->area ?? 'Sin área';
@endphp
<tr class="animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.03 }}s;">
    <td class="px-4 py-3 text-center">
        <span class="fw-bold text-primary" style="font-size: 0.85rem;">#{{ $pregunta->id }}</span>
    </td>
    
    <td class="px-4 py-3">
        <div class="pregunta-texto">
            <span class="fw-semibold">{{ Str::limit($pregunta->pregunta, 80) }}</span>
        </div>
    </td>
    
    <td class="px-4 py-3">
        <span class="badge-area">
            <i class="fas fa-layer-group fa-xs me-1"></i>
            {{ $areaNombre }}
        </span>
    </td>
    
    <td class="px-4 py-3">
        <div class="answers-container">
            <span class="answer-item" title="{{ $pregunta->respuesta1 }}">
                <i class="fas fa-arrow-right fa-xs"></i>
                A: {{ Str::limit($pregunta->respuesta1, 35) }}
            </span>
            <span class="answer-item" title="{{ $pregunta->respuesta2 }}">
                <i class="fas fa-arrow-right fa-xs"></i>
                B: {{ Str::limit($pregunta->respuesta2, 35) }}
            </span>
        </div>
    </td>
    
    <td class="px-4 py-3">
        <span class="badge-correcta" title="{{ $pregunta->respuesta_correcta }}">
            <i class="fas fa-check-circle fa-xs me-1"></i>
            {{ Str::limit($pregunta->respuesta_correcta, 30) }}
        </span>
    </td>
    
    <td class="px-4 py-3 text-center">
        @if($pregunta->justificacion && !empty($pregunta->justificacion))
            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-3 d-inline-flex align-items-center gap-1">
                <i class="fas fa-check-circle"></i>
                <span>Con justificación</span>
            </span>
        @else
            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-3 d-inline-flex align-items-center gap-1">
                <i class="fas fa-times-circle"></i>
                <span>Sin justificación</span>
            </span>
        @endif
    </td>
    
    <td class="px-4 py-3 text-center">
        <div class="d-flex gap-1 justify-content-center">
            <button type="button" onclick="verPregunta({{ $pregunta->id }})" 
                    class="btn-accion btn-ver" 
                    title="Ver detalles">
                <i class="fas fa-eye"></i>
                <span>Ver</span>
            </button>
            <a href="{{ route('admin.preguntas.edit', $pregunta->id) }}" 
               class="btn-accion btn-editar" 
               title="Editar pregunta">
                <i class="fas fa-edit"></i>
                <span>Editar</span>
            </a>
            <button type="button" onclick="confirmarEliminar('{{ route('admin.preguntas.destroy', $pregunta->id) }}', {{ $pregunta->id }}, '{{ addslashes($pregunta->pregunta) }}')" 
                    class="btn-accion btn-eliminar" 
                    title="Eliminar pregunta">
                <i class="fas fa-trash-alt"></i>
                <span>Eliminar</span>
            </button>
        </div>
    </td>
</tr>

@empty
<tr>
    <td colspan="7" class="text-center py-5">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-question-circle"></i>
            </div>
            <h5 class="empty-state-title">No hay preguntas registradas</h5>
            <p class="empty-state-text">Comienza agregando una nueva pregunta al banco</p>
            <a href="{{ route('admin.preguntas.create') }}" class="btn btn-primary-custom px-4 py-2">
                <i class="fas fa-plus-circle me-2"></i>Agregar primera pregunta
            </a>
        </div>
    </td>
</tr>
@endforelse