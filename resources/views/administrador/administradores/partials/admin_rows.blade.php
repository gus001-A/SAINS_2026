@forelse($admins as $admin)
    <tr>
        <td class="fw-semibold text-primary">{{ $admin->id }}</td>
        <td>
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle">
                    {{ strtoupper(substr($admin->administrador->nombre ?? 'A', 0, 1)) }}
                </div>
                <div class="d-flex flex-column">
                    <span class="fw-semibold mb-0">
                        {{ $admin->administrador->nombre ?? '—' }} 
                        {{ $admin->administrador->apellido_paterno ?? '' }}
                    </span>
                    @if($admin->administrador && $admin->administrador->apellido_materno)
                        <small class="text-muted">{{ $admin->administrador->apellido_materno }}</small>
                    @endif
                </div>
            </div>
        </td>
        <td>
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-envelope text-muted fa-sm"></i>
                <span class="small">{{ $admin->correo }}</span>
            </div>
        </td>
        <td>
            @if($admin->administrador && $admin->administrador->telefono)
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-phone-alt text-muted fa-sm"></i>
                    <span class="small">{{ $admin->administrador->telefono }}</span>
                </div>
            @else
                <span class="text-muted small">—</span>
            @endif
        </td>
        <td>
            @if($admin->administrador && $admin->administrador->sexo)
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-{{ $admin->administrador->sexo == 'M' ? 'mars' : 'venus' }} text-muted fa-sm"></i>
                    <span class="small">
                        {{ $admin->administrador->sexo == 'M' ? 'Masculino' : 'Femenino' }}
                    </span>
                </div>
            @else
                <span class="text-muted small">—</span>
            @endif
        </td>
        <td>
            @php
                // ✅ La fecha está en la tabla administradores, NO en users
                $fechaRegistro = $admin->administrador->created_at ?? null;
            @endphp
            @if($fechaRegistro)
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-calendar-alt text-muted fa-sm"></i>
                        <span class="small fw-semibold">
                            {{ \Carbon\Carbon::parse($fechaRegistro)->format('d/m/Y') }}
                        </span>
                    </div>
                    <small class="text-muted" style="font-size: 0.7rem;">
                        <i class="far fa-clock me-1"></i>
                        {{ \Carbon\Carbon::parse($fechaRegistro)->format('g:i A') }}
                    </small>
                </div>
            @else
                <span class="text-muted small">—</span>
            @endif
        </td>
        <td class="text-center">
            <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('admin.administradores.edit', $admin->id) }}" 
                   class="btn btn-editar btn-accion" 
                   data-tooltip="Editar administrador">
                    <i class="fas fa-edit"></i>
                    <span class="d-none d-md-inline">Editar</span>
                </a>
                <button type="button" 
                        onclick="confirmarEliminar('{{ route('admin.administradores.destroy', $admin->id) }}', '{{ $admin->administrador->nombre_completo ?? $admin->correo }}', '{{ $admin->correo }}')" 
                        class="btn btn-eliminar btn-accion"
                        data-tooltip="Eliminar administrador">
                    <i class="fas fa-trash-alt"></i>
                    <span class="d-none d-md-inline">Eliminar</span>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-5">
            <div class="d-flex flex-column align-items-center gap-3">
                <div class="rounded-circle p-4" style="background: linear-gradient(135deg, #667eea10 0%, #764ba210 100%);">
                    <i class="fas fa-user-shield fa-4x text-muted"></i>
                </div>
                <div>
                    <p class="text-muted mb-2 fs-5">No hay administradores registrados</p>
                    <p class="text-muted small">Comienza creando el primer administrador del sistema</p>
                </div>
                <a href="{{ route('admin.administradores.create') }}" class="btn btn-primary-custom px-4">
                    <i class="fas fa-user-plus me-2"></i>Crear primer administrador
                </a>
            </div>
        </td>
    </tr>
@endforelse