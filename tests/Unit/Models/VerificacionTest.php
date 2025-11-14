<?php
// tests/Unit/Models/VerificacionTest.php
namespace Tests\Unit\Models;

use App\Models\Verificacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesRoles;
use Tests\TestCase;

class VerificacionTest extends TestCase
{
    use RefreshDatabase, CreatesRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createRoles();
    }

    /** @test */
    public function verificacion_pertenece_a_usuario()
    {
        $user = User::factory()->create();
        $user->assignRole('Prestador');
        
        // Crear directamente sin factory
        $verificacion = Verificacion::create([
            'usuario_id' => $user->id,
            'estado' => 'pendiente',
            'ruta_imagen_carnet' => 'test.jpg',
            'ruta_reverso_carnet' => 'test_reverso.jpg',
            'ruta_foto_cara' => 'test_foto.jpg',
        ]);

        $this->assertInstanceOf(User::class, $verificacion->usuario);
        $this->assertEquals($user->id, $verificacion->usuario->id);
    }

    /** @test */
    public function puede_filtrar_verificaciones_pendientes()
    {
        $user1 = User::factory()->create();
        $user1->assignRole('Prestador');
        
        $user2 = User::factory()->create();
        $user2->assignRole('Prestador');
        
        $user3 = User::factory()->create();
        $user3->assignRole('Prestador');
        
        // Crear verificaciones directamente
        Verificacion::create([
            'usuario_id' => $user1->id,
            'estado' => 'pendiente',
            'ruta_imagen_carnet' => 'test1.jpg',
            'ruta_reverso_carnet' => 'test1_reverso.jpg',
            'ruta_foto_cara' => 'test1_foto.jpg',
        ]);
        
        Verificacion::create([
            'usuario_id' => $user2->id,
            'estado' => 'aprobado',
            'ruta_imagen_carnet' => 'test2.jpg',
            'ruta_reverso_carnet' => 'test2_reverso.jpg',
            'ruta_foto_cara' => 'test2_foto.jpg',
        ]);
        
        Verificacion::create([
            'usuario_id' => $user3->id,
            'estado' => 'rechazado',
            'ruta_imagen_carnet' => 'test3.jpg',
            'ruta_reverso_carnet' => 'test3_reverso.jpg',
            'ruta_foto_cara' => 'test3_foto.jpg',
        ]);

        $pendientes = Verificacion::pendientes()->get();

        $this->assertCount(1, $pendientes);
        $this->assertEquals('pendiente', $pendientes->first()->estado);
    }

    /** @test */
    public function puede_filtrar_verificaciones_aprobadas()
    {
        $user1 = User::factory()->create();
        $user1->assignRole('Prestador');
        
        $user2 = User::factory()->create();
        $user2->assignRole('Prestador');
        
        $user3 = User::factory()->create();
        $user3->assignRole('Prestador');
        
        // Crear verificaciones directamente
        Verificacion::create([
            'usuario_id' => $user1->id,
            'estado' => 'pendiente',
            'ruta_imagen_carnet' => 'test1.jpg',
            'ruta_reverso_carnet' => 'test1_reverso.jpg',
            'ruta_foto_cara' => 'test1_foto.jpg',
        ]);
        
        Verificacion::create([
            'usuario_id' => $user2->id,
            'estado' => 'aprobado',
            'ruta_imagen_carnet' => 'test2.jpg',
            'ruta_reverso_carnet' => 'test2_reverso.jpg',
            'ruta_foto_cara' => 'test2_foto.jpg',
        ]);
        
        Verificacion::create([
            'usuario_id' => $user3->id,
            'estado' => 'aprobado',
            'ruta_imagen_carnet' => 'test3.jpg',
            'ruta_reverso_carnet' => 'test3_reverso.jpg',
            'ruta_foto_cara' => 'test3_foto.jpg',
        ]);

        $aprobadas = Verificacion::aprobadas()->get();

        $this->assertCount(2, $aprobadas);
        $this->assertEquals('aprobado', $aprobadas->first()->estado);
    }

    /** @test */
    public function puede_filtrar_verificaciones_rechazadas()
    {
        $user1 = User::factory()->create();
        $user1->assignRole('Prestador');
        
        $user2 = User::factory()->create();
        $user2->assignRole('Prestador');
        
        $user3 = User::factory()->create();
        $user3->assignRole('Prestador');
        
        // Crear verificaciones directamente
        Verificacion::create([
            'usuario_id' => $user1->id,
            'estado' => 'pendiente',
            'ruta_imagen_carnet' => 'test1.jpg',
            'ruta_reverso_carnet' => 'test1_reverso.jpg',
            'ruta_foto_cara' => 'test1_foto.jpg',
        ]);
        
        Verificacion::create([
            'usuario_id' => $user2->id,
            'estado' => 'rechazado',
            'ruta_imagen_carnet' => 'test2.jpg',
            'ruta_reverso_carnet' => 'test2_reverso.jpg',
            'ruta_foto_cara' => 'test2_foto.jpg',
        ]);
        
        Verificacion::create([
            'usuario_id' => $user3->id,
            'estado' => 'rechazado',
            'ruta_imagen_carnet' => 'test3.jpg',
            'ruta_reverso_carnet' => 'test3_reverso.jpg',
            'ruta_foto_cara' => 'test3_foto.jpg',
        ]);

        $rechazadas = Verificacion::rechazadas()->get();

        $this->assertCount(2, $rechazadas);
        $this->assertEquals('rechazado', $rechazadas->first()->estado);
    }

    /** @test */
    public function fecha_emision_se_convierte_a_carbon()
    {
        $user = User::factory()->create();
        $user->assignRole('Prestador');
        
        $verificacion = Verificacion::create([
            'usuario_id' => $user->id,
            'estado' => 'pendiente',
            'fecha_emision' => '2024-01-15',
            'ruta_imagen_carnet' => 'test.jpg',
            'ruta_reverso_carnet' => 'test_reverso.jpg',
            'ruta_foto_cara' => 'test_foto.jpg',
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $verificacion->fecha_emision);
        $this->assertEquals('2024-01-15', $verificacion->fecha_emision->format('Y-m-d'));
    }

    /** @test */
    public function fecha_verificacion_se_convierte_a_carbon()
    {
        $user = User::factory()->create();
        $user->assignRole('Prestador');
        
        $verificacion = Verificacion::create([
            'usuario_id' => $user->id,
            'estado' => 'aprobado',
            'fecha_verificacion' => '2024-01-15 10:30:00',
            'ruta_imagen_carnet' => 'test.jpg',
            'ruta_reverso_carnet' => 'test_reverso.jpg',
            'ruta_foto_cara' => 'test_foto.jpg',
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $verificacion->fecha_verificacion);
        $this->assertEquals('2024-01-15', $verificacion->fecha_verificacion->format('Y-m-d'));
    }
}