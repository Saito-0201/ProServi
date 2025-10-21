@extends('layouts.prestador')

@section('title', 'Detalles del Servicio - Prestador')
@section('header', 'Detalles del Servicio')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('prestador.index') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('prestador.servicios.index') }}">Mis Servicios</a></li>
    <li class="breadcrumb-item active">Detalles</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $servicio->titulo }}</h3>
                    <div class="card-tools">
                        <span class="badge badge-{{ $servicio->estado == 'activo' ? 'success' : 'danger' }}">
                            {{ ucfirst($servicio->estado) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @if($servicio->imagen)
                        <div class="text-center mb-4">
                            <img src="{{ asset('storage/' . $servicio->imagen) }}" alt="{{ $servicio->titulo }}" 
                                 class="img-fluid rounded" style="max-height: 300px;">
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Información del Servicio</h5>
                            <p><strong>Categoría:</strong> {{ $servicio->categoria->nombre_cat }}</p>
                            <p><strong>Subcategoría:</strong> {{ $servicio->subcategoria->nombre }}</p>
                            <p><strong>Tipo de precio:</strong> 
                                @switch($servicio->tipo_precio)
                                    @case('fijo') Fijo @break
                                    @case('cotizacion') A convenir @break
                                    @case('variable') Variable @break
                                    @case('diario') Diario @break
                                    @case('por_servicio') Por servicio @break
                                @endswitch
                            </p>
                            
                            @if($servicio->precio)
                                <p><strong>Precio:</strong> Bs. {{ number_format($servicio->precio, 2) }}</p>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Ubicación</h5>
                            <p><strong>Dirección:</strong> {{ $servicio->direccion }}</p>
                            <p><strong>Ciudad:</strong> {{ $servicio->ciudad }}</p>
                            <p><strong>Provincia:</strong> {{ $servicio->provincia }}</p>
                            <p><strong>País:</strong> {{ $servicio->pais }}</p>
                            @if($servicio->latitud && $servicio->longitud)
                                <div id="map" style="height: 200px; width: 100%;" class="mt-2 rounded border"></div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5>Descripción</h5>
                        <p>{{ $servicio->descripcion }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Estadísticas</h3>
                </div>
                <div class="card-body">
                    <p><strong>Visitas:</strong> {{ $servicio->visitas }}</p>
                    <p><strong>Publicado:</strong> {{ $servicio->created_at->format('d/m/Y') }}</p>
                    <p><strong>Última actualización:</strong> {{ $servicio->updated_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if($servicio->latitud && $servicio->longitud && config('services.google.maps.maps_api_key'))
    <script>
        function initMap() {
            const location = { lat: {{ $servicio->latitud }}, lng: {{ $servicio->longitud }} };
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: location,
            });
            new google.maps.Marker({
                position: location,
                map: map,
            });
        }
    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps.maps_api_key') }}&callback=initMap">
    </script>
    @endif
@endpush