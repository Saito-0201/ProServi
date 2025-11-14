@extends('layouts.cliente')

@section('title','Inicio — PROSERVI')
@section('page_title','Inicio')

@section('cliente-content')
  {{-- Bienvenida --}}
  <div class="card ui-card mb-4 border-0 shadow-sm">
    <div class="card-body d-flex align-items-center py-4">
      {{-- Foto de perfil con verificación segura --}}
      @php
        $fotoPerfil = $clienteInfo->foto_perfil ?? null;
        $fotoUrl = $fotoPerfil ? asset('storage/' . $fotoPerfil) : asset('uploads/images/default-user.png');
        
        // Verificar si la imagen existe
        if ($fotoPerfil) {
            $filePath = public_path('storage/' . $fotoPerfil);
            if (!file_exists($filePath)) {
                $fotoUrl = asset('uploads/images/default-user.png');
            }
        }
      @endphp
      
      {{-- Contenedor de imagen redonda --}}
      <div class="profile-avatar-container me-3">
        <img src="{{ $fotoUrl }}"
             class="profile-avatar" alt="Perfil">
      </div>
      <div class="flex-grow-1">
        <h4 class="mb-1 text-primary">¡Hola, {{ Auth::user()->name }}!</h4>
        <p class="text-muted mb-0">Aquí tienes un resumen de tu actividad en PROSERVI.</p>
      </div>
    </div>
  </div>

  {{-- Estadísticas rápidas --}}
  <div class="row mb-4">
    <div class="col-md-4 mb-3 mb-md-0">
      <div class="card ui-card border-0 shadow-sm h-100">
        <div class="card-body text-center p-4">
          <div class="stat-icon mb-3">
            <i class="bi bi-heart-fill text-danger fs-1"></i>
          </div>
          <h3 class="fw-bold text-dark mb-1">{{ $totalFavoritos }}</h3>
          <p class="text-muted mb-0">Favoritos</p>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3 mb-md-0">
      <div class="card ui-card border-0 shadow-sm h-100">
        <div class="card-body text-center p-4">
          <div class="stat-icon mb-3">
            <i class="bi bi-star-fill text-warning fs-1"></i>
          </div>
          <h3 class="fw-bold text-dark mb-1">{{ $totalCalificaciones }}</h3>
          <p class="text-muted mb-0">Calificaciones</p>
        </div>
      </div>
    </div>
    {{-- Eliminamos la columna de servicios vistos --}}
  </div>

  {{-- Recomendaciones personalizadas --}}
  <div class="card ui-card mb-4 border-0 shadow-sm">
    <div class="card-body">
      <h5 class="mb-4 fw-bold text-dark">Servicios populares</h5>
      
      @if($serviciosPopulares && count($serviciosPopulares) > 0)
      <div class="row g-4">
        @foreach($serviciosPopulares->take(4) as $servicio)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <div class="card border-0 shadow-sm h-100 service-card">
            <div class="service-image-container">
              <img src="{{ $servicio->imagen_url ?? asset('images/default-service.jpg') }}" 
                   class="service-image" alt="{{ $servicio->titulo }}">
              <div class="service-overlay">
                <button class="btn-service-action favorite-btn" data-service-id="{{ $servicio->id }}">
                  <i class="bi bi-heart{{ $servicio->esFavorito ? '-fill text-danger' : '' }}"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <h6 class="card-title fw-bold">{{ Str::limit($servicio->titulo, 30) }}</h6>
              <p class="text-muted small mb-2">{{ $servicio->categoria->nombre_cat ?? 'Sin categoría' }}</p>
              
              {{-- Calificaciones del SERVICIO (no del prestador) --}}
              <div class="d-flex align-items-center mb-2">
                @if($servicio->calificacion_promedio > 0)
                <div class="rating-badge">
                  <i class="bi bi-star-fill text-warning me-1"></i> 
                  <span class="rating-value">{{ number_format($servicio->calificacion_promedio, 1) }}</span>
                </div>
                <span class="text-muted small ms-2">({{ $servicio->total_calificaciones }})</span>
                @else
                <span class="badge bg-secondary">Sin calificaciones</span>
                @endif
              </div>

              {{-- Información del prestador --}}
              <div class="d-flex align-items-center mb-2">
                <small class="text-muted">
                  <i class="bi bi-person me-1"></i>
                  {{ $servicio->prestador->name ?? 'Prestador no disponible' }}
                  @if($servicio->prestador && $servicio->prestador->prestadorInfo && $servicio->prestador->prestadorInfo->verificado)
                    <i class="bi bi-patch-check-fill text-success ms-1" title="Prestador verificado"></i>
                  @endif
                </small>
              </div>

              <p class="card-text fw-bold text-primary mb-0">
                {{ $servicio->precio_formateado ?? 'Consultar precio' }}
              </p>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
              <a href="{{ route('cliente.servicios.show', $servicio->id) }}" class="btn btn-primary btn-sm w-100">
                Ver detalles
              </a>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      {{-- Botón para ver más servicios --}}
      <div class="text-center mt-4">
        <a href="{{ route('cliente.servicios.index') }}" class="btn btn-outline-primary">
          Ver todos los servicios
        </a>
      </div>
      @else
      <div class="text-center py-5 empty-services">
        <div class="empty-icon mb-3">
          <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
        </div>
        <h6 class="text-muted mb-2">No hay servicios disponibles</h6>
        <p class="text-muted small mb-3">Explora nuestros servicios disponibles</p>
        <a href="{{ route('cliente.servicios.index') }}" class="btn btn-primary">
          Explorar todos los servicios
        </a>
      </div>
      @endif
    </div>
  </div>

  {{-- Completar perfil --}}
  @php
    $perfilIncompleto = false;
    if ($clienteInfo) {
        $perfilIncompleto = empty($clienteInfo->telefono) || empty($clienteInfo->genero);
    } else {
        $perfilIncompleto = true;
    }
  @endphp
  
  @if($perfilIncompleto)
  <div class="card ui-card border-0 shadow-sm bg-primary bg-opacity-10">
    <div class="card-body">
      <div class="d-flex align-items-center">
        <div class="flex-shrink-0">
          <i class="bi bi-person-check fs-1 text-primary"></i>
        </div>
        <div class="flex-grow-1 ms-3">
          <h5 class="card-title text-primary">Completa tu perfil</h5>
          <p class="card-text mb-2">Añade más información a tu perfil para una mejor experiencia y recomendaciones personalizadas.</p>
          <a href="{{ route('cliente.perfil.edit') }}" class="btn btn-primary mt-2">Completar perfil</a>
        </div>
      </div>
    </div>
  </div>
  @endif

  {{-- Estilos CSS para el dashboard --}}
  <style>
    /* Estilos para el avatar redondo  */
    .profile-avatar-container {
      width: 64px;
      height: 64px;
      min-width: 64px;
      border-radius: 50%;
      overflow: hidden;
      border: 3px solid #f8f9fa;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .profile-avatar {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    
    /* Estilos para estadísticas */
    .stat-icon {
      width: 70px;
      height: 70px;
      margin: 0 auto;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .stat-icon.text-danger { background: rgba(220, 53, 69, 0.1); }
    .stat-icon.text-warning { background: rgba(255, 193, 7, 0.1); }
    .stat-icon.text-info { background: rgba(23, 162, 184, 0.1); }
    
    /* Estilos para servicios */
    .service-card {
      transition: all 0.3s ease;
    }
    
    .service-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .service-image-container {
      position: relative;
      overflow: hidden;
      border-radius: 12px 12px 0 0;
      height: 160px;
    }
    
    .service-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }
    
    .service-card:hover .service-image {
      transform: scale(1.05);
    }
    
    .service-overlay {
      position: absolute;
      top: 10px;
      right: 10px;
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .service-card:hover .service-overlay {
      opacity: 1;
    }
    
    .btn-service-action {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: none;
      background: rgba(255, 255, 255, 0.9);
      color: #6c757d;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }
    
    .btn-service-action:hover {
      background: #fff;
      color: #dc3545;
      transform: scale(1.1);
    }
    
    .rating-badge {
      background: rgba(255, 193, 7, 0.15);
      padding: 4px 8px;
      border-radius: 20px;
      font-size: 0.8rem;
      color: #f59e0b;
    }
    
    .empty-services {
      background: #f8fafc;
      border-radius: 16px;
    }
    
    /* Responsive para el avatar */
    @media (max-width: 768px) {
      .profile-avatar-container {
        width: 56px;
        height: 56px;
        min-width: 56px;
      }
      
      .card-body.py-4 {
        padding: 1.5rem 1rem !important;
      }
      
      .card-body .flex-grow-1 h4 {
        font-size: 1.25rem;
      }
      
      .card-body .flex-grow-1 p {
        font-size: 0.9rem;
      }
    }
    
    @media (max-width: 576px) {
      .profile-avatar-container {
        width: 48px;
        height: 48px;
        min-width: 48px;
      }
      
      .card-body.d-flex {
        flex-direction: column;
        text-align: center;
      }
      
      .profile-avatar-container {
        margin-right: 0 !important;
        margin-bottom: 1rem;
      }
    }
  </style>

  {{-- Script para favoritos --}}
  @auth
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Manejar clics en botones de favoritos
      document.querySelectorAll('.favorite-btn').forEach(button => {
        button.addEventListener('click', function(e) {
          e.preventDefault();
          const serviceId = this.dataset.serviceId;
          toggleFavorite(serviceId, this);
        });
      });
      
      function toggleFavorite(serviceId, button) {
        if (!serviceId) return;
        
        fetch('{{ route("cliente.favoritos.toggle") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ servicio_id: serviceId })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const icon = button.querySelector('i');
            if (data.is_favorite) {
              icon.classList.remove('bi-heart');
              icon.classList.add('bi-heart-fill', 'text-danger');
              showToast('Servicio agregado a favoritos', 'success');
            } else {
              icon.classList.remove('bi-heart-fill', 'text-danger');
              icon.classList.add('bi-heart');
              showToast('Servicio eliminado de favoritos', 'info');
            }
            
            // Actualizar contador de favoritos en la página
            const favoriteCountElement = document.querySelector('.stat-icon.text-danger + h3');
            if (favoriteCountElement) {
              const currentCount = parseInt(favoriteCountElement.textContent);
              favoriteCountElement.textContent = currentCount + (data.is_favorite ? 1 : -1);
            }
          } else {
            showToast('Error al actualizar favoritos', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error de conexión', 'error');
        });
      }

      // Función para mostrar notificaciones toast
      function showToast(message, type) {
        // Crear elemento toast si no existe
        if (!document.getElementById('toast-container')) {
          const toastContainer = document.createElement('div');
          toastContainer.id = 'toast-container';
          toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
          toastContainer.style.zIndex = '9999';
          document.body.appendChild(toastContainer);
        }
        
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
          <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        `;
        
        document.getElementById('toast-container').appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Eliminar el toast después de que se oculte
        toast.addEventListener('hidden.bs.toast', function() {
          toast.remove();
        });
      }
    });
  </script>
  @endauth
@endsection