<?php

namespace App\Data\Music;

use Spatie\LaravelData\Data;

class TrackCreateData extends Data
{
    public int $user_id;
    public string $cd;
    public int $number;
    public string $name;
    public string $path;
    public ?string $image = null;
    public ?string $duration = null;
    public ?int $bitrate = null;
    public ?string $link = null;
    public ?string $lyrics = null;

    public function __construct(
    ) {
    }
}
