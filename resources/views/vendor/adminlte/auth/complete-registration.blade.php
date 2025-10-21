@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Completa tu Registro</h5>
                </div>

                <div class="card-body">
                    <div class="mb-4 text-center">
                        @if(session('temp_user.avatar'))
                            <img src="{{ session('temp_user.avatar') }}" class="rounded-circle mb-3" width="100" alt="Foto de Google">
                        @endif
                        <h4>{{ session('temp_user.name') }} {{ session('temp_user.lastname') }}</h4>
                        <p class="text-muted">{{ session('temp_user.email') }}</p>
                    </div>

                    <form method="POST" action="{{ route('complete.registration.submit') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="form-label">Rol</label>
                            <select name="role" class="form-select" required>
                                <option value="" disabled selected>Selecciona tu rol</option>
                                <option value="Cliente">Cliente (Busco servicios)</option>
                                <option value="Prestador">Prestador (Ofrezco servicios)</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check-circle me-2"></i> Continuar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection