<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Servicios</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; }
        .subtitle { font-size: 14px; color: #666; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th { background-color: #f8f9fa; border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table td { border: 1px solid #ddd; padding: 6px; }
        .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #666; }
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 10px; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-danger { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Reporte de Servicios - ProServi</div>
        <div class="subtitle">Generado el: {{ $fecha_reporte }}</div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Prestador</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Precio</th>
                <th>Visitas</th>
                <th>Calificación</th>
                <th>Fecha Publicación</th>
            </tr>
        </thead>
        <tbody>
            @forelse($servicios as $servicio)
                <tr>
                    <td>{{ $servicio->id }}</td>
                    <td>{{ $servicio->titulo }}</td>
                    <td>{{ $servicio->prestador->name }} {{ $servicio->prestador->lastname }}</td>
                    <td>{{ $servicio->categoria->nombre_cat }}</td>
                    <td>
                        <span class="badge badge-{{ $servicio->estado == 'activo' ? 'success' : 'danger' }}">
                            {{ ucfirst($servicio->estado) }}
                        </span>
                    </td>
                    <td>
                        @if($servicio->precio)
                            Bs. {{ number_format($servicio->precio, 2) }}
                        @else
                            Por cotización
                        @endif
                    </td>
                    <td>{{ $servicio->visitas }}</td>
                    <td>{{ number_format($servicio->calificacion_promedio, 1) }}/5 ({{ $servicio->total_calificaciones }})</td>
                    <td>{{ $servicio->fecha_publicacion->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">No se encontraron servicios</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total de servicios: {{ $servicios->count() }} | Página 1 de 1
    </div>
</body>
</html>