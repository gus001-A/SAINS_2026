@extends('administrador.layouts.master')

@section('title', 'Nuevo Video - SAINS')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="display-5 fw-bold" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                    <i class="fas fa-video me-3"></i>Nuevo Video
                </h1>
                <p class="text-muted">Registre un nuevo video educativo para el curso</p>
            </div>
            <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-danger btn-lg rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>Volver al listado
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-modern p-4 p-lg-5">
            <form action="{{ route('admin.videos.store') }}" method="POST" id="videoForm" novalidate>
                @csrf
                
                <!-- Stepper Indicator -->
                <div class="stepper-wrapper mb-5">
                    <div class="stepper-item active" data-step="1">
                        <div class="step-counter">1</div>
                        <div class="step-name">Información General</div>
                    </div>
                    <div class="stepper-item" data-step="2">
                        <div class="step-counter">2</div>
                        <div class="step-name">Configuración del Video</div>
                    </div>
                    <div class="stepper-item" data-step="3">
                        <div class="step-counter">3</div>
                        <div class="step-name">Plan y Duración</div>
                    </div>
                </div>
                
                <!-- Step 1: Información General -->
                <div class="step-content" data-step="1">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(239,68,68,0.1), rgba(220,38,38,0.1));">
                            <i class="fas fa-info-circle fa-2x" style="color: #ef4444;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Información General</h4>
                            <p class="text-muted small mb-0">Datos básicos del video educativo</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-book text-danger me-1"></i> Materia <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-book"></i></span>
                                <select name="materia" id="materia" class="form-select @error('materia') is-invalid @enderror" required>
                                    <option value="">Seleccione una materia</option>
                                    @foreach($asignaturas as $asignatura)
                                        <option value="{{ $asignatura->nombre }}" {{ old('materia') == $asignatura->nombre ? 'selected' : '' }}>
                                            {{ $asignatura->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('materia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Seleccione la materia a la que pertenece el video</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-tag text-danger me-1"></i> Tema <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-tag"></i></span>
                                <input type="text" name="tema" id="tema" 
                                       class="form-control @error('tema') is-invalid @enderror" 
                                       value="{{ old('tema') }}" placeholder="Ej: Álgebra, Gramática..." required>
                            </div>
                            @error('tema')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Tema específico del video</small>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-heading text-danger me-1"></i> Título del Video <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-heading"></i></span>
                                <input type="text" name="titulo" id="titulo" 
                                       class="form-control @error('titulo') is-invalid @enderror" 
                                       value="{{ old('titulo') }}" placeholder="Título descriptivo del video" required>
                            </div>
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Ej: "Introducción al Álgebra", "Clase de Español - Sustantivos"</small>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: Configuración del Video -->
                <div class="step-content" data-step="2" style="display: none;">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(239,68,68,0.1), rgba(220,38,38,0.1));">
                            <i class="fab fa-youtube fa-2x" style="color: #ef4444;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Configuración del Video</h4>
                            <p class="text-muted small mb-0">Seleccione la plataforma y proporcione el enlace o ID</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-globe text-danger me-1"></i> Plataforma <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex gap-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="platform" id="platformYoutube" value="youtube" checked>
                                    <label class="form-check-label fw-semibold" for="platformYoutube">
                                        <i class="fab fa-youtube text-danger fa-lg me-2"></i> YouTube
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="platform" id="platformVimeo" value="vimeo">
                                    <label class="form-check-label fw-semibold" for="platformVimeo">
                                        <i class="fab fa-vimeo text-primary fa-lg me-2"></i> Vimeo
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12" id="youtubeInput">
                            <label class="form-label fw-semibold">
                                <i class="fab fa-youtube text-danger me-1"></i> URL de YouTube <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fab fa-youtube text-danger"></i></span>
                                <input type="url" name="link" id="youtubeUrl" 
                                       class="form-control @error('link') is-invalid @enderror" 
                                       value="{{ old('link') }}" placeholder="https://www.youtube.com/watch?v=XXXXXXXXXXX">
                            </div>
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Pega la URL completa de YouTube</small>
                        </div>
                        
                        <div class="col-12" id="vimeoInput" style="display: none;">
                            <label class="form-label fw-semibold">
                                <i class="fab fa-vimeo text-primary me-1"></i> ID de Vimeo <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fab fa-vimeo text-primary"></i></span>
                                <input type="text" id="vimeoId" 
                                       class="form-control" placeholder="Ej: 123456789">
                            </div>
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Solo el ID del video de Vimeo</small>
                            <input type="hidden" name="link" id="vimeoHiddenUrl">
                        </div>
                        
                        <div class="col-12" id="previewContainer" style="display: none;">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-eye text-danger me-1"></i> Vista Previa
                            </label>
                            <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-sm">
                                <iframe id="videoPreview" src="" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 3: Plan y Duración -->
                <div class="step-content" data-step="3" style="display: none;">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(239,68,68,0.1), rgba(220,38,38,0.1));">
                            <i class="fas fa-crown fa-2x" style="color: #ef4444;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">Plan y Duración</h4>
                            <p class="text-muted small mb-0">Configure el tipo de plan y duración del video</p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-clock text-danger me-1"></i> Duración del Video
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent"><i class="fas fa-clock"></i></span>
                                <input type="text" name="duracion" id="duracion" 
                                       class="form-control @error('duracion') is-invalid @enderror" 
                                       value="{{ old('duracion') }}" placeholder="MM:SS o HH:MM:SS">
                            </div>
                            @error('duracion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Ej: 15:30 para 15 minutos con 30 segundos</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-gratipay text-danger me-1"></i> Tipo de Plan
                            </label>
                            <div class="form-check form-switch mt-2">
                                <input type="checkbox" class="form-check-input" id="plan" name="plan" value="1" 
                                       style="width: 4em; height: 2em; cursor: pointer;" {{ old('plan') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold ms-2" for="plan">
                                    <span id="planText">Premium</span>
                                </label>
                            </div>
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Active el switch para plan gratuito, desactivado para premium</small>
                        </div>
                        
                        <div class="col-12">
                            <div class="alert alert-info rounded-3 mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Información:</strong> Los videos premium solo estarán disponibles para usuarios con suscripción activa. Los videos gratuitos estarán disponibles para todos los usuarios.
                            </div>
                        </div>
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
                        <i class="fas fa-save me-2"></i> Guardar Video
                    </button>
                    <a href="{{ route('admin.videos.index') }}" class="btn btn-cancel px-4 py-3 rounded-pill">
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
    /* Stepper Styles */
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
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border-color: #ef4444;
        color: white;
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(239,68,68,0.3);
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
        color: #ef4444;
        font-weight: 600;
    }
    
    /* Form Styles */
    .form-label {
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    
    .form-control-lg, .form-select-lg, .input-group-lg .form-control, .input-group-lg .form-select {
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    
    .form-control-lg:focus, .form-select-lg:focus {
        border-color: #ef4444;
        box-shadow: 0 0 0 0.2rem rgba(239,68,68,0.25);
    }
    
    .input-group-text {
        border-radius: 12px 0 0 12px;
        border: 2px solid #e0e0e0;
        border-right: none;
        background: white;
    }
    
    .input-group .form-control,
    .input-group .form-select {
        border-left: none;
    }
    
    .input-group .form-control:focus,
    .input-group .form-select:focus {
        border-left: none;
    }
    
    .card-modern {
        background: white;
        border-radius: 20px;
        transition: transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    
    .card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(239,68,68,0.15);
    }
    
    /* Invalid feedback */
    .invalid-feedback {
        font-size: 0.8rem;
        margin-top: 0.25rem;
        color: #dc3545;
    }
    
    /* Buttons */
    .btn-save {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239,68,68,0.4);
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
        box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
        color: white;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border: none;
        font-weight: 600;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239,68,68,0.4);
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
    
    .btn-outline-danger {
        border-radius: 50px;
        transition: all 0.3s ease;
    }
    
    .btn-outline-danger:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
    }
    
    /* Radio buttons */
    .form-check-input:checked {
        background-color: #ef4444;
        border-color: #ef4444;
    }
    
    .form-check-input:focus {
        border-color: #ef4444;
        box-shadow: 0 0 0 0.2rem rgba(239,68,68,0.25);
    }
    
    /* Switch */
    .form-switch .form-check-input {
        width: 4em;
        background-color: #f72585;
    }
    
    .form-switch .form-check-input:checked {
        background-color: #10b981;
        border-color: #10b981;
    }
    
    /* Animations */
    .step-content {
        animation: fadeIn 0.5s ease;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 3;
    
    // Cambiar texto del plan según switch
    document.getElementById('plan')?.addEventListener('change', function() {
        const planText = document.getElementById('planText');
        if (this.checked) {
            planText.innerHTML = 'Gratuito';
            planText.classList.add('text-success');
            planText.classList.remove('text-warning');
        } else {
            planText.innerHTML = 'Premium';
            planText.classList.add('text-warning');
            planText.classList.remove('text-success');
        }
    });
    
    // Cambiar entre YouTube y Vimeo
    const platformYoutube = document.getElementById('platformYoutube');
    const platformVimeo = document.getElementById('platformVimeo');
    const youtubeInput = document.getElementById('youtubeInput');
    const vimeoInput = document.getElementById('vimeoInput');
    const youtubeUrl = document.getElementById('youtubeUrl');
    const vimeoId = document.getElementById('vimeoId');
    const vimeoHiddenUrl = document.getElementById('vimeoHiddenUrl');
    const previewContainer = document.getElementById('previewContainer');
    const videoPreview = document.getElementById('videoPreview');
    
    function updatePlatform() {
        if (platformYoutube.checked) {
            youtubeInput.style.display = 'block';
            vimeoInput.style.display = 'none';
            youtubeUrl.required = true;
            vimeoId.required = false;
        } else {
            youtubeInput.style.display = 'none';
            vimeoInput.style.display = 'block';
            youtubeUrl.required = false;
            vimeoId.required = true;
        }
        updatePreview();
    }
    
    function updatePreview() {
        let embedUrl = null;
        
        if (platformYoutube.checked && youtubeUrl.value) {
            let url = youtubeUrl.value;
            if (url.includes('youtube.com/watch?v=')) {
                embedUrl = url.replace('watch?v=', 'embed/');
                embedUrl = embedUrl.split('&')[0];
            } else if (url.includes('youtu.be/')) {
                const videoId = url.split('youtu.be/')[1];
                embedUrl = `https://www.youtube.com/embed/${videoId}`;
            }
        } else if (platformVimeo.checked && vimeoId.value) {
            embedUrl = `https://player.vimeo.com/video/${vimeoId.value}`;
        }
        
        if (embedUrl) {
            videoPreview.src = embedUrl;
            previewContainer.style.display = 'block';
        } else {
            previewContainer.style.display = 'none';
        }
    }
    
    platformYoutube.addEventListener('change', updatePlatform);
    platformVimeo.addEventListener('change', updatePlatform);
    youtubeUrl.addEventListener('input', updatePreview);
    vimeoId.addEventListener('input', updatePreview);
    updatePlatform();
    
    // Validar paso actual
    function validarPaso(step) {
        if (step === 1) {
            const materia = document.getElementById('materia').value;
            const tema = document.getElementById('tema').value.trim();
            const titulo = document.getElementById('titulo').value.trim();
            
            if (!materia) {
                Swal.fire('Error', 'Debe seleccionar una materia', 'error');
                return false;
            }
            
            if (!tema) {
                Swal.fire('Error', 'Debe ingresar el tema', 'error');
                return false;
            }
            
            if (!titulo) {
                Swal.fire('Error', 'Debe ingresar el título del video', 'error');
                return false;
            }
            
            return true;
        }
        
        if (step === 2) {
            if (platformYoutube.checked) {
                const url = youtubeUrl.value.trim();
                if (!url) {
                    Swal.fire('Error', 'Debe ingresar la URL de YouTube', 'error');
                    return false;
                }
                if (!url.includes('youtube.com') && !url.includes('youtu.be')) {
                    Swal.fire('Error', 'Debe ingresar una URL válida de YouTube', 'error');
                    return false;
                }
            } else {
                const id = vimeoId.value.trim();
                if (!id) {
                    Swal.fire('Error', 'Debe ingresar el ID del video de Vimeo', 'error');
                    return false;
                }
            }
            return true;
        }
        
        return true;
    }
    
    // Antes de enviar, construir la URL completa
    document.getElementById('videoForm').addEventListener('submit', function(e) {
        if (!validarPaso(1) || !validarPaso(2)) {
            e.preventDefault();
            return false;
        }
        
        // Construir URL completa para el campo link
        if (platformVimeo.checked && vimeoId.value) {
            const fullUrl = `https://vimeo.com/${vimeoId.value}`;
            vimeoHiddenUrl.value = fullUrl;
        } else {
            vimeoHiddenUrl.value = '';
        }
        
        const btnSubmit = document.getElementById('submitBtn');
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando...';
    });
    
    // Navegación entre pasos
    function mostrarPaso(step) {
        document.querySelectorAll('.step-content').forEach(content => {
            content.style.display = 'none';
        });
        document.querySelector(`.step-content[data-step="${step}"]`).style.display = 'block';
        
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
    
    // Inicializar
    mostrarPaso(1);
    
    // Inicializar texto del plan
    if (document.getElementById('plan').checked) {
        document.getElementById('planText').innerHTML = 'Gratuito';
        document.getElementById('planText').classList.add('text-success');
    } else {
        document.getElementById('planText').innerHTML = 'Premium';
        document.getElementById('planText').classList.add('text-warning');
    }
</script>
@endpush