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
            $nombreGenerador = $cupon->usuarioGenero->estudiante->nombre . ' ' . ($cupon->usuarioGenero->estudiante->paterno ?? '');
            $correoGenerador = $cupon->usuarioGenero->correo;
        } elseif ($cupon->usuarioGenero->administrador) {
            $nombreGenerador = ($cupon->usuarioGenero->administrador->nombre ?? '') . ' ' . ($cupon->usuarioGenero->administrador->apellido_paterno ?? '');
            if (trim($nombreGenerador) == '') {
                $nombreGenerador = $cupon->usuarioGenero->correo;
            }
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
            $nombreUsuario = $cupon->usuarioUso->estudiante->nombre . ' ' . ($cupon->usuarioUso->estudiante->paterno ?? '');
            $correoUsuario = $cupon->usuarioUso->correo;
        } elseif ($cupon->usuarioUso->administrador) {
            $nombreUsuario = ($cupon->usuarioUso->administrador->nombre ?? '') . ' ' . ($cupon->usuarioUso->administrador->apellido_paterno ?? '');
            if (trim($nombreUsuario) == '') {
                $nombreUsuario = $cupon->usuarioUso->correo;
            }
            $correoUsuario = $cupon->usuarioUso->correo;
        } else {
            $nombreUsuario = $cupon->usuarioUso->correo;
            $correoUsuario = $cupon->usuarioUso->correo;
        }
    }
@endphp
<tr class="fade-in cupon-row" style="{{ $expirado && !$cupon->usado ? 'background: rgba(220, 53, 69, 0.02);' : '' }}">
    
    <!-- Código del Cupón -->
    <td class="px-4 py-3">
        <div class="codigo-cupon">
            <i class="fas fa-tag text-primary"></i>
            <span class="fw-semibold cupon-code" style="font-family: monospace; font-size: 0.85rem;">{{ $cupon->codigo }}</span>
            <button class="btn-copiar" onclick="copiarCodigo('{{ $cupon->codigo }}')" title="Copiar código">
                <i class="fas fa-copy"></i>
            </button>
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
    
    <!-- Generado por -->
    <td class="px-4 py-3">
        <div class="usuario-info">
            <div class="avatar-mini" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                {{ strtoupper(substr($nombreGenerador, 0, 1)) }}
            </div>
            <div class="usuario-detalles">
                <span class="usuario-nombre">{{ Str::limit($nombreGenerador, 25) }}</span>
                @if($correoGenerador && $correoGenerador != $nombreGenerador)
                    <span class="usuario-email">{{ Str::limit($correoGenerador, 25) }}</span>
                @endif
            </div>
        </div>
    </td>
    
    <!-- Usado por -->
    <td class="px-4 py-3">
        @if($cupon->usado && $cupon->usuarioUso)
            <div class="usuario-info">
                <div class="avatar-mini" style="background: linear-gradient(135deg, #f59e0b, #ef4444);">
                    {{ strtoupper(substr($nombreUsuario, 0, 1)) }}
                </div>
                <div class="usuario-detalles">
                    <span class="usuario-nombre">{{ Str::limit($nombreUsuario, 25) }}</span>
                    @if($correoUsuario && $correoUsuario != $nombreUsuario)
                        <span class="usuario-email">{{ Str::limit($correoUsuario, 25) }}</span>
                    @endif
                </div>
            </div>
        @else
            <span class="badge-disponible">
                <i class="fas fa-clock me-1"></i> Disponible
            </span>
        @endif
    </td>
    
    <!-- Fecha Creación -->
    <td class="px-4 py-3">
        <div class="fecha-info">
            <i class="fas fa-calendar-plus text-muted" style="font-size: 0.7rem;"></i>
            <div>
                <div class="fw-semibold" style="font-size: 0.85rem;">{{ $cupon->fecha_genero?->format('d/m/Y') ?? '—' }}</div>
            </div>
        </div>
    </td>
    
    <!-- Fecha Expiración -->
    <td class="px-4 py-3">
        @if($cupon->fecha_expiracion)
            <div class="expiracion-info">
                <div class="fecha-expiracion-box {{ $expirado ? 'expirado' : ($proximoExpiracion ? 'proximo' : 'normal') }}">
                    <div class="fecha-header">
                        <i class="fas {{ $expirado ? 'fa-skull' : ($proximoExpiracion ? 'fa-hourglass-half' : 'fa-calendar-week') }}" style="font-size: 0.7rem;"></i>
                        <span style="font-size: 0.85rem;">{{ $cupon->fecha_expiracion->format('d/m/Y') }}</span>
                    </div>
                </div>
                @if($expirado && !$cupon->usado)
                    <span class="badge-vencido mt-1">
                        <i class="fas fa-skull-crosswalk"></i> Vencido
                    </span>
                @endif
            </div>
        @else
            <div class="sin-expiracion">
                <i class="fas fa-infinity"></i>
                <span style="font-size: 0.85rem;">Sin límite</span>
            </div>
        @endif
    </td>
    
    <!-- Fecha Uso -->
    <td class="px-4 py-3">
        @if($cupon->fecha_uso)
            <div class="fecha-info uso">
                <i class="fas fa-check-circle text-success" style="font-size: 0.7rem;"></i>
                <div>
                    <div class="fw-semibold" style="font-size: 0.85rem;">{{ $cupon->fecha_uso->format('d/m/Y') }}</div>
                </div>
            </div>
        @else
            <div class="no-uso">
                <i class="fas fa-hourglass" style="font-size: 0.7rem;"></i>
                <span style="font-size: 0.85rem;">Sin usar</span>
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
        
        <span class="badge-state badge-{{ $estadoActual }}">
            <i class="fas {{ $iconoEstado }}"></i>
            {{ ucfirst($estadoActual) }}
        </span>
    </td>
    
    <!-- Acciones -->
    <td class="px-4 py-3 text-center">
        <div class="d-flex justify-content-center gap-2">
            <button onclick="verCupon({{ $cupon->id }})" 
                    class="btn-action btn-view" 
                    title="Ver detalles">
                <i class="fas fa-eye"></i>
                <span class="d-none d-md-inline">Ver</span>
            </button>
            
            @if(!$cupon->usado && !$expirado && $cupon->estatus == 'activo')
                <a href="{{ route('admin.cupones.edit', $cupon->id) }}" 
                   class="btn-action btn-edit" 
                   title="Editar cupón">
                    <i class="fas fa-edit"></i>
                    <span class="d-none d-md-inline">Editar</span>
                </a>
                
                <button onclick="eliminarCupon({{ $cupon->id }}, '{{ $cupon->codigo }}')" 
                        class="btn-action btn-delete" 
                        title="Eliminar cupón">
                    <i class="fas fa-trash-alt"></i>
                    <span class="d-none d-md-inline">Eliminar</span>
                </button>
            @elseif($expirado && !$cupon->usado)
                <button onclick="eliminarCupon({{ $cupon->id }}, '{{ $cupon->codigo }}')" 
                        class="btn-action btn-delete" 
                        title="Eliminar cupón expirado">
                    <i class="fas fa-trash-alt"></i>
                    <span class="d-none d-md-inline">Eliminar</span>
                </button>
            @elseif($cupon->usado)
                <span class="btn-action btn-disabled" style="opacity: 0.5; cursor: not-allowed; background: #e9ecef; color: #6c757d; display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 25px; font-size: 0.75rem; font-weight: 600;">
                    <i class="fas fa-lock"></i>
                    <span class="d-none d-md-inline">Bloqueado</span>
                </span>
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
            <h5 class="empty-state-title">No hay cupones registrados</h5>
            <p class="empty-state-text">Comienza creando el primer cupón de descuento</p>
            <a href="{{ route('admin.cupones.create') }}" class="btn btn-primary-custom px-4 py-2">
                <i class="fas fa-plus-circle me-2"></i>Crear primer cupón
            </a>
        </div>
    </td>
</tr>
@endforelse

@push('styles')
<style>
/* ============================================ */
/* ESTILOS PARA LA TABLA DE CUPONES */
/* ============================================ */

/* Código del cupón */
.codigo-cupon {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    padding: 6px 12px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}
.codigo-cupon:hover {
    border-color: #667eea;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
}
.btn-copiar {
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 4px;
    border-radius: 6px;
    color: #667eea;
    transition: all 0.2s;
}
.btn-copiar:hover {
    background: rgba(102, 126, 234, 0.1);
    transform: scale(1.05);
}

/* Tarjeta de descuento */
.descuento-card {
    text-align: center;
    padding: 8px 12px;
    border-radius: 12px;
    display: inline-block;
    min-width: 80px;
}
.descuento-card.porcentaje {
    background: linear-gradient(135deg, #667eea15, #764ba215);
    border-left: 3px solid #667eea;
}
.descuento-card.fijo {
    background: linear-gradient(135deg, #10b98115, #05966915);
    border-left: 3px solid #10b981;
}
.descuento-valor {
    font-size: 1.1rem;
    font-weight: 800;
    color: #1e293b;
}
.descuento-valor span {
    font-size: 0.7rem;
    font-weight: 400;
}
.descuento-tipo {
    font-size: 0.6rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #94a3b8;
}

/* Información de usuario */
.usuario-info {
    display: flex;
    align-items: center;
    gap: 10px;
}
.avatar-mini {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    color: white;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
.usuario-detalles {
    display: flex;
    flex-direction: column;
    min-width: 0;
}
.usuario-nombre {
    font-weight: 600;
    font-size: 0.85rem;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.usuario-email {
    font-size: 0.7rem;
    color: #94a3b8;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Badge disponible */
.badge-disponible {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #e3f2fd;
    color: #1565c0;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

/* Fechas */
.fecha-info {
    display: flex;
    align-items: center;
    gap: 8px;
}
.fecha-info.uso i {
    color: #10b981;
}

/* Fecha expiración */
.expiracion-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.fecha-expiracion-box {
    padding: 6px 12px;
    border-radius: 10px;
    display: inline-block;
    background: #f8fafc;
}
.fecha-header {
    display: flex;
    align-items: center;
    gap: 8px;
}
.fecha-expiracion-box.expirado {
    background: #ffebee;
    color: #c62828;
}
.fecha-expiracion-box.proximo {
    background: #fff3e0;
    color: #e65100;
}
.fecha-expiracion-box.normal {
    background: #e3f2fd;
    color: #1565c0;
}
.badge-vencido {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #ffebee;
    color: #c62828;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
}
.sin-expiracion {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #94a3b8;
}
.no-uso {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #94a3b8;
}

/* Badges de estado */
.badge-state {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 25px;
    font-size: 0.75rem;
    font-weight: 600;
}
.badge-activo {
    background: #e8f5e9;
    color: #2e7d32;
}
.badge-usado {
    background: #fff3e0;
    color: #e65100;
}
.badge-expirado {
    background: #ffebee;
    color: #c62828;
}
.badge-inactivo {
    background: #f1f5f9;
    color: #64748b;
}

/* Botones de acción */
.btn-action {
    padding: 0.5rem 1rem;
    margin: 0 2px;
    font-size: 0.75rem;
    border-radius: 25px;
    transition: all 0.25s ease;
    font-weight: 600;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    text-decoration: none;
}
.btn-view {
    background: #e3f2fd;
    color: #1565c0;
}
.btn-view:hover {
    background: #1565c0;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(21, 101, 192, 0.3);
}
.btn-edit {
    background: #e8f5e9;
    color: #2e7d32;
}
.btn-edit:hover {
    background: #2e7d32;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
}
.btn-delete {
    background: #ffebee;
    color: #c62828;
}
.btn-delete:hover {
    background: #c62828;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(198, 40, 40, 0.3);
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}
.empty-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2.5rem;
    color: #667eea;
}
.empty-state-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}
.empty-state-text {
    font-size: 0.9rem;
    color: #94a3b8;
    margin-bottom: 1rem;
}

/* Animación */
.fade-in {
    animation: fadeIn 0.3s ease-out forwards;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Dark Mode para los estilos de la tabla */
body.dark-mode .codigo-cupon {
    background: #1e293b;
    border-color: #334155;
}
body.dark-mode .descuento-valor {
    color: #f1f5f9;
}
body.dark-mode .usuario-nombre {
    color: #f1f5f9;
}
body.dark-mode .usuario-email {
    color: #94a3b8;
}
body.dark-mode .badge-activo {
    background: #064e3b;
    color: #34d399;
}
body.dark-mode .badge-usado {
    background: #451a03;
    color: #fbbf24;
}
body.dark-mode .badge-expirado {
    background: #450a0a;
    color: #f87171;
}
body.dark-mode .badge-inactivo {
    background: #1e293b;
    color: #94a3b8;
}
body.dark-mode .badge-disponible {
    background: #1e3a5f;
    color: #7ab7ef;
}
body.dark-mode .btn-view {
    background: #0c4a6e;
    color: #7ab7ef;
}
body.dark-mode .btn-view:hover {
    background: #1565c0;
    color: white;
}
body.dark-mode .btn-edit {
    background: #064e3b;
    color: #4ade80;
}
body.dark-mode .btn-edit:hover {
    background: #2e7d32;
    color: white;
}
body.dark-mode .btn-delete {
    background: #450a0a;
    color: #f87171;
}
body.dark-mode .btn-delete:hover {
    background: #c62828;
    color: white;
}
body.dark-mode .fecha-expiracion-box {
    background: #1e293b;
}
body.dark-mode .fecha-expiracion-box.expirado {
    background: #450a0a;
    color: #f87171;
}
body.dark-mode .fecha-expiracion-box.proximo {
    background: #451a03;
    color: #fbbf24;
}
body.dark-mode .fecha-expiracion-box.normal {
    background: #1e3a5f;
    color: #7ab7ef;
}
body.dark-mode .empty-state-title {
    color: #f1f5f9;
}
body.dark-mode .empty-state-text {
    color: #94a3b8;
}

/* ============================================ */
/* ESTILOS EXISTENTES (modales, tarjetas, etc.) */
/* ============================================ */

.modal {
    z-index: 1050;
}
.modal-backdrop {
    z-index: 1040;
}
.modal-content {
    z-index: 1051;
}
.modal.show {
    display: block !important;
    background-color: rgba(0,0,0,0.5);
}

/* Tus estilos existentes de tarjetas aquí... */
.stat-card-primary,
.stat-card-success,
.stat-card-warning,
.stat-card-danger {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}
.stat-card-primary::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #667eea, #764ba2); }
.stat-card-success::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #10b981, #059669); }
.stat-card-warning::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #f59e0b, #d97706); }
.stat-card-danger::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #ef4444, #dc2626); }

.stat-icon {
    width: 55px;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
}
.stat-card-primary .stat-icon { background: rgba(102, 126, 234, 0.1); color: #667eea; }
.stat-card-success .stat-icon { background: rgba(16, 185, 129, 0.1); color: #10b981; }
.stat-card-warning .stat-icon { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
.stat-card-danger .stat-icon { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

.hover-card { transition: all 0.3s ease-in-out; }
.hover-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important; }

.ordenar-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #1e293b;
    text-decoration: none;
    transition: all 0.2s ease;
}
.ordenar-link:hover {
    color: #667eea;
    transform: translateY(-1px);
}

.table-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 2px solid #e2e8f0;
}
.table-header th {
    color: #1e293b;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-primary-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
    border-radius: 12px;
}
.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-outline-secondary {
    border: 2px solid #e9ecef;
    transition: all 0.2s ease;
    background: transparent;
    border-radius: 12px;
}
.btn-outline-secondary:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
    color: white;
    transform: translateY(-2px);
}

.form-control-lg,
.form-select-lg {
    font-size: 0.95rem;
    border: 2px solid #e9ecef;
    transition: all 0.2s ease;
    background-color: white;
    border-radius: 12px;
}
.form-control-lg:focus,
.form-select-lg:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.pagination {
    margin-bottom: 0;
    gap: 4px;
}
.page-item .page-link {
    border-radius: 10px !important;
    margin: 0;
    color: #667eea;
    border: none;
    padding: 0.5rem 0.85rem;
    font-size: 0.85rem;
    transition: all 0.2s;
}
.page-item .page-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-2px);
}
.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
}

.table td {
    padding: 1rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}
.table tbody tr {
    transition: all 0.2s ease-in-out;
}
.table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.04);
    transform: translateX(2px);
}

/* Dark Mode existentes */
body.dark-mode .table-header {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border-bottom-color: #334155;
}
body.dark-mode .table-header th {
    color: #e2e8f0;
}
body.dark-mode .ordenar-link {
    color: #e2e8f0;
}
body.dark-mode .card {
    background-color: #1e293b;
}
body.dark-mode .bg-light {
    background-color: #0f172a !important;
}
body.dark-mode .form-control-lg,
body.dark-mode .form-select-lg {
    background-color: #0f172a;
    border-color: #334155;
    color: #e2e8f0;
}
body.dark-mode .btn-outline-secondary {
    border-color: #475569;
    color: #cbd5e1;
}
body.dark-mode .page-link {
    background-color: #0f172a;
    color: #818cf8;
}
body.dark-mode .table td {
    border-bottom-color: rgba(255, 255, 255, 0.05);
    color: #e0e0e0;
}
body.dark-mode .table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.08);
}
body.dark-mode .alert-info {
    background-color: #1e3a5f;
    border-color: #1e3a8a;
    color: #a5f3fc;
}
</style>
@endpush