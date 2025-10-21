<!DOCTYPE html>
<html>
<head>
    <title>Verificación Exitosa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        function detectOS() {
            const userAgent = navigator.userAgent || navigator.vendor || window.opera;
            return {
                isMac: /macintosh|mac os x/i.test(userAgent),
                isIOS: /iphone|ipad|ipod/i.test(userAgent),
                isSafari: /safari/i.test(userAgent) && !/chrome|crios/i.test(userAgent)
            };
        }

        document.addEventListener('DOMContentLoaded', function() {
            const { isMac, isIOS, isSafari } = detectOS();
            const statusEl = document.getElementById('status');
            const fallbackEl = document.getElementById('fallback');
            
            // Mensaje adaptado
            statusEl.textContent = "{{ $wasAlreadyVerified ? '✓ Correo ya verificado' : '✓ ¡Verificación exitosa!' }}";
            
            // Comportamiento específico por SO/navegador
            if (isIOS || isSafari) {
                // iOS/Safari: Muestra instrucciones inmediatas
                fallbackEl.style.display = 'block';
                fallbackEl.innerHTML += `
                    <p><strong>En iPhone/iPad:</strong> Toca "Compartir" → "Cerrar pestaña"</p>
                    <p><strong>En Mac:</strong> Presiona <kbd>Cmd</kbd> + <kbd>W</kbd></p>
                `;
            } else {
                // Otros navegadores: Intenta cerrar
                setTimeout(() => {
                    try {
                        window.open('', '_self').close();
                    } catch (e) {
                        fallbackEl.style.display = 'block';
                        if (isMac) {
                            fallbackEl.innerHTML += `<p><strong>En Mac:</strong> Presiona <kbd>Cmd</kbd> + <kbd>W</kbd></p>`;
                        }
                    }
                }, 2000);
            }
        });
    </script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 
                        Helvetica, Arial, sans-serif;
            text-align: center;
            padding: 2rem;
            line-height: 1.6;
            color: #333;
        }
        #status {
            color: #28a745;
            font-size: 1.5rem;
            margin: 1.5rem 0;
            font-weight: bold;
        }
        #fallback {
            display: none;
            margin-top: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #e1e4e8;
        }
        .btn-close {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            margin: 0.5rem 0;
        }
        kbd {
            background-color: #f3f3f3;
            border: 1px solid #d1d1d1;
            border-radius: 3px;
            padding: 2px 5px;
            font-size: 0.9em;
        }
        @media (max-width: 500px) {
            body { padding: 1rem; }
            #status { font-size: 1.3rem; }
        }
    </style>
</head>
<body>
    <div id="status"></div>
    <p id="closing-message">Cerrando esta ventana...</p>
    
    <div id="fallback">
        <p>⚠️ La ventana no se pudo cerrar automáticamente.</p>
        <button class="btn-close" onclick="window.close()">Cerrar Ahora</button>
        <div id="os-specific-help"></div>
        <p>O <a href="{{ config('app.url') }}" style="color: #007bff;">volver a la aplicación</a></p>
    </div>
</body>
</html>