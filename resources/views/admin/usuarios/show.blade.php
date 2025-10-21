@extends('adminlte::page')

@section('content_header')
    <h1><b>Usuario/Datos del usuario</b></h1>
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
                        <!-- Rol de Usuario (Reemplazando user_type) -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="role">Rol de Usuario</label><b> (*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ ucfirst($usuario->getRoleNames()->first() ?? 'Sin rol') }}" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Nombre -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Nombre</label><b> (*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $usuario->name }}" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Apellido -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lastname">Apellido</label><b> (*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user-friends"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $usuario->lastname }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label><b> (*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control" value="{{ $usuario->email }}" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Estado de verificación de email -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email_verified">Estado de Email</label><b> (*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                    </div>
                                    <input type="text" class="form-control" 
                                           value="{{ $usuario->email_verified_at ? 'Verificado' : 'No verificado' }}" 
                                           disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Fecha de Registro -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="created_at">Fecha de Registro</label><b> (*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $usuario->created_at->format('d/m/Y H:i') }}" disabled>
                                </div>
                            </div>
                        </div>

                        <!-- Última Actualización -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="updated_at">Última Actualización</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $usuario->updated_at->format('d/m/Y H:i') }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="{{ url('/admin/usuarios') }}" class="btn btn-secondary">
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