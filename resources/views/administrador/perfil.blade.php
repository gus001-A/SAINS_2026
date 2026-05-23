@extends('administrador.layouts.master')

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
                            <div class="profile-avatar">
                                <i class="fas fa-user-astronaut fa-3x"></i>
                            </div>
                            <div class="profile-status"></div>
                        </div>
                        <div>
                            <h1 class="display-6 fw-bold mb-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                                Mi Perfil
                            </h1>
                            <p class="text-muted mb-0">
                                <i class="fas fa-envelope me-2"></i>{{ $admin->correo ?? 'admin@sains.com' }}
                            </p>
                            @if($admin->administrador)
                            <p class="text-muted small mb-0 mt-1">
                                <i class="fas fa-user me-2"></i>{{ $admin->administrador->nombre ?? '' }} {{ $admin->administrador->apellido_paterno ?? '' }}
                            </p>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="btn-glass" id="btnDashboard">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </div>
            </div>

            <!-- Tarjeta principal con efecto glassmorphism -->
            <div class="profile-card">
                <form action="{{ route('admin.perfil.update') }}" method="POST" id="formPerfil">
                    @csrf
                    @method('PUT')
                    
                    <!-- Sección Email -->
                    <div class="profile-section">
                        <div class="section-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="section-content">
                            <h3 class="section-title">Información de contacto</h3>
                            <p class="section-subtitle">Actualiza tu correo electrónico principal</p>
                            
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="fas fa-at"></i>
                                    <span>Correo electrónico</span>
                                </label>
                                <input type="email" name="email" 
                                       class="input-modern @error('email') is-error @enderror" 
                                       value="{{ old('email', $admin->correo ?? '') }}" 
                                       placeholder="tu@email.com" required>
                                @error('email')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div class="input-hint">Este correo se usará para iniciar sesión</div>
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
                                    <input type="password" name="current_password" id="current_password"
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
                                            <input type="password" name="new_password" id="new_password"
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
                                    <input type="password" name="new_password_confirmation" id="confirm_password"
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
                                        <span>Mínimo 8 caracteres</span>
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
                            <i class="fas fa-times me-2"></i>Cancelar
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
    
    .profile-avatar:hover {
        transform: scale(1.05);
    }
    
    .profile-status {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 18px;
        height: 18px;
        background: #4caf50;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.1); }
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
        transition: color 0.3s ease;
    }
    
    .toggle-password:hover {
        color: #667eea;
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
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(245, 87, 108, 0.3);
    }
    
    .btn-generate-password:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(245, 87, 108, 0.4);
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
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }
    
    .btn-cancel {
        padding: 0.85rem 2rem;
        background: white;
        border: 2px solid #e1e4e8;
        border-radius: 50px;
        color: #6c757d;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-cancel:hover {
        border-color: #dc3545;
        color: #dc3545;
        transform: translateY(-2px);
    }
    
    @media (max-width: 768px) {
        .profile-section {
            flex-direction: column;
            padding: 1.5rem;
        }
        
        .tips-list {
            grid-template-columns: 1fr;
        }
        
        .profile-actions {
            flex-direction: column;
        }
        
        .profile-header .d-flex {
            justify-content: center;
            text-align: center;
            flex-direction: column;
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
</style>
@endpush

@push('scripts')
<script>
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
        if (password.length >= 8) fuerza++;
        if (password.match(/[A-Z]/)) fuerza++;
        if (password.match(/[0-9]/)) fuerza++;
        if (password.match(/[^A-Za-z0-9]/)) fuerza++;
        
        if (password.length === 0) return null;
        
        if (fuerza <= 1) return { nivel: 1, texto: 'Débil', clase: 'strength-weak', color: '#dc3545', icon: 'exclamation-triangle' };
        if (fuerza <= 2) return { nivel: 2, texto: 'Media', clase: 'strength-medium', color: '#ffc107', icon: 'chart-line' };
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
    
    document.addEventListener('DOMContentLoaded', function() {
        const btnGenerar = document.getElementById('btnGenerarPassword');
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('confirm_password');
        const form = document.getElementById('formPerfil');
        const btnReset = document.getElementById('btnReset');
        const btnDashboard = document.getElementById('btnDashboard');
        
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
                    background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                    color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#1a1a2e',
                    confirmButtonColor: '#667eea'
                });
            });
        }
        
        if (newPassword) {
            newPassword.addEventListener('input', actualizarFuerza);
        }
        
        if (confirmPassword) {
            confirmPassword.addEventListener('input', verificarCoincidencia);
        }
        
        if (form) {
            form.addEventListener('submit', function(e) {
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
                            confirmButtonColor: '#dc3545',
                            background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                            color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#1a1a2e'
                        });
                        return false;
                    }
                    
                    if (newPass && confirmPass && newPass.value !== confirmPass.value) {
                        Swal.fire({
                            title: '❌ Error',
                            text: 'Las contraseñas nuevas no coinciden',
                            icon: 'error',
                            confirmButtonText: 'Revisar',
                            confirmButtonColor: '#dc3545',
                            background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                            color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#1a1a2e'
                        });
                        return false;
                    }
                    
                    if (newPass && newPass.value.length < 8 && newPass.value.length > 0) {
                        Swal.fire({
                            title: '❌ Contraseña muy corta',
                            text: 'La contraseña debe tener al menos 8 caracteres',
                            icon: 'error',
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#dc3545',
                            background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                            color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#1a1a2e'
                        });
                        return false;
                    }
                }
                
                const hasPasswordChange = (newPass && newPass.value) !== '';
                
                Swal.fire({
                    title: '💾 ¿Guardar cambios?',
                    text: hasPasswordChange ? 'Se actualizará tu correo electrónico y contraseña' : 'Se actualizará tu correo electrónico',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '✅ Sí, guardar',
                    cancelButtonText: '❌ Cancelar',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#dc3545',
                    background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                    color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#1a1a2e'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: '🔄 Actualizando perfil...',
                            text: 'Por favor espera un momento',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                                form.submit();
                            }
                        });
                    }
                });
            });
        }
        
        if (btnReset) {
            btnReset.addEventListener('click', function() {
                Swal.fire({
                    title: '⚠️ ¿Cancelar cambios?',
                    text: 'Se perderán todos los cambios no guardados',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, cancelar',
                    cancelButtonText: 'Seguir editando',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                    color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#1a1a2e'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            });
        }
        
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
                    cancelButtonColor: '#6c757d',
                    background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
                    color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#1a1a2e'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });
        }
    });
    
    @if(session('success'))
        Swal.fire({
            title: '✅ ¡Éxito!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'Excelente',
            confirmButtonColor: '#28a745',
            background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#1a1a2e',
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
            confirmButtonColor: '#dc3545',
            background: document.body.classList.contains('dark-mode') ? '#1a1a2e' : '#fff',
            color: document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#1a1a2e'
        });
    @endif
    
    let formModificado = false;
    const campos = document.querySelectorAll('#formPerfil input');
    campos.forEach(campo => {
        if (campo.type !== 'hidden') {
            campo.addEventListener('input', () => { formModificado = true; });
            campo.addEventListener('change', () => { formModificado = true; });
        }
    });
    
    window.addEventListener('beforeunload', function(e) {
        if (formModificado) {
            e.preventDefault();
            e.returnValue = 'Tienes cambios sin guardar. ¿Seguro que quieres salir?';
            return e.returnValue;
        }
    });
    
    document.getElementById('formPerfil')?.addEventListener('submit', () => {
        formModificado = false;
    });
</script>
@endpush