@forelse($preguntas as $pregunta)
<tr class="animate__animated animate__fadeInUp animate__fast" style="animation-delay: {{ $loop->index * 0.03 }}s;">
    <td class="px-3 py-2" data-label="ID">
        <span class="fw-bold text-primary" style="font-size: 0.85rem;">#{{ $pregunta->id }}</span>
    </td>
    
    <td class="px-3 py-2" data-label="Pregunta">
        <div class="pregunta-texto">
            <span class="fw-semibold">{{ Str::limit($pregunta->pregunta, 70) }}</span>
        </div>
    </td>
    
    <td class="px-3 py-2" data-label="Área">
        <span class="badge-area">
            <i class="fas fa-layer-group fa-xs me-1"></i>
            {{ $pregunta->area->nombre ?? $pregunta->area->area ?? 'Sin área' }}
        </span>
    </td>
    
    <td class="px-3 py-2" data-label="Respuestas">
        <div class="answers-container">
            <span class="answer-item">
                <i class="fas fa-arrow-right fa-xs"></i>
                {{ Str::limit($pregunta->respuesta1, 40) }}
            </span>
            <span class="answer-item">
                <i class="fas fa-arrow-right fa-xs"></i>
                {{ Str::limit($pregunta->respuesta2, 40) }}
            </span>
        </div>
    </td>
    
    <td class="px-3 py-2" data-label="Respuesta Correcta">
        <span class="badge-correcta">
            <i class="fas fa-check-circle fa-xs me-1"></i>
            {{ Str::limit($pregunta->respuesta_correcta, 35) }}
        </span>
    </td>
    
    <td class="px-3 py-2 text-center" data-label="Acciones">
        <div class="d-flex gap-1 justify-content-center">
            <a href="{{ route('admin.preguntas.edit', $pregunta->id) }}" 
               class="btn-accion btn-editar" 
               title="Editar pregunta">
                <i class="fas fa-edit"></i>
            </a>
            <button onclick="confirmarEliminar('{{ route('admin.preguntas.destroy', $pregunta->id) }}', {{ $pregunta->id }}, '{{ addslashes($pregunta->pregunta) }}')" 
                    class="btn-accion btn-eliminar" 
                    title="Eliminar pregunta">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </td>
</tr>

@empty
<tr class="animate__animated animate__fadeIn">
    <td colspan="6" class="text-center py-5">
        <div class="d-flex flex-column align-items-center gap-3">
            <div class="rounded-circle p-4" style="background: linear-gradient(135deg, #667eea10 0%, #764ba210 100%);">
                <i class="fas fa-question-circle fa-4x text-muted"></i>
            </div>
            <div>
                <h5 class="text-muted mb-2">No hay preguntas registradas</h5>
                <p class="text-muted small">Comienza agregando una nueva pregunta al banco</p>
            </div>
            <a href="{{ route('admin.preguntas.create') }}" class="btn btn-primary-custom px-4 py-2">
                <i class="fas fa-plus-circle me-2"></i>Agregar primera pregunta
            </a>
        </div>
    外侧
</tr>
@endforelse