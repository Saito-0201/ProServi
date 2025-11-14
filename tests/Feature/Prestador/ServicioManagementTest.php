<?php

namespace Tests\Feature\Prestador;

use App\Models\Categoria;
use App\Models\Servicio;
use App\Models\Subcategoria;
use App\Models\User;
use App\Models\PrestadorInfo; // Añadir este import
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ServicioManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $prestador;
    protected $categoria;
    protected $subcategoria;
    protected $prestadorInfo; // Añadir esta propiedad

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->prestador = User::factory()->create();
        $this->prestador->assignRole('Prestador');
        
        // Crear PrestadorInfo con teléfono para el prestador
        $this->prestadorInfo = PrestadorInfo::factory()->create([
            'usuario_id' => $this->prestador->id,
            'telefono' => '+59112345678' // Añadir teléfono
        ]);
        
        $this->categoria = Categoria::factory()->create();
        $this->subcategoria = Subcategoria::factory()->create([
            'categoria_id' => $this->categoria->id
        ]);
        
        $this->actingAs($this->prestador);
    }

    /** @test */
    public function prestador_puede_ver_lista_de_sus_servicios()
    {
        Servicio::factory()->count(2)->create(['prestador_id' => $this->prestador->id]);
        Servicio::factory()->create(['prestador_id' => User::factory()->create()->id]); // Servicio de otro prestador

        $response = $this->get(route('prestador.servicios.index'));

        $response->assertStatus(200);
        $response->assertViewHas('servicios');
        // Verifica que solo vea sus servicios
        $this->assertEquals(2, $response->viewData('servicios')->count());
    }

    /** @test */
    public function prestador_puede_ver_formulario_crear_servicio()
    {
        $response = $this->get(route('prestador.servicios.create'));

        $response->assertStatus(200);
        $response->assertViewIs('prestador.servicios.create');
        $response->assertViewHas(['categorias', 'subcategorias', 'google_maps_api_key']);
    }

    /** @test */
    public function prestador_puede_crear_nuevo_servicio()
    {
        Storage::fake('public');

        $servicioData = [
            'categoria_id' => $this->categoria->id,
            'subcategoria_id' => $this->subcategoria->id,
            'titulo' => 'Mi Servicio de Prueba',
            'descripcion' => 'Descripción de mi servicio',
            'tipo_precio' => 'fijo',
            'precio' => 150.00,
            'imagen' => UploadedFile::fake()->image('mi-servicio.jpg'),
            'direccion' => 'Mi Dirección 123',
            'ciudad' => 'Sacaba',
            'provincia' => 'Cochabamba',
            'pais' => 'Bolivia',
            'latitud' => -17.40264043,
            'longitud' => -66.03693462
        ];

        $response = $this->post(route('prestador.servicios.store'), $servicioData);

        $response->assertRedirect(route('prestador.servicios.index'));
        $this->assertDatabaseHas('servicios', [
            'titulo' => 'Mi Servicio de Prueba',
            'prestador_id' => $this->prestador->id
        ]);
    }

    /** @test */
    public function prestador_puede_ver_detalles_de_su_servicio()
    {
        $servicio = Servicio::factory()->create(['prestador_id' => $this->prestador->id]);

        $response = $this->get(route('prestador.servicios.show', $servicio));

        $response->assertStatus(200);
        $response->assertViewHas('servicio', $servicio);
    }

    /** @test */
    public function prestador_no_puede_ver_servicio_de_otro_prestador()
    {
        $otroPrestador = User::factory()->create();
        $otroPrestador->assignRole('Prestador');
        $servicio = Servicio::factory()->create(['prestador_id' => $otroPrestador->id]);

        $response = $this->get(route('prestador.servicios.show', $servicio));

        $response->assertStatus(403);
    }

    /** @test */
    public function prestador_puede_ver_formulario_editar_su_servicio()
    {
        $servicio = Servicio::factory()->create(['prestador_id' => $this->prestador->id]);

        $response = $this->get(route('prestador.servicios.edit', $servicio));

        $response->assertStatus(200);
        $response->assertViewHas('servicio', $servicio);
        $response->assertViewHas(['categorias', 'subcategorias', 'google_maps_api_key']);
    }

    /** @test */
    public function prestador_puede_actualizar_su_servicio()
    {
        Storage::fake('public');
        $servicio = Servicio::factory()->create(['prestador_id' => $this->prestador->id]);

        $response = $this->put(route('prestador.servicios.update', $servicio), [
            'categoria_id' => $this->categoria->id,
            'subcategoria_id' => $this->subcategoria->id,
            'titulo' => 'Servicio Actualizado por Prestador',
            'descripcion' => 'Descripción actualizada',
            'tipo_precio' => 'cotizacion',
            'precio' => null,
            'imagen' => UploadedFile::fake()->image('imagen-actualizada.jpg'),
            'direccion' => 'Nueva Dirección 789',
            'ciudad' => 'Cochabamba',
            'provincia' => 'Cochabamba',
            'pais' => 'Bolivia',
            'latitud' => -17.40264043,
            'longitud' => -66.03693462,
            'estado' => 'inactivo'
        ]);

        $response->assertRedirect(route('prestador.servicios.index'));
        $this->assertDatabaseHas('servicios', [
            'id' => $servicio->id,
            'titulo' => 'Servicio Actualizado por Prestador',
            'estado' => 'inactivo'
        ]);
    }

    /** @test */
    public function prestador_no_puede_actualizar_servicio_de_otro_prestador()
    {
        $otroPrestador = User::factory()->create();
        $otroPrestador->assignRole('Prestador');
        $servicio = Servicio::factory()->create(['prestador_id' => $otroPrestador->id]);

        $response = $this->put(route('prestador.servicios.update', $servicio), [
            'titulo' => 'Intento de actualización'
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function prestador_puede_eliminar_su_servicio()
    {
        Storage::fake('public');
        $servicio = Servicio::factory()->create(['prestador_id' => $this->prestador->id]);

        $response = $this->delete(route('prestador.servicios.destroy', $servicio));

        $response->assertRedirect(route('prestador.servicios.index'));
        $this->assertDatabaseMissing('servicios', ['id' => $servicio->id]);
    }

    /** @test */
    public function prestador_puede_obtener_subcategorias_por_categoria()
    {
        $subcategoria2 = Subcategoria::factory()->create([
            'categoria_id' => $this->categoria->id
        ]);

        $response = $this->get(route('prestador.servicios.subcategorias', $this->categoria));

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    /** @test */
    public function creacion_servicio_con_precio_fijo_requiere_precio()
    {
        $response = $this->post(route('prestador.servicios.store'), [
            'categoria_id' => $this->categoria->id,
            'subcategoria_id' => $this->subcategoria->id,
            'titulo' => 'Servicio sin precio',
            'descripcion' => 'Descripción',
            'tipo_precio' => 'fijo',
            'precio' => null,
            'direccion' => 'Dirección',
            'ciudad' => 'Sacaba',
            'provincia' => 'Cochabamba',
            'pais' => 'Bolivia', // Añadir país requerido
            'latitud' => -17.40264043, // Añadir latitud
            'longitud' => -66.03693462 // Añadir longitud
        ]);

        $response->assertSessionHasErrors('precio');
        $this->assertDatabaseCount('servicios', 0);
    }

    /** @test */
    public function creacion_servicio_con_cotizacion_no_requiere_precio()
    {
        Storage::fake('public');

        $response = $this->post(route('prestador.servicios.store'), [
            'categoria_id' => $this->categoria->id,
            'subcategoria_id' => $this->subcategoria->id,
            'titulo' => 'Servicio por cotización',
            'descripcion' => 'Descripción',
            'tipo_precio' => 'cotizacion',
            'precio' => null,
            'imagen' => UploadedFile::fake()->image('servicio.jpg'),
            'direccion' => 'Dirección',
            'ciudad' => 'Sacaba',
            'provincia' => 'Cochabamba',
            'pais' => 'Bolivia',
            'latitud' => -17.40264043, // Añadir latitud
            'longitud' => -66.03693462 // Añadir longitud
        ]);

        $response->assertRedirect(route('prestador.servicios.index'));
        $this->assertDatabaseHas('servicios', [
            'titulo' => 'Servicio por cotización',
            'tipo_precio' => 'cotizacion',
            'precio' => null
        ]);
    }

    /** @test */
    public function prestador_sin_telefono_es_redirigido_al_crear_servicio()
    {
        // Crear un prestador sin teléfono
        $prestadorSinTelefono = User::factory()->create();
        $prestadorSinTelefono->assignRole('Prestador');
        // No crear PrestadorInfo o crear uno sin teléfono
        PrestadorInfo::factory()->create([
            'usuario_id' => $prestadorSinTelefono->id,
            'telefono' => null
        ]);
        
        $this->actingAs($prestadorSinTelefono);

        $response = $this->get(route('prestador.servicios.create'));

        $response->assertStatus(200);
        $response->assertViewIs('prestador.servicios.sin-telefono');
    }
}