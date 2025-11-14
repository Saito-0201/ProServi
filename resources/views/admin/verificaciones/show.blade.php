@extends('adminlte::page')

@section('content_header')
    <h1><b>Detalles de Verificación</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Información de la Solicitud</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Datos del Prestador</h4>
                            <div class="form-group">
                                <label><strong>Nombre:</strong></label>
                                <p>{{ $verificacion->usuario->name }} {{ $verificacion->usuario->lastname }}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Email:</strong></label>
                                <p>{{ $verificacion->usuario->email }}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Fecha de Registro:</strong></label>
                                <p>{{ $verificacion->usuario->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h4>Datos de Verificación</h4>
                            <div class="form-group">
                                <label><strong>Número de Carnet:</strong></label>
                                <p>{{ $verificacion->numero_carnet ?? 'No especificado' }}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Fecha de Emisión:</strong></label>
                                <p>{{ $verificacion->fecha_emision ? $verificacion->fecha_emision->format('d/m/Y') : 'No especificada' }}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Estado:</strong></label>
                                <p>
                                    <span class="badge bg-{{ $verificacion->estado == 'aprobado' ? 'success' : ($verificacion->estado == 'rechazado' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($verificacion->estado) }}
                                    </span>
                                </p>
                            </div>
                            
                            <!-- Mostrar motivo de rechazo solo si está rechazado y tiene motivo -->
                            @if($verificacion->estado == 'rechazado' && $verificacion->motivo_rechazo)
                            <div class="form-group">
                                <label><strong>Motivo de Rechazo:</strong></label>
                                <div class="alert alert-danger" style="margin-bottom: 0;">
                                    <p class="mb-0"><i class="fas fa-exclamation-triangle"></i> {{ $verificacion->motivo_rechazo }}</p>
                                </div>
                            </div>
                            @endif
                            
                            @if($verificacion->fecha_verificacion)
                            <div class="form-group">
                                <label><strong>Fecha de Verificación:</strong></label>
                                <p>{{ $verificacion->fecha_verificacion->format('d/m/Y H:i') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Documentos Adjuntos</h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><strong>Carnet (Frente):</strong></label><br>
                                        @if($verificacion->ruta_imagen_carnet)
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalCarnetFrente">
                                                <i class="fas fa-eye"></i> Ver Imagen
                                            </button>
                                        @else
                                            <span class="text-muted">No disponible</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><strong>Carnet (Reverso):</strong></label><br>
                                        @if($verificacion->ruta_reverso_carnet)
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalCarnetReverso">
                                                <i class="fas fa-eye"></i> Ver Imagen
                                            </button>
                                        @else
                                            <span class="text-muted">No disponible</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><strong>Foto de Rostro:</strong></label><br>
                                        @if($verificacion->ruta_foto_cara)
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalFotoRostro">
                                                <i class="fas fa-eye"></i> Ver Imagen
                                            </button>
                                        @else
                                            <span class="text-muted">No disponible</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ route('admin.verificaciones.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver al Listado
                            </a>
                            <a href="{{ route('admin.verificaciones.edit', $verificacion->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Editar Verificación
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modales para las imágenes - TODOS CON Storage::url() -->
    @if($verificacion->ruta_imagen_carnet)
    <div class="modal fade" id="modalCarnetFrente" tabindex="-1" role="dialog" aria-labelledby="modalCarnetFrenteLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCarnetFrenteLabel">Carnet - Frente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ Storage::url($verificacion->ruta_imagen_carnet) }}" 
                         alt="Carnet Frente" 
                         class="img-fluid rounded"
                         style="max-height: 70vh;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <a href="{{ Storage::url($verificacion->ruta_imagen_carnet) }}" 
                       target="_blank" 
                       class="btn btn-primary">
                        <i class="fas fa-external-link-alt"></i> Abrir en nueva pestaña
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($verificacion->ruta_reverso_carnet)
    <div class="modal fade" id="modalCarnetReverso" tabindex="-1" role="dialog" aria-labelledby="modalCarnetReversoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCarnetReversoLabel">Carnet - Reverso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ Storage::url($verificacion->ruta_reverso_carnet) }}" 
                         alt="Carnet Reverso" 
                         class="img-fluid rounded"
                         style="max-height: 70vh;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <a href="{{ Storage::url($verificacion->ruta_reverso_carnet) }}" 
                       target="_blank" 
                       class="btn btn-primary">
                        <i class="fas fa-external-link-alt"></i> Abrir en nueva pestaña
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($verificacion->ruta_foto_cara)
    <div class="modal fade" id="modalFotoRostro" tabindex="-1" role="dialog" aria-labelledby="modalFotoRostroLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFotoRostroLabel">Foto de Rostro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ Storage::url($verificacion->ruta_foto_cara) }}" 
                         alt="Foto de Rostro" 
                         class="img-fluid rounded"
                         style="max-height: 70vh;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <a href="{{ Storage::url($verificacion->ruta_foto_cara) }}" 
                       target="_blank" 
                       class="btn btn-primary">
                        <i class="fas fa-external-link-alt"></i> Abrir en nueva pestaña
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal para rechazar -->
    @if($verificacion->estado == 'pendiente')
    <div class="modal fade" id="rechazarModal" tabindex="-1" role="dialog" aria-labelledby="rechazarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rechazarModalLabel">Rechazar Verificación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.verificaciones.rechazar', $verificacion->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            <strong>Advertencia:</strong> Esta acción no se puede deshacer. Por favor, ingrese el motivo del rechazo.
                        </div>
                        <div class="form-group">
                            <label for="motivo_rechazo"><strong>Motivo de Rechazo</strong></label><b> (*)</b>
                            <textarea class="form-control" name="motivo_rechazo" rows="4" 
                                      placeholder="Ingrese el motivo del rechazo (mínimo 10 caracteres)..." 
                                      required minlength="10"></textarea>
                            <small class="form-text text-muted">Mínimo 10 caracteres</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times"></i> Rechazar Verificación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@stop

@section('css')
    <style>
        .badge {
            font-size: 0.85em;
            margin: 2px;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeaa7;
            color: #856404;
        }
    </style>
@stop

@section('js')
    <script>
        // Validación del modal de rechazo
        $(document).ready(function() {
            $('#rechazarModal').on('show.bs.modal', function (event) {
                // Limpiar el textarea cada vez que se abre el modal
                $('textarea[name="motivo_rechazo"]').val('');
            });

            // Validación del formulario de rechazo
            $('form[action*="rechazar"]').on('submit', function(e) {
                const motivo = $('textarea[name="motivo_rechazo"]').val().trim();
                if (!motivo || motivo.length < 10) {
                    e.preventDefault();
                    alert('Por favor, ingrese un motivo de rechazo válido (mínimo 10 caracteres).');
                    return false;
                }
            });
        });
    </script>
@stop