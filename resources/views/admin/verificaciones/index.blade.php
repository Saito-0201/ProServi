@extends('adminlte::page')

@section('content_header')
    <h1><b>Gestión de Verificaciones</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Solicitudes de Verificación</h3>
                </div>
                <div class="card-body">
                    <table id="verificaciones-table" class="table table-bordered table-hover table-striped table-sm">
                        <thead>
                            <tr>
                                <th style="text-align: center">Nro</th>
                                <th style="text-align: center">Prestador</th>
                                <th style="text-align: center">Email</th>
                                <th style="text-align: center">N° Carnet</th>
                                <th style="text-align: center">Fecha Solicitud</th>
                                <th style="text-align: center">Estado</th>
                                <th style="text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $contador = 1;
                            @endphp
                            @foreach($verificaciones as $verificacion)
                                <tr>
                                    <td style="text-align: center">{{ $contador++ }}</td>
                                    <td>{{ $verificacion->usuario->name }} {{ $verificacion->usuario->lastname }}</td>
                                    <td>{{ $verificacion->usuario->email }}</td>
                                    <td style="text-align: center">{{ $verificacion->numero_carnet ?? 'N/A' }}</td>
                                    <td style="text-align: center">{{ $verificacion->created_at->format('d/m/Y H:i') }}</td>
                                    <td style="text-align: center">
                                        <span class="badge bg-{{ $verificacion->estado == 'aprobado' ? 'success' : ($verificacion->estado == 'rechazado' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($verificacion->estado) }}
                                        </span>
                                    </td>
                                    <td style="text-align: center">
                                        <div class="btn-group" role="group" aria-label="Acciones">
                                            <a href="{{ route('admin.verificaciones.show', $verificacion->id) }}" 
                                               class="btn btn-info btn-sm" 
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.verificaciones.edit', $verificacion->id) }}" 
                                               class="btn btn-success btn-sm" 
                                               title="Editar verificación">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .badge {
            font-size: 0.85em;
            margin: 2px;
        }
    </style>
@stop

@section('js')
    <script>
        $(function () {
            $("#verificaciones-table").DataTable({
                "pageLength": 10,
                "language": {
                    "emptyTable": "No hay solicitudes de verificación",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ solicitudes",
                    "infoEmpty": "Mostrando 0 a 0 de 0 solicitudes",
                    "infoFiltered": "(Filtrado de _MAX_ total solicitudes)",
                    "lengthMenu": "Mostrar _MENU_ solicitudes",
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
                "order": [[4, 'desc']] // Ordenar por fecha de solicitud descendente
            });
        });
    </script>
@stop