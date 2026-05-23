<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización sobre tu pago - SAINS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #ef444420 0%, #dc262620 100%);
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
            transition: transform 0.3s ease;
        }
        
        /* Header con efecto de advertencia */
        .email-header {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #b91c1c 100%);
            padding: 60px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .email-header::before {
            content: '⚠️';
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 40px;
            opacity: 0.3;
            animation: shake 3s ease-in-out infinite;
        }
        
        .email-header::after {
            content: '❗';
            position: absolute;
            bottom: 20px;
            right: 20px;
            font-size: 40px;
            opacity: 0.3;
            animation: shake 3s ease-in-out infinite reverse;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
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
            color: #dc2626;
        }
        
        .intro-text {
            font-size: 17px;
            color: #4a5568;
            margin-bottom: 35px;
            line-height: 1.6;
            text-align: center;
        }
        
        /* Warning Box */
        .warning-box {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-radius: 28px;
            padding: 30px;
            margin: 30px 0;
            border: 2px solid #fecaca;
            box-shadow: 0 8px 20px rgba(239,68,68,0.1);
        }
        
        .warning-title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-size: 20px;
            font-weight: 800;
            color: #991b1b;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .warning-title span:first-child {
            font-size: 28px;
        }
        
        .warning-text {
            font-size: 16px;
            color: #7f1d1d;
            text-align: center;
            line-height: 1.5;
        }
        
        /* Reason Box */
        .reason-box {
            background: white;
            border-radius: 24px;
            padding: 24px;
            margin: 25px 0;
            border: 2px solid #fecaca;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .reason-title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 800;
            color: #dc2626;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .reason-text {
            font-size: 16px;
            color: #991b1b;
            font-style: italic;
            text-align: center;
            padding: 15px;
            background: #fef2f2;
            border-radius: 16px;
            font-weight: 500;
        }
        
        /* Steps Box */
        .steps-box {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border-radius: 28px;
            padding: 28px;
            margin: 25px 0;
            border: 2px solid #fde68a;
            box-shadow: 0 8px 20px rgba(245,158,11,0.1);
        }
        
        .steps-title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 25px;
        }
        
        .steps-title span {
            font-size: 28px;
        }
        
        .steps-title strong {
            font-size: 18px;
            color: #92400e;
            font-weight: 800;
        }
        
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .step-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: white;
            border-radius: 16px;
            transition: all 0.2s;
        }
        
        .step-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .step-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
        }
        
        .step-text {
            font-size: 14px;
            color: #1a2a3a;
            font-weight: 500;
            line-height: 1.3;
        }
        
        /* CTA Button */
        .cta-section {
            text-align: center;
            margin: 40px 0 30px;
        }
        
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            text-decoration: none;
            padding: 18px 48px;
            border-radius: 60px;
            font-weight: 800;
            font-size: 18px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(245,158,11,0.4);
            letter-spacing: 1px;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(245,158,11,0.5);
        }
        
        /* Support Box */
        .support-box {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 24px;
            padding: 24px;
            margin: 25px 0;
            text-align: center;
            border: 1px solid #e2e8f0;
        }
        
        .support-title {
            font-size: 18px;
            font-weight: 800;
            color: #1a2a3a;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .support-contact {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        
        .contact-item {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            background: white;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
        }
        
        .contact-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-color: #f59e0b;
        }
        
        .contact-email {
            color: #667eea;
            font-weight: 600;
        }
        
        .contact-phone {
            color: #10b981;
            font-weight: 600;
        }
        
        .contact-item i {
            font-size: 18px;
        }
        
        /* Mensaje de ánimo */
        .encouragement {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #fef2f2, #fff5f5);
            border-radius: 24px;
        }
        
        .encouragement p {
            font-size: 15px;
            color: #991b1b;
            font-weight: 500;
        }
        
        /* Footer */
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
            
            .steps-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .support-contact {
                flex-direction: column;
                gap: 12px;
                align-items: center;
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
            <!-- Header con efecto de advertencia -->
            <div class="email-header">
                <div class="badge">⚠️ PAGO RECHAZADO</div>
                <div class="logo">
                    <span>SAINS</span>
                </div>
                <h1>Tu pago requiere atención</h1>
                <p>No pudimos validar tu comprobante</p>
            </div>
            
            <!-- Body -->
            <div class="email-body">
                <div class="greeting">
                     Hola, <strong>{{ $estudiante->nombre }} {{ $estudiante->paterno }}</strong>
                </div>
                
                <div class="intro-text">
                    Lamentamos informarte que <strong>tu pago no pudo ser aprobado</strong> debido a que el 
                    comprobante presentado no cumple con los requisitos de validación.
                </div>
                
                <!-- Warning Box -->
                <div class="warning-box">
                    <div class="warning-title">
                        <span>❌</span>
                        <span>ESTADO: RECHAZADO</span>
                        <span>❌</span>
                    </div>
                    <div class="warning-text">
                        <strong>Tu pago ha sido rechazado</strong> por nuestro equipo de validación.
                        Por favor, revisa el motivo y realiza una nueva solicitud.
                    </div>
                </div>
                
                <!-- Reason Box -->
                <div class="reason-box">
                    <div class="reason-title">
                        <span>📋</span>
                        <span>MOTIVO DEL RECHAZO</span>
                        <span>📋</span>
                    </div>
                    <div class="reason-text">
                        "{{ $motivo }}"
                    </div>
                </div>
                
                <!-- Steps Box - Cómo resolverlo -->
                <div class="steps-box">
                    <div class="steps-title">
                        <span>🔧</span>
                        <strong>¿CÓMO RESOLVERLO?</strong>
                        <span>🔧</span>
                    </div>
                    <div class="steps-grid">
                        <div class="step-item">
                            <div class="step-icon">✅</div>
                            <span class="step-text">Verifica la legibilidad del comprobante</span>
                        </div>
                        <div class="step-item">
                            <div class="step-icon">💰</div>
                            <span class="step-text">Confirma que el monto sea $800 MXN</span>
                        </div>
                        <div class="step-item">
                            <div class="step-icon">🔖</div>
                            <span class="step-text">Revisa que la referencia sea correcta</span>
                        </div>
                        <div class="step-item">
                            <div class="step-icon">📎</div>
                            <span class="step-text">Usa formato JPG, PNG o PDF (máx. 5MB)</span>
                        </div>
                        <div class="step-item">
                            <div class="step-icon">🔄</div>
                            <span class="step-text">Realiza un nuevo pago con los datos correctos</span>
                        </div>
                        <div class="step-item">
                            <div class="step-icon">📤</div>
                            <span class="step-text">Sube el comprobante corregido</span>
                        </div>
                    </div>
                </div>
                
                <!-- CTA Button -->
                <div class="cta-section">
                    <a href="{{ route('estudiante.checkout') }}" class="btn-primary">
                        🔄 REALIZAR NUEVO PAGO
                        <span>→</span>
                    </a>
                </div>
                
                <!-- Support Box - Con los datos correctos -->
                <div class="support-box">
                    <div class="support-title">
                        <span>📞</span>
                        <span>¿NECESITAS AYUDA?</span>
                        <span>📞</span>
                    </div>
                    <p style="color: #64748b; margin-bottom: 15px;">
                        Si consideras que esto es un error o necesitas asistencia, 
                        nuestro equipo está disponible para ayudarte:
                    </p>
                    <div class="support-contact">
                        <a href="mailto:sains.ingreso@gmail.com" class="contact-item contact-email">
                            <span>✉️</span>
                            sains.ingreso@gmail.com
                        </a>
                        <a href="tel:7771886018" class="contact-item contact-phone">
                            <span>📱</span>
                            777 188 6018
                        </a>
                    </div>
                </div>
                
                <!-- Mensaje de ánimo -->
                <div class="encouragement">
                    <p>
                         No te desanimes, ¡estamos seguros de que en el próximo intento 
                        podremos activar tu cuenta! Recuerda que puedes intentarlo las veces que necesites.
                    </p>
                </div>
            </div>
            
            <!-- Footer -->
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