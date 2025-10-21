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
                            <a href="{{ route('admin.verificaciones.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver al Listado
                            </a>
                            <a href="{{ route('admin.verificaciones.edit', $verificacion->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Editar Verificación
                            </a>
                            
                            @if($verificacion->estado == 'pendiente')
                                <form action="{{ route('admin.verificaciones.aprobar', $verificacion->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check"></i> Aprobar
                                    </button>
                                </form>
                                <form action="{{ route('admin.verificaciones.rechazar', $verificacion->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-times"></i> Rechazar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop