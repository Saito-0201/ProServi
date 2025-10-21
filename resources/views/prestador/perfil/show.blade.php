@extends('layouts.prestador')

@section('title', 'Mi Perfil - PROSERVI')
@section('page_title', 'Mi perfil')

@section('prestador-content')
<div class="row">
    <div class="col-lg-4">
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body text-center py-4">
                <div class="profile-image-container mb-3 mx-auto">
                    <img src="{{ $info->foto_perfil ? asset('storage/' . $info->foto_perfil) : asset('uploads/images/default-user.png') }}" 
                         alt="{{ $user->name }}" class="profile-image" id="profile-image-preview">
                    <label for="foto-perfil" class="profile-image-edit" title="Cambiar foto">
                        <i class="fas fa-camera"></i>
                    </label>
                    <form id="foto-form" action="{{ route('prestador.perfil.update-foto') }}" method="POST" enctype="multipart/form-data" class="d-none">
                        @csrf
                        <input type="file" name="foto_perfil" id="foto-perfil" accept="image/*">
                    </form>
                </div>
                
                <h3 class="mb-1 text-dark">{{ $user->name }} {{ $user->lastname }}</h3>
                <p class="text-muted mb-3">
                    <i class="fas fa-briefcase me-1"></i>
                    Prestador desde {{ $user->getFechaCreacionFormateada() }}
                </p>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('prestador.perfil.edit') }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i> Editar perfil
                    </a>
                </div>

                @if(!($info->verificado ?? false))
                    <div class="d-grid gap-2 mt-2">
                        <a href="{{ route('prestador.verificacion.create') }}" class="btn btn-outline-success">
                            <i class="fas fa-shield-alt me-2"></i> Verificar perfil
                        </a>
                        <small class="text-muted text-center">Aumenta la confianza de tus clientes</small>
                    </div>
                @else
                    <div class="d-grid gap-2 mt-2">
                        <span class="btn btn-success disabled">
                            <i class="fas fa-check-circle me-2"></i> Perfil verificado
                        </span>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0 text-dark"><i class="fas fa-chart-bar me-2"></i>Estadísticas</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded">
                    <span class="text-muted">Servicios activos</span>
                    <strong class="text-primary fs-5">{{ $serviciosActivos }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                    <span class="text-muted">Verificado</span>
                    <strong class="text-primary">{{ ($info->verificado ?? false) ? 'Sí' : 'No' }}</strong>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0 text-dark"><i class="fas fa-user-circle me-2"></i>Información Personal</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 text-sm-end">
                        <strong class="text-muted">Nombre completo</strong>
                    </div>
                    <div class="col-sm-8">
                        {{ $user->name }} {{ $user->lastname }}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-4 text-sm-end">
                        <strong class="text-muted">Email</strong>
                    </div>
                    <div class="col-sm-8">
                        {{ $user->email }}
                        @if($user->email_verified_at)
                        <span class="badge bg-success ms-2">Verificado</span>
                        @else
                        <span class="badge bg-warning ms-2">No verificado</span>
                        @endif
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-4 text-sm-end">
                        <strong class="text-muted">Teléfono</strong>
                    </div>
                    <div class="col-sm-8">
                        {{ $info->telefono ?? 'No especificado' }}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-4 text-sm-end">
                        <strong class="text-muted">Género</strong>
                    </div>
                    <div class="col-sm-8">
                        {{ $info->genero ? ucfirst($info->genero) : 'No especificado' }}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-4 text-sm-end">
                        <strong class="text-muted">Especialidades</strong>
                    </div>
                    <div class="col-sm-8">
                        {{ $info->especialidades ?? 'No especificadas' }}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-4 text-sm-end">
                        <strong class="text-muted">Disponibilidad</strong>
                    </div>
                    <div class="col-sm-8">
                        {{ $info->disponibilidad ?? 'No especificada' }}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-4 text-sm-end">
                        <strong class="text-muted">Descripción</strong>
                    </div>
                    <div class="col-sm-8">
                        {{ $info->descripcion ?? 'No hay información' }}
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-4 text-sm-end">
                        <strong class="text-muted">Experiencia</strong>
                    </div>
                    <div class="col-sm-8">
                        {{ $info->experiencia ?? 'No especificada' }}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0 text-dark"><i class="fas fa-history me-2"></i>Actividad Reciente</h5>
            </div>
            <div class="card-body">
                @if($serviciosActivos > 0 || ($info->total_calificaciones ?? 0) > 0)
                <div class="timeline">
                    @if($serviciosActivos > 0)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Servicios activos</h6>
                            <p class="text-muted mb-1">Tienes {{ $serviciosActivos }} servicios publicados</p>
                            <small class="text-muted">Última actualización: {{ now()->format('d/m/Y') }}</small>
                        </div>
                    </div>
                    @endif
                    
                    @if(($info->total_calificaciones ?? 0) > 0)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Calificaciones recibidas</h6>
                            <p class="text-muted mb-1">Has recibido {{ $info->total_calificaciones }} calificaciones</p>
                            <small class="text-muted">Promedio: {{ $info->calificacion_promedio ?? '0' }}/5</small>
                        </div>
                    </div>
                    @endif
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Prestador en PROSERVI</h6>
                            <p class="text-muted mb-1">Te uniste a nuestra comunidad</p>
                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">Aún no tienes actividad</h6>
                    <p class="text-muted small">Comienza a publicar servicios para ver tu actividad aquí.</p>
                    <a href="{{ route('prestador.servicios.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Publicar primer servicio
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.profile-image-container {
    position: relative;
    display: inline-block;
}

.profile-image {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.profile-image-edit {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: #3b82f6;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.profile-image-edit:hover {
    background: #2563eb;
    transform: scale(1.1);
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 25px;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.timeline-content {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 3px solid #3b82f6;
}

.timeline-content h6 {
    color: #2d3748;
    margin-bottom: 5px;
}

@media (max-width: 576px) {
    .profile-image {
        width: 120px;
        height: 120px;
    }
    
    .col-sm-4.text-sm-end {
        text-align: left !important;
        margin-bottom: 5px;
    }
    
    .col-sm-8 {
        margin-bottom: 15px;
    }
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Subir foto de perfil
    const fotoPerfilInput = document.getElementById('foto-perfil');
    if (fotoPerfilInput) {
        fotoPerfilInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                // Mostrar previsualización
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-image-preview').setAttribute('src', e.target.result);
                };
                reader.readAsDataURL(this.files[0]);
                
                // Enviar formulario automáticamente
                document.getElementById('foto-form').submit();
            }
        });
    }
    
    // Mostrar notificación de éxito si existe
    @if(session('success'))
    // Crear y mostrar notificación
    const toast = document.createElement('div');
    toast.className = 'position-fixed top-0 end-0 p-3';
    toast.style.zIndex = '9999';
    toast.innerHTML = `
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">PROSERVI</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body text-success">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        </div>
    `;
    document.body.appendChild(toast);
    
    // Eliminar automáticamente después de 5 segundos
    setTimeout(function() {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 5000);
    @endif
});
</script>
@endpush