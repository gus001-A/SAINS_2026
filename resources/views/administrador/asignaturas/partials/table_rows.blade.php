@forelse($asignaturas as $asignatura)
<tr class="fade-in">
    <td class="fw-semibold">
        <span class="text-muted">#</span>{{ $asignatura->id }}
    </td>
    <td>
        <div class="d-flex align-items-center gap-3">
            <div class="materia-icon rounded-3 p-2" style="background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));">
                <i class="fas fa-book text-primary"></i>
            </div>
            <div>
                <span class="fw-semibold">{{ $asignatura->nombre }}</span>
            </div>
        </div>
    </td>
    <td class="text-center">
        @php
            $totalClases = $asignatura->clases()->count();
        @endphp
        <div class="d-flex justify-content-center">
            @if($totalClases > 0)
                <span class="badge bg-info rounded-pill px-3 py-2">
                    <i class="fas fa-video me-1"></i> {{ $totalClases }}
                </span>
            @else
                <span class="badge bg-secondary rounded-pill px-3 py-2 opacity-50">
                    <i class="fas fa-video me-1"></i> 0
                </span>
            @endif
        </div>
    </td>
    <td class="text-center">
        @php
            $totalCarreras = $asignatura->carrerasComoMateria1()->count() +
                            $asignatura->carrerasComoMateria2()->count() +
                            $asignatura->carrerasComoMateria3()->count();
        @endphp
        <div class="d-flex justify-content-center">
            @if($totalCarreras > 0)
                <span class="badge bg-warning rounded-pill px-3 py-2">
                    <i class="fas fa-graduation-cap me-1"></i> {{ $totalCarreras }}
                </span>
            @else
                <span class="badge bg-secondary rounded-pill px-3 py-2 opacity-50">
                    <i class="fas fa-graduation-cap me-1"></i> 0
                </span>
            @endif
        </div>
    </td>
    <td class="text-center">
        <div class="d-flex justify-content-center gap-2">
            <button type="button" 
                    onclick="editMateria({{ $asignatura->id }}, '{{ addslashes($asignatura->nombre) }}')" 
                    class="btn btn-editar btn-accion"
                    title="Editar materia">
                <i class="fas fa-edit me-1"></i>Editar
            </button>
            <button type="button" 
                    onclick="deleteMateria({{ $asignatura->id }}, '{{ addslashes($asignatura->nombre) }}')" 
                    class="btn btn-eliminar btn-accion"
                    title="Eliminar materia">
                <i class="fas fa-trash-alt me-1"></i>Eliminar
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center py-5">
        <div class="empty-state">
            <div class="empty-icon mb-3">
                <i class="fas fa-book-open fa-4x text-muted opacity-25"></i>
            </div>
            <h6 class="text-muted mb-2">No hay materias registradas</h6>
            <p class="text-muted small mb-3">Comienza creando tu primera materia</p>
            <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#createMateriaModal">
                <i class="fas fa-plus me-2"></i>Crear primera materia
            </button>
        </div>
    </td>
</tr>
@endforelse

@push('styles')
<style>
    .materia-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    tr:hover .materia-icon {
        transform: scale(1.05);
    }
    
    .empty-state {
        text-align: center;
        padding: 2rem;
    }
    
    .empty-icon {
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    
    .badge.bg-info {
        background: linear-gradient(135deg, #06b6d4, #0891b2) !important;
    }
    
    .badge.bg-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706) !important;
    }
    
    .badge.bg-secondary {
        background: linear-gradient(135deg, #64748b, #475569) !important;
    }
    
    /* Dark mode para el partial */
    body.dark-mode .materia-icon {
        background: rgba(102, 126, 234, 0.15) !important;
    }
    
    body.dark-mode .badge.bg-info {
        background: linear-gradient(135deg, #0891b2, #06b6d4) !important;
    }
    
    body.dark-mode .badge.bg-warning {
        background: linear-gradient(135deg, #d97706, #f59e0b) !important;
    }
</style>
@endpush