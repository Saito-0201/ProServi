@extends('adminlte::auth.auth-page', ['authType' => 'register'])

@php
    $loginUrl = View::getSection('login_url') ?? config('adminlte.login_url', 'login');
    $registerUrl = View::getSection('register_url') ?? config('adminlte.register_url', 'register');

    if (config('adminlte.use_route_url', false)) {
        $loginUrl = $loginUrl ? route($loginUrl) : '';
        $registerUrl = $registerUrl ? route($registerUrl) : '';
    } else {
        $loginUrl = $loginUrl ? url($loginUrl) : '';
        $registerUrl = $registerUrl ? url($registerUrl) : '';
    }
@endphp

@section('auth_header', __('adminlte::adminlte.register_message'))

@section('auth_body')

    

    <form action="{{ $registerUrl }}" method="post">
        @csrf

        {{-- Nombre --}}
        <div class="input-group mb-3">
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                value="{{ old('name') }}" placeholder="Nombre" autofocus required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Apellido --}}
        <div class="input-group mb-3">
            <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror" 
                value="{{ old('lastname') }}" placeholder="Apellido" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
            @error('lastname')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Email --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                value="{{ old('email') }}" placeholder="Correo electrónico" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Rol (Cliente o Prestador) --}}
        <div class="input-group mb-3">
            <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                <option value="" disabled selected>Selecciona tu rol</option>
                <option value="Cliente" {{ old('role') == 'Cliente' ? 'selected' : '' }}>Cliente (Busco servicios)</option>
                <option value="Prestador" {{ old('role') == 'Prestador' ? 'selected' : '' }}>Prestador (Ofrezco servicios)</option>
            </select>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-users"></span>
                </div>
            </div>
            @error('role')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Contraseña --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                placeholder="Contraseña" required minlength="8">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Confirmar contraseña --}}
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation" 
                class="form-control @error('password_confirmation') is-invalid @enderror" 
                placeholder="Confirmar contraseña" required minlength="8">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>

        {{-- Términos y condiciones --}}
        <div class="form-group mb-3">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="terms" id="terms" 
                    class="custom-control-input @error('terms') is-invalid @enderror" required>
                <label class="custom-control-label" for="terms">
                    Acepto los <a href="#" data-toggle="modal" data-target="#termsModal">términos y condiciones</a>
                </label>
                @error('terms')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        {{-- Botón de registro --}}
        <button type="submit" class="btn btn-primary btn-block">
            <span class="fas fa-user-plus"></span>
            Registrarse
        </button>
    </form>

    {{-- Botón de Google --}}
    <div class="text-center mb-4">
        <div class="my-3 position-relative">
            <hr>
            <span class="position-absolute bg-white px-2" style="top: -12px; left: 50%; transform: translateX(-50%);">
                O
            </span>
        </div>
        <a href="{{ route('login.google') }}" class="btn btn-danger btn-block">
            <i class="fab fa-google mr-2"></i>
            Registrarse con Google
        </a>
    </div>

    <!-- Modal de Términos y Condiciones -->
    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Términos y Condiciones</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('auth.terms')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Entendido</button>
                </div>
            </div>
        </div>
    </div>
@stop


@section('auth_footer')
    <p class="my-0">
        <a href="{{ $loginUrl }}">
            ¿Ya tienes una cuenta? Inicia sesión
        </a>
    </p>
@stop

@section('scripts')
<script>
// Procesar redirección después del registro
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si hay datos de redirección guardados
    const redirectData = localStorage.getItem('proservi_redirect');
    if (redirectData) {
        console.log('Datos de redirección encontrados en registro');
        
        // Después de un registro exitoso, esta página se recargará
        // Entonces verificamos si el usuario está autenticado
        setTimeout(() => {
            fetch('/api/check-auth')
                .then(response => response.json())
                .then(authData => {
                    if (authData.authenticated) {
                        console.log('Usuario autenticado después del registro, procesando redirección...');
                        
                        const data = JSON.parse(redirectData);
                        if (data.intendedRoute === 'cliente.servicios.show') {
                            const clienteUrl = "/cliente/servicios/" + data.serviceId;
                            console.log('Redirigiendo nuevo usuario a:', clienteUrl);
                            
                            localStorage.removeItem('proservi_redirect');
                            window.location.href = clienteUrl;
                        }
                    }
                });
        }, 1000);
    }
});
</script>
@endsection