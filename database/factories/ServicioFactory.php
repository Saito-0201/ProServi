<?php

namespace Database\Factories;

use App\Models\Servicio;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServicioFactory extends Factory
{
    protected $model = Servicio::class;

    public function definition()
    {
        return [
            'prestador_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'categoria_id' => Categoria::inRandomOrder()->first()->id ?? Categoria::factory(),
            'subcategoria_id' => Subcategoria::inRandomOrder()->first()->id ?? Subcategoria::factory(),
            'titulo' => $this->faker->sentence(3),
            'descripcion' => $this->faker->paragraph(),
            'tipo_precio' => $this->faker->randomElement(['fijo','cotizacion','variable','diario','por_servicio']),
            'precio' => $this->faker->randomFloat(2, 10, 1000),
            'imagen' => $this->faker->imageUrl(),
            'visitas' => $this->faker->numberBetween(0, 500),
            'fecha_publicacion' => $this->faker->dateTime(),
            'latitud' => $this->faker->latitude(-18, -16),
            'longitud' => $this->faker->longitude(-67, -65),
            'direccion' => $this->faker->address(),
            'ciudad' => $this->faker->city(),
            'provincia' => $this->faker->state(),
            'pais' => 'Bolivia',
            'estado' => $this->faker->randomElement(['activo','inactivo']),
        ];
    }
}
