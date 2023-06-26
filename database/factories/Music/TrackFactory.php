<?php

namespace Database\Factories\Music;

use App\Models\Music\Album;
use App\Models\Music\Track;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrackFactory extends Factory
{
    protected $model = Track::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $sourceUrl = 'https://unreal-stuff.ru/userfiles/external/music/';
        $tracks = [
            'track1.mp3',
            'track2.mp3',
            'track3.mp3',
            'track4.mp3',
            'track5.mp3',
            'track6.mp3',
            'track7.mp3',
            'track8.mp3',
            'track9.mp3',
            'track10.mp3',
            'track11.mp3',
            'track12.mp3',
        ];
        $images = [
            'image1.jpg',
            'image2.jpg',
            'image3.jpg',
            'image4.jpg',
            'image5.jpg',
        ];

        return [
            'album_id' => Album::all()->random()->id,
            'number' => rand(1, 12),
            'name' => $this->faker->words(rand(1, 5), true),
            'path' => $sourceUrl . $tracks[rand(0, count($tracks) - 1)],
            'image' => $sourceUrl . $images[rand(0, count($images) - 1)],
            'duration' => gmdate('H:i:s', $this->faker->numberBetween(45, 355)),
            'bitrate' => '320',
            'link' => $sourceUrl . $tracks[rand(0, count($tracks) - 1)],
        ];
    }
}
