<?php

namespace App\Models\Finances;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Finances\FinancesShop
 *
 * @property int $id
 * @property int $user_id
 * @property string $image
 * @property string $value
 * @property string $update_at
 * @method static \Database\Factories\Finances\FinancesShopFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|FinancesShop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FinancesShop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FinancesShop query()
 * @method static \Illuminate\Database\Eloquent\Builder|FinancesShop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancesShop whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancesShop whereUpdateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancesShop whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancesShop whereValue($value)
 * @mixin \Eloquent
 */
class FinancesShop extends Model
{
    public $timestamps = false;

    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'image',
        'value',
        'update_at',
    ];

    public static function randColor()
    {
        return sprintf('#%02X%02X%02X', rand(0, 255), rand(0, 255), rand(0, 255));
    }

    public function isImage($item)
    {
        return (strlen($item) == 7 && strpos($item, '#') === 0) ? false : true;
    }
}
