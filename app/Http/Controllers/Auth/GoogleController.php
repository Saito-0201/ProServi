<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\Events\Registered;

class GoogleController extends Controller
{
    /**
     * Redirección a Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->redirectUrl(config('services.google.redirect'))
            ->redirect();
    }

    /**
     * Manejo del callback de Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Verificar si el usuario ya existe
            $existingUser = User::where('email', $googleUser->getEmail())->first();

            if ($existingUser) {
                // Si el usuario existe pero no tiene google_id, actualizarlo
                if (empty($existingUser->google_id)) {
                    $existingUser->google_id = $googleUser->getId();
                    $existingUser->save();
                }
                
                Auth::login($existingUser);
                return redirect()->intended('/home');
            }

            // Crear datos temporales del usuario (SIN contraseña)
            $tempUser = [
                'name' => $googleUser->user['given_name'] ?? $googleUser->getName(),
                'lastname' => $googleUser->user['family_name'] ?? '',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => null,
                'avatar' => $googleUser->getAvatar(),
            ];

            // Guardar en sesión para completar registro
            session(['temp_user' => $tempUser]);

            return redirect()->route('complete.registration');

        } catch (\Exception $e) {
            return redirect('/register')
                ->with('error', 'Error al autenticar con Google: '.$e->getMessage());
        }
    }

    /**
     * Mostrar formulario para completar registro
     */
    public function showCompleteForm()
    {
        if (!session('temp_user')) {
            return redirect('/register')
                ->with('error', 'Por favor completa el registro primero');
        }

        return view('auth.complete-registration');
    }

    /**
     * Procesar formulario de completar registro
     */
    public function completeRegistration(Request $request)
    {
        $request->validate([
            'role' => 'required|in:Cliente,Prestador',
        ]);

        $tempUser = session('temp_user');

        $user = User::create([
            'name' => $tempUser['name'],
            'lastname' => $tempUser['lastname'],
            'email' => $tempUser['email'],
            'password' => $tempUser['password'],
            'google_id' => $tempUser['google_id'],
            'email_verified_at' => now(), // VERIFICACIÓN AUTOMÁTICA para Google
        ]);

        // Asignar rol seleccionado
        $user->syncRoles([$request->role]);

        // Disparar evento de registro (pero no enviará email de verificación)
        event(new Registered($user));

        // Limpiar sesión temporal
        session()->forget('temp_user');

        // Iniciar sesión y redirigir al home
        Auth::login($user);
        
        return redirect('/home')
            ->with('status', '¡Registro completado con éxito!');
    }

    /**
     * Método para que usuarios de Google establezcan una contraseña
     */
    public function setPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        
        if (is_null($user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
            
            return back()->with('status', 'Contraseña establecida correctamente.');
        }
        
        return back()->with('error', 'No puedes establecer una contraseña si ya tienes una.');
    }
}