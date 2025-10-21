@extends('layouts.cliente')

@section('title', 'Editar Perfil - PROSERVI')
@section('page_title','Mi perfil')

@section('cliente-content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i>Editar Perfil</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('cliente.perfil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <div class="profile-image-container mb-3 mx-auto">
                                <img src="{{ $clienteInfo->foto_perfil ? asset('storage/' . $clienteInfo->foto_perfil) : asset('uploads/images/default-user.png') }}" 
                                     alt="{{ $user->name }}" class="profile-image" id="profile-image-preview">
                                <label for="foto_perfil" class="profile-image-edit">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" name="foto_perfil" id="foto_perfil" class="d-none" accept="image/*">
                            </div>
                            <small class="text-muted">Formatos: JPG, PNG. Máx: 12MB</small>
                            @error('foto_perfil')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            
                            @if($clienteInfo->foto_perfil)
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="event.preventDefault(); document.getElementById('remove-foto-form').submit();">
                                    <i class="fas fa-trash me-1"></i> Eliminar foto
                                </button>
                            </div>
                            @endif
                        </div>
                        
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nombre *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="lastname" class="form-label">Apellido *</label>
                                        <input type="text" class="form-control @error('lastname') is-invalid @enderror" 
                                               id="lastname" name="lastname" value="{{ old('lastname', $user->lastname) }}" required>
                                        @error('lastname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono/Celular</label>
                                <input type="tel" class="form-control @error('telefono') is-invalid @enderror" 
                                       id="telefono" name="telefono" value="{{ old('telefono', $clienteInfo->telefono) }}"
                                       placeholder="Ej: +591 12345678">
                                @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="genero" class="form-label">Género</label>
                                <select class="form-select @error('genero') is-invalid @enderror" id="genero" name="genero">
                                    <option value="">Seleccionar género</option>
                                    <option value="masculino" {{ old('genero', $clienteInfo->genero) == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                    <option value="femenino" {{ old('genero', $clienteInfo->genero) == 'femenino' ? 'selected' : '' }}>Femenino</option>
                                    <option value="otro" {{ old('genero', $clienteInfo->genero) == 'otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('genero')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    {{-- Mostrar sección de cambio de contraseña solo para usuarios que NO se registraron con Google --}}
                    @if(!$user->google_id)
                    <hr>
                    
                    <h5 class="mb-3"><i class="fas fa-lock me-2"></i>Cambiar Contraseña</h5>
                    <p class="text-muted small mb-4">Deja estos campos en blanco si no deseas cambiar tu contraseña.</p>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Contraseña Actual</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password">
                                @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="password" class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="d-flex justify-content-between pt-3">
                        <a href="{{ route('cliente.perfil.show') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
                
                @if($clienteInfo->foto_perfil)
                <form id="remove-foto-form" action="{{ route('cliente.perfil.remove-foto') }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
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
    background: var(--ui-primary);
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
    background: var(--ui-primary-600);
    transform: scale(1.1);
}

.card {
    border-radius: 12px;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}
</style>
@endsection

@push('scripts')
<script>
$(document).on('change', '#foto_perfil', function() {
    if (this.files && this.files[0]) {
        const file = this.files[0];

        // Previsualización
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#profile-image-preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(file);

        // Subida AJAX
        const formData = new FormData();
        formData.append('foto_perfil', file);
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: '{{ route("cliente.perfil.update-foto") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                Toastify({
                    text: "Foto actualizada correctamente",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "var(--ui-primary)",
                }).showToast();
            },
            error: function(xhr) {
                Toastify({
                    text: "Error al subir la imagen: " + (xhr.responseJSON?.message || 'Error desconocido'),
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#dc3545",
                }).showToast();
            }
        });
    }
});
</script>
@endpush