<?php

namespace Database\Factories;

use App\Models\ClienteInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteInfoFactory extends Factory
{
    protected $model = ClienteInfo::class;

    public function definition()
    {
        return [
            'usuario_id' => \App\Models\User::factory(),
            'telefono' => $this->faker->phoneNumber,
            'genero' => $this->faker->randomElement(['masculino', 'femenino', 'otro']),
        ];
    }
}
