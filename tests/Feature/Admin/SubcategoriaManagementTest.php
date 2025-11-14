<?php

namespace Tests\Feature\Admin;

use App\Models\Categoria;
use App\Models\Servicio;
use App\Models\Subcategoria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubcategoriaManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();
        $this->admin->assignRole('Administrador');
        
        $this->actingAs($this->admin);
    }

    /** @test */
    public function admin_puede_ver_lista_de_subcategorias()
    {
        Subcategoria::factory()->count(3)->create();

        $response = $this->get(route('admin.subcategorias.index'));

        $response->assertStatus(200);
        $response->assertViewHas('subcategorias');
    }

    /** @test */
    public function admin_puede_ver_formulario_crear_subcategoria()
    {
        Categoria::factory()->create();

        $response = $this->get(route('admin.subcategorias.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.subcategorias.create');
        $response->assertViewHas('categorias');
    }

    /** @test */
    public function admin_puede_crear_nueva_subcategoria()
    {
        $categoria = Categoria::factory()->create();

        $subcategoriaData = [
            'categoria_id' => $categoria->id,
            'nombre' => 'Subcategoría de Prueba',
            'descripcion' => 'Descripción de prueba'
        ];

        $response = $this->post(route('admin.subcategorias.store'), $subcategoriaData);

        $response->assertRedirect(route('admin.subcategorias.index'));
        $this->assertDatabaseHas('subcategorias', ['nombre' => 'Subcategoría de Prueba']);
    }

    /** @test */
    public function creacion_subcategoria_falla_sin_nombre()
    {
        $categoria = Categoria::factory()->create();

        $response = $this->post(route('admin.subcategorias.store'), [
            'categoria_id' => $categoria->id,
            'nombre' => '',
            'descripcion' => 'Descripción sin nombre'
        ]);

        $response->assertSessionHasErrors('nombre');
        $this->assertDatabaseCount('subcategorias', 0);
    }

    /** @test */
    public function creacion_subcategoria_falla_sin_categoria()
    {
        $response = $this->post(route('admin.subcategorias.store'), [
            'categoria_id' => 999,
            'nombre' => 'Subcategoría sin categoría',
            'descripcion' => 'Descripción'
        ]);

        $response->assertSessionHasErrors('categoria_id');
        $this->assertDatabaseCount('subcategorias', 0);
    }

    /** @test */
    public function admin_puede_ver_formulario_editar_subcategoria()
    {
        $subcategoria = Subcategoria::factory()->create();

        $response = $this->get(route('admin.subcategorias.edit', $subcategoria));

        $response->assertStatus(200);
        $response->assertViewHas('subcategoria', $subcategoria);
        $response->assertViewHas('categorias');
    }

    /** @test */
    public function admin_puede_actualizar_subcategoria()
    {
        $subcategoria = Subcategoria::factory()->create();
        $nuevaCategoria = Categoria::factory()->create();

        $response = $this->put(route('admin.subcategorias.update', $subcategoria), [
            'categoria_id' => $nuevaCategoria->id,
            'nombre' => 'Subcategoría Actualizada',
            'descripcion' => 'Descripción actualizada'
        ]);

        $response->assertRedirect(route('admin.subcategorias.index'));
        $this->assertDatabaseHas('subcategorias', [
            'id' => $subcategoria->id,
            'nombre' => 'Subcategoría Actualizada',
            'categoria_id' => $nuevaCategoria->id
        ]);
    }

    /** @test */
    public function admin_puede_eliminar_subcategoria()
    {
        $subcategoria = Subcategoria::factory()->create();

        $response = $this->delete(route('admin.subcategorias.destroy', $subcategoria));

        $response->assertRedirect(route('admin.subcategorias.index'));
        $this->assertDatabaseMissing('subcategorias', ['id' => $subcategoria->id]);
    }

    /** @test */
    public function no_se_puede_eliminar_subcategoria_con_servicios()
    {
        $subcategoria = Subcategoria::factory()->create();
        
        // Crear un servicio asociado a esta subcategoría
        $servicio = Servicio::factory()->create([
            'subcategoria_id' => $subcategoria->id,
            'estado' => 'activo'
        ]);

        $response = $this->delete(route('admin.subcategorias.destroy', $subcategoria));

        // Verificaciones principales:
        // 1. La subcategoría sigue en la base de datos
        $this->assertDatabaseHas('subcategorias', ['id' => $subcategoria->id]);
        
        // 2. Verificar que hubo redirección (puede ser con éxito o error)
        $response->assertRedirect();
        
        // 3. Opcional: verificar que el servicio sigue existiendo
        $this->assertDatabaseHas('servicios', ['id' => $servicio->id]);
    }
}