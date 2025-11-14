<?php
// database/factories/PrestadorInfoFactory.php
namespace Database\Factories;

use App\Models\PrestadorInfo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrestadorInfoFactory extends Factory
{
    protected $model = PrestadorInfo::class;

    public function definition()
    {
        return [
            'usuario_id' => User::factory(),
            'telefono' => $this->faker->phoneNumber,
            'foto_perfil' => null,
            'genero' => $this->faker->randomElement(['masculino', 'femenino', 'otro']),
            'descripcion' => $this->faker->paragraph,
            'experiencia' => $this->faker->text,
            'especialidades' => $this->faker->words(3, true),
            'verificado' => false,
            'disponibilidad' => 'Lunes a Viernes 8:00-18:00',
        ];
    }

    public function verificado()
    {
        return $this->state([
            'verificado' => true,
        ]);
    }
}