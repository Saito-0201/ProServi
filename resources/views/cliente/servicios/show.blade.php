{{-- resources/views/cliente/servicios/show.blade.php --}}
@extends('layouts.cliente')

@section('title', $servicio->titulo . ' — PROSERVI')
@section('page_title', 'Detalles')

@section('cliente-content')
<div class="row">
    {{-- Columna principal --}}
    <div class="col-lg-8">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('cliente.servicios.index') }}">Servicios</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cliente.servicios.buscar', ['categoria' => $servicio->categoria_id]) }}">{{ $servicio->categoria->nombre_cat }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($servicio->titulo, 30) }}</li>
            </ol>
        </nav>

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
                        @auth
                        <button class="btn btn-outline-danger btn-sm me-2 btn-favorite {{ $esFavorito ? 'active' : '' }}" 
                                id="btn-favorite" data-service-id="{{ $servicio->id }}">
                            <i class="bi bi-heart{{ $esFavorito ? '-fill' : '' }}"></i>
                            <span class="d-none d-md-inline">{{ $esFavorito ? 'En favoritos' : 'Guardar' }}</span>
                        </button>
                        @endauth
                        <button class="btn btn-outline-secondary btn-sm" id="btn-share">
                            <i class="bi bi-share"></i>
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
                                    <i class="bi bi-star{{ $i <= $rating ? '-fill' : '' }} text-warning"></i>
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
                        <i class="bi bi-eye me-1"></i>{{ $servicio->visitas }} vistas
                    </span>
                </div>

                {{-- Ubicación --}}
                <div class="mb-4">
                    <p class="text-muted mb-1">
                        <i class="bi bi-geo-alt me-1"></i>
                        {{ $servicio->ciudad }}, {{ $servicio->provincia }}
                    </p>
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i>
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
                    <a href="https://wa.me/{{ $servicio->prestadorInfo->telefono ?? '' }}?text=Hola,%20estoy%20interesado%20en%20tu%20servicio:%20{{ urlencode($servicio->titulo) }}"
                       class="btn btn-success btn-lg flex-fill" target="_blank" id="btn-contact">
                        <i class="bi bi-whatsapp me-2"></i> Contactar por WhatsApp
                    </a>
                    <button class="btn btn-outline-primary btn-lg flex-fill" data-bs-toggle="modal" data-bs-target="#calificarModal">
                        <i class="bi bi-star me-2"></i> Calificar servicio
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
                                    <i class="bi bi-star{{ $i <= $servicio->calificacion_promedio ? '-fill' : '' }} text-warning"></i>
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
                                            <i class="bi bi-star{{ $i <= $calificacion->puntuacion ? '-fill' : '' }} text-warning"></i>
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
                        <i class="bi bi-chat-text text-muted" style="font-size: 2rem;"></i>
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
                                <i class="bi bi-patch-check-fill me-1"></i>Verificado
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

                <div class="d-grid">
                    <a href="{{ route('cliente.prestadores.show', $servicio->prestador_id) }}" class="btn btn-outline-primary">
                        <i class="bi bi-person me-1"></i> Ver perfil completo
                    </a>
                </div>
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
                                                <i class="bi bi-star{{ $i <= $ratingRel ? '-fill' : '' }} text-warning"></i>
                                            @endfor
                                        </div>
                                        <p class="card-text mb-0 text-primary fw-bold">{{ $servicioRel->precio_formateado }}</p>
                                        <a href="{{ route('cliente.servicios.show', $servicioRel->id) }}" class="stretched-link"></a>
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
                    <i class="bi bi-shield-check text-success me-2"></i>Recomendaciones de seguridad
                </h6>
                <ul class="list-unstyled small text-muted mb-0">
                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Verifica que el prestador esté verificado</li>
                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Acuerda el precio antes del servicio</li>
                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Paga después de recibir el servicio</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Reporta cualquier irregularidad</li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Modal para calificar --}}
<div class="modal fade" id="calificarModal" tabindex="-1" aria-labelledby="calificarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="calificarModalLabel">
                    <span id="modal-title-text">Calificar servicio</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="calificarForm" action="{{ route('cliente.calificaciones.store') }}" method="POST">
                @csrf
                <input type="hidden" name="servicio_id" value="{{ $servicio->id }}">
                <input type="hidden" name="calificacion_id" id="calificacion_id" value="">
                
                <div class="modal-body">
                    <div class="mb-4 text-center">
                        <h6 class="mb-3" id="rating-question">¿Cómo calificarías este servicio?</h6>
                        <div class="rating-stars mb-3" id="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star rating-star" data-rating="{{ $i }}" style="font-size: 2rem; cursor: pointer;"></i>
                            @endfor
                        </div>
                        <input type="hidden" name="puntuacion" id="puntuacion" value="0">
                        <small class="text-muted" id="rating-text">Selecciona una calificación</small>
                    </div>

                    <div class="mb-3">
                        <label for="comentario" class="form-label">Comentario (opcional)</label>
                        <textarea class="form-control" id="comentario" name="comentario" rows="4" 
                                  placeholder="Comparte tu experiencia con este servicio..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger me-auto" id="btn-delete-rating" style="display: none;">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="submit-rating" disabled>
                        <span id="submit-button-text">Enviar calificación</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal de confirmación para eliminar calificación --}}
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>Confirmar eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="bi bi-trash text-danger" style="font-size: 3rem;"></i>
                </div>
                <h6 class="mb-3">¿Estás seguro de que quieres eliminar tu calificación?</h6>
                <p class="text-muted">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-danger" id="confirm-delete-rating">
                    <i class="bi bi-trash me-1"></i> Sí, eliminar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal para compartir --}}
<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">Compartir servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center gap-3 mb-4">
                    <a href="#" class="share-btn whatsapp" data-platform="whatsapp">
                        <i class="bi bi-whatsapp"></i>
                        <span>WhatsApp</span>
                    </a>
                    <a href="#" class="share-btn facebook" data-platform="facebook">
                        <i class="bi bi-facebook"></i>
                        <span>Facebook</span>
                    </a>
                    <a href="#" class="share-btn twitter" data-platform="twitter">
                        <i class="bi bi-twitter"></i>
                        <span>Twitter</span>
                    </a>
                </div>
                <div class="input-group">
                    <input type="text" class="form-control" id="share-url" value="{{ url()->current() }}" readonly>
                    <button class="btn btn-outline-secondary" type="button" id="copy-url">
                        <i class="bi bi-clipboard"></i>
                    </button>
                </div>
                <small class="text-muted mt-2 d-block" id="copy-feedback" style="display: none;">URL copiada al portapapeles</small>
            </div>
        </div>
    </div>
</div>

<style>

/* ===== CORRECCIÓN PARA IMÁGENES DE PERFIL ===== */
.rounded-circle {
    object-fit: cover;
    object-position: center;
    flex-shrink: 0; /* Evita que se reduzca en flexbox */
}

/* Asegurar que todas las imágenes de perfil tengan el mismo estilo */
.prestador-avatar {
    width: 60px;
    height: 60px;
    object-fit: cover;
    object-position: center;
    border-radius: 50%;
    flex-shrink: 0;
}

/* Para imágenes cuadradas (backup) */
.img-square {
    aspect-ratio: 1 / 1;
}

/* Corrección específica para la imagen del prestador */
.card-body .rounded-circle {
    object-fit: cover;
    object-position: center;
    min-width: 60px; /* Fuerza el tamaño */
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

.rating-star {
    color: #dee2e6;
    transition: color 0.2s ease;
    margin: 0 0.1rem;
}

.rating-star:hover,
.rating-star.active {
    color: #ffc107;
}

.share-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none;
    color: #6c757d;
    transition: transform 0.2s ease;
}

.share-btn:hover {
    transform: translateY(-2px);
    color: inherit;
}

.share-btn i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.share-btn.whatsapp:hover { color: #25D366; }
.share-btn.facebook:hover { color: #1877F2; }
.share-btn.twitter:hover { color: #1DA1F2; }

.btn-favorite.active {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.card {
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
}

#share-url {
    font-size: 0.9rem;
}

#copy-url {
    border-left: 1px solid #dee2e6;
}

/* ===== NUEVOS ESTILOS PARA IMÁGENES RECTANGULARES EN RESPONSIVE ===== */

/* Servicios relacionados - Imagen rectangular en móvil */
.card.border-0 .row.g-0 .col-4 {
    width: 100% !important;
    flex: 0 0 100% !important;
}

.card.border-0 .row.g-0 .col-8 {
    width: 100% !important;
    flex: 0 0 100% !important;
}

.card.border-0 .row.g-0 {
    flex-direction: column;
}

/* Imagen rectangular para servicios relacionados */
.card.border-0 .col-4 img {
    height: 200px !important; /* Altura rectangular */
    width: 100% !important;
    object-fit: cover;
    border-radius: 8px 8px 0 0 !important;
}

/* Ajustar el contenido debajo de la imagen */
.card.border-0 .col-8 .card-body {
    padding: 1rem !important;
}

/* ===== RESPONSIVE DESIGN MEJORADO ===== */
@media (max-width: 768px) {
    /* Servicios relacionados - Layout rectangular */
    .card.border-0 .row.g-0 {
        flex-direction: column;
    }
    
    .card.border-0 .col-4 {
        width: 100% !important;
    }
    
    .card.border-0 .col-4 img {
        height: 180px !important;
        width: 100%;
        object-fit: cover;
        border-radius: 8px 8px 0 0;
    }
    
    .card.border-0 .col-8 {
        width: 100% !important;
    }
    
    .card.border-0 .col-8 .card-body {
        padding: 1rem;
    }
    
    /* Ajustar la galería principal en móvil */
    .service-gallery {
        height: 300px;
    }
    
    /* Ajustar layout principal en móvil */
    .row > .col-lg-8,
    .row > .col-lg-4 {
        padding: 0 10px;
    }

    /* Estilos para el modal de calificación mejorado */
    #btn-delete-rating {
        transition: all 0.3s ease;
    }

    #btn-delete-rating:hover {
        transform: scale(1.05);
    }

    .rating-star {
        transition: all 0.2s ease;
    }

    .rating-star:hover {
        transform: scale(1.2);
    }
}

@media (max-width: 576px) {
    /* Servicios relacionados más compactos */
    .card.border-0 .col-4 img {
        height: 160px !important;
    }
    
    .service-gallery {
        height: 250px;
    }
    
    /* Ajustar botones en móvil */
    .d-grid.gap-2.d-md-flex .btn {
        font-size: 0.9rem;
        padding: 0.75rem 1rem;
    }
}

@media (max-width: 400px) {
    .card.border-0 .col-4 img {
        height: 140px !important;
    }
    
    .service-gallery {
        height: 200px;
    }
}

/* ===== ESTILOS PARA GARANTizar IMÁGENES RECTANGULARES ===== */
.object-fit-cover {
    object-fit: cover;
}

/* Forzar layout vertical en móvil para servicios relacionados */
@media (max-width: 768px) {
    .card.border-0.mb-3 {
        margin-bottom: 1rem !important;
    }
    
    .card.border-0 .row.g-0 {
        display: flex;
        flex-direction: column;
    }
    
    .card.border-0 .col-4,
    .card.border-0 .col-8 {
        max-width: 100%;
        flex: 0 0 100%;
    }
    
    /* Imagen rectangular */
    .card.border-0 .col-4 img {
        height: 160px;
        width: 100%;
        border-radius: 8px 8px 0 0;
    }
    
    /* Contenido */
    .card.border-0 .col-8 .card-body {
        padding: 1rem;
    }
}

/* Mejoras de hover para móvil */
@media (hover: hover) {
    .card:hover {
        transform: translateY(-2px);
    }
}

@media (hover: none) {
    .card:active {
        transform: scale(0.98);
    }
}

/* Estilos para el modal de confirmación de eliminación */
#confirmDeleteModal .modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

#confirmDeleteModal .modal-header {
    border-bottom: 1px solid #e9ecef;
    background: linear-gradient(135deg, #fff5f5, #fff);
}

#confirmDeleteModal .modal-body {
    padding: 2rem;
}

#confirm-delete-rating {
    transition: all 0.3s ease;
    min-width: 120px;
}

#confirm-delete-rating:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

#confirm-delete-rating:disabled {
    opacity: 0.7;
    transform: none;
    box-shadow: none;
}

/* Animación para el ícono de eliminación */
#confirmDeleteModal .bi-trash {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Responsive para modales */
@media (max-width: 576px) {
    #confirmDeleteModal .modal-dialog {
        margin: 1rem;
    }
    
    #confirmDeleteModal .modal-body {
        padding: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let userCurrentRating = null;
    let calificacionToDelete = null;
    const servicioId = {{ $servicio->id }};

    // Modales
    const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));

    // Sistema de calificación con estrellas
    const ratingStars = document.querySelectorAll('.rating-star');
    const puntuacionInput = document.getElementById('puntuacion');
    const ratingText = document.getElementById('rating-text');
    const submitButton = document.getElementById('submit-rating');
    const comentarioTextarea = document.getElementById('comentario');
    const deleteButton = document.getElementById('btn-delete-rating');
    const calificacionIdInput = document.getElementById('calificacion_id');
    const modalTitle = document.getElementById('modal-title-text');
    const ratingQuestion = document.getElementById('rating-question');
    const submitButtonText = document.getElementById('submit-button-text');
    const confirmDeleteButton = document.getElementById('confirm-delete-rating');

    const ratingTexts = {
        1: 'Malo',
        2: 'Regular',
        3: 'Bueno',
        4: 'Muy bueno',
        5: 'Excelente'
    };

    // Cargar calificación del usuario al abrir el modal
    const calificarModal = document.getElementById('calificarModal');
    if (calificarModal) {
        calificarModal.addEventListener('show.bs.modal', function() {
            loadUserRating();
        });
    }

    // Función para cargar la calificación del usuario
    function loadUserRating() {
        fetch(`{{ route('cliente.calificaciones.user-rating', '') }}/${servicioId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.rating) {
                userCurrentRating = data.rating;
                setRating(userCurrentRating.puntuacion);
                comentarioTextarea.value = userCurrentRating.comentario || '';
                calificacionIdInput.value = userCurrentRating.id;
                
                // Actualizar interfaz para modo edición
                modalTitle.textContent = 'Editar calificación';
                ratingQuestion.textContent = '¿Cómo calificarías este servicio?';
                submitButtonText.textContent = 'Actualizar calificación';
                deleteButton.style.display = 'block';
                submitButton.disabled = false;
            } else {
                // Resetear para nueva calificación
                resetRatingForm();
            }
        })
        .catch(error => {
            console.error('Error loading user rating:', error);
            resetRatingForm();
        });
    }

    // Función para resetear el formulario
    function resetRatingForm() {
        userCurrentRating = null;
        setRating(0);
        comentarioTextarea.value = '';
        calificacionIdInput.value = '';
        modalTitle.textContent = 'Calificar servicio';
        ratingQuestion.textContent = '¿Cómo calificarías este servicio?';
        submitButtonText.textContent = 'Enviar calificación';
        deleteButton.style.display = 'none';
        submitButton.disabled = true;
    }

    // Función para establecer la calificación
    function setRating(rating) {
        puntuacionInput.value = rating;
        
        // Actualizar estrellas
        ratingStars.forEach(star => {
            const starRating = parseInt(star.getAttribute('data-rating'));
            star.classList.toggle('active', starRating <= rating);
            star.classList.toggle('bi-star-fill', starRating <= rating);
            star.classList.toggle('bi-star', starRating > rating);
        });
        
        // Actualizar texto
        ratingText.textContent = ratingTexts[rating] || 'Selecciona una calificación';
    }

    // Event listeners para las estrellas
    ratingStars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            setRating(rating);
            submitButton.disabled = false;
        });

        star.addEventListener('mouseenter', function() {
            const hoverRating = parseInt(this.getAttribute('data-rating'));
            ratingStars.forEach(s => {
                const starRating = parseInt(s.getAttribute('data-rating'));
                s.style.color = starRating <= hoverRating ? '#ffc107' : '#dee2e6';
            });
        });

        star.addEventListener('mouseleave', function() {
            const currentRating = parseInt(puntuacionInput.value);
            ratingStars.forEach(s => {
                const starRating = parseInt(s.getAttribute('data-rating'));
                s.style.color = starRating <= currentRating ? '#ffc107' : '#dee2e6';
            });
        });
    });

    // Eliminar calificación - Mostrar modal de confirmación
    if (deleteButton) {
        deleteButton.addEventListener('click', function() {
            if (userCurrentRating) {
                calificacionToDelete = userCurrentRating.id;
                confirmDeleteModal.show();
            }
        });
    }

    // Confirmar eliminación
    if (confirmDeleteButton) {
        confirmDeleteButton.addEventListener('click', function() {
            if (calificacionToDelete) {
                deleteRating(calificacionToDelete);
                confirmDeleteModal.hide();
            }
        });
    }

    // Función para eliminar calificación
    function deleteRating(calificacionId) {
        // Mostrar loading en el botón
        const originalText = confirmDeleteButton.innerHTML;
        confirmDeleteButton.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Eliminando...';
        confirmDeleteButton.disabled = true;

        fetch(`{{ route('cliente.calificaciones.destroy', '') }}/${calificacionId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (response.ok) {
                showToast('Calificación eliminada correctamente', 'success');
                // Cerrar modales y recargar página para ver cambios
                const calificarModalInstance = bootstrap.Modal.getInstance(document.getElementById('calificarModal'));
                calificarModalInstance.hide();
                
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                throw new Error('Error al eliminar calificación');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error al eliminar la calificación', 'error');
        })
        .finally(() => {
            // Restaurar botón
            confirmDeleteButton.innerHTML = originalText;
            confirmDeleteButton.disabled = false;
            calificacionToDelete = null;
        });
    }

    // Sistema de favoritos
    const favoriteButton = document.getElementById('btn-favorite');
    if (favoriteButton) {
        favoriteButton.addEventListener('click', function() {
            const serviceId = this.getAttribute('data-service-id');
            toggleFavorite(serviceId, this);
        });
    }

    // Sistema de compartir
    const shareButton = document.getElementById('btn-share');
    const shareModal = new bootstrap.Modal(document.getElementById('shareModal'));
    const shareUrl = document.getElementById('share-url');
    const copyButton = document.getElementById('copy-url');
    const copyFeedback = document.getElementById('copy-feedback');
    
    if (shareButton) {
        shareButton.addEventListener('click', function() {
            shareModal.show();
        });
    }

    if (copyButton) {
        copyButton.addEventListener('click', function() {
            shareUrl.select();
            shareUrl.setSelectionRange(0, 99999);
            document.execCommand('copy');
            
            // Mostrar feedback
            copyFeedback.style.display = 'block';
            setTimeout(() => {
                copyFeedback.style.display = 'none';
            }, 2000);
        });
    }

    // Configurar botones de compartir
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const platform = this.getAttribute('data-platform');
            const url = encodeURIComponent(shareUrl.value);
            const title = encodeURIComponent('{{ $servicio->titulo }}');
            
            let shareUrlPlatform = '';
            
            switch(platform) {
                case 'whatsapp':
                    shareUrlPlatform = `https://wa.me/?text=${title}%20${url}`;
                    break;
                case 'facebook':
                    shareUrlPlatform = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                    break;
                case 'twitter':
                    shareUrlPlatform = `https://twitter.com/intent/tweet?text=${title}&url=${url}`;
                    break;
            }
            
            window.open(shareUrlPlatform, '_blank');
            shareModal.hide();
        });
    });

    // Validación del formulario de calificación
    const calificarForm = document.getElementById('calificarForm');
    if (calificarForm) {
        calificarForm.addEventListener('submit', function(e) {
            if (parseInt(puntuacionInput.value) === 0) {
                e.preventDefault();
                showToast('Por favor, selecciona una calificación', 'error');
            }
        });
    }

    // Función para alternar favoritos
    function toggleFavorite(serviceId, button) {
        if (!serviceId) return;
        
        fetch('{{ route("cliente.favoritos.toggle") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ servicio_id: serviceId })
        })
        .then(function(response) { 
            return response.json(); 
        })
        .then(function(data) {
            if (data.success) {
                if (data.is_favorite) {
                    button.classList.add('active');
                    button.innerHTML = '<i class="bi bi-heart-fill"></i> <span class="d-none d-md-inline">En favoritos</span>';
                    showToast('Servicio agregado a favoritos', 'success');
                } else {
                    button.classList.remove('active');
                    button.innerHTML = '<i class="bi bi-heart"></i> <span class="d-none d-md-inline">Guardar</span>';
                    showToast('Servicio eliminado de favoritos', 'info');
                }
            } else {
                showToast('Error al actualizar favoritos', 'error');
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            showToast('Error de conexión', 'error');
        });
    }

    // Función para mostrar notificaciones toast
    function showToast(message, type) {
        // Crear elemento toast si no existe
        if (!document.getElementById('toast-container')) {
            var toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }
        
        var toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-' + type + ' border-0';
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = 
            '<div class="d-flex">' +
                '<div class="toast-body">' +
                    message +
                '</div>' +
                '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
            '</div>';
        
        document.getElementById('toast-container').appendChild(toast);
        
        var bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Eliminar el toast después de que se oculte
        toast.addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
    }
});
</script>
@endsection