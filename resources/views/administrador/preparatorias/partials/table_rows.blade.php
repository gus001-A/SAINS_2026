@forelse($preparatorias as $prepa)
    <tr>
        <td class="fw-semibold">{{ $prepa->clave ?? '—' }}</td>
        <td>
            <div class="d-flex flex-column">
                <span class="fw-semibold">{{ $prepa->centro_educativo }}</span>
                @if($prepa->direccion)
                    <small class="text-muted">{{ Str::limit($prepa->direccion, 50) }}</small>
                @endif
            </div>
        </td>
        <td>{{ $prepa->estado }}</td>
        <td>{{ $prepa->municipio }}</td>
        <td>{{ $prepa->localidad ?: '—' }}</td>
        <td>
            @if($prepa->turno)
                <span class="badge-turno">{{ $prepa->turno }}</span>
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td>
            @if($prepa->tipo == 'PUBLICO')
                <span class="badge-publica">Pública</span>
            @elseif($prepa->tipo == 'PRIVADO')
                <span class="badge-privada">Privada</span>
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td class="text-center">
            <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('admin.preparatorias.edit', $prepa->id) }}" 
                   class="btn btn-editar btn-accion">
                    <i class="fas fa-edit me-1"></i>Editar
                </a>
                <button type="button" 
                        onclick="confirmarEliminar('{{ route('admin.preparatorias.destroy', $prepa->id) }}', '¿Eliminar preparatoria?', 'Esta acción eliminará {{ $prepa->centro_educativo }}')" 
                        class="btn btn-eliminar btn-accion">
                    <i class="fas fa-trash-alt me-1"></i>Eliminar
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-5">
            <i class="fas fa-institution fa-3x text-muted mb-3 d-block"></i>
            <p class="text-muted mb-0">No hay preparatorias registradas</p>
            <a href="{{ route('admin.preparatorias.create') }}" class="btn btn-primary-custom mt-3">
                Registrar primera preparatoria
            </a>
        </td>
    </tr>
@endforelse