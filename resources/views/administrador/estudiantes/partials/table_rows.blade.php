@forelse($estudiantes as $usuario)
@php
    $estudiante = $usuario->estudiante;
    $preparatoria = $estudiante ? $estudiante->escuelaProcedencia : null;
    $registroCompleto = $estudiante && $estudiante->nombre && $estudiante->paterno;
    $rowClass = !$registroCompleto ? 'row-incompleto' : '';
@endphp
<tr class="animate__fadeInUp {{ $rowClass }}" style="animation-delay: {{ $loop->index * 0.03 }}s;">
    <!-- Columna 1: Estudiante -->
    <td class="px-4 py-3">
        <div class="estudiante-info">
            <div class="avatar-estudiante {{ !$registroCompleto ? 'avatar-incompleto' : '' }}">
                @if($registroCompleto && $estudiante->nombre)
                    {{ strtoupper(substr($estudiante->nombre, 0, 1)) }}
                @else
                    <i class="fas fa-user fa-fw"></i>
                @endif
            </div>
            <div class="estudiante-detalles">
                @if($registroCompleto)
                    <span class="estudiante-nombre" title="{{ $estudiante->nombre . ' ' . $estudiante->paterno . ' ' . $estudiante->materno }}">
                        {{ $estudiante->nombre . ' ' . $estudiante->paterno . ' ' . $estudiante->materno }}
                    </span>
                @else
                    <span class="estudiante-nombre estudiante-nombre-incompleto">
                        <i class="fas fa-exclamation-triangle me-1"></i>Registro Incompleto
                    </span>
                @endif
                <span class="estudiante-email"><i class="fas fa-envelope fa-xs"></i> {{ $usuario->correo }}</span>
            </div>
        </div>
    </td>
    
    <!-- Columna 2: Contacto -->
    <td class="px-4 py-3">
        @if($registroCompleto && ($estudiante->telefono || $estudiante->telefono_casa))
            <div class="d-flex flex-column gap-2">
                @if($estudiante->telefono)
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-mobile-alt text-success fa-fw"></i>
                        <span>{{ $estudiante->telefono }}</span>
                    </div>
                @endif
                @if($estudiante->telefono_casa)
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-phone-alt text-info fa-fw"></i>
                        <span>{{ $estudiante->telefono_casa }}</span>
                    </div>
                @endif
            </div>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    
    <!-- Columna 3: Sexo -->
    <td class="px-4 py-3 text-center">
        @if($registroCompleto && $estudiante->sexo == 'M')
            <span class="badge-sexo-m"><i class="fas fa-mars"></i> Masculino</span>
        @elseif($registroCompleto && $estudiante->sexo == 'F')
            <span class="badge-sexo-f"><i class="fas fa-venus"></i> Femenino</span>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    
    <!-- Columna 4: Nacimiento -->
    <td class="px-4 py-3 text-center">
        @if($registroCompleto && $estudiante->fecha_nacimiento)
            <div>
                <span class="fw-semibold">{{ \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->format('d/m/Y') }}</span>
                <br>
                <small class="text-muted">{{ \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->age }} años</small>
            </div>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    
    <!-- Columna 5: Preparatoria -->
    <td class="px-4 py-3">
        @if($registroCompleto && $preparatoria && $preparatoria->centro_educativo)
            <div class="d-flex flex-column gap-1">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-school text-primary fa-sm"></i>
                    <span class="small fw-semibold" title="{{ $preparatoria->centro_educativo }}">
                        {{ Str::limit($preparatoria->centro_educativo, 50) }}
                    </span>
                </div>
                @if($preparatoria->estado)
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-map-marker-alt text-muted fa-xs"></i>
                        <span class="text-muted small">{{ $preparatoria->estado }}{{ $preparatoria->municipio ? ', ' . $preparatoria->municipio : '' }}</span>
                    </div>
                @endif
                @if($preparatoria->turno)
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-clock text-muted fa-xs"></i>
                        <span class="text-muted small">Turno: {{ $preparatoria->turno }}</span>
                    </div>
                @endif
            </div>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    
    <!-- Columna 6: Plan -->
    <td class="px-4 py-3 text-center">
        @if($registroCompleto && $estudiante->plan_activo)
            <span class="badge-plan-activo"><i class="fas fa-check-circle"></i> Activo</span>
        @elseif($registroCompleto && !$estudiante->plan_activo)
            <span class="badge-plan-inactivo"><i class="fas fa-times-circle"></i> Inactivo</span>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    
    <!-- Columna 7: Cupón -->
    <td class="px-4 py-3 text-center">
        @if($registroCompleto && $estudiante->cupon)
            <span class="badge-cupon" title="{{ $estudiante->cupon }}">
                <i class="fas fa-ticket-alt me-1"></i> {{ Str::limit($estudiante->cupon, 15) }}
            </span>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    
    <!-- Columna 8: Acciones -->
    <td class="px-4 py-3">
        <div class="d-flex gap-2 justify-content-center">
            @if($registroCompleto)
                <button onclick="verEstudiante({{ $usuario->id }})" class="btn-accion btn-ver" title="Ver detalles">
                    <i class="fas fa-eye"></i> Ver
                </button>
                <button onclick="editarEstudiante({{ $usuario->id }})" class="btn-accion btn-editar" title="Editar">
                    <i class="fas fa-edit"></i> Editar
                </button>
                <button onclick="eliminarEstudiante({{ $usuario->id }}, '{{ addslashes($estudiante->nombre . ' ' . $estudiante->paterno) }}')" class="btn-accion btn-eliminar" title="Eliminar">
                    <i class="fas fa-trash-alt"></i> Eliminar
                </button>
            @else
                <button onclick="eliminarEstudiante({{ $usuario->id }}, '{{ addslashes($usuario->correo) }}')" class="btn-accion btn-eliminar" title="Eliminar">
                    <i class="fas fa-trash-alt"></i> Eliminar
                </button>
            @endif
        </div>
    </td>
</tr>

@empty
<tr>
    <td colspan="8" class="text-center py-5">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-users"></i>
            </div>
            <h5 class="empty-state-title">No hay estudiantes registrados</h5>
            <p class="empty-state-text">Comienza agregando el primer estudiante a la plataforma</p>
            <a href="{{ route('admin.estudiantes.create') }}" class="btn btn-primary-custom px-4 py-2">
                <i class="fas fa-user-plus me-2"></i>Registrar primer estudiante
            </a>
        </div>
    </td>
</tr>
@endforelse