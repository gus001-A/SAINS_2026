@forelse($pagos as $pago)
<tr>
    <td class="px-4 py-3 text-center">
        <span class="fw-bold" style="color: #667eea;">#{{ $pago->id }}</span>
    </td>
    
    <td class="px-4 py-3">
        @php
            $tipoClass = '';
            $tipoIcon = '';
            if($pago->tipo_pago == 'Bancario') {
                $tipoClass = 'badge-bancario';
                $tipoIcon = 'fa-university';
            } elseif($pago->tipo_pago == 'Oxxo') {
                $tipoClass = 'badge-oxxo';
                $tipoIcon = 'fa-store';
            } else {
                $tipoClass = 'badge-transferencia';
                $tipoIcon = 'fa-exchange-alt';
            }
        @endphp
        <span class="badge-tipo {{ $tipoClass }}">
            <i class="fas {{ $tipoIcon }}"></i>
            {{ $pago->tipo_pago }}
        </span>
    </td>
    
    <td class="px-4 py-3">
        @php
            $estudiante = \App\Models\Estudiante::find($pago->alumno_pago);
        @endphp
        @if($estudiante)
            <div class="fw-semibold" style="color: #1f2937;">{{ $estudiante->nombre_completo }}</div>
            <small class="text-muted" style="font-size: 0.7rem;">
                <i class="fas fa-id-card me-1"></i>{{ $pago->alumno_pago }}
            </small>
        @else
            <span class="text-muted">
                <i class="fas fa-user-slash me-1"></i>ID: {{ $pago->alumno_pago }}
            </span>
        @endif
    </td>
    
    <td class="px-4 py-3 text-center">
        <span class="fw-bold" style="color: #10b981; font-size: 1rem;">
            <i class="fas fa-dollar-sign me-1"></i>{{ number_format($pago->monto_pago, 2) }}
        </span>
    </td>
    
    <td class="px-4 py-3">
        @if($pago->fecha_pago)
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-calendar-alt" style="color: #667eea;"></i>
                <span>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</span>
            </div>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    
    <td class="px-4 py-3">
        <code class="ref-code">{{ $pago->referencia_pago ?? '—' }}</code>
    </td>
    
    <td class="px-4 py-3">
        @php
            $estadoClasses = [
                'pendiente' => 'badge-pendiente',
                'aprobado' => 'badge-aprobado',
                'rechazado' => 'badge-rechazado',
                'cancelado' => 'badge-cancelado'
            ];
            $estadoIconos = [
                'pendiente' => 'fa-clock',
                'aprobado' => 'fa-check-circle',
                'rechazado' => 'fa-times-circle',
                'cancelado' => 'fa-ban'
            ];
        @endphp
        <span class="badge-estado {{ $estadoClasses[$pago->estatus] ?? 'badge-pendiente' }}">
            <i class="fas {{ $estadoIconos[$pago->estatus] ?? 'fa-clock' }}"></i>
            {{ ucfirst($pago->estatus) }}
        </span>
    </td>
    
    <td class="px-4 py-3">
        @if($pago->usuario_revision && $pago->revisor)
            @php
                $admin = $pago->revisor->administrador;
                $revisorNombre = $admin ? $admin->nombre_completo : $pago->revisor->correo;
            @endphp
            <div class="d-flex flex-column gap-1">
                <div class="d-flex align-items-center gap-1">
                    <i class="fas fa-user-check text-success fa-sm"></i>
                    <span class="fw-semibold small" style="color: #10b981;">{{ $revisorNombre }}</span>
                </div>
                @if($pago->fecha_aprueba)
                    <div class="d-flex align-items-center gap-1">
                        <i class="fas fa-clock text-muted fa-xs"></i>
                        <small class="text-muted" style="font-size: 0.7rem;">
                            {{ \Carbon\Carbon::parse($pago->fecha_aprueba)->format('d/m/Y H:i') }}
                        </small>
                    </div>
                @endif
            </div>
        @else
            <div class="d-flex align-items-center gap-1">
                <i class="fas fa-hourglass-half" style="color: #f59e0b; font-size: 0.8rem;"></i>
                <span class="text-muted small">Pendiente</span>
            </div>
        @endif
    </td>
    
    <td class="px-4 py-3 text-center">
        <div class="d-flex gap-2 justify-content-center">
            <button onclick="verPago({{ $pago->id }})" class="btn-accion btn-ver" title="Ver detalles">
                <i class="fas fa-eye"></i>
            </button>
            @if($pago->estatus != 'aprobado')
                <button onclick="eliminarPago({{ $pago->id }}, '{{ addslashes($estudiante ? $estudiante->nombre_completo : 'este pago') }}')" 
                        class="btn-accion btn-eliminar" 
                        title="Eliminar pago">
                    <i class="fas fa-trash-alt"></i>
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
                <i class="fas fa-credit-card"></i>
            </div>
            <h5 class="empty-state-title">No hay pagos registrados</h5>
            @if(request('search') || request('tipo_pago') || request('estatus'))
                <p class="empty-state-text">No se encontraron resultados con los filtros aplicados.</p>
                <a href="{{ route('admin.pagos.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-undo-alt me-1"></i>Limpiar filtros
                </a>
            @else
                <p class="empty-state-text">Comienza registrando el primer pago de un estudiante</p>
            @endif
        </div>
    </td>
</tr>
@endforelse