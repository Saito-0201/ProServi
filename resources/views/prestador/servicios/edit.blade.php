{{-- resources/views/prestador/servicios/edit.blade.php --}}
@extends('layouts.prestador')
@section('title','Editar servicio — PROSERVI')
@section('page_title','Editar servicio')

@section('prestador-content')
<form class="card ui-card" method="POST" action="{{ route('prestador.servicios.update',$servicio) }}" enctype="multipart/form-data" id="servicioForm">
  @csrf @method('PUT')
  <div class="card-body">
    <div class="row g-3">

      <div class="col-md-6">
        <label class="form-label"><i class="bi bi-type me-1"></i> Título</label>
        <input type="text" name="titulo" class="form-control" value="{{ old('titulo',$servicio->titulo) }}" required maxlength="150">
        @error('titulo')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-6">
        <label class="form-label"><i class="bi bi-tags me-1"></i> Categoría</label>
        <select name="categoria_id" id="categoria_id" class="form-select" required>
          @foreach($categorias as $c)
            <option value="{{ $c->id }}" @selected(old('categoria_id',$servicio->categoria_id)==$c->id)>{{ $c->nombre_cat }}</option>
          @endforeach
        </select>
        @error('categoria_id')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-6">
        <label class="form-label"><i class="bi bi-tag me-1"></i> Subcategoría</label>
        <select name="subcategoria_id" id="subcategoria_id" class="form-select" required>
          @foreach($subcategorias as $sc)
            <option value="{{ $sc->id }}" @selected(old('subcategoria_id',$servicio->subcategoria_id)==$sc->id)>{{ $sc->nombre }}</option>
          @endforeach
        </select>
        @error('subcategoria_id')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-6">
        <label class="form-label"><i class="bi bi-cash-coin me-1"></i> Tipo de precio</label>
        <select name="tipo_precio" id="tipo_precio" class="form-select" required>
          <option value="fijo" @selected(old('tipo_precio',$servicio->tipo_precio)=='fijo')>Precio Fijo</option>
          <option value="cotizacion" @selected(old('tipo_precio',$servicio->tipo_precio)=='cotizacion')>Por Cotización</option>
          <option value="variable" @selected(old('tipo_precio',$servicio->tipo_precio)=='variable')>Variable</option>
          <option value="diario" @selected(old('tipo_precio',$servicio->tipo_precio)=='diario')>Precio Diario</option>
          <option value="por_servicio" @selected(old('tipo_precio',$servicio->tipo_precio)=='por_servicio')>Por Servicio</option>
        </select>
        @error('tipo_precio')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-6" id="precio-field">
        <label class="form-label"><i class="bi bi-currency-dollar me-1"></i> Precio (Bs)</label>
        <input type="number" step="0.01" name="precio" id="precio" class="form-control" value="{{ old('precio',$servicio->precio) }}" min="0" placeholder="0.00">
        @error('precio')<small class="text-danger">{{ $message }}</small>@enderror
        <small class="text-muted">Obligatorio para: Precio Fijo, Diario y Por Servicio</small>
      </div>

      <div class="col-12">
        <label class="form-label"><i class="bi bi-text-paragraph me-1"></i> Descripción</label>
        <textarea name="descripcion" rows="5" class="form-control" required>{{ old('descripcion',$servicio->descripcion) }}</textarea>
        @error('descripcion')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-6">
        <label class="form-label"><i class="bi bi-image me-1"></i> Imagen</label>
        <input type="file" name="imagen" class="form-control" accept="image/*">
        @if($servicio->imagen)
          <div class="mt-2">
            <img src="{{ asset('storage/'.$servicio->imagen) }}" alt="Imagen actual" class="img-thumbnail" style="max-height: 150px;">
            <small class="d-block text-muted">Imagen actual</small>
          </div>
        @endif
        @error('imagen')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-6">
        <label class="form-label"><i class="bi bi-toggle-on me-1"></i> Estado</label>
        <select name="estado" class="form-select">
          <option value="activo" @selected(old('estado',$servicio->estado)=='activo')>Activo</option>
          <option value="inactivo" @selected(old('estado',$servicio->estado)=='inactivo')>Inactivo</option>
        </select>
        @error('estado')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      {{-- Mapa interactivo de Google Maps --}}
      <div class="col-12">
        <label class="form-label"><i class="bi bi-geo me-1"></i> Seleccionar ubicación en el mapa</label>
        <div id="map-container" style="position: relative; height: 400px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 4px;">
          <div id="map" style="height: 100%; width: 100%;">
            @if(empty($google_maps_api_key))
              <div class="alert alert-warning h-100 d-flex align-items-center justify-content-center">
                <div class="text-center">
                  <i class="bi bi-map fa-3x mb-3"></i>
                  <h5>Google Maps no configurado</h5>
                  <p>Configure GOOGLE_MAPS_API_KEY en el archivo .env</p>
                </div>
              </div>
            @endif
          </div>
        </div>
        <small class="text-muted">Haz clic en el mapa para seleccionar la ubicación de tu servicio.</small>
      </div>

      {{-- Campos de ubicación que se llenarán automáticamente --}}
      <div class="col-md-6">
        <label class="form-label">Dirección</label>
        <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion',$servicio->direccion) }}" required>
        @error('direccion')<small class="text-danger">{{ $message }}</small>@enderror
      </div>
      
      <div class="col-md-4">
        <label class="form-label">Ciudad</label>
        <input type="text" name="ciudad" id="ciudad" class="form-control" value="{{ old('ciudad',$servicio->ciudad) }}" required>
        @error('ciudad')<small class="text-danger">{{ $message }}</small>@enderror
      </div>
      
      <div class="col-md-4">
        <label class="form-label">Provincia</label>
        <input type="text" name="provincia" id="provincia" class="form-control" value="{{ old('provincia',$servicio->provincia) }}" required>
        @error('provincia')<small class="text-danger">{{ $message }}</small>@enderror
      </div>
      
      <div class="col-md-4">
        <label class="form-label">País</label>
        <input type="text" name="pais" id="pais" class="form-control" value="{{ old('pais',$servicio->pais) }}">
        @error('pais')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-6">
        <label class="form-label"><i class="bi bi-compass me-1"></i> Latitud</label>
        <input type="text" name="latitud" id="latitud" class="form-control" value="{{ old('latitud',$servicio->latitud) }}" required>
        @error('latitud')<small class="text-danger">{{ $message }}</small>@enderror
      </div>
      
      <div class="col-md-6">
        <label class="form-label"><i class="bi bi-compass me-1"></i> Longitud</label>
        <input type="text" name="longitud" id="longitud" class="form-control" value="{{ old('longitud',$servicio->longitud) }}" required>
        @error('longitud')<small class="text-danger">{{ $message }}</small>@enderror
      </div>
    </div>
  </div>
  <div class="card-footer d-flex justify-content-end gap-2">
    <a href="{{ route('prestador.servicios.index') }}" class="btn btn-light">Cancelar</a>
    <button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> Actualizar</button>
  </div>
</form>
@endsection

@push('styles')
<style>
    #map-container {
        position: relative;
    }
    .location-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 1000;
    }
    .gm-style-iw {
        max-width: 250px !important;
    }
</style>
@endpush

@push('scripts')
  {{-- Subcategorías por categoría --}}
  <script>
    const categoriaSel = document.getElementById('categoria_id');
    const subcategoriaSel = document.getElementById('subcategoria_id');
    function loadSubs(catId, selectedId = null){
      if(!catId){ subcategoriaSel.innerHTML = '<option value="">Selecciona categoría</option>'; return; }
      fetch(`{{ url('prestador/servicios') }}/${catId}/subcategorias`)
        .then(r=>r.json())
        .then(list=>{
          subcategoriaSel.innerHTML = '<option value="">Seleccionar…</option>';
          list.forEach(s=>{
            const opt = new Option(s.nombre, s.id, false, selectedId && +selectedId === +s.id);
            subcategoriaSel.add(opt);
          });
        });
    }
    categoriaSel?.addEventListener('change', e=> loadSubs(e.target.value));
    if(categoriaSel?.value){ loadSubs(categoriaSel.value, '{{ old('subcategoria_id',$servicio->subcategoria_id) }}'); }
  </script>

  {{-- Manejo de tipo de precio --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipoPrecioSelect = document.getElementById('tipo_precio');
        const precioField = document.getElementById('precio-field');
        const precioInput = document.getElementById('precio');

        function togglePrecioField() {
            const tipoPrecio = tipoPrecioSelect.value;
            
            if (tipoPrecio === 'cotizacion' || tipoPrecio === 'variable') {
                precioField.style.display = 'none';
                precioInput.removeAttribute('required');
                // No limpiar el valor en edición para mantener datos existentes
            } else {
                precioField.style.display = 'block';
                precioInput.setAttribute('required', 'required');
            }
        }

        // Inicializar estado basado en el tipo de precio actual
        togglePrecioField();

        // Cambiar dinámicamente
        tipoPrecioSelect.addEventListener('change', togglePrecioField);

        // Validación antes de enviar el formulario
        document.getElementById('servicioForm').addEventListener('submit', function(e) {
            const tipoPrecio = tipoPrecioSelect.value;
            const precio = precioInput.value;

            if (['fijo', 'diario', 'por_servicio'].includes(tipoPrecio) && (!precio || precio <= 0)) {
                e.preventDefault();
                alert('Por favor ingrese un precio válido para el tipo de precio seleccionado.');
                precioInput.focus();
            }
        });
    });
  </script>

  {{-- Google Maps API --}}
  @if(!empty($google_maps_api_key))
  <script>
    // Variables globales para el mapa
    let map;
    let marker;
    let geocoder;
    let locationButton;

    // Función para cargar Google Maps API de forma óptima
    function loadGoogleMapsAPI() {
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key={{ $google_maps_api_key }}&libraries=places&callback=initMap`;
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }

    // Función principal de inicialización del mapa
    async function initMap() {
        console.log('Google Maps API cargada correctamente');
        
        // Coordenadas actuales del servicio
        const currentLocation = { 
            lat: parseFloat('{{ $servicio->latitud }}'), 
            lng: parseFloat('{{ $servicio->longitud }}') 
        };
        
        try {
            // Crear el mapa
            map = new google.maps.Map(document.getElementById('map'), {
                center: currentLocation,
                zoom: 16,
                streetViewControl: false,
                mapTypeControl: false,
                fullscreenControl: true,
                mapId: 'servicio_map'
            });

            geocoder = new google.maps.Geocoder();
            
            // Crear marcador
            marker = new google.maps.Marker({
                map: map,
                draggable: true,
                position: currentLocation,
                title: "Arrastre para ajustar la ubicación"
            });

            // Crear botón de geolocalización
            createLocationButton();
            
            // Inicializar coordenadas
            updateCoordinates(currentLocation);

            // Evento al arrastrar el marcador
            marker.addListener('dragend', function(event) {
                const position = marker.getPosition();
                updateCoordinates({
                    lat: position.lat(),
                    lng: position.lng()
                });
                reverseGeocode(position);
            });

            // Evento al hacer click en el mapa
            map.addListener('click', function(event) {
                marker.setPosition(event.latLng);
                updateCoordinates({
                    lat: event.latLng.lat(),
                    lng: event.latLng.lng()
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

    // Crear botón de geolocalización
    function createLocationButton() {
        // Crear contenedor para el botón
        const buttonContainer = document.createElement('div');
        buttonContainer.style.position = 'absolute';
        buttonContainer.style.bottom = '90px';
        buttonContainer.style.right = '15px';
        buttonContainer.style.zIndex = '1000';
        
        // Crear botón de geolocalización
        locationButton = document.createElement("button");
        locationButton.innerHTML = '<i class="bi bi-geo-alt"></i>';
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
        locationButton.innerHTML = '<i class="bi bi-arrow-clockwise"></i>';
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
        
        marker.setPosition(pos);
        map.setCenter(pos);
        map.setZoom(16);
        
        updateCoordinates(pos);
        reverseGeocode(pos);
        
        // Restaurar botón
        locationButton.innerHTML = '<i class="bi bi-geo-alt"></i>';
        locationButton.disabled = false;
    }

    // Función de error para geolocalización
    function geoError(error) {
        // Restaurar botón
        locationButton.innerHTML = '<i class="bi bi-geo-alt"></i>';
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
        marker.setPosition(pos);
        updateCoordinates({
            lat: pos.lat(),
            lng: pos.lng()
        });
    }

    // Función para actualizar coordenadas en los campos
    function updateCoordinates(coords) {
        document.getElementById('latitud').value = coords.lat;
        document.getElementById('longitud').value = coords.lng;
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
            warningDiv.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Tu navegador no soporta geolocalización.';
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
@endpush