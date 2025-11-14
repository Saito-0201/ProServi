<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Usuarios</title>
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
        .badge-warning { background-color: #ffc107; color: black; }
        .badge-secondary { background-color: #6c757d; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Reporte de Usuarios - ProServi</div>
        <div class="subtitle">Generado el: {{ $fecha_reporte }}</div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Teléfono</th>
                <th>Email Verificado</th>
                <th>Estado Verificación</th>
                <th>Servicios</th>
                <th>Fecha Registro</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usuarios as $usuario)
                @php
                    $rol = $usuario->roles->first()->name ?? 'Sin rol';
                    $telefono = $usuario->prestadorInfo->telefono ?? $usuario->clienteInfo->telefono ?? 'No registrado';
                    $estadoVerificacion = 'N/A';
                    $totalServicios = 0;
                    
                    if ($rol === 'Prestador' && $usuario->prestadorInfo) {
                        $estadoVerificacion = $usuario->prestadorInfo->verificado ? 'Verificado' : 'No verificado';
                        $totalServicios = $usuario->servicios()->count();
                    }
                @endphp
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->name }} {{ $usuario->lastname }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $rol }}</td>
                    <td>{{ $telefono }}</td>
                    <td>
                        <span class="badge badge-{{ $usuario->email_verified_at ? 'success' : 'danger' }}">
                            {{ $usuario->email_verified_at ? 'Sí' : 'No' }}
                        </span>
                    </td>
                    <td>
                        @if($rol === 'Prestador' && $usuario->prestadorInfo)
                            <span class="badge badge-{{ $usuario->prestadorInfo->verificado ? 'success' : 'warning' }}">
                                {{ $estadoVerificacion }}
                            </span>
                        @else
                            <span class="badge badge-secondary">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($rol === 'Prestador')
                            <span class="badge badge-info">{{ $totalServicios }}</span>
                        @else
                            <span class="badge badge-secondary">N/A</span>
                        @endif
                    </td>
                    <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">No se encontraron usuarios</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total de usuarios: {{ $usuarios->count() }} | Página 1 de 1
    </div>
</body>
</html>