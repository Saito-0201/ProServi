{{-- resources/views/cliente/prestadores/show.blade.php --}}
@extends('layouts.cliente')

@section('title', $prestador->name . ' — Prestador PROSERVI')
@section('page_title', 'Perfil del Prestador')

@section('cliente-content')
<div class="row">
    {{-- Columna principal --}}
    <div class="col-lg-8">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('cliente.servicios.index') }}">Servicios</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $prestador->name }}</li>
            </ol>
        </nav>

        {{-- Header del prestador --}}
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        @php
                            $fotoPrestador = $prestador->prestadorInfo && $prestador->prestadorInfo->foto_perfil 
                                ? Storage::url($prestador->prestadorInfo->foto_perfil) 
                                : asset('uploads/images/default-user.png');
                        @endphp
                        <img src="{{ $fotoPrestador }}" 
                            class="prestador-avatar-large mb-3" 
                            width="120" 
                            height="120" 
                            alt="{{ $prestador->name }}"
                            onerror="this.src='{{ asset('uploads/images/default-user.png') }}'">
                    </div>
                    
                    <div class="col-md-9" >
                        <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                            <h1 class="h3 mb-0 me-2">{{ $prestador->name }} {{ $prestador->lastname }}</h1>

                            @if($prestador->prestadorInfo && $prestador->prestadorInfo->verificado)
                                <span class="verify-chip verify-chip--ok" aria-label="Prestador verificado">
                                    <i class="bi bi-patch-check-fill me-1"></i> Prestador verificado
                                </span>
                            @else
                                <span class="verify-chip verify-chip--no" aria-label="Prestador no verificado">
                                    <i class="bi bi-x-circle me-1"></i> Prestador no verificado
                                </span>
                            @endif
                        </div>
                        
                        @if($prestador->prestadorInfo && $prestador->prestadorInfo->especialidades)
                            <p class="text-muted mb-3">
                                <i class="bi bi-award me-2"></i>
                                {{ $prestador->prestadorInfo->especialidades }}
                            </p>
                        @endif
                        
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <div class="text-center">
                                    <div class="h4 text-primary mb-1">{{ $servicios->count() }}</div>
                                    <small class="text-muted">Servicios activos</small>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="text-center">
                                    <div class="h4 text-primary mb-1">
                                        @php
                                            $totalCalificaciones = $prestador->servicios->sum('total_calificaciones');
                                        @endphp
                                        {{ $totalCalificaciones }}
                                    </div>
                                    <small class="text-muted">Calificaciones</small>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="text-center">
                                    <div class="h4 text-primary mb-1">
                                        @php
                                            $promedio = $prestador->servicios->avg('calificacion_promedio');
                                        @endphp
                                        {{ $promedio ? number_format($promedio, 1) : '0.0' }}
                                    </div>
                                    <small class="text-muted">Rating promedio</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2">
                            @if($prestador->prestadorInfo && $prestador->prestadorInfo->disponibilidad)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    {{ $prestador->prestadorInfo->disponibilidad }}
                                </span>
                            @endif
                            
                            @if($prestador->prestadorInfo && $prestador->prestadorInfo->experiencia)
                                <span class="badge bg-info">
                                    <i class="bi bi-briefcase me-1"></i>
                                    {{ $prestador->prestadorInfo->experiencia }} de experiencia
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Descripción --}}
        @if($prestador->prestadorInfo && $prestador->prestadorInfo->descripcion)
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="bi bi-person-lines-fill text-primary me-2"></i>Sobre mí
                </h5>
                <p class="card-text text-muted" style="line-height: 1.6; white-space: pre-line;">
                    {{ $prestador->prestadorInfo->descripcion }}
                </p>
            </div>
        </div>
        @endif

        {{-- Experiencia --}}
        @if($prestador->prestadorInfo && $prestador->prestadorInfo->experiencia)
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="bi bi-briefcase text-primary me-2"></i>Experiencia
                </h5>
                <p class="card-text text-muted">
                    {{ $prestador->prestadorInfo->experiencia }} de experiencia en el sector
                </p>
            </div>
        </div>
        @endif

        {{-- Servicios del prestador --}}
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-grid text-primary me-2"></i>Servicios ofrecidos
                    </h5>
                    <span class="badge bg-primary">{{ $servicios->count() }} servicios</span>
                </div>
                
                @if($servicios->count() > 0)
                    <div class="row g-3">
                        @foreach($servicios as $servicio)
                            <div class="col-md-6">
                                <div class="card service-card h-100 border-0 shadow-sm">
                                    <div class="service-image-container">
                                        <img src="{{ $servicio->imagen_url }}" 
                                            class="service-image" alt="{{ $servicio->titulo }}">
                                        <div class="category-badge">
                                            <span class="badge bg-primary">{{ $servicio->categoria->nombre_cat }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="card-body">
                                        <h6 class="service-title mb-2">{{ Str::limit($servicio->titulo, 40) }}</h6>
                                        
                                        <p class="service-location small text-muted mb-2">
                                            <i class="bi bi-geo-alt me-1"></i>{{ $servicio->ciudad }}
                                        </p>

                                        <div class="mb-3">
                                            <span class="text-primary fw-bold">{{ $servicio->precio_formateado }}</span>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="service-rating">
                                                <div class="stars">
                                                    @php
                                                        $rating = $servicio->calificacion_promedio ?? 0;
                                                        $totalCalif = $servicio->total_calificaciones ?? 0;
                                                    @endphp
                                                    @if($totalCalif > 0)
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="bi bi-star{{ $i <= $rating ? '-fill' : '' }} text-warning"></i>
                                                        @endfor
                                                    @else
                                                        <span class="text-muted small">Sin calificaciones</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <span class="service-views small text-muted">
                                                <i class="bi bi-eye me-1"></i>{{ $servicio->visitas }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer bg-white border-0 pt-0">
                                        <div class="d-grid">
                                            <a href="{{ route('cliente.servicios.show', $servicio->id) }}" 
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye me-1"></i> Ver detalles
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2 mb-0">Este prestador no tiene servicios activos</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">
        {{-- Información de contacto --}}
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-telephone text-primary me-2"></i>Contactar
                </h5>
                
                @if($prestador->prestadorInfo && $prestador->prestadorInfo->telefono)
                    <div class="d-grid gap-2">
                        <a href="https://wa.me/{{ $prestador->prestadorInfo->telefono }}?text=Hola,%20vi%20tu%20perfil%20en%20PROSERVI%20y%20me%20interesa%20tus%20servicios"
                           class="btn btn-success" target="_blank">
                            <i class="bi bi-whatsapp me-2"></i> Contactar por WhatsApp
                        </a>
                        
                        <a href="tel:{{ $prestador->prestadorInfo->telefono }}" class="btn btn-outline-primary">
                            <i class="bi bi-telephone me-2"></i> Llamar por teléfono
                        </a>
                    </div>
                @else
                    <div class="alert alert-info">
                        <small>
                            <i class="bi bi-info-circle me-2"></i>
                            Información de contacto no disponible
                        </small>
                    </div>
                @endif
            </div>
        </div>

        {{-- Estadísticas --}}
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-graph-up text-primary me-2"></i>Estadísticas
                </h5>
                
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="h4 text-primary mb-1">{{ $servicios->count() }}</div>
                        <small class="text-muted">Servicios activos</small>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="h4 text-primary mb-1">
                            @php
                                $totalVistas = $servicios->sum('visitas');
                            @endphp
                            {{ number_format($totalVistas) }}
                        </div>
                        <small class="text-muted">Total de vistas</small>
                    </div>
                    <div class="col-6">
                        <div class="h4 text-primary mb-1">
                            @php
                                $promedioRating = $servicios->avg('calificacion_promedio');
                            @endphp
                            {{ $promedioRating ? number_format($promedioRating, 1) : '0.0' }}
                        </div>
                        <small class="text-muted">Rating promedio</small>
                    </div>
                    <div class="col-6">
                        <div class="h4 text-primary mb-1">
                            @php
                                $totalCalifs = $servicios->sum('total_calificaciones');
                            @endphp
                            {{ $totalCalifs }}
                        </div>
                        <small class="text-muted">Total calificaciones</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Especialidades --}}
        @if($prestador->prestadorInfo && $prestador->prestadorInfo->especialidades)
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="bi bi-award text-primary me-2"></i>Especialidades
                </h5>
                <div class="d-flex flex-wrap gap-2">
                    @php
                        $especialidades = explode(',', $prestador->prestadorInfo->especialidades);
                    @endphp
                    @foreach($especialidades as $especialidad)
                        <span class="badge bg-light text-dark border">{{ trim($especialidad) }}</span>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Información de seguridad --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="card-title mb-3">
                    <i class="bi bi-shield-check text-success me-2"></i>Prestador verificado
                </h6>
                <ul class="list-unstyled small text-muted mb-0">
                    @if($prestador->prestadorInfo && $prestador->prestadorInfo->verificado)
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Identidad verificada</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Documentación validada</li>
                    @endif
                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Servicios activos</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Calificaciones reales</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>

.verify-chip{
    display:inline-flex;
    align-items:center;
    gap:.25rem;
    padding:.25rem .6rem;
    border-radius:999px;
    font-size:.85rem;
    line-height:1;
    font-weight:600;
    border:1px solid transparent;
    white-space:nowrap;
}
.verify-chip--ok{
    color:#198754;                 /* verde */
    background:rgba(25,135,84,.08);
    border-color:rgba(25,135,84,.25);
}
.verify-chip--no{
    color:#6c757d;                 /* gris */
    background:rgba(108,117,125,.12);
    border-color:rgba(108,117,125,.25);
}

.prestador-avatar-large {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.verified-badge-large {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.9rem;
    border: 1px solid rgba(25, 135, 84, 0.2);
}

.verified-badge-large i {
    font-size: 1rem;
}

.service-card {
    transition: all 0.3s ease;
    border: 1px solid #f1f5f9;
    height: 100%;
}

.service-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border-color: #3b82f6;
}

.service-image-container {
    position: relative;
    height: 160px;
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

.category-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 5;
}

.category-badge .badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

.stars {
    display: inline-flex;
    gap: 1px;
    align-items: center;
}

.stars i {
    font-size: 0.8rem;
}

.service-title {
    font-weight: 600;
    line-height: 1.3;
}

.service-location {
    font-size: 0.85rem;
}

.service-views {
    font-size: 0.8rem;
}

/* Responsive */
@media (max-width: 768px) {
    .prestador-avatar-large {
        width: 100px;
        height: 100px;
        margin-bottom: 1rem;
    }
    
    .service-image-container {
        height: 140px;
    }
}

@media (max-width: 576px) {
    .prestador-avatar-large {
        width: 80px;
        height: 80px;
    }
    
    .service-image-container {
        height: 120px;
    }
    
    .card-body .row .col-sm-4 {
        margin-bottom: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll para anclas
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Animación para las tarjetas de servicio
    const serviceCards = document.querySelectorAll('.service-card');
    serviceCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('animate__animated', 'animate__fadeInUp');
    });

    // Tooltips para badges de verificación
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

@endsection