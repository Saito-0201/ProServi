<?php
// database/factories/FavoritoFactory.php
namespace Database\Factories;

use App\Models\Favorito;
use App\Models\User;
use App\Models\Servicio;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavoritoFactory extends Factory
{
    protected $model = Favorito::class;

    public function definition()
    {
        return [
            'cliente_id' => User::factory(),
            'servicio_id' => Servicio::factory(),
            'fecha' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}