<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriasSeeder extends Seeder
{
    public function run()
    {
        $categorias = [
            ['nombre_cat' => 'Hogar, Construcción y Mantenimiento', 'descripcion_cat' => null],
            ['nombre_cat' => 'Salud y Bienestar', 'descripcion_cat' => null],
            ['nombre_cat' => 'Tecnología y Electrónica', 'descripcion_cat' => null],
            ['nombre_cat' => 'Belleza y Cuidado Personal', 'descripcion_cat' => null],
            ['nombre_cat' => 'Automotriz y Transporte', 'descripcion_cat' => null],
            ['nombre_cat' => 'Educación y Clases', 'descripcion_cat' => null],
            ['nombre_cat' => 'Eventos y Celebraciones', 'descripcion_cat' => null],
            ['nombre_cat' => 'Legal, Financiero y Administrativo', 'descripcion_cat' => null],
            ['nombre_cat' => 'Mascotas y Animales', 'descripcion_cat' => null],
            ['nombre_cat' => 'Gastronomía y Alimentos', 'descripcion_cat' => null],
            ['nombre_cat' => 'Arte, Diseño y Artesanías', 'descripcion_cat' => null],
            ['nombre_cat' => 'Deportes y Recreación', 'descripcion_cat' => null],
            ['nombre_cat' => 'Otros Servicios', 'descripcion_cat' => null],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
