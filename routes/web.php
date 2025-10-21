<?php


use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


// Página principal
Route::get('/', [\App\Http\Controllers\LandingController::class, 'index']);


Route::view('/terminos', 'terms')->name('terms');
Route::view('/privacidad', 'privacy')->name('privacy');



// Rutas de autenticación (con verificación de email activada)
Auth::routes(['verify' => true]);

// ==================== REDIRECCIÓN DESPUÉS DEL LOGIN ====================
Route::get('/home', function () {
    $user = Auth::user();
    
    if ($user->hasRole('Administrador')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('Prestador')) {
        return redirect()->route('prestador.index');
    } elseif ($user->hasRole('Cliente')) {
        return redirect()->route('cliente.index');
    } else {
        // Si no tiene rol, redirigir al landing con mensaje
        return redirect('/')->with('error', 'No tienes un rol asignado. Contacta al administrador.');
    }
})->name('home')->middleware(['auth','verified']);

// ==================== RUTAS PÚBLICAS ====================
Route::name('public.')->group(function () {
    Route::get('/', [\App\Http\Controllers\LandingController::class, 'index'])->name('home');
    
    // Servicios públicos
    Route::get('/servicios', [\App\Http\Controllers\Publico\ServicioController::class, 'index'])->name('servicios.index');
    Route::get('/servicios/{id}', [\App\Http\Controllers\Publico\ServicioController::class, 'show'])->name('servicios.show');
    Route::get('/servicios/categoria/{categoriaId}/subcategorias', [\App\Http\Controllers\Publico\ServicioController::class, 'getSubcategorias'])
         ->name('servicios.subcategorias');
    Route::get('/public/servicios/subcategorias/{categoria}', [\App\Http\Controllers\Publico\ServicioController::class, 'getSubcategorias'])
    ->name('public.servicios.subcategorias');
});


// ==================== CLIENTE ====================
Route::prefix('cliente')->name('cliente.')->middleware(['auth', 'verified', 'role:Cliente'])->group(function () {  
    
    // INICIO
    Route::get('/', [\App\Http\Controllers\Cliente\ClienteController::class, 'index'])->name('index');
    
    // PERFIL
    Route::prefix('perfil')->group(function () {
        Route::get('/', [\App\Http\Controllers\Cliente\PerfilController::class, 'show'])->name('perfil.show');
        Route::get('/editar', [\App\Http\Controllers\Cliente\PerfilController::class, 'edit'])->name('perfil.edit');
        Route::put('/actualizar', [\App\Http\Controllers\Cliente\PerfilController::class, 'update'])->name('perfil.update');
        Route::post('/actualizar-foto', [\App\Http\Controllers\Cliente\PerfilController::class, 'updateFoto'])->name('perfil.update-foto');
        Route::delete('/eliminar-foto', [\App\Http\Controllers\Cliente\PerfilController::class, 'removeFoto'])->name('perfil.remove-foto');
    });
    
    // SERVICIOS
    Route::prefix('servicios')->group(function () {
        Route::get('/', [\App\Http\Controllers\Cliente\ServicioController::class, 'index'])->name('servicios.index');
        Route::get('/buscar', [\App\Http\Controllers\Cliente\ServicioController::class, 'buscar'])->name('servicios.buscar');
        Route::get('/ubicacion', [\App\Http\Controllers\Cliente\ServicioController::class, 'porUbicacion'])->name('servicios.ubicacion');
        Route::get('/categoria/{categoria}', [\App\Http\Controllers\Cliente\ServicioController::class, 'porCategoria'])->name('servicios.categoria');
        Route::get('/{servicio}', [\App\Http\Controllers\Cliente\ServicioController::class, 'show'])->name('servicios.show');
        
        // AJAX Routes
        Route::get('/subcategorias/{categoriaId}', [\App\Http\Controllers\Cliente\ServicioController::class, 'getSubcategorias'])
            ->name('servicios.subcategorias');
    });
    

    // PRESTADORES
    Route::prefix('prestadores')->group(function () {
        Route::get('/{prestador}', [\App\Http\Controllers\Cliente\PrestadorController::class, 'show'])->name('prestadores.show');
    });
    
    // FAVORITOS
    Route::prefix('favoritos')->group(function () {
        Route::get('/', [\App\Http\Controllers\Cliente\FavoritoController::class, 'index'])->name('favoritos.index');
        Route::post('/toggle', [\App\Http\Controllers\Cliente\FavoritoController::class, 'toggle'])->name('favoritos.toggle');
        Route::delete('/{favorito}', [\App\Http\Controllers\Cliente\FavoritoController::class, 'destroy'])->name('favoritos.destroy');
        Route::get('/check/{servicio}', [\App\Http\Controllers\Cliente\FavoritoController::class, 'check'])->name('favoritos.check');
    });
    
    // CALIFICACIONES
    Route::prefix('calificaciones')->group(function () {
    Route::post('/', [\App\Http\Controllers\Cliente\CalificacionController::class, 'store'])->name('calificaciones.store');
    Route::delete('/{calificacion}', [\App\Http\Controllers\Cliente\CalificacionController::class, 'destroy'])->name('calificaciones.destroy');
    Route::get('/user-rating/{servicio}', [\App\Http\Controllers\Cliente\CalificacionController::class, 'getUserRating'])->name('calificaciones.user-rating');
    });
});

// ==================== PRESTADOR ====================
Route::prefix('prestador')->name('prestador.')->middleware(['auth', 'verified', 'role:Prestador'])->group(function () {

    // Inicio simple (estadísticas)
    Route::get('/', [App\Http\Controllers\Prestador\PrestadorController::class, 'index'])->name('index');

    // Perfil 
    Route::get('/perfil', [App\Http\Controllers\Prestador\PerfilController::class, 'show'])->name('perfil.show');
    Route::get('/perfil/editar', [App\Http\Controllers\Prestador\PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [App\Http\Controllers\Prestador\PerfilController::class, 'update'])->name('perfil.update');

    // Verificación de perfil
    Route::get('/verificacion', [App\Http\Controllers\Prestador\VerificacionController::class, 'create'])->name('verificacion.create');
    Route::post('/verificacion', [App\Http\Controllers\Prestador\VerificacionController::class, 'store'])->name('verificacion.store');
    Route::get('/verificacion/estado', [App\Http\Controllers\Prestador\VerificacionController::class, 'estado'])->name('verificacion.estado');
    Route::delete('/verificacion', [App\Http\Controllers\Prestador\VerificacionController::class, 'destroy'])->name('verificacion.destroy');

    // Gestión de foto de perfil del prestador
    Route::post('perfil/foto', [\App\Http\Controllers\Prestador\PerfilController::class, 'updateFoto'])
         ->name('perfil.update-foto');
    Route::delete('perfil/foto', [\App\Http\Controllers\Prestador\PerfilController::class, 'removeFoto'])
         ->name('perfil.remove-foto');
    // Servicios 
    Route::get('/servicios', [App\Http\Controllers\Prestador\ServicioController::class, 'index'])->name('servicios.index');
    Route::get('/servicios/crear', [App\Http\Controllers\Prestador\ServicioController::class, 'create'])->name('servicios.create');
    Route::post('/servicios', [App\Http\Controllers\Prestador\ServicioController::class, 'store'])->name('servicios.store');
    Route::get('/servicios/{servicio}', [App\Http\Controllers\Prestador\ServicioController::class, 'show'])->name('servicios.show');
    Route::get('/servicios/{servicio}/editar', [App\Http\Controllers\Prestador\ServicioController::class, 'edit'])->name('servicios.edit');
    Route::put('/servicios/{servicio}', [App\Http\Controllers\Prestador\ServicioController::class, 'update'])->name('servicios.update');
    Route::delete('/servicios/{servicio}', [App\Http\Controllers\Prestador\ServicioController::class, 'destroy'])->name('servicios.destroy');
    
    //Calificaciones
    Route::get('/calificaciones', [App\Http\Controllers\Prestador\CalificacionController::class, 'index'])->name('calificaciones.index');

    // AJAX: subcategorías por categoría
    Route::get('/servicios/{categoria}/subcategorias', [App\Http\Controllers\Prestador\ServicioController::class, 'subcategorias'])
        ->name('servicios.subcategorias');
});


// ==================== ADMINISTRADOR ====================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:Administrador'])->group(function () {
    
    // DASHBOARD
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');
    
    // CONFIGURACIONES
    Route::get('/configuraciones', [App\Http\Controllers\ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::post('/configuraciones/create', [App\Http\Controllers\ConfiguracionController::class, 'store'])->name('configuracion.store');
    
    // ROLES
    Route::get('/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [App\Http\Controllers\RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles/create', [App\Http\Controllers\RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}', [App\Http\Controllers\RoleController::class, 'show'])->name('roles.show');
    Route::get('/roles/{id}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('roles.destroy');
    
    // USUARIOS
    Route::get('/usuarios', [App\Http\Controllers\UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/create', [App\Http\Controllers\UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios/create', [App\Http\Controllers\UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{id}', [App\Http\Controllers\UsuarioController::class, 'show'])->name('usuarios.show');
    Route::get('/usuarios/{id}/edit', [App\Http\Controllers\UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{id}', [App\Http\Controllers\UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [App\Http\Controllers\UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    
    // CATEGORÍAS
    Route::get('/categorias', [App\Http\Controllers\CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/create', [App\Http\Controllers\CategoriaController::class, 'create'])->name('categorias.create');
    Route::post('/categorias/create', [App\Http\Controllers\CategoriaController::class, 'store'])->name('categorias.store');
    Route::get('/categorias/{id}', [App\Http\Controllers\CategoriaController::class, 'show'])->name('categorias.show');
    Route::get('/categorias/{id}/edit', [App\Http\Controllers\CategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{id}', [App\Http\Controllers\CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/categorias/{id}', [App\Http\Controllers\CategoriaController::class, 'destroy'])->name('categorias.destroy');
    
    // SUBCATEGORÍAS
    Route::get('/subcategorias', [App\Http\Controllers\SubcategoriaController::class, 'index'])->name('subcategorias.index');
    Route::get('/subcategorias/create', [App\Http\Controllers\SubcategoriaController::class, 'create'])->name('subcategorias.create');
    Route::post('/subcategorias/create', [App\Http\Controllers\SubcategoriaController::class, 'store'])->name('subcategorias.store');
    Route::get('/subcategorias/{id}', [App\Http\Controllers\SubcategoriaController::class, 'show'])->name('subcategorias.show');
    Route::get('/subcategorias/{id}/edit', [App\Http\Controllers\SubcategoriaController::class, 'edit'])->name('subcategorias.edit');
    Route::put('/subcategorias/{id}', [App\Http\Controllers\SubcategoriaController::class, 'update'])->name('subcategorias.update');
    Route::delete('/subcategorias/{id}', [App\Http\Controllers\SubcategoriaController::class, 'destroy'])->name('subcategorias.destroy');
    
    // SERVICIOS
    Route::get('/servicios', [App\Http\Controllers\ServicioController::class, 'index'])->name('servicios.index');
    Route::get('/servicios/create', [App\Http\Controllers\ServicioController::class, 'create'])->name('servicios.create');
    Route::post('/servicios', [App\Http\Controllers\ServicioController::class, 'store'])->name('servicios.store');
    Route::get('/servicios/{id}', [App\Http\Controllers\ServicioController::class, 'show'])->name('servicios.show');
    Route::get('/servicios/{id}/edit', [App\Http\Controllers\ServicioController::class, 'edit'])->name('servicios.edit');
    Route::put('/servicios/{id}', [App\Http\Controllers\ServicioController::class, 'update'])->name('servicios.update');
    Route::delete('/servicios/{id}', [App\Http\Controllers\ServicioController::class, 'destroy'])->name('servicios.destroy');

    // Rutas de verificaciones
    Route::get('/verificaciones', [App\Http\Controllers\VerificacionController::class, 'index'])->name('verificaciones.index');
    Route::get('/verificaciones/{id}', [App\Http\Controllers\VerificacionController::class, 'show'])->name('verificaciones.show');
    Route::get('/verificaciones/{id}/edit', [App\Http\Controllers\VerificacionController::class, 'edit'])->name('verificaciones.edit');
    Route::put('/verificaciones/{id}', [App\Http\Controllers\VerificacionController::class, 'update'])->name('verificaciones.update');
    Route::patch('/verificaciones/{id}/aprobar', [App\Http\Controllers\VerificacionController::class, 'aprobar'])->name('verificaciones.aprobar');
    Route::patch('/verificaciones/{id}/rechazar', [App\Http\Controllers\VerificacionController::class, 'rechazar'])->name('verificaciones.rechazar');
    Route::get('/verificaciones-pendientes', [App\Http\Controllers\VerificacionController::class, 'pendientes'])->name('verificaciones.pendientes');
    
    // Ruta para obtener subcategorías (sin middleware auth para AJAX)
    Route::get('/servicios/{categoria_id}/subcategorias', [App\Http\Controllers\ServicioController::class, 'getSubcategorias'])
        ->name('servicios.subcategorias');
});

// ==================== AUTENTICACIÓN CON GOOGLE ====================
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('login.google.callback');

// Completar registro con Google
Route::get('/complete-registration', [GoogleController::class, 'showCompleteForm'])->name('complete.registration')->middleware('guest');
Route::post('/complete-registration', [GoogleController::class, 'completeRegistration'])->name('complete.registration.submit')->middleware('guest');

// ==================== VERIFICACIÓN DE EMAIL ====================
// Aviso de verificación
Route::get('/email/verify', [VerificationController::class, 'show'])
    ->middleware('auth')
    ->name('verification.notice');

// Procesar el enlace de verificación
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

// Reenviar verificación
Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// Ruta para verificar el estado después de la verificación
Route::get('/email/verify-check', function () {
    if (auth()->user()->hasVerifiedEmail()) {
        return redirect('/home');
    }
    return back()->with('error', 'Tu correo electrónico aún no ha sido verificado.');
})->middleware('auth')->name('verification.check.status');

Route::get('/email/verify/check', function () {
    if (auth()->user()->hasVerifiedEmail()) {
        return redirect()->intended(config('adminlte.home'));
    }
    return back()->with('not_verified', true);
})->middleware(['auth'])->name('verification.check');