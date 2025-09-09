<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Anime>
 */
class AnimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "anime_id" => fake()->unique()->numberBetween(1, 1000),
            "title" => fake()->sentence(3),
            "english_title" => fake()->sentence(3),
            "other_title" => fake()->sentence(3),
            "synopsis" => fake()->paragraph(),
            "type" => fake()->randomElement(\App\Enums\AnimeType::cases()),
            "episodes" => fake()->numberBetween(1, 100),
            "aired_from" => fake()->date(),
            "aired_to" => fake()->date(),
            "premiered_season" => fake()->randomElement(\App\Enums\Season::cases()),
            "premiered_year" => fake()->year(),
            "source" => fake()->word(),
            "duration" => fake()->time(),
            "score" => fake()->randomFloat(2, 1, 10),
            "status" => fake()->randomElement(\App\Enums\AnimeStatus::cases()),
            "rating" => fake()->randomElement(\App\Enums\AnimeRating::cases()),
            "trailer_url" => fake()->url(),
            "image_url" => "https://picsum.photos/300/400"

        ];
    }
}
