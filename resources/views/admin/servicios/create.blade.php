@extends('adminlte::page')

@section('title', 'Registro de Nuevo Servicio')

@section('content_header')
    <h1><b>Registro de Nuevo Servicio</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(session('mensaje'))
                <div class="alert alert-{{ session('icono') }} alert-dismissible fade show" role="alert">
                    {{ session('mensaje') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Llene los datos del formulario</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.servicios.store') }}" method="post" enctype="multipart/form-data" id="servicioForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prestador_id">Prestador</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <select name="prestador_id" id="prestador_id" class="form-control" required>
                                            <option value="">Seleccionar Prestador</option>
                                            @foreach($prestadores as $prestador)
                                                <option value="{{ $prestador->id }}" {{ old('prestador_id') == $prestador->id ? 'selected' : '' }}>
                                                    {{ $prestador->name }} {{ $prestador->lastname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('prestador_id')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categoria_id">Categoría</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-list"></i></span>
                                        </div>
                                        <select name="categoria_id" id="categoria_id" class="form-control" required>
                                            <option value="">Seleccionar Categoría</option>
                                            @foreach($categorias as $categoria)
                                                <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                                    {{ $categoria->nombre_cat }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('categoria_id')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subcategoria_id">Subcategoría</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-list-alt"></i></span>
                                        </div>
                                        <select name="subcategoria_id" id="subcategoria_id" class="form-control" required>
                                            <option value="">Primero seleccione una categoría</option>
                                        </select>
                                    </div>
                                    @error('subcategoria_id')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo_precio">Tipo de Precio</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        <select name="tipo_precio" id="tipo_precio" class="form-control" required>
                                            @foreach($tiposPrecio as $value => $label)
                                                <option value="{{ $value }}" {{ old('tipo_precio') == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('tipo_precio')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="titulo">Título del Servicio</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="titulo" value="{{ old('titulo') }}" 
                                               placeholder="Ingrese título del servicio..." required maxlength="150">
                                    </div>
                                    @error('titulo')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group" id="precio-field">
                                    <label for="precio">Precio (Bs)</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-money-bill"></i></span>
                                        </div>
                                        <input type="number" class="form-control" name="precio" id="precio" 
                                               value="{{ old('precio') }}" step="0.01" min="0" placeholder="0.00">
                                    </div>
                                    @error('precio')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label><b> (*)</b>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                                </div>
                                <textarea name="descripcion" id="descripcion" class="form-control" rows="4" 
                                          placeholder="Ingrese descripción del servicio..." required>{{ old('descripcion') }}</textarea>
                            </div>
                            @error('descripcion')
                            <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="imagen">Imagen del Servicio</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-image"></i></span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="imagen" id="imagen" accept="image/*">
                                    <label class="custom-file-label" for="imagen">Seleccionar imagen...</label>
                                </div>
                            </div>
                            @error('imagen')
                            <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="row" hidden>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="direccion">Dirección</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="direccion" id="direccion" 
                                               value="{{ old('direccion') }}" placeholder="Ingrese dirección..." required>
                                    </div>
                                    @error('direccion')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6" hidden>
                                <div class="form-group">
                                    <label for="ciudad">Ciudad</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-city"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="ciudad" id="ciudad" 
                                               value="{{ old('ciudad') }}" placeholder="Ingrese ciudad..." required>
                                    </div>
                                    @error('ciudad')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row" hidden>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="provincia">Provincia</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="provincia" id="provincia" 
                                               value="{{ old('provincia') }}" placeholder="Ingrese provincia..." required>
                                    </div>
                                    @error('provincia')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label for="pais">País</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="pais" id="pais" 
                                               value="{{ old('pais', 'Bolivia') }}" placeholder="Ingrese país..." required>
                                    </div>
                                    @error('pais')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row" hidden>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estado">Estado:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                                        </div>
                                        <select name="estado" id="estado" class="form-control" required>
                                            <option value="activo" {{ old('estado', $servicio->estado ?? 'activo') == 'activo' ? 'selected' : '' }}>Activo</option>
                                            <option value="inactivo" {{ old('estado', $servicio->servicio->estado ?? 'activo') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        

                        <div id="map-container" style="position: relative;">
                            <div id="map" style="height: 400px; width: 100%; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 4px;">
                                @if(empty(config('services.google.maps.maps_api_key')))
                                    <div class="alert alert-warning h-100 d-flex align-items-center justify-content-center">
                                        <div class="text-center">
                                            <i class="fas fa-map-marked-alt fa-3x mb-3"></i>
                                            <h5>Google Maps no configurado</h5>
                                            <p>Configure GOOGLE_MAPS_API_KEY en el archivo .env</p>
                                            <p class="small text-muted">API Key actual: {{ config('services.google.maps.maps_api_key') ?? 'No configurada' }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row" hidden>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="latitud">Latitud</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-latitude"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="latitud" id="latitud" 
                                               value="{{ old('latitud', '-17.4040') }}" {{ empty(config('services.google.maps.maps_api_key')) ? '' : 'readonly' }}
                                               placeholder="{{ empty(config('services.google.maps.maps_api_key')) ? 'Ingrese latitud manualmente' : '' }}">
                                    </div>
                                    @error('latitud')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitud">Longitud</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-longitude"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="longitud" id="longitud" 
                                               value="{{ old('longitud', '-66.0409') }}" {{ empty(config('services.google.maps.maps_api_key')) ? '' : 'readonly' }}
                                               placeholder="{{ empty(config('services.google.maps.maps_api_key')) ? 'Ingrese longitud manualmente' : '' }}">
                                    </div>
                                    @error('longitud')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Registrar
                                    </button>
                                    <a href="{{ route('admin.servicios.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@push('css')
    <style>
        .custom-file-label::after {
            content: "Examinar";
        }
        .gm-style-iw {
            max-width: 250px !important;
        }
        #map-container {
            position: relative;
        }
        .location-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
        }
    </style>
@endpush

@push('js')
@if(!empty(config('services.google.maps.maps_api_key')))
<script>
// Variables globales para el mapa
let map;
let marker;
let geocoder;
let locationButton;

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
    
    // Coordenadas por defecto (Sacaba)
    const defaultLocation = { lat: -17.4040, lng: -66.0409 };
    
    try {
        // Importar las librerías necesarias
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
        
        // Crear el mapa
        map = new Map(document.getElementById('map'), {
            center: defaultLocation,
            zoom: 16,
            streetViewControl: false,
            mapTypeControl: false,
            fullscreenControl: true,
            mapId: 'servicio_map'
        });

        geocoder = new google.maps.Geocoder();
        
        // Usar AdvancedMarkerElement en lugar del Marker deprecado
        marker = new AdvancedMarkerElement({
            map: map,
            gmpDraggable: true,
            position: defaultLocation,
            title: "Arrastre para ajustar la ubicación"
        });

        // Crear botón de geolocalización
        createLocationButton();
        
        // Inicializar coordenadas
        updateCoordinates(defaultLocation);

        // Evento al arrastrar el marcador
        marker.addListener('dragend', function(event) {
            const position = marker.position;
            updateCoordinates({
                lat: position.lat,
                lng: position.lng
            });
            reverseGeocode(position);
        });

        // Evento al hacer click en el mapa
        map.addListener('click', function(event) {
            marker.position = event.latLng;
            updateCoordinates({
                lat: event.latLng.lat,
                lng: event.latLng.lng
            });
            reverseGeocode(event.latLng);
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

// Crear botón de geolocalización fuera del formulario
function createLocationButton() {
    // Crear contenedor para el botón
    const buttonContainer = document.createElement('div');
    buttonContainer.style.position = 'absolute';
    buttonContainer.style.bottom = '90px';
    buttonContainer.style.right = '15px';
    buttonContainer.style.zIndex = '1000';
    
    // Crear botón de geolocalización
    locationButton = document.createElement("button");
    locationButton.innerHTML = '<i class="fas fa-location-arrow"></i>';
    locationButton.classList.add("btn", "btn-primary", "btn-sm");
    locationButton.title = "Usar mi ubicación actual";
    locationButton.type = "button";
    
    // Agregar evento al botón
    locationButton.addEventListener("click", (e) => {
        e.preventDefault();
        locateUser();
    });
    
    // Agregar botón al contenedor
    buttonContainer.appendChild(locationButton);
    
    // Agregar contenedor al mapa
    document.getElementById('map-container').appendChild(buttonContainer);
}

// Función para localizar al usuario
function locateUser() {
    if (!navigator.geolocation) {
        handleLocationError(false, map.getCenter(), "Tu navegador no soporta geolocalización.");
        return;
    }
    
    // Mostrar indicador de carga
    locationButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    locationButton.disabled = true;
    
    const geoOptions = {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 60000
    };
    
    navigator.geolocation.getCurrentPosition(
        geoSuccess,
        geoError,
        geoOptions
    );
}

// Función de éxito para geolocalización
function geoSuccess(position) {
    const pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude,
    };
    
    marker.position = pos;
    map.setCenter(pos);
    map.setZoom(16);
    
    updateCoordinates(pos);
    reverseGeocode(pos);
    
    // Restaurar botón
    locationButton.innerHTML = '<i class="fas fa-location-arrow"></i>';
    locationButton.disabled = false;
}

// Función de error para geolocalización
function geoError(error) {
    // Restaurar botón
    locationButton.innerHTML = '<i class="fas fa-location-arrow"></i>';
    locationButton.disabled = false;
    
    let errorMessage;
    switch(error.code) {
        case error.PERMISSION_DENIED:
            errorMessage = "Permiso de ubicación denegado. Para usar esta función, por favor habilita los permisos de ubicación en tu navegador.";
            break;
        case error.POSITION_UNAVAILABLE:
            errorMessage = "La información de ubicación no está disponible. Verifica tu conexión a internet o GPS.";
            break;
        case error.TIMEOUT:
            errorMessage = "La solicitud de ubicación ha expirado. Por favor, intenta nuevamente.";
            break;
        default:
            errorMessage = "Error desconocido al obtener la ubicación.";
            break;
    }
    
    handleLocationError(true, map.getCenter(), errorMessage);
}

// Función para mostrar mensajes de geolocalización
function showGeolocationMessage(message, type) {
    // Eliminar mensajes anteriores
    const existingMessage = document.getElementById('geolocation-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    // Crear nuevo mensaje
    const messageDiv = document.createElement('div');
    messageDiv.id = 'geolocation-message';
    messageDiv.className = `alert alert-${type} alert-dismissible fade show`;
    messageDiv.style.position = 'absolute';
    messageDiv.style.top = '60px';
    messageDiv.style.right = '10px';
    messageDiv.style.zIndex = '1000';
    messageDiv.style.maxWidth = '300px';
    messageDiv.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    `;
    
    document.getElementById('map-container').appendChild(messageDiv);
    
    // Auto-eliminar después de 7 segundos
    if (type === 'success' || type === 'info') {
        setTimeout(() => {
            if (document.getElementById('geolocation-message')) {
                document.getElementById('geolocation-message').remove();
            }
        }, 7000);
    }
}

// Función para manejar errores de geolocalización
function handleLocationError(browserHasGeolocation, pos, errorMessage) {
    showGeolocationMessage(errorMessage, "danger");
    
    // Centrar en la ubicación actual del mapa si falla
    marker.position = pos;
    updateCoordinates({
        lat: pos.lat,
        lng: pos.lng
    });
}

// Función para actualizar coordenadas en los campos
function updateCoordinates(coords) {
    let lat, lng;
    
    if (typeof coords.lat === 'function') {
        lat = coords.lat();
        lng = coords.lng();
    } else {
        lat = coords.lat;
        lng = coords.lng;
    }
    
    document.getElementById('latitud').value = lat;
    document.getElementById('longitud').value = lng;
}

// Función para geocodificación inversa
function reverseGeocode(latLng) {
    geocoder.geocode({ 'location': latLng }, function(results, status) {
        if (status === 'OK' && results[0]) {
            fillAddressFields(results[0]);
        }
    });
}

// Función para llenar campos de dirección automáticamente
function fillAddressFields(place) {
    const addressComponents = place.address_components;
    
    document.getElementById('direccion').value = place.formatted_address || '';
    
    addressComponents.forEach(component => {
        const types = component.types;
        
        if (types.includes('locality')) {
            document.getElementById('ciudad').value = component.long_name;
        } else if (types.includes('administrative_area_level_1')) {
            document.getElementById('provincia').value = component.long_name;
        } else if (types.includes('country')) {
            document.getElementById('pais').value = component.long_name;
        }
    });
}

// Manejar errores de autenticación de Google Maps
window.gm_authFailure = function() {
    console.error('Error de autenticación de Google Maps');
    document.getElementById('map').innerHTML = `
        <div class="alert alert-danger">
            <h5>Error de autenticación</h5>
            <p>Verifique que su API Key de Google Maps sea válida y tenga las APIs necesarias habilitadas.</p>
            <p class="small">APIs requeridas: Maps JavaScript API, Geocoding API</p>
        </div>
    `;
};

// Verificar si la geolocalización está disponible
document.addEventListener('DOMContentLoaded', function() {
    if (!navigator.geolocation) {
        const mapContainer = document.getElementById('map-container');
        const warningDiv = document.createElement('div');
        warningDiv.className = 'alert alert-warning';
        warningDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Tu navegador no soporta geolocalización.';
        mapContainer.parentNode.insertBefore(warningDiv, mapContainer.nextSibling);
    }
});

</script>

@else
<script>
// Código para cuando no hay API Key configurada
document.addEventListener('DOMContentLoaded', function() {
    console.log('API Key de Google Maps no configurada - Modo sin mapa');
    // Habilitar campos de latitud y longitud para edición manual
    document.getElementById('latitud').removeAttribute('readonly');
    document.getElementById('longitud').removeAttribute('readonly');
    
    document.getElementById('latitud').placeholder = 'Ingrese latitud manualmente';
    document.getElementById('longitud').placeholder = 'Ingrese longitud manualmente';
});
</script>
@endif

<script>
// Funciones para la interacción del formulario (sin dependencia de Google Maps)

// Manejar cambio de categoría para cargar subcategorías
document.getElementById('categoria_id').addEventListener('change', function() {
    const categoriaId = this.value;
    const subcategoriaSelect = document.getElementById('subcategoria_id');
    
    if (categoriaId) {
        fetch(`{{ url('admin/servicios') }}/${categoriaId}/subcategorias`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al cargar subcategorías');
                }
                return response.json();
            })
            .then(data => {
                subcategoriaSelect.innerHTML = '<option value="">Seleccionar Subcategoría</option>';
                data.forEach(subcategoria => {
                    subcategoriaSelect.innerHTML += `<option value="${subcategoria.id}">${subcategoria.nombre}</option>`;
                });
            })
            .catch(error => {
                console.error('Error:', error);
                subcategoriaSelect.innerHTML = '<option value="">Error al cargar subcategorías</option>';
            });
    } else {
        subcategoriaSelect.innerHTML = '<option value="">Primero seleccione una categoría</option>';
    }
});

// Manejar cambio de tipo de precio
document.getElementById('tipo_precio').addEventListener('change', function() {
    const precioField = document.getElementById('precio-field');
    if (this.value === 'cotizacion' || this.value === 'variable') {
        precioField.style.display = 'none';
        document.getElementById('precio').value = '';
        document.getElementById('precio').removeAttribute('required');
    } else {
        precioField.style.display = 'block';
        document.getElementById('precio').setAttribute('required', 'required');
    }
});

// Inicializar estado del campo precio
document.addEventListener('DOMContentLoaded', function() {
    const tipoPrecio = document.getElementById('tipo_precio');
    if (tipoPrecio.value === 'cotizacion' || tipoPrecio.value === 'variable') {
        document.getElementById('precio-field').style.display = 'none';
        document.getElementById('precio').removeAttribute('required');
    }
    
    // Mostrar nombre de archivo seleccionado
    document.getElementById('imagen').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Seleccionar imagen...';
        document.querySelector('.custom-file-label').textContent = fileName;
    });
    
    // Si no hay API key, habilitar edición manual de coordenadas
    @if(empty(config('services.google.maps.maps_api_key')))
    document.getElementById('latitud').removeAttribute('readonly');
    document.getElementById('longitud').removeAttribute('readonly');
    document.getElementById('latitud').placeholder = 'Ingrese latitud manualmente';
    document.getElementById('longitud').placeholder = 'Ingrese longitud manualmente';
    @endif
});
</script>
@endpush