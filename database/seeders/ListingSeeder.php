<?php

namespace Database\Seeders;

use App\Models\Listing;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();

        $sampleListings = [
            [
                'title' => 'Libro Cálculo de Stewart - 8va Edición',
                'description' => 'Libro de Cálculo de Stewart en excelente estado. Incluye solucionario. Ideal para estudiantes de ingeniería. Poco uso, sin rayones ni anotaciones importantes.',
                'price' => 35000,
                'condition' => 'como_nuevo',
                'category' => 'Libros de Texto',
                'location' => 'Campus Concepción',
                'is_negotiable' => true,
            ],
            [
                'title' => 'MacBook Air M1 2020 - 256GB',
                'description' => 'MacBook Air en perfecto estado, comprado hace 1 año. Incluye cargador original y funda protectora. Ideal para estudiantes de diseño o programación.',
                'price' => 850000,
                'condition' => 'bueno',
                'category' => 'Electrónicos',
                'location' => 'Los Ángeles',
                'is_negotiable' => true,
                'allows_exchange' => true,
            ],
            [
                'title' => 'Apuntes Completos de Química Orgánica',
                'description' => 'Apuntes detallados de todo el ramo de Química Orgánica. Incluye ejercicios resueltos, esquemas de reacciones y preparación para pruebas.',
                'price' => 15000,
                'condition' => 'bueno',
                'category' => 'Apuntes y Resúmenes',
                'location' => 'Campus Central',
                'is_negotiable' => false,
            ],
            [
                'title' => 'Calculadora Científica Casio fx-991',
                'description' => 'Calculadora científica programable en excelente estado. Perfecta para ingeniería y ciencias. Incluye manual de usuario.',
                'price' => 25000,
                'condition' => 'nuevo',
                'category' => 'Útiles Escolares',
                'is_negotiable' => true,
            ],
            [
                'title' => 'Clases Particulares de Matemáticas',
                'description' => 'Ofrezco clases particulares de matemáticas para estudiantes universitarios. Especialidad en Cálculo, Álgebra Lineal y Estadística. Experiencia de 3 años.',
                'price' => 12000,
                'condition' => 'nuevo',
                'category' => 'Servicios Académicos',
                'is_negotiable' => true,
            ],
            [
                'title' => 'Bicicleta de Montaña Trek - Aro 26',
                'description' => 'Bicicleta Trek en buen estado, ideal para moverse por el campus. Tiene algunos detalles estéticos pero funciona perfectamente.',
                'price' => 180000,
                'condition' => 'aceptable',
                'category' => 'Transporte',
                'location' => 'Campus Concepción',
                'allows_exchange' => true,
                'is_negotiable' => true,
            ],
            [
                'title' => 'Guitarra Acústica Yamaha F310',
                'description' => 'Guitarra acústica en excelente estado, poco uso. Ideal para principiantes. Incluye funda y púas.',
                'price' => 95000,
                'condition' => 'como_nuevo',
                'category' => 'Instrumentos Musicales',
                'is_negotiable' => true,
            ],
            [
                'title' => 'Polera UdeC Oficial - Talla M',
                'description' => 'Polera oficial de la Universidad de Concepción, nueva con etiquetas. Color azul marino.',
                'price' => 18000,
                'condition' => 'nuevo',
                'category' => 'Ropa y Accesorios',
                'is_negotiable' => false,
            ],
        ];

        foreach ($sampleListings as $listingData) {
            $category = $categories->where('name', $listingData['category'])->first();
            if (!$category) continue;

            Listing::create([
                'user_id' => $users->random()->id,
                'category_id' => $category->id,
                'title' => $listingData['title'],
                'description' => $listingData['description'],
                'price' => $listingData['price'],
                'condition' => $listingData['condition'],
                'location' => $listingData['location'] ?? null,
                'is_negotiable' => $listingData['is_negotiable'] ?? false,
                'allows_exchange' => $listingData['allows_exchange'] ?? false,
                'status' => 'active',
                'views' => rand(0, 50),
                'expires_at' => now()->addDays(30),
            ]);
        }
    }
}
