{{-- resources/views/publico/servicios/show.blade.php --}}
@extends('layouts.landing')

@section('title', $servicio->titulo . ' — PROSERVI')
@section('content')

<div class="section">
    <div class="container">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('public.servicios.index') }}">Servicios</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($servicio->titulo, 30) }}</li>
            </ol>
        </nav>

        <div class="row">
            {{-- Columna principal --}}
            <div class="col-lg-8">
                {{-- Galería de imágenes --}}
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="service-gallery">
                            <img src="{{ $servicio->imagen_url }}" 
                                 class="service-main-image" alt="{{ $servicio->titulo }}" id="main-image">
                        </div>
                    </div>
                </div>

                {{-- Información del servicio --}}
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h2 class="h4 mb-0 fw-bold">{{ $servicio->titulo }}</h2>
                            <div class="d-flex align-items-center">
                                <button class="btn btn-outline-secondary btn-sm require-login" 
                                        data-action="compartir" 
                                        data-message="Para compartir este servicio necesitas tener una cuenta">
                                    <i class="fas fa-share"></i>
                                    <span class="d-none d-md-inline">Compartir</span>
                                </button>
                            </div>
                        </div>

                        {{-- Rating y ubicación --}}
                        <div class="d-flex align-items-center mb-3">
                            <div class="service-rating me-3">
                                <div class="stars">
                                    @php
                                        $rating = $servicio->calificacion_promedio ?? 0;
                                        $totalCalificaciones = $servicio->total_calificaciones ?? 0;
                                    @endphp
                                    
                                    @if($totalCalificaciones > 0)
                                        {{-- Mostrar estrellas y rating cuando hay calificaciones --}}
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $rating ? '' : '-empty' }} text-warning"></i>
                                        @endfor
                                        <span class="ms-2 fw-bold">{{ number_format($rating, 1) }}/5</span>
                                    @else
                                        {{-- Mostrar "Sin calificaciones" cuando no hay calificaciones --}}
                                        <span class="text-muted fw-bold">Sin calificaciones</span>
                                    @endif
                                </div>
                                
                                @if($totalCalificaciones > 0)
                                    <small class="text-muted">
                                        ({{ $totalCalificaciones }} calificación{{ $totalCalificaciones != 1 ? 'es' : '' }})
                                    </small>
                                @else
                                    <small class="text-muted">
                                        (Sé el primero en calificar)
                                    </small>
                                @endif
                            </div>
                            
                            <span class="service-views text-muted">
                                <i class="fas fa-eye me-1"></i>{{ $servicio->visitas }} vistas
                            </span>
                        </div>

                        {{-- Ubicación --}}
                        <div class="mb-4">
                            <p class="text-muted mb-1">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $servicio->ciudad }}, {{ $servicio->provincia }}
                            </p>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Publicado el {{ $servicio->fecha_publicacion->format('d/m/Y') }}
                            </small>
                        </div>

                        {{-- Precio --}}
                        <div class="mb-4">
                            <h5 class="text-primary mb-2">Precio del servicio</h5>
                            <span class="display-6 fw-bold text-primary">{{ $servicio->precio_formateado }}</span>
                            @if($servicio->tipo_precio !== 'cotizacion' && $servicio->precio)
                            @endif
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-4">
                            <h5 class="mb-3">Descripción del servicio</h5>
                            <p class="service-description">{{ $servicio->descripcion }}</p>
                        </div>

                        {{-- Categorías --}}
                        <div class="mb-4">
                            <h5 class="mb-3">Categorías</h5>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-primary">{{ $servicio->categoria->nombre_cat }}</span>
                                <span class="badge bg-secondary">{{ $servicio->subcategoria->nombre }}</span>
                            </div>
                        </div>

                        {{-- Botones de acción --}}
                        <div class="d-grid gap-2 d-md-flex mb-4">
                            <button class="btn btn-success btn-lg flex-fill require-login" 
                                    data-action="contactar" 
                                    data-message="Para contactar al prestador necesitas tener una cuenta">
                                <i class="fab fa-whatsapp me-2"></i> Contactar por WhatsApp
                            </button>
                            <button class="btn btn-outline-primary btn-lg flex-fill require-login" 
                                    data-action="calificar" 
                                    data-message="Para calificar este servicio necesitas tener una cuenta">
                                <i class="fas fa-star me-2"></i> Calificar servicio
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Calificaciones y comentarios --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-4">Calificaciones y comentarios</h5>
                        
                        {{-- Mostrar promedio general --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <h3 class="text-primary mb-1">{{ number_format($servicio->calificacion_promedio, 1) }}</h3>
                                    <div class="stars mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $servicio->calificacion_promedio ? '' : '-empty' }} text-warning"></i>
                                        @endfor
                                    </div>
                                    <small class="text-muted">Basado en {{ $servicio->total_calificaciones }} calificaciones</small>
                                </div>
                            </div>
                        </div>
                        
                        @if($calificaciones->count() > 0)
                            @foreach($calificaciones as $calificacion)
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1">{{ $calificacion->cliente->name ?? 'Usuario' }}</h6>
                                            <div class="stars small">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star{{ $i <= $calificacion->puntuacion ? '' : '-empty' }} text-warning"></i>
                                                @endfor
                                                <span class="ms-2 text-muted">{{ $calificacion->puntuacion }}/5</span>
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $calificacion->fecha->format('d/m/Y') }}</small>
                                    </div>
                                    @if($calificacion->comentario)
                                        <p class="mb-0 text-muted">{{ $calificacion->comentario }}</p>
                                    @else
                                        <p class="mb-0 text-muted"><em>Sin comentario</em></p>
                                    @endif
                                </div>
                            @endforeach
                            
                            {{-- Paginación --}}
                            @if($calificaciones->hasPages())
                                <div class="mt-4">
                                    {{ $calificaciones->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-comment text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2 mb-0">Este servicio aún no tiene calificaciones</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Información del prestador --}}
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Sobre el prestador</h5>
                        
                        <div class="d-flex align-items-center mb-3">
                            @php
                                $fotoPrestador = $servicio->prestador->prestadorInfo && $servicio->prestador->prestadorInfo->foto_perfil 
                                    ? Storage::url($servicio->prestador->prestadorInfo->foto_perfil) 
                                    : asset('uploads/images/default-user.png');
                            @endphp
                            <img src="{{ $fotoPrestador }}" 
                                class="prestador-avatar me-3" 
                                width="60" 
                                height="60" 
                                alt="{{ $servicio->prestador->name }}"
                                onerror="this.src='{{ asset('images/default-user.jpg') }}'">
                            <div>
                                <h6 class="mb-1">{{ $servicio->prestador->name }} {{ $servicio->prestador->lastname }}</h6>
                                @if($servicio->prestador->prestadorInfo && $servicio->prestador->prestadorInfo->verificado)
                                    <span class="badge bg-success small">
                                        <i class="fas fa-check-circle me-1"></i>Verificado
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($servicio->prestador->prestadorInfo && $servicio->prestador->prestadorInfo->descripcion)
                            <p class="text-muted small mb-3">{{ $servicio->prestador->prestadorInfo->descripcion }}</p>
                        @endif

                        <div class="prestador-stats mb-3">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="h5 mb-1">{{ $servicio->prestador->servicios()->where('estado', 'activo')->count() }}</div>
                                    <small class="text-muted">Servicios</small>
                                </div>
                                <div class="col-4">
                                    <div class="h5 mb-1">{{ $servicio->prestador->prestadorInfo?->experiencia ?? 'N/A' }}</div>
                                    <small class="text-muted">Experiencia</small>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-outline-primary w-100 require-login" 
                                data-action="ver-perfil" 
                                data-message="Para ver el perfil completo del prestador necesitas tener una cuenta">
                            <i class="fas fa-user me-1"></i> Ver perfil completo
                        </button>
                    </div>
                </div>

                {{-- Servicios relacionados --}}
                @if($serviciosRelacionados->count() > 0)
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Servicios relacionados</h5>
                            
                            @foreach($serviciosRelacionados as $servicioRel)
                                <div class="card border-0 mb-3">
                                    <div class="row g-0">
                                        <div class="col-4">
                                            <img src="{{ $servicioRel->imagen_url }}" 
                                                 class="img-fluid rounded-start h-100 object-fit-cover" alt="{{ $servicioRel->titulo }}">
                                        </div>
                                        <div class="col-8">
                                            <div class="card-body p-2">
                                                <h6 class="card-title mb-1">{{ Str::limit($servicioRel->titulo, 30) }}</h6>
                                                <div class="stars small mb-1">
                                                    @php
                                                        $ratingRel = $servicioRel->calificacion_promedio ?? 0;
                                                    @endphp
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star{{ $i <= $ratingRel ? '' : '-empty' }} text-warning"></i>
                                                    @endfor
                                                </div>
                                                <p class="card-text mb-0 text-primary fw-bold">{{ $servicioRel->precio_formateado }}</p>
                                                <a href="{{ route('public.servicios.show', $servicioRel->id) }}" class="stretched-link"></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Información de seguridad --}}
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h6 class="card-title mb-3">
                            <i class="fas fa-shield-check text-success me-2"></i>Recomendaciones de seguridad
                        </h6>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Verifica que el prestador esté verificado</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Acuerda el precio antes del servicio</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Paga después de recibir el servicio</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Reporta cualquier irregularidad</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de Login Mejorado --}}
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Acción Requerida</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-4">
                    <i class="fas fa-lock text-primary" style="font-size: 3rem;"></i>
                </div>
                <h6 class="mb-3">Necesitas una cuenta para continuar</h6>
                <p class="text-muted mb-4" id="modal-message">
                    <!-- El mensaje específico se insertará aquí via JavaScript -->
                </p>
                <div class="d-grid gap-2">
                    <button onclick="redirectToRegister()" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i> Crear Cuenta
                    </button>
                    <button onclick="redirectToLogin()" class="btn btn-outline-primary">
                        <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Tus estilos CSS existentes aquí... */
.prestador-avatar {
    width: 60px;
    height: 60px;
    object-fit: cover;
    object-position: center;
    border-radius: 50%;
    flex-shrink: 0;
}

.service-gallery {
    position: relative;
    height: 400px;
    overflow: hidden;
    border-radius: 12px;
}

.service-main-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.service-description {
    line-height: 1.6;
    white-space: pre-line;
}

.prestador-stats {
    border-top: 1px solid #e9ecef;
    border-bottom: 1px solid #e9ecef;
    padding: 1rem 0;
}

.require-login {
    cursor: pointer;
    position: relative;
    transition: all 0.3s ease;
}

.require-login:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

/* Responsive styles... */
@media (max-width: 768px) {
    .service-gallery {
        height: 300px;
    }
    
    .card.border-0 .col-4 img {
        height: 180px !important;
    }
}

@media (max-width: 576px) {
    .service-gallery {
        height: 250px;
    }
    
    .card.border-0 .col-4 img {
        height: 160px !important;
    }
}
</style>

<script>
// ===== SISTEMA DE REDIRECCIÓN MEJORADO PARA LOGIN Y REGISTRO =====

// Función para guardar información de redirección
function saveRedirectInfo() {
    const redirectData = {
        serviceId: {{ $servicio->id }},
        currentUrl: window.location.href,
        timestamp: new Date().getTime(),
        intendedRoute: 'cliente.servicios.show', // Ruta a la que queremos redirigir
        action: 'view_service' // Acción específica
    };
    
    localStorage.setItem('proservi_redirect', JSON.stringify(redirectData));
    console.log('Información de redirección guardada para login/registro:', redirectData);
}

// Función para procesar la redirección después del login/registro
function processRedirect() {
    const redirectData = localStorage.getItem('proservi_redirect');
    
    if (redirectData) {
        try {
            const data = JSON.parse(redirectData);
            const currentTime = new Date().getTime();
            const timeDiff = currentTime - data.timestamp;
            
            // Solo procesar si la información tiene menos de 10 minutos
            if (timeDiff < 10 * 60 * 1000) {
                console.log('Procesando redirección después de login/registro:', data);
                
                // Verificar si el usuario está autenticado
                fetch('/api/check-auth')
                    .then(response => response.json())
                    .then(authData => {
                        if (authData.authenticated) {
                            // Usuario autenticado, redirigir según su rol
                            handleAuthenticatedRedirect(data, authData);
                        } else {
                            // Usuario no autenticado, mantener la información por si se registra después
                            console.log('Usuario no autenticado, manteniendo datos de redirección');
                        }
                    })
                    .catch(error => {
                        console.error('Error verificando autenticación:', error);
                        // Fallback: intentar redirigir directamente
                        attemptDirectRedirect(data);
                    });
            } else {
                // Información muy vieja, limpiar
                console.log('Información de redirección expirada');
                localStorage.removeItem('proservi_redirect');
            }
        } catch (error) {
            console.error('Error procesando redirección:', error);
            localStorage.removeItem('proservi_redirect');
        }
    }
}

// Manejar redirección para usuario autenticado
function handleAuthenticatedRedirect(data, authData) {
    if (data.intendedRoute === 'cliente.servicios.show') {
        // Si el usuario es cliente, redirigir a la vista de cliente
        if (authData.role === 'Cliente') {
            const clienteUrl = "{{ route('cliente.servicios.show', ':id') }}".replace(':id', data.serviceId);
            console.log('Redirigiendo cliente a:', clienteUrl);
            
            // Limpiar el localStorage y redirigir
            localStorage.removeItem('proservi_redirect');
            window.location.href = clienteUrl;
        } 
        // Si el usuario es prestador, redirigir a su dashboard
        else if (authData.role === 'Prestador') {
            console.log('Usuario es prestador, redirigiendo a dashboard');
            localStorage.removeItem('proservi_redirect');
            window.location.href = "{{ route('prestador.index') }}";
        }
        // Si es administrador o otro rol
        else {
            console.log('Rol no manejado, redirigiendo a home');
            localStorage.removeItem('proservi_redirect');
            window.location.href = "{{ url('/') }}";
        }
    }
}

// Intento de redirección directa (fallback)
function attemptDirectRedirect(data) {
    if (data.intendedRoute === 'cliente.servicios.show') {
        const clienteUrl = "{{ route('cliente.servicios.show', ':id') }}".replace(':id', data.serviceId);
        console.log('Intentando redirección directa a:', clienteUrl);
        
        // Verificar si la ruta existe (no dará 404)
        fetch(clienteUrl, { method: 'HEAD' })
            .then(response => {
                if (response.ok) {
                    localStorage.removeItem('proservi_redirect');
                    window.location.href = clienteUrl;
                } else {
                    console.log('Ruta no accesible, redirigiendo a home');
                    localStorage.removeItem('proservi_redirect');
                    window.location.href = "{{ url('/') }}";
                }
            })
            .catch(() => {
                console.log('Error en redirección, yendo a home');
                localStorage.removeItem('proservi_redirect');
                window.location.href = "{{ url('/') }}";
            });
    }
}

// Función para redirigir a registro
function redirectToRegister() {
    saveRedirectInfo();
    console.log('Redirigiendo a registro...');
    window.location.href = '{{ route("register") }}';
}

// Función para redirigir a login
function redirectToLogin() {
    saveRedirectInfo();
    console.log('Redirigiendo a login...');
    window.location.href = '{{ route("login") }}';
}

// Verificar autenticación del usuario
function checkUserAuth() {
    return fetch('/api/check-auth')
        .then(response => response.json())
        .catch(() => ({ authenticated: false, role: null }));
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Página cargada, verificando redirección...');
    
    // Verificar si debemos procesar redirección
    setTimeout(() => {
        processRedirect();
    }, 500);
    
    // Modal de login mejorado
    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
    
    // Configurar botones que requieren login
    const requireLoginButtons = document.querySelectorAll('.require-login');
    
    requireLoginButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const action = this.dataset.action || 'usar esta función';
            const message = this.dataset.message || `Para ${action} necesitas tener una cuenta`;
            
            // Primero verificar si el usuario ya está autenticado
            checkUserAuth().then(authData => {
                if (authData.authenticated) {
                    // Usuario ya autenticado, redirigir directamente
                    if (authData.role === 'Cliente') {
                        const clienteUrl = "{{ route('cliente.servicios.show', $servicio->id) }}";
                        window.location.href = clienteUrl;
                    } else {
                        // Usuario autenticado pero no es cliente
                        alert('Ya estás autenticado, pero necesitas una cuenta de cliente para esta acción.');
                    }
                } else {
                    // Usuario no autenticado, mostrar modal
                    document.getElementById('loginModalLabel').textContent = action.charAt(0).toUpperCase() + action.slice(1);
                    document.getElementById('modal-message').textContent = message;
                    loginModal.show();
                }
            });
        });
    });

    // WhatsApp button específico
    const whatsappBtn = document.querySelector('[data-action="contactar"]');
    if (whatsappBtn) {
        whatsappBtn.addEventListener('click', function() {
            // La lógica de WhatsApp irá aquí cuando el usuario esté autenticado
            console.log('Botón de WhatsApp clickeado');
        });
    }
});

// Verificar redirección periódicamente después del login/registro
window.addEventListener('load', function() {
    console.log('Página completamente cargada, iniciando verificación de redirección...');
    
    // Verificar inmediatamente
    processRedirect();
    
    // Verificar cada 2 segundos por si acaso (máximo 30 segundos)
    let checkCount = 0;
    const maxChecks = 15; // 30 segundos máximo
    
    const redirectInterval = setInterval(() => {
        checkCount++;
        processRedirect();
        
        // Si no hay datos de redirección o hemos hecho muchas verificaciones, limpiar
        if (!localStorage.getItem('proservi_redirect') || checkCount >= maxChecks) {
            clearInterval(redirectInterval);
            console.log('Deteniendo verificación de redirección');
        }
    }, 2000);
});
</script>
@endsection