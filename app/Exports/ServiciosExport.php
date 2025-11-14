<?php

namespace App\Exports;

use App\Models\Servicio;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ServiciosExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filtros;

    public function __construct($filtros)
    {
        $this->filtros = $filtros;
    }

    public function query()
    {
        $query = Servicio::with(['prestador', 'categoria', 'subcategoria']);
        
        // Aplicar filtros igual que en el controlador
        if (!empty($this->filtros['estado'])) {
            $query->where('estado', $this->filtros['estado']);
        }
        
        if (!empty($this->filtros['categoria_id'])) {
            $query->where('categoria_id', $this->filtros['categoria_id']);
        }
        
        if (!empty($this->filtros['prestador_id'])) {
            $query->where('prestador_id', $this->filtros['prestador_id']);
        }
        
        if (!empty($this->filtros['fecha_inicio'])) {
            $query->whereDate('fecha_publicacion', '>=', $this->filtros['fecha_inicio']);
        }
        
        if (!empty($this->filtros['fecha_fin'])) {
            $query->whereDate('fecha_publicacion', '<=', $this->filtros['fecha_fin']);
        }
        
        // Ordenamiento
        $orden = $this->filtros['orden'] ?? 'fecha_publicacion';
        $direccion = $this->filtros['direccion'] ?? 'desc';
        
        return $query->orderBy($orden, $direccion);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Título',
            'Descripción',
            'Prestador',
            'Categoría',
            'Subcategoría',
            'Estado',
            'Tipo Precio',
            'Precio (Bs.)',
            'Visitas',
            'Calificación Promedio',
            'Total Calificaciones',
            'Fecha Publicación',
            'Ciudad',
            'Dirección'
        ];
    }

    public function map($servicio): array
    {
        return [
            $servicio->id,
            $servicio->titulo,
            $servicio->descripcion,
            $servicio->prestador ? $servicio->prestador->name . ' ' . $servicio->prestador->lastname : 'N/A',
            $servicio->categoria->nombre_cat,
            $servicio->subcategoria->nombre,
            ucfirst($servicio->estado),
            $this->formatearTipoPrecio($servicio->tipo_precio),
            $servicio->precio ? number_format($servicio->precio, 2) : 'Por cotización',
            $servicio->visitas,
            number_format($servicio->calificacion_promedio, 1),
            $servicio->total_calificaciones,
            $servicio->fecha_publicacion->format('d/m/Y H:i'),
            $servicio->ciudad,
            $servicio->direccion
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
            'A2:O1000' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => ['rgb' => 'DDDDDD']
                    ]
                ]
            ]
        ];
    }

    private function formatearTipoPrecio($tipo)
    {
        $tipos = [
            'fijo' => 'Precio Fijo',
            'cotizacion' => 'Por Cotización',
            'variable' => 'Variable',
            'diario' => 'Por Día',
            'por_servicio' => 'Por Servicio'
        ];
        
        return $tipos[$tipo] ?? $tipo;
    }
}