<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = $this->faker->randomElement([
            'Libros de Texto',
            'Electrónicos', 
            'Ropa y Accesorios',
            'Deportes',
            'Instrumentos Musicales',
            'Útiles Escolares',
            'Servicios Académicos',
            'Transporte',
            'Apuntes y Resúmenes'
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(10),
            'icon' => $this->faker->randomElement(['📚', '💻', '👕', '⚽', '🎵', '✏️', '🎓', '🚲', '📝']),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}