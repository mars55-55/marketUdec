<?php

namespace Database\Factories;

use App\Models\Listing;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListingFactory extends Factory
{
    protected $model = Listing::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(3),
            'price' => $this->faker->numberBetween(5000, 100000),
            'condition' => $this->faker->randomElement(['nuevo', 'como_nuevo', 'bueno', 'aceptable', 'para_repuestos']),
            'location' => $this->faker->randomElement(['Campus Concepción', 'Campus Los Ángeles', 'Campus Central']),
            'status' => 'active',
            'is_negotiable' => $this->faker->boolean(),
            'allows_exchange' => $this->faker->boolean(),
            'views' => $this->faker->numberBetween(0, 100),
            'expires_at' => now()->addDays(30),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function sold(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sold',
        ]);
    }

    public function paused(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paused',
        ]);
    }

    public function blocked(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'blocked',
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subDays(5),
        ]);
    }

    public function negotiable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_negotiable' => true,
        ]);
    }

    public function allowsExchange(): static
    {
        return $this->state(fn (array $attributes) => [
            'allows_exchange' => true,
        ]);
    }
}