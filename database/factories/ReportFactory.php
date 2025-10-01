<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\User;
use App\Models\Listing;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'reporter_id' => User::factory(),
            'listing_id' => Listing::factory(),
            'reported_user_id' => User::factory(),
            'reason' => $this->faker->randomElement(['inappropriate', 'spam', 'fraud', 'fake', 'other']),
            'description' => $this->faker->paragraph(),
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function reviewed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'reviewed',
            'reviewed_at' => now(),
        ]);
    }

    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'resolved',
            'reviewed_at' => now(),
            'resolved_at' => now(),
        ]);
    }

    public function forListing(): static
    {
        return $this->state(fn (array $attributes) => [
            'reported_user_id' => null,
        ]);
    }

    public function forUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'listing_id' => null,
        ]);
    }
}