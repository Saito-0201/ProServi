@php
    use App\Models\Calificacion;
@endphp

@extends('layouts.prestador')

@section('title', 'Mis Calificaciones - Prestador')
@section('header', 'Mis Calificaciones')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('prestador.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Mis Calificaciones</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h2>{{ number_format($promedio, 1) }}/5</h2>
                    <div class="mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($promedio))
                                <i class="fas fa-star text-warning"></i>
                            @elseif($i - 0.5 <= $promedio)
                                <i class="fas fa-star-half-alt text-warning"></i>
                            @else
                                <i class="far fa-star text-warning"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="text-muted">{{ $totalCalificaciones }} calificaciones</p>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Distribución de calificaciones</h5>
                </div>
                <div class="card-body">
                    @for($i = 5; $i >= 1; $i--)
                        @php
                            $count = Calificacion::whereHas('servicio', function($query) {
                                    $query->where('prestador_id', Auth::id());
                                })
                                ->where('puntuacion', $i)
                                ->count();
                            $percentage = $totalCalificaciones > 0 ? ($count / $totalCalificaciones) * 100 : 0;
                        @endphp
                        <div class="row align-items-center mb-2">
                            <div class="col-2">
                                <span class="text-muted">{{ $i }} <i class="fas fa-star text-warning"></i></span>
                            </div>
                            <div class="col-8">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%;" 
                                         aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="col-2 text-right">
                                <small class="text-muted">{{ $count }}</small>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Todas las calificaciones</h5>
                    
                    @forelse($calificaciones as $calificacion)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $calificacion->cliente->name }}</strong>
                                    <div class="text-warning mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $calificacion->puntuacion)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-muted">{{ $calificacion->created_at->format('d/m/Y') }}</span>
                            </div>
                            
                            <p class="mb-1"><strong>Servicio:</strong> {{ $calificacion->servicio->titulo }}</p>
                            
                            @if($calificacion->comentario)
                                <p class="mb-0">{{ $calificacion->comentario }}</p>
                            @else
                                <p class="text-muted mb-0">Sin comentario</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <p>No tienes calificaciones aún</p>
                        </div>
                    @endforelse
                    
                    @if($calificaciones->hasPages())
                        <div class="mt-3">
                            {{ $calificaciones->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection