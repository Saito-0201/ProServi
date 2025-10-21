{{-- resources/views/publico/servicios/index.blade.php --}}
@extends('layouts.landing')

@section('title', 'Servicios - PROSERVI')
@section('content')

<div class="section">
    <div class="container">
        {{-- Buscador Principal Minimalista --}}
        <div class="card ui-card mb-4 border-0 shadow-sm">
            <div class="card-body p-0">
                {{-- BUSCADOR MINIMALISTA MEJORADO --}}
                <form method="GET" action="{{ route('public.servicios.index') }}" class="searchbar" role="search" autocomplete="on" id="search-form">
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
                <form id="filter-form" method="GET" action="{{ route('public.servicios.index') }}">
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
                            <i class="fas fa-filter me-1"></i> Aplicar filtros
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="clear-filters">
                            <i class="fas fa-redo me-1"></i> Limpiar
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
                        <form id="filter-form-mobile" method="GET" action="{{ route('public.servicios.index') }}">
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
                                    <i class="fas fa-filter me-1"></i> Aplicar filtros
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="clear-filters-mobile">
                                    <i class="fas fa-redo me-1"></i> Limpiar
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
                    </div>
                    <div class="d-none d-md-block">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary active" id="btn-grid-view">
                                <i class="fas fa-th"></i>
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="btn-list-view">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Contenedor de servicios --}}
                <div id="services-container" class="row g-4">
                    {{-- Los servicios se cargan via AJAX --}}
                </div>

                {{-- Estados de la aplicación --}}
                <div id="loading-indicator" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="text-muted mt-2">Cargando más servicios...</p>
                </div>

                <div id="no-more-results" class="text-center py-4" style="display: none;">
                    <p class="text-muted">
                        <i class="fas fa-check-circle me-2"></i>Has visto todos los servicios
                    </p>
                </div>

                <div id="empty-state" class="text-center py-5" style="display: none;">
                    <div class="empty-icon mb-3">
                        <i class="fas fa-search text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-muted">No se encontraron servicios</h5>
                    <p class="text-muted mb-4">Intenta ajustar tus filtros de búsqueda</p>
                    <button class="btn btn-primary" id="reset-search-btn">
                        <i class="fas fa-redo me-1"></i> Reiniciar búsqueda
                    </button>
                </div>

                <div id="error-state" class="text-center py-5" style="display: none;">
                    <div class="empty-icon mb-3">
                        <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-danger">Error al cargar servicios</h5>
                    <p class="text-muted mb-4">Por favor, intenta nuevamente</p>
                    <button class="btn btn-primary" id="retry-loading-btn">
                        <i class="fas fa-redo me-1"></i> Reintentar
                    </button>
                </div>
            </div>
        </div>

        {{-- Modal de Login --}}
        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">Iniciar Sesión Requerido</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="mb-4">
                            <i class="fas fa-lock text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h6 class="mb-3">Necesitas una cuenta para acceder a esta función</h6>
                        <p class="text-muted mb-4">
                            Regístrate para contactar prestadores, guardar favoritos y calificar servicios.
                        </p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i> Crear Cuenta
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
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

/* Botones que requieren login */
.require-login {
    cursor: pointer;
    position: relative;
}

.require-login:hover::before {
    content: "🔒";
    position: absolute;
    top: -5px;
    right: -5px;
    font-size: 0.8rem;
    background: white;
    border-radius: 50%;
    padding: 2px;
}
</style>

<script>
class PublicServicesManager {
    constructor() {
        this.currentPage = 1;
        this.isLoading = false;
        this.hasMore = true;
        this.currentView = 'grid';
        this.currentFilters = {};
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadInitialServices();
        this.initializeCategorySelects();
    }

    setupEventListeners() {
        // Scroll infinito
        window.addEventListener('scroll', this.handleScroll.bind(this));
        
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

    initializeCategorySelects() {
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
                    fetch(`/public/servicios/subcategorias/${categoriaId}`)
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

    async loadInitialServices() {
        this.currentPage = 1;
        this.updateFiltersFromURL();
        await this.loadServices();
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
            
            const response = await fetch(`{{ route('public.servicios.index') }}?${params}`);
            
            if (!response.ok) throw new Error('Error en la respuesta del servidor');
            
            const data = await response.json();
            
            if (!data.success) throw new Error(data.error || 'Error al cargar servicios');
            
            if (data.html && data.html.trim() !== '') {
                if (this.currentPage === 1) {
                    document.getElementById('services-container').innerHTML = data.html;
                } else {
                    document.getElementById('services-container').insertAdjacentHTML('beforeend', data.html);
                }
            }
            
            this.setupLoginRequiredButtons();
            
            this.hasMore = data.hasMore && data.currentPage < data.lastPage;
            
            if (this.hasMore) {
                this.currentPage = data.nextPage;
            } else {
                this.showNoMoreResults();
            }
            
            this.updateResultsCount(data.total);
            
        } catch (error) {
            console.error('Error loading services:', error);
            this.showError('Error al cargar los servicios: ' + error.message);
        } finally {
            this.hideLoading();
            this.isLoading = false;
        }
    }

    setupLoginRequiredButtons() {
        // Configurar botones que requieren login
        const loginRequiredButtons = document.querySelectorAll('.require-login');
        loginRequiredButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.showLoginModal();
            });
        });
    }

    showLoginModal() {
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
    }

    handleScroll() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        
        if (scrollTop + windowHeight >= documentHeight - 100) {
            this.loadServices();
        }
    }

    handleSearch(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        this.currentFilters = {};
        
        for (const [key, value] of formData) {
            if (value) this.currentFilters[key] = value;
        }
        
        this.currentPage = 1;
        this.hasMore = true;
        this.updateURL();
        this.loadServices();
    }

    handleFilterSubmit(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        this.currentFilters = {};
        
        for (const [key, value] of formData) {
            if (value) this.currentFilters[key] = value;
        }
        
        this.currentPage = 1;
        this.hasMore = true;
        this.updateURL();
        this.closeFilters();
        this.loadServices();
    }

    updateURL() {
        const url = new URL(window.location);
        url.search = '';
        
        Object.keys(this.currentFilters).forEach(key => {
            if (this.currentFilters[key]) {
                url.searchParams.set(key, this.currentFilters[key]);
            }
        });
        
        window.history.replaceState({}, '', url);
    }

    resetSearch() {
        this.currentFilters = {};
        this.currentPage = 1;
        this.hasMore = true;
        
        // Limpiar formularios
        const forms = ['search-form', 'filter-form', 'filter-form-mobile'];
        forms.forEach(formId => {
            const form = document.getElementById(formId);
            if (form) form.reset();
        });
        
        this.updateURL();
        this.loadServices();
    }

    retryLoading() {
        this.hideError();
        this.loadServices();
    }

    changeView(view) {
        this.currentView = view;
        const btnGridView = document.getElementById('btn-grid-view');
        const btnListView = document.getElementById('btn-list-view');
        
        if (view === 'grid') {
            btnGridView.classList.add('active');
            btnListView.classList.remove('active');
            document.getElementById('services-container').className = 'row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3';
        } else {
            btnGridView.classList.remove('active');
            btnListView.classList.add('active');
            document.getElementById('services-container').className = 'row g-4 row-cols-1';
        }
        
        this.currentPage = 1;
        this.hasMore = true;
        this.loadServices();
    }

    openFilters() {
        // En móvil, usar modal
        if (window.innerWidth < 768) {
            const filtersModal = new bootstrap.Modal(document.getElementById('filtersModal'));
            filtersModal.show();
        } else {
            // En escritorio, usar panel lateral
            document.getElementById('filters-sidebar').classList.add('active');
            document.getElementById('filters-overlay').classList.add('active');
        }
    }

    closeFilters() {
        document.getElementById('filters-sidebar').classList.remove('active');
        document.getElementById('filters-overlay').classList.remove('active');
    }

    // Métodos de UI
    showLoading() {
        const loading = document.getElementById('loading-indicator');
        if (loading) loading.style.display = 'block';
    }

    hideLoading() {
        const loading = document.getElementById('loading-indicator');
        if (loading) loading.style.display = 'none';
    }

    showNoMoreResults() {
        const noMore = document.getElementById('no-more-results');
        if (noMore) noMore.style.display = 'block';
    }

    hideNoMoreResults() {
        const noMore = document.getElementById('no-more-results');
        if (noMore) noMore.style.display = 'none';
    }

    showEmptyState() {
        const empty = document.getElementById('empty-state');
        if (empty) empty.style.display = 'block';
    }

    hideEmptyState() {
        const empty = document.getElementById('empty-state');
        if (empty) empty.style.display = 'none';
    }

    showError(message) {
        const error = document.getElementById('error-state');
        if (error) {
            error.style.display = 'block';
            const errorText = error.querySelector('p');
            if (errorText) errorText.textContent = message;
        }
    }

    hideError() {
        const error = document.getElementById('error-state');
        if (error) error.style.display = 'none';
    }

    updateResultsCount(count) {
        const resultsCount = document.getElementById('results-count');
        if (resultsCount) {
            if (count === 0) {
                resultsCount.textContent = 'No se encontraron servicios';
                this.showEmptyState();
            } else {
                resultsCount.textContent = `${count} servicio${count !== 1 ? 's' : ''} encontrado${count !== 1 ? 's' : ''}`;
                this.hideEmptyState();
            }
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    new PublicServicesManager();
});
</script>
@endsection