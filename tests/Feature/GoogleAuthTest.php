<?php
// tests/Feature/GoogleAuthTest.php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\CreatesRoles;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase, CreatesRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createRoles();
    }

    /** @test */
    public function usuario_puede_redirigir_a_google_oauth()
    {
        $response = $this->get(route('login.google'));

        $response->assertRedirect();
        // Solo verificamos que es una redirección, no el contenido específico
    }

    /** @test */
    public function usuario_existente_puede_iniciar_sesion_con_google()
    {
        $user = User::factory()->create([
            'email' => 'existente@example.com',
            'google_id' => 'google123',
        ]);

        $this->mockSocialiteUser('existente@example.com', 'google123');

        $response = $this->get(route('login.google.callback'));

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('home'));
    }

    /** @test */
    public function usuario_existente_sin_google_id_actualiza_y_inicia_sesion()
    {
        $user = User::factory()->create([
            'email' => 'existente@example.com',
            'google_id' => null, // Usuario existente sin Google ID
        ]);

        $this->mockSocialiteUser('existente@example.com', 'nuevo_google_id');

        $response = $this->get(route('login.google.callback'));

        $this->assertAuthenticatedAs($user);
        
        // Verificar que se actualizó el google_id
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'google_id' => 'nuevo_google_id',
        ]);
    }

    /** @test */
    public function nuevo_usuario_es_redirigido_a_completar_registro()
    {
        $this->mockSocialiteUser('nuevo@example.com', 'google456');

        $response = $this->get(route('login.google.callback'));

        $response->assertRedirect(route('complete.registration'));
        
        // Verificar que los datos temporales se guardaron en sesión
        $this->assertTrue(session()->has('temp_user'));
        $this->assertEquals('nuevo@example.com', session('temp_user')['email']);
    }

    /** @test */
    public function usuario_puede_completar_registro_con_google()
    {
        // Simular datos temporales en sesión
        $tempUser = [
            'name' => 'Juan',
            'lastname' => 'Google',
            'email' => 'juan.google@example.com',
            'google_id' => 'google789',
            'password' => null,
            'avatar' => 'https://avatar.url',
        ];

        session(['temp_user' => $tempUser]);

        $response = $this->post(route('complete.registration.submit'), [
            'role' => 'Cliente',
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('status', '¡Registro completado con éxito!');
        
        // Verificar que el usuario se creó correctamente
        $this->assertDatabaseHas('users', [
            'email' => 'juan.google@example.com',
            'google_id' => 'google789',
            'email_verified_at' => now(), // Debe estar verificado automáticamente
        ]);
        
        $user = User::where('email', 'juan.google@example.com')->first();
        $this->assertTrue($user->hasRole('Cliente'));
        $this->assertAuthenticatedAs($user);
        
        // Verificar que se limpió la sesión temporal
        $this->assertFalse(session()->has('temp_user'));
    }

    /** @test */
    public function completar_registro_falla_sin_rol()
    {
        session(['temp_user' => [
            'name' => 'Juan',
            'lastname' => 'Google',
            'email' => 'juan@example.com',
            'google_id' => 'google123',
        ]]);

        $response = $this->post(route('complete.registration.submit'), []);

        $response->assertSessionHasErrors('role');
    }

    /**
     * Mock Socialite user
     */
    private function mockSocialiteUser($email, $googleId)
    {
        $socialiteUser = new SocialiteUser();
        $socialiteUser->map([
            'id' => $googleId,
            'name' => 'Test User',
            'email' => $email,
            'avatar' => 'https://avatar.url',
            'given_name' => 'Test',
            'family_name' => 'User',
        ]);

        Socialite::shouldReceive('driver->user')
            ->andReturn($socialiteUser);
    }
}