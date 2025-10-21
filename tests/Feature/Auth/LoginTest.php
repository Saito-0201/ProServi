<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\CreatesRoles;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase, CreatesRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createRoles();
    }

    /** @test */
    public function usuario_puede_iniciar_sesion_como_cliente()
    {
        $user = User::factory()->create([
            'email' => 'cliente@example.com',
            'password' => Hash::make('Password123!'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('Cliente');

        $response = $this->post('/login', [
            'email' => 'cliente@example.com',
            'password' => 'Password123!',
        ]);

        // El usuario debe estar autenticado
        $this->assertAuthenticatedAs($user);
        
        // Verificar que redirige a /home (que luego redirigirá a /cliente)
        $response->assertRedirect('/home');
        
        // Seguir la redirección a /home y verificar que redirige a /cliente
        $homeResponse = $this->get('/home');
        $homeResponse->assertRedirect('/cliente');
    }

    /** @test */
    public function login_falla_con_cuenta_google_sin_password()
    {
        $user = User::factory()->googleAccount()->create([
            'email' => 'google@example.com',
        ]);

        $response = $this->post('/login', [
            'email' => 'google@example.com',
            'password' => 'cualquier-password',
        ]);

        // Verificar que redirige y tiene errores
        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function usuario_puede_cerrar_sesion()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */
    public function login_falla_con_credenciales_incorrectas()
    {
        $user = User::factory()->create([
            'email' => 'usuario@example.com',
            'password' => Hash::make('Password123!'),
        ]);

        $response = $this->post('/login', [
            'email' => 'usuario@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function login_falla_si_email_no_verificado()
    {
        $user = User::factory()->unverified()->create([
            'email' => 'noverificado@example.com',
            'password' => Hash::make('Password123!'),
        ]);
        $user->assignRole('Cliente');

        $response = $this->post('/login', [
            'email' => 'noverificado@example.com',
            'password' => 'Password123!',
        ]);

        // El usuario debe estar autenticado
        $this->assertAuthenticatedAs($user);
        
        // Después del login, intenta acceder a una ruta protegida
        // Esto debería redirigir a /email/verify debido al middleware 'verified'
        $protectedResponse = $this->get('/cliente');
        
        $protectedResponse->assertRedirect('/email/verify');
    }
}