<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;
use Tests\CreatesRoles;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase, CreatesRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createRoles();
    }

    /** @test */
    public function usuario_puede_solicitar_link_reset_contrasena()
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'usuario@example.com',
        ]);
        $user->assignRole('Cliente');

        $response = $this->post('/password/email', [
            'email' => 'usuario@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    /** @test */
    public function solicitud_reset_falla_con_cuenta_google()
    {
        $user = User::factory()->googleAccount()->create([
            'email' => 'google@example.com',
        ]);

        $response = $this->post('/password/email', [
            'email' => 'google@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function usuario_puede_ver_formulario_reset_contrasena()
    {
        $response = $this->get('/password/reset/token');

        $response->assertStatus(200);
        $response->assertViewIs('auth.passwords.reset');
    }

    /** @test */
    public function usuario_puede_resetear_su_contrasena()
    {
        $user = User::factory()->create([
            'email' => 'usuario@example.com',
            'password' => Hash::make('oldpassword'),
        ]);
        $user->assignRole('Cliente');

        // Obtener un token real del broker de passwords
        $token = app('auth.password.broker')->createToken($user);

        $response = $this->post('/password/reset', [
            'token' => $token,
            'email' => 'usuario@example.com',
            'password' => 'Newpassword123!',
            'password_confirmation' => 'Newpassword123!',
        ]);

        // Verificar redirección a /home (que luego redirigirá según el rol)
        $response->assertRedirect('/home');
        $this->assertTrue(Hash::check('Newpassword123!', $user->fresh()->password));
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function reset_contrasena_falla_con_token_invalido()
    {
        $user = User::factory()->create([
            'email' => 'usuario@example.com',
        ]);

        $response = $this->post('/password/reset', [
            'token' => 'token-invalido',
            'email' => 'usuario@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function reset_contrasena_falla_con_password_corta()
    {
        $user = User::factory()->create([
            'email' => 'usuario@example.com',
        ]);

        $response = $this->post('/password/reset', [
            'token' => 'test-token',
            'email' => 'usuario@example.com',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function reset_contrasena_falla_sin_confirmacion()
    {
        $user = User::factory()->create([
            'email' => 'usuario@example.com',
        ]);

        $response = $this->post('/password/reset', [
            'token' => 'test-token',
            'email' => 'usuario@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function reset_contrasena_falla_con_cuenta_google()
    {
        $user = User::factory()->create([
            'email' => 'google@example.com',
            'password' => null, // Cuenta Google
            'google_id' => '123456',
        ]);

        $response = $this->post('/password/reset', [
            'token' => 'test-token',
            'email' => 'google@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
    }
}