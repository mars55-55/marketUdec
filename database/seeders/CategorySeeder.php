<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Libros de Texto',
                'slug' => 'libros-texto',
                'description' => 'Libros académicos, manuales universitarios y material de estudio',
                'icon' => '📚',
            ],
            [
                'name' => 'Apuntes y Resúmenes',
                'slug' => 'apuntes-resumenes',
                'description' => 'Apuntes de clases, resúmenes, guías de estudio',
                'icon' => '📝',
            ],
            [
                'name' => 'Electrónicos',
                'slug' => 'electronicos',
                'description' => 'Computadoras, tablets, calculadoras, smartphones',
                'icon' => '💻',
            ],
            [
                'name' => 'Servicios Académicos',
                'slug' => 'servicios-academicos',
                'description' => 'Tutorías, traducciones, elaboración de trabajos',
                'icon' => '🎓',
            ],
            [
                'name' => 'Útiles Escolares',
                'slug' => 'utiles-escolares',
                'description' => 'Cuadernos, lápices, carpetas, material de oficina',
                'icon' => '✏️',
            ],
            [
                'name' => 'Ropa y Accesorios',
                'slug' => 'ropa-accesorios',
                'description' => 'Ropa, zapatos, accesorios personales',
                'icon' => '👕',
            ],
            [
                'name' => 'Deportes',
                'slug' => 'deportes',
                'description' => 'Equipo deportivo, ropa deportiva, implementos',
                'icon' => '⚽',
            ],
            [
                'name' => 'Instrumentos Musicales',
                'slug' => 'instrumentos-musicales',
                'description' => 'Guitarras, teclados, instrumentos de viento',
                'icon' => '🎵',
            ],
            [
                'name' => 'Transporte',
                'slug' => 'transporte',
                'description' => 'Bicicletas, motos, accesorios para vehículos',
                'icon' => '🚲',
            ],
            [
                'name' => 'Otros',
                'slug' => 'otros',
                'description' => 'Artículos diversos que no encajan en otras categorías',
                'icon' => '📦',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
