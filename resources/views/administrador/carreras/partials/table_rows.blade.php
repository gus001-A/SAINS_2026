@forelse($carreras as $carrera)
<tr class="animate__animated animate__fadeInUp animate__fast" style="animation-delay: {{ $loop->index * 0.03 }}s;">
    <td class="px-4 py-3 text-center">
        <span class="fw-semibold" style="color: #667eea;">#{{ $carrera->id }}</span>
    </td>
    
    <td class="px-4 py-3">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-graduation-cap text-primary fa-lg"></i>
            <span class="fw-semibold">{{ $carrera->nombre }}</span>
        </div>
        @if($carrera->clave)
            <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">
                <i class="fas fa-key me-1"></i>{{ $carrera->clave }}
            </small>
        @endif
    </td>
    
    <td class="px-4 py-3">
        @if($carrera->tronco)
            <span class="badge-tronco">
                <i class="fas fa-layer-group me-1"></i> {{ $carrera->tronco->nombre }}
            </span>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    
    <td class="px-4 py-3 text-center">
        @if($carrera->calificacion_minima)
            <span class="badge-calificacion-definida" title="Calificación mínima requerida">
                <i class="fas fa-chart-line me-1"></i> {{ number_format($carrera->calificacion_minima, 2) }}%
            </span>
        @else
            <span class="badge-calificacion-no-definida">
                <i class="fas fa-minus-circle me-1"></i> No definida
            </span>
        @endif
    </td>
    
    <td class="px-4 py-3 text-center">
        <span class="fw-semibold" style="color: #667eea;">{{ $carrera->universidades->count() }}</span>
    </td>
    
    <td class="px-4 py-3 text-center">
        <div class="d-flex gap-2 justify-content-center">
            <button onclick="editCarrera({{ $carrera->id }}, '{{ addslashes($carrera->nombre) }}', {{ $carrera->tronco_id ?? 'null' }}, {{ $carrera->calificacion_minima ?? 'null' }}, {{ $carrera->id_asignatura_1 ?? 'null' }}, {{ $carrera->id_asignatura_2 ?? 'null' }}, {{ $carrera->id_asignatura_3 ?? 'null' }})" 
                    class="btn-accion btn-editar" 
                    title="Editar carrera">
                <i class="fas fa-edit"></i> Editar
            </button>
            <button onclick="deleteCarrera({{ $carrera->id }}, '{{ addslashes($carrera->nombre) }}')" 
                    class="btn-accion btn-eliminar" 
                    title="Eliminar carrera">
                <i class="fas fa-trash-alt"></i> Eliminar
            </button>
        </div>
    </td>
</tr>

@empty
<tr>
    <td colspan="6" class="text-center py-5">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h5 class="empty-state-title">No hay carreras registradas</h5>
            @if(request('search') || request('tronco_id'))
                <p class="empty-state-text">No se encontraron resultados con los filtros aplicados.</p>
                <a href="{{ route('admin.carreras.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-undo-alt me-1"></i>Limpiar filtros
                </a>
            @else
                <p class="empty-state-text">Comienza agregando la primera carrera</p>
                <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#createCarreraModal">
                    <i class="fas fa-plus-circle me-2"></i>Agregar primera carrera
                </button>
            @endif
        </div>
    </td>
</tr>
@endforelse