<?php

namespace Database\Factories;

use App\Models\ListingImage;
use App\Models\Listing;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListingImageFactory extends Factory
{
    protected $model = ListingImage::class;

    public function definition(): array
    {
        return [
            'listing_id' => Listing::factory(),
            'path' => 'images/listings/' . $this->faker->uuid . '.jpg',
            'is_primary' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function primary(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_primary' => true,
        ]);
    }
}