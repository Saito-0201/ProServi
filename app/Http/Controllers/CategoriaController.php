<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    
    public function index()
    {   
        $categorias = Categoria::all();
        return view('admin.categorias.index', compact('categorias'));
    }

    
    public function create()
    {
        $estados = ['activo' => 'Activo', 'inactivo' => 'Inactivo'];
        return view('admin.categorias.create', compact('estados'));
    }

    
    public function store(Request $request)
    {   
        

        $request->validate([
            'nombre_cat' => 'required|string|max:100|unique:categorias',
            'descripcion_cat' => 'nullable|string|max:255',
            'estado' => ['required', Rule::in(['activo', 'inactivo'])]
        ]);

        Categoria::create([
            'nombre_cat' => $request->nombre_cat,
            'descripcion_cat' => $request->descripcion_cat,
            'estado' => $request->estado
        ]);

        return redirect()->route('admin.categorias.index')
            ->with('mensaje', 'Categoría creada correctamente')
            ->with('icono', 'success');
    }


    
    public function show($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('admin.categorias.show', compact('categoria'));
    }

    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        $estados = ['activo' => 'Activo', 'inactivo' => 'Inactivo'];
        return view('admin.categorias.edit', compact('categoria', 'estados'));
    }

    
    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);
    
    $request->validate([
        'nombre_cat' => 'required|string|max:100|unique:categorias,nombre_cat,' . $categoria->id,
        'descripcion_cat' => 'nullable|string|max:255',
        'estado' => ['required', Rule::in(['activo', 'inactivo'])] 
    ]);

    $categoria->update([
        'nombre_cat' => $request->nombre_cat,
        'descripcion_cat' => $request->descripcion_cat,
        'estado' => $request->estado 
    ]);

    return redirect()->route('admin.categorias.index')
        ->with('mensaje', 'Categoría actualizada correctamente')
        ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categoria = Categoria::find($id);
        $categoria->delete();
        return redirect()->route('admin.categorias.index')
               ->with('mensaje','Categoria eliminada correctamente')
               ->with('icono','success');
    }
}
