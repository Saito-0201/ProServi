@extends('adminlte::page')

@section('title', 'Reporte de Servicios')

@section('content_header')
    <h1><i class="fas fa-chart-bar"></i> Reporte de Servicios</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtros del Reporte</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.reportes.servicios') }}" method="GET" id="filtrosForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select name="estado" id="estado" class="form-control">
                                <option value="">Todos los estados</option>
                                <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activos</option>
                                <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="categoria_id">Categoría</label>
                            <select name="categoria_id" id="categoria_id" class="form-control">
                                <option value="">Todas las categorías</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre_cat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="prestador_id">Prestador</label>
                            <select name="prestador_id" id="prestador_id" class="form-control">
                                <option value="">Todos los prestadores</option>
                                @foreach($prestadores as $prestador)
                                    <option value="{{ $prestador->id }}" {{ request('prestador_id') == $prestador->id ? 'selected' : '' }}>
                                        {{ $prestador->name }} {{ $prestador->lastname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="orden">Ordenar por</label>
                            <select name="orden" id="orden" class="form-control">
                                <option value="fecha_publicacion" {{ request('orden') == 'fecha_publicacion' ? 'selected' : '' }}>Fecha Publicación</option>
                                <option value="visitas" {{ request('orden') == 'visitas' ? 'selected' : '' }}>Visitas</option>
                                <option value="calificacion_promedio" {{ request('orden') == 'calificacion_promedio' ? 'selected' : '' }}>Calificación</option>
                                <option value="created_at" {{ request('orden') == 'created_at' ? 'selected' : '' }}>Fecha Creación</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_fin">Fecha Fin</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <select name="direccion" id="direccion" class="form-control">
                                <option value="desc" {{ request('direccion') == 'desc' ? 'selected' : '' }}>Descendente</option>
                                <option value="asc" {{ request('direccion') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="margin-top: 32px">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Aplicar Filtros
                            </button>
                            <a href="{{ route('admin.reportes.servicios') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $estadisticas['total'] }}</h3>
                    <p>Total Servicios</p>
                </div>
                <div class="icon">
                    <i class="fas fa-concierge-bell"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $estadisticas['activos'] }}</h3>
                    <p>Servicios Activos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $estadisticas['inactivos'] }}</h3>
                    <p>Servicios Inactivos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-pause-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>{{ number_format($estadisticas['promedio_calificacion'], 1) }}/5</h3>
                    <p>Calificación Promedio</p>
                </div>
                <div class="icon">
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Servicios</h3>
            <div class="card-tools">
                <form action="{{ route('admin.reportes.servicios.exportar') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="estado" value="{{ request('estado') }}">
                    <input type="hidden" name="categoria_id" value="{{ request('categoria_id') }}">
                    <input type="hidden" name="prestador_id" value="{{ request('prestador_id') }}">
                    <input type="hidden" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
                    <input type="hidden" name="fecha_fin" value="{{ request('fecha_fin') }}">
                    <input type="hidden" name="orden" value="{{ request('orden', 'fecha_publicacion') }}">
                    <input type="hidden" name="direccion" value="{{ request('direccion', 'desc') }}">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i> Exportar a Excel
                    </button>
                </form>
                <form action="{{ route('admin.reportes.servicios.exportar-pdf') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="estado" value="{{ request('estado') }}">
                    <input type="hidden" name="categoria_id" value="{{ request('categoria_id') }}">
                    <input type="hidden" name="prestador_id" value="{{ request('prestador_id') }}">
                    <input type="hidden" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
                    <input type="hidden" name="fecha_fin" value="{{ request('fecha_fin') }}">
                    <input type="hidden" name="orden" value="{{ request('orden', 'fecha_publicacion') }}">
                    <input type="hidden" name="direccion" value="{{ request('direccion', 'desc') }}">
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf"></i> Exportar a PDF
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="serviciosTable" class="table table-bordered table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th style="text-align: center">ID</th>
                            <th style="text-align: center">Título</th>
                            <th style="text-align: center">Prestador</th>
                            <th style="text-align: center">Categoría</th>
                            <th style="text-align: center">Estado</th>
                            <th style="text-align: center">Precio</th>
                            <th style="text-align: center">Visitas</th>
                            <th style="text-align: center">Calificación</th>
                            <th style="text-align: center">Fecha Publicación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($servicios as $servicio)
                            <tr>
                                <td style="text-align: center">{{ $servicio->id }}</td>
                                <td>
                                    <strong>{{ $servicio->titulo }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($servicio->descripcion, 50) }}</small>
                                </td>
                                <td>{{ $servicio->prestador->name }} {{ $servicio->prestador->lastname }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $servicio->categoria->nombre_cat }}</span>
                                    <br>
                                    <small>{{ $servicio->subcategoria->nombre }}</small>
                                </td>
                                <td style="text-align: center">
                                    <span class="badge badge-{{ $servicio->estado == 'activo' ? 'success' : 'danger' }}">
                                        {{ ucfirst($servicio->estado) }}
                                    </span>
                                </td>
                                <td style="text-align: center">
                                    @if($servicio->precio)
                                        Bs. {{ number_format($servicio->precio, 2) }}
                                    @else
                                        <span class="text-muted">Por cotización</span>
                                    @endif
                                    <br>
                                    <small class="text-muted">{{ $servicio->tipo_precio }}</small>
                                </td>
                                <td style="text-align: center">
                                    <span class="badge bg-info">{{ $servicio->visitas }}</span>
                                </td>
                                <td style="text-align: center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="text-warning">
                                            <i class="fas fa-star"></i> {{ number_format($servicio->calificacion_promedio, 1) }}
                                        </span>
                                        <small class="text-muted ml-1">({{ $servicio->total_calificaciones }})</small>
                                    </div>
                                </td>
                                <td style="text-align: center">{{ $servicio->fecha_publicacion->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <br>
                                    No se encontraron servicios con los filtros aplicados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .small-box {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .bg-purple {
            background-color: #6f42c1 !important;
            color: white;
        }
        .table th {
            background-color: #f8f9fa;
        }
        #serviciosTable_wrapper .dt-buttons {
            background-color: transparent;
            box-shadow: none;
            border: none;
            display: flex;
            justify-content: center; 
            gap: 10px; 
            margin-bottom: 15px; 
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Auto-submit form cuando cambien algunos filtros
            $('#estado, #categoria_id, #orden, #direccion').change(function() {
                $('#filtrosForm').submit();
            });
            
            // DataTable para servicios
            $('#serviciosTable').DataTable({
                "pageLength": 10,
                "language": {
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Servicios",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Servicios",
                    "infoFiltered": "(Filtrado de _MAX_ total Servicios)",
                    "lengthMenu": "Mostrar _MENU_ Servicios",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscador:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "ordering": true,
                "info": true,
                "searching": true,
            }).buttons().container().appendTo('#serviciosTable_wrapper .row:eq(0)');
            
            console.log('Reporte de servicios cargado correctamente');
        });
    </script>
@stop