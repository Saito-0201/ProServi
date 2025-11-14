<?php
// tests/Feature/Prestador/VerificationTest.php
namespace Tests\Feature\Prestador;

use App\Models\User;
use App\Models\Verificacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\CreatesRoles;
use Tests\TestCase;

class VerificationTest extends TestCase
{
    use RefreshDatabase, CreatesRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createRoles();
        
        $this->prestador = User::factory()->create();
        $this->prestador->assignRole('Prestador');
        
        $this->actingAs($this->prestador);
        
        Storage::fake('public');
    }

    /** @test */
    public function prestador_puede_ver_formulario_verificacion()
    {
        $response = $this->get(route('prestador.verificacion.create'));

        $response->assertStatus(200);
        $response->assertViewIs('prestador.verificacion.create');
    }

    /** @test */
    public function prestador_puede_enviar_solicitud_verificacion()
    {
        $files = [
            'foto_cara' => UploadedFile::fake()->image('foto.jpg'),
            'carnet_frente' => UploadedFile::fake()->image('frente.jpg'),
            'carnet_reverso' => UploadedFile::fake()->image('reverso.jpg'),
        ];

        $response = $this->post(route('prestador.verificacion.store'), $files);

        $response->assertRedirect(route('prestador.verificacion.estado'));
        $response->assertSessionHas('success', 'Solicitud de verificación enviada correctamente. Estará en revisión.');
        
        $this->assertDatabaseHas('verificaciones', [
            'usuario_id' => $this->prestador->id,
            'estado' => 'pendiente',
        ]);
        
        // Solo verificamos que se creó el registro, no los archivos específicos
    }

    /** @test */
    public function prestador_puede_ver_estado_verificacion()
    {
        // Crear verificación directamente
        $verificacion = Verificacion::create([
            'usuario_id' => $this->prestador->id,
            'estado' => 'pendiente',
            'ruta_imagen_carnet' => 'test.jpg',
            'ruta_reverso_carnet' => 'test_reverso.jpg',
            'ruta_foto_cara' => 'test_foto.jpg',
        ]);

        $response = $this->get(route('prestador.verificacion.estado'));

        $response->assertStatus(200);
        $response->assertViewIs('prestador.verificacion.estado');
        $response->assertViewHas('verificacion');
    }

    /** @test */
    public function prestador_puede_cancelar_solicitud_pendiente()
    {
        // Crear verificación directamente
        $verificacion = Verificacion::create([
            'usuario_id' => $this->prestador->id,
            'estado' => 'pendiente',
            'ruta_foto_cara' => 'verificaciones/fotos/test.jpg',
            'ruta_imagen_carnet' => 'verificaciones/carnets/frente.jpg',
            'ruta_reverso_carnet' => 'verificaciones/carnets/reverso.jpg',
        ]);

        $response = $this->delete(route('prestador.verificacion.destroy'));

        $response->assertRedirect(route('prestador.verificacion.estado'));
        $response->assertSessionHas('success', 'Solicitud de verificación cancelada correctamente.');
        
        $this->assertDatabaseMissing('verificaciones', ['id' => $verificacion->id]);
    }

    /** @test */
    public function prestador_no_puede_enviar_solicitud_si_ya_tiene_pendiente()
    {
        // Crear verificación pendiente directamente
        Verificacion::create([
            'usuario_id' => $this->prestador->id,
            'estado' => 'pendiente',
            'ruta_imagen_carnet' => 'test.jpg',
            'ruta_reverso_carnet' => 'test_reverso.jpg',
            'ruta_foto_cara' => 'test_foto.jpg',
        ]);

        $files = [
            'foto_cara' => UploadedFile::fake()->image('foto.jpg'),
            'carnet_frente' => UploadedFile::fake()->image('frente.jpg'),
            'carnet_reverso' => UploadedFile::fake()->image('reverso.jpg'),
        ];

        $response = $this->post(route('prestador.verificacion.store'), $files);

        $response->assertRedirect(route('prestador.verificacion.estado'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function prestador_puede_reenviar_solicitud_despues_de_rechazo()
    {
        // Crear verificación rechazada directamente
        $verificacion = Verificacion::create([
            'usuario_id' => $this->prestador->id,
            'estado' => 'rechazado',
            'motivo_rechazo' => 'Documentos no claros',
            'ruta_imagen_carnet' => 'test.jpg',
            'ruta_reverso_carnet' => 'test_reverso.jpg',
            'ruta_foto_cara' => 'test_foto.jpg',
        ]);

        $files = [
            'foto_cara' => UploadedFile::fake()->image('nueva_foto.jpg'),
            'carnet_frente' => UploadedFile::fake()->image('nuevo_frente.jpg'),
            'carnet_reverso' => UploadedFile::fake()->image('nuevo_reverso.jpg'),
        ];

        $response = $this->post(route('prestador.verificacion.store'), $files);

        $response->assertRedirect(route('prestador.verificacion.estado'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('verificaciones', [
            'usuario_id' => $this->prestador->id,
            'estado' => 'pendiente',
            'motivo_rechazo' => null,
        ]);
    }

    /** @test */
    public function solicitud_verificacion_falla_sin_archivos()
    {
        $response = $this->post(route('prestador.verificacion.store'), []);

        $response->assertSessionHasErrors(['foto_cara', 'carnet_frente', 'carnet_reverso']);
    }

    /** @test */
    public function solicitud_verificacion_falla_con_archivos_invalidos()
    {
        $files = [
            'foto_cara' => UploadedFile::fake()->create('document.pdf', 1000), // PDF en lugar de imagen
            'carnet_frente' => UploadedFile::fake()->image('frente.jpg')->size(7000), // Muy grande
            'carnet_reverso' => UploadedFile::fake()->image('reverso.jpg'),
        ];

        $response = $this->post(route('prestador.verificacion.store'), $files);

        $response->assertSessionHasErrors(['foto_cara', 'carnet_frente']);
    }
}