<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Redirección después del registro - A VERIFICACIÓN
     */
    protected $redirectTo = '/email/verify';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Validador de datos de registro
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'string', 'in:Cliente,Prestador'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['required', 'accepted'],
        ], [
            'role.in' => 'Debe seleccionar un rol válido.',
            'terms.required' => 'Debe aceptar los términos y condiciones.',
        ]);
    }

    /**
     * Muestra el formulario de registro
     */
    public function showRegistrationForm()
    {
        $roles = Role::whereIn('name', ['Cliente', 'Prestador'])->get();
        return view('auth.register', compact('roles'));
    }

    /**
     * Crea un nuevo usuario después de validar el registro
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'google_id' => null, // Asegurar que sea null para usuarios manuales
            'email_verified_at' => null, // No verificado automáticamente
        ]);
    }

    /**
     * Sobrescribe el método register para asignar el rol
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        // Asignar rol después de crear el usuario
        $user->syncRoles([$request->role]);

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
}