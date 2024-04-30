<?php declare(strict_types=1);

namespace App\Enums\Reminds;

use App\Enums\Traits\Arrayable;

enum RemindIntervalEnum: string
{
    use Arrayable;

    case HOUR = '1 hour';
    case DAY = '1 day';
    case WEEK = '1 week';
    case MONTH = '1 month';
    case YEAR = '1 year';

    public static function getLabel($value): string
    {
        return match ($value) {
            '1 hour' => __('reminds.intervals.hour'),
            '1 day' => __('reminds.intervals.day'),
            '1 week' => __('reminds.intervals.week'),
            '1 month' => __('reminds.intervals.month'),
            '1 year' => __('reminds.intervals.year'),
        };
    }
}
