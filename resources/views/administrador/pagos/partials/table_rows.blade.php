{{-- administrador/pagos/partials/table_rows.blade.php --}}
@forelse($pagos as $pago)
<tr>
    <td class="px-3 py-2">
        <span class="fw-bold text-primary" style="font-size: 0.85rem;">#{{ $pago->id }}</span>
    </td>
    
    <td class="px-3 py-2">
        <span class="badge-tipo-contacto" style="background: {{ $pago->tipo_pago == 'Bancario' ? '#e3f2fd' : ($pago->tipo_pago == 'Oxxo' ? '#fff3e0' : '#e8f5e9') }}; color: {{ $pago->tipo_pago == 'Bancario' ? '#1565c0' : ($pago->tipo_pago == 'Oxxo' ? '#e65100' : '#2e7d32') }};">
            <i class="fas {{ $pago->tipo_pago == 'Bancario' ? 'fa-university' : ($pago->tipo_pago == 'Oxxo' ? 'fa-store' : 'fa-exchange-alt') }} fa-xs"></i>
            {{ $pago->tipo_pago }}
        </span>
    </td>
    
    <td class="px-3 py-2">
        <div class="usuario-info">
            <div class="avatar-mini" style="background: linear-gradient(135deg, #f59e0b, #ef4444);">
                {{ substr($pago->alumno->nombre_completo ?? $pago->alumno_pago, 0, 1) }}
            </div>
            <div class="usuario-detalles">
                <span class="usuario-nombre">{{ $pago->alumno->nombre_completo ?? 'Usuario eliminado' }}</span>
                <span class="usuario-email">ID: {{ $pago->alumno_pago }}</span>
            </div>
        </div>
    </td>
    
    <td class="px-3 py-2">
        <span class="fw-bold text-success">${{ number_format($pago->monto_pago, 2) }}</span>
    </td>
    
    <td class="px-3 py-2">
        @if($pago->fecha_pago)
            <div class="fecha-info">
                <span class="fecha-fecha">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</span>
            </div>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    
    <td class="px-3 py-2">
        <code style="font-size: 0.7rem; background: #f8f9fa; padding: 3px 8px; border-radius: 4px;">
            {{ $pago->referencia_pago ?? '—' }}
        </code>
    </td>
    
    <td class="px-3 py-2">
        @php
            $estadoClasses = [
                'pendiente' => 'badge-pendiente',
                'aprobado' => 'badge-aprobado',
                'rechazado' => 'badge-rechazado',
                'cancelado' => 'badge-cancelado'
            ];
            $estadoClass = $estadoClasses[$pago->estatus] ?? 'badge-pendiente';
            $estadoIconos = [
                'pendiente' => 'fa-clock',
                'aprobado' => 'fa-check-circle',
                'rechazado' => 'fa-times-circle',
                'cancelado' => 'fa-ban'
            ];
            $estadoIcono = $estadoIconos[$pago->estatus] ?? 'fa-clock';
        @endphp
        <span class="badge-estado {{ $estadoClass }}">
            <i class="fas {{ $estadoIcono }} fa-xs"></i>
            {{ ucfirst($pago->estatus) }}
        </span>
    </td>
    
    <td class="px-3 py-2">
        @if($pago->usuario_revision && $pago->revisor)
            @php
                $admin = $pago->revisor->administrador;
                $revisorNombre = $admin ? $admin->nombre_completo : $pago->revisor->correo;
            @endphp
            <div class="usuario-info">
                <div class="avatar-mini" style="background: linear-gradient(135deg, #27ae60, #2ecc71);">
                    <i class="fas fa-user-check fa-xs"></i>
                </div>
                <div class="usuario-detalles">
                    <span class="usuario-nombre">{{ $revisorNombre }}</span>
                    @if($pago->fecha_aprueba)
                        <span class="usuario-email">{{ \Carbon\Carbon::parse($pago->fecha_aprueba)->format('d/m/Y H:i') }}</span>
                    @endif
                </div>
            </div>
        @else
            <span class="text-muted">
                <i class="fas fa-hourglass-half me-1"></i>Sin revisar
            </span>
        @endif
    </td>
    
    <td class="px-3 py-2 text-center">
        <div class="d-flex gap-1 justify-content-center">
            <button onclick="verPago({{ $pago->id }})" 
                    class="btn-accion btn-ver" 
                    title="Ver detalles">
                <i class="fas fa-eye"></i>
            </button>
            @if($pago->estatus != 'aprobado')
                <button onclick="eliminarPago({{ $pago->id }}, '{{ addslashes($pago->alumno->nombre_completo ?? $pago->alumno_pago) }}')" 
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
        <div class="d-flex flex-column align-items-center gap-3">
            <div class="rounded-circle p-4" style="background: linear-gradient(135deg, #667eea10 0%, #764ba210 100%);">
                <i class="fas fa-credit-card fa-4x text-muted"></i>
            </div>
            <div>
                <h5 class="text-muted mb-2">No hay pagos registrados</h5>
                <p class="text-muted small">Comienza registrando el primer pago de un estudiante</p>
            </div>
            <a href="{{ route('admin.pagos.create') }}" class="btn btn-primary-custom px-4 py-2">
                <i class="fas fa-plus-circle me-2"></i>Registrar primer pago
            </a>
        </div>
    </td>
</tr>
@endforelse