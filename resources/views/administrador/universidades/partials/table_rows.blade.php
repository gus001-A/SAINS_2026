@forelse($universidades as $universidad)
<tr class="animate__animated animate__fadeInUp animate__fast" style="animation-delay: {{ $loop->index * 0.03 }}s;">
    <td class="px-4 py-3 text-center">
        <span class="badge-clave">
            <i class="fas fa-key me-1"></i>{{ $universidad->clave ?? '—' }}
        </span>
    </td>
    
    <td class="px-4 py-3">
        <div class="fw-semibold" style="color: #1f2937;">{{ $universidad->direccion }}</div>
        @if($universidad->nombre)
            <small class="text-muted" style="font-size: 0.7rem;">
                <i class="fas fa-tag me-1"></i>{{ $universidad->nombre }}
            </small>
        @endif
    </td>
    
    <td class="px-4 py-3">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-map-marker-alt" style="color: #667eea;"></i>
            <span>{{ $universidad->estado }}</span>
        </div>
    </td>
    
    <td class="px-4 py-3">
        @if($universidad->municipio)
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-city" style="color: #10b981;"></i>
                <span>{{ $universidad->municipio }}</span>
            </div>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    
    <td class="px-4 py-3">
        @if($universidad->carrera)
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-graduation-cap" style="color: #f59e0b;"></i>
                <span class="fw-semibold">{{ $universidad->carrera->nombre }}</span>
            </div>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    
    <td class="px-4 py-3 text-center">
        @if($universidad->duracion)
            <span class="badge-duracion">
                <i class="fas fa-clock me-1"></i>{{ $universidad->duracion }}
            </span>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    
    <td class="px-4 py-3 text-center">
        @if($universidad->tipo == 'PUBLICA')
            <span class="badge-publica">
                <i class="fas fa-landmark me-1"></i>PÚBLICA
            </span>
        @elseif($universidad->tipo == 'PRIVADA')
            <span class="badge-privada">
                <i class="fas fa-building me-1"></i>PRIVADA
            </span>
        @elseif($universidad->tipo == 'AUTONOMA')
            <span class="badge-autonoma">
                <i class="fas fa-balance-scale me-1"></i>AUTÓNOMA
            </span>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    
    <td class="px-4 py-3 text-center">
        <div class="d-flex gap-2 justify-content-center">
            <button onclick="verUniversidad({{ $universidad->id }})" 
                    class="btn-accion btn-ver" 
                    title="Ver detalles">
                <i class="fas fa-eye"></i> Ver
            </button>
            <a href="{{ route('admin.universidades.edit', $universidad->id) }}" 
               class="btn-accion btn-editar" 
               title="Editar universidad">
                <i class="fas fa-edit"></i> Editar
            </a>
            <button type="button" 
                    onclick="confirmarEliminar('{{ route('admin.universidades.destroy', $universidad->id) }}', '{{ addslashes($universidad->clave ?? $universidad->direccion) }}')" 
                    class="btn-accion btn-eliminar" 
                    title="Eliminar universidad">
                <i class="fas fa-trash-alt"></i> Eliminar
            </button>
        </div>
    </td>
</tr>

@empty
<tr>
    <td colspan="8" class="text-center py-5">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-university"></i>
            </div>
            <h5 class="empty-state-title">No hay universidades registradas</h5>
            @if(request('search') || request('estado'))
                <p class="empty-state-text">No se encontraron resultados con los filtros aplicados.</p>
                <button onclick="window.location.href='{{ route('admin.universidades.index') }}'" class="btn btn-outline-secondary">
                    <i class="fas fa-undo-alt me-1"></i>Limpiar filtros
                </button>
            @else
                <p class="empty-state-text">Comienza registrando la primera universidad</p>
                <a href="{{ route('admin.universidades.create') }}" class="btn btn-primary-custom">
                    <i class="fas fa-plus-circle me-2"></i>Registrar primera universidad
                </a>
            @endif
        </div>
    </td>
</tr>
@endforelse