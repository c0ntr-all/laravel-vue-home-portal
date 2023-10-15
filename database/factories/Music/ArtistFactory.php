<?php

namespace Database\Factories\Music;

use App\Models\Music\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArtistFactory extends Factory
{
    protected $model = Artist::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $countries = ['USA', 'Russia', 'UK', 'Indonesia', 'Canada', 'South Africa'];
        $sourceUrl = 'https://unreal-stuff.ru/userfiles/external/music/';
        $images = [
            'image1.jpg',
            'image2.jpg',
            'image3.jpg',
            'image4.jpg',
            'image5.jpg',
        ];

        return [
            'name' => $this->faker->word,
            'country' => $countries[rand(0, count($countries) - 1)],
            'content' => $this->faker->text,
            'image' => $sourceUrl . $images[rand(0, count($images) - 1)],
            'path' => $this->faker->address,
        ];
    }
}
