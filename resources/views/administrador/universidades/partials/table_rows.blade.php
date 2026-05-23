@forelse($universidades as $universidad)
<tr class="animate__animated animate__fadeInUp animate__fast" style="animation-delay: {{ $loop->index * 0.03 }}s;">
    <td class="px-3 py-2 fw-semibold">
        <span class="badge-clave">{{ $universidad->clave ?? '—' }}</span>
    </td>
    <td class="px-3 py-2">
        <div class="d-flex flex-column">
            <span class="fw-semibold">{{ $universidad->direccion }}</span>
        </div>
    </td>
    <td class="px-3 py-2">{{ $universidad->estado }}</td>
    <td class="px-3 py-2">{{ $universidad->municipio ?? '—' }}</td>
    <td class="px-3 py-2">{{ $universidad->carrera->nombre ?? '—' }}</td>
    <td class="px-3 py-2">
        @if($universidad->duracion)
            <span class="badge-duracion">{{ $universidad->duracion }}</span>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    <td class="px-3 py-2">
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
    <td class="px-3 py-2 text-center">
        <div class="d-flex justify-content-center gap-2">
            <a href="{{ route('admin.universidades.edit', $universidad->id) }}" 
               class="btn-accion btn-editar" 
               title="Editar universidad">
                <i class="fas fa-edit me-1"></i>Editar
            </a>
            <button type="button" 
                    onclick="confirmarEliminar('{{ route('admin.universidades.destroy', $universidad->id) }}', '{{ addslashes($universidad->clave) }}')" 
                    class="btn-accion btn-eliminar" 
                    title="Eliminar universidad">
                <i class="fas fa-trash-alt me-1"></i>Eliminar
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="text-center py-5">
        <div class="d-flex flex-column align-items-center gap-3 animate__animated animate__fadeIn">
            <div class="rounded-circle p-4" style="background: linear-gradient(135deg, #667eea10 0%, #764ba210 100%);">
                <i class="fas fa-university fa-4x" style="background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
            </div>
            <div>
                <h5 class="text-muted mb-2">✨ No hay universidades registradas</h5>
                <p class="text-muted small">Comienza registrando la primera universidad</p>
            </div>
            <a href="{{ route('admin.universidades.create') }}" class="btn btn-primary-custom px-4 py-2">
                <i class="fas fa-plus-circle me-2"></i>Registrar primera universidad
            </a>
        </div>
    </td>
</tr>
@endforelse