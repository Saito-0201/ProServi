@extends('layouts.prestador')

@section('title', 'Estado de Verificación - PROSERVI')
@section('page_title', 'Estado')

@section('prestador-content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Mostrar mensajes de éxito/error -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                @if(!$verificacion)
                    <div class="mb-4">
                        <i class="fas fa-user-clock fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted">No tienes solicitud de verificación</h4>
                    <p class="text-muted mb-4">Aún no has enviado una solicitud de verificación de identidad.</p>
                    <a href="{{ route('prestador.verificacion.create') }}" class="btn btn-primary">
                        <i class="fas fa-shield-alt me-2"></i>Solicitar verificación
                    </a>
                @else
                    @switch($verificacion->estado)
                        @case('pendiente')
                            <div class="mb-4">
                                <i class="fas fa-clock fa-4x text-warning"></i>
                            </div>
                            <h4 class="text-warning">Verificación en revisión</h4>
                            <p class="text-muted mb-3">Tu solicitud de verificación está siendo revisada por nuestro equipo.</p>
                            <div class="alert alert-info">
                                <small>
                                    <i class="fas fa-info-circle me-2"></i>
                                    El proceso de verificación puede tomar de 1 a 3 días hábiles.
                                </small>
                            </div>
                            <form id="cancelar-verificacion-form" action="{{ route('prestador.verificacion.destroy') }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-outline-danger btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#confirmCancelModal">
                                    <i class="fas fa-times me-1"></i>Cancelar solicitud
                                </button>
                            </form>
                            @break

                        @case('aprobado')
                            <div class="mb-4">
                                <i class="fas fa-check-circle fa-4x text-success"></i>
                            </div>
                            <h4 class="text-success">¡Perfil verificado!</h4>
                            <p class="text-muted mb-3">Tu identidad ha sido verificada correctamente.</p>
                            <div class="alert alert-success">
                                <small>
                                    <i class="fas fa-check-circle me-2"></i>
                                    Verificado el: {{ $verificacion->fecha_verificacion->format('d/m/Y') }}
                                </small>
                            </div>
                            @break

                        @case('rechazado')
                            <div class="mb-4">
                                <i class="fas fa-times-circle fa-4x text-danger"></i>
                            </div>
                            <h4 class="text-danger">Verificación rechazada</h4>
                            <p class="text-muted mb-3">Tu solicitud de verificación fue rechazada.</p>
                            
                            <!-- Mostrar motivo de rechazo -->
                            @if($verificacion->motivo_rechazo)
                            <div class="alert alert-danger">
                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Motivo del rechazo:</h6>
                                <p class="mb-0">{{ $verificacion->motivo_rechazo }}</p>
                            </div>
                            @else
                            <div class="alert alert-warning">
                                <small>
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Por favor, verifica que la información y las fotos sean claras y válidas.
                                </small>
                            </div>
                            @endif
                            
                            <a href="{{ route('prestador.verificacion.create') }}" class="btn btn-primary">
                                <i class="fas fa-redo me-2"></i>Volver a intentar
                            </a>
                            @break
                    @endswitch
                @endif
            </div>
        </div>

        @if($verificacion)
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-history me-2"></i>Detalles de la solicitud</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Fecha de solicitud:</strong><br>
                        {{ $verificacion->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Estado actual:</strong><br>
                        <span class="badge bg-{{ $verificacion->estado == 'aprobado' ? 'success' : ($verificacion->estado == 'rechazado' ? 'danger' : 'warning') }}">
                            {{ ucfirst($verificacion->estado) }}
                        </span>
                    </div>
                </div>
                @if($verificacion->fecha_verificacion)
                <div class="row mt-3">
                    <div class="col-12">
                        <strong>Fecha de verificación:</strong><br>
                        {{ $verificacion->fecha_verificacion->format('d/m/Y H:i') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Modal de confirmación de cancelación --}}
<div class="modal fade" id="confirmCancelModal" tabindex="-1" aria-labelledby="confirmCancelLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="confirmCancelLabel">
          <i class="fas fa-shield-alt text-danger me-2"></i>Cancelar verificación
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <p class="mb-0">
          ¿Quieres cancelar la verificación? <br>
          <small class="text-muted">Perderás el estado del proceso actual y podrás solicitarla de nuevo más adelante.</small>
        </p>
      </div>

      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, volver</button>
        <button type="button" class="btn btn-danger" id="confirmCancelBtn">
          Sí, cancelar verificación
        </button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  var confirmBtn = document.getElementById('confirmCancelBtn');
  if (confirmBtn) {
    confirmBtn.addEventListener('click', function () {
      document.getElementById('cancelar-verificacion-form').submit();
    });
  }
});
</script>
@endpush

<style>
.badge {
    font-size: 0.85em;
    padding: 0.4em 0.8em;
}
</style>
@endsection