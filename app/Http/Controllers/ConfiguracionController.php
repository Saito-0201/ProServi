<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $configuracion = Configuracion::first();
        return view('admin.configuraciones.index',compact('configuracion'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //primero valida la informacion
        $request->validate([
            'site_name' => 'required|string|max:50',
            'site_description' => 'required|string|max:255',
            'site_phone' => 'required|string|max:20',
            'site_email' => 'required|email|max:100',
            'site_logo' => 'image|mimes:jpeg,png,jpg', 
        ]);

        // Buscar si existe un registro
        $configuracion = Configuracion::first();

        // Si hay un archivo de logo, lo procesamos
        if ($request->hasFile('site_logo')) {
            $site_logo = $request->file('site_logo');
            $nombreArchivo = time() . '_' . $site_logo->getClientOriginalName();
            $rutaDestino = public_path('uploads/logos');
            $site_logo->move($rutaDestino, $nombreArchivo);
            $logoPath = 'uploads/logos/' . $nombreArchivo;
        } else {
            // Si no se sube un nuevo logo, mantener el actual
            $logoPath = $configuracion->site_logo ?? null;
        }

        if ($configuracion) {
            // Si existe, actualizar
            $configuracion->update([
                'site_name' => $request->site_name,
                'site_description' => $request->site_description,
                'site_phone' => $request->site_phone,
                'site_email' => $request->site_email,
                'site_logo' => $logoPath,
            ]);
        } else {
            // Si no existe, crear uno nuevo// y para registrar viene del modelo
            Configuracion::create([
                'site_name' => $request->site_name,
                'site_description' => $request->site_description,
                'site_phone' => $request->site_phone,
                'site_email' => $request->site_email,
                'site_logo' => $logoPath,
            ]);
        }

        return redirect()->back()
            ->with('mensaje', 'Configuración guardada correctamente')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Configuracion $configuracion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Configuracion $configuracion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Configuracion $configuracion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Configuracion $configuracion)
    {
        //
    }
}
