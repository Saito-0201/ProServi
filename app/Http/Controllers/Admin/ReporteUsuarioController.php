<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsuariosExport;
use PDF;

class ReporteUsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['roles', 'prestadorInfo', 'clienteInfo']);
        
        // Filtros mejorados
        if ($request->filled('rol')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->rol);
            });
        }
        
        if ($request->filled('estado_verificacion')) {
            if ($request->estado_verificacion === 'verificado') {
                $query->whereHas('prestadorInfo', function($q) {
                    $q->where('verificado', true);
                });
            } elseif ($request->estado_verificacion === 'no_verificado') {
                $query->whereHas('prestadorInfo', function($q) {
                    $q->where('verificado', false);
                });
            }
        }
        
        // Filtro por fechas - CORREGIDO
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        
        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }
        
        // Ordenamiento mejorado
        $orden = $request->get('orden', 'created_at');
        $direccion = $request->get('direccion', 'desc');
        
        // Validar que el campo de ordenamiento existe
        $camposValidos = ['created_at', 'name', 'email', 'lastname'];
        if (in_array($orden, $camposValidos)) {
            $query->orderBy($orden, $direccion);
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $usuarios = $query->paginate(20);
        
        $estadisticas = [
            'total' => User::count(),
            'administradores' => User::role('Administrador')->count(),
            'prestadores' => User::role('Prestador')->count(),
            'clientes' => User::role('Cliente')->count(),
            'nuevos_7_dias' => User::where('created_at', '>=', now()->subDays(7))->count(),
            'prestadores_verificados' => User::role('Prestador')->whereHas('prestadorInfo', function($q) {
                $q->where('verificado', true);
            })->count(),
            'filtrados' => $query->count(), // Total con filtros aplicados
        ];
        
        return view('admin.reportes.usuarios', compact('usuarios', 'estadisticas'));
    }
    
    public function exportar(Request $request)
    {
        $filtros = $request->all();
        
        return Excel::download(new UsuariosExport($filtros), 'reporte-usuarios-' . date('Y-m-d') . '.xlsx');
    }
    
    // NUEVO: Exportar a PDF
    public function exportarPdf(Request $request)
    {
        $query = User::with(['roles', 'prestadorInfo', 'clienteInfo']);
        
        // Aplicar los mismos filtros
        if ($request->filled('rol')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->rol);
            });
        }
        
        if ($request->filled('estado_verificacion')) {
            if ($request->estado_verificacion === 'verificado') {
                $query->whereHas('prestadorInfo', function($q) {
                    $q->where('verificado', true);
                });
            } elseif ($request->estado_verificacion === 'no_verificado') {
                $query->whereHas('prestadorInfo', function($q) {
                    $q->where('verificado', false);
                });
            }
        }
        
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        
        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }
        
        $usuarios = $query->orderBy('created_at', 'desc')->get();
        
        $data = [
            'usuarios' => $usuarios,
            'filtros' => $request->all(),
            'fecha_reporte' => now()->format('d/m/Y H:i'),
        ];
        
        $pdf = PDF::loadView('admin.reportes.pdf.usuarios', $data);
        
        return $pdf->download('reporte-usuarios-' . date('Y-m-d') . '.pdf');
    }
}