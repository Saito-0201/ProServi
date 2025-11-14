<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Restablecer Contraseña' }} - ProServi</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2D3748;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            min-height: 100vh;
        }
        
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
        }
        
        .email-container {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }
        
        /* Estilos específicos para restablecimiento */
        .email-header {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 50%, #f87171 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .logo {
            max-width: 200px;
            height: auto;
            filter: brightness(0) invert(1);
        }
        
        .header-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 20px;
            border-radius: 50px;
            color: white;
            font-size: 14px;
            font-weight: 500;
            margin-top: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .email-body {
            padding: 50px 40px;
        }
        
        .greeting {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #dc2626, #ef4444);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .message-card {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 40px;
            text-align: center;
        }
        
        .message-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        .message-title {
            font-size: 20px;
            font-weight: 600;
            color: #dc2626;
            margin-bottom: 15px;
        }
        
        .message-text {
            color: #7f1d1d;
            font-size: 16px;
            line-height: 1.7;
        }
        
        .button-container {
            text-align: center;
            margin: 40px 0;
        }
        
        .verify-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
            text-decoration: none;
            padding: 18px 45px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 17px;
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.3);
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .verify-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(220, 38, 38, 0.4);
        }
        
        .alternative-section {
            background: #EDF2F7;
            border-radius: 16px;
            padding: 30px;
            margin-top: 40px;
            border-left: 4px solid #dc2626;
        }
        
        .alternative-title {
            font-size: 16px;
            font-weight: 600;
            color: #2D3748;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .alternative-title::before {
            content: '🔗';
            margin-right: 10px;
            font-size: 18px;
        }
        
        .alternative-text {
            color: #718096;
            font-size: 15px;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        
        .link-container {
            background: white;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 20px;
            margin-top: 15px;
        }
        
        .link-url {
            word-break: break-all;
            color: #dc2626;
            font-family: 'Monaco', 'Consolas', monospace;
            font-size: 14px;
            text-decoration: none;
            line-height: 1.5;
        }
        
        .link-url:hover {
            color: #b91c1c;
            text-decoration: underline;
        }
        
        .email-footer {
            background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
            padding: 40px 30px;
            text-align: center;
            color: #cbd5e0;
        }
        
        .footer-logo {
            font-size: 24px;
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
        }
        
        .footer-tagline {
            font-size: 16px;
            margin-bottom: 25px;
            color: #a0aec0;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .footer-link {
            color: #cbd5e0;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }
        
        .footer-link:hover {
            color: white;
        }
        
        .copyright {
            margin-top: 25px;
            font-size: 12px;
            color: #718096;
        }
        
        .security-note {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #7f1d1d;
            border-radius: 12px;
            padding: 20px;
            margin-top: 30px;
            text-align: center;
        }
        
        @media (max-width: 640px) {
            body {
                padding: 20px 15px;
            }
            
            .email-body {
                padding: 30px 20px;
            }
            
            .email-header {
                padding: 30px 20px;
            }
            
            .greeting {
                font-size: 28px;
            }
            
            .verify-button {
                padding: 16px 35px;
                font-size: 16px;
            }
            
            .footer-links {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <img src="{{ asset('uploads/images/logopro.png') }}" alt="ProServi Logo" class="logo">
            </div>
            
            <!-- Cuerpo -->
            <div class="email-body">
                <h1 class="greeting">{{ $greeting ?? '¡Hola!' }}</h1>
                
                <div class="message-card">
                    <div class="message-icon">🔒</div>
                    <h2 class="message-title">Restablece tu contraseña</h2>
                    
                    @foreach ($introLines as $line)
                    <p class="message-text">{{ $line }}</p>
                    @endforeach
                </div>
                
                <!-- Botón de acción -->
                @isset($actionText)
                <div class="button-container">
                    <a href="{{ $actionUrl }}" class="verify-button">
                        {{ $actionText }}
                    </a>
                </div>
                @endisset
                
                <!-- Enlace alternativo -->
                @isset($actionText)
                <div class="alternative-section">
                    <h3 class="alternative-title">¿Problemas con el botón?</h3>
                    <p class="alternative-text">
                        Copia y pega la siguiente URL en tu navegador:
                    </p>
                    <div class="link-container">
                        <a href="{{ $actionUrl }}" class="link-url">
                            {{ $displayableActionUrl ?? $actionUrl }}
                        </a>
                    </div>
                </div>
                @endisset
                
                <!-- Mensajes finales -->
                @foreach ($outroLines as $line)
                <div class="security-note">
                    <p style="margin: 0; font-size: 14px;">
                        <strong>Importante:</strong> {{ $line }}
                    </p>
                </div>
                @endforeach
            </div>
            
            <!-- Footer -->
            <div class="email-footer">
                <div class="footer-logo">ProServi</div>
                <p class="footer-tagline">
                    Tu partner confiable en servicios profesionales
                </p>
                <div class="footer-links">
                    <a href="https://proservi.bo" class="footer-link">Nuestro Sitio</a>
                    <a href="https://proservi.bo/contacto" class="footer-link">Contacto</a>
                    <a href="https://proservi.bo/seguridad" class="footer-link">Seguridad</a>
                </div>
                <div class="copyright">
                    © {{ date('Y') }} ProServi. Todos los derechos reservados.
                </div>
            </div>
        </div>
    </div>
</body>
</html>