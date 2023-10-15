<?php

namespace Database\Factories\Music;

use App\Models\Music\Album;
use App\Models\Music\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlbumFactory extends Factory
{
    protected $model = Album::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $artists = Artist::all();
        $sourceUrl = 'https://unreal-stuff.ru/userfiles/external/music/';
        $images = [
            'image1.jpg',
            'image2.jpg',
            'image3.jpg',
            'image4.jpg',
            'image5.jpg',
        ];

        return [
            'artist_id' => $artists->random()->id,
            'year' => $this->faker->year,
            'name' => $this->faker->word,
            'content' => $this->faker->text,
            'image' => $sourceUrl . $images[rand(0, count($images) - 1)],
            'path' => $this->faker->address
        ];
    }
}
