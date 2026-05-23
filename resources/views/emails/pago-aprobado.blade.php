<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Bienvenido a SAINS! Tu pago ha sido aprobado</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
            line-height: 1.6;
            color: #1a2a3a;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .email-wrapper {
            background: #ffffff;
            border-radius: 48px;
            overflow: hidden;
            box-shadow: 0 30px 60px -20px rgba(0, 0, 0, 0.3);
            transform: translateY(0);
            transition: transform 0.3s ease;
        }
        
        /* Header con efecto de celebración */
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            padding: 60px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .email-header::before {
            content: '🎉';
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 40px;
            opacity: 0.3;
            animation: float 3s ease-in-out infinite;
        }
        
        .email-header::after {
            content: '🎊';
            position: absolute;
            bottom: 20px;
            right: 20px;
            font-size: 40px;
            opacity: 0.3;
            animation: float 3s ease-in-out infinite reverse;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .confetti-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: rgba(255,255,255,0.5);
            animation: confetti 5s ease-in-out infinite;
        }
        
        @keyframes confetti {
            0% { transform: translateY(-100px) rotate(0deg); opacity: 1; }
            100% { transform: translateY(300px) rotate(360deg); opacity: 0; }
        }
        
        .logo {
            font-size: 56px;
            font-weight: 800;
            color: white;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            letter-spacing: 4px;
            text-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .logo span {
            background: rgba(255,255,255,0.25);
            padding: 8px 24px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }
        
        .badge {
            display: inline-block;
            background: rgba(255,255,255,0.25);
            backdrop-filter: blur(10px);
            padding: 8px 24px;
            border-radius: 60px;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 25px;
            position: relative;
            z-index: 1;
            letter-spacing: 1px;
        }
        
        .email-header h1 {
            font-size: 42px;
            font-weight: 800;
            color: white;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .email-header p {
            font-size: 18px;
            color: rgba(255,255,255,0.95);
            position: relative;
            z-index: 1;
            font-weight: 500;
        }
        
        /* Body */
        .email-body {
            padding: 50px 45px;
        }
        
        .greeting {
            font-size: 28px;
            font-weight: 800;
            color: #1a2a3a;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .greeting strong {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .intro-text {
            font-size: 17px;
            color: #4a5568;
            margin-bottom: 35px;
            line-height: 1.6;
            text-align: center;
        }
        
        /* Info Box - Diseño más moderno */
        .info-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: 28px;
            padding: 30px;
            margin: 30px 0;
            border: 2px solid #bbf7d0;
            box-shadow: 0 8px 20px rgba(16,185,129,0.1);
        }
        
        .info-box-title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-size: 20px;
            font-weight: 800;
            color: #166534;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .info-box-title i {
            font-size: 28px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .info-card {
            background: white;
            border-radius: 20px;
            padding: 18px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        
        .info-card:hover {
            transform: translateY(-3px);
        }
        
        .info-label {
            font-size: 12px;
            color: #15803d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            margin-bottom: 8px;
        }
        
        .info-value {
            font-size: 16px;
            font-weight: 800;
            color: #064e3b;
        }
        
        .info-value.reference {
            font-family: monospace;
            background: #f0fdf4;
            padding: 6px 12px;
            border-radius: 12px;
            letter-spacing: 1px;
            font-size: 14px;
        }
        
        /* Features - Diseño más atractivo */
        .features-title {
            font-size: 26px;
            font-weight: 800;
            color: #1a2a3a;
            margin: 45px 0 25px;
            text-align: center;
            position: relative;
        }
        
        .features-title:before,
        .features-title:after {
            content: '';
            position: absolute;
            top: 50%;
            width: 80px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #667eea, transparent);
        }
        
        .features-title:before {
            left: 0;
        }
        
        .features-title:after {
            right: 0;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: 30px 0;
        }
        
        .feature-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 24px;
            padding: 24px 16px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            cursor: pointer;
        }
        
        .feature-card:hover {
            transform: translateY(-8px);
            border-color: #667eea;
            box-shadow: 0 15px 35px rgba(102,126,234,0.15);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            box-shadow: 0 8px 20px rgba(102,126,234,0.3);
        }
        
        .feature-icon i {
            font-size: 32px;
            color: white;
        }
        
        .feature-card h4 {
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 8px;
            color: #1a2a3a;
        }
        
        .feature-card p {
            font-size: 13px;
            color: #64748b;
            margin: 0;
        }
        
        /* Progress Section - Más moderno */
        .progress-section {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border-radius: 28px;
            padding: 28px;
            margin: 35px 0;
        }
        
        .progress-section h4 {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: #1a2a3a;
        }
        
        .tips-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 20px;
        }
        
        .tip-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: white;
            border-radius: 16px;
            transition: all 0.2s;
        }
        
        .tip-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .tip-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        
        .tip-text {
            font-size: 14px;
            color: #1a2a3a;
            font-weight: 500;
        }
        
        /* CTA Button - Más impactante */
        .cta-section {
            text-align: center;
            margin: 40px 0 20px;
        }
        
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            padding: 18px 48px;
            border-radius: 60px;
            font-weight: 800;
            font-size: 18px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            letter-spacing: 1px;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
        }
        
        /* Footer simplificado */
        .email-footer {
            background: #1a2a3a;
            padding: 35px;
            text-align: center;
        }
        
        .footer-logo {
            font-size: 24px;
            font-weight: 800;
            color: white;
            margin-bottom: 15px;
            letter-spacing: 2px;
        }
        
        .copyright {
            font-size: 12px;
            color: rgba(255,255,255,0.5);
            line-height: 1.8;
        }
        
        .copyright a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
        }
        
        .copyright a:hover {
            text-decoration: underline;
        }
        
        /* Responsive */
        @media (max-width: 640px) {
            .container {
                padding: 20px 15px;
            }
            
            .email-header {
                padding: 40px 25px;
            }
            
            .email-header h1 {
                font-size: 28px;
            }
            
            .email-body {
                padding: 35px 25px;
            }
            
            .greeting {
                font-size: 22px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .tips-list {
                grid-template-columns: 1fr;
            }
            
            .features-title:before,
            .features-title:after {
                display: none;
            }
            
            .btn-primary {
                padding: 14px 32px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-wrapper">
            <!-- Header con confeti animado -->
            <div class="email-header">
                <div class="confetti-bg">
                    <div class="confetti" style="left: 10%; animation-delay: 0s;"></div>
                    <div class="confetti" style="left: 30%; animation-delay: 0.5s; background: #ffd700;"></div>
                    <div class="confetti" style="left: 50%; animation-delay: 1s;"></div>
                    <div class="confetti" style="left: 70%; animation-delay: 0.3s; background: #ff6b6b;"></div>
                    <div class="confetti" style="left: 90%; animation-delay: 0.8s;"></div>
                </div>
                <div class="badge">✨ ¡FELICIDADES! ✨</div>
                <div class="logo">
                    <span>SAINS</span>
                </div>
                <h1>¡Tu pago ha sido aprobado!</h1>
                <p>Bienvenido a la experiencia SAINS Premium</p>
            </div>
            
            <!-- Body -->
            <div class="email-body">
                <div class="greeting">
                    🎊 ¡Hola, <strong>{{ $estudiante->nombre }} {{ $estudiante->paterno }}</strong>! 🎊
                </div>
                
                <div class="intro-text">
                    Nos llena de emoción informarte que <strong>tu pago ha sido aprobado exitosamente</strong> y 
                    tu cuenta ya cuenta con <strong>acceso premium completo</strong>. 
                    ¡Prepárate para una experiencia de aprendizaje única!
                </div>
                
                <!-- Info Box Mejorada -->
                <div class="info-box">
                    <div class="info-box-title">
                        <span>📋</span>
                        <span>DETALLES DE TU TRANSACCIÓN</span>
                        <span>📋</span>
                    </div>
                    <div class="info-grid">
                        <div class="info-card">
                            <span class="info-label">🔖 Referencia de pago</span>
                            <span class="info-value reference">{{ $pago->referencia_pago }}</span>
                        </div>
                        <div class="info-card">
                            <span class="info-label">💰 Monto pagado</span>
                            <span class="info-value">${{ number_format($pago->monto_pago, 2) }} MXN</span>
                        </div>
                        <div class="info-card">
                            <span class="info-label">📅 Fecha de aprobación</span>
                            <span class="info-value">{{ $fecha_aprobacion }}</span>
                        </div>
                        <div class="info-card">
                            <span class="info-label">👨‍💼 Validado por</span>
                            <span class="info-value">{{ $admin_nombre }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- What you get - Diseño mejorado -->
                <div class="features-title">
                    🎯 LO QUE INCLUYE TU PLAN PREMIUM
                </div>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i>📚</i>
                        </div>
                        <h4>Contenido Completo</h4>
                        <p>Todas las materias y módulos disponibles</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i>🎥</i>
                        </div>
                        <h4>Clases en Video Premium</h4>
                        <p>+50 lecciones con contenido exclusivo</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i>📝</i>
                        </div>
                        <h4>Simulador de Examen</h4>
                        <p>Practica de forma ilimitada</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i>📊</i>
                        </div>
                        <h4>Estadísticas en Tiempo Real</h4>
                        <p>Sigue tu progreso detalladamente</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i>🏆</i>
                        </div>
                        <h4>Certificado Oficial</h4>
                        <p>Al completar exitosamente el curso</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i>🚀</i>
                        </div>
                        <h4>Actualizaciones Gratuitas</h4>
                        <p>Siempre con el contenido más actualizado</p>
                    </div>
                </div>
                
                <!-- Tips Section Mejorada -->
                <div class="progress-section">
                    <h4>
                        <span>💡</span>
                        TIPS PARA APROVECHAR AL MÁXIMO TU CURSO
                        <span>💡</span>
                    </h4>
                    <div class="tips-list">
                        <div class="tip-item">
                            <div class="tip-icon">🎯</div>
                            <span class="tip-text">Comienza con el simulador para evaluar tu nivel</span>
                        </div>
                        <div class="tip-item">
                            <div class="tip-icon">⏰</div>
                            <span class="tip-text">Establece una rutina de 30 min diarios</span>
                        </div>
                        <div class="tip-item">
                            <div class="tip-icon">📹</div>
                            <span class="tip-text">Revisa las clases antes de cada examen</span>
                        </div>
                        <div class="tip-item">
                            <div class="tip-icon">📈</div>
                            <span class="tip-text">Usa las estadísticas para identificar áreas de mejora</span>
                        </div>
                    </div>
                </div>
                
                <!-- CTA Button - Más impactante -->
                <div class="cta-section">
                    <a href="{{ route('estudiante.dashboard') }}" class="btn-primary">
                        🚀 COMENZAR MI PREPARACIÓN AHORA
                        <span>→</span>
                    </a>
                </div>
            </div>
            
            <!-- Footer simplificado y elegante -->
            <div class="email-footer">
                <div class="footer-logo">SAINS</div>
                <div class="copyright">
                    <p>© {{ date('Y') }} SAINS Educación. Todos los derechos reservados.</p>
                    <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
                    <p>
                        <a href="#">Términos y condiciones</a> | 
                        <a href="#">Política de privacidad</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>