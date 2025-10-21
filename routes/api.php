<?php

use App\Http\Controllers\Cliente\ServicioController;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/subcategorias/{categoria_id}', function ($categoria_id) {
    $subcategorias = Subcategoria::where('categoria_id', $categoria_id)->get();
    return response()->json($subcategorias);
});

// Rutas API para servicios
Route::prefix('servicios')->group(function () {
    Route::get('/por-ubicacion', [ServicioController::class, 'porUbicacion']);
});

// Ruta para verificar autenticación desde JavaScript
Route::get('/api/check-auth', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->check() ? [
            'id' => auth()->id(),
            'name' => auth()->user()->name,
            'email' => auth()->user()->email
        ] : null,
        'role' => auth()->check() ? (auth()->user()->hasRole('Cliente') ? 'Cliente' : 
                  (auth()->user()->hasRole('Prestador') ? 'Prestador' : 
                  (auth()->user()->hasRole('Administrador') ? 'Administrador' : 'Usuario'))) : null
    ]);
});
