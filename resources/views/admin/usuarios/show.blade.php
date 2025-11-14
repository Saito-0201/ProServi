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
                        <!-- Rol de Usuario -->
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

                    <!-- Información específica según el rol -->
                    @if($usuario->hasRole('Cliente') && $usuario->clienteInfo)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h4 class="text-info"><i class="fas fa-user-circle"></i> Información del Cliente</h4>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" class="form-control" value="{{ $usuario->clienteInfo->telefono ?? 'No registrado' }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="genero">Género</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                        </div>
                                        <input type="text" class="form-control" value="{{ ucfirst($usuario->clienteInfo->genero ?? 'No especificado') }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="foto">Foto de Perfil</label>
                                    <div class="input-group mb-3">
                                        @if($usuario->clienteInfo && $usuario->clienteInfo->foto_perfil)
                                            <img src="{{ asset('storage/' . $usuario->clienteInfo->foto_perfil) }}" alt="Foto" class="img-thumbnail" style="max-height: 100px;">
                                        @else
                                            <span class="text-muted">Sin foto</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($usuario->hasRole('Prestador') && $usuario->prestadorInfo)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h4 class="text-info"><i class="fas fa-briefcase"></i> Información del Prestador</h4>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" class="form-control" value="{{ $usuario->prestadorInfo->telefono ?? 'No registrado' }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="genero">Género</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                        </div>
                                        <input type="text" class="form-control" value="{{ ucfirst($usuario->prestadorInfo->genero ?? 'No especificado') }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="verificado">Estado de Verificación</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas {{ $usuario->prestadorInfo->verificado ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }}"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" value="{{ $usuario->prestadorInfo->verificado ? 'Verificado' : 'No verificado' }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea class="form-control" rows="3" disabled>{{ $usuario->prestadorInfo->descripcion ?? 'Sin descripción' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="experiencia">Experiencia</label>
                                    <textarea class="form-control" rows="3" disabled>{{ $usuario->prestadorInfo->experiencia ?? 'Sin experiencia registrada' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="especialidades">Especialidades</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-star"></i></span>
                                        </div>
                                        <input type="text" class="form-control" value="{{ $usuario->prestadorInfo->especialidades ?? 'No especificadas' }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="disponibilidad">Disponibilidad</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                        </div>
                                        <input type="text" class="form-control" value="{{ $usuario->prestadorInfo->disponibilidad ?? 'No especificada' }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="foto">Foto de Perfil</label>
                                    <div class="input-group mb-3">
                                        @if($usuario->prestadorInfo && $usuario->prestadorInfo->foto_perfil)
                                            <img src="{{ asset('storage/' . $usuario->prestadorInfo->foto_perfil) }}" alt="Foto" class="img-thumbnail" style="max-height: 100px;">
                                        @else
                                            <span class="text-muted">Sin foto</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de verificación -->
                        @if($usuario->verificacion)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h4 class="text-warning"><i class="fas fa-id-card"></i> Información de Verificación</h4>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estado_verificacion">Estado</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                @if($usuario->verificacion->estado == 'aprobado')
                                                    <i class="fas fa-check text-success"></i>
                                                @elseif($usuario->verificacion->estado == 'rechazado')
                                                    <i class="fas fa-times text-danger"></i>
                                                @else
                                                    <i class="fas fa-clock text-warning"></i>
                                                @endif
                                            </span>
                                        </div>
                                        <input type="text" class="form-control text-uppercase" value="{{ $usuario->verificacion->estado }}" disabled>
                                    </div>
                                </div>
                            </div>
                            @if($usuario->verificacion->numero_carnet)
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="numero_carnet">Número de Carnet</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        </div>
                                        <input type="text" class="form-control" value="{{ $usuario->verificacion->numero_carnet }}" disabled>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                    @endif
                    
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
                                <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Editar Usuario
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