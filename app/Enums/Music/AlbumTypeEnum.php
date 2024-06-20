<?php declare(strict_types=1);

namespace App\Enums\Music;

use App\Enums\Traits\Arrayable;

enum AlbumTypeEnum: string
{
    use Arrayable;

    case STUDIO = 'studio';
    case EP = 'ep';
    case SINGLE = 'single';
    case MAXI_SINGLE = 'maxi-single';
    case SPLIT = 'split';
    case DEMO = 'demo';
    case LIVE = 'live';
    case PROMO = 'promo';
}
