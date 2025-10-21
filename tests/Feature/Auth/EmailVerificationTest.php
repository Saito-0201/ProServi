<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\CreatesRoles;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase, CreatesRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createRoles();
    }

    /** @test */
    public function usuario_puede_ver_notificacion_verificacion_email()
    {
        $user = User::factory()->unverified()->create();
        $user->assignRole('Cliente');

        $response = $this->actingAs($user)->get('/email/verify');

        $response->assertStatus(200);
        $response->assertViewIs('auth.verify-email');
    }

    /** @test */
    public function usuario_puede_verificar_su_email()
    {
        Event::fake();

        $user = User::factory()->unverified()->create();
        $user->assignRole('Cliente');

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertViewIs('auth.email-verified');
    }

    /** @test */
    public function verificacion_email_falla_con_hash_invalido()
    {
        $user = User::factory()->unverified()->create();
        $user->assignRole('Cliente');

        // Crear URL con hash que NO coincide con el email del usuario
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email@example.com')] // Hash de email diferente
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        // El usuario NO debe estar verificado
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
        
        // Debe retornar error 403
        $response->assertStatus(403);
    }

    /** @test */
    public function usuario_puede_reenviar_email_verificacion()
    {
        $user = User::factory()->unverified()->create();
        $user->assignRole('Cliente');

        $response = $this->actingAs($user)
            ->post('/email/verification-notification');

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Se ha reenviado el enlace de verificación.');
    }

    /** @test */
    public function usuario_verificado_es_redirigido_de_notificacion()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $user->assignRole('Cliente');

        $response = $this->actingAs($user)->get('/email/verify');

        $response->assertRedirect('/home');
    }
}