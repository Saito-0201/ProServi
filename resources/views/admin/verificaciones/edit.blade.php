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
                                
                                <div class="form-group">
                                    <label for="numero_carnet">Número de Carnet</label><b> (*)</b>
                                    <input type="text" class="form-control" name="numero_carnet" 
                                           value="{{ old('numero_carnet', $verificacion->numero_carnet) }}" 
                                           placeholder="Ingrese número de carnet" required>
                                    @error('numero_carnet')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="fecha_emision">Fecha de Emisión</label><b> (*)</b>
                                    <input type="date" class="form-control" name="fecha_emision" 
                                           value="{{ old('fecha_emision', $verificacion->fecha_emision ? $verificacion->fecha_emision->format('Y-m-d') : '') }}" 
                                           required>
                                    @error('fecha_emision')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="estado">Estado de Verificación</label><b> (*)</b>
                                    <select name="estado" class="form-control" required>
                                        <option value="pendiente" {{ old('estado', $verificacion->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="aprobado" {{ old('estado', $verificacion->estado) == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                                        <option value="rechazado" {{ old('estado', $verificacion->estado) == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                    </select>
                                    @error('estado')
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
                                                <a href="{{ asset('storage/' . $verificacion->ruta_imagen_carnet) }}" target="_blank" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Ver Imagen
                                                </a>
                                            @else
                                                <span class="text-muted">No disponible</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><strong>Carnet (Reverso):</strong></label><br>
                                            @if($verificacion->ruta_reverso_carnet)
                                                <a href="{{ asset('storage/' . $verificacion->ruta_reverso_carnet) }}" target="_blank" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Ver Imagen
                                                </a>
                                            @else
                                                <span class="text-muted">No disponible</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><strong>Foto de Rostro:</strong></label><br>
                                            @if($verificacion->ruta_foto_cara)
                                                <a href="{{ asset('storage/' . $verificacion->ruta_foto_cara) }}" target="_blank" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Ver Imagen
                                                </a>
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
@stop

@section('js')
    <script>
        // Validación básica del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const numeroCarnet = document.querySelector('input[name="numero_carnet"]').value;
            const fechaEmision = document.querySelector('input[name="fecha_emision"]').value;
            
            if (!numeroCarnet || !fechaEmision) {
                e.preventDefault();
                alert('Por favor, complete todos los campos obligatorios');
            }
        });
    </script>
@stop