{{-- resources/views/prestador/servicios/sin-telefono.blade.php --}}
@extends('layouts.prestador')
@section('title','Agregar WhatsApp — PROSERVI')
@section('page_title','Número de WhatsApp Requerido')

@section('prestador-content')
<div class="card ui-card">
    <div class="card-body text-center py-5">
        <div class="mb-4">
            <i class="bi bi-whatsapp text-success" style="font-size: 4rem;"></i>
        </div>
        
        <h3 class="mb-3">Número de WhatsApp Requerido</h3>
        
        <p class="text-muted mb-4">
            Para publicar servicios y que los clientes puedan contactarte, necesitas agregar tu número de WhatsApp.
        </p>

        <div class="alert alert-info mb-4">
            <i class="bi bi-info-circle me-2"></i>
            Tu número de WhatsApp será visible únicamente para los clientes interesados en tus servicios.
        </div>

        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('prestador.perfil.edit') }}" class="btn btn-success btn-lg">
                <i class="bi bi-whatsapp me-2"></i> Agregar WhatsApp
            </a>
            
            <a href="{{ route('prestador.servicios.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>
</div>
@endsection