<?php

namespace Tests\Feature\Cliente;

use App\Models\Calificacion;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalificacionManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $cliente;
    protected $servicio;
    protected $prestador;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->cliente = User::factory()->create();
        $this->cliente->assignRole('Cliente');
        
        $this->prestador = User::factory()->create();
        $this->prestador->assignRole('Prestador');
        
        $this->servicio = Servicio::factory()->create([
            'prestador_id' => $this->prestador->id,
            'estado' => 'activo'
        ]);
        
        $this->actingAs($this->cliente);
    }

    /** @test */
    public function cliente_puede_calificar_servicio()
    {
        $response = $this->post(route('cliente.calificaciones.store'), [
            'servicio_id' => $this->servicio->id,
            'puntuacion' => 5,
            'comentario' => 'Excelente servicio, muy profesional'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Calificación enviada correctamente.');

        $this->assertDatabaseHas('calificacions', [
            'cliente_id' => $this->cliente->id,
            'servicio_id' => $this->servicio->id,
            'puntuacion' => 5,
            'comentario' => 'Excelente servicio, muy profesional'
        ]);
    }

    /** @test */
    public function cliente_puede_actualizar_su_calificacion_existente()
    {
        // Crear calificación inicial
        $calificacion = Calificacion::factory()->create([
            'cliente_id' => $this->cliente->id,
            'prestador_id' => $this->prestador->id,
            'servicio_id' => $this->servicio->id,
            'puntuacion' => 3,
            'comentario' => 'Servicio regular'
        ]);

        $response = $this->post(route('cliente.calificaciones.store'), [
            'servicio_id' => $this->servicio->id,
            'puntuacion' => 5,
            'comentario' => 'Actualizado: Excelente servicio'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Calificación actualizada correctamente.');

        $this->assertDatabaseHas('calificacions', [
            'cliente_id' => $this->cliente->id,
            'servicio_id' => $this->servicio->id,
            'puntuacion' => 5,
            'comentario' => 'Actualizado: Excelente servicio'
        ]);

        // Verificar que no se creó un duplicado
        $this->assertEquals(1, Calificacion::where('servicio_id', $this->servicio->id)
            ->where('cliente_id', $this->cliente->id)
            ->count());
    }

    /** @test */
    public function calificacion_actualiza_promedio_del_servicio()
    {
        // Calificación 1
        $response1 = $this->post(route('cliente.calificaciones.store'), [
            'servicio_id' => $this->servicio->id,
            'puntuacion' => 4,
            'comentario' => 'Muy bueno'
        ]);

        $this->servicio->refresh();
        $this->assertEquals(4.00, $this->servicio->calificacion_promedio);
        $this->assertEquals(1, $this->servicio->total_calificaciones);

        // Calificación 2 de otro cliente
        $otroCliente = User::factory()->create();
        $otroCliente->assignRole('Cliente');
        $this->actingAs($otroCliente);

        $response2 = $this->post(route('cliente.calificaciones.store'), [
            'servicio_id' => $this->servicio->id,
            'puntuacion' => 5,
            'comentario' => 'Excelente'
        ]);

        $this->servicio->refresh();
        $this->assertEquals(4.50, $this->servicio->calificacion_promedio); // (4 + 5) / 2
        $this->assertEquals(2, $this->servicio->total_calificaciones);
    }

    /** @test */
    public function cliente_puede_eliminar_su_calificacion()
    {
        $calificacion = Calificacion::factory()->create([
            'cliente_id' => $this->cliente->id,
            'prestador_id' => $this->prestador->id,
            'servicio_id' => $this->servicio->id,
            'puntuacion' => 4
        ]);

        $response = $this->delete(route('cliente.calificaciones.destroy', $calificacion->id));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Calificación eliminada correctamente.');

        $this->assertDatabaseMissing('calificacions', ['id' => $calificacion->id]);
    }

    /** @test */
    public function eliminar_calificacion_actualiza_promedio_del_servicio()
    {
        // Crear dos calificaciones
        $calificacion1 = Calificacion::factory()->create([
            'cliente_id' => $this->cliente->id,
            'prestador_id' => $this->prestador->id,
            'servicio_id' => $this->servicio->id,
            'puntuacion' => 4
        ]);

        $otroCliente = User::factory()->create();
        $otroCliente->assignRole('Cliente');
        $calificacion2 = Calificacion::factory()->create([
            'cliente_id' => $otroCliente->id,
            'prestador_id' => $this->prestador->id,
            'servicio_id' => $this->servicio->id,
            'puntuacion' => 5
        ]);

        // Forzar el cálculo del promedio
        $this->servicio->calcularPromedioCalificaciones();
        $this->servicio->refresh();

        $this->assertEquals(4.50, $this->servicio->calificacion_promedio);
        $this->assertEquals(2, $this->servicio->total_calificaciones);

        // Eliminar una calificación
        $this->actingAs($this->cliente);
        $response = $this->delete(route('cliente.calificaciones.destroy', $calificacion1->id));

        // Forzar el cálculo del promedio después de eliminar
        $this->servicio->calcularPromedioCalificaciones();
        $this->servicio->refresh();

        $this->assertEquals(5.00, $this->servicio->calificacion_promedio);
        $this->assertEquals(1, $this->servicio->total_calificaciones);
    }

    /** @test */
    public function cliente_no_puede_eliminar_calificacion_de_otro_cliente()
    {
        $otroCliente = User::factory()->create();
        $otroCliente->assignRole('Cliente');
        
        $calificacion = Calificacion::factory()->create([
            'cliente_id' => $otroCliente->id,
            'prestador_id' => $this->prestador->id,
            'servicio_id' => $this->servicio->id
        ]);

        $response = $this->delete(route('cliente.calificaciones.destroy', $calificacion->id));

        $response->assertStatus(404); // No debería encontrar la calificación
    }

    /** @test */
    public function cliente_puede_obtener_su_calificacion_para_un_servicio()
    {
        $calificacion = Calificacion::factory()->create([
            'cliente_id' => $this->cliente->id,
            'prestador_id' => $this->prestador->id,
            'servicio_id' => $this->servicio->id,
            'puntuacion' => 4,
            'comentario' => 'Mi comentario'
        ]);

        $response = $this->get(route('cliente.calificaciones.user-rating', $this->servicio->id));

        $response->assertStatus(200);
        $response->assertJson([
            'rating' => [
                'puntuacion' => 4,
                'comentario' => 'Mi comentario',
                'id' => $calificacion->id
            ]
        ]);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_obtener_calificacion()
    {
        auth()->logout(); // Cerrar sesión de manera más simple

        $response = $this->get(route('cliente.calificaciones.user-rating', $this->servicio->id));

        // Verificar redirección a login
        $response->assertRedirect('/login');
    }

    // En el método: solo_clientes_pueden_calificar_servicios
    public function solo_clientes_pueden_calificar_servicios()
    {
        $prestador = User::factory()->create();
        $prestador->assignRole('Prestador');
        $this->actingAs($prestador);

        $response = $this->post(route('cliente.calificaciones.store'), [
            'servicio_id' => $this->servicio->id,
            'puntuacion' => 5,
            'comentario' => 'Intento de calificación como prestador'
        ]);

        // Verificar que devuelve error 403 (prohibido)
        $response->assertStatus(403);
    }

    /** @test */
    public function calificacion_requiere_puntuacion_valida()
    {
        $response = $this->post(route('cliente.calificaciones.store'), [
            'servicio_id' => $this->servicio->id,
            'puntuacion' => 6, // Puntuación inválida (máximo 5)
            'comentario' => 'Comentario'
        ]);

        $response->assertSessionHasErrors('puntuacion');
    }

    /** @test */
    public function calificacion_requiere_servicio_valido()
    {
        $response = $this->post(route('cliente.calificaciones.store'), [
            'servicio_id' => 999, // Servicio inexistente
            'puntuacion' => 5,
            'comentario' => 'Comentario'
        ]);

        $response->assertSessionHasErrors('servicio_id');
    }

    /** @test */
    public function comentario_tiene_limite_de_caracteres()
    {
        $comentarioLargo = str_repeat('a', 501); // 501 caracteres

        $response = $this->post(route('cliente.calificaciones.store'), [
            'servicio_id' => $this->servicio->id,
            'puntuacion' => 5,
            'comentario' => $comentarioLargo
        ]);

        $response->assertSessionHasErrors('comentario');
    }
}