@extends('adminlte::page')

@section('content_header')
    <h1><b>Categoría/Detalles de la categoría</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Datos registrados</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Nombre de la Categoría -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre_cat">Nombre de Categoría</label><b> (*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $categoria->nombre_cat }}" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Estado -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="estado">Estado</label><b> (*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-toggle-{{ $categoria->estado == 'activa' ? 'on' : 'off' }}"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" 
                                           value="{{ ucfirst($categoria->estado) }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Descripción -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descripcion_cat">Descripción</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                                    </div>
                                    <textarea class="form-control" rows="3" disabled>{{ $categoria->descripcion_cat ?? 'Sin descripción' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Fechas -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="created_at">Fecha de Creación</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-plus"></i></span>
                                    </div>
                                    <input type="text" class="form-control" 
                                           value="{{ $categoria->created_at->format('d/m/Y H:i') }}" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="updated_at">Última Actualización</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                    </div>
                                    <input type="text" class="form-control" 
                                           value="{{ $categoria->updated_at->format('d/m/Y H:i') }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <strong>Copyright &copy; {{ date('Y') }} <a href="#">PROSERVI</a>.</strong> Todos los derechos reservados.
@stop

@section('css')
    <style>
        .input-group-text {
            min-width: 40px;
            justify-content: center;
        }
        textarea.form-control {
            resize: none;
        }
    </style>
@stop