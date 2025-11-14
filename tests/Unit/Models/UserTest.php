<?php
// tests/Unit/Models/UserTest.php
namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Verificacion;
use App\Models\PrestadorInfo;
use App\Models\ClienteInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesRoles;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, CreatesRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createRoles();
    }

    /** @test */
    public function usuario_puede_tener_info_cliente()
    {
        $user = User::factory()->create();
        $user->assignRole('Cliente');
        
        // Crear directamente sin factory
        ClienteInfo::create([
            'usuario_id' => $user->id,
            'telefono' => '12345678',
        ]);

        $this->assertNotNull($user->clienteInfo);
        $this->assertEquals($user->id, $user->clienteInfo->usuario_id);
    }

    /** @test */
    public function usuario_puede_tener_info_prestador()
    {
        $user = User::factory()->create();
        $user->assignRole('Prestador');
        
        // Crear directamente sin factory
        PrestadorInfo::create([
            'usuario_id' => $user->id,
            'telefono' => '12345678',
            'verificado' => false,
        ]);

        $this->assertNotNull($user->prestadorInfo);
        $this->assertEquals($user->id, $user->prestadorInfo->usuario_id);
    }

    /** @test */
    public function usuario_puede_tener_verificacion()
    {
        $user = User::factory()->create();
        $user->assignRole('Prestador');
        
        // Crear directamente sin factory
        Verificacion::create([
            'usuario_id' => $user->id,
            'estado' => 'pendiente',
            'ruta_imagen_carnet' => 'test.jpg',
            'ruta_reverso_carnet' => 'test_reverso.jpg',
            'ruta_foto_cara' => 'test_foto.jpg',
        ]);

        $this->assertNotNull($user->verificacion);
        $this->assertEquals($user->id, $user->verificacion->usuario_id);
    }

    /** @test */
    public function puede_verificar_si_es_cliente()
    {
        $user = User::factory()->create();
        $user->assignRole('Cliente');

        $this->assertTrue($user->esCliente());
        $this->assertFalse($user->esPrestador());
    }

    /** @test */
    public function puede_verificar_si_es_prestador()
    {
        $user = User::factory()->create();
        $user->assignRole('Prestador');

        $this->assertTrue($user->esPrestador());
        $this->assertFalse($user->esCliente());
    }

    /** @test */
    public function puede_verificar_si_tiene_solicitud_verificacion()
    {
        $user = User::factory()->create();
        $user->assignRole('Prestador');

        $this->assertFalse($user->tieneSolicitudVerificacion());

        // Crear verificación directamente
        Verificacion::create([
            'usuario_id' => $user->id,
            'estado' => 'pendiente',
            'ruta_imagen_carnet' => 'test.jpg',
            'ruta_reverso_carnet' => 'test_reverso.jpg',
            'ruta_foto_cara' => 'test_foto.jpg',
        ]);

        $this->assertTrue($user->tieneSolicitudVerificacion());
    }

    /** @test */
    public function puede_verificar_si_esta_verificado()
    {
        $user = User::factory()->create();
        $user->assignRole('Prestador');

        $this->assertFalse($user->estaVerificado());

        // Crear verificación aprobada directamente
        Verificacion::create([
            'usuario_id' => $user->id,
            'estado' => 'aprobado',
            'ruta_imagen_carnet' => 'test.jpg',
            'ruta_reverso_carnet' => 'test_reverso.jpg',
            'ruta_foto_cara' => 'test_foto.jpg',
        ]);

        $this->assertTrue($user->estaVerificado());
    }

    /** @test */
    public function puede_obtener_estado_verificacion()
    {
        $user = User::factory()->create();
        $user->assignRole('Prestador');

        $this->assertEquals('Sin solicitud', $user->estado_verificacion);

        // Crear verificación directamente
        Verificacion::create([
            'usuario_id' => $user->id,
            'estado' => 'pendiente',
            'ruta_imagen_carnet' => 'test.jpg',
            'ruta_reverso_carnet' => 'test_reverso.jpg',
            'ruta_foto_cara' => 'test_foto.jpg',
        ]);

        $this->assertEquals('pendiente', $user->estado_verificacion);
    }

    /** @test */
    public function usuario_manual_recibe_email_verificacion()
    {
        $user = User::factory()->unverified()->create();

        $notificationSent = false;
        
        // En una implementación real, esto enviaría el email
        // Para el test, verificamos que el método no lanza excepción
        try {
            $user->sendEmailVerificationNotification();
            $notificationSent = true;
        } catch (\Exception $e) {
            $notificationSent = false;
        }

        $this->assertTrue($notificationSent);
    }

    /** @test */
    public function puede_obtener_fecha_creacion_formateada()
    {
        $user = User::factory()->create([
            'created_at' => '2024-01-15 10:30:00'
        ]);

        $fechaFormateada = $user->getFechaCreacionFormateada();

        $this->assertStringContainsString('enero', strtolower($fechaFormateada));
        $this->assertStringContainsString('2024', $fechaFormateada);
    }
}