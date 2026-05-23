@forelse($interacciones as $interaccion)
<tr class="animate__animated animate__fadeInUp animate__fast" style="animation-delay: {{ $loop->index * 0.03 }}s;">
    <td class="px-3 py-2" data-label="ID">
        <span class="fw-bold" style="background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; background-clip: text; color: transparent; font-size: 0.9rem;">#{{ $interaccion->id }}</span>
    </td>
    
    <td class="px-3 py-2" data-label="Fecha/Hora">
        @if($interaccion->fecha_contacto)
            <div class="fecha-info">
                <span class="fecha-fecha">{{ \Carbon\Carbon::parse($interaccion->fecha_contacto)->format('d/m/Y') }}</span>
                <span class="fecha-hora">
                    <i class="far fa-clock fa-xs me-1"></i>
                    {{ $interaccion->hora_contacto ? \Carbon\Carbon::parse($interaccion->hora_contacto)->format('h:i A') : 'N/A' }}
                </span>
            </div>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    
    <td class="px-3 py-2" data-label="Administrador">
        <div class="usuario-info">
            <div class="avatar-mini" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                {{ substr($interaccion->admin_nombre_completo ?? 'A', 0, 1) }}
            </div>
            <div class="usuario-detalles">
                <span class="usuario-nombre">{{ $interaccion->admin_nombre_completo ?? 'N/A' }}</span>
                @if($interaccion->administrador && $interaccion->administrador->email)
                    <span class="usuario-email">{{ $interaccion->administrador->email }}</span>
                @endif
            </div>
        </div>
    </td>
    
    <td class="px-3 py-2" data-label="Estudiante">
        <div class="usuario-info">
            <div class="avatar-mini" style="background: linear-gradient(135deg, #f59e0b, #ef4444);">
                {{ substr($interaccion->est_nombre_completo ?? 'E', 0, 1) }}
            </div>
            <div class="usuario-detalles">
                <span class="usuario-nombre">{{ $interaccion->est_nombre_completo ?? 'N/A' }}</span>
                @if($interaccion->estudiante && $interaccion->estudiante->email)
                    <span class="usuario-email">{{ $interaccion->estudiante->email }}</span>
                @endif
            </div>
        </div>
    </td>
    
    <td class="px-3 py-2 text-center" data-label="Tipo">
        @php
            $tipoClasses = [
                'llamada' => 'badge-llamada',
                'email' => 'badge-email',
                'whatsapp' => 'badge-whatsapp'
            ];
            $tipoClass = $tipoClasses[$interaccion->tipo_contacto] ?? 'badge-llamada';
            $tipoIconos = [
                'llamada' => 'fa-phone',
                'email' => 'fa-envelope',
                'whatsapp' => 'fa-whatsapp'
            ];
            $tipoIcono = $tipoIconos[$interaccion->tipo_contacto] ?? 'fa-phone';
        @endphp
        <span class="badge-tipo-contacto {{ $tipoClass }}">
            <i class="{{ $interaccion->tipo_contacto == 'whatsapp' ? 'fab' : 'fas' }} {{ $tipoIcono }} fa-sm"></i>
            {{ ucfirst($interaccion->tipo_contacto) }}
        </span>
    </td>
    
    <td class="px-3 py-2" data-label="Motivo">
        <span class="motivo-texto" title="{{ $interaccion->motivo_contacto ?? 'N/A' }}">
            {{ Str::limit($interaccion->motivo_contacto ?? 'N/A', 35) }}
        </span>
    </td>
    
    <td class="px-3 py-2 text-center" data-label="Estado">
        @php
            $estadoClasses = [
                'pendiente' => 'badge-pendiente',
                'en_proceso' => 'badge-en_proceso',
                'finalizado' => 'badge-finalizado'
            ];
            $estadoClass = $estadoClasses[$interaccion->estado_seguimiento] ?? 'badge-pendiente';
            $estadoIconos = [
                'pendiente' => 'fa-clock',
                'en_proceso' => 'fa-sync-alt',
                'finalizado' => 'fa-check-circle'
            ];
            $estadoIcono = $estadoIconos[$interaccion->estado_seguimiento] ?? 'fa-clock';
        @endphp
        <span class="badge-estado {{ $estadoClass }}">
            <i class="fas {{ $estadoIcono }} fa-sm"></i>
            {{ ucfirst(str_replace('_', ' ', $interaccion->estado_seguimiento ?? 'N/A')) }}
        </span>
    </td>
    
    <td class="px-3 py-2" data-label="Próximo Contacto">
        @if($interaccion->proximo_contacto)
            <div class="fecha-info">
                <span class="fecha-fecha">{{ \Carbon\Carbon::parse($interaccion->proximo_contacto)->format('d/m/Y') }}</span>
                @if($interaccion->proximo_contacto_hora)
                    <span class="fecha-hora">
                        <i class="far fa-clock fa-xs me-1"></i>
                        {{ \Carbon\Carbon::parse($interaccion->proximo_contacto_hora)->format('h:i A') }}
                    </span>
                @endif
            </div>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    
    <td class="px-3 py-2 text-center" data-label="Acciones">
        <div class="d-flex gap-1 justify-content-center">
            <button onclick="verInteraccion({{ $interaccion->id }})" 
                    class="btn-accion btn-ver" 
                    title="Ver detalles">
                <i class="fas fa-eye"></i>
            </button>
            <button onclick="editarInteraccion({{ $interaccion->id }})" 
                    class="btn-accion btn-editar" 
                    title="Editar interacción">
                <i class="fas fa-edit"></i>
            </button>
            <button onclick="eliminarInteraccion({{ $interaccion->id }})" 
                    class="btn-accion btn-eliminar" 
                    title="Eliminar interacción">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="9" class="text-center py-5">
        <div class="d-flex flex-column align-items-center gap-3 animate__animated animate__fadeIn">
            <div class="rounded-circle p-4" style="background: linear-gradient(135deg, #667eea10 0%, #764ba210 100%);">
                <i class="fas fa-phone-slash fa-4x" style="background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; background-clip: text; color: transparent;"></i>
            </div>
            <div>
                <h5 class="text-muted mb-2">✨ No hay interacciones registradas</h5>
                <p class="text-muted small">Comienza registrando la primera interacción con un estudiante</p>
            </div>
            <a href="{{ route('admin.callcenter.create') }}" class="btn btn-primary-custom px-4 py-2">
                <i class="fas fa-plus-circle me-2"></i>Registrar primera interacción
            </a>
        </div>
    </td>
</tr>
@endforelse