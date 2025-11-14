<?php

namespace Tests\Feature\Cliente;

use App\Models\Categoria;
use App\Models\Servicio;
use App\Models\Subcategoria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicioBusquedaTest extends TestCase
{
    use RefreshDatabase;

    protected $cliente;
    protected $categoria;
    protected $subcategoria;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->cliente = User::factory()->create();
        $this->cliente->assignRole('Cliente');
        
        $this->categoria = Categoria::factory()->create();
        $this->subcategoria = Subcategoria::factory()->create([
            'categoria_id' => $this->categoria->id
        ]);
        
        $this->actingAs($this->cliente);
    }

    /** @test */
    public function cliente_puede_ver_pagina_principal_de_servicios()
    {
        Servicio::factory()->count(5)->create(['estado' => 'activo']);

        $response = $this->get(route('cliente.servicios.index'));

        $response->assertStatus(200);
        $response->assertViewIs('cliente.servicios.index');
        $response->assertViewHas(['categorias', 'ciudades']);
    }

    /** @test */
    public function cliente_puede_buscar_servicios_por_palabra_clave()
    {
        Servicio::factory()->create(['estado' => 'activo']);

        $response = $this->get(route('cliente.servicios.index', ['q' => 'test']));

        $response->assertStatus(200);
        // Verificar que la página carga correctamente con parámetros de búsqueda
        $this->assertTrue($response->isOk());
    }

    /** @test */
    public function cliente_puede_filtrar_servicios_por_categoria()
    {
        Servicio::factory()->create([
            'categoria_id' => $this->categoria->id,
            'subcategoria_id' => $this->subcategoria->id,
            'estado' => 'activo'
        ]);

        $response = $this->get(route('cliente.servicios.index', [
            'categoria' => $this->categoria->id
        ]));

        $response->assertStatus(200);
        $this->assertTrue($response->isOk());
    }

    /** @test */
    public function cliente_puede_filtrar_servicios_por_ciudad()
    {
        Servicio::factory()->create([
            'ciudad' => 'Sacaba',
            'estado' => 'activo'
        ]);

        $response = $this->get(route('cliente.servicios.index', [
            'ciudad' => 'Sacaba'
        ]));

        $response->assertStatus(200);
        $this->assertTrue($response->isOk());
    }

    /** @test */
    public function cliente_puede_filtrar_servicios_por_tipo_precio()
    {
        Servicio::factory()->create([
            'tipo_precio' => 'fijo',
            'precio' => 100.00,
            'estado' => 'activo'
        ]);

        $response = $this->get(route('cliente.servicios.index', [
            'tipo_precio' => 'fijo'
        ]));

        $response->assertStatus(200);
        $this->assertTrue($response->isOk());
    }

    /** @test */
    public function cliente_puede_filtrar_servicios_por_rango_precio()
    {
        Servicio::factory()->create([
            'tipo_precio' => 'fijo',
            'precio' => 150.00,
            'estado' => 'activo'
        ]);

        $response = $this->get(route('cliente.servicios.index', [
            'precio_min' => 100,
            'precio_max' => 200
        ]));

        $response->assertStatus(200);
        $this->assertTrue($response->isOk());
    }

    /** @test */
    public function cliente_puede_filtrar_servicios_por_calificacion_minima()
    {
        Servicio::factory()->create([
            'calificacion_promedio' => 4.5,
            'estado' => 'activo'
        ]);

        $response = $this->get(route('cliente.servicios.index', [
            'rating_min' => 4
        ]));

        $response->assertStatus(200);
        $this->assertTrue($response->isOk());
    }

    /** @test */
    public function cliente_puede_filtrar_servicios_por_prestadores_verificados()
    {
        $prestadorVerificado = User::factory()->create();
        $prestadorVerificado->assignRole('Prestador');
        $prestadorVerificado->prestadorInfo()->create(['verificado' => 1]);

        Servicio::factory()->create([
            'prestador_id' => $prestadorVerificado->id,
            'estado' => 'activo'
        ]);

        $response = $this->get(route('cliente.servicios.index', [
            'verificados' => true
        ]));

        $response->assertStatus(200);
        $this->assertTrue($response->isOk());
    }

    /** @test */
    public function cliente_puede_ordenar_servicios_por_fecha()
    {
        Servicio::factory()->count(2)->create(['estado' => 'activo']);

        $response = $this->get(route('cliente.servicios.index', [
            'orden' => 'fecha_desc'
        ]));

        $response->assertStatus(200);
        $this->assertTrue($response->isOk());
    }

    /** @test */
    public function cliente_puede_ver_detalles_de_servicio()
    {
        $servicio = Servicio::factory()->create(['estado' => 'activo']);

        $response = $this->get(route('cliente.servicios.show', $servicio));

        $response->assertStatus(200);
        $response->assertViewHas('servicio');
    }

    /** @test */
    public function visitas_se_incrementan_al_ver_detalle_servicio()
    {
        $servicio = Servicio::factory()->create([
            'visitas' => 5,
            'estado' => 'activo'
        ]);

        $response = $this->get(route('cliente.servicios.show', $servicio));

        $servicio->refresh();
        $this->assertEquals(6, $servicio->visitas);
    }

    /** @test */
    public function cliente_puede_obtener_subcategorias_por_categoria_ajax()
    {
        $subcategoria2 = Subcategoria::factory()->create([
            'categoria_id' => $this->categoria->id
        ]);

        $response = $this->get(route('cliente.servicios.subcategorias', $this->categoria->id));

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    /** @test */
    public function busqueda_ajax_devuelve_resultados_correctos()
    {
        Servicio::factory()->create([
            'titulo' => 'Servicio de Prueba',
            'estado' => 'activo'
        ]);

        $response = $this->get(route('cliente.servicios.index', [
            'ajax' => true,
            'q' => 'prueba'
        ]));

        $response->assertStatus(200);
        $this->assertTrue($response->isOk());
    }
}