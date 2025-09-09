<?php

namespace Database\Seeders;

use App\Models\Anime;
use Illuminate\Database\Seeder;

class AnimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Anime::factory()->create([
            'anime_id' => 52991,
            'title' => "Sousou no Frieren",
        ]);
        Anime::factory(100)->create()->each(function ($anime) {
            $genres = \App\Models\Master\Genre::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $studios = \App\Models\Master\Studio::inRandomOrder()->take(rand(1, 2))->pluck('id');
            $producers = \App\Models\Master\Producer::inRandomOrder()->take(rand(1, 2))->pluck('id');
            $licensors = \App\Models\Master\Licensor::inRandomOrder()->take(rand(0, 2))->pluck('id');

            $anime->genres()->attach($genres);
            $anime->studios()->attach($studios);
            $anime->producers()->attach($producers);
            $anime->licensors()->attach($licensors);
        });
    }
}
