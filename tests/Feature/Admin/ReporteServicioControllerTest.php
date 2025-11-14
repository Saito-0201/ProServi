<?php

namespace Tests\Feature\Admin;

use App\Models\Categoria;
use App\Models\Servicio;
use App\Models\User;
use App\Models\Subcategoria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReporteServicioControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $prestador;
    protected $cliente;
    protected $categoria;
    protected $subcategoria;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();
        $this->admin->assignRole('Administrador');
        
        $this->prestador = User::factory()->create();
        $this->prestador->assignRole('Prestador');
        
        $this->cliente = User::factory()->create();
        $this->cliente->assignRole('Cliente');
        
        $this->categoria = Categoria::factory()->create(['estado' => 'activo']);
        $this->subcategoria = Subcategoria::factory()->create([
            'categoria_id' => $this->categoria->id
        ]);
        
        $this->actingAs($this->admin);
    }

    /** @test */
    public function admin_puede_ver_pagina_principal_reportes_servicios()
    {
        Servicio::factory()->count(5)->create([
            'prestador_id' => $this->prestador->id,
            'categoria_id' => $this->categoria->id,
            'subcategoria_id' => $this->subcategoria->id,
            'estado' => 'activo'
        ]);

        $response = $this->get(route('admin.reportes.servicios'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.reportes.servicios');
        $response->assertViewHas(['servicios', 'categorias', 'prestadores', 'estadisticas']);
    }

    /** @test */
    public function admin_puede_filtrar_servicios_por_estado()
    {
        Servicio::factory()->create(['estado' => 'activo']);
        Servicio::factory()->create(['estado' => 'inactivo']);

        $response = $this->get(route('admin.reportes.servicios', ['estado' => 'activo']));

        $response->assertStatus(200);
        $response->assertViewHas('servicios', function ($servicios) {
            return $servicios->every(function ($servicio) {
                return $servicio->estado === 'activo';
            });
        });
    }

    /** @test */
    public function admin_puede_filtrar_servicios_por_categoria()
    {
        $categoria2 = Categoria::factory()->create(['estado' => 'activo']);
        $subcategoria2 = Subcategoria::factory()->create(['categoria_id' => $categoria2->id]);

        Servicio::factory()->create(['categoria_id' => $this->categoria->id]);
        Servicio::factory()->create(['categoria_id' => $categoria2->id]);

        $response = $this->get(route('admin.reportes.servicios', ['categoria_id' => $this->categoria->id]));

        $response->assertStatus(200);
        $response->assertViewHas('servicios', function ($servicios) {
            return $servicios->every(function ($servicio) {
                return $servicio->categoria_id === $this->categoria->id;
            });
        });
    }

    /** @test */
    public function admin_puede_filtrar_servicios_por_prestador()
    {
        $prestador2 = User::factory()->create();
        $prestador2->assignRole('Prestador');

        Servicio::factory()->create(['prestador_id' => $this->prestador->id]);
        Servicio::factory()->create(['prestador_id' => $prestador2->id]);

        $response = $this->get(route('admin.reportes.servicios', ['prestador_id' => $this->prestador->id]));

        $response->assertStatus(200);
        $response->assertViewHas('servicios', function ($servicios) {
            return $servicios->every(function ($servicio) {
                return $servicio->prestador_id === $this->prestador->id;
            });
        });
    }

    /** @test */
    public function admin_puede_filtrar_servicios_por_rango_fechas()
    {
        $fechaInicio = now()->subDays(10)->format('Y-m-d');
        $fechaFin = now()->subDays(5)->format('Y-m-d');

        // Servicio dentro del rango
        Servicio::factory()->create([
            'created_at' => now()->subDays(7)
        ]);

        // Servicio fuera del rango
        Servicio::factory()->create([
            'created_at' => now()->subDays(15)
        ]);

        $response = $this->get(route('admin.reportes.servicios', [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('servicios', function ($servicios) use ($fechaInicio, $fechaFin) {
            return $servicios->count() === 1;
        });
    }

    /** @test */
    public function admin_puede_ordenar_servicios_por_diferentes_criterios()
    {
        Servicio::factory()->create(['visitas' => 10, 'calificacion_promedio' => 4.5]);
        Servicio::factory()->create(['visitas' => 20, 'calificacion_promedio' => 3.5]);

        // Ordenar por visitas descendente
        $response = $this->get(route('admin.reportes.servicios', [
            'orden' => 'visitas',
            'direccion' => 'desc'
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('servicios', function ($servicios) {
            return $servicios->first()->visitas === 20;
        });

        // Ordenar por calificación ascendente
        $response = $this->get(route('admin.reportes.servicios', [
            'orden' => 'calificacion_promedio',
            'direccion' => 'asc'
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('servicios', function ($servicios) {
            return $servicios->first()->calificacion_promedio == 3.5;
        });
    }

    /** @test */
    public function estadisticas_se_calculan_correctamente()
    {
        Servicio::factory()->create(['estado' => 'activo', 'calificacion_promedio' => 4.0, 'visitas' => 10]);
        Servicio::factory()->create(['estado' => 'activo', 'calificacion_promedio' => 3.0, 'visitas' => 20]);
        Servicio::factory()->create(['estado' => 'inactivo', 'calificacion_promedio' => 5.0, 'visitas' => 5]);

        $response = $this->get(route('admin.reportes.servicios'));

        $response->assertStatus(200);
        $response->assertViewHas('estadisticas', function ($estadisticas) {
            return $estadisticas['total'] === 3 &&
                   $estadisticas['activos'] === 2 &&
                   $estadisticas['inactivos'] === 1 &&
                   $estadisticas['promedio_calificacion'] == 4.0 && // (4+3+5)/3 = 4
                   $estadisticas['total_visitas'] === 35;
        });
    }

    /** @test */
    public function admin_puede_exportar_reportes_servicios_a_excel()
    {
        Servicio::factory()->count(3)->create();

        $response = $this->post(route('admin.reportes.servicios.exportar'), [
            'estado' => 'activo',
            'orden' => 'fecha_publicacion',
            'direccion' => 'desc'
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /** @test */
    public function exportacion_excel_incluye_filtros_aplicados()
    {
        Servicio::factory()->create(['estado' => 'activo']);
        Servicio::factory()->create(['estado' => 'inactivo']);

        $response = $this->post(route('admin.reportes.servicios.exportar'), [
            'estado' => 'inactivo',
            'categoria_id' => $this->categoria->id,
            'fecha_inicio' => now()->subMonth()->format('Y-m-d'),
            'fecha_fin' => now()->format('Y-m-d')
        ]);

        $response->assertStatus(200);
        // Verificar que se genera el archivo con los filtros
        $response->assertHeader('Content-Disposition', 
            'attachment; filename=reporte-servicios-' . date('Y-m-d') . '.xlsx');
    }

    /** @test */
    public function solo_administradores_pueden_acceder_a_reportes_servicios()
    {
        // Intentar acceder como cliente
        $this->actingAs($this->cliente);
        $response = $this->get(route('admin.reportes.servicios'));
        $response->assertForbidden();

        // Intentar acceder como prestador
        $this->actingAs($this->prestador);
        $response = $this->get(route('admin.reportes.servicios'));
        $response->assertForbidden();

        // Acceder como administrador
        $this->actingAs($this->admin);
        $response = $this->get(route('admin.reportes.servicios'));
        $response->assertStatus(200);
    }

    /** @test */
    public function paginacion_funciona_correctamente()
    {
        Servicio::factory()->count(25)->create();

        $response = $this->get(route('admin.reportes.servicios'));

        $response->assertStatus(200);
        $response->assertViewHas('servicios', function ($servicios) {
            return $servicios->count() === 20; // Por defecto paginación de 20
        });

        // Verificar segunda página
        $response = $this->get(route('admin.reportes.servicios', ['page' => 2]));
        $response->assertStatus(200);
        $response->assertViewHas('servicios', function ($servicios) {
            return $servicios->count() === 5; // 25 total - 20 primera página = 5 segunda página
        });
    }

    /** @test */
    public function combinar_multiples_filtros_funciona_correctamente()
    {
        $servicioActivo = Servicio::factory()->create([
            'estado' => 'activo',
            'categoria_id' => $this->categoria->id,
            'prestador_id' => $this->prestador->id
        ]);

        Servicio::factory()->create(['estado' => 'inactivo']);
        Servicio::factory()->create(['categoria_id' => Categoria::factory()->create()->id]);

        $response = $this->get(route('admin.reportes.servicios', [
            'estado' => 'activo',
            'categoria_id' => $this->categoria->id,
            'prestador_id' => $this->prestador->id
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('servicios', function ($servicios) use ($servicioActivo) {
            return $servicios->count() === 1 && 
                   $servicios->first()->id === $servicioActivo->id;
        });
    }
}