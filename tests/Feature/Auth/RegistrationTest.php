<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\CreatesRoles;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase, CreatesRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createRoles();
    }

    /** @test */
    public function usuario_puede_ver_formulario_de_registro()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    /** @test */
    public function usuario_puede_registrarse_como_cliente()
    {
        Event::fake();

        $response = $this->post('/register', [
            'name' => 'Juan',
            'lastname' => 'Pérez',
            'email' => 'juan@example.com',
            'role' => 'Cliente',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => 'on',
        ]);

        $response->assertRedirect('/email/verify');
        
        $this->assertDatabaseHas('users', [
            'name' => 'Juan',
            'lastname' => 'Pérez',
            'email' => 'juan@example.com',
        ]);

        $user = User::where('email', 'juan@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('Password123!', $user->password));
        $this->assertTrue($user->hasRole('Cliente'));
        $this->assertNull($user->email_verified_at);

        Event::assertDispatched(Registered::class);
    }

    /** @test */
    public function usuario_puede_registrarse_como_prestador()
    {
        Event::fake();

        $response = $this->post('/register', [
            'name' => 'María',
            'lastname' => 'García',
            'email' => 'maria@example.com',
            'role' => 'Prestador',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => 'on',
        ]);

        $response->assertRedirect('/email/verify');
        
        $user = User::where('email', 'maria@example.com')->first();
        $this->assertTrue($user->hasRole('Prestador'));
    }

    /** @test */
    public function registro_falla_sin_nombre()
    {
        $response = $this->post('/register', [
            'name' => '',
            'lastname' => 'Pérez',
            'email' => 'juan@example.com',
            'role' => 'Cliente',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => 'on',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('users', ['email' => 'juan@example.com']);
    }

    /** @test */
    public function registro_falla_sin_apellido()
    {
        $response = $this->post('/register', [
            'name' => 'Juan',
            'lastname' => '',
            'email' => 'juan@example.com',
            'role' => 'Cliente',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => 'on',
        ]);

        $response->assertSessionHasErrors('lastname');
    }

    /** @test */
    public function registro_falla_con_email_invalido()
    {
        $response = $this->post('/register', [
            'name' => 'Juan',
            'lastname' => 'Pérez',
            'email' => 'email-invalido',
            'role' => 'Cliente',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => 'on',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function registro_falla_con_email_duplicado()
    {
        User::factory()->create(['email' => 'juan@example.com']);

        $response = $this->post('/register', [
            'name' => 'Juan',
            'lastname' => 'Pérez',
            'email' => 'juan@example.com',
            'role' => 'Cliente',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => 'on',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function registro_falla_sin_rol_valido()
    {
        $response = $this->post('/register', [
            'name' => 'Juan',
            'lastname' => 'Pérez',
            'email' => 'juan@example.com',
            'role' => 'RolInvalido',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => 'on',
        ]);

        $response->assertSessionHasErrors('role');
    }

    /** @test */
    public function registro_falla_con_password_corta()
    {
        $response = $this->post('/register', [
            'name' => 'Juan',
            'lastname' => 'Pérez',
            'email' => 'juan@example.com',
            'role' => 'Cliente',
            'password' => '123',
            'password_confirmation' => '123',
            'terms' => 'on',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function registro_falla_sin_confirmacion_password()
    {
        $response = $this->post('/register', [
            'name' => 'Juan',
            'lastname' => 'Pérez',
            'email' => 'juan@example.com',
            'role' => 'Cliente',
            'password' => 'Password123!',
            'password_confirmation' => 'Password456!',
            'terms' => 'on',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function registro_falla_sin_aceptar_terminos()
    {
        $response = $this->post('/register', [
            'name' => 'Juan',
            'lastname' => 'Pérez',
            'email' => 'juan@example.com',
            'role' => 'Cliente',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => '',
        ]);

        $response->assertSessionHasErrors('terms');
    }
}