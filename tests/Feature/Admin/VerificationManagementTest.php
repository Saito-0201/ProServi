<?php
// tests/Feature/Admin/VerificationManagementTest.php
namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Verificacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\CreatesRoles;
use Tests\TestCase;

class VerificationManagementTest extends TestCase
{
    use RefreshDatabase, CreatesRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createRoles();
        
        $this->admin = User::factory()->create();
        $this->admin->assignRole('Administrador');
        
        $this->actingAs($this->admin);
        
        Storage::fake('public');
    }

    /** @test */
    public function admin_puede_ver_lista_verificaciones()
    {
        Verificacion::factory()->count(3)->create();

        $response = $this->get(route('admin.verificaciones.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.verificaciones.index');
        $response->assertViewHas('verificaciones');
    }

    /** @test */
    public function admin_puede_ver_detalles_verificacion()
    {
        $verificacion = Verificacion::factory()->create();

        $response = $this->get(route('admin.verificaciones.show', $verificacion->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.verificaciones.show');
        $response->assertViewHas('verificacion');
    }

    /** @test */
    public function admin_puede_aprobar_verificacion()
    {
        $verificacion = Verificacion::factory()->create(['estado' => 'pendiente']);

        $response = $this->patch(route('admin.verificaciones.aprobar', $verificacion->id));

        $response->assertRedirect(route('admin.verificaciones.index'));
        $response->assertSessionHas('mensaje', 'Verificación aprobada correctamente');
        
        $this->assertDatabaseHas('verificaciones', [
            'id' => $verificacion->id,
            'estado' => 'aprobado',
            'fecha_verificacion' => now(),
            'motivo_rechazo' => null,
        ]);
    }

    /** @test */
    public function admin_puede_rechazar_verificacion()
    {
        $verificacion = Verificacion::factory()->create(['estado' => 'pendiente']);

        $rechazoData = [
            'motivo_rechazo' => 'Documentos no legibles o incompletos',
        ];

        $response = $this->patch(route('admin.verificaciones.rechazar', $verificacion->id), $rechazoData);

        $response->assertRedirect(route('admin.verificaciones.index'));
        $response->assertSessionHas('mensaje', 'Verificación rechazada correctamente');
        
        $this->assertDatabaseHas('verificaciones', [
            'id' => $verificacion->id,
            'estado' => 'rechazado',
            'fecha_verificacion' => now(),
            'motivo_rechazo' => 'Documentos no legibles o incompletos',
        ]);
    }

    /** @test */
    public function admin_puede_actualizar_verificacion()
    {
        $verificacion = Verificacion::factory()->create(['estado' => 'pendiente']);

        $updateData = [
            'estado' => 'aprobado',
            'numero_carnet' => '123456789',
            'fecha_emision' => '2024-01-15',
        ];

        $response = $this->put(route('admin.verificaciones.update', $verificacion->id), $updateData);

        $response->assertRedirect(route('admin.verificaciones.index'));
        $response->assertSessionHas('mensaje', 'Verificación actualizada correctamente');
        
        $this->assertDatabaseHas('verificaciones', [
            'id' => $verificacion->id,
            'estado' => 'aprobado',
            'numero_carnet' => '123456789',
        ]);
    }

    /** @test */
    public function rechazo_verificacion_falla_sin_motivo()
    {
        $verificacion = Verificacion::factory()->create(['estado' => 'pendiente']);

        $response = $this->patch(route('admin.verificaciones.rechazar', $verificacion->id), [
            'motivo_rechazo' => '', // Motivo vacío
        ]);

        $response->assertSessionHasErrors('motivo_rechazo');
    }

    /** @test */
    public function aprobacion_verificacion_actualiza_prestador_info()
    {
        $user = User::factory()->create();
        $user->assignRole('Prestador');
        
        $prestadorInfo = \App\Models\PrestadorInfo::factory()->create([
            'usuario_id' => $user->id,
            'verificado' => false,
        ]);
        
        $verificacion = Verificacion::factory()->create([
            'usuario_id' => $user->id,
            'estado' => 'pendiente',
        ]);

        $this->patch(route('admin.verificaciones.aprobar', $verificacion->id));

        $this->assertDatabaseHas('prestadores_info', [
            'usuario_id' => $user->id,
            'verificado' => true,
        ]);
    }
}