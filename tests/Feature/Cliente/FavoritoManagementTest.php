<?php

namespace Tests\Feature\Cliente;

use App\Models\Favorito;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoritoManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $cliente;
    protected $servicio;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->cliente = User::factory()->create();
        $this->cliente->assignRole('Cliente');
        
        $this->servicio = Servicio::factory()->create(['estado' => 'activo']);
        
        $this->actingAs($this->cliente);
    }

    /** @test */
    public function cliente_puede_ver_lista_de_sus_favoritos()
    {
        Favorito::factory()->create([
            'cliente_id' => $this->cliente->id,
            'servicio_id' => $this->servicio->id
        ]);

        $response = $this->get(route('cliente.favoritos.index'));

        $response->assertStatus(200);
        $response->assertViewIs('cliente.favoritos.index');
        $response->assertViewHas('favoritos');
    }

    /** @test */
    public function cliente_puede_agregar_servicio_a_favoritos()
    {
        $response = $this->post(route('cliente.favoritos.toggle'), [
            'servicio_id' => $this->servicio->id
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_favorite' => true,
            'message' => 'Servicio agregado a favoritos'
        ]);

        $this->assertDatabaseHas('favoritos', [
            'cliente_id' => $this->cliente->id,
            'servicio_id' => $this->servicio->id
        ]);
    }

    /** @test */
    public function cliente_puede_eliminar_servicio_de_favoritos()
    {
        // Primero agregar a favoritos
        Favorito::factory()->create([
            'cliente_id' => $this->cliente->id,
            'servicio_id' => $this->servicio->id
        ]);

        $response = $this->post(route('cliente.favoritos.toggle'), [
            'servicio_id' => $this->servicio->id
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_favorite' => false,
            'message' => 'Servicio eliminado de favoritos'
        ]);

        $this->assertDatabaseMissing('favoritos', [
            'cliente_id' => $this->cliente->id,
            'servicio_id' => $this->servicio->id
        ]);
    }

    /** @test */
    public function no_se_puede_agregar_servicio_inactivo_a_favoritos()
    {
        $servicioInactivo = Servicio::factory()->create(['estado' => 'inactivo']);

        $response = $this->post(route('cliente.favoritos.toggle'), [
            'servicio_id' => $servicioInactivo->id
        ]);

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'Servicio no disponible'
        ]);
    }

    /** @test */
    public function cliente_puede_verificar_si_servicio_es_favorito()
    {
        // Servicio no está en favoritos
        $response = $this->get(route('cliente.favoritos.check', $this->servicio->id));

        $response->assertStatus(200);
        $response->assertJson(['is_favorite' => false]);

        // Agregar a favoritos
        Favorito::factory()->create([
            'cliente_id' => $this->cliente->id,
            'servicio_id' => $this->servicio->id
        ]);

        $response = $this->get(route('cliente.favoritos.check', $this->servicio->id));

        $response->assertStatus(200);
        $response->assertJson(['is_favorite' => true]);
    }

    /** @test */
    public function cliente_puede_eliminar_favorito_desde_lista()
    {
        $favorito = Favorito::factory()->create([
            'cliente_id' => $this->cliente->id,
            'servicio_id' => $this->servicio->id
        ]);

        $response = $this->delete(route('cliente.favoritos.destroy', $favorito->id));

        $response->assertRedirect(route('cliente.favoritos.index'));
        $this->assertDatabaseMissing('favoritos', ['id' => $favorito->id]);
    }

    /** @test */
    public function cliente_no_puede_eliminar_favorito_de_otro_cliente()
    {
        $otroCliente = User::factory()->create();
        $otroCliente->assignRole('Cliente');
        
        $favorito = Favorito::factory()->create([
            'cliente_id' => $otroCliente->id,
            'servicio_id' => $this->servicio->id
        ]);

        $response = $this->delete(route('cliente.favoritos.destroy', $favorito->id));

        $response->assertStatus(404); // No debería encontrar el favorito
    }

    /** @test */
    public function toggle_favorito_funciona_correctamente()
    {
        // Primera llamada: agregar
        $response1 = $this->post(route('cliente.favoritos.toggle'), [
            'servicio_id' => $this->servicio->id
        ]);

        $response1->assertJson(['is_favorite' => true]);
        $this->assertDatabaseHas('favoritos', [
            'cliente_id' => $this->cliente->id,
            'servicio_id' => $this->servicio->id
        ]);

        // Segunda llamada: eliminar
        $response2 = $this->post(route('cliente.favoritos.toggle'), [
            'servicio_id' => $this->servicio->id
        ]);

        $response2->assertJson(['is_favorite' => false]);
        $this->assertDatabaseMissing('favoritos', [
            'cliente_id' => $this->cliente->id,
            'servicio_id' => $this->servicio->id
        ]);
    }
}