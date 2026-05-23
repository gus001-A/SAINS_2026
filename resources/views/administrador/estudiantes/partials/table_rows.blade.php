@forelse($estudiantes as $usuario)
@php
    // Verificar si el estudiante existe
    $estudiante = $usuario->estudiante;
    
    // Si no existe el registro en la tabla estudiantes, mostrar datos básicos del usuario
    if (!$estudiante) {
        // Opcional: Registrar este problema para debugging
        // \Log::warning('Usuario con rol estudiante sin registro en tabla estudiantes', ['user_id' => $usuario->id, 'email' => $usuario->correo]);
    }
    
    $preparatoria = $estudiante ? $estudiante->escuelaProcedencia : null;
@endphp
<tr class="animate__animated animate__fadeInUp animate__fast" style="animation-delay: {{ $loop->index * 0.03 }}s;">
    <td class="px-4 py-3">
        <div class="estudiante-info">
            <div class="avatar-estudiante">
                {{ $estudiante ? strtoupper(substr($estudiante->nombre ?? 'E', 0, 1)) : strtoupper(substr($usuario->name ?? $usuario->correo ?? 'E', 0, 1)) }}
            </div>
            <div class="estudiante-detalles">
                <span class="estudiante-nombre">
                    {{ $estudiante ? ($estudiante->nombre . ' ' . $estudiante->paterno . ' ' . $estudiante->materno) : ($usuario->name ?? 'Usuario sin completar') }}
                </span>
                <span class="estudiante-email"><i class="fas fa-envelope fa-xs"></i> {{ $usuario->correo }}</span>
            </div>
        </div>
    </td>
    
    <td class="px-4 py-3">
        <div class="d-flex flex-column gap-1">
            @if($estudiante && $estudiante->telefono)
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-mobile-alt text-success fa-sm"></i>
                    <span>{{ $estudiante->telefono }}</span>
                </div>
            @endif
            @if($estudiante && $estudiante->telefono_casa)
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-phone-alt text-info fa-sm"></i>
                    <span>{{ $estudiante->telefono_casa }}</span>
                </div>
            @endif
            @if(!$estudiante || (!$estudiante->telefono && !$estudiante->telefono_casa))
                <span class="text-muted">—</span>
            @endif
        </div>
    </td>
    
    <td class="px-4 py-3">
        @if($estudiante && $estudiante->sexo == 'M')
            <span class="badge-sexo-m"><i class="fas fa-mars"></i> Masculino</span>
        @elseif($estudiante && $estudiante->sexo == 'F')
            <span class="badge-sexo-f"><i class="fas fa-venus"></i> Femenino</span>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    
    <td class="px-4 py-3">
        <div class="text-center">
            <span class="fw-semibold">
                {{ $estudiante && $estudiante->fecha_nacimiento ? \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->format('d/m/Y') : '—' }}
            </span>
            @if($estudiante && $estudiante->fecha_nacimiento)
                <br>
                <small class="text-muted">
                    {{ \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->age }} años
                </small>
            @endif
        </div>
    </td>
    
    <td class="px-4 py-3">
        <div class="d-flex flex-column">
            @if($estudiante && $preparatoria)
                @if($preparatoria->centro_educativo)
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <i class="fas fa-school text-primary fa-sm"></i>
                        <span class="small text-muted" title="{{ $preparatoria->centro_educativo }}">
                            {{ Str::limit($preparatoria->centro_educativo, 25) }}
                        </span>
                    </div>
                @endif
            @else
                <span class="text-muted">—</span>
            @endif
        </div>
    </td>
    
    <td class="px-4 py-3 text-center">
        @if($estudiante && $estudiante->plan_activo)
            <span class="badge-plan-activo"><i class="fas fa-check-circle"></i> Activo</span>
        @else
            <span class="badge-plan-inactivo"><i class="fas fa-times-circle"></i> Inactivo</span>
        @endif
    </td>
    
    <td class="px-4 py-3">
        @if($estudiante && $estudiante->cupon)
            <span class="badge-cupon" title="{{ $estudiante->cupon }}">
                <i class="fas fa-tag me-1"></i> {{ Str::limit($estudiante->cupon, 12) }}
            </span>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    
    <td class="px-4 py-3">
        <div class="d-flex gap-2 justify-content-center flex-wrap">
            <button onclick="verEstudiante({{ $usuario->id }})" 
                    class="btn-accion btn-ver" 
                    title="Ver detalles del estudiante">
                <i class="fas fa-eye"></i> Ver
            </button>
            <button onclick="editarEstudiante({{ $usuario->id }})" 
                    class="btn-accion btn-editar" 
                    title="Editar estudiante">
                <i class="fas fa-edit"></i> Editar
            </button>
            <button onclick="eliminarEstudiante({{ $usuario->id }}, '{{ $estudiante ? addslashes($estudiante->nombre . ' ' . $estudiante->paterno . ' ' . $estudiante->materno) : addslashes($usuario->correo) }}')" 
                    class="btn-accion btn-eliminar" 
                    title="Eliminar estudiante">
                <i class="fas fa-trash-alt"></i> Eliminar
            </button>
        </div>
    </td>
</tr>

@empty
<tr>
    <td colspan="9" class="text-center py-5">
        <div class="d-flex flex-column align-items-center gap-3">
            <div class="rounded-circle p-4" style="background: linear-gradient(135deg, #667eea10 0%, #764ba210 100%);">
                <i class="fas fa-users fa-4x text-muted"></i>
            </div>
            <div>
                <h5 class="text-muted mb-2">No hay estudiantes registrados</h5>
                <p class="text-muted small">Comienza agregando el primer estudiante a la plataforma</p>
            </div>
            <a href="{{ route('admin.estudiantes.create') }}" class="btn btn-primary-custom px-4 py-2">
                <i class="fas fa-user-plus me-2"></i>Registrar primer estudiante
            </a>
        </div>
    </td>
</tr>
@endforelse