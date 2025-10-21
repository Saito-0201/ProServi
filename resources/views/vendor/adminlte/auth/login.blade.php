@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@php
    $loginUrl = View::getSection('login_url') ?? config('adminlte.login_url', 'login');
    $registerUrl = View::getSection('register_url') ?? config('adminlte.register_url', 'register');
    $passResetUrl = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset');

    if (config('adminlte.use_route_url', false)) {
        $loginUrl = $loginUrl ? route($loginUrl) : '';
        $registerUrl = $registerUrl ? route($registerUrl) : '';
        $passResetUrl = $passResetUrl ? route($passResetUrl) : '';
    } else {
        $loginUrl = $loginUrl ? url($loginUrl) : '';
        $registerUrl = $registerUrl ? url($registerUrl) : '';
        $passResetUrl = $passResetUrl ? url($passResetUrl) : '';
    }
@endphp

@section('auth_header', __('adminlte::adminlte.login_message'))

@section('auth_body')

    

    {{-- Mostrar mensaje de error personalizado 
    @if($errors->has('email'))
        <div class="alert alert-danger">
            <i class="icon fas fa-ban"></i>
            {{ $errors->first('email') }}
        </div>
    @endif --}}

    <form action="{{ $loginUrl }}" method="post">
        @csrf

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                placeholder="{{ __('adminlte::adminlte.password') }}">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Login field --}}
        <div class="row">
            <div class="col-7">
                <div class="icheck-primary" title="{{ __('adminlte::adminlte.remember_me_hint') }}">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label for="remember">
                        {{ __('adminlte::adminlte.remember_me') }}
                    </label>
                </div>
            </div>

            <div class="col-5">
                <button type=submit class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                    <span class="fas fa-sign-in-alt"></span>
                    {{ __('adminlte::adminlte.sign_in') }}
                </button>
            </div>
        </div>

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
                {{ __('Iniciar sesión con Google') }}
            </a>
        </div>
    </form>
@stop

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <style>
        .line {
            display: inline-block;
            width: 30%;
            height: 1px;
            background: #ddd;
            vertical-align: middle;
        }
        .btn-google {
            background: #dd4b39;
            color: white;
        }
        .btn-google:hover {
            background: #c23321;
            color: white;
        }
        .alert {
            margin-bottom: 1rem;
        }
    </style>
@stop

@section('auth_footer')
    {{-- Password reset link --}}
    @if($passResetUrl)
        <p class="my-0">
            <a href="{{ $passResetUrl }}">
                {{ __('adminlte::adminlte.i_forgot_my_password') }}
            </a>
        </p>
    @endif

    {{-- Register link --}}
    @if($registerUrl)
        <p class="my-0">
            <a href="{{ $registerUrl }}">
                {{ __('adminlte::adminlte.register_a_new_membership') }}
            </a>
        </p>
    @endif
@stop

@section('scripts')
<script>
// Procesar redirección después del login
document.addEventListener('DOMContentLoaded', function() {
    const redirectData = localStorage.getItem('proservi_redirect');
    if (redirectData) {
        console.log('Datos de redirección encontrados en login');
        
        // Después de un login exitoso
        setTimeout(() => {
            fetch('/api/check-auth')
                .then(response => response.json())
                .then(authData => {
                    if (authData.authenticated) {
                        console.log('Usuario autenticado después del login, procesando redirección...');
                        
                        const data = JSON.parse(redirectData);
                        if (data.intendedRoute === 'cliente.servicios.show') {
                            const clienteUrl = "/cliente/servicios/" + data.serviceId;
                            console.log('Redirigiendo usuario a:', clienteUrl);
                            
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