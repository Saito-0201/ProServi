@extends('adminlte::page')

@section('title', 'Servicio/Datos del servicio')

@section('content_header')
    <h1><b>Datos del Servicio</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Datos registrados</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- ID -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id">ID</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $servicio->id }}" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Título -->
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="titulo">Título del Servicio</label><b> (*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $servicio->titulo }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Prestador -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="prestador">Prestador</label><b> (*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $servicio->prestador->name }} {{ $servicio->prestador->lastname }}" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Categoría -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="categoria">Categoría</label><b> (*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-list"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $servicio->categoria->nombre_cat }}" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Subcategoría -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="subcategoria">Subcategoría</label><b> (*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-list-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $servicio->subcategoria->nombre }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Tipo de Precio -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_precio">Tipo de Precio</label><b> (*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input type="text" class="form-control" 
                                           value="@switch($servicio->tipo_precio)
                                                @case('fijo') Precio Fijo @break
                                                @case('cotizacion') Por Cotización @break
                                                @case('diario') Precio Diario @break
                                                @case('por_servicio') Por Servicio @break
                                                @default {{ $servicio->tipo_precio }}
                                            @endswitch" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Precio -->
                        @if($servicio->tipo_precio != 'cotizacion')
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="precio">Precio</label><b> (*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-money-bill"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="Bs {{ number_format($servicio->precio, 2) }}" disabled>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Estado -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                                    </div>
                                    <input type="text" class="form-control" 
                                           value="{{ $servicio->estado == 'activo' ? 'Activo' : 'Inactivo' }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Descripción -->
                    <div class="form-group">
                        <label for="descripcion">Descripción</label><b> (*)</b>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                            </div>
                            <textarea class="form-control" rows="4" disabled>{{ $servicio->descripcion }}</textarea>
                        </div>
                    </div>
                    
                    <!-- Imagen -->
                    @if($servicio->imagen)
                    <div class="form-group">
                        <label for="imagen">Imagen del Servicio</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-image"></i></span>
                            </div>
                            <div class="form-control" style="height: auto;">
                                <img src="{{ asset('storage/' . $servicio->imagen) }}" alt="Imagen del servicio" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="row">
                        <!-- Visitas -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="visitas">Visitas</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-eye"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $servicio->visitas }}" disabled>
                                </div>
                            </div>
                        </div>
                        
                        
                        
                        <!-- Created At -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="created_at">Fecha de Creación</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-plus"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $servicio->created_at->format('d/m/Y H:i') }}" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Updated At -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="updated_at">Última Actualización</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $servicio->updated_at->format('d/m/Y H:i') }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Dirección -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direccion">Dirección</label><b> (*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $servicio->direccion }}" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Ciudad -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="ciudad">Ciudad</label><b> (*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $servicio->ciudad }}" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Provincia -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="provincia">Provincia</label><b> (*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $servicio->provincia }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- País -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pais">País</label><b> (*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $servicio->pais }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mapa de ubicación -->
                    @if($servicio->latitud && $servicio->longitud)
                    <div class="form-group">
                        <label for="map">Ubicación en Mapa</label>
                        <div id="map" style="height: 400px; width: 100%; border: 1px solid #ddd; border-radius: 4px;">
                            <!-- El mapa se cargará aquí -->
                        </div>
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="{{ route('admin.servicios.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver al Listado
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('css')
    <style>
        .form-control:disabled {
            background-color: #f8f9fa;
            opacity: 1;
        }
    </style>
@endpush

@push('js')
@if($servicio->latitud && $servicio->longitud && !empty(config('services.google.maps.maps_api_key')))
<script>
// Función para cargar Google Maps API de forma óptima
function loadGoogleMapsAPI() {
    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps.maps_api_key') }}&libraries=marker&callback=initMap`;
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
}

// Función principal de inicialización del mapa
async function initMap() {
    console.log('Google Maps API cargada correctamente');
    
    // Coordenadas del servicio
    const serviceLocation = { 
        lat: {{ $servicio->latitud }}, 
        lng: {{ $servicio->longitud }} 
    };
    
    try {
        // Importar las librerías necesarias
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
        
        // Crear el mapa
        const map = new Map(document.getElementById('map'), {
            center: serviceLocation,
            zoom: 15,
            streetViewControl: true,
            mapTypeControl: true,
            fullscreenControl: true,
            mapId: 'servicio_show_map'
        });

        // Crear marcador
        const marker = new AdvancedMarkerElement({
            map: map,
            position: serviceLocation,
            title: "{{ $servicio->titulo }}"
        });

    } catch (error) {
        console.error('Error al inicializar el mapa:', error);
        document.getElementById('map').innerHTML = `
            <div class="alert alert-danger">
                <h5>Error al inicializar el mapa</h5>
                <p>${error.message}</p>
                <p class="small">Asegúrate de tener habilitadas las APIs necesarias en Google Cloud Console</p>
            </div>
        `;
    }
}

// Cargar la API cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadGoogleMapsAPI);
} else {
    loadGoogleMapsAPI();
}

// Manejar errores de autenticación de Google Maps
window.gm_authFailure = function() {
    console.error('Error de autenticación de Google Maps');
    document.getElementById('map').innerHTML = `
        <div class="alert alert-danger">
            <h5>Error de autenticación</h5>
            <p>Verifique que su API Key de Google Maps sea válida y tenga las APIs necesarias habilitadas.</p>
            <p class="small">APIs requeridas: Maps JavaScript API</p>
        </div>
    `;
};
</script>
@elseif($servicio->latitud && $servicio->longitud)
<script>
// Mostrar mensaje si no hay API key configurada
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('map').innerHTML = `
        <div class="alert alert-info">
            <h5>Mapa no disponible</h5>
            <p>Configure GOOGLE_MAPS_API_KEY en el archivo .env para visualizar el mapa.</p>
            <p class="small">Coordenadas: {{ $servicio->latitud }}, {{ $servicio->longitud }}</p>
        </div>
    `;
});
</script>
@endif
@endpush