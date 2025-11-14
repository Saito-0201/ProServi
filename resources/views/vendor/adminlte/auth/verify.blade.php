@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@section('auth_header', 'Verifica tu dirección de email')

@section('auth_body')

    @if(session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="alert alert-info">
        <strong>¡Importante!</strong> Antes de continuar, por favor revisa tu correo electrónico para el enlace de verificación.
    </div>

    <p>Si no recibiste el correo electrónico:</p>

    <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
            <strong>haz clic aquí para solicitar otro</strong>
        </button>
    </form>

    <div class="mt-4">
        <a href="{{ route('home') }}" class="btn btn-primary">
            Continuar
        </a>
        
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-secondary">
                Cerrar Sesión
            </button>
        </form>
    </div>

@stop