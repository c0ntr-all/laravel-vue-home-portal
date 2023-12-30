<?php

namespace App\Models\Finances;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Traits\HasUser;

/**
 * App\Models\Finances\Finances
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property float $summ
 * @property string $recipient
 * @property string|null $comment
 * @property string $done_at
 * @property string $created_at
 * @property-read \App\Models\Finances\FinancesShop|null $shop
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\Finances\FinancesFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Finances newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Finances newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Finances query()
 * @method static \Illuminate\Database\Eloquent\Builder|Finances whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Finances whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Finances whereDoneAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Finances whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Finances whereRecipient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Finances whereSumm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Finances whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Finances whereUser(string $userId)
 * @method static \Illuminate\Database\Eloquent\Builder|Finances whereUserId($value)
 * @mixin \Eloquent
 */
class Finances extends Model
{
    use HasFactory,
        HasUser;

    public $timestamps = false;
    protected $fillable = [
        'id',
        'type',
        'user_id',
        'recipient',
        'summ',
        'comment',
        'done_at',
        'created_at'
    ];
    public function shop()
    {
        return $this->hasOne(FinancesShop::class, 'id', 'recipient');
    }

    public function getItems(): Collection
    {
        // Пока все берем
        return Finances::all();
    }
}
