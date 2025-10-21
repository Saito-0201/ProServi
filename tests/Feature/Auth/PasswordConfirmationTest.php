<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_puede_ver_formulario_confirmacion_contrasena()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/password/confirm');

        $response->assertStatus(200);
        $response->assertViewIs('auth.passwords.confirm');
    }

    /** @test */
    public function usuario_puede_confirmar_su_contrasena()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($user)->post('/password/confirm', [
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function confirmacion_falla_con_contrasena_incorrecta()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($user)->post('/password/confirm', [
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}