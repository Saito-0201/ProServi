<?php
// database/factories/CategoriaFactory.php
namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaFactory extends Factory
{
    protected $model = Categoria::class;

    public function definition()
    {
        return [
            'nombre_cat' => $this->faker->word,
            'descripcion_cat' => $this->faker->sentence,
            'estado' => 'activo',
        ];
    }
}