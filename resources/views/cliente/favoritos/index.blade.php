@extends('layouts.cliente')

@section('title', 'Mis Favoritos — PROSERVI')
@section('page_title', 'Mis Favoritos')

@section('cliente-content')
<div class="container-fluid">
  {{-- Tarjeta principal de favoritos --}}
  <div class="card ui-card mb-4 border-0 shadow-sm">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 fw-bold text-dark">Mis Servicios Favoritos</h5>
        <span class="badge bg-primary fs-6">{{ $favoritos->total() }} servicios guardados</span>
      </div>
      
      @if($favoritos->count() > 0)
      <div class="row g-4">
        @foreach($favoritos as $favorito)
          @php
            $servicio = $favorito->servicio;
          @endphp
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card border-0 shadow-sm h-100 service-card">
              <div class="service-image-container">
                <img src="{{ $servicio->imagen_url ?? asset('images/default-service.jpg') }}" 
                     class="service-image" alt="{{ $servicio->titulo }}"
                     onerror="this.src='{{ asset('images/default-service.jpg') }}'">
                <div class="service-overlay">
                  <button class="btn-service-action favorite-btn active" 
                          data-service-id="{{ $servicio->id }}" 
                          title="Quitar de favoritos">
                    <i class="bi bi-heart-fill text-danger"></i>
                  </button>
                </div>
                {{-- Badge de categoría --}}
                @if($servicio->categoria)
                <div class="category-badge">
                  <span class="badge bg-primary">{{ $servicio->categoria->nombre_cat }}</span>
                </div>
                @endif
              </div>
              <div class="card-body">
                <h6 class="card-title fw-bold mb-2">{{ Str::limit($servicio->titulo, 30) }}</h6>
                
                {{-- Ubicación --}}
                <p class="text-muted small mb-2">
                  <i class="bi bi-geo-alt me-1"></i>{{ $servicio->ciudad }}, {{ $servicio->provincia }}
                </p>
                
                {{-- Calificaciones del SERVICIO --}}
                <div class="d-flex align-items-center mb-2">
                  @if($servicio->calificacion_promedio > 0)
                  <div class="rating-badge">
                    <i class="bi bi-star-fill text-warning me-1"></i> 
                    <span class="rating-value">{{ number_format($servicio->calificacion_promedio, 1) }}</span>
                  </div>
                  <span class="text-muted small ms-2">({{ $servicio->total_calificaciones }})</span>
                  @else
                  <span class="badge bg-secondary small">Sin calificaciones</span>
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

                {{-- Precio --}}
                <p class="card-text fw-bold text-primary mb-0">
                  {{ $servicio->precio_formateado ?? 'Consultar precio' }}
                </p>
              </div>
              <div class="card-footer bg-transparent border-0 pt-0">
                <div class="d-flex gap-2">
                  <a href="{{ route('cliente.servicios.show', $servicio->id) }}" 
                     class="btn btn-primary btn-sm flex-grow-1">
                    Ver detalles
                  </a>
                  <button class="btn btn-outline-danger btn-sm favorite-remove-btn" 
                          data-service-id="{{ $servicio->id }}"
                          title="Eliminar de favoritos">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      {{-- Paginación --}}
      @if($favoritos->hasPages())
      <div class="d-flex justify-content-center mt-5">
        {{ $favoritos->links() }}
      </div>
      @endif

      @else
      {{-- Estado vacío --}}
      <div class="text-center py-5 empty-favorites">
        <div class="empty-icon mb-3">
          <i class="bi bi-heart text-muted" style="font-size: 3rem;"></i>
        </div>
        <h4 class="text-muted mb-2">Aún no tienes favoritos</h4>
        <p class="text-muted mb-4">Guarda tus servicios favoritos para acceder a ellos rápidamente</p>
        <a href="{{ route('cliente.servicios.index') }}" class="btn btn-primary">
          <i class="bi bi-search me-2"></i> Explorar servicios
        </a>
      </div>
      @endif
    </div>
  </div>
</div>

{{-- Estilos CSS para la vista de favoritos --}}
<style>
.service-card {
  transition: all 0.3s ease;
  border: 1px solid #f1f5f9;
}

.service-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  border-color: #3b82f6;
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

.category-badge {
  position: absolute;
  top: 10px;
  left: 10px;
  z-index: 5;
}

.category-badge .badge {
  font-size: 0.7rem;
  padding: 0.25rem 0.5rem;
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

.btn-service-action.active {
  color: #dc3545;
}

.rating-badge {
  background: rgba(255, 193, 7, 0.15);
  padding: 4px 8px;
  border-radius: 20px;
  font-size: 0.8rem;
  color: #f59e0b;
  display: inline-flex;
  align-items: center;
}

.empty-favorites {
  background: #f8fafc;
  border-radius: 16px;
}

.card-body .btn-sm {
  padding: 0.375rem 0.75rem;
  font-size: 0.875rem;
}

/* Responsive */
@media (max-width: 768px) {
  .service-image-container {
    height: 140px;
  }
  
  .card-body .d-flex.gap-2 {
    flex-direction: column;
  }
  
  .card-body .d-flex.gap-2 .btn {
    width: 100%;
    margin-bottom: 0.5rem;
  }
}

@media (max-width: 576px) {
  .service-image-container {
    height: 120px;
  }
  
  .col-12.col-sm-6.col-md-4.col-lg-3 {
    margin-bottom: 1.5rem;
  }
}
</style>

{{-- Script para manejar favoritos --}}
@auth
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar clics en botones de favoritos (corazón en overlay)
    document.querySelectorAll('.favorite-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const serviceId = this.dataset.serviceId;
            toggleFavorite(serviceId, this.closest('.col-12'));
        });
    });
    
    // Manejar clics en botones de eliminar (ícono de basura)
    document.querySelectorAll('.favorite-remove-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const serviceId = this.dataset.serviceId;
            toggleFavorite(serviceId, this.closest('.col-12'));
        });
    });
    
    // Función para alternar favoritos
    function toggleFavorite(serviceId, cardElement) {
        fetch('{{ route("cliente.favoritos.toggle") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ servicio_id: serviceId })
        })
        .then(response => {
            if (!response.ok) throw new Error('Error en la respuesta');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (!data.is_favorite) {
                    // Animación de desvanecimiento antes de eliminar
                    cardElement.style.transition = 'all 0.3s ease';
                    cardElement.style.opacity = '0';
                    cardElement.style.transform = 'translateX(-100px)';
                    
                    setTimeout(() => {
                        cardElement.remove();
                        
                        // Actualizar contador
                        const countBadge = document.querySelector('.badge.bg-primary');
                        if (countBadge) {
                            const newCount = parseInt(countBadge.textContent) - 1;
                            countBadge.textContent = newCount + ' servicios guardados';
                            
                            // Si no hay más favoritos, recargar la página
                            if (newCount === 0) {
                                setTimeout(() => {
                                    location.reload();
                                }, 500);
                            }
                        }
                        
                        showToast('Servicio eliminado de favoritos', 'info');
                    }, 300);
                }
            } else {
                showToast(data.message || 'Error al actualizar favoritos', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error de conexión', 'error');
        });
    }
    
    // Función para mostrar toasts
    function showToast(message, type = 'info') {
        if (typeof bootstrap === 'undefined') {
            alert(message);
            return;
        }
        
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
        
        toast.innerHTML = 
            '<div class="d-flex">' +
                '<div class="toast-body">' + message + '</div>' +
                '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
            '</div>';
        
        document.getElementById('toast-container').appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', () => toast.remove());
    }
});
</script>
@endauth
@endsection