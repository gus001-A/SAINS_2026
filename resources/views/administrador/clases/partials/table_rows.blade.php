@forelse($clases as $index => $clase)
<tr class="animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.03 }}s;">
    <td class="px-4 py-3 text-center">
        <span class="fw-semibold text-primary">{{ $clases->firstItem() + $index }}</span>
    </td>
    <td class="px-4 py-3">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-3 p-2" style="background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));">
                <i class="fas fa-book-open text-primary fa-sm"></i>
            </div>
            <span class="fw-semibold">{{ Str::limit($clase->asignatura->nombre ?? 'N/A', 35) }}</span>
        </div>
    </td>
    <td class="px-4 py-3 text-center">
        <span class="badge-clase">
            <i class="fas fa-hashtag me-1"></i>
            Clase {{ $clase->num_clase }}
        </span>
    </td>
    <td class="px-4 py-3">
        <div class="d-flex flex-column">
            <span class="fw-semibold">{{ Str::limit($clase->nombre_clase, 45) }}</span>
            @if($clase->descripcion)
                <small class="text-muted">{{ Str::limit($clase->descripcion, 60) }}</small>
            @endif
        </div>
    </td>
    <td class="px-4 py-3 text-center">
        @if($clase->link)
            <a href="{{ $clase->link }}" target="_blank" class="badge-video text-decoration-none">
                <i class="fab fa-youtube me-1"></i> Ver video
            </a>
        @else
            <span class="badge-sin-video">
                <i class="fas fa-ban me-1"></i> Sin video
            </span>
        @endif
    </td>
    <td class="px-4 py-3 text-center">
        @if($clase->url)
            <a href="{{ $clase->url }}" target="_blank" class="badge-material text-decoration-none">
                <i class="fas fa-file-alt me-1"></i> Ver material
            </a>
        @else
            <span class="badge-sin-material">
                <i class="fas fa-ban me-1"></i> Sin material
            </span>
        @endif
    </td>
    <td class="px-4 py-3 text-center">
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ route('admin.clases.show', $clase->id) }}" 
               class="btn-ver btn-accion" 
               title="Ver detalles">
                <i class="fas fa-eye"></i> Ver
            </a>
            <a href="{{ route('admin.clases.edit', $clase->id) }}" 
               class="btn-editar btn-accion" 
               title="Editar">
                <i class="fas fa-pencil-alt"></i> Editar
            </a>
            <button type="button" 
                    onclick="eliminarClase({{ $clase->id }}, '{{ addslashes($clase->nombre_clase) }}')" 
                    class="btn-eliminar btn-accion" 
                    title="Eliminar">
                <i class="fas fa-trash-alt"></i> Eliminar
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center py-5">
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-chalkboard"></i>
            </div>
            <h5 class="empty-state-title">No hay clases registradas</h5>
            <p class="empty-state-text">Comienza creando tu primera clase</p>
            <a href="{{ route('admin.clases.create') }}" class="btn btn-primary-custom mt-3 px-4">
                <i class="fas fa-plus me-2"></i>Crear primera clase
            </a>
        </div>
    </td>
</tr>
@endforelse

@push('styles')
<style>
    .badge-clase {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }
    
    .badge-video {
        background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
        color: #2e7d32;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s ease;
    }
    
    .badge-video:hover {
        background: linear-gradient(135deg, #2e7d32, #1b5e20);
        color: white;
        transform: translateY(-2px);
    }
    
    .badge-material {
        background: linear-gradient(135deg, #e3f2fd, #bbdef5);
        color: #1565c0;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s ease;
    }
    
    .badge-material:hover {
        background: linear-gradient(135deg, #1565c0, #0d47a1);
        color: white;
        transform: translateY(-2px);
    }
    
    .badge-sin-video {
        background: #ffebee;
        color: #c62828;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .badge-sin-material {
        background: #fff3e0;
        color: #e65100;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    body.dark-mode .badge-video {
        background: rgba(46, 125, 50, 0.2);
        color: #81c784;
    }
    
    body.dark-mode .badge-video:hover {
        background: #2e7d32;
        color: white;
    }
    
    body.dark-mode .badge-material {
        background: rgba(21, 101, 192, 0.2);
        color: #64b5f6;
    }
    
    body.dark-mode .badge-material:hover {
        background: #1565c0;
        color: white;
    }
    
    body.dark-mode .badge-sin-video {
        background: rgba(198, 40, 40, 0.2);
        color: #ef9a9a;
    }
    
    body.dark-mode .badge-sin-material {
        background: rgba(230, 81, 0, 0.2);
        color: #ffa726;
    }
    
    body.dark-mode .badge-clase {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }
</style>
@endpush