<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Restablece tu contraseña para continuar con tu preparación universitaria">
    <meta name="author" content="SAINS">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Restablecer Contraseña | SAINS</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #818cf8;
            --secondary: #ec4899;
            --secondary-dark: #db2777;
            --success: #10b981;
            --dark: #0f172a;
            --gray: #64748b;
            --gray-light: #f8fafc;
            --white: #ffffff;
            --gradient-primary: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --gradient-secondary: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--gray-light);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }
        
        /* Navbar */
        .navbar {
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .navbar.scrolled {
            padding: 0.5rem 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        
        .navbar-brand img {
            height: 50px;
            transition: height 0.3s ease;
        }
        
        .navbar.scrolled .navbar-brand img {
            height: 40px;
        }
        
        .nav-link {
            font-weight: 600;
            color: var(--dark) !important;
            transition: all 0.3s ease;
            margin: 0 0.5rem;
            position: relative;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s ease;
            border-radius: 2px;
        }
        
        .nav-link:hover::after {
            width: 30px;
        }
        
        .nav-link:hover {
            color: var(--primary) !important;
        }
        
        .btn-login {
            background: var(--gradient-primary);
            color: white !important;
            padding: 0.5rem 1.5rem !important;
            border-radius: 50px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 5px rgba(79,70,229,0.3);
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79,70,229,0.4);
            color: white !important;
        }
        
        /* Reset Card */
        .reset-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 76px);
            padding: 40px 20px;
        }
        
        .reset-card {
            background: white;
            border-radius: 32px;
            padding: 40px;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 30px 60px rgba(0,0,0,0.2);
            animation: fadeInUp 0.6s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .reset-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .reset-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea15, #764ba215);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .reset-icon i {
            font-size: 2.5rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .reset-header h2 {
            font-weight: 800;
            font-size: 1.75rem;
            margin-bottom: 8px;
            color: var(--dark);
        }
        
        .reset-header p {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        /* Form Styles */
        .form-floating {
            margin-bottom: 1rem;
        }
        
        .form-floating > .form-control {
            border-radius: 16px;
            border: 2px solid #e2e8f0;
            padding: 1rem 0.75rem;
            transition: all 0.3s ease;
            background: var(--white);
            height: 58px;
        }
        
        .form-floating > .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79,70,229,0.1);
        }
        
        .form-floating > label {
            color: var(--gray);
            font-weight: 500;
            padding: 1rem 0.75rem;
        }
        
        .position-relative {
            position: relative;
        }
        
        .btn-toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            background: none;
            border: none;
            color: var(--gray);
            transition: color 0.3s ease;
            z-index: 10;
        }
        
        .btn-toggle-password:hover {
            color: var(--primary);
        }
        
        /* Password Requirements */
        .password-requirements {
            background: #f8fafc;
            border-radius: 16px;
            padding: 15px;
            margin-top: 15px;
        }
        
        .password-requirements p {
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--gray);
        }
        
        .requirement {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.7rem;
            margin-bottom: 5px;
            color: #64748b;
            transition: all 0.3s ease;
        }
        
        .requirement i {
            font-size: 0.7rem;
            width: 16px;
        }
        
        .requirement.valid {
            color: var(--success);
        }
        
        .requirement.valid i {
            color: var(--success);
        }
        
        /* Button */
        .btn-reset {
            background: var(--gradient-primary);
            color: white;
            border-radius: 50px;
            padding: 14px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: none;
            width: 100%;
            margin-top: 20px;
        }
        
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79,70,229,0.4);
            color: white;
        }
        
        .btn-reset:disabled {
            opacity: 0.6;
            transform: none;
        }
        
        /* Footer */
        .footer {
            background: #0f172a;
            color: white;
            padding: 3rem 0 2rem;
            margin-top: auto;
        }
        
        .footer a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer a:hover {
            color: white;
        }
        
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .reset-card {
                padding: 30px 20px;
            }
            
            .reset-header h2 {
                font-size: 1.5rem;
            }
            
            .navbar-brand img {
                height: 40px;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo_u.png') }}" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/#nosotros') }}">Nosotros</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/#metodo') }}">Método</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/#docentes') }}">Docentes</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/#plan') }}">Plan Premium</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/#contacto') }}">Contacto</a></li>
                </ul>
                <div class="d-flex">
                    <a href="{{ url('/') }}" class="btn btn-login">
                        <i class="fas fa-arrow-left me-2"></i>Volver al inicio
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Reset Password Container -->
    <div class="reset-container">
        <div class="reset-card">
            <div class="reset-header">
                <div class="reset-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h2>Restablecer contraseña</h2>
                <p>Ingresa tu nueva contraseña para continuar</p>
            </div>
            
            <form id="resetPasswordForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
                
                <div class="form-floating position-relative">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Nueva contraseña" required>
                    <label><i class="fas fa-lock me-2 text-primary"></i>Nueva contraseña</label>
                    <button type="button" class="btn-toggle-password" data-target="password">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                
                <div class="form-floating position-relative">
                    <input type="password" name="password_confirmation" class="form-control" id="passwordConfirm" placeholder="Confirmar contraseña" required>
                    <label><i class="fas fa-check-circle me-2 text-primary"></i>Confirmar contraseña</label>
                    <button type="button" class="btn-toggle-password" data-target="passwordConfirm">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                
                <!-- Password Requirements -->
                <div class="password-requirements" id="passwordRequirements">
                    <p><i class="fas fa-shield-alt me-1"></i> Requisitos de seguridad:</p>
                    <div class="requirement" id="req-length">
                        <i class="fas fa-circle"></i>
                        <span>Mínimo 6 caracteres</span>
                    </div>
                    <div class="requirement" id="req-number">
                        <i class="fas fa-circle"></i>
                        <span>Al menos un número</span>
                    </div>
                    <div class="requirement" id="req-letter">
                        <i class="fas fa-circle"></i>
                        <span>Al menos una letra mayúscula</span>
                    </div>
                    <div class="requirement" id="req-match">
                        <i class="fas fa-circle"></i>
                        <span>Las contraseñas coinciden</span>
                    </div>
                </div>
                
                <button type="submit" class="btn-reset" id="submitBtn">
                    <i class="fas fa-save me-2"></i>Actualizar contraseña
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <img src="{{ asset('images/logo_u.png') }}" alt="Logo" height="50" class="mb-3" style="filter: brightness(0) invert(1);">
                    <p class="small text-white-50">SAINS es una empresa graduada de la Incubadora de Base Tecnológica MIDAS UAEM y galardonada con el Premio Nacional Cuezcomate 2016 a la innovación y transferencia tecnológica.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3">Enlaces rápidos</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/#nosotros') }}">Nosotros</a></li>
                        <li class="mb-2"><a href="{{ url('/#metodo') }}">Método</a></li>
                        <li class="mb-2"><a href="{{ url('/#docentes') }}">Docentes</a></li>
                        <li class="mb-2"><a href="{{ url('/#plan') }}">Plan Premium</a></li>
                        <li><a href="{{ route('terms') }}">Términos y condiciones</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3">Contacto</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fab fa-whatsapp me-2"></i> <a href="https://wa.me/527771886018">777 188 6018</a></li>
                        <li class="mb-2"><i class="fab fa-whatsapp me-2"></i> <a href="https://wa.me/527772505603">777 250 5603</a></li>
                        <li class="mb-2"><i class="fab fa-facebook me-2"></i> <a href="#">sains.ingreso</a></li>
                        <li><i class="fas fa-envelope me-2"></i> <a href="mailto:ayuda@ingresoalauni.com">ayuda@ingresoalauni.com</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4" style="background: rgba(255,255,255,0.1);">
            <div class="text-center">
                <div class="social-links mb-3">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
                <p class="small text-white-50">© 2025 SAINS. Todos los derechos reservados. Convocatoria 2026</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) navbar.classList.add('scrolled');
            else navbar.classList.remove('scrolled');
        });
        
        // Toggle password visibility
        document.querySelectorAll('.btn-toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const target = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (target.type === 'password') {
                    target.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    target.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
        
        // Password validation
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('passwordConfirm');
        const submitBtn = document.getElementById('submitBtn');
        
        const reqLength = document.getElementById('req-length');
        const reqNumber = document.getElementById('req-number');
        const reqLetter = document.getElementById('req-letter');
        const reqMatch = document.getElementById('req-match');
        
        function validatePassword() {
            const pwd = password.value;
            const pwdConfirm = passwordConfirm.value;
            let isValid = true;
            
            // Length validation (min 6 characters)
            if (pwd.length >= 6) {
                reqLength.classList.add('valid');
                reqLength.querySelector('i').classList.remove('fa-circle');
                reqLength.querySelector('i').classList.add('fa-check-circle');
            } else {
                reqLength.classList.remove('valid');
                reqLength.querySelector('i').classList.remove('fa-check-circle');
                reqLength.querySelector('i').classList.add('fa-circle');
                isValid = false;
            }
            
            // Number validation
            if (/\d/.test(pwd)) {
                reqNumber.classList.add('valid');
                reqNumber.querySelector('i').classList.remove('fa-circle');
                reqNumber.querySelector('i').classList.add('fa-check-circle');
            } else {
                reqNumber.classList.remove('valid');
                reqNumber.querySelector('i').classList.remove('fa-check-circle');
                reqNumber.querySelector('i').classList.add('fa-circle');
                isValid = false;
            }
            
            // Uppercase letter validation
            if (/[A-Z]/.test(pwd)) {
                reqLetter.classList.add('valid');
                reqLetter.querySelector('i').classList.remove('fa-circle');
                reqLetter.querySelector('i').classList.add('fa-check-circle');
            } else {
                reqLetter.classList.remove('valid');
                reqLetter.querySelector('i').classList.remove('fa-check-circle');
                reqLetter.querySelector('i').classList.add('fa-circle');
                isValid = false;
            }
            
            // Match validation
            if (pwd === pwdConfirm && pwd !== '') {
                reqMatch.classList.add('valid');
                reqMatch.querySelector('i').classList.remove('fa-circle');
                reqMatch.querySelector('i').classList.add('fa-check-circle');
            } else {
                reqMatch.classList.remove('valid');
                reqMatch.querySelector('i').classList.remove('fa-check-circle');
                reqMatch.querySelector('i').classList.add('fa-circle');
                isValid = false;
            }
            
            submitBtn.disabled = !isValid;
            return isValid;
        }
        
        password.addEventListener('input', validatePassword);
        passwordConfirm.addEventListener('input', validatePassword);
        
        // Form submission
        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validatePassword()) {
                Swal.fire({
                    title: 'Contraseña inválida',
                    text: 'Por favor, cumple con todos los requisitos de seguridad',
                    icon: 'warning',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }
            
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Actualizando...';
            btn.disabled = true;
            
            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => { data[key] = value; });
            
            fetch('{{ route("password.update") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '¡Contraseña actualizada!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#4f46e5',
                        confirmButtonText: 'Iniciar sesión'
                    }).then(() => {
                        window.location.href = '/';
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'error',
                        confirmButtonColor: '#4f46e5'
                    });
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor. Intenta de nuevo más tarde.',
                    icon: 'error',
                    confirmButtonColor: '#4f46e5'
                });
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });
    </script>
</body>
</html>