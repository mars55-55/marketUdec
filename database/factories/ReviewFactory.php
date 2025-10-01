<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use App\Models\Listing;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'reviewer_id' => User::factory(),
            'listing_id' => Listing::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->optional(0.8)->paragraph(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function rating(int $rating): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $rating,
        ]);
    }

    public function withComment(string $comment = null): static
    {
        return $this->state(fn (array $attributes) => [
            'comment' => $comment ?? $this->faker->paragraph(),
        ]);
    }

    public function withoutComment(): static
    {
        return $this->state(fn (array $attributes) => [
            'comment' => null,
        ]);
    }
}