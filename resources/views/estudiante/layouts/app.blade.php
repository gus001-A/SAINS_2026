<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SAINS - Sistema de Aprendizaje')</title>

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

        /* ========== ELIMINAR VIÑETAS DE LISTAS ========== */
        .dropdown-menu-custom,
        .dropdown-menu-custom ul,
        .dropdown-menu-custom li,
        ul, 
        ol,
        .list-unstyled {
            list-style: none !important;
            padding-left: 0 !important;
            margin-bottom: 0;
        }

        .dropdown-item,
        li {
            list-style: none !important;
        }

        /* ========== NAVBAR RESPONSIVO ========== */
        .navbar {
            background: var(--bg-navbar);
            border-bottom: 1px solid var(--border);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            max-width: 100%;
            margin: 0;
            padding: 0 1rem;
        }

        @media (min-width: 1400px) {
            .navbar-container {
                max-width: 1400px;
                margin: 0 auto;
                padding: 0 1.5rem;
            }
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            flex-shrink: 0;
        }
        
        .logo-img {
            height: 40px;
            width: auto;
            display: block;
        }

        @media (max-width: 576px) {
            .logo-img {
                height: 32px;
            }
        }

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
            font-size: 1.3rem;
            color: var(--primary);
        }

        .menu-toggle:hover {
            background: var(--border);
            transform: scale(0.98);
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            flex-wrap: wrap;
            justify-content: center;
            flex: 1;
            transition: all 0.3s ease;
        }

        @media (max-width: 992px) {
            .menu-toggle {
                display: block;
            }
            
            .nav-menu {
                position: fixed;
                top: 60px;
                left: -280px;
                width: 280px;
                height: calc(100vh - 60px);
                background: var(--bg-navbar);
                flex-direction: column;
                align-items: stretch;
                justify-content: flex-start;
                padding: 1rem;
                gap: 0.5rem;
                box-shadow: 2px 0 10px rgba(0,0,0,0.1);
                z-index: 999;
                transition: left 0.3s ease;
                overflow-y: auto;
                flex: none;
            }
            
            .nav-menu.active {
                left: 0;
            }
            
            .menu-overlay {
                display: none;
                position: fixed;
                top: 60px;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 998;
            }
            
            .menu-overlay.active {
                display: block;
            }
            
            .nav-link {
                width: 100%;
                justify-content: flex-start;
                padding: 0.75rem 1rem;
            }
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.6rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 10px;
            transition: all 0.2s ease;
            white-space: nowrap;
            background: none;
            border: none;
            cursor: pointer;
        }

        @media (min-width: 993px) and (max-width: 1200px) {
            .nav-link span {
                display: none;
            }
            
            .nav-link i {
                font-size: 1.2rem;
            }
            
            .nav-link {
                padding: 0.6rem 0.9rem;
            }
        }

        @media (min-width: 1201px) {
            .nav-link span {
                display: inline;
            }
        }

        .nav-link:hover {
            background: var(--hover-bg);
            color: var(--primary);
            transform: translateY(-2px);
        }

        .nav-link.active {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }

        /* ========== USER SECTION CON FOTO ========== */
        .user-section {
            flex-shrink: 0;
        }
        
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.3rem 0.8rem;
            background: var(--hover-bg);
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            min-width: auto;
        }

        .user-dropdown:hover {
            background: var(--border);
            transform: translateY(-2px);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
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
            font-size: 0.65rem;
            color: var(--primary);
            font-weight: 500;
            line-height: 1.2;
        }

        @media (max-width: 576px) {
            .user-info {
                display: none;
            }
            
            .user-dropdown {
                padding: 0.3rem 0.6rem;
            }
            
            .user-avatar {
                width: 28px;
                height: 28px;
            }
        }

        .user-dropdown i:last-child {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-left: auto;
        }

        /* Dropdown menu */
        .dropdown-menu-custom {
            background: var(--bg-navbar);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 0.5rem;
            margin-top: 0.5rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            min-width: 220px;
        }

        .dropdown-menu-custom .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-secondary);
            border-radius: 8px;
            transition: all 0.2s;
            text-decoration: none;
            cursor: pointer;
        }

        .dropdown-menu-custom .dropdown-item i {
            width: 22px;
            font-size: 1rem;
            color: var(--primary);
        }

        .dropdown-menu-custom .dropdown-item:hover {
            background: var(--hover-bg);
            color: var(--primary);
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-top: 1px solid var(--border);
        }

        .text-danger {
            color: var(--danger) !important;
        }

        .text-danger i {
            color: var(--danger) !important;
        }

        /* Main content */
        .main-content {
            padding: 1rem;
            max-width: 100%;
            margin: 0 auto;
            min-height: calc(100vh - 120px);
        }

        @media (min-width: 768px) {
            .main-content {
                padding: 1.5rem;
            }
        }

        @media (min-width: 1400px) {
            .main-content {
                max-width: 1400px;
                margin: 0 auto;
                padding: 2rem 1.5rem;
            }
        }

        .footer {
            background: var(--bg-navbar);
            border-top: 1px solid var(--border);
            padding: 0.75rem 0;
            text-align: center;
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: 2rem;
        }

        @media (min-width: 768px) {
            .footer {
                padding: 1rem 0;
                font-size: 0.8rem;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease;
        }

        /* Utilidades */
        .cursor-pointer {
            cursor: pointer;
        }

        /* Estilos para el perfil */
        .stat-card {
            background: white;
            border-radius: 24px;
            padding: 1.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
            border: 1px solid var(--border);
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            filter: brightness(1.05);
            color: white;
        }
        
        .form-control, .form-select {
            border-radius: 12px;
            border: 1px solid var(--border);
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        .form-label {
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            color: var(--text-secondary);
        }

        /* Estilos para el temporizador de inactividad */
        .inactivity-timer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 8px 15px;
            border-radius: 50px;
            font-size: 0.75rem;
            z-index: 999;
            display: none;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(10px);
            font-family: monospace;
        }
        
        .inactivity-timer i {
            font-size: 0.8rem;
        }
        
        .inactivity-timer.warning {
            background: rgba(245, 158, 11, 0.9);
            animation: pulse 1s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- NAVBAR RESPONSIVO -->
    <nav class="navbar">
        <div class="navbar-container">
            <button class="menu-toggle" id="menuToggle" aria-label="Menú">
                <i class="fas fa-bars"></i>
            </button>

            <a href="{{ route('estudiante.dashboard') }}" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="SAINS Logo" class="logo-img">
            </a>

            <div class="nav-menu" id="navMenu">
                <a class="nav-link {{ request()->routeIs('estudiante.dashboard') ? 'active' : '' }}" 
                   href="{{ route('estudiante.dashboard') }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                
                <a class="nav-link {{ request()->routeIs('estudiante.examenes') ? 'active' : '' }}" 
                   href="{{ route('estudiante.examenes') }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Mis Exámenes</span>
                </a>
                
                <a class="nav-link {{ request()->routeIs('estudiante.progreso') ? 'active' : '' }}" 
                   href="{{ route('estudiante.progreso') }}">
                    <i class="fas fa-chart-simple"></i>
                    <span>Mi Progreso</span>
                </a>
                
                <a class="nav-link {{ request()->routeIs('estudiante.simulador') ? 'active' : '' }}" 
                   href="{{ route('estudiante.simulador') }}">
                    <i class="fas fa-laptop-code"></i>
                    <span>Simulador</span>
                </a>
            </div>

            <!-- User Section CON FOTO DE PERFIL -->
            <div class="user-section">
                <div class="dropdown">
                    <button class="user-dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar" id="userAvatar">
                            @php
                                $estudiante = $estudiante ?? (Auth::user() ? Auth::user()->estudiante : null);
                                $user = Auth::user();
                                $fotoUrl = $estudiante && $estudiante->foto 
                                    ? Storage::url($estudiante->foto) 
                                    : null;
                                $tieneFoto = $estudiante && $estudiante->foto && $fotoUrl;
                            @endphp
                            
                            @if($tieneFoto)
                                <img src="{{ $fotoUrl }}" alt="Foto perfil" id="userAvatarImg">
                            @else
                                <i class="fas fa-user" id="userAvatarIcon"></i>
                            @endif
                        </div>
                        <div class="user-info">
                            <span class="user-name" id="userName">
                                @if(isset($estudiante) && $estudiante)
                                    {{ $estudiante->nombre ?? '' }} {{ $estudiante->paterno ?? '' }}
                                @else
                                    {{ Auth::user()->correo ?? 'Estudiante' }}
                                @endif
                            </span>
                            <span class="user-role">Estudiante</span>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-custom dropdown-menu-end">
                        <!-- SOLO MI PERFIL -->
                        <li>
                            <a class="dropdown-item" href="{{ route('estudiante.perfil') }}">
                                <i class="fas fa-user-edit"></i> Mi Perfil
                            </a>
                        </li>
                        
                        <!-- SOLO CERRAR SESIÓN -->
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

    <!-- Timer de inactividad -->
    <div class="inactivity-timer" id="inactivityTimer">
        <i class="fas fa-hourglass-half"></i>
        <span id="timerText">Sesión expirará en 5:00</span>
    </div>

    <div class="menu-overlay" id="menuOverlay"></div>

    <main class="main-content fade-in">
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container-fluid">
            <span>© {{ date('Y') }} SAINS - Sistema de Aprendizaje Integral. Todos los derechos reservados.</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Variables globales
        let heartbeatInterval = null;
        let inactivityTimer = null;
        let warningTimer = null;
        let timeLeft = 30 * 60; // 30 minutos en segundos
        const TIMEOUT_MINUTES = 30;
        let warningShown = false;
        
        // ========== CONTROL DE INACTIVIDAD ==========
        function resetInactivityTimer() {
            // Resetear el tiempo restante
            timeLeft = TIMEOUT_MINUTES * 60;
            warningShown = false;
            
            // Ocultar el timer visual si estaba visible
            const timerDiv = document.getElementById('inactivityTimer');
            if (timerDiv) {
                timerDiv.style.display = 'none';
                timerDiv.classList.remove('warning');
            }
            
            // Limpiar timers existentes
            if (inactivityTimer) clearTimeout(inactivityTimer);
            if (warningTimer) clearInterval(warningTimer);
            
            // Iniciar nuevo timer
            inactivityTimer = setTimeout(() => {
                showInactivityWarning();
            }, TIMEOUT_MINUTES * 60 * 1000);
        }
        
        function showInactivityWarning() {
            if (warningShown) return;
            warningShown = true;
            
            // Mostrar el timer visual
            const timerDiv = document.getElementById('inactivityTimer');
            const timerText = document.getElementById('timerText');
            
            if (timerDiv && timerText) {
                timerDiv.style.display = 'flex';
                timerDiv.classList.add('warning');
                
                // Actualizar el contador cada segundo
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
            
            // Mostrar SweetAlert de advertencia
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
                timer: 300000, // 5 minutos
                timerProgressBar: true,
                didOpen: () => {
                    const progressBar = Swal.getPopup().querySelector('#swalProgressBar');
                    if (progressBar) {
                        let width = 100;
                        const interval = setInterval(() => {
                            width -= 100 / 300; // 300 = 5 minutos / 1 segundo
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
                    // Usuario activo, resetear timers
                    resetInactivityTimer();
                    // Hacer heartbeat para mantener sesión activa
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
        
        // Eventos que indican actividad del usuario
        const activityEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click', 'keydown'];
        
        function startInactivityTracking() {
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
        
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 992) {
                    closeMenu();
                }
            });
        });
        
        window.addEventListener('resize', function() {
            if (window.innerWidth > 992) {
                closeMenu();
            }
        });

        // ========== FUNCIONES DE SWEETALERT ==========
        window.mostrarExito = (msg) => Swal.fire({ 
            icon: 'success', 
            title: '¡Éxito!', 
            text: msg, 
            background: '#ffffff', 
            color: '#0f172a', 
            confirmButtonColor: '#4361ee', 
            timer: 3000 
        });
        
        window.mostrarError = (msg) => Swal.fire({ 
            icon: 'error', 
            title: 'Error', 
            text: msg, 
            background: '#ffffff', 
            color: '#0f172a', 
            confirmButtonColor: '#4361ee' 
        });
        
        window.mostrarAlerta = (msg) => Swal.fire({ 
            icon: 'warning', 
            title: 'Atención', 
            text: msg, 
            background: '#ffffff', 
            color: '#0f172a', 
            confirmButtonColor: '#4361ee' 
        });
        
        window.mostrarInfo = (msg) => Swal.fire({ 
            icon: 'info', 
            title: 'Información', 
            text: msg, 
            background: '#ffffff', 
            color: '#0f172a', 
            confirmButtonColor: '#4361ee' 
        });

        // ========== HEARTBEAT (Registro de actividad) ==========
        function iniciarHeartbeat() {
            if (heartbeatInterval) clearInterval(heartbeatInterval);
            
            heartbeatInterval = setInterval(() => {
                fetch('{{ route("estudiante.heartbeat") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    credentials: 'same-origin'
                })
                .catch(error => console.log('Heartbeat error:', error));
            }, 60000); // Cada minuto
        }

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
                        if (heartbeatInterval) clearInterval(heartbeatInterval);
                        stopInactivityTracking();
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
            iniciarHeartbeat();
            
            // Iniciar el tracking de inactividad SOLO si el usuario está autenticado
            @if(Auth::check())
                startInactivityTracking();
            @endif
        });

        // Mostrar mensajes flash
        @if(session('success')) mostrarExito("{{ session('success') }}"); @endif
        @if(session('error')) mostrarError("{{ session('error') }}"); @endif
        @if(session('warning')) mostrarAlerta("{{ session('warning') }}"); @endif
        @if(session('info')) mostrarInfo("{{ session('info') }}"); @endif
    </script>

    @stack('scripts')
</body>
</html>