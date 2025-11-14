<?php

namespace Tests\Feature\Admin;

use App\Models\Categoria;
use App\Models\Servicio;
use App\Models\Subcategoria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ServicioManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $prestador;
    protected $categoria;
    protected $subcategoria;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();
        $this->admin->assignRole('Administrador');
        
        $this->prestador = User::factory()->create();
        $this->prestador->assignRole('Prestador');
        
        $this->categoria = Categoria::factory()->create();
        $this->subcategoria = Subcategoria::factory()->create([
            'categoria_id' => $this->categoria->id
        ]);
        
        $this->actingAs($this->admin);
    }

    /** @test */
    public function admin_puede_ver_lista_de_servicios()
    {
        Servicio::factory()->count(3)->create();

        $response = $this->get(route('admin.servicios.index'));

        $response->assertStatus(200);
        $response->assertViewHas('servicios');
    }

    /** @test */
    public function admin_puede_ver_formulario_crear_servicio()
    {
        try {
            $response = $this->get(route('admin.servicios.create'));
            
            if ($response->status() === 500) {
                // Esto mostrará el error real
                dd('Error en create:', $response->exception->getMessage(), $response->exception->getFile(), $response->exception->getLine());
            }
            
            $response->assertStatus(200);
        } catch (\Exception $e) {
            dd('Excepción:', $e->getMessage());
        }
    }

    /** @test */
    public function admin_puede_crear_nuevo_servicio()
    {
        Storage::fake('public');

        $servicioData = [
            'prestador_id' => $this->prestador->id,
            'categoria_id' => $this->categoria->id,
            'subcategoria_id' => $this->subcategoria->id,
            'titulo' => 'Servicio de Prueba',
            'descripcion' => 'Descripción del servicio de prueba',
            'tipo_precio' => 'fijo',
            'precio' => 100.00,
            'imagen' => UploadedFile::fake()->image('servicio.jpg'),
            'direccion' => 'Calle Principal 123',
            'ciudad' => 'Sacaba',
            'provincia' => 'Cochabamba',
            'pais' => 'Bolivia',
            'estado' => 'activo'
        ];

        $response = $this->post(route('admin.servicios.store'), $servicioData);

        $response->assertRedirect(route('admin.servicios.index'));
        $this->assertDatabaseHas('servicios', ['titulo' => 'Servicio de Prueba']);
    }

    /** @test */
    public function creacion_servicio_falla_sin_titulo()
    {
        $response = $this->post(route('admin.servicios.store'), [
            'prestador_id' => $this->prestador->id,
            'categoria_id' => $this->categoria->id,
            'subcategoria_id' => $this->subcategoria->id,
            'titulo' => '',
            'descripcion' => 'Descripción sin título',
            'tipo_precio' => 'fijo',
            'precio' => 100.00,
            'direccion' => 'Calle Principal 123',
            'ciudad' => 'Sacaba',
            'provincia' => 'Cochabamba',
            'pais' => 'Bolivia',
            'estado' => 'activo'
        ]);

        $response->assertSessionHasErrors('titulo');
        $this->assertDatabaseCount('servicios', 0);
    }

    /** @test */
    public function admin_puede_ver_detalles_servicio()
    {
        $servicio = Servicio::factory()->create();

        $response = $this->get(route('admin.servicios.show', $servicio));

        $response->assertStatus(200);
        $response->assertViewHas('servicio', $servicio);
    }

    /** @test */
    public function admin_puede_ver_formulario_editar_servicio()
    {
        $servicio = Servicio::factory()->create();

        $response = $this->get(route('admin.servicios.edit', $servicio));

        $response->assertStatus(200);
        $response->assertViewHas('servicio', $servicio);
    }

    /** @test */
    public function admin_puede_actualizar_servicio()
    {
        Storage::fake('public');
        $servicio = Servicio::factory()->create();

        $response = $this->put(route('admin.servicios.update', $servicio), [
            'prestador_id' => $servicio->prestador_id,
            'categoria_id' => $servicio->categoria_id,
            'subcategoria_id' => $servicio->subcategoria_id,
            'titulo' => 'Servicio Actualizado',
            'descripcion' => 'Descripción actualizada',
            'tipo_precio' => 'fijo',
            'precio' => 150.00,
            'imagen' => UploadedFile::fake()->image('nueva-imagen.jpg'),
            'direccion' => 'Nueva Dirección 456',
            'ciudad' => 'Cochabamba',
            'provincia' => 'Cochabamba',
            'pais' => 'Bolivia',
            'estado' => 'inactivo'
        ]);

        $response->assertRedirect(route('admin.servicios.index'));
        $this->assertDatabaseHas('servicios', [
            'id' => $servicio->id,
            'titulo' => 'Servicio Actualizado',
            'estado' => 'inactivo' // Verificamos que el estado se cambió
        ]);
    }

    /** @test */
    public function admin_puede_eliminar_servicio()
    {
        Storage::fake('public');
        $servicio = Servicio::factory()->create();

        $response = $this->delete(route('admin.servicios.destroy', $servicio));

        $response->assertRedirect(route('admin.servicios.index'));
        $this->assertDatabaseMissing('servicios', ['id' => $servicio->id]);
    }

    /** @test */
    public function admin_puede_obtener_subcategorias_por_categoria()
    {
        $subcategoria2 = Subcategoria::factory()->create([
            'categoria_id' => $this->categoria->id
        ]);

        $response = $this->get(route('admin.servicios.subcategorias', $this->categoria->id));

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }
}