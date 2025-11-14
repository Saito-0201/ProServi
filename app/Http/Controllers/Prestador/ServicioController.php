<?php

namespace App\Http\Controllers\Prestador;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\PrestadorInfo; // Cambiado a singular
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ServicioController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $q = Servicio::with(['categoria', 'subcategoria'])
            ->where('prestador_id', $userId)
            ->when($request->filled('q'), function ($q2) use ($request) {
                $s = trim($request->q);
                $q2->where(function ($w) use ($s) {
                    $w->where('titulo', 'like', "%$s%")
                      ->orWhere('descripcion', 'like', "%$s%");
                });
            })
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString();

        return view('prestador.servicios.index', [
            'servicios' => $q,
        ]);
    }

    public function create()
    {
        $userId = auth()->id();
        $prestadorInfo = PrestadorInfo::where('usuario_id', $userId)->first(); // Cambiado a PrestadorInfo
        
        // Verificar si tiene número de WhatsApp
        if (!$prestadorInfo || !$prestadorInfo->telefono) {
            return view('prestador.servicios.sin-telefono', [
                'prestadorInfo' => $prestadorInfo
            ]);
        }

        return view('prestador.servicios.create', [
            'categorias' => Categoria::orderBy('nombre_cat')->get(),
            'subcategorias' => [], 
            'google_maps_api_key' => config('services.google.maps.maps_api_key'),
            'prestadorInfo' => $prestadorInfo
        ]);
    }

    public function store(Request $request)
    {
        $userId = auth()->id();
        $prestadorInfo = PrestadorInfo::where('usuario_id', $userId)->first(); // Cambiado a PrestadorInfo
        
        // Validar que tenga número de WhatsApp
        if (!$prestadorInfo || !$prestadorInfo->telefono) {
            return redirect()->route('prestador.servicios.create')
                ->with('error', 'Debe agregar un número de WhatsApp antes de publicar un servicio.');
        }

        $request->validate([
            'categoria_id'   => ['required','exists:categorias,id'],
            'subcategoria_id'=> ['required','exists:subcategorias,id'],
            'titulo'         => ['required','string','max:150'],
            'descripcion'    => ['required','string'],
            'tipo_precio'    => ['required','in:fijo,cotizacion,variable,diario,por_servicio'],
            'precio'         => ['nullable','numeric','required_if:tipo_precio,fijo,diario,por_servicio'],
            'imagen'         => ['nullable','image','max:4096'],
            'direccion'      => ['required','string'],
            'ciudad'         => ['required','string','max:100'],
            'provincia'      => ['required','string','max:100'],
            'pais'           => ['nullable','string','max:100'],
            'latitud'        => ['nullable','numeric'],
            'longitud'       => ['nullable','numeric'],
        ]);

        // Validación adicional para precio según tipo
        if (in_array($request->tipo_precio, ['fijo', 'diario', 'por_servicio'])) {
            $request->validate([
                'precio' => ['required', 'numeric', 'min:0'],
            ]);
        } else {
            // Para cotización y variable, el precio debe ser null
            $request->merge(['precio' => null]);
        }

        $servicio = new Servicio($request->only([
            'categoria_id','subcategoria_id','titulo','descripcion',
            'tipo_precio','precio','direccion','ciudad','provincia',
            'pais','latitud','longitud'
        ]));

        $servicio->prestador_id = $userId;
        $servicio->estado = 'activo'; 

        if ($request->hasFile('imagen')) {
            $servicio->imagen = $request->file('imagen')->store('servicios/fotos', 'public');
        }

        $servicio->save();

        return redirect()->route('prestador.servicios.index')
            ->with('ok', 'Servicio creado correctamente');
    }

    public function show(Servicio $servicio)
    {
        $this->authorizeServicio($servicio);
        $servicio->load(['categoria','subcategoria']);

        return view('prestador.servicios.show', compact('servicio'));
    }

    public function edit(Servicio $servicio)
    {
        $this->authorizeServicio($servicio);

        $userId = auth()->id();
        $prestadorInfo = PrestadorInfo::where('usuario_id', $userId)->first(); // Cambiado a PrestadorInfo

        return view('prestador.servicios.edit', [
            'servicio'      => $servicio->load(['categoria','subcategoria']),
            'categorias'    => Categoria::orderBy('nombre_cat')->get(),
            'subcategorias' => Subcategoria::where('categoria_id', $servicio->categoria_id)->orderBy('nombre')->get(),
            'google_maps_api_key' => config('services.google.maps.maps_api_key'),
            'prestadorInfo' => $prestadorInfo
        ]);
    }

    public function update(Request $request, Servicio $servicio)
    {
        $this->authorizeServicio($servicio);

        $userId = auth()->id();
        $prestadorInfo = PrestadorInfo::where('usuario_id', $userId)->first(); // Cambiado a PrestadorInfo
        
        // Validar que tenga número de WhatsApp
        if (!$prestadorInfo || !$prestadorInfo->telefono) {
            return redirect()->route('prestador.servicios.edit', $servicio)
                ->with('error', 'Debe agregar un número de WhatsApp antes de actualizar el servicio.');
        }

        $request->validate([
            'categoria_id'   => ['required','exists:categorias,id'],
            'subcategoria_id'=> ['required','exists:subcategorias,id'],
            'titulo'         => ['required','string','max:150'],
            'descripcion'    => ['required','string'],
            'tipo_precio'    => ['required','in:fijo,cotizacion,variable,diario,por_servicio'],
            'precio'         => ['nullable','numeric','required_if:tipo_precio,fijo,diario,por_servicio'],
            'imagen'         => ['nullable','image','max:4096'],
            'direccion'      => ['required','string'],
            'ciudad'         => ['required','string','max:100'],
            'provincia'      => ['required','string','max:100'],
            'pais'           => ['nullable','string','max:100'],
            'latitud'        => ['nullable','numeric'],
            'longitud'       => ['nullable','numeric'],
            'estado'         => ['required','in:activo,inactivo'],
        ]);

        // Validación adicional para precio según tipo
        if (in_array($request->tipo_precio, ['fijo', 'diario', 'por_servicio'])) {
            $request->validate([
                'precio' => ['required', 'numeric', 'min:0'],
            ]);
        } else {
            // Para cotización y variable, el precio debe ser null
            $request->merge(['precio' => null]);
        }

        // Guardar la imagen anterior para eliminarla después si es necesario
        $imagenAnterior = $servicio->imagen;

        $servicio->fill($request->only([
            'categoria_id','subcategorias_id','titulo','descripcion','tipo_precio',
            'precio','direccion','ciudad','provincia','pais','latitud','longitud','estado'
        ]));

        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior si existe
            if ($imagenAnterior) {
                Storage::disk('public')->delete($imagenAnterior);
            }
            // Guardar la nueva imagen
            $servicio->imagen = $request->file('imagen')->store('servicios/fotos', 'public');
        }

        $servicio->save();

        return redirect()->route('prestador.servicios.index')->with('ok', 'Servicio actualizado');
    }

    public function destroy(Servicio $servicio)
    {
        $this->authorizeServicio($servicio);

        // Eliminar la imagen si existe
        if ($servicio->imagen) {
            Storage::disk('public')->delete($servicio->imagen);
        }
        
        $servicio->delete();

        return redirect()->route('prestador.servicios.index')
            ->with('ok', 'Servicio eliminado correctamente');
    }

    public function subcategorias(Categoria $categoria)
    {
        return response()->json(
            Subcategoria::where('categoria_id', $categoria->id)->orderBy('nombre')->get(['id','nombre'])
        );
    }

    private function authorizeServicio(Servicio $servicio)
    {
        abort_unless($servicio->prestador_id === auth()->id(), 403);
    }
}