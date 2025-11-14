@extends('layouts.prestador')

@section('title', 'Verificar Perfil - PROSERVI')
@section('page_title', 'Verificar mi perfil')

@section('prestador-content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Verificación de Identidad</h5>
            </div>
            <div class="card-body">
                <!-- Mostrar mensajes de error -->
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <!-- Mostrar mensaje si viene de un rechazo -->
                @if($fromRechazo)
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Solicitud anterior rechazada</h6>
                    <p class="mb-0">Tu solicitud anterior fue rechazada. Por favor, revisa los requisitos y envía nuevamente tu documentación.</p>
                </div>
                @endif

                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>¿Por qué verificarme?</h6>
                    <p class="mb-0">La verificación aumenta la confianza de los clientes en tus servicios. Los prestadores verificados reciben más solicitudes y aparecen mejor posicionados en las búsquedas.</p>
                </div>

                <form action="{{ route('prestador.verificacion.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Foto de la cara -->
                    <div class="mb-4">
                        <label class="form-label">Foto de tu rostro *</label>
                        <div class="card border-dashed">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-user-circle fa-3x text-muted"></i>
                                </div>
                                <p class="text-muted small">
                                    Toma una foto clara de tu rostro donde se vea bien tu cara. Asegúrate de tener buena iluminación.
                                </p>
                                <input type="file" class="form-control @error('foto_cara') is-invalid @enderror" 
                                       name="foto_cara" accept="image/*" required>
                                @error('foto_cara')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Carnet - Frente -->
                    <div class="mb-4">
                        <label class="form-label">Carnet de Identidad (Frente) *</label>
                        <div class="card border-dashed">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-id-card fa-3x text-muted"></i>
                                </div>
                                <p class="text-muted small">
                                    Foto frontal de tu carnet de identidad. Asegúrate de que todos los datos sean legibles.
                                </p>
                                <input type="file" class="form-control @error('carnet_frente') is-invalid @enderror" 
                                       name="carnet_frente" accept="image/*" required>
                                @error('carnet_frente')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Carnet - Reverso -->
                    <div class="mb-4">
                        <label class="form-label">Carnet de Identidad (Reverso) *</label>
                        <div class="card border-dashed">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-id-card fa-3x text-muted"></i>
                                </div>
                                <p class="text-muted small">
                                    Foto del reverso de tu carnet de identidad.
                                </p>
                                <input type="file" class="form-control @error('carnet_reverso') is-invalid @enderror" 
                                       name="carnet_reverso" accept="image/*" required>
                                @error('carnet_reverso')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Importante</h6>
                        <ul class="mb-0 small">
                            <li>Todas las fotos deben ser claras y legibles</li>
                            <li>Los archivos no deben superar los 5MB cada uno</li>
                            <li>Formatos aceptados: JPG, PNG</li>
                            <li>Tu información será tratada con confidencialidad</li>
                        </ul>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Enviar para verificación
                        </button>
                        <a href="{{ route('prestador.verificacion.estado') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver al estado
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.border-dashed {
    border: 2px dashed #dee2e6;
}
.border-dashed:hover {
    border-color: #3b82f6;
}
.alert {
    border-radius: 8px;
}
</style>
@endsection