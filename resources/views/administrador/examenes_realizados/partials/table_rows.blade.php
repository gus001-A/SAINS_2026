@forelse($examenes as $examen)
@php
    $nombreCompleto = '';
    if ($examen->estudianteRel) {
        $nombreCompleto = trim($examen->estudianteRel->nombre . ' ' . 
                               $examen->estudianteRel->paterno . ' ' . 
                               $examen->estudianteRel->materno);
    }
    
    $calif = $examen->calificacion ?? 0;
    // CORRECCIÓN: Excelente >=80, Aprobado 60-79, Reprobado <60
    if ($calif >= 80) {
        $gradeClass = 'grade-excellent';
        $gradeIcon = 'fa-star';
        $gradeText = 'Excelente';
    } elseif ($calif >= 60) {
        $gradeClass = 'grade-good';
        $gradeIcon = 'fa-smile';
        $gradeText = 'Aprobado';
    } else {
        $gradeClass = 'grade-poor';
        $gradeIcon = 'fa-frown';
        $gradeText = 'Reprobado';
    }
    
    $intento = $examen->intento ?? 1;
    $attemptClass = $intento == 1 ? 'attempt-1' : ($intento == 2 ? 'attempt-2' : 'attempt-3');
    
    $tipoExamen = $examen->examenGenerado?->tipo_examen ?? '';
    $tipoClass = '';
    $tipoIcon = '';
    $tipoLabel = '';
    
    if ($tipoExamen == 'Materia') {
        $tipoClass = 'badge-materia';
        $tipoIcon = 'fa-book';
        $tipoLabel = '📚 Materia';
    } elseif ($tipoExamen == 'Curso') {
        $tipoClass = 'badge-curso';
        $tipoIcon = 'fa-graduation-cap';
        $tipoLabel = '🎓 Curso';
    } elseif ($tipoExamen == 'Simulación') {
        $tipoClass = 'badge-simulacion';
        $tipoIcon = 'fa-chart-line';
        $tipoLabel = '🎯 Simulación';
    } else {
        $tipoClass = 'badge-secondary';
        $tipoIcon = 'fa-question-circle';
        $tipoLabel = $tipoExamen ?: '📄 Desconocido';
    }
    
    $fechaFormateada = 'Fecha no disponible';
    if (!empty($examen->fecha_inicio)) {
        try {
            $fechaObj = \Carbon\Carbon::parse($examen->fecha_inicio);
            $fechaFormateada = $fechaObj->format('d/m/Y');
            if (!empty($examen->hora_inicio)) {
                $horaMostrar = strlen($examen->hora_inicio) >= 5 ? substr($examen->hora_inicio, 0, 5) : $examen->hora_inicio;
                $fechaFormateada .= ' ' . $horaMostrar;
            }
        } catch (\Exception $e) {
            $fechaFormateada = 'Fecha inválida';
        }
    }
    
    $iniciales = '';
    $palabras = explode(' ', trim($nombreCompleto));
    foreach($palabras as $palabra) {
        if (!empty($palabra)) {
            $iniciales .= strtoupper(substr($palabra, 0, 1));
        }
        if (strlen($iniciales) >= 2) break;
    }
    if (empty($iniciales)) $iniciales = '?';
@endphp
<tr class="animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.03 }}s;">
    <!-- Columna 1: Estudiante -->
    <td class="px-4 py-3">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-estudiante">
                {{ $iniciales }}
            </div>
            <div class="d-flex flex-column">
                <span class="fw-semibold">{{ Str::limit($nombreCompleto, 30) }}</span>
                <small class="text-muted">ID: {{ $examen->estudiante }}</small>
            </div>
        </div>
    </td>
    
    <!-- Columna 2: Examen + Tipo -->
    <td class="px-4 py-3">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge-tipo {{ $tipoClass }}">
                    <i class="fas {{ $tipoIcon }} fa-xs me-1"></i>
                    {{ $tipoLabel }}
                </span>
            </div>
            <span class="fw-medium">{{ Str::limit($examen->examenGenerado->titulo ?? 'N/A', 40) }}</span>
            @if($examen->examenGenerado)
                <small class="text-muted mt-1">
                    <i class="fas fa-calendar-alt fa-xs me-1"></i>
                    Creado: {{ $examen->examenGenerado->created_at?->format('d/m/Y') ?? 'N/A' }}
                </small>
            @endif
        </div>
    </td>
    
    <!-- Columna 3: Calificación -->
    <td class="px-4 py-3 text-center">
        <div class="grade-badge {{ $gradeClass }}" data-tooltip="Calificación: {{ number_format($calif, 1) }}% - {{ $gradeText }}">
            <i class="fas {{ $gradeIcon }} fa-xs"></i>
            <span>{{ number_format($calif, 1) }}%</span>
            <span style="font-size: 0.65rem;">{{ $gradeText }}</span>
        </div>
    </td>
    
    <!-- Columna 4: Intento -->
    <td class="px-4 py-3 text-center">
        <div class="attempt-badge {{ $attemptClass }}" data-tooltip="Intento {{ $intento }}">
            {{ $intento }}
        </div>
    </td>
    
    <!-- Columna 5: Fecha -->
    <td class="px-4 py-3">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-calendar-alt text-muted fa-sm"></i>
                <span>{{ $fechaFormateada }}</span>
            </div>
            @if($examen->fecha_fin)
                <small class="text-muted mt-1">
                    <i class="fas fa-clock text-muted fa-xs me-1"></i>
                    Fin: {{ \Carbon\Carbon::parse($examen->fecha_fin)->format('d/m/Y H:i') }}
                </small>
            @endif
        </div>
    </td>
    
    <!-- Columna 6: Tiempo -->
    <td class="px-4 py-3 text-center">
        @if($examen->tiempo)
            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-3" data-tooltip="Tiempo total empleado">
                <i class="fas fa-hourglass-half me-1"></i>
                {{ $examen->tiempo }}
            </span>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    
    <!-- Columna 7: Acciones -->
    <td class="px-4 py-3 text-center">
        <div class="d-flex justify-content-center gap-2">
            <a href="{{ route('admin.examenes-realizados.show', $examen->id) }}" 
               class="btn-accion btn-ver" 
               title="Ver detalles del examen">
                <i class="fas fa-eye"></i>
                <span>Ver</span>
            </a>
            <button type="button" 
                    onclick="confirmarEliminar('{{ route('admin.examenes-realizados.destroy', $examen->id) }}')"
                    class="btn-accion btn-eliminar" 
                    title="Eliminar examen">
                <i class="fas fa-trash-alt"></i>
                <span>Eliminar</span>
            </button>
        </div>
    </td>
</tr>

@empty
<tr>
    <td colspan="7" class="text-center py-5">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <h5 class="empty-state-title">No hay exámenes realizados</h5>
            <p class="empty-state-text">
                @if(request('estudiante') || request('tipo_examen') || request('calificacion') || request('intento') || request('fecha_desde'))
                    No se encontraron resultados con los filtros aplicados.
                    <br>
                    <a href="{{ route('admin.examenes-realizados.index') }}" class="btn btn-link mt-2">
                        <i class="fas fa-undo-alt me-1"></i>Limpiar filtros
                    </a>
                @else
                    Los exámenes aparecerán aquí cuando los estudiantes los completen.
                @endif
            </p>
        </div>
    </td>
</tr>
@endforelse

@if($examenes->hasPages())
    <div class="pagination-container d-none">
        {{ $examenes->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
@endif