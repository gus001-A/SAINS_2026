@forelse($preparatorias as $prepa)
<tr>
    <td class="fw-bold" style="color: #667eea; font-size: 0.9rem;">
        {{ $prepa->clave ?? '—' }}
    </td>
    <td>
        <div class="d-flex flex-column">
            <span class="fw-semibold" style="font-size: 0.95rem;">{{ $prepa->centro_educativo }}</span>
            @if($prepa->direccion)
                <small class="text-muted" style="font-size: 0.7rem;">{{ Str::limit($prepa->direccion, 60) }}</small>
            @endif
        </div>
    </td>
    <td>
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-map-marker-alt text-muted" style="font-size: 0.7rem;"></i>
            <span style="font-size: 0.9rem;">{{ $prepa->estado }}</span>
        </div>
    </td>
    <td>
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-city text-muted" style="font-size: 0.7rem;"></i>
            <span style="font-size: 0.9rem;">{{ $prepa->municipio }}</span>
        </div>
    </td>
    <td>
        <span style="font-size: 0.9rem;">{{ $prepa->localidad ?: '—' }}</span>
    </td>
    <td>
        @if($prepa->turno)
            <span class="badge-turno">
                <i class="fas fa-clock me-1"></i>{{ $prepa->turno }}
            </span>
        @else
            <span class="text-muted" style="font-size: 0.9rem;">—</span>
        @endif
    </td>
    <td>
        @if($prepa->tipo == 'PUBLICO')
            <span class="badge-publica">
                <i class="fas fa-landmark me-1"></i>Pública
            </span>
        @elseif($prepa->tipo == 'PRIVADO')
            <span class="badge-privada">
                <i class="fas fa-building me-1"></i>Privada
            </span>
        @else
            <span class="text-muted" style="font-size: 0.9rem;">—</span>
        @endif
    </td>
    <td class="text-center">
        <div class="d-flex justify-content-center gap-2">
            <a href="{{ route('admin.preparatorias.edit', $prepa->id) }}" 
               class="btn-action btn-edit" 
               data-tooltip="Editar preparatoria">
                <i class="fas fa-edit"></i>
                <span class="d-none d-md-inline">Editar</span>
            </a>
            <button type="button" 
                    onclick="confirmarEliminar('{{ route('admin.preparatorias.destroy', $prepa->id) }}', '{{ addslashes($prepa->centro_educativo) }}')" 
                    class="btn-action btn-delete"
                    data-tooltip="Eliminar preparatoria">
                <i class="fas fa-trash-alt"></i>
                <span class="d-none d-md-inline">Eliminar</span>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="text-center py-5">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-institution"></i>
            </div>
            <h5 class="empty-state-title">No hay preparatorias registradas</h5>
            <p class="empty-state-text">Comienza registrando la primera preparatoria en el sistema</p>
            <a href="{{ route('admin.preparatorias.create') }}" class="btn btn-primary-custom px-4 py-2 mt-2">
                <i class="fas fa-plus-circle me-2"></i>Registrar primera preparatoria
            </a>
        </div>
    </td>
</tr>
@endforelse