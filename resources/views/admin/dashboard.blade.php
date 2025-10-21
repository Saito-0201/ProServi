@extends('adminlte::page')

@section('title', 'Dashboard - Panel de Administración')

@section('content_header')
    <h1><i class="fas fa-tachometer-alt"></i> Dashboard - Panel de Administración</h1>
    <hr>
@stop

@section('content')
    <!-- Estadísticas Principales -->
    <div class="row">
        <!-- Total de Usuarios -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalUsuarios }}</h3>
                    <p>Total de Usuarios</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('admin.usuarios.index') }}" class="small-box-footer">
                    Más información <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <!-- Prestadores -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalPrestadores }}</h3>
                    <p>Prestadores de Servicios</p>
                </div>
                <div class="icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <a href="{{ route('admin.usuarios.index') }}?rol=prestador" class="small-box-footer">
                    Más información <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <!-- Servicios Publicados -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalServicios }}</h3>
                    <p>Servicios Publicados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Más información <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <!-- Solicitudes de Verificación -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $solicitudesPendientes }}</h3>
                    <p>Solicitudes Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-double"></i>
                </div>
                <a href="{{ route('admin.verificaciones.index') }}" class="small-box-footer">
                    Revisar ahora <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Segunda Fila de Estadísticas -->
    <div class="row">
        <!-- Clientes -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $totalClientes }}</h3>
                    <p>Clientes Registrados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-friends"></i>
                </div>
                <a href="{{ route('admin.usuarios.index') }}?rol=cliente" class="small-box-footer">
                    Ver detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <!-- Calificaciones -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>{{ number_format($promedioCalificaciones, 1) }}/5</h3>
                    <p>Promedio de Calificaciones</p>
                </div>
                <div class="icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="small-box-footer">
                    {{ $totalCalificaciones }} evaluaciones
                </div>
            </div>
        </div>
        
        <!-- Categorías -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-teal">
                <div class="inner">
                    <h3>{{ $totalCategorias }}</h3>
                    <p>Categorías de Servicios</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tags"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Gestionar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <!-- Actividad Reciente -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-indigo">
                <div class="inner">
                    <h3>{{ $usuariosRecientes }}</h3>
                    <p>Nuevos Usuarios (7 días)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="small-box-footer">
                    {{ $serviciosRecientes }} nuevos servicios
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Columna Izquierda: Gráficos y Estadísticas -->
        <div class="col-md-8">
            <!-- Estado de Servicios -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-pie"></i> Distribución de Servicios</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="serviciosChart" width="400" height="200"></canvas>
                        </div>
                        <div class="col-md-6">
                            <canvas id="verificacionesChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Categorías -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-trophy"></i> Top 5 Categorías Más Populares</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Categoría</th>
                                    <th>Total de Servicios</th>
                                    <th>Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCategorias as $categoria)
                                <tr>
                                    <td>
                                        <i class="fas fa-folder text-primary"></i>
                                        {{ $categoria->nombre_cat }}
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $categoria->servicios_count }}</span>
                                    </td>
                                    <td>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-primary" style="width: {{ ($categoria->servicios_count / $totalServicios) * 100 }}%"></div>
                                        </div>
                                        <small>{{ number_format(($categoria->servicios_count / $totalServicios) * 100, 1) }}%</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Actividad Reciente -->
        <div class="col-md-4">
            <!-- Solicitudes Pendientes -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-clock text-warning"></i> Solicitudes Pendientes</h3>
                </div>
                <div class="card-body p-0">
                    @if($ultimasSolicitudes->count() > 0)
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            @foreach($ultimasSolicitudes as $solicitud)
                            <li class="item">
                                <div class="product-info">
                                    <a href="{{ route('admin.verificaciones.show', $solicitud->id) }}" class="product-title">
                                        {{ $solicitud->usuario->name }} {{ $solicitud->usuario->lastname }}
                                        <span class="badge badge-warning float-right">Pendiente</span>
                                    </a>
                                    <span class="product-description">
                                        <i class="fas fa-id-card"></i> {{ $solicitud->numero_carnet ?? 'Sin carnet' }}
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> 
                                            {{ $solicitud->created_at->diffForHumans() }}
                                        </small>
                                    </span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center p-3">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <p class="text-muted">No hay solicitudes pendientes</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.verificaciones.index') }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-list"></i> Ver todas las solicitudes
                    </a>
                </div>
            </div>

            <!-- Servicios Populares -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-fire text-danger"></i> Servicios Más Populares</h3>
                </div>
                <div class="card-body p-0">
                    @if($serviciosPopulares->count() > 0)
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            @foreach($serviciosPopulares as $servicio)
                            <li class="item">
                                <div class="product-info">
                                    <a href="#" class="product-title">
                                        {{ Str::limit($servicio->titulo, 30) }}
                                        <span class="badge badge-info float-right">{{ $servicio->visitas }}</span>
                                    </a>
                                    <span class="product-description">
                                        <i class="fas fa-user-tie"></i> {{ $servicio->prestador->name }}
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-tag"></i> {{ $servicio->categoria->nombre_cat }}
                                        </small>
                                    </span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center p-3">
                            <i class="fas fa-info-circle fa-2x text-info mb-2"></i>
                            <p class="text-muted">No hay servicios populares</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .small-box {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .small-box:hover {
            transform: translateY(-5px);
        }
        .bg-purple {
            background-color: #6f42c1 !important;
            color: white;
        }
        .bg-teal {
            background-color: #20c997 !important;
            color: white;
        }
        .products-list .item {
            border-bottom: 1px solid #f4f4f4;
            padding: 10px 0;
        }
        .products-list .item:last-child {
            border-bottom: none;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de Estado de Servicios
        const serviciosCtx = document.getElementById('serviciosChart').getContext('2d');
        const serviciosChart = new Chart(serviciosCtx, {
            type: 'doughnut',
            data: {
                labels: ['Activos', 'Inactivos'],
                datasets: [{
                    data: [{{ $serviciosActivos }}, {{ $serviciosInactivos }}],
                    backgroundColor: ['#28a745', '#dc3545'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Estado de Servicios'
                    }
                }
            }
        });

        // Gráfico de Verificaciones
        const verificacionesCtx = document.getElementById('verificacionesChart').getContext('2d');
        const verificacionesChart = new Chart(verificacionesCtx, {
            type: 'pie',
            data: {
                labels: ['Pendientes', 'Aprobadas', 'Rechazadas'],
                datasets: [{
                    data: [{{ $solicitudesPendientes }}, {{ $solicitudesAprobadas }}, {{ $solicitudesRechazadas }}],
                    backgroundColor: ['#ffc107', '#28a745', '#dc3545'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Estado de Verificaciones'
                    }
                }
            }
        });

        // Actualizar automáticamente cada 5 minutos
        setInterval(function() {
            location.reload();
        }, 300000); // 5 minutos

        console.log("Dashboard administrativo cargado correctamente");
    </script>
@stop