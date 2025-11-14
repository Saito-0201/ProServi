<?php
namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\CreatesRoles;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase, CreatesRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createRoles();
        
        // Crear usuario admin para las pruebas
        $this->admin = User::factory()->create();
        $this->admin->assignRole('Administrador');
        
        $this->actingAs($this->admin);
    }

    /** @test */
    public function admin_puede_ver_lista_de_usuarios()
    {
        User::factory()->count(3)->create();

        $response = $this->get(route('admin.usuarios.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.usuarios.index');
        $response->assertViewHas('usuarios');
    }

    /** @test */
    public function admin_puede_ver_formulario_crear_usuario()
    {
        $response = $this->get(route('admin.usuarios.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.usuarios.create');
        $response->assertViewHas('roles');
    }

    /** @test */
    public function admin_puede_crear_nuevo_usuario()
    {
        $userData = [
            'name' => 'Nuevo',
            'lastname' => 'Usuario',
            'email' => 'nuevo@example.com',
            'role' => 'Cliente',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ];

        $response = $this->post(route('admin.usuarios.store'), $userData);

        $response->assertRedirect(route('admin.usuarios.index'));
        $response->assertSessionHas('mensaje', 'Usuario creado correctamente');
        
        $this->assertDatabaseHas('users', [
            'name' => 'Nuevo',
            'lastname' => 'Usuario',
            'email' => 'nuevo@example.com',
        ]);
        
        $user = User::where('email', 'nuevo@example.com')->first();
        $this->assertTrue($user->hasRole('Cliente'));
    }

    /** @test */
    public function admin_puede_ver_detalles_de_usuario()
    {
        $user = User::factory()->create();
        $user->assignRole('Cliente');

        $response = $this->get(route('admin.usuarios.show', $user->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.usuarios.show');
        $response->assertViewHas('usuario');
    }

    /** @test */
    public function admin_puede_ver_formulario_editar_usuario()
    {
        $user = User::factory()->create();
        $user->assignRole('Cliente');

        $response = $this->get(route('admin.usuarios.edit', $user->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.usuarios.edit');
        $response->assertViewHas(['usuario', 'roles']);
    }

    /** @test */
    public function admin_puede_actualizar_usuario()
    {
        $user = User::factory()->create();
        $user->assignRole('Cliente');

        $updateData = [
            'name' => 'Actualizado',
            'lastname' => 'Usuario',
            'email' => 'actualizado@example.com',
            'role' => 'Prestador',
        ];

        $response = $this->put(route('admin.usuarios.update', $user->id), $updateData);

        $response->assertRedirect(route('admin.usuarios.index'));
        $response->assertSessionHas('mensaje', 'Usuario actualizado correctamente');
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Actualizado',
            'email' => 'actualizado@example.com',
        ]);
        
        $updatedUser = User::find($user->id);
        $this->assertTrue($updatedUser->hasRole('Prestador'));
    }

    /** @test */
    public function admin_puede_actualizar_contrasena_de_usuario()
    {
        $user = User::factory()->create();
        $user->assignRole('Cliente');

        $updateData = [
            'name' => $user->name,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'role' => 'Cliente',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ];

        $response = $this->put(route('admin.usuarios.update', $user->id), $updateData);

        $response->assertRedirect(route('admin.usuarios.index'));
        
        $updatedUser = User::find($user->id);
        $this->assertTrue(Hash::check('NewPassword123!', $updatedUser->password));
    }

    /** @test */
    public function admin_puede_eliminar_usuario()
    {
        $user = User::factory()->create();
        $user->assignRole('Cliente');

        $response = $this->delete(route('admin.usuarios.destroy', $user->id));

        $response->assertRedirect(route('admin.usuarios.index'));
        $response->assertSessionHas('mensaje', 'Usuario eliminado correctamente');
        
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function creacion_usuario_falla_sin_datos_requeridos()
    {
        $response = $this->post(route('admin.usuarios.store'), []);

        $response->assertSessionHasErrors(['name', 'lastname', 'email', 'role', 'password']);
    }

    /** @test */
    public function creacion_usuario_falla_con_email_duplicado()
    {
        $existingUser = User::factory()->create(['email' => 'existente@example.com']);

        $userData = [
            'name' => 'Nuevo',
            'lastname' => 'Usuario',
            'email' => 'existente@example.com',
            'role' => 'Cliente',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ];

        $response = $this->post(route('admin.usuarios.store'), $userData);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function actualizacion_usuario_falla_con_email_duplicado()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $updateData = [
            'name' => 'Actualizado',
            'lastname' => 'Usuario',
            'email' => $user2->email, // Email duplicado
            'role' => 'Cliente',
        ];

        $response = $this->put(route('admin.usuarios.update', $user1->id), $updateData);

        $response->assertSessionHasErrors('email');
    }
}