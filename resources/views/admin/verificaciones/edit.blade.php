@extends('adminlte::page')

@section('content_header')
    <h1><b>Editar Verificación</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Modificar datos de verificación</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.verificaciones.update', $verificacion->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        
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
                            </div>
                            
                            <div class="col-md-6">
                                <h4>Datos de Verificación</h4>
                                
                                <!-- Campo de número de carnet (solo editable si no está rechazado) -->
                                <div class="form-group" id="numeroCarnetGroup">
                                    <label for="numero_carnet">Número de Carnet</label><b> (*)</b>
                                    <input type="text" class="form-control" name="numero_carnet" 
                                           value="{{ old('numero_carnet', $verificacion->numero_carnet) }}" 
                                           placeholder="Ingrese número de carnet" 
                                           id="numeroCarnetInput"
                                           {{ $verificacion->estado == 'rechazado' ? 'readonly' : 'required' }}>
                                    @error('numero_carnet')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    @if($verificacion->estado == 'rechazado')
                                    @endif
                                </div>
                                
                                <!-- Campo de fecha de emisión (solo editable si no está rechazado) -->
                                <div class="form-group" id="fechaEmisionGroup">
                                    <label for="fecha_emision">Fecha de Emisión</label><b> (*)</b>
                                    <input type="date" class="form-control" name="fecha_emision" 
                                           value="{{ old('fecha_emision', $verificacion->fecha_emision ? $verificacion->fecha_emision->format('Y-m-d') : '') }}" 
                                           id="fechaEmisionInput"
                                           {{ $verificacion->estado == 'rechazado' ? 'readonly' : 'required' }}>
                                    @error('fecha_emision')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    @if($verificacion->estado == 'rechazado')
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <label for="estado">Estado de Verificación</label><b> (*)</b>
                                    <select name="estado" class="form-control" id="estadoSelect" required>
                                        <option value="pendiente" {{ old('estado', $verificacion->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="aprobado" {{ old('estado', $verificacion->estado) == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                                        <option value="rechazado" {{ old('estado', $verificacion->estado) == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                    </select>
                                    @error('estado')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Campo de motivo de rechazo (solo visible cuando estado es rechazado) -->
                                <div class="form-group" id="motivoRechazoGroup" style="display: none;">
                                    <label for="motivo_rechazo">Motivo de Rechazo</label><b> (*)</b>
                                    <textarea class="form-control" name="motivo_rechazo" 
                                              rows="3" 
                                              placeholder="Ingrese el motivo del rechazo..."
                                              id="motivoRechazoInput">{{ old('motivo_rechazo', $verificacion->motivo_rechazo) }}</textarea>
                                    @error('motivo_rechazo')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
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
                                <div class="form-group">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save"></i> Actualizar Verificación
                                    </button>
                                    <a href="{{ route('admin.verificaciones.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modales para las imágenes -->
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
@stop

@section('js')
    <script>
        // Función para mostrar/ocultar el campo de motivo de rechazo y controlar campos editables
        function toggleCamposPorEstado() {
            const estadoSelect = document.getElementById('estadoSelect');
            const motivoGroup = document.getElementById('motivoRechazoGroup');
            const motivoInput = document.getElementById('motivoRechazoInput');
            const numeroCarnetInput = document.getElementById('numeroCarnetInput');
            const fechaEmisionInput = document.getElementById('fechaEmisionInput');
            
            if (estadoSelect.value === 'rechazado') {
                // Mostrar motivo de rechazo y hacerlo requerido
                motivoGroup.style.display = 'block';
                motivoInput.required = true;
                
                // Hacer campos de carnet y fecha de solo lectura
                numeroCarnetInput.readOnly = true;
                numeroCarnetInput.required = false;
                fechaEmisionInput.readOnly = true;
                fechaEmisionInput.required = false;
                
                // Cambiar estilo para indicar que no son editables
                numeroCarnetInput.style.backgroundColor = '#e9ecef';
                fechaEmisionInput.style.backgroundColor = '#e9ecef';
                
            } else {
                // Ocultar motivo de rechazo
                motivoGroup.style.display = 'none';
                motivoInput.required = false;
                
                // Hacer campos de carnet y fecha editables
                numeroCarnetInput.readOnly = false;
                numeroCarnetInput.required = true;
                fechaEmisionInput.readOnly = false;
                fechaEmisionInput.required = true;
                
                // Restaurar estilo normal
                numeroCarnetInput.style.backgroundColor = '';
                fechaEmisionInput.style.backgroundColor = '';
                
                // Limpiar el campo de motivo si no es rechazado
                if (estadoSelect.value !== 'rechazado') {
                    motivoInput.value = '';
                }
            }
        }

        // Ejecutar al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            toggleCamposPorEstado();
            
            // Escuchar cambios en el select de estado
            document.getElementById('estadoSelect').addEventListener('change', toggleCamposPorEstado);
        });

        // Validación básica del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const estado = document.querySelector('select[name="estado"]').value;
            const motivoRechazo = document.querySelector('textarea[name="motivo_rechazo"]');
            
            // Solo validar campos de carnet y fecha si no está rechazado
            if (estado !== 'rechazado') {
                const numeroCarnet = document.querySelector('input[name="numero_carnet"]').value;
                const fechaEmision = document.querySelector('input[name="fecha_emision"]').value;
                
                if (!numeroCarnet || !fechaEmision) {
                    e.preventDefault();
                    alert('Por favor, complete todos los campos obligatorios');
                    return;
                }
            }
            
            // Validar motivo de rechazo si el estado es rechazado
            if (estado === 'rechazado' && (!motivoRechazo.value || motivoRechazo.value.trim() === '')) {
                e.preventDefault();
                alert('Por favor, ingrese el motivo del rechazo');
                return;
            }
        });
    </script>
@stop