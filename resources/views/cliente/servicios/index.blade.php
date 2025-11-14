{{-- resources/views/cliente/servicios/index.blade.php --}}
@extends('layouts.cliente')

@section('title', 'Buscar Servicios — PROSERVI')
@section('page_title', 'ㅤServicios')

@section('cliente-content')
{{-- Buscador Principal Minimalista --}}
<div class="card ui-card mb-4 border-0 shadow-sm">
    <div class="card-body p-0">
        {{-- BUSCADOR MINIMALISTA MEJORADO --}}
        <form method="GET" action="{{ route('cliente.servicios.buscar') }}" class="searchbar" role="search" autocomplete="on" id="search-form">
            <!-- Icono de búsqueda (ahora es un botón) -->
            <button type="submit" class="search-btn" aria-label="Buscar servicios">
                <svg class="icon icon--muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>

            <!-- Input de búsqueda -->
            <input class="searchbar__input" type="search" name="q" 
                   placeholder="¿Qué servicio necesitas? Ej: plomero, electricista, clases de matemáticas..."
                   autocomplete="on" value="{{ request('q') }}" id="search-input" aria-label="Buscar servicios">

            <!-- Botón de filtros (tres puntos) - ÚNICO BOTÓN DE FILTROS -->
            <button type="button" class="icon-btn" aria-label="Abrir filtros de búsqueda" id="open-filters-btn">
                <svg class="icon icon--muted" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <circle cx="12" cy="5" r="2"></circle>
                    <circle cx="12" cy="12" r="2"></circle>
                    <circle cx="12" cy="19" r="2"></circle>
                </svg>
            </button>
        </form>
    </div>
</div>

{{-- Panel lateral de filtros para escritorio --}}
<div class="filters-sidebar" id="filters-sidebar">
    <div class="filters-header">
        <h5 class="mb-0">Filtros de búsqueda</h5>
        <button type="button" class="btn-close" id="close-filters-sidebar" aria-label="Cerrar panel de filtros"></button>
    </div>
    
    <div class="filters-content">
        <form id="filter-form" method="GET" action="{{ route('cliente.servicios.buscar') }}">
            {{-- Categoría --}}
            <div class="mb-3">
                <label for="categoria-select" class="form-label small fw-semibold">Categoría</label>
                <select name="categoria" class="form-select" id="categoria-select">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre_cat }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Subcategoría (se carga via AJAX) --}}
            <div class="mb-3" id="subcategoria-container" style="display: none;">
                <label for="subcategoria-select" class="form-label small fw-semibold">Subcategoría</label>
                <select name="subcategoria" class="form-select" id="subcategoria-select">
                    <option value="">Todas las subcategorías</option>
                </select>
            </div>

            {{-- Ciudad --}}
            <div class="mb-3">
                <label for="ciudad-select" class="form-label small fw-semibold">Ciudad</label>
                <select name="ciudad" class="form-select" id="ciudad-select">
                    <option value="">Todas las ciudades</option>
                    @foreach($ciudades as $ciudad)
                        <option value="{{ $ciudad }}" {{ request('ciudad') == $ciudad ? 'selected' : '' }}>
                            {{ $ciudad }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tipo de precio --}}
            <div class="mb-3">
                <label for="tipo-precio-select" class="form-label small fw-semibold">Tipo de precio</label>
                <select name="tipo_precio" class="form-select" id="tipo-precio-select">
                    <option value="">Todos los tipos</option>
                    <option value="fijo" {{ request('tipo_precio') == 'fijo' ? 'selected' : '' }}>Precio fijo</option>
                    <option value="cotizacion" {{ request('tipo_precio') == 'cotizacion' ? 'selected' : '' }}>Solicitar cotización</option>
                    <option value="variable" {{ request('tipo_precio') == 'variable' ? 'selected' : '' }}>Precio variable</option>
                    <option value="diario" {{ request('tipo_precio') == 'diario' ? 'selected' : '' }}>Precio por día</option>
                    <option value="por_servicio" {{ request('tipo_precio') == 'por_servicio' ? 'selected' : '' }}>Precio por servicio</option>
                </select>
            </div>

            {{-- Rango de precios --}}
            <div class="mb-3">
                <label class="form-label small fw-semibold">Rango de precios (Bs)</label>
                <div class="row g-2">
                    <div class="col-6">
                        <input type="number" name="precio_min" class="form-control" id="precio-min" 
                              placeholder="Mínimo" value="{{ request('precio_min') }}" min="0">
                    </div>
                    <div class="col-6">
                        <input type="number" name="precio_max" class="form-control" id="precio-max" 
                              placeholder="Máximo" value="{{ request('precio_max') }}" min="0">
                    </div>
                </div>
            </div>

            {{-- Filtros adicionales --}}
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="verificados" value="1" 
                          id="verificados-check" {{ request('verificados') ? 'checked' : '' }}>
                    <label class="form-check-label small" for="verificados-check">
                        Solo prestadores verificados
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <label for="rating-min-select" class="form-label small fw-semibold">Calificación mínima</label>
                <select name="rating_min" class="form-select" id="rating-min-select">
                    <option value="">Cualquier calificación</option>
                    <option value="4" {{ request('rating_min') == '4' ? 'selected' : '' }}>4+ estrellas</option>
                    <option value="3" {{ request('rating_min') == '3' ? 'selected' : '' }}>3+ estrellas</option>
                </select>
            </div>

            {{-- Ordenamiento --}}
            <div class="mb-3">
                <label for="orden-select" class="form-label small fw-semibold">Ordenar por</label>
                <select name="orden" class="form-select" id="orden-select">
                    <option value="">Recomendados</option>
                    <option value="fecha_desc" {{ request('orden') == 'fecha_desc' ? 'selected' : '' }}>Más recientes</option>
                    <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                    <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                    <option value="rating_desc" {{ request('orden') == 'rating_desc' ? 'selected' : '' }}>Mejor calificados</option>
                </select>
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-filter me-1"></i> Aplicar filtros
                </button>
                <button type="button" class="btn btn-outline-secondary" id="clear-filters">
                    <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Overlay para el panel lateral --}}
<div class="filters-overlay" id="filters-overlay"></div>

{{-- Modal de filtros para móvil --}}
<div class="modal fade" id="filtersModal" tabindex="-1" aria-labelledby="filtersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filtersModalLabel">Filtros de búsqueda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                {{-- Contenido IDÉNTICO al panel lateral --}}
                <form id="filter-form-mobile" method="GET" action="{{ route('cliente.servicios.buscar') }}">
                    {{-- Categoría --}}
                    <div class="mb-3">
                        <label for="categoria-select-mobile" class="form-label small fw-semibold">Categoría</label>
                        <select name="categoria" class="form-select" id="categoria-select-mobile">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre_cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Subcategoría (se carga via AJAX) --}}
                    <div class="mb-3" id="subcategoria-container-mobile" style="display: none;">
                        <label for="subcategoria-select-mobile" class="form-label small fw-semibold">Subcategoría</label>
                        <select name="subcategoria" class="form-select" id="subcategoria-select-mobile">
                            <option value="">Todas las subcategorías</option>
                        </select>
                    </div>

                    {{-- Ciudad --}}
                    <div class="mb-3">
                        <label for="ciudad-select-mobile" class="form-label small fw-semibold">Ciudad</label>
                        <select name="ciudad" class="form-select" id="ciudad-select-mobile">
                            <option value="">Todas las ciudades</option>
                            @foreach($ciudades as $ciudad)
                                <option value="{{ $ciudad }}" {{ request('ciudad') == $ciudad ? 'selected' : '' }}>
                                    {{ $ciudad }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tipo de precio --}}
                    <div class="mb-3">
                        <label for="tipo-precio-select-mobile" class="form-label small fw-semibold">Tipo de precio</label>
                        <select name="tipo_precio" class="form-select" id="tipo-precio-select-mobile">
                            <option value="">Todos los tipos</option>
                            <option value="fijo" {{ request('tipo_precio') == 'fijo' ? 'selected' : '' }}>Precio fijo</option>
                            <option value="cotizacion" {{ request('tipo_precio') == 'cotizacion' ? 'selected' : '' }}>Solicitar cotización</option>
                            <option value="variable" {{ request('tipo_precio') == 'variable' ? 'selected' : '' }}>Precio variable</option>
                            <option value="diario" {{ request('tipo_precio') == 'diario' ? 'selected' : '' }}>Precio por día</option>
                            <option value="por_servicio" {{ request('tipo_precio') == 'por_servicio' ? 'selected' : '' }}>Precio por servicio</option>
                        </select>
                    </div>

                    {{-- Rango de precios --}}
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Rango de precios (Bs)</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" name="precio_min" class="form-control" id="precio-min-mobile" 
                                      placeholder="Mínimo" value="{{ request('precio_min') }}" min="0">
                            </div>
                            <div class="col-6">
                                <input type="number" name="precio_max" class="form-control" id="precio-max-mobile" 
                                      placeholder="Máximo" value="{{ request('precio_max') }}" min="0">
                            </div>
                        </div>
                    </div>

                    {{-- Filtros adicionales --}}
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="verificados" value="1" 
                                  id="verificados-check-mobile" {{ request('verificados') ? 'checked' : '' }}>
                            <label class="form-check-label small" for="verificados-check-mobile">
                                Solo prestadores verificados
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="rating-min-select-mobile" class="form-label small fw-semibold">Calificación mínima</label>
                        <select name="rating_min" class="form-select" id="rating-min-select-mobile">
                            <option value="">Cualquier calificación</option>
                            <option value="4" {{ request('rating_min') == '4' ? 'selected' : '' }}>4+ estrellas</option>
                            <option value="3" {{ request('rating_min') == '3' ? 'selected' : '' }}>3+ estrellas</option>
                        </select>
                    </div>

                    {{-- Ordenamiento --}}
                    <div class="mb-3">
                        <label for="orden-select-mobile" class="form-label small fw-semibold">Ordenar por</label>
                        <select name="orden" class="form-select" id="orden-select-mobile">
                            <option value="">Recomendados</option>
                            <option value="fecha_desc" {{ request('orden') == 'fecha_desc' ? 'selected' : '' }}>Más recientes</option>
                            <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                            <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                            <option value="rating_desc" {{ request('orden') == 'rating_desc' ? 'selected' : '' }}>Mejor calificados</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">
                            <i class="bi bi-filter me-1"></i> Aplicar filtros
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="clear-filters-mobile">
                            <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Contenido principal de servicios --}}
<div class="row">
    <div class="col-12">
        {{-- Header de resultados --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-0 fw-bold" id="results-count">
                    Cargando servicios...
                </h5>
                <p class="text-muted small mb-0" id="results-subtitle">
                    Explorando servicios aleatorios
                </p>
            </div>
            <div class="d-none d-md-block">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary active" id="btn-grid-view" hidden>
                        <i class="bi bi-grid-3x3"></i>
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="btn-list-view" hidden>
                        <i class="bi bi-list"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Contenedor de servicios --}}
        <div id="services-container" class="row g-4">
            {{-- Los servicios se cargan via AJAX --}}
        </div>

        {{-- Estados de la aplicación --}}
        <div id="loading-indicator" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="text-muted mt-2" id="loading-text">Cargando servicios aleatorios...</p>
        </div>

        <div id="no-more-results" class="text-center py-4" style="display: none;">
            <p class="text-muted">
                <i class="bi bi-check-circle me-2"></i>Has visto todos los servicios
            </p>
        </div>

        <div id="empty-state" class="text-center py-5" style="display: none;">
            <div class="empty-icon mb-3">
                <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
            </div>
            <h5 class="text-muted">No se encontraron servicios</h5>
            <p class="text-muted mb-4">Intenta ajustar tus filtros de búsqueda</p>
            <button class="btn btn-primary" id="reset-search-btn">
                <i class="bi bi-arrow-clockwise me-1"></i> Reiniciar búsqueda
            </button>
        </div>

        <div id="error-state" class="text-center py-5" style="display: none;">
            <div class="empty-icon mb-3">
                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
            </div>
            <h5 class="text-danger">Error al cargar servicios</h5>
            <p class="text-muted mb-4">Por favor, intenta nuevamente</p>
            <button class="btn btn-primary" id="retry-loading-btn">
                <i class="bi bi-arrow-clockwise me-1"></i> Reintentar
            </button>
        </div>
    </div>
</div>

<style>
/* ===== VARIABLES CSS ===== */
:root {
    --bg: #ffffff;
    --pill: #ffffff;
    --text: #1f2937;
    --muted: #9aa6b2;
    --shadow: 0 8px 24px rgba(0,0,0,.12);
    --ring: #3b82f6;
}

/* ===== BUSCADOR MINIMALISTA MEJORADO ===== */
.searchbar {
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
    background: var(--pill);
    border-radius: 999px;
    padding: 12px 16px;
    box-shadow: var(--shadow);
    border: 2px solid transparent;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

.searchbar:focus-within {
    border-color: var(--ring);
    box-shadow: 0 8px 28px rgba(59,130,246,.25);
}

/* Botón de búsqueda */
.search-btn {
    width: 40px;
    height: 40px;
    border: 0;
    background: transparent;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    flex-shrink: 0;
    color: var(--muted);
}

.search-btn:hover {
    background: rgba(0,0,0,.05);
    color: var(--text);
}

.search-btn:active {
    transform: translateY(1px);
}

.search-btn:focus-visible {
    outline: 2px solid var(--ring);
    outline-offset: 2px;
}

/* icons */
.icon {
    width: 22px; 
    height: 22px; 
    display: block;
}

.icon--muted { 
    color: var(--muted);
}

/* input */
.searchbar__input {
    flex: 1;
    min-width: 0;
    border: 0;
    outline: 0;
    background: transparent;
    font-size: 16px;
    color: var(--text);
    padding: 8px 4px;
    font-family: inherit;
}

.searchbar__input::placeholder { 
    color: var(--muted);
}

/* Botón de filtros (tres puntos) */
.icon-btn {
    width: 40px; 
    height: 40px; 
    border-radius: 50%;
    border: 0; 
    background: transparent;
    display: flex; 
    align-items: center; 
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    flex-shrink: 0;
    color: var(--muted);
}

.icon-btn:hover { 
    background: rgba(0,0,0,.05);
    color: var(--text);
}

.icon-btn:active { 
    transform: translateY(1px); 
}

.icon-btn:focus-visible {
    outline: 2px solid var(--ring);
    outline-offset: 2px;
}

/* ===== ELIMINAR ESTILOS ANTIGUOS ===== */
.card.ui-card {
    background: transparent;
    border: none;
    box-shadow: none;
}

.card-body.p-0 {
    padding: 0 !important;
}

/* ===== ESTILOS PARA EL PANEL LATERAL DE FILTROS ===== */
.filters-sidebar {
    position: fixed;
    top: 0;
    right: -400px;
    width: 380px;
    height: 100vh;
    background: white;
    z-index: 1050;
    box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
    transition: right 0.3s ease;
    overflow-y: auto;
    padding: 20px;
}

.filters-sidebar.active {
    right: 0;
}

.filters-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e9ecef;
}

.filters-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1040;
    display: none;
}

.filters-overlay.active {
    display: block;
}

/* ===== ESTILOS PARA LAS TARJETAS DE SERVICIO ===== */
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

/* ===== RESPONSIVE DESIGN MEJORADO ===== */
@media (max-width: 768px) {
    .searchbar { 
        padding: 10px 14px;
        gap: 6px;
        border-radius: 16px;
    }
    
    .search-btn,
    .icon-btn {
        width: 36px;
        height: 36px;
    }
    
    .icon { 
        width: 20px; 
        height: 20px; 
    }
    
    .searchbar__input { 
        font-size: 16px;
        padding: 6px 4px;
    }
    
    .searchbar__input::placeholder {
        font-size: 15px;
    }
    
    /* Panel lateral oculto en móvil */
    .filters-sidebar {
        display: none;
    }
    
    /* Vista lista en móvil */
    #services-container.row-cols-1 .service-card {
        display: flex;
        flex-direction: row;
        height: 160px;
    }
    
    #services-container.row-cols-1 .service-image-container {
        width: 120px;
        height: 160px;
        border-radius: 12px 0 0 12px;
    }
}

@media (max-width: 576px) {
    .searchbar { 
        padding: 8px 12px;
        gap: 4px;
    }
    
    .search-btn,
    .icon-btn {
        width: 32px;
        height: 32px;
    }
    
    .icon { 
        width: 18px; 
        height: 18px; 
    }
    
    .searchbar__input { 
        font-size: 15px;
        padding: 4px 2px;
    }
}

@media (max-width: 400px) {
    .searchbar { 
        padding: 6px 10px;
    }
    
    .search-btn,
    .icon-btn {
        width: 30px;
        height: 30px;
    }
    
    .icon { 
        width: 16px; 
        height: 16px; 
    }
    
    .searchbar__input { 
        font-size: 14px;
    }
}

/* Estados de carga */
#loading-indicator .spinner-border {
    width: 3rem;
    height: 3rem;
}

.service-item {
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* 🔥 NUEVO: Estilos para la experiencia mejorada */
.smart-loading {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.location-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

.relevance-badge {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.7rem;
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
}

/* Mejoras de rendimiento para scroll */
.services-container {
    content-visibility: auto;
    contain-intrinsic-size: 1px 5000px;
}

.service-card {
    will-change: transform;
    backface-visibility: hidden;
}

/* Animaciones suaves */
.fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Indicador de ubicación activa */
.location-active {
    border-left: 4px solid #10b981;
}

/* Estados de carga mejorados */
.loading-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: .5; }
}

/* Responsive mejoras */
@media (max-width: 768px) {
    .service-card {
        margin-bottom: 1rem;
    }
    
    .relevance-badge {
        font-size: 0.65rem;
        padding: 1px 6px;
    }
}

/* Estados hover mejorados */
.service-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
}

/* Scrollbar personalizado */
.services-container::-webkit-scrollbar {
    width: 6px;
}

.services-container::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.services-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.services-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>

<script>
// Clase principal para gestionar el scroll infinito con carga inteligente
class IntelligentScrollManager {
    constructor() {
        this.currentPage = 1;
        this.isLoading = false;
        this.hasMore = true;
        this.currentView = 'grid';
        this.currentFilters = {};
        this.favoriteLock = false;
        this.userLocation = null;
        this.init();
    }

    init() {
        if (typeof bootstrap === 'undefined') {
            setTimeout(() => this.delayedInit(), 100);
            return;
        }
        this.setupEventListeners();
        this.initializeCategorySelects();
        this.detectUserLocation(); // 🔥 NUEVO: Detectar ubicación al iniciar
        this.loadInitialServices();
    }

    delayedInit() {
        if (typeof bootstrap === 'undefined') {
            console.error('Bootstrap no está disponible. Recargando página...');
            setTimeout(() => location.reload(), 500);
            return;
        }
        this.setupEventListeners();
        this.initializeCategorySelects();
        this.detectUserLocation();
        this.loadInitialServices();
    }

    /**
     * 🔥 NUEVO: Detectar ubicación del usuario para personalización
     */
    async detectUserLocation() {
        if (!navigator.geolocation) {
            console.log('Geolocalización no soportada');
            this.useDefaultLocation();
            return;
        }

        try {
            const position = await new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 300000 // 5 minutos
                });
            });

            this.userLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            console.log('Ubicación detectada:', this.userLocation);
            
            // Enviar ubicación al servidor para personalización
            await this.sendLocationToServer();
            
        } catch (error) {
            console.log('No se pudo obtener la ubicación:', error.message);
            this.useDefaultLocation();
        }
    }

    /**
     * Usar ubicación por defecto
     */
    useDefaultLocation() {
        this.userLocation = {
            lat: -17.393,
            lng: -66.157,
            ciudad: 'Cochabamba'
        };
    }

    /**
     * 🔥 NUEVO: Enviar ubicación al servidor
     */
    async sendLocationToServer() {
        try {
            const response = await fetch('{{ route("cliente.servicios.detect-location") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.userLocation)
            });

            const data = await response.json();
            if (data.success) {
                console.log('Ubicación guardada en servidor:', data.location);
            }
        } catch (error) {
            console.error('Error enviando ubicación:', error);
        }
    }

    setupEventListeners() {
        // Scroll infinito mejorado
        window.addEventListener('scroll', this.throttle(this.handleScroll.bind(this), 200));
        
        // Botones de vista
        const btnGridView = document.getElementById('btn-grid-view');
        const btnListView = document.getElementById('btn-list-view');
        
        if (btnGridView) btnGridView.addEventListener('click', () => this.changeView('grid'));
        if (btnListView) btnListView.addEventListener('click', () => this.changeView('list'));
        
        // Formularios de búsqueda
        const searchForm = document.getElementById('search-form');
        const filterForm = document.getElementById('filter-form');
        const filterFormMobile = document.getElementById('filter-form-mobile');
        
        if (searchForm) searchForm.addEventListener('submit', (e) => this.handleSearch(e));
        if (filterForm) filterForm.addEventListener('submit', (e) => this.handleFilterSubmit(e));
        if (filterFormMobile) filterFormMobile.addEventListener('submit', (e) => this.handleFilterSubmit(e));
        
        // Mejorar experiencia de búsqueda en tiempo real
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce((e) => {
                this.handleRealTimeSearch(e.target.value);
            }, 500));
        }

        // Botones limpiar
        const clearFilters = document.getElementById('clear-filters');
        const clearFiltersMobile = document.getElementById('clear-filters-mobile');
        const resetSearchBtn = document.getElementById('reset-search-btn');
        const retryLoadingBtn = document.getElementById('retry-loading-btn');
        
        if (clearFilters) clearFilters.addEventListener('click', () => this.resetSearch());
        if (clearFiltersMobile) clearFiltersMobile.addEventListener('click', () => this.resetSearch());
        if (resetSearchBtn) resetSearchBtn.addEventListener('click', () => this.resetSearch());
        if (retryLoadingBtn) retryLoadingBtn.addEventListener('click', () => this.retryLoading());
        
        // Filtros
        const openFiltersBtn = document.getElementById('open-filters-btn');
        const closeFiltersSidebar = document.getElementById('close-filters-sidebar');
        const filtersOverlay = document.getElementById('filters-overlay');
        
        if (openFiltersBtn) openFiltersBtn.addEventListener('click', () => this.openFilters());
        if (closeFiltersSidebar) closeFiltersSidebar.addEventListener('click', () => this.closeFilters());
        if (filtersOverlay) filtersOverlay.addEventListener('click', () => this.closeFilters());
    }

    /**
     * 🔥 NUEVO: Búsqueda en tiempo real
     */
    async handleRealTimeSearch(query) {
        if (query.length < 2 && query.length !== 0) return;
        
        this.currentFilters.q = query;
        this.currentPage = 1;
        this.hasMore = true;
        
        this.hideNoMoreResults();
        this.hideEmptyState();
        await this.loadServices();
    }

    /**
     * 🔥 NUEVO: Throttle para optimizar scroll
     */
    throttle(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    }

    /**
     * 🔥 NUEVO: Debounce para búsqueda en tiempo real
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    async loadInitialServices() {
        this.currentPage = 1;
        this.updateFiltersFromURL();
        
        // Mostrar loading optimizado
        this.showSmartLoading();
        await this.loadServices();
    }

    async loadServices() {
        if (this.isLoading || !this.hasMore) return;
        
        this.isLoading = true;
        this.showLoading();
        this.hideError();
        
        try {
            const params = new URLSearchParams({
                ...this.currentFilters,
                page: this.currentPage,
                view: this.currentView,
                ajax: true
            });

            // 🔥 NUEVO: Agregar ubicación a los parámetros si está disponible
            if (this.userLocation) {
                params.append('lat', this.userLocation.lat);
                params.append('lng', this.userLocation.lng);
            }
            
            const response = await fetch(`{{ route('cliente.servicios.index') }}?${params}`);
            
            if (!response.ok) throw new Error('Error en la respuesta del servidor');
            
            const data = await response.json();
            
            if (!data.success) throw new Error(data.error || 'Error al cargar servicios');
            
            // Actualizar interfaz según el tipo de contenido
            this.updateInterfaceForContent(data);
            
            if (data.html && data.html.trim() !== '') {
                if (this.currentPage === 1) {
                    document.getElementById('services-container').innerHTML = data.html;
                    // 🔥 NUEVO: Animación suave para nueva carga
                    this.animateNewContent();
                } else {
                    document.getElementById('services-container').insertAdjacentHTML('beforeend', data.html);
                }
            } else if (this.currentPage === 1) {
                // No hay resultados
                this.showEmptyState();
            }
            
            this.initializeFavoriteButtons();
            
            this.hasMore = data.hasMore && data.currentPage < data.lastPage;
            
            if (this.hasMore) {
                this.currentPage = data.nextPage;
            } else {
                this.showNoMoreResults();
            }
            
            this.updateResultsCount(data.total, data.hasFilters);
            
        } catch (error) {
            console.error('Error loading services:', error);
            this.showError('Error al cargar los servicios: ' + error.message);
        } finally {
            this.isLoading = false;
            this.hideLoading();
        }
    }

    /**
     * 🔥 NUEVO: Actualizar interfaz según el tipo de contenido
     */
    updateInterfaceForContent(data) {
        const loadingText = document.getElementById('loading-text');
        const resultsSubtitle = document.getElementById('results-subtitle');
        
        if (loadingText) {
            if (data.hasFilters) {
                loadingText.textContent = 'Buscando servicios...';
            } else if (this.userLocation) {
                loadingText.textContent = 'Encontrando servicios cerca de ti...';
            } else {
                loadingText.textContent = 'Descubriendo servicios recomendados...';
            }
        }

        if (resultsSubtitle) {
            if (data.total === 0) {
                resultsSubtitle.textContent = 'No se encontraron servicios que coincidan';
            } else if (data.hasFilters) {
                resultsSubtitle.textContent = 'Resultados que coinciden con tu búsqueda';
            } else if (this.userLocation) {
                resultsSubtitle.textContent = 'Servicios populares en tu área';
            } else {
                resultsSubtitle.textContent = 'Servicios recomendados para ti';
            }
        }
    }

    /**
     * 🔥 NUEVO: Animación suave para nuevo contenido
     */
    animateNewContent() {
        const serviceCards = document.querySelectorAll('.service-item');
        serviceCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    /**
     * 🔥 NUEVO: Loading inteligente
     */
    showSmartLoading() {
        const loadingIndicator = document.getElementById('loading-indicator');
        const loadingText = document.getElementById('loading-text');
        
        if (loadingIndicator) {
            loadingIndicator.style.display = 'block';
            
            // Mensajes de loading contextuales
            const messages = [
                'Buscando los mejores servicios...',
                'Consultando prestadores cercanos...',
                'Cargando servicios recomendados...',
                'Actualizando resultados...'
            ];
            
            if (loadingText) {
                const randomMessage = messages[Math.floor(Math.random() * messages.length)];
                loadingText.textContent = randomMessage;
            }
        }
    }

    handleScroll() {
        if (this.isLoading || !this.hasMore) return;
        
        const scrollTop = window.scrollY;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        
        // 🔥 MEJORADO: Cargar antes de llegar al final para mejor experiencia
        if (documentHeight - (scrollTop + windowHeight) < 1000) {
            this.loadServices();
        }
    }

    async handleSearch(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        await this.performSearch(formData);
    }

    async handleFilterSubmit(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        await this.performSearch(formData);
        
        // Cerrar filtros después de aplicar
        if (e.target.id === 'filter-form-mobile') {
            const modalElement = document.getElementById('filtersModal');
            if (modalElement) {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) modal.hide();
            }
        } else {
            this.closeFilters();
        }
    }

    async performSearch(formData) {
        this.currentFilters = {};
        for (const [key, value] of formData) {
            if (value) this.currentFilters[key] = value;
        }
        
        const urlParams = new URLSearchParams(this.currentFilters);
        window.history.replaceState({}, '', `${window.location.pathname}?${urlParams}`);
        
        this.currentPage = 1;
        this.hasMore = true;
        
        this.hideNoMoreResults();
        this.hideEmptyState();
        await this.loadServices();
    }

    resetSearch() {
        this.currentFilters = {};
        window.history.replaceState({}, '', window.location.pathname);
        
        // Resetear todos los formularios
        const searchInput = document.getElementById('search-input');
        if (searchInput) searchInput.value = '';
        
        const searchForm = document.getElementById('search-form');
        const filterForm = document.getElementById('filter-form');
        const filterFormMobile = document.getElementById('filter-form-mobile');
        
        if (searchForm) searchForm.reset();
        if (filterForm) filterForm.reset();
        if (filterFormMobile) filterFormMobile.reset();
        
        // Ocultar contenedores de subcategoría
        const subcatContainer = document.getElementById('subcategoria-container');
        const subcatContainerMobile = document.getElementById('subcategoria-container-mobile');
        
        if (subcatContainer) subcatContainer.style.display = 'none';
        if (subcatContainerMobile) subcatContainerMobile.style.display = 'none';
        
        this.currentPage = 1;
        this.hasMore = true;
        
        this.hideNoMoreResults();
        this.hideEmptyState();
        this.closeFilters();
        this.loadServices();
    }

    changeView(view) {
        this.currentView = view;
        
        // Actualizar botones activos
        const btnGridView = document.getElementById('btn-grid-view');
        const btnListView = document.getElementById('btn-list-view');
        
        if (btnGridView) btnGridView.classList.toggle('active', view === 'grid');
        if (btnListView) btnListView.classList.toggle('active', view === 'list');
        
        // Actualizar clases del contenedor
        const container = document.getElementById('services-container');
        if (view === 'list') {
            container.className = 'row g-4 row-cols-1';
        } else {
            container.className = 'row g-4 row-cols-1 row-cols-sm-2 row-cols-xl-3';
        }
        
        // Recargar servicios con la nueva vista
        this.currentPage = 1;
        this.loadServices();
    }

    initializeFavoriteButtons() {
        const favoriteButtons = document.querySelectorAll('.btn-favorite');
        favoriteButtons.forEach(button => {
            // 🔥 ELIMINAR EVENT LISTENERS EXISTENTES PARA EVITAR DUPLICADOS
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);
        });

        // 🔥 RE-ASIGNAR EVENT LISTENERS A LOS NUEVOS BOTONES
        document.querySelectorAll('.btn-favorite').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation(); // 🔥 DETENER PROPAGACIÓN
                
                const serviceId = button.getAttribute('data-service-id');
                this.toggleFavorite(serviceId, button);
            });
        });
    }

    async toggleFavorite(serviceId, button) {
        // 🔒 EVITAR MÚLTIPLES CLICS SIMULTÁNEOS
        if (this.favoriteLock) {
            console.log('Bloqueado, esperando...');
            return;
        }
        
        this.favoriteLock = true;
        
        // Feedback visual inmediato
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="bi bi-hourglass-split"></i>';
        button.disabled = true;
        
        try {
            const response = await fetch('{{ route("cliente.favoritos.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ servicio_id: serviceId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // ACTUALIZAR ESTADO VISUAL DEL BOTÓN
                button.classList.toggle('active', data.is_favorite);
                
                // CAMBIAR ICONO SEGÚN ESTADO
                if (data.is_favorite) {
                    button.innerHTML = '<i class="bi bi-heart-fill text-white"></i>';
                    button.classList.add('active');
                } else {
                    button.innerHTML = '<i class="bi bi-heart text-muted"></i>';
                    button.classList.remove('active');
                }
                
                this.showToast(data.message, data.is_favorite ? 'success' : 'info');
            } else {
                // Restaurar estado original en caso de error
                button.innerHTML = originalHTML;
                this.showToast(data.message || 'Error al actualizar favoritos', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            // Restaurar estado original en caso de error
            button.innerHTML = originalHTML;
            this.showToast('Error de conexión', 'error');
        } finally {
            button.disabled = false;
            
            // 🔓 LIBERAR BLOQUEO DESPUÉS DE 500ms
            setTimeout(() => {
                this.favoriteLock = false;
            }, 500);
        }
    }

    closeFilters() {
        const filtersSidebar = document.getElementById('filters-sidebar');
        const filtersOverlay = document.getElementById('filters-overlay');
        
        if (filtersSidebar) filtersSidebar.classList.remove('active');
        if (filtersOverlay) filtersOverlay.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    openFilters() {
        if (window.innerWidth <= 768) {
            // En móvil, usar modal de Bootstrap
            const modalElement = document.getElementById('filtersModal');
            if (modalElement && typeof bootstrap !== 'undefined') {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        } else {
            // En escritorio, usar panel lateral
            const filtersSidebar = document.getElementById('filters-sidebar');
            const filtersOverlay = document.getElementById('filters-overlay');
            
            if (filtersSidebar) filtersSidebar.classList.add('active');
            if (filtersOverlay) filtersOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    updateResultsCount(total, hasFilters) {
        const countElement = document.getElementById('results-count');
        const subtitleElement = document.getElementById('results-subtitle');
        
        if (countElement) {
            if (total === 0) {
                countElement.textContent = '0 servicios encontrados';
                if (subtitleElement) subtitleElement.textContent = 'Intenta ajustar tus filtros de búsqueda';
                this.showEmptyState();
            } else {
                countElement.textContent = `${total} servicio(s) encontrado(s)`;
                this.hideEmptyState();
            }
        }
    }

    showLoading() {
        const loadingIndicator = document.getElementById('loading-indicator');
        if (loadingIndicator) loadingIndicator.style.display = 'block';
    }

    hideLoading() {
        const loadingIndicator = document.getElementById('loading-indicator');
        if (loadingIndicator) loadingIndicator.style.display = 'none';
    }

    showNoMoreResults() {
        const noMoreResults = document.getElementById('no-more-results');
        if (noMoreResults) noMoreResults.style.display = 'block';
    }

    hideNoMoreResults() {
        const noMoreResults = document.getElementById('no-more-results');
        if (noMoreResults) noMoreResults.style.display = 'none';
    }

    showEmptyState() {
        const emptyState = document.getElementById('empty-state');
        const servicesContainer = document.getElementById('services-container');
        
        if (emptyState) emptyState.style.display = 'block';
        if (servicesContainer) servicesContainer.style.display = 'none';
    }

    hideEmptyState() {
        const emptyState = document.getElementById('empty-state');
        const servicesContainer = document.getElementById('services-container');
        
        if (emptyState) emptyState.style.display = 'none';
        if (servicesContainer) servicesContainer.style.display = 'flex';
    }

    showError(message) {
        const errorState = document.getElementById('error-state');
        const servicesContainer = document.getElementById('services-container');
        
        if (errorState) errorState.style.display = 'block';
        if (servicesContainer) servicesContainer.style.display = 'none';
        this.showToast(message, 'danger');
    }

    hideError() {
        const errorState = document.getElementById('error-state');
        if (errorState) errorState.style.display = 'none';
    }

    retryLoading() {
        this.hideError();
        this.loadServices();
    }

    showToast(message, type = 'info') {
        if (typeof bootstrap === 'undefined') {
            console.log('Toast:', message);
            return;
        }
        
        if (!document.getElementById('toast-container')) {
            const toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }
        
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        
        toast.innerHTML = 
            '<div class="d-flex">' +
                '<div class="toast-body">' + message + '</div>' +
                '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
            '</div>';
        
        document.getElementById('toast-container').appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', () => toast.remove());
    }

    updateFiltersFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        this.currentFilters = {};
        
        for (const [key, value] of urlParams) {
            if (value) this.currentFilters[key] = value;
        }
        
        this.fillFormFromFilters();
    }

    fillFormFromFilters() {
        // Llenar todos los formularios con los filtros actuales
        const forms = ['search-form', 'filter-form', 'filter-form-mobile'];
        forms.forEach(formId => {
            const form = document.getElementById(formId);
            if (form) {
                Object.keys(this.currentFilters).forEach(key => {
                    const element = form.querySelector(`[name="${key}"]`);
                    if (element) {
                        if (element.type === 'checkbox') {
                            element.checked = this.currentFilters[key] === '1';
                        } else {
                            element.value = this.currentFilters[key];
                        }
                    }
                });
            }
        });
    }

    initializeCategorySelects() {
        // Configurar selects de categoría para ambos formularios
        this.setupCategorySelect('categoria-select', 'subcategoria-container', 'subcategoria-select');
        this.setupCategorySelect('categoria-select-mobile', 'subcategoria-container-mobile', 'subcategoria-select-mobile');
    }

    setupCategorySelect(selectId, containerId, subcatSelectId) {
        const select = document.getElementById(selectId);
        if (select) {
            select.addEventListener('change', function() {
                const categoriaId = this.value;
                const container = document.getElementById(containerId);
                const subcatSelect = document.getElementById(subcatSelectId);
                
                if (categoriaId) {
                    fetch(`/cliente/servicios/subcategorias/${categoriaId}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Error al cargar subcategorías');
                            return response.json();
                        })
                        .then(data => {
                            subcatSelect.innerHTML = '<option value="">Todas las subcategorías</option>';
                            data.forEach(subcategoria => {
                                subcatSelect.innerHTML += `<option value="${subcategoria.id}">${subcategoria.nombre}</option>`;
                            });
                            container.style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            container.style.display = 'none';
                        });
                } else {
                    container.style.display = 'none';
                }
            });
        }
    }
}

// Inicializar cuando el DOM esté listo
function initializeApp() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            new IntelligentScrollManager();
        });
    } else {
        new IntelligentScrollManager();
    }
}

// Esperar a que jQuery y Bootstrap estén disponibles
if (window.jQuery && typeof bootstrap !== 'undefined') {
    initializeApp();
} else {
    setTimeout(initializeApp, 100);
}
</script>
@endsection