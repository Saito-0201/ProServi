@extends('layouts.cliente')

@section('title', 'Buscar Servicios - PROSERVI')

@section('cliente-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Resultados de búsqueda</h1>
    <span class="text-muted">{{ $servicios->total() }} resultados encontrados</span>
</div>

<!-- Filtros de búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('cliente.servicios.buscar') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Palabra clave</label>
                <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Qué servicio buscas...">
            </div>
            
            <div class="col-md-3">
                <label class="form-label">Categoría</label>
                <select name="categoria" class="form-select">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre_cat }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-3">
                <label class="form-label">Ubicación</label>
                <input type="text" name="ubicacion" class="form-control" value="{{ request('ubicacion') }}" placeholder="Ciudad o provincia">
            </div>
            
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i> Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Resultados -->
@if($servicios->count() > 0)
<div class="row g-4">
    @foreach($servicios as $servicio)
    <div class="col-md-6 col-lg-4">
        <div class="service-card h-100">
            <div class="service-image" style="background-image: url('{{ $servicio->imagen ? asset('storage/' . $servicio->imagen) : asset('images/default-service.jpg') }}');"></div>
            <div class="service-content">
                <div class="d-flex align-items-center mb-3">
                    <div class="service-icon me-3">
                        <i class="fas fa-{{ $servicio->categoria->icono ?? 'tools' }}"></i>
                    </div>
                    <div>
                        <h3 class="h5 mb-1">{{ $servicio->titulo }}</h3>
                        <div class="rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $servicio->calificacion_promedio ? '' : '-empty' }}"></i>
                            @endfor
                            <span class="ms-2">({{ $servicio->calificaciones_count }})</span>
                        </div>
                    </div>
                </div>
                <p class="mb-3 text-muted">{{ Str::limit($servicio->descripcion, 100) }}</p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge bg-primary">{{ $servicio->categoria->nombre_cat }}</span>
                    <span class="service-price">
                        @if($servicio->tipo_precio == 'fijo')
                            Bs. {{ number_format($servicio->precio, 2) }}
                        @else
                            {{ ucfirst($servicio->tipo_precio) }}
                        @endif
                    </span>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('cliente.servicios.show', $servicio) }}" class="btn btn-primary btn-sm">
                        Ver detalles
                    </a>
                    @auth
                    <button class="btn btn-outline-danger btn-sm favorite-btn" data-service-id="{{ $servicio->id }}">
                        <i class="fas fa-heart"></i>
                    </button>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Paginación -->
<div class="d-flex justify-content-center mt-5">
    {{ $servicios->appends(request()->query())->links() }}
</div>
@else
<div class="text-center py-5">
    <div class="empty-state">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <h4 class="mb-3">No se encontraron resultados</h4>
        <p class="text-muted mb-4">Intenta ajustar tus filtros de búsqueda o explorar nuestras categorías.</p>
        <a href="{{ route('cliente.servicios.index') }}" class="btn btn-primary">
            <i class="fas fa-list me-2"></i> Ver todos los servicios
        </a>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Funcionalidad de favoritos
    $('.favorite-btn').click(function() {
        const serviceId = $(this).data('service-id');
        const button = $(this);
        
        $.ajax({
            url: '{{ route("cliente.favoritos.toggle") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                servicio_id: serviceId
            },
            success: function(response) {
                if (response.is_favorite) {
                    button.addClass('btn-danger').removeClass('btn-outline-danger');
                    button.html('<i class="fas fa-heart"></i>');
                } else {
                    button.removeClass('btn-danger').addClass('btn-outline-danger');
                    button.html('<i class="fas fa-heart"></i>');
                }
            }
        });
    });
});
</script>
@endpush