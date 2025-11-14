<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ServiciosExport;
use PDF;

class ReporteServicioController extends Controller
{
    public function index(Request $request)
    {
        $query = Servicio::with(['prestador', 'categoria', 'subcategoria']);
        
        // Filtros mejorados
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }
        
        if ($request->filled('prestador_id')) {
            $query->where('prestador_id', $request->prestador_id);
        }
        
        // Filtro por fechas 
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_publicacion', '>=', $request->fecha_inicio);
        }
        
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_publicacion', '<=', $request->fecha_fin);
        }
        
        // Ordenamiento mejorado
        $orden = $request->get('orden', 'fecha_publicacion');
        $direccion = $request->get('direccion', 'desc');
        
        // Validar que el campo de ordenamiento existe
        $camposValidos = ['fecha_publicacion', 'visitas', 'calificacion_promedio', 'created_at', 'precio'];
        if (in_array($orden, $camposValidos)) {
            $query->orderBy($orden, $direccion);
        } else {
            $query->orderBy('fecha_publicacion', 'desc');
        }
        
        $servicios = $query->paginate(20);
        
        $categorias = Categoria::where('estado', 'activo')->get();
        $prestadores = User::role('Prestador')->get();
        
        // Estadísticas con los mismos filtros
        $estadisticas = [
            'total' => Servicio::count(),
            'activos' => Servicio::where('estado', 'activo')->count(),
            'inactivos' => Servicio::where('estado', 'inactivo')->count(),
            'promedio_calificacion' => Servicio::avg('calificacion_promedio') ?? 0,
            'total_visitas' => Servicio::sum('visitas'),
            'filtrados' => $query->count(), // Total con filtros aplicados
        ];
        
        return view('admin.reportes.servicios', compact(
            'servicios', 
            'categorias', 
            'prestadores',
            'estadisticas'
        ));
    }
    
    public function exportar(Request $request)
    {
        $filtros = $request->all();
        
        return Excel::download(new ServiciosExport($filtros), 'reporte-servicios-' . date('Y-m-d') . '.xlsx');
    }
    
    // NUEVO: Exportar a PDF
    public function exportarPdf(Request $request)
    {
        $query = Servicio::with(['prestador', 'categoria', 'subcategoria']);
        
        // Aplicar los mismos filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }
        
        if ($request->filled('prestador_id')) {
            $query->where('prestador_id', $request->prestador_id);
        }
        
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_publicacion', '>=', $request->fecha_inicio);
        }
        
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_publicacion', '<=', $request->fecha_fin);
        }
        
        $servicios = $query->orderBy('fecha_publicacion', 'desc')->get();
        
        $data = [
            'servicios' => $servicios,
            'filtros' => $request->all(),
            'fecha_reporte' => now()->format('d/m/Y H:i'),
        ];
        
        $pdf = PDF::loadView('admin.reportes.pdf.servicios', $data);
        
        return $pdf->download('reporte-servicios-' . date('Y-m-d') . '.pdf');
    }
}