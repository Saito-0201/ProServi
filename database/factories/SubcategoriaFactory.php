<?php
// database/factories/SubcategoriaFactory.php
namespace Database\Factories;

use App\Models\Subcategoria;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubcategoriaFactory extends Factory
{
    protected $model = Subcategoria::class;

    public function definition()
    {
        return [
            'categoria_id' => Categoria::factory(),
            'nombre' => $this->faker->word,
            'descripcion' => $this->faker->sentence,
        ];
    }
}