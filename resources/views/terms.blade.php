<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Términos y condiciones del curso SAINS para preparación universitaria">
    <meta name="keywords" content="términos, condiciones, curso, SAINS, universidad, ingreso, EXANI-II">
    <meta name="author" content="SAINS">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>SAINS | Términos y Condiciones 2026</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a0ca3;
            --primary-light: #6c8cff;
            --secondary: #f72585;
            --success: #4caf50;
            --warning: #ff9800;
            --dark: #0f0f1a;
            --gray: #6c757d;
            --light: #f8f9fa;
            --white: #ffffff;
            --gradient-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-3: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            overflow-x: hidden;
            background: var(--light);
            transition: background-color 0.3s ease, color 0.2s ease;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--light);
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
            box-shadow: 0 2px 20px rgba(0,0,0,0.05);
        }
        
        .navbar.scrolled {
            padding: 0.5rem 0;
            box-shadow: 0 4px 30px rgba(0,0,0,0.1);
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
        }
        
        .nav-link:hover::after {
            width: 30px;
        }
        
        .nav-link:hover {
            color: var(--primary) !important;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white !important;
            padding: 0.5rem 1.5rem !important;
            border-radius: 50px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(67,97,238,0.4);
            color: white !important;
        }
        
        /* Theme Toggle */
        .theme-toggle {
            position: relative;
            width: 60px;
            height: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 30px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 8px;
            margin-left: 15px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .theme-toggle i {
            font-size: 14px;
            color: white;
            z-index: 1;
            transition: all 0.3s ease;
        }
        
        .theme-toggle .toggle-ball {
            position: absolute;
            width: 24px;
            height: 24px;
            background: white;
            border-radius: 50%;
            left: 3px;
            transition: transform 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .theme-toggle.dark .toggle-ball {
            transform: translateX(30px);
        }
        
        .theme-toggle:hover {
            transform: scale(1.05);
            box-shadow: 0 2px 10px rgba(102,126,234,0.5);
        }
        
        /* Hero Section */
        .hero {
            background: var(--gradient-1);
            min-height: 50vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.15;
        }
        
        .hero::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 20s infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-50px, -50px); }
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
        }
        
        .hero h1 {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        
        .hero .subtitle {
            font-size: 1.1rem;
            opacity: 0.95;
        }
        
        /* Content Section */
        .lgx-inner {
            padding: 80px 0;
        }
        
        .lgx-heading {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .lgx-heading .heading {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .lgx-heading .heading::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--primary);
            border-radius: 3px;
        }
        
        .lgx-content-text {
            text-align: justify;
            line-height: 1.8;
        }
        
        .lgx-content-text p {
            margin-bottom: 1.5rem;
            font-size: 1rem;
            color: var(--dark);
        }
        
        .lgx-content-text strong {
            color: var(--primary);
        }
        
        .lgx-content-text ul {
            margin-bottom: 1.5rem;
            padding-left: 2rem;
        }
        
        .lgx-content-text li {
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
        
        .lgx-content-text a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .lgx-content-text a:hover {
            color: var(--primary-dark);
        }
        
        /* Garantía Section */
        .lgx-registration-simple {
            background: var(--gradient-1);
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .lgx-registration-simple::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .lgx-registration-simple::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .lgx-inner {
            position: relative;
            z-index: 2;
        }
        
        .lgx-heading-white .heading {
            color: white;
            background: none;
            -webkit-background-clip: unset;
            background-clip: unset;
        }
        
        .lgx-heading-white .heading::after {
            background: white;
        }
        
        .lgx-heading-white .subheading {
            color: rgba(255,255,255,0.9);
            font-size: 1.3rem;
            margin-top: 1rem;
        }
        
        .lgx-heading-white .lead {
            color: rgba(255,255,255,0.9);
            font-size: 1.1rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .lgx-heading-white .lead a {
            color: white;
            text-decoration: underline;
        }
        
        .lgx-heading-white .lead a:hover {
            color: var(--secondary);
        }
        
        .guarantee-img {
            display: block;
            margin: 2rem auto;
            max-width: 200px;
            filter: drop-shadow(0 5px 15px rgba(0,0,0,0.2));
            transition: transform 0.3s ease;
        }
        
        .guarantee-img:hover {
            transform: scale(1.05);
        }
        
        /* Modal Styles */
        .modal-content {
            border-radius: 20px;
            border: none;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
        }
        
        .auth-header {
            position: relative;
            overflow: hidden;
        }
        
        .auth-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .form-floating {
            position: relative;
        }
        
        .form-floating > .form-control {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 1rem 0.75rem;
            transition: all 0.3s ease;
        }
        
        .form-floating > .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .form-floating > label {
            padding: 1rem 0.75rem;
            color: #6c757d;
        }
        
        .btn-toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            z-index: 10;
            transition: color 0.3s ease;
        }
        
        .btn-toggle-password:hover {
            color: #667eea !important;
        }
        
        /* Footer */
        .footer {
            background: var(--dark);
            color: white;
            padding: 4rem 0 2rem;
            transition: background 0.3s ease;
        }
        
        .footer a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer a:hover {
            color: white;
        }
        
        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }
        
        /* Botón volver al inicio */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
        }
        
        /* Dark Mode */
        body.dark-mode {
            background: #0a0a0f;
        }
        
        body.dark-mode .navbar {
            background: rgba(10,10,15,0.98);
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }
        
        body.dark-mode .nav-link {
            color: #ffffff !important;
        }
        
        body.dark-mode .lgx-content-text {
            color: rgba(255,255,255,0.8);
        }
        
        body.dark-mode .lgx-content-text p {
            color: rgba(255,255,255,0.8);
        }
        
        body.dark-mode .footer {
            background: #05050a;
        }
        
        body.dark-mode .lgx-registration-simple {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
        }
        
        body.dark-mode .modal-content {
            background: #1a1a2e;
        }
        
        body.dark-mode .form-control {
            background-color: #1a1a2e;
            border-color: rgba(102,126,234,0.3);
            color: white;
        }
        
        body.dark-mode .form-control:focus {
            background-color: #1a1a2e;
            color: white;
        }
        
        body.dark-mode .form-floating > label {
            color: rgba(255,255,255,0.6);
        }
        
        body.dark-mode .btn-close-white {
            filter: invert(1);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }
            
            .lgx-heading .heading {
                font-size: 1.8rem;
            }
            
            .lgx-heading-white .subheading {
                font-size: 1rem;
            }
            
            .lgx-inner {
                padding: 50px 0;
            }
        }
        
        /* Utility Classes */
        .text-justify {
            text-align: justify;
        }
        
        .lead {
            font-size: 1rem;
            line-height: 1.6;
        }
    </style>
</head>
<body>

    <!-- Modal Login/Register -->
    <div class="modal fade" id="authModal" tabindex="-1">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <!-- Header -->
                    <div class="auth-header text-center p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px 20px 0 0;">
                        <button type="button" class="btn-close btn-close-white float-end" data-bs-dismiss="modal"></button>
                        <img src="{{ asset('images/logo_u.png') }}" alt="Logo" height="60" style="filter: brightness(0) invert(1);">
                        <h3 class="text-white mt-3 mb-1 fw-bold" id="authTitle">Iniciar sesión</h3>
                        <p class="text-white-50 mb-0 small" id="authSubtitle">Ingresa a tu cuenta para continuar</p>
                    </div>
                    
                    <!-- Formulario de LOGIN -->
                    <div class="p-4" id="loginFormContainer">
                        <form id="loginForm" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-floating mt-2">
                                <input type="email" name="email" class="form-control" id="loginEmail" placeholder="correo@ejemplo.com" required>
                                <label for="loginEmail"><i class="fas fa-envelope me-2 text-primary"></i>Correo electrónico</label>
                            </div>
                            
                            <div class="form-floating mt-3 position-relative">
                                <input type="password" name="password" class="form-control" id="loginPassword" placeholder="Contraseña" required>
                                <label for="loginPassword"><i class="fas fa-lock me-2 text-primary"></i>Contraseña</label>
                                <button type="button" class="btn-toggle-password" data-target="loginPassword">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="rememberMe">
                                    <label class="form-check-label small" for="rememberMe">Recordarme</label>
                                </div>
                                <a href="#" class="text-primary small text-decoration-none" id="forgotPassword">
                                    <i class="fas fa-key me-1"></i> ¿Olvidaste tu contraseña?
                                </a>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-3 mt-3 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 12px;">
                                <i class="fas fa-sign-in-alt me-2"></i> Iniciar sesión
                            </button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <p class="mb-0 text-muted small">¿No tienes cuenta?</p>
                            <a href="#" class="text-primary fw-bold text-decoration-none" id="showRegisterBtn" style="cursor: pointer;">
                                <i class="fas fa-user-plus me-1"></i> Crear cuenta gratis
                            </a>
                        </div>
                    </div>
                    
                    <!-- Formulario de REGISTRO (oculto inicialmente) -->
                    <div class="p-4" id="registerFormContainer" style="display: none;">
                        <form id="registerForm" action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="form-floating">
                                        <input type="text" name="name" class="form-control" id="regName" placeholder="Nombre" required>
                                        <label for="regName"><i class="fas fa-user me-2 text-primary"></i>Nombre</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating">
                                        <input type="text" name="lastname" class="form-control" id="regLastname" placeholder="Apellido" required>
                                        <label for="regLastname"><i class="fas fa-user me-2 text-primary"></i>Apellido</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-floating mt-3">
                                <input type="email" name="email" class="form-control" id="regEmail" placeholder="correo@ejemplo.com" required>
                                <label for="regEmail"><i class="fas fa-envelope me-2 text-primary"></i>Correo electrónico</label>
                            </div>
                            
                            <div class="form-floating mt-3">
                                <input type="tel" name="phone" class="form-control" id="regPhone" placeholder="Teléfono" required>
                                <label for="regPhone"><i class="fas fa-phone me-2 text-primary"></i>Teléfono</label>
                            </div>
                            
                            <div class="row g-3 mt-1">
                                <div class="col-6 position-relative">
                                    <div class="form-floating">
                                        <input type="password" name="password" class="form-control" id="regPassword" placeholder="Contraseña" required>
                                        <label for="regPassword"><i class="fas fa-lock me-2 text-primary"></i>Contraseña</label>
                                        <button type="button" class="btn-toggle-password" data-target="regPassword">
                                            <i class="far fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6 position-relative">
                                    <div class="form-floating">
                                        <input type="password" name="password_confirmation" class="form-control" id="regPasswordConfirm" placeholder="Confirmar contraseña" required>
                                        <label for="regPasswordConfirm"><i class="fas fa-check-circle me-2 text-primary"></i>Confirmar</label>
                                        <button type="button" class="btn-toggle-password" data-target="regPasswordConfirm">
                                            <i class="far fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label small" for="terms">
                                    Acepto los <a href="{{ route('terms') }}" target="_blank" class="text-primary">Términos y condiciones</a>
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-3 mt-3 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 12px;">
                                <i class="fas fa-user-plus me-2"></i> Registrarme
                            </button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <p class="mb-0 text-muted small">¿Ya tienes cuenta?</p>
                            <a href="#" class="text-primary fw-bold text-decoration-none" id="showLoginBtn" style="cursor: pointer;">
                                <i class="fas fa-sign-in-alt me-1"></i> Iniciar sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo_u.png') }}" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#nosotros">Nosotros</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#metodo">Método</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#docentes">Docentes</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#plan">Plan Premium</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#contacto">Contacto</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    <button class="btn btn-login" data-bs-toggle="modal" data-bs-target="#authModal">Iniciar sesión</button>
                    <div class="theme-toggle ms-3" id="themeToggle">
                        <i class="fas fa-sun"></i>
                        <i class="fas fa-moon"></i>
                        <div class="toggle-ball"></div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content text-center" data-aos="fade-up">
                <span class="hero-badge" style="display: inline-block; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); padding: 0.5rem 1.5rem; border-radius: 50px; font-size: 0.875rem; margin-bottom: 1.5rem; font-weight: 600;">
                    <i class="fas fa-file-contract me-2"></i> Información Legal
                </span>
                <h1>Términos y Condiciones</h1>
                <p class="subtitle">Curso de preparación para el examen de ingreso a la universidad 2026</p>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="lgx-inner">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="lgx-heading" data-aos="fade-up">
                        <h2 class="heading">Términos y condiciones del curso SAINS</h2>
                    </div>
                    <div class="lgx-content-text" data-aos="fade-up">
                        <p class="lead text-justify">
                            El presente contrato contiene los términos y condiciones de prestación de servicios y venta de cursos de Preparación para el Examen de Ingreso a la UNIVERSIDAD a través del portal de internet <a href="#">www.ingresoalauni.com</a>, <a href="#">www.examendeingreso.com</a> bajo los siguientes términos y condiciones que serán aplicadas al presente contrato.
                        </p>
                        
                        <p class="lead text-justify">
                            La Academia de Ciencias Juvenil de Morelos A.C. responderá de la calidad de los cursos contratados con las siguientes características del curso:
                        </p>
                        
                        <p class="lead text-justify">
                            El usuario al inscribirse a la plataforma acepta estar informado de las características del curso y acepta los términos y condiciones del servicio.
                        </p>
                        
                        <p class="lead text-justify"><strong>CURSO DE PREPARACIÓN PARA EL EXAMEN DE INGRESO A LA UNIVERSIDAD EXANI II CENEVAL NIVEL SUPERIOR</strong></p>
                        
                        <p class="lead text-justify"><strong>¿QUÉ ES EL CURSO SAINS?</strong></p>
                        <p class="lead text-justify">
                            El Curso de Preparación para el Examen de Ingreso a la UNIVERSIDAD, es un servicio de la ACJUM A.C. y SAINS, con el propósito de preparar al estudiante de Nivel Medio Superior, interesado en presentar el examen de ingreso en alguna de las carreras ofrecidas por la UNIVERSIDAD.
                        </p>
                        
                        <p class="lead text-justify"><strong>¿CÓMO FUNCIONA?</strong></p>
                        <p class="lead text-justify">
                            Durante 20 sesiones, un equipo de profesores especialistas en los temas del examen de ingreso a la universidad, resolverán la guía, paso a paso, tema por tema, resolviendo los ejercicios de cada tema de la guía de estudio EXANI II.
                        </p>
                        
                        <p class="lead text-justify"><strong>DURACIÓN:</strong></p>
                        <p class="lead text-justify">Es un curso de 110 HRS. ENTRENAMIENTO ACADEMICO.</p>
                        
                        <p class="lead text-justify"><strong>MODALIDAD: SEMANAL Y SABATINO</strong></p>
                        
                        <p class="lead text-justify">
                            Además, en este curso tienes acceso a las herramientas en línea 24/7 que complementan tus habilidades académicas con:
                        </p>
                        
                        <ul class="lead">
                            <li>Videos explicativos de la resolución de cada ejercicio de la Guía</li>
                            <li>Guía de estudio por materia con lecturas y desarrollo del tema.</li>
                            <li>Cuaderno de ejercicios</li>
                            <li>Resolución de la Guía Ceneval Exani II</li>
                            <li>Simulador de examen de admisión en línea</li>
                            <li>1000 videos explicativos de todas las materias</li>
                            <li>Acceso a bibliotecas virtuales</li>
                        </ul>
                        
                        <p class="lead text-justify"><strong>¿PARA QUÉ CARRERAS ME PREPARA EL CURSO?</strong></p>
                        <p class="lead text-justify">
                            El curso te sirve para todas las carreras que ofrece la UNIVERSIDAD, ya que está diseñado en base a las temáticas del examen de admisión CENEVAL EXANI II.
                        </p>
                        
                        <p class="lead text-justify"><strong>¿QUÉ MATERIAS ESTUDIARE EN EL CURSO?</strong></p>
                        
                        <ul class="lead">
                            <li>FORMATO DEL EXAMEN /2 HRS/ EN LINEA</li>
                            <li>PENSAMIENTO MATEMATICO/ 8 HRS/ PRESENCIAL</li>
                            <li>PENSAMIENTO ANALITICO/ 8 HRS/ PRESENCIAL</li>
                            <li>ESTRUCTURA DE LA LENGUA/ 6 HRS/ PRESENCIAL</li>
                            <li>COMPRENSION LECTORA/ 6HRS/ PRESENCIAL</li>
                            <li>INGLES / 4 HRS/ EN LINEA</li>
                            <li>LENGUAJE ESCRITO/ 6 HRS/ PRESENCIAL</li>
                            <li>MATEMATICAS/ 6HRS/ PRESENCIAL</li>
                            <li>ESTRATEGIAS DE ESTUDIO/ 2/ HRS/ PRESENCIAL</li>
                        </ul>
                        
                        <p class="lead text-justify"><strong>CRITERIOS DE APERTURA DE CURSOS</strong></p>
                        <ul class="lead">
                            <li>Que el curso que se pretende tomar se encuentre vigente y en la página web</li>
                            <li>Que cuente con el número de alumnos requeridos (por lo menos 11)</li>
                        </ul>
                        
                        <p class="lead text-justify"><strong>INSCRIPCIÓN</strong></p>
                        <p class="lead text-justify">
                            Una vez que se haya seleccionado el curso al que desea inscribirse, y rellenado el formato de preinscripción, será enviada su solicitud al sistema de registro.
                        </p>
                        
                        <p class="lead text-justify"><strong>PAGO:</strong></p>
                        <p class="lead text-justify">
                            La ACJUM A.C. establece las siguientes condiciones de pago: Podrá realizar el pago con depósito bancario o bien mediante transferencia electrónica. También se podrá realizar el pago en efectivo en las instalaciones de SAINS o en la sede del Curso.
                        </p>
                        
                        <p class="lead text-justify"><strong>Cancelación de la inscripción:</strong></p>
                        <p class="lead text-justify">
                            Las condiciones para la cancelación de la inscripción a continuación se indican serán de aplicación sin perjuicio de los derechos que legalmente le asistan:
                            Deberá realizarse dentro de las 48 horas siguientes a que se realice el pago de la inscripción. Posterior no podrá ser cancelado.
                            Solo se rembolsará el 80% del valor de la inscripción. La devolución de tu dinero se efectúa en el mismo método de pago que utilizaste al realizar tu inscripción. El tiempo estimado para tu reembolso es de 15 días laborables
                        </p>
                        
                        <p class="lead text-justify"><strong>Disposiciones Generales</strong></p>
                        <p class="lead text-justify">
                            Una vez leídos los términos y condiciones; y concluida la inscripción se constituye una aceptación automática de las condiciones descritos.
                        </p>
                        
                        <p class="lead text-justify"><strong>USO DE LA PLATAFORMA WEB ingresoalauni.com</strong></p>
                        <p class="lead text-justify">
                            El Usuario para poder hacer un uso adecuado y óptimo del Sitio Web y acceder a los contenidos y servicios en éste disponibles, deberá contar con: Conexión estable a Internet, JavaScript, cache y cookie activos. SAINS no se hace responsable del uso y navegación que haga el Usuario con navegadores con versiones anteriores.
                        </p>
                        
                        <p class="lead text-justify"><strong>Normas de utilización del Sitio web</strong></p>
                        <p class="lead text-justify">
                            El Usuario no podrá en ningún caso modificar o suprimir los datos identificativos que existan en el Sitio Web respecto a SAINS o, en su caso, de terceros...
                        </p>
                        
                        <p class="lead text-justify"><strong>Garantías.</strong></p>
                        <p class="lead text-justify">
                            SAINS no garantiza que el contenido del Sitio Web sea exacto o completo. Los contenidos y materiales disponibles en el Sitio Web incluyendo, sin carácter limitativo, el texto, los gráficos y los enlaces se proporcionan en el estado en que se encuentran y sin garantías de ningún tipo, ya sean expresas o implícitas.
                        </p>
                        
                        <p class="lead text-justify"><strong>Política de Cancelaciones y Devoluciones.</strong></p>
                        <p class="lead text-justify">
                            El Usuario podrá devolver cualquier producto que haya comprado a través del Sitio Web dentro de las primeras 24 horas después de la activación del pago, siempre y cuando los productos no hayan sido modificados, alterados, ni usados en contravención a lo dispuesto en estas Condiciones de uso.
                        </p>
                        
                        <p class="lead text-justify"><strong>Condiciones de Pago</strong></p>
                        <p class="lead text-justify">
                            Los productos y servicios adquiridos a través del Sitio Web, se pagarán por el Usuario mediante, tarjeta de crédito o a través del sistema de la pasarela de pago seguro PayPal u otras plataformas similares.
                        </p>
                        
                        <p class="lead text-justify"><strong>Legislación aplicable y jurisdicción competente.</strong></p>
                        <p class="lead text-justify">
                            Las compras realizadas a través del Sitio Web se someten a la legislación Mexicana. En el supuesto de que surja cualquier conflicto o discrepancia en la interpretación o aplicación de las presentes condiciones contractuales, los Juzgados y Tribunales que, en su caso, conocerán del asunto, serán los que disponga la normativa legal aplicable en materia de jurisdicción competente, en la que se atiende. En el caso de que la parte compradora tenga su domicilio fuera de México, ambas partes se someten, con renuncia expresa a cualquier otro fuero, a los Juzgados y Tribunales del estado de Morelos.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Garantía Section -->
    <section class="lgx-registration-simple">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-registration-area-simple">
                            <div class="lgx-heading lgx-heading-white" data-aos="fade-up">
                                <h2 class="heading">GARANTIA DE REEMBOLSO</h2>
                                <h3 class="subheading">
                                    SI NO OBTIENES UN LUGAR EN LA UNI<br>
                                    TE REGRESAMOS TU DINERO
                                </h3>
                                <h3 class="subheading">¿CÓMO FUNCIONA?</h3>
                                <p class="lead">
                                    Es muy simple, solo necesitas acreditar nuestras metodología de preparación académica, es decir estudiar todos los videos, podcast, ejercicios y guías para obtener un avance del 100% del curso, posterior necesitas acreditar con 95 puntos el simulador de Admisión, si logras esas metas y NO QUEDAS EN LA UNI, TE REGRESAMOS TU DINERO, solo envianos un mail a <a href="mailto:ayuda@ingresoalauni.com">ayuda@ingresoalauni.com</a>
                                </p>
                                <br>
                                <img src="{{ asset('images/Garantia.png') }}" alt="Garantía" class="guarantee-img">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contacto">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <img src="{{ asset('images/logo_u.png') }}" alt="Logo" height="50" class="mb-3" style="filter: brightness(0) invert(1);">
                    <p class="small text-white-50">SAINS es una empresa graduada de la Incubadora de Base Tecnológica MIDAS UAEM y galardonada con el Premio Nacional Cuezcomate 2016 a la innovación y transferencia tecnológica.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3">Enlaces rápidos</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}#nosotros">Nosotros</a></li>
                        <li class="mb-2"><a href="{{ route('home') }}#metodo">Método</a></li>
                        <li class="mb-2"><a href="{{ route('home') }}#docentes">Docentes</a></li>
                        <li class="mb-2"><a href="{{ route('home') }}#plan">Plan Premium</a></li>
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

    <!-- Back to Top Button -->
    <div class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
        
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            const backToTop = document.getElementById('backToTop');
            
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            
            if (window.scrollY > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });
        
        // Back to top functionality
        document.getElementById('backToTop').addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        // ========== MODAL SWITCH: LOGIN <-> REGISTER ==========
        const loginContainer = document.getElementById('loginFormContainer');
        const registerContainer = document.getElementById('registerFormContainer');
        const authTitle = document.getElementById('authTitle');
        const authSubtitle = document.getElementById('authSubtitle');
        
        const showRegisterBtn = document.getElementById('showRegisterBtn');
        if (showRegisterBtn) {
            showRegisterBtn.addEventListener('click', function(e) {
                e.preventDefault();
                loginContainer.style.display = 'none';
                registerContainer.style.display = 'block';
                authTitle.textContent = 'Crear cuenta';
                authSubtitle.textContent = 'Comienza tu preparación para el ingreso 2026';
            });
        }
        
        const showLoginBtn = document.getElementById('showLoginBtn');
        if (showLoginBtn) {
            showLoginBtn.addEventListener('click', function(e) {
                e.preventDefault();
                registerContainer.style.display = 'none';
                loginContainer.style.display = 'block';
                authTitle.textContent = 'Iniciar sesión';
                authSubtitle.textContent = 'Ingresa a tu cuenta para continuar';
            });
        }
        
        // Toggle password visibility
        document.querySelectorAll('.btn-toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
        
        // Forgot password handler
        const forgotPassword = document.getElementById('forgotPassword');
        if (forgotPassword) {
            forgotPassword.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Recuperar contraseña',
                    html: `
                        <p>Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>
                        <input type="email" id="resetEmail" class="form-control mt-3" placeholder="correo@ejemplo.com">
                    `,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Enviar',
                    cancelButtonText: 'Cancelar',
                    preConfirm: () => {
                        const email = document.getElementById('resetEmail').value;
                        if (!email) {
                            Swal.showValidationMessage('Por favor ingresa un correo electrónico');
                            return false;
                        }
                        return email;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: '¡Enviado!',
                            text: `Se ha enviado un enlace de recuperación a ${result.value}`,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });
        }
        
        // Handle register form submission
        const registerFormElement = document.getElementById('registerForm');
        if (registerFormElement) {
            registerFormElement.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const password = this.querySelector('input[name="password"]').value;
                const passwordConfirmation = this.querySelector('input[name="password_confirmation"]').value;
                const terms = document.getElementById('terms').checked;
                
                if (password !== passwordConfirmation) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Las contraseñas no coinciden',
                        icon: 'error',
                        confirmButtonText: 'Corregir'
                    });
                    return false;
                }
                
                if (!terms) {
                    Swal.fire({
                        title: 'Acepta los términos',
                        text: 'Debes aceptar los términos y condiciones para registrarte',
                        icon: 'warning',
                        confirmButtonText: 'Entendido'
                    });
                    return false;
                }
                
                Swal.fire({
                    title: 'Registrando...',
                    text: 'Por favor espera',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const formData = new FormData(this);
                
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: '¡Registro exitoso!',
                            text: 'Tu cuenta ha sido creada correctamente',
                            icon: 'success',
                            confirmButtonText: 'Continuar',
                            timer: 2000
                        }).then(() => {
                            window.location.href = data.redirect;
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'Intentar de nuevo'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo completar el registro. Verifica tus datos.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                });
            });
        }
        
        // Handle login form submission
        const loginFormElement = document.getElementById('loginForm');
        if (loginFormElement) {
            loginFormElement.addEventListener('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Iniciando sesión...',
                    text: 'Por favor espera',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const formData = new FormData(this);
                
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: '¡Bienvenido!',
                            text: 'Has iniciado sesión correctamente',
                            icon: 'success',
                            confirmButtonText: 'Continuar',
                            timer: 2000
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error de autenticación',
                            text: data.message || 'Correo o contraseña incorrectos',
                            icon: 'error',
                            confirmButtonText: 'Intentar de nuevo'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo iniciar sesión. Intenta más tarde.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                });
            });
        }
        
        // Dark Mode Toggle
        const themeToggle = document.getElementById('themeToggle');
        
        function setTheme(theme) {
            if (theme === 'dark') {
                document.body.classList.add('dark-mode');
                if (themeToggle) themeToggle.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.body.classList.remove('dark-mode');
                if (themeToggle) themeToggle.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        }
        
        function toggleTheme() {
            if (document.body.classList.contains('dark-mode')) {
                setTheme('light');
                Swal.fire({
                    title: 'Modo Claro',
                    text: 'Has cambiado al modo claro ☀️',
                    icon: 'info',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                setTheme('dark');
                Swal.fire({
                    title: 'Modo Oscuro',
                    text: 'Has cambiado al modo oscuro 🌙',
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        }
        
        if (themeToggle) {
            themeToggle.addEventListener('click', toggleTheme);
        }
        
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            setTheme('dark');
        } else if (savedTheme === 'light') {
            setTheme('light');
        } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            setTheme('dark');
        }
        
        // Flash messages
        @if(session('success'))
            Swal.fire({
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                timer: 3000
            });
        @endif
        
        @if(session('error'))
            Swal.fire({
                title: '¡Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'Entendido'
            });
        @endif
        
        @if(session('warning'))
            Swal.fire({
                title: 'Advertencia',
                text: '{{ session('warning') }}',
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
        @endif
        
        @if(session('info'))
            Swal.fire({
                title: 'Información',
                text: '{{ session('info') }}',
                icon: 'info',
                confirmButtonText: 'Entendido'
            });
        @endif
    </script>
</body>
</html>