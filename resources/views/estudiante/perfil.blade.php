@extends('estudiante.layouts.app')

@section('title', 'Mi Perfil - SAINS')

@section('content')
<div class="profile-container">
    <!-- Fondo decorativo -->
    <div class="profile-bg-decoration"></div>
    
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Encabezado con gradiente -->
            <div class="profile-header mb-5">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-4">
                        <div class="profile-avatar-wrapper">
                            @if($estudiante && $estudiante->foto)
                                <div class="profile-avatar-img" id="avatarContainer">
                                    <img src="{{ Storage::url($estudiante->foto) }}?t={{ time() }}" alt="Foto perfil" id="fotoPerfil">
                                </div>
                            @else
                                <div class="profile-avatar" id="avatarContainer">
                                    <i class="fas fa-user-graduate fa-3x"></i>
                                </div>
                            @endif
                            <button type="button" class="profile-status" id="btnCambiarFoto" title="Cambiar foto">
                                <i class="fas fa-camera"></i>
                            </button>
                            <input type="file" id="inputFoto" accept="image/*" style="display: none;">
                        </div>
                        <div>
                            <h1 class="display-6 fw-bold mb-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                                Mi Perfil
                            </h1>
                            <p class="text-muted mb-0">
                                <i class="fas fa-envelope me-2"></i>{{ $user->correo ?? 'estudiante@sains.com' }}
                            </p>
                            @if($estudiante)
                            <p class="text-muted small mb-0 mt-1">
                                <i class="fas fa-user me-2"></i>{{ $estudiante->nombre ?? '' }} {{ $estudiante->paterno ?? '' }} {{ $estudiante->materno ?? '' }}
                            </p>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('estudiante.dashboard') }}" class="btn-glass" id="btnDashboard">
                        <i class="fas fa-chart-line me-2"></i>Dashboard
                    </a>
                </div>
            </div>

            <!-- Tarjeta principal -->
            <div class="profile-card">
                <form action="{{ route('estudiante.perfil.actualizar') }}" method="POST" id="formPerfil">
                    @csrf
                    @method('PUT')
                    
                    <!-- Sección Información Personal -->
                    <div class="profile-section">
                        <div class="section-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="section-content">
                            <h3 class="section-title">Información personal</h3>
                            <p class="section-subtitle">Actualiza tus datos personales</p>
                            
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="fas fa-user"></i>
                                            <span>Nombre</span>
                                        </label>
                                        <input type="text" name="nombre" 
                                               class="input-modern" 
                                               value="{{ old('nombre', $estudiante->nombre ?? '') }}" 
                                               placeholder="Tu nombre">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="fas fa-user-tie"></i>
                                            <span>Apellido paterno</span>
                                        </label>
                                        <input type="text" name="paterno" 
                                               class="input-modern" 
                                               value="{{ old('paterno', $estudiante->paterno ?? '') }}" 
                                               placeholder="Apellido paterno">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="fas fa-user-friends"></i>
                                            <span>Apellido materno</span>
                                        </label>
                                        <input type="text" name="materno" 
                                               class="input-modern" 
                                               value="{{ old('materno', $estudiante->materno ?? '') }}" 
                                               placeholder="Apellido materno">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="fas fa-phone"></i>
                                            <span>Teléfono celular</span>
                                        </label>
                                        <input type="tel" name="telefono" 
                                               id="telefono"
                                               class="input-modern" 
                                               value="{{ old('telefono', $estudiante->telefono ?? '') }}" 
                                               placeholder="Ej: 5512345678"
                                               maxlength="10">
                                        <div class="input-hint" id="telefonoHint">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Número de 10 dígitos (solo números)
                                        </div>
                                        <div id="telefonoError" class="error-message" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>Fecha de nacimiento</span>
                                        </label>
                                        <input type="date" name="fecha_nacimiento" 
                                               class="input-modern" 
                                               value="{{ old('fecha_nacimiento', $estudiante && $estudiante->fecha_nacimiento ? $estudiante->fecha_nacimiento->format('Y-m-d') : '') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="fas fa-venus-mars"></i>
                                            <span>Sexo</span>
                                        </label>
                                        <select name="sexo" class="input-modern">
                                            <option value="">Seleccionar</option>
                                            <option value="M" {{ ($estudiante->sexo ?? '') == 'M' ? 'selected' : '' }}>Masculino</option>
                                            <option value="F" {{ ($estudiante->sexo ?? '') == 'F' ? 'selected' : '' }}>Femenino</option>
                                        </select>
                                    </div>
                                </div>
                                @if($estudiante && $estudiante->foto)
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">&nbsp;</label>
                                        <button type="button" class="btn-delete-photo" id="btnEliminarFoto">
                                            <i class="fas fa-trash-alt me-2"></i>Eliminar foto actual
                                        </button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Separador animado -->
                    <div class="section-divider">
                        <div class="divider-line"></div>
                        <div class="divider-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="divider-line"></div>
                    </div>
                    
                    <!-- Sección Email -->
                    <div class="profile-section">
                        <div class="section-icon">
                            <i class="fas fa-at"></i>
                        </div>
                        <div class="section-content">
                            <h3 class="section-title">Información de contacto</h3>
                            <p class="section-subtitle">Actualiza tu correo electrónico principal</p>
                            
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="fas fa-envelope"></i>
                                    <span>Correo electrónico</span>
                                </label>
                                <input type="email" name="correo" 
                                       id="correo"
                                       class="input-modern @error('correo') is-error @enderror" 
                                       value="{{ old('correo', $user->correo ?? '') }}" 
                                       placeholder="tu@email.com" required>
                                @error('correo')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div class="input-hint">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Al cambiar tu correo, deberás iniciar sesión con el nuevo
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Separador animado -->
                    <div class="section-divider">
                        <div class="divider-line"></div>
                        <div class="divider-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="divider-line"></div>
                    </div>

                    <!-- Sección Contraseña -->
                    <div class="profile-section">
                        <div class="section-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="section-content">
                            <h3 class="section-title">Seguridad</h3>
                            <p class="section-subtitle">Cambia tu contraseña periódicamente</p>
                            
                            <!-- Contraseña actual -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="fas fa-key"></i>
                                    <span>Contraseña actual</span>
                                </label>
                                <div class="password-wrapper">
                                    <input type="password" name="password_actual" id="current_password"
                                           class="input-modern" placeholder="••••••••">
                                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('current_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="input-hint">Requerido para cambiar la contraseña</div>
                            </div>

                            <!-- Nueva contraseña con generador -->
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="fas fa-plus-circle"></i>
                                            <span>Nueva contraseña</span>
                                        </label>
                                        <div class="password-wrapper">
                                            <input type="password" name="password_nueva" id="new_password"
                                                   class="input-modern" placeholder="••••••••">
                                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility('new_password')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div id="passwordStrength"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">&nbsp;</label>
                                        <button type="button" class="btn-generate-password" id="btnGenerarPassword">
                                            <i class="fas fa-dice-d6 me-2"></i>Generar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirmar contraseña -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Confirmar nueva contraseña</span>
                                </label>
                                <div class="password-wrapper">
                                    <input type="password" name="password_nueva_confirmation" id="confirm_password"
                                           class="input-modern" placeholder="••••••••">
                                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('confirm_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div id="passwordMatch"></div>
                            </div>

                            <!-- Tarjeta de consejos de seguridad -->
                            <div class="security-tips">
                                <div class="tips-header">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>Consejos de seguridad</span>
                                </div>
                                <div class="tips-list">
                                    <div class="tip-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Mínimo 6 caracteres</span>
                                    </div>
                                    <div class="tip-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Incluye mayúsculas y minúsculas</span>
                                    </div>
                                    <div class="tip-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Agrega números y símbolos</span>
                                    </div>
                                    <div class="tip-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>No uses la misma contraseña en otros sitios</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="profile-actions">
                        <button type="submit" class="btn-save" id="btnSubmit">
                            <i class="fas fa-save me-2"></i>Guardar cambios
                        </button>
                        <button type="button" class="btn-cancel" id="btnReset">
                            <i class="fas fa-undo-alt me-2"></i>Restablecer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-container {
        position: relative;
        min-height: calc(100vh - 100px);
        padding: 2rem 1.5rem;
        overflow-x: hidden;
    }
    
    .profile-bg-decoration {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 0;
        background: radial-gradient(circle at 0% 0%, rgba(102, 126, 234, 0.05) 0%, transparent 50%),
                    radial-gradient(circle at 100% 100%, rgba(118, 75, 162, 0.05) 0%, transparent 50%);
        pointer-events: none;
    }
    
    .profile-avatar-wrapper {
        position: relative;
    }
    
    .profile-avatar {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        transition: transform 0.3s ease;
    }
    
    .profile-avatar-img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        transition: transform 0.3s ease;
    }
    
    .profile-avatar-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-avatar:hover,
    .profile-avatar-img:hover {
        transform: scale(1.05);
    }
    
    .profile-status {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.7rem;
    }
    
    .profile-status:hover {
        transform: scale(1.1);
        background: linear-gradient(135deg, #764ba2, #667eea);
    }
    
    .btn-delete-photo {
        width: 100%;
        padding: 0.85rem;
        background: linear-gradient(135deg, #dc3545, #c82333);
        border: none;
        border-radius: 16px;
        color: white;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-delete-photo:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        filter: brightness(1.05);
    }
    
    .btn-glass {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(102, 126, 234, 0.2);
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        color: #667eea;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: inline-flex;
        align-items: center;
    }
    
    .btn-glass:hover {
        background: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.2);
        color: #667eea;
    }
    
    .profile-card {
        background: white;
        border-radius: 32px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        overflow: hidden;
        position: relative;
        z-index: 1;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 80px rgba(0,0,0,0.15);
    }
    
    .profile-section {
        padding: 2rem 2.5rem;
        display: flex;
        gap: 2rem;
        transition: background 0.3s ease;
    }
    
    .profile-section:hover {
        background: rgba(102, 126, 234, 0.02);
    }
    
    .section-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #667eea;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }
    
    .profile-section:hover .section-icon {
        transform: scale(1.05) rotate(5deg);
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2));
    }
    
    .section-content {
        flex: 1;
    }
    
    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: #1a1a2e;
    }
    
    .section-subtitle {
        color: #6c757d;
        font-size: 0.85rem;
        margin-bottom: 1.5rem;
    }
    
    .form-group-modern {
        margin-bottom: 1.5rem;
    }
    
    .form-label-modern {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #1a1a2e;
    }
    
    .form-label-modern i {
        color: #667eea;
        font-size: 0.9rem;
    }
    
    .input-modern {
        width: 100%;
        padding: 0.85rem 1rem;
        border: 2px solid #e1e4e8;
        border-radius: 16px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: white;
    }
    
    .input-modern:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }
    
    .input-modern.is-error {
        border-color: #dc3545;
    }
    
    .input-modern.is-valid {
        border-color: #28a745;
    }
    
    .password-wrapper {
        position: relative;
    }
    
    .password-wrapper .input-modern {
        padding-right: 3rem;
    }
    
    .toggle-password {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
        padding: 0;
    }
    
    .toggle-password:hover {
        color: #667eea;
        transform: translateY(-50%) scale(1.1);
    }
    
    .input-hint {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 0.5rem;
    }
    
    .error-message {
        font-size: 0.75rem;
        color: #dc3545;
        margin-top: 0.5rem;
    }
    
    .btn-generate-password {
        width: 100%;
        padding: 0.85rem;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border: none;
        border-radius: 16px;
        color: white;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 5px 15px rgba(245, 87, 108, 0.3);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-generate-password:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(245, 87, 108, 0.4);
        filter: brightness(1.05);
    }
    
    .btn-generate-password:active {
        transform: translateY(0);
    }
    
    .strength-meter {
        height: 4px;
        border-radius: 2px;
        margin-top: 8px;
        transition: all 0.3s ease;
    }
    
    .strength-weak {
        background: linear-gradient(90deg, #dc3545, #ff6b6b);
        width: 33%;
    }
    
    .strength-medium {
        background: linear-gradient(90deg, #ffc107, #ffda6a);
        width: 66%;
    }
    
    .strength-strong {
        background: linear-gradient(90deg, #28a745, #6fcf97);
        width: 100%;
    }
    
    .strength-text {
        font-size: 0.7rem;
        margin-top: 4px;
    }
    
    .security-tips {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
        border-radius: 20px;
        padding: 1.25rem;
        margin-top: 1rem;
    }
    
    .tips-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 1rem;
    }
    
    .tips-header i {
        font-size: 1.1rem;
    }
    
    .tips-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }
    
    .tip-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        color: #1a1a2e;
    }
    
    .tip-item i {
        color: #28a745;
        font-size: 0.75rem;
    }
    
    .section-divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0 2.5rem;
    }
    
    .divider-line {
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, transparent, #e1e4e8, transparent);
    }
    
    .divider-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }
    
    .profile-actions {
        padding: 1.5rem 2.5rem 2rem;
        background: rgba(102, 126, 234, 0.03);
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        border-top: 1px solid rgba(102, 126, 234, 0.1);
    }
    
    .btn-save {
        padding: 0.85rem 2rem;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        border-radius: 50px;
        color: white;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-save:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        filter: brightness(1.05);
    }
    
    .btn-save:active {
        transform: translateY(0);
    }
    
    .btn-cancel {
        padding: 0.85rem 2rem;
        background: white;
        border: 2px solid #e1e4e8;
        border-radius: 50px;
        color: #6c757d;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-cancel:hover {
        border-color: #dc3545;
        color: #dc3545;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.1);
    }
    
    .btn-cancel:active {
        transform: translateY(0);
    }
    
    .generated-password-modal {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 15px;
        border-radius: 16px;
        font-family: 'Courier New', monospace;
        font-size: 1.2rem;
        letter-spacing: 2px;
        text-align: center;
        margin: 15px 0;
        font-weight: bold;
    }
    
    @media (max-width: 768px) {
        .profile-container {
            padding: 1rem;
        }
        
        .profile-section {
            flex-direction: column;
            padding: 1.5rem;
            gap: 1rem;
        }
        
        .tips-list {
            grid-template-columns: 1fr;
        }
        
        .profile-actions {
            flex-direction: column;
            padding: 1.5rem;
        }
        
        .profile-header .d-flex {
            justify-content: center;
            text-align: center;
            flex-direction: column;
        }
        
        .section-divider {
            padding: 0 1.5rem;
        }
    }
    
    body.dark-mode .profile-card {
        background: #1a1a2e;
    }
    
    body.dark-mode .section-title {
        color: #e0e0e0;
    }
    
    body.dark-mode .input-modern {
        background: #0f0f1a;
        border-color: rgba(255, 255, 255, 0.1);
        color: #e0e0e0;
    }
    
    body.dark-mode .input-modern:focus {
        border-color: #667eea;
    }
    
    body.dark-mode .security-tips {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
    }
    
    body.dark-mode .tip-item {
        color: #e0e0e0;
    }
    
    body.dark-mode .btn-cancel {
        background: #0f0f1a;
        border-color: rgba(255, 255, 255, 0.1);
        color: #a0a0a0;
    }
    
    body.dark-mode .btn-cancel:hover {
        border-color: #dc3545;
        color: #dc3545;
    }
    
    body.dark-mode .btn-glass {
        background: rgba(26, 26, 46, 0.9);
        color: #667eea;
    }
    
    body.dark-mode .profile-status {
        border-color: #1a1a2e;
    }
</style>
@endpush

@push('scripts')
<script>
    // ========== FUNCIÓN PARA VALIDAR TELÉFONO ==========
    function validarTelefono(telefono) {
        // Eliminar espacios y caracteres no numéricos
        const telefonoLimpio = telefono.replace(/\s/g, '').replace(/[^0-9]/g, '');
        
        // Validar que sea exactamente 10 dígitos
        if (telefonoLimpio.length === 0) return { valido: true, mensaje: '' };
        if (telefonoLimpio.length !== 10) {
            return { valido: false, mensaje: 'El teléfono debe tener exactamente 10 dígitos' };
        }
        
        // Validar que no empiece con 0 o 1 (opcional, solo números válidos en México)
        if (telefonoLimpio[0] === '0') {
            return { valido: false, mensaje: 'El teléfono no puede empezar con 0' };
        }
        
        return { valido: true, mensaje: '' };
    }
    
    function formatearTelefono(input) {
        // Limpiar el valor actual
        let valor = input.value.replace(/\D/g, '');
        
        // Limitar a 10 dígitos
        if (valor.length > 10) {
            valor = valor.slice(0, 10);
        }
        
        // Actualizar el campo
        input.value = valor;
        
        // Validar
        const resultado = validarTelefono(valor);
        const errorDiv = document.getElementById('telefonoError');
        const hintDiv = document.getElementById('telefonoHint');
        
        if (!resultado.valido && valor.length > 0) {
            errorDiv.style.display = 'block';
            errorDiv.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>' + resultado.mensaje;
            hintDiv.style.display = 'none';
            input.classList.add('is-error');
            input.classList.remove('is-valid');
        } else {
            errorDiv.style.display = 'none';
            hintDiv.style.display = 'block';
            
            if (valor.length === 10) {
                input.classList.add('is-valid');
                input.classList.remove('is-error');
            } else {
                input.classList.remove('is-valid');
                input.classList.remove('is-error');
            }
        }
        
        return resultado.valido;
    }
    
    // Funciones para el manejo de contraseñas
    function togglePasswordVisibility(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return;
        
        const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
        field.setAttribute('type', type);
        
        const button = field.parentElement.querySelector('.toggle-password i');
        if (button) {
            button.classList.toggle('fa-eye');
            button.classList.toggle('fa-eye-slash');
        }
    }
    
    function generarContrasena(longitud = 12) {
        const mayusculas = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        const minusculas = 'abcdefghijkmnopqrstuvwxyz';
        const numeros = '23456789';
        const especiales = '!@#$%&*';
        
        let contrasena = '';
        contrasena += mayusculas[Math.floor(Math.random() * mayusculas.length)];
        contrasena += minusculas[Math.floor(Math.random() * minusculas.length)];
        contrasena += numeros[Math.floor(Math.random() * numeros.length)];
        contrasena += especiales[Math.floor(Math.random() * especiales.length)];
        
        const todos = mayusculas + minusculas + numeros + especiales;
        for (let i = contrasena.length; i < longitud; i++) {
            contrasena += todos[Math.floor(Math.random() * todos.length)];
        }
        
        return contrasena.split('').sort(() => Math.random() - 0.5).join('');
    }
    
    function evaluarFuerza(password) {
        let fuerza = 0;
        if (password.length >= 6) fuerza++;
        if (password.length >= 8) fuerza++;
        if (password.match(/[A-Z]/)) fuerza++;
        if (password.match(/[0-9]/)) fuerza++;
        if (password.match(/[^A-Za-z0-9]/)) fuerza++;
        
        if (password.length === 0) return null;
        
        if (fuerza <= 2) return { nivel: 1, texto: 'Débil', clase: 'strength-weak', color: '#dc3545', icon: 'exclamation-triangle' };
        if (fuerza <= 3) return { nivel: 2, texto: 'Media', clase: 'strength-medium', color: '#ffc107', icon: 'chart-line' };
        return { nivel: 3, texto: 'Fuerte', clase: 'strength-strong', color: '#28a745', icon: 'check-circle' };
    }
    
    function actualizarFuerza() {
        const password = document.getElementById('new_password');
        if (!password) return;
        
        const fuerza = evaluarFuerza(password.value);
        const container = document.getElementById('passwordStrength');
        
        if (!container) return;
        
        if (!fuerza) {
            container.innerHTML = '';
            return;
        }
        
        container.innerHTML = `
            <div class="strength-meter ${fuerza.clase}"></div>
            <div class="strength-text" style="color: ${fuerza.color}">
                <i class="fas fa-${fuerza.icon} me-1"></i>
                Contraseña ${fuerza.texto}
            </div>
        `;
        
        verificarCoincidencia();
    }
    
    function verificarCoincidencia() {
        const newPass = document.getElementById('new_password');
        const confirmPass = document.getElementById('confirm_password');
        const matchDiv = document.getElementById('passwordMatch');
        
        if (!newPass || !confirmPass || !matchDiv) return;
        
        if (confirmPass.value.length === 0) {
            matchDiv.innerHTML = '';
            return;
        }
        
        if (newPass.value === confirmPass.value) {
            matchDiv.innerHTML = '<div class="input-hint" style="color: #28a745;"><i class="fas fa-check-circle me-1"></i>Las contraseñas coinciden</div>';
        } else {
            matchDiv.innerHTML = '<div class="error-message"><i class="fas fa-exclamation-circle me-1"></i>Las contraseñas no coinciden</div>';
        }
    }
    
    // ========== FUNCIONES PARA LA FOTO ==========
    function subirFoto(file) {
        const formData = new FormData();
        formData.append('foto', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        Swal.fire({
            title: 'Subiendo foto...',
            text: 'Por favor espera',
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
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message,
                    confirmButtonColor: '#dc3545'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al conectar con el servidor',
                confirmButtonColor: '#dc3545'
            });
        });
    }
    
    function eliminarFoto() {
        Swal.fire({
            title: '¿Eliminar foto de perfil?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Eliminando foto...',
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
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            confirmButtonColor: '#dc3545'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al eliminar la foto',
                        confirmButtonColor: '#dc3545'
                    });
                });
            }
        });
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // ========== VALIDACIÓN DE TELÉFONO EN TIEMPO REAL ==========
        const telefonoInput = document.getElementById('telefono');
        if (telefonoInput) {
            telefonoInput.addEventListener('input', function(e) {
                // Solo permitir números
                this.value = this.value.replace(/[^0-9]/g, '');
                
                // Limitar a 10 dígitos
                if (this.value.length > 10) {
                    this.value = this.value.slice(0, 10);
                }
                
                // Validar
                const resultado = validarTelefono(this.value);
                const errorDiv = document.getElementById('telefonoError');
                const hintDiv = document.getElementById('telefonoHint');
                
                if (!resultado.valido && this.value.length > 0) {
                    errorDiv.style.display = 'block';
                    errorDiv.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>' + resultado.mensaje;
                    hintDiv.style.display = 'none';
                    this.classList.add('is-error');
                    this.classList.remove('is-valid');
                } else {
                    errorDiv.style.display = 'none';
                    hintDiv.style.display = 'block';
                    
                    if (this.value.length === 10) {
                        this.classList.add('is-valid');
                        this.classList.remove('is-error');
                    } else {
                        this.classList.remove('is-valid');
                        this.classList.remove('is-error');
                    }
                }
            });
            
            // También validar al perder el foco
            telefonoInput.addEventListener('blur', function() {
                if (this.value.length > 0 && this.value.length !== 10) {
                    const errorDiv = document.getElementById('telefonoError');
                    const hintDiv = document.getElementById('telefonoHint');
                    errorDiv.style.display = 'block';
                    errorDiv.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>El teléfono debe tener exactamente 10 dígitos';
                    hintDiv.style.display = 'none';
                    this.classList.add('is-error');
                }
            });
            
            // Al hacer foco, ocultar error si estaba vacío
            telefonoInput.addEventListener('focus', function() {
                if (this.value.length === 0) {
                    const errorDiv = document.getElementById('telefonoError');
                    const hintDiv = document.getElementById('telefonoHint');
                    errorDiv.style.display = 'none';
                    hintDiv.style.display = 'block';
                    this.classList.remove('is-error');
                }
            });
        }
        
        // ========== MANEJAR CAMBIO DE FOTO ==========
        const btnCambiarFoto = document.getElementById('btnCambiarFoto');
        const inputFoto = document.getElementById('inputFoto');
        
        if (btnCambiarFoto) {
            btnCambiarFoto.addEventListener('click', function() {
                inputFoto.click();
            });
        }
        
        if (inputFoto) {
            inputFoto.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Formato no válido',
                        text: 'Usa JPG, PNG, GIF o WEBP',
                        confirmButtonColor: '#dc3545'
                    });
                    inputFoto.value = '';
                    return;
                }
                
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo muy grande',
                        text: 'La imagen no debe superar los 2MB',
                        confirmButtonColor: '#dc3545'
                    });
                    inputFoto.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    Swal.fire({
                        title: '¿Usar esta foto?',
                        imageUrl: e.target.result,
                        imageWidth: 200,
                        imageHeight: 200,
                        imageAlt: 'Vista previa',
                        showCancelButton: true,
                        confirmButtonText: '✅ Sí, usar esta foto',
                        cancelButtonText: '❌ Cancelar',
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#dc3545'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            subirFoto(file);
                        } else {
                            inputFoto.value = '';
                        }
                    });
                };
                reader.readAsDataURL(file);
            });
        }
        
        const btnEliminarFoto = document.getElementById('btnEliminarFoto');
        if (btnEliminarFoto) {
            btnEliminarFoto.addEventListener('click', eliminarFoto);
        }
        
        // ========== GENERAR CONTRASEÑA ==========
        const btnGenerar = document.getElementById('btnGenerarPassword');
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('confirm_password');
        
        if (btnGenerar) {
            btnGenerar.addEventListener('click', function() {
                const nuevaContrasena = generarContrasena(12);
                if (newPassword) newPassword.value = nuevaContrasena;
                if (confirmPassword) confirmPassword.value = nuevaContrasena;
                
                actualizarFuerza();
                verificarCoincidencia();
                
                Swal.fire({
                    title: '✨ ¡Contraseña generada!',
                    html: `
                        <div class="generated-password-modal">${nuevaContrasena}</div>
                        <p class="mt-3 mb-0 small">🔒 Guarda esta contraseña en un lugar seguro</p>
                        <p class="small text-muted">Puedes copiarla presionando Ctrl+C</p>
                    `,
                    icon: 'success',
                    confirmButtonText: '✅ Entendido',
                    confirmButtonColor: '#667eea'
                });
            });
        }
        
        if (newPassword) newPassword.addEventListener('input', actualizarFuerza);
        if (confirmPassword) confirmPassword.addEventListener('input', verificarCoincidencia);
        
        // ========== ACTUALIZAR PERFIL ==========
        const formPerfil = document.getElementById('formPerfil');
        if (formPerfil) {
            formPerfil.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validar teléfono antes de enviar
                const telefono = document.getElementById('telefono');
                if (telefono && telefono.value.length > 0) {
                    const resultado = validarTelefono(telefono.value);
                    if (!resultado.valido) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Teléfono inválido',
                            text: resultado.mensaje,
                            confirmButtonColor: '#dc3545'
                        });
                        telefono.focus();
                        return false;
                    }
                }
                
                const formData = new FormData(this);
                const btn = this.querySelector('button[type="submit"]');
                const originalText = btn.innerHTML;
                
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
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
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: '¡Perfil actualizado!',
                            text: data.message,
                            confirmButtonColor: '#28a745',
                            timer: 2000,
                            showConfirmButton: true
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: data.message || 'Ocurrió un error al actualizar',
                            confirmButtonColor: '#dc3545'
                        });
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo conectar con el servidor',
                        confirmButtonColor: '#dc3545'
                    });
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            });
        }
        
        // ========== CAMBIAR CONTRASEÑA ==========
        const formPassword = document.getElementById('formCambiarPassword');
        if (formPassword) {
            formPassword.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const currentPass = document.getElementById('current_password');
                const newPass = document.getElementById('new_password');
                const confirmPass = document.getElementById('confirm_password');
                
                if ((newPass && newPass.value) || (confirmPass && confirmPass.value)) {
                    if (!currentPass || !currentPass.value) {
                        Swal.fire({
                            title: '⚠️ Contraseña actual requerida',
                            text: 'Debes ingresar tu contraseña actual para cambiarla',
                            icon: 'warning',
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#dc3545'
                        });
                        return false;
                    }
                    
                    if (newPass && confirmPass && newPass.value !== confirmPass.value) {
                        Swal.fire({
                            title: '❌ Error',
                            text: 'Las contraseñas nuevas no coinciden',
                            icon: 'error',
                            confirmButtonText: 'Revisar',
                            confirmButtonColor: '#dc3545'
                        });
                        return false;
                    }
                    
                    if (newPass && newPass.value.length < 6 && newPass.value.length > 0) {
                        Swal.fire({
                            title: '❌ Contraseña muy corta',
                            text: 'La contraseña debe tener al menos 6 caracteres',
                            icon: 'error',
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#dc3545'
                        });
                        return false;
                    }
                }
                
                const formData = new FormData(this);
                const btn = this.querySelector('button[type="submit"]');
                const originalText = btn.innerHTML;
                
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Actualizando...';
                btn.disabled = true;
                
                Swal.fire({
                    title: 'Cambiando contraseña...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                
                fetch('{{ route("estudiante.perfil.cambiar-password") }}', {
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
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: '¡Contraseña actualizada!',
                            text: data.message,
                            confirmButtonColor: '#28a745',
                            timer: 2000,
                            showConfirmButton: true
                        }).then(() => {
                            formPassword.reset();
                            const strengthBar = document.getElementById('strengthBar');
                            const strengthText = document.getElementById('strengthText');
                            const matchDiv = document.getElementById('passwordMatch');
                            
                            if (strengthBar) strengthBar.style.width = '0%';
                            if (strengthText) strengthText.innerHTML = '';
                            if (matchDiv) matchDiv.innerHTML = '';
                            
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                        });
                    } else {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            confirmButtonColor: '#dc3545'
                        });
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo conectar con el servidor',
                        confirmButtonColor: '#dc3545'
                    });
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            });
        }
        
        // ========== BOTÓN RESET ==========
        const btnReset = document.getElementById('btnReset');
        if (btnReset) {
            btnReset.addEventListener('click', function() {
                Swal.fire({
                    title: '⚠️ ¿Restablecer cambios?',
                    text: 'Se perderán todos los cambios no guardados',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, restablecer',
                    cancelButtonText: 'Seguir editando',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            });
        }
        
        // ========== BOTÓN DASHBOARD ==========
        const btnDashboard = document.getElementById('btnDashboard');
        if (btnDashboard) {
            btnDashboard.addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                
                Swal.fire({
                    title: '🏠 ¿Ir al Dashboard?',
                    text: 'Los cambios no guardados se perderán',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, ir',
                    cancelButtonText: 'Quedarme',
                    confirmButtonColor: '#667eea',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });
        }
    });
    
    // Mensajes flash
    @if(session('success'))
        Swal.fire({
            title: '✅ ¡Éxito!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'Excelente',
            confirmButtonColor: '#28a745',
            timer: 3000,
            timerProgressBar: true
        });
    @endif
    
    @if(session('error'))
        Swal.fire({
            title: '❌ Error',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#dc3545'
        });
    @endif
</script>
@endpush