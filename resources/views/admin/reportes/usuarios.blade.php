@extends('adminlte::page')

@section('title', 'Reporte de Usuarios')

@section('content_header')
    <h1><i class="fas fa-users"></i> Reporte de Usuarios</h1>
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
            <form action="{{ route('admin.reportes.usuarios') }}" method="GET" id="filtrosForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="rol">Rol</label>
                            <select name="rol" id="rol" class="form-control">
                                <option value="">Todos los roles</option>
                                <option value="Administrador" {{ request('rol') == 'Administrador' ? 'selected' : '' }}>Administradores</option>
                                <option value="Prestador" {{ request('rol') == 'Prestador' ? 'selected' : '' }}>Prestadores</option>
                                <option value="Cliente" {{ request('rol') == 'Cliente' ? 'selected' : '' }}>Clientes</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="estado_verificacion">Estado Verificación</label>
                            <select name="estado_verificacion" id="estado_verificacion" class="form-control">
                                <option value="">Todos</option>
                                <option value="verificado" {{ request('estado_verificacion') == 'verificado' ? 'selected' : '' }}>Verificados</option>
                                <option value="no_verificado" {{ request('estado_verificacion') == 'no_verificado' ? 'selected' : '' }}>No Verificados</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="orden">Ordenar por</label>
                            <select name="orden" id="orden" class="form-control">
                                <option value="created_at" {{ request('orden') == 'created_at' ? 'selected' : '' }}>Fecha Registro</option>
                                <option value="name" {{ request('orden') == 'name' ? 'selected' : '' }}>Nombre</option>
                                <option value="email" {{ request('orden') == 'email' ? 'selected' : '' }}>Email</option>
                            </select>
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
                        <div class="form-group" style="margin-top: 32px">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Aplicar Filtros
                            </button>
                            <a href="{{ route('admin.reportes.usuarios') }}" class="btn btn-secondary">
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
        <div class="col-md-2">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $estadisticas['total'] }}</h3>
                    <p>Total Usuarios</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $estadisticas['administradores'] }}</h3>
                    <p>Administradores</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $estadisticas['prestadores'] }}</h3>
                    <p>Prestadores</p>
                </div>
                <div class="icon">
                    <i class="fas fa-briefcase"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $estadisticas['clientes'] }}</h3>
                    <p>Clientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-friends"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>{{ $estadisticas['prestadores_verificados'] }}</h3>
                    <p>Prestadores Verificados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="small-box bg-teal">
                <div class="inner">
                    <h3>{{ $estadisticas['nuevos_7_dias'] }}</h3>
                    <p>Nuevos (7 días)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Usuarios</h3>
            <div class="card-tools">
                <form action="{{ route('admin.reportes.usuarios.exportar') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="rol" value="{{ request('rol') }}">
                    <input type="hidden" name="estado_verificacion" value="{{ request('estado_verificacion') }}">
                    <input type="hidden" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
                    <input type="hidden" name="fecha_fin" value="{{ request('fecha_fin') }}">
                    <input type="hidden" name="orden" value="{{ request('orden', 'created_at') }}">
                    <input type="hidden" name="direccion" value="{{ request('direccion', 'desc') }}">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i> Exportar a Excel
                    </button>
                </form>

                <form action="{{ route('admin.reportes.usuarios.exportar-pdf') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="rol" value="{{ request('rol') }}">
                    <input type="hidden" name="estado_verificacion" value="{{ request('estado_verificacion') }}">
                    <input type="hidden" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
                    <input type="hidden" name="fecha_fin" value="{{ request('fecha_fin') }}">
                    <input type="hidden" name="orden" value="{{ request('orden', 'created_at') }}">
                    <input type="hidden" name="direccion" value="{{ request('direccion', 'desc') }}">
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf"></i> Exportar a PDF
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="usuariosTable" class="table table-bordered table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th style="text-align: center">ID</th>
                            <th style="text-align: center">Nombre Completo</th>
                            <th style="text-align: center">Email</th>
                            <th style="text-align: center">Rol</th>
                            <th style="text-align: center">Teléfono</th>
                            <th style="text-align: center">Email Verificado</th>
                            <th style="text-align: center">Estado Verificación</th>
                            <th style="text-align: center">Servicios</th>
                            <th style="text-align: center">Fecha Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                            @php
                                $rol = $usuario->roles->first()->name ?? 'Sin rol';
                                $telefono = $usuario->prestadorInfo->telefono ?? $usuario->clienteInfo->telefono ?? 'No registrado';
                                $estadoVerificacion = 'N/A';
                                $totalServicios = 0;
                                
                                // CORRECCIÓN: Verificar si existe prestadorInfo antes de acceder a verificado
                                if ($rol === 'Prestador' && $usuario->prestadorInfo) {
                                    $estadoVerificacion = $usuario->prestadorInfo->verificado ? 'Verificado' : 'No verificado';
                                    $totalServicios = $usuario->servicios()->count();
                                }

                                // Funciones helper para colores y nombres de roles
                                $getRolBadgeColor = function($rol) {
                                    $colores = [
                                        'Administrador' => 'danger',
                                        'Prestador' => 'warning',
                                        'Cliente' => 'primary'
                                    ];
                                    return $colores[$rol] ?? 'secondary';
                                };

                                $getRolNombre = function($rol) {
                                    $nombres = [
                                        'Administrador' => 'Administrador',
                                        'Prestador' => 'Prestador',
                                        'Cliente' => 'Cliente'
                                    ];
                                    return $nombres[$rol] ?? $rol;
                                };
                            @endphp
                            <tr>
                                <td style="text-align: center">{{ $usuario->id }}</td>
                                <td>
                                    <strong>{{ $usuario->name }} {{ $usuario->lastname }}</strong>
                                </td>
                                <td>{{ $usuario->email }}</td>
                                <td style="text-align: center">
                                    <span class="badge badge-{{ $getRolBadgeColor($rol) }}">
                                        {{ $getRolNombre($rol) }}
                                    </span>
                                </td>
                                <td style="text-align: center">{{ $telefono }}</td>
                                <td style="text-align: center">
                                    <span class="badge badge-{{ $usuario->email_verified_at ? 'success' : 'danger' }}">
                                        {{ $usuario->email_verified_at ? 'Sí' : 'No' }}
                                    </span>
                                </td>
                                <td style="text-align: center">
                                    @if($rol === 'Prestador' && $usuario->prestadorInfo)
                                        <span class="badge badge-{{ $usuario->prestadorInfo->verificado ? 'success' : 'warning' }}">
                                            {{ $estadoVerificacion }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">N/A</span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    @if($rol === 'Prestador')
                                        <span class="badge bg-info">{{ $totalServicios }}</span>
                                    @else
                                        <span class="badge badge-secondary">N/A</span>
                                    @endif
                                </td>
                                <td style="text-align: center">{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <br>
                                    No se encontraron usuarios con los filtros aplicados
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
        .bg-teal {
            background-color: #20c997 !important;
            color: white;
        }
        .table th {
            background-color: #f8f9fa;
        }
        #usuariosTable_wrapper .dt-buttons {
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
        $('#rol, #estado_verificacion, #orden, #direccion').change(function() {
            $('#filtrosForm').submit();
        });
        
        // DataTable para usuarios - CONFIGURACIÓN MEJORADA
        $('#usuariosTable').DataTable({
            "pageLength": 10,
            "language": {
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Usuarios",
                "infoEmpty": "Mostrando 0 a 0 de 0 Usuarios",
                "infoFiltered": "(Filtrado de _MAX_ total Usuarios)",
                "lengthMenu": "Mostrar _MENU_ Usuarios",
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
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        });
        
        console.log('Reporte de usuarios cargado correctamente');
    });
    </script>
@stop