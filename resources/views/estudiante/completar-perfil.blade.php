@extends('estudiante.layouts.app')

@section('title', 'Completar Perfil | SAINS')

@section('content')
<div class="completar-perfil-container">
    <div class="perfil-card">
        <!-- Header -->
        <div class="perfil-header">
            <div class="perfil-header-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="perfil-header-text">
                <h1>Completa tu perfil</h1>
                <p>Cuéntanos un poco sobre ti para personalizar tu experiencia</p>
            </div>
        </div>

        <!-- Formulario -->
        <form id="formCompletarPerfil" class="perfil-form">
            @csrf
            
            <!-- Sección: Información Personal -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-user-circle"></i>
                    <h3>Información Personal</h3>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre"><i class="fas fa-user"></i> Nombre(s) *</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ej: Juan Carlos" required>
                    </div>
                    <div class="form-group">
                        <label for="paterno"><i class="fas fa-user"></i> Apellido Paterno *</label>
                        <input type="text" id="paterno" name="paterno" class="form-control" placeholder="Ej: Rodríguez" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="materno"><i class="fas fa-user"></i> Apellido Materno</label>
                        <input type="text" id="materno" name="materno" class="form-control" placeholder="Ej: Pérez">
                    </div>
                    <div class="form-group">
                        <label for="telefono"><i class="fas fa-phone"></i> Teléfono celular *</label>
                        <input type="tel" id="telefono" name="telefono" class="form-control" placeholder="Ej: 5512345678" required>
                        <small class="form-text">10 dígitos, sin espacios ni guiones</small>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="telefono_casa"><i class="fas fa-phone-alt"></i> Teléfono de casa</label>
                        <input type="tel" id="telefono_casa" name="telefono_casa" class="form-control" placeholder="Ej: 5555555555">
                    </div>
                    <div class="form-group">
                        <label for="fecha_nacimiento"><i class="fas fa-calendar-alt"></i> Fecha de nacimiento *</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="sexo"><i class="fas fa-venus-mars"></i> Sexo *</label>
                        <select id="sexo" name="sexo" class="form-control" required>
                            <option value="">Selecciona una opción</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="correo"><i class="fas fa-envelope"></i> Correo electrónico</label>
                        <input type="email" id="correo" class="form-control" value="{{ Auth::user()->correo }}" disabled>
                        <small class="form-text">Tu correo registrado, no se puede modificar</small>
                    </div>
                </div>
            </div>

            <!-- Sección: Información Académica -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>Información Académica</h3>
                </div>
                
                <div class="form-group">
                    <label for="escuela_procedencia"><i class="fas fa-school"></i> Escuela de procedencia (Preparatoria)</label>
                    <select id="escuela_procedencia" name="escuela_procedencia" class="form-control">
                        <option value="">Selecciona tu preparatoria</option>
                        @php
                            $preparatorias = \App\Models\Preparatoria::orderBy('centro_educativo')->get();
                        @endphp
                        @foreach($preparatorias as $prepa)
                            <option value="{{ $prepa->id }}">{{ $prepa->centro_educativo }} ({{ $prepa->estado }})</option>
                        @endforeach
                    </select>
                    <small class="form-text">Si no aparece tu escuela, selecciona "Otra" o déjalo en blanco</small>
                </div>
                
                <div class="form-group">
                    <label for="universidad_interes"><i class="fas fa-university"></i> Universidad de interés</label>
                    <select id="universidad_interes" name="universidad_interes" class="form-control">
                        <option value="">Selecciona una universidad</option>
                        @php
                            $universidades = \App\Models\Universidad::orderBy('clave')->get();
                        @endphp
                        @foreach($universidades as $uni)
                            <option value="{{ $uni->id }}">{{ $uni->clave }} - {{ $uni->direccion }}</option>
                        @endforeach
                    </select>
                    <small class="form-text">¿A qué universidad te gustaría ingresar?</small>
                </div>
            </div>

            <!-- Sección: Cupón de descuento (opcional) -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-ticket-alt"></i>
                    <h3>¿Tienes un cupón de descuento?</h3>
                </div>
                
                <div class="form-group">
                    <label for="cupon"><i class="fas fa-tag"></i> Código de cupón</label>
                    <input type="text" id="cupon" name="cupon" class="form-control" placeholder="Ej: SAINS2024">
                    <small class="form-text">Si tienes un cupón, ingrésalo aquí para obtener un descuento en tu plan</small>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="window.location.href='{{ route('estudiante.dashboard') }}'">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="submit" class="btn-submit" id="btnSubmit">
                    <i class="fas fa-save me-2"></i>Guardar perfil
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.completar-perfil-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
    justify-content: center;
}

.perfil-card {
    background: white;
    border-radius: 32px;
    box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    width: 100%;
    transition: all 0.3s ease;
}

.perfil-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 32px;
    text-align: center;
    color: white;
}

.perfil-header-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 2rem;
}

.perfil-header-text h1 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.perfil-header-text p {
    font-size: 0.9rem;
    opacity: 0.9;
    margin: 0;
}

.perfil-form {
    padding: 32px;
}

.form-section {
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1px solid #e2e8f0;
}

.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
}

.section-title i {
    font-size: 1.5rem;
    color: #667eea;
}

.section-title h3 {
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0;
    color: #1e293b;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 0;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: #475569;
}

.form-group label i {
    font-size: 0.85rem;
    color: #667eea;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.9rem;
    transition: all 0.3s;
    background: white;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control:disabled {
    background: #f1f5f9;
    color: #64748b;
    cursor: not-allowed;
}

.form-text {
    font-size: 0.7rem;
    color: #94a3b8;
    margin-top: 4px;
    display: block;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 16px;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid #e2e8f0;
}

.btn-cancel, .btn-submit {
    padding: 12px 28px;
    border-radius: 40px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s;
    border: none;
}

.btn-cancel {
    background: #f1f5f9;
    color: #64748b;
}

.btn-cancel:hover {
    background: #e2e8f0;
    transform: translateY(-2px);
}

.btn-submit {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-submit:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

/* Loading spinner */
.btn-submit.loading {
    position: relative;
    pointer-events: none;
}

.btn-submit.loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 768px) {
    .completar-perfil-container {
        padding: 12px;
    }
    
    .perfil-header {
        padding: 24px;
    }
    
    .perfil-header-text h1 {
        font-size: 1.4rem;
    }
    
    .perfil-form {
        padding: 24px;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-cancel, .btn-submit {
        width: 100%;
        justify-content: center;
    }
}

/* Dark mode */
body.dark-mode .perfil-card {
    background: #1e293b;
}

body.dark-mode .form-section {
    border-bottom-color: #334155;
}

body.dark-mode .section-title h3 {
    color: #f1f5f9;
}

body.dark-mode .form-group label {
    color: #94a3b8;
}

body.dark-mode .form-control {
    background: #0f172a;
    border-color: #334155;
    color: #f1f5f9;
}

body.dark-mode .form-control:disabled {
    background: #0f172a;
    color: #94a3b8;
}

body.dark-mode .form-control:focus {
    border-color: #667eea;
}

body.dark-mode .btn-cancel {
    background: #334155;
    color: #e2e8f0;
}

body.dark-mode .btn-cancel:hover {
    background: #475569;
}

body.dark-mode .form-actions {
    border-top-color: #334155;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formCompletarPerfil');
    const btnSubmit = document.getElementById('btnSubmit');
    
    // Validaciones en tiempo real
    const telefonoInput = document.getElementById('telefono');
    if (telefonoInput) {
        telefonoInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });
    }
    
    const telefonoCasa = document.getElementById('telefono_casa');
    if (telefonoCasa) {
        telefonoCasa.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });
    }
    
    // Validar edad (mínimo 15 años, máximo 80)
    const fechaInput = document.getElementById('fecha_nacimiento');
    if (fechaInput) {
        fechaInput.addEventListener('change', function() {
            const fecha = new Date(this.value);
            const hoy = new Date();
            let edad = hoy.getFullYear() - fecha.getFullYear();
            const mesDiff = hoy.getMonth() - fecha.getMonth();
            
            if (mesDiff < 0 || (mesDiff === 0 && hoy.getDate() < fecha.getDate())) {
                edad--;
            }
            
            if (edad < 15) {
                Swal.fire({
                    title: 'Edad no válida',
                    text: 'Debes tener al menos 15 años para registrarte',
                    icon: 'warning',
                    confirmButtonColor: '#667eea'
                });
                this.value = '';
            } else if (edad > 80) {
                Swal.fire({
                    title: 'Edad no válida',
                    text: 'Por favor verifica tu fecha de nacimiento',
                    icon: 'warning',
                    confirmButtonColor: '#667eea'
                });
                this.value = '';
            }
        });
    }
    
    // Enviar formulario
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Validar campos requeridos
        const nombre = document.getElementById('nombre').value.trim();
        const paterno = document.getElementById('paterno').value.trim();
        const telefono = document.getElementById('telefono').value.trim();
        const fecha = document.getElementById('fecha_nacimiento').value;
        const sexo = document.getElementById('sexo').value;
        
        if (!nombre) {
            Swal.fire('Error', 'El nombre es requerido', 'error');
            document.getElementById('nombre').focus();
            return;
        }
        
        if (!paterno) {
            Swal.fire('Error', 'El apellido paterno es requerido', 'error');
            document.getElementById('paterno').focus();
            return;
        }
        
        if (!telefono) {
            Swal.fire('Error', 'El teléfono es requerido', 'error');
            document.getElementById('telefono').focus();
            return;
        }
        
        if (telefono.length !== 10) {
            Swal.fire('Error', 'El teléfono debe tener 10 dígitos', 'error');
            document.getElementById('telefono').focus();
            return;
        }
        
        if (!fecha) {
            Swal.fire('Error', 'La fecha de nacimiento es requerida', 'error');
            document.getElementById('fecha_nacimiento').focus();
            return;
        }
        
        if (!sexo) {
            Swal.fire('Error', 'El sexo es requerido', 'error');
            document.getElementById('sexo').focus();
            return;
        }
        
        // Mostrar loading
        btnSubmit.disabled = true;
        btnSubmit.classList.add('loading');
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
        
        // Preparar datos
        const formData = new FormData(form);
        
        try {
            const response = await fetch('{{ route("estudiante.completar.perfil") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                Swal.fire({
                    title: '¡Perfil completado!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#667eea',
                    timer: 2000,
                    showConfirmButton: true
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                Swal.fire('Error', data.message, 'error');
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('loading');
                btnSubmit.innerHTML = '<i class="fas fa-save me-2"></i>Guardar perfil';
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error', 'Ocurrió un error al guardar el perfil', 'error');
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('loading');
            btnSubmit.innerHTML = '<i class="fas fa-save me-2"></i>Guardar perfil';
        }
    });
});
</script>
@endsection