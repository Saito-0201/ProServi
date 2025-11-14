@extends('adminlte::page')

@section('content_header')
    <h1><b>Usuario/Modificar datos del usuario</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Actualice los datos del formulario</h3>
                </div>
                <div class="card-body">
                    <form action="{{url('/admin/usuarios',$usuario->id)}}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <!-- Nombre -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Nombre</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="name" 
                                               value="{{ old('name', $usuario->name) }}" 
                                               placeholder="Ingrese nombre..." required>
                                    </div>
                                    @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
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
                                        <input type="text" class="form-control" name="lastname" 
                                               value="{{ old('lastname', $usuario->lastname) }}" 
                                               placeholder="Ingrese apellido..." required>
                                    </div>
                                    @error('lastname')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Rol de Usuario -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Rol de Usuario</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                        </div>
                                        <select name="role" class="form-control" required id="roleSelect">
                                            <option value="">Seleccione un rol...</option>
                                            @foreach($roles as $role)
                                                @php
                                                    $userRole = $usuario->getRoleNames()->first();
                                                @endphp
                                                <option value="{{ $role->name }}" {{ old('role', $userRole) == $role->name ? 'selected' : '' }}>
                                                    {{ ucfirst($role->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('role')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
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
                                        <input type="email" class="form-control" name="email" 
                                               value="{{ old('email', $usuario->email) }}" 
                                               placeholder="Ingrese correo electrónico..." required>
                                    </div>
                                    @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                        </div>

                        <!-- Información específica para Cliente -->
                        <div id="clienteInfo" style="display: none;">
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h4 class="text-info"><i class="fas fa-user-circle"></i> Información del Cliente</h4>
                                    <hr>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="telefono_cliente">Teléfono</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="telefono_cliente" 
                                                   value="{{ old('telefono_cliente', $usuario->clienteInfo->telefono ?? '') }}" 
                                                   placeholder="Teléfono del cliente...">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="genero_cliente">Género</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                            </div>
                                            <select name="genero_cliente" class="form-control">
                                                <option value="">Seleccione género...</option>
                                                <option value="masculino" {{ old('genero_cliente', $usuario->clienteInfo->genero ?? '') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                                <option value="femenino" {{ old('genero_cliente', $usuario->clienteInfo->genero ?? '') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                                                <option value="otro" {{ old('genero_cliente', $usuario->clienteInfo->genero ?? '') == 'otro' ? 'selected' : '' }}>Otro</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información específica para Prestador -->
                        <div id="prestadorInfo" style="display: none;">
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h4 class="text-info"><i class="fas fa-briefcase"></i> Información del Prestador</h4>
                                    <hr>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="telefono_prestador">Teléfono</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="telefono_prestador" 
                                                   value="{{ old('telefono_prestador', $usuario->prestadorInfo->telefono ?? '') }}" 
                                                   placeholder="Teléfono del prestador...">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="genero_prestador">Género</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                            </div>
                                            <select name="genero_prestador" class="form-control">
                                                <option value="">Seleccione género...</option>
                                                <option value="masculino" {{ old('genero_prestador', $usuario->prestadorInfo->genero ?? '') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                                <option value="femenino" {{ old('genero_prestador', $usuario->prestadorInfo->genero ?? '') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                                                <option value="otro" {{ old('genero_prestador', $usuario->prestadorInfo->genero ?? '') == 'otro' ? 'selected' : '' }}>Otro</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="disponibilidad">Disponibilidad</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="disponibilidad" 
                                                   value="{{ old('disponibilidad', $usuario->prestadorInfo->disponibilidad ?? '') }}" 
                                                   placeholder="Ej: Lunes a Viernes 8:00-18:00">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="descripcion">Descripción</label>
                                        <textarea class="form-control" name="descripcion" rows="3" 
                                                  placeholder="Descripción del prestador...">{{ old('descripcion', $usuario->prestadorInfo->descripcion ?? '') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="experiencia">Experiencia</label>
                                        <textarea class="form-control" name="experiencia" rows="3" 
                                                  placeholder="Experiencia del prestador...">{{ old('experiencia', $usuario->prestadorInfo->experiencia ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="especialidades">Especialidades</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-star"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="especialidades" 
                                                   value="{{ old('especialidades', $usuario->prestadorInfo->especialidades ?? '') }}" 
                                                   placeholder="Ej: Plomería, Electricidad, Carpintería...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Contraseña -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Nueva Contraseña</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <input type="password" class="form-control" name="password" 
                                               placeholder="Dejar en blanco para no cambiar">
                                    </div>
                                    @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Confirmar Contraseña -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmar Contraseña</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <input type="password" class="form-control" name="password_confirmation" 
                                               placeholder="Confirme la contraseña...">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Actualizar
                                    </button>
                                    <a href="{{url('/admin/usuarios')}}" class="btn btn-secondary">
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

@section('css')
    <style>
        .text-danger {
            color: #dc3545;
        }
    </style>
@stop

@section('js')
    <script>
        // Mostrar/ocultar información específica según el rol seleccionado
        function toggleRoleInfo() {
            const roleSelect = document.getElementById('roleSelect');
            const clienteInfo = document.getElementById('clienteInfo');
            const prestadorInfo = document.getElementById('prestadorInfo');
            
            const selectedRole = roleSelect.value;
            
            // Ocultar todo primero
            clienteInfo.style.display = 'none';
            prestadorInfo.style.display = 'none';
            
            // Mostrar según el rol seleccionado
            if (selectedRole === 'Cliente') {
                clienteInfo.style.display = 'block';
            } else if (selectedRole === 'Prestador') {
                prestadorInfo.style.display = 'block';
            }
        }

        // Ejecutar al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            toggleRoleInfo();
            
            // Ejecutar cuando cambie el select de rol
            document.getElementById('roleSelect').addEventListener('change', toggleRoleInfo);
        });

        // Validación básica de contraseñas coincidentes
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = document.querySelector('input[name="password_confirmation"]').value;
            
            if (password && password !== confirmPassword) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
            }
        });
    </script>
@stop