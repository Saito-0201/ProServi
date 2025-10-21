<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subcategoria;
use App\Models\Categoria;

class SubcategoriasSeeder extends Seeder
{
    public function run()
    {
        $subcategorias = [
            'Hogar, Construcción y Mantenimiento' => [
                'Albañilería y Construcción',
                'Fontanería (Plomería)',
                'Electricidad e Instalaciones',
                'Carpintería (Madera, Melamina, Aluminio)',
                'Pintura y Texturizado',
                'Cerrajería',
                'Jardinería y Paisajismo',
                'Limpieza del Hogar',
                'Control de Plagas (Fumigación)',
                'Mudanzas, Fletes y Acarreos',
                'Instalación y Reparación de Muebles',
                'Techos y Techistas',
                'Herrería y Soldadura',
            ],
            'Salud y Bienestar' => [
                'Médicos Generales (Consulta)',
                'Especialistas (Pediatría, Ginecología, etc.)',
                'Odontólogos y Dentistas',
                'Fisioterapia y Rehabilitación',
                'Psicología y Terapia',
                'Nutrición y Dietética',
                'Enfermería a Domicilio',
                'Masajes Terapéuticos y Relajantes',
                'Medicina Alternativa (Naturista, Acupuntura)',
                'Ópticas y Optometría',
                'Laboratorios de Análisis Clínicos',
                'Farmacias y Boticas',
            ],
            'Tecnología y Electrónica' => [
                'Reparación de Celulares y Tablets',
                'Reparación de Computadoras y Laptops',
                'Soporte Técnico (Presencial y Remoto)',
                'Instalación de Redes WiFi y Cableado',
                'Instalación de Cámaras de Seguridad (CCTV)',
                'Instalación de Antenas TV y Satelitales',
                'Programación y Desarrollo de Software',
                'Diseño Web y Multimedia',
                'Videojuegos y Consolas',
                'Venta y Instalación de Equipos Electrónicos',
            ],
            'Belleza y Cuidado Personal' => [
                'Peluquería y Estilistas',
                'Barbería',
                'Maquillaje Profesional',
                'Manicura y Pedicura (Uñas)',
                'Depilación',
                'Estética Facial y Corporal',
                'Spa y Relax',
                'Cosmetología',
                'Micropigmentación y Pestañas',
                'Tatuajes y Piercings',
            ],
            'Automotriz y Transporte' => [
                'Mecánica Automotriz General',
                'Lavado y Detallado de Autos',
                'Electricidad Automotriz',
                'Llantas y Alineación',
                'Servicio de Taxi y Radio-Taxi',
                'Transporte Ejecutivo (Choferes)',
                'Transporte de Carga (Fletes)',
                'Lubricentros y Cambio de Aceite',
                'Enderezado y Pintura',
                'Venta de Repuestos y Accesorios',
            ],
            'Educación y Clases' => [
                'Clases de Regularización (Colegio)',
                'Tutorías Universitarias',
                'Clases de Idiomas',
                'Clases de Música (Charango, Guitarra, Piano, etc.)',
                'Clases de Danza y Baile (Folklórica, Salsa, etc.)',
                'Clases de Arte y Pintura',
                'Clases de Computación e Informática',
                'Asesoría de Tesis y Trabajos',
                'Capacitación Empresarial',
                'Guarderías y Cuidado de Niños',
            ],
            'Eventos y Celebraciones' => [
                'Fotógrafos y Videógrafos',
                'DJ y Sonido para Eventos',
                'Animación y Show Infantil',
                'Catering y Servicio de Comida',
                'Decoración de Eventos',
                'Alquiler de Mobiliario (Mesas, Sillas, Carpas)',
                'Salones de Fiestas y Eventos',
                'Payasos y Magos',
                'Coctelería y Bartenders',
                'Organización de Eventos (Event Planner)',
            ],
            'Legal, Financiero y Administrativo' => [
                'Abogados (Familia, Laboral, Civil)',
                'Contadores y Auditores',
                'Asesores Financieros y de Créditos',
                'Trámites Legales y Notariales',
                'Traductores e Intérpretes',
                'Tramitadores (Visas, Pasaportes, Documentos)',
                'Arquitectos e Ingenieros (Consultoría)',
                'Diseñadores de Interiores',
                'Publicidad y Marketing Digital',
            ],
            'Mascotas y Animales' => [
                'Veterinarias y Clínicas',
                'Paseadores de Perros',
                'Peluquería Canina (Baño y Corte)',
                'Guarderías y Hospedaje para Mascotas',
                'Entrenamiento y Adiestramiento',
                'Tiendas de Mascotas y Alimentos',
                'Venta de Accesorios para Mascotas',
            ],
            'Gastronomía y Alimentos' => [
                'Restaurantes y Comida para Llevar',
                'Chefs a Domicilio',
                'Repostería y Pastelería',
                'Elaboración de Salteñas y Empanadas',
                'Cursos de Cocina',
                'Catering para Empresas',
                'Venta de Alimentos por Encargo',
            ],
            'Arte, Diseño y Artesanías' => [
                'Diseño Gráfico',
                'Artesanías y Manualidades',
                'Pintura y Dibujo Artístico',
                'Serigrafía y Estampados',
                'Costura y Confección de Ropa',
                'Reparación de Ropa y Zapatos',
                'Talabartería (Trabajos en Cuero)',
                'Tejido Artesanal (Aguayos, Chullos)',
            ],
            'Deportes y Recreación' => [
                'Gimnasios y Boxes de CrossFit',
                'Entrenadores Personales',
                'Ligas y Torneos Deportivos',
                'Alquiler de Canchas Deportivas',
                'Guías de Turismo y Trekking',
                'Venta de Artículos Deportivos',
            ],
            'Otros Servicios' => [
                'Mensajería y Cadetería',
                'Lavanderías y Planchado de Ropa',
                'Agencias de Viajes y Turismo',
                'Vendedores y Distribuidores Independientes',
                'Tiendas de Reparación General (Todo en uno)',
                'Servicios de Seguridad Privada',
            ],
        ];

        foreach ($subcategorias as $categoriaNombre => $subs) {
            $categoria = Categoria::where('nombre_cat', $categoriaNombre)->first();
            if ($categoria) {
                foreach ($subs as $sub) {
                    Subcategoria::create([
                        'categoria_id' => $categoria->id,
                        'nombre' => $sub,
                    ]);
                }
            }
        }
    }
}
