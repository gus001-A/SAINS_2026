@extends('administrador.layouts.master')

@section('title', 'Editar Clase - SAINS')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="display-5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                    <i class="fas fa-edit me-3"></i>Editar Clase
                </h1>
                <p class="text-muted">Modifique la información de la clase, video, material y recursos adicionales</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.clases.show', $clase->id) }}" class="btn btn-info rounded-pill px-4">
                    <i class="fas fa-eye me-2"></i>Ver Clase
                </a>
                <a href="{{ route('admin.clases.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i>Volver al listado
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-modern p-4 p-lg-5">
            <form action="{{ route('admin.clases.update', $clase->id) }}" method="POST" id="claseForm" novalidate>
                @csrf
                @method('PUT')
                
                <!-- Stepper Indicator -->
                <div class="stepper-wrapper mb-5">
                    <div class="stepper-item active" data-step="1">
                        <div class="step-counter">1</div>
                        <div class="step-name">Información Básica</div>
                    </div>
                    <div class="stepper-item" data-step="2">
                        <div class="step-counter">2</div>
                        <div class="step-name">Video y Material</div>
                    </div>
                    <div class="stepper-item" data-step="3">
                        <div class="step-counter">3</div>
                        <div class="step-name">Recursos Adicionales</div>
                    </div>
                </div>
                
                <!-- Step 1: Información Básica -->
                <div class="step-content" data-step="1">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));">
                            <i class="fas fa-info-circle fa-2x" style="color: #4361ee;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Información Básica</h4>
                            <p class="text-muted small mb-0">Datos principales de la clase</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-book text-primary me-1"></i> Materia <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-book text-primary"></i></span>
                                <select name="id_asignatura" id="id_asignatura" class="form-select @error('id_asignatura') is-invalid @enderror" required>
                                    <option value="">Seleccione una materia</option>
                                    @foreach($asignaturas as $asignatura)
                                        <option value="{{ $asignatura->id }}" {{ old('id_asignatura', $clase->id_asignatura) == $asignatura->id ? 'selected' : '' }}>
                                            {{ $asignatura->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('id_asignatura')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-hashtag text-primary me-1"></i> Número de Clase <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-sort-numeric-up text-primary"></i></span>
                                <input type="number" name="num_clase" id="num_clase" 
                                       class="form-control @error('num_clase') is-invalid @enderror" 
                                       value="{{ old('num_clase', $clase->num_clase) }}" placeholder="Ej: 1, 2, 3..." min="1" required>
                            </div>
                            @error('num_clase')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-info-circle text-info me-1"></i>Si cambia la materia, el número se ajustará automáticamente
                            </small>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-heading text-primary me-1"></i> Nombre de la Clase <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-tag text-primary"></i></span>
                                <input type="text" name="nombre_clase" id="nombre_clase" 
                                       class="form-control @error('nombre_clase') is-invalid @enderror" 
                                       value="{{ old('nombre_clase', $clase->nombre_clase) }}" placeholder="Ej: Introducción a la Física" required>
                            </div>
                            @error('nombre_clase')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: Video y Material Principal -->
                <div class="step-content" data-step="2" style="display: none;">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(247,37,133,0.1), rgba(114,9,183,0.1));">
                            <i class="fas fa-video fa-2x" style="color: #f72585;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Video y Material Principal</h4>
                            <p class="text-muted small mb-0">Seleccione un video del catálogo o agregue un enlace personalizado</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="card bg-light border-0 rounded-3 p-3">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="fas fa-database text-primary"></i>
                                    <h6 class="mb-0 fw-semibold">Seleccionar video del catálogo</h6>
                                </div>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-transparent"><i class="fas fa-database text-primary"></i></span>
                                    <select name="video_selected" id="video_selected" class="form-select">
                                        <option value="">-- Seleccione un video --</option>
                                        @foreach($videos as $video)
                                            <option value="{{ $video->link }}" 
                                                    data-materia="{{ $video->materia }}"
                                                    data-tema="{{ $video->tema }}"
                                                    data-titulo="{{ $video->titulo }}"
                                                    data-duracion="{{ $video->duracion }}"
                                                    {{ old('video_selected', $clase->link) == $video->link ? 'selected' : '' }}>
                                                [{{ $video->materia }}] {{ $video->tema }} - {{ $video->titulo }} ({{ $video->duracion ?? 'Sin duración' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-muted mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Al seleccionar un video, el enlace se cargará automáticamente en el campo de abajo.
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="position-relative">
                                <div class="text-center my-3">
                                    <span class="bg-white px-3 text-muted">o</span>
                                    <hr class="my-0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="card bg-light border-0 rounded-3 p-3">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="fab fa-youtube text-danger"></i>
                                    <h6 class="mb-0 fw-semibold">Enlace personalizado</h6>
                                </div>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-transparent"><i class="fab fa-youtube text-danger"></i></span>
                                    <input type="url" name="link" id="link" 
                                           class="form-control @error('link') is-invalid @enderror" 
                                           value="{{ old('link', $clase->link) }}" 
                                           placeholder="https://www.youtube.com/watch?v=... o https://youtu.be/...">
                                </div>
                                @error('link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Puedes pegar directamente el enlace del video (YouTube, Vimeo, etc.)
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-file-alt text-info me-1"></i> Material de Apoyo Principal
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-link text-info"></i></span>
                                <input type="url" name="url" id="url" 
                                       class="form-control @error('url') is-invalid @enderror" 
                                       value="{{ old('url', $clase->url) }}" 
                                       placeholder="https://drive.google.com/... o enlace externo">
                            </div>
                            @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Enlace a material complementario principal - Opcional</small>
                        </div>
                    </div>
                </div>
                
                <!-- Step 3: Recursos Adicionales -->
                <div class="step-content" data-step="3" style="display: none;">
                    <div class="d-flex align-items-center justify-content-between gap-3 mb-4 pb-2 border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(16,185,129,0.1), rgba(5,150,105,0.1));">
                                <i class="fas fa-paperclip fa-2x" style="color: #10b981;"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold">Recursos Adicionales</h4>
                                <p class="text-muted small mb-0">Agregue materiales complementarios (PDF, presentaciones, podcasts, etc.)</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success rounded-pill px-4" id="btnAgregarRecurso">
                            <i class="fas fa-plus me-2"></i>Agregar Recurso
                        </button>
                    </div>
                    
                    <div id="recursosContainer"></div>
                    
                    <div id="noRecursosMsg" class="text-center text-muted py-5" style="display: none;">
                        <i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i>
                        <p>No hay recursos adicionales agregados</p>
                        <small>Haga clic en "Agregar Recurso" para añadir materiales complementarios</small>
                    </div>
                </div>
                
                <!-- Botones de navegación -->
                <div class="d-flex justify-content-between gap-3 mt-5 pt-4 border-top">
                    <button type="button" class="btn btn-secondary px-5 py-3 rounded-pill" id="prevBtn" style="display: none;">
                        <i class="fas fa-arrow-left me-2"></i> Anterior
                    </button>
                    <button type="button" class="btn btn-primary px-5 py-3 rounded-pill" id="nextBtn">
                        Siguiente <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                    <button type="submit" class="btn btn-save px-5 py-3 rounded-pill" id="submitBtn" style="display: none;">
                        <i class="fas fa-save me-2"></i> Actualizar Clase
                    </button>
                    <a href="{{ route('admin.clases.index') }}" class="btn btn-cancel px-4 py-3 rounded-pill">
                        <i class="fas fa-times me-2"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stepper-wrapper {
        display: flex;
        justify-content: space-between;
        position: relative;
        background: transparent;
    }
    .stepper-wrapper::before {
        content: '';
        position: absolute;
        top: 25px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e0e0e0;
        z-index: 1;
    }
    .stepper-item {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }
    .step-counter {
        width: 50px;
        height: 50px;
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: bold;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }
    .stepper-item.active .step-counter {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-color: #667eea;
        color: white;
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(102,126,234,0.3);
    }
    .stepper-item.completed .step-counter {
        background: #4caf50;
        border-color: #4caf50;
        color: white;
    }
    .step-name {
        font-size: 0.85rem;
        color: #666;
        font-weight: 500;
    }
    .stepper-item.active .step-name {
        color: #667eea;
        font-weight: 600;
    }
    .form-label {
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    .form-control, .form-select, .input-group-lg .form-control, .input-group-lg .form-select {
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
    }
    .input-group-text {
        border-radius: 12px 0 0 12px;
        border: 2px solid #e0e0e0;
        border-right: none;
        background: white;
    }
    .input-group .form-control, .input-group .form-select {
        border-left: none;
    }
    .card-modern {
        background: white;
        border-radius: 20px;
        transition: transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102,126,234,0.4);
        color: white;
    }
    .btn-cancel {
        background: #6c757d;
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-cancel:hover {
        background: #dc3545;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(220,53,69,0.3);
        color: white;
    }
    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        font-weight: 600;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102,126,234,0.4);
    }
    .btn-secondary {
        background: #6c757d;
        border: none;
        font-weight: 600;
    }
    .btn-secondary:hover {
        transform: translateY(-2px);
        background: #5a6268;
    }
    .btn-info {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(14,165,233,0.4);
        color: white;
    }
    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16,185,129,0.4);
    }
    .recurso-item {
        background: #f8f9fa;
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    .recurso-item:hover {
        background: white;
        border-color: #667eea;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transform: translateX(5px);
    }
    .step-content {
        animation: fadeIn 0.5s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endpush

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 3;
    let recursoIndex = {{ $clase->recursos->count() }};
    const asignaturaOriginal = {{ $clase->id_asignatura }};
    
    const tiposRecursos = {
        'pdf': '📄 PDF',
        'video_youtube': '🎬 Video YouTube',
        'video_vimeo': '🎬 Video Vimeo',
        'video_drive': '☁️ Video Drive',
        'presentacion': '📊 Presentación',
        'documento': '📝 Documento',
        'podcast': '🎙️ Podcast',
        'imagen': '🖼️ Imagen',
        'enlace': '🔗 Enlace externo',
        'otros': '📁 Otros'
    };
    
    // Evento para auto-completar link del video
    const videoSelect = document.getElementById('video_selected');
    if (videoSelect) {
        videoSelect.addEventListener('change', function() {
            const linkInput = document.getElementById('link');
            if (this.value) {
                linkInput.value = this.value;
            }
        });
    }
    
    // Actualizar número automáticamente al cambiar materia
    function actualizarNumeroPorMateria() {
        const nuevaAsignaturaId = document.getElementById('id_asignatura').value;
        if (!nuevaAsignaturaId) return;
        
        if (parseInt(nuevaAsignaturaId) !== asignaturaOriginal) {
            fetch('/administrador/clases/siguiente-numero/' + nuevaAsignaturaId, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('num_clase').value = data.siguiente_numero;
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
    
    const asignaturaSelect = document.getElementById('id_asignatura');
    if (asignaturaSelect) {
        asignaturaSelect.addEventListener('change', actualizarNumeroPorMateria);
    }
    
    // Función para agregar recurso
    function agregarRecurso(titulo, tipo, url, descripcion, id) {
        titulo = titulo || '';
        tipo = tipo || 'pdf';
        url = url || '';
        descripcion = descripcion || '';
        id = id || null;
        
        const container = document.getElementById('recursosContainer');
        const noRecursosMsg = document.getElementById('noRecursosMsg');
        
        if (noRecursosMsg) {
            noRecursosMsg.style.display = 'none';
        }
        
        let tiposHtml = '';
        for (const [key, value] of Object.entries(tiposRecursos)) {
            const selected = (tipo === key) ? 'selected' : '';
            tiposHtml += `<option value="${key}" ${selected}>${value}</option>`;
        }
        
        const html = `
            <div class="recurso-item" data-id="${id || ''}" data-index="${recursoIndex}">
                <input type="hidden" name="recursos[${recursoIndex}][id]" value="${id || ''}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Título del recurso *</label>
                        <input type="text" name="recursos[${recursoIndex}][titulo]" class="form-control" value="${escapeHtml(titulo)}" placeholder="Ej: Guía de estudio" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Tipo de recurso *</label>
                        <select name="recursos[${recursoIndex}][tipo]" class="form-select" required>${tiposHtml}</select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">URL / Enlace *</label>
                        <input type="url" name="recursos[${recursoIndex}][url]" class="form-control" value="${escapeHtml(url)}" placeholder="https://..." required>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label fw-semibold small">&nbsp;</label>
                        <button type="button" class="btn btn-danger w-100 eliminar-recurso" title="Eliminar recurso"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Descripción (opcional)</label>
                        <textarea name="recursos[${recursoIndex}][descripcion]" class="form-control" rows="2" placeholder="Breve descripción">${escapeHtml(descripcion)}</textarea>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
        recursoIndex++;
        
        // Evento para eliminar recurso
        document.querySelectorAll('.eliminar-recurso').forEach(btn => {
            if (!btn.hasAttribute('data-listener')) {
                btn.setAttribute('data-listener', 'true');
                btn.addEventListener('click', function() {
                    const item = this.closest('.recurso-item');
                    const rid = item.getAttribute('data-id');
                    if (rid && rid !== '') {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'recursos_eliminar[]';
                        input.value = rid;
                        document.getElementById('claseForm').appendChild(input);
                    }
                    item.remove();
                    if (document.querySelectorAll('.recurso-item').length === 0 && noRecursosMsg) {
                        noRecursosMsg.style.display = 'block';
                    }
                });
            }
        });
    }
    
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }
    
    // Validar paso actual
    function validarPaso(step) {
        if (step === 1) {
            const idAsignatura = document.getElementById('id_asignatura').value;
            const numClase = document.getElementById('num_clase').value.trim();
            const nombreClase = document.getElementById('nombre_clase').value.trim();
            
            if (!idAsignatura) {
                Swal.fire('Error', 'Debe seleccionar una materia', 'error');
                return false;
            }
            if (!numClase) {
                Swal.fire('Error', 'Debe ingresar el número de clase', 'error');
                return false;
            }
            if (!nombreClase) {
                Swal.fire('Error', 'Debe ingresar el nombre de la clase', 'error');
                return false;
            }
        }
        return true;
    }
    
    // Mostrar paso
    function mostrarPaso(step) {
        // Ocultar todos los steps
        document.querySelectorAll('.step-content').forEach(content => {
            content.style.display = 'none';
        });
        // Mostrar el step actual
        document.querySelector(`.step-content[data-step="${step}"]`).style.display = 'block';
        
        // Actualizar stepper
        document.querySelectorAll('.stepper-item').forEach((item, index) => {
            const stepNum = index + 1;
            if (stepNum === step) {
                item.classList.add('active');
                item.classList.remove('completed');
            } else if (stepNum < step) {
                item.classList.add('completed');
                item.classList.remove('active');
            } else {
                item.classList.remove('active', 'completed');
            }
        });
        
        // Mostrar/ocultar botones
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');
        
        if (step === 1) {
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'block';
            submitBtn.style.display = 'none';
        } else if (step === totalSteps) {
            prevBtn.style.display = 'block';
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'block';
        } else {
            prevBtn.style.display = 'block';
            nextBtn.style.display = 'block';
            submitBtn.style.display = 'none';
        }
    }
    
    // Event Listeners
    document.getElementById('nextBtn').addEventListener('click', function() {
        if (validarPaso(currentStep)) {
            currentStep++;
            mostrarPaso(currentStep);
        }
    });
    
    document.getElementById('prevBtn').addEventListener('click', function() {
        if (currentStep > 1) {
            currentStep--;
            mostrarPaso(currentStep);
        }
    });
    
    document.getElementById('btnAgregarRecurso').addEventListener('click', function() {
        agregarRecurso('', 'pdf', '', '', null);
    });
    
    // Submit del formulario
    document.getElementById('claseForm').addEventListener('submit', function(e) {
        if (!validarPaso(1)) {
            e.preventDefault();
            return false;
        }
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Actualizando...';
    });
    
    // Inicializar
    mostrarPaso(1);
    
    // Cargar recursos existentes
    const recursosExistentes = @json($clase->recursos);
    if (recursosExistentes.length > 0) {
        recursosExistentes.forEach(recurso => {
            agregarRecurso(recurso.titulo, recurso.tipo, recurso.url, recurso.descripcion, recurso.id);
        });
    } else {
        const noRecursosMsg = document.getElementById('noRecursosMsg');
        if (noRecursosMsg) noRecursosMsg.style.display = 'block';
    }
</script>
@endpush