@extends('layouts.prestador')

@section('title', 'Estadísticas - Prestador')
@section('header', 'Estadísticas')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('prestador.index') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Estadísticas</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Visitas por Servicio</h3>
                </div>
                <div class="card-body">
                    <canvas id="visitasChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Contactos por Mes</h3>
                </div>
                <div class="card-body">
                    <canvas id="contactosChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Resumen de Servicios</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-concierge-bell"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Servicios</span>
                                    <span class="info-box-number">{{ $totalServicios }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Servicios Activos</span>
                                    <span class="info-box-number">{{ $serviciosActivos }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-eye"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Visitas Totales</span>
                                    <span class="info-box-number">125</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-comments"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Contactos Totales</span>
                                    <span class="info-box-number">42</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Gráfico de visitas por servicio
        var visitasCtx = document.getElementById('visitasChart').getContext('2d');
        var visitasChart = new Chart(visitasCtx, {
            type: 'bar',
            data: {
                labels: ['Servicio 1', 'Servicio 2', 'Servicio 3', 'Servicio 4'],
                datasets: [{
                    label: 'Número de Visitas',
                    data: [65, 59, 80, 43],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Gráfico de contactos por mes
        var contactosCtx = document.getElementById('contactosChart').getContext('2d');
        var contactosChart = new Chart(contactosCtx, {
            type: 'line',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
                datasets: [{
                    label: 'Contactos',
                    data: [12, 19, 8, 15, 10, 17],
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            }
        });
    });
</script>
@endpush