<?php

namespace Tests\Feature\Admin;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CategoriaManagementTest extends TestCase
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
    public function admin_puede_ver_lista_de_categorias()
    {
        Categoria::factory()->count(3)->create();

        $response = $this->get(route('admin.categorias.index'));

        $response->assertStatus(200);
        $response->assertViewHas('categorias');
    }

    /** @test */
    public function admin_puede_ver_formulario_crear_categoria()
    {
        $response = $this->get(route('admin.categorias.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categorias.create');
    }

    /** @test */
    public function admin_puede_crear_nueva_categoria()
    {
        $categoriaData = [
            'nombre_cat' => 'Categoría de Prueba',
            'descripcion_cat' => 'Descripción de prueba',
            'estado' => 'activo'
        ];

        $response = $this->post(route('admin.categorias.store'), $categoriaData);

        $response->assertRedirect(route('admin.categorias.index'));
        $this->assertDatabaseHas('categorias', ['nombre_cat' => 'Categoría de Prueba']);
    }

    /** @test */
    public function creacion_categoria_falla_sin_nombre()
    {
        $response = $this->post(route('admin.categorias.store'), [
            'descripcion_cat' => 'Descripción sin nombre',
            'estado' => 'activo'
        ]);

        $response->assertSessionHasErrors('nombre_cat');
        $this->assertDatabaseCount('categorias', 0);
    }

    /** @test */
    public function admin_puede_ver_formulario_editar_categoria()
    {
        $categoria = Categoria::factory()->create();

        $response = $this->get(route('admin.categorias.edit', $categoria));

        $response->assertStatus(200);
        $response->assertViewHas('categoria', $categoria);
    }

    /** @test */
    public function admin_puede_actualizar_categoria()
    {
        $categoria = Categoria::factory()->create();

        $response = $this->put(route('admin.categorias.update', $categoria), [
            'nombre_cat' => 'Categoría Actualizada',
            'descripcion_cat' => 'Descripción actualizada',
            'estado' => 'inactivo'
        ]);

        $response->assertRedirect(route('admin.categorias.index'));
        $this->assertDatabaseHas('categorias', [
            'id' => $categoria->id,
            'nombre_cat' => 'Categoría Actualizada'
        ]);
    }

    /** @test */
    public function admin_puede_eliminar_categoria()
    {
        $categoria = Categoria::factory()->create();

        $response = $this->delete(route('admin.categorias.destroy', $categoria));

        $response->assertRedirect(route('admin.categorias.index'));
        $this->assertDatabaseMissing('categorias', ['id' => $categoria->id]);
    }

    /** @test */
    public function actualizacion_categoria_falla_sin_nombre()
    {
        $categoria = Categoria::factory()->create();

        $response = $this->put(route('admin.categorias.update', $categoria), [
            'nombre_cat' => '',
            'descripcion_cat' => 'Descripción sin nombre',
            'estado' => 'activo'
        ]);

        $response->assertSessionHasErrors('nombre_cat');
    }
}