<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Prepárate para ingresar a la universidad con nuestro curso completo. Simuladores, clases y materiales exclusivos.">
    <meta name="keywords" content="curso ingreso universidad, admisión, EXANI-II, UNAM, UAEM, CENEVAL">
    <meta name="author" content="SAINS">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>SAINS | Curso de ingreso a la universidad 2026</title>
    
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
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #818cf8;
            --secondary: #ec4899;
            --secondary-dark: #db2777;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
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
            overflow-x: hidden;
            background: var(--white);
        }
        
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
        
        .user-dropdown {
            background: var(--gradient-primary);
            color: white !important;
            padding: 0.5rem 1.5rem !important;
            border-radius: 50px !important;
            font-weight: 600 !important;
            border: none;
            box-shadow: 0 2px 5px rgba(79,70,229,0.3);
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(125deg, #0f172a 0%, #1e1b4b 40%, #312e81 70%, #4f46e5 100%);
            min-height: 100vh;
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
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2000 2000"><path fill="rgba(255,255,255,0.03)" d="M0,0 L2000,0 L2000,2000 L0,2000 Z M500,500 L1500,500 L1500,1500 L500,1500 Z"/><circle cx="1000" cy="1000" r="300" fill="rgba(255,255,255,0.02)"/></svg>');
            background-size: cover;
            opacity: 0.5;
        }
        
        .hero::after {
            content: '';
            position: absolute;
            top: -30%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(129,140,248,0.15) 0%, transparent 70%);
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
        }
        
        .hero h1 {
            font-size: 4.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            color: white;
            text-shadow: 0 2px 20px rgba(0,0,0,0.2);
        }
        
        .hero .subtitle {
            font-size: 1.25rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 2rem;
        }
        
        .hero-badge {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
            color: white;
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .btn-hero {
            background: white;
            color: var(--primary-dark);
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border: none;
        }
        
        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.25);
            color: var(--primary-dark);
        }
        
        .partner-logos {
            margin-top: 3rem;
            display: flex;
            gap: 2.5rem;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
        }
        
        .partner-logos img {
            height: 35px;
            opacity: 0.8;
            transition: opacity 0.3s ease;
            filter: brightness(0) invert(1);
        }
        
        .partner-logos img:hover {
            opacity: 1;
        }
        
        /* Info Cards */
        .info-card {
            background: white;
            border-radius: 24px;
            padding: 2.5rem 2rem;
            text-align: center;
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.04);
        }
        
        .info-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 50px -15px rgba(79,70,229,0.2);
            border-color: var(--primary-light);
        }
        
        .info-card i {
            font-size: 3rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 1.25rem;
            display: inline-block;
        }
        
        .info-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }
        
        .info-card p {
            color: var(--gray);
            line-height: 1.5;
        }
        
        /* Admission Banner */
        .admission-banner {
            background: var(--gradient-primary);
            border-radius: 32px;
            padding: 3rem;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .admission-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .admission-date {
            font-size: 2rem;
            font-weight: 700;
            margin: 0.5rem 0;
        }
        
        .university-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 1.5rem;
            align-items: center;
        }
        
        .university-grid img {
            max-width: 100%;
            height: auto;
            filter: brightness(0) invert(1);
            opacity: 0.85;
            transition: all 0.3s ease;
        }
        
        .university-grid img:hover {
            opacity: 1;
            transform: scale(1.05);
        }
        
        /* Learning Path */
        .path-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            border: 2px solid transparent;
        }
        
        .path-card.active, .path-card:hover {
            background: var(--gradient-primary);
            color: white;
            transform: translateY(-5px);
            border-color: var(--primary);
        }
        
        .path-icon {
            width: 70px;
            height: 70px;
            background: rgba(79,70,229,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.75rem;
            color: var(--primary);
            transition: all 0.3s ease;
        }
        
        .path-card.active .path-icon,
        .path-card:hover .path-icon {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        
        .path-card h5 {
            font-weight: 700;
            margin-bottom: 0;
        }
        
        .path-content {
            display: none;
            margin-top: 2rem;
            padding: 2.5rem;
            background: white;
            border-radius: 28px;
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .path-content.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Instructors */
        .instructor-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            text-align: center;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        
        .instructor-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 40px -12px rgba(79,70,229,0.25);
        }
        
        .instructor-img {
            width: 100%;
            height: 280px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .instructor-card:hover .instructor-img {
            transform: scale(1.05);
        }
        
        .instructor-info {
            padding: 1.5rem;
        }
        
        .instructor-info h4 {
            font-weight: 800;
            margin-bottom: 0.25rem;
            font-size: 1.1rem;
        }
        
        .instructor-info p {
            color: var(--primary);
            font-weight: 600;
            font-size: 0.8rem;
            margin-bottom: 0;
        }
        
        /* Premium Pricing Card */
        .premium-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-radius: 36px;
            padding: 3rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.15);
        }
        
        .premium-card::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -10%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(79,70,229,0.25) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .premium-card::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(236,72,153,0.2) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .premium-badge {
            position: absolute;
            top: 30px;
            right: 30px;
            background: var(--gradient-secondary);
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.8rem;
            color: white;
            z-index: 2;
        }
        
        .premium-price {
            font-size: 4rem;
            font-weight: 800;
            margin: 1rem 0;
            background: var(--gradient-secondary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .premium-price small {
            font-size: 0.9rem;
            font-weight: 400;
            color: #94a3b8;
        }
        
        .price-old {
            text-decoration: line-through;
            color: #94a3b8;
            font-size: 1.25rem;
        }
        
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .feature-list li {
            margin-bottom: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #e2e8f0;
        }
        
        .feature-list li i {
            color: var(--secondary);
            font-size: 1rem;
            width: 20px;
        }
        
        .btn-premium {
            background: var(--gradient-secondary);
            color: white;
            padding: 0.9rem 2rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: none;
            width: 100%;
            box-shadow: 0 5px 15px rgba(236,72,153,0.3);
        }
        
        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(236,72,153,0.4);
            color: white;
        }
        
        /* Guarantee Card */
        .guarantee-card {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 28px;
            padding: 2rem;
            text-align: center;
            height: 100%;
            border: 1px solid rgba(16,185,129,0.15);
            transition: all 0.3s ease;
        }
        
        .guarantee-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 35px -12px rgba(16,185,129,0.2);
        }
        
        .guarantee-icon {
            font-size: 3.5rem;
            color: var(--success);
            margin-bottom: 1rem;
        }
        
        /* Newsletter Section */
        .newsletter-section {
            background: var(--gradient-primary);
            border-radius: 32px;
            padding: 3rem;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .newsletter-section::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        /* Footer */
        .footer {
            background: #0f172a;
            color: white;
            padding: 4rem 0 2rem;
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
        
        /* Floating WhatsApp */
        .float-whatsapp {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            animation: pulse 2s infinite;
        }
        
        .float-whatsapp a {
            display: block;
            width: 60px;
            height: 60px;
            background: #25d366;
            border-radius: 50%;
            text-align: center;
            line-height: 60px;
            font-size: 2rem;
            color: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        
        .float-whatsapp a:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(37,211,102,0.4);
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        /* MODALES */
        .modal-content {
            border-radius: 28px;
            border: none;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
            background: var(--white);
        }
        
        .auth-header {
            position: relative;
            overflow: hidden;
            background: var(--gradient-primary);
            padding: 1.5rem 1.5rem 2rem !important;
        }
        
        .auth-header::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 0;
            right: 0;
            height: 30px;
            background: var(--white);
            border-radius: 25px 25px 0 0;
        }
        
        .auth-body {
            padding: 1.25rem 1.5rem 1.5rem !important;
        }
        
        .form-floating > .form-control {
            border-radius: 14px;
            border: 2px solid #e2e8f0;
            padding: 0.75rem 0.75rem;
            transition: all 0.3s ease;
            background: var(--white);
            height: 48px;
            font-size: 0.9rem;
        }
        
        .form-floating > .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
        }
        
        .form-floating > label {
            color: var(--gray);
            font-weight: 500;
            font-size: 0.85rem;
            padding: 0.65rem 0.75rem;
        }
        
        .btn-toggle-password {
            cursor: pointer;
            background: none;
            border: none;
            color: var(--gray);
            transition: color 0.3s ease;
            z-index: 10;
            top: 50% !important;
            transform: translateY(-50%) !important;
        }
        
        .btn-toggle-password:hover {
            color: var(--primary);
        }
        
        .btn-modal-primary {
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 0.7rem;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .btn-modal-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79,70,229,0.3);
            color: white;
        }
        
        .social-auth-btn {
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            padding: 0.6rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: white;
            text-decoration: none;
            display: block;
            text-align: center;
            color: var(--dark);
        }
        
        .social-auth-btn:hover {
            border-color: var(--primary);
            background: var(--gray-light);
            transform: translateY(-2px);
            color: var(--dark);
        }
        
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: var(--gray);
            font-size: 0.8rem;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .divider::before {
            margin-right: 0.75rem;
        }
        
        .divider::after {
            margin-left: 0.75rem;
        }
        
        /* SweetAlert2 personalizado */
        .swal2-popup {
            border-radius: 24px !important;
            padding: 2rem !important;
        }
        
        .swal2-confirm {
            border-radius: 50px !important;
            padding: 0.7rem 2rem !important;
            font-weight: 600 !important;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.2rem;
            }
            
            .hero .subtitle {
                font-size: 1rem;
            }
            
            .admission-date {
                font-size: 1.5rem;
            }
            
            .premium-price {
                font-size: 3rem;
            }
            
            .path-card {
                margin-bottom: 1rem;
            }
            
            .section-padding {
                padding: 60px 0;
            }
            
            .premium-card {
                padding: 2rem;
            }
            
            .premium-badge {
                top: 15px;
                right: 15px;
                font-size: 0.7rem;
                padding: 0.3rem 1rem;
            }
            
            .auth-header {
                padding: 1.25rem 1rem 1.75rem !important;
            }
            
            .auth-body {
                padding: 1rem 1.25rem 1.25rem !important;
            }
            
            .modal-dialog {
                margin: 0.5rem;
            }
        }
        
        .section-padding {
            padding: 80px 0;
        }
        
        .bg-light-gray {
            background: var(--gray-light);
        }
        
        .rounded-4 {
            border-radius: 20px;
        }
    </style>
</head>
<body>

    <!-- ⭐ Modal Login/Register -->
    <div class="modal fade" id="authModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="auth-header text-center">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" style="font-size: 0.7rem;"></button>
                    <img src="{{ asset('images/logo_u.png') }}" alt="Logo" height="45" style="filter: brightness(0) invert(1);">
                    <h5 class="text-white mt-2 mb-1 fw-bold" id="authTitle">Bienvenido de vuelta</h5>
                    <p class="text-white-50 mb-0" style="font-size: 0.75rem;" id="authSubtitle">Ingresa a tu cuenta para continuar</p>
                </div>
                
                <!-- Login Form -->
                <div class="auth-body" id="loginFormContainer">
                    <form id="loginForm" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="form-floating mb-2">
                            <input type="email" name="email" class="form-control" id="loginEmail" placeholder="Correo" required>
                            <label><i class="fas fa-envelope me-1 text-primary"></i> Correo</label>
                        </div>
                        
                        <div class="form-floating mb-2 position-relative">
                            <input type="password" name="password" class="form-control" id="loginPassword" placeholder="Contraseña" required>
                            <label><i class="fas fa-lock me-1 text-primary"></i> Contraseña</label>
                            <button type="button" class="btn-toggle-password position-absolute end-0 me-2" data-target="loginPassword">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe" name="remember" style="transform: scale(0.85);">
                                <label class="form-check-label" for="rememberMe" style="font-size: 0.75rem;">Recordarme</label>
                            </div>
                            <a href="#" id="forgotPassword" style="font-size: 0.75rem;">¿Olvidaste tu contraseña?</a>
                        </div>
                        
                        <button type="submit" class="btn btn-modal-primary w-100">
                            <i class="fas fa-sign-in-alt me-2"></i> Iniciar sesión
                        </button>
                    </form>
                    
                    <div class="divider my-3">
                        <span class="px-2">o continúa con</span>
                    </div>
                    
                    <a href="{{ route('auth.google') }}" class="social-auth-btn w-100">
                        <i class="fab fa-google text-danger me-2"></i> Continuar con Google
                    </a>
                    
                    <div class="text-center mt-3">
                        <p class="mb-0 text-muted" style="font-size: 0.75rem;">¿No tienes cuenta?</p>
                        <a href="#" class="text-primary fw-bold text-decoration-none" id="showRegisterBtn" style="font-size: 0.85rem;">
                            <i class="fas fa-user-plus me-1"></i> Crear cuenta gratis
                        </a>
                    </div>
                </div>
                
                <!-- Register Form -->
                <div class="auth-body" id="registerFormContainer" style="display: none;">
                    <form id="registerForm" action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="form-floating mb-2">
                            <input type="email" name="email" class="form-control" id="regEmail" placeholder="Correo" required>
                            <label><i class="fas fa-envelope me-1 text-primary"></i> Correo</label>
                        </div>
                        
                        <div class="form-floating mb-2 position-relative">
                            <input type="password" name="password" class="form-control" id="regPassword" placeholder="Contraseña" required>
                            <label><i class="fas fa-lock me-1 text-primary"></i> Contraseña</label>
                            <button type="button" class="btn-toggle-password position-absolute end-0 me-2" data-target="regPassword">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        
                        <div class="form-floating mb-2 position-relative">
                            <input type="password" name="password_confirmation" class="form-control" id="regPasswordConfirm" placeholder="Confirmar" required>
                            <label><i class="fas fa-check-circle me-1 text-primary"></i> Confirmar</label>
                            <button type="button" class="btn-toggle-password position-absolute end-0 me-2" data-target="regPasswordConfirm">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="terms" required style="transform: scale(0.85);">
                            <label class="form-check-label" for="terms" style="font-size: 0.75rem;">
                                Acepto los <a href="{{ route('terms') }}" target="_blank">Términos</a>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-modal-primary w-100">
                            <i class="fas fa-user-plus me-2"></i> Crear cuenta
                        </button>
                    </form>
                    
                    <div class="divider my-3">
                        <span class="px-2">o regístrate con</span>
                    </div>
                    
                    <a href="{{ route('auth.google') }}" class="social-auth-btn w-100">
                        <i class="fab fa-google text-danger me-2"></i> Continuar con Google
                    </a>
                    
                    <div class="text-center mt-3">
                        <p class="mb-0 text-muted" style="font-size: 0.75rem;">¿Ya tienes cuenta?</p>
                        <a href="#" class="text-primary fw-bold text-decoration-none" id="showLoginBtn" style="font-size: 0.85rem;">
                            <i class="fas fa-sign-in-alt me-1"></i> Iniciar sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ⭐ Modal de Recuperación de Contraseña -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="auth-header text-center">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" style="font-size: 0.7rem;"></button>
                    <img src="{{ asset('images/logo_u.png') }}" alt="Logo" height="45" style="filter: brightness(0) invert(1);">
                    <h5 class="text-white mt-2 mb-1 fw-bold">Recuperar contraseña</h5>
                    <p class="text-white-50 mb-0" style="font-size: 0.75rem;">Te enviaremos un enlace para restablecer tu contraseña</p>
                </div>
                
                <div class="auth-body">
                    <form id="forgotPasswordForm">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control" id="resetEmail" placeholder="Correo" required>
                            <label><i class="fas fa-envelope me-1 text-primary"></i> Correo electrónico</label>
                        </div>
                        
                        <button type="submit" class="btn btn-modal-primary w-100">
                            <i class="fas fa-paper-plane me-2"></i> Enviar enlace de recuperación
                        </button>
                        
                        <div class="text-center mt-3">
                            <a href="#" class="text-primary fw-bold text-decoration-none" id="backToLogin" style="font-size: 0.85rem;">
                                <i class="fas fa-arrow-left me-1"></i> Volver al inicio de sesión
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/logo_u.png') }}" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#nosotros">Nosotros</a></li>
                    <li class="nav-item"><a class="nav-link" href="#metodo">Método</a></li>
                    <li class="nav-item"><a class="nav-link" href="#docentes">Docentes</a></li>
                    <li class="nav-item"><a class="nav-link" href="#plan">Plan Premium</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
                </ul>
                <div class="d-flex">
                    @auth
                        <div class="dropdown">
                            <button class="btn user-dropdown dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-2"></i>
                                {{ Auth::user()->correo }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('estudiante.dashboard') }}"><i class="fas fa-graduation-cap me-2"></i>Mi curso</a></li>
                                <li><a class="dropdown-item" href="{{ route('estudiante.perfil') }}"><i class="fas fa-user me-2"></i>Mi perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <button class="btn btn-login" data-bs-toggle="modal" data-bs-target="#authModal">
                            <i class="fas fa-sign-in-alt me-2"></i> Iniciar sesión
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content text-center" data-aos="fade-up">
                <span class="hero-badge">
                    <i class="fas fa-calendar-alt me-2"></i> Convocatoria 2026
                </span>
                <h1>Asegura tu lugar en la<br>universidad de tus sueños</h1>
                <p class="subtitle">Prepárate con el método más efectivo. Clases, simuladores y guías<br>exclusivas para el examen de admisión 2026.</p>
                
                @auth
                    <a href="{{ route('estudiante.dashboard') }}" class="btn-hero">
                        <i class="fas fa-graduation-cap"></i> Ir a mi curso
                    </a>
                @else
                    <a href="#" class="btn-hero" data-bs-toggle="modal" data-bs-target="#authModal">
                        <i class="fas fa-graduation-cap"></i> Comienza ahora
                    </a>
                @endauth
                
                <div class="partner-logos justify-content-center">
                    <img src="{{ asset('images/02.png') }}" alt="Partner 1">
                    <img src="{{ asset('images/01.png') }}" alt="Partner 2">
                </div>
            </div>
        </div>
    </section>

    <!-- Info Cards Section -->
    <section class="section-padding" id="nosotros">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="info-card">
                        <i class="fas fa-laptop-code"></i>
                        <h3>100% Online</h3>
                        <p class="text-muted">Estudia desde cualquier lugar, a tu ritmo y en cualquier dispositivo.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="info-card">
                        <i class="fas fa-chart-line"></i>
                        <h3>+85% de efectividad</h3>
                        <p class="text-muted">Nuestros estudiantes logran ingresar a la carrera de sus sueños.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="info-card">
                        <i class="fas fa-trophy"></i>
                        <h3>Certificados</h3>
                        <p class="text-muted">Instructores especializados en pruebas de selección universitaria.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Admission Banner -->
    <section class="section-padding pt-0">
        <div class="container">
            <div class="admission-banner" data-aos="fade-up">
                <div class="row align-items-center">
                    <div class="col-lg-5 mb-4 mb-lg-0">
                        <h2 class="mb-3">Temporada de Exámenes de Admisión 2026</h2>
                        <div class="admission-date">MAYO - JUNIO 2026</div>
                        <p class="mt-3 opacity-75">Prepárate con anticipación y asegura tu ingreso a la universidad de tus sueños</p>
                        @auth
                            <a href="{{ route('estudiante.simulador') }}" class="btn btn-light mt-3" style="border-radius: 50px; padding: 0.75rem 1.75rem; font-weight: 600;">
                                <i class="fas fa-play me-2"></i> ¡Practica ya!
                            </a>
                        @else
                            <button class="btn btn-light mt-3" data-bs-toggle="modal" data-bs-target="#authModal" style="border-radius: 50px; padding: 0.75rem 1.75rem; font-weight: 600;">
                                <i class="fas fa-play me-2"></i> ¡Practica ya!
                            </button>
                        @endauth
                    </div>
                    <div class="col-lg-7">
                        <div class="university-grid">
                            <img src="{{ asset('images/logo-unam.png') }}" alt="UNAM">
                            <img src="{{ asset('images/logo-uam.png') }}" alt="UAM">
                            <img src="{{ asset('images/logo-uaem.png') }}" alt="UAEM">
                            <img src="{{ asset('images/logo-buap.png') }}" alt="BUAP">
                            <img src="{{ asset('images/logo-upemor.png') }}" alt="UPEMOR">
                            <img src="{{ asset('images/logo-utez.png') }}" alt="UTEZ">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Learning Path Section -->
    <section class="section-padding bg-light-gray" id="metodo">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="mb-3">Ruta de Aprendizaje</h2>
                <p class="text-muted">El método más innovador de preparación para admisión universitaria 2026</p>
            </div>
            
            <div class="row g-4 mb-5" data-aos="fade-up">
                <div class="col-md-3">
                    <div class="path-card active" data-path="1">
                        <div class="path-icon"><i class="fas fa-chart-simple"></i></div>
                        <h5 class="mb-0">1. Mídete</h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="path-card" data-path="2">
                        <div class="path-icon"><i class="fas fa-video"></i></div>
                        <h5 class="mb-0">2. Mejora</h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="path-card" data-path="3">
                        <div class="path-icon"><i class="fas fa-pen-ruler"></i></div>
                        <h5 class="mb-0">3. Practica</h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="path-card" data-path="4">
                        <div class="path-icon"><i class="fas fa-flag-checkered"></i></div>
                        <h5 class="mb-0">4. ¡Listo!</h5>
                    </div>
                </div>
            </div>
            
            <div class="path-content active" data-path-content="1">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <img src="{{ asset('images/midete_tips.png') }}" alt="Mídete" class="img-fluid rounded-4 shadow-sm">
                    </div>
                    <div class="col-md-6">
                        <h3 class="mb-3">Exámenes en línea</h3>
                        <p class="text-muted mb-3">Practica con nuestros simuladores y descubre tus fortalezas y áreas de oportunidad.</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Simuladores tipo examen real</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Resultados inmediatos</li>
                            <li><i class="fas fa-check-circle text-primary me-2"></i> Identifica tus áreas de mejora</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="path-content" data-path-content="2">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <img src="{{ asset('images/forta_tips.png') }}" alt="Mejora" class="img-fluid rounded-4 shadow-sm">
                    </div>
                    <div class="col-md-6">
                        <h3 class="mb-3">Aprende con videos y podcast</h3>
                        <p class="text-muted mb-3">Estudia de manera fácil desde cualquier dispositivo con nuestro contenido multimedia.</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Clases en video bajo demanda</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Podcast para aprender en movimiento</li>
                            <li><i class="fas fa-check-circle text-primary me-2"></i> Material descargable</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="path-content" data-path-content="3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <img src="{{ asset('images/prac_tips.png') }}" alt="Practica" class="img-fluid rounded-4 shadow-sm">
                    </div>
                    <div class="col-md-6">
                        <h3 class="mb-3">Resuelve ejercicios interactivos</h3>
                        <p class="text-muted mb-3">Nuestro banco de preguntas está basado en el temario oficial de las principales universidades.</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Miles de ejercicios tipo examen</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Retroalimentación inmediata</li>
                            <li><i class="fas fa-check-circle text-primary me-2"></i> Exámenes imprimibles en PDF</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="path-content" data-path-content="4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <img src="{{ asset('images/simula_tips.png') }}" alt="Listo" class="img-fluid rounded-4 shadow-sm">
                    </div>
                    <div class="col-md-6">
                        <h3 class="mb-3">Estás listo para el examen 2026</h3>
                        <p class="text-muted mb-3">Con nuestra metodología comprobada, llegarás preparado y con confianza.</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Simulacros de tiempo real</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Estrategias de examen</li>
                            <li><i class="fas fa-check-circle text-primary me-2"></i> Acompañamiento personalizado</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Instructors Section -->
    <section class="section-padding" id="docentes">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="mb-3">Nuestros Instructores</h2>
                <p class="text-muted">Prepárate con los mejores profesores certificados en pruebas de selección universitaria</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="instructor-card">
                        <img src="{{ asset('images/dr.-giovanni-rios.jpg') }}" alt="Dr. Giovanni Rios" class="instructor-img">
                        <div class="instructor-info">
                            <h4>Dr. Giovanni Rios</h4>
                            <p>Formato de Examen</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="instructor-card">
                        <img src="{{ asset('images/lic.-edith-saldana.jpg') }}" alt="Lic. Edith Saldaña" class="instructor-img">
                        <div class="instructor-info">
                            <h4>Lic. Edith Saldaña</h4>
                            <p>Inglés</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="instructor-card">
                        <img src="{{ asset('images/dr.-bernarnino-brisleno.jpg') }}" alt="Dr. Bernardino Brisleño" class="instructor-img">
                        <div class="instructor-info">
                            <h4>Dr. Bernardino Brisleño</h4>
                            <p>Estrategias de Estudio</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="instructor-card">
                        <img src="{{ asset('images/mtra.-maria-casas.jpg') }}" alt="Mtra. Maria Casas" class="instructor-img">
                        <div class="instructor-info">
                            <h4>Mtra. Maria M. Casas</h4>
                            <p>Matemáticas</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="instructor-card">
                        <img src="{{ asset('images/mtra.-monse-orellana.jpg') }}" alt="Mtra. Monse Orellana" class="instructor-img">
                        <div class="instructor-info">
                            <h4>Mtra. Monse Orellana</h4>
                            <p>Comprensión Lectora</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="instructor-card">
                        <img src="{{ asset('images/lic.-israel-saldana.jpg') }}" alt="Lic. Israel Saldaña" class="instructor-img">
                        <div class="instructor-info">
                            <h4>Lic. Israel Saldaña</h4>
                            <p>Español</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="instructor-card">
                        <img src="{{ asset('images/dr.-hector-matrinez.jpg') }}" alt="Dr. Hector Martinez" class="instructor-img">
                        <div class="instructor-info">
                            <h4>Dr. Hector Martinez</h4>
                            <p>Historia y Cs. Sociales</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="instructor-card">
                        <img src="{{ asset('images/mtra.-nilda-c.-sanchez.jpg') }}" alt="Mtra. Nilda Sanchez" class="instructor-img">
                        <div class="instructor-info">
                            <h4>Mtra. Nilda C. Sanchez</h4>
                            <p>Ciencias</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Premium Plan Section -->
    <section class="section-padding bg-light-gray" id="plan">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="mb-3">Plan Premium 2026</h2>
                <p class="text-muted">Todo lo que necesitas para asegurar tu ingreso a la universidad</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">
                    <div class="premium-card">
                        <div class="premium-badge">
                            <i class="fas fa-star me-1"></i> Más popular
                        </div>
                        <div class="row align-items-center">
                            <div class="col-lg-5 text-center text-lg-start">
                                <h3 class="text-white mb-2">Curso Premium</h3>
                                <div class="price-old">$3,499 MXN</div>
                                <div class="premium-price">$2,499 <small>MXN</small></div>
                                <p class="text-white-50 mb-3 small">Pago único o 3 meses sin intereses</p>
                                <button class="btn-premium select-plan" data-plan="premium">
                                    <i class="fas fa-rocket me-2"></i> Comprar ahora
                                </button>
                            </div>
                            <div class="col-lg-7 mt-4 mt-lg-0">
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="feature-list">
                                            <li><i class="fas fa-check-circle"></i> 150+ clases en video</li>
                                            <li><i class="fas fa-check-circle"></i> 5000+ ejercicios interactivos</li>
                                            <li><i class="fas fa-check-circle"></i> Guías de estudio descargables</li>
                                            <li><i class="fas fa-check-circle"></i> Simulador ilimitado</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="feature-list">
                                            <li><i class="fas fa-check-circle"></i> Clases en vivo semanales</li>
                                            <li><i class="fas fa-check-circle"></i> Asesoría personalizada</li>
                                            <li><i class="fas fa-check-circle"></i> MasterClass exclusivas</li>
                                            <li><i class="fas fa-check-circle"></i> Certificado de finalización</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row justify-content-center mt-5">
                <div class="col-md-8" data-aos="fade-up" data-aos-delay="200">
                    <div class="guarantee-card">
                        <div class="guarantee-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="mb-3">Garantía de Reembolso</h3>
                        <p>Si no obtienes un lugar en la universidad, te regresamos tu dinero</p>
                        <hr>
                        <p class="small text-muted">*Aplica para plan Premium completando el 100% del curso y obteniendo 95+ puntos en el simulador oficial</p>
                        <img src="{{ asset('images/garantia-oficial.png') }}" alt="Garantía" class="mt-3" style="max-width: 130px;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="section-padding">
        <div class="container">
            <div class="newsletter-section" data-aos="fade-up">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <i class="fas fa-envelope-open-text fa-3x mb-3"></i>
                        <h3 class="mb-3">Suscríbete y recibe los 7 métodos más exitosos para ingresar a la universidad</h3>
                        <form class="row g-2 justify-content-center mt-4" id="newsletterForm">
                            @csrf
                            <div class="col-md-7">
                                <input type="email" id="newsletterEmail" class="form-control form-control-lg" placeholder="Ingresa tu correo electrónico" required style="border-radius: 50px; border: none;">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-light btn-lg w-100" style="border-radius: 50px; font-weight: 600;">Suscribirme</button>
                            </div>
                        </form>
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
                    <p class="small text-white-50">SAINS es una empresa graduada de la Incubadora de Base Tecnológica MIDAS UAEM y galardonada con el Premio Nacional Cuezcomate 2016.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3">Enlaces rápidos</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#nosotros">Nosotros</a></li>
                        <li class="mb-2"><a href="#metodo">Método</a></li>
                        <li class="mb-2"><a href="#docentes">Docentes</a></li>
                        <li class="mb-2"><a href="#plan">Plan Premium</a></li>
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

    <!-- Floating WhatsApp -->
    <div class="float-whatsapp">
        <a href="https://wa.me/527771886018" target="_blank">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        AOS.init({ duration: 800, once: true, offset: 100 });
        
        // Navbar scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) navbar.classList.add('scrolled');
            else navbar.classList.remove('scrolled');
        });
        
        // SweetAlert2 para mensajes de sesión
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'Continuar',
                customClass: { confirmButton: 'rounded-pill' }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'Entendido',
                customClass: { confirmButton: 'rounded-pill' }
            });
        @endif
        
        // Learning Path
        document.querySelectorAll('.path-card').forEach(card => {
            card.addEventListener('click', function() {
                const pathId = this.dataset.path;
                document.querySelectorAll('.path-card').forEach(c => c.classList.remove('active'));
                document.querySelectorAll('.path-content').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                document.querySelector(`.path-content[data-path-content="${pathId}"]`).classList.add('active');
            });
        });
        
        // Modal switch (Login/Register)
        const loginContainer = document.getElementById('loginFormContainer');
        const registerContainer = document.getElementById('registerFormContainer');
        const authTitle = document.getElementById('authTitle');
        const authSubtitle = document.getElementById('authSubtitle');
        
        document.getElementById('showRegisterBtn')?.addEventListener('click', function(e) {
            e.preventDefault();
            loginContainer.style.display = 'none';
            registerContainer.style.display = 'block';
            authTitle.textContent = 'Crear tu cuenta';
            authSubtitle.textContent = 'Comienza tu preparación hoy mismo';
        });
        
        document.getElementById('showLoginBtn')?.addEventListener('click', function(e) {
            e.preventDefault();
            registerContainer.style.display = 'none';
            loginContainer.style.display = 'block';
            authTitle.textContent = 'Bienvenido de vuelta';
            authSubtitle.textContent = 'Ingresa a tu cuenta para continuar';
        });
        
        // Toggle password
        document.querySelectorAll('.btn-toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const target = document.getElementById(this.dataset.target);
                const icon = this.querySelector('i');
                if (target.type === 'password') {
                    target.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    target.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });
        
        // ⭐ Modal de recuperación de contraseña
        const forgotPasswordModal = new bootstrap.Modal(document.getElementById('forgotPasswordModal'));
        
        document.getElementById('forgotPassword')?.addEventListener('click', function(e) {
            e.preventDefault();
            const authModal = bootstrap.Modal.getInstance(document.getElementById('authModal'));
            if (authModal) authModal.hide();
            setTimeout(() => {
                forgotPasswordModal.show();
            }, 200);
        });
        
        document.getElementById('backToLogin')?.addEventListener('click', function(e) {
            e.preventDefault();
            forgotPasswordModal.hide();
            setTimeout(() => {
                const authModal = new bootstrap.Modal(document.getElementById('authModal'));
                authModal.show();
            }, 200);
        });
        
        // Enviar formulario de recuperación
        document.getElementById('forgotPasswordForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('resetEmail').value;
            const btn = this.querySelector('button[type="submit"]');
            const original = btn.innerHTML;
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando...';
            btn.disabled = true;
            
            fetch('{{ route("password.email") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = original;
                btn.disabled = false;
                
                if (data.success) {
                    forgotPasswordModal.hide();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Correo enviado!',
                        text: data.message || 'Revisa tu bandeja de entrada.',
                        confirmButtonColor: '#4f46e5',
                        confirmButtonText: 'Entendido',
                        customClass: { confirmButton: 'rounded-pill' }
                    });
                    document.getElementById('forgotPasswordForm').reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'No se pudo enviar el correo.',
                        confirmButtonColor: '#4f46e5',
                        customClass: { confirmButton: 'rounded-pill' }
                    });
                }
            })
            .catch(error => {
                btn.innerHTML = original;
                btn.disabled = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor.',
                    confirmButtonColor: '#4f46e5',
                    customClass: { confirmButton: 'rounded-pill' }
                });
            });
        });
        
        // Newsletter
        document.getElementById('newsletterForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('newsletterEmail').value;
            if (!email) {
                Swal.fire({ icon: 'warning', title: 'Atención', text: 'Ingresa tu correo', confirmButtonColor: '#4f46e5' });
                return;
            }
            localStorage.setItem('newsletter_email', email);
            Swal.fire({
                icon: 'success',
                title: '🎓 ¡Oferta exclusiva!',
                html: '<p class="mb-0">Regístrate ahora y recibe material gratuito</p>',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'Registrarme',
                customClass: { confirmButton: 'rounded-pill' }
            }).then(() => {
                this.reset();
                loginContainer.style.display = 'none';
                registerContainer.style.display = 'block';
                authTitle.textContent = 'Crear tu cuenta';
                authSubtitle.textContent = 'Comienza tu preparación hoy mismo';
                document.getElementById('regEmail').value = email;
                const modal = new bootstrap.Modal(document.getElementById('authModal'));
                modal.show();
            });
        });
        
        // Plan selection
        document.querySelectorAll('.select-plan').forEach(btn => {
            btn.addEventListener('click', () => {
                @auth
                    window.location.href = '{{ route("estudiante.checkout") }}';
                @else
                    Swal.fire({
                        icon: 'info',
                        title: '¡Excelente elección!',
                        text: 'Inicia sesión o crea una cuenta para continuar con tu compra.',
                        confirmButtonColor: '#4f46e5',
                        confirmButtonText: 'Iniciar sesión',
                        customClass: { confirmButton: 'rounded-pill' }
                    }).then(() => {
                        const modal = new bootstrap.Modal(document.getElementById('authModal'));
                        modal.show();
                    });
                @endauth
            });
        });
        
        // Login handler
        document.getElementById('loginForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const btn = this.querySelector('button[type="submit"]');
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                body: formData
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Bienvenido!',
                        text: data.message,
                        confirmButtonColor: '#4f46e5',
                        customClass: { confirmButton: 'rounded-pill' }
                    }).then(() => window.location.href = data.redirect || '/');
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Credenciales incorrectas', confirmButtonColor: '#4f46e5' });
                    btn.innerHTML = original;
                    btn.disabled = false;
                }
            }).catch(() => {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Error de conexión', confirmButtonColor: '#4f46e5' });
                btn.innerHTML = original;
                btn.disabled = false;
            });
        });
        
        // Register handler
        document.getElementById('registerForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const pwd = this.querySelector('input[name="password"]').value;
            const pwd2 = this.querySelector('input[name="password_confirmation"]').value;
            if (pwd !== pwd2) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Las contraseñas no coinciden', confirmButtonColor: '#4f46e5' });
                return;
            }
            if (!document.getElementById('terms').checked) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Debes aceptar los términos', confirmButtonColor: '#4f46e5' });
                return;
            }
            const btn = this.querySelector('button[type="submit"]');
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                body: new FormData(this)
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Registro exitoso!',
                        text: 'Tu cuenta ha sido creada',
                        confirmButtonColor: '#4f46e5',
                        customClass: { confirmButton: 'rounded-pill' }
                    }).then(() => window.location.href = data.redirect || '/');
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Error en el registro', confirmButtonColor: '#4f46e5' });
                    btn.innerHTML = original;
                    btn.disabled = false;
                }
            }).catch(() => {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Error de conexión', confirmButtonColor: '#4f46e5' });
                btn.innerHTML = original;
                btn.disabled = false;
            });
        });
    </script>
</body>
</html>