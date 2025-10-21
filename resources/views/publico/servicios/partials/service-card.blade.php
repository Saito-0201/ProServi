{{-- resources/views/publico/servicios/partials/service-card.blade.php --}}
@php
    $view = $view ?? 'grid';
    $colClass = $view === 'list' ? 'col-12' : 'col-12 col-sm-6 col-xl-4';
@endphp

<div class="{{ $colClass }} service-item" data-service-id="{{ $servicio->id }}">
    <div class="card service-card border-0 shadow-sm h-100">
        {{-- Imagen del servicio --}}
        <div class="service-image-container">
            <img src="{{ $servicio->imagen_url }}" 
                class="service-image" alt="{{ $servicio->titulo }}" loading="lazy">
            
            {{-- Categoría --}}
            @if($servicio->categoria)
                <div class="category-badge">
                    <span class="badge bg-primary">{{ $servicio->categoria->nombre_cat }}</span>
                </div>
            @endif
            
            {{-- Favorito (requiere login) --}}
            <button class="btn-favorite require-login" 
                    data-bs-toggle="tooltip" 
                    title="Inicia sesión para guardar en favoritos">
                <i class="fas fa-heart"></i>
            </button>
        </div>

        <div class="card-body">
            {{-- Titulo y badge de verificado --}}
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-center flex-grow-1 me-2">
                    <h6 class="service-title mb-0">{{ Str::limit($servicio->titulo, 40) }}</h6>
                    
                    @if($servicio->prestadorInfo && $servicio->prestadorInfo->verificado)
                        <div class="verified-badge ms-2" data-bs-toggle="tooltip" title="Prestador verificado">
                            <i class="fas fa-check-circle text-success"></i>
                            <span class="small">Verificado</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <p class="service-location small text-muted mb-2">
                <i class="fas fa-map-marker-alt me-1"></i>{{ $servicio->ciudad }}, {{ $servicio->provincia }}
            </p>

            {{-- Precio --}}
            <div class="mb-3">
                <span class="text-primary fw-bold">{{ $servicio->precio_formateado }}</span>
            </div>

            {{-- Rating --}}
            <div class="d-flex align-items-center justify-content-between">
                <div class="service-rating">
                    <div class="stars">
                        @php
                            $rating = $servicio->calificacion_promedio ?? 0;
                            $totalCalificaciones = $servicio->total_calificaciones ?? 0;
                        @endphp
                        
                        @if($totalCalificaciones > 0)
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $rating ? '' : '-empty' }} text-warning"></i>
                            @endfor
                        @else
                            <span class="badge bg-secondary small">Sin calificaciones</span>
                        @endif
                    </div>
                    
                    <small class="text-muted">
                        @if($totalCalificaciones > 0)
                            ({{ $totalCalificaciones }} {{ $totalCalificaciones == 1 ? 'calificación' : 'calificaciones' }})
                        @endif
                    </small>
                </div>
                
                <span class="service-views small text-muted">
                    <i class="fas fa-eye me-1"></i>{{ $servicio->visitas }}
                </span>
            </div>
        </div>

        <div class="card-footer bg-white border-0 pt-0">
            <div class="d-grid gap-2">
                <a href="{{ route('public.servicios.show', $servicio->id) }}" 
                  class="btn btn-outline-primary">
                    <i class="fas fa-eye me-1"></i> Ver detalles
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.category-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 5;
}

.btn-favorite {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    transition: all 0.3s ease;
    z-index: 10;
}

.btn-favorite:hover {
    background: white;
    color: #dc3545;
    transform: scale(1.1);
}

.verified-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    border: 1px solid rgba(25, 135, 84, 0.2);
}

.service-image-container {
    position: relative;
    height: 200px;
    overflow: hidden;
    border-radius: 12px 12px 0 0;
}

.service-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.service-card:hover .service-image {
    transform: scale(1.05);
}

.require-login {
    cursor: pointer;
}
</style>