@extends('layouts.prestador')

@section('title', 'Inicio - Prestador')
@section('page_title', 'Inicio')

@section('prestador-content')
    <div class="row">
        <!-- Estadísticas rápidas -->
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="card-title">{{ $totalServicios }}</h4>
                            <p class="card-text">Servicios Publicados</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-cone-striped fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="card-title">{{ $serviciosActivos }}</h4>
                            <p class="card-text">Servicios Activos</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-check-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="card-title">{{ number_format($ratingPromedio, 1) }}</h4>
                            <p class="card-text">Calificación Promedio</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-star fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="card-title">{{ $visitasTotales }}</h4>
                            <p class="card-text">Visitas Totales</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-eye fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Servicios Recientes -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Servicios Recientes</h5>
                    <a href="{{ route('prestador.servicios.index') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
                </div>
                <div class="card-body">
                    @if($serviciosRecientes->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($serviciosRecientes as $servicio)
                                <a href="{{ route('prestador.servicios.show', $servicio->id) }}" 
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div class="me-3">
                                            <img src="{{ $servicio->imagen ? asset('storage/' . $servicio->imagen) : asset('images/default-service.jpg') }}" 
                                                 alt="{{ $servicio->titulo }}" 
                                                 class="rounded" width="50" height="50" style="object-fit: cover;">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $servicio->titulo }}</h6>
                                            <small class="text-muted">{{ $servicio->categoria->nombre_cat }}</small>
                                        </div>
                                        <div>
                                            <span class="badge bg-{{ $servicio->estado == 'activo' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($servicio->estado) }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-cone-striped fs-1 text-muted"></i>
                            <p class="text-muted mt-2">No tienes servicios publicados aún</p>
                            <a href="{{ route('prestador.servicios.create') }}" class="btn btn-primary mt-2">
                                Crear primer servicio
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Calificaciones Recientes -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Calificaciones Recientes</h5>
                    <a href="{{ route('prestador.calificaciones.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="card-body">
                    @if($calificacionesRecientes->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($calificacionesRecientes as $calificacion)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between mb-2">
                                        <h6 class="mb-0">{{ $calificacion->servicio->titulo }}</h6>
                                        <small class="text-muted">{{ $calificacion->fecha->format('d/m/Y') }}</small>
                                    </div>
                                    <div class="mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $calificacion->puntuacion ? '-fill' : '' }} text-warning"></i>
                                        @endfor
                                    </div>
                                    <p class="mb-1">{{ $calificacion->comentario ?? 'Sin comentario' }}</p>
                                    <small class="text-muted">Por: {{ $calificacion->cliente->name }} {{ $calificacion->cliente->lastname }}</small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-star fs-1 text-muted"></i>
                            <p class="text-muted mt-2">No tienes calificaciones aún</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    

    <style>
        .stat-card {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
    </style>
@endsection