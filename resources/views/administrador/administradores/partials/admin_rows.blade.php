@forelse($admins as $admin)
<tr>
    <td class="fw-bold" style="color: #667eea; font-size: 1rem;">#{{ $admin->id }}</td>
    <td>
        <div class="d-flex align-items-center gap-3">
            <div class="admin-avatar">
                {{ strtoupper(substr($admin->administrador->nombre ?? 'A', 0, 1)) }}
            </div>
            <div class="admin-info">
                <span class="admin-name">
                    {{ $admin->administrador->nombre ?? '—' }} 
                    {{ $admin->administrador->apellido_paterno ?? '' }}
                </span>
                @if($admin->administrador && $admin->administrador->apellido_materno)
                    <span class="admin-lastname">{{ $admin->administrador->apellido_materno }}</span>
                @endif
            </div>
        </div>
    </td>
    <td>
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-envelope text-muted" style="font-size: 0.8rem;"></i>
            <span style="font-size: 0.95rem;">{{ $admin->correo }}</span>
            @if($admin->email_verified_at)
                <i class="fas fa-check-circle text-success" style="font-size: 0.8rem;" data-tooltip="Verificado"></i>
            @endif
        </div>
    </td>
    <td>
        @if($admin->administrador && $admin->administrador->telefono)
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-phone-alt text-muted" style="font-size: 0.8rem;"></i>
                <span style="font-size: 0.95rem;">{{ $admin->administrador->telefono }}</span>
            </div>
        @else
            <span class="text-muted" style="font-size: 0.95rem;">—</span>
        @endif
    </td>
    <td>
        @if($admin->administrador && $admin->administrador->sexo)
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-{{ $admin->administrador->sexo == 'M' ? 'mars' : 'venus' }} text-muted" style="font-size: 0.8rem;"></i>
                <span style="font-size: 0.95rem;">
                    {{ $admin->administrador->sexo == 'M' ? 'Masculino' : 'Femenino' }}
                </span>
            </div>
        @else
            <span class="text-muted" style="font-size: 0.95rem;">—</span>
        @endif
    </td>
    <td class="text-center">
        <div class="d-flex justify-content-center gap-2">
            <a href="{{ route('admin.administradores.edit', $admin->id) }}" 
               class="btn-action btn-edit" 
               data-tooltip="Editar administrador">
                <i class="fas fa-edit"></i>
                <span class="d-none d-md-inline">Editar</span>
            </a>
            <button type="button" 
                    onclick="confirmarEliminar('{{ route('admin.administradores.destroy', $admin->id) }}', '{{ addslashes($admin->administrador->nombre_completo ?? $admin->correo) }}', '{{ $admin->correo }}')" 
                    class="btn-action btn-delete"
                    data-tooltip="Eliminar administrador">
                <i class="fas fa-trash-alt"></i>
                <span class="d-none d-md-inline">Eliminar</span>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center py-5">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <h5 class="empty-state-title">No hay administradores registrados</h5>
            <p class="empty-state-text">Comienza creando el primer administrador del sistema</p>
            <a href="{{ route('admin.administradores.create') }}" class="btn btn-primary-custom px-4 py-2 mt-2">
                <i class="fas fa-user-plus me-2"></i>Crear primer administrador
            </a>
        </div>
    </td>
</tr>
@endforelse