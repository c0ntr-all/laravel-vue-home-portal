<?php

namespace App\Models\Reminds;

use App\Models\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Reminds\RemindGroup
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $content
 * @property string|null $color
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reminds\Remind> $reminds
 * @property-read int|null $reminds_count
 * @method static \Database\Factories\Reminds\RemindGroupFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|RemindGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RemindGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RemindGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|RemindGroup whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RemindGroup whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RemindGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RemindGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RemindGroup whereUserId($value)
 * @mixin \Eloquent
 */
class RemindGroup extends Model
{
    use HasFactory,
        HasUser;

    public $table = 'reminds_groups';

    public $fillable = [
        'user_id',
        'name',
        'content',
        'color',
    ];

    public function reminds(): HasMany
    {
        return $this->hasMany(Remind::class, 'group_id', 'id');
    }
}
