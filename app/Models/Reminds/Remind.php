<?php

namespace App\Models\Reminds;

use App\Helpers\TextHelper;
use App\Models\Traits\HasDates;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

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

    protected $casts = [
        'datetime' => 'datetime',
    ];

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(RemindGroup::class);
    }

    public function getTimeLeftAttribute(): array
    {
        return TextHelper::getHumanTimeLeft($this->datetime);
    }
}
