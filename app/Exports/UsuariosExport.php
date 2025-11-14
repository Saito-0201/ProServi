<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsuariosExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filtros;

    public function __construct($filtros)
    {
        $this->filtros = $filtros;
    }

    public function query()
    {
        $query = User::with(['roles', 'prestadorInfo', 'clienteInfo', 'servicios']);
        
        // Aplicar filtros igual que en el controlador
        if (!empty($this->filtros['rol'])) {
            $query->whereHas('roles', function($q) {
                $q->where('name', $this->filtros['rol']);
            });
        }
        
        if (!empty($this->filtros['estado_verificacion'])) {
            if ($this->filtros['estado_verificacion'] === 'verificado') {
                $query->whereHas('prestadorInfo', function($q) {
                    $q->where('verificado', true);
                });
            } elseif ($this->filtros['estado_verificacion'] === 'no_verificado') {
                $query->whereHas('prestadorInfo', function($q) {
                    $q->where('verificado', false);
                });
            }
        }
        
        if (!empty($this->filtros['fecha_inicio'])) {
            $query->whereDate('created_at', '>=', $this->filtros['fecha_inicio']);
        }
        
        if (!empty($this->filtros['fecha_fin'])) {
            $query->whereDate('created_at', '<=', $this->filtros['fecha_fin']);
        }
        
        // Ordenamiento
        $orden = $this->filtros['orden'] ?? 'created_at';
        $direccion = $this->filtros['direccion'] ?? 'desc';
        
        return $query->orderBy($orden, $direccion);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Apellido',
            'Email',
            'Rol',
            'Teléfono',
            'Email Verificado',
            'Estado Verificación',
            'Total Servicios',
            'Fecha Registro',
            'Última Actualización'
        ];
    }

    public function map($usuario): array
    {
        $rol = $usuario->roles->first()->name ?? 'Sin rol';
        $telefono = $usuario->prestadorInfo->telefono ?? $usuario->clienteInfo->telefono ?? 'No registrado';
        $estadoVerificacion = 'N/A';
        $totalServicios = 0;
        
        if ($rol === 'Prestador' && $usuario->prestadorInfo) {
            $estadoVerificacion = $usuario->prestadorInfo->verificado ? 'Verificado' : 'No verificado';
            $totalServicios = $usuario->servicios()->count();
        }

        return [
            $usuario->id,
            $usuario->name,
            $usuario->lastname,
            $usuario->email,
            $this->formatearRol($rol),
            $telefono,
            $usuario->email_verified_at ? 'Sí' : 'No',
            $estadoVerificacion,
            $totalServicios,
            $usuario->created_at->format('d/m/Y H:i'),
            $usuario->updated_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para el encabezado
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2E86C1']]
            ],
            
            // Estilo para las filas
            'A2:K1000' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => ['rgb' => 'DDDDDD']
                    ]
                ]
            ]
        ];
    }

    private function formatearRol($rol)
    {
        $roles = [
            'Administrador' => 'Administrador',
            'Prestador' => 'Prestador de Servicios',
            'Cliente' => 'Cliente'
        ];
        
        return $roles[$rol] ?? $rol;
    }
}