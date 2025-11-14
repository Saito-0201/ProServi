@extends('layouts.prestador')

@section('title', 'Editar Perfil - PROSERVI')
@section('page_title', 'Editar perfil')

@section('prestador-content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i>Editar Perfil</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('prestador.perfil.update') }}" method="POST" id="profile-form">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <div class="profile-image-container mb-3 mx-auto">
                                <img src="{{ $info->foto_perfil ? asset('storage/' . $info->foto_perfil) : asset('uploads/images/default-user.png') }}" 
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
                            
                            @if($info->foto_perfil)
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeProfilePhoto()">
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
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required readonly>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">El email no se puede modificar por seguridad</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Celular/WhatsApp</label>
                                <input type="tel" class="form-control @error('telefono') is-invalid @enderror" 
                                       id="telefono" name="telefono" value="{{ old('telefono', $info->telefono) }}"
                                       placeholder="Ej: 76981578">
                                @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="genero" class="form-label">Género</label>
                                <select class="form-select @error('genero') is-invalid @enderror" id="genero" name="genero">
                                    <option value="">Seleccionar género</option>
                                    <option value="masculino" {{ old('genero', $info->genero) == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                    <option value="femenino" {{ old('genero', $info->genero) == 'femenino' ? 'selected' : '' }}>Femenino</option>
                                    <option value="otro" {{ old('genero', $info->genero) == 'otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('genero')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="especialidades" class="form-label">Especialidades</label>
                                <input type="text" class="form-control @error('especialidades') is-invalid @enderror" 
                                       id="especialidades" name="especialidades" value="{{ old('especialidades', $info->especialidades) }}"
                                       placeholder="Ej: Plomería, Electricidad, Carpintería">
                                <small class="text-muted">Separar por comas si tienes múltiples especialidades</small>
                                @error('especialidades')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="disponibilidad" class="form-label">Disponibilidad</label>
                                <input type="text" class="form-control @error('disponibilidad') is-invalid @enderror" 
                                       id="disponibilidad" name="disponibilidad" value="{{ old('disponibilidad', $info->disponibilidad) }}"
                                       placeholder="Ej: Lunes a Viernes 8:00-18:00">
                                @error('disponibilidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                  id="descripcion" name="descripcion" rows="3"
                                  placeholder="Breve descripción sobre ti y tus servicios">{{ old('descripcion', $info->descripcion) }}</textarea>
                        @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="experiencia" class="form-label">Experiencia</label>
                        <textarea class="form-control @error('experiencia') is-invalid @enderror" 
                                  id="experiencia" name="experiencia" rows="3"
                                  placeholder="Tu experiencia profesional y años de trabajo">{{ old('experiencia', $info->experiencia) }}</textarea>
                        @error('experiencia')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                        <a href="{{ route('prestador.perfil.show') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            <i class="fas fa-save me-2"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
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

.loading {
    opacity: 0.6;
    pointer-events: none;
}
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Subida de foto de perfil separada
$(document).on('change', '#foto_perfil', function() {
    if (this.files && this.files[0]) {
        const file = this.files[0];
        
        // Validar tamaño del archivo (12MB máximo)
        if (file.size > 12 * 1024 * 1024) {
            showToast('El archivo es demasiado grande. Máximo 12MB.', 'error');
            return;
        }

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
            url: '{{ route("prestador.perfil.update-foto") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#profile-image-preview').addClass('loading');
            },
            success: function(response) {
                showToast('Foto actualizada correctamente', 'success');
            },
            error: function(xhr) {
                let errorMessage = 'Error al subir la imagen';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = xhr.responseJSON.errors.foto_perfil[0];
                }
                showToast(errorMessage, 'error');
            },
            complete: function() {
                $('#profile-image-preview').removeClass('loading');
            }
        });
    }
});

// Envío del formulario principal
$('#profile-form').on('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = $('#submit-btn');
    const originalText = submitBtn.html();
    
    // Mostrar loading
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Guardando...');
    
    // Enviar formulario
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            showToast('Perfil actualizado correctamente', 'success');
            // Redirigir después de 1 segundos
            setTimeout(() => {
                window.location.href = '{{ route("prestador.perfil.show") }}';
            }, 1000);
        },
        error: function(xhr) {
            let errorMessage = 'Error al actualizar el perfil';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                // Mostrar el primer error de validación
                const firstError = Object.values(xhr.responseJSON.errors)[0][0];
                errorMessage = firstError;
            }
            showToast(errorMessage, 'error');
        },
        complete: function() {
            submitBtn.prop('disabled', false).html(originalText);
        }
    });
});

// Eliminar foto de perfil
function removeProfilePhoto() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Quieres eliminar tu foto de perfil?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        position: 'center', // El diálogo de confirmación se mantiene centrado
        backdrop: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("prestador.perfil.remove-foto") }}',
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#profile-image-preview').attr('src', '{{ asset("uploads/images/default-user.png") }}');
                    showToast('Foto de perfil eliminada correctamente', 'success');
                    $('.btn-outline-danger').closest('.mt-2').remove();
                },
                error: function(xhr) {
                    showToast('Error al eliminar la foto de perfil', 'error');
                }
            });
        }
    });
}

// Función para mostrar toasts en esquina superior derecha
function showToast(message, type = 'success') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: type,
        title: message
    });
}

// Función para mostrar alertas normales (centradas)
function showAlert(title, message, type = 'success') {
    Swal.fire({
        title: title,
        text: message,
        icon: type,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Aceptar',
        position: 'center'
    });
}

// Prevenir envío doble del formulario
$(document).on('submit', 'form', function() {
    $(this).find('button[type="submit"]').prop('disabled', true);
});
</script>
@endpush