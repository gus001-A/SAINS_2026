@extends('estudiante.layouts.app')

@section('title', 'Mi Perfil - SAINS')

@section('content')
<div class="profile-container">
    <!-- Hero Section con gradiente -->
    <div class="profile-hero">
        <div class="hero-particles"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        <span class="gradient-text">Mi Perfil</span>
                        <span class="hero-badge">Estudiante</span>
                    </h1>
                    <p class="hero-subtitle">Gestiona tu información personal y seguridad</p>
                </div>
                <a href="{{ route('estudiante.dashboard') }}" class="dashboard-link">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Tarjeta principal con efecto glassmorphism -->
        <div class="main-card">
            <!-- Header del perfil -->
            <div class="profile-header">
                <div class="avatar-section">
                    <div class="avatar-container">
                        @if($estudiante && $estudiante->foto)
                            <img src="{{ Storage::url($estudiante->foto) }}?t={{ time() }}" alt="Foto de perfil" id="fotoPerfil">
                        @else
                            <div class="avatar-placeholder">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                        @endif
                        <button class="avatar-edit-btn" id="btnCambiarFoto">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    <div class="user-info">
                        <h2>{{ $estudiante->nombre ?? 'Estudiante' }} {{ $estudiante->paterno ?? '' }}</h2>
                        <p class="user-email">
                            <i class="fas fa-envelope"></i>
                            {{ $user->correo ?? 'estudiante@sains.com' }}
                        </p>
                        <span class="user-status">
                            <i class="fas fa-circle"></i>
                            Activo
                        </span>
                    </div>
                </div>
            </div>

            <!-- Grid de información -->
            <div class="profile-grid">
                <!-- Sección 1: Información Personal (EDITABLE) -->
                <div class="info-card">
                    <div class="card-header">
                        <div class="header-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div>
                            <h3>Información Personal</h3>
                            <p>Actualiza tus datos personales</p>
                        </div>
                        <button class="btn-edit-section" id="btnEditarPerfil">
                            <i class="fas fa-pen"></i>
                            Editar
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Vista de solo lectura -->
                        <div id="perfilView">
                            <div class="info-grid">
                                <div class="info-field">
                                    <label><i class="fas fa-user"></i>Nombre</label>
                                    <span class="field-value" id="view_nombre">{{ $estudiante->nombre ?? '—' }}</span>
                                </div>
                                <div class="info-field">
                                    <label><i class="fas fa-user-tie"></i>Apellido Paterno</label>
                                    <span class="field-value" id="view_paterno">{{ $estudiante->paterno ?? '—' }}</span>
                                </div>
                                <div class="info-field">
                                    <label><i class="fas fa-user-friends"></i>Apellido Materno</label>
                                    <span class="field-value" id="view_materno">{{ $estudiante->materno ?? '—' }}</span>
                                </div>
                                <div class="info-field">
                                    <label><i class="fas fa-phone"></i>Teléfono</label>
                                    <span class="field-value" id="view_telefono">{{ $estudiante->telefono ?? '—' }}</span>
                                </div>
                                <div class="info-field">
                                    <label><i class="fas fa-calendar-alt"></i>Fecha de Nacimiento</label>
                                    <span class="field-value" id="view_fecha_nacimiento">{{ $estudiante && $estudiante->fecha_nacimiento ? $estudiante->fecha_nacimiento->format('d/m/Y') : '—' }}</span>
                                </div>
                                <div class="info-field">
                                    <label><i class="fas fa-venus-mars"></i>Sexo</label>
                                    <span class="field-value" id="view_sexo">
                                        @if($estudiante && $estudiante->sexo)
                                            {{ $estudiante->sexo == 'M' ? 'Masculino' : 'Femenino' }}
                                        @else
                                            —
                                        @endif
                                    </span>
                                </div>
                                <div class="info-field">
                                    <label><i class="fas fa-envelope"></i>Correo Electrónico</label>
                                    <span class="field-value" id="view_correo">{{ $user->correo ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Formulario de edición (oculto inicialmente) -->
                        <form id="formEditarPerfil" style="display: none;">
                            @csrf
                            @method('PUT')
                            <div class="info-grid">
                                <div class="info-field">
                                    <label><i class="fas fa-user"></i>Nombre *</label>
                                    <input type="text" name="nombre" class="form-control-modern" value="{{ $estudiante->nombre ?? '' }}" required>
                                </div>
                                <div class="info-field">
                                    <label><i class="fas fa-user-tie"></i>Apellido Paterno</label>
                                    <input type="text" name="paterno" class="form-control-modern" value="{{ $estudiante->paterno ?? '' }}">
                                </div>
                                <div class="info-field">
                                    <label><i class="fas fa-user-friends"></i>Apellido Materno</label>
                                    <input type="text" name="materno" class="form-control-modern" value="{{ $estudiante->materno ?? '' }}">
                                </div>
                                <div class="info-field">
                                    <label><i class="fas fa-phone"></i>Teléfono</label>
                                    <input type="tel" name="telefono" class="form-control-modern" value="{{ $estudiante->telefono ?? '' }}">
                                </div>
                                <div class="info-field">
                                    <label><i class="fas fa-calendar-alt"></i>Fecha de Nacimiento</label>
                                    <input type="date" name="fecha_nacimiento" class="form-control-modern" value="{{ $estudiante && $estudiante->fecha_nacimiento ? $estudiante->fecha_nacimiento->format('Y-m-d') : '' }}">
                                </div>
                                <div class="info-field">
                                    <label><i class="fas fa-venus-mars"></i>Sexo</label>
                                    <select name="sexo" class="form-control-modern">
                                        <option value="">Seleccionar</option>
                                        <option value="M" {{ $estudiante && $estudiante->sexo == 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ $estudiante && $estudiante->sexo == 'F' ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                </div>
                                <div class="info-field">
                                    <label><i class="fas fa-envelope"></i>Correo Electrónico *</label>
                                    <input type="email" name="correo" class="form-control-modern" value="{{ $user->correo ?? '' }}" required>
                                </div>
                            </div>
                            <div class="form-actions-edit">
                                <button type="button" class="btn-cancel-edit" id="btnCancelarEdicion">Cancelar</button>
                                <button type="submit" class="btn-save-edit">Guardar cambios</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sección 2: Foto de Perfil -->
                <div class="info-card">
                    <div class="card-header">
                        <div class="header-icon">
                            <i class="fas fa-camera"></i>
                        </div>
                        <div>
                            <h3>Foto de Perfil</h3>
                            <p>Actualiza tu imagen</p>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <div class="photo-preview-container">
                            @if($estudiante && $estudiante->foto)
                                <div class="current-photo">
                                    <img src="{{ Storage::url($estudiante->foto) }}" alt="Foto actual" id="previewFotoActual">
                                    <button class="delete-photo-btn" id="btnEliminarFoto" title="Eliminar foto">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            @else
                                <div class="no-photo">
                                    <i class="fas fa-user-circle"></i>
                                    <p>Sin foto de perfil</p>
                                </div>
                            @endif
                        </div>
                        
                        <button class="upload-btn" id="btnCambiarFotoPrincipal">
                            <i class="fas fa-cloud-upload-alt"></i>
                            {{ $estudiante && $estudiante->foto ? 'Cambiar foto' : 'Subir foto' }}
                        </button>
                        <input type="file" id="inputFoto" accept="image/*" style="display: none;">
                        
                        <div class="upload-info">
                            <i class="fas fa-info-circle"></i>
                            <span>JPG, PNG, GIF, WEBP · Máx. 2MB</span>
                        </div>
                    </div>
                </div>

                <!-- Sección 3: Cambiar Contraseña -->
                <div class="info-card full-width">
                    <div class="card-header">
                        <div class="header-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div>
                            <h3>Seguridad</h3>
                            <p>Actualiza tu contraseña de acceso</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="formCambiarPassword" class="password-form" autocomplete="off">
                            @csrf
                            @method('PUT')
                            
                            <!-- Campo fantasma para engañar al autocompletado del navegador -->
                            <input type="text" style="display:none">
                            <input type="password" style="display:none">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-key"></i>
                                        <span>Contraseña actual</span>
                                        <span class="required-badge">*Obligatorio</span>
                                    </label>
                                    <div class="password-input-wrapper">
                                        <input type="password" name="password_actual" id="current_password" 
                                               class="form-control" 
                                               placeholder="Ingresa tu contraseña actual" 
                                               autocomplete="off"
                                               autocomplete="new-password"
                                               readonly
                                               onfocus="this.removeAttribute('readonly');"
                                               onmousedown="this.value='';"
                                               required>
                                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility('current_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="form-hint">
                                        <i class="fas fa-info-circle"></i>
                                        Debes ingresar tu contraseña actual manualmente (no se autocompleta)
                                    </small>
                                </div>
                            </div>

                            <div class="form-row two-cols">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-plus-circle"></i>
                                        <span>Nueva contraseña</span>
                                    </label>
                                    <div class="password-input-wrapper">
                                        <input type="password" name="password_nueva" id="new_password"
                                               class="form-control" placeholder="Nueva contraseña" 
                                               autocomplete="new-password"
                                               required>
                                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility('new_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div id="passwordStrength" class="strength-meter"></div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Confirmar contraseña</span>
                                    </label>
                                    <div class="password-input-wrapper">
                                        <input type="password" name="password_nueva_confirmation" id="confirm_password"
                                               class="form-control" placeholder="Confirma tu nueva contraseña"
                                               autocomplete="new-password"
                                               required>
                                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility('confirm_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div id="passwordMatch" class="match-indicator"></div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn-generate" id="btnGenerarPassword">
                                    <i class="fas fa-dice-d6"></i>
                                    Generar contraseña segura
                                </button>
                                <button type="submit" class="btn-save" id="btnSubmitPassword">
                                    <i class="fas fa-save"></i>
                                    Actualizar contraseña
                                </button>
                            </div>
                        </form>

                        <!-- Tips de seguridad -->
                        <div class="security-tips">
                            <div class="tips-title">
                                <i class="fas fa-shield-alt"></i>
                                <span>Consejos de seguridad</span>
                            </div>
                            <div class="tips-grid">
                                <div class="tip">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Mínimo 6 caracteres</span>
                                </div>
                                <div class="tip">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Incluye mayúsculas y minúsculas</span>
                                </div>
                                <div class="tip">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Agrega números y símbolos</span>
                                </div>
                                <div class="tip">
                                    <i class="fas fa-check-circle"></i>
                                    <span>No uses la misma contraseña en otros sitios</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de vista previa de foto -->
<div class="modal fade" id="photoPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-modal">
            <div class="modal-body text-center p-4">
                <img id="previewImage" src="" alt="Vista previa" class="preview-img">
                <div class="modal-actions mt-4">
                    <button class="btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn-confirm" id="confirmPhoto">Usar esta foto</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Reset y variables */
    :root {
        --primary: #6366f1;
        --primary-dark: #4f46e5;
        --primary-light: #818cf8;
        --secondary: #8b5cf6;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --dark: #1f2937;
        --gray: #6b7280;
        --light: #f9fafb;
        --border: #e5e7eb;
    }
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    .profile-container {
        min-height: calc(100vh - 72px);
        background: linear-gradient(135deg, #f5f7fa 0%, #eef2f7 100%);
        position: relative;
    }
    
    /* Hero Section */
    .profile-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        padding: 4rem 0;
        overflow: hidden;
    }
    
    .hero-particles {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 30px 30px;
        animation: float 20s infinite linear;
    }
    
    @keyframes float {
        0% { transform: translateY(0px); }
        100% { transform: translateY(-30px); }
    }
    
    .hero-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
        position: relative;
        z-index: 1;
    }
    
    .hero-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .gradient-text {
        background: linear-gradient(135deg, #fff 0%, #e0e7ff 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    
    .hero-badge {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        padding: 0.25rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        color: white;
    }
    
    .hero-subtitle {
        color: rgba(255,255,255,0.9);
        font-size: 1rem;
        margin: 0;
    }
    
    .dashboard-link {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.3);
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        color: white;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .dashboard-link:hover {
        background: white;
        color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    /* Main Card */
    .main-card {
        max-width: 1200px;
        margin: -2rem auto 2rem;
        background: white;
        border-radius: 32px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        overflow: hidden;
        position: relative;
        z-index: 2;
    }
    
    /* Profile Header */
    .profile-header {
        background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
        padding: 2rem;
        border-bottom: 1px solid var(--border);
    }
    
    .avatar-section {
        display: flex;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
    }
    
    .avatar-container {
        position: relative;
    }
    
    .avatar-container img,
    .avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .avatar-placeholder {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .avatar-placeholder i {
        font-size: 3rem;
    }
    
    .avatar-edit-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 36px;
        height: 36px;
        background: var(--primary);
        border: 3px solid white;
        border-radius: 50%;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-edit-btn:hover {
        transform: scale(1.1);
        background: var(--primary-dark);
    }
    
    .user-info h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }
    
    .user-email {
        color: var(--gray);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .user-status {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .user-status i {
        font-size: 0.5rem;
    }
    
    /* Profile Grid */
    .profile-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 1.5rem;
        padding: 2rem;
    }
    
    .info-card {
        background: white;
        border-radius: 24px;
        border: 1px solid var(--border);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        border-color: var(--primary-light);
    }
    
    .info-card.full-width {
        grid-column: 1 / -1;
    }
    
    .card-header {
        padding: 1.5rem 1.5rem 0 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-bottom: none;
        position: relative;
    }
    
    .header-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--primary);
    }
    
    .card-header h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }
    
    .card-header p {
        font-size: 0.85rem;
        color: var(--gray);
        margin: 0;
    }
    
    .btn-edit-section {
        margin-left: auto;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        color: white;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-edit-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3);
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
    }
    
    .info-field {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .info-field label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .field-value {
        font-size: 1rem;
        font-weight: 500;
        color: var(--dark);
    }
    
    /* Formulario de edición */
    .form-control-modern {
        width: 100%;
        padding: 0.6rem 0.75rem;
        border: 2px solid var(--border);
        border-radius: 12px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background: white;
    }
    
    .form-control-modern:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    
    .form-actions-edit {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border);
    }
    
    .btn-cancel-edit {
        padding: 0.6rem 1.5rem;
        background: var(--gray);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-cancel-edit:hover {
        background: #4b5563;
        transform: translateY(-2px);
    }
    
    .btn-save-edit {
        padding: 0.6rem 1.5rem;
        background: linear-gradient(135deg, var(--success), #059669);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-save-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
    }
    
    /* Photo Section */
    .photo-preview-container {
        margin-bottom: 1.5rem;
    }
    
    .current-photo {
        position: relative;
        display: inline-block;
    }
    
    .current-photo img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--primary);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .delete-photo-btn {
        position: absolute;
        bottom: 10px;
        right: 10px;
        width: 36px;
        height: 36px;
        background: var(--danger);
        border: 2px solid white;
        border-radius: 50%;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .delete-photo-btn:hover {
        transform: scale(1.1);
        background: #dc2626;
    }
    
    .no-photo {
        text-align: center;
    }
    
    .no-photo i {
        font-size: 5rem;
        color: var(--gray);
        margin-bottom: 0.5rem;
    }
    
    .no-photo p {
        color: var(--gray);
        margin: 0;
    }
    
    .upload-btn {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .upload-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
    }
    
    .upload-info {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: var(--gray);
    }
    
    /* Password Form */
    .password-form {
        margin-bottom: 2rem;
    }
    
    .form-row {
        margin-bottom: 1.5rem;
    }
    
    .form-row.two-cols {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    
    .form-group {
        width: 100%;
    }
    
    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }
    
    .form-label i {
        color: var(--primary);
    }
    
    .required-badge {
        background: #fef3c7;
        color: #d97706;
        font-size: 0.65rem;
        padding: 0.2rem 0.5rem;
        border-radius: 50px;
        font-weight: 600;
        margin-left: 0.5rem;
    }
    
    .form-hint {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.7rem;
        color: var(--gray);
    }
    
    .form-hint i {
        color: var(--primary);
        margin-right: 0.25rem;
    }
    
    .password-input-wrapper {
        position: relative;
    }
    
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border);
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: white;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    
    /* Prevenir autocompletado */
    input#current_password,
    input#new_password,
    input#confirm_password {
        background-image: url('data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        background-attachment: fixed;
    }
    
    .toggle-password {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--gray);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .toggle-password:hover {
        color: var(--primary);
    }
    
    .strength-meter {
        margin-top: 0.5rem;
        height: 4px;
        border-radius: 2px;
        transition: all 0.3s ease;
    }
    
    .strength-weak {
        background: linear-gradient(90deg, #ef4444, #f97316);
        width: 33%;
    }
    
    .strength-medium {
        background: linear-gradient(90deg, #f59e0b, #fbbf24);
        width: 66%;
    }
    
    .strength-strong {
        background: linear-gradient(90deg, #10b981, #34d399);
        width: 100%;
    }
    
    .match-indicator {
        margin-top: 0.5rem;
        font-size: 0.75rem;
    }
    
    .match-indicator.success {
        color: var(--success);
    }
    
    .match-indicator.error {
        color: var(--danger);
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }
    
    .btn-generate,
    .btn-save {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
    }
    
    .btn-generate {
        background: linear-gradient(135deg, #f59e0b, #ef4444);
        color: white;
    }
    
    .btn-generate:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(245, 158, 11, 0.3);
    }
    
    .btn-save {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
    }
    
    /* Security Tips */
    .security-tips {
        background: linear-gradient(135deg, #f8f9ff 0%, #fef3c7 100%);
        border-radius: 20px;
        padding: 1.25rem;
        margin-top: 1.5rem;
    }
    
    .tips-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 1rem;
    }
    
    .tips-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.75rem;
    }
    
    .tip {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        color: var(--dark);
    }
    
    .tip i {
        color: var(--success);
        font-size: 0.7rem;
    }
    
    /* Modal */
    .glass-modal {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(20px);
        border-radius: 32px;
    }
    
    .preview-img {
        max-width: 100%;
        max-height: 300px;
        border-radius: 16px;
    }
    
    .modal-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }
    
    .btn-cancel,
    .btn-confirm {
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-cancel {
        background: var(--gray);
        color: white;
    }
    
    .btn-confirm {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 1.5rem;
        }
        
        .profile-grid {
            grid-template-columns: 1fr;
            padding: 1rem;
        }
        
        .form-row.two-cols {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .avatar-section {
            justify-content: center;
            text-align: center;
        }
        
        .user-info {
            text-align: center;
        }
        
        .user-email,
        .user-status {
            justify-content: center;
        }
        
        .tips-grid {
            grid-template-columns: 1fr;
        }
        
        .card-header {
            flex-direction: column;
            text-align: center;
        }
        
        .btn-edit-section {
            margin-left: 0;
        }
    }
    
    @media (max-width: 480px) {
        .main-card {
            margin: -1rem 1rem 1rem;
        }
        
        .profile-header {
            padding: 1.5rem;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .upload-btn,
        .btn-generate,
        .btn-save {
            width: 100%;
            justify-content: center;
        }
        
        .form-actions-edit {
            flex-direction: column;
        }
        
        .btn-cancel-edit,
        .btn-save-edit {
            width: 100%;
        }
    }
    
    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .info-card {
        animation: fadeInUp 0.6s ease backwards;
    }
    
    .info-card:nth-child(1) { animation-delay: 0.1s; }
    .info-card:nth-child(2) { animation-delay: 0.2s; }
    .info-card:nth-child(3) { animation-delay: 0.3s; }
</style>
@endpush

@push('scripts')
<script>
    // Funciones para contraseñas
    function togglePasswordVisibility(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return;
        
        const type = field.type === 'password' ? 'text' : 'password';
        field.type = type;
        
        const icon = field.parentElement.querySelector('.toggle-password i');
        if (icon) {
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    }
    
    function generatePassword(length = 12) {
        const upper = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        const lower = 'abcdefghijkmnopqrstuvwxyz';
        const numbers = '23456789';
        const symbols = '!@#$%&*';
        
        let password = '';
        password += upper[Math.floor(Math.random() * upper.length)];
        password += lower[Math.floor(Math.random() * lower.length)];
        password += numbers[Math.floor(Math.random() * numbers.length)];
        password += symbols[Math.floor(Math.random() * symbols.length)];
        
        const all = upper + lower + numbers + symbols;
        for (let i = password.length; i < length; i++) {
            password += all[Math.floor(Math.random() * all.length)];
        }
        
        return password.split('').sort(() => Math.random() - 0.5).join('');
    }
    
    function evaluateStrength(password) {
        let score = 0;
        if (password.length >= 6) score++;
        if (password.length >= 8) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;
        
        if (password.length === 0) return null;
        
        if (score <= 2) return { level: 1, text: 'Débil', class: 'strength-weak' };
        if (score <= 3) return { level: 2, text: 'Media', class: 'strength-medium' };
        return { level: 3, text: 'Fuerte', class: 'strength-strong' };
    }
    
    function updateStrength() {
        const password = document.getElementById('new_password');
        if (!password) return;
        
        const strength = evaluateStrength(password.value);
        const container = document.getElementById('passwordStrength');
        
        if (!container) return;
        
        if (!strength) {
            container.innerHTML = '';
            return;
        }
        
        container.innerHTML = `<div class="strength-meter ${strength.class}"></div>`;
        checkMatch();
    }
    
    function checkMatch() {
        const newPass = document.getElementById('new_password');
        const confirmPass = document.getElementById('confirm_password');
        const matchDiv = document.getElementById('passwordMatch');
        
        if (!newPass || !confirmPass || !matchDiv) return;
        
        if (confirmPass.value.length === 0) {
            matchDiv.innerHTML = '';
            return;
        }
        
        if (newPass.value === confirmPass.value) {
            matchDiv.innerHTML = '<div class="match-indicator success"><i class="fas fa-check-circle"></i> Las contraseñas coinciden</div>';
        } else {
            matchDiv.innerHTML = '<div class="match-indicator error"><i class="fas fa-exclamation-circle"></i> Las contraseñas no coinciden</div>';
        }
    }
    
    // Manejo de foto
    let selectedFile = null;
    
    function uploadPhoto(file) {
        const formData = new FormData();
        formData.append('foto', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        Swal.fire({
            title: 'Subiendo foto...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
        
        fetch('{{ route("estudiante.subir.foto") }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Foto actualizada!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al conectar con el servidor'
            });
        });
    }
    
    function deletePhoto() {
        Swal.fire({
            title: '¿Eliminar foto?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Eliminando...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                
                fetch('{{ route("estudiante.eliminar.foto") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Foto eliminada',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al eliminar la foto'
                    });
                });
            }
        });
    }
    
    // Limpiar campo de contraseña actual al cargar
    function clearPasswordFields() {
        const currentPass = document.getElementById('current_password');
        if (currentPass) {
            currentPass.value = '';
        }
    }
    
    // Edición de perfil
    document.getElementById('btnEditarPerfil')?.addEventListener('click', function() {
        document.getElementById('perfilView').style.display = 'none';
        document.getElementById('formEditarPerfil').style.display = 'block';
        this.style.display = 'none';
    });
    
    document.getElementById('btnCancelarEdicion')?.addEventListener('click', function() {
        document.getElementById('perfilView').style.display = 'block';
        document.getElementById('formEditarPerfil').style.display = 'none';
        document.getElementById('btnEditarPerfil').style.display = 'flex';
    });
    
    document.getElementById('formEditarPerfil')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const btn = this.querySelector('.btn-save-edit');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        btn.disabled = true;
        
        Swal.fire({
            title: 'Actualizando perfil...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
        
        fetch('{{ route("estudiante.perfil.actualizar") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar vista
                document.getElementById('view_nombre').textContent = formData.get('nombre') || '—';
                document.getElementById('view_paterno').textContent = formData.get('paterno') || '—';
                document.getElementById('view_materno').textContent = formData.get('materno') || '—';
                document.getElementById('view_telefono').textContent = formData.get('telefono') || '—';
                
                const fechaNac = formData.get('fecha_nacimiento');
                if (fechaNac) {
                    const fecha = new Date(fechaNac);
                    document.getElementById('view_fecha_nacimiento').textContent = fecha.toLocaleDateString('es-MX');
                }
                
                const sexo = formData.get('sexo');
                document.getElementById('view_sexo').textContent = sexo === 'M' ? 'Masculino' : (sexo === 'F' ? 'Femenino' : '—');
                document.getElementById('view_correo').textContent = formData.get('correo') || '—';
                
                // Actualizar nombre en el header
                const nombreCompleto = `${formData.get('nombre') || ''} ${formData.get('paterno') || ''}`.trim();
                if (nombreCompleto) {
                    document.querySelector('.user-info h2').textContent = nombreCompleto;
                }
                
                Swal.fire({
                    icon: 'success',
                    title: '¡Perfil actualizado!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Volver a modo vista
                document.getElementById('perfilView').style.display = 'block';
                document.getElementById('formEditarPerfil').style.display = 'none';
                document.getElementById('btnEditarPerfil').style.display = 'flex';
            } else {
                let errorMsg = data.message;
                if (data.errors) {
                    errorMsg = Object.values(data.errors).flat().join('\n');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg
                });
            }
            btn.innerHTML = originalText;
            btn.disabled = false;
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión'
            });
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        // Limpiar campos de contraseña
        clearPasswordFields();
        
        // Eventos de foto
        const btnChangePhoto = document.getElementById('btnCambiarFotoPrincipal');
        const fileInput = document.getElementById('inputFoto');
        const photoModal = new bootstrap.Modal(document.getElementById('photoPreviewModal'));
        
        if (btnChangePhoto) {
            btnChangePhoto.addEventListener('click', () => fileInput.click());
        }
        
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Formato no válido',
                        text: 'Usa JPG, PNG, GIF o WEBP'
                    });
                    fileInput.value = '';
                    return;
                }
                
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo muy grande',
                        text: 'La imagen no debe superar los 2MB'
                    });
                    fileInput.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    selectedFile = file;
                    photoModal.show();
                };
                reader.readAsDataURL(file);
            });
        }
        
        document.getElementById('confirmPhoto')?.addEventListener('click', function() {
            if (selectedFile) {
                photoModal.hide();
                uploadPhoto(selectedFile);
            }
        });
        
        document.getElementById('btnEliminarFoto')?.addEventListener('click', deletePhoto);
        
        // Generar contraseña
        document.getElementById('btnGenerarPassword')?.addEventListener('click', function() {
            const newPassword = generatePassword(12);
            document.getElementById('new_password').value = newPassword;
            document.getElementById('confirm_password').value = newPassword;
            updateStrength();
            checkMatch();
            
            Swal.fire({
                title: '¡Contraseña generada!',
                html: `
                    <div style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 15px; border-radius: 16px; font-family: monospace; font-size: 1.2rem; letter-spacing: 2px; margin: 15px 0; font-weight: bold;">${newPassword}</div>
                    <p class="mt-3">Guarda esta contraseña en un lugar seguro</p>
                `,
                icon: 'success'
            });
        });
        
        // Validaciones de contraseña
        document.getElementById('new_password')?.addEventListener('input', updateStrength);
        document.getElementById('confirm_password')?.addEventListener('input', checkMatch);
        
        // Limpiar contraseña actual periódicamente
        setInterval(clearPasswordFields, 100);
        
        // Submit del formulario de contraseña
        document.getElementById('formCambiarPassword')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const currentPass = document.getElementById('current_password');
            const newPass = document.getElementById('new_password');
            const confirmPass = document.getElementById('confirm_password');
            
            // Validar que la contraseña actual esté presente y no sea el valor autocompletado
            if (!currentPass.value || currentPass.value.trim() === '' || currentPass.value === 'QN345ZppdmiVuEW') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Contraseña actual requerida',
                    text: 'Debes ingresar tu contraseña actual manualmente (no se autocompleta)',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#f59e0b'
                });
                currentPass.value = '';
                currentPass.focus();
                return;
            }
            
            if (!newPass.value || newPass.value.trim() === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nueva contraseña requerida',
                    text: 'Debes ingresar una nueva contraseña',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#f59e0b'
                });
                newPass.focus();
                return;
            }
            
            if (newPass.value !== confirmPass.value) {
                Swal.fire({
                    icon: 'error',
                    title: 'Las contraseñas no coinciden',
                    text: 'La nueva contraseña y su confirmación deben ser iguales',
                    confirmButtonText: 'Revisar',
                    confirmButtonColor: '#ef4444'
                });
                confirmPass.focus();
                return;
            }
            
            if (newPass.value.length < 6) {
                Swal.fire({
                    icon: 'error',
                    title: 'Contraseña muy corta',
                    text: 'La contraseña debe tener al menos 6 caracteres',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#ef4444'
                });
                newPass.focus();
                return;
            }
            
            const btn = this.querySelector('.btn-save');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verificando...';
            btn.disabled = true;
            
            Swal.fire({
                title: 'Verificando contraseña actual...',
                text: 'Por favor espera',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            fetch('{{ route("estudiante.perfil.cambiar-password") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    password_actual: currentPass.value,
                    password_nueva: newPass.value,
                    password_nueva_confirmation: confirmPass.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Contraseña actualizada!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: true,
                        confirmButtonColor: '#10b981'
                    }).then(() => {
                        this.reset();
                        document.getElementById('passwordStrength').innerHTML = '';
                        document.getElementById('passwordMatch').innerHTML = '';
                        clearPasswordFields();
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    });
                } else {
                    let errorMessage = data.message;
                    if (data.message.includes('actual') || data.message.includes('contraseña actual')) {
                        errorMessage = '❌ La contraseña actual es incorrecta. Por favor, verifica e intenta nuevamente.';
                        currentPass.value = '';
                        currentPass.focus();
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        confirmButtonText: 'Intentar de nuevo',
                        confirmButtonColor: '#ef4444'
                    });
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor. Verifica tu conexión a internet.',
                    confirmButtonText: 'Reintentar',
                    confirmButtonColor: '#ef4444'
                });
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });
    });
    
    // Mensajes flash
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
    
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}'
        });
    @endif
    
    @if(session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: '{{ session('warning') }}'
        });
    @endif
</script>
@endpush