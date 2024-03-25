<?php declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Lang;

class TextHelper
{
    /**
     * Возвращает массив с текстом разницы по времени и флагом, что оно прошло
     *
     * @param $date
     * @return array
     */
    public static function getHumanTimeLeft($date): array
    {

        $startdate = Carbon::now();
        $enddate = Carbon::parse($date);

        $diff = $enddate->diffAsCarbonInterval($startdate)->toArray();

        $past = false;

        if ($enddate->isBefore($startdate)) {
            $past = true;
        }

        $out = '';

        if (!$past) {
            $out = $diff['years'] . Lang::choice('года|лет', $diff['years'], [], 'ru') . ', ' .
                $diff['months'] . Lang::choice('месяца|месяцев', $diff['months'], [], 'ru') . ', ' .
                $diff['weeks'] . Lang::choice('недели|недель', $diff['weeks'], [], 'ru') . ', ' .
                $diff['days'] . Lang::choice('дня|дней', $diff['days'], [], 'ru') . ', ' .
                $diff['hours'] . Lang::choice('час|часа', $diff['hours'], [], 'ru') . ', ' .
                $diff['minutes'] . Lang::choice('минут|минуты', $diff['minutes'], [], 'ru') . ', ' .
                $diff['seconds'] . Lang::choice('секунда|секунды|секунд', $diff['seconds'], [], 'ru') . ', ';
        } else {
            if ($diff['hours']) {
                $out = $diff['hours'] . ' ' . Lang::choice('час|часа', $diff['hours'], [], 'ru') . ' ';
                $out .= $diff['minutes'] . ' ' . Lang::choice('минут|минуты', $diff['minutes'], [], 'ru') . ' ';
                $out .= $diff['seconds'] . ' ' . Lang::choice('секунда|секунды|секунд', $diff['seconds'], [], 'ru') . ($past ? ' назад' : '');
            }

            if ($diff['days']) {
                $out = 'Более ' . $diff['days'] . ' ' . Lang::choice('дня|дней', $diff['days'], [], 'ru') . ' назад';
            }

            if ($diff['weeks']) {
                $out = 'Более ' . $diff['weeks'] . ' ' . Lang::choice('недели|недель', $diff['weeks'], [], 'ru') . ' назад';
            }

            if ($diff['months']) {
                $out = 'Более ' . $diff['months'] . ' ' . Lang::choice('месяца|месяцев', $diff['months'], [], 'ru') . ' назад';
            }

            if ($diff['years']) {
                $out = 'Более ' . $diff['years'] . ' ' . Lang::choice('года|лет', $diff['years'], [], 'ru') . ' назад';
            }
        }

        return [
            'past' => $past,
            'message' => $out
        ];
    }
}
