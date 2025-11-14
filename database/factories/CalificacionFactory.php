<?php
// database/factories/CalificacionFactory.php
namespace Database\Factories;

use App\Models\Calificacion;
use App\Models\User;
use App\Models\Servicio;
use Illuminate\Database\Eloquent\Factories\Factory;

class CalificacionFactory extends Factory
{
    protected $model = Calificacion::class;

    public function definition()
    {
        return [
            'cliente_id' => User::factory(),
            'prestador_id' => User::factory(),
            'servicio_id' => Servicio::factory(),
            'puntuacion' => $this->faker->numberBetween(1, 5),
            'comentario' => $this->faker->optional()->paragraph,
            'fecha' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}