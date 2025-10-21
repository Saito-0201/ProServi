{{-- resources/views/prestador/servicios/index.blade.php --}}
@extends('layouts.prestador')

@section('title','Mis servicios — PROSERVI')
@section('page_title','Mis servicios')

@section('prestador-content')
  

  @if(session('ok'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('ok') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if($servicios->count() === 0)
    <div class="card ui-card">
      <div class="card-body text-center py-5">
        <p class="text-muted mb-3">Aún no tienes servicios.</p>
        <a href="{{ route('prestador.servicios.create') }}" class="btn btn-primary">
          <i class="bi bi-plus-circle me-1"></i> Crear mi primer servicio
        </a>
      </div>
    </div>
  @else
    <div class="row g-4">
      @foreach($servicios as $s)
        <div class="col-12 col-md-6 col-xl-4">
          <div class="card h-100 ui-card service-card">
            <div class="ratio ratio-16x9">
              <img class="rounded-top object-fit-cover"
                   src="{{ $s->imagen ? asset('storage/'.$s->imagen) : asset('images/default-service.jpg') }}"
                   alt="{{ $s->titulo }}">
            </div>
            <div class="card-body">
              <h5 class="mb-1">{{ $s->titulo }}</h5>
              <div class="text-muted small mb-2">
                {{ optional($s->categoria)->nombre_cat }} · {{ optional($s->subcategoria)->nombre }}
              </div>

              <div class="d-flex align-items-center gap-2 mb-2">
                @php
                  $tipoPrecioLabels = [
                    'fijo' => 'Fijo',
                    'cotizacion' => 'Cotización',
                    'variable' => 'Variable',
                    'diario' => 'Diario',
                    'por_servicio' => 'Por Servicio'
                  ];
                @endphp

                @switch($s->tipo_precio)
                  @case('fijo')
                  <span class="badge text-bg-primary">{{ $tipoPrecioLabels[$s->tipo_precio] }}</span>
                  @break
                  @case('cotizacion')
                  <span class="badge text-bg-secondary">{{ $tipoPrecioLabels[$s->tipo_precio] }}</span>
                  @break
                  @case('variable')
                  <span class="badge text-bg-info">{{ $tipoPrecioLabels[$s->tipo_precio] }}</span>
                  @break
                  @case('diario')
                  <span class="badge text-bg-warning">{{ $tipoPrecioLabels[$s->tipo_precio] }}</span>
                  @break
                  @case('por_servicio')
                  <span class="badge text-bg-success">{{ $tipoPrecioLabels[$s->tipo_precio] }}</span>
                  @break
                  @default
                  <span class="badge text-bg-light">{{ ucfirst($s->tipo_precio) }}</span>
                @endswitch

                {{-- Mostrar precio solo para tipos que lo requieren --}}
                @if(in_array($s->tipo_precio, ['fijo', 'diario', 'por_servicio']) && $s->precio)
                  <span class="fw-semibold text-primary">Bs. {{ number_format($s->precio, 2) }}</span>
                @elseif($s->tipo_precio === 'cotizacion')
                  <span class="fw-semibold text-muted">Por cotización</span>
                @elseif($s->tipo_precio === 'variable')
                  <span class="fw-semibold text-muted">Variable</span>
                @endif
              </div>

              <div class="text-muted small mb-3">
                <i class="bi bi-geo-alt"></i> {{ $s->ciudad }} · {{ $s->provincia }}
              </div>

              <div class="d-grid gap-2">
                <a class="btn btn-outline-primary" href="{{ route('prestador.servicios.show',$s) }}">
                  <i class="bi bi-eye me-1"></i> Ver detalles
                </a>
                <div class="d-flex gap-2">
                  <a class="btn btn-outline-secondary flex-fill" href="{{ route('prestador.servicios.edit',$s) }}">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <button class="btn btn-outline-danger flex-fill" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $s->id }}">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal de confirmación de eliminación -->
        <div class="modal fade" id="deleteModal{{ $s->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $s->id }}" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $s->id }}">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar el servicio <strong>"{{ $s->titulo }}"</strong>?</p>
                <p class="text-danger">Esta acción no se puede deshacer.</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('prestador.servicios.destroy', $s) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger">Eliminar servicio</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-4">
      {{ $servicios->links() }}
    </div>
  @endif
@endsection