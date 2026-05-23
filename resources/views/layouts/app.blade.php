<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SAINS | Panel de Estudiante')</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            overflow-x: hidden;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 280px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            transition: all 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar.collapsed {
            margin-left: -280px;
        }
        
        .sidebar-header {
            padding: 25px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header img {
            height: 50px;
            margin-bottom: 15px;
        }
        
        .student-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            border: 3px solid rgba(255,255,255,0.3);
        }
        
        .student-avatar i {
            font-size: 40px;
            color: white;
        }
        
        .student-name {
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .student-email {
            font-size: 12px;
            opacity: 0.7;
            word-break: break-all;
        }
        
        .nav-menu {
            padding: 20px 0;
        }
        
        .nav-item {
            padding: 12px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.8);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .nav-item:hover, .nav-item.active {
            background: rgba(102,126,234,0.3);
            color: white;
            border-left: 3px solid #667eea;
        }
        
        .nav-item i {
            width: 24px;
            font-size: 18px;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 280px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }
        
        .main-content.expanded {
            margin-left: 0;
        }
        
        /* Top Navbar */
        .top-navbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .menu-toggle {
            background: none;
            border: none;
            font-size: 24px;
            color: #333;
            cursor: pointer;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar-small {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Cards */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        
        .stat-icon i {
            font-size: 28px;
            color: white;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 14px;
        }
        
        /* Botones */
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.4);
            color: white;
        }
        
        .btn-outline-gradient {
            background: transparent;
            border: 2px solid #667eea;
            color: #667eea;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-gradient:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
        }
        
        /* Modal de pago */
        .payment-modal .modal-content {
            border-radius: 30px;
            overflow: hidden;
        }
        
        .payment-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        .payment-option {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .payment-option:hover {
            border-color: #667eea;
            transform: translateY(-5px);
        }
        
        /* Modal de completar perfil */
        .profile-modal .modal-content {
            border-radius: 30px;
            overflow: hidden;
        }
        
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -280px;
            }
            
            .sidebar.mobile-open {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo_u.png') }}" alt="Logo" style="filter: brightness(0) invert(1);">
            <div class="student-avatar">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="student-name">
                @if(isset($estudiante) && $estudiante)
                    {{ $estudiante->nombre }} {{ $estudiante->paterno }}
                @else
                    Estudiante
                @endif
            </div>
            <div class="student-email">{{ $user->correo ?? 'usuario@ejemplo.com' }}</div>
        </div>
        
        <div class="nav-menu">
            @yield('sidebar-menu')
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="user-info">
                <span>Bienvenido, {{ $estudiante->nombre ?? 'Estudiante' }}</span>
                <div class="user-avatar-small">
                    <i class="fas fa-user text-white"></i>
                </div>
            </div>
        </div>
        
        <!-- Contenido Dinámico -->
        <div class="container-fluid p-4" id="contenidoDinamico">
            @yield('content')
        </div>
    </div>
    
    <!-- Modales Globales -->
    @include('layouts.partials.modales')
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Funciones globales
        let modalPlanes, modalRegistrarPago, modalCompletarPerfil;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar modales si existen
            const elPlanes = document.getElementById('modalPlanesPago');
            const elRegistrar = document.getElementById('modalRegistrarPago');
            const elPerfil = document.getElementById('modalCompletarPerfil');
            
            if (elPlanes) modalPlanes = new bootstrap.Modal(elPlanes);
            if (elRegistrar) modalRegistrarPago = new bootstrap.Modal(elRegistrar);
            if (elPerfil) modalCompletarPerfil = new bootstrap.Modal(elPerfil);
            
            // Toggle sidebar en móvil
            const menuToggle = document.getElementById('menuToggle');
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    document.getElementById('sidebar').classList.toggle('mobile-open');
                });
            }
            
            // Si el perfil no está completo, mostrar el modal automáticamente
            @if(isset($perfilCompleto) && !$perfilCompleto)
                if (modalCompletarPerfil) modalCompletarPerfil.show();
            @endif
        });
        
        function abrirModalCompletarPerfil() {
            if (modalCompletarPerfil) modalCompletarPerfil.show();
        }
        
        function mostrarModalPago() {
            if (modalPlanes) modalPlanes.show();
        }
        
        function mostrarModalRegistrarPago() {
            if (modalRegistrarPago) modalRegistrarPago.show();
        }
        
        function cerrarSesion() {
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: '¿Estás seguro de que quieres salir?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, salir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formLogout').submit();
                }
            });
        }
        
        function seleccionarPago(tipo) {
            if (modalPlanes) modalPlanes.hide();
            
            if (tipo === 'oxxo') {
                Swal.fire({
                    title: 'Pago en Oxxo',
                    html: `
                        <p>Realiza tu pago en cualquier sucursal Oxxo con la siguiente referencia:</p>
                        <h3 class="text-primary">SAINS-{{ $user->id ?? 'USUARIO' }}-{{ date('Ymd') }}</h3>
                        <p>Monto: $899 MXN</p>
                        <hr>
                        <p>Después de realizar tu pago, registra tu comprobante en el apartado <strong>"Registrar Pago"</strong></p>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Entendido'
                });
            } else if (tipo === 'banamex') {
                const link = document.createElement('a');
                link.href = '{{ asset("images/p_3.jpg") }}';
                link.download = 'ficha_pago_banamex.jpg';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                Swal.fire({
                    title: 'Ficha descargada',
                    text: 'La ficha de depósito Banamex se ha descargado. Realiza tu depósito y luego registra tu pago.',
                    icon: 'success',
                    confirmButtonText: 'Entendido'
                });
            } else if (tipo === 'paypal') {
                window.open('https://www.paypal.com/mx/webapps/mpp/make-online-payments', '_blank');
                Swal.fire({
                    title: 'Redirigiendo a PayPal',
                    text: 'Serás redirigido a PayPal para completar tu pago. Después, registra tu comprobante.',
                    icon: 'info',
                    confirmButtonText: 'Entendido'
                });
            }
            
            setTimeout(() => {
                if (modalRegistrarPago) modalRegistrarPago.show();
            }, 500);
        }
    </script>
    
    <form id="formLogout" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    
    @stack('scripts')
</body>
</html>