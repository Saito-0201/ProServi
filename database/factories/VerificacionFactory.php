<?php
// database/factories/VerificacionFactory.php
namespace Database\Factories;

use App\Models\Verificacion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VerificacionFactory extends Factory
{
    protected $model = Verificacion::class;

    public function definition()
    {
        return [
            'usuario_id' => User::factory(),
            'numero_carnet' => $this->faker->numerify('CI########'),
            'fecha_emision' => $this->faker->date(),
            'ruta_imagen_carnet' => 'verificaciones/carnets/' . $this->faker->uuid() . '.jpg',
            'ruta_reverso_carnet' => 'verificaciones/carnets/' . $this->faker->uuid() . '.jpg',
            'ruta_foto_cara' => 'verificaciones/fotos/' . $this->faker->uuid() . '.jpg',
            'estado' => 'pendiente',
            'fecha_verificacion' => null,
            'motivo_rechazo' => null,
        ];
    }

    public function pendiente()
    {
        return $this->state([
            'estado' => 'pendiente',
            'fecha_verificacion' => null,
            'motivo_rechazo' => null,
        ]);
    }

    public function aprobado()
    {
        return $this->state([
            'estado' => 'aprobado',
            'fecha_verificacion' => now(),
            'motivo_rechazo' => null,
        ]);
    }

    public function rechazado()
    {
        return $this->state([
            'estado' => 'rechazado',
            'fecha_verificacion' => now(),
            'motivo_rechazo' => $this->faker->sentence(),
        ]);
    }
}