<?php

namespace Tests\Feature\Cliente;

use App\Models\Servicio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactoTest extends TestCase
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
    public function cliente_puede_ver_informacion_de_contacto_del_prestador()
    {
        // Crear información del prestador
        $prestadorInfo = $this->prestador->prestadorInfo()->create([
            'telefono' => '77450083',
            'verificado' => 1
        ]);

        $response = $this->get(route('cliente.servicios.show', $this->servicio));

        $response->assertStatus(200);
        $response->assertSee('77450083'); // Debería mostrar el teléfono
        $response->assertSee('Contactar'); // Debería mostrar botón de contacto
    }

    /** @test */
    public function cliente_puede_generar_enlace_whatsapp_para_contactar()
    {
        $prestadorInfo = $this->prestador->prestadorInfo()->create([
            'telefono' => '59177450083'
        ]);

        $response = $this->get(route('cliente.servicios.show', $this->servicio));

        $response->assertStatus(200);
        // Debería generar enlace WhatsApp con formato correcto
        $response->assertSee('https://wa.me/59177450083');
    }

    /** @test */
    public function enlace_whatsapp_incluye_mensaje_personalizado()
    {
        $prestadorInfo = $this->prestador->prestadorInfo()->create([
            'telefono' => '59177450083'
        ]);

        $response = $this->get(route('cliente.servicios.show', $this->servicio));

        $response->assertStatus(200);
        // El enlace debería incluir información del servicio
        $response->assertSee(urlencode($this->servicio->titulo));
        $response->assertSee(urlencode('PROSERVI'));
    }

    /** @test */
    public function contacto_muestra_estado_verificacion_del_prestador()
    {
        // Prestador verificado
        $prestadorInfo = $this->prestador->prestadorInfo()->create([
            'verificado' => 1
        ]);

        $response = $this->get(route('cliente.servicios.show', $this->servicio));

        $response->assertStatus(200);
        $response->assertSee('Verificado'); // Debería mostrar badge de verificado

        // Prestador no verificado
        $prestadorInfo->update(['verificado' => 0]);
        $response = $this->get(route('cliente.servicios.show', $this->servicio));

        $response->assertStatus(200);
        $response->assertDontSee('Verificado');
    }

    /** @test */
    public function cliente_puede_contactar_sin_telefono_del_prestador()
    {
        // Prestador sin teléfono en su información
        $prestadorInfo = $this->prestador->prestadorInfo()->create([
            'telefono' => null
        ]);

        $response = $this->get(route('cliente.servicios.show', $this->servicio));

        $response->assertStatus(200);
        // Debería manejar el caso cuando no hay teléfono
        // Podría mostrar un mensaje alternativo o usar otro método de contacto
    }

    /** @test */
    public function informacion_de_contacto_se_muestra_solo_para_servicios_activos()
    {
        $servicioInactivo = Servicio::factory()->create([
            'prestador_id' => $this->prestador->id,
            'estado' => 'inactivo'
        ]);

        $response = $this->get(route('cliente.servicios.show', $servicioInactivo));

        $response->assertStatus(200);
        // Podría no mostrar botones de contacto para servicios inactivos
        // o mostrar un mensaje de no disponible
    }
}