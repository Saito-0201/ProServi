@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@section('auth_header', 'Verifica tu dirección de email')

@section('auth_body')

    @if(session('resent'))
        <div class="alert alert-success" role="alert">
            Un nuevo enlace de verificación ha sido enviado a tu dirección de correo electrónico.
        </div>
    @endif

    @if(session('not_verified'))
        <div class="alert alert-danger" role="alert">
            Aún no has verificado tu correo electrónico. Por favor, revisa tu bandeja de entrada y haz clic en el enlace de verificación.
        </div>
    @endif

    Antes de continuar, por favor revisa tu correo electrónico para el enlace de verificación.
    Si no recibiste el correo electrónico,

    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
            haz clic aquí para solicitar otro
        </button>
    </form>

    <div class="mt-4">
        <form method="GET" action="{{ route('verification.check') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                Ya verifiqué mi email
            </button>
        </form>
    </div>

@stop