<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SAINS - Panel Administrativo')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #4361ee;
            --primary-dark: #3a0ca3;
            --primary-light: #818cf8;
            --secondary: #f72585;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --bg-body: #f8fafc;
            --bg-navbar: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --hover-bg: #f1f5f9;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-body);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* ========== NAVBAR MODERNO UNA SOLA LÍNEA ========== */
        .navbar {
            background: var(--bg-navbar);
            border-bottom: 1px solid var(--border);
            padding: 0.5rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.98);
        }

        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            max-width: 100%;
            margin: 0;
            padding: 0 1.5rem;
        }

        @media (min-width: 1400px) {
            .navbar-container {
                max-width: 1400px;
                margin: 0 auto;
                padding: 0 2rem;
            }
        }

        /* Logo */
        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            flex-shrink: 0;
        }
        
        .logo-img {
            height: 38px;
            width: auto;
            transition: transform 0.2s ease;
        }
        
        .logo:hover .logo-img {
            transform: scale(1.02);
        }

        /* Menú de navegación - Desktop */
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            flex: 1;
            justify-content: center;
        }

        /* Links de navegación */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            border-radius: 10px;
            transition: all 0.2s ease;
            white-space: nowrap;
            background: none;
            border: none;
            cursor: pointer;
            position: relative;
        }

        .nav-link i {
            font-size: 1rem;
            transition: transform 0.2s ease;
        }

        .nav-link span {
            font-size: 0.85rem;
        }

        .nav-link:hover {
            background: var(--hover-bg);
            color: var(--primary);
        }

        .nav-link:hover i {
            transform: translateY(-2px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(67, 97, 238, 0.05));
            color: var(--primary);
            font-weight: 600;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 3px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 3px;
        }

        /* Dropdown */
        .dropdown {
            position: relative;
        }

        .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dropdown-toggle::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            border: none;
            font-size: 0.7rem;
            margin-left: 0.3rem;
            transition: transform 0.2s ease;
        }

        .dropdown:hover .dropdown-toggle::after {
            transform: rotate(180deg);
        }

        /* Dropdown menu - SIN VIÑETAS */
        .dropdown-menu-custom {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1050;
            background: var(--bg-navbar);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 0.5rem;
            margin-top: 0.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
            list-style: none !important;
        }

        .dropdown:hover .dropdown-menu-custom {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-menu-custom .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 1rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-secondary);
            border-radius: 8px;
            transition: all 0.2s;
            text-decoration: none;
            list-style: none !important;
        }

        .dropdown-menu-custom .dropdown-item i {
            width: 20px;
            font-size: 0.9rem;
            color: var(--primary);
        }

        .dropdown-menu-custom .dropdown-item:hover {
            background: var(--hover-bg);
            color: var(--primary);
            transform: translateX(3px);
        }

        .dropdown-menu-custom .dropdown-item.active {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(67, 97, 238, 0.05));
            color: var(--primary);
            font-weight: 600;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-top: 1px solid var(--border);
        }

        /* ========== NOTIFICACIONES ========== */
        .notifications-section {
            flex-shrink: 0;
            margin-right: 0.5rem;
        }

        .notifications-btn {
            background: var(--hover-bg);
            border: none;
            border-radius: 40px;
            padding: 0.5rem 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            color: var(--text-secondary);
        }

        .notifications-btn:hover {
            background: var(--border);
            transform: translateY(-2px);
            color: var(--primary);
        }

        .notifications-btn i {
            font-size: 1.1rem;
        }

        .notifications-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 20px;
            min-width: 18px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .notifications-badge.animate {
            animation: bounce 0.5s ease;
        }

        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            30% { transform: scale(1.3); }
            60% { transform: scale(0.9); }
        }

        .notifications-dropdown {
            width: 380px;
            max-width: 90vw;
            padding: 0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 35px -12px rgba(0,0,0,0.2);
            border: 1px solid var(--border);
        }

        .notifications-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.25rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
        }

        .notifications-header h6 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .btn-refresh {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 4px 8px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-refresh:hover {
            background: rgba(255,255,255,0.3);
            transform: rotate(180deg);
        }

        .notifications-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            transition: all 0.2s;
            cursor: pointer;
            text-decoration: none;
            color: var(--text-primary);
        }

        .notification-item:hover {
            background: var(--hover-bg);
            transform: translateX(3px);
        }

        .notification-item.new {
            background: linear-gradient(90deg, rgba(16,185,129,0.05), transparent);
            border-left: 3px solid #10b981;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, rgba(16,185,129,0.1), rgba(16,185,129,0.05));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notification-icon i {
            font-size: 1.2rem;
            color: #10b981;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
        }

        .notification-message {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 0.25rem;
        }

        .notification-time {
            font-size: 0.65rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .notification-monto {
            font-size: 0.8rem;
            font-weight: 700;
            color: #10b981;
            margin-top: 0.25rem;
        }

        .notification-empty {
            text-align: center;
            padding: 2rem;
            color: var(--text-muted);
        }

        .notification-empty i {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            opacity: 0.5;
        }

        .notifications-footer {
            padding: 0.75rem 1.25rem;
            background: var(--hover-bg);
            text-align: center;
            border-top: 1px solid var(--border);
        }

        .btn-view-all {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
        }

        .btn-view-all:hover {
            text-decoration: underline;
        }

        /* Toast de notificación */
        .payment-toast {
            position: fixed;
            top: 80px;
            right: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            z-index: 1050;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            border-left: 4px solid #10b981;
            min-width: 300px;
            max-width: 380px;
        }
        
        .payment-toast.show {
            transform: translateX(0);
        }
        
        .payment-toast-content {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
        }
        
        .payment-toast-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #10b98120, #10b98110);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: ring 0.5s ease;
        }
        
        @keyframes ring {
            0% { transform: rotate(0deg); }
            25% { transform: rotate(10deg); }
            50% { transform: rotate(-10deg); }
            75% { transform: rotate(5deg); }
            100% { transform: rotate(0deg); }
        }
        
        .payment-toast-icon i {
            font-size: 1.4rem;
            color: #10b981;
        }
        
        .payment-toast-text {
            flex: 1;
        }
        
        .payment-toast-text strong {
            display: block;
            font-size: 0.9rem;
            margin-bottom: 4px;
            color: #1e293b;
        }
        
        .payment-toast-text small {
            font-size: 0.7rem;
            color: #64748b;
            display: block;
            line-height: 1.3;
        }
        
        .toast-close {
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            padding: 5px;
            border-radius: 50%;
            transition: all 0.2s;
        }
        
        .toast-close:hover {
            background: #f0f0f0;
            color: #666;
        }

        /* User Section */
        .user-section {
            flex-shrink: 0;
        }
        
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.3rem 0.8rem 0.3rem 0.5rem;
            background: var(--hover-bg);
            border-radius: 40px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .user-dropdown:hover {
            background: var(--border);
            border-color: var(--primary-light);
            transform: translateY(-1px);
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-avatar i {
            font-size: 1rem;
            color: white;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.1rem;
        }

        .user-name {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .user-role {
            font-size: 0.6rem;
            color: var(--primary);
            font-weight: 600;
            line-height: 1.2;
            letter-spacing: 0.3px;
        }

        .user-dropdown i:last-child {
            font-size: 0.7rem;
            color: var(--text-muted);
            transition: transform 0.2s ease;
        }

        .user-dropdown:hover i:last-child {
            transform: rotate(180deg);
        }

        /* Botón menú móvil */
        .menu-toggle {
            display: none;
            background: var(--hover-bg);
            border: none;
            border-radius: 10px;
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .menu-toggle i {
            font-size: 1.2rem;
            color: var(--primary);
        }

        .menu-toggle:hover {
            background: var(--border);
            transform: scale(0.98);
        }

        /* Menu Overlay */
        .menu-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
        }

        /* ========== TEMPORIZADOR DE INACTIVIDAD ========== */
        .inactivity-timer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            color: white;
            padding: 8px 16px;
            border-radius: 40px;
            font-size: 0.75rem;
            z-index: 999;
            display: none;
            align-items: center;
            gap: 8px;
            font-family: monospace;
            font-weight: 500;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .inactivity-timer i {
            font-size: 0.8rem;
        }
        
        .inactivity-timer.warning {
            background: rgba(245, 158, 11, 0.95);
            animation: pulse 1s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.9; transform: scale(1.02); }
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            padding: 1.5rem;
            max-width: 100%;
            margin: 0 auto;
            min-height: calc(100vh - 120px);
        }

        @media (min-width: 1400px) {
            .main-content {
                max-width: 1400px;
                margin: 0 auto;
                padding: 2rem;
            }
        }

        /* ========== FOOTER ========== */
        .footer {
            background: var(--bg-navbar);
            border-top: 1px solid var(--border);
            padding: 1rem 0;
            text-align: center;
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 2rem;
        }

        /* ========== UTILIDADES ========== */
        .text-danger {
            color: var(--danger) !important;
        }

        .text-danger i {
            color: var(--danger) !important;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 1100px) {
            .nav-link span {
                display: none;
            }
            
            .nav-link i {
                font-size: 1.1rem;
            }
            
            .nav-link {
                padding: 0.5rem 0.8rem;
            }
            
            .dropdown-menu-custom {
                min-width: 200px;
            }
        }

        @media (max-width: 900px) {
            .menu-toggle {
                display: block;
            }
            
            .nav-menu {
                position: fixed;
                top: 60px;
                left: -300px;
                width: 280px;
                height: calc(100vh - 60px);
                background: var(--bg-navbar);
                flex-direction: column;
                align-items: stretch;
                justify-content: flex-start;
                padding: 1rem;
                gap: 0.5rem;
                box-shadow: 2px 0 20px rgba(0, 0, 0, 0.1);
                z-index: 999;
                transition: left 0.3s ease;
                overflow-y: auto;
                border-right: 1px solid var(--border);
            }
            
            .nav-menu.active {
                left: 0;
            }
            
            .menu-overlay.active {
                display: block;
            }
            
            .nav-link {
                width: 100%;
                justify-content: flex-start;
                padding: 0.75rem 1rem;
            }
            
            .nav-link span {
                display: inline;
            }
            
            .nav-link.active::before {
                display: none;
            }
            
            .dropdown-menu-custom {
                position: static;
                box-shadow: none;
                margin-top: 0.5rem;
                padding-left: 1.5rem;
                width: 100%;
                opacity: 1;
                visibility: visible;
                transform: none;
                display: none;
            }
            
            .dropdown.active .dropdown-menu-custom {
                display: block;
            }
            
            .dropdown-toggle::after {
                margin-left: auto;
            }
            
            .user-info {
                display: none;
            }
            
            .user-dropdown {
                padding: 0.3rem 0.6rem;
            }
            
            .user-avatar {
                width: 32px;
                height: 32px;
            }
            
            .notifications-dropdown {
                width: 320px;
            }
            
            .notification-item {
                padding: 0.75rem 1rem;
            }
            
            .notification-icon {
                width: 32px;
                height: 32px;
            }
            
            .notification-icon i {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .payment-toast {
                top: 70px;
                right: 10px;
                left: 10px;
                width: auto;
                max-width: none;
            }
        }

        /* Dark Mode */
        body.dark-mode .payment-toast {
            background: #1e293b;
        }
        
        body.dark-mode .payment-toast-text strong {
            color: #f1f5f9;
        }
        
        body.dark-mode .dropdown-menu-custom {
            background: #1e293b;
            border-color: #334155;
        }
        
        body.dark-mode .dropdown-menu-custom .dropdown-item {
            color: #e2e8f0;
        }
        
        body.dark-mode .dropdown-menu-custom .dropdown-item:hover {
            background: #334155;
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- NAVBAR MODERNO -->
    <nav class="navbar">
        <div class="navbar-container">
            <button class="menu-toggle" id="menuToggle" aria-label="Menú">
                <i class="fas fa-bars"></i>
            </button>

            <a href="{{ route('admin.dashboard') }}" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="SAINS Logo" class="logo-img">
            </a>

            <div class="nav-menu" id="navMenu">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.estudiantes*') ? 'active' : '' }}" href="{{ route('admin.estudiantes.index') }}">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.examenes*') ? 'active' : '' }}" href="{{ route('admin.examenes.index') }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Exámenes</span>
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.pagos*') ? 'active' : '' }}" href="{{ route('admin.pagos.index') }}">
                    <i class="fas fa-credit-card"></i>
                    <span>Pagos</span>
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.cupones*') ? 'active' : '' }}" href="{{ route('admin.cupones.index') }}">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Cupones</span>
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.administradores*') ? 'active' : '' }}" href="{{ route('admin.administradores.index') }}">
                    <i class="fas fa-user-shield"></i>
                    <span>Admins</span>
                </a>

                <a class="nav-link {{ request()->routeIs('admin.callcenter*') ? 'active' : '' }}" href="{{ route('admin.callcenter.index') }}">
                    <i class="fas fa-headset"></i>
                    <span>Call Center</span>
                </a>
   
                <a class="nav-link {{ request()->routeIs('admin.preguntas*') ? 'active' : '' }}" href="{{ route('admin.preguntas.index') }}">
                    <i class="fas fa-question-circle"></i>
                    <span>Preguntas</span>
                </a>
                
                <!-- DROPDOWN CATÁLOGOS - SIN VIÑETAS -->
                <div class="dropdown" id="catalogosDropdown">
                    <button class="nav-link dropdown-toggle" type="button" id="catalogosBtn">
                        <i class="fas fa-database"></i>
                        <span>Catálogos</span>
                    </button>
                    <div class="dropdown-menu-custom" id="catalogosMenu">
                        <a class="dropdown-item {{ request()->routeIs('admin.asignaturas*') ? 'active' : '' }}" href="{{ route('admin.asignaturas.index') }}">
                            <i class="fas fa-book"></i> <span>Materias</span>
                        </a>
                        <a class="dropdown-item {{ request()->routeIs('admin.carreras*') ? 'active' : '' }}" href="{{ route('admin.carreras.index') }}">
                            <i class="fas fa-graduation-cap"></i> <span>Carreras</span>
                        </a>
                        <a class="dropdown-item {{ request()->routeIs('admin.preparatorias*') ? 'active' : '' }}" href="{{ route('admin.preparatorias.index') }}">
                            <i class="fas fa-school"></i> <span>Preparatorias</span>
                        </a>
                        <a class="dropdown-item {{ request()->routeIs('admin.universidades*') ? 'active' : '' }}" href="{{ route('admin.universidades.index') }}">
                            <i class="fas fa-university"></i> <span>Universidades</span>
                        </a>
                        <a class="dropdown-item {{ request()->routeIs('admin.clases*') ? 'active' : '' }}" href="{{ route('admin.clases.index') }}">
                            <i class="fas fa-chalkboard"></i> <span>Clases</span>
                        </a>
                        <a class="dropdown-item {{ request()->routeIs('admin.videos*') ? 'active' : '' }}" href="{{ route('admin.videos.index') }}">
                            <i class="fas fa-video"></i> <span>Videos</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- NOTIFICACIONES DE PAGOS -->
            <div class="notifications-section">
                <div class="dropdown">
                    <button class="notifications-btn" type="button" data-bs-toggle="dropdown" id="notificationsBtn">
                        <i class="fas fa-bell"></i>
                        <span class="notifications-badge" id="notificationsBadge" style="display: none;">0</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end notifications-dropdown" id="notificationsDropdown">
                        <div class="notifications-header">
                            <h6><i class="fas fa-dollar-sign me-2"></i>Pagos Pendientes</h6>
                            <button class="btn-refresh" id="refreshNotifications" title="Actualizar">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div class="notifications-list" id="notificationsList">
                            <div class="text-center py-3 text-muted">
                                <i class="fas fa-spinner fa-spin"></i> Cargando...
                            </div>
                        </div>
                        <div class="notifications-footer">
                            <a href="{{ route('admin.pagos.index') }}" class="btn-view-all">
                                <i class="fas fa-arrow-right me-1"></i> Ver todos los pagos
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Section -->
            <div class="user-section">
                <div class="dropdown">
                    <button class="user-dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar" id="userAvatar">
                            @php
                                $admin = auth()->user()->administrador ?? null;
                                $fotoUrl = $admin && $admin->foto ? Storage::url($admin->foto) : null;
                                $tieneFoto = $admin && $admin->foto && $fotoUrl;
                            @endphp
                            
                            @if($tieneFoto)
                                <img src="{{ $fotoUrl }}" alt="Foto perfil">
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        </div>
                        <div class="user-info">
                            <span class="user-name">
                                {{ $admin->nombre ?? '' }} 
                                {{ $admin->apellido_paterno ?? '' }}
                            </span>
                            <span class="user-role">Administrador</span>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-custom dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.perfil') }}">
                                <i class="fas fa-user-edit"></i> Mi Perfil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="#" id="btnLogout">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Timer de inactividad - SOLO PARA ESTUDIANTES -->
    @auth
        @if(auth()->user()->rol === 'estudiante')
            <div class="inactivity-timer" id="inactivityTimer">
                <i class="fas fa-hourglass-half"></i>
                <span id="timerText">Sesión expirará en 5:00</span>
            </div>
        @endif
    @endauth

    <div class="menu-overlay" id="menuOverlay"></div>

    <main class="main-content fade-in">
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container-fluid">
            <span>© {{ date('Y') }} SAINS - Sistema de Administración Integral. Todos los derechos reservados.</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // ========== VARIABLES GLOBALES ==========
        let heartbeatInterval = null;
        let notificationInterval = null;
        let lastNotificationCount = 0;
        let lastNotificationIds = [];
        let notificationAudio = null;
        let sonidoHabilitado = true;
        
        // ========== SOLO PARA ESTUDIANTES - NO PARA ADMIN ==========
        let inactivityTimer = null;
        let warningTimer = null;
        let timeLeft = 30 * 60;
        const TIMEOUT_MINUTES = 30;
        let warningShown = false;
        
        // ========== DETERMINAR SI ES ESTUDIANTE O ADMIN ==========
        @auth
            const esEstudiante = {{ auth()->user()->rol === 'estudiante' ? 'true' : 'false' }};
            const esAdmin = {{ auth()->user()->rol === 'Administrador' ? 'true' : 'false' }};
        @else
            const esEstudiante = false;
            const esAdmin = false;
        @endauth
        
        // ========== FUNCIONES DE INACTIVIDAD (SOLO PARA ESTUDIANTES) ==========
        function resetInactivityTimer() {
            if (!esEstudiante) return;
            
            timeLeft = TIMEOUT_MINUTES * 60;
            warningShown = false;
            
            const timerDiv = document.getElementById('inactivityTimer');
            if (timerDiv) {
                timerDiv.style.display = 'none';
                timerDiv.classList.remove('warning');
            }
            
            if (inactivityTimer) clearTimeout(inactivityTimer);
            if (warningTimer) clearInterval(warningTimer);
            
            inactivityTimer = setTimeout(() => {
                showInactivityWarning();
            }, TIMEOUT_MINUTES * 60 * 1000);
        }
        
        function showInactivityWarning() {
            if (!esEstudiante) return;
            if (warningShown) return;
            warningShown = true;
            
            const timerDiv = document.getElementById('inactivityTimer');
            const timerText = document.getElementById('timerText');
            
            if (timerDiv && timerText) {
                timerDiv.style.display = 'flex';
                timerDiv.classList.add('warning');
                
                warningTimer = setInterval(() => {
                    timeLeft--;
                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    timerText.textContent = `Sesión expirará en ${minutes}:${seconds.toString().padStart(2, '0')}`;
                    
                    if (timeLeft <= 0) {
                        clearInterval(warningTimer);
                        cerrarSesionPorInactividad();
                    }
                }, 1000);
            }
            
            Swal.fire({
                title: '⚠️ ¿Sigues ahí?',
                html: `Tu sesión expirará en <strong>5 minutos</strong> por inactividad.<br><br>
                       <div class="progress" style="height: 5px;">
                           <div id="swalProgressBar" class="progress-bar bg-warning" style="width: 100%; transition: width 0.1s linear;"></div>
                       </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4361ee',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Seguir aquí',
                cancelButtonText: 'Cerrar sesión',
                timer: 300000,
                timerProgressBar: true,
                didOpen: () => {
                    const progressBar = Swal.getPopup().querySelector('#swalProgressBar');
                    if (progressBar) {
                        let width = 100;
                        const interval = setInterval(() => {
                            width -= 100 / 300;
                            if (progressBar) progressBar.style.width = Math.max(0, width) + '%';
                            if (width <= 0) clearInterval(interval);
                        }, 1000);
                    }
                },
                willClose: () => {
                    if (warningTimer) clearInterval(warningTimer);
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    resetInactivityTimer();
                    fetch('{{ route("estudiante.heartbeat") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).catch(e => console.log('Heartbeat error:', e));
                } else {
                    cerrarSesionPorInactividad();
                }
            });
        }
        
        function cerrarSesionPorInactividad() {
            if (!esEstudiante) return;
            
            if (warningTimer) clearInterval(warningTimer);
            if (heartbeatInterval) clearInterval(heartbeatInterval);
            
            Swal.fire({
                title: 'Sesión expirada',
                text: 'Tu sesión ha expirado por inactividad',
                icon: 'info',
                confirmButtonColor: '#4361ee',
                confirmButtonText: 'Aceptar',
                allowOutsideClick: false
            }).then(() => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('logout') }}";
                form.style.display = 'none';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            });
        }
        
        const activityEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click', 'keydown'];
        
        function startInactivityTracking() {
            if (!esEstudiante) return;
            resetInactivityTimer();
            activityEvents.forEach(event => {
                document.addEventListener(event, resetInactivityTimer);
            });
        }
        
        function stopInactivityTracking() {
            if (inactivityTimer) clearTimeout(inactivityTimer);
            if (warningTimer) clearInterval(warningTimer);
            activityEvents.forEach(event => {
                document.removeEventListener(event, resetInactivityTimer);
            });
        }
        
        // ========== HEARTBEAT (SOLO PARA ESTUDIANTES) ==========
        function iniciarHeartbeat() {
            if (!esEstudiante) return;
            
            if (heartbeatInterval) clearInterval(heartbeatInterval);
            
            heartbeatInterval = setInterval(() => {
                fetch('{{ route("estudiante.heartbeat") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    credentials: 'same-origin'
                }).catch(e => console.log('Heartbeat error:', e));
            }, 60000);
        }
        
        // ========== NOTIFICACIONES DE PAGOS CON SONIDO MEJORADO ==========
        
        function crearAudioNotificacion() {
            try {
                notificationAudio = new Audio('https://cdn.pixabay.com/download/audio/2022/05/27/audio_1c8e2e5c2b.mp3?filename=cash-register-199277.mp3');
                notificationAudio.volume = 0.5;
                notificationAudio.preload = 'auto';
                notificationAudio.load();
                console.log('🔊 Sistema de sonido de notificaciones inicializado');
            } catch(e) {
                console.log('⚠️ Audio no soportado en este navegador:', e);
            }
        }

        function reproducirSonidoNotificacion() {
            if (!sonidoHabilitado) return;
            
            if (notificationAudio && esAdmin) {
                notificationAudio.currentTime = 0;
                notificationAudio.play().catch(e => {
                    console.log('❌ Error al reproducir sonido:', e);
                    notificationAudio.load();
                    setTimeout(() => {
                        notificationAudio.play().catch(e => console.log('❌ Segundo intento fallido:', e));
                    }, 100);
                });
            }
        }
        
        function reproducirSonidoMultiple(veces = 2, intervalo = 400) {
            for (let i = 0; i < veces; i++) {
                setTimeout(() => {
                    reproducirSonidoNotificacion();
                }, i * intervalo);
            }
        }
        
        function mostrarToastNotificacion(mensaje, estudiante, monto) {
            if (window.lastToastTime && (Date.now() - window.lastToastTime) < 4000) {
                return;
            }
            window.lastToastTime = Date.now();
            
            const toast = document.createElement('div');
            toast.className = 'payment-toast';
            toast.innerHTML = `
                <div class="payment-toast-content">
                    <div class="payment-toast-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="payment-toast-text">
                        <strong>💰 ¡Nuevo pago registrado!</strong>
                        <small>${estudiante}</small>
                        <small style="font-weight: 700; color: #10b981; margin-top: 4px;">${monto}</small>
                    </div>
                    <button class="toast-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(toast);
            setTimeout(() => toast.classList.add('show'), 100);
            
            const closeBtn = toast.querySelector('.toast-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                });
            }
            
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 6000);
        }
        
        function mostrarAlertaNuevoPago(totalNuevos, detalles = null) {
            if (!esAdmin) return;
            
            const texto = totalNuevos === 1 ? 'nuevo pago pendiente' : 'nuevos pagos pendientes';
            
            if (detalles) {
                mostrarToastNotificacion(
                    `Hay ${totalNuevos} ${texto} por revisar`,
                    detalles.estudiante || 'Cliente',
                    detalles.monto || `$${detalles.monto || '0'}`
                );
            } else {
                const toast = document.createElement('div');
                toast.className = 'payment-toast';
                toast.innerHTML = `
                    <div class="payment-toast-content">
                        <div class="payment-toast-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="payment-toast-text">
                            <strong>💰 ${totalNuevos} ${texto}!</strong>
                            <small>Hay pagos por revisar en el sistema</small>
                        </div>
                        <button class="toast-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                
                document.body.appendChild(toast);
                setTimeout(() => toast.classList.add('show'), 100);
                
                const closeBtn = toast.querySelector('.toast-close');
                if (closeBtn) {
                    closeBtn.addEventListener('click', () => {
                        toast.classList.remove('show');
                        setTimeout(() => toast.remove(), 300);
                    });
                }
                
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            }
            
            if (!document.hidden && document.hasFocus() && totalNuevos > 0) {
                Swal.fire({
                    title: '💰 ¡Nuevo pago pendiente!',
                    html: `<div style="text-align: left;">
                               <p>Se ha${totalNuevos === 1 ? '' : 'n'} detectado <strong>${totalNuevos} ${texto}</strong>.</p>
                               <p>Por favor, revise los comprobantes en la sección de pagos.</p>
                               <hr>
                               <small class="text-muted">🕒 Actualizado automáticamente cada 30 segundos</small>
                           </div>`,
                    icon: 'info',
                    confirmButtonColor: '#4361ee',
                    confirmButtonText: '📋 Ver pagos',
                    showCancelButton: true,
                    cancelButtonText: '🔕 Ignorar',
                    timer: 10000,
                    timerProgressBar: true,
                    backdrop: true,
                    allowOutsideClick: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route("admin.pagos.index") }}';
                    }
                });
            }
        }

        async function cargarNotificaciones() {
            if (!esAdmin) return;
            
            try {
                const response = await fetch('{{ route("admin.api.notificaciones") }}', {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                
                if (data.success) {
                    const notificaciones = data.notificaciones;
                    const totalPendientes = data.total_pendientes;
                    
                    const nuevosIds = notificaciones.map(n => n.id);
                    let hayNuevas = false;
                    let cantidadNuevas = 0;
                    let primerNuevo = null;
                    
                    if (lastNotificationCount > 0 && totalPendientes > lastNotificationCount) {
                        cantidadNuevas = totalPendientes - lastNotificationCount;
                        hayNuevas = true;
                    } else if (lastNotificationIds.length > 0) {
                        const idsAntiguos = new Set(lastNotificationIds);
                        for (const notif of notificaciones) {
                            if (!idsAntiguos.has(notif.id)) {
                                cantidadNuevas++;
                                hayNuevas = true;
                                if (!primerNuevo) primerNuevo = notif;
                            }
                        }
                    } else if (totalPendientes > 0 && lastNotificationCount === 0 && notificaciones.length > 0) {
                        hayNuevas = true;
                        cantidadNuevas = totalPendientes;
                        primerNuevo = notificaciones[0];
                    }
                    
                    const badge = document.getElementById('notificationsBadge');
                    if (badge) {
                        if (totalPendientes > 0) {
                            badge.style.display = 'block';
                            badge.textContent = totalPendientes > 99 ? '99+' : totalPendientes;
                            
                            if (hayNuevas && lastNotificationCount > 0) {
                                reproducirSonidoMultiple(2, 300);
                                badge.classList.remove('animate');
                                badge.offsetHeight;
                                badge.classList.add('animate');
                                
                                if (primerNuevo) {
                                    mostrarAlertaNuevoPago(cantidadNuevas, {
                                        estudiante: primerNuevo.estudiante,
                                        monto: primerNuevo.monto
                                    });
                                } else {
                                    mostrarAlertaNuevoPago(cantidadNuevas);
                                }
                                
                                const originalTitle = document.title;
                                document.title = `💰 ${totalPendientes} nuevo(s) pago(s) - SAINS`;
                                setTimeout(() => {
                                    document.title = originalTitle;
                                }, 5000);
                            }
                            
                            lastNotificationCount = totalPendientes;
                            lastNotificationIds = nuevosIds;
                        } else {
                            badge.style.display = 'none';
                            lastNotificationCount = 0;
                            lastNotificationIds = [];
                        }
                    }
                    
                    actualizarListaNotificaciones(notificaciones, totalPendientes, nuevosIds);
                }
            } catch (error) {
                console.error('Error cargando notificaciones:', error);
            }
        }

        function actualizarListaNotificaciones(notificaciones, totalPendientes, nuevosIds) {
            const listaContainer = document.getElementById('notificationsList');
            if (!listaContainer) return;
            
            if (notificaciones.length === 0) {
                listaContainer.innerHTML = `
                    <div class="notification-empty">
                        <i class="fas fa-check-circle"></i>
                        <p>No hay pagos pendientes</p>
                        <small>Todos los pagos están revisados</small>
                    </div>
                `;
                return;
            }
            
            const esNuevo = (id) => lastNotificationIds.length > 0 && !lastNotificationIds.includes(id);
            
            listaContainer.innerHTML = notificaciones.map(notif => `
                <a href="${notif.url}" class="notification-item ${esNuevo(notif.id) ? 'new' : ''}" data-id="${notif.id}">
                    <div class="notification-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">💰 Nuevo pago pendiente</div>
                        <div class="notification-message">${notif.mensaje}</div>
                        <div class="notification-monto">$${Number(notif.monto).toLocaleString('es-MX')}</div>
                        <div class="notification-time">
                            <i class="fas fa-clock"></i> ${notif.fecha}
                        </div>
                    </div>
                    ${esNuevo(notif.id) ? '<div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%;"></div>' : ''}
                </a>
            `).join('');
        }

        function iniciarNotificaciones() {
            if (!esAdmin) return;
            
            crearAudioNotificacion();
            cargarNotificaciones();
            
            if (notificationInterval) clearInterval(notificationInterval);
            notificationInterval = setInterval(cargarNotificaciones, 30000);
            
            const refreshBtn = document.getElementById('refreshNotifications');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', () => {
                    cargarNotificaciones();
                    const icon = refreshBtn.querySelector('i');
                    if (icon) {
                        icon.style.transform = 'rotate(180deg)';
                        setTimeout(() => {
                            icon.style.transform = '';
                        }, 300);
                    }
                    Swal.fire({
                        title: 'Actualizando...',
                        text: 'Buscando nuevos pagos',
                        icon: 'info',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                });
            }
            
            console.log('🔔 Sistema de notificaciones de pagos iniciado');
        }
        
        // ========== MENÚ RESPONSIVE ==========
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');
        const menuOverlay = document.getElementById('menuOverlay');
        
        function closeMenu() {
            navMenu.classList.remove('active');
            menuOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        function openMenu() {
            navMenu.classList.add('active');
            menuOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        if (menuToggle) {
            menuToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                if (navMenu.classList.contains('active')) {
                    closeMenu();
                } else {
                    openMenu();
                }
            });
        }
        
        if (menuOverlay) {
            menuOverlay.addEventListener('click', closeMenu);
        }
        
        document.querySelectorAll('.nav-link:not(.dropdown-toggle)').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 900) {
                    closeMenu();
                }
            });
        });
        
        function initCatalogosDropdown() {
            const catalogosDropdown = document.getElementById('catalogosDropdown');
            const catalogosBtn = document.getElementById('catalogosBtn');
            
            if (!catalogosDropdown || !catalogosBtn) return;
            
            if (window.innerWidth <= 900) {
                catalogosBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    catalogosDropdown.classList.toggle('active');
                });
            } else {
                catalogosDropdown.classList.remove('active');
            }
        }
        
        initCatalogosDropdown();
        
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth > 900) {
                    closeMenu();
                    const catalogosDropdown = document.getElementById('catalogosDropdown');
                    if (catalogosDropdown) {
                        catalogosDropdown.classList.remove('active');
                    }
                    document.body.style.overflow = '';
                }
                initCatalogosDropdown();
            }, 250);
        });

        // ========== FUNCIONES DE SWEETALERT ==========
        window.mostrarExito = (msg) => Swal.fire({ icon: 'success', title: '¡Éxito!', text: msg, background: '#ffffff', color: '#0f172a', confirmButtonColor: '#4361ee', timer: 3000 });
        window.mostrarError = (msg) => Swal.fire({ icon: 'error', title: 'Error', text: msg, background: '#ffffff', color: '#0f172a', confirmButtonColor: '#4361ee' });
        window.mostrarAlerta = (msg) => Swal.fire({ icon: 'warning', title: 'Atención', text: msg, background: '#ffffff', color: '#0f172a', confirmButtonColor: '#4361ee' });
        window.mostrarInfo = (msg) => Swal.fire({ icon: 'info', title: 'Información', text: msg, background: '#ffffff', color: '#0f172a', confirmButtonColor: '#4361ee' });

        window.confirmarEliminar = (url, titulo = '¿Eliminar registro?', msg = 'Esta acción no se puede revertir') => {
            Swal.fire({
                title: titulo,
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: '#ffffff',
                color: '#0f172a'
            }).then(result => result.isConfirmed && (window.location.href = url));
        };

        window.confirmarAccion = (url, titulo = '¿Estás seguro?', msg = '¿Deseas continuar?', confirmText = 'Sí, continuar') => {
            Swal.fire({
                title: titulo,
                text: msg,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4361ee',
                cancelButtonColor: '#6c757d',
                confirmButtonText: confirmText,
                cancelButtonText: 'Cancelar',
                background: '#ffffff',
                color: '#0f172a'
            }).then(result => result.isConfirmed && (window.location.href = url));
        };

        // ========== CERRAR SESIÓN ==========
        const logoutBtn = document.getElementById('btnLogout');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', (e) => {
                e.preventDefault();
                Swal.fire({
                    title: '¿Cerrar sesión?',
                    text: '¿Estás seguro de que quieres salir?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4361ee',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, salir',
                    cancelButtonText: 'Cancelar',
                    background: '#ffffff',
                    color: '#0f172a'
                }).then(result => {
                    if (result.isConfirmed) {
                        stopInactivityTracking();
                        if (heartbeatInterval) clearInterval(heartbeatInterval);
                        if (notificationInterval) clearInterval(notificationInterval);
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = "{{ route('logout') }}";
                        form.style.display = 'none';
                        
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        
                        form.appendChild(csrfToken);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        }

        // ========== INICIALIZAR ==========
        document.addEventListener('DOMContentLoaded', function() {
            if (esEstudiante) {
                iniciarHeartbeat();
                startInactivityTracking();
            }
            if (esAdmin) {
                iniciarNotificaciones();
            }
            
            const precargarAudio = function() {
                if (notificationAudio && notificationAudio.readyState === 0) {
                    notificationAudio.load();
                }
                document.removeEventListener('click', precargarAudio);
                document.removeEventListener('touchstart', precargarAudio);
            };
            document.addEventListener('click', precargarAudio);
            document.addEventListener('touchstart', precargarAudio);
        });

        window.addEventListener('beforeunload', function() {
            if (notificationInterval) {
                clearInterval(notificationInterval);
            }
            if (heartbeatInterval) {
                clearInterval(heartbeatInterval);
            }
        });

        @if(session('success')) mostrarExito("{{ session('success') }}"); @endif
        @if(session('error')) mostrarError("{{ session('error') }}"); @endif
        @if(session('warning')) mostrarAlerta("{{ session('warning') }}"); @endif
        @if(session('info')) mostrarInfo("{{ session('info') }}"); @endif
    </script>

    @stack('scripts')
</body>
</html>