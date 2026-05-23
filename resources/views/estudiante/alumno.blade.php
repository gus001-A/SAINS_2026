@extends('estudiante.layouts.estudiante')

@section('title', 'Dashboard | SAINS')

@section('content')
<div class="container-fluid">
    <!-- Tarjetas de estadísticas -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-value" id="totalExamenes">0</div>
                <div class="stat-label">Exámenes Realizados</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-video"></i>
                </div>
                <div class="stat-value" id="leccionesVistas">0</div>
                <div class="stat-label">Lecciones Completadas</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-value" id="mejorPuntaje">0</div>
                <div class="stat-label">Mejor Puntaje</div>
            </div>
        </div>
    </div>
    
    <!-- Próximos pasos -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="stat-card">
                <h4 class="mb-3 fw-bold">📋 Próximos pasos</h4>
                @if(!isset($estudiante) || !$estudiante)
                    <div class="alert alert-warning alert-custom">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Completa tu perfil para comenzar. 
                        <a href="#" onclick="abrirModalCompletarPerfil()" class="alert-link fw-bold">Completar perfil ahora</a>
                    </div>
                @elseif(!$estudiante->plan_activo)
                    <div class="alert alert-warning alert-custom">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Aún no tienes un plan activo. 
                        <a href="#" onclick="mostrarModalPago()" class="alert-link fw-bold">Adquiere tu plan aquí</a>
                    </div>
                @else
                    <div class="alert alert-success alert-custom">
                        <i class="fas fa-check-circle me-2"></i>
                        ¡Tienes un plan activo! Continúa con tu preparación.
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Botones rápidos -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="stat-card">
                <h4 class="mb-3 fw-bold">⚡ Acciones rápidas</h4>
                <div class="row g-3">
                    <div class="col-md-4">
                        <button class="btn-gradient w-100" onclick="iniciarSimulador()">
                            <i class="fas fa-play me-2"></i> Iniciar Simulador
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button class="btn-outline-gradient w-100" onclick="verHistorialExamenes()">
                            <i class="fas fa-history me-2"></i> Ver Historial
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button class="btn-outline-gradient w-100" onclick="mostrarModalPago()">
                            <i class="fas fa-shopping-cart me-2"></i> Adquirir Plan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function cargarDatosDashboard() {
        fetch('{{ route("alumno.api.estadisticas") }}', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalExamenes').innerText = data.total_examenes || 0;
                document.getElementById('leccionesVistas').innerText = data.lecciones_vistas || 0;
                document.getElementById('mejorPuntaje').innerText = data.mejor_puntaje || 0;
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function iniciarSimulador() {
        @if(!isset($estudiante) || !$estudiante)
            Swal.fire({
                title: '⚠️ Perfil incompleto',
                text: 'Primero debes completar tu perfil',
                icon: 'warning',
                confirmButtonText: 'Completar perfil'
            }).then(() => {
                abrirModalCompletarPerfil();
            });
        @elseif(!$estudiante->plan_activo)
            Swal.fire({
                title: '📚 Plan requerido',
                text: 'Necesitas un plan activo para acceder al simulador',
                icon: 'warning',
                confirmButtonText: 'Adquirir plan',
                showCancelButton: true,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) mostrarModalPago();
            });
        @else
            window.location.href = '{{ route("alumno.simulador") }}';
        @endif
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        cargarDatosDashboard();
        
        // Cargar últimos exámenes si es necesario
        if (document.getElementById('listaUltimosExamenes')) {
            cargarUltimosExamenes();
        }
    });
    
    function cargarUltimosExamenes() {
        const container = document.getElementById('listaUltimosExamenes');
        if (!container) return;
        
        fetch('{{ route("alumno.api.ultimos-examenes") }}', {
            headers: { 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.examenes && data.examenes.length > 0) {
                container.innerHTML = data.examenes.map(examen => `
                    <div class="list-group-item-custom d-flex justify-content-between align-items-center">
                        <div>
                            <strong>📅 ${examen.fecha}</strong><br>
                            <small>🎯 Calificación: ${examen.calificacion}%</small>
                        </div>
                        <span class="badge ${examen.calificacion >= 70 ? 'bg-success' : 'bg-warning'} p-2">
                            ${examen.calificacion >= 70 ? '✅ Aprobado' : '📚 Mejorable'}
                        </span>
                    </div>
                `).join('');
            } else {
                container.innerHTML = '<div class="list-group-item-custom text-muted">📭 No has realizado ningún examen aún</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = '<div class="list-group-item-custom text-danger">❌ Error al cargar los exámenes</div>';
        });
    }
</script>
@endpush