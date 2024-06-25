<?php declare(strict_types=1);

namespace App\Enums\Music;

use App\Enums\Traits\Arrayable;

enum AlbumVersionTypeEnum: string
{
    use Arrayable;

    case ORIGINAL = 'original';
    case REMASTERED = 'remastered';
    case JAPANESE = 'japanese';
    case REISSUE = 'reissue';
    case LIMITED = 'limited';
    case SPECIAL = 'special';
    case DELUXE = 'deluxe';
    case EXPANDED = 'expanded';
    case ANNIVERSARY = 'anniversary';
    case BOOTLEG = 'bootleg';
    case COMPILATION = 'compilation';
}
