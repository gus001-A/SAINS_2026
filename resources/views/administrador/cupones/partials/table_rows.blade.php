@forelse($cupones as $cupon)
@php
    $expirado = $cupon->fecha_expiracion && now()->greaterThan($cupon->fecha_expiracion);
    $proximoExpiracion = $cupon->fecha_expiracion && now()->diffInDays($cupon->fecha_expiracion, false) <= 7 && now()->diffInDays($cupon->fecha_expiracion, false) >= 0;
    $diasRestantes = $cupon->fecha_expiracion ? now()->diffInDays($cupon->fecha_expiracion, false) : null;
    $esUrgente = $diasRestantes !== null && $diasRestantes <= 3 && $diasRestantes >= 0 && !$expirado && !$cupon->usado;
    
    // Obtener nombre del generador
    $nombreGenerador = 'Sistema';
    $correoGenerador = '';
    if ($cupon->usuarioGenero) {
        if ($cupon->usuarioGenero->estudiante) {
            $nombreGenerador = $cupon->usuarioGenero->estudiante->nombre_completo;
            $correoGenerador = $cupon->usuarioGenero->correo;
        } elseif ($cupon->usuarioGenero->administrador) {
            $nombreGenerador = $cupon->usuarioGenero->administrador->nombre_completo ?? $cupon->usuarioGenero->correo;
            $correoGenerador = $cupon->usuarioGenero->correo;
        } else {
            $nombreGenerador = $cupon->usuarioGenero->correo;
            $correoGenerador = $cupon->usuarioGenero->correo;
        }
    }
    
    // Obtener nombre del usuario que usó el cupón
    $nombreUsuario = '';
    $correoUsuario = '';
    if ($cupon->usuarioUso) {
        if ($cupon->usuarioUso->estudiante) {
            $nombreUsuario = $cupon->usuarioUso->estudiante->nombre_completo;
            $correoUsuario = $cupon->usuarioUso->correo;
        } elseif ($cupon->usuarioUso->administrador) {
            $nombreUsuario = $cupon->usuarioUso->administrador->nombre_completo ?? $cupon->usuarioUso->correo;
            $correoUsuario = $cupon->usuarioUso->correo;
        } else {
            $nombreUsuario = $cupon->usuarioUso->correo;
            $correoUsuario = $cupon->usuarioUso->correo;
        }
    }
@endphp
<tr class="animate__animated animate__fadeInUp animate__fast cupon-row" 
    style="animation-delay: {{ $loop->index * 0.03 }}s; {{ $expirado && !$cupon->usado ? 'background: rgba(220, 53, 69, 0.02);' : '' }}">
    
    <!-- Código del Cupón -->
    <td class="px-4 py-3">
        <div class="codigo-cupon-wrapper">
            <div class="codigo-cupon">
                <i class="fas fa-tag text-primary"></i>
                <span class="fw-semibold cupon-code">{{ $cupon->codigo }}</span>
                <button class="btn-copiar" onclick="copiarCodigo('{{ $cupon->codigo }}')" title="Copiar código">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
            @if($cupon->usado)
                <span class="badge-utilizado mt-1">
                    <i class="fas fa-check-circle"></i> Utilizado
                </span>
            @endif
        </div>
    </td>
    
    <!-- Descuento -->
    <td class="px-4 py-3">
        @if($cupon->tipo_descuento == 'porcentaje')
            <div class="descuento-card porcentaje">
                <div class="descuento-valor">{{ $cupon->valor_descuento }}<span>%</span></div>
                <div class="descuento-tipo">DESCUENTO</div>
            </div>
        @else
            <div class="descuento-card fijo">
                <div class="descuento-valor">${{ number_format($cupon->valor_descuento, 0) }}</div>
                <div class="descuento-tipo">AHORRO</div>
            </div>
        @endif
    </td>
    
    <!-- Generado por (con nombre completo) -->
    <td class="px-4 py-3">
        <div class="usuario-info">
            <div class="avatar-inicial" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                {{ strtoupper(substr($nombreGenerador, 0, 1)) }}
            </div>
            <div class="usuario-detalles">
                <span class="usuario-nombre">{{ $nombreGenerador }}</span>
                @if($correoGenerador && $correoGenerador != $nombreGenerador)
                    <span class="usuario-email">{{ $correoGenerador }}</span>
                @endif
            </div>
        </div>
    </td>
    
    <!-- Usado por (con nombre completo) -->
    <td class="px-4 py-3">
        @if($cupon->usado && $cupon->usuarioUso)
            <div class="usuario-info">
                <div class="avatar-inicial" style="background: linear-gradient(135deg, #f59e0b, #ef4444);">
                    {{ strtoupper(substr($nombreUsuario, 0, 1)) }}
                </div>
                <div class="usuario-detalles">
                    <span class="usuario-nombre">{{ $nombreUsuario }}</span>
                    @if($correoUsuario && $correoUsuario != $nombreUsuario)
                        <span class="usuario-email">{{ $correoUsuario }}</span>
                    @endif
                </div>
            </div>
        @else
            <div class="disponible-badge">
                <i class="fas fa-clock"></i> Disponible
            </div>
        @endif
    </td>
    
    <!-- Fecha Creación (SIN HORA) -->
    <td class="px-4 py-3">
        <div class="fecha-info">
            <i class="fas fa-calendar-plus text-muted"></i>
            <div>
                <div class="fw-semibold small">{{ $cupon->fecha_genero?->format('d/m/Y') ?? '—' }}</div>
            </div>
        </div>
    </td>
    
    <!-- Fecha Expiración (SIN HORA) -->
    <td class="px-4 py-3">
        @if($cupon->fecha_expiracion)
            <div class="expiracion-info">
                <div class="fecha-expiracion-box 
                    {{ $expirado ? 'expirado' : ($proximoExpiracion ? 'proximo' : 'normal') }}">
                    <div class="fecha-header">
                        <i class="fas {{ $expirado ? 'fa-skull' : ($proximoExpiracion ? 'fa-hourglass-half' : 'fa-calendar-week') }}"></i>
                        <span>{{ $cupon->fecha_expiracion->format('d/m/Y') }}</span>
                    </div>
                </div>
                
                <!-- Contador de tiempo restante -->
                @if(!$expirado && !$cupon->usado && $diasRestantes !== null && $diasRestantes >= 0)
                    <div class="tiempo-restante {{ $esUrgente ? 'urgente' : '' }}">
                        @if($diasRestantes == 0)
                            <i class="fas fa-fire"></i>
                            <span>Expira hoy</span>
                        @else
                            <i class="fas fa-hourglass-start"></i>
                            <span>{{ $diasRestantes }} día{{ $diasRestantes != 1 ? 's' : '' }} restantes</span>
                        @endif
                    </div>
                @endif
                
                <!-- Badges de estado -->
                @if($proximoExpiracion && !$expirado && !$cupon->usado)
                    <span class="badge-expirado-pronto">
                        <i class="fas fa-exclamation-triangle"></i> ¡Expira pronto!
                    </span>
                @endif
                @if($expirado && !$cupon->usado)
                    <span class="badge-vencido">
                        <i class="fas fa-skull-crosswalk"></i> Cupón vencido
                    </span>
                @endif
            </div>
        @else
            <div class="sin-expiracion">
                <i class="fas fa-infinity"></i>
                <span>Sin fecha límite</span>
            </div>
        @endif
    </td>
    
    <!-- Fecha Uso (SIN HORA) -->
    <td class="px-4 py-3">
        @if($cupon->fecha_uso)
            <div class="fecha-info uso">
                <i class="fas fa-check-circle text-success"></i>
                <div>
                    <div class="fw-semibold small">{{ $cupon->fecha_uso->format('d/m/Y') }}</div>
                </div>
            </div>
        @else
            <div class="no-uso">
                <i class="fas fa-hourglass"></i>
                <span>Sin usar</span>
            </div>
        @endif
    </td>
    
    <!-- Estado -->
    <td class="px-4 py-3">
        @php
            $estadoActual = '';
            $iconoEstado = '';
            $colorEstado = '';
            
            if($cupon->usado) {
                $estadoActual = 'usado';
                $iconoEstado = 'fa-check-double';
                $colorEstado = '#e65100';
            } elseif($expirado) {
                $estadoActual = 'expirado';
                $iconoEstado = 'fa-hourglass-end';
                $colorEstado = '#c62828';
            } elseif($cupon->estatus == 'inactivo') {
                $estadoActual = 'inactivo';
                $iconoEstado = 'fa-ban';
                $colorEstado = '#6c757d';
            } else {
                $estadoActual = 'activo';
                $iconoEstado = 'fa-check-circle';
                $colorEstado = '#2e7d32';
            }
        @endphp
        
        <div class="estado-badge estado-{{ $estadoActual }}" style="border-left-color: {{ $colorEstado }};">
            <i class="fas {{ $iconoEstado }}"></i>
            <span>{{ ucfirst($estadoActual) }}</span>
            @if($estadoActual == 'activo' && $proximoExpiracion)
                <div class="estado-alerta">⚠️</div>
            @endif
        </div>
        
        <!-- Tooltip con más info -->
        @if($estadoActual == 'activo' && $cupon->fecha_expiracion && !$expirado)
            <div class="estado-tooltip">
                <i class="fas fa-info-circle"></i>
                <span>Válido hasta {{ $cupon->fecha_expiracion->format('d/m/Y') }}</span>
            </div>
        @endif
    </td>
    
    <!-- Acciones (CONDICIONALES MEJORADAS) -->
    <td class="px-4 py-3 text-center">
        <div class="acciones-buttons">
            <!-- Botón VER siempre visible -->
            <button class="btn-accion btn-ver" onclick="verCupon({{ $cupon->id }})" title="Ver detalles">
                <i class="fas fa-eye"></i>
                <span>Ver</span>
            </button>
            
            @if(!$cupon->usado && !$expirado)
                <!-- Cupón activo y no expirado: se puede EDITAR y ELIMINAR -->
                <button class="btn-accion btn-editar" onclick="editarCupon({{ $cupon->id }})" title="Editar cupón">
                    <i class="fas fa-edit"></i>
                    <span>Editar</span>
                </button>
                <button class="btn-accion btn-eliminar" onclick="eliminarCupon({{ $cupon->id }}, '{{ $cupon->codigo }}')" title="Eliminar cupón">
                    <i class="fas fa-trash-alt"></i>
                    <span>Eliminar</span>
                </button>
            @elseif($expirado && !$cupon->usado)
                <!-- Cupón expirado pero NO usado: solo se puede ELIMINAR (no editar) -->
                <button class="btn-accion btn-eliminar" onclick="eliminarCupon({{ $cupon->id }}, '{{ $cupon->codigo }}')" title="Eliminar cupón expirado">
                    <i class="fas fa-trash-alt"></i>
                    <span>Eliminar</span>
                </button>
            @elseif($cupon->usado)
                <!-- Cupón usado: totalmente BLOQUEADO -->
                <button class="btn-accion btn-usado" disabled title="Cupón ya utilizado" style="opacity: 0.5; cursor: not-allowed;">
                    <i class="fas fa-lock"></i>
                    <span>Bloqueado</span>
                </button>
            @endif
        </div>
    </td>
</tr>

@empty
<tr>
    <td colspan="9" class="text-center py-5">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="empty-content">
                <h5>No hay cupones registrados</h5>
                <p>Comienza creando el primer cupón de descuento para tus estudiantes</p>
                <a href="{{ route('admin.cupones.create') }}" class="btn-empty-action">
                    <i class="fas fa-plus-circle"></i> Crear primer cupón
                </a>
            </div>
        </div>
    </td>
</tr>
@endforelse