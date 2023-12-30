<?php

namespace App\Models\Reminds;

use App\Models\Traits\HasDates;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Lang;

/**
 * App\Models\Reminds\Remind
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $group_id
 * @property string $title
 * @property string|null $content
 * @property string|null $datetime
 * @property string|null $interval
 * @property int $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string|null $group_name
 * @property-read array $time_left
 * @property-read \App\Models\Reminds\RemindGroup|null $group
 * @property-read User $user
 * @method static \Database\Factories\Reminds\RemindFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Remind newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Remind newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Remind query()
 * @method static \Illuminate\Database\Eloquent\Builder|Remind whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Remind whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Remind whereDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Remind whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Remind whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Remind whereInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Remind whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Remind whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Remind whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Remind whereUserId($value)
 * @mixin \Eloquent
 */
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(RemindGroup::class);
    }

    public function getGroupNameAttribute(): string|null
    {
        return $this->group()->first()?->name;
    }

    public function getTimeLeftAttribute(): array
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

    public function getItems(): Collection
    {
        return Remind::orderByDesc('group_id')->orderByDesc('is_active')->orderByDesc('datetime')->get();
    }
}
