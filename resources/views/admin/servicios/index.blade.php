@extends('adminlte::page')

@section('content_header')
    <h1><b>Listado de Servicios</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Servicios registrados</h3>

                    <div class="card-tools">
                        <a href="{{url('/admin/servicios/create')}}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Crear nuevo
                        </a>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-hover table-striped table-sm">
                        <thead>
                            <tr>
                                <th style="text-align: center">Nro</th>
                                <th style="text-align: center">Título</th>
                                <th style="text-align: center">Prestador</th>
                                <th style="text-align: center">Categoría</th>
                                <th style="text-align: center">Precio</th>
                                <th style="text-align: center">Ubicación</th>
                                <th style="text-align: center">Estado</th>
                                <th style="text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $contador = 1;
                            @endphp
                            @foreach($servicios as $servicio)
                                <tr>
                                    <td style="text-align: center">{{$contador++}}</td>
                                    <td>{{ $servicio->titulo }}</td>
                                    <td>{{ $servicio->prestador->name }} {{ $servicio->prestador->lastname }}</td>
                                    <td>{{ $servicio->categoria->nombre_cat }}</td>
                                    <td style="text-align: center">
                                        @if($servicio->tipo_precio == 'cotizacion')
                                            <span class="badge bg-info">Por cotización</span>
                                        @elseif($servicio->tipo_precio == 'variable')
                                            <span class="badge bg-warning">Variable</span>
                                        @else
                                            Bs {{ number_format($servicio->precio, 2) }}
                                        @endif
                                    </td>
                                    <td>{{ $servicio->ciudad }}, {{ $servicio->provincia }}</td>
                                    <td style="text-align: center">
                                        <span class="badge bg-{{ $servicio->estado == 'activo' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($servicio->estado) }}
                                        </span>
                                    </td>
                                    <td style="text-align: center">
                                        <div class="btn-group">
                                            <a href="{{url('/admin/servicios/'. $servicio->id) }}" 
                                               class="btn btn-info btn-sm" 
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{url('/admin/servicios/'. $servicio->id.'/edit') }}" 
                                               class="btn btn-success btn-sm" 
                                               title="Editar">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <form action="{{url('/admin/servicios',$servicio->id)}}" method="post"
                                              onclick="preguntar{{$servicio->id}}(event)" id="miFormulario{{$servicio->id}}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                        </form>
                                        <script>
                                            function preguntar{{$servicio->id}}(event) {
                                                event.preventDefault();
                                                Swal.fire({
                                                    title: '¿Desea eliminar este servicio?',
                                                    text: 'Esta acción no se puede deshacer',
                                                    icon: 'warning',
                                                    showDenyButton: true,
                                                    confirmButtonText: 'Eliminar',
                                                    confirmButtonColor: '#d33',
                                                    denyButtonColor: '#3085d6',
                                                    denyButtonText: 'Cancelar',
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        var form = $('#miFormulario{{$servicio->id}}');
                                                        form.submit();
                                                    }
                                                });
                                            }
                                        </script>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
@stop

@section('css')
    <style>

        #example1_wrapper .dt-buttons {
            background-color: transparent;
            box-shadow: none;
            border: none;
            display: flex;
            justify-content: center; 
            gap: 10px; 
            margin-bottom: 15px; 
        }

        #example1_wrapper .btn {
            color: #fff; 
            border-radius: 4px; 
            padding: 5px 15px; 
            font-size: 14px; 
        }

        .btn-danger { background-color: #dc3545; border: none; }
        .btn-success { background-color: #28a745; border: none; }
        .btn-info { background-color: #17a2b8; border: none; }
        .btn-warning { background-color: #ffc107; color: #212529; border: none; }
        .btn-default { background-color: #6e7176; color: #212529; border: none; }
        
        .badge {
            font-size: 0.85em;
            margin: 2px;
        }
        
        .btn-group .btn {
            margin: 0 2px;
        }
    </style>
@stop



@section('js')
    <script>
        $(function () {
            $("#example1").DataTable({
                "pageLength": 5,
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
                buttons: [
                    { text: '<i class="fas fa-copy"></i> COPIAR', extend: 'copy', className: 'btn btn-default' },
                    { text: '<i class="fas fa-file-pdf"></i> PDF', extend: 'pdf', className: 'btn btn-danger' },
                    { text: '<i class="fas fa-file-csv"></i> CSV', extend: 'csv', className: 'btn btn-info' },
                    { text: '<i class="fas fa-file-excel"></i> EXCEL', extend: 'excel', className: 'btn btn-success' },
                    { text: '<i class="fas fa-print"></i> IMPRIMIR', extend: 'print', className: 'btn btn-warning' }
                ]
            }).buttons().container().appendTo('#example1_wrapper .row:eq(0)');
        });
    </script>
@stop