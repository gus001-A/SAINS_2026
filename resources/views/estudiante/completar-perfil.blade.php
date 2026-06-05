@extends('estudiante.layouts.app')

@section('title', 'Completar Perfil | SAINS')

@section('content')
<div class="completar-perfil-container">
    <div class="perfil-card">
        <div class="perfil-header">
            <div class="perfil-header-icon">
                <i class="fas fa-user-astronaut"></i>
            </div>
            <div class="perfil-header-text">
                <h1>¡Casi listo, futuro SAINS!</h1>
                <p>Completa estos datos para comenzar tu preparación</p>
            </div>
        </div>

        <form id="formCompletarPerfil" class="perfil-form">
            @csrf

            <!-- Sección 1: Información Personal -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-user-circle"></i>
                    <h3>Información Personal</h3>
                    <span class="required-badge">Obligatorio</span>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre(s) <span class="required-star">*</span></label>
                        <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ej: Juan Carlos"
                            required>
                        <div class="error-message" id="error-nombre"></div>
                    </div>
                    <div class="form-group">
                        <label for="paterno">Apellido Paterno <span class="required-star">*</span></label>
                        <input type="text" id="paterno" name="paterno" class="form-control" placeholder="Ej: Rodríguez"
                            required>
                        <div class="error-message" id="error-paterno"></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="materno">Apellido Materno</label>
                        <input type="text" id="materno" name="materno" class="form-control" placeholder="Ej: Pérez">
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono celular <span class="required-star">*</span></label>
                        <input type="tel" id="telefono" name="telefono" class="form-control"
                            placeholder="Ej: 5512345678" maxlength="10" required>
                        <small class="form-text">10 dígitos, solo números</small>
                        <div class="error-message" id="error-telefono"></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="telefono_casa">Teléfono de casa</label>
                        <input type="tel" id="telefono_casa" name="telefono_casa" class="form-control"
                            placeholder="Ej: 5555555555" maxlength="10">
                    </div>
                    <div class="form-group">
                        <label for="fecha_nacimiento">Fecha de nacimiento <span class="required-star">*</span></label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" required>
                        <small class="form-text">Debes tener al menos 14 años cumplidos</small>
                        <div class="error-message" id="error-fecha"></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="sexo">Sexo <span class="required-star">*</span></label>
                        <select id="sexo" name="sexo" class="form-control" required>
                            <option value="">Selecciona una opción</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                        <div class="error-message" id="error-sexo"></div>
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo electrónico</label>
                        <input type="email" id="correo" class="form-control" value="{{ Auth::user()->correo }}"
                            disabled>
                        <small class="form-text">Tu correo registrado, no se puede modificar</small>
                    </div>
                </div>
            </div>

            <!-- Sección 2: Información Académica -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>Información Académica</h3>
                    <span class="optional-badge">Opcional</span>
                </div>

                <div class="form-group">
                    <label for="escuela_procedencia">Escuela de procedencia (Preparatoria)</label>
                    <select id="escuela_procedencia" name="escuela_procedencia" class="form-control">
                        <option value="">Selecciona tu preparatoria</option>
                        @php
                        $preparatorias = \App\Models\Preparatoria::orderBy('centro_educativo')->get();
                        @endphp
                        @foreach($preparatorias as $prepa)
                        <option value="{{ $prepa->id }}">{{ $prepa->centro_educativo }} ({{ $prepa->estado }})</option>
                        @endforeach
                        <option value="otra">📝 Otra (Especificar)</option>
                    </select>
                    <div id="otra_prepa_container" style="display:none; margin-top: 10px;">
                        <input type="text" id="otra_prepa" name="otra_prepa" class="form-control"
                            placeholder="Escribe el nombre de tu preparatoria">
                    </div>
                </div>

                <div class="form-group">
                    <label for="universidad_interes">Universidad de interés</label>
                    <select id="universidad_interes" name="universidad_interes" class="form-control">
                        <option value="">Selecciona una universidad</option>
                        @php
                        $universidades = \App\Models\Universidad::orderBy('clave')->get();
                        @endphp
                        @foreach($universidades as $uni)
                        <option value="{{ $uni->id }}">{{ $uni->clave }} - {{ $uni->direccion }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Sección 3: Cupón -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-ticket-alt"></i>
                    <h3>¿Tienes un cupón de descuento?</h3>
                    <span class="optional-badge">Opcional</span>
                </div>

                <div class="form-group">
                    <label for="cupon">Código de cupón</label>
                    <input type="text" id="cupon" name="cupon" class="form-control" placeholder="Ej: SAINS2024">
                    <small class="form-text">Si tienes un cupón, ingrésalo aquí</small>
                </div>
            </div>

            <!-- Botones -->
            <div class="form-actions">
                <button type="button" class="btn-cancel"
                    onclick="window.location.href='{{ route('estudiante.dashboard') }}'">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="submit" class="btn-submit" id="btnSubmit">
                    <i class="fas fa-save"></i> Guardar perfil
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.completar-perfil-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    min-height: calc(100vh - 200px);
}

.perfil-card {
    background: white;
    border-radius: 32px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
}

.perfil-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px;
    text-align: center;
    color: white;
}

.perfil-header-icon {
    width: 90px;
    height: 90px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 2.5rem;
}

.perfil-header-text h1 {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 8px;
}

.perfil-header-text p {
    font-size: 1rem;
    opacity: 0.95;
}

.perfil-form {
    padding: 40px;
}

.form-section {
    margin-bottom: 40px;
    padding: 20px;
    background: #f8fafc;
    border-radius: 20px;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
    padding-bottom: 12px;
    border-bottom: 2px solid #e2e8f0;
}

.section-title i {
    font-size: 1.5rem;
    color: #667eea;
}

.section-title h3 {
    font-size: 1.2rem;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
    flex: 1;
}

.required-badge,
.optional-badge {
    font-size: 0.7rem;
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: 600;
}

.required-badge {
    background: #fee2e2;
    color: #dc2626;
}

.optional-badge {
    background: #e0e7ff;
    color: #4f46e5;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: #334155;
}

.required-star {
    color: #ef4444;
    font-size: 1rem;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.form-control.error {
    border-color: #ef4444;
    background-color: #fef2f2;
}

.form-control:disabled {
    background: #f1f5f9;
    color: #64748b;
}

.form-text {
    font-size: 0.7rem;
    color: #94a3b8;
    margin-top: 6px;
    display: block;
}

.error-message {
    font-size: 0.75rem;
    color: #ef4444;
    margin-top: 5px;
    display: none;
}

.error-message.show {
    display: block;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 16px;
    margin-top: 20px;
}

.btn-cancel,
.btn-submit {
    padding: 14px 32px;
    border-radius: 40px;
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-cancel {
    background: #f1f5f9;
    color: #64748b;
}

.btn-cancel:hover {
    background: #e2e8f0;
}

.btn-submit {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.btn-submit:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

.btn-submit.loading i {
    animation: spin 1s linear infinite;
}

@media (max-width: 768px) {
    .perfil-form {
        padding: 20px;
    }

    .form-row {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn-cancel,
    .btn-submit {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Script cargado');

    const form = document.getElementById('formCompletarPerfil');
    const btnSubmit = document.getElementById('btnSubmit');

    if (!form || !btnSubmit) {
        console.error('❌ Formulario o botón no encontrado');
        return;
    }

    console.log('✅ Formulario listo');

    // Validar edad mínima 14 años
    function validateAge(birthDate) {
        const today = new Date();
        const birth = new Date(birthDate);
        let age = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();

        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            age--;
        }

        if (age < 14) {
            return {
                valid: false,
                message: 'Debes tener al menos 14 años cumplidos.'
            };
        }
        return {
            valid: true,
            age: age
        };
    }

    // Configurar fecha máxima
    const fechaInput = document.getElementById('fecha_nacimiento');
    if (fechaInput) {
        const today = new Date();
        const maxDate = new Date(today.getFullYear() - 14, today.getMonth(), today.getDate());
        fechaInput.setAttribute('max', maxDate.toISOString().split('T')[0]);

        fechaInput.addEventListener('change', function() {
            if (this.value) {
                const validation = validateAge(this.value);
                if (!validation.valid) {
                    this.classList.add('error');
                    const errorDiv = document.getElementById('error-fecha');
                    errorDiv.textContent = validation.message;
                    errorDiv.classList.add('show');
                    this.value = '';
                } else {
                    this.classList.remove('error');
                    document.getElementById('error-fecha').classList.remove('show');
                }
            }
        });
    }

    // Validar teléfono (solo números y 10 dígitos)
    const telefonoInput = document.getElementById('telefono');
    if (telefonoInput) {
        telefonoInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
            const errorDiv = document.getElementById('error-telefono');

            if (this.value.length === 10) {
                this.classList.remove('error');
                errorDiv.classList.remove('show');
            } else if (this.value.length > 0) {
                this.classList.add('error');
                errorDiv.textContent = 'El teléfono debe tener 10 dígitos';
                errorDiv.classList.add('show');
            }
        });
    }

    // Mostrar campo "otra" preparatoria
    const escuelaSelect = document.getElementById('escuela_procedencia');
    const otraPrepaContainer = document.getElementById('otra_prepa_container');

    if (escuelaSelect) {
        escuelaSelect.addEventListener('change', function() {
            otraPrepaContainer.style.display = this.value === 'otra' ? 'block' : 'none';
        });
    }

    // Enviar formulario
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log('📤 Enviando formulario...');

        // Limpiar errores
        document.querySelectorAll('.error-message').forEach(el => el.classList.remove('show'));
        document.querySelectorAll('.form-control').forEach(el => el.classList.remove('error'));

        let hasError = false;

        // Validar nombre
        const nombre = document.getElementById('nombre').value.trim();
        if (!nombre) {
            showError('nombre', 'El nombre es requerido');
            hasError = true;
        }

        // Validar apellido paterno
        const paterno = document.getElementById('paterno').value.trim();
        if (!paterno) {
            showError('paterno', 'El apellido paterno es requerido');
            hasError = true;
        }

        // Validar teléfono
        const telefono = document.getElementById('telefono').value.trim();
        if (!telefono) {
            showError('telefono', 'El teléfono es requerido');
            hasError = true;
        } else if (telefono.length !== 10) {
            showError('telefono', 'El teléfono debe tener 10 dígitos');
            hasError = true;
        }

        // Validar fecha
        const fecha = document.getElementById('fecha_nacimiento').value;
        if (!fecha) {
            showError('fecha', 'La fecha de nacimiento es requerida');
            hasError = true;
        } else {
            const validation = validateAge(fecha);
            if (!validation.valid) {
                showError('fecha', validation.message);
                hasError = true;
            }
        }

        // Validar sexo
        const sexo = document.getElementById('sexo').value;
        if (!sexo) {
            showError('sexo', 'Selecciona tu sexo');
            hasError = true;
        }

        if (hasError) {
            Swal.fire({
                title: 'Campos incompletos',
                text: 'Por favor completa todos los campos obligatorios',
                icon: 'warning',
                confirmButtonColor: '#667eea'
            });
            return;
        }

        // Mostrar loading
        btnSubmit.disabled = true;
        btnSubmit.classList.add('loading');
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

        // Preparar datos
        const formData = new FormData(form);

        // Manejar "otra" preparatoria
        if (escuelaSelect.value === 'otra') {
            const otraPrepa = document.getElementById('otra_prepa').value;
            if (otraPrepa) {
                formData.append('otra_preparatoria', otraPrepa);
            }
            formData.delete('escuela_procedencia');
            formData.append('escuela_procedencia', 'otra');
        }

        try {
            const response = await fetch('{{ route("estudiante.completar.perfil") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                        .content,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();
            console.log('Respuesta:', data);

            if (data.success) {
                Swal.fire({
                    title: '¡Perfil completado!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#667eea',
                    timer: 2000
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                Swal.fire('Error', data.message, 'error');
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('loading');
                btnSubmit.innerHTML = '<i class="fas fa-save"></i> Guardar perfil';
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error', 'Ocurrió un error al guardar el perfil', 'error');
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('loading');
            btnSubmit.innerHTML = '<i class="fas fa-save"></i> Guardar perfil';
        }
    });

    function showError(fieldId, message) {
        const errorDiv = document.getElementById(`error-${fieldId}`);
        const input = document.getElementById(fieldId === 'fecha' ? 'fecha_nacimiento' : fieldId);

        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.classList.add('show');
        }

        if (input) {
            input.classList.add('error');
        }
    }
});
</script>
@endsection