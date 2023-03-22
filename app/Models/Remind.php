<?php

namespace App\Models;

use App\Models\Traits\HasDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Lang;

class Remind extends Model
{
    use HasFactory;
    use HasDates;

    protected $fillable = [
        'title',
        'content',
        'datetime',
        'is_active',
        'created_at',
        'updated_at'
    ];

    public function getItems(): Collection
    {
        return Remind::orderByDesc('is_active')->orderByDesc('datetime')->get();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTimeLeftAttribute()
    {
        $startdate = Carbon::now();
        $enddate = Carbon::parse($this->datetime);

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
