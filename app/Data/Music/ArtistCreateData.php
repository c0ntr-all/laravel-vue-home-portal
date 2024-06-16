<?php

namespace App\Data\Music;

use Spatie\LaravelData\Data;

class ArtistCreateData extends Data
{
    public string $name;
    public int $user_id;
    public ?string $description = null;
    public ?string $country = null;
    public ?string $image = null;
    public string $path;

    public function __construct(
    ) {
    }
}
